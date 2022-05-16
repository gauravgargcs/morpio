<?php

/**
 * Description of bugs
 *
 * @author Darrel
 */
class Version extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('version_model');
        if(is_subdomain() || ! super_admin()){
            redirect('admin/dashboard');
        }
    }

    public function index()
    {
       $data['title'] = lang('Version');
        $data['all_version'] = get_result('tbl_features_version');

       $data['subview'] = $this->load->view('admin/version/list', $data, TRUE);
       $this->load->view('admin/_layout_skote_main', $data);
    }
      public function edit($id='')
    {
        $this->add($id);
    }
    public function add($id='')
    {
       $data['title'] = lang('New Version');

        $data['id']=$id;

        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('title', lang('title'), 'trim|required');
        $this->form_validation->set_rules('version', lang('version'), 'trim|required');
        
        if($this->form_validation->run() == FALSE)
        {
            if(validation_errors())
            {
                $data["error"] = validation_errors();
              set_message('error',  $data["error"] );
            }
            else
            {
                $data["error"] = "";
            }
           
            
        }
        else
        {
            $data_insert = $this->version_model->array_from_post(array(
                'title',
                'version',
                'description'));
            $data_insert['created_at']=date('Y-m-d H:i:s');
         //save data into table.
            $this->version_model->_table_name = "tbl_features_version"; // table name
            $this->version_model->_primary_key = "id"; // $id
            if($id){

               $id = $this->version_model->save($data_insert, $id);
            }else{
                $this->version_model->save($data_insert);
            }


            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'features_version',
                'module_field_id' => $id,
                'activity' => 'features_version_added',
                'icon' => 'fa-tasks',
                'link' => 'admin/tasks/view_task_details/' . $id,
                'value1' => $data_insert['version'],
            );
            // Update into tbl_project
            $this->version_model->_table_name = "tbl_activities"; //table name
            $this->version_model->_primary_key = "activities_id";
            $this->version_model->save($activities);
           
             $type = "success";
          
            set_message($type, 'Version added successfully.');
            redirect('admin/version');
        }
          $this->version_model->_table_name = "tbl_features_version"; // table name
            $this->version_model->_primary_key = "id"; // $id
            $data['version']= $this->version_model->get($id,true);
        $data['subview'] = $this->load->view('admin/version/add', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }
     public function delete($id)
    {
         //save data into table.
        $this->version_model->_table_name = "tbl_features_version"; // table name
        $this->version_model->delete_multiple(array('id' => $id));
        echo json_encode(array("status" => 'success', 'message' => lang('version_deleted')));
        exit();
    }
}