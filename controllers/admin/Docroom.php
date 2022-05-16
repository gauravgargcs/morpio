<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Docroom extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings_model');
        $this->load->library('docroom_connect');

    }

    public function index($action = NULL)
    {
        $data=array();
        $data['title'] = lang('docroom');
        $data['user_id'] =$user_id= $this->session->userdata('user_id');
        $data['user_role_id'] = $this->session->userdata('user_type');
        $data['profile_info'] = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
        $data['user_info'] = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
        
        $key1=$data['user_info']->docroom_api_key1;
        $key2=$data['user_info']->docroom_api_key2;
        $access_token=$data['user_info']->docroom_access_token;
        $account_id=$main_folder_id=$docroom_folderName='';
        $docrooom_folders= $docrooom_files=$docrooom_folders_main=array();
        $enable_features=false;
        $user_docroom_info = userDocroomInfo();
        if(empty($user_docroom_info)){
            if($key1 != '' && $key2 !=''){
                $docroom_auth=$this->docroom_connect->authorize($key1,$key2);
                if(!empty($docroom_auth)){
                    $status=$docroom_auth->_status;
                    if($status=='success'){
                        $access_token=$docroom_auth->data->access_token;
                        $account_id=$docroom_auth->data->account_id;

                        $host=$_SERVER['SERVER_NAME'];

                        $create_folder= $this->docroom_connect->create_folder(array('access_token'=>$access_token,'account_id'=>$account_id),$host);
                        if(!empty($create_folder)){
                            $create_folder_status=$create_folder->_status;
                            if($create_folder_status=='success'){
                                $main_folder_id= $create_folder->data->id;
                                $folderName=$create_folder->data->folderName;

                                $docdata['user_id'] = $user_id;
                                $docdata['docroom_folder_id'] = $main_folder_id;
                                $docdata['docroom_folderName'] = $folderName;
                                $docdata['docroom_account_id'] = $account_id;
                                $this->settings_model->_table_name = 'tbl_user_docroom';
                                $this->settings_model->_primary_key = 'user_docroom_id';
                                $this->settings_model->save($docdata);

                                if(isset($_GET['folder_id']) && isset($_GET['parentId'])){
                                    $docroom_folder_id=$_GET['folder_id'];
                                }else{
                                    $docroom_folder_id=$main_folder_id;
                                }

                                $docroom_listings=$this->docroom_connect->listing(array('access_token'=>$access_token,'account_id'=>$account_id,'parent_folder_id'=>$docroom_folder_id));
                                if(!empty($docroom_listings)){
                                    $listing_status=$docroom_listings->_status;
                                    if($listing_status=='success'){
                                        $docrooom_folders=$docroom_listings->data->folders;
                                        $docrooom_files=$docroom_listings->data->files;
                                    }else{
                                        $response=$docroom_listings->response;
                                        set_message($listing_status, $response);
                                        
                                    }
                                }

                                $docroom_listings_main=$this->docroom_connect->listing(array('access_token'=>$access_token,'account_id'=>$account_id,'parent_folder_id'=>$main_folder_id));
                                $docrooom_folders_main=$docroom_listings_main->data->folders;
                                $enable_features=true;

                            }else{
                                $response=$create_folder->response;
                                set_message($create_folder_status, $response);
                                
                            }
                        }
                    }else{
                        $response=$docroom_auth->response;
                        set_message($status, $response);
                       
                    }
                }
            }
        }else{
            $main_folder_id=$user_docroom_info->docroom_folder_id;
            $docroom_folderName=$user_docroom_info->docroom_folderName;
            $account_id=$user_docroom_info->docroom_account_id;

            if(isset($_GET['folder_id']) && isset($_GET['parentId'])){
                $docroom_folder_id=$_GET['folder_id'];
            }else{
                $docroom_folder_id=$main_folder_id;
            }
            
            if($access_token != ''){
                $docroom_listings=$this->docroom_connect->listing(array('access_token'=>$access_token,'account_id'=>$account_id,'parent_folder_id'=>$docroom_folder_id));
                if(!empty($docroom_listings)){
                    $listing_status=$docroom_listings->_status;
                    if($listing_status=='success'){
                        $docrooom_folders=$docroom_listings->data->folders;
                        $docrooom_files=$docroom_listings->data->files;
                    }else{
                        $response=$docroom_listings->response;
                        if($response=="Could not validate access_token and account_id, please reauthenticate or try again."){
                            $result= $this->reauthenticate_docroom($key1, $key2);
                            $access_token=$result['access_token'];
                            $account_id=$result['account_id'];
                            $enable_features=$result['enable_features'];
                            if($account_id !="" && $access_token!=""){
                                $docroom_listings=$this->docroom_connect->listing(array('access_token'=>$access_token,'account_id'=>$account_id,'parent_folder_id'=>$docroom_folder_id));
                                if(!empty($docroom_listings)){
                                    $listing_status=$docroom_listings->_status;
                                    if($listing_status=='success'){
                                        $docrooom_folders=$docroom_listings->data->folders;
                                        $docrooom_files=$docroom_listings->data->files;
                                    }
                                }
                            }
                        }
                        set_message($listing_status, $response);
                    }
                }

                $docroom_listings_main=$this->docroom_connect->listing(array('access_token'=>$access_token,'account_id'=>$account_id,'parent_folder_id'=>$main_folder_id));

                $docrooom_folders_main=$docroom_listings_main->data->folders;
                $enable_features=true;

            }
        }

        $data['enable_features']=$enable_features;
        $data['main_folder_id']=$main_folder_id;
        $data['docroom_folderName']=$docroom_folderName;
        $data['access_token']=$access_token;
        $data['account_id']=$account_id;
        $data['docrooom_folders_main']=$docrooom_folders_main;

        $data['docrooom_folders']=$docrooom_folders;
        $data['docrooom_files']=$docrooom_files;
        $data['user_docroom_info']=$user_docroom_info;
        $data['subview'] = $this->load->view('admin/docroom/document_management', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data);
    }
    public function settings()
    {
        $user_id = $this->session->userdata('user_id');
        $user_info = $this->admin_model->check_by(array('user_id' => $user_id), 'tbl_users');
       
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div>', '</div>');
        $this->form_validation->set_rules('api_key1', lang('api_key1'), 'required');
        $this->form_validation->set_rules('api_key2', lang('api_key2'), 'required');
        if ($this->input->post()) {
            if ($this->form_validation->run() == true) {
                $key1 = $this->input->post('api_key1', true);
                $key2 = $this->input->post('api_key2', true);
 
                $docroom_auth=$this->docroom_connect->authorize($key1,$key2);
                $access_token=$account_id="";
                if(!empty($docroom_auth)){
                    $status=$docroom_auth->_status;
                    if($status=='success'){
                        $access_token=$docroom_auth->data->access_token;
                        $account_id=$docroom_auth->data->account_id;
                        $this->session->set_userdata('docroom_access_token',$access_token);
                        $data['docroom_access_token'] = $access_token;
                        $data['docroom_api_key1'] = $key1;
                        $data['docroom_api_key2'] = $key2;
                        $this->settings_model->_table_name = 'tbl_users';
                        $this->settings_model->_primary_key = 'user_id';
                        $suc = $this->settings_model->save($data, $user_id);
                        if (!empty($suc)) {
                            $type = "success";
                            $message = lang('user_docroom_integration');
                            $action = ('activity_user_docroom_integration');
                            $activity = array(
                                'user' => $this->session->userdata('user_id'),
                                'module' => 'settings',
                                'module_field_id' => $user_id,
                                'activity' => $action,
                                'value1' => $user_info->username,
                                'value2' => $this->input->post('api_key1'),
                            );
                            $this->settings_model->_table_name = 'tbl_activities';
                            $this->settings_model->_primary_key = 'activities_id';
                            $this->settings_model->save($activity);
                            
                            set_message($type, $message);

                            $user_docroom_info = userDocroomInfo();
                            if(empty($user_docroom_info)){
                               
                                $host=$_SERVER['SERVER_NAME'];

                                $create_folder= $this->docroom_connect->create_folder(array('access_token'=>$access_token,'account_id'=>$account_id),$host);
                                if(!empty($create_folder)){
                                    $create_folder_status=$create_folder->_status;
                                    if($create_folder_status=='success'){
                                        $folder_id=$create_folder->data->id;
                                        $folderName=$create_folder->data->folderName;

                                        $docdata['user_id'] = $user_id;
                                        $docdata['docroom_folder_id'] = $folder_id;
                                        $docdata['docroom_folderName'] = $folderName;
                                        $docdata['docroom_account_id'] = $account_id;
                                        $this->settings_model->_table_name = 'tbl_user_docroom';
                                        $this->settings_model->_primary_key = 'user_docroom_id';
                                        $this->settings_model->save($docdata);
                                    }else{
                                        $response=$create_folder->response;
                                        set_message($create_folder_status, $response);
                                    }
                                }
                            }

                        } else {
                            $type = 'error';
                            $message = 'something wrong';
                            set_message($type, $message);

                        }

                    }else{
                        $response=$docroom_auth->response;
                        set_message($status, $response);
                    }
                }
                
            } else {
                $s_data['form_error'] = validation_errors();
                $this->session->set_userdata($s_data);
            }

            redirect('admin/docroom/settings');

        }
            
        $rata['title'] = lang('docroom_settings');
        $rata['subview'] = $this->load->view('admin/docroom/settings', $rata, TRUE);
        $this->load->view('admin/_layout_skote_main', $rata);
    }

    public function folders_menu_listings()
    {

        $access_token=$this->session->userdata('docroom_access_token');
        $html="";
        if ($this->input->is_ajax_request()) {
            $account_id=$this->input->post('account_id');
            $parent_folder_id=$this->input->post('parent_folder_id');
            $child_folder_listings=$this->docroom_connect->listing(array('access_token'=>$access_token,'account_id'=>$account_id,'parent_folder_id'=>$parent_folder_id));
            if(!empty($child_folder_listings)){
                $child_folder_status=$child_folder_listings->_status;
                if($child_folder_status=='success'){
                    $child_folders=$child_folder_listings->data->folders;
                    if(!empty($child_folders)){ 
                        $html.="<div class='card border-0 shadow-none ps-2 mb-0'><ul class='list-unstyled mb-0'>";
                        foreach ($child_folders as $key => $child_folder) {
                            $child_folder_id=$child_folder->id;
                            $child_parentId=$child_folder->parentId;
                            $child_folderName=strval($child_folder->folderName);
                            $child_totalSize=convertToReadableSize($child_folder->totalSize);
                            $child_isPublic=$child_folder->isPublic;
                            $child_status=$child_folder->status;
                            $child_date_added=$child_folder->date_added;
                            $child_date_updated=$child_folder->date_updated;
                            $child_file_count=$child_folder->file_count;
                            $child_child_folder_count=$child_folder->child_folder_count;
                            $child_total_downloads=$child_folder->total_downloads;
                            $child_url_folder=$child_folder->url_folder;
                            $child_urlHash=$child_folder->urlHash;                                    
                            $dblclickfun='load_folders('.$child_folder_id.','.$child_parentId.',"'.$child_folderName.'")';
                            $html.="<li><div class='custom-accordion'>";
                                $html.="<a class='text-body fw-medium py-1 d-flex align-items-center' data-bs-toggle='collapse' href='#child-folder-collapse-".$child_folder_id."' role='button' aria-expanded='false' aria-controls='child-folder-collapse-".$child_folder_id."' onmouseover='load_folders_menu_data(".$child_folder_id.");' ondblclick='".$dblclickfun."'><i class='mdi mdi-folder font-size-16 text-warning me-2'></i> <span class='me-auto'>".$child_folderName."<span class='font-size-10 text-warning ms-2'>(".$child_file_count.")</span>";
                                if($child_child_folder_count>0){ 
                                    $html.="<i class='mdi mdi-chevron-up accor-down-icon ms-auto'></i>";
                                }
                                $html.="</span></a>";
                                if($child_child_folder_count>0){
                                    $html.="<div class='collapse show child-folders' id='child-folder-collapse-".$child_folder_id."' data-id='".$child_folder_id."'></div>";
                                }
                            $html.="</div></li>";
                        }
                        $html.="</ul></div>";
                    } 
                }
            }
            echo json_encode(array("folder_list" => $html));
            exit();  
        }else {
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    public function create_folder()
    {
        $user_id = $this->session->userdata('user_id');
        $access_token=$this->session->userdata('docroom_access_token');
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div>', '</div>');
        $this->form_validation->set_rules('folder_name', lang('name'), 'required');
        if ($this->input->post()) {
            if ($this->form_validation->run() == true) {
                
                $folder_name=$this->input->post('folder_name', true);
                $folder_id=$this->input->post('folder_id', true);
              
                $user_docroom_info = userDocroomInfo();
                $main_folder_id=$user_docroom_info->docroom_folder_id;

                $account_id=$user_docroom_info->docroom_account_id;

                if(!empty($folder_id)){
                    $docroom_folder_id=$folder_id;
                }else{
                    $docroom_folder_id=$main_folder_id;
                }
                $create_folder=$this->docroom_connect->create_folder(array('access_token'=>$access_token,'account_id'=>$account_id,'parent_id'=>$docroom_folder_id),$folder_name);

                if(!empty($create_folder)){
                    $status=$create_folder->_status;
                    if($status=='success'){
                        set_message('success', lang('folder_created_successfully'));
                    }else{
                        $response=$create_folder->response;
                        set_message($status, $response);
                        redirect('admin/docroom/settings');
                        
                    }
                }
            }else {

                $s_data['form_error'] = validation_errors();
                $this->session->set_userdata($s_data);

            }

            redirect($_SERVER['HTTP_REFERER']);

        }else{
            $folder_path='../'; 
            if(isset($_GET['folder_id']) && isset($_GET['parentId']) && isset($_GET['folder_name'])){
                $folder_path=$_GET['folder_name'].' / ';
            } 
            $data['folder_path']=$folder_path;
            $data['modal_subview'] = $this->load->view('admin/docroom/_modal_create_folder', $data, FALSE);
            $this->load->view('admin/_layout_skote_modal', $data);
        }
    }

    public function create_file($folder_id="")
    {
        $user_id = $this->session->userdata('user_id');
        $access_token=$this->session->userdata('docroom_access_token');
        $result=array('error'=>"true");
        if ($_FILES) {
            $user_docroom_info = userDocroomInfo();

            $main_folder_id=$user_docroom_info->docroom_folder_id;
            $account_id=$user_docroom_info->docroom_account_id;
            if(!empty($folder_id)){
                $docroom_folder_id=$folder_id;
            }else{
                $docroom_folder_id=$main_folder_id;
            }
            //process the files which has been submitted
            $files = $_FILES['file'];
            if ($files && count($files) > 0) {
                $temp_file = $files["tmp_name"];
                $file_name = $files["name"];

                $upload_file=$this->docroom_connect->upload_file(array('access_token'=>$access_token,'account_id'=>$account_id,'folder_id'=>$docroom_folder_id),$temp_file,$file_name);
            }
            $result=array('uploaded_file'=> $upload_file);
        }
        
        return $result;

    }

    public function download_file($file_id=0)
    {
        $user_id = $this->session->userdata('user_id');
        $access_token=$this->session->userdata('docroom_access_token');
        if($file_id>0){
            $user_docroom_info = userDocroomInfo();
            $account_id=$user_docroom_info->docroom_account_id;
            $download_file=$this->docroom_connect->download_file(array('access_token'=>$access_token,'account_id'=>$account_id),$file_id);
            if(!empty($download_file)){
                $download_file_status=$download_file->_status;
                if($download_file_status=='success'){
                    $download_url=$download_file->data->download_url;
                    redirect($download_url);
                }
            }
        }

        redirect($_SERVER["HTTP_REFERER"]);

    }

    public function reauthenticate_docroom($key1, $key2){
        $user_id = $this->session->userdata('user_id');
        $docroom_auth=$this->docroom_connect->authorize($key1,$key2);
        $enable_features=true;
        if(!empty($docroom_auth)){
            $status=$docroom_auth->_status;
            if($status=='success'){
                $access_token=$docroom_auth->data->access_token;
                $account_id=$docroom_auth->data->account_id;

                $this->session->set_userdata('docroom_access_token',$access_token);
                $data['docroom_access_token'] = $access_token;
                $data['docroom_api_key1'] = $key1;
                $data['docroom_api_key2'] = $key2;
                $this->settings_model->_table_name = 'tbl_users';
                $this->settings_model->_primary_key = 'user_id';
                $suc = $this->settings_model->save($data, $user_id);
            }else{
                $enable_features=false;
            }
        }
        return array('access_token'=>$access_token,'account_id'=>$account_id,'enable_features'=>$enable_features);
    }
}
