<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Global_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('global_model');
        $this->load->model('admin_model');
    }

    public function fetch_address_info_gmaps()
    {
        include_once(APPPATH . 'third_party/JD_Geocoder_Request.php');
        $data = $this->input->post();
        $address = '';
        $address .= $data['address'];
        if (!empty($data['city'])) {
            $address .= ', ' . $data['city'];
        }
        if (!empty($data['country'])) {
            $address .= ', ' . $data['country'];
        }
        $georequest = new JD_Geocoder_Request();
        $georequest->forwardSearch($address);
        echo json_encode($georequest);
        exit();
    }

    public function get_project_by_client_id($client_id)
    {
        $html = null;
        $client_project_info = get_result('tbl_project', array('client_id' => $client_id));
        if (!empty($client_project_info)) {
            $html .= "<option value='" . 0 . "'>" . lang('none') . "</option>";
            foreach ($client_project_info as $v_client_project) {
                $html .= "<option value='" . $v_client_project->project_id . "'>" . $v_client_project->project_name . "</option>";
            }
        }
        echo $html;
        exit();
    }

    public function items_suggestions($limit = null)
    {
        if (empty($limit)) {
            $limit = 18;
            $class = 'select_pos_item';
        } else {
            $class = 'select_item';
        }
        $term = $this->input->post('term', true);
        $rows = $this->admin_model->getItemsInfo($term, $limit);
        $html = null;
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $html .= '<div class="col-2 p0 m0" style=""> <a class="' . $class . ' btn p0" data-item-id="' . $row->saved_items_id . '"><img class="rounded-circle avatar-xs" src="' . product_image($row->saved_items_id) . '" style="height: 70px;width: 70px"> <div class="text-primary text"> <small style="white-space: pre-wrap;">' . $row->item_name . ' ' . $row->code . '</small></div></a></div>';
            }
            echo $html;
        }
        exit();
    }


    function suggestions($id = NULL)
    {
        if (!empty($id)) {
            $row = $this->admin_model->check_by(array('saved_items_id' => $id), 'tbl_saved_items');
            $row->qty = 1;
            $row->rate = $row->unit_cost;
            $row->unit = $row->unit_type;
            $row->new_itmes_id = $row->saved_items_id;
            $tax_info = json_decode($row->tax_rates_id);
            if (!empty($tax_info)) {
                foreach ($tax_info as $tax_id) {
                    $tax = $this->db->where('tax_rates_id', $tax_id)->get('tbl_tax_rates')->row();
                    if (!empty($tax->tax_rate_name)) {
                        $tax_name[] = $tax->tax_rate_name . '|' . $tax->tax_rate_percent;
                    }
                }
                $tax = (object)[
                    'taxname' => (!empty($tax_name) ? ($tax_name) : null),
                ];
            }
            if (empty($tax)) {
                $tax = (object)[
                    'taxname' => '',
                ];
            }
            $result = (object)array_merge((array)$row, (array)$tax);
            $pr = array('saved_items_id' => str_replace(".", "", microtime(true)), 'saved_items_id' => $row->saved_items_id, 'label' => $row->item_name . " (" . $row->code . ")", 'row' => $result);
            echo json_encode($pr);
            die();
        }
        $term = $this->input->get('term', TRUE);
        $rows = $this->admin_model->getItemsInfo($term);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $row->qty = 1;
                $row->rate = $row->unit_cost;
                $row->unit = $row->unit_type;
                $row->new_itmes_id = $row->saved_items_id;
                $tax_info = json_decode($row->tax_rates_id);
                if (!empty($tax_info)) {
                    foreach ($tax_info as $tax_id) {
                        $tax = $this->db->where('tax_rates_id', $tax_id)->get('tbl_tax_rates')->row();
                        if (!empty($tax->tax_rate_name)) {
                            $tax_name[] = $tax->tax_rate_name . '|' . $tax->tax_rate_percent;
                        }
                    }
                    $tax = (object)[
                        'taxname' => (!empty($tax_name) ? ($tax_name) : null),
                    ];
                }
                if (empty($tax)) {
                    $tax = (object)[
                        'taxname' => '',
                    ];
                }
                $result = (object)array_merge((array)$row, (array)$tax);
                $pr[] = array('saved_items_id' => $row->saved_items_id, 'label' => $row->item_name . " (" . $row->code . ")", 'row' => $result);
            }
            echo json_encode($pr);
            die();
        } else {
            echo json_encode(array(array('saved_items_id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
            die();
        }
    }

    public
    function json_by_company($tbl, $companies_id, $status = null, $value = null)
    {
        $html = null;
        if (!empty($status)) {
            $where = array($status => $value, 'companies_id' => $companies_id);
        } else {
            $where = array('companies_id' => $companies_id);
        }
        $result_info = $this->db->where($where)->get($tbl)->result();
        echo json_encode($result_info);
        exit();
    }

    function json_get_employee($companies_id)
    {
        $all_employee = array();
        $all_employee = $this->admin_model->get_all_employee($companies_id);
        echo json_encode($all_employee);
        exit();
    }

    function json_get_department($companies_id)
    {
        $all_department_info = array();
        // get all department info and designation info
        $data['all_dept_info'] = by_company('tbl_departments', 'departments_id', null, $companies_id);
        $this->load->model('department_model');
        // get all department info and designation info
        if (!empty($data['all_dept_info'])) {
            foreach ($data['all_dept_info'] as $v_dept_info) {
                $all_department_info[$v_dept_info->deptname] = $this->department_model->get_add_department_by_id($v_dept_info->departments_id);
            }
        }
        echo json_encode($all_department_info);
        exit();
    }
    function get_client_menu($companies_id)
    {
        $client_menu = array();
        $login_info = array();
        $client_menu = by_company('tbl_client_menu', 'menu_id', null, $companies_id);

        $data['client_menu'] = $this->load->view('admin/user/client_menu', array('all_client_menu' => $client_menu), array('login_info' => $login_info));

        echo json_encode($data);
        exit();
    }

    public function get_milestone_by_project_id($project_id)
    {
        $milestone_info = get_result('tbl_milestones', array('project_id' => $project_id));
        $HTML = null;
        if (!empty($milestone_info)) {
            $HTML .= "<option value='" . 0 . "'>" . lang('none') . "</option>";
            foreach ($milestone_info as $v_milestone) {
                $HTML .= "<option value='" . $v_milestone->milestones_id . "'>" . $v_milestone->milestone_name . "</option>";
            }
        }
        echo $HTML;
        exit();
    }

    public
    function get_related_moduleName_by_value($val, $proposal = null)
    {
        if ($val == 'project') {
            $all_project_info = $this->admin_model->get_permission('tbl_project');
            $HTML = null;
            if ($all_project_info) {
                $HTML .= '<div class="col-md-6"><select onchange="get_milestone_by_id(this.value)" name="' . $val . '_id" id="related_to"  class="form-control select_box" >';
                foreach ($all_project_info as $v_project) {
                    $HTML .= "<option value='" . $v_project->project_id . "'>" . $v_project->project_name . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
            exit();
        } elseif ($val == 'opportunities') {
            $HTML = null;
            $all_opp_info = $this->admin_model->get_permission('tbl_opportunities');
            if ($all_opp_info) {
                $HTML .= '<div class="col-xl-7"><select name="' . $val . '_id" id="related_to"  class="form-control select_box">';
                foreach ($all_opp_info as $v_opp) {
                    $HTML .= "<option value='" . $v_opp->opportunities_id . "'>" . $v_opp->opportunity_name . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
            exit();
        } elseif ($val == 'leads') {
            $all_leads_info = $this->admin_model->get_permission('tbl_leads');
            $HTML = null;
            if ($all_leads_info) {


                $HTML .= '<div class="col-lg-7 col-md-7 col-sm-7"><select name="' . $val . '_id" id="related_to"  class="form-control select_box">';

                foreach ($all_leads_info as $v_leads) {
                    $HTML .= "<option value='" . $v_leads->leads_id . "'>" . $v_leads->contact_name . "</option>";
                }
                $HTML .= '</select></div></div>';
                if (!empty($proposal)) {

                    $HTML .= '<div class="row mb-3 leads_module" ><label class="col-xl-5 col-form-label col-md-5 col-sm-5">' . lang("currency") . '</label><div class="col-lg-7 col-md-7 col-sm-7"><select name="currency" class="form-control select_box">';

                    $all_currency = $this->db->get('tbl_currencies')->result();
                    foreach ($all_currency as $v_currency) {
                        $HTML .= "<option " . (config_item('default_currency') == $v_currency->code ? ' selected="selected"' : '') . " value='" . $v_currency->code . "'>" . $v_currency->name . "</option>";
                    }
                    $HTML .= '</select></div></div>';
                }
            }
            echo $HTML;
            exit();
        } elseif ($val == 'client') {
            $all_client_info = get_result('tbl_client');
            $HTML = null;
            if ($all_client_info) {

                $HTML .= ' <div class="col-lg-7 col-md-7 col-sm-7"><select name="' . $val . '_id" id="related_to"  class="form-control select_box">';

                foreach ($all_client_info as $v_client) {
                    $HTML .= "<option value='" . $v_client->client_id . "'>" . $v_client->name . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
            exit();
        } elseif ($val == 'bug') {
            $all_bugs_info = $this->admin_model->get_permission('tbl_bug');
            $HTML = null;
            if ($all_bugs_info) {

                $HTML .= '<div class="col-xl-7"><select name="' . $val . '_id" id="related_to"  class="form-control select_box">';
                foreach ($all_bugs_info as $v_bugs) {
                    $HTML .= "<option value='" . $v_bugs->bug_id . "'>" . $v_bugs->bug_title . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
            exit();
        } elseif ($val == 'goal') {
            $all_goal_info = $this->admin_model->get_permission('tbl_goal_tracking');
            $HTML = null;
            if ($all_goal_info) {

                $HTML .= '<div class="col-xl-7"><select name="' . $val . '_tracking_id" id="related_to"  class="form-control select_box">';
                foreach ($all_goal_info as $v_goal) {
                    $HTML .= "<option value='" . $v_goal->goal_tracking_id . "'>" . $v_goal->subject . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
            exit();
        } elseif ($val == 'sub_task') {
            $all_task_info = $this->admin_model->get_permission('tbl_task');
            $HTML = null;
            if ($all_task_info) {

                $HTML .= '<div class="col-xl-7"><select name="' . $val . '_id" id="related_to"  class="form-control select_box">';
                foreach ($all_task_info as $v_task) {
                    $HTML .= "<option value='" . $v_task->task_id . "'>" . $v_task->task_name . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
            exit();
        }
    }

    public
    function check_current_password()
    {
        $old_password = $this->input->post('name', true);
        if (!empty($old_password)) {
            if (!empty($old_password)) {
                $password = $this->hash($old_password);
            }
            $check_dupliaction_id = $this->admin_model->check_by(array('user_id' => my_id(), 'password' => $password), 'tbl_users');
            if (empty($check_dupliaction_id)) {
                $result['error'] = lang("password_does_not_match");
            } else {
                $result['success'] = 1;
                $row = $this->input->post('row', true);
                $encrypt_password = $this->input->post('encrypt_password', true);
                if (!empty($encrypt_password)) {
                    if (!empty($row)) {
                        $result['password'] = ($encrypt_password);
                    } else {
                        $result['password'] = decrypt($encrypt_password);
                    }
                }
            }
            echo json_encode($result);
            exit();
        }
    }

    public
    function check_existing_user_name($user_id = null)
    {
        $username = $this->input->post('name', true);
        if (!empty($username)) {
            $check_user_name = $this->admin_model->check_user_name($username, $user_id);
            if (!empty($check_user_name)) {
                $result['error'] = lang("name_already_exist");
            } else {
                $result['success'] = 1;
            }
            echo json_encode($result);
            exit();
        }
    }

    public
    function check_existing_domain()
    {
        $domain = $this->input->post('name', true);
        if (!empty($domain)) {
            $check_domain = $this->admin_model->check_by(array('domain' => $domain), 'tbl_subscriptions');
            if (!empty($check_domain)) {
                $result['error'] = lang("domain_already_taken");
            } else {
                $result['success'] = 1;
            }
            echo json_encode($result);
            exit();
        }
    }

    public
    function check_existing_subscription_email()
    {
        $email = $this->input->post('name', true);
        if (!empty($email)) {
            $check_email = $this->admin_model->check_by(array('email' => $email), 'tbl_subscriptions');
            $check_email_address = $this->admin_model->check_by(array('email' => $email), 'tbl_users');
            if (!empty($check_email) || !empty($check_email_address)) {
                $result['error'] = lang("this_email_already_exist");
                if (!empty($check_email_address)) {
                    $result['error'] = lang("this_email_already_exist_someone_already_login");
                }
            } else {
                $result['success'] = 1;
            }
            echo json_encode($result);
            exit();
        }
    }


    public
    function check_duplicate_emp_id($user_id = null)
    {
        $employment_id = $this->input->post('name', true);
        if (!empty($employment_id)) {
            $where = array('employment_id' => $employment_id);
            if (!empty($user_id)) {
                $where['user_id !='] = $user_id;
            }
            $check_dupliaction_id = $this->admin_model->check_by($where, 'tbl_account_details');
            if (!empty($check_dupliaction_id)) {
                $result['error'] = lang("employee_id_exist");
            } else {
                $result['success'] = 1;
            }
            echo json_encode($result);
            exit();
        }
    }

    public
    function check_email_addrees($user_id = null)
    {
        $email_address = $this->input->post('name', true);
        if (!empty($email_address)) {
            $where = array('email' => $email_address);
            if (!empty($user_id)) {
                $where['user_id !='] = $user_id;
            }
            $check_email_address = $this->admin_model->check_by($where, 'tbl_users');
            if (!empty($check_email_address)) {
                $result['error'] = lang("this_email_already_exist");
            } else {
                $result['success'] = 1;
            }
            echo json_encode($result);
            exit();
        }
    }

    public
    function get_item_name_by_id($stock_sub_category_id)
    {
        $HTML = NULL;
        $this->admin_model->_table_name = 'tbl_stock';
        $this->admin_model->_order_by = 'stock_sub_category_id';
        $stock_info = $this->admin_model->get_by(array('stock_sub_category_id' => $stock_sub_category_id, 'total_stock >=' => '1'), FALSE);
        if (!empty($stock_info)) {
            foreach ($stock_info as $v_stock_info) {
                $HTML .= "<option value='" . $v_stock_info->stock_id . "'>" . $v_stock_info->item_name . "</option>";
            }
        }
        echo $HTML;
        exit();
    }

    function check_available_leave($user_id, $start_date = NULL, $end_date = NULL, $leave_category_id = NULL)
    {
        $office_hours = config_item('office_hours');
        $result = null;
        if (!empty($leave_category_id) && !empty($start_date)) {

            $total_leave = $this->global_model->check_by(array('leave_category_id' => $leave_category_id), 'tbl_leave_category');
            $leave_total = $total_leave->leave_quota;

            $all_leave = $this->db->where(array('user_id' => $user_id))->get('tbl_leave_application')->result();

            if (!empty($all_leave)) {
                foreach ($all_leave as $v_all_leave) {

                    if (empty($v_all_leave->leave_end_date)) {
                        $v_all_leave->leave_end_date = $v_all_leave->leave_start_date;
                    }
                    $get_dates = $this->global_model->GetDays($v_all_leave->leave_start_date, $v_all_leave->leave_end_date);
                    $result_start = in_array($start_date, $get_dates);

                    if (!empty($end_date) && $end_date != 'null') {
                        $result_end = in_array($end_date, $get_dates);
                    }
                    if (!empty($result_start) || !empty($result_end)) {
                        $result = lang('leave_date_conflict');
                    }
                }
            }

            $token_leave = $this->db->where(array('user_id' => $user_id, 'leave_category_id' => $leave_category_id, 'application_status' => '2'))->get('tbl_leave_application')->result();

            $total_taken = 0;
            $total_hourly = 0;
            if (!empty($token_leave)) {
                $ge_days = 0;
                $m_days = 0;
                foreach ($token_leave as $v_leave) {
                    if ($v_leave->leave_type != 'hours') {
                        $month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($v_leave->leave_start_date)), date('Y', strtotime($v_leave->leave_start_date)));
                        $datetime1 = new DateTime($v_leave->leave_start_date);
                        if (empty($v_leave->leave_end_date)) {
                            $v_leave->leave_end_date = $v_leave->leave_start_date;
                        }
                        $datetime2 = new DateTime($v_leave->leave_end_date);
                        $difference = $datetime1->diff($datetime2);

                        if ($difference->m != 0) {
                            $m_days += $month;
                        } else {
                            $m_days = 0;
                        }
                        $ge_days += $difference->d + 1;
                        $total_taken = $m_days + $ge_days;
                    }
                    if ($v_leave->leave_type == 'hours') {
                        $total_hourly += ($v_leave->hours / $office_hours);
                    }
                }
            }
            if (empty($total_taken)) {
                $total_taken = 0;
            }
            if (empty($total_hourly)) {
                $total_hourly = 0;
            }

            $total_taken = $total_hourly + $total_taken;


            $input_ge_days = 0;
            $input_m_days = 0;
            if (!empty($end_date) && $end_date != 'null') {
                $input_month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($start_date)), date('Y', strtotime($end_date)));

                $input_datetime1 = new DateTime($start_date);
                $input_datetime2 = new DateTime($end_date);
                $input_difference = $input_datetime1->diff($input_datetime2);

                if ($input_difference->m != 0) {
                    $input_m_days += $input_month;
                } else {
                    $input_m_days = 0;
                }
                $input_ge_days += $input_difference->d + 1;
                $input_total_taken = $input_m_days + $input_ge_days;
            } else {
                $input_total_taken = 1;
            }
            $taken_with_input = $total_taken + $input_total_taken;

            $left_leave = $leave_total - $total_taken;


            $left_leave_hours = $left_leave  * $office_hours;

            $left_leave_days = (int) ($left_leave_hours  / $office_hours);

            $left_leave_hours = $left_leave_hours  % $office_hours;

            $left_leave_string = $left_leave_days . ' days ' .  $left_leave_hours . ' hours ';


            if ($leave_total < $taken_with_input) {
                if ($user_id == $this->session->userdata('user_id')) {
                    $t = 'You ';
                } else {
                    $profile = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
                    $t = $profile->fullname;
                }

                $total_taken = leave_days_hours($total_taken, true);

                $result = "$t already took  $total_taken $total_leave->leave_category You can apply maximum for $left_leave_string more";
            }
        } else {
            $result = lang('all_required_fill');
        }
        echo $result;
        exit();
    }

    // public
    // function check_available_leave($user_id, $start_date = NULL, $end_date = NULL, $leave_category_id = NULL)
    // {

    //     $office_hours = config_item('office_hours');
    //     $result = null;
    //     if (!empty($leave_category_id) && !empty($start_date)) {

    //         $total_leave = $this->global_model->check_by(array('leave_category_id' => $leave_category_id), 'tbl_leave_category');
    //         $leave_total = $total_leave->leave_quota;

    //         $all_leave = $this->db->where(array('user_id' => $user_id))->get('tbl_leave_application')->result();

    //         if (!empty($all_leave)) {
    //             foreach ($all_leave as $v_all_leave) {

    //                 if (empty($v_all_leave->leave_end_date)) {
    //                     $v_all_leave->leave_end_date = $v_all_leave->leave_start_date;
    //                 }
    //                 $get_dates = $this->global_model->GetDays($v_all_leave->leave_start_date, $v_all_leave->leave_end_date);
    //                 $result_start = in_array($start_date, $get_dates);

    //                 if (!empty($end_date) && $end_date != 'null') {
    //                     $result_end = in_array($end_date, $get_dates);
    //                 }
    //                 if (!empty($result_start) || !empty($result_end)) {
    //                     $result = lang('leave_date_conflict');
    //                 }
    //             }
    //         }

    //         $token_leave = $this->db->where(array('user_id' => $user_id, 'leave_category_id' => $leave_category_id, 'application_status' => '2'))->get('tbl_leave_application')->result();

    //         $total_taken = 0;
    //         $total_hourly = 0;
    //         if (!empty($token_leave)) {
    //             $ge_days = 0;
    //             $m_days = 0;
    //             foreach ($token_leave as $v_leave) {
    //                 if ($v_leave->leave_type != 'hours') {
    //                     $month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($v_leave->leave_start_date)), date('Y', strtotime($v_leave->leave_start_date)));
    //                     $datetime1 = new DateTime($v_leave->leave_start_date);
    //                     if (empty($v_leave->leave_end_date)) {
    //                         $v_leave->leave_end_date = $v_leave->leave_start_date;
    //                     }
    //                     $datetime2 = new DateTime($v_leave->leave_end_date);
    //                     $difference = $datetime1->diff($datetime2);

    //                     if ($difference->m != 0) {
    //                         $m_days += $month;
    //                     } else {
    //                         $m_days = 0;
    //                     }
    //                     $ge_days += $difference->d + 1;
    //                     $total_taken = $m_days + $ge_days;
    //                 }
    //                 if ($v_leave->leave_type == 'hours') {
    //                     $total_hourly += ($v_leave->hours / $office_hours);
    //                 }
    //             }
    //         }
    //         if (empty($total_taken)) {
    //             $total_taken = 0;
    //         }
    //         if (empty($total_hourly)) {
    //             $total_hourly = 0;
    //         }
    //         $total_taken = $total_hourly + $total_taken;


    //         $input_ge_days = 0;
    //         $input_m_days = 0;
    //         if (!empty($end_date) && $end_date != 'null') {
    //             $input_month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($start_date)), date('Y', strtotime($end_date)));

    //             $input_datetime1 = new DateTime($start_date);
    //             $input_datetime2 = new DateTime($end_date);
    //             $input_difference = $input_datetime1->diff($input_datetime2);

    //             if ($input_difference->m != 0) {
    //                 $input_m_days += $input_month;
    //             } else {
    //                 $input_m_days = 0;
    //             }
    //             $input_ge_days += $input_difference->d + 1;
    //             $input_total_taken = $input_m_days + $input_ge_days;
    //         } else {
    //             $input_total_taken = 1;
    //         }
    //         $taken_with_input = $total_taken + $input_total_taken;

    //         $left_leave = $leave_total - $total_taken;

    //         if ($leave_total < $taken_with_input) {
    //             if ($user_id == $this->session->userdata('user_id')) {
    //                 $t = 'You ';
    //             } else {
    //                 $profile = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
    //                 $t = $profile->fullname;
    //             }
    //             $result = "$t already took  $total_taken $total_leave->leave_category You can apply maximum for $left_leave more";
    //         }
    //     } else {
    //         $result = lang('all_required_fill');
    //     }
    //     echo $result;
    //     exit();
    // }

    public
    function get_leave_details($user_id)
    {
        if ($user_id == $this->session->userdata('user_id')) {
            $title = lang('my_leave');
        } else {
            $profile = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
            $title = $profile->fullname;
        }

        $panel = null;
        $panel .= '<div class="panel panel-custom"><div class="panel-heading"><div class="panel-title"><strong>' . $title . ' ' . lang('details') . '</strong></div></div><table class="table"><tbody>';
        $total_taken = 0;
        $total_quota = 0;
        $leave_report = leave_report($user_id);
        if (!empty($leave_report['leave_category'])) {
            foreach ($leave_report['leave_category'] as $lkey => $v_l_report) {
                $total_quota += $leave_report['leave_quota'][$lkey];
                $total_taken += $leave_report['leave_taken'][$lkey];

                $panel .= '<tr><td><strong>' . $leave_report['leave_category'][$lkey] . '</strong>:</td><td>';
                $panel .= leave_days_hours($leave_report['leave_taken'][$lkey]) . '/' . $leave_report['leave_quota'][$lkey];
                $panel .= '</td></tr>';
            }
        }
        $panel .= '<tr><td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;"><strong>' . lang('total') . '</strong>:</td><td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;">' . leave_days_hours($total_taken) . '/' . $total_quota . '</td></tr></tbody></table></div>';
        echo $panel;
        exit();
    }

    public
    function get_package_details($pricing_id)
    {
        $plan_info = get_row('tbl_frontend_pricing', array('id' => $pricing_id));
        $data['package_name'] = $plan_info->name;
        $data['package_details'] = $this->load->view('frontend/package_details', array('plan_info' => $plan_info), TRUE);
        echo json_encode($data);
        exit();
    }

    public
    function get_employee_by_designations_id($designation_id)
    {
        $HTML = NULL;
        $this->admin_model->_table_name = 'tbl_account_details';
        $this->admin_model->_order_by = 'designations_id';
        $employee_info = $this->admin_model->get_by(array('designations_id' => $designation_id), FALSE);
        if (!empty($employee_info)) {
            foreach ($employee_info as $v_employee_info) {
                $HTML .= "<option value='" . $v_employee_info->user_id . "'>" . $v_employee_info->fullname . "</option>";
            }
        }
        echo $HTML;
        exit();
    }

    public
    function check_advance_amount($amount, $user_id = null)
    {
        $result = $this->global_model->get_advance_amount($user_id);
        if (!empty($result)) {
            if ($result < $amount) {
                echo lang('exced_basic_salary');
                exit();
            } else {
                echo null;
                exit();
            }
        } else {
            echo lang('you_can_not_apply');
            exit();
        }
    }

    public
    function get_taxes_dropdown()
    {
        $name = $this->input->post('name', true);
        $taxname = $this->input->post('taxname', true);
        echo $this->admin_model->get_taxes_dropdown($name, $taxname);
        exit();
    }

    /* Get item by id / ajax */
    public
    function get_item_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item = $this->admin_model->get_item_by_id($id);
            echo json_encode($item);
            exit();
        }
    }

    public
    function update_ei_items_order($type)
    {
        $data = $this->input->post();
        //        if (!empty($data['items_id'])) {
        foreach ($data['items_id'] as $order) {
            if ($type == 'estimate') {
                $this->db->where('estimate_items_id', $order[0]);
                $this->db->update('tbl_estimate_items', array(
                    'order' => $order[1]
                ));
            } else if ($type == 'purchase') {
                $this->db->where('items_id', $order[0]);
                $this->db->update('tbl_purchase_items', array(
                    'order' => $order[1]
                ));
            } else if ($type == 'proposal') {
                $this->db->where('proposals_items_id', $order[0]);
                $this->db->update('tbl_proposals_items', array(
                    'order' => $order[1]
                ));
            } else if ($type == 'todo') {
                $this->db->where('todo_id', $order[0]);
                $this->db->update('tbl_todo', array(
                    'order' => $order[1]
                ));
            } else {
                $this->db->where('items_id', $order[0]);
                $this->db->update('tbl_items', array(
                    'order' => $order[1]
                ));
            }
            //            }
        }
    }

    /* Set notifications to read */
    public
    function mark_as_read()
    {
        if ($this->input->is_ajax_request()) {
            $this->db->where('to_user_id', $this->session->userdata('user_id'));
            $this->db->update('tbl_notifications', array(
                'read' => 1
            ));
            if ($this->db->affected_rows() > 0) {
                echo json_encode(array(
                    'success' => true
                ));
            } //$this->db->affected_rows() > 0
            return false;
        }
    }

    public
    function read_inline($id)
    {
        $this->db->where('to_user_id', $this->session->userdata('user_id'));
        $this->db->where('notifications_id', $id);
        $this->db->update('tbl_notifications', array(
            'read_inline' => 1
        ));
    }

    public
    function mark_desktop_notification_as_read($id)
    {
        $this->db->where('to_user_id', $this->session->userdata('user_id'));
        $this->db->where('notifications_id', $id);
        $this->db->update('tbl_notifications', array(
            'read' => 1,
            'read_inline' => 1
        ));
    }

    public
    function mark_all_as_read()
    {
        $this->db->where('to_user_id', $this->session->userdata('user_id'));
        $this->db->update('tbl_notifications', array(
            'read' => 1,
            'read_inline' => 1
        ));
    }

    public
    function get_notification()
    {
        $notificationsIds = array();

        if (config_item('desktop_notifications') == "1") {
            $notifications = $this->global_model->get_user_notifications(false);

            $notificationsPluck = array_filter($notifications, function ($n) {
                return $n->read == 0;
            });
            $notificationsIds = array_pluck($notificationsPluck, 'notifications_id');
        }
        echo json_encode(array(
            'html' => $this->load->view('admin/components/notifications', array(), true),
            'notificationsIds' => $notificationsIds
        ));
        exit();
    }

    /* upload a post file */

    function upload_file()
    {
        upload_file_to_temp();
        $result=upload_file_to_docroom();
        echo json_encode($result);
        exit();
    }

    /* check valid file for project */

    function validate_project_file()
    {
        return validate_post_file($this->input->post("file_name"));
    }

    function set_media_view($type, $module)
    {
        $k_session[$module . '_media_view'] = $type;
        $this->session->set_userdata($k_session);
        return true;
    }

    public
    function hash($string)
    {
        return hash('sha512', $string . config_item('encryption_key'));
    }

    public
    function set_language($lang)
    {
        $this->session->set_userdata('lang', $lang);
        redirect($_SERVER["HTTP_REFERER"]);
    }

    public
    function subscriptions_details($id = null)
    {
        $data['title'] = lang('home');
        $data['subscription_info'] = get_my_subs();
        $data['subview'] = $this->load->view('admin/frontend/subscriptions_details', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public
    function set_companies($companies_id = null)
    {
        if (empty($companies_id)) {
            $this->session->unset_userdata('companies_id', $companies_id);
        } else {
            $this->session->set_userdata('companies_id', $companies_id);
        }
        redirect($_SERVER["HTTP_REFERER"]);
    }

    public
    function checkout()
    {
        $data['title'] = lang('upgrade') . ' ' . lang('plan');
        if (!empty($type)) {
            $data['type'] = $type;
        }
        $data['sub_info'] = get_active_subs();
        $data['subview'] = $this->load->view('admin/settings/checkout', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public
    function updatePackage()
    {
        $data['title'] = lang('upgrade') . ' ' . lang('plan');
        if (!empty($type)) {
            $data['type'] = $type;
        }
        $data['sub_info'] = get_active_subs();
        $data['currency_wise_price'] = get_currencywise_price(true);
        $data['subview'] = $this->load->view('admin/settings/pricing', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public
    function NewPlan($companies_id = null)
    {
        $this->load->model('settings_model');
        $data['title'] = lang('new') . ' ' . lang('plan');
        $data['companies_id'] = $companies_id;
        $data['sub_info'] = array();
        $data['currency_wise_price'] = get_currencywise_price(true);
        $data['subview'] = $this->load->view('admin/settings/pricing', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public
    function checkoutPayment()
    {
        $data['packege_id'] = $this->input->post('pricing_id', true);
        $data['interval_type'] = $this->input->post('interval_type', true);
        $data['currency_type'] = $this->input->post('currency_type', true);

        $front_end = $this->input->post('front_end', true);
        if (empty($data['currency_type'])) {
            $data['currency_type'] = config_item('default_currency');
        }
        if (empty($data['interval_type'])) {
            $data['interval_type'] = 'monthly';
        }
        $data['c_pricing_info'] = get_currencywise_price(true, $data['currency_type']);
        if (empty($data['packege_id'])) {
            $data['packege_id'] = $data['c_pricing_info'][0]->frontend_pricing_id;
        }
        $data['title'] = lang('checkout') . ' ' . lang('payment') . ' ' . lang('for') . ' ' . plan_name($data['packege_id']);
        $data['package_info'] = plan_info($data['packege_id']);
        $data['subs_info'] = get_my_subs();
        $data['currency_wise_price'] = get_currencywise_price(true);
        $data['subview'] = $this->load->view('admin/settings/checkoutPayment_skote', $data, TRUE);
        $user_id = $this->session->userdata('user_id');
        if (!empty($user_id) && empty($front_end)) {
        $data['subview'] = $this->load->view('admin/settings/checkoutPayment_skote', $data, TRUE);

            $this->load->view('admin/_layout_skote_main', $data); //page load
        } elseif (!empty($front_end)) {
            $this->load->view('admin/_layout_skote_main', $data); //page load
        } else {
        $data['subview'] = $this->load->view('admin/settings/checkoutPayment_skote', $data, TRUE);

            $this->load->view('frontend/_layout_skote_main', $data); //page load
        }
    }

    public
    function get_coupon_data()
    {
        $coupon_code = $this->input->post('coupon_code', true);
        $pricing_id = $this->input->post('pricing_id', true);
        $billing_cycle = $this->input->post('billing_cycle', true);
        $pricewise_currency = $this->input->post('pricewise_currency', true);
        $subscriptions_id = $this->input->post('subscriptions_id', true);
        $subs_info = get_old_data('tbl_subscriptions', array('subscriptions_id' => $subscriptions_id));
        $coupon_result = $this->is_valid_coupon($coupon_code, $pricing_id, $billing_cycle, $pricewise_currency, $subs_info->email);
        echo json_encode($coupon_result);
        exit();
    }

    function is_valid_coupon($coupon_code, $pricing_id, $billing_cycle, $pricewise_currency, $email_address)
    {
        $currency = $this->admin_model->check_by(array('code' => $pricewise_currency), 'tbl_currencies');

        $where = array('code' => $coupon_code, 'end_date >=' => date('Y-m-d H:i'), 'status' => 1);
        $coupon_info = get_old_data('tbl_frontend_coupon', $where);
        $currecy_wise_price = get_old_data('tbl_currencywise_price', array('frontend_pricing_id' => $pricing_id, 'currency' => $pricewise_currency));

        if ($billing_cycle == 'monthly') {
            $c_amount = $currecy_wise_price->monthly;
        } else {
            $c_amount = $currecy_wise_price->yearly;
        }
        $sub_total = $c_amount;
        $result['sub_total_text'] = display_money($sub_total, $currency->symbol);
        $result['sub_total_input'] = $sub_total;
        $result['total_text'] = display_money($sub_total, $currency->symbol);
        $result['total_input'] = $sub_total;

        if (!empty($coupon_info)) {
            $user_id = my_id();
            if (!empty($user_id)) {
                $where = array('user_id' => $user_id, 'coupon' => $coupon_code);
            } else {
                $where = array('email' => $email_address, 'coupon' => $coupon_code);
            }
            $already_apply = get_old_data('tbl_applied_coupon', $where);
            if (empty($already_apply)) {
                $percentage = $coupon_info->amount;
                if ($coupon_info->type == 1) {
                    $discount_amount = ($percentage / 100) * $sub_total;
                    $discount_percentage = $percentage . '%';
                } else {
                    $discount_amount = $percentage;
                    $discount_percentage = $percentage;
                }
                $result['sub_total_text'] = display_money($sub_total, $currency->symbol);
                $result['sub_total_input'] = $sub_total;
                $result['total_text'] = display_money($sub_total - $discount_amount, $currency->symbol);
                $result['total_input'] = $sub_total - $discount_amount;
                $result['discount_percentage'] = $discount_percentage;
                $result['coupon_code_input'] = $coupon_code;
                if ($coupon_info->pricing_id == 0) {
                    $result['success'] = true;
                    $result['discount_amount_text'] = display_money($discount_amount, $currency->symbol);
                    $result['discount_amount_input'] = $discount_amount;
                } elseif ($coupon_info->pricing_id == $pricing_id) {
                    $result['success'] = true;
                    $result['discount_amount_text'] = display_money($discount_amount, $currency->symbol);
                    $result['discount_amount_input'] = $discount_amount;
                } else {
                    $result['error'] = true;
                    $result['message'] = lang('the_coupon_code_is_invalid');
                    $result['coupon_code_input'] = null;
                }
            } else {
                $result['error'] = true;
                $result['message'] = lang('the_coupon_code_already_used');
                $result['coupon_code_input'] = null;
            }
        } else {
            $result['error'] = true;
            $result['message'] = lang('the_coupon_code_is_invalid');
            $result['coupon_code_input'] = null;
        }
        return $result;
    }

    function package_renews_date()
    {
        $currency = get_old_data('tbl_currencies', array('code' => config_item('default_currency')));
        $billing_cycle = $this->input->post('billing_cycle', true);
        $pricing_id = $this->input->post('pricing_id', true);
        $discount_amount = $this->input->post('discount_amount', true);
        $pricing_info = plan_info($pricing_id);

        $result['sub_total_text'] = display_money($pricing_info->amount * $billing_cycle, $currency->symbol);
        $result['sub_total_input'] = $pricing_info->amount * $billing_cycle;

        if (empty($discount_amount)) {
            $discount_amount = 0;
        }
        $total = $result['sub_total_input'] - $discount_amount;
        $result['total_text'] = display_money($total, $currency->symbol);
        $result['total_input'] = $total;

        $create_date = date('Y-m-d H:i:s');
        $endDatetime = DateTime::createFromFormat('Y-m-d H:i:s', $create_date);
        $endDatetime->modify("+ " . $billing_cycle);
        $result['renew_date'] = $endDatetime->format('Y-m-d');

        echo json_encode($result);

        exit();
    }

    public
    function package_details($pricing_id)
    {
        $this->load->model('settings_model');
        $data['title'] = lang('package_details');
        $data['package_info'] = get_old_data('tbl_frontend_pricing', array('id' => $pricing_id));
        $data['subview'] = $this->load->view('admin/settings/package_details', $data, FALSE);
        $this->load->view('admin/_layout_skote_modal', $data);
    }

    public
    function subs_package_details($subscriptions_history_id)
    {
        $this->load->model('settings_model');
        $data['title'] = lang('package_details');
        $data['package_info'] = get_old_data('tbl_subscriptions_history', array('id' => $subscriptions_history_id));
        $data['subview'] = $this->load->view('admin/settings/package_details', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }

    public
    function pricing_change_data($pricing_id, $pricewise_currency, $interval_type)
    {
        if ($this->input->is_ajax_request()) {
            $data = array();
            $_data['currecy_wise_price'] = get_old_data('tbl_currencywise_price', array('frontend_pricing_id' => $pricing_id, 'currency' => $pricewise_currency));
            $_data['package_info'] = get_old_data('tbl_frontend_pricing', array('id' => $pricing_id));
            $_data['interval_type'] = $interval_type;
            $_data['currency_type'] = $pricewise_currency;
            $data['set_merge_info'] = $this->load->view('admin/settings/checkout_payment_skote', $_data, true);
            $data['package_name'] = plan_name($pricing_id);
            echo json_encode($data);
            exit();
        }
    }

    public
    function active_subscription()
    {
        
        $payment_method = $this->input->post('payment_method');        
        $input_data = $this->session->userdata('input_info'); 
        $mdata = $this->input->post(); 
        // if (empty($input_data)) {
        //     $payment_method = $this->input->post('payment_method');
        //     $mdata = $this->input->post(); 
          
        // } else if (!empty($input_data)) {
        //     $input_data = $this->session->userdata('input_info');
        //     $payment_method = $input_data['payment_method'];
        //     $mdata = $input_data;
        // }else{
        //        $mdata = $this->input->post();   
        // }
        if (!$payment_method) {
            $type = 'error';
            $message = lang('please_select_payment_method');
            set_message($type, $message);
            redirect('checkoutPayment');
        } else if (!empty($payment_method)) {
            $this->load->model('payments_model');
            
            $rr = $this->payments_model->process_payment($mdata);
            echo '<pre>';
            print_r($rr);
            exit();
        } else {
            $type = 'error';
            $message = lang('please_select_payment_method');
            set_message($type, $message);
            redirect('checkoutPayment');
        }
    }
}
