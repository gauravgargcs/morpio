<?php

// if (!defined('BASEPATH'))
//     exit('No direct script access allowed');

//Rest controller
Class Rest extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
       // $this->load->model('admin_model');
    }


    public function random($charactersLength)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $charactersLength; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;   
    }
    public function hash($string)
    {
        return hash('sha512', $string . config_item('encryption_key'));
    }

    public function login_post()
    {   
       

        $this->form_validation->set_rules('username','Username','required|trim');
        $this->form_validation->set_rules('password','Password','required|trim');
        if($this->form_validation->run()) {
            $user =  $_POST['username'];  
            $pass =  $_POST['password'];
            $userdata = array(
                'username' => $user,
                'password' => $this->hash($pass)
            );
            $res = array();
            $this->load->model('rest_model');
            $checked = $this->rest_model->checkuser($userdata);

            if ($checked) {
                $randomnum = $this->random(20);

                $user_id = $checked['user_id'];
                $updated = $this->rest_model->update_user($user_id,$randomnum);
                if($updated) {
                    $new_data = $this->rest_model->fetch_data($user_id);
                    if($new_data) {
                        $res['status'] = 'success';
                        $res['message'] = 'You have been logged in successfully';
                        $res['data'] = $new_data;

                        unset($res['data']['password']);
                    }
                }
            }
            else {
                $res['status'] = 'error';
                $res['message'] = 'Incorrect username or password';
                $res['data'] = '';
            }
        }
        else {
            $res['status'] = 'error';
            $res['message'] = strip_tags(validation_errors());
            $res['data'] = '';
        }

        
        $this->response($res, REST_Controller::HTTP_OK);
    }
}
