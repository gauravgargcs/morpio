<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admistrator
 *
 * @author pc mart ltd
 */
class Calendar extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
        $this->load->model('invoice_model');
        $this->load->model('estimates_model');
        $this->load->model('items_model');
        $this->load->model('tasks_model');

    }

    public function index($action = NULL)
    {
        $data['title'] = lang('my_calendar');
        $data['dataTables'] = true;
        $data['select_2'] = true;
        $data['datepicker'] = true;
        $data['page'] = lang('calendar');
        $data['action']= $action;
        if (!empty($action) && $action == 'search') {
            $data['searchType'] = $this->uri->segment(5);

        } else  {
            $data['searchType'] = 'all';
        }
        //total expense count
        $this->admin_model->_table_name = "tbl_transactions"; //table name
        $this->admin_model->_order_by = "transactions_id"; // order by 
        $total_income_expense = $this->admin_model->get(); // get result
        $today_income_expense = $this->admin_model->get_by(array('date' => date('Y-m-d H:i'))); // get result

        $today_income = 0;
        $today_expense = 0;
        foreach ($today_income_expense as $t_income_expense) {
            if ($t_income_expense->type == 'Income') {
                $today_income += $t_income_expense->amount;

            } elseif ($t_income_expense->type == 'Expense') {
                $today_expense += $t_income_expense->amount;
            }
        }
        $data['today_income'] = $today_income;

        $data['today_expense'] = $today_expense;

        $total_income = 0;
        $total_expense = 0;
        foreach ($total_income_expense as $v_income_expense) {
            if ($v_income_expense->type == 'Income') {
                $total_income += $v_income_expense->amount;

            } elseif ($v_income_expense->type == 'Expense') {
                $total_expense += $v_income_expense->amount;
            }
        }
        $data['total_income'] = $total_income;

        $data['total_expense'] = $total_expense;

        $user_id = $this->session->userdata('user_id');
        $user_info = get_staff_details($user_id);
        $data['role'] = $user_info->role_id;

        $data['invoce_total'] = $this->invoice_totals_per_currency();
        if (!empty($action) && $action == 'payments') {
            $data['yearly'] = $this->input->post('yearly', TRUE);
        } else {
            $data['yearly'] = date('Y'); // get current year
        }
        if (!empty($action) && $action == 'Income') {
            $data['Income'] = $this->input->post('Income', TRUE);
        } else {
            $data['Income'] = date('Y'); // get current year
        }
        if ($this->input->post('year', TRUE)) { // if input year 
            $data['year'] = $this->input->post('year', TRUE);
        } else { // else current year
            $data['year'] = date('Y'); // get current year
        }
        // get all expense list by year and month
        $data['all_income'] = $this->get_transactions_list($data['Income'], 'Income');

        $data['all_expense'] = $this->get_transactions_list($data['year'], 'Expense');

        $data['yearly_overview'] = $this->get_yearly_overview($data['yearly']);

        if ($this->input->post('month', TRUE)) { // if input year 
            $data['month'] = $this->input->post('month', TRUE);
        } else { // else current year
            $data['month'] = date('Y-m'); // get current year
        }
        $data['income_expense'] = $this->get_income_expense($data['month']);
        $data['subview'] = $this->load->view('admin/calendar', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data);
    }

    function invoice_totals_per_currency()
    {
        $invoices_info = get_result('tbl_invoices', array('inv_deleted' => 'No'));
        $paid = $due = array();
        $currency = 'USD';
        $symbol = array();
        foreach ($invoices_info as $v_invoices) {
            if (!isset($paid[$v_invoices->currency])) {
                $paid[$v_invoices->currency] = 0;
            }
            if (!isset($due[$v_invoices->currency])) {
                $due[$v_invoices->currency] = 0;
            }
            $paid[$v_invoices->currency] += $this->invoice_model->get_invoice_paid_amount($v_invoices->invoices_id);
            $due[$v_invoices->currency] += $this->invoice_model->get_invoice_due_amount($v_invoices->invoices_id);
            $currency = $this->admin_model->check_by(array('code' => $v_invoices->currency), 'tbl_currencies');
            $symbol[$v_invoices->currency] = $currency->symbol;
        }
        return array("paid" => $paid, "due" => $due, "symbol" => $symbol);
    }

    public function get_yearly_overview($year)
    {// this function is to create get monthy recap report
        for ($i = 1; $i <= 12; $i++) { // query for months
            if ($i >= 1 && $i <= 9) { // if i<=9 concate with Mysql.becuase on Mysql query fast in two digit like 01.
                $month = '0' . $i;
            } else {
                $month = $i;
            }
            $yearly_report[$i] = $this->admin_model->calculate_amount($year, $month); // get all report by start date and in date 
        }
        return $yearly_report; // return the result
    }

    public function get_income_expense($month)
    {// this function is to create get monthy recap report
        //m = date('m', strtotime($month));
        $m = date('m', strtotime($month));
        $year = date('Y', strtotime($month));
        $date = new DateTime($year . '-' . $m . '-01');
        $start_date = $date->modify('first day of this month')->format('Y-m-d');
        $end_date = $date->modify('last day of this month')->format('Y-m-d');
        $get_expense_list = $this->admin_model->get_transactions_list_by_month($start_date, $end_date); // get all report by start date and in date 

        return $get_expense_list; // return the result
    }

    public function get_transactions_list($year, $type)
    {// this function is to create get monthy recap report
        for ($i = 1; $i <= 12; $i++) { // query for months
            $date = new DateTime($year . '-' . $i . '-01');
            $start_date = $date->modify('first day of this month')->format('Y-m-d');
            $end_date = $date->modify('last day of this month')->format('Y-m-d');
            $get_expense_list[$i] = $this->admin_model->get_transactions_list_by_date($type, $start_date, $end_date); // get all report by start date and in date 
        }
        return $get_expense_list; // return the result
    }

    public function calendar_settings()
    {
        $data['title'] = lang('calendar_settings');
        $data['modal_subview'] = $this->load->view('admin/settings/calendar_settings', $data, FALSE);
        $this->load->view('admin/_layout_skote_modal_lg', $data);
    }

    public function save_settings()
    {
        $input_data = $this->admin_model->array_from_post(array('gcal_api_key', 'gcal_id', 'project_on_calendar', 'milestone_on_calendar', 'tasks_on_calendar', 'bugs_on_calendar', 'invoice_on_calendar', 'payments_on_calendar', 'estimate_on_calendar', 'opportunities_on_calendar', 'goal_tracking_on_calendar', 'holiday_on_calendar', 'absent_on_calendar', 'on_leave_on_calendar',
            'project_color', 'milestone_color', 'tasks_color', 'bugs_color', 'invoice_color', 'payments_color', 'estimate_color', 'opportunities_color', 'goal_tracking_color', 'absent_color', 'on_leave_color'));

        foreach ($input_data as $key => $value) {
            $data = array('value' => $value);
            $this->db->where('config_key', $key)->update('tbl_config', $data);
            $exists = $this->db->where('config_key', $key)->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
            }
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $this->session->userdata('user_id'),
            'activity' => ('activity_save_settings'),
            'value1' => $input_data['gcal_api_key']
        );

        $this->admin_model->_table_name = 'tbl_activities';
        $this->admin_model->_primary_key = 'activities_id';
        $this->admin_model->save($activity);
        // messages for user
        $type = "success";
        $message = lang('save_settings');
        set_message($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function calendarEvent()
    {
        
        $input_data = $this->admin_model->array_from_post(array('title','notes','start_date','end_date','calendarId','id'));

        $calendarId=$input_data['calendarId'];

        switch ($calendarId) {
            case 'todo':
                $returnData=$this->add_todo($input_data);
                echo json_encode($returnData);
                exit();    
                break;
            case 'projects':
                $returnData=$this->add_project($input_data);
                echo json_encode($returnData);
                exit();
                break;
            case 'tasks':
                $returnData=$this->add_task($input_data);
                echo json_encode($returnData);
                exit();
                break;
            case 'zoom':
                $returnData=$this->add_zoom($input_data);
                echo json_encode($returnData);
                exit();
                break;
        }

    }

    function add_todo($input_data=NULL)
    {
        // messages for user
        $type = "error";
        $message = '';
        
        if(!empty($input_data)){
            $data['title']= $input_data['title'];
            $data['notes']= $input_data['notes'];
            $data['created_date']= date('Y-m-d H-i',strtotime($input_data['start_date']));
            $data['due_date']= date('Y-m-d H-i',strtotime($input_data['end_date']));
            $data['status'] = 1;
            $data['assigned'] = 0;
            $data['user_id'] = $this->session->userdata('user_id');
            $id=$input_data['id'];
            if(empty($id)) {
                $data['order'] = 1;
                $id=NULL;
            }
            $this->admin_model->_table_name = "tbl_todo"; // table name
            $this->admin_model->_primary_key = "todo_id"; // $id
            $this->admin_model->save($data, $id);
            $type = "success";
            $message = lang('todo_information_updated');
        }

        return array('status'=>$type, 'message'=>$message);
    }

    function add_project($input_data=NULL)
    {
        // messages for user
        $type = "error";
        $message = '';

        if(!empty($input_data)){
            $created = can_action('57', 'created');
            $edited = can_action('57', 'edited');
            $data=[];
            if (!empty($created) || !empty($edited)) {
                $this->admin_model->_table_name = 'tbl_project';
                $this->admin_model->_primary_key = 'project_id';
                $data['project_name']=$input_data['title'];
                $data['description']=$input_data['notes'];

                $data['start_date']=date('Y-m-d H-i',strtotime($input_data['start_date']));
                $data['end_date']=date('Y-m-d H-i',strtotime($input_data['end_date']));
                $id=$input_data['id'];

                $data['project_cost'] = '0';
                $data['hourly_rate'] = '0';            
                $data['estimate_hours'] = '0:00';

                $data['project_settings'] = null;
               
                if (empty($id)) {
                    plan_capability('projects');
                    $id=NULL;
                }

                $return_id = $this->admin_model->save($data, $id);

                if (!empty($id)) {
                    $id = $id;
                    $action = 'activity_update_project';
                    $msg = lang('update_project');
                } else {
                    $id = $return_id;
                    $action = 'activity_save_project';
                    $msg = lang('save_project');
                }

                save_custom_field(4, $id);

                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'projects',
                    'module_field_id' => $id,
                    'activity' => $action,
                    'icon' => 'fa-folder-open-o',
                    'link' => 'admin/projects/project_details/' . $id,
                    'value1' => $data['project_name']
                );
                $this->admin_model->_table_name = 'tbl_activities';
                $this->admin_model->_primary_key = 'activities_id';
                $this->admin_model->save($activity);

                $this->items_model->set_progress($id);
                // messages for user
                $type = "success";
                $message = $msg;   
            }
        }

        return array('status'=>$type, 'message'=>$message);
    }

    function add_task($input_data=NULL)
    {
        // messages for user
        $type = "error";
        $message = '';
        
        if(!empty($input_data)){
            $created = can_action('54', 'created');
            $edited = can_action('54', 'edited');
            if (!empty($created) || !empty($edited)) {

                $data['task_name']=$input_data['title'];
                $data['task_description']=$input_data['notes'];

                $data['task_start_date']=date('Y-m-d H-i',strtotime($input_data['start_date']));
                $data['due_date']=date('Y-m-d H-i',strtotime($input_data['end_date']));
                $id=$input_data['id'];

                $data['task_hour'] = '0:00';
                              
                $data['billable'] = 'No';    
                $data['hourly_rate'] = '0';    
                $data['project_id'] = NULL;
                $data['milestones_id'] = NULL;
                $data['goal_tracking_id'] = NULL;
                $data['bug_id'] = NULL;
                $data['leads_id'] = NULL;
                $data['opportunities_id'] = NULL;
                $data['sub_task_id'] = NULL;
                
                if (empty($id)) {
                    $data['created_by'] = $this->session->userdata('user_id');
                    $id=NULL;
                    plan_capability('tasks');
                }

                //save data into table.
                $this->tasks_model->_table_name = "tbl_task"; // table name
                $this->tasks_model->_primary_key = "task_id"; // $id
                $id = $this->tasks_model->save($data, $id);

                $this->tasks_model->set_task_progress($id);

                $u_data['index_no'] = $id;
                $id = $this->tasks_model->save($u_data, $id);
                $u_data['index_no'] = $id;
                $id = $this->tasks_model->save($u_data, $id);

                save_custom_field(3, $id);

                if (!empty($id)) {
                    $msg = lang('update_task');
                    $activity = 'activity_update_task';
                    $id = $id;
                } else {
                    $msg = lang('save_task');
                    $activity = 'activity_new_task';
                }

                // save into activities
                $activities = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'tasks',
                    'module_field_id' => $id,
                    'activity' => $activity,
                    'icon' => 'fa-tasks',
                    'link' => 'admin/tasks/view_task_details/' . $id,
                    'value1' => $data['task_name'],
                );
                // Update into tbl_project
                $this->tasks_model->_table_name = "tbl_activities"; //table name
                $this->tasks_model->_primary_key = "activities_id";
                $this->tasks_model->save($activities);

                $type = "success";
                $message = $msg;
            }
        }

        return array('status'=>$type, 'message'=>$message);
    }

    function add_zoom($input_data=NULL)
    {

        $created = can_action_by_label('zoom', 'created');
        $edited = can_action_by_label('zoom', 'edited');
        // messages for user
        $type = "error";
        $message = '';
        
        if(!empty($input_data)){
           
            $data['topic']= $input_data['title'];
            $data['notes']= $input_data['notes'];
            $data['meeting_time']= date('Y-m-d H-i',strtotime($input_data['start_date']));
            $data['user_id'] = $this->session->userdata('user_id');
            $id=$input_data['id'];
            if(empty($id)) {
                $id=NULL;
            }
            $data['duration']=$data['host']='';
            $data['additional']=json_encode(array());

            if (!empty($created) || !empty($edited) && !empty($id)) {
                
                require_once(module_dirPath(ZOOM_MODULE) . 'libraries/ZoomAPI.php');
         
                $api = new ZoomAPI();
                if (!empty($id)) {
                    $meetingId = get_any_field('tbl_zoom_meeting', array('zoom_meeting_id' => $id), 'meetingId');
                    $response = $api->createMeeting($data, $meetingId);
                } else {
                    $response = $api->createMeeting($data);
                }
                
                if ($response) {
                    if (isset($response->id)) {
                        $data['meetingId'] = $response->id;
                        if (!empty($response->start_url)) {
                            $data['start_url'] = $response->start_url;
                            $data['join_url'] = $response->join_url;
                            $data['status'] = $response->status;
                        }
                        $this->admin_model->_table_name = 'tbl_zoom_meeting';
                        $this->admin_model->_primary_key = 'zoom_meeting_id';
                        $this->admin_model->save($data, $id);
                        
                        $activity = array(
                            'user' => $this->session->userdata('user_id'),
                            'module' => 'settings',
                            'module_field_id' => $this->session->userdata('user_id'),
                            'activity' => ('activity_new_custom_field'),
                            'value1' => $data['topic']
                        );
                        
                        $this->admin_model->_table_name = 'tbl_activities';
                        $this->admin_model->_primary_key = 'activities_id';
                        $this->admin_model->save($activity);
                        // messages for user
                        $type = "success";
                        $message = lang('meeting_information_saved');
                    } else {
                        $type = "error";
                        $message = $response->message;
                    }
                }
            } else {
                $type = "error";
                $message = lang('something_went_wrong');
            }
        }
        return array('status'=>$type, 'message'=>$message);
    }


    public function deleteCalendarEvent()
    {
        
        $input_data = $this->admin_model->array_from_post(array('calendarId','id'));

        $calendarId=$input_data['calendarId'];

        switch ($calendarId) {
            case 'todo':
                $returnData=$this->delete_todo($input_data);
                echo json_encode($returnData);
                exit();    
                break;
            case 'projects':
                $returnData=$this->delete_project($input_data);
                echo json_encode($returnData);
                exit();
                break;
            case 'tasks':
                $returnData=$this->delete_task($input_data);
                echo json_encode($returnData);
                exit();
                break;
            case 'zoom':
                $returnData=$this->delete_zoom($input_data);
                echo json_encode($returnData);
                exit();
           
        }

    }

    function delete_todo($input_data=NULL)
    {
        // messages for user
        $type = "error";
        $message = '';
        
        if(!empty($input_data)){
            $id=$input_data['id'];
            $this->db->where('todo_id', $id);
            $this->db->delete('tbl_todo');
            $type = "success";
            $message = lang('todo_information_deleted');
        }

        return array('status'=>$type, 'message'=>$message);
    }

    function delete_project($input_data=NULL)
    {
        // messages for user
        $type = "error";
        $message = '';
        
        if(!empty($input_data)){
            $id=$input_data['id'];
            $deleted = can_action('57', 'deleted');
            if (!empty($deleted)) {
                $project_info = $this->items_model->check_by(array('project_id' => $id), 'tbl_project');
                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'projects',
                    'module_field_id' => $id,
                    'activity' => 'activity_project_deleted',
                    'icon' => 'fa-folder-open-o',
                    'value1' => $project_info->project_name
                );
                $this->items_model->_table_name = 'tbl_activities';
                $this->items_model->_primary_key = 'activities_id';
                $this->items_model->save($activity);

                // deleted project comments with file
                $all_comments_info = $this->db->where(array('project_id' => $id))->get('tbl_task_comment')->result();
                if (!empty($all_comments_info)) {
                    foreach ($all_comments_info as $comments_info) {
                        if (!empty($comments_info->comments_attachment)) {
                            $attachment = json_decode($comments_info->comments_attachment);
                            foreach ($attachment as $v_file) {
                                remove_files($v_file->fileName);
                            }
                        }
                    }
                }
                //delete data into table.
                $this->items_model->_table_name = "tbl_task_comment"; // table name
                $this->items_model->delete_multiple(array('project_id' => $id));

                // deleted project attachment with file
                $this->items_model->_table_name = "tbl_task_attachment"; //table name
                $this->items_model->_order_by = "project_id";
                $files_info = $this->items_model->get_by(array('project_id' => $id), FALSE);
                if (!empty($files_info)) {
                    foreach ($files_info as $v_files) {
                        $uploadFileinfo = $this->db->where('task_attachment_id', $v_files->task_attachment_id)->get('tbl_task_uploaded_files')->result();
                        if (!empty($uploadFileinfo)) {
                            foreach ($uploadFileinfo as $Fileinfo) {
                                remove_files($Fileinfo->file_name);
                            }
                        }
                        //save data into table.
                        $this->items_model->_table_name = "tbl_task_uploaded_files"; // table name
                        $this->items_model->delete_multiple(array('task_attachment_id' => $v_files->task_attachment_id));
                    }
                }
                $this->items_model->_table_name = "tbl_task_attachment"; // table name
                $this->items_model->delete_multiple(array('project_id' => $id));

                // deleted project milestone
                $this->items_model->_table_name = "tbl_milestones"; // table name
                $this->items_model->delete_multiple(array('project_id' => $id));

                // deleted project tasks and task comments , attachments,timer
                $project_tasks = $this->db->where('project_id', $id)->get('tbl_task')->result();
                if (!empty($project_tasks)) {
                    foreach ($project_tasks as $v_taks) {

                        $all_comments_info = $this->db->where(array('task_id' => $v_taks->task_id))->get('tbl_task_comment')->result();
                        if (!empty($all_comments_info)) {
                            foreach ($all_comments_info as $comments_info) {
                                if (!empty($comments_info->comments_attachment)) {
                                    $attachment = json_decode($comments_info->comments_attachment);
                                    foreach ($attachment as $v_file) {
                                        remove_files($v_file->fileName);
                                    }
                                }
                            }
                        }
                        //delete data into table.
                        $this->items_model->_table_name = "tbl_task_comment"; // table name
                        $this->items_model->delete_multiple(array('task_id' => $v_taks->task_id));

                        $this->items_model->_table_name = "tbl_task_attachment"; //table name
                        $this->items_model->_order_by = "task_id";
                        $files_info = $this->items_model->get_by(array('task_id' => $v_taks->task_id), FALSE);
                        if (!empty($files_info)) {
                            foreach ($files_info as $t_v_files) {
                                $uploadFileinfo = $this->db->where('task_attachment_id', $t_v_files->task_attachment_id)->get('tbl_task_uploaded_files')->result();
                                if (!empty($uploadFileinfo)) {
                                    foreach ($uploadFileinfo as $Fileinfo) {
                                        remove_files($Fileinfo->file_name);
                                    }
                                }
                                $this->items_model->_table_name = "tbl_task_uploaded_files"; //table name
                                $this->items_model->delete_multiple(array('task_attachment_id' => $t_v_files->task_attachment_id));
                            }
                        }
                        //delete into table.
                        $this->items_model->_table_name = "tbl_task_attachment"; // table name
                        $this->items_model->delete_multiple(array('task_id' => $v_taks->task_id));

                        //delete into table.
                        $this->items_model->_table_name = "tbl_tasks_timer"; // table name
                        $this->items_model->delete_multiple(array('task_id' => $v_taks->task_id));

                        $pin_info = $this->items_model->check_by(array('module_name' => 'tasks', 'module_id' => $v_taks->task_id), 'tbl_pinaction');
                        if (!empty($pin_info)) {
                            $this->items_model->_table_name = 'tbl_pinaction';
                            $this->items_model->delete_multiple(array('module_name' => 'tasks', 'module_id' => $v_taks->task_id));
                        }
                    }
                }

                $this->items_model->_table_name = "tbl_task"; // table name
                $this->items_model->delete_multiple(array('project_id' => $id));

                // deleted project bugs and bug comments , attachments,bug taks and everything
                $project_bugs = $this->db->where('project_id', $id)->get('tbl_bug')->result();
                if (!empty($project_bugs)) {
                    foreach ($project_bugs as $v_bugs) {

                        $all_comments_info = $this->db->where(array('bug_id' => $v_bugs->bug_id))->get('tbl_task_comment')->result();
                        if (!empty($all_comments_info)) {
                            foreach ($all_comments_info as $comments_info) {
                                if (!empty($comments_info->comments_attachment)) {
                                    $attachment = json_decode($comments_info->comments_attachment);
                                    foreach ($attachment as $v_file) {
                                        remove_files($v_file->fileName);
                                    }
                                }
                            }
                        }

                        //delete data into table.
                        $this->bugs_model->_table_name = "tbl_task_comment"; // table name
                        $this->bugs_model->delete_multiple(array('bug_id' => $v_bugs->bug_id));


                        $this->bugs_model->_table_name = "tbl_task_attachment"; //table name
                        $this->bugs_model->_order_by = "bug_id";
                        $files_info = $this->bugs_model->get_by(array('bug_id' => $v_bugs->bug_id), FALSE);

                        foreach ($files_info as $b_v_files) {
                            $uploadFileinfo = $this->db->where('task_attachment_id', $b_v_files->task_attachment_id)->get('tbl_task_uploaded_files')->result();
                            if (!empty($uploadFileinfo)) {
                                foreach ($uploadFileinfo as $Fileinfo) {
                                    remove_files($Fileinfo->file_name);
                                }
                            }
                            $this->bugs_model->_table_name = "tbl_task_uploaded_files"; //table name
                            $this->bugs_model->delete_multiple(array('task_attachment_id' => $b_v_files->task_attachment_id));
                        }
                        //delete into table.
                        $this->bugs_model->_table_name = "tbl_task_attachment"; // table name
                        $this->bugs_model->delete_multiple(array('bug_id' => $v_bugs->bug_id));

                        $this->bugs_model->_table_name = 'tbl_pinaction';
                        $this->bugs_model->delete_multiple(array('module_name' => 'bugs', 'module_id' => $v_bugs->bug_id));

                        $bug_tasks = $this->db->where('bug_id', $v_bugs->bug_id)->get('tbl_task')->result();
                        if (!empty($bug_tasks)) {
                            foreach ($bug_tasks as $tasks_bugs) {

                                $all_comments_info = $this->db->where(array('task_id' => $tasks_bugs->task_id))->get('tbl_task_comment')->result();
                                if (!empty($all_comments_info)) {
                                    foreach ($all_comments_info as $comments_info) {
                                        if (!empty($comments_info->comments_attachment)) {
                                            $attachment = json_decode($comments_info->comments_attachment);
                                            foreach ($attachment as $v_file) {
                                                remove_files($v_file->fileName);
                                            }
                                        }
                                    }
                                }
                                //delete data into table.
                                $this->items_model->_table_name = "tbl_task_comment"; // table name
                                $this->items_model->delete_multiple(array('task_id' => $tasks_bugs->task_id));

                                $this->items_model->_table_name = "tbl_task_attachment"; //table name
                                $this->items_model->_order_by = "task_id";
                                $files_info = $this->items_model->get_by(array('task_id' => $tasks_bugs->task_id), FALSE);
                                if (!empty($files_info)) {
                                    foreach ($files_info as $t_v_files) {
                                        $uploadFileinfo = $this->db->where('task_attachment_id', $t_v_files->task_attachment_id)->get('tbl_task_uploaded_files')->result();
                                        if (!empty($uploadFileinfo)) {
                                            foreach ($uploadFileinfo as $Fileinfo) {
                                                remove_files($Fileinfo->file_name);
                                            }
                                        }
                                        $this->items_model->_table_name = "tbl_task_uploaded_files"; //table name
                                        $this->items_model->delete_multiple(array('task_attachment_id' => $v_files->task_attachment_id));
                                    }
                                }
                                //delete into table.
                                $this->items_model->_table_name = "tbl_task_attachment"; // table name
                                $this->items_model->delete_multiple(array('task_id' => $tasks_bugs->task_id));

                                $pin_info = $this->items_model->check_by(array('module_name' => 'tasks', 'module_id' => $tasks_bugs->task_id), 'tbl_pinaction');
                                if (!empty($pin_info)) {
                                    $this->items_model->_table_name = 'tbl_pinaction';
                                    $this->items_model->delete_multiple(array('module_name' => 'tasks', 'module_id' => $tasks_bugs->task_id));
                                }


                            }
                        }
                        //save data into table.
                        $this->items_model->_table_name = "tbl_task"; // table name
                        $this->items_model->delete_multiple(array('bug_id' => $v_bugs->bug_id));

                    }
                }

                //delete the bugs
                $this->items_model->_table_name = "tbl_bug"; // table name
                $this->items_model->delete_multiple(array('project_id' => $id));

                // delete all project tickets ans tikcets reply with attachment
                $project_tickets = $this->db->where('project_id', $id)->get('tbl_tickets')->result();
                // deleted project comments with file
                if (!empty($project_tickets)) {
                    foreach ($project_tickets as $v_tickets) {

                        $tickets_reply = $this->db->where('tickets_id', $v_tickets->tickets_id)->get('tbl_tickets_replies')->result();
                        if (!empty($tickets_reply)) {
                            foreach ($tickets_reply as $v_ti_reply) {
                                if (!empty($v_ti_reply->attachment)) {
                                    $attachment = json_decode($v_ti_reply->attachment);
                                    foreach ($attachment as $v_file) {
                                        remove_files($v_file->fileName);
                                    }
                                }
                            }
                            //delete data into table.
                            $this->items_model->_table_name = "tbl_tickets_replies"; // table name
                            $this->items_model->delete_multiple(array('tickets_id' => $v_tickets->tickets_id));
                        }

                        if (!empty($v_tickets->upload_file)) {
                            $attachment = json_decode($v_tickets->upload_file);
                            foreach ($attachment as $v_file) {
                                remove_files($v_file->fileName);
                            }
                        }
                    }
                    //delete data into table.
                    $this->items_model->_table_name = "tbl_tickets"; // table name
                    $this->items_model->delete_multiple(array('project_id' => $id));
                }

                // delete all invoice and invoice items
                $project_invoice = $this->db->where('project_id', $id)->get('tbl_invoices')->result();
                if (!empty($project_invoice)) {
                    foreach ($project_invoice as $vp_invoice) {
                        $this->items_model->_table_name = "tbl_items"; // table name
                        $this->items_model->delete_multiple(array('invoices_id' => $vp_invoice->invoices_id));
                    }
                    $this->items_model->_table_name = "tbl_invoices"; // table name
                    $this->items_model->delete_multiple(array('project_id' => $id));
                }

                // delete all estimates and estimates items
                $project_estimate = $this->db->where('project_id', $id)->get('tbl_estimates')->result();
                if (!empty($project_estimate)) {
                    foreach ($project_estimate as $vp_estimate) {
                        $this->items_model->_table_name = "tbl_estimate_items"; // table name
                        $this->items_model->delete_multiple(array('estimates_id' => $vp_estimate->estimates_id));
                    }
                    $this->items_model->_table_name = "tbl_estimates"; // table name
                    $this->items_model->delete_multiple(array('project_id' => $id));
                }
                // delete all estimates and estimates items
                $project_expense = $this->db->where('project_id', $id)->get('tbl_transactions')->result();
                if (!empty($project_expense)) {
                    foreach ($project_expense as $vp_expense) {
                        $account_info = $this->items_model->check_by(array('account_id' => $vp_expense->account_id), 'tbl_accounts');

                        $ac_data['balance'] = $account_info->balance + $vp_expense->amount;
                        $this->items_model->_table_name = "tbl_accounts"; //table name
                        $this->items_model->_primary_key = "account_id";
                        $this->items_model->save($ac_data, $account_info->account_id);

                        $this->items_model->_table_name = "tbl_transactions"; //table name
                        $this->items_model->_primary_key = "transactions_id";
                        $this->items_model->delete($id);
                    }
                }

                //delete the timer from tbl_tasks_timer
                $this->items_model->_table_name = "tbl_tasks_timer"; // table name
                $this->items_model->delete_multiple(array('project_id' => $id));

                $this->items_model->_table_name = 'tbl_pinaction';
                $this->items_model->delete_multiple(array('module_name' => 'project', 'module_id' => $id));

                $this->items_model->_table_name = 'tbl_project';
                $this->items_model->_primary_key = 'project_id';
                $this->items_model->delete($id);

                $type = 'success';
                $message = lang('project_deleted');
            } 
        }

        return array('status'=>$type, 'message'=>$message);
    }

    function delete_task($input_data=NULL)
    {
        // messages for user
        $type = "error";
        $message = '';
        
        if(!empty($input_data)){
            $id=$input_data['id'];
            $can_delete = $this->tasks_model->can_action('tbl_task', 'delete', array('task_id' => $id));
            if (!empty($can_delete)) {
                $task_info = $this->tasks_model->check_by(array('task_id' => $id), 'tbl_task');

                // save into activities
                $activities = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'tasks',
                    'module_field_id' => $task_info->task_id,
                    'activity' => 'activity_task_deleted',
                    'icon' => 'fa-tasks',
                    'value1' => $task_info->task_name,
                );
                // Update into tbl_project
                $this->tasks_model->_table_name = "tbl_activities"; //table name
                $this->tasks_model->_primary_key = "activities_id";
                $this->tasks_model->save($activities);

                $this->tasks_model->_table_name = "tbl_task_attachment"; //table name
                $this->tasks_model->_order_by = "task_id";
                $files_info = $this->tasks_model->get_by(array('task_id' => $id), FALSE);

                foreach ($files_info as $v_files) {
                    $uploadFileinfo = $this->db->where('task_attachment_id', $v_files->task_attachment_id)->get('tbl_task_uploaded_files')->result();
                    if (!empty($uploadFileinfo)) {
                        foreach ($uploadFileinfo as $Fileinfo) {
                            remove_files($Fileinfo->file_name);
                        }
                    }
                    $this->tasks_model->_table_name = "tbl_task_uploaded_files"; //table name
                    $this->tasks_model->delete_multiple(array('task_attachment_id' => $v_files->task_attachment_id));
                }
                //delete into table.
                $this->tasks_model->_table_name = "tbl_task_attachment"; // table name
                $this->tasks_model->delete_multiple(array('task_id' => $id));

                // deleted comments with file
                $all_comments_info = $this->db->where(array('task_id' => $id))->get('tbl_task_comment')->result();
                if (!empty($all_comments_info)) {
                    foreach ($all_comments_info as $comments_info) {
                        if (!empty($comments_info->comments_attachment)) {
                            $attachment = json_decode($comments_info->comments_attachment);
                            foreach ($attachment as $v_file) {
                                remove_files($v_file->fileName);
                            }
                        }
                    }
                }
                //delete data into table.
                $this->tasks_model->_table_name = "tbl_task_comment"; // table name
                $this->tasks_model->delete_multiple(array('task_id' => $id));

                $pin_info = $this->tasks_model->check_by(array('module_name' => 'tasks', 'module_id' => $id), 'tbl_pinaction');
                if (!empty($pin_info)) {
                    $this->tasks_model->_table_name = 'tbl_pinaction';
                    $this->tasks_model->delete_multiple(array('module_name' => 'tasks', 'module_id' => $id));
                }
                //delete into table.
                $this->tasks_model->_table_name = "tbl_tasks_timer"; // table name
                $this->tasks_model->delete_multiple(array('task_id' => $id));

                $this->tasks_model->_table_name = "tbl_task"; // table name
                $this->tasks_model->_primary_key = "task_id"; // $id
                $this->tasks_model->delete($id);
                $type='success';
                $message=lang('task_deleted');
            } 
        }

        return array('status'=>$type, 'message'=>$message);
    }

    function delete_zoom($input_data=NULL)
    {
        // messages for user
        $type = "error";
        $message = '';
        
        if(!empty($input_data)){
            $id=$input_data['id'];
            $deleted = can_action_by_label('zoom', 'deleted');
            if (!empty($deleted)) {
                $field_info = $this->db->where('zoom_meeting_id', $id)->get('tbl_zoom_meeting')->row();
                
                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'settings',
                    'module_field_id' => $id,
                    'activity' => ('activity_delete_custom_field'),
                    'value1' => $field_info->topic
                );
                
                $this->admin_model->_table_name = 'tbl_activities';
                $this->admin_model->_primary_key = 'activities_id';
                $this->admin_model->save($activity);
                
                require_once(module_dirPath(ZOOM_MODULE) . 'libraries/ZoomAPI.php');
                
                $zoom = new ZoomAPI();
                $zoom->deleteMeeting($field_info->meetingId);
                
                $this->admin_model->_table_name = 'tbl_zoom_meeting';
                $this->admin_model->_primary_key = 'zoom_meeting_id';
                $this->admin_model->delete($id);
                // messages for user
                $type = "success";
                $message = lang('delete_meeting_info');
            } else {
                $type = "error";
                $message = lang('there_in_no_value');
            }
        }

        return array('status'=>$type, 'message'=>$message);
    }


}
