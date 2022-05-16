<?php

/**
 * Description of Admin_Controller
 *
 * @author pc mart ltd
 */
class Admin_Controller extends MY_Controller
{
    private $_current_version;

    function __construct()
    {
        parent::__construct();
        $this->load->model('global_model');
        $this->load->model('admin_model');
        $this->_current_version = $this->admin_model->get_current_db_version();
        if ($this->admin_model->is_db_upgrade_required($this->_current_version) && !$this->input->post('auto_update')) {
            if ($this->input->post('upgrade_database')) {
                $this->admin_model->upgrade_database();
            }
            include_once(APPPATH . 'views/admin/settings/db_update_required.php');
            die;
        }
        if (strpos($this->uri->uri_string(), 'login') === FALSE && !$this->input->is_ajax_request()) {
            $this->session->set_userdata(array(
                'url' => $this->uri->uri_string()
            ));
        }

        $companies_id = $this->session->userdata('companies_id');
        $super_admin = super_admin();
        if (empty($super_admin) && empty($companies_id)) {
            $plan_info = get_active_subs();
            if (!empty($plan_info) && $plan_info->maintenance_mode != 'Yes') {
                $running_plan = get_running_plan();
                if (!empty($running_plan)) {
                    if (!empty($running_plan['trial'])) {
                        $trial_period = $running_plan['trial'];
                    } else {
                        $trial_period = $running_plan['running'];
                    }
                    if (is_numeric($trial_period)) {
                        if ($trial_period <= 0) {
                            redirect('checkout');
                        }
                    }
                }
            } else {
                $my_plan = get_my_subs();
                if (!empty($my_plan)) {
                    if ($my_plan->maintenance_mode == 'Yes' || $my_plan->status == 'suspended' || $my_plan->status == 'terminated') {
                        $account_status = $my_plan->status;
                        if ($my_plan->maintenance_mode == 'Yes') {
                            $maintenance_message = $my_plan->maintenance_mode_message;
                        }
                        include_once(APPPATH . 'views/default/admin/settings/domain_error.php');
                        die();
                    }
                } elseif (get_url(config_item('default_url')) != get_url(guess_base_url())) {
                    if (!empty($this->session->userdata('user_id'))) {
                        redirect('NewPlan/');
                    }
                }
            }
        }
  
        //get all navigation data
        $all_menu = result_by_company('tbl_menu');

        $_SESSION['user_roll'] = $all_menu;
        //get user id from session
        $designations_id = $this->session->userdata('designations_id');
        $this->global_model->_table_name = 'tbl_user_role'; //table name
        $this->global_model->_order_by = 'user_role_id';
        // get user navigation by user id
        $user_menu = $this->global_model->select_user_roll($designations_id);

        $user_type = $this->session->userdata('user_type');

        if ($user_type != 1) {
            $restricted_link = array();
            foreach ($all_menu as $data1) {
                $duplicate = false;
                foreach ($user_menu as $data2) {
                    if ($data1->menu_id === $data2->menu_id) {
                        $duplicate = true;
                    }
                }
                if ($duplicate === false) {
                    $restricted_link[] = $data1->link;
                }
            }
            $exception_uris = $restricted_link;
        } else {
            $exception_uris = array();
        }
        $subdomain = is_subdomain();
        if (!empty($subdomain)) {
            $except_menu = $this->expect_menu_url();
            if (!empty($except_menu)) {
                $exception_uris = array_merge($exception_uris, $except_menu);
            }
        }
        $user_flag = $this->session->userdata('user_flag');
        if (!empty($user_flag)) {
            if ($user_flag != '1') {
                $url = $this->session->userdata('url');
                redirect($url);
            }
        } else {
            redirect('locked');
        }

        $uri = null;
        $a = $this->uri->segment(1) . '/' . $this->uri->segment(2);
        if ($a != 'admin/settings') {
            for ($i = 1; $i <= $this->uri->total_segments(); $i++) {
                $uri .= $this->uri->segment($i) . '/';
                $result = rtrim($uri, '/');
                $key = array_search('admin/dashboard', $exception_uris);
                unset($exception_uris[$key]);
                if (in_array($result, $exception_uris) == true) {
                    set_message('error', lang('there_in_no_value'));
                    redirect('admin/dashboard');
                }
            }
        }

    }

    function expect_menu_url()
    {
        $except_menu = array();
        if (!empty(available_plan('employee_no'))) {
            array_push($except_menu, 'admin/user');
        }
        if (!empty(available_plan('client_no'))) {
            array_push($except_menu, 'admin/client');
        }
        if (!empty(available_plan('project_no'))) {
            array_push($except_menu, 'admin/projects');
        }
        if (!empty(available_plan('invoice_no'))) {
            array_push($except_menu, 'admin/invoice');
        }
        if (!empty(available_plan('leads'))) {
            array_push($except_menu, 'admin/leads');
        }
        if (!empty(available_plan('accounting'))) {
            array_push($except_menu, 'admin/transactions');
        }
        if (!empty(available_plan('bank_account'))) {
            array_push($except_menu, 'admin/account');
        }
        if (!empty(available_plan('tasks'))) {
            array_push($except_menu, 'admin/tasks');
        }
        if (!empty(available_plan('calendar'))) {
            array_push($except_menu, 'admin/calendar');
        }
        if (!empty(available_plan('mailbox'))) {
            array_push($except_menu, 'admin/mailbox');
        }
        if (!empty(available_plan('live_chat'))) {
            array_push($except_menu, 'chat/conversations');
        }
        if (!empty(available_plan('tickets'))) {
            array_push($except_menu, 'admin/tickets');
            array_push($except_menu, 'admin/report/tickets_report');
        }
        if (!empty(available_plan('filemanager'))) {
            array_push($except_menu, 'admin/filemanager');
        }
        if (!empty(available_plan('stock_manager'))) {
            array_push($except_menu, 'admin/stock');
        }
        if (!empty(available_plan('recruitment'))) {
            array_push($except_menu, 'admin/job_circular/');
        }
        if (!empty(available_plan('attendance'))) {
            array_push($except_menu, 'admin/attendance/');
        }

        if (!empty(available_plan('payroll'))) {
            array_push($except_menu, 'admin/payroll/');
        }
        if (!empty(available_plan('leave_management'))) {
            array_push($except_menu, 'admin/leave_management');
        }
        if (!empty(available_plan('performance'))) {
            array_push($except_menu, 'admin/performance/');
        }
        if (!empty(available_plan('training'))) {
            array_push($except_menu, 'admin/training');
        }
        if (!empty(available_plan('reports'))) {
            array_push($except_menu, 'admin/report/');
        }
        $all_module = get_old_data('tbl_modules', array('active' => 1, 'module_name !=' => 'mailbox'), true);
        if (!empty($all_module)) {
            foreach ($all_module as $v_module) {
                if (!empty(available_plan($v_module->module_name))) {
                    array_push($except_menu, 'admin/' . $v_module->module_name);
                }
            }
        }
        if (!empty(available_plan('reports'))) {
            array_push($except_menu, 'admin/report/');
        }
        return $except_menu;
    }
}
