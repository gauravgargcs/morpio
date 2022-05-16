<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paypal extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Paypal_Lib');
        $this->load->model('paypal_model');
        $this->load->model('invoice_model');
    }

    function index()
    {
        $this->session->set_flashdata('response_status', 'error');
        $this->session->set_flashdata('message', lang('paypal_canceled'));
        redirect('client');
    }

    function pay($invoice_id = NULL)
    {
        if ($this->input->post()) {
            $this->load->model('payments_model');
            $in_data = $this->input->post();
            $in_data['description'] = lang('paypal_redirection_alert') . ' ' . $in_data['amount'];
            $in_data['invoices_id'] = $invoice_id;
            $in_data['payment_method'] = 'paypal';
            $this->payments_model->invoice_payment($in_data);
            exit();
        } else {
            $invoice_info = $this->invoice_model->check_by(array('invoices_id' => $invoice_id), 'tbl_invoices');
            $invoice_due = $this->invoice_model->calculate_to('invoice_due', $invoice_id);
            if ($invoice_due <= 0) {
                $invoice_due = 0.00;
            }
            $data['invoice_info'] = array(
                'item_name' => $invoice_info->reference_no,
                'item_number' => $invoice_id,
                'currency' => $invoice_info->currency,
                'amount' => $invoice_due
            );
            $data['paypal_url'] = $this->uri->uri_string();
            $data['subview'] = $this->load->view('payment/paypal', $data, FALSE);
            $this->load->view('client/_layout_modal', $data);
        }
    }


    public function complete_payment($invoice_id = null)
    {
        $input_data = $this->session->userdata('input_info');
        if (!empty($input_data)) {
            $reference_no = $this->session->userdata('reference_no');
            $cf = $input_data['payment_method'] . '_gateway';
            $paypalResponse = $this->$cf->complete_purchase([
                'token' => $reference_no,
                'amount' => $input_data['amount'],
                'currency' => $input_data['currency'],
            ]);
            // Check if error exists in the response
            if (isset($paypalResponse['L_ERRORCODE0'])) {
                set_message('error', $paypalResponse['L_SHORTMESSAGE0'] . '<br />' . $paypalResponse['L_LONGMESSAGE0']);
            } elseif (isset($paypalResponse['PAYMENTINFO_0_ACK']) && $paypalResponse['PAYMENTINFO_0_ACK'] === 'Success') {
                $this->session->unset_userdata('input_info');
                $this->session->unset_userdata('reference_no');

                $result = $this->paypal_gateway->addPayment($input_data['invoices_id'], $input_data['amount']);
                if ($result['type'] == 'success') {
                    set_message($result['type'], $result['message']);
                } else {
                    set_message($result['type'], $result['message']);
                }
            } else {
                $type = 'error';
                $message = lang('please_select_payment_method');
                set_message($type, $message);
            }
        }
        $client_id = $this->session->userdata('client_id');
        if (!empty($client_id)) {
            redirect('client/dashboard');
        } else {
            redirect('frontend/view_invoice/' . url_encode($input_data['invoice_id']));
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

        redirect('payment/' . $this->router->fetch_class() . '/complete_subs_payment');
    }

    public function complete_subs_payment($subscriptions_id = null)
    {
        $input_data = $this->session->userdata('input_info');

        if (!empty($input_data)) {
            $reference_no = $this->session->userdata('reference_no');
            $cf = $input_data['payment_method'] . '_gateway';
            $paypalResponse = $this->$cf->complete_purchase([
                'token' => $reference_no,
                'amount' => $input_data['total'],
                'currency' => $input_data['currency'],
            ]);

            // Check if error exists in the response
            if (isset($paypalResponse['L_ERRORCODE0'])) {
                set_message('error', $paypalResponse['L_SHORTMESSAGE0'] . '<br />' . $paypalResponse['L_LONGMESSAGE0']);
                $sdata['sserror'] = $paypalResponse['L_SHORTMESSAGE0'] . '<br />' . $paypalResponse['L_LONGMESSAGE0'];
                $this->session->set_userdata($sdata);
                redirect('checkoutPayment');
            } elseif (isset($paypalResponse['PAYMENTINFO_0_ACK']) && $paypalResponse['PAYMENTINFO_0_ACK'] === 'Success') {

                $this->session->unset_userdata('input_info');
                $this->session->unset_userdata('reference_no');

                $subscriptions_id = $input_data['subscriptions_id'];
                $pricing_id = $input_data['pricing_id'];
                $renew_date = $input_data['renew_date'];
                $currency = $input_data['currency'];
                $interval_type = $input_data['interval_type'];
                $payment_method = $input_data['payment_method'];

                $subtotal = $input_data['subtotal']; // is amount in db
                $total_amount = $input_data['total'];
                $coupon_code = $input_data['coupon_code_input'];
                $discount_percent = $input_data['discount_percent'];
                $discount_amount = $input_data['discount_amount'];
                $i_have_read_agree = ($input_data['i_have_read_agree'] == 'on' ? 'Yes' : 'No');

                if (!empty($subscriptions_id)) {
                    $sub_data = array(
                        'pricing_id' => $pricing_id,
                        'trial_period' => 0,
                        'is_trial' => 'No',
                        'created_date' => date("Y-m-d"),
                        'expired_date' => $renew_date,
                        'status' => 'running',
                        'currency' => $currency,
                        'frequency' => $interval_type,
                    );
                    update_old('tbl_subscriptions', array('subscriptions_id' => $subscriptions_id), $sub_data);


                    $ush_data['status'] = 'pending';
                    update_old('tbl_subscriptions_history', array('subscriptions_id' => $subscriptions_id), $ush_data);

                    $plan_info = plan_info($pricing_id);
                    $sub_h_data = array(
                        'subscriptions_id' => $subscriptions_id,
                        'currency' => $currency,
                        'frequency' => $interval_type,
                        'validity' => $renew_date,
                        'amount' => $subtotal,
                        'status' => 'running',
                        'ip' => $this->input->ip_address(),
                        'created_at' => date("Y-m-d H:i:s"),
                        'i_have_read_agree' => $i_have_read_agree,
                        'name' => $plan_info->name,
                        'multi_branch' => $plan_info->multi_branch,
                        'employee_no' => $plan_info->employee_no,
                        'client_no' => $plan_info->client_no,
                        'project_no' => $plan_info->project_no,
                        'invoice_no' => $plan_info->invoice_no,
                        'leads' => $plan_info->leads,
                        'accounting' => $plan_info->accounting,
                        'bank_account' => $plan_info->bank_account,
                        'online_payment' => $plan_info->online_payment,
                        'calendar' => $plan_info->calendar,
                        'mailbox' => $plan_info->mailbox,
                        'live_chat' => $plan_info->live_chat,
                        'tickets' => $plan_info->tickets,
                        'tasks' => $plan_info->tasks,
                        'filemanager' => $plan_info->filemanager,
                        'stock_manager' => $plan_info->stock_manager,
                        'recruitment' => $plan_info->recruitment,
                        'attendance' => $plan_info->attendance,
                        'payroll' => $plan_info->payroll,
                        'leave_management' => $plan_info->leave_management,
                        'performance' => $plan_info->performance,
                        'training' => $plan_info->training,
                        'reports' => $plan_info->reports,
                        'disk_space' => $plan_info->disk_space,
                    );
                    $subscriptions_history_id = save_old('tbl_subscriptions_history', $sub_h_data);

                    $subp_data = array(
                        'reference_no' => $reference_no,
                        'subscriptions_history_id' => $subscriptions_history_id,
                        'payment_method' => $payment_method,
                        'currency' => $currency,
                        'subtotal' => $subtotal,
                        'discount_percent' => $discount_percent,
                        'discount_amount' => $discount_amount,
                        'coupon_code' => $coupon_code,
                        'total_amount' => $total_amount,
                        'payment_date' => date("Y-m-d H:i:s"),
                    );
                    save_old('tbl_subscription_payment', $subp_data);

                    $type = 'success';
                    $message = lang('congrass_successfuly_active_plan');
                } else {
                    $type = 'error';
                    $message = lang('something_went_wrong');
                    redirect('checkoutPayment');
                }
            } else {
                $type = 'error';
                $message = lang('please_select_payment_method');
                redirect('checkoutPayment');
            }
        }
        set_message($type, $message);
        redirect('admin/dashboard');
    }
}

////end 