<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ccavenue extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_model');
    }

    function pay($invoice_id = NULL)
    {
        $data['title'] = lang('make_payment');
        $invoice_info = $this->invoice_model->check_by(array('invoices_id' => $invoice_id), 'tbl_invoices');

        $invoice_due = $this->invoice_model->calculate_to('invoice_due', $invoice_id);
        if ($invoice_due <= 0) {
            $invoice_due = 0.00;
        }

        $data['invoice_info'] = array(
            'item_name' => $invoice_info->reference_no,
            'item_number' => $invoice_id,
            'currency' => $invoice_info->currency,
            'invoices_id' => $invoice_id,
            'client_id' => $invoice_info->client_id,
            'amount' => $invoice_due
        );

        $data['access_code'] = config_item('ccavenue_access_code');
        $data['merchant_id'] = config_item('ccavenue_merchant_id');
        $data['working_key'] = config_item('ccavenue_key');
        $data['txnid'] = $this->ccavenue_gateway->gen_transaction_id();
        $posted = null;
        if ($this->input->post()) {
            $data['access_code'] = config_item('ccavenue_access_code');
            $data['action_url'] = $this->ccavenue_gateway->get_invoice_action_url();
            $input = $this->input->post();
            if (!empty($input)) {
                $input['amount'] = number_format($input['amount'], 2, '.', '');
            }
            if ($input['currency'] != 'INR') {
                $input['currency'] = 'INR';
            }
            $this->load->view('payment/pay_ccavenue', $data);
        } else {
            $data['action_url'] = $this->uri->uri_string();
            $data['encrypted_data'] = '';
            $data['subview'] = $this->load->view('payment/ccavenue', $data, TRUE);
            $client_id = $this->session->userdata('client_id');
            if (!empty($client_id)) {
                $this->load->view('client/_layout_main', $data);
            } else {
                $this->load->view('frontend/_layout_main', $data);
            }
        }
    }

    public function confirm($type = null)
    {
        $data['title'] = lang('make_payment') . 'via' . lang('ccavenue');
        if (!empty($type)) {
            $view = 'subscriptions/pay_ccavenue';
        } else {
            $view = 'pay_ccavenue';
        }
        $data['subview'] = $this->load->view('payment/' . $view, $data, TRUE);
        if (!empty($type)) {
            $user_id = $this->session->userdata('user_id');
            if (!empty($user_id)) {
                $this->load->view('admin/_layout_main', $data); //page load
            } elseif (!empty($front_end)) {
                $this->load->view('admin/_layout_open', $data); //page load
            } else {
                $this->load->view('frontend/_layout_main', $data); //page load
            }
        } else {
            $client_id = $this->session->userdata('client_id');
            if (!empty($client_id)) {
                $this->load->view('client/_layout_main', $data);
            } else {
                $this->load->view('frontend/_layout_main', $data);
            }
        }
    }

    public function payPlan($pricing_id)
    {
        $sdata = get_active_subs();
        $package_info = plan_info($pricing_id);
        $frequency = $sdata->frequency;
        $package_info->amount = get_currencywise_price(true, $sdata->currency, $sdata->pricing_id)->$frequency;
        $sdata->reference_no = 'process_payment_by_' . $this->router->fetch_class();
        $sdata->billing_cycle = $sdata->frequency;
        $sdata->interval_type = $sdata->frequency;
        $sdata->subtotal = $package_info->amount;
        $sdata->total = $package_info->amount;
        $sdata->coupon_code = '';
        $sdata->coupon_code_input = '';
        $sdata->discount_amount = '';
        $sdata->discount_percent = '';
        $sdata->renew_date = renew_date($sdata->billing_cycle);
        $sdata->payment_method = $this->router->fetch_class();
        $sdata->title = 'process_payment_by_' . $this->router->fetch_class();
        $sdata->i_have_read_agree = 'on';
        $data['input_info'] = (array) $sdata;
        $this->session->set_userdata($sdata);

        redirect('payment/' . $this->router->fetch_class() . '/make_subs_payment');
    }

    public function make_subs_payment()
    {
        $data['title'] = lang('make_payment');
        $input_data = $this->session->userdata('input_info');
        if (!empty($input_data)) {
            $data['input_data'] = $input_data;
            $data['access_code'] = getConfigItems('ccavenue_access_code');
            $data['merchant_id'] = getConfigItems('ccavenue_merchant_id');
            $data['working_key'] = getConfigItems('ccavenue_key');
            $data['txnid'] = $this->ccavenue_gateway->gen_transaction_id();
            $posted = null;
            if ($this->input->post()) {
                $data['access_code'] = config_item('ccavenue_access_code');
                $input = $this->input->post();
                if (!empty($input)) {
                    $input['amount'] = number_format($input['amount'], 2, '.', '');
                }
                if ($input['currency'] != 'INR') {
                    $input['currency'] = 'INR';
                }
                $this->load->view('payment/pay_ccavenue', $data);
            } else {
                $data['action_url'] = $this->uri->uri_string();

                $data['encrypted_data'] = '';
                $data['subview'] = $this->load->view('payment/subscriptions/ccavenue', $data, true);
                $user_id = $this->session->userdata('user_id');
                if (!empty($user_id)) {
                    $this->load->view('frontend/_layout_main', $data); //page load
                } elseif (!empty($front_end)) {
                    $this->load->view('frontend/_layout_open', $data); //page load
                } else {
                    $this->load->view('frontend/_layout_main', $data); //page load
                }
            }
        }
    }

    public function invoice_success()
    {
        $order_info = $this->ccavenue_gateway->get_invoice_order_status($_POST);
        if ($order_info['order_status'] == 'success') {
            $result = $this->ccavenue_gateway->addPayment($order_info['order_id'], $order_info['amount']);
            if ($result['type'] == 'success') {
                set_message($result['type'], $result['message']);
            } else {
                set_message($result['type'], $result['message']);
            }
        } else {
            set_message('error', 'Thank You. Your transaction status is ' . $order_info['order_status']);
        }
        $client_id = $this->session->userdata('client_id');
        if (!empty($client_id)) {
            redirect('client/dashboard');
        } else {
            redirect('frontend/view_invoice/' . url_encode($order_info['order_id']));
        }
    }

    public function invoice_failure()
    {
        $order_info = $this->ccavenue_gateway->get_invoice_order_status($_POST);
        if (!$order_info) {
            set_message('error', lang('invalid_transaction'));
        } else {
            set_message('error', 'Thank You. Your transaction status is ' . $order_info['order_status']);
        }
        $client_id = $this->session->userdata('client_id');
        if (!empty($client_id)) {
            redirect('client/dashboard');
        } else {
            redirect('frontend/view_invoice/' . url_encode($_POST['order_id']));
        }
    }

    public function subs_success()
    {
        $order_info = $this->ccavenue_gateway->get_order_status($_POST);
        if ($order_info == 'success') {
            $input_data = $this->session->userdata('input_info');
            $input_data['transaction_id'] = $_POST['orderNo'];
            $result = $this->stripe_gateway->addSubsPayment($input_data);
            if ($result['type'] == 'success') {
                set_message($result['type'], $result['message']);
            } else {
                set_message($result['type'], $result['message']);
            }
        } else {
            set_message('error', 'Thank You. Your transaction status is ' . $order_info);
        }
        $this->session->unset_userdata('input_info');
        redirect('admin/dashboard');
    }

    public function subs_failure()
    {
        $order_info = $this->ccavenue_gateway->get_order_status($_POST);
        if (!$order_info) {
            set_message('error', lang('invalid_transaction'));
        } else {
            set_message('error', 'Thank You. Your transaction status is ' . $order_info);
        }
        $this->session->unset_userdata('input_info');
        redirect('checkoutPayment');
    }
}

////end 