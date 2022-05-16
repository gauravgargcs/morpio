<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Checkout extends MY_Controller
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

        $data['subview'] = $this->load->view('payment/checkout', $data, true);
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
            $oResponse = $this->two_checkout_gateway->finish_invoice_payment($data);
            if ($oResponse->isSuccessful()) {
                $oResponse = $oResponse->getData();
                if ($oResponse['response']['responseCode'] == 'APPROVED') {
                    $result = $this->two_checkout_gateway->addPayment($data['invoice_id'], $data['amount']);
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
            $data['subview'] = $this->load->view('payment/subscriptions/two_checkout', $data, true);
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

    public function complete_purchase()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['input_data'] = json_decode($data['input_data']);
            $oResponse = $this->two_checkout_gateway->finish_payment($data);
            if ($oResponse->isSuccessful()) {
                $data['input_data']->transaction_id = $oResponse->getTransactionReference();
                $oResponse = $oResponse->getData();
                if ($oResponse['response']['responseCode'] == 'APPROVED') {
                    $result = $this->two_checkout_gateway->addSubsPayment($data['input_data']);
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
                redirect('checkoutPayment');
            }
        }
    }

    function plan_process()
    {

        if ($this->input->post()) {
            $errors = array();
            $invoice_id = $this->input->post('invoice_id');
            if (!isset($_POST['token'])) {
                $errors['token'] = 'The order cannot be processed. Please make sure you have JavaScript enabled and try again.';
            }
            // If no errors, process the order:
            if (empty($errors)) {

                require_once(APPPATH . 'libraries/2checkout/Twocheckout.php');

                Twocheckout::privateKey(config_item('2checkout_private_key'));
                Twocheckout::sellerId(config_item('2checkout_seller_id'));
                Twocheckout::sandbox((config_item('two_checkout_live') == 'TRUE') ? false : true);
                $user_info = $this->invoice_model->check_by(array('user_id' => $this->session->userdata('user_id')), 'tbl_users');
                $invoice_info = plan_info($invoice_id);
                $companies_info = get_row('tbl_companies', array('companies_id' => $this->session->userdata('companies_id')));
                try {
                    $charge = Twocheckout_Charge::auth(array(
                        "merchantOrderId" => $invoice_id,
                        "token" => $this->input->post('token'),
                        "currency" => config_item('default_currency'),
                        "total" => $this->input->post('amount'),
                        "billingAddr" => array(
                            "name" => $companies_info->name,
                            "addrLine1" => $companies_info->address,
                            "city" => $companies_info->city,
                            "country" => $companies_info->country,
                            "email" => $companies_info->email,
                            "phoneNumber" => $companies_info->phone
                        )
                    ));


                    if ($charge['response']['responseCode'] == 'APPROVED') {
                        $transaction = array(
                            'companies_id' => $this->session->userdata('companies_id'),
                            'pricing_id' => $invoice_id,
                            'trial_period' => 0,
                            'start_date' => date("Y-m-d"),
                            'end_date' => calculate_plan_end_date($invoice_id),
                            'currency' => $charge['response']['currencyCode'],
                            'payment_method' => 'Online paid by checkout',
                            'amount' => $charge['response']['total'],
                            'payment_date' => date("Y-m-d H:i:s"),
                        );

                        $sub_data = array(
                            'pricing_id' => $invoice_id,
                            'trial_period' => 0,
                            'is_trial' => 'No',
                            'expired_date' => calculate_plan_end_date($invoice_id),
                            'status' => 'running',
                        );
                        update_old('tbl_subscriptions', array('subscriptions_id' => $subscriptions_id), $sub_data);


                        $ush_data['status'] = 'pending';
                        update_old('tbl_subscriptions_history', array('subscriptions_id' => $subscriptions_id), $ush_data);

                        $plan_info = plan_info($invoice_id);
                        $sub_h_data = array(
                            'subscriptions_id' => $subscriptions_id,
                            'currency' => $currency,
                            'frequency' => $interval_type,
                            'validity' => $renew_date,
                            'amount' => $charge['response']['total'],
                            'status' => 'running',
                            'ip' => $this->input->ip_address(),
                            'created_at' => date("Y-m-d H:i:s"),
                            'i_have_read_agree' => 'Yes',
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
                            'subscriptions_history_id' => $subscriptions_history_id,
                            'payment_method' => 'checkout',
                            'currency' => $currency,
                            'subtotal' => $subtotal,
                            'total_amount' => $charge['response']['total'],
                            'payment_date' => date("Y-m-d H:i:s"),
                        );
                        save_old('tbl_subscription_payment', $subp_data);
                    }
                } catch (Twocheckout_Error $e) {
                    $type = 'error';
                    $message = 'Payment declined with error: ' . $e->getMessage();
                    set_message($type, $message);
                    redirect('client/invoice/manage_invoice/invoice_details/' . $invoice_info->invoices_id);
                }
            }
        }
    }

    function process()
    {

        if ($this->input->post()) {
            $errors = array();
            $invoice_id = $this->input->post('invoice_id');
            if (!isset($_POST['token'])) {
                $errors['token'] = 'The order cannot be processed. Please make sure you have JavaScript enabled and try again.';
            }
            // If no errors, process the order:
            if (empty($errors)) {

                require_once(APPPATH . 'libraries/2checkout/Twocheckout.php');

                Twocheckout::privateKey(config_item('2checkout_private_key'));
                Twocheckout::sellerId(config_item('2checkout_seller_id'));
                Twocheckout::sandbox((config_item('two_checkout_live') == 'TRUE') ? false : true);
                $user_info = $this->invoice_model->check_by(array('user_id' => $this->session->userdata('user_id')), 'tbl_users');
                $invoice_info = $this->invoice_model->check_by(array('invoices_id' => $invoice_id), 'tbl_invoices');
                $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');

                try {
                    $charge = Twocheckout_Charge::auth(array(
                        "merchantOrderId" => $invoice_info->invoices_id,
                        "token" => $this->input->post('token'),
                        "currency" => $invoice_info->currency,
                        "total" => $this->input->post('amount'),
                        "billingAddr" => array(
                            "name" => $client_info->name,
                            "addrLine1" => $client_info->address,
                            "city" => $client_info->city,
                            "country" => $client_info->country,
                            "email" => $client_info->email,
                            "phoneNumber" => $client_info->phone
                        )
                    ));


                    if ($charge['response']['responseCode'] == 'APPROVED') {
                        $transaction = array(
                            'invoices_id' => $charge['response']['merchantOrderId'],
                            'paid_by' => $client_info->client_id,
                            'payer_email' => $charge['response']['billingAddr']['email'],
                            'payment_method' => '1',
                            'currency' => $charge['response']['currencyCode'],
                            'notes' => 'Paid by ' . $user_info->username,
                            'amount' => $charge['response']['total'],
                            'trans_id' => $charge['response']['transactionId'],
                            'month_paid' => date('m'),
                            'year_paid' => date('Y'),
                            'payment_date' => date('d-m-Y H:i:s')
                        );

                        $this->invoice_model->_table_name = 'tbl_payments';
                        $this->invoice_model->_primary_key = 'payments_id';
                        $this->invoice_model->save($transaction);

                        $due = round($this->invoice_model->calculate_to('invoice_due', $invoice_id), 2);
                        if ($_POST['amount'] < $due) {
                            $status = 'partially_paid';
                        }
                        if ($_POST['amount'] == $due) {
                            $status = 'Paid';
                        }
                        $invoice_data['status'] = $status;
                        update('tbl_invoices', array('invoices_id' => $invoice_id), $invoice_data);

                        $currency = $this->invoice_model->client_currency_sambol($client_info->client_id);
                        $activity = array(
                            'user' => $this->session->userdata('user_id'),
                            'module' => 'invoice',
                            'module_field_id' => $invoice_info->invoices_id,
                            'activity' => 'activity_new_payment',
                            'icon' => 'fa-usd',
                            'value1' => $currency->symbol . ' ' . $charge['response']['total'],
                            'value2' => $invoice_info->reference_no,
                        );
                        $this->invoice_model->_table_name = 'tbl_activities';
                        $this->invoice_model->_primary_key = 'activities_id';
                        $this->invoice_model->save($activity);
                    }
                    $this->send_payment_email($invoice_id, $charge['response']['total']); // Send email to client

                    $this->notify_to_client($invoice_id, $invoice_info->reference_no); // Send email to client
                } catch (Twocheckout_Error $e) {
                    $type = 'error';
                    $message = 'Payment declined with error: ' . $e->getMessage();
                    set_message($type, $message);
                    redirect('client/invoice/manage_invoice/invoice_details/' . $invoice_info->invoices_id);
                }
            }
        }
    }

    function send_payment_email($invoices_id, $paid_amount)
    {
        $email_template = $this->invoice_model->check_by(array('email_group' => 'payment_email'), 'tbl_email_templates');
        $message = $email_template->template_body;
        $subject = $email_template->subject;

        $inv_info = $this->invoice_model->check_by(array('invoices_id' => $invoices_id), 'tbl_invoices');
        $currency = $inv_info->currency;
        $reference = $inv_info->reference_no;

        $invoice_currency = str_replace("{INVOICE_CURRENCY}", $currency, $message);
        $reference = str_replace("{INVOICE_REF}", $reference, $invoice_currency);
        $amount = str_replace("{PAID_AMOUNT}", $paid_amount, $reference);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $amount);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);
        $client_info = $this->invoice_model->check_by(array('client_id' => $inv_info->client_id), 'tbl_client');

        $address = $client_info->email;

        $params['recipient'] = $address;

        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'invoice',
            'module_field_id' => $invoices_id,
            'activity' => lang('activity_send_payment'),
            'icon' => 'fa-usd',
            'value1' => $reference,
            'value2' => $currency . ' ' . $amount,
        );
        $this->invoice_model->_table_name = 'tbl_activities';
        $this->invoice_model->_primary_key = 'activities_id';
        $this->invoice_model->save($activity);

        $this->invoice_model->send_email($params);
    }

    function notify_to_client($client_id, $invoice_ref)
    {

        $this->load->library('email');
        $client_info = $this->invoice_model->check_by(array('client_id' => $client_id), 'tbl_client');
        $data['invoice_ref'] = $invoice_ref;

        $email_msg = $this->load->view('payment/stripe_InvoicePaid', $data, TRUE);
        $email_subject = '[' . $this->config->item('company_name') . ' ] Purchase Confirmation';
        $this->email->from($this->config->item('company_email'), $this->config->item('company_name') . ' Payments');
        $this->email->to($client_info->email);
        $this->email->reply_to($this->config->item('company_email'), $this->config->item('company_name'));
        $this->email->subject($email_subject);

        $this->email->message($email_msg);

        $send = $this->email->send();
        if ($send) {
            return $send;
        } else {
            $error = show_error($this->email->print_debugger());
            return $error;
        }
    }
}

////end 