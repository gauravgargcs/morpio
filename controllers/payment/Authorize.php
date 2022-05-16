<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 *
 * @package    Freelancer Office
 */
class Authorize extends MY_Controller
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
            'client_id' => $invoice_info->client_id,
            'currency' => $invoice_info->currency,
            'amount' => $invoice_due
        );

        $data['subview'] = $this->load->view('payment/authorize', $data, TRUE);
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
            try {
                $oResponse = $this->authorize_gateway->finish_invoice_payment($data);
            } catch (Exception $e) {
                $message = $e->getMessage();
                set_message('error', (string)$message);
            }
            if (!empty($oResponse)) {
                $oResponseData = $oResponse->getData();
                if (isset($oResponseData->messages->resultCode) && $oResponseData->messages->resultCode == 'Error') {
                    $message = $oResponseData->messages->message->text;
                    set_message('error', (string)$message);
                }
                if ($oResponse->isSuccessful()) {
                    if ($oResponseData->transactionResponse->responseCode == '1') {
                        $result = $this->authorize_aim_gateway->addPayment($data['invoice_id'], $data['amount']);
                        if ($result['type'] == 'success') {
                            set_message($result['type'], $result['message']);
                        } else {
                            set_message($result['type'], $result['message']);
                        }
                    }
                } elseif ($oResponse->isRedirect()) {
                    $oResponse->redirect();
                } else {
                    set_message('error', $oResponse->getMessage());
                }
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
        $this->session->set_userdata($data);
        redirect('payment/' . $this->router->fetch_class() . '/make_subs_payment');
    }

    public function make_subs_payment()
    {
        $data['title'] = lang('make_payment');
        $input_data = $this->session->userdata('input_info');
        if (!empty($input_data)) {
            $data['input_data'] = $input_data;            
            $data['subview'] = $this->load->view('payment/subscriptions/authorize', $data, true);
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

    public function complete_purchase()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['input_data'] = json_decode($data['input_data']);
            try {
                $oResponse = $this->authorize_gateway->finish_payment($data);
            } catch (Exception $e) {
                $message = $e->getMessage();
                set_message('error', (string)$message);
                redirect('checkoutPayment');
            }
            if (!empty($oResponse)) {
                $oResponseData = $oResponse->getData();
                if (isset($oResponseData->messages->resultCode) && $oResponseData->messages->resultCode == 'Error') {
                    $message = $oResponseData->messages->message->text;
                    set_message('error', (string)$message);
                }
                if ($oResponse->isSuccessful()) {
                    if ($oResponseData->transactionResponse->responseCode == '1') {
                        $data['input_data']->transaction_id = $oResponseData->transactionResponse->transId;
                        $result = $this->authorize_aim_gateway->addSubsPayment($data['input_data']);
                        if ($result['type'] == 'success') {
                            set_message($result['type'], $result['message']);
                        } else {
                            set_message($result['type'], $result['message']);
                        }
                        redirect('admin/dashboard');
                    }
                } elseif ($oResponse->isRedirect()) {
                    $oResponse->redirect();
                } else {
                    set_message('error', $oResponse->getMessage());
                }
            }
            redirect('checkoutPayment');
        }
    }
}

////end 
