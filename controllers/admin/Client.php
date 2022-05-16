<?php

/**
 * Description of client
 *
 * @author NaYeM
 */
class Client extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('items_model');
        $this->load->model('client_model');
        $this->load->model('invoice_model');
        $this->load->model('estimates_model');
    }

    public function manage_client($id = NULL)
    {
        if (!empty($id)) {
            if (is_numeric($id)) {
                $data['active'] = 2;
                // get all Client info by client id
                $this->client_model->_table_name = "tbl_client"; //table name
                $this->client_model->_order_by = "client_id";
                $data['client_info'] = $this->client_model->get_by(array('client_id' => $id), TRUE);
                if (empty($data['client_info'])) {
                    set_message('error', lang('there_in_no_value'));
                    redirect('admin/client/manage_client');
                }
            } else {
                $data['active'] = 1;
            }
        } else {
            $data['active'] = 1;
        }
         
        
        $data['title'] = lang('manage_client'); //Page title
        $data['page'] = lang('client');

        // get all country
        $this->client_model->_table_name = "tbl_countries"; //table name
        $this->client_model->_order_by = "id";
        $data['countries'] = $this->client_model->get();

        // get all currencies
        $this->client_model->_table_name = 'tbl_currencies';
        $this->client_model->_order_by = 'name';
        $data['currencies'] = $this->client_model->get();
        // get all language
        $data['languages'] = $this->db->where('active', 1)->order_by('name', 'ASC')->get('tbl_languages')->result();
        $search_id = $this->uri->segment(5);

        if (!empty($search_id)) {
            if($id=='new'){
                $data['active'] = 2;
            }elseif ($id == 'group') {
                $where = array('customer_group_id' => $search_id);
                $data['all_client_info'] = get_result('tbl_client', $where);
            }elseif ($id == 'by_status') {
                if ($search_id == 'all') {
                    redirect('admin/client/manage_client');
                } else {
                    $where = array('status' => $search_id);
                    $data['all_client_info'] = get_result('tbl_client', $where);
                }
            } elseif ($id == 'by_source') {
                if ($search_id == 'all') {
                    redirect('admin/client/manage_client');
                } else {
                    $where = array('source' => $search_id);
                    $data['all_client_info'] = get_result('tbl_client', $where);
                }
            }
        } else {
            $data['all_client_info'] = get_result('tbl_client');
        }

        // get all leads status
        $status_info = get_result('tbl_lead_status');
        if (!empty($status_info)) {
            foreach ($status_info as $v_status) {
                $data['status_info'][$v_status->lead_type][] = $v_status;
            }
        }
        $data['assign_user'] = $this->items_model->allowad_user('4');
        $data['subview'] = $this->load->view('admin/client/manage_client', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public function change_client_status($id = null)
    {
        $data['active'] = $this->input->post('active', true);
        $this->client_model->_table_name = 'tbl_client';
        $this->client_model->_primary_key = "client_id";
        $this->client_model->save($data, $id);
        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'client',
            'module_field_id' => $id,
            'activity' => 'activity_change_status',
            'icon' => 'fa-ticket',
            'value1' => $data['active'],
        );
        // Update into tbl_project
        $this->client_model->_table_name = "tbl_activities"; //table name
        $this->client_model->_primary_key = "activities_id";
        $this->client_model->save($activities);
        $type = "success";
        $message = lang('update_client_status');
        echo json_encode(array("status" => $type, "message" => $message));
        exit();
    }

    public function multiple_client_change_status(){
        $client_status_id = $this->input->post('status') ;
        $client_field = $this->input->post('field') ;
        $client_ids = $this->input->post('client_id') ;
        if($client_ids){
            foreach ($client_ids as $key => $client_id) {
                $can_edit = $this->items_model->can_action('tbl_client', 'edit', array('client_id' => $client_id));
                if (!empty($can_edit)) {
                    $data[$client_field] = $client_status_id;
                    $this->items_model->_table_name = 'tbl_client';
                    $this->items_model->_primary_key = 'client_id';
                    $this->items_model->save($data, $client_id);

                    $client_info = $this->items_model->check_by(array('client_id' => $client_id), 'tbl_client');
                    $notifiedUsers = array();
                    if (!empty($client_info->permission) && $client_info->permission != 'all') {
                        $permissionUsers = json_decode($client_info->permission);
                        foreach ($permissionUsers as $user => $v_permission) {
                            array_push($notifiedUsers, $user);
                        }
                    } else {
                        $notifiedUsers = $this->items_model->allowad_user_id('4');
                    }
                    if (!empty($notifiedUsers)) {
                        foreach ($notifiedUsers as $users) {
                            if ($users != $this->session->userdata('user_id')) {
                                add_notification(array(
                                    'to_user_id' => $users,
                                    'from_user_id' => true,
                                    'description' => 'not_changed_status',
                                    'link' => 'admin/client/client_details/' . $client_info->client_id,
                                    'value' => lang('client') . ' ' . $client_info->contact_name,
                                ));
                            }
                        }
                        show_notification($notifiedUsers);
                    }

                    // messages for user
                    $type = "success";
                    $message = lang('change_status');
                    $data_return['success'] = true;
                    $data_return['message'] =  lang('change_status');
                }else{
                    $data_return['success'] = false;
                    $data_return['message'] =  lang('there_in_no_value');
                }
            }   
        }else{ 
            $data_return['success'] = false;
            $data_return['message'] =  lang('there_in_no_value');
        }
            
        echo json_encode($data_return);
        exit();
    }
    public function searchByGroup($id = NULL)
    {

        $data['active'] = 1;

        $data['title'] = lang('manage_client'); //Page title
        $data['page'] = lang('client');

        // get all country
        $this->client_model->_table_name = "tbl_countries"; //table name
        $this->client_model->_order_by = "id";
        $data['countries'] = $this->client_model->get();

        // get all currencies
        $this->client_model->_table_name = 'tbl_currencies';
        $this->client_model->_order_by = 'name';
        $data['currencies'] = $this->client_model->get();
        // get all language
        $data['languages'] = get_result('tbl_languages', array('active' => 1));

        $data['all_client_info'] = get_result('tbl_client', array('customer_group_id' => $id));

        $data['subview'] = $this->load->view('admin/client/manage_client', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public function import()
    {
        $data['title'] = lang('import') . ' ' . lang('client');
        // get all country
        $this->client_model->_table_name = "tbl_countries"; //table name
        $this->client_model->_order_by = "id";
        $data['countries'] = $this->client_model->get();

        // get all currencies
        $this->client_model->_table_name = 'tbl_currencies';
        $this->client_model->_order_by = 'name';
        $data['currencies'] = $this->client_model->get();
        // get all language
        $data['languages'] = get_result('tbl_languages', array('active' => 1));;

        $data['subview'] = $this->load->view('admin/client/import_client', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public function save_imported()
    {
        //load the excel library
        $this->load->library('excel');
        ob_start();
        $file = $_FILES["upload_file"]["tmp_name"];
        if (!empty($file)) {
            $valid = false;
            $types = array('Excel2007', 'Excel5');
            foreach ($types as $type) {
                $reader = PHPExcel_IOFactory::createReader($type);
                if ($reader->canRead($file)) {
                    $valid = true;
                }
            }
            if (!empty($valid)) {
                try {
                    $objPHPExcel = PHPExcel_IOFactory::load($file);
                } catch (Exception $e) {
                    die("Error loading file :" . $e->getMessage());
                }
                //All data from excel
                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

                for ($x = 2; $x <= count($sheetData); $x++) {
                    // **********************
                    // Save Into leads table
                    // **********************
                    $data = $this->client_model->array_from_post(array('customer_group_id', 'vat', 'language', 'currency', 'country'));

                    $data['name'] = trim($sheetData[$x]["A"]);
                    $data['email'] = trim($sheetData[$x]["B"]);
                    $data['short_note'] = trim($sheetData[$x]["C"]);
                    $data['phone'] = trim($sheetData[$x]["D"]);
                    $data['mobile'] = trim($sheetData[$x]["E"]);
                    $data['fax'] = trim($sheetData[$x]["F"]);
                    $data['city'] = trim($sheetData[$x]["G"]);
                    $data['zipcode'] = trim($sheetData[$x]["H"]);
                    $data['address'] = trim($sheetData[$x]["I"]);
                    $data['skype_id'] = trim($sheetData[$x]["J"]);
                    $data['twitter'] = trim($sheetData[$x]["K"]);
                    $data['facebook'] = trim($sheetData[$x]["L"]);
                    $data['linkedin'] = trim($sheetData[$x]["M"]);
                    $data['hosting_company'] = trim($sheetData[$x]["N"]);
                    $data['hostname'] = trim($sheetData[$x]["O"]);
                    $data['username'] = trim($sheetData[$x]["P"]);
                    $data['password'] = trim($sheetData[$x]["Q"]);
                    $data['port'] = trim($sheetData[$x]["R"]);

                    plan_capability('client');

                    $this->client_model->_table_name = 'tbl_client';
                    $this->client_model->_primary_key = "client_id";
                    $id = $this->client_model->save($data);

                    $action = ('activity_update_company');
                    $activities = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'client',
                        'module_field_id' => $id,
                        'activity' => $action,
                        'icon' => 'fa-user',
                        'value1' => $data['name']
                    );
                    $this->client_model->_table_name = 'tbl_activities';
                    $this->client_model->_primary_key = "activities_id";
                    $this->client_model->save($activities);
                }
            } else {
                $type = 'error';
                $message = "Sorry your uploaded file type not allowed ! please upload XLS/CSV File ";
            }
        } else {
            $type = 'error';
            $message = "You did not Select File! please upload XLS/CSV File ";
        }
        set_message($type, $message);
        redirect('admin/client/manage_client');

    }


    public function save_client($id = NULL)
    {

        $data = $this->client_model->array_from_post(array('name', 'email', 'short_note', 'website', 'phone', 'mobile', 'fax', 'address', 'city', 'zipcode', 'currency',
            'skype_id', 'linkedin', 'facebook', 'twitter', 'language', 'country', 'vat', 'hosting_company', 'hostname', 'port', 'username', 'latitude', 'longitude', 'customer_group_id','status','source'));

        $password = $this->input->post('password', true);
        if (!empty($password)) {
            $data['password'] = encrypt($password);
        }
        if (empty($id)) {
            $data['leads_id'] = 0;
        }
        if (empty($data['active'])) {
            $data['active'] = 1;
        }

        if (!empty($_FILES['profile_photo']['name'])) {
            $val = $this->client_model->uploadImage('profile_photo');
            $val == TRUE || redirect('admin/client/manage_client');
            $data['profile_photo'] = $val['path'];
        }
        if (empty($id)) {
            plan_capability('client');
        }
        $permission = $this->input->post('permission', true);
        if (!empty($permission)) {
            if ($permission == 'everyone') {
                $assigned = 'all';
            } else {
                $assigned_to = $this->items_model->array_from_post(array('assigned_to'));
                if (!empty($assigned_to['assigned_to'])) {
                    foreach ($assigned_to['assigned_to'] as $assign_user) {
                        $assigned[$assign_user] = $this->input->post('action_' . $assign_user, true);
                    }
                }
            }
            if (!empty($assigned)) {
                if ($assigned != 'all') {
                    $assigned = json_encode($assigned);
                }
            } else {
                $assigned = 'all';
            }
            $data['permission'] = $assigned;
        } else {
            set_message('error', lang('assigned_to') . ' Field is required');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $this->client_model->_table_name = 'tbl_client';
        $this->client_model->_primary_key = "client_id";
        $return_id = $this->client_model->save($data, $id);
        if (!empty($id)) {
            $id = $id;
            $action = ('activity_added_new_company');
        } else {
            $id = $return_id;
            $action = ('activity_update_company');
        }
        save_custom_field(12, $id);

        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'client',
            'module_field_id' => $id,
            'activity' => $action,
            'icon' => 'fa-user',
            'value1' => $data['name']
        );
        $this->client_model->_table_name = 'tbl_activities';
        $this->client_model->_primary_key = "activities_id";
        $this->client_model->save($activities);
        // messages for user
        $type = "success";
        $message = lang('client_updated');
        set_message($type, $message);
        $save_and_create_contact = $this->input->post('save_and_create_contact', true);
        if (!empty($save_and_create_contact)) {
            redirect('admin/client/client_details/' . $id . '/add_contacts');
        } else {
            redirect('admin/client/manage_client');
        }

    }

    public function see_password($type = null)
    {
        $data['title'] = lang('see_password');
        $data['ids'] = null;
        $data['password'] = null;
        $data['row'] = null;
        if (!empty($type) && !is_numeric($type)) {
            $ex = explode('_', $type);
            if ($ex[0] == 'c') {
                $data['password'] = get_row('tbl_client', array('client_id' => $ex[1]), 'password');
            } elseif ($ex[0] == 'smtp') {
                $data['password'] = config_item('smtp_pass');
            } elseif ($ex[0] == 'emin') {
                $data['password'] = config_item('config_password');
            } elseif ($ex[0] == 'paypalpassword') {
                $data['password'] = config_item('paypal_api_password');
            } elseif ($ex[0] == 'activationtoken') {
                $plan_info = get_old_data('tbl_subscriptions', array('subscriptions_id' => $ex[1]));
                $default_url = preg_replace('#^https?://#', '', rtrim(config_item('default_url'), '/'));
                $sub_domain = (isset($_SERVER['HTTPS']) ? "https://" : "http://");
                $sub_domain .= $plan_info->domain . '.';
                $sub_domain .= $default_url;
                $data['row'] = true;
//                $data['password'] = $sub_domain;
                $data['password'] = '<span>' . ($plan_info->activation_tocken) . '<br/>' . ('url') . ': <a target="_blank" href="' . $sub_domain . '">' . $sub_domain . '</a></span>';
                $data['activationtoken'] = true;
            } elseif ($ex[0] == 'database') {
                $data['password'] = encrypt($ex[1] . '_' . $ex[2]);
            } elseif ($ex[0] == 'cpanel') {
                $data['password'] = config_item('saas_cpanel_password');
            } elseif ($ex[0] == 'plesk') {
                $data['password'] = config_item('saas_plesk_password');
            }
        } else {
            $data['password'] = null;
        }
        $data['subview'] = $this->load->view('admin/settings/see_password', $data, FALSE);
        $this->load->view('admin/_layout_skote_modal', $data);
    }

    public function customer_group()
    {
        $data['title'] = lang('customer_group');
        $data['subview'] = $this->load->view('admin/client/customer_group', $data, FALSE);
        $this->load->view('admin/_layout_skote_modal', $data);
    }

    public function update_customer_group($id = null)
    {
        $this->client_model->_table_name = 'tbl_customer_group';
        $this->client_model->_primary_key = 'customer_group_id';

        $cate_data['customer_group'] = $this->input->post('customer_group', TRUE);
        $cate_data['description'] = $this->input->post('description', TRUE);
        $cate_data['type'] = 'client';

        // update root category
        $where = array('type' => 'client', 'customer_group' => $cate_data['customer_group']);
        // duplicate value check in DB
        if (!empty($id)) { // if id exist in db update data
            $customer_group_id = array('customer_group_id !=' => $id);
        } else { // if id is not exist then set id as null
            $customer_group_id = null;
        }
        // check whether this input data already exist or not
        $check_category = $this->client_model->check_update('tbl_customer_group', $where, $customer_group_id);
        if (!empty($check_category)) { // if input data already exist show error alert
            // massage for user
            $type = 'error';
            $msg = "<strong style='color:#000'>" . $cate_data['customer_group'] . '</strong>  ' . lang('already_exist');
        } else { // save and update query
            $id = $this->client_model->save($cate_data, $id);

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('customer_group_added'),
                'value1' => $cate_data['customer_group']
            );
            $this->client_model->_table_name = 'tbl_activities';
            $this->client_model->_primary_key = 'activities_id';
            $this->client_model->save($activity);

            // messages for user
            $type = "success";
            $msg = lang('customer_group_added');
        }
        if (!empty($id)) {
            $result = array(
                'id' => $id,
                'group' => $cate_data['customer_group'],
                'status' => $type,
                'message' => $msg,
            );
        } else {
            $result = array(
                'status' => $type,
                'message' => $msg,
            );
        }
        echo json_encode($result);
        exit();
    }


    public function client_details($id, $action = null)
    {
        if ($action == 'add_contacts') {
            // get all language
            $data['languages'] = get_order_by('tbl_languages', array('active' => 1), 'name', true);
            // get all location
            $this->client_model->_table_name = 'tbl_locales';
            $this->client_model->_order_by = 'name';
            $data['locales'] = $this->client_model->get();
            $data['company'] = $id;
            $user_id = $this->uri->segment(6);
            if (!empty($user_id)) {
                // get all user_info by user id
                $data['account_details'] = $this->client_model->check_by(array('user_id' => $user_id), 'tbl_account_details');
                $data['user_info'] = $this->client_model->check_by(array('user_id' => $user_id), 'tbl_users');
            }

        }
        $data['title'] = "View Client Details"; //Page title
        // get all client details
        $this->client_model->_table_name = "tbl_client"; //table name
        $this->client_model->_order_by = "client_id";
        $data['client_details'] = $this->client_model->get_by(array('client_id' => $id), TRUE);
        
        if (empty($data['client_details'])) {
            set_message('error', lang('there_in_no_value'));
            redirect('admin/client/manage_client');
        }
        // get all invoice by client id
        $this->client_model->_table_name = "tbl_invoices"; //table name
        $this->client_model->_order_by = "client_id";
        $data['client_invoices'] = $this->client_model->get_by(array('client_id' => $id), FALSE);

        // get all estimates by client id
        $this->client_model->_table_name = "tbl_estimates"; //table name
        $this->client_model->_order_by = "client_id";
        $data['client_estimates'] = $this->client_model->get_by(array('client_id' => $id), FALSE);

        // get client contatc by client id
        $data['client_contacts'] = $this->client_model->get_client_contacts($id);

        $data['subview'] = $this->load->view('admin/client/client_details', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public function update_users($id)
    {
        // get all assign_user
        $can_edit = $this->items_model->can_action('tbl_client', 'edit', array('client_id' => $id));
        if (!empty($can_edit)) {
            // get permission user by menu id
            $data['assign_user'] = $this->items_model->allowad_user('4');

            $data['client_info'] = $this->items_model->check_by(array('client_id' => $id), 'tbl_client');
            $data['modal_subview'] = $this->load->view('admin/client/_modal_users', $data, FALSE);
            $this->load->view('admin/_layout_skote_modal', $data);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public
    function update_member($id)
    {
        $can_edit = $this->items_model->can_action('tbl_client', 'edit', array('client_id' => $id));
        if (!empty($can_edit)) {
            $client_info = $this->items_model->check_by(array('client_id' => $id), 'tbl_client');

            $permission = $this->input->post('permission', true);
            if (!empty($permission)) {

                if ($permission == 'everyone') {
                    $assigned = 'all';
                } else {
                    $assigned_to = $this->items_model->array_from_post(array('assigned_to'));
                    if (!empty($assigned_to['assigned_to'])) {
                        foreach ($assigned_to['assigned_to'] as $assign_user) {
                            $assigned[$assign_user] = $this->input->post('action_' . $assign_user, true);
                        }
                    }
                }
                if (!empty($assigned)) {
                    if ($assigned != 'all') {
                        $assigned = json_encode($assigned);
                    }
                } else {
                    $assigned = 'all';
                }
                $data['permission'] = $assigned;
            } else {
                set_message('error', lang('assigned_to') . ' Field is required');
                redirect($_SERVER['HTTP_REFERER']);
            }

            //save data into table.
            $this->items_model->_table_name = "tbl_client"; // table name
            $this->items_model->_primary_key = "client_id"; // $id
            $this->items_model->save($data, $id);

            $msg = lang('update_client');
            $activity = 'activity_update_client';

            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'client',
                'module_field_id' => $id,
                'activity' => $activity,
                'icon' => 'fa-rocket',
                'link' => 'admin/client/client_details/' . $id,
                'value1' => $client_info->name,
            );
            // Update into tbl_project
            $this->items_model->_table_name = "tbl_activities"; //table name
            $this->items_model->_primary_key = "activities_id";
            $this->items_model->save($activities);

            $notifiedUsers = array();
            if (!empty($client_info->permission) && $client_info->permission != 'all') {
                $permissionUsers = json_decode($client_info->permission);
                foreach ($permissionUsers as $user => $v_permission) {
                    array_push($notifiedUsers, $user);
                }
            } else {
                $notifiedUsers = $this->items_model->allowad_user_id('4');
            }
            if (!empty($notifiedUsers)) {
                foreach ($notifiedUsers as $users) {
                    if ($users != $this->session->userdata('user_id')) {
                        add_notification(array(
                            'to_user_id' => $users,
                            'from_user_id' => true,
                            'description' => 'assign_to_you_the_client',
                            'link' => 'admin/client/client_details/' . $client_info->client_id,
                            'value' => lang('client') . ' ' . $client_info->name,
                        ));
                    }
                }
                show_notification($notifiedUsers);
            }

            $type = "success";
            $message = $msg;
            set_message($type, $message);
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function multiple_client_update_users()
    {
        // get permission user by menu id
        $data['assign_user'] = $this->items_model->allowad_user('4');

        $data['modal_subview'] = $this->load->view('admin/client/_multiple_client_modal_users', $data, FALSE);
        $this->load->view('admin/_layout_skote_modal', $data);
    }

    public function mutiple_client_update_member()
    {
        $client_ids= $this->input->post('client_id');
        if($client_ids){
            foreach ($client_ids as $key => $id) {
                $can_edit = $this->items_model->can_action('tbl_client', 'edit', array('client_id' => $id));
                if (!empty($can_edit)) {
                    $client_info = $this->items_model->check_by(array('client_id' => $id), 'tbl_client');

                    $permission = $this->input->post('permission', true);
                    $assigned =[];
                    if (!empty($client_info->permission) && $client_info->permission != 'all') {
                        $get_permission = json_decode($client_info->permission);
                        if (is_object($get_permission)) {
                            foreach ($get_permission as $user_id => $v_permission) {
                                $assigned[$user_id] = $v_permission;
                            }
                        }
                    }


                    if (!empty($permission)) {
                        if ($permission == 'everyone') {
                            $assigned = 'all';
                            $assigned_to['assigned_to'] = $this->items_model->allowad_user_id('4');
                        } else {
                            $assigned_to = $this->items_model->array_from_post(array('assigned_to'));
                            if (!empty($assigned_to['assigned_to'])) {
                                foreach ($assigned_to['assigned_to'] as $assign_user) {
                                    $assigned[$assign_user] = $this->input->post('action_' . $assign_user, true);
                                }
                            }
                        }
                        if (is_array($assigned)) {
                             // if ($assigned != 'all') {
                                $assigned = json_encode($assigned);
                             // }
                        } else {
                            $assigned = 'all';
                        }

                        $data['permission'] = $assigned;
                         
                    } else {
                        set_message('error', lang('assigned_to') . ' Field is required');
                        // redirect($_SERVER['HTTP_REFERER']);
                    }
                    
                    //save data into table.
                    $this->items_model->_table_name = "tbl_client"; // table name
                    $this->items_model->_primary_key = "client_id"; // $id
                    $this->items_model->save($data, $id);

                    $msg = lang('update_client');
                    $activity = 'activity_update_client';

                    // save into activities
                    $activities = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'client',
                        'module_field_id' => $id,
                        'activity' => $activity,
                        'icon' => 'fa-rocket',
                        'link' => 'admin/client/client_details/' . $id,
                        'value1' => $client_info->name,
                    );
                    // Update into tbl_project
                    $this->items_model->_table_name = "tbl_activities"; //table name
                    $this->items_model->_primary_key = "activities_id";
                    $this->items_model->save($activities);

                    $notifiedUsers = array();
                    if (!empty($client_info->permission) && $client_info->permission != 'all') {
                        $permissionUsers = json_decode($client_info->permission);
                        foreach ($permissionUsers as $user => $v_permission) {
                            array_push($notifiedUsers, $user);
                        }
                    } else {
                        $notifiedUsers = $this->items_model->allowad_user_id('4');
                    }
                    if (!empty($notifiedUsers)) {
                        foreach ($notifiedUsers as $users) {
                            if ($users != $this->session->userdata('user_id')) {
                                add_notification(array(
                                    'to_user_id' => $users,
                                    'from_user_id' => true,
                                    'description' => 'assign_to_you_the_client',
                                    'link' => 'admin/client/client_details/' . $client_info->client_id,
                                    'value' => lang('client') . ' ' . $client_info->name,
                                ));
                            }
                        }
                        show_notification($notifiedUsers);
                    }
                } else {
                    set_message('error', lang('there_in_no_value'));
                   // redirect($_SERVER['HTTP_REFERER']);
                }
            }
            $type = "success";
            $message = $msg;
            set_message($type, $message);
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }




    public function elfinder_init($client_id)
    {
        $this->load->helper('path');
        $_allowed_files = explode('|', config_item('allowed_files'));
        $config_allowed_files = array();
        if (is_array($_allowed_files)) {
            foreach ($_allowed_files as $v_extension) {
                array_push($config_allowed_files, '.' . $v_extension);
            }
        }
        $allowed_files = array();
        if (is_array($config_allowed_files)) {
            foreach ($config_allowed_files as $extension) {
                $_mime = get_mime_by_extension($extension);
                if ($_mime == 'application/x-zip') {
                    array_push($allowed_files, 'application/zip');
                }
                if ($extension == '.exe') {
                    array_push($allowed_files, 'application/x-executable');
                    array_push($allowed_files, 'application/x-msdownload');
                    array_push($allowed_files, 'application/x-ms-dos-executable');
                }
                array_push($allowed_files, $_mime);
            }
        }
        $client_info = $this->db->where('client_id', $client_id)->get('tbl_client')->row();
        $c_slug = slug_it($client_info->name);
        $path = set_realpath('filemanager/' . $c_slug);
        $root_options = array(
            'driver' => 'LocalFileSystem',
//            'path' => $path,
//            'URL' => site_url('-') . '/' . $c_slug . '/',
            'uploadMaxSize' => config_item('max_file_size') . 'M',
            'accessControl' => 'access',
            'uploadAllow' => $allowed_files,
            'uploadOrder' => array(
                'allow',
                'deny'
            ),
            'attributes' => array(
                array(
                    'pattern' => '/.tmb/',
                    'hidden' => true
                ),
                array(
                    'pattern' => '/.quarantine/',
                    'hidden' => true
                )
            )
        );
        $client_contacts = $this->client_model->get_client_contacts($client_id);
        if (!empty($client_contacts)) {
            foreach ($client_contacts as $contact) {
                $c_slug = slug_it($client_info->name);
                $path = set_realpath('filemanager/' . $c_slug);
                if (!is_dir($path)) {
                    mkdir($path);
                }
                $c_path = set_realpath('filemanager/' . $c_slug . '/' . $contact->media_path_slug);
                if (empty($contact->media_path_slug)) {
                    $this->db->where('user_id', $contact->user_id);
                    $slug = slug_it($contact->username);
                    $this->db->update('tbl_users', array(
                        'media_path_slug' => $slug
                    ));
                    $contact->media_path_slug = $slug;
                    $c_path = set_realpath('filemanager/' . $c_slug . '/' . $contact->media_path_slug);
                }
                if (!is_dir($c_path)) {
                    mkdir($c_path);
                }
                if (!file_exists($c_path . '/index.html')) {
                    fopen($c_path . '/index.html', 'w');
                }
                array_push($root_options['attributes'], array(
                    'pattern' => '/.(' . $contact->media_path_slug . '+)/', // Prevent deleting/renaming folder
                    'read' => true,
                    'write' => true,
                ));
                $root_options['path'] = $path;
                $root_options['URL'] = site_url('filemanager/' . $contact->media_path_slug) . '/';

                $opts = array(
                    'roots' => array(
                        $root_options
                    )
                );

                $this->load->library('elfinder_lib', $opts);
            }
        }
    }

    public function save_contact($id = NULL)
    {
        $data = $this->client_model->array_from_post(array('fullname', 'company', 'phone', 'mobile', 'skype', 'language', 'locale', 'direction'));
        if (!empty($id)) {
            $u_data['email'] = $this->input->post('email', TRUE);
            $u_data['last_ip'] = $this->input->ip_address();
            $this->client_model->_table_name = 'tbl_users';
            $this->client_model->_primary_key = 'user_id';
            $user_id = $this->client_model->save($u_data, $id);
            $data['user_id'] = $user_id;
            $acount_info = $this->client_model->check_by(array('user_id' => $id), 'tbl_account_details');

            $this->client_model->_table_name = 'tbl_account_details';
            $this->client_model->_primary_key = 'account_details_id';
            $return_id = $this->client_model->save($data, $acount_info->account_details_id);

            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'client',
                'module_field_id' => $id,
                'activity' => ('activity_update_contact'),
                'icon' => 'fa-user',
                'value1' => $data['fullname']
            );
            $this->client_model->_table_name = 'tbl_activities';
            $this->client_model->_primary_key = "activities_id";
            $this->client_model->save($activities);
        } else {
            $user_data = $this->client_model->array_from_post(array('email', 'username', 'password'));
            $u_data['last_ip'] = $this->input->ip_address();
            $check_email = $this->client_model->check_by(array('email' => $user_data['email']), 'tbl_users');
            $check_username = $this->client_model->check_by(array('username' => $user_data['username']), 'tbl_users');

            if ($user_data['password'] == $this->input->post('confirm_password', TRUE)) {
                $u_data['password'] = $this->hash($user_data['password']);

                if (!empty($check_username)) {
                    $message['error'][] = lang('this_username_already_exist');
                } else {
                    $u_data['username'] = $user_data['username'];
                }
                if (!empty($check_email)) {
                    $message['error'][] = lang('this_email_already_exist');
                } else {
                    $u_data['email'] = $user_data['email'];
                }
            } else {
                $message['error'][] = lang('password_does_not_macth');
            }

            if (!empty($u_data['password']) && !empty($u_data['username']) && !empty($u_data['email'])) {
                $u_data['role_id'] = $this->input->post('role_id', true);
                $u_data['activated'] = '1';

                $this->client_model->_table_name = 'tbl_users';
                $this->client_model->_primary_key = 'user_id';
                $user_id = $this->client_model->save($u_data, $id);

                $data['user_id'] = $user_id;

                $this->client_model->_table_name = 'tbl_account_details';
                $this->client_model->_primary_key = 'account_details_id';
                $return_id = $this->client_model->save($data, $id);
                // check primary contact
                $primary_contact = $this->client_model->check_by(array('client_id' => $data['company']), 'tbl_client');

                if ($primary_contact->primary_contact == 0) {
                    $c_data['primary_contact'] = $return_id;
                    $this->client_model->_table_name = 'tbl_client';
                    $this->client_model->_primary_key = 'client_id';
                    $this->client_model->save($c_data, $data['company']);
                }
                if ($this->input->post('send_email_password') == 'on') {
                    $this->send_confirmation_email($u_data, $user_data['password']); //send thank you email
                }
//                $send_email_password = $this->input->post('send_email_password', true);
//                if (!empty($send_email_password)) {
//
//                    $email_template = $this->client_model->check_by(array('email_group' => 'registration'), 'tbl_email_templates');
//                    $SITE_URL = str_replace("{SITE_URL}", base_url(), $email_template->template_body);
//                    $username = str_replace("{USERNAME}", $u_data['username'], $SITE_URL);
//                    $user_email = str_replace("{EMAIL}", $u_data['email'], $username);
//
//                    $user_password = str_replace("{PASSWORD}", $user_data['password'], $user_email);
//                    $message = str_replace("{SITE_NAME}", config_item('company_name'), $user_password);
//
//                    $params['recipient'] = $u_data['email'];
//                    $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $email_template->subject;
//                    $params['message'] = $message;
//                    $params['resourceed_file'] = '';
//
//                    $this->client_model->send_email($params);
//                }
                $activities = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'client',
                    'module_field_id' => $id,
                    'activity' => ('activity_added_new_contact'),
                    'icon' => 'fa-user',
                    'value1' => $data['fullname']
                );
                $this->client_model->_table_name = 'tbl_activities';
                $this->client_model->_primary_key = "activities_id";
                $this->client_model->save($activities);
            }
        }
        if (!empty($user_id)) {
            $this->client_model->_table_name = 'tbl_client_role'; //table name
            $this->client_model->delete_multiple(array('user_id' => $user_id));

            $all_client_menu = result_by_company('tbl_client_menu');

            foreach ($all_client_menu as $v_client_menu) {
                $client_role_data['menu_id'] = $this->input->post($v_client_menu->label, true);
                if (!empty($client_role_data['menu_id'])) {
                    $client_role_data['user_id'] = $user_id;
                    $this->client_model->_table_name = 'tbl_client_role';
                    $this->client_model->_primary_key = 'client_role_id';
                    $this->client_model->save($client_role_data);
                }
            }
        }
        // messages for user
        $message['success'] = lang('contact_information_successfully_update');
        if (!empty($message['error'])) {
            $this->session->set_userdata($message);
        } else {
            set_message('success', lang('contact_information_successfully_update'));
        }
        if (!empty($data['company'])) {
            redirect('admin/client/client_details/' . $data['company']);
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }

    }

    function send_confirmation_email($u_data, $password)
    {
        $email_template = $this->client_model->check_by(array('email_group' => 'registration'), 'tbl_email_templates');
        $SITE_URL = str_replace("{SITE_URL}", base_url(), $email_template->template_body);
        $username = str_replace("{USERNAME}", $u_data['username'], $SITE_URL);
        $user_email = str_replace("{EMAIL}", $u_data['email'], $username);

        $user_password = str_replace("{PASSWORD}", $password, $user_email);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $user_password);

        $params['recipient'] = $u_data['email'];
        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $email_template->subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';

        $this->client_model->send_email($params);
    }

    public function make_primary($user_id, $client_id)
    {
        $user_info = $this->client_model->check_by(array('user_id' => $user_id), 'tbl_account_details');

        $this->db->set('primary_contact', $user_id);
        $this->db->where('client_id', $client_id)->update('tbl_client');
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'client',
            'module_field_id' => $client_id,
            'activity' => ('activity_primary_contact'),
            'icon' => 'fa-user',
            'value1' => $user_info->fullname
        );
        $this->client_model->_table_name = 'tbl_activities';
        $this->client_model->_primary_key = "activities_id";
        $this->client_model->save($activities);

        // messages for user
        $type = "success";
        $message = lang('primary_contact_set');
        set_message($type, $message);
        redirect('admin/client/client_details/' . $client_id);
    }

    public function delete_contacts($client_id, $id)
    {
        $sbtn = $this->input->post('submit', true);
        if (!empty($sbtn)) {
            // delete into user table by user id
            $this->client_model->_table_name = 'tbl_client';
            $this->client_model->_order_by = 'primary_contact';
            $primary_contact = $this->client_model->get_by(array('primary_contact' => $id), TRUE);
            if (!empty($primary_contact)) {
                // delete into user table by user id
                $this->client_model->_table_name = 'tbl_account_details';
                $this->client_model->_order_by = 'company';
                $client_info = $this->client_model->get_by(array('company' => $client_id), FALSE);
                $result = count($client_info);
                if ($result != '1') {
                    $data['primary_contact'] = $client_info[1]->account_details_id;
                } else {
                    $data['primary_contact'] = 0;
                }
                $this->client_model->_table_name = 'tbl_client';
                $this->client_model->_primary_key = 'primary_contact';
                $this->client_model->save($data, $client_id);
            }
            $user_info = $this->client_model->check_by(array('user_id' => $id), 'tbl_account_details');
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'client',
                'module_field_id' => $id,
                'activity' => ('activity_deleted_contact'),
                'icon' => 'fa-user',
                'value1' => $user_info->fullname
            );
            $this->client_model->_table_name = 'tbl_account_details';
            $this->client_model->delete_multiple(array('user_id' => $id));

            $cwhere = array('user_id' => $id);
            $this->client_model->_table_name = 'tbl_private_chat';
            $this->client_model->delete_multiple($cwhere);

            $this->client_model->_table_name = 'tbl_private_chat_users';
            $this->client_model->delete_multiple($cwhere);

            $this->client_model->_table_name = 'tbl_private_chat_messages';
            $this->client_model->delete_multiple($cwhere);

            $this->client_model->_table_name = 'tbl_activities';
            $this->client_model->delete_multiple(array('user' => $id));

            $this->client_model->_table_name = 'tbl_payments';
            $this->client_model->delete_multiple(array('paid_by' => $id));

            // delete all tbl_quotations by id
            $this->client_model->_table_name = 'tbl_quotations';
            $this->client_model->_order_by = 'user_id';
            $quotations_info = $this->client_model->get_by(array('user_id' => $id), FALSE);

            if (!empty($quotations_info)) {
                foreach ($quotations_info as $v_quotations) {
                    $this->client_model->_table_name = 'tbl_quotation_details';
                    $this->client_model->delete_multiple(array('quotations_id' => $v_quotations->quotations_id));
                }
            }
            $this->client_model->_table_name = 'tbl_quotations';
            $this->client_model->delete_multiple(array('user_id' => $id));

            $this->client_model->_table_name = 'tbl_quotationforms';
            $this->client_model->delete_multiple(array('quotations_created_by_id' => $id));
            $this->client_model->_table_name = 'tbl_users';
            $this->client_model->delete_multiple(array('user_id' => $id));

            $this->client_model->_table_name = 'tbl_user_role';
            $this->client_model->delete_multiple(array('designations_id' => $id));

            $this->client_model->_table_name = 'tbl_inbox';
            $this->client_model->delete_multiple(array('user_id' => $id));

            $this->client_model->_table_name = 'tbl_sent';
            $this->client_model->delete_multiple(array('user_id' => $id));

            $this->client_model->_table_name = 'tbl_draft';
            $this->client_model->delete_multiple(array('user_id' => $id));

            $this->client_model->_table_name = 'tbl_tickets';
            $this->client_model->delete_multiple(array('reporter' => $id));

            $this->client_model->_table_name = 'tbl_tickets_replies';
            $this->client_model->delete_multiple(array('replierid' => $id));

            // messages for user
            $type = "success";
            $message = lang('delete_contact');
            set_message($type, $message);
            redirect('admin/client/client_details/' . $client_id);
        } else {
            $data['title'] = "Delete Client Contact"; //Page title
            $data['user_info'] = get_row('tbl_account_details', array('user_id' => $id));
            $data['client_id'] = $client_id;
            $data['subview'] = $this->load->view('admin/user/delete_user', $data, TRUE);
            $this->load->view('admin/_layout_skote_main', $data); //page load
        }
    }

    public
    function delete_client($client_id, $yes = null)
    {
        $sbtn = $this->input->post('submit', true);
        if (!empty($sbtn) && !empty($yes)) {
            // delete into user table by user id
            $this->client_model->_table_name = 'tbl_account_details';
            $this->client_model->_order_by = 'company';
            $client_info = $this->client_model->get_by(array('company' => $client_id), FALSE);
            if (!empty($client_info)) {
                foreach ($client_info as $v_client) {
                    $this->client_model->delete_multiple(array('account_details_id' => $v_client->account_details_id));

                    $this->client_model->_table_name = 'tbl_users';
                    $this->client_model->delete_multiple(array('user_id' => $v_client->user_id));

                    $this->client_model->_table_name = 'tbl_inbox';
                    $this->client_model->delete_multiple(array('user_id' => $v_client->user_id));

                    $this->client_model->_table_name = 'tbl_sent';
                    $this->client_model->delete_multiple(array('user_id' => $v_client->user_id));

                    $this->client_model->_table_name = 'tbl_draft';
                    $this->client_model->delete_multiple(array('user_id' => $v_client->user_id));

                    $this->client_model->_table_name = 'tbl_tickets';
                    $this->client_model->delete_multiple(array('reporter' => $v_client->user_id));
                    //save data into table.
                    // Bugs
                    $bugs_info = get_result('tbl_bug', array('reporter' => $v_client->user_id));
                    if (!empty($bugs_info)) {
                        foreach ($bugs_info as $v_bugs) {
                            $this->client_model->_table_name = "tbl_task_attachment"; //table name
                            $this->client_model->_order_by = "bug_id";
                            $files_info = $this->client_model->get_by(array('bug_id' => $v_bugs->bug_id), FALSE);
                            foreach ($files_info as $v_files) {
                                $this->client_model->_table_name = "tbl_task_uploaded_files"; //table name
                                $this->client_model->delete_multiple(array('task_attachment_id' => $v_files->task_attachment_id));
                            }
                            //delete into table.
                            $this->client_model->_table_name = "tbl_task_attachment"; // table name
                            $this->client_model->delete_multiple(array('bug_id' => $v_bugs->bug_id));

                            //delete data into table.
                            $this->client_model->_table_name = "tbl_task_comment"; // table name
                            $this->client_model->delete_multiple(array('bug_id' => $v_bugs->bug_id));

                            //delete data into table.
                            $this->client_model->_table_name = "tbl_task"; // table name
                            $this->client_model->delete_multiple(array('bug_id' => $v_bugs->bug_id));

                            $this->client_model->_table_name = "tbl_bug"; // table name
                            $this->client_model->delete_multiple(array('reporter' => $v_client->user_id));
                        }
                    }

                }
            }
            // delete all leads by id
            $this->client_model->_table_name = 'tbl_leads';
            $this->client_model->_order_by = 'client_id';
            $leads_info = $this->client_model->get_by(array('client_id' => $client_id), FALSE);
            if (!empty($leads_info)) {
                foreach ($leads_info as $v_leads) {
                    //delete data into table.
                    $this->client_model->_table_name = "tbl_calls"; // table name
                    $this->client_model->delete_multiple(array('leads_id' => $v_leads->leads_id));

                    //delete data into table.
                    $this->client_model->_table_name = "tbl_mettings"; // table name
                    $this->client_model->delete_multiple(array('leads_id' => $v_leads->leads_id));

                    //delete data into table.
                    $this->client_model->_table_name = "tbl_task_comment"; // table name
                    $this->client_model->delete_multiple(array('leads_id' => $v_leads->leads_id));

                    $this->client_model->_table_name = "tbl_task_attachment"; //table name
                    $this->client_model->_order_by = "leads_id";
                    $files_info = $this->client_model->get_by(array('leads_id' => $v_leads->leads_id), FALSE);

                    if (!empty($files_info)) {
                        foreach ($files_info as $v_files) {
                            //save data into table.
                            $this->client_model->_table_name = "tbl_task_uploaded_files"; // table name
                            $this->client_model->delete_multiple(array('task_attachment_id' => $v_files->task_attachment_id));
                        }
                    }
                    //save data into table.
                    $this->client_model->_table_name = "tbl_task_attachment"; // table name
                    $this->client_model->delete_multiple(array('leads_id' => $v_leads->leads_id));

                    $this->client_model->_table_name = 'tbl_leads';
                    $this->client_model->_primary_key = 'leads_id';
                    $this->client_model->delete($v_leads->leads_id);
                }
            }

            // project
            // delete all leads by id
            $this->client_model->_table_name = 'tbl_project';
            $this->client_model->_order_by = 'client_id';
            $project_info = $this->client_model->get_by(array('client_id' => $client_id), FALSE);
            if (!empty($project_info)) {
                foreach ($project_info as $v_project) {
                    //delete data into table.
                    $this->client_model->_table_name = "tbl_task_comment"; // table name
                    $this->client_model->delete_multiple(array('project_id' => $v_project->project_id));

                    $this->client_model->_table_name = "tbl_task_attachment"; //table name
                    $this->client_model->_order_by = "task_id";
                    $files_info = $this->client_model->get_by(array('project_id' => $v_project->project_id), FALSE);
                    if (!empty($files_info)) {
                        foreach ($files_info as $v_files) {
                            //save data into table.
                            $this->client_model->_table_name = "tbl_task_uploaded_files"; // table name
                            $this->client_model->delete_multiple(array('task_attachment_id' => $v_files->task_attachment_id));
                        }
                    }
                    //save data into table.
                    $this->client_model->_table_name = "tbl_task_attachment"; // table name
                    $this->client_model->delete_multiple(array('project_id' => $v_project->project_id));

                    //save data into table.
                    $this->client_model->_table_name = "tbl_milestones"; // table name
                    $this->client_model->delete_multiple(array('project_id' => $v_project->project_id));

                    // tasks
                    $taskss_info = get_result('tbl_task', array('project_id' => $v_project->project_id));
                    if (!empty($taskss_info)) {
                        foreach ($taskss_info as $v_taskss) {

                            $this->client_model->_table_name = "tbl_task_attachment"; //table name
                            $this->client_model->_order_by = "task_id";
                            $files_info = $this->client_model->get_by(array('task_id' => $v_taskss->task_id), FALSE);
                            foreach ($files_info as $v_files) {
                                $this->client_model->_table_name = "tbl_task_uploaded_files"; //table name
                                $this->client_model->delete_multiple(array('task_attachment_id' => $v_files->task_attachment_id));
                            }
                            //delete into table.
                            $this->client_model->_table_name = "tbl_task_attachment"; // table name
                            $this->client_model->delete_multiple(array('task_id' => $v_taskss->task_id));

                            //delete data into table.
                            $this->client_model->_table_name = "tbl_task_comment"; // table name
                            $this->client_model->delete_multiple(array('task_id' => $v_taskss->task_id));

                            $this->client_model->_table_name = "tbl_task"; // table name
                            $this->client_model->_primary_key = "task_id"; // $id
                            $this->client_model->delete($v_taskss->task_id);
                        }

                    }

                    // Bugs
                    $bugs_info = get_result('tbl_bug', array('project_id' => $v_project->project_id));
                    if (!empty($bugs_info)) {
                        foreach ($bugs_info as $v_bugs) {
                            $this->client_model->_table_name = "tbl_task_attachment"; //table name
                            $this->client_model->_order_by = "bug_id";
                            $files_info = $this->client_model->get_by(array('bug_id' => $v_bugs->bug_id), FALSE);
                            foreach ($files_info as $v_files) {
                                $this->client_model->_table_name = "tbl_task_uploaded_files"; //table name
                                $this->client_model->delete_multiple(array('task_attachment_id' => $v_files->task_attachment_id));
                            }
                            //delete into table.
                            $this->client_model->_table_name = "tbl_task_attachment"; // table name
                            $this->client_model->delete_multiple(array('bug_id' => $v_bugs->bug_id));

                            //delete data into table.
                            $this->client_model->_table_name = "tbl_task_comment"; // table name
                            $this->client_model->delete_multiple(array('bug_id' => $v_bugs->bug_id));

                            //delete data into table.
                            $this->client_model->_table_name = "tbl_task"; // table name
                            $this->client_model->delete_multiple(array('bug_id' => $v_bugs->bug_id));

                            $this->client_model->_table_name = "tbl_bug"; // table name
                            $this->client_model->_primary_key = "bug_id"; // $id
                            $this->client_model->delete($v_bugs->bug_id);
                        }

                    }

                    $this->client_model->_table_name = 'tbl_project';
                    $this->client_model->_primary_key = 'project_id';
                    $this->client_model->delete($v_project->project_id);
                }
            }

            // delete all invoice by id
            $invoice_info = get_result('tbl_invoices', array('client_id' => $client_id));
            if (!empty($invoice_info)) {
                foreach ($invoice_info as $v_invoice) {
                    // delete all payment info by id
                    $this->client_model->_table_name = 'tbl_payments';
                    $this->client_model->delete_multiple(array('invoices_id' => $v_invoice->invoices_id));
                }
            }
            $this->client_model->_table_name = 'tbl_invoices';
            $this->client_model->delete_multiple(array('client_id' => $client_id));

            // delete all project by id
            $this->client_model->_table_name = 'tbl_estimates';
            $this->client_model->_order_by = 'client_id';
            $estimates_info = $this->client_model->get_by(array('client_id' => $client_id), FALSE);
            if (!empty($estimates_info)) {
                foreach ($estimates_info as $v_estimates) {
                    $this->client_model->_table_name = 'tbl_estimate_items';
                    $this->client_model->delete_multiple(array('estimates_id' => $v_estimates->estimates_id));

                }
            }
            $this->client_model->_table_name = 'tbl_estimates';
            $this->client_model->delete_multiple(array('client_id' => $client_id));
            // delete all tbl_quotations by id
            $this->client_model->_table_name = 'tbl_quotations';
            $this->client_model->_order_by = 'client_id';
            $quotations_info = $this->client_model->get_by(array('client_id' => $client_id), FALSE);

            if (!empty($quotations_info)) {
                foreach ($quotations_info as $v_quotations) {
                    $this->client_model->_table_name = 'tbl_quotation_details';
                    $this->client_model->delete_multiple(array('quotations_id' => $v_quotations->quotations_id));
                }
            }
            $this->client_model->_table_name = 'tbl_quotations';
            $this->client_model->delete_multiple(array('client_id' => $client_id));

            $this->client_model->_table_name = 'tbl_transactions';
            $this->client_model->delete_multiple(array('paid_by' => $client_id));

            $this->client_model->_table_name = 'tbl_reminders';
            $this->client_model->delete_multiple(array('module' => 'client', 'module_id' => $client_id));

            $user_info = $this->client_model->check_by(array('client_id' => $client_id), 'tbl_client');
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'client',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_deleted_client'),
                'icon' => 'fa-user',
                'value1' => $user_info->name
            );
            $this->client_model->_table_name = 'tbl_activities';
            $this->client_model->_primary_key = "activities_id";
            $this->client_model->save($activities);

            // deletre into tbl_account details by user id
            $this->client_model->_table_name = 'tbl_client';
            $this->client_model->_primary_key = 'client_id';
            $this->client_model->delete($client_id);

            // messages for user
            $type = "success";
            $message = lang('delete_client');
            set_message($type, $message);
            redirect('admin/client/manage_client');
        } else {
            $data['title'] = "Delete Client "; //Page title
            $data['client_info'] = get_row('tbl_client', array('client_id' => $client_id));
            if (empty($data['client_info'])) {
                set_message('error', lang('there_in_no_value'));
                redirect('admin/client/manage_client');
            }
            $data['subview'] = $this->load->view('admin/client/delete_client', $data, TRUE);
            $this->load->view('admin/_layout_skote_main', $data); //page load
        }
    }

    public function multiple_client_delete_users()
    {
        $client_ids = $this->input->post('client_id') ;
        if($client_ids){
            foreach ($client_ids as $key => $client_id) {
                $can_edit = $this->items_model->can_action('tbl_client', 'edit', array('client_id' => $client_id));
                if (!empty($can_edit)) {
                    // delete into user table by user id
                    $this->client_model->_table_name = 'tbl_account_details';
                    $this->client_model->_order_by = 'company';
                    $client_info = $this->client_model->get_by(array('company' => $client_id), FALSE);
                    if (!empty($client_info)) {
                        foreach ($client_info as $v_client) {
                            $this->client_model->delete_multiple(array('account_details_id' => $v_client->account_details_id));

                            $this->client_model->_table_name = 'tbl_users';
                            $this->client_model->delete_multiple(array('user_id' => $v_client->user_id));

                            $this->client_model->_table_name = 'tbl_inbox';
                            $this->client_model->delete_multiple(array('user_id' => $v_client->user_id));

                            $this->client_model->_table_name = 'tbl_sent';
                            $this->client_model->delete_multiple(array('user_id' => $v_client->user_id));

                            $this->client_model->_table_name = 'tbl_draft';
                            $this->client_model->delete_multiple(array('user_id' => $v_client->user_id));

                            $this->client_model->_table_name = 'tbl_tickets';
                            $this->client_model->delete_multiple(array('reporter' => $v_client->user_id));
                            //save data into table.
                            // Bugs
                            $bugs_info = get_result('tbl_bug', array('reporter' => $v_client->user_id));
                            if (!empty($bugs_info)) {
                                foreach ($bugs_info as $v_bugs) {
                                    $this->client_model->_table_name = "tbl_task_attachment"; //table name
                                    $this->client_model->_order_by = "bug_id";
                                    $files_info = $this->client_model->get_by(array('bug_id' => $v_bugs->bug_id), FALSE);
                                    foreach ($files_info as $v_files) {
                                        $this->client_model->_table_name = "tbl_task_uploaded_files"; //table name
                                        $this->client_model->delete_multiple(array('task_attachment_id' => $v_files->task_attachment_id));
                                    }
                                    //delete into table.
                                    $this->client_model->_table_name = "tbl_task_attachment"; // table name
                                    $this->client_model->delete_multiple(array('bug_id' => $v_bugs->bug_id));

                                    //delete data into table.
                                    $this->client_model->_table_name = "tbl_task_comment"; // table name
                                    $this->client_model->delete_multiple(array('bug_id' => $v_bugs->bug_id));

                                    //delete data into table.
                                    $this->client_model->_table_name = "tbl_task"; // table name
                                    $this->client_model->delete_multiple(array('bug_id' => $v_bugs->bug_id));

                                    $this->client_model->_table_name = "tbl_bug"; // table name
                                    $this->client_model->delete_multiple(array('reporter' => $v_client->user_id));
                                }
                            }

                        }
                    }
                    // delete all leads by id
                    $this->client_model->_table_name = 'tbl_leads';
                    $this->client_model->_order_by = 'client_id';
                    $leads_info = $this->client_model->get_by(array('client_id' => $client_id), FALSE);
                    if (!empty($leads_info)) {
                        foreach ($leads_info as $v_leads) {
                            //delete data into table.
                            $this->client_model->_table_name = "tbl_calls"; // table name
                            $this->client_model->delete_multiple(array('leads_id' => $v_leads->leads_id));

                            //delete data into table.
                            $this->client_model->_table_name = "tbl_mettings"; // table name
                            $this->client_model->delete_multiple(array('leads_id' => $v_leads->leads_id));

                            //delete data into table.
                            $this->client_model->_table_name = "tbl_task_comment"; // table name
                            $this->client_model->delete_multiple(array('leads_id' => $v_leads->leads_id));

                            $this->client_model->_table_name = "tbl_task_attachment"; //table name
                            $this->client_model->_order_by = "leads_id";
                            $files_info = $this->client_model->get_by(array('leads_id' => $v_leads->leads_id), FALSE);

                            if (!empty($files_info)) {
                                foreach ($files_info as $v_files) {
                                    //save data into table.
                                    $this->client_model->_table_name = "tbl_task_uploaded_files"; // table name
                                    $this->client_model->delete_multiple(array('task_attachment_id' => $v_files->task_attachment_id));
                                }
                            }
                            //save data into table.
                            $this->client_model->_table_name = "tbl_task_attachment"; // table name
                            $this->client_model->delete_multiple(array('leads_id' => $v_leads->leads_id));

                            $this->client_model->_table_name = 'tbl_leads';
                            $this->client_model->_primary_key = 'leads_id';
                            $this->client_model->delete($v_leads->leads_id);
                        }
                    }

                    // project
                    // delete all leads by id
                    $this->client_model->_table_name = 'tbl_project';
                    $this->client_model->_order_by = 'client_id';
                    $project_info = $this->client_model->get_by(array('client_id' => $client_id), FALSE);
                    if (!empty($project_info)) {
                        foreach ($project_info as $v_project) {
                            //delete data into table.
                            $this->client_model->_table_name = "tbl_task_comment"; // table name
                            $this->client_model->delete_multiple(array('project_id' => $v_project->project_id));

                            $this->client_model->_table_name = "tbl_task_attachment"; //table name
                            $this->client_model->_order_by = "task_id";
                            $files_info = $this->client_model->get_by(array('project_id' => $v_project->project_id), FALSE);
                            if (!empty($files_info)) {
                                foreach ($files_info as $v_files) {
                                    //save data into table.
                                    $this->client_model->_table_name = "tbl_task_uploaded_files"; // table name
                                    $this->client_model->delete_multiple(array('task_attachment_id' => $v_files->task_attachment_id));
                                }
                            }
                            //save data into table.
                            $this->client_model->_table_name = "tbl_task_attachment"; // table name
                            $this->client_model->delete_multiple(array('project_id' => $v_project->project_id));

                            //save data into table.
                            $this->client_model->_table_name = "tbl_milestones"; // table name
                            $this->client_model->delete_multiple(array('project_id' => $v_project->project_id));

                            // tasks
                            $taskss_info = get_result('tbl_task', array('project_id' => $v_project->project_id));
                            if (!empty($taskss_info)) {
                                foreach ($taskss_info as $v_taskss) {

                                    $this->client_model->_table_name = "tbl_task_attachment"; //table name
                                    $this->client_model->_order_by = "task_id";
                                    $files_info = $this->client_model->get_by(array('task_id' => $v_taskss->task_id), FALSE);
                                    foreach ($files_info as $v_files) {
                                        $this->client_model->_table_name = "tbl_task_uploaded_files"; //table name
                                        $this->client_model->delete_multiple(array('task_attachment_id' => $v_files->task_attachment_id));
                                    }
                                    //delete into table.
                                    $this->client_model->_table_name = "tbl_task_attachment"; // table name
                                    $this->client_model->delete_multiple(array('task_id' => $v_taskss->task_id));

                                    //delete data into table.
                                    $this->client_model->_table_name = "tbl_task_comment"; // table name
                                    $this->client_model->delete_multiple(array('task_id' => $v_taskss->task_id));

                                    $this->client_model->_table_name = "tbl_task"; // table name
                                    $this->client_model->_primary_key = "task_id"; // $id
                                    $this->client_model->delete($v_taskss->task_id);
                                }

                            }

                            // Bugs
                            $bugs_info = get_result('tbl_bug', array('project_id' => $v_project->project_id));
                            if (!empty($bugs_info)) {
                                foreach ($bugs_info as $v_bugs) {
                                    $this->client_model->_table_name = "tbl_task_attachment"; //table name
                                    $this->client_model->_order_by = "bug_id";
                                    $files_info = $this->client_model->get_by(array('bug_id' => $v_bugs->bug_id), FALSE);
                                    foreach ($files_info as $v_files) {
                                        $this->client_model->_table_name = "tbl_task_uploaded_files"; //table name
                                        $this->client_model->delete_multiple(array('task_attachment_id' => $v_files->task_attachment_id));
                                    }
                                    //delete into table.
                                    $this->client_model->_table_name = "tbl_task_attachment"; // table name
                                    $this->client_model->delete_multiple(array('bug_id' => $v_bugs->bug_id));

                                    //delete data into table.
                                    $this->client_model->_table_name = "tbl_task_comment"; // table name
                                    $this->client_model->delete_multiple(array('bug_id' => $v_bugs->bug_id));

                                    //delete data into table.
                                    $this->client_model->_table_name = "tbl_task"; // table name
                                    $this->client_model->delete_multiple(array('bug_id' => $v_bugs->bug_id));

                                    $this->client_model->_table_name = "tbl_bug"; // table name
                                    $this->client_model->_primary_key = "bug_id"; // $id
                                    $this->client_model->delete($v_bugs->bug_id);
                                }

                            }

                            $this->client_model->_table_name = 'tbl_project';
                            $this->client_model->_primary_key = 'project_id';
                            $this->client_model->delete($v_project->project_id);
                        }
                    }

                    // delete all invoice by id
                    $invoice_info = get_result('tbl_invoices', array('client_id' => $client_id));
                    if (!empty($invoice_info)) {
                        foreach ($invoice_info as $v_invoice) {
                            // delete all payment info by id
                            $this->client_model->_table_name = 'tbl_payments';
                            $this->client_model->delete_multiple(array('invoices_id' => $v_invoice->invoices_id));
                        }
                    }
                    $this->client_model->_table_name = 'tbl_invoices';
                    $this->client_model->delete_multiple(array('client_id' => $client_id));

                    // delete all project by id
                    $this->client_model->_table_name = 'tbl_estimates';
                    $this->client_model->_order_by = 'client_id';
                    $estimates_info = $this->client_model->get_by(array('client_id' => $client_id), FALSE);
                    if (!empty($estimates_info)) {
                        foreach ($estimates_info as $v_estimates) {
                            $this->client_model->_table_name = 'tbl_estimate_items';
                            $this->client_model->delete_multiple(array('estimates_id' => $v_estimates->estimates_id));

                        }
                    }
                    $this->client_model->_table_name = 'tbl_estimates';
                    $this->client_model->delete_multiple(array('client_id' => $client_id));
                    // delete all tbl_quotations by id
                    $this->client_model->_table_name = 'tbl_quotations';
                    $this->client_model->_order_by = 'client_id';
                    $quotations_info = $this->client_model->get_by(array('client_id' => $client_id), FALSE);

                    if (!empty($quotations_info)) {
                        foreach ($quotations_info as $v_quotations) {
                            $this->client_model->_table_name = 'tbl_quotation_details';
                            $this->client_model->delete_multiple(array('quotations_id' => $v_quotations->quotations_id));
                        }
                    }
                    $this->client_model->_table_name = 'tbl_quotations';
                    $this->client_model->delete_multiple(array('client_id' => $client_id));

                    $this->client_model->_table_name = 'tbl_transactions';
                    $this->client_model->delete_multiple(array('paid_by' => $client_id));

                    $this->client_model->_table_name = 'tbl_reminders';
                    $this->client_model->delete_multiple(array('module' => 'client', 'module_id' => $client_id));

                    $user_info = $this->client_model->check_by(array('client_id' => $client_id), 'tbl_client');
                    $activities = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'client',
                        'module_field_id' => $this->session->userdata('user_id'),
                        'activity' => ('activity_deleted_client'),
                        'icon' => 'fa-user',
                        'value1' => $user_info->name
                    );
                    $this->client_model->_table_name = 'tbl_activities';
                    $this->client_model->_primary_key = "activities_id";
                    $this->client_model->save($activities);

                    // deletre into tbl_account details by user id
                    $this->client_model->_table_name = 'tbl_client';
                    $this->client_model->_primary_key = 'client_id';
                    $this->client_model->delete($client_id);
        
                    // messages for user
                    $type = "success";
                    $message = lang('delete_client');
                    $data_return['success'] = true;
                    $data_return['message'] =  lang('change_status');


                }else{
                    $data_return['success'] = false;
                    $data_return['message'] =  lang('there_in_no_value');
                }
            }   
        }else{ 
            $data_return['success'] = false;
            $data_return['message'] =  lang('there_in_no_value');
        }
        
        echo json_encode($data_return);
        exit();
    }

    function hash($string)
    {
        return hash('sha512', $string . config_item('encryption_key'));
    }

    public function new_notes($id = NULL)
    {
        $data['title'] = lang('give_award');
        $notes = $this->input->post('notes', true);
        $n_data['user_id'] = $this->input->post('client_id', true);
        if (!empty($notes)) {
            $n_data['notes'] = $notes;
            $n_data['is_client'] = 'Yes';
            $n_data['added_by'] = $this->session->userdata('user_id');
            // deletre into tbl_account details by user id
            $this->client_model->_table_name = 'tbl_notes';
            $this->client_model->_primary_key = 'notes_id';
            $this->client_model->save($n_data, $id);
        }
        redirect('admin/client/client_details/' . $n_data['user_id'] . '/notes');
    }

    public function delete_notes($id, $client_id)
    {
        $notes_info = $this->db->where('notes_id', $id)->get('tbl_notes')->row();
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'client',
            'module_field_id' => $this->session->userdata('user_id'),
            'activity' => ('activity_deleted_notes'),
            'icon' => 'fa-user',
            'value1' => $notes_info->notes
        );
        $this->client_model->_table_name = 'tbl_activities';
        $this->client_model->_primary_key = "activities_id";
        $this->client_model->save($activities);

        $this->client_model->_table_name = 'tbl_notes';
        $this->client_model->_primary_key = 'notes_id';
        $this->client_model->delete($id);
        redirect('admin/client/client_details/' . $client_id . '/notes');
    }

}
