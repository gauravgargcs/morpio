<?php class MY_Model extends CI_Model
{
    protected $_table_name = '';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = '';
    public $rules = array();
    protected $_timestamps = FALSE;

    function __construct()
    {
        parent::__construct();
    }

    // CURD FUNCTION

    public function array_from_post($fields)
    {
        $data = array();
        foreach ($fields as $field) {
            $data[$field] = $this->input->post($field, true);
        }
        return $data;
    }

    public function get($id = NULL, $single = FALSE)
    {
        $companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            if ($this->db->field_exists('companies_id', $this->_table_name)) {
                $this->db->where('companies_id', $companies_id);
            }
        }
        if ($id != NULL) {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->where($this->_primary_key, $id);
            $method = 'row';
        } elseif ($single == TRUE) {
            $method = 'row';
        } else {
            $method = 'result';
        }

        if (!count($this->db->order_by($this->_order_by))) {
            $this->db->order_by($this->_order_by);
        }
        return $this->db->get($this->_table_name)->$method();
    }

    public function get_by($where, $single = FALSE)
    {
        $companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            if ($this->db->field_exists('companies_id', $this->_table_name)) {
                $this->db->where('companies_id', $companies_id);
            }
        }
        $this->db->where($where);
        return $this->get(NULL, $single);
    }

    public function save($data, $id = NULL)
    {
        $companies_id = $this->input->post('companies_id', true);
        if (empty($companies_id)) {
            $companies_id = $this->session->userdata('companies_id');
        }        
        if (empty($companies_id)) {
            // set_message('error', 'you have to select branch to run the action');
            // if (empty($_SERVER['HTTP_REFERER'])) {
            //     redirect('admin/dashboard');
            // } else {
            //     redirect($_SERVER['HTTP_REFERER']);
            // }
            $companies_id = NULL;
        }
        
        if (!empty($companies_id) && $this->db->field_exists('companies_id', $this->_table_name)) {
            $data['companies_id'] = $companies_id;
        }
        // Set timestamps
        if ($this->_timestamps == TRUE) {
            $now = date('Y-m-d H:i:s');
            $id || $data['created'] = $now;
            $data['modified'] = $now;
        }
        // Insert
        if ($id === NULL) {
            !isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
            $this->db->set($data);
            $this->db->insert($this->_table_name);
            $id = $this->db->insert_id();
        } // Update
        else {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->set($data);
            $this->db->where($this->_primary_key, $id);
            $this->db->update($this->_table_name);
        }

        return $id;
    }

    public function save_batch($tbl_name, $data)
    {
        $companies_id = $this->input->post('companies_id', true);
        if (!empty($companies_id)) {
            $companies_id = $companies_id;
        } else {
            $companies_id = $this->session->userdata('companies_id');
        }
        if (empty($companies_id)) {
            $companies_id = NULL;
        }
        if ($this->db->field_exists('companies_id', $this->_table_name)) {
            $data['companies_id'] = $companies_id;
        }
        $this->db->insert_batch($tbl_name, $data);
        return true;
    }

    public function save_data($data, $id = NULL)
    {
        // Set timestamps
        if ($this->_timestamps == TRUE) {
            $now = date('Y-m-d H:i:s');
            $id || $data['created'] = $now;
            $data['modified'] = $now;
        }
        // Insert
        if ($id === NULL) {
            !isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
            $this->db->set($data);
            $this->db->insert($this->_table_name);
            $id = $this->db->insert_id();
        } // Update
        else {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->set($data);
            $this->db->where($this->_primary_key, $id);
            $this->db->update($this->_table_name);
        }

        return $id;
    }

    public function delete($id)
    {
        $filter = $this->_primary_filter;
        $id = $filter($id);

        if (!$id) {
            return FALSE;
        }
        $this->db->where($this->_primary_key, $id);
        $this->db->limit(1);
        $this->db->delete($this->_table_name);
    }

    /**
     * Delete Multiple rows
     */
    public function delete_multiple($where)
    {
        $this->db->where($where);
        $this->db->delete($this->_table_name);
    }

    function uploadImage($field)
    {

        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = config_item('allowed_files');
        $config['max_size'] = config_item('max_file_size') * 1024;
        $config['overwrite'] = TRUE;
        $new_name = time().$_FILES[$field]['name'];
        $config['file_name'] = $new_name;
        //        $config['max_width'] = '1024';
        //        $config['max_height'] = '768';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($field)) {
            $error = $this->upload->display_errors();
            $type = "error";
            $message = $error;
            set_message($type, $message);
            return FALSE;
            // uploading failed. $error will holds the errors.
        } else {
            $fdata = $this->upload->data();
            $img_data['path'] = $config['upload_path'] . $fdata['file_name'];
            return $img_data;
            // uploading successfull, now do your further actions
        }
    }

    function uploadFile($field)
    {
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = config_item('allowed_files');
        $config['max_size'] = config_item('allowed_files') * 1024;
         $new_name = time().$_FILES[$field]['name'];
        $config['file_name'] = $new_name;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($field)) {
            $error = $this->upload->display_errors();
            $type = "error";
            $message = $error;
            set_message($type, $message);
            return FALSE;
            // uploading failed. $error will holds the errors.
        } else {
            $fdata = $this->upload->data();
            $file_data['fileName'] = $fdata['file_name'];
            $file_data['path'] = $config['upload_path'] . $fdata['file_name'];
            $file_data['fullPath'] = $fdata['full_path'];
            $file_data['ext'] = $fdata['file_ext'];
            $file_data['size'] = $fdata['file_size'];
            $file_data['is_image'] = $fdata['is_image'];
            $file_data['image_width'] = $fdata['image_width'];
            $file_data['image_height'] = $fdata['image_height'];
            return $file_data;
        }
    }

    function uploadAllType($field)
    {
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = config_item('allowed_files');
        $config['max_size'] = config_item('allowed_files') * 1024;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($field)) {
            $error = $this->upload->display_errors();
            $type = "error";
            $message = $error;
            set_message($type, $message);
            return FALSE;
            // uploading failed. $error will holds the errors.
        } else {
            $fdata = $this->upload->data();
            $file_data['fileName'] = $fdata['file_name'];
            $file_data['path'] = $config['upload_path'] . $fdata['file_name'];
            $file_data['fullPath'] = $fdata['full_path'];
            $file_data['ext'] = $fdata['file_ext'];
            $file_data['size'] = $fdata['file_size'];
            $file_data['is_image'] = $fdata['is_image'];
            $file_data['image_width'] = $fdata['image_width'];
            $file_data['image_height'] = $fdata['image_height'];
            return $file_data;
            // uploading successfull, now do your further actions
        }
    }

    function multi_uploadAllType($field)
    {
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = config_item('allowed_files');
        $config['max_size'] = config_item('allowed_files') * 1024;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_multi_upload($field)) {
            $error = $this->upload->display_errors();
            $type = "error";
            $message = $error;
            set_message($type, $message);
            return FALSE;
            // uploading failed. $error will holds the errors.
        } else {
            $multi_fdata = $this->upload->get_multi_upload_data();
            foreach ($multi_fdata as $fdata) {

                $file_data['fileName'] = $fdata['file_name'];
                $file_data['path'] = $config['upload_path'] . $fdata['file_name'];
                $file_data['fullPath'] = $fdata['full_path'];
                $file_data['ext'] = $fdata['file_ext'];
                $file_data['size'] = $fdata['file_size'];
                $file_data['is_image'] = $fdata['is_image'];
                $file_data['image_width'] = $fdata['image_width'];
                $file_data['image_height'] = $fdata['image_height'];
                $file_data['docroom_file_id'] = $fdata['docroom_file_id'];

                $result[] = $file_data;
            }
            return $result;
            // uploading successfull, now do your further actions
        }
    }

    public function check_by($where, $tbl_name)
    {

        $this->db->select('*');
        $this->db->from($tbl_name);
        $companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            // if ($this->db->field_exists('companies_id', $tbl_name)) {
            //     $this->db->where('companies_id', $companies_id);
            // }
        }
        $this->db->where($where);
        $query_result = $this->db->get();
        $result = $query_result->row();
        return $result;
    }

    public function get_result($where, $tbl_name)
    {

        $this->db->select('*');
        $this->db->from($tbl_name);
        $companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            // if ($this->db->field_exists('companies_id', $tbl_name)) {
            //     $this->db->where('companies_id', $companies_id);
            // }
            // else if($this->db->table_exists($tbl_name.'_companies')){
            //     $this->db->join($tbl_name.'_companies', $tbl_name.'_companies.'. $tbl_name.'_id = '.$tbl_name.'.id');

            //     $this->db->where($tbl_name.'_companies.companies_id', $companies_id);

            // }
        }
        $this->db->where($where);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function get_result_group($where, $tbl_name, $groupby = null, $select='*')
    {
        $this->db->select($select);
        $this->db->from($tbl_name);
        $companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            // if ($this->db->field_exists('companies_id', $tbl_name)) {
            //     $this->db->where('companies_id', $companies_id);
            // }
            // else if($this->db->table_exists($tbl_name.'_companies')){
            //     $this->db->join($tbl_name.'_companies', $tbl_name.'_companies.'. $tbl_name.'_id = '.$tbl_name.'.id');

            //     $this->db->where($tbl_name.'_companies.companies_id', $companies_id);

            // }
        }
        $this->db->where($where);
        if($groupby) {
            $this->db->group_by($groupby);   
        }
        $query_result = $this->db->get();
        $result = $query_result->result_array();
        return $result;
    }
    
    function count_rows($table, $where = null)
    {
        $companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            if ($this->db->field_exists('companies_id', $table)) {
                $this->db->where('companies_id', $companies_id);
            }
        }
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    function get_any_field($table, $where_criteria, $table_field)
    {
        $query = $this->db->select($table_field)->where($where_criteria)->get($table);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->$table_field;
        }
    }

    /**
     * @ Upadate row with duplicasi check
     */
    public function check_update($table, $where, $id = Null)
    {
        $this->db->select('*', FALSE);
        $this->db->from($table);
        if ($id != null) {
            $this->db->where($id);
        }
        $companies_id = $this->input->post('companies_id', true);
        if (!empty($companies_id)) {
            $companies_id = $companies_id;
        } else {
            $companies_id = $this->session->userdata('companies_id');
        }
        if (!empty($companies_id)) {
            if ($this->db->field_exists('companies_id', $table)) {
                $this->db->where('companies_id', $companies_id);
            }
        }
        $this->db->where($where);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    // set actiion setting

    public function set_action($where, $value, $tbl_name)
    {
        $this->db->set($value);
        $this->db->where($where);
        $this->db->update($tbl_name);
    }

    function get_sum($table, $field, $where)
    {
        $companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            if ($this->db->field_exists('companies_id', $table)) {
                $this->db->where('companies_id', $companies_id);
            }
        }
        $this->db->where($where);
        $this->db->select_sum($field);
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->$field;
        } else {
            return 0;
        }
    }

    public function get_limit($where, $tbl_name, $limit)
    {

        $this->db->select('*');
        $this->db->from($tbl_name);
        $companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            if ($this->db->field_exists('companies_id', $tbl_name)) {
                $this->db->where('companies_id', $companies_id);
            }
        }
        $this->db->where($where);
        $this->db->limit($limit);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    function short_description($string = FALSE, $from_start = 30, $from_end = 10, $limit = FALSE)
    {
        if (!$string) {
            return FALSE;
        }
        if ($limit) {
            if (mb_strlen($string) < $limit) {
                return $string;
            }
        }
        return mb_substr($string, 0, $from_start - 1) . "..." . ($from_end > 0 ? mb_substr($string, -$from_end) : '');
    }

    function get_table_field($tableName, $where = array(), $field)
    {

        return $this->db->select($field)->where($where)->get($tableName)->row()->$field;
    }

    function get_time_different($from = null, $to)
    {
        if (empty($from)) {
            $from = time();
        }
        $time_elapsed = $from - $to;
        $seconds = $time_elapsed;
        $minutes = round($time_elapsed / 60);
        $hours = round($time_elapsed / 3600);
        $days = round($time_elapsed / 86400);
        $weeks = round($time_elapsed / 604800);
        $months = round($time_elapsed / 2600640);
        $years = round($time_elapsed / 31207680);

        // Seconds
        if ($seconds <= 60) {
            return lang('time_ago_just_now');
        } //Minutes
        elseif ($minutes <= 60) {
            if ($minutes == 1) {
                return lang('time_ago_minute');
            } else {
                return lang('time_ago_minutes', $minutes);
            }
        } //Hours
        elseif ($hours <= 24) {
            if ($hours == 1) {
                return lang('time_ago_hour');
            } else {
                return lang('time_ago_hours', $hours);
            }
        } //Days
        elseif ($days <= 7) {
            if ($days == 1) {
                return lang('time_ago_yesterday');
            } else {
                return lang('time_ago_days', $days);
            }
        } //Weeks
        elseif ($weeks <= 4.3) {
            if ($weeks == 1) {
                return lang('time_ago_week');
            } else {
                return lang('time_ago_weeks', $weeks);
            }
        } //Months
        elseif ($months <= 12) {
            if ($months == 1) {
                return lang('time_ago_month');
            } else {
                return lang('time_ago_months', $months);
            }
        } //Years
        else {
            if ($years == 1) {
                return lang('time_ago_year');
            } else {
                return lang('time_ago_years', $years);
            }
        }
    }


    public function client_currency_sambol($client_id)
    {
        $this->db->select('tbl_client.currency', FALSE);
        $this->db->select('tbl_currencies.*', FALSE);
        $this->db->from('tbl_client');
        $this->db->join('tbl_currencies', 'tbl_currencies.code = tbl_client.currency', 'left');
        $companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            if ($this->db->field_exists('companies_id', 'tbl_client')) {
                $this->db->where('tbl_client.companies_id', $companies_id);
            }
        }
        $this->db->where('tbl_client.client_id', $client_id);
        $query_result = $this->db->get();
        $result = $query_result->row();
        return $result;
    }

    public function allowad_user_id($menu_id)
    {
        $permission_user = $this->all_permission_user($menu_id);
        // if not exist data show empty array.
        $user_id = array();
        // get all admin user
        $admin_user = get_result('tbl_users', array('role_id' => 1, 'super_admin !=' => 'Yes'));
        if (!empty($admin_user)) {
            foreach ($admin_user as $v_user) {
                array_push($user_id, $v_user->user_id);
            }
        }
        if (!empty($permission_user)) {
            foreach ($permission_user as $p_user) {
                array_push($user_id, $p_user->user_id);
            }
        }
        return array_unique($user_id);
    }

    public function allowad_user($menu_id)
    {
        $permission_user = $this->all_permission_user($menu_id);
        // get all admin user
        $admin_user = get_result('tbl_users', array('role_id' => 1, 'super_admin !=' => 'Yes'),'',null,'user_id');
        // if not exist data show empty array.
        if (!empty($permission_user)) {
            $permission_user = $permission_user;
        } else {
            $permission_user = array();
        }
        if (!empty($admin_user)) {
            $admin_user = $admin_user;
        } else {
            $admin_user = array();
        }
        $result = array_merge($admin_user, $permission_user);
        $r_result = array();
        foreach ($result as $v_result) {
            array_push($r_result, $v_result->user_id);
        }
        $r_result = array_unique($r_result);
        $users = array();
        if (!empty($r_result)) {
            foreach ($r_result as $v_user) {
                array_push($users, $this->db->select('user_id,username')->where('user_id', $v_user)->get('tbl_users')->row());
            }
        }
        return $users;
    }

    public function all_permission_user($menu_id)
    {

         
        $parent_label = $this->db->where(array('menu_id' => $menu_id))->get('tbl_menu')->row();
        $companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            $menu_id = get_any_field('tbl_menu', array('label' => $parent_label->label, 'companies_id' => $companies_id), 'menu_id');
        }

        $this->db->select('tbl_user_role.designations_id', FALSE);
        $this->db->select('tbl_account_details.designations_id', FALSE);
        $this->db->select('tbl_users.*', FALSE);
        $this->db->from('tbl_user_role');
        $this->db->join('tbl_account_details', 'tbl_account_details.designations_id = tbl_user_role.designations_id', 'left');
        $this->db->join('tbl_users', 'tbl_users.user_id = tbl_account_details.user_id', 'left');
        //        $this->db->group_start();
       
        $this->db->where('tbl_users.activated', 1);
        if (!empty($companies_id)) {
             $this->db->where('tbl_user_role.menu_id', $menu_id);
            if ($this->db->field_exists('companies_id', 'tbl_users')) {
                $this->db->where('tbl_users.companies_id', $companies_id);
            }
        }
        //        if (empty(super_admin())) {
        //            $this->db->where('tbl_users.super_admin !=', 'Yes');
        //        }
        //        $this->db->group_end();
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function get_permission($table, $flag = null, $where=[])
    {
        $result_info = array();
        $role = $this->session->userdata('user_type');
        $user_id = $this->session->userdata('user_id');
        $result_info = get_result($table,$where);
        if ($role != 1) {
            if (!empty($result_info)) {
                foreach ($result_info as $result) {
                    if ($result->permission == 'all') {
                        $permission[] = $result;
                    } else {
                        $get_permission = json_decode($result->permission);
                        if (is_object($get_permission)) {
                            foreach ($get_permission as $id => $v_permission) {
                                if ($user_id == $id) {
                                    $permission[] = $result;
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $permission = $result_info;
        }
        if (empty($permission)) {
            $permission = array();
        }
        return $permission;
    }
      public function get_permission_selected($table, $flag = null, $select='')
    {
        
        $result_info = array();
        $role = $this->session->userdata('user_type');
        $user_id = $this->session->userdata('user_id');
        $result_info = get_result_selected($table,[],$select);
        
        if ($role != 1) {
            if (!empty($result_info)) {
                foreach ($result_info as $result) {
                    if ($result->permission == 'all') {
                        $permission[] = $result;
                    } else {
                        $get_permission = json_decode($result->permission);
                        if (is_object($get_permission)) {
                            foreach ($get_permission as $id => $v_permission) {
                                if ($user_id == $id) {
                                    $permission[] = $result;
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $permission = $result_info;
        }
        if (empty($permission)) {
            $permission = array();
        }
        return $permission;
    }


    public function my_permission($table, $user_id)
    {

        $result_info = get_result($table);
        if (!empty($result_info)) {
            foreach ($result_info as $result) {
                if ($result->permission == 'all') {
                    $permission[] = $result;
                } else {
                    $get_permission = json_decode($result->permission);
                    if (is_object($get_permission)) {
                        foreach ($get_permission as $id => $v_permission) {
                            if ($user_id == $id) {
                                $permission[] = $result;
                            }
                        }
                    }
                }
            }
        }
        if (empty($permission)) {
            $permission = array();
        }
        return $permission;
    }

    public function can_action($table, $action, $id, $permission = null)
    {
        $role = $this->session->userdata('user_type');
        $user_id = $this->session->userdata('user_id');
        $result_info = $this->db->where($id)->get($table)->row();
        if (!empty($permission) || $role != 1) {
            if (!empty($result_info)) {
                if ($result_info->permission != 'all') {
                    $get_permission = json_decode($result_info->permission);
                } else {
                    return true;
                }
                if (is_object($get_permission)) {
                    foreach ($get_permission as $user => $v_permission) {
                        if (!empty($v_permission)) {
                            foreach ($v_permission as $v_action) {
                                if ($user == $user_id) {
                                    if ($v_action == $action) {
                                        return true;
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }


    public function hash($string)
    {
        return hash('sha512', $string . config_item('encryption_key'));
    }

    public function generate_invoice_number()
    {
        $total_invoice = $this->count_rows('tbl_invoices');
        if ($total_invoice > 0) {
            //            $ref_number = intval(substr($row->reference_no, -4));
            $next_number = ++$total_invoice;
            //            if ($next_number < $ref_number) {
            //                $next_number = $ref_number + 1;
            //            }
            if ($next_number < config_item('invoice_start_no')) {
                $next_number = config_item('invoice_start_no');
            }
            $next_number = $this->reference_no_exists($next_number);
            $next_number = sprintf('%04d', $next_number);
            return $next_number;
        } else {
            return sprintf('%04d', config_item('invoice_start_no'));
        }
    }

    public function reference_no_exists($next_number)
    {
        $next_number = sprintf('%04d', $next_number);
        $records = $this->db->where('reference_no', config_item('invoice_prefix') . $next_number)->get('tbl_invoices')->num_rows();
        if ($records > 0) {
            return $this->reference_no_exists($next_number + 1);
        } else {
            return $next_number;
        }
    }

    public function generate_purchase_number()
    {
        $total_invoice = $this->count_rows('tbl_purchases');
        if ($total_invoice > 0) {
            //            $ref_number = intval(substr($row->reference_no, -4));
            $next_number = ++$total_invoice;
            //            if ($next_number < $ref_number) {
            //                $next_number = $ref_number + 1;
            //            }
            if ($next_number < config_item('invoice_start_no')) {
                $next_number = config_item('invoice_start_no');
            }
            $next_number = $this->purchase_reference_no_exists($next_number);
            $next_number = sprintf('%04d', $next_number);
            return $next_number;
        } else {
            return sprintf('%04d', config_item('invoice_start_no'));
        }
    }

    public function purchase_reference_no_exists($next_number)
    {
        $next_number = sprintf('%04d', $next_number);
        $records = $this->db->where('reference_no', config_item('purchase_prefix') . $next_number)->get('tbl_purchases')->num_rows();
        if ($records > 0) {
            return $this->purchase_reference_no_exists($next_number + 1);
        } else {
            return $next_number;
        }
    }

    public function generate_estimate_number()
    {
        $total_estimate = $this->count_rows('tbl_estimates');
        if ($total_estimate > 0) {
            $next_number = ++$total_estimate;
            //            if ($next_number < $ref_number) {
            //                $next_number = $ref_number + 1;
            //            }
            if ($next_number < config_item('estimate_start_no')) {
                $next_number = config_item('estimate_start_no');
            }
            $next_number = $this->extimate_reference_no_exists($next_number);
            $next_number = sprintf('%04d', $next_number);
            return $next_number;
        } else {
            return sprintf('%04d', config_item('estimate_start_no'));
        }
    }

    public function extimate_reference_no_exists($next_number)
    {
        $next_number = sprintf('%04d', $next_number);

        $records = $this->db->where('reference_no', config_item('estimate_prefix') . $next_number)->get('tbl_estimates')->num_rows();
        if ($records > 0) {
            return $this->reference_no_exists($next_number + 1);
        } else {
            return $next_number;
        }
    }

    function send_email($params, $test = null)
    {

        $config = array();
        // If postmark API is being used
        if (config_item('use_postmark') == 'TRUE') {
            $config = array(
                'api_key' => config_item('postmark_api_key')
            );
            $this->load->library('postmark', $config);
            $this->postmark->from(config_item('postmark_from_address'), config_item('company_name'));
            $this->postmark->to($params['recipient']);
            $this->postmark->subject($params['subject']);
            $this->postmark->message_plain($params['message']);
            $this->postmark->message_html($params['message']);
            // Check resourceed file
            if (isset($params['resourcement_url'])) {
                $this->postmark->resource($params['resourcement_url']);
            }
            $this->postmark->send();
        } else {
            // If using SMTP
            //            if (config_item('protocol') == 'smtp') {
            //                $this->load->library('encrypt');
            //                $config = array(
            //                    'protocol' => config_item('protocol'),
            //                    'smtp_host' => config_item('smtp_host'),
            //                    'smtp_port' => config_item('smtp_port'),
            //                    'smtp_user' => config_item('smtp_user'),
            //                    'smtp_pass' => config_item('smtp_pass'),
            //                    'smtp_crypto' => config_item('email_encryption'),
            //                    'crlf' => "\r\n"
            //                );
            //            }
            // Send email
            $this->load->library('email');
            $this->email->clear();
            $config['useragent'] = "CodeIgniter";
            //$config['mailpath'] = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
            $config['wordwrap'] = TRUE;
            $config['wrapchars'] = 76;
            $config['mailtype'] = "html";
            $config['charset'] = 'utf-8';
            $config['validate'] = FALSE;
            $config['priority'] = 3;
            $config['newline'] = "\r\n";
            $config['crlf'] = "\r\n";
            $config['protocol'] = config_item('protocol');

            if ($config['protocol'] == "smtp") {
                $config['protocol'] = 'smtp';
                $config['smtp_host'] = config_item('smtp_host');
                $config['smtp_port'] = config_item('smtp_port');
                $config['smtp_timeout'] = "30";
                $config['smtp_user'] = config_item('smtp_user');
                $config['smtp_pass'] = decrypt(config_item('smtp_pass'));
                $config['smtp_crypto'] = config_item('smtp_encryption');
            }
            // var_dump($config);die;
            $this->email->initialize($config);
            $this->email->from(config_item('company_email'), config_item('company_name'));
            $this->email->to($params['recipient']);
            if (!empty($params['reply_to'])) {
                $this->email->reply_to($params['reply_to']);
            }
            $this->email->subject($params['subject']);
            $this->email->message($params['message']);
            if ($params['resourceed_file'] != '') {
                $this->email->attach($params['resourceed_file']);
            }
            $send = $this->email->send();
// var_dump($send);die;
            if (!$test) {
                if ($send) {
                    return $send;
                } else {
                    // $error = show_error($this->email->print_debugger());
                    return $send;
                }
            }
            return true;
        }
    }

    public function all_files()
    {
        $language = array(
            "main_lang.php" => "./application/",
            "tasks_lang.php" => "./application/",
            "projects_lang.php" => "./application/",
            "leads_lang.php" => "./application/",
            "opportunities_lang.php" => "./application/",
            "sales_lang.php" => "./application/",
            "transactions_lang.php" => "./application/",
            "bugs_lang.php" => "./application/",
            "tickets_lang.php" => "./application/",
            "client_lang.php" => "./application/",
            "departments_lang.php" => "./application/",
            "leave_management_lang.php" => "./application/",
            "settings_lang.php" => "./application/",
            "utilities_lang.php" => "./application/",
            "stock_lang.php" => "./application/",
            "performance_lang.php" => "./application/",
            "payroll_lang.php" => "./application/",
            "form_validation_lang.php" => "./application/",
            "date_lang.php" => "./application/",
            "db_lang.php" => "./application/",
            "email_lang.php" => "./application/",
            "migration_lang.php" => "./application/",
            "pagination_lang.php" => "./application/",
            "upload_lang.php" => "./application/"
        );
        return $language;
    }

    function task_spent_time_by_id($id, $project = null)
    {
        if (!empty($project)) {
            $where = 'project_id = ' . $id;
        } else {
            $where = 'task_id = ' . $id;
        }
        $total_time = "SELECT start_time,end_time,end_time - start_time time_spent
						FROM tbl_tasks_timer WHERE $where";
        $result = $this->db->query($total_time)->result();
        $time_spent = array();
        foreach ($result as $time) {
            if ($time->start_time != 0 && $time->end_time != 0) {
                $time_spent[] = $time->time_spent;
            }
        }
        if (is_array($time_spent)) {
            return array_sum($time_spent);
        } else {
            return 0;
        }
    }

    function get_estime_time($hour)
    {
        if (!empty($hour)) {
            $total = explode(':', $hour);
            if (!empty($total[0])) {
                $hours = $total[0] * 3600;
                if (!empty($total[1])) {
                    $minute = ($total[1] * 60);
                } else {
                    $minute = 0;
                }
                return $hours + $minute;
            }
        }
    }

    function get_time_spent_result($seconds)
    {
        $init = $seconds;
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;
        return "<ul class='timer'><li>" . $hours . "<span>" . lang('hours') . "</span></li>" . "<li class='dots'>" . ":</li><li>" . $minutes . "<span>" . lang('minutes') . "</span></li>" . "<li class='dots'>" . ":</li><li>" . $seconds . "<span>" . lang('seconds') . "</span></li></ul>";
    }

    function get_time_spent_pain_result($seconds)
    {
        $init = $seconds;
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;
        return "$hours:$minutes:$seconds";
    }

    function get_spent_time($seconds, $result = null)
    {
        $init = $seconds;
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;
        if (!empty($result)) {
            return $hours . " : " . $minutes . " : " . $seconds;
        } else {
            return $hours . " <strong> " . lang('hours') . " </strong>" . " : " . $minutes . " <strong> " . lang('minutes') . "</strong>" . " : " . $seconds . "<strong> " . lang('seconds') . "</strong>";
        }
    }

    public function get_progress($goal_info, $currency = null)
    {

        $goal_type_info = get_row('tbl_goal_type', array('goal_type_id' => $goal_info->goal_type_id));
        $start_date = $goal_info->start_date;
        $end_date = $goal_info->end_date;
        $achievement = round($goal_info->achievement);
        if ($goal_type_info->tbl_name == 'tbl_transactions') {
            if ($goal_type_info->type_name == 'achive_total_income_by_bank' || $goal_type_info->type_name == 'achive_total_expense_by_bank') {
                if ($goal_info->account_id != '0') {
                    $where = array(
                        'account_id' => $goal_info->account_id,
                        'date >=' => $start_date,
                        'date <=' => $end_date,
                        'type' => $goal_type_info->query
                    );
                } else {
                    $where = array(
                        'date >=' => $start_date,
                        'date <=' => $end_date,
                        'type' => $goal_type_info->query
                    );
                }
            } else {

                $where = array(
                    'date >=' => $start_date,
                    'date <=' => $end_date,
                    'type' => $goal_type_info->query
                );
            }
            $curency = $this->check_by(array(
                'code' => config_item('default_currency')
            ), 'tbl_currencies');
            $transactions_result = $this->db->select_sum('amount')->where($where)->get($goal_type_info->tbl_name)->row()->amount;
            $tr_amount = round($transactions_result);
            if ($achievement <= $tr_amount) {
                $result['progress'] = 100;
            } else {
                $progress = ($tr_amount / $achievement) * 100;
                $result['progress'] = round($progress);
            }
            if (!empty($currency)) {
                $result['achievement'] = $tr_amount;
            } else {
                $result['achievement'] = display_money($tr_amount, $curency->symbol);
            }
        }
        if ($goal_type_info->tbl_name == 'tbl_invoices' || $goal_type_info->tbl_name == 'tbl_estimates') {
            $where = array(
                'date_saved >=' => $start_date . " 00:00:00",
                'date_saved <=' => $end_date . " 23:59:59"
            );
            $invoice_result = count(get_result($goal_type_info->tbl_name, $where));
            if ($achievement <= $invoice_result) {
                $result['progress'] = 100;
            } else {
                $progress = ($invoice_result / $achievement) * 100;
                $result['progress'] = round($progress);
            }
            $result['achievement'] = $invoice_result;
        }
        if ($goal_type_info->tbl_name == 'tbl_task') {
            $where = array(
                'task_created_date >=' => $start_date . " 00:00:00",
                'task_created_date <=' => $end_date . " 23:59:59",
                'task_status' => 'completed'
            );

            $task_result = count(get_result($goal_type_info->tbl_name, $where));
            if ($achievement <= $task_result) {
                $result['progress'] = 100;
            } else {
                $progress = ($task_result / $achievement) * 100;
                $result['progress'] = round($progress);
            }
            $result['achievement'] = $task_result;
        }
        if ($goal_type_info->tbl_name == 'tbl_bug') {
            $where = array(
                'update_time >=' => $start_date . " 00:00:00",
                'update_time <=' => $end_date . " 23:59:59",
                'bug_status' => 'resolved'
            );

            $bugs_result = count(get_result($goal_type_info->tbl_name, $where));
            if ($achievement <= $bugs_result) {
                $result['progress'] = 100;
            } else {
                $progress = ($bugs_result / $achievement) * 100;
                $result['progress'] = round($progress);
            }
            $result['achievement'] = $bugs_result;
        }
        if ($goal_type_info->tbl_name == 'tbl_client') {
            if ($goal_type_info->type_name = 'convert_leads_to_client') {
                $where = array(
                    'date_added >=' => $start_date . " 00:00:00",
                    'date_added <=' => $end_date . " 23:59:59",
                    'leads_id !=' => '0'
                );
            } else {
                $where = array(
                    'date_added >=' => $start_date . " 00:00:00",
                    'date_added <=' => $end_date . " 23:59:59",
                    'leads_id' => '0'
                );
            }
            $client_result = count(get_result($goal_type_info->tbl_name, $where));

            if ($achievement <= $client_result) {
                $result['progress'] = 100;
            } else {
                $progress = ($client_result / $achievement) * 100;
                $result['progress'] = round($progress);
            }
            $result['achievement'] = $client_result;
        }
        if ($goal_type_info->tbl_name == 'tbl_payments') {
            $where = array(
                'payment_date >=' => $start_date,
                'payment_date <=' => $end_date
            );

            $payments_result = $this->db->select('currency')->select_sum('amount')->where($where)->get($goal_type_info->tbl_name)->row();

            if ($achievement <= $payments_result->amount) {
                $result['progress'] = 100;
            } else {
                $progress = ($payments_result->amount / $achievement) * 100;
                $result['progress'] = round($progress);
            }
            if (!empty($currency)) {
                $result['achievement'] = $payments_result->amount;
            } else {
                $result['achievement'] = display_money($payments_result->amount, $payments_result->currency);
            }
        }
        if ($goal_type_info->tbl_name == 'tbl_project') {
            $where = array(
                'created_time >=' => $start_date . " 00:00:00",
                'created_time <=' => $end_date . " 23:59:59",
                'project_status' => 'completed'
            );

            $task_result = count(get_result($goal_type_info->tbl_name, $where));
            if ($achievement <= $task_result) {
                $result['progress'] = 100;
            } else {
                $progress = ($task_result / $achievement) * 100;
                $result['progress'] = round($progress);
            }
            $result['achievement'] = $task_result;
        }
        if (!empty($result)) {
            return $result;
        } else {
            $result['progress'] = 0;
            $result['achievement'] = 0;
            return $result;
        }
    }

    public function send_goal_mail($type, $goal_info)
    {
        $email_template = $this->check_by(array(
            'email_group' => $type
        ), 'tbl_email_templates');


        $goal_type_info = get_row('tbl_goal_type', array('goal_type_id' => $goal_info->goal_type_id));
        $progress = $this->get_progress($goal_info);

        $message = $email_template->template_body;

        $subject = $email_template->subject;

        $Type = str_replace("{Goal_Type}", lang($goal_type_info->type_name), $message);
        $achievement = str_replace("{achievement}", $goal_info->achievement, $Type);
        $total_achievement = str_replace("{total_achievement}", $progress['achievement'], $achievement);
        $start_date = str_replace("{start_date}", $goal_info->start_date, $total_achievement);
        $message = str_replace("{End_date}", $goal_info->end_date, $start_date);

        $data['message'] = $message;

        $message = $this->load->view('email_template', $data, TRUE);

        $params['subject'] = $subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';

        if (!empty($goal_info->permission) && $goal_info->permission != 'all') {
            $user = json_decode($goal_info->permission);
            foreach ($user as $key => $v_user) {
                $allowad_user[] = $key;
            }
        } else {
            $allowad_user = $this->allowad_user_id('69');
        }

        if (!empty($allowad_user)) {
            foreach ($allowad_user as $v_user) {
                if (!empty($v_user)) {
                    $login_info = $this->check_by(array(
                        'user_id' => $v_user
                    ), 'tbl_users');
                    $params['recipient'] = $login_info->email;
                    $this->send_email($params);

                    if ($v_user != $this->session->userdata('user_id')) {
                        add_notification(array(
                            'to_user_id' => $v_user,
                            'icon' => 'shield',
                            'description' => 'not_' . $type,
                            'link' => 'admin/goal_tracking/goal_details/' . $goal_info->goal_tracking_id,
                            'value' => $goal_info->subject
                        ));
                    }
                }
            }
        }

        $udate['email_send'] = 'yes';
        $this->_table_name = "tbl_goal_tracking"; //table name
        $this->_primary_key = "goal_tracking_id";
        $this->save($udate, $goal_info->goal_tracking_id);

        return true;
    }

    function GetDays($start_date, $end_date, $step = '+1 day', $output_format = 'Y-m-d')
    {

        $dates = array();
        $current = strtotime($start_date);
        $end_date = strtotime($end_date);
        while ($current <= $end_date) {
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }

    public function all_designation($companies_id = null)
    {
        $all_department = by_company('tbl_departments', 'departments_id', null, $companies_id);
        if (!empty($all_department)) {
            foreach ($all_department as $v_department) {
                $designation[$v_department->deptname] = get_result('tbl_designations', array('departments_id' => $v_department->departments_id));
            }
            return $designation;
        }
    }

    public function get_all_employee($companies_id = null)
    {
        $all_department = by_company('tbl_departments', 'departments_id', null, $companies_id);
        if (!empty($all_department)) {
            foreach ($all_department as $v_department) {
                $designation[$v_department->deptname] = $this->all_employee($v_department->departments_id);
            }
            return $designation;
        }
    }

    function all_employee($department_id)
    {
        $this->db->select('tbl_account_details.*', FALSE);
        $this->db->select('tbl_designations.*', FALSE);
        $this->db->select('tbl_departments.*', FALSE);
        $this->db->from('tbl_account_details');
        $this->db->join('tbl_designations', 'tbl_account_details.designations_id = tbl_designations.designations_id', 'left');
        $this->db->join('tbl_departments', 'tbl_departments.departments_id = tbl_designations.departments_id', 'left');
        $this->db->where('tbl_departments.departments_id', $department_id);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function get_all_items($limit = null)
    {
        if (!empty($limit)) {
        }
        $all_items = get_result('tbl_saved_items');
        if (!empty($all_items)) {
            foreach ($all_items as $v_items) {
                $saved_items[$v_items->customer_group_id][] = $v_items;
            }
        }
        if (!empty($saved_items)) {
            return $saved_items;
        } else {
            return array();
        }
    }

    public function getItemsInfo($term, $limit = 10)
    {
        if (!empty($term)) {
            $this->db->where("(item_name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(item_name, ' (', code, ')') LIKE '%" . $term . "%')");
        }
        $companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            if ($this->db->field_exists('companies_id', 'tbl_saved_items')) {
                $this->db->where('companies_id', $companies_id);
            }
        }
        $this->db->limit($limit);
        $q = $this->db->get('tbl_saved_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_invoice_item_taxes($items_id, $type = null)
    {
        if ($type == 'estimate') {
            $item_info = get_row('tbl_estimate_items', array('estimate_items_id' => $items_id));
        } else if ($type == 'proposal') {
            $item_info = get_row('tbl_proposals_items', array('proposals_items_id' => $items_id));
        } else if ($type == 'purchase') {
            $item_info = get_row('tbl_purchase_items', array('items_id' => $items_id));
        } else {
            $item_info = get_row('tbl_items', array('items_id' => $items_id));
        }
        return json_decode($item_info->item_tax_name);
    }

    public function reduce_items($id, $qty)
    {
        $this->db->set('quantity', 'quantity -' . $qty, FALSE);
        $this->db->where('saved_items_id', $id);
        $this->db->update('tbl_saved_items');
    }

    public function return_items($id, $qty)
    {
        $this->db->set('quantity', 'quantity +' . $qty, FALSE);
        $this->db->where('saved_items_id', $id);
        $this->db->update('tbl_saved_items');
    }

    function get_online_users()
    {
        $profile = profile();
        $online['online_time'] = time();
        update('tbl_users', array('user_id' => $profile->user_id), $online);

        if ($profile->role_id == 2) {
            $companies_id = $this->session->userdata('companies_id');
            if (empty($companies_id)) {
                $companies_id = null;
            }
            $where = array('role_id !=' => '2', 'companies_id' => $companies_id, 'activated' => '1');
        } else {
            $where = array('activated' => '1');
        }
        $users = $this->db->select('user_id,online_time')->where($where)->get('tbl_users')->result();
        $result = array();
        $now = time() - 60 * 10;
        foreach ($users as $v_user) {
            if ($v_user->user_id != $this->session->userdata('user_id')) {
                $time = $v_user->online_time;
                if ($time > $now) {
                    $result['online'][] = $v_user;
                } else {
                    $result['offline'][] = $v_user;
                }
            }
        }
        return $result;
    }
    
    public function staff_query($table)
    {
        $role = $this->session->userdata('user_type');
        $userid = $this->input->post('user_id', true);
        if ($role == 3 || !empty($userid)) {
            if (empty($userid)) {
                $userid = my_id();
            }
            if (!empty($this->db->field_exists('permission', $table))) {
                $this->db->group_start();
                if ($this->db->version() >= 8) {
                    $sq = $this->db->escape('\\b' . ($userid) . '\\b');
                } else {
                    $sq = $this->db->escape('[[:<:]]' . ($userid) . '[[:>:]]');
                }
                $this->db->where($table . '.permission REGEXP', $sq, false);
                $this->db->or_where(array($table . '.permission' => 'all'));
                $this->db->group_end(); //close bracket
            }
        }
    }
}
