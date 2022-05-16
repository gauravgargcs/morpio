<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Cronjob extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('cron_model');
        $this->load->model('invoice_model');
        $this->load->model('estimates_model');
        $this->load->model('proposal_model');
        $this->load->helper('string');
        $this->load->helper('file');
        $this->load->library('email');
    }



    function index($manually = null)
    {

        if (config_item('active_cronjob') == "on" && time() > (config_item('last_cronjob_run') + 3) || !empty($manually)) {
            $input_data['last_cronjob_run'] = time();
            $companies_id = $this->session->userdata('companies_id');
            if (empty($companies_id)) {
                $companies_id = null;
            }
            foreach ($input_data as $key => $value) {
                $where = array('companies_id' => $companies_id, 'config_key' => $key);
                $data = array('value' => $value);
                $this->db->where($where)->update('tbl_config', $data);
                $exists = $this->db->where($where)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("companies_id" => $companies_id, "config_key" => $key, "value" => $value));
                }
            }
            // send overdue invoice email
            $this->invoices_cron();
            //send recurring_invoice email
            $this->recurring_invoice();
            // send expire_estimate email
            $this->expire_estimate();
            // send expire_poposals email
            $this->expire_poposals();
            // send projects_cron email
            $this->projects_cron();
            // send goal_tracking_cron email
            $this->goal_tracking_cron();
            $this->set_attendance();
            $this->database_backup();
            $this->database_delete_backup();
            $this->autoclose_tickets();
            $this->reminders();
            
        }
    }


    private function reminders()
    {
        // Customer reminders
        $this->db->where('notified', 'No');
        $reminders = $this->db->get('tbl_reminders')->result();
        foreach ($reminders as $reminder) {
            if (date('Y-m-d H:i:s') >= $reminder->date) {
                $module = $reminder->module;
                $module_id = $reminder->module_id;
                if ($module == 'invoice') {
                    $invoice_info = $this->invoice_model->check_by(array('invoices_id' => $module_id), 'tbl_invoices');
                    $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');
                    $email_template = $this->invoice_model->check_by(array('email_group' => 'invoice_reminder'), 'tbl_email_templates');
                    if (!empty($client_info)) {
                        $client = $client_info->name;
                        $currency = $this->invoice_model->client_currency_sambol($client_info->client_id);;
                    } else {
                        $client = '-';
                        $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                    }
                    $amount = $this->invoice_model->calculate_to('invoice_due', $invoice_info->invoices_id);
                    $currency = $currency->code;
                    $due_date = $invoice_info->due_date;
                    $message = $reminder->description . '<br> ' . $email_template->template_body;

                    $client_name = str_replace("{CLIENT}", $client, $message);
                    $Amount = str_replace("{AMOUNT}", $amount, $client_name);
                    $Currency = str_replace("{CURRENCY}", $currency, $Amount);
                    $Due_date = str_replace("{DUE_DATE}", $due_date, $Currency);
                    $link = str_replace("{INVOICE_LINK}", base_url() . 'client/invoice/manage_invoice/invoice_details/' . $invoice_info->invoices_id, $Due_date);
                    $message = str_replace("{SITE_NAME}", config_item('company_name'), $link);
                    $ref = $invoice_info->reference_no;
                    $not_link = 'client/invoice/manage_invoice/invoice_details/' . $invoice_info->invoices_id;
                    $value = $invoice_info->reference_no;


                } else if ($module == 'estimate') {
                    $estimate_info = $this->invoice_model->check_by(array('estimates_id' => $module_id), 'tbl_estimates');
                    $client_info = $this->invoice_model->check_by(array('client_id' => $estimate_info->client_id), 'tbl_client');
                    $ref = $estimate_info->reference_no;
                    $message = $reminder->description;
                    $not_link = 'client/estimates/index/estimates_details/' . $module_id;
                    $value = $estimate_info->reference_no;

                } else if ($module == 'proposal') {
                    $proposal_info = $this->invoice_model->check_by(array('module' => 'client', 'module_id' => $module_id), 'tbl_proposals');
                    $client_info = $this->invoice_model->check_by(array('client_id' => $proposal_info->module_id), 'tbl_client');
                    $ref = $proposal_info->reference_no;
                    $message = $reminder->description;
                    $not_link = 'client/proposals/index/proposals_details/' . $module_id;
                    $value = $proposal_info->reference_no;
                } else if ($module == 'client') {
                    $client_info = $this->invoice_model->check_by(array('client_id' => $module_id), 'tbl_client');
                    $ref = null;
                    $message = $reminder->description;
                    $not_link = 'client/settings';
                    $value = $client_info->name;
                } else if ($module == 'leads') {
                    $client_info = $this->invoice_model->check_by(array('leads_id' => $module_id), 'tbl_leads');
                    $ref = null;
                    $message = $reminder->description;
                    $not_link = '#';
                    $value = $client_info->lead_name;
                }
                if (!empty($client_info)) {
                    $subject = lang($module) . ' ' . lang('reminder') . ' ' . $ref;
                    $recipient = $client_info->email;
                    $data['message'] = $message;

                    $message = $this->load->view('email_template', $data, TRUE);
                    $params = array(
                        'recipient' => $recipient,
                        'subject' => $subject,
                        'message' => $message
                    );
                    $params['resourceed_file'] = '';
                    $this->invoice_model->send_email($params);
                }
                if (!empty($client_info->primary_contact)) {
                    $notifyUser = array($client_info->primary_contact);
                } else {
                    $user_info = $this->invoice_model->check_by(array('company' => $client_info->client_id), 'tbl_account_details');
                    if (!empty($user_info)) {
                        $notifyUser = array($user_info->user_id);
                    }
                }
                if (!empty($notifyUser)) {
                    foreach ($notifyUser as $v_user) {
                        if ($v_user != $this->session->userdata('user_id')) {
                            add_notification(array(
                                'to_user_id' => $v_user,
                                'icon' => 'shopping-cart',
                                'description' => 'not_reminder',
                                'link' => $not_link,
                                'value' => $value,
                            ));
                        }
                    }
                    show_notification($notifyUser);
                }

                if ($reminder->notify_by_email == 'Yes') {
                    $user_info = $this->invoice_model->check_by(array('user_id' => $reminder->user_id), 'tbl_users');
                    if (!empty($user_info)) {
                        $params = array(
                            'recipient' => $user_info->email,
                            'subject' => $subject,
                            'message' => $message
                        );
                        $params['resourceed_file'] = '';
                        $this->invoice_model->send_email($params);

                        $notifyUser = array($user_info->user_id);
                        if (!empty($notifyUser)) {
                            foreach ($notifyUser as $v_user) {
                                if ($v_user != $this->session->userdata('user_id')) {
                                    add_notification(array(
                                        'to_user_id' => $v_user,
                                        'icon' => 'shopping-cart',
                                        'description' => 'not_reminder',
                                        'link' => $not_link,
                                        'value' => $value,
                                    ));
                                }
                            }
                            show_notification($notifyUser);
                        }
                    }
                }
                $this->db->where('reminder_id', $reminder->reminder_id);
                $this->db->update('tbl_reminders', array('notified' => 'Yes'));
            }
        }
    }

    function autoclose_tickets()
    {
        $auto_close_ticket = config_item('auto_close_ticket');
        if (config_item('auto_close_ticket') > 0) {
            $all_tickets = $this->db->where('status !=', 'closed')->get('tbl_tickets')->result();
            if (!empty($all_tickets)) {
                foreach ($all_tickets as $ticket) {
                    $close_ticket = false;
                    if (!is_null($ticket->last_reply)) {
                        $last_reply = strtotime($ticket->last_reply);
                        if ($last_reply <= strtotime('-' . $auto_close_ticket . ' hours')) {
                            $close_ticket = true;
                        }
                    } else {

                        $created = strtotime($ticket->created);
                        if ($created <= strtotime('-' . $auto_close_ticket . ' hours')) {
                            $close_ticket = true;
                        }
                    }
                    if ($close_ticket == true) {
                        $this->db->where('tickets_id', $ticket->tickets_id);
                        $this->db->update('tbl_tickets', array(
                            'status' => 'closed'
                        ));
                    }
                }
            }
        }
        return TRUE;
    }

    function database_backup()
    {
       

         if ((config_item('automatic_database_backup') == 'on') && date('H') == 3 &&  (time() > (config_item('last_autobackup') + 1 * 24 * 60 * 60) ||  time() > (config_item('last_monthly_autobackup') + 30 * 24 * 60 * 60))) {


                $this->load->dbutil();
                $prefs = array('format' => 'zip', 'filename' => 'Database-auto-full-backup_' . date('Y-m-d_H-i'));
                $backup = $this->dbutil->backup($prefs);
         }else{
            return false;
         }
       
        // Auto Backup every 1 days
        if ((config_item('automatic_database_backup') == 'on') && time() >= (config_item('last_autobackup') + 1 * 24 * 60 * 60)) {


            $file_name = 'DB-daily-backup_'.$this->db->database .'-'. date('Y-m-d_H-i') . '.zip';
            if (!write_file('./uploads/backup/'.$file_name, $backup)) {
                log_message('error', "Error while creating auto database backup!");
            } else {
                $input_data['last_autobackup'] = time();
                foreach ($input_data as $key => $value) {
                    $data = array('value' => $value);
                    $this->db->where('config_key', $key)->update('tbl_config', $data);
                    $exists = $this->db->where('config_key', $key)->get('tbl_config');
                    if ($exists->num_rows() == 0) {
                        $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                    }
                }
                $insert_data = array ('name'=>$this->db->database,
                                      'file' =>$file_name,
                                      'type'=>'DAILY',
                                      'created_at'=>date('Y-m-d H:i:s'),
                                    );
                  $this->db->insert('tbl_db_backup', $insert_data);
                log_message('error', "Daily Auto backup has been created.");

            }
        }
        // Auto Backup every 30 days
        if ((config_item('automatic_database_backup') == 'on') && time() >= (config_item('last_monthly_autobackup') + 30 * 24 * 60 * 60)) {
            
            $file_name = 'DB-monthly-backup_'.$this->db->database .'-'. date('Y-m-d_H-i') . '.zip';
            if (!write_file('./uploads/backup/'.$file_name, $backup)) {
                log_message('error', "Error while creating auto database backup!");
            } else {
                $input_data['last_monthly_autobackup'] = time();
                foreach ($input_data as $key => $value) {
                    $data = array('value' => $value);
                    $this->db->where('config_key', $key)->update('tbl_config', $data);
                    $exists = $this->db->where('config_key', $key)->get('tbl_config');
                    if ($exists->num_rows() == 0) {
                        $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                    }
                }
                 $insert_data = array ('name'=>$this->db->database,
                                      'file' =>$file_name,
                                      'type'=>'MONTHLY',
                                      'created_at'=>date('Y-m-d H:i:s'),
                                    );
                  $this->db->insert('tbl_db_backup', $insert_data);
                log_message('error', " Auto backup has been created.");

            }
        }
        return TRUE;
    }

    function set_attendance()
    {
        // get all attendance by date
        $where = array('role_id !=' => 2, 'activated' => 1);
        $all_employee_info = $this->db->where($where)->get('tbl_users')->result();

        foreach ($all_employee_info as $v_employee) {
        //             set timezone to user timezone

            $date = date('Y-m-d');
            // get office houre info

            // get all attendance by date
            $this->invoice_model->_table_name = 'tbl_attendance';
            $this->invoice_model->_order_by = 'attendance_id';
            $all_attendance_info = $this->invoice_model->get_by(array('user_id' => $v_employee->user_id, 'date_in' => $date), FALSE);
            // get working holiday
            $holidays = $this->global_model->get_holidays(); //tbl working Days Holiday

            $day_name = date("l", strtotime(date('Y-m-d')));
            if (!empty($holidays)) {
                foreach ($holidays as $v_holiday) {
                    if ($v_holiday->day == $day_name) {
                        $yes_holiday[] = $day_name;
                    }
                }
            }
            // get public holiday
            $public_holiday = $this->invoice_model->check_by(array('start_date' => date('Y-m-d')), 'tbl_holiday');

            if (empty($public_holiday) || empty($yes_holiday)) {
                if (!empty($all_attendance_info)) {

                } else {
                    // get leave info
                    $atdnc_data['user_id'] = $v_employee->user_id;
                    $atdnc_data['date_in'] = $date;
                    $atdnc_data['date_out'] = $date;
                    $atdnc_data['attendance_status'] = 0;
                    $this->invoice_model->_table_name = 'tbl_attendance';
                    $this->invoice_model->_primary_key = "attendance_id";
                    $this->invoice_model->save($atdnc_data);
                }
            }

        }
        return TRUE;
    }

    function goal_tracking_cron()
    {
        $mdate = date('Y-m-d');
        $all_goal_tracking = $this->cron_model->get_permission('tbl_goal_tracking');

        if (!empty($all_goal_tracking)) {
            foreach ($all_goal_tracking as $v_goal_track) {
                $goal_achieve = $this->cron_model->get_progress($v_goal_track);

                if ($v_goal_track->end_date <= $mdate) { // check today is last date or not


                    if ($v_goal_track->email_send == 'no') {// check mail are send or not

                        if ($v_goal_track->achievement <= $goal_achieve['achievement']) {
                            if ($v_goal_track->notify_goal_achive == 'on') {// check is notify is checked or not check

                                $this->cron_model->send_goal_mail('goal_achieve', $v_goal_track);
                            }
                        } else {
                            if ($v_goal_track->notify_goal_not_achive == 'on') {// check is notify is checked or not check
                                $this->cron_model->send_goal_mail('goal_not_achieve', $v_goal_track);
                            }
                        }
                    }
                }
            }
        }
        return TRUE;
    }


    function expire_poposals()
    {
        $expire_proposal = $this->cron_model->get_overdue('tbl_proposals', 'proposal');

        if (!empty($expire_proposal)) {
            foreach ($expire_proposal as $v_proposal) {
                if ($v_proposal->module == 'client') {
                    $currencies = $this->proposal_model->client_currency_sambol($v_proposal->client_id);

                    $email_template = $this->cron_model->check_by(array('email_group' => 'proposal_overdue_email'), 'tbl_email_templates');

                    $message = $email_template->template_body;

                    $subject = $email_template->subject;

                    $client_name = str_replace("{CLIENT}", client_name($v_proposal->client_id), $message);
                    $Ref = str_replace("{PROPOSAL_REF}", $v_proposal->reference_no, $client_name);
                    $Amount = str_replace("{AMOUNT}", $this->proposal_model->proposal_calculation('total', $v_proposal->proposals_id), $Ref);
                    $Currency = str_replace("{CURRENCY}", $currencies->symbol, $Amount);

                    $link = str_replace("{PROPOSAL_LINK}", base_url() . 'client/proposals/index/proposals_details/' . $v_proposal->proposals_id, $Currency);
                    $Due_date = str_replace("{DUE_DATE}", display_datetime($v_proposal->due_date), $link);
                    $message = str_replace("{SITE_NAME}", config_item('company_name'), $Due_date);

                    $data['message'] = $message;
                    $message = $this->load->view('email_template', $data, TRUE);
                    $params = array(
                        'recipient' => $v_proposal->email,
                        'subject' => $subject,
                        'message' => $message
                    );

                    $params['resourceed_file'] = '';
                    $this->proposal_model->send_email($params);


                    $data = array('alert_overdue' => '1', 'status' => 'sent', 'emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));

                    $this->proposal_model->_table_name = 'tbl_proposals';
                    $this->proposal_model->_primary_key = 'proposals_id';
                    $this->proposal_model->save($data, $v_proposal->proposals_id);

                    if ($v_proposal->primary_contact) {
                        $notifyUser = array($v_proposal->primary_contact);
                    } else {
                        $user_info = $this->proposal_model->check_by(array('company' => $v_proposal->client_id), 'tbl_account_details');
                        if (!empty($user_info)) {
                            $notifyUser = array($user_info->user_id);
                        }
                    }
                    if (!empty($notifyUser)) {
                        foreach ($notifyUser as $v_user) {
                            if ($v_user != $this->session->userdata('user_id')) {
                                add_notification(array(
                                    'to_user_id' => $v_user,
                                    'icon' => 'shopping-cart',
                                    'description' => 'not_proposal_overdue',
                                    'link' => 'client/proposals/index/proposals_details/' . $v_proposal->proposals_id,
                                    'value' => $v_proposal->reference_no,
                                ));
                            }
                        }
                        show_notification($notifyUser);
                    }

                }
            }
            return TRUE;
        } else {
            log_message('error', 'There are no overdue invoices to send emails');
            return TRUE;
        }

    }

    function expire_estimate()
    {
        $expire_estimate = $this->cron_model->get_overdue('tbl_estimates');

        if (!empty($expire_estimate)) {
            foreach ($expire_estimate as $v_estimate) {
                $currencies = $this->estimates_model->client_currency_sambol($v_estimate->client_id);

                $email_template = $this->cron_model->check_by(array('email_group' => 'estimate_overdue_email'), 'tbl_email_templates');

                $message = $email_template->template_body;

                $subject = $email_template->subject;

                $client_name = str_replace("{CLIENT}", $v_estimate->name, $message);
                $Ref = str_replace("{ESTIMATE_REF}", $v_estimate->reference_no, $client_name);
                $Amount = str_replace("{AMOUNT}", $this->estimates_model->estimate_calculation('estimate_amount', $v_estimate->estimates_id), $Ref);
                $Currency = str_replace("{CURRENCY}", $currencies->symbol, $Amount);

                $link = str_replace("{ESTIMATE_LINK}", base_url() . 'client/estimates/index/estimates_details/' . $v_estimate->estimates_id, $Currency);
                $Due_date = str_replace("{DUE_DATE}", display_datetime($v_estimate->due_date), $link);
                $message = str_replace("{SITE_NAME}", config_item('company_name'), $Due_date);


                $data['message'] = $message;
                $message = $this->load->view('email_template', $data, TRUE);
                $params = array(
                    'recipient' => $v_estimate->email,
                    'subject' => $subject,
                    'message' => $message
                );

                $params['resourceed_file'] = '';
                $this->invoice_model->send_email($params);


                $data = array('alert_overdue' => '1', 'emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));

                $this->estimates_model->_table_name = 'tbl_estimates';
                $this->estimates_model->_primary_key = 'estimates_id';
                $this->estimates_model->save($data, $v_estimate->estimates_id);

                if ($v_estimate->primary_contact) {
                    $notifyUser = array($v_estimate->primary_contact);
                } else {
                    $user_info = $this->estimates_model->check_by(array('company' => $v_estimate->client_id), 'tbl_account_details');
                    if (!empty($user_info)) {
                        $notifyUser = array($user_info->user_id);
                    }
                }
                if (!empty($notifyUser)) {
                    foreach ($notifyUser as $v_user) {
                        if ($v_user != $this->session->userdata('user_id')) {
                            add_notification(array(
                                'to_user_id' => $v_user,
                                'icon' => 'shopping-cart',
                                'description' => 'not_estimate_overdue',
                                'link' => 'client/estimates/index/estimates_details/' . $v_estimate->estimates_id,
                                'value' => $v_estimate->reference_no,
                            ));
                        }
                    }
                    show_notification($notifyUser);
                }

            }
            return TRUE;
        } else {
            log_message('error', 'There are no overdue invoices to send emails');
            return TRUE;
        }

    }

    function invoices_cron()
    {
        $overdue_invoices = $this->cron_model->get_overdue('tbl_invoices');

        if (!empty($overdue_invoices)) {
            foreach ($overdue_invoices as $invoice_info) {

                $email_template = $this->cron_model->check_by(array('email_group' => 'invoice_overdue_email'), 'tbl_email_templates');

                $message = $email_template->template_body;

                $subject = $email_template->subject;

                $client_name = str_replace("{CLIENT}", $invoice_info->name, $message);
                $Ref = str_replace("{REF}", $invoice_info->reference_no, $client_name);
                $Amount = str_replace("{AMOUNT}", $this->invoice_model->calculate_to('invoice_due', $invoice_info->invoices_id), $Ref);
                $Currency = str_replace("{CURRENCY}", $invoice_info->currency, $Amount);
                $Due_date = str_replace("{DUE_DATE}", $invoice_info->due_date, $Currency);
                if (!empty($Due_date)) {
                    $Due_date = $Due_date;
                } else {
                    $Due_date = $Currency;
                }
                $link = str_replace("{INVOICE_LINK}", base_url() . 'client/invoice/manage_invoice/invoice_details/' . $invoice_info->invoices_id, $Due_date);
                $message = str_replace("{SITE_NAME}", config_item('company_name'), $link);

                $data['message'] = $message;
                $message = $this->load->view('email_template', $data, TRUE);
                $params = array(
                    'recipient' => $invoice_info->email,
                    'subject' => $subject,
                    'message' => $message
                );
                $params['resourceed_file'] = '';
                $this->invoice_model->send_email($params);

                $u_invoice['alert_overdue'] = 1;
                update('tbl_invoices', array('invoices_id' => $invoice_info->invoices_id), $u_invoice);

                if ($invoice_info->primary_contact) {
                    $notifyUser = array($invoice_info->primary_contact);
                } else {
                    $user_info = $this->invoice_model->check_by(array('company' => $invoice_info->client_id), 'tbl_account_details');
                    if (!empty($user_info)) {
                        $notifyUser = array($user_info->user_id);
                    }
                }
                if (!empty($notifyUser)) {
                    foreach ($notifyUser as $v_user) {
                        if ($v_user != $this->session->userdata('user_id')) {
                            add_notification(array(
                                'to_user_id' => $v_user,
                                'icon' => 'shopping-cart',
                                'description' => 'not_invoice_overdue',
                                'link' => 'client/estimates/index/estimates_details/' . $invoice_info->invoices_id,
                                'value' => $invoice_info->reference_no,
                            ));
                        }
                    }
                    show_notification($notifyUser);
                }
            }
            return TRUE;
        } else {
            log_message('error', 'There are no overdue invoices to send emails');
            return TRUE;
        }

    }

    public function recurring_invoice()
    {
        // Gather a list of recurring invoices to generate
        $invoices_recurring = $this->cron_model->get_recurring_invoice();
        if (!empty($invoices_recurring)) {
            foreach ($invoices_recurring as $v_r_invoice) {

                // Create the new invoice
                $invoice_date = array(
                    'client_id' => $v_r_invoice->client_id,
                    'due_date' => $this->cron_model->get_date_due($v_r_invoice->recur_next_date),
                    'reference_no' => config_item('invoice_prefix') . ' ' . $this->invoice_model->generate_invoice_number(),
                    'discount' => $v_r_invoice->discount,
                    'tax' => $v_r_invoice->tax,
                    'currency' => $v_r_invoice->currency,
                    'notes' => $v_r_invoice->notes
                );
                $this->invoice_model->_table_name = 'tbl_invoices';
                $this->invoice_model->_primary_key = 'invoices_id';
                $return_id = $this->invoice_model->save($invoice_date);

                // Copy the original invoice to the new invoice
                $this->cron_model->copy_invoice_items($v_r_invoice->invoices_id, $return_id);

                // Update the next recur date for the recurring invoice
                $this->cron_model->set_next_recur_date($v_r_invoice->invoices_id);

                // Email the new invoice if applicable
                if (config_item('send_email_when_recur') == 'TRUE') {

                    $new_invoice = $this->db->where('invoices_id', $return_id)->get('tbl_invoices')->row();
                    $client_info = $this->db->where('client_id', $new_invoice->client_id)->get('tbl_client')->row();

                    $email_template = $this->cron_model->check_by(array('email_group' => 'invoice_message'), 'tbl_email_templates');

                    $message = $email_template->template_body;

                    $subject = $email_template->subject;

                    $ClientName = str_replace("{CLIENT}", $client_info->name, $message);
                    $Amount = str_replace("{AMOUNT}", $this->invoice_model->calculate_to('invoice_due', $new_invoice->invoices_id), $ClientName);
                    $Currency = str_replace("{CURRENCY}", $new_invoice->currency, $Amount);
                    $link = str_replace("{INVOICE_LINK}", base_url() . 'client/invoice/manage_invoice/invoice_details/' . $new_invoice->invoices_id, $Currency);
                    $message = str_replace("{SITE_NAME}", config_item('company_name'), $link);

                    $this->send_email_invoice($new_invoice->invoices_id, $message, $subject); // Email Invoice

                    $data = array('emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));

                    $this->db->where('invoices_id', $new_invoice->invoices_id)->update('tbl_invoices', $data);

                    if (!empty($client_info->primary_contact)) {
                        $notifyUser = array($client_info->primary_contact);
                    } else {
                        $user_info = $this->invoice_model->check_by(array('company' => $client_info->client_id), 'tbl_account_details');
                        if (!empty($user_info)) {
                            $notifyUser = array($user_info->user_id);
                        }
                    }
                    if (!empty($notifyUser)) {
                        foreach ($notifyUser as $v_user) {
                            if ($v_user != $this->session->userdata('user_id')) {
                                add_notification(array(
                                    'to_user_id' => $v_user,
                                    'icon' => 'shopping-cart',
                                    'description' => 'not_invoice_created',
                                    'link' => 'client/invoice/manage_invoice/invoice_details/' . $new_invoice->invoices_id,
                                    'value' => $new_invoice->reference_no,
                                ));
                            }
                        }
                        show_notification($notifyUser);
                    }

                }
            }
        }
        return TRUE;
    }

    function send_email_invoice($invoice_id, $message, $subject)
    {
        $invoice_info = $this->invoice_model->check_by(array('invoices_id' => $invoice_id), 'tbl_invoices');
        $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');
        $recipient = $client_info->email;
        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);
        $params = array(
            'recipient' => $recipient,
            'subject' => $subject,
            'message' => $message
        );
        $params['resourceed_file'] = '';
        $this->invoice_model->send_email($params);


    }

    function projects_cron()
    {
        $overdue_project = $this->cron_model->get_overdue('tbl_project');

        if ($overdue_project) {
            foreach ($overdue_project as $v_project) {


                $email_template = $this->cron_model->check_by(array('email_group' => 'project_overdue_email'), 'tbl_email_templates');

                $message = $email_template->template_body;

                $subject = $email_template->subject;

                $client_name = str_replace("{CLIENT}", $v_project->name, $message);
                $projectName = str_replace("{PROJECT_NAME}", $v_project->project_name, $client_name);
                $Due_date = str_replace("{DUE_DATE}", display_datetime($v_project->end_date), $projectName);
                $link = str_replace("{PROJECT_LINK}", base_url() . 'client/projects/project_details/' . $v_project->project_id, $Due_date);
                $message = str_replace("{SITE_NAME}", config_item('company_name'), $link);

                $data['message'] = $message;
                $message = $this->load->view('email_template', $data, TRUE);
                $params = array(
                    'recipient' => $v_project->email,
                    'subject' => $subject,
                    'message' => $message
                );
                $params['resourceed_file'] = '';
                $this->invoice_model->send_email($params);

                $u_project['alert_overdue'] = 1;
                update('tbl_project', array('project_id' => $v_project->project_id), $u_project);

                if ($v_project->primary_contact) {
                    $notifyUser = array($v_project->primary_contact);
                } else {
                    $user_info = $this->invoice_model->check_by(array('company' => $v_project->client_id), 'tbl_account_details');
                    if (!empty($user_info)) {
                        $notifyUser = array($user_info->user_id);
                    }
                }
                if (!empty($notifyUser)) {
                    foreach ($notifyUser as $v_user) {
                        if ($v_user != $this->session->userdata('user_id')) {
                            add_notification(array(
                                'to_user_id' => $v_user,
                                'icon' => 'folder-open-o',
                                'description' => 'not_project_overdue',
                                'link' => 'client/projects/project_details/' . $v_project->project_id,
                                'value' => $v_project->name,
                            ));
                        }
                    }
                    show_notification($notifyUser);
                }
            }
            return TRUE;
        } else {
            log_message('error', 'There are no overdue projects to send emails');
            return TRUE;
        }
    }

    public function manually()
    {
        $this->index(true);
        redirect('admin/settings/cronjob');
    }
    private function database_delete_backup(){


           $data['backups']= get_result('tbl_db_backup',['created_at < '=>date('Y-m-d H:i:s' ,strtotime('-30 days')),'type'=>'DAILY']);
        
           if($data['backups']){                

               foreach ($data['backups'] as $key => $backups) {
                 
                 if (unlink('./uploads/backup/' . $backups->file)) {
                    $type = 'success';
                    $message = lang('backup_delete_success');
                } else {
                    $type = 'error';
                    $message = lang('backup_error');
                }

                $this->cron_model->_table_name = 'tbl_db_backup';
                $this->cron_model->_primary_key = 'id';
                $this->cron_model->delete($backups->id);
            }
           }
    }


    private function mailboxes($imap,$user_id)
    {
        $mail_Boxes = array();
        $mailBoxes = $imap->getFolders();
        foreach ($mailBoxes as $mb) {
            $res = explode('/', $mb);
            $v1 = $res[0];
            $mail_Boxes[$v1]['main'] = $res[0];
            if (count($res) > 1) {
                $a = $mail_Boxes[$v1]['sub'][$mb] = mb_convert_encoding($res[1], "utf-8", "UTF7-IMAP");
                $search = 'all';
                if (preg_match("/{$search}/i", $a)) {
                    unset($mail_Boxes[$v1]['sub'][$mb]);
                }
            }
        }
        
        if (isset($mail_Boxes['[Gmail]'])) {
            $mail_Boxes['#'] = $mail_Boxes['[Gmail]'];
            unset($mail_Boxes['[Gmail]']);
        }
        $udata = array(
            'smtp_menu' => json_encode($mail_Boxes)
        );
        // $user_id = $user_id;
        $this->db->where('user_id', $user_id);
        $this->db->update('tbl_users', $udata);
        return $mail_Boxes;
    }
    
    
   public function fetch_remote_mails($id='')
    {
        $this->load->helper('admin');

        require_once(module_dirPath(MAILBOX_MODULE) . 'libraries/Imap_zisco.php');
       
        $user_id = $id;
        $user_info = profile($id);
        $now = time();
        $res['success'] = false;
        if(!$user_info->smtp_host_name){
           echo "Please setup mailbox settings!";die;
        }
       // var_dump($user_info);die();
        $imap = $this->imap_connection($user_info);

        if ($imap) {
            $gmailBoxes = $this->mailboxes($imap,$user_id);

            foreach ($gmailBoxes as $k => $f) {
                if ($k != '#') {
                    $this->fetch_mails_2($imap, $user_info, $k);
                  
                }
                if (isset($f['sub'])) {
                    foreach ($f['sub'] as $k2 => $v2) {
                        $this->fetch_mails_2($imap, $user_info, $k2);
                    }
                }
            }
            $now = time();
            $this->db->query("UPDATE tbl_users SET smtp_syn_time = $now WHERE user_id='$user_id'");
             $res['success'] = '1';
        }
        
        echo json_encode($res);
        exit();
    }
    
  private  function imap_connection($user_info)
    {
        $mailbox = $user_info->smtp_host_name;
        $username = $user_info->smtp_username;
        if (empty($username)) {
            $username = $user_info->active_email;
        }
        $password = decrypt($user_info->smtp_password);
        $encryption = $user_info->smtp_encription;
        $imap = new Imap_zisco($mailbox, $username, $password, $encryption);
        if ($imap->isConnected() === false) {
            $activity['module'] = lang('mailbox');
            $activity['activity'] = 'failed_to_connect_import_tickets';
            $activity['value1'] = $user_info->username;
            $activity['value2'] = 'fetch_mails';
            activity_log($activity);
        } else {
            return $imap;
        }
        return false;
    }
    
   private function fetch_mails_2($imap, $user_info, $folder)
    {
        if (!empty($imap)) {
            if (isset($folder)) {
                $tbl = 'tbl_inbox';
                $imap->selectFolder($folder);
                
                if ($user_info->smtp_unread_email == 1 && $folder == 'INBOX') {
                    $emails = $imap->getUnreadMessages(true, $folder);
                } else {
                    $emails = $imap->getMessages(true, $folder);
                    // $emails = $imap->getNewMessages(true, $folder);
                }
              
                $counter = 1;
                $idata = array();
                if ($emails) {
                    // $emails = array_reverse($emails);
                   
                            for ($i = $emails; $i > 0 ; $i--) { 
                                $key = $i;
                                // $email_uid = imap_uid($imap, $i); ;
                    //             echo "<pre>";
                    //         print_r($emails[$i]);die;
                    //         }
                    // foreach ($emails as $key =>$email_uid) {
                        
                        
                        $uid =   $imap->getImap_uid($key);
                        // var_dump($uid );die();
                        $mailbox = $user_info->smtp_host_name;
                        $uid_exist = $this->db->query("SELECT mail_uid as uid FROM tbl_inbox WHERE smtp_host_name ='$mailbox' AND mail_folder='$folder' AND mail_uid = '$uid' AND  user_id = '$user_info->user_id'   LIMIT 1")->row();
                        
                        if (empty($uid_exist)) {

                            $email = $imap->getMessage($key, true);
                            //   echo "<pre>";
                            // print_r($email);die;
                            $counter++;
                            $from = $email['from'];
                            $subject = fix_encoding_chars($email['subject']);
                            // Check if empty body
                            if (isset($email['body']) && $email['body'] == '' || !isset($email['body'])) {
                                $email['body'] = 'No message found';
                            }
                            $body = convert_to_body($email['body']);
                            $system_blocked_subjects = [
                                'Mail delivery failed',
                                'failure notice',
                                'Returned mail: see transcript for details',
                                'Undelivered Mail Returned to Sender',
                            ];
                            
                            $subject_blocked = false;
                            foreach ($system_blocked_subjects as $sb) {
                                if (strpos('x' . $subject, $sb) !== false) {
                                    $subject_blocked = true;
                                    break;
                                }
                            }
                            if ($subject_blocked == true) {
                                return;
                            }
                            $to = trim($user_info->active_email);
                            if (!$to) {
                                $message = $mailstatus = 'No active email is found for fetching emails';
                            } else {
                                if ($to == $from) {
                                    $mailstatus = 'block_potential_email';
                                } else {
                                    $inser_data['from_user_id'] = 0;
                                    $inser_data['user_id'] = $user_info->user_id;
                                    $inser_data['mail_uid'] = $email['uid'];
                                    $inser_data['to'] = $to;
                                    $inser_data['from'] = $from;
                                    $inser_data['subject'] = $subject;
                                    $inser_data['message_body'] = fix_encoding_chars($body);
                                    $inser_data['message_time'] = date('Y-m-d H:i:s',strtotime($email['date']));
                                    $inser_data['mail_folder'] = $folder;
                                    $inser_data['smtp_host_name'] = $mailbox;
                                  
    
                                    $up_data = [];
                                    if (isset($email['attachments'])) {
                                        foreach ($email['attachments'] as $key => $attachment) {
                                            $email_attachment = $imap->getAttachment($email['uid'], $key);
                                            $path = module_dirPath(MAILBOX_MODULE) . "uploads/";
                                            $file_name = unique_filename($path, $attachment['name']);
                                            $path = $path . $file_name;
                                            $is_image = check_image_extension($file_name);
                                            $fp = fopen($path, 'w+');
                                            if (fwrite($fp, $email_attachment['content'])) {
                                                $up_data[] = [
                                                    'fileName' => $file_name,
                                                    "path" => $path,
                                                    "is_image" => $is_image,
                                                    "fullPath" => $path . $file_name,
                                                    "size" => $attachment['size'] * 1024,
                                                ];
                                            }
                                            fclose($fp);
                                        }
                                        $inser_data['attach_file'] = json_encode($up_data);
                                    } else {
                                        $inser_data['attach_file'] = '';
                                    }
                                }
                            }
                              $this->db->insert($tbl, $inser_data);
                              if ($user_info->smtp_delete_mail_after_import == 0) {
                                    $imap->setUnseenMessage($email['uid']);
                                } else {
                                    $imap->deleteMessage($email['uid']);
                                }
                             
                            if ($counter == 3) {
                                break;
                            }
                           
                        }
                    }
                }

                // if (count($idata) > 0) {
                    // $suc = $this->db->insert_batch($tbl, $idata);


                  
                    // if (!empty($suc)) {
                    //     foreach ($idata as $email) {
                    //         if ($user_info->smtp_delete_mail_after_import == 0) {
                    //             $imap->setUnseenMessage($email['uid']);
                    //         } else {
                    //             $imap->deleteMessage($email['uid']);
                    //         }
                    //     }
                    // }
                    
                   
                // }
            }
        }
    }


}

