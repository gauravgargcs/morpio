<?php

class Menu
{

    public function dynamicMenu($is_skotetheme=NULL)
    {
        $CI = &get_instance();
        $designations_id = $CI->session->userdata('designations_id');
        $user_type = $CI->session->userdata('user_type');
        $super_admin = super_admin();
        $companies_id = $CI->session->userdata('companies_id');
        if ($user_type != 1) {// query for employee user role
            $CI->db->select('tbl_user_role.*', FALSE);
            $CI->db->select('tbl_menu.*', FALSE);
            $CI->db->from('tbl_user_role');
            $CI->db->join('tbl_menu', 'tbl_user_role.menu_id = tbl_menu.menu_id', 'left');
            $CI->db->where('tbl_user_role.designations_id', $designations_id);
            if (!empty($companies_id)) {
                if ($CI->db->field_exists('companies_id', 'tbl_menu')) {
                    $CI->db->where('tbl_menu.companies_id', $companies_id);
                }
            }
            $CI->db->where('tbl_menu.status', 1);
            $CI->db->order_by('sort');
            $query_result = $CI->db->get();
            $user_menu = $query_result->result();
        } else { // get all menu for admin
            if (!empty($super_admin)) {
                if (!empty($companies_id)) {
                    $where = array('companies_id' => $companies_id, 'status' => 1);
                } else {
                    $user_menu = get_menu(array('status' => 1, 'companies_id' => null));
                }
            } else {
                $where = array('status' => 1);
            }
            if (!empty($where)) {
                $user_menu = get_order_by('tbl_menu', $where, 'sort', true);
            }
        }
        $subdomain = is_subdomain();
        if (!empty($subdomain)) {
            $except_menu = $this->get_expect_menu();
        } else {
            $except_menu = array();
        }
        // Create a multidimensional array to conatin a list of items and parents
        $menu = array(
            'items' => array(),
            'parents' => array()
        );
        foreach ($user_menu as $v_menu) {
            if (in_array($v_menu->label, $except_menu) == false) {
                $menu['items'][$v_menu->menu_id] = $v_menu;
                $menu['parents'][$v_menu->parent][] = $v_menu->menu_id;
            }
        }
        if($is_skotetheme==1){
            // Builds the array lists with data from the menu table
            return $output = $this->buildMenuSkote(0, $menu);
        }else{
            // Builds the array lists with data from the menu table
            return $output = $this->buildMenu(0, $menu);
        }
       
    }

    function get_expect_menu()
    {
        $except_menu = array();
        if (!empty(available_plan('employee_no'))) {
            array_push($except_menu, 'user');
        }
        if (!empty(available_plan('client_no'))) {
            array_push($except_menu, 'client');
        }
        if (!empty(available_plan('project_no'))) {
            array_push($except_menu, 'projects');
        }
        if (!empty(available_plan('invoice_no'))) {
            array_push($except_menu, 'invoice');
            array_push($except_menu, 'recurring_invoice');
            array_push($except_menu, 'payments_received');
        }
        if (!empty(available_plan('leads'))) {
            array_push($except_menu, 'leads');
        }
        if (!empty(available_plan('tasks'))) {
            array_push($except_menu, 'tasks');
        }
        if (!empty(available_plan('bank_account'))) {
            array_push($except_menu, 'bank_cash');
        }
        if (!empty(available_plan('accounting'))) {
            array_push($except_menu, 'transactions');
            array_push($except_menu, 'expense');
            array_push($except_menu, 'deposit');
            array_push($except_menu, 'transfer');
            array_push($except_menu, 'transactions_report');
            array_push($except_menu, 'balance_sheet');
            array_push($except_menu, 'transfer_report');
        }
        if (!empty(available_plan('stock_manager'))) {
            array_push($except_menu, 'stock');
            array_push($except_menu, 'stock_category');
            array_push($except_menu, 'manage_stock');
            array_push($except_menu, 'assign_stock');
            array_push($except_menu, 'stock_report');
            array_push($except_menu, 'stock_list');
            array_push($except_menu, 'assign_stock');
            array_push($except_menu, 'assign_stock_report');
            array_push($except_menu, 'stock_history');
        }
        if (!empty(available_plan('calendar'))) {
            array_push($except_menu, 'calendar');
        }
        if (!empty(available_plan('mailbox'))) {
            array_push($except_menu, 'mailbox');
        }
        if (!empty(available_plan('live_chat'))) {
            array_push($except_menu, 'private_chat');
        }
        if (!empty(available_plan('tickets'))) {
            array_push($except_menu, 'tickets');
            array_push($except_menu, 'all_tickets');
            array_push($except_menu, 'answered');
            array_push($except_menu, 'open');
            array_push($except_menu, 'in_progress');
            array_push($except_menu, 'closed');
            array_push($except_menu, 'tickets_report');
        }
        if (!empty(available_plan('filemanager'))) {
            array_push($except_menu, 'filemanager');
        }
        if (!empty(available_plan('recruitment'))) {
            array_push($except_menu, 'job_circular');
            array_push($except_menu, 'jobs_posted');
            array_push($except_menu, 'jobs_applications');
        }
        if (!empty(available_plan('attendance'))) {
            array_push($except_menu, 'attendance');
            array_push($except_menu, 'timechange_request');
            array_push($except_menu, 'attendance_report');
            array_push($except_menu, 'time_history');
        }

        if (!empty(available_plan('payroll'))) {
            array_push($except_menu, 'payroll');
            array_push($except_menu, 'manage_salary_details');
            array_push($except_menu, 'employee_salary_list');
            array_push($except_menu, 'make_payment');
            array_push($except_menu, 'generate_payslip');
            array_push($except_menu, 'salary_template');
            array_push($except_menu, 'hourly_rate');
            array_push($except_menu, 'payroll_summary');
            array_push($except_menu, 'provident_fund');
            array_push($except_menu, 'advance_salary');
        }
        if (!empty(available_plan('leave_management'))) {
            array_push($except_menu, 'leave_management');
            array_push($except_menu, 'leave_category');
        }
        if (!empty(available_plan('performance'))) {
            array_push($except_menu, 'performance');
            array_push($except_menu, 'performance_indicator');
            array_push($except_menu, 'performance_report');
            array_push($except_menu, 'give_appraisal');
        }
        if (!empty(available_plan('training'))) {
            array_push($except_menu, 'training');
        }
        $all_module = get_old_data('tbl_modules', array('active' => 1, 'module_name !=' => 'mailbox'), true);
        if (!empty($all_module)) {
            foreach ($all_module as $v_module) {
                if (!empty(available_plan($v_module->module_name))) {
                    array_push($except_menu, $v_module->module_name);
                }
            }
        }
        if (!empty(available_plan('reports'))) {
            array_push($except_menu, 'transactions_report');
            array_push($except_menu, 'report');
            array_push($except_menu, 'account_statement');
            array_push($except_menu, 'income_report');
            array_push($except_menu, 'expense_report');
            array_push($except_menu, 'income_expense');
            array_push($except_menu, 'date_wise_report');
            array_push($except_menu, 'all_income');
            array_push($except_menu, 'all_expense');
            array_push($except_menu, 'all_transaction');
            array_push($except_menu, 'transfer_report');
            array_push($except_menu, 'report_by_month');
            array_push($except_menu, 'tasks_report');
            array_push($except_menu, 'bugs_report');
            array_push($except_menu, 'tickets_report');
            array_push($except_menu, 'client_report');
            array_push($except_menu, 'tasks_assignment');
            array_push($except_menu, 'bugs_assignment');
            array_push($except_menu, 'project_report');
            array_push($except_menu, 'stock_report');
            array_push($except_menu, 'assign_stock_report');
            array_push($except_menu, 'performance_report');
            array_push($except_menu, 'attendance_report');
            array_push($except_menu, 'sales_report');
        }
        return $except_menu;
    }

    public function clientMenu()
    {
        $CI = &get_instance();
        $user_id = $CI->session->userdata('user_id');
        $CI->db->select('tbl_client_role.*', FALSE);
        $CI->db->select('tbl_client_menu.*', FALSE);
        $CI->db->from('tbl_client_role');
        $CI->db->join('tbl_client_menu', 'tbl_client_role.menu_id = tbl_client_menu.menu_id', 'left');
        $CI->db->where('tbl_client_role.user_id', $user_id);
        $companies_id = $CI->session->userdata('companies_id');
        if (!empty($companies_id)) {
            if ($CI->db->field_exists('companies_id', 'tbl_client_menu')) {
                $CI->db->where('tbl_client_menu.companies_id', $companies_id);
            }
        }
        $CI->db->order_by('sort');
        $query_result = $CI->db->get();
        $client_menu = $query_result->result();

        // Create a multidimensional array to conatin a list of items and parents
        $menu = array(
            'items' => array(),
            'parents' => array()
        );
        foreach ($client_menu as $v_menu) {
            $menu['items'][$v_menu->menu_id] = $v_menu;
            $menu['parents'][$v_menu->parent][] = $v_menu->menu_id;
        }

        // Builds the array lists with data from the menu table
        return $output = $this->buildMenu(0, $menu);
    }

    public function buildMenu($parent, $menu, $sub = NULL)
    {
        $html = "";
        $class = '';
         $dadge = null;
         $terget = null;
        if (isset($menu['parents'][$parent])) {
            if (!empty($sub)) {
                $html .= "<ul id=" . $sub . " class='nav myUL sidebar-subnav collapse'><li class=\"sidebar-subnav-header\">" . lang($sub) . "</li>\n";
                  if( $menu['items'][$parent]->link != 'javascript://'  && $menu['items'][$parent]->link != '#' &&  !$menu['items'][$parent]->link != '#' ){
                     $result = $this->active_menu_id($menu['items'][$parent]->menu_id);
                     if ($result) {
                        $active = 'active';
                    } else {
                        $active = '';
                    }
                     $html .= "<li class='" . $active . $class . "' >\n  <a $terget title='" . lang($menu['items'][$parent]->label) . "' href='" . base_url() . $menu['items'][$parent]->link . "'>\n <em class='" . $menu['items'][$parent]->icon . "'></em><span>" . lang('all').' '.lang($menu['items'][$parent]->label) . "</span>" . $dadge . "</a>\n</li> \n";
                }
            } else {
                $html .= "<ul class='nav myUL'>\n";
            }
            foreach ($menu['parents'][$parent] as $itemId) {
                $class = null;
                $dadge = null;
                $result = $this->active_menu_id($menu['items'][$itemId]->menu_id);
                if ($result) {
                    $active = 'active';
                } else {
                    $active = '';
                }
                if ($menu['items'][$itemId]->link == 'knowledgebase') {
                    $terget = 'target="_blank"';
                } else {
                    $terget = null;
                }
                if (!isset($menu['parents'][$itemId])) { //if condition is false only view menu
                    $html .= "<li class='" . $active . $class . "' >\n  <a $terget title='" . lang($menu['items'][$itemId]->label) . "' href='" . base_url() . $menu['items'][$itemId]->link . "'>\n <em class='" . $menu['items'][$itemId]->icon . "'></em><span>" . lang($menu['items'][$itemId]->label) . "</span>" . $dadge . "</a>\n</li> \n";
                }
                if (isset($menu['parents'][$itemId])) { //if condition is true show with submenu
                    $html .= "<li class='sub-menu " . $active . $class . "'>\n  <a data-toggle='collapse' href='#" . $menu['items'][$itemId]->label . "'> <em class='" . $menu['items'][$itemId]->icon . "'></em><span>" . lang($menu['items'][$itemId]->label) . "</span>" . $dadge . "</a>\n";
                    $html .= self::buildMenu($itemId, $menu, $menu['items'][$itemId]->label);
                    $html .= "</li> \n";
                }
            }
            $html .= "</ul> \n";
        }
        return $html;
    }

    public function buildMenuSkote($parent, $menu, $sub = NULL)
    {
        $html = "";
        $class = "";
        $dadge = "";
         $terget = null;
        if (isset($menu['parents'][$parent])) {
            if (!empty($sub)) {
                $html .= "<ul class=\"sub-menu\" id=" . $sub . " aria-expanded=\"false\">";
                 if( $menu['items'][$parent]->link != 'javascript://'  && $menu['items'][$parent]->link != '#' &&  !$menu['items'][$parent]->link != '#' ){
                     $result = $this->active_menu_id($menu['items'][$parent]->menu_id);
                     if ($result) {
                        $active = 'active';
                    } else {
                        $active = '';
                    }
                   $html .= "<li class='" . $active . $class . "' >\n  <a $terget title='" . lang($menu['items'][$parent]->label) . "' href='" . base_url() . $menu['items'][$parent]->link . "'>\n <i class='" . $menu['items'][$parent]->icon . "'></i><span>" . lang('all').' '.lang($menu['items'][$parent]->label) . "</span></a>\n</li> \n";
                }
            } else {
                $html .= "";
            }
            foreach ($menu['parents'][$parent] as $itemId) {
                $class = null;
                $dadge = null;
                $result = $this->active_menu_id($menu['items'][$itemId]->menu_id);
                if ($result) {
                    $active = 'active';
                } else {
                    $active = '';
                }
                if ($menu['items'][$itemId]->link == 'knowledgebase') {
                    $terget = 'target="_blank"';
                } else {
                    $terget = null;
                }

                if (!isset($menu['parents'][$itemId])) { //if condition is false only view menu
                    $html .= "<li class='" . $active . $class . "' >\n  <a $terget title='" . lang($menu['items'][$itemId]->label) . "' href='" . base_url() . $menu['items'][$itemId]->link . "'>\n <i class='" . $menu['items'][$itemId]->icon . "'></i><span>" . lang($menu['items'][$itemId]->label) . "</span>" . $dadge . "</a>\n</li> \n";
                }
                if (isset($menu['parents'][$itemId])) { //if condition is true show with submenu
                    $html .= "<li class='" . $active . $class . "'>\n  <a href='javascript://' class='has-arrow waves-effect '> <i class='" . $menu['items'][$itemId]->icon . "'></i><span>" . lang($menu['items'][$itemId]->label) . "</span>" . $dadge . "</a>\n";
                    $html .= self::buildMenuSkote($itemId, $menu, $menu['items'][$itemId]->label);
                    $html .= "</ul> \n";
                }
            }
            $html .= "\n";
        }
        return $html;
    }

    public function active_menu_id($id)
    {
        $CI = &get_instance();
        $activeId = $CI->session->userdata('menu_active_id');
        if (!empty($activeId)) {
            foreach ($activeId as $v_activeId) {
                if ($id == $v_activeId) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

}
