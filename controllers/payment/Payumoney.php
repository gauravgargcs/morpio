<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 *
 * @package
 */
class Payumoney extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_model');
    }


    public function pay($invoice_id = null)
    {
        $data['title'] = lang('make_payment');
        $invoice_info = $this->invoice_model->check_by(array('invoices_id' => $invoice_id), 'tbl_invoices');
        $client_info = $this->db->where('client_id', $invoice_info->client_id)->get('tbl_client')->row();
        $invoice_due = $this->invoice_model->calculate_to('invoice_due', $invoice_id);
        if ($invoice_due <= 0) {
            $invoice_due = 0.00;
        }
        $data['key'] = getConfigItems('payumoney_key');
        $posted = [];
        if ($this->input->post()) {
            $data['action_url'] = $this->payu_money_gateway->get_invoice_action_url();
            foreach ($this->input->post() as $key => $value) {
                $posted[$key] = $value;
            }
            $data['txnid'] = $posted['txnid'];
            $data['invoice_id'] = $posted['invoice_id'];
            $data['amount'] = $posted['amount'];
            $data['firstname'] = $posted['firstname'];
            $data['lastname'] = '';
            $data['email'] = $posted['email'];
            $data['phonenumber'] = $posted['phone'];
            $data['address'] = $posted['address'];
        } else {
            $data['txnid'] = $this->payu_money_gateway->gen_transaction_id();
            $data['action_url'] = $this->uri->uri_string();
            $data['amount'] = $invoice_due;
            $data['invoice_id'] = $invoice_id;
            $data['firstname'] = (!empty($client_info->name) ? $client_info->name : '');
            $data['lastname'] = '';
            $data['email'] = (!empty($client_info->email) ? $client_info->email : '');
            $data['phonenumber'] = (!empty($client_info->phone) ? $client_info->phone : '');
            $data['address'] = (!empty($client_info->address) ? $client_info->address : '');
        }
        $data['hash'] = '';

        // there is post request
        if (count($posted) > 0) {
            $data['hash'] = $this->payu_money_gateway->get_invoice_hash([
                'key' => $posted['key'],
                'txnid' => $posted['txnid'],
                'amount' => $posted['amount'],
                'productinfo' => $posted['productinfo'],
                'firstname' => $posted['firstname'],
                'email' => $posted['email'],
            ]);
        }

        $data['subview'] = $this->load->view('payment/payu_money', $data, true);
        $user_id = $this->session->userdata('user_id');
        if (!empty($user_id) && empty($front_end)) {
            $this->load->view('admin/_layout_main', $data); //page load
        } elseif (!empty($front_end)) {
            $this->load->view('admin/_layout_open', $data); //page load
        } else {
            $this->load->view('frontend/_layout_main', $data); //page load
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

    public
    function make_subs_payment()
    {
        $data['title'] = lang('make_payment');
        $input_data = $this->session->userdata('input_info');

        if (!empty($input_data)) {
            $data['input_data'] = $input_data;
            $data['key'] = getConfigItems('payumoney_key');

            $posted = [];
            if ($this->input->post()) {
                $data['action_url'] = $this->payu_money_gateway->get_action_url();
                foreach ($this->input->post() as $key => $value) {
                    $posted[$key] = $value;
                }
                $data['txnid'] = $posted['txnid'];
                $data['firstname'] = $posted['firstname'];
                $data['lastname'] = '';
                $data['email'] = $posted['email'];
                $data['phonenumber'] = $posted['phone'];
                $data['address'] = $posted['address'];
            } else {
                $data['txnid'] = $this->payu_money_gateway->gen_transaction_id();
                $data['action_url'] = $this->uri->uri_string();
                $subs_info = get_old_data('tbl_subscriptions', array('subscriptions_id' => $input_data['subscriptions_id']));
                $data['firstname'] = $subs_info->name;
                $data['lastname'] = '';
                $data['email'] = $subs_info->email;
                $data['phonenumber'] = $subs_info->mobile;
                $data['address'] = config_item('company_address');
            }
            $data['hash'] = '';

            // there is post request
            if (count($posted) > 0) {
                $data['hash'] = $this->payu_money_gateway->get_hash([
                    'key' => $posted['key'],
                    'txnid' => $posted['txnid'],
                    'amount' => $posted['amount'],
                    'productinfo' => $posted['productinfo'],
                    'firstname' => $posted['firstname'],
                    'email' => $posted['email'],
                ]);
            }

            $data['subview'] = $this->load->view('payment/subscriptions/payu_money', $data, true);
            $this->load->view('frontend/_layout_main', $data); //page load
        }
    }

    public
    function invPaymentSuccess()
    {
        $hashInfo = $this->payu_money_gateway->invoice_valid_hash($_POST);
        if (!$hashInfo) {
            set_message('error', lang('invalid_transaction'));
        } else {
            if ($hashInfo['status'] == 'success') {
                $data = $this->session->userdata('input_info');
                $data['transaction_id'] = $hashInfo['txnid'];
                $result = $this->payu_money_gateway->addSubsPayment($data['input_data']);
                if ($result['type'] == 'success') {
                    set_message($result['type'], $result['message']);
                } else {
                    set_message($result['type'], $result['message']);
                }
            } else {
                set_message('warning', 'Thank You. Your transaction status is ' . $hashInfo['status']);
            }
        }
        $this->session->unset_userdata('input_info');
        $client_id = $this->session->userdata('client_id');
        if (!empty($client_id)) {
            redirect('client/dashboard');
        } else {
            redirect('frontend/view_invoice/' . url_encode($_POST['productinfo']));
        }
    }

    public
    function invPaymentFailure()
    {
        $hashInfo = $this->payu_money_gateway->invoice_valid_hash($_POST);
        if (!$hashInfo) {
            set_message('error', lang('invalid_transaction'));
        } else {
            if ($hashInfo['unmappedstatus'] != 'userCancelled') {
                set_message('error', $hashInfo['error_Message'] . ' - ' . $hashInfo['status']);
            }
        }
        $this->session->unset_userdata('input_info');
        $client_id = $this->session->userdata('client_id');
        if (!empty($client_id)) {
            redirect('client/dashboard');
        } else {
            redirect('frontend/view_invoice/' . url_encode($_POST['productinfo']));
        }
    }

    public
    function subs_success()
    {
        $hashInfo = $this->payu_money_gateway->get_valid_hash($_POST);
        if (!$hashInfo) {
            set_message('error', lang('invalid_transaction'));
        } else {
            if ($hashInfo['status'] == 'success') {
                $data = $this->session->userdata('input_info');
                $data['transaction_id'] = $hashInfo['txnid'];
                $result = $this->payu_money_gateway->addSubsPayment($data['input_data']);
                if ($result['type'] == 'success') {
                    set_message($result['type'], $result['message']);
                } else {
                    set_message($result['type'], $result['message']);
                }
            } else {
                set_message('warning', 'Thank You. Your transaction status is ' . $hashInfo['status']);
            }
        }
        $this->session->unset_userdata('input_info');
        redirect('admin/dashboard');
    }

    public
    function subs_failure()
    {
        $hashInfo = $this->payu_money_gateway->get_valid_hash($_POST);
        if (!$hashInfo) {
            set_message('error', lang('invalid_transaction'));
        } else {
            if ($hashInfo['unmappedstatus'] != 'userCancelled') {
                set_message('error', $hashInfo['error_Message'] . ' - ' . $hashInfo['status']);
            }
        }
        $this->session->unset_userdata('input_info');
        redirect('checkoutPayment');
    }
}

////end