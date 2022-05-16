<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Listings extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('items_model');
        $this->load->model('invoice_model');
        $this->load->model('estimates_model');
        $this->load->model('items_model');
        $this->load->library('docroom_connect');

        $this->load->helper('ckeditor');
        $this->data['ckeditor'] = array(
            'id' => 'ck_editor',
            'path' => 'asset/js/ckeditor',
            'config' => array(
                'toolbar' => "Full",
                'width' => "99.8%",
                'height' => "400px"
            )
        );
    }

    public function index($id = NULL)
    {
        
        // var_dump($data['all_project_info']); die;
        $data['subview'] = $this->load->view('admin/listing/embed', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }
    public function get_token()
    {
         $url = DEAL_ROOM_URL . 'get-access-token';
        $curl_handle = curl_init();

        curl_setopt($curl_handle, CURLOPT_URL, $url);
       
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);
        
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array(
            'username' => $this->session->set_userdata('username'),
            'email' =>  $this->session->set_userdata('email'),
           
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            
        ));
        $success = curl_exec($curl_handle);
        if (!$success) {
            header('HTTP/1.0 400 Bad error');
            $error = $this->getErrorByStatusCode(curl_getinfo($curl_handle, CURLINFO_HTTP_CODE));
            if ($error == '') {
                // Uknown error
                $error = curl_error($curl_handle);
            }
            echo $error;
            die;
        }
        curl_close($curl_handle);
        var_dump($success);die;
    }
}
