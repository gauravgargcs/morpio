<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Braintree extends MY_Controller
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
            'client_id' => $invoice_info->client_id,
            'amount' => $invoice_due
        );

        $data['client_token'] = $this->paypal_braintree_gateway->generate_invoice_token();
        $data['subview'] = $this->load->view('payment/braintree', $data, true);
        $client_id = $this->session->userdata('client_id');
        if (!empty($client_id)) {
            $this->load->view('client/_layout_main', $data);
        } else {
            $this->load->view('frontend/_layout_main', $data);
        }
    }

    public function purchase()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['nonce'] = $this->input->post('payment_method_nonce');
            $oResponse = $this->paypal_braintree_gateway->finish_invoice_payment($data);
            if (!empty($oResponse) && $oResponse->isSuccessful()) {
                $transactionid = $oResponse->getTransactionReference();
                $paymentResponse = $this->paypal_braintree_gateway->fetch_invoice_payment($transactionid);
                $paymentData = $paymentResponse->getData();
                $result = $this->stripe_gateway->addPayment($data['invoice_id'], $data['amount']);
                if ($result['type'] == 'success') {
                    set_message($result['type'], $result['message']);
                } else {
                    set_message($result['type'], $result['message']);
                }
            } elseif ($oResponse->isRedirect()) {
                $oResponse->redirect();
            } else {
                set_message('error', $oResponse->getMessage());
            }
            $client_id = $this->session->userdata('client_id');
            if (!empty($client_id)) {
                redirect('client/dashboard');
            } else {
                redirect('frontend/view_invoice/' . url_encode($data['invoice_id']));
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
            $data['client_token'] = $this->paypal_braintree_gateway->generate_token();
            $data['subview'] = $this->load->view('payment/subscriptions/braintree', $data, true);
            $this->load->view('frontend/_layout_main', $data); //page load
        }
    }

    public function complete_purchase()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['input_data'] = json_decode($data['input_data']);
            $data['amount'] = $data['input_data']->total;
            $data['nonce'] = $this->input->post('payment_method_nonce');
            $data['currency'] = $data['input_data']->currency;
            $oResponse = $this->paypal_braintree_gateway->finish_payment($data);
            if ($oResponse->isSuccessful()) {
                $transactionid = $oResponse->getTransactionReference();
                $paymentResponse = $this->paypal_braintree_gateway->fetch_payment($transactionid);
                $paymentData = $paymentResponse->getData();

                $data['input_data']->transaction_id = $transactionid;
                $result = $this->stripe_gateway->addSubsPayment($data['input_data']);
                if ($result['type'] == 'success') {
                    set_message($result['type'], $result['message']);
                } else {
                    set_message($result['type'], $result['message']);
                }
                redirect('admin/dashboard');
            } elseif ($oResponse->isRedirect()) {
                $oResponse->redirect();
            } else {
                set_message('error', $oResponse->getMessage());
                redirect('checkoutPayment');
            }
        }
    }
}

////end 