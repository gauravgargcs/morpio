<?php

/**
 * Description of bugs
 *
 * @author Nayeem
 */
class Bugs extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('bugs_model');
        $this->load->helper('ckeditor');
        $this->data['ckeditor'] = array(
            'id' => 'ck_editor',
            'path' => 'asset/js/ckeditor',
            'config' => array(
                'toolbar' => "Full",
                'width' => "90%",
                'height' => "200px"
            )
        );
    }

    public function index($id = NULL, $opt_id = NULL)
    {
        $data['title'] = lang('all_bugs');
        // get permission user by menu id
        $data['assign_user'] = $this->bugs_model->allowad_user('58');
        $data['all_bugs_info'] = $this->bugs_model->get_permission('tbl_bug');
        if ($id) { // retrive data from db by id
            $data['active'] = 2;
            $can_edit = $this->bugs_model->can_action('tbl_bug', 'edit', array('bug_id' => $id));
            $edited = can_action('58', 'edited');
            if ($id == 'project') {
                $data['project_id'] = $opt_id;
            } elseif ($id == 'opportunities') {
                $data['opportunities_id'] = $opt_id;
            } else {
                if (!empty($can_edit) && !empty($edited)) {
                    if (is_numeric($id)) {
                        // get all bug information
                        $data['bug_info'] = $this->db->where('bug_id', $id)->get('tbl_bug')->row();
                    }
                }
            }
            $data['all_opportunities_info'] = $this->bugs_model->get_permission('tbl_opportunities');
        } else {
            $data['active'] = 1;
        }

        $data['editor'] = $this->data;
        $data['subview'] = $this->load->view('admin/bugs/bugs', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data);
    }

    public function save_bug($id = NULL)
    {
        $created = can_action('58', 'created');
        $edited = can_action('58', 'edited');
        if (!empty($created) || !empty($edited)) {
            $data = $this->bugs_model->array_from_post(array(
                'bug_title',
                'bug_description',
                'priority',
                'reporter',
                'client_visible',
                'bug_status'));

            if (empty($id)) {
                $data['created_time'] = date("Y-m-d H:i:s");
            }
            $result = 0;
            $project_id = $this->input->post('project_id', TRUE);

            if (!empty($project_id)) {
                $data['project_id'] = $project_id;
            } else {
                $data['project_id'] = NULL;
                $result += count(1);
            }
            $opportunities_id = $this->input->post('opportunities_id', TRUE);
            if (!empty($opportunities_id)) {
                $data['opportunities_id'] = $opportunities_id;
            } else {
                $data['opportunities_id'] = NULL;
                $result += count(1);
            }
            if ($result == 2) {
                if (!empty($id)) {
                    $bugs_info = $this->db->where('bug_id', $id)->get('tbl_bug')->row();
                    $data['project_id'] = $bugs_info->project_id;
                    $data['opportunities_id'] = $bugs_info->opportunities_id;
                } else {
                    $data['project_id'] = $this->input->post('un_project_id', TRUE);
                    $data['opportunities_id'] = $this->input->post('un_opportunities_id', TRUE);
                }
            }

            $permission = $this->input->post('permission', true);
            if (!empty($permission)) {

                if ($permission == 'everyone') {
                    $assigned = 'all';
                    $assigned_to['assigned_to'] = $this->bugs_model->allowad_user_id('58');
                } else {
                    $assigned_to = $this->bugs_model->array_from_post(array('assigned_to'));
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
            $this->bugs_model->_table_name = "tbl_bug"; // table name
            $this->bugs_model->_primary_key = "bug_id"; // $id
            $return_id = $this->bugs_model->save($data, $id);

            if ($assigned == 'all') {
                $assigned_to['assigned_to'] = $this->bugs_model->allowad_user_id('58');
            }
            if (!empty($id)) {
                $msg = lang('update_bug');
                $activity = 'activity_update_bug';
                $id = $id;
                if (!empty($assigned_to['assigned_to'])) {
                    // send update
                    $this->notify_update_bugs($assigned_to['assigned_to'], $id, TRUE);
                }
            } else {
                $id = $return_id;
                $msg = lang('save_bug');
                $activity = 'activity_new_bug';
                if (!empty($assigned_to['assigned_to'])) {
                    $this->notify_bugs($assigned_to['assigned_to'], $id);
                    $this->notify_bugs_reported($id);
                }
            }
            save_custom_field(6, $id);
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'bugs',
                'module_field_id' => $id,
                'activity' => $activity,
                'icon' => 'fa-bug',
                'link' => 'admin/bugs/view_bug_details/' . $id,
                'value1' => $data['bug_title'],
            );
            // Update into tbl_project
            $this->bugs_model->_table_name = "tbl_activities"; //table name
            $this->bugs_model->_primary_key = "activities_id";
            $this->bugs_model->save($activities);

            $type = "success";
            $message = $msg;
            set_message($type, $message);
        }
        if (!empty($data['project_id']) && is_numeric($data['project_id'])) {
            redirect('admin/projects/project_details/' . $data['project_id']);
        } elseif (!empty($opportunities_id) && is_numeric($opportunities_id)) {
            redirect('admin/opportunities/opportunity_details/' . $opportunities_id);
        } else if (!empty($id)) {
            redirect('admin/bugs/view_bug_details/' . $id);
        } else {
            redirect('admin/bugs');
        }

    }

    function notify_update_bugs($users, $bug_id)
    {
        $email_template = $this->bugs_model->check_by(array('email_group' => 'bug_updated'), 'tbl_email_templates');

        $bugs_info = $this->bugs_model->check_by(array('bug_id' => $bug_id), 'tbl_bug');
        $message = $email_template->template_body;

        $subject = $email_template->subject;

        $bug_title = str_replace("{BUG_TITLE}", $bugs_info->bug_title, $message);
        $bug_status = str_replace("{STATUS}", lang($bugs_info->bug_status), $bug_title);

        $assigned_by = str_replace("{MARKED_BY}", ucfirst($this->session->userdata('name')), $bug_status);
        $Link = str_replace("{BUG_URL}", base_url() . 'admin/bugs/view_bug_details/' . $bugs_info->bug_id, $assigned_by);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $Link);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $params['subject'] = $subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';

        foreach ($users as $v_user) {
            $login_info = $this->bugs_model->check_by(array('user_id' => $v_user), 'tbl_users');
            $params['recipient'] = $login_info->email;
            $this->bugs_model->send_email($params);

            if ($v_user != $this->session->userdata('user_id')) {
                add_notification(array(
                    'to_user_id' => $v_user,
                    'from_user_id' => true,
                    'description' => 'assign_to_you_the_bugs',
                    'link' => 'admin/bugs/view_bug_details/' . $bug_id,
                    'value' => $bugs_info->bug_title,
                ));
            }
        }
        show_notification($users);
    }

    function notify_bugs_reported($bug_id)
    {

        $email_template = $this->bugs_model->check_by(array('email_group' => 'bug_reported'), 'tbl_email_templates');
        $bugs_info = $this->bugs_model->check_by(array('bug_id' => $bug_id), 'tbl_bug');

        $message = $email_template->template_body;

        $subject = $email_template->subject;

        $bug_title = str_replace("{BUG_TITLE}", $bugs_info->bug_title, $message);

        $assigned_by = str_replace("{ADDED_BY}", ucfirst($this->session->userdata('name')), $bug_title);
        $Link = str_replace("{BUG_URL}", base_url() . 'admin/bugs/view_bug_details/' . $bugs_info->bug_id, $assigned_by);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $Link);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $params['subject'] = $subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';

        $login_info = $this->bugs_model->check_by(array('user_id' => $bugs_info->reporter), 'tbl_users');
        $params['recipient'] = $login_info->email;
        $this->bugs_model->send_email($params);
    }

    function notify_bugs($users, $bug_id, $update = NULL)
    {
        if (!empty($update)) {
            $email_template = $this->bugs_model->check_by(array('email_group' => 'bugs_updated'), 'tbl_email_templates');
            $description = 'not_bug_update';
        } else {
            $email_template = $this->bugs_model->check_by(array('email_group' => 'bug_assigned'), 'tbl_email_templates');
            $description = 'assign_to_you_the_bugs';
        }
        $bugs_info = $this->bugs_model->check_by(array('bug_id' => $bug_id), 'tbl_bug');
        $message = $email_template->template_body;

        $subject = $email_template->subject;

        $bug_title = str_replace("{BUG_TITLE}", $bugs_info->bug_title, $message);

        $assigned_by = str_replace("{ASSIGNED_BY}", ucfirst($this->session->userdata('name')), $bug_title);
        $Link = str_replace("{BUG_URL}", base_url() . 'admin/bugs/view_bug_details/' . $bugs_info->bug_id, $assigned_by);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $Link);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $params['subject'] = $subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';

        foreach ($users as $v_user) {
            $login_info = $this->bugs_model->check_by(array('user_id' => $v_user), 'tbl_users');
            $params['recipient'] = $login_info->email;
            $this->bugs_model->send_email($params);

            if ($v_user != $this->session->userdata('user_id')) {
                add_notification(array(
                    'to_user_id' => $v_user,
                    'from_user_id' => true,
                    'description' => $description,
                    'link' => 'admin/bugs/view_bug_details/' . $bug_id,
                    'value' => $bugs_info->bug_title,
                ));
            }
        }
        show_notification($users);
    }

    public function update_users($id)
    {
        // get all assign_user
        $can_edit = $this->bugs_model->can_action('tbl_bug', 'edit', array('bug_id' => $id));
        $edited = can_action('58', 'edited');
        if (!empty($can_edit) && !empty($edited)) {
            $data['assign_user'] = $this->bugs_model->allowad_user('58');

            $data['bugs_info'] = $this->bugs_model->check_by(array('bug_id' => $id), 'tbl_bug');
            $data['modal_subview'] = $this->load->view('admin/bugs/_modal_users', $data, FALSE);
            $this->load->view('admin/_layout_skote_modal', $data);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function update_member($id)
    {
        $can_edit = $this->bugs_model->can_action('tbl_bug', 'edit', array('bug_id' => $id));
        $edited = can_action('58', 'edited');
        if (!empty($can_edit) && !empty($edited)) {
            $bugs_info = $this->bugs_model->check_by(array('bug_id' => $id), 'tbl_bug');

            $permission = $this->input->post('permission', true);
            if (!empty($permission)) {
                if ($permission == 'everyone') {
                    $assigned = 'all';
                    $assigned_to['assigned_to'] = $this->bugs_model->allowad_user_id('58');
                } else {
                    $assigned_to = $this->bugs_model->array_from_post(array('assigned_to'));
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
            $this->bugs_model->_table_name = "tbl_bug"; // table name
            $this->bugs_model->_primary_key = "bug_id"; // $id
            $this->bugs_model->save($data, $id);
            if ($assigned == 'all') {
                $assigned_to['assigned_to'] = $this->bugs_model->allowad_user_id('58');
            }

            $msg = lang('update_bug');
            $activity = 'activity_update_bug';
            if (!empty($assigned_to['assigned_to'])) {
                $this->notify_update_bugs($assigned_to['assigned_to'], $id);
            }

            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'bugs',
                'module_field_id' => $id,
                'activity' => $activity,
                'icon' => 'fa-bug',
                'link' => 'admin/bugs/view_bug_details/' . $id,
                'value1' => $bugs_info->bug_title,
            );
            // Update into tbl_project
            $this->bugs_model->_table_name = "tbl_activities"; //table name
            $this->bugs_model->_primary_key = "activities_id";
            $this->bugs_model->save($activities);

            $type = "success";
            $message = $msg;
            set_message($type, $message);
        } else {
            set_message('error', lang('there_in_no_value'));

        }
        redirect($_SERVER['HTTP_REFERER']);

    }

    public function change_status($id, $status)
    {
        $can_edit = $this->bugs_model->can_action('tbl_bug', 'edit', array('bug_id' => $id));
        $edited = can_action('58', 'edited');
        if (!empty($can_edit) && !empty($edited)) {
            $bugs_info = $this->bugs_model->check_by(array('bug_id' => $id), 'tbl_bug');

            if (!empty($bugs_info->permission) && $bugs_info->permission != 'all') {
                $user = json_decode($bugs_info->permission);
                foreach ($user as $key => $v_user) {
                    $allowad_user[] = $key;
                }
            } else {
                $allowad_user = $this->bugs_model->allowad_user_id('58');
            }

            if (!empty($notifiedUsers)) {
                foreach ($notifiedUsers as $users) {
                    if ($users != $this->session->userdata('user_id')) {
                        add_notification(array(
                            'to_user_id' => $users,
                            'from_user_id' => true,
                            'description' => 'not_changed_status',
                            'link' => 'admin/bugs/view_bug_details/' . $id,
                            'value' => lang('status') . ' : ' . $bugs_info->bug_status . ' to ' . $status,
                        ));
                    }
                }
                show_notification($notifiedUsers);
            }

            if (!empty($allowad_user)) {
                $this->notify_update_bugs($allowad_user, $id, TRUE);
            }

            $data['bug_status'] = $status;

//save data into table.
            $this->bugs_model->_table_name = "tbl_bug"; // table name
            $this->bugs_model->_primary_key = "bug_id"; // $id
            $id = $this->bugs_model->save($data, $id);
// save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'bugs',
                'module_field_id' => $id,
                'activity' => 'activity_update_bug',
                'icon' => 'fa-bug',
                'link' => 'admin/bugs/view_bug_details/' . $id,
                'value1' => lang($data['bug_status']),
            );
// Update into tbl_project
            $this->bugs_model->_table_name = "tbl_activities"; //table name
            $this->bugs_model->_primary_key = "activities_id";
            $this->bugs_model->save($activities);

            $type = "success";
            $message = lang('update_bug');
            set_message($type, $message);
        } else {
            set_message('error', lang('there_in_no_value'));

        }
        redirect($_SERVER['HTTP_REFERER']);

    }

    public function save_bugs_notes($id = NULL)
    {

        $data = $this->bugs_model->array_from_post(array('notes'));

        //save data into table.
        $this->bugs_model->_table_name = "tbl_bug"; // table name
        $this->bugs_model->_primary_key = "bug_id"; // $id
        $id = $this->bugs_model->save($data, $id);
        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'bugs',
            'module_field_id' => $id,
            'activity' => 'activity_update_bug',
            'icon' => 'fa-bug',
            'link' => 'admin/bugs/view_bug_details/' . $id . '/4',
            'value1' => $data['notes'],
        );
        // Update into tbl_project
        $this->bugs_model->_table_name = "tbl_activities"; //table name
        $this->bugs_model->_primary_key = "activities_id";
        $this->bugs_model->save($activities);

        $type = "success";
        $message = lang('update_bug');
        set_message($type, $message);
        redirect('admin/bugs/view_bug_details/' . $id . '/4');
    }

    public function save_comments()
    {

        $data['bug_id'] = $this->input->post('bug_id', TRUE);
        $data['comment'] = $this->input->post('comment', TRUE);

        $files = $this->input->post("files");
        $target_path = getcwd() . "/uploads/";
        //process the fiiles which has been uploaded by dropzone
        if (!empty($files) && is_array($files)) {
            foreach ($files as $key => $file) {
                if (!empty($file)) {
                    $file_name = $this->input->post('file_name_' . $file);
                    $new_file_name = move_temp_file($file_name, $target_path);
                    $file_ext = explode(".", $new_file_name);
                    $is_image = check_image_extension($new_file_name);
                    $size = $this->input->post('file_size_' . $file) / 1000;
                    if ($new_file_name) {
                        $up_data[] = array(
                            "fileName" => $new_file_name,
                            "path" => "uploads/" . $new_file_name,
                            "fullPath" => getcwd() . "/uploads/" . $new_file_name,
                            "ext" => '.' . end($file_ext),
                            "size" => round($size, 2),
                            "is_image" => $is_image,
                        );
                        $success = true;
                    } else {
                        $success = false;
                    }
                }
            }
        }
        //process the files which has been submitted manually
        if ($_FILES) {
            $files = $_FILES['manualFiles'];
            if ($files && count($files) > 0) {
                foreach ($files["tmp_name"] as $key => $file) {
                    $temp_file = $file;
                    $file_name = $files["name"][$key];
                    $file_size = $files["size"][$key];
                    $new_file_name = move_temp_file($file_name, $target_path, "", $temp_file);
                    if ($new_file_name) {
                        $file_ext = explode(".", $new_file_name);
                        $is_image = check_image_extension($new_file_name);
                        $up_data[] = array(
                            "fileName" => $new_file_name,
                            "path" => "uploads/" . $new_file_name,
                            "fullPath" => getcwd() . "/uploads/" . $new_file_name,
                            "ext" => '.' . end($file_ext),
                            "size" => round($file_size, 2),
                            "is_image" => $is_image,
                        );
                    }
                }
            }
        }
        if (!empty($up_data)) {
            $data['comments_attachment'] = json_encode($up_data);
        }
        $data['user_id'] = $this->session->userdata('user_id');

//save data into table.
        $this->bugs_model->_table_name = "tbl_task_comment"; // table name
        $this->bugs_model->_primary_key = "task_comment_id"; // $id
        $comment_id = $this->bugs_model->save($data);
        if (!empty($comment_id)) {
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'bugs',
                'module_field_id' => $data['bug_id'],
                'activity' => 'activity_new_bug_comment',
                'icon' => 'fa-bug',
                'link' => 'admin/bugs/view_bug_details/' . $data['bug_id'] . '/2',
                'value1' => $data['comment'],
            );
            // Update into tbl_project
            $this->bugs_model->_table_name = "tbl_activities"; //table name
            $this->bugs_model->_primary_key = "activities_id";
            $this->bugs_model->save($activities);

            $bugs_info = $this->bugs_model->check_by(array('bug_id' => $data['bug_id']), 'tbl_bug');

            if (!empty($bugs_info->permission) && $bugs_info->permission != 'all') {
                $user = json_decode($bugs_info->permission);
                foreach ($user as $key => $v_user) {
                    $notifiedUsers[] = $key;
                }
            } else {
                $notifiedUsers = $this->bugs_model->allowad_user_id('58');
            }

            if (!empty($notifiedUsers)) {
                foreach ($notifiedUsers as $users) {
                    if ($users != $this->session->userdata('user_id')) {
                        add_notification(array(
                            'to_user_id' => $users,
                            'from_user_id' => true,
                            'description' => 'not_new_comment',
                            'link' => 'admin/bugs/view_bug_details/' . $data['bug_id'] . '/2',
                            'value' => lang('bug') . ' : ' . $bugs_info->bug_title,
                        ));
                    }
                }
                show_notification($notifiedUsers);
            }
            // send notification
            $this->notify_comments_bugs($comment_id);
            $response_data = "";
            $view_data['comment_details'] = $this->db->where(array('task_comment_id' => $comment_id))->order_by('comment_datetime', 'DESC')->get('tbl_task_comment')->result();
            $response_data = $this->load->view("admin/bugs/comments_list", $view_data, true);
            echo json_encode(array("status" => 'success', "data" => $response_data, 'message' => lang('bug_comment_save')));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('error_occurred')));
            exit();
        }
    }

    public function save_comments_reply($task_comment_id)
    {
        $data['bug_id'] = $this->input->post('bug_id', TRUE);
        $data['comment'] = $this->input->post('reply_comments', TRUE);
        $data['user_id'] = $this->session->userdata('user_id');
        $data['comments_reply_id'] = $task_comment_id;
        //save data into table.
        $this->bugs_model->_table_name = "tbl_task_comment"; // table name
        $this->bugs_model->_primary_key = "task_comment_id"; // $id
        $comment_id = $this->bugs_model->save($data);
        if (!empty($comment_id)) {
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'bugs',
                'module_field_id' => $data['bug_id'],
                'activity' => 'activity_new_comment_reply',
                'icon' => 'fa-bug',
                'link' => 'admin/bugs/view_bug_details/' . $data['bug_id'] . '/2',
                'value1' => $this->db->where('task_comment_id', $task_comment_id)->get('tbl_task_comment')->row()->comment,
                'value2' => $data['comment'],
            );
            // Update into tbl_project
            $this->bugs_model->_table_name = "tbl_activities"; //table name
            $this->bugs_model->_primary_key = "activities_id";
            $this->bugs_model->save($activities);

            $bugs_info = $this->bugs_model->check_by(array('bug_id' => $data['bug_id']), 'tbl_bug');
            $comments_info = $this->bugs_model->check_by(array('task_comment_id' => $task_comment_id), 'tbl_task_comment');
            $user = $this->bugs_model->check_by(array('user_id' => $comments_info->user_id), 'tbl_users');
            if ($user->role_id == 2) {
                $url = 'client/';
            } else {
                $url = 'admin/';
            }
            $notifiedUsers = array($comments_info->user_id);

            if (!empty($notifiedUsers)) {
                foreach ($notifiedUsers as $users) {
                    if ($users != $this->session->userdata('user_id')) {
                        add_notification(array(
                            'to_user_id' => $users,
                            'from_user_id' => true,
                            'description' => 'not_comment_reply',
                            'link' => $url . 'bugs/view_bug_details/' . $data['bug_id'] . '/2',
                            'value' => lang('bug') . ' : ' . $bugs_info->bug_title,
                        ));
                    }
                }
                show_notification($notifiedUsers);
            }

            // send notification
            $this->notify_comments_bugs($comment_id);
            $response_data = "";
            $view_data['comment_reply_details'] = $this->db->where(array('task_comment_id' => $comment_id))->order_by('comment_datetime', 'ASC')->get('tbl_task_comment')->result();
            $response_data = $this->load->view("admin/bugs/comments_reply", $view_data, true);
            echo json_encode(array("status" => 'success', "data" => $response_data, 'message' => lang('bug_comment_save')));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('error_occurred')));
            exit();
        }
    }

    function notify_comments_bugs($comment_id)
    {
        $email_template = $this->bugs_model->check_by(array('email_group' => 'bug_comments'), 'tbl_email_templates');
        $bugs_comment_info = $this->bugs_model->check_by(array('task_comment_id' => $comment_id), 'tbl_task_comment');

        $bugs_info = $this->bugs_model->check_by(array('bug_id' => $bugs_comment_info->bug_id), 'tbl_bug');
        $message = $email_template->template_body;

        $subject = $email_template->subject;

        $bug_name = str_replace("{BUG_TITLE}", $bugs_info->bug_title, $message);
        $assigned_by = str_replace("{POSTED_BY}", ucfirst($this->session->userdata('name')), $bug_name);
        $Link = str_replace("{COMMENT_URL}", base_url() . 'admin/bugs/view_bug_details/' . $bugs_info->bug_id . '/' . $data['active'] = 2, $assigned_by);
        $comments = str_replace("{COMMENT_MESSAGE}", $bugs_comment_info->comment, $Link);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $comments);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $params['subject'] = $subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';

        if (!empty($bugs_info->permission) && $bugs_info->permission != 'all') {
            $user = json_decode($bugs_info->permission);
            foreach ($user as $key => $v_user) {
                $allowad_user[] = $key;
            }
        } else {
            $allowad_user = $this->bugs_model->allowad_user_id('58');
        }
        if (!empty($allowad_user)) {
            foreach ($allowad_user as $v_user) {
                $login_info = $this->bugs_model->check_by(array('user_id' => $v_user), 'tbl_users');
                $params['recipient'] = $login_info->email;
                $this->bugs_model->send_email($params);
            }
        }
    }

    public function delete_bug_comments($task_comment_id)
    {
        $comments_info = $this->bugs_model->check_by(array('task_comment_id' => $task_comment_id), 'tbl_task_comment');

        if (!empty($comments_info->comments_attachment)) {
            $attachment = json_decode($comments_info->comments_attachment);
            foreach ($attachment as $v_file) {
                remove_files($v_file->fileName);
            }
        }
        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'bugs',
            'module_field_id' => $comments_info->bug_id,
            'activity' => 'activity_comment_deleted',
            'icon' => 'fa-bug',
            'link' => 'admin/bugs/view_bug_details/' . $comments_info->bug_id . '/2',
            'value1' => $comments_info->comment,
        );
        // Update into tbl_project
        $this->bugs_model->_table_name = "tbl_activities"; //table name
        $this->bugs_model->_primary_key = "activities_id";
        $this->bugs_model->save($activities);

//save data into table.
        $this->bugs_model->_table_name = "tbl_task_comment"; // table name
        $this->bugs_model->_primary_key = "task_comment_id"; // $id
        $this->bugs_model->delete($task_comment_id);

        //save data into table.
        $this->bugs_model->_table_name = "tbl_task_comment"; // table name
        $this->bugs_model->delete_multiple(array('comments_reply_id' => $task_comment_id));

        echo json_encode(array("status" => 'success', 'message' => lang('bug_comment_deleted')));
        exit();

    }

    public function delete_bug_files($task_attachment_id)
    {
        $file_info = $this->bugs_model->check_by(array('task_attachment_id' => $task_attachment_id), 'tbl_task_attachment');
// save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'bugs',
            'module_field_id' => $file_info->bug_id,
            'activity' => 'activity_bug_attachfile_deleted',
            'icon' => 'fa-bug',
            'link' => 'admin/bugs/view_bug_details/' . $file_info->bug_id . '/3',
            'value1' => $file_info->title,
        );
// Update into tbl_project
        $this->bugs_model->_table_name = "tbl_activities"; //table name
        $this->bugs_model->_primary_key = "activities_id";
        $this->bugs_model->save($activities);

//save data into table.
        $this->bugs_model->_table_name = "tbl_task_attachment"; // table name        
        $this->bugs_model->delete_multiple(array('task_attachment_id' => $task_attachment_id));

        $uploadFileinfo = $this->db->where('task_attachment_id', $task_attachment_id)->get('tbl_task_uploaded_files')->result();
        if (!empty($uploadFileinfo)) {
            foreach ($uploadFileinfo as $Fileinfo) {
                $docroom_file_id=$Fileinfo->docroom_file_id;
                $user_id = $this->session->userdata('user_id');
                $access_token=$this->session->userdata('docroom_access_token');
                if($docroom_file_id>0){
                    $user_docroom_info = userDocroomInfo();
                    $account_id=$user_docroom_info->docroom_account_id;

                    $delete_file=$this->docroom_connect->delete_file(array('access_token'=>$access_token,'account_id'=>$account_id),$docroom_file_id);
                }

                remove_files($Fileinfo->file_name);
            }
        }
        //save data into table.
        $this->bugs_model->_table_name = "tbl_task_uploaded_files"; // table name
        $this->bugs_model->delete_multiple(array('task_attachment_id' => $task_attachment_id));

        echo json_encode(array("status" => 'success', 'message' => lang('bug_attachfile_deleted')));
        exit();
    }

    public function new_attachment($id)
    {
        $data['dropzone'] = true;
        $data['bugs_details'] = $this->bugs_model->check_by(array('bug_id' => $id), 'tbl_bug');
        $data['modal_subview'] = $this->load->view('admin/bugs/new_attachment', $data, FALSE);
        $this->load->view('admin/_layout_skote_modal', $data);
    }

    public function attachment_details($type, $id)
    {
        $data['type'] = $type;
        $data['attachment_info'] = $this->bugs_model->check_by(array('task_attachment_id' => $id), 'tbl_task_attachment');
        $data['modal_subview'] = $this->load->view('admin/bugs/attachment_details', $data, FALSE);
        $this->load->view('admin/_layout_skote_modal_extra_lg', $data);
    }

    public function save_attachment($task_attachment_id = NULL)
    {

        $data = $this->bugs_model->array_from_post(array('title', 'description', 'bug_id'));

        $data['user_id'] = $this->session->userdata('user_id');

        // save and update into tbl_files
        $this->bugs_model->_table_name = "tbl_task_attachment"; //table name
        $this->bugs_model->_primary_key = "task_attachment_id";
        if (!empty($task_attachment_id)) {
            $id = $task_attachment_id;
            $this->bugs_model->save($data, $id);
            $msg = lang('project_file_updated');
        } else {
            $id = $this->bugs_model->save($data);
            $msg = lang('project_file_added');
        }
        $files = $this->input->post("files");
        $docroom_file_id = $this->input->post("docroom_file_id");

        $target_path = getcwd() . "/uploads/";
        //process the fiiles which has been uploaded by dropzone
        if (!empty($files) && is_array($files)) {
            foreach ($files as $key => $file) {
                if (!empty($file)) {
                    $file_name = $this->input->post('file_name_' . $file);
                    $new_file_name = move_temp_file($file_name, $target_path);
                    $file_ext = explode(".", $new_file_name);
                    $is_image = check_image_extension($new_file_name);

                    if ($new_file_name) {
                        $up_data = array(
                            "files" => "uploads/" . $new_file_name,
                            "uploaded_path" => getcwd() . "/uploads/" . $new_file_name,
                            "file_name" => $new_file_name,
                            "size" => $this->input->post('file_size_' . $file),
                            "ext" => end($file_ext),
                            "is_image" => $is_image,
                            "image_width" => 0,
                            "image_height" => 0,
                            "task_attachment_id" => $id,
                            "docroom_file_id" => $docroom_file_id[$key]
                        );
                        $this->bugs_model->_table_name = "tbl_task_uploaded_files"; // table name
                        $this->bugs_model->_primary_key = "uploaded_files_id"; // $id
                        $uploaded_files_id = $this->bugs_model->save($up_data);

                        // saved into comments
                        $comment = $this->input->post('comment_' . $file);
                        $u_cdata = array(
                            "comment" => $comment,
                            "bug_id" => $data['bug_id'],
                            "user_id" => $this->session->userdata('user_id'),
                            "uploaded_files_id" => $uploaded_files_id,
                        );
                        $this->bugs_model->_table_name = "tbl_task_comment"; // table name
                        $this->bugs_model->_primary_key = "task_comment_id"; // $id
                        $this->bugs_model->save($u_cdata);

                        $success = true;

                    } else {
                        $success = false;
                    }
                }
            }
        }
        //process the files which has been submitted manually
        if ($_FILES) {
            $files = $_FILES['manualFiles'];
            if ($files && count($files) > 0) {
                $comment = $this->input->post('comment');
                foreach ($files["tmp_name"] as $key => $file) {
                    $temp_file = $file;
                    $file_name = $files["name"][$key];
                    $file_size = $files["size"][$key];
                    $new_file_name = move_temp_file($file_name, $target_path, "", $temp_file);
                    if ($new_file_name) {
                        $file_ext = explode(".", $new_file_name);
                        $is_image = check_image_extension($new_file_name);
                        $up_data = array(
                            "files" => "uploads/" . $new_file_name,
                            "uploaded_path" => getcwd() . "/uploads/" . $new_file_name,
                            "file_name" => $new_file_name,
                            "size" => $file_size,
                            "ext" => end($file_ext),
                            "is_image" => $is_image,
                            "image_width" => 0,
                            "image_height" => 0,
                            "task_attachment_id" => $id,
                            "docroom_file_id" => $docroom_file_id[$key]
                        );
                        $this->bugs_model->_table_name = "tbl_task_uploaded_files"; // table name
                        $this->bugs_model->_primary_key = "uploaded_files_id"; // $id
                        $uploaded_files_id = $this->bugs_model->save($up_data);

                        // saved into comments
                        if (!empty($comment[$key])) {
                            $u_cdata = array(
                                "comment" => $comment[$key],
                                "user_id" => $this->session->userdata('user_id'),
                                "uploaded_files_id" => $uploaded_files_id,
                            );
                            $this->bugs_model->_table_name = "tbl_task_comment"; // table name
                            $this->bugs_model->_primary_key = "task_comment_id"; // $id
                            $this->bugs_model->save($u_cdata);
                        }

                    }
                }
            }
        }

        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'bugs',
            'module_field_id' => $data['bug_id'],
            'activity' => 'activity_new_project_attachment',
            'icon' => 'fa-folder-open-o',
            'link' => 'admin/bugs/view_bug_details/' . $data['bug_id'] . '/3',
            'value1' => $data['title'],
        );
        // Update into tbl_project
        $this->bugs_model->_table_name = "tbl_activities"; //table name
        $this->bugs_model->_primary_key = "activities_id";
        $this->bugs_model->save($activities);

        // send notification message
        $this->notify_attchemnt_bugs($id);

        // messages for user
        $type = "success";
        $message = $msg;
        set_message($type, $message);
        redirect('admin/bugs/view_bug_details/' . $data['bug_id'] . '/' . '3');
    }

    public function save_attachment_comments()
    {
        $task_attachment_id = $this->input->post('task_attachment_id');
        if (!empty($task_attachment_id)) {
            $data['task_attachment_id'] = $task_attachment_id;
        } else {
            $data['uploaded_files_id'] = $this->input->post('uploaded_files_id');
        }
        $data['bug_id'] = $this->input->post('bug_id');
        $data['comment'] = $this->input->post('description');

        $files = $this->input->post("files");
        $target_path = getcwd() . "/uploads/";
        //process the fiiles which has been uploaded by dropzone
        if (!empty($files) && is_array($files)) {
            foreach ($files as $key => $file) {
                if (!empty($file)) {
                    $file_name = $this->input->post('file_name_' . $file);
                    $new_file_name = move_temp_file($file_name, $target_path);
                    $file_ext = explode(".", $new_file_name);
                    $is_image = check_image_extension($new_file_name);
                    $size = $this->input->post('file_size_' . $file) / 1000;
                    if ($new_file_name) {
                        $up_data[] = array(
                            "fileName" => $new_file_name,
                            "path" => "uploads/" . $new_file_name,
                            "fullPath" => getcwd() . "/uploads/" . $new_file_name,
                            "ext" => '.' . end($file_ext),
                            "size" => round($size, 2),
                            "is_image" => $is_image,
                        );
                        $success = true;
                    } else {
                        $success = false;
                    }
                }
            }
        }
        //process the files which has been submitted manually
        if ($_FILES) {
            $files = $_FILES['manualFiles'];
            if ($files && count($files) > 0) {
                $comment = $this->input->post('comment');
                foreach ($files["tmp_name"] as $key => $file) {
                    $temp_file = $file;
                    $file_name = $files["name"][$key];
                    $file_size = $files["size"][$key];
                    $new_file_name = move_temp_file($file_name, $target_path, "", $temp_file);
                    if ($new_file_name) {
                        $file_ext = explode(".", $new_file_name);
                        $is_image = check_image_extension($new_file_name);
                        $up_data[] = array(
                            "fileName" => $new_file_name,
                            "path" => "uploads/" . $new_file_name,
                            "fullPath" => getcwd() . "/uploads/" . $new_file_name,
                            "ext" => '.' . end($file_ext),
                            "size" => round($file_size, 2),
                            "is_image" => $is_image,
                        );
                    }
                }
            }
        }
        if (!empty($up_data)) {
            $data['comments_attachment'] = json_encode($up_data);
        }
        $data['user_id'] = $this->session->userdata('user_id');

        //save data into table.
        $this->bugs_model->_table_name = "tbl_task_comment"; // table name
        $this->bugs_model->_primary_key = "task_comment_id"; // $id
        $comment_id = $this->bugs_model->save($data);
        if (!empty($comment_id)) {
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'bugs',
                'module_field_id' => $data['bug_id'],
                'activity' => 'activity_new_bug_comment',
                'icon' => 'fa-bug',
                'link' => 'admin/bugs/view_bug_details/' . $data['bug_id'] . '/2',
                'value1' => $data['comment'],
            );
            // Update into tbl_project
            $this->bugs_model->_table_name = "tbl_activities"; //table name
            $this->bugs_model->_primary_key = "activities_id";
            $this->bugs_model->save($activities);

            $notifiedUsers = array();
            $bugs_info = $this->bugs_model->check_by(array('bug_id' => $data['bug_id']), 'tbl_bug');

            if (!empty($bugs_info->permission) && $bugs_info->permission != 'all') {
                $user = json_decode($bugs_info->permission);
                foreach ($user as $key => $v_user) {
                    $notifiedUsers[] = $key;
                }
            } else {
                $notifiedUsers = $this->bugs_model->allowad_user_id('58');
            }
            if (!empty($notifiedUsers)) {
                foreach ($notifiedUsers as $users) {
                    if ($users != $this->session->userdata('user_id')) {
                        add_notification(array(
                            'to_user_id' => $users,
                            'from_user_id' => true,
                            'description' => 'not_new_comment',
                            'link' => 'admin/bugs/view_bug_details/' . $data['bug_id'] . '/2',
                            'value' => lang('bug') . ' : ' . $bugs_info->bug_title,
                        ));
                    }
                }
                show_notification($notifiedUsers);
            }
            $response_data = "";
            $view_data['comment_details'] = $this->db->where(array('task_comment_id' => $comment_id))->order_by('comment_datetime', 'DESC')->get('tbl_task_comment')->result();
            $response_data = $this->load->view("admin/bugs/comments_list", $view_data, true);
            echo json_encode(array("status" => 'success', "data" => $response_data, 'message' => lang('bug_comment_save')));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('error_occurred')));
            exit();
        }

    }

    public function download_files($uploaded_files_id, $comments = null)
    {
        $this->load->helper('download');
        if (!empty($comments)) {
            if ($uploaded_files_id) {
                $down_data = file_get_contents('uploads/' . $uploaded_files_id); // Read the file's contents
                force_download($uploaded_files_id, $down_data);
            } else {
                echo json_encode(array("status" => 'error', 'message' => lang('error_occurred')));
                exit();
            }
        } else {
            $uploaded_files_info = $this->bugs_model->check_by(array('uploaded_files_id' => $uploaded_files_id), 'tbl_task_uploaded_files');
            if ($uploaded_files_info->uploaded_path) {
                $data = file_get_contents($uploaded_files_info->uploaded_path); // Read the file's contents
                force_download($uploaded_files_info->file_name, $data);
            } else {
                echo json_encode(array("status" => 'error', 'message' => lang('error_occurred')));
                exit();
            }
        }
    }

    public function download_all_files($attachment_id)
    {
        $uploaded_files_info = $this->db->where('task_attachment_id', $attachment_id)->get('tbl_task_uploaded_files')->result();
        $attachment_info = $this->db->where('task_attachment_id', $attachment_id)->get('tbl_task_attachment')->row();
        $this->load->library('zip');
        if (!empty($uploaded_files_info)) {
            $filename = slug_it($attachment_info->title);
            foreach ($uploaded_files_info as $v_files) {
                $down_data = ($v_files->files); // Read the file's contents
                $this->zip->read_file($down_data);
            }
            $this->zip->download($filename . '.zip');
        } else {
            $type = "error";
            $message = lang('operation_failed');
            set_message($type, $message);
            redirect('admin/bugs/view_bug_details/' . $attachment_info->bug_id . '/3');
        }
    }


    public function save_bug_attachment($task_attachment_id = NULL)
    {
        $data = $this->bugs_model->array_from_post(array('title', 'description', 'bug_id'));
        $data['user_id'] = $this->session->userdata('user_id');

// save and update into tbl_files
        $this->bugs_model->_table_name = "tbl_task_attachment"; //table name
        $this->bugs_model->_primary_key = "task_attachment_id";
        if (!empty($task_attachment_id)) {
            $id = $task_attachment_id;
            $this->bugs_model->save($data, $id);
            $msg = lang('bug_file_updated');
        } else {
            $id = $this->bugs_model->save($data);
            $msg = lang('bug_file_added');
        }

        if (!empty($_FILES['bug_files']['name']['0'])) {
            $old_path_info = $this->input->post('uploaded_path');
            if (!empty($old_path_info)) {
                foreach ($old_path_info as $old_path) {
                    unlink($old_path);
                }
            }
            $mul_val = $this->bugs_model->multi_uploadAllType('bug_files');

            foreach ($mul_val as $val) {
                $val == TRUE || redirect('admin/bugs/view_bug_details/3/' . $data['bug_id']);
                $fdata['files'] = $val['path'];
                $fdata['file_name'] = $val['fileName'];
                $fdata['uploaded_path'] = $val['fullPath'];
                $fdata['size'] = $val['size'];
                $fdata['ext'] = $val['ext'];
                $fdata['is_image'] = $val['is_image'];
                $fdata['image_width'] = $val['image_width'];
                $fdata['image_height'] = $val['image_height'];
                $fdata['task_attachment_id'] = $id;
                $fdata['docroom_file_id'] = $val['docroom_file_id'];

                $this->bugs_model->_table_name = "tbl_task_uploaded_files"; // table name
                $this->bugs_model->_primary_key = "uploaded_files_id"; // $id
                $this->bugs_model->save($fdata);
            }
        }
// save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'bugs',
            'module_field_id' => $data['bug_id'],
            'activity' => 'activity_new_bug_attachment',
            'icon' => 'fa-bug',
            'link' => 'admin/bugs/view_bug_details/' . $data['bug_id'] . '/3',
            'value1' => $data['title'],
        );
// Update into tbl_project
        $this->bugs_model->_table_name = "tbl_activities"; //table name
        $this->bugs_model->_primary_key = "activities_id";
        $this->bugs_model->save($activities);
// send notification message
        $this->notify_attchemnt_bugs($id);
// messages for user
        $type = "success";
        $message = $msg;
        set_message($type, $message);
        redirect('admin/bugs/view_bug_details/' . $data['bug_id'] . '/3');
    }

    function notify_attchemnt_bugs($task_attachment_id)
    {
        $email_template = $this->bugs_model->check_by(array('email_group' => 'bug_attachment'), 'tbl_email_templates');
        $bugs_comment_info = $this->bugs_model->check_by(array('task_attachment_id' => $task_attachment_id), 'tbl_task_attachment');

        $bugs_info = $this->bugs_model->check_by(array('bug_id' => $bugs_comment_info->bug_id), 'tbl_bug');

        $message = $email_template->template_body;

        $subject = $email_template->subject;

        $bug_name = str_replace("{BUG_TITLE}", $bugs_info->bug_title, $message);
        $assigned_by = str_replace("{UPLOADED_BY}", ucfirst($this->session->userdata('name')), $bug_name);
        $Link = str_replace("{BUG_URL}", base_url() . 'admin/bugs/view_bug_details/' . $bugs_info->bug_id . '/' . $data['active'] = 3, $assigned_by);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $Link);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $params['subject'] = $subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';
        if (!empty($bugs_info->permission) && $bugs_info->permission != 'all') {
            $user = json_decode($bugs_info->permission);
            foreach ($user as $key => $v_user) {
                $allowad_user[] = $key;
            }
        } else {
            $allowad_user = $this->bugs_model->allowad_user_id('58');
        }
        if (!empty($allowad_user)) {
            foreach ($allowad_user as $v_user) {
                $login_info = $this->bugs_model->check_by(array('user_id' => $v_user), 'tbl_users');
                $params['recipient'] = $login_info->email;
                $this->bugs_model->send_email($params);

                if ($v_user != $this->session->userdata('user_id')) {
                    add_notification(array(
                        'to_user_id' => $v_user,
                        'from_user_id' => true,
                        'description' => 'not_uploaded_attachment',
                        'link' => 'admin/bugs/view_bug_details/' . $bugs_info->bug_id . '/2',
                        'value' => lang('bug') . ' : ' . $bugs_info->bug_title,
                    ));
                }
            }
            show_notification($allowad_user);
        }
    }

    public function view_bug_details($id, $active = NULL, $edit = NULL)
    {
        $data['title'] = lang('bug_details');
        $data['page_header'] = lang('bug_management');

        //get all bug information
        $data['bug_details'] = $this->bugs_model->check_by(array('bug_id' => $id), 'tbl_bug');

//        //get all comments info
//        $data['comment_details'] = $this->bugs_model->get_all_comment_info($id);
// get all assign_user
        $this->bugs_model->_table_name = 'tbl_users';
        $this->bugs_model->_order_by = 'user_id';
        $data['assign_user'] = $this->bugs_model->get_by(array('role_id !=' => '2'), FALSE);

        $this->bugs_model->_table_name = "tbl_task_attachment"; //table name
        $this->bugs_model->_order_by = "bug_id";
        $data['files_info'] = $this->bugs_model->get_by(array('bug_id' => $id), FALSE);

        foreach ($data['files_info'] as $key => $v_files) {
            $this->bugs_model->_table_name = "tbl_task_uploaded_files"; //table name
            $this->bugs_model->_order_by = "task_attachment_id";
            $data['project_files_info'][$key] = $this->bugs_model->get_by(array('task_attachment_id' => $v_files->task_attachment_id), FALSE);
        }

        $data['dropzone'] = true;
        if ($active == 2) {
            $data['active'] = 2;
        } elseif ($active == 3) {
            $data['active'] = 3;
        } elseif ($active == 4) {
            $data['active'] = 4;
        } else {
            $data['active'] = 1;
        }

        $data['subview'] = $this->load->view('admin/bugs/view_bugs', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data);
    }


    public function delete_bug($id)
    {
        $can_delete = $this->bugs_model->can_action('tbl_bug', 'delete', array('bug_id' => $id));
        $deleted = can_action('58', 'deleted');
        if (!empty($can_delete) && !empty($deleted)) {
            $bug_info = $this->bugs_model->check_by(array('bug_id' => $id), 'tbl_bug');

// save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'bugs',
                'module_field_id' => $bug_info->bug_id,
                'activity' => 'activity_bug_deleted',
                'icon' => 'fa-bug',
                'value1' => $bug_info->bug_title,
            );
// Update into tbl_project
            $this->bugs_model->_table_name = "tbl_activities"; //table name
            $this->bugs_model->_primary_key = "activities_id";
            $this->bugs_model->save($activities);

            $this->bugs_model->_table_name = "tbl_task_attachment"; //table name
            $this->bugs_model->_order_by = "bug_id";
            $files_info = $this->bugs_model->get_by(array('bug_id' => $id), FALSE);

            foreach ($files_info as $v_files) {
                $uploadFileinfo = $this->db->where('task_attachment_id', $v_files->task_attachment_id)->get('tbl_task_uploaded_files')->result();
                if (!empty($uploadFileinfo)) {
                    foreach ($uploadFileinfo as $Fileinfo) {
                        $docroom_file_id=$Fileinfo->docroom_file_id;
                        $user_id = $this->session->userdata('user_id');
                        $access_token=$this->session->userdata('docroom_access_token');
                        if($docroom_file_id>0){
                            $user_docroom_info = userDocroomInfo();
                            $account_id=$user_docroom_info->docroom_account_id;

                            $delete_file=$this->docroom_connect->delete_file(array('access_token'=>$access_token,'account_id'=>$account_id),$docroom_file_id);
                        }

                        remove_files($Fileinfo->file_name);
                    }
                }
                $this->bugs_model->_table_name = "tbl_task_uploaded_files"; //table name
                $this->bugs_model->delete_multiple(array('task_attachment_id' => $v_files->task_attachment_id));
            }
            //delete into table.
            $this->bugs_model->_table_name = "tbl_task_attachment"; // table name
            $this->bugs_model->delete_multiple(array('bug_id' => $id));

            // deleted comments with file
            $all_comments_info = $this->db->where(array('bug_id' => $id))->get('tbl_task_comment')->result();
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
            $this->bugs_model->delete_multiple(array('bug_id' => $id));

            $pin_info = $this->bugs_model->check_by(array('module_name' => 'bugs', 'module_id' => $id), 'tbl_pinaction');
            if (!empty($pin_info)) {
                $this->bugs_model->_table_name = 'tbl_pinaction';
                $this->bugs_model->delete_multiple(array('module_name' => 'bugs', 'module_id' => $id));
            }
            $this->bugs_model->_table_name = "tbl_bug"; // table name
            $this->bugs_model->_primary_key = "bug_id"; // $id
            $this->bugs_model->delete($id);

            $type = "success";
            $message = lang('bug_deleted');
        } else {
            $type = "error";
            $message = lang('there_in_no_value');
        }
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }


}
