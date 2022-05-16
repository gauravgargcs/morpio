<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Companies extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('companies_model');
        $super_admin = super_admin();
        if (empty($super_admin)) {
            redirect('404');
        }
    }

    public function index($id = NULL)
    {
        $data['title'] = lang('manage') . ' ' . lang('companies');
        if ($id) {
            $data['active'] = 2;
            $data['company_info'] = $this->db->where('companies_id', $id)->get('tbl_companies')->row();
        } else {
            $data['active'] = 1;
        }
        // get all countries
        $this->companies_model->_table_name = "tbl_countries"; //table name
        $this->companies_model->_order_by = "id";
        $data['countries'] = $this->companies_model->get();

        $data['all_companies'] = $this->db->get('tbl_companies')->result();
        $data['subview'] = $this->load->view('admin/companies/companies', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public function details($id)
    {
        $data['title'] = lang('companies') . ' ' . lang('details');
        $data['company_info'] = $this->db->where('companies_id', $id)->get('tbl_companies')->row();
        $data['subview'] = $this->load->view('admin/companies/details', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }


    public function save_companies($id = NULL)
    {

        $this->companies_model->_table_name = 'tbl_companies';
        $this->companies_model->_primary_key = 'companies_id';

        $data = $this->companies_model->array_from_post(array('name', 'email', 'phone', 'mobile', 'city', 'country', 'zip_code', 'address',
            'vat', 'skype_id', 'facebook', 'twitter', 'linkedin', 'fax'));

        if (!empty($_FILES['logo']['name'])) {
            $val = $this->companies_model->uploadImage('logo');
            $val == TRUE || redirect('admin/companies');
            $data['logo'] = $val['path'];
        }
        // saved into tbl_account_details
//        $profile['fullname'] = $this->input->post('fullname', true);
//        $profile['country'] = $data['country'];
//        $profile['phone'] = $data['phone'];
//        $profile['mobile'] = $data['mobile'];
//        $profile['city'] = $data['city'];
//        $profile['address'] = $data['address'];

        // saved into tbl_users
//        $login_data = array('email' => $data['email'], 'username' => $this->input->post('username', true), 'role_id' => 1);

        // check whether this input data already exist or not
        $check_id = null;
//        $where = array('username' => $login_data['username']);
        $email = array('email' => $data['email']);

        // duplicate value check in DB
        if (!empty($id)) { // if id exist in db update data
            $companies_id = array('companies_id !=' => $id);
        } else { // if id is not exist then set id as null
            $companies_id = null;
        }
//        if (empty($id)) {
//            $check_user = $this->companies_model->check_update('tbl_users', $where, $check_id);
//            $check_email = $this->companies_model->check_update('tbl_users', $email, $check_id);
//        }
        $check_company = $this->companies_model->check_update('tbl_companies', $email, $companies_id);
        if (!empty($check_company)) { // if input data already exist show error alert
//            if (!empty($check_user)) {
//                $error = $login_data['username'];
//            } else {
            $error = $data['email'];
//            }
            // massage for user
            $type = 'error';
            $msg = "<strong style='color:#000'>" . $error . '</strong>  ' . lang('already_exist');

//            $password = $this->input->post('password', TRUE);
//            if (!empty($password)) {
//                $confirm_password = $this->input->post('confirm_password', TRUE);
//                if ($password != $confirm_password) {
//                    $type = 'error';
//                    $msg = lang('password_does_not_match');
//                }
//            }
        } else { // save and update query
            if (!empty($id)) {
                update('tbl_companies', array('companies_id' => $id), $data);
            } else {
                $companies_id = $this->companies_model->save($data);
                if (!empty($companies_id)) {
//                    $password = $this->input->post('password', TRUE);
//                    $login_data['password'] = $this->hash($password);
//                    $login_data['permission'] = 'all';
//                    $login_data['activated'] = 1;
//                    $login_data['companies_id'] = $companies_id;
//                    $login_data['role_id'] = 1;
//                    $login_data['last_ip'] = $this->input->ip_address;
//                    $login_data['created'] = date('Y-m-d H:i:s');
//                    $this->companies_model->_table_name = 'tbl_users';
//                    $this->companies_model->_primary_key = 'user_id';
//                    $profile['user_id'] = $this->companies_model->save_data($login_data);
//
//                    $RTL = config_item('RTL');
//                    if (!empty($RTL)) {
//                        $direction = 'rtl';
//                    } else {
//                        $direction = 'ltr';
//                    }
//                    $profile['direction'] = $direction;
//
//                    $this->companies_model->_table_name = 'tbl_account_details';
//                    $this->companies_model->_primary_key = 'account_details_id';
//                    $this->companies_model->save_data($profile);
//
//                    // update primary_contact
//                    $uc_data['primary_contact'] = $profile['user_id'];
//                    update('tbl_companies', array('companies_id' => $companies_id), $uc_data);

                    // create config
                    $this->create_config($companies_id);
                    // create working days
                    $this->create_working_days($companies_id);
                    // create  menu
                    $this->create_menu($companies_id);

                    $this->create_dashboard($companies_id);
                    // create client menu
                    $this->create_client_menu($companies_id);
                    // create email template
                    $this->create_email_template($companies_id);
                }
            }
            if (!empty($id)) {
                $id = $id;
                $action = 'activity_update_companies';
                $msg = lang('update_company');
            } else {
                $id = $companies_id;
                $action = 'activity_save_companies';
                $msg = lang('save_companies');
            }


            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'companies',
                'module_field_id' => $id,
                'activity' => $action,
                'icon' => 'fa-circle-o',
                'value1' => $data['name']
            );
            $this->companies_model->_table_name = 'tbl_activities';
            $this->companies_model->_primary_key = 'activities_id';
            $this->companies_model->save($activity);
            // messages for user
            $type = "success";
        }
        $message = $msg;
        set_message($type, $message);
        redirect('admin/companies');

    }

    public function delete_company($id)
    {

        $action = 'activity_delete_companies';
        $msg = lang('delete_message');
        $acc_info = $this->companies_model->check_by(array('companies_id' => $id), 'tbl_companies');
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'account',
            'module_field_id' => $id,
            'activity' => $action,
            'icon' => 'fa-circle-o',
            'value1' => $acc_info->name
        );
        $this->companies_model->_table_name = 'tbl_activities';
        $this->companies_model->_primary_key = 'activities_id';
        $this->companies_model->save($activity);

        $user_info = $this->db->where(array('companies_id' => $id))->get('tbl_users')->result();
        if (!empty($user_info)) {
            foreach ($user_info as $v_user) {
                $this->companies_model->_table_name = "tbl_account_details"; //table name
                $this->companies_model->delete_multiple(array('user_id' => $v_user->user_id));
            }
            $this->companies_model->_table_name = "tbl_users"; //table name
            $this->companies_model->delete_multiple(array('companies_id' => $id));
        }

        $this->companies_model->_table_name = "tbl_config"; //table name
        $this->companies_model->delete_multiple(array('companies_id' => $id));

        $this->companies_model->_table_name = "tbl_working_days"; //table name
        $this->companies_model->delete_multiple(array('companies_id' => $id));

        $this->companies_model->_table_name = "tbl_menu"; //table name
        $this->companies_model->delete_multiple(array('companies_id' => $id));

        $this->companies_model->_table_name = "tbl_dashboard"; //table name
        $this->companies_model->delete_multiple(array('companies_id' => $id));

        $this->companies_model->_table_name = "tbl_client_menu"; //table name
        $this->companies_model->delete_multiple(array('companies_id' => $id));

        $this->companies_model->_table_name = "tbl_email_templates"; //table name
        $this->companies_model->delete_multiple(array('companies_id' => $id));

        $this->companies_model->_table_name = 'tbl_companies';
        $this->companies_model->_primary_key = 'companies_id';
        $this->companies_model->delete($id);

        $type = "success";
        echo json_encode(array("status" => $type, 'message' => $msg));
        exit();
    }

    public function hash($string)
    {
        return hash('sha512', $string . config_item('encryption_key'));
    }

    public function change_status($flag, $id)
    {
        $company_info = $this->db->where('companies_id', $id)->get('tbl_companies')->row();
        // if flag == 1 it is active user else deactive user
        if ($flag == 1) {
            $msg = lang('active');
            $action = array('status' => $flag, 'banned' => 0);
        } else {
            $msg = lang('deactive');
            $action = array('status' => $flag);
        }
        $where = array('companies_id' => $id);
        $this->companies_model->set_action($where, $action, 'tbl_companies');

        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'user',
            'module_field_id' => $id,
            'activity' => 'activity_change_status',
            'icon' => 'fa-user',
            'value1' => $company_info->name . ' ' . $msg,
        );
        $this->companies_model->_table_name = 'tbl_activities';
        $this->companies_model->_primary_key = "activities_id";
        $this->companies_model->save($activities);

        $type = "success";
        $message = lang('company') . ' ' . $msg . " Successfully!";
        echo json_encode(array("status" => $type, "message" => $message));
        exit();
    }

    public function set_banned($flag, $id)
    {
        $company_info = $this->db->where('companies_id', $id)->get('tbl_companies')->row();
        if ($flag == 1) {
            $msg = lang('banned');
            $action = array('status' => 0, 'banned' => $flag, 'ban_reason' => $this->input->post('ban_reason', TRUE));
        } else {
            $msg = lang('unbanned');
            $action = array('status' => 1, 'banned' => $flag);
        }

        $where = array('companies_id' => $id);
        $this->companies_model->set_action($where, $action, 'tbl_companies');

        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'user',
            'module_field_id' => $id,
            'activity' => 'activity_change_status',
            'icon' => 'fa-user',
            'value1' => $company_info->name . ' ' . $msg,
        );
        $this->companies_model->_table_name = 'tbl_activities';
        $this->companies_model->_primary_key = "activities_id";
        $this->companies_model->save($activities);

        $type = "success";
        $message = lang('company') . ' ' . $msg . " Successfully!";
        set_message($type, $message);
        redirect('admin/companies'); //redirect page
    }

    public function change_banned($flag, $id)
    {
        $data['companies_id'] = $id;
        $data['flag'] = $flag;
        $data['modal_subview'] = $this->load->view('admin/companies/_modal_banned_reson', $data, FALSE);
        $this->load->view('admin/_layout_skote_modal', $data);

    }

    function create_config($companies_id = null)
    {
        $config_data = $this->db->where('companies_id', null)->get('tbl_config')->result_array();

        $configs = array();
        foreach ($config_data as $config) {
            $config['companies_id'] = $companies_id;
            array_push($configs, $config);
        }
        $this->db->insert_batch('tbl_config', $configs);
        return true;
    }

    function create_working_days($companies_id = null)
    {
        $all_working_day = $this->db->where('companies_id', null)->get('tbl_working_days')->result();
        $working_day = array();
        if (!empty($all_working_day)) {
            foreach ($all_working_day as $working_days) {
                $working_days->working_days_id = null;
                $working_days->companies_id = $companies_id;
                array_push($working_day, $working_days);
            }
            $this->db->insert_batch('tbl_working_days', $working_day);
        }
        return true;
    }

    function create_menu($companies_id = null)
    {
        $all_menu = $this->db->where('companies_id', null)->get('tbl_menu')->result();
        $menu = array();
        if (!empty($all_menu)) {
            foreach ($all_menu as $menus) {
                $menus->menu_id = null;
                $menus->companies_id = $companies_id;
                array_push($menu, $menus);
            }
        }
        $this->db->insert_batch('tbl_menu', $menu);

        $all_company_menu = $this->db->where(array('companies_id' => $companies_id, 'parent !=' => 0))->get('tbl_menu')->result();
        if (!empty($all_company_menu)) {
            foreach ($all_company_menu as $c_menu) {
                $parent_label = $this->db->where(array('menu_id' => $c_menu->parent))->get('tbl_menu')->row();
                $new_id = get_any_field('tbl_menu', array('label' => $parent_label->label, 'companies_id' => $companies_id), 'menu_id');
                update('tbl_menu', array('companies_id' => $companies_id, 'parent' => $parent_label->menu_id), array('parent' => $new_id));
            }
        }
        return true;
    }

    function create_dashboard($companies_id = null)
    {
        $all_menu = $this->db->where('companies_id', null)->get('tbl_dashboard')->result();
        $menu = array();
        if (!empty($all_menu)) {
            foreach ($all_menu as $menus) {
                $menus->id = null;
                $menus->companies_id = $companies_id;
                array_push($menu, $menus);
            }
        }
        $this->db->insert_batch('tbl_dashboard', $menu);
        return true;
    }

    function create_client_menu($companies_id = null)
    {
        $all_client_menu = $this->db->where('companies_id', null)->get('tbl_client_menu')->result();
        $menu = array();
        if (!empty($all_client_menu)) {
            foreach ($all_client_menu as $client_menus) {
                $client_menus->menu_id = null;
                $client_menus->companies_id = $companies_id;
                array_push($menu, $client_menus);
            }
        }
        $this->db->insert_batch('tbl_client_menu', $menu);
        return true;
    }

    function create_email_template($companies_id = null)
    {
        $all_email_templates = $this->db->where('companies_id', null)->get('tbl_email_templates')->result();
        $templates = array();
        if (!empty($all_email_templates)) {
            foreach ($all_email_templates as $email_templates) {
                $email_templates->email_templates_id = null;
                $email_templates->companies_id = $companies_id;
                array_push($templates, $email_templates);
            }
        }
        $this->db->insert_batch('tbl_email_templates', $templates);
        return true;
    }


}
