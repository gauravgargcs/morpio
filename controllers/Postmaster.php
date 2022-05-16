<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Postmaster extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
           
        require APPPATH . "third_party/Imap_zisco.php";
        
        $this->load->model('mailbox_model');
        $this->load->model('tickets_model');
        $this->load->model('department_model');
        $this->load->helper('string');
        $this->load->helper('file');
        $this->load->library('email');

    }

    function index()
    {       
        ini_set("max_execution_time",360);

        $this->department_model->_table_name = "tbl_departments"; //table name
        $this->department_model->_order_by = "departments_id";
        $all_dept_info = $this->department_model->get();
        foreach ($all_dept_info as $v_dept_info) {
            $config['dept_host'] = $v_dept_info->cp_email_domain;
            $config['dept_email'] = $v_dept_info->cp_email_user;
            $config['dept_password'] = $v_dept_info->cp_email_password;
            $config['departments_id'] = $v_dept_info->departments_id;
            $config['companies_id'] = $v_dept_info->companies_id;
            $now = time();
            if(!empty($config['dept_email']) && !empty($config['dept_host'])){
                $imap = $this->imap_connection($config);
                if ($imap) {
                    
                    $this->fetch_mails($imap, $config);
                    
                    $where = array('config_key' => 'last_postmaster_run');
                    $data = array('value' => $now);
                    $this->db->where($where)->update('tbl_config', $data);
                    $exists = $this->db->where($where)->get('tbl_config');
                    if ($exists->num_rows() == 0) {
                        $this->db->insert('tbl_config', array("config_key" => 'last_postmaster_run', "value" => $now));
                    }

                }
                


            }
            
        }
    }

    function imap_connection($config)
    {
        $hostname = 'mail.'.$config['dept_host'];
        $username = $config['dept_email'].'@'.$config['dept_host'];
        $password = decrypt($config['dept_password']);
        $encryption = 'ssl';
    
        /* try to connect */
        $imap = new Imap_zisco($hostname, $username, $password, $encryption);
        if ($imap->isConnected() === false) {
            $activity['module'] = lang('tickets');
            $activity['activity'] = 'failed_to_connect_import_tickets';
            $activity['value1'] = $username;
            $activity['value2'] = 'fetch_mails';
            activity_log($activity);
        } else {
            return $imap;
        }
        return false;
    }
    
    function fetch_mails($imap, $config)
    {
        if (!empty($imap)) {
            /* grab emails */
            $emails = $imap->getEmails('ALL');
        
            /* if emails are returned, cycle through each… */
            if($emails) {

                $output = '';

                /* for every email… */
                foreach($emails as $email) {
                    // echo '<pre>';
                    // var_dump($email); die;
                    $uid = $email['uid'];
                    $email_date = $email['date'];
                    $mailbox = 'mail.'.$config['dept_host'];
                    $uid_exist = $this->db->query("SELECT mail_uid as uid FROM tbl_tickets WHERE config_host ='$mailbox' AND mail_folder='INBOX' AND mail_uid = '$uid' LIMIT 1")->row();
                    $user_id=0;
                    $idata = array();
                    if (empty($uid_exist)) {
                        $from = $email['from'];
                        $user_where = array('email' => $from['email']);
                        $get_user_info = $this->db->where('email', $from['email'])->get('tbl_users')->row();
                        
                        $companies_id=NULL;
                        if(!empty($get_user_info)){
                            $user_id=$get_user_info->user_id;
                        }
                        $subject = fix_encoding_chars($email['subject']);
                        // Check if empty body
                        if (isset($email['body']) && $email['body'] == '' || !isset($email['body'])) {
                            $email['body'] = 'No message found';
                        }
                        $body = convert_to_body($email['body']);
                        $departments_id=$config['departments_id'];
                        $companies_id=$config['companies_id'];

                        $designation = $this->db->where('departments_id', $departments_id)->get('tbl_designations')->row();
                        if (!empty($designation)) {
                            $profile_info = $this->db->select('user_id')->where('designations_id', $designation->designations_id)->get('tbl_account_details')->result();
                            $assigned_to=$assigned_to_arr=array();
                            foreach ($profile_info as $p => $p_user_id) {
                                $assigned_to_users=$p_user_id->user_id;
                                array_push($assigned_to_arr,$assigned_to_users);
                            }
                            $assigned_to['assigned_to']=$assigned_to_arr;
                            $action_arr=array('edit','delete','view');
                            if (!empty($assigned_to['assigned_to'])) {
                                foreach ($assigned_to['assigned_to'] as $assign_user) {
                                    $assigned[$assign_user] = $action_arr;
                                }
                            }
                            $permission = json_encode($assigned);
                        }else{
                            $permission ='all';
                        }
                        $idata[$uid]['departments_id'] = $departments_id;
                        $idata[$uid]['reporter'] = $user_id;
                        $idata[$uid]['status'] = 'open';
                        $idata[$uid]['priority'] = 'Medium';
                        $idata[$uid]['companies_id'] = $companies_id;
                        $idata[$uid]['mail_uid'] = $email['uid'];
                        $idata[$uid]['ticket_code'] = strtoupper(random_string('alnum', 7));
                        $idata[$uid]['permission'] = $permission;
                        $idata[$uid]['subject'] = $subject;
                        $idata[$uid]['body'] = fix_encoding_chars($body);
                        $idata[$uid]['created'] = date('Y-m-d H:i:s',strtotime($email_date));
                        $idata[$uid]['mail_folder'] = 'INBOX';
                        $idata[$uid]['config_host'] = $mailbox;
                        
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
                            $idata[$uid]['upload_file'] = json_encode($up_data);
                        } else {
                            $idata[$uid]['upload_file'] = '';
                        }

                        if (count($idata) > 0) {
                            $suc = $this->db->insert_batch('tbl_tickets', $idata);
                            if (!empty($suc)) {
                               $activities = array(
                                    'user' => $user_id,
                                    'module' => 'tickets',
                                    'module_field_id' => 0,
                                    'activity' => 'activity_fetch_ticket_emails',
                                    'icon' => 'fa-mail',
                                    'value1' => key(array_slice($idata, 0, 1, TRUE)),
                                    'value2' => key(array_slice($idata, -1, 1, TRUE)),
                                );
                                $this->mailbox_model->_table_name = "tbl_activities"; //table name
                                $this->mailbox_model->_primary_key = "activities_id";
                                $this->mailbox_model->save($activities);
                            } 
                        }
                    }
                } //end of for loop
                
            } //end of if statement

            /* close the connection */
            $imap->imap_close();

        }
    }
    
}
