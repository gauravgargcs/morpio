<?php
/**
 * Controller
 *
 * @author   Jaraware Infosoft. <https://jaraware.com/>
 * @desc     About to set sso token and validate sso token fetched from cloud.  store or update userdata
 */
class Oneall_callback extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('login_model');
    }
    
    /**
    * function index
    * @author Jaraware Infosoft
    * @desc validate sso token and redirect to dashboard
    * @param request $request
    * @return void
    */
    function index() {
        //Your Site Settings
        $site_subdomain = OPENALL_SUBDOMAIN;
        $site_public_key = OPENALL_PUBKEY;
        $site_private_key = OPENALL_PRIVKEY;
        
        // Check if we have received a connection_token
        if ( ! empty ($_POST['connection_token']))
        {
           
            // Get connection_token
            $token = $_POST['connection_token'];

            // Retrieve the user's profile data
            $resource_uri = 'http://'.$site_subdomain.'.api.oneall.com/connections/'.$token .'.json';

            // Setup connection
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $resource_uri);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_USERPWD, $site_public_key . ":" . $site_private_key);
            curl_setopt($curl, CURLOPT_TIMEOUT, 15);
            curl_setopt($curl, CURLOPT_VERBOSE, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($curl, CURLOPT_FAILONERROR, 0);

            // Send request
            $result_json = curl_exec($curl);

            // Error
            if ($result_json === false)
            {
                echo 'Curl error: ' . curl_error($curl). '<br />';
                echo 'Curl info: ' . curl_getinfo($curl). '<br />';
                curl_close($curl);
            }
            // Success
            else
            {
                // Close connection
                curl_close($curl);

                // Decode
                $json = json_decode ($result_json);

                // Extract data
                $data = $json->response->result->data;// Operation successful
                if ($data->plugin->data->status == 'success')
                {
                    // The user_token uniquely identifies the user 
                    $user_token = $data->user->user_token;
                    // The identity contains the user's profile data
                    $identity = $data->user->identity;
                    $identity_token = $identity->identity_token;
                    $_SESSION['session_identity_token'] =  $identity_token;
                    $email = "";
                    if (isset($data->user->identity->emails[0]->value) && !empty($data->user->identity->emails[0]->value)){
                        $email = trim($data->user->identity->emails[0]->value);
                    }    
                    // At this point you must use the identity data to either login the user
                    // with an existing account or to create a new account.
                    // check if user exits based on user token
                    $user_id = get_user_id_for_user_token($user_token);
                    if($user_id) {
                        $admin = getUserData($user_id);
                        // create sso session
                        if($data->plugin->key == 'single_sign_on') {
                            $sso_token = read_sso_session($identity_token);
                        } else {
                            $sso_token = create_sso_session($identity_token);
                        }
                        $user = array(
                            'user_token' => $user_token,
                            'identity_token' => $identity_token,
                        );
                        // update user token & identity token
                        update_user($user, $user_id);
                        $this->setsessiondata($user_id, $sso_token);
                    } else {
                        // check if user exits based on user email
                        $user_id = get_user_id_for_email($email);

                        if(!$user_id){ 
                            /*
                          // user found
                            //Add user data
                            $user_data = $this->addUserDetail($email);
                            $user_id = $user_data['user_id'];
                            $user_data = $this->addCompanyDetail($user_data);
                            $user_data = $this->addAccountDetail($email, $user_data);
                            $this->updateClientMenuItems($user_data);*/
                            $type = 'error';
                        $message = lang("login_not_allowed_outside_user");
                        set_message($type, $message);
                             redirect('login'); 
                        }else{


                            $user = array('user_token' => $user_token, 'identity_token' => $identity_token);
                            update_user($user, $user_id);
                            if($data->plugin->key == 'single_sign_on') {
                                $sso_token = read_sso_session($identity_token);
                            } else {
                                $sso_token = create_sso_session($identity_token);
                            }
                            $this->setsessiondata($user_id, $sso_token);
                        }
                    }
                } else {
                    redirect('login'); die();
                }
            }
        }
    }
    
    /**
    * function addAccountDetail
    * @author Jaraware Infosoft
    * @desc Add account details in db
    * @param string $email
    * @param array $userdata
    * @return array $userdata
    */
    function addAccountDetail($email, $user_data) {
        $profile['user_id'] = $user_data['user_id'];
        $profile['fullname'] = null;
        $profile['company'] = $user_data['company'];
        $RTL = config_item('RTL');
        if (!empty($RTL)) {
            $direction = 'rtl';
        } else {
            $direction = 'ltr';
        }
        $profile['direction'] = $direction;
        $this->login_model->_table_name = 'tbl_account_details';
        $this->login_model->_primary_key = 'account_details_id';
        $this->login_model->save($profile);
        return $user_data;
    }
    
    /**
    * function addCompanyDetail
    * @author Jaraware Infosoft
    * @desc Store company details in db
    * @param array $userdata
    * @return array $userdata
    */
    function addCompanyDetail($user_data) {
        $client['primary_contact'] = 0;
        $client['email'] = $user_data['email'];
        $this->login_model->_table_name = 'tbl_client';
        $this->login_model->_primary_key = 'client_id';
        $user_data['company'] =  $this->login_model->save($client);
        return $user_data;
    }
    
    /**
    * function updateClientMenuItems
    * @author Jaraware Infosoft
    * @desc Store client side menu items
    * @param array $userdata
    * @return array $userdata
    */
    function updateClientMenuItems($user_data) {
        $user_data['activation_period'] = config_item('email_activation_expire') / 3600;
        $user_data['password'] = null;
        //$this->send_email('activate', $user_data['email'], $user_data);
        $client_default_menu = unserialize(config_item('client_default_menu'));
        if (!empty($client_default_menu)) {
            if (!empty($client_default_menu['client_default_menu'])) {
                $client_menu = $client_default_menu['client_default_menu'];
            } else {
                $client_menu = array('17', '6');
            }
        } else {
            $client_menu = array('17', '6');
        }
        foreach ($client_menu as $v_menu) {
            $client_role_data['menu_id'] = $v_menu;
            $client_role_data['user_id'] = $user_data['user_id'];
            log_message('error', json_encode($client_role_data));
            $this->login_model->_table_name = 'tbl_client_role';
            $this->login_model->_primary_key = 'client_role_id';
            $this->login_model->save($client_role_data);
        }
        $type = 'success';
        $message = lang('registration_success');
        //set_message($type, $message);
    }
    
    /**
    * function addUserDetail
    * @author Jaraware Infosoft
    * @desc Store client side menu items
    * @param string $email
    * @return array $userdata
    */
    function addUserDetail($email) {
        // user not found create new
        $user_data['email'] = $email;
        $user_data['username'] = $email;
        $user_data['activated'] = 1;
        $user_data['new_email_key'] = md5(rand() . microtime());
        $user_data['role_id'] = 2;
        $user_data['last_ip'] = $this->input->ip_address;
        $user_data['created'] = date('Y-m-d H:i:s');
        $this->login_model->_table_name = 'tbl_users';
        $this->login_model->_primary_key = 'user_id';
        $user_data['user_id'] = $this->login_model->save($user_data);
        return $user_data;
    }
    
    /**
    * function setsessiondata
    * @author Jaraware Infosoft
    * @desc Store session based on user role
    * @param string $user_id
    * @param string $sso_token
    * @return void
    */
    function setsessiondata($user_id, $sso_token) {
        // set session after login 
        $admin = getUserData($user_id);
        $user_info = getUserAccountData($user_id);
        if (!empty($user_info->direction)) {
            $direction = $user_info->direction;
        } else {
            $RTL = config_item('RTL');
            if (!empty($RTL)) {
                $direction = 'rtl';
            }
        }
        if (empty($direction)) {
            $direction = 'ltr';
        }
        $data = array(
            'user_name' => $admin->username,
            'email' => $admin->email,
            'name' => $user_info->fullname,
            'photo' => $user_info->avatar,
            'companies_id' => $admin->companies_id,
            'user_id' => $admin->user_id,
            'docroom_access_token' => $admin->docroom_access_token,
            'last_login' => $admin->last_login,
            'online_time' => time(),
            'loggedin' => TRUE,
            'user_type' => $admin->role_id,
            'user_flag' => ($admin->role_id == 2) ? 2 : 1,
            'direction' => $direction,
            'url' => ($admin->role_id == 2) ? 'client/dashboard' : 'admin/dashboard',            
            'user_token' => $admin->user_token,
            'identity_token' => $admin->identity_token,
            'sso_token' => $sso_token,
        );
        if($admin->role_id == 2) {
           $data['client_id'] = $user_info->company;
        } else {
            $data['designations_id'] = $user_info->designations_id;
            $data['super_admin'] = $admin->super_admin;
        }
        set_message('success', 'Logged in successfully');
        $this->session->set_userdata($data);
        if($admin->role_id == 2) {
            redirect('client/dashboard'); die();
        } else {
            redirect('admin/dashboard'); die();
        }
    }
}
?>