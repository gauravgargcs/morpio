<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Frontend extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('account_model');
       
    }

    public function home()
    {
         $super_admin = super_admin();
        if (empty($super_admin)) {
            redirect('login');
        }
        // redirect('admin/dashboard');
        $data['title'] = lang('Frontend');
        $data['subview'] = $this->load->view('admin/frontend/home', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public function subscriber()
    {
         $super_admin = super_admin();
        if (empty($super_admin)) {
            redirect('login');
        }
        $data['title'] = lang('Frontend');
        $data['subview'] = $this->load->view('admin/frontend/subscriber', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public function subscriptions()
    {
         $super_admin = super_admin();
        if (empty($super_admin)) {
            redirect('login');
        }
        $data['title'] = lang('Frontend');
        $data['subview'] = $this->load->view('admin/frontend/subscriptions', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
        
        // $this->output->enable_profiler(TRUE);


    }
     public function tenant()
    {
         $super_admin = super_admin();
         $is_admin = admin();
        if (empty($super_admin) && empty($is_admin)) {
            redirect('login');
        }
        $data['title'] = lang('Manage Tenant');
        $data['subview'] = $this->load->view('admin/frontend/tenant', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
        

        // $this->output->enable_profiler(TRUE);
        // $this->output->enable_profiler(TRUE);

    }
    public function multiple_tenant_change_industry()
    {

        $industry = $this->input->post('industry') ;
      
        

        $tenant_id = $this->input->post('tenant_id') ;
        
            // var_dump($tenant_id);die;
        if($tenant_id){
            foreach ($tenant_id as $key => $subscription_id) {
                // code...
         
                $data['industry_type'] = $industry;
            
            $this->account_model->_table_name = "tbl_subscriptions"; // table name
            $this->account_model->_primary_key = "subscriptions_id"; // $id
            $id = $this->account_model->save($data, $subscription_id);
            $activity = 'activity_update_tenant_industry';
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'subscriptions',
                'module_field_id' => $subscriptions_id,
                'activity' => $activity,
                'icon' => 'fa-tasks',
                'link' => 'admin/frontend/tenant' ,
                'value1' => $industry,
            );
            // Update into tbl_project
                $this->account_model->_table_name = "tbl_activities"; //table name
                $this->account_model->_primary_key = "activities_id";
                $this->account_model->save($activities);
                // messages for user
                $type = "success";
                $message = lang('changed_industry');
                // set_message($type, $message);
                
           }
           $data_return['success'] = true;
                $data_return['message'] = $message;
               echo json_encode($data_return);
              exit();
        }
            
       

    }

    public function subscriptions_details($id)
    {
         $super_admin = super_admin();
        if (empty($super_admin)) {
            redirect('login');
        }
        $data['title'] = lang('Frontend');
        $data['subscription_info'] = get_result('tbl_subscriptions', array('subscriptions_id' => $id),'row');
        $data['subview'] = $this->load->view('admin/frontend/subscriptions_details', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }
      public function get_access_for_admin($id)
    {
        $super_admin = super_admin();
         $is_admin = admin();
        if (empty($super_admin) && empty($is_admin)) {
            redirect('login');
        }
        $subscription_info = get_result('tbl_subscriptions', array('subscriptions_id' => $id),'row');
           
        if (!empty($subscription_info) && $subscription_info->status != 'pending') {
            $login_data['activation_period'] = config_item('email_activation_expire') / 3600;
            if($request_key = $this->send_token_access_email($subscription_info));{

             redirect($request_key);
            }
        }
        redirect('admin/frontend/subscriptions');
    }
    private function send_token_access_email($subs_info){
        $activation_period = config_item('email_activation_expire') / 3600;
        $sub_domain = (isset($_SERVER['HTTPS']) ? "https://" : "http://");
        $sub_domain .= $subs_info->domain . '.';
        $sub_domain .= $_SERVER['HTTP_HOST'];
     
         $this->new_db = new_database($subs_info);
             if(!$this->new_db ){
                return false;
             }
             $this->load->library('uuid');
              
            $admin = $this->new_db->where(['super_admin'=>'yes'])->order_by('user_id','DESC')->get('tbl_users')->row();
            // var_dump($admin);die;
             $acc_access_request = 1;
            $super_admin = super_admin();
            if($super_admin ){
               $acc_access_request = 2; 
            }
         $data = array(
            'acc_access_request' =>$acc_access_request,
            'acc_access_request_key' => $this->uuid->v4(),
            'acc_access_request_updated_at' =>date('Y-m-d H:i:s') );
         $this->new_db->where( 'user_id', $admin->user_id)->update('tbl_users',$data);

        $approve_access_link =  $sub_domain.'/login/approve_access/'. $data['acc_access_request_key'];
        $message =   'Hello Admin,<br/> main tentn admin just requested for login please approved by below link <br/> ' .$approve_access_link ;

        $params['recipient'] = $subs_info->email;
        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . 'Admin access request';
        $params['message'] = $message;
        $params['resourceed_file'] = '';
        $this->account_model->send_email($params);
        return $return_url=$sub_domain.'/login/get_access/'. $admin->user_id;
    }


    public function send_activation_token($id)
    {
        $super_admin = super_admin();
        
        if (empty($super_admin) ) {
            redirect('login');
        }
        $subscription_info = get_row('tbl_subscriptions', array('subscriptions_id' => $id));
        if (!empty($subscription_info) && $subscription_info->status == 'pending') {
            $login_data['activation_period'] = config_item('email_activation_expire') / 3600;
            $this->send_token_activation_email($subscription_info);
             set_message('success', lang('send_token_activation_email_success'));
        }
        redirect('admin/frontend/subscriptions');
    }



    function send_token_activation_email($subs_info)
    {
        $activation_period = config_item('email_activation_expire') / 3600;
        $sub_domain = (isset($_SERVER['HTTPS']) ? "https://" : "http://");
        $sub_domain .= $subs_info->domain . '.';
        $sub_domain .= $_SERVER['HTTP_HOST'];
        $sub_domain .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        $email_template = $this->account_model->check_by(array('email_group' => 'token_activate_account'), 'tbl_email_templates');

        $activatation_token = str_replace("{ACTIVATION_TOKEN}", $subs_info->activation_tocken, $email_template->template_body);
        $activate_url = str_replace("{ACTIVATE_URL}", $sub_domain, $activatation_token);
        $activate_period = str_replace("{ACTIVATION_PERIOD}", $activation_period, $activate_url);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $activate_period);

        $params['recipient'] = $subs_info->email;
        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $email_template->subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';
        $this->account_model->send_email($params);
    }

    public function update_sub_validity($id)
    {
        $validity = date('Y-m-d H-i', strtotime($this->input->post('validity', true)));
        $status = $this->input->post('status', true);
        $data = $this->total_count_date($id, $validity, $status);
        if (empty($data['status'])) {
            $data['status'] = $status;
        }
        $data['maintenance_mode'] = ($this->input->post('maintenance_mode', true) == 'on') ? 'Yes' : 'No';
        $data['remarks'] = $this->input->post('remarks', true);
        $data['maintenance_mode_message'] = $this->input->post('maintenance_mode_message', true);

        $this->account_model->_table_name = "tbl_subscriptions"; // table name
        $this->account_model->_primary_key = "subscriptions_id"; // $id
        $this->account_model->save($data, $id);

        set_message('success', lang('update_settings'));
        redirect('admin/frontend/subscriptions_details/' . $id);
    }

    function total_count_date($id, $date, $status)
    {
 $super_admin = super_admin();
        if (empty($super_admin)) {
            redirect('login');
        }
        $sub_info = get_row('tbl_subscriptions', array('subscriptions_id' => $id));
        $time = date('Y-m-d', strtotime($sub_info->created_date));
        $to_date = strtotime($time); //past date.
        $cur_date = strtotime($date);
        $timeleft = $cur_date - $to_date;
        $daysleft = round((($timeleft / 24) / 60) / 60);
        if ($date > $time && $status == 'expired') {
            $data['status'] = 'running';
        }
        if ($sub_info->trial_period != 0) {
            $data['trial_period'] = $daysleft;
            $data['is_trial'] = 'Yes';
        } else {
            $data['trial_period'] = 0;
            $data['is_trial'] = 'No';
            $data['expired_date'] = date("Y-m-d", strtotime($daysleft . "day"));
        }
        return $data;
    }

    public function delete_subscriptions($id)
    {
         $super_admin = super_admin();
        if (empty($super_admin)) {
            redirect('login');
        }
        $sub_info = get_result('tbl_subscriptions', array('subscriptions_id' => $id),'row');
        if (!empty($sub_info)) {
            if ($sub_info->db_id != 0) {
                $this->deleteDatabase($sub_info);
            }
            // *********** Delete into tbl_salary_allowance *******************
            $subscriptions_history = get_result('tbl_subscriptions_history', array('subscriptions_id' => $id));
            if (!empty($subscriptions_history)) {
                foreach ($subscriptions_history as $v_history) {
                    // *********** Delete into tbl_salary_allowance *******************
                    $this->account_model->_table_name = "tbl_subscription_payment"; // table name
                    $this->account_model->delete_multiple(array('subscriptions_history_id' => $v_history->id));
                }
            }
            $this->account_model->_table_name = "tbl_subscriptions_history"; // table name
            $this->account_model->delete_multiple(array('subscriptions_id' => $id));

            // *********** Delete into tbl_salary_allowance *******************
            $this->account_model->_table_name = "tbl_subscriptions_database"; // table name
            $this->account_model->delete_multiple(array('id' => $sub_info->db_id));

            // *********** Delete into tbl_salary_template *******************
            $this->account_model->_table_name = "tbl_subscriptions"; // table name
            $this->account_model->_primary_key = "subscriptions_id"; // $id
            $this->account_model->delete($id);

            $type = "success";
            echo json_encode(array("status" => $type, 'message' => lang('information_successfully_deleted')));
            exit();
        }
    }

    function deleteDatabase($sub_info)
    {
         $super_admin = super_admin();
        if (empty($super_admin)) {
            redirect('login');
        }
        $db_info = get_row('tbl_subscriptions_database', array('id' => $sub_info->db_id));
        if (config_item('saas_server') === 'cpanel') {
            include_once(APPPATH . 'libraries/Xmlapi.php');
            $cpanel_password = decrypt(config_item('saas_cpanel_password'));
            $xmlapi = new xmlapi(config_item('saas_cpanel_host'));
            $xmlapi->set_port(config_item('saas_cpanel_port'));
            $xmlapi->password_auth(config_item('saas_cpanel_username'), $cpanel_password);
            $xmlapi->set_debug(0);
            $output = config_item('saas_cpanel_output');
            $xmlapi->set_output($output);
            $cpaneluser = config_item('saas_cpanel_username');
            $databasename = $db_info->database_name;
            $result = $xmlapi->api1_query($cpaneluser, 'Mysql', 'deldb', array($databasename));
        }
        $this->myforge = $this->load->dbforge($db_info->database_name, TRUE);
        if ($this->myforge->drop_database($db_info->database_name)) {
            return true;
        } else {
            $this->new_db = new_database($sub_info);
            if (!empty($this->new_db)) {
                $tables = $this->new_db->list_tables();
                foreach ($tables as $table) {
                    $this->myforge->drop_table($table);
                }
            }
            return true;
        }
        return true;
    }

    public function subscriber_action($type, $id)
    {
 $super_admin = super_admin();
        if (empty($super_admin)) {
            redirect('login');
        }
        $data['status'] = $type;

        $this->account_model->_table_name = "tbl_subscribers"; // table name
        $this->account_model->_primary_key = "subscribers_id"; // $id
        $this->account_model->save($data, $id);

        set_message('success', lang('update_settings'));
        redirect('admin/frontend/subscriber/' . $id);
    }


    public function save_menu($d_id = null, $flag = null)
    {
         $super_admin = super_admin();
        if (empty($super_admin)) {
            redirect('login');
        }
        $report_menu_id = json_decode($this->input->post('frontend_menu', true));
        if (!empty($report_menu_id)) {
            foreach ($report_menu_id as $mrkey => $r_id) {
                $rdata['sort'] = $mrkey + 1;
                $this->account_model->_table_name = 'tbl_frontend_menu';
                $this->account_model->_primary_key = 'menu_id';
                $this->account_model->save($rdata, $r_id);
            }
        }
        if (!empty($d_id)) {
            $where = array('menu_id' => $d_id);
            $action = array('status' => $flag);
            $this->account_model->set_action($where, $action, 'tbl_frontend_menu');
        }
        $type = "success";
        $message = lang('update_settings');
        echo json_encode(array('status' => $type, 'message' => $message));
        exit();
    }

    public function new_slider($id = null)
    {
        $data['title'] = lang('new_slider');
        /// edit and update get employee award info
        if (!empty($id)) {
            $data['slider_info'] = $this->account_model->check_by(array('id' => $id), 'tbl_frontend_slider');
        }
        $data['subview'] = $this->load->view('admin/frontend/new_slider', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }

    public function save_slider($id = null)
    {
        $data = $this->account_model->array_from_post(array('title', 'description', 'button_one', 'button_one_link', 'button_two', 'button_two_link', 'button_two_link', 'status'));

        if (!empty($_FILES['slider']['name'])) {
            $val = $this->account_model->uploadImage('slider');
            $val == TRUE || redirect('admin/frontend/home');
            $data['slider'] = $val['path'];
        }

        $this->account_model->_table_name = "tbl_frontend_slider"; // table name
        $this->account_model->_primary_key = "id"; // $id
        $this->account_model->save($data, $id);

        set_message('success', lang('update_settings'));
        redirect('admin/frontend/home');
    }

    function change_status($tbl, $id = null, $status = null)
    {
        if (!empty($id)) {
            $where = array('id' => $id);
            $action = array('status' => $status);
            $this->account_model->set_action($where, $action, $tbl);
        }
        $type = "success";
        $message = lang('update_settings');
        echo json_encode(array('status' => $type, 'message' => $message));
        exit();
    }

    public function features($id = NULL)
    {
         redirect('admin/dashboard');
        $data['title'] = lang('Frontend');
        if ($id) {
            $data['active'] = 2;
            $can_edit = $this->account_model->can_action('tbl_accounts', 'edit', array('account_id' => $id));
            $edited = can_action('36', 'edited');
            if (!empty($can_edit) && !empty($edited)) {
                $data['account_info'] = $this->account_model->check_by(array('account_id' => $id), 'tbl_accounts');
            }
        } else {
            $data['active'] = 1;
        }
        $data['subview'] = $this->load->view('admin/frontend/home', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public function new_features($id = null)
    {
        $data['title'] = lang('new_slider');
        /// edit and update get employee award info
        if (!empty($id)) {
            if (is_numeric($id)) {
                $data['features_info'] = $this->account_model->check_by(array('id' => $id), 'tbl_frontend_features');
            } else {
                $data['type'] = $id;
            }
        }
        $data['subview'] = $this->load->view('admin/frontend/new_features', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }

    public function save_features($id = null)
    {
        $features_id = json_decode($this->input->post('features', true));
        if (!empty($features_id)) {
            foreach ($features_id as $mrkey => $r_id) {
                $rdata['sort'] = $mrkey + 1;
                $this->account_model->_table_name = 'tbl_frontend_features';
                $this->account_model->_primary_key = 'id';
                $this->account_model->save($rdata, $r_id);
            }
        }
        $data = $this->account_model->array_from_post(array('title', 'description', 'icon', 'status'));
        if (!empty($data['title'])) {
            $type = $this->input->post('type', true);
            if (!empty($type)) {
                $data['type'] = $type;
            }
            if (!empty($_FILES['icon']['name'])) {
                $val = $this->account_model->uploadImage('icon');
                $val == TRUE || redirect('admin/frontend/home');
                $data['icon'] = $val['path'];
            }
            $this->account_model->_table_name = "tbl_frontend_features"; // table name
            $this->account_model->_primary_key = "id"; // $id
            $this->account_model->save($data, $id);
        }
        set_message('success', lang('update_settings'));
        redirect('admin/frontend/home');
    }

    public function delete($tbl, $id)
    {
        $field = 'id';
        if ($tbl == 'tbl_subscribers') {
            $field = 'subscribers_id';
        }
        $this->account_model->_table_name = $tbl;
        $this->account_model->_primary_key = $field;
        $this->account_model->delete($id);
        if($tbl=="tbl_frontend_pricing"){
            $joined_table="tbl_currencywise_price";
            $joined_where=array('frontend_pricing_id'=>$id);
            $this->account_model->_table_name = $joined_table;
            $this->account_model->delete_multiple($joined_where);
        }
        $type = "success";
        //            $message = $msg;
        //            set_message($type, $message);
        echo json_encode(array("status" => $type, 'message' => 'deleted'));
        exit();
        //        redirect('admin/account/manage_account');
    }

    public function pricing($id = null)
    {
        $data['title'] = lang('pricing');
        if (!empty($id)) {
            $data['active'] = 2;
            $data['pricing_info'] = $this->account_model->check_by(array('id' => $id), 'tbl_frontend_pricing');
        } else {
            $data['active'] = 1;
        }
        $data['subview'] = $this->load->view('admin/frontend/pricing', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data);
    }
     public function pricing_add($id = null)
    {
        $data['title'] = lang('pricing');
        if (!empty($id)) {
            $data['active'] = 2;
            $data['pricing_info'] = $this->account_model->check_by(array('id' => $id), 'tbl_frontend_pricing');
        } else {
            $data['active'] = 1;
        }
        $data['subview'] = $this->load->view('admin/frontend/pricing_add', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data);
    }


    public function coupon($id = null)
    {
        $data['title'] = lang('coupon');
        if (!empty($id)) {
            $data['active'] = 2;
            $data['coupon_info'] = $this->account_model->check_by(array('id' => $id), 'tbl_frontend_coupon');
        } else {
            $data['active'] = 1;
        }
        $data['subview'] = $this->load->view('admin/frontend/coupon', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data);
    }
     public function coupon_add($id = null)
    {
        $data['title'] = lang('coupon');
        if (!empty($id)) {
            $data['active'] = 2;
            $data['coupon_info'] = $this->account_model->check_by(array('id' => $id), 'tbl_frontend_coupon');
        } else {
            $data['active'] = 1;
        }
        $data['subview'] = $this->load->view('admin/frontend/coupon_add', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data);
    }

    public function resources($id = null)
    {
        $data['title'] = lang('resources');
        $data['subview'] = $this->load->view('admin/frontend/resources', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data);
    }

    public function save_pricing($id = null)
    {

        $features_id = json_decode($this->input->post('pricing', true));
        if (!empty($features_id)) {
            foreach ($features_id as $mrkey => $r_id) {
                if (!empty($r_id)) {
                    $rdata['sort'] = $mrkey + 1;
                    $this->account_model->_table_name = 'tbl_frontend_pricing';
                    $this->account_model->_primary_key = 'id';
                    $this->account_model->save($rdata, $r_id);
                }
            }
        }

        $data = $this->account_model->array_from_post(array(
            'name', 'amount',
            'employee_no', 'client_no', 'leads', 'accounting', 'bank_account', 'tasks', 'project_no', 'disk_space', 'invoice_no', 'trial_period', 'description', 'status', 'recommended'
        ));

        $data['multi_branch'] = ($this->input->post('multi_branch') == 'Yes') ? 'Yes' : 'No';
        $data['online_payment'] = ($this->input->post('online_payment') == 'Yes') ? 'Yes' : 'No';
        $data['calendar'] = ($this->input->post('calendar') == 'Yes') ? 'Yes' : 'No';
        $data['mailbox'] = ($this->input->post('mailbox') == 'Yes') ? 'Yes' : 'No';
        $data['live_chat'] = ($this->input->post('live_chat') == 'Yes') ? 'Yes' : 'No';
        $data['tickets'] = ($this->input->post('tickets') == 'Yes') ? 'Yes' : 'No';
        $data['filemanager'] = ($this->input->post('filemanager') == 'Yes') ? 'Yes' : 'No';
        $data['recruitment'] = ($this->input->post('recruitment') == 'Yes') ? 'Yes' : 'No';
        $data['attendance'] = ($this->input->post('attendance') == 'Yes') ? 'Yes' : 'No';
        $data['payroll'] = ($this->input->post('payroll') == 'Yes') ? 'Yes' : 'No';
        $data['leave_management'] = ($this->input->post('leave_management') == 'Yes') ? 'Yes' : 'No';
        $data['performance'] = ($this->input->post('performance') == 'Yes') ? 'Yes' : 'No';
        $data['training'] = ($this->input->post('training') == 'Yes') ? 'Yes' : 'No';
        $data['reports'] = ($this->input->post('reports') == 'Yes') ? 'Yes' : 'No';
        $data['allow_paypal'] = ($this->input->post('allow_paypal') == 'Yes') ? 'Yes' : 'No';
        $data['allow_stripe'] = ($this->input->post('allow_stripe') == 'Yes') ? 'Yes' : 'No';
        $data['allow_2checkout'] = ($this->input->post('allow_2checkout') == 'Yes') ? 'Yes' : 'No';
        $data['allow_authorize'] = ($this->input->post('allow_authorize') == 'Yes') ? 'Yes' : 'No';
        $data['allow_ccavenue'] = ($this->input->post('allow_ccavenue') == 'Yes') ? 'Yes' : 'No';
        $data['allow_braintree'] = ($this->input->post('allow_braintree') == 'Yes') ? 'Yes' : 'No';
        $data['allow_mollie'] = ($this->input->post('allow_mollie') == 'Yes') ? 'Yes' : 'No';
        $data['allow_payumoney'] = ($this->input->post('allow_payumoney') == 'Yes') ? 'Yes' : 'No';
        
        $all_module = get_old_data('tbl_modules', array('active' => 1, 'module_name !=' => 'mailbox'), true);
        if (!empty($all_module)) {
            foreach ($all_module as $v_module) {
                $name = 'allow_' . $v_module->module_name;
                $data[$name] = ($this->input->post($name, true) == 'Yes') ? 'Yes' : 'No';
            }
        }
        
        $this->account_model->_table_name = "tbl_frontend_pricing"; // table name
        $this->account_model->_primary_key = "id"; // $id
        if (!empty($id)) { // if id exist in db update data
            $check_id = array('id !=' => $id);
        } else { // if id is not exist then set id as null
            $check_id = null;
        }
        $where = array('recommended' => 'Yes');
        $already_recommended = $this->account_model->check_update('tbl_frontend_pricing', $where, $check_id);
        if (!empty($already_recommended)) {
            foreach ($already_recommended as $v_recommended) {
                $udata['recommended'] = 'No';
                $this->account_model->save($udata, $v_recommended->id);
            }
        }
        if (empty($data['recommended'])) {
            $data['recommended'] = 'No';
        }

        if (!empty($data['name'])) {
            if (empty($data['status']) && $data['status'] != 0) {
                $data['status'] = 1;
            }
            $id = $this->account_model->save($data, $id);
        }
        if (!empty($id)) {
            $this->account_model->_table_name = 'tbl_currencywise_price';
            $this->account_model->_primary_key = 'currencywise_price_id';
            $this->account_model->delete_multiple(array('frontend_pricing_id' => $id));
        }

        $currency = $this->input->post('currency', true);
        $monthly = $this->input->post('monthly', true);
        $quarterly = $this->input->post('quarterly', true);
        $yearly = $this->input->post('yearly', true);

        foreach ($currency as $key => $cur) {
            if ( $monthly[$key]!=='' || $quarterly[$key]!=='' || $yearly[$key]!=='' ) {
                $cr_data['frontend_pricing_id'] = $id;
                $cr_data['currency'] = $cur;
                $cr_data['monthly'] = $monthly[$key];
                $cr_data['quarterly'] = $quarterly[$key];
                $cr_data['yearly'] = $yearly[$key];

                $this->account_model->save($cr_data);
            }
        }
        set_message('success', lang('update_settings'));
        redirect('admin/frontend/pricing');
    }

    public
    function save_coupon($id = null)
    {
        $data = $this->account_model->array_from_post(array('name', 'code', 'amount', 'type', 'end_date', 'pricing_id', 'show_on_pricing'));
        if(!empty($data['end_date'])){
            $data['end_date']=date('Y-m-d H:i', strtotime($data['end_date']));
        }

        if (!empty($id)) { // if id exist in db update data
            $check_id = array('id !=' => $id);
        } else { // if id is not exist then set id as null
            $check_id = null;
        }
        if (!empty($data['pricing_id'])) {
            $where = array('pricing_id' => $data['pricing_id'], 'show_on_pricing' => 'Yes');
            $already_show = $this->account_model->check_update('tbl_frontend_coupon', $where, $check_id);
            $this->account_model->_table_name = "tbl_frontend_coupon"; // table name
            $this->account_model->_primary_key = "id"; // $id
            if (!empty($already_show)) {
                foreach ($already_show as $v_show) {
                    $udata['show_on_pricing'] = 'No';
                    $this->account_model->save($udata, $v_show->id);
                }
            }
        } else {
            $data['pricing_id'] = 0;
        }
        if (empty($data['show_on_pricing'])) {
            $data['show_on_pricing'] = 'No';
        }
        if (!empty($data['name'])) {
            if (empty($data['status'])) {
                $data['status'] = 1;
            }
            $this->account_model->_table_name = "tbl_frontend_coupon"; // table name
            $this->account_model->_primary_key = "id"; // $id
            $this->account_model->save($data, $id);
        }
        set_message('success', lang('update_settings'));
        redirect('admin/frontend/coupon');
    }

    function saved_config()
    {
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }
        }
        set_message('success', lang('update_settings'));
        redirect('admin/frontend/home');
    }

    function saved_config_image()
    {
        if (!empty($_FILES['fully_management_system']['name'])) {
            $val = $this->account_model->uploadImage('fully_management_system');
            $val == TRUE || redirect('admin/frontend/home');
            $input_data['fully_management_system'] = $val['path'];
        }
        if (!empty($input_data)) {
            foreach ($input_data as $key => $value) {
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }
        }
        set_message('success', lang('update_settings'));
        redirect('admin/frontend/home');
    }

    public function new_ratings($id = null)
    {
        $data['title'] = lang('new_ratings');
        /// edit and update get employee award info
        if (!empty($id)) {
            $data['ratings_info'] = $this->account_model->check_by(array('id' => $id), 'tbl_customer_ratings');
        }
        $data['subview'] = $this->load->view('admin/frontend/new_ratings', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }


    public
    function save_ratings($id = null)
    {

        $data = $this->account_model->array_from_post(array('name', 'position', 'description', 'ratings', 'status'));

        if (!empty($data['name'])) {

            if (!empty($_FILES['image']['name'])) {
                $val = $this->account_model->uploadImage('image');
                $val == TRUE || redirect('admin/frontend/home');
                $data['image'] = $val['path'];
            }
            $this->account_model->_table_name = "tbl_customer_ratings"; // table name
            $this->account_model->_primary_key = "id"; // $id
            $this->account_model->save($data, $id);
        }
        set_message('success', lang('update_settings'));
        redirect('admin/frontend/home');
    }

    public function quote_request()
    {
        $data['title'] = lang('quote_request');
        $data['subview'] = $this->load->view('admin/frontend/quote_request', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data);
    }

    public function partners($id = null)
    {
        $data['title'] = lang('partners');
        if (!empty($id)) {
            $data['active'] = 2;
            $data['partner_info'] = $this->account_model->check_by(array('id' => $id), 'tbl_partners');
        } else {
            $data['active'] = 1;
        }
        $data['subview'] = $this->load->view('admin/frontend/partners', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data);
    }

    public
    function save_partners($id = null)
    {
        $data = $this->account_model->array_from_post(array('name', 'designation', 'facebook', 'twitter', 'linkedin', 'dribbble', 'description'));
        if (!empty($data['name'])) {
            if (!empty($_FILES['profile']['name'])) {
                $val = $this->account_model->uploadImage('profile');
                $val == TRUE || redirect('admin/frontend/partners');
                $data['profile'] = $val['path'];
            }
            $data['type'] = 'partners';
            if (empty($data['status'])) {
                $data['status'] = 1;
            }
            $this->account_model->_table_name = "tbl_partners"; // table name
            $this->account_model->_primary_key = "id"; // $id
            $this->account_model->save($data, $id);
        }
        set_message('success', lang('update_settings'));
        redirect('admin/frontend/partners');
    }

    public function team($id = null)
    {
        $data['title'] = lang('our_team');
        if (!empty($id)) {
            $data['active'] = 2;
            $data['partner_info'] = $this->account_model->check_by(array('id' => $id), 'tbl_partners');
        } else {
            $data['active'] = 1;
        }
        $data['subview'] = $this->load->view('admin/frontend/team', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data);
    }

    public
    function save_team($id = null)
    {
        $data = $this->account_model->array_from_post(array('name', 'type', 'designation', 'facebook', 'twitter', 'linkedin', 'dribbble', 'description'));
        if (!empty($data['name'])) {
            if (!empty($_FILES['profile']['name'])) {
                $val = $this->account_model->uploadImage('profile');
                $val == TRUE || redirect('admin/frontend/team');
                $data['profile'] = $val['path'];
            }
            if (empty($data['status'])) {
                $data['status'] = 1;
            }
            $this->account_model->_table_name = "tbl_partners"; // table name
            $this->account_model->_primary_key = "id"; // $id
            $this->account_model->save($data, $id);
        }
        set_message('success', lang('update_settings'));
        redirect('admin/frontend/team');
    }
    public function import_db($id = null){
        $super_admin = super_admin();
        if (empty($super_admin)) {
            redirect('login');
        }
        $subscription_info = get_row('tbl_subscriptions', array('subscriptions_id' => $id));
        if (!empty($subscription_info)) {
            if ($subscription_info->db_id != 0) {
                $sub_db_info = get_row('tbl_subscriptions_database', array('id' => $subscription_info->db_id));
                if (!empty($sub_db_info)) {
                    if ($sub_db_info->database_name != '') {
                        $server_result = $this->createCPanelDatabase($subscription_info);

                        ini_set('max_execution_time', 30000);
                        $database_name=$server_result['database_name'];
                        $create_db_result=$server_result['create_db'];
                        $db_error=convert_to_html($create_db_result->data->result);
                        if(!empty($db_error)){
                            $type = 'error';
                            $message = $db_error;
                            set_message($type, $message);
                        }else{
                            update("tbl_subscriptions_database",array('id' => $sub_db_info->id), array('database_name' => $database_name));

                            $this->load->helper('file');
                            $this->load->helper('unzip');
                            $this->load->database();

                            $config['upload_path'] = './uploads/temp/';
                            $config['allowed_types'] = '*';
                            $config['max_size'] = '9000';
                            $config['overwrite'] = TRUE;

                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);

                            if (!$this->upload->do_upload('upload_file')) {
                                $error = $this->upload->display_errors('', ' ');
                                $type = 'error';
                                $message = $error;
                                set_message($type, $message);
                            } else {
                                $data = array('upload_data' => $this->upload->data());
                                $backup = "uploads/temp/" . $data['upload_data']['file_name'];

                                if (!unzip($backup, "uploads/temp/", true, true)) {
                                    $type = 'error';
                                    $message = lang('backup_restore_error');
                                } else {
                                    $servername = config_item('database_hostname');
                                    $username = config_item('database_username');
                                    $password = config_item('database_password');
                                    // Create connection using musqli_connect function
                                    $conn = mysqli_connect($servername, $username, $password, $database_name);

                                    // Connection Check
                                    if (!$conn) {
                                        $type = 'error';
                                        $message = "Connection failed: " . $conn->connect_error;
                                    }else{
                                        $za = new ZipArchive(); 

                                        $za->open($backup); 

                                        for( $i = 0; $i < $za->numFiles; $i++ ){ 
                                            $stat = $za->statIndex( $i ); 
                                            $backup="uploads/temp/" . basename( $stat['name'] ); 
                                            // $backup = str_replace('.zip', '.sql', $backup);
                                            $file_content = file_get_contents($backup);
                                            $conn->query('USE ' . $database_name . ';');
                                            foreach (explode(";", $file_content) as $sql) {
                                                $sql = trim($sql);
                                                if ($sql) {
                                                    $conn->query($sql);
                                                }
                                            }
                                        }
                                        unlink($backup . ".sql");
                                        unlink($backup . ".zip");
                                        $conn -> close();

                                        $type = 'success';
                                        $message = lang('backup_restore_success');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } 
        set_message($type, $message);
        redirect($_SERVER["HTTP_REFERER"]);
        //redirect('admin/frontend/subscriptions_details/'.$id); 
    }

    /**
     * Create cPanel database
     *
     * @param Instance $instance
     * @return void
     */
    private function createCPanelDatabase($subscription_info)
    {
        include_once(APPPATH . 'libraries/Xmlapi.php');
        $cpanel_password = decrypt(config_item('saas_cpanel_password'));
        $db_username = $this->db->username;
        $xmlapi = new xmlapi(config_item('saas_cpanel_host'));
        $xmlapi->set_port(config_item('saas_cpanel_port'));
        $xmlapi->password_auth(config_item('saas_cpanel_username'), $cpanel_password);
        $xmlapi->set_debug(1);
        $output = config_item('saas_cpanel_output');
        $xmlapi->set_output($output);
        $cpaneluser = config_item('saas_cpanel_username');
        if ($output == 'json') {
            $cpaneluser_short = config_item('saas_cpanel_username');
        } else {
            $cpaneluser_short = substr(config_item('saas_cpanel_username'), 0, 8);
        }
        $databasename = $subscription_info->domain . '_' . $subscription_info->subscriptions_id;
        $database_name = $cpaneluser_short . "_" . $databasename;
        $delete_db = $xmlapi->api1_query($cpaneluser, "Mysql", "deldb", array($database_name));
        $create_db = $xmlapi->api1_query($cpaneluser, "Mysql", "adddb", array($databasename));
        if ($output == 'json') {
            $create_db = json_decode($create_db);
        }
       
        //DROP DATABASE databasename;
        $assign_permission = $xmlapi->api1_query($cpaneluser, 'Mysql', 'adduserdb', array('' . $databasename . '', '' . $db_username . '', 'all'));
        if ($output == 'json') {
            $assign_permission = json_decode($assign_permission);
        }
        if ($assign_permission->error) {
            return [
                'error' => $create_db->error
            ];
        }
        return [
            'database_name' => $database_name,
            'assign_permission'=>$assign_permission,
            'create_db'=>$create_db

        ];
    }


    // public function service($id = null)
    // {
    //     $data['title'] = lang('service');
    //     if (!empty($id)) {
    //         $data['active'] = 2;
    //         $data['service_info'] = $this->account_model->check_by(array('id' => $id), 'tbl_services');
    //     } else {
    //         $data['active'] = 1;
    //     }
    //     $data['subview'] = $this->load->view('admin/frontend/service', $data, TRUE);
    //     $this->load->view('admin/_layout_skote_main', $data);
    // }

    // public
    // function save_service($id = null)
    // {
    //     $data = $this->account_model->array_from_post(array('title', 'description', 'status'));
    //     if (!empty($data['title'])) {

    //         if (empty($data['status'])) {
    //             $data['status'] = 1;
    //         }
    //         $this->account_model->_table_name = "tbl_services"; // table name
    //         $this->account_model->_primary_key = "id"; // $id
    //         $this->account_model->save($data, $id);
    //     }
    //     set_message('success', lang('update_settings'));
    //     redirect('admin/frontend/service');
    // }
}
