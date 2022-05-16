<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of payroll_model
 *
 * @author NaYeM
 */
class Payments_model extends MY_Model
{

    public $_table_name;
    public $_order_by;
    public $_primary_key;


    /**
     * Process subscription payment offline or online
     * @since  Version 1.0.1
     * @param  array $data $_POST data
     * @return boolean
     */
    public function process_payment($data)
    {
         
        if (!is_numeric($data['payment_method']) && !empty($data['payment_method'])) {
            if ($data['payment_method'] == 'braintree') {
                $data['payment_method'] = 'paypal_braintree';
            }
            $cf = $data['payment_method'] . '_gateway';
            $this->$cf->process_payment($data);
        }

        return false;
    }

    /**
     * Process subscription payment offline or online
     * @since  Version 1.0.1
     * @param  array $data $_POST data
     * @return boolean
     */
    public function addSubsPayment($input_data)
    {
        if (!empty($input_data)) {

            $reference_no = $this->session->userdata('reference_no');
            $subscriptions_id = $input_data->subscriptions_id;
            $transaction_id = !empty($input_data->transaction_id) ? $input_data->transaction_id : '-';
            $pricing_id = $input_data->pricing_id;
            $renew_date = $input_data->renew_date;
            $currency = $input_data->currency;
            $interval_type = $input_data->interval_type;
            $payment_method = $input_data->payment_method;

            $subtotal = $input_data->subtotal; // is amount in db
            $total_amount = $input_data->total;
            $coupon_code = $input_data->coupon_code_input;
            $discount_percent = $input_data->discount_percent;
            $discount_amount = $input_data->discount_amount;
            $i_have_read_agree = ($input_data->i_have_read_agree == 'on' ? 'Yes' : 'No');

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


                $ush_data['status'] = 'running';
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
                    'transaction_id' => $transaction_id,
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

                $result['type'] = 'success';
                $result['message'] = lang('congrass_successfuly_active_plan');
                set_message($result['type'], $result['message']);

                $this->notify_company($subscriptions_id, $total_amount);

                $this->session->unset_userdata('input_info');
                $this->session->unset_userdata('reference_no');

                return $result;
            } else {

                $this->session->unset_userdata('input_info');
                $this->session->unset_userdata('reference_no');
                $type = 'error';
                $message = lang('something_went_wrong');
                set_message($type, $message);
                redirect('checkoutPayment');
            }
        } else {

            $this->session->unset_userdata('input_info');
            $this->session->unset_userdata('reference_no');
            $result['type'] = 'error';
            $result['message'] = lang('please_select_payment_method');
            return $result;
        }
        return false;
    }


    /**
     * Process subscription payment offline or online
     * @since  Version 1.0.1
     * @param  array $data $_POST data
     * @return boolean
     */
    public function invoice_payment($data)
    {
        if (!is_numeric($data['payment_method']) && !empty($data['payment_method'])) {
            if ($data['payment_method'] == 'braintree') {
                $data['payment_method'] = 'paypal_braintree';
            }
            $cf = $data['payment_method'] . '_gateway';
            $this->$cf->invoice_payment($data);
        }

        return false;
    }

    /**
     * Process invoice payment offline or online
     * @since  Version 1.0.1
     * @param  array $data $_POST data
     * @return boolean
     */
    public function addPayment($invoices_id, $amount)
    {
        $this->load->model('invoice_model');
        $invoice_info = $this->db->where('invoices_id', $invoices_id)->get('tbl_invoices')->row();
        $client_info = $this->db->where('client_id', $invoice_info->client_id)->get('tbl_client')->row();
        $currency = $this->invoice_model->client_currency_sambol($invoice_info->client_id);
        if (empty($currency)) {
            $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        }

        $transaction = array(
            'invoices_id' => $invoices_id,
            'paid_by' => $invoice_info->client_id,
            'payer_email' => $client_info->email,
            'payment_method' => '1',
            'notes' => "Payment for Invoice " . $invoice_info->reference_no,
            'amount' => $amount,
            'currency' => $currency->symbol,
            'trans_id' => $invoice_info->reference_no,
            'month_paid' => date('m'),
            'year_paid' => date('Y'),
            'payment_date' => date('d-m-Y')
        );
        $this->invoice_model->_table_name = 'tbl_payments';
        $this->invoice_model->_primary_key = 'payments_id';
        $payments_id = $this->invoice_model->save($transaction);
        $due = round($this->invoice_model->calculate_to('invoice_due', $invoices_id), 2);
        if ($amount < $due) {
            $status = 'partially_paid';
        }
        if ($amount == $due) {
            $status = 'paid';
        }
        if (!empty($status)) {
            $invoice_data['status'] = $status;
            update('tbl_invoices', array('invoices_id' => $invoices_id), $invoice_data);
        }

        // Store the order in the database.
        if ($payments_id != 0) {
            $currency = $this->invoice_model->client_currency_sambol($client_info->client_id);
            $activity = array(
                'user' => (!empty($this->session->userdata('user_id')) ? $this->session->userdata('user_id') : $client_info->client_id),
                'module' => 'invoice',
                'module_field_id' => $invoice_info->invoices_id,
                'activity' => 'activity_new_payment',
                'icon' => 'fa-usd',
                'value1' => display_money($amount, $currency->symbol),
                'value2' => $invoice_info->reference_no,
            );
            $this->invoice_model->_table_name = 'tbl_activities';
            $this->invoice_model->_primary_key = 'activities_id';
            $this->invoice_model->save($activity);

            $this->send_payment_email($invoices_id, $amount); // Send email to client
            $this->notify_to_client($invoices_id, $invoice_info->reference_no); // Send email to client
            $result['type'] = 'success';
            $result['message'] = 'Payment received and applied to Invoice ' . $invoice_info->reference_no;
            set_message($result['type'], $result['message']);
        } else {
            $result['type'] = 'error';
            $result['message'] = 'Payment not recorded in the database. Please contact the system Admin.';
            set_message($result['type'], $result['message']);
        }
        return $result;
    }

    function send_payment_email($invoices_id, $paid_amount)
    {
        $this->load->model('invoice_model');
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
        $this->load->model('invoice_model');
        $this->load->library('email');
        $client_info = $this->invoice_model->check_by(array('client_id' => $client_id), 'tbl_client');
        if (!empty($client_info->email)) {
            $data['invoice_ref'] = $invoice_ref;
            $email_msg = $this->load->view('payment/stripe_InvoicePaid', $data, TRUE);
            $email_subject = '[' . $this->config->item('company_name') . ' ] Purchase Confirmation';
            $params['recipient'] = $client_info->email;
            $params['subject'] = $email_subject;
            $params['message'] = $email_msg;
            $params['resourceed_file'] = '';
            $this->invoice_model->send_email($params);
        } else {
            return true;
        }
    }

    function notify_company($subscriptions_id, $total_amount)
    {
        $subscription_info = get_old_data('tbl_subscriptions', array('subscriptions_id' => $subscriptions_id));

        $this->load->model('invoice_model');
        $this->load->library('email');
        if (!empty($subscription_info->email)) {
            $sub_domain = (isset($_SERVER['HTTPS']) ? "https://" : "http://");
            $sub_domain .= $subscription_info->domain . '.';
            $sub_domain .= $_SERVER['HTTP_HOST'];
            $sub_domain .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

            $currency = $this->invoice_model->check_by(array('code' => $subscription_info->currency), 'tbl_currencies');
            $data['name'] = $subscription_info->name;
            $data['amount'] = display_money($total_amount, $currency->symbol);
            $data['plan_name'] = $this->db->where(array('id' => $subscription_info->pricing_id))->get('tbl_frontend_pricing')->row()->name;
            $data['login_url'] = $sub_domain;
            $email_msg = $this->load->view('payment/PurchaseConfirmation', $data, TRUE);
            $email_subject = '[' . $this->config->item('company_name') . ' ] Subscription Purchase Confirmation';
            $params['recipient'] = $subscription_info->email;
            $params['subject'] = $email_subject;
            $params['message'] = $email_msg;
            $params['resourceed_file'] = '';
            $this->invoice_model->send_email($params);
        } else {
            return true;
        }
    }
}
