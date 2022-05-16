<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 *
 * @package
 */
class Stripe extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_model');
    }

    function pay($invoices_id = NULL)
    {
        $invoice_info = $this->invoice_model->check_by(array('invoices_id' => $invoices_id), 'tbl_invoices');
        $invoice_due = $this->invoice_model->calculate_to('invoice_due', $invoices_id);
        if ($invoice_due <= 0) {
            $invoice_due = 0.00;
        }

        $data['title'] = lang('make_payment');

        $data['stripe'] = TRUE;
        $data['invoices_info'] = $invoice_info;
        $data['invoice_info'] = array(
            'item_name' => $invoice_info->reference_no,
            'item_number' => $invoices_id,
            'currency' => $invoice_info->currency,
            'allow_stripe' => $invoice_info->allow_stripe,
            'amount' => $invoice_due
        );

        if ($this->input->post()) {
            $data['post'] = true;
            $data['item_name'] = $invoice_info->reference_no;
            $data['amount'] = $this->input->post('amount', true);
            $data['currency'] = $invoice_info->currency;
        }

        if ($this->input->post()) {
            $data['subview'] = $this->load->view('payment/stripe', $data, true);
            $client_id = $this->session->userdata('client_id');
            if (!empty($client_id)) {
                $this->load->view('client/_layout_main', $data);
            } else {
                $this->load->view('frontend/_layout_main', $data);
            }
        } else {
            $data['subview'] = $this->load->view('payment/stripe', $data, FALSE);
            $this->load->view('client/_layout_modal', $data);
        }
    }

    public function payPlan($pricing_id)
    {
        $sdata = get_active_subs();
        $package_info = plan_info($pricing_id);
        $frequency = $sdata->frequency;
        $package_info->amount = get_currencywise_price(true, $sdata->currency, $sdata->pricing_id)->$frequency;
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
        $this->session->set_userdata($data);

        redirect('payment/' . $this->router->fetch_class() . '/make_subs_payment');
    }

    public function make_subs_payment()
    {
        $data['title'] = lang('make_payment');
        $input_data = $this->session->userdata('input_info');
        if (!empty($input_data)) {
            $data['input_data'] = $input_data;
           
            $data['subview'] = $this->load->view('payment/subscriptions/stripe', $data, true);
            $user_id = $this->session->userdata('user_id');
            if (!empty($user_id) && empty($front_end)) {
                $this->load->view('frontend/_layout_main', $data); //page load
            } elseif (!empty($front_end)) {
                $this->load->view('frontend/_layout_open', $data); //page load
            } else {
                $this->load->view('frontend/_layout_main', $data); //page load
            }
        }
    }

    public function purchase()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            try {
                $charge = $this->stripe_gateway->finish_invoice_payment($data);
                if ($charge->paid == true) {
                    $result = $this->stripe_gateway->addPayment($data['invoice_id'], $data['amount']);
                    if ($result['type'] == 'success') {
                        set_message($result['type'], $result['message']);
                    } else {
                        set_message($result['type'], $result['message']);
                    }
                }
            } catch (Exception $e) {
                set_message('error', $e->getMessage());
            }
            if (!empty($client_id)) {
                redirect('client/dashboard');
            } else {
                redirect('frontend/view_invoice/' . url_encode($data['invoice_id']));
            }
        }
    }

    public function complete_purchase()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['input_data'] = json_decode($data['input_data']);
            $data['email'] = $this->input->post('stripeEmail');
            try {
                $charge = $this->stripe_gateway->finish_payment($data);
                if ($charge->paid == true) {
                    $transactionid = $charge->id;
                    $data['input_data']->transaction_id = $transactionid;
                    $result = $this->stripe_gateway->addSubsPayment($data['input_data']);
                    if ($result['type'] == 'success') {
                        set_message($result['type'], $result['message']);
                    } else {
                        set_message($result['type'], $result['message']);
                    }
                }
                redirect('admin/dashboard');
            } catch (Exception $e) {
                set_message('error', $e->getMessage());
                redirect('checkoutPayment');
            }
        }
    }
}

////end