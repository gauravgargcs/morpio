<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Setup extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();

        $this->load->model('admin_model');
        $this->load->model('settings_model');
        $config_data = result_by_company('tbl_config');
        foreach ($config_data as $v_config_info) {
            $this->config->set_item($v_config_info->config_key, $v_config_info->value);
        }
        $system_lang = $this->admin_model->get_lang();
        $this->config->set_item('language', $system_lang);
        $files = $this->admin_model->all_files();
        if (!empty($system_lang)) {
            foreach ($files as $file => $altpath) {
                $shortfile = str_replace("_lang.php", "", $file);
                $this->lang->load($shortfile, $system_lang);
            }
        } else {
            foreach ($files as $file => $altpath) {
                $shortfile = str_replace("_lang.php", "", $file);
                $this->lang->load($shortfile, 'english');
            }
        }
        $is_active = get_active_subs();
        if (!empty($is_active)) {
            redirect('login');
        }
    }

    public function index()
    {
        error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
        ini_set('max_execution_time', 30000);
        $data['title'] = lang('welcome_to') . ' ' . config_item('company_name');
        $data['step'] = 1;
        $email = $this->input->post('email', true);
        if (!empty($email)) {
//            $data=$this->settings_model->array_from_post(array('activation_token', 'username','email', 'password'));
            $activation_token = $this->input->post('activation_token', true);
            $username = $this->input->post('username', true);
            $email_error = $this->check_subscription_email($email, true);
            $activation_token_error = $this->check_existing_activation_token($activation_token, $email, true);
            if (!empty($email_error['error'])) {
                $data['step'] = 1;
                $data['email_error'] = $email_error['error'];
                $data['error'] = true;
            }
            if (!empty($activation_token_error['error'])) {
                $data['step'] = 1;
                $data['activation_token_error'] = $activation_token_error['error'];
                $data['error'] = true;
            }
            if (empty($data['error']) && isset($_POST['step']) && $_POST['step'] == 1) {
                $result = $this->validateDB($_POST);
                $data['subscription_info'] = $result['subscription'];
                $data['step'] = 2;    
                $data['timezones'] = $this->settings_model->timezones();

            } elseif (isset($_POST['step']) && $_POST['step'] == 2) {
                $data['step'] = 3;
            } elseif (isset($_POST['step']) && $_POST['step'] == 3) {
                $validate_result = $this->validate_email($_POST);
                if (!empty($validate_result['error'])) {
                    $data['step'] = 3;
                } else {
                    $data['step'] = 4;
                }
            } elseif (isset($_POST['step']) && $_POST['step'] == 4) {
                $subscription = get_row('tbl_subscriptions', array('subscriptions_id' => $_POST['subscriptions_id']));
                $result = $this->migrateDB($subscription);
                if (!empty($result)) {
                    $this->update_config($subscription);
                    $this->update_user_details($subscription);
                    $this->update_subscription($subscription);
                    $this->install_module_sql($subscription);
                }
                $data['step'] = 5;
            } elseif (isset($_POST['step']) && $_POST['step'] == 5) {
                redirect('login');
            }
        }
        $this->load->view('admin/settings/setup', $data);
    }
    private function update_subscription($subscription)
    {
        $this->db = new_database($subscription, true);
        $s_data['status'] = 'running';
        $s_data['is_trial'] = 'Yes';
        $this->settings_model->_table_name = '  tbl_subscriptions';
        $this->settings_model->_primary_key = 'subscriptions_id';
        $this->settings_model->save($s_data, $subscription->subscriptions_id);
        $sh_data['status'] = 'running';
        $sh_info = get_row('tbl_subscriptions_history', array('subscriptions_id' => $subscription->subscriptions_id, 'status' => 'pending'));
        if (!empty($sh_info)) {
            $this->settings_model->_table_name = 'tbl_subscriptions_history';
            $this->settings_model->_primary_key = 'id';
            $this->settings_model->save($sh_data, $sh_info->id);
        } else {
            $plan_info = get_row('tbl_frontend_pricing', array('id' => $subscription->pricing_id));
            $amount_info = get_row('tbl_currencywise_price', array('frontend_pricing_id' => $subscription->pricing_id, 'currency' => $subscription->currency));
            if (!empty($amount_info)) {
                if ($subscription->frequency == 'yearly') {
                    $amount = $amount_info->yearly;
                } else {
                    $amount = $amount_info->monthly;
                }
            } else {
                $amount = '';
            }
            $sh_data['subscriptions_id'] = $subscription->subscriptions_id;
            $sh_data['currency'] = $subscription->currency;
            $sh_data['frequency'] = $subscription->frequency;
            $sh_data['amount'] = $amount;
            $sh_data['ip'] = $this->input->ip_address();
            $sh_data['name'] = $plan_info->name;
            $sh_data['multi_branch'] = $plan_info->multi_branch;
            $sh_data['employee_no'] = $plan_info->employee_no;
            $sh_data['client_no'] = $plan_info->client_no;
            $sh_data['project_no'] = $plan_info->project_no;
            $sh_data['invoice_no'] = $plan_info->invoice_no;
            $sh_data['leads'] = $plan_info->leads;
            $sh_data['accounting'] = $plan_info->accounting;
            $sh_data['bank_account'] = $plan_info->bank_account;
            $sh_data['online_payment'] = $plan_info->online_payment;
            $sh_data['calendar'] = $plan_info->calendar;
            $sh_data['mailbox'] = $plan_info->mailbox;
            $sh_data['live_chat'] = $plan_info->live_chat;
            $sh_data['tickets'] = $plan_info->tickets;
            $sh_data['tasks'] = $plan_info->tasks;
            $sh_data['filemanager'] = $plan_info->filemanager;
            $sh_data['stock_manager'] = $plan_info->stock_manager;
            $sh_data['recruitment'] = $plan_info->recruitment;
            $sh_data['attendance'] = $plan_info->attendance;
            $sh_data['payroll'] = $plan_info->payroll;
            $sh_data['leave_management'] = $plan_info->leave_management;
            $sh_data['performance'] = $plan_info->performance;
            $sh_data['training'] = $plan_info->training;
            $sh_data['reports'] = $plan_info->reports;
            $sh_data['disk_space'] = $plan_info->disk_space;
            $sh_data['status'] = 'running';
            $this->settings_model->_table_name = 'tbl_subscriptions_history';
            $this->settings_model->_primary_key = 'id';
            $this->settings_model->save_data($sh_data);
        }
        return true;
    }

    private function migrateDB($subscription)
    {
        $database_name = get_row('tbl_subscriptions_database', array('id' => $subscription->db_id));
        $db_name = $database_name->database_name;
        $db_username = $this->db->username;
        $db_password = $this->db->password;
        $db_host = $this->db->hostname;
        $mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);
        if (mysqli_connect_errno())
            return false;

        $database = 'db_client_install_with_demo_data.sql';
        if($this->input->post('category')=='demo-template'){
        $database = 'db_client_install_with_demo_data.sql';

        }
        // var_dump($database);die();
        $query = file_get_contents('assets/sample/'.$database);
        $mysqli->multi_query($query);
        do {
        } while (mysqli_more_results($mysqli) && mysqli_next_result($mysqli));
        $mysqli->close();
        return true;
    }
    private function validateDB($post_data)
    {
        $subscription_info = get_row('tbl_subscriptions', array('email' => $post_data['email'], 'activation_tocken' => $post_data['activation_token']));
        if (!empty($subscription_info)) {
            if (config_item('saas_server') === 'cpanel') {
                $server_result = $this->createCPanelDatabase($subscription_info);
            } elseif (config_item('saas_server') === 'plesk') {
                $server_result = $this->createPleskDatabase($subscription_info);
            } elseif (config_item('saas_server') === 'local') {
                $server_result = $this->createLocalDatabase($subscription_info);
            }
            if (!empty($server_result['database_name'])) {
                $subscriptions_database = get_row('tbl_subscriptions_database', $server_result);
                if (empty($subscriptions_database)) {
                    $this->settings_model->_table_name = 'tbl_subscriptions_database';
                    $this->settings_model->_primary_key = 'id';
                    $db_id = $this->settings_model->save($server_result);
                } else {
                    $db_id = $subscriptions_database->id;
                }
                $s_data['db_id'] = $db_id;
                $this->settings_model->_table_name = 'tbl_subscriptions';
                $this->settings_model->_primary_key = 'subscriptions_id';
                $this->settings_model->save($s_data, $subscription_info->subscriptions_id);
                $result['subscription'] = $subscription_info;
                $result['result'] = 'success';
            } else {
                $result['error'] = $server_result['error'];
            }
            return $result;
        }
    }

    /**
     * Create cPanel database
     *
     * @param Instance $instance
     * @return void
     */
    private function createCPanelDatabase($subscription_info)
    {
        include_once(APPPATH . 'libraries/Xmlapi.php');
        $cpanel_password = decrypt(config_item('saas_cpanel_password'));
        $db_username = $this->db->username;
        $xmlapi = new xmlapi(config_item('saas_cpanel_host'));
        $xmlapi->set_port(config_item('saas_cpanel_port'));
        $xmlapi->password_auth(config_item('saas_cpanel_username'), $cpanel_password);
        $xmlapi->set_debug(1);
        $output = config_item('saas_cpanel_output');
        $xmlapi->set_output($output);
        $cpaneluser = config_item('saas_cpanel_username');
        if ($output == 'json') {
            $cpaneluser_short = config_item('saas_cpanel_username');
        } else {
            $cpaneluser_short = substr(config_item('saas_cpanel_username'), 0, 8);
        }
        $databasename = $subscription_info->domain . '_' . $subscription_info->subscriptions_id;
        $create_db = $xmlapi->api1_query($cpaneluser, "Mysql", "adddb", array($databasename));
        if ($output == 'json') {
            $create_db = json_decode($create_db);
        }
        $assign_permission = $xmlapi->api1_query($cpaneluser, 'Mysql', 'adduserdb', array('' . $databasename . '', '' . $db_username . '', 'all'));
        // $assign_permission = $xmlapi->api1_query($cpaneluser, "Mysql", "adduserdb", array($cpaneluser_short . "_" . $databasename, $db_username, 'all'));
         $databasename = $cpaneluser_short . "_" . $databasename;
        if ($output == 'json') {
            $assign_permission = json_decode($assign_permission);
        }
        if ($assign_permission->error) {
            return [
                'error' => $create_db->error
            ];
        }
        return [
            'database_name' => $databasename
        ];
    }

    /**
     * Create Plesk database
     *
     * @param Instance $instance
     * @return void
     */
    private function createPleskDatabase($subscription_info)
    {
        include_once(APPPATH . 'libraries/PleskApiClient.php');
        $plesk_password = decrypt(config_item('saas_plesk_password'));
        $host = config_item('saas_plesk_host');
        $login = config_item('saas_plesk_username');
        $cpaneluser_short = substr(config_item('saas_plesk_username'), 0, 8);
        $databasename = $subscription_info->domain . '_' . $subscription_info->subscriptions_id;
        $password = $plesk_password;
        $client = new PleskApiClient($host);
        $client->setCredentials($login, $password);
        $request = "<packet>
        <database>
           <add-db>
                <webspace-id>" . config_item('saas_plesk_webspace_id') . "</webspace-id>
                <name>" . $cpaneluser_short . '_' . $databasename . "</name>
                <type>mysql</type>
           </add-db>
        </database>
        </packet>";
        $response = $client->request($request);
        $response = simplexml_load_string($response);
        $database = $response->database;
        $database = (array)$database;;
        if ((isset($database['add-db']->result->status) && $database['add-db']->result->status != 'ok')) {
            return [
                'error' => $database['add-db']->result->errtext
            ];
        } else {
            $dbId = $database['add-db']->result->id;
            $requestToAssignDefaultUser = "<packet>
            <database>
               <set-default-user>
                  <db-id>$dbId</db-id>
                  <default-user-id>1</default-user-id>
               </set-default-user>
            </database>
            </packet>";
            $response = $client->request($requestToAssignDefaultUser);
            return [
                'database_name' => $cpaneluser_short . '_' . $databasename
            ];
        }
    }
    /**
     * Create local database
     *
     * @param Instance $instance
     * @return void
     */
    private function createLocalDatabase($subscription_info)
    {
        $database_name = $subscription_info->domain . '_' . $subscription_info->subscriptions_id;
        $this->db->query('CREATE DATABASE IF NOT EXISTS ' . $database_name /*!40100 CHARACTER SET utf8 COLLATE 'utf8_general_ci' */);
        return [
            'database_name' => $database_name
        ];
    }
    public function update_user_details($subscription)
    {
        $plan_info = plan_info($subscription->pricing_id);
        $this->db = new_database($subscription);
        $dep['deptname'] = $_POST['department'];
        $dep['companies_id'] = null;
        $existing_department = get_row('tbl_departments', array('deptname' => $_POST['department']));
        $this->settings_model->_table_name = 'tbl_departments';
        $this->settings_model->_primary_key = 'departments_id';
        if (empty($existing_department)) {
            $departments_id = $this->settings_model->save($dep);
        } else {
            $departments_id = $this->settings_model->save($dep, $existing_department->departments_id);
        }
        if ($departments_id != 0) {
            $des['departments_id'] = $departments_id;
            $des['designations'] = $_POST['designation'];
            $this->settings_model->_table_name = '  tbl_designations';
            $this->settings_model->_primary_key = 'designations_id';
            $designations_id = $this->settings_model->save($des);
        } // save user details start
        $login_data['email'] = $_POST['email'];
        $login_data['username'] = $_POST['username'];
        $login_data['password'] = $this->hash($_POST['password']);
        $login_data['role_id'] = 1;
        $login_data['activated'] = 1;
        $login_data['companies_id'] = null;
        // if (!empty($plan_info) && $plan_info->multi_branch == 'Yes') {
            $login_data['super_admin'] = 'Yes';
        // } else {
        //     $login_data['super_admin'] = null;
        // }
        $login_data['created'] = date('Y-m-d H:i:s');
        $login_data['media_path_slug'] = slug_it($_POST['username']);
        $existing_username = get_row('tbl_users', array('username' => $_POST['username']));
        if (empty($existing_username)) {
            $this->settings_model->_table_name = ' tbl_users';
            $this->settings_model->_primary_key = 'user_id';
            $user_id = $this->settings_model->save($login_data);
            if ($user_id != 0) {
                $acc_data['user_id'] = $user_id;
                $acc_data['fullname'] = $_POST['fullname'];
                $acc_data['city'] = $_POST['company_city'];
                $acc_data['country'] = $_POST['company_country'];
                $acc_data['address'] = $_POST['company_address'];
                $acc_data['designations_id'] = $designations_id;
                $this->settings_model->_table_name = '  tbl_account_details';
                $this->settings_model->_primary_key = 'account_details_id';
                $this->settings_model->save($acc_data);
            }
        }
        return true;
    }

    public function update_config($subscription)
    {
        $this->new_db = new_database($subscription);
        $companies_id = null;
        $company_data['company_name'] = $_POST['company_name'];
        $company_data['company_email'] = $_POST['email'];
        $company_data['website_name'] = $_POST['company_name'];
        $company_data['contact_person'] = $_POST['contact_person'];
        $company_data['company_address'] = $_POST['company_address'];
        $company_data['company_country'] = $_POST['company_country'];
        $company_data['company_city'] = $_POST['company_city'];
        $company_data['company_zip_code'] = $_POST['company_zip_code'];
        $company_data['company_phone'] = $_POST['company_phone'];
        $company_data['timezone'] = $_POST['timezone'];
        $company_data['use_postmark'] = $_POST['use_postmark'];
        $company_data['postmark_api_key'] = $_POST['postmark_api_key'];
        $company_data['postmark_from_address'] = $_POST['postmark_from_address'];
        $company_data['protocol'] = $_POST['protocol'];
        $company_data['smtp_host'] = $_POST['smtp_host'];
        $company_data['smtp_user'] = $_POST['smtp_user'];
        $company_data['smtp_pass'] = encrypt($_POST['smtp_pass']);
        $company_data['smtp_port'] = $_POST['smtp_port'];
        $company_data['smtp_encryption'] = $_POST['smtp_encryption'];
        foreach ($company_data as $key => $value) {
            $data = array('value' => $value);
            $where = array('companies_id' => $companies_id, 'config_key' => $key);
            $this->new_db->where($where)->update('tbl_config', $data);
            $exists = $this->new_db->where($where)->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->new_db->insert('tbl_config', array("companies_id" => $companies_id, "config_key" => $key, "value" => $value));
            }
        }
        return true;
    }
    
    public function install_module_sql($subscription)
    {
        $this->new_db = new_database($subscription);
        // mailbox sql start
        $this->new_db->query('ALTER TABLE `tbl_inbox` ADD `from_user_id` INT(11) NULL DEFAULT NULL AFTER `inbox_id`;');
        // $this->new_db->query('ALTER TABLE tbl_users ADD `smtp_unread_email` tinyint(1) NOT NULL DEFAULT 0, ADD  `smtp_delete_mail_after_import` tinyint(1) NOT NULL DEFAULT 0;');
        $this->new_db->query('ALTER TABLE tbl_inbox ADD `mail_uid` int(11) DEFAULT NULL,ADD `upload_file` text DEFAULT NULL;');
        $this->new_db->query('ALTER TABLE tbl_sent ADD `mail_uid` int(11) DEFAULT NULL,ADD `upload_file` text DEFAULT NULL;');
        $this->new_db->query('ALTER TABLE `tbl_users` CHANGE `active_email` `active_email` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `smtp_email_type` `smtp_email_type` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `smtp_encription` `smtp_encription` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `smtp_action` `smtp_action` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `smtp_host_name` `smtp_host_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `smtp_username` `smtp_username` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;');
        $this->new_db->query('ALTER TABLE `tbl_inbox` CHANGE `to` `to` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `from` `from` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;');
        $this->new_db->query('ALTER TABLE `tbl_inbox` ADD `cc` VARCHAR(100) NULL DEFAULT NULL AFTER `to`;');
        
        $this->new_db->query("ALTER TABLE `tbl_inbox` ADD `smtp_host_name` varchar(100) NOT NULL DEFAULT 'system' ");
        $this->new_db->query("ALTER TABLE `tbl_inbox` ADD `mail_folder` varchar(100) NOT NULL DEFAULT '' ");
        
        $this->new_db->query("UPDATE tbl_inbox SET smtp_host_name = 'system', mail_folder = 'inbox'  WHERE  smtp_host_name != '' AND mail_uid IS NULL");
        
        $this->new_db->query("ALTER TABLE `tbl_inbox` ADD UNIQUE `key_for_uid` USING HASH(`mail_uid`, `smtp_host_name`, `mail_folder`)");
        
        $this->new_db->query("ALTER TABLE `tbl_inbox` ADD  `mail_status` VARCHAR(100) NOT NULL DEFAULT ''");
        // $this->new_db->query("ALTER TABLE `tbl_users` ADD  `smtp_syn_time` INTEGER ");
        // $this->new_db->query("ALTER TABLE `tbl_users` ADD  `mail_host` VARCHAR(100)");
        // $this->new_db->query("ALTER TABLE `tbl_users` ADD  `smtp_menu` VARCHAR(512) DEFAULT ''");
        // mailbox sql end
        
        // quickbooks and xero sql start
  //       $this->new_db->query(
  //           "INSERT INTO `tbl_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `sort`, `time`, `status`)
  // VALUES (NULL, 'quickbooks', 'admin/quickbooks', 'fa fa-refresh', '0', '3', CURRENT_TIMESTAMP, '1');"
  //       );
  //       $this->new_db->query(
  //           "INSERT INTO `tbl_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `sort`, `time`, `status`)
  // VALUES (NULL, 'xero', 'admin/xero', 'fa fa-refresh', '0', '3', CURRENT_TIMESTAMP, '1');"
  //       );
        
        
        // $this->new_db->query("ALTER TABLE `tbl_client` ADD  `host` VARCHAR(100) DEFAULT  ''");
        // $this->new_db->query("UPDATE `tbl_client` SET `host` = 'system' WHERE host = ''");
        // $this->new_db->query("ALTER TABLE `tbl_client` ADD  `remote_contact_id` VARCHAR(224)  DEFAULT  NULL");
        
        // $this->new_db->query("ALTER TABLE `tbl_suppliers` ADD  `host` VARCHAR(100) DEFAULT  ''");
        $this->new_db->query("UPDATE `tbl_suppliers` SET `host` = 'system' WHERE host = ''");
        // $this->new_db->query("ALTER TABLE `tbl_suppliers` ADD  `remote_contact_id`  VARCHAR(224)  DEFAULT  NULL");
        
        
        $this->new_db->query("ALTER TABLE `tbl_invoices` ADD  `host` VARCHAR(100) DEFAULT  ''");
        $this->new_db->query("UPDATE `tbl_invoices` SET `host` = 'system' WHERE host = ''");
        $this->new_db->query("ALTER TABLE `tbl_invoices` ADD  `remote_invoice_id` VARCHAR(224)  DEFAULT  NULL");
        $this->new_db->query("ALTER TABLE `tbl_invoices` ADD  `remote_client_id` VARCHAR(224)  DEFAULT  NULL");
//        $this->new_db->query("ALTER TABLE `tbl_invoices` DROP IF EXISTS `reference_no`");
        $this->new_db->query("ALTER TABLE `tbl_invoices` ADD UNIQUE  `remote_invoice_key` USING HASH(`host`, `remote_invoice_id`)");
        
        $this->new_db->query("ALTER TABLE `tbl_saved_items` ADD  `host` VARCHAR(100) DEFAULT ''");
        $this->new_db->query("UPDATE `tbl_saved_items` SET `host` = 'system' WHERE host = ''");
        $this->new_db->query("ALTER TABLE `tbl_saved_items` ADD  `remote_item_id` VARCHAR(224)  DEFAULT  NULL");
        
        
        $this->new_db->query("ALTER TABLE `tbl_items` ADD  `host` VARCHAR(100) DEFAULT ''");
        $this->new_db->query("UPDATE `tbl_items` SET `host` = 'system' WHERE host = ''");
        $this->new_db->query("ALTER TABLE `tbl_items` ADD  `remote_item_id` VARCHAR(224)  DEFAULT  NULL");
        $this->new_db->query("ALTER TABLE `tbl_items` ADD  `remote_invoice_id` VARCHAR(224) DEFAULT NULL");
        
        $this->new_db->query("ALTER TABLE `tbl_payments` ADD  `host` VARCHAR(100) DEFAULT ''");
        $this->new_db->query("UPDATE `tbl_payments` SET `host` = 'system' WHERE host = ''");
        $this->new_db->query("ALTER TABLE `tbl_payments` ADD  `remote_payment_id` VARCHAR(224)  DEFAULT  NULL");
        $this->new_db->query("ALTER TABLE `tbl_payments` ADD  `remote_invoice_id` VARCHAR(224) DEFAULT NULL");
        $this->new_db->query("ALTER TABLE `tbl_payments` ADD  `remote_contact_id` VARCHAR(224) DEFAULT NULL");
        $this->new_db->query("ALTER TABLE `tbl_payments` ADD  `remote_account_id` VARCHAR(224) DEFAULT NULL");
        $this->new_db->query("ALTER TABLE `tbl_payments` ADD UNIQUE  `remote_payment_key`(`host`, `remote_payment_id`)");
        
        
        $this->new_db->query("ALTER TABLE `tbl_purchases` ADD  `host` VARCHAR(100) DEFAULT  ''");
        $this->new_db->query("UPDATE `tbl_purchases` SET `host` = 'system' WHERE host = ''");
        $this->new_db->query("ALTER TABLE `tbl_purchases` ADD  `remote_invoice_id` VARCHAR(224)  DEFAULT  NULL");
        $this->new_db->query("ALTER TABLE `tbl_purchases` ADD  `remote_supplier_id` VARCHAR(224)  DEFAULT  NULL");
        //   $this->new_db->query("ALTER TABLE `tbl_purchases` DROP INDEX IF EXISTS `reference_no`");
        $this->new_db->query("ALTER TABLE `tbl_purchases` ADD UNIQUE  `remote_purchase_key` USING HASH(`host`, `remote_invoice_id`)");
        
        $this->new_db->query("ALTER TABLE `tbl_purchase_items` ADD  `host` VARCHAR(100) DEFAULT ''");
        $this->new_db->query("UPDATE `tbl_purchase_items` SET `host` = 'system' WHERE host = ''");
        $this->new_db->query("ALTER TABLE `tbl_purchase_items` ADD  `remote_item_id` VARCHAR(224)  DEFAULT  NULL");
        $this->new_db->query("ALTER TABLE `tbl_purchase_items` ADD  `remote_invoice_id` VARCHAR(224) DEFAULT NULL");
        
        
        $this->new_db->query("ALTER TABLE `tbl_purchase_payments` ADD  `host` VARCHAR(100) DEFAULT ''");
        $this->new_db->query("UPDATE `tbl_purchase_payments` SET `host` = 'system' WHERE host = ''");
        $this->new_db->query("ALTER TABLE `tbl_purchase_payments` ADD  `remote_payment_id` VARCHAR(224)  DEFAULT  NULL");
        $this->new_db->query("ALTER TABLE `tbl_purchase_payments` ADD  `remote_invoice_id` VARCHAR(224) DEFAULT NULL");
        $this->new_db->query("ALTER TABLE `tbl_purchase_payments` ADD  `remote_contact_id` VARCHAR(224) DEFAULT NULL");
        $this->new_db->query("ALTER TABLE `tbl_purchase_payments` ADD  `remote_account_id` VARCHAR(224) DEFAULT NULL");
        $this->new_db->query("ALTER TABLE `tbl_purchase_payments` ADD UNIQUE  `remote_payment_key`(`host`, `remote_payment_id`)");
        
        
        // $this->new_db->query("ALTER TABLE `tbl_project` ADD  `host` VARCHAR(100) DEFAULT  ''");
        $this->new_db->query("UPDATE `tbl_project` SET `host` = 'system' WHERE host = ''");
        // $this->new_db->query("ALTER TABLE `tbl_project` ADD  `remote_project_id` VARCHAR(224)  DEFAULT  NULL");
        // $this->new_db->query("ALTER TABLE `tbl_project` ADD  `remote_contact_id` VARCHAR(224)  DEFAULT  NULL");
        $this->new_db->query("ALTER TABLE `tbl_project` CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        // $this->new_db->query("ALTER TABLE tbl_project CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        
        $this->new_db->query("ALTER TABLE `tbl_transactions` ADD  `host` VARCHAR(100) DEFAULT  ''");
        $this->new_db->query("UPDATE `tbl_transactions` SET `host` = 'system' WHERE host = ''");
        $this->new_db->query("ALTER TABLE `tbl_transactions` ADD  `remote_account_id` VARCHAR(224) DEFAULT NULL");
        $this->new_db->query("ALTER TABLE `tbl_transactions` ADD  `remote_contact_id` VARCHAR(224)  DEFAULT  NULL");
        $this->new_db->query("ALTER TABLE `tbl_transactions` ADD  `remote_invoice_id` VARCHAR(224)  DEFAULT  NULL");
        $this->new_db->query("ALTER TABLE `tbl_transactions` ADD UNIQUE `remote_transaction_key` USING HASH(`host`, `type`, `remote_invoice_id`)");
        // quickbooks and xero sql end
        
        // spreadsheet sql start
  //       $this->new_db->query(
  //           "INSERT INTO `tbl_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `sort`, `time`, `status`)
  // VALUES (NULL, 'spreadsheet', 'admin/spreadsheet', 'fa fa-file-excel-o', '0', '2', CURRENT_TIMESTAMP, '1');"
  //       );
        
  //       $this->new_db->query(
  //           "INSERT INTO `tbl_client_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `sort`, `time`, `status`)
  // VALUES (NULL, 'spreadsheet', 'client/spreadsheet', 'fa fa-file-excel-o', '0', '2', CURRENT_TIMESTAMP, '1');"
  //       );
        
        $this->new_db->query("CREATE TABLE IF NOT EXISTS `tbl_spreadsheet_hash_share` (
    `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
    `rel_type` varchar(20) NOT NULL,
    `rel_id` int NOT NULL,
    `id_share` int NOT NULL,
    `hash` text,
    `inserted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `role` int NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->new_db->query("CREATE TABLE IF NOT EXISTS `tbl_spreadsheet_related` (
    `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
    `parent_id` int NOT NULL,
    `rel_type` varchar(20) NOT NULL,
    `rel_id` int NOT NULL,
    `inserted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `hash` varchar(250) NOT NULL DEFAULT '',
    `role` int NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;");
        $this->new_db->query("CREATE TABLE IF NOT EXISTS `tbl_spreadsheet_my_folder` (
    `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
    `parent_id` text NOT NULL,
    `name` text NOT NULL,
    `type` varchar(20) DEFAULT NULL,
    `size` text,
    `staffid` int NOT NULL,
    `category` varchar(20) NOT NULL DEFAULT 'my_folder',
    `inserted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `data_form` longtext,
    `staffs_share` text,
    `departments_share` text,
    `clients_share` text,
    `client_groups_share` text,
    `rel_type` varchar(100) DEFAULT NULL,
    `rel_id` varchar(11) DEFAULT NULL,
    `group_share_staff` varchar(1) DEFAULT NULL,
    `group_share_client` varchar(1) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;");
        // spreadsheet sql end
        
        // zoom sql start
  //       $this->new_db->query(
  //           "INSERT INTO `tbl_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `sort`, `time`, `status`)
  // VALUES (NULL, 'zoom', 'admin/zoom', 'fa fa-file-video-o', '0', '3', CURRENT_TIMESTAMP, '1');"
  //       );
        
        $this->new_db->query(
            "CREATE TABLE IF NOT EXISTS `tbl_zoom_meeting` (
    `zoom_meeting_id` int NOT NULL AUTO_INCREMENT,
    `companies_id` int DEFAULT NULL,
    `user_id` text,
    `client_id` text,
    `leads_id` varchar(100) DEFAULT NULL,
    `host` int DEFAULT NULL,
    `meetingId` varchar(500) DEFAULT NULL,
    `start_url` text,
    `join_url` varchar(500) DEFAULT NULL,
    `topic` varchar(250) DEFAULT NULL,
    `duration` varchar(100) DEFAULT NULL,
    `meeting_time` datetime DEFAULT NULL,
    `meeting_start` varchar(40) DEFAULT NULL,
    `notes` text,
    `additional` varchar(500) DEFAULT NULL,
    `status` varchar(50) DEFAULT NULL,
    PRIMARY KEY (`zoom_meeting_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8;"
        );
        
        $this->new_db->query(
            "INSERT INTO `tbl_email_templates` (`email_templates_id`, `email_group`, `subject`, `template_body`) VALUES (NULL,'meeting_start', 'Your Meeting start now.we are waiting', ' <p>Hello {USER}!</p>\r\n\r\n\r\n<p>A Meeting start from {HOST}.you are invited.</p>\r\n\r\n\r\n<p>we are waiting for you:<br>\r\n</p><p> Click on link to join the meeting:<br>\r\n<big><strong><a href=\"{MEETING_URL}\">join the meeting...</a></strong></big><br>\r\nLink doesn\'t work? Copy the following link to your browser address bar:<br>\r\n<a href=\"{MEETING_URL}\">{MEETING_URL}</a></p>\r\n\r\n\r\n<p><br>\r\n<br>\r\nHave fun!<br>\r\nThe {SITE_NAME} Team</p>');"
        );
        // zoom sql end
        return true;
    }
    
    public function validate_email($post_data)
    {
        $config = array();
        // If postmark API is being used
        if ($post_data['use_postmark'] == 'TRUE') {
            $config = array(
                'api_key' => $post_data['postmark_api_key']
            );
            $this->load->library('postmark', $config);
            $this->postmark->from($post_data['postmark_from_address'], $post_data['company_name']);
            $this->postmark->to($post_data['email']);
            $this->postmark->subject('SMTP Setup Testing');
            $this->postmark->message_plain('This is test SMTP email. <br />If you received this message that means that your SMTP settings is Corrects.');
            $this->postmark->message_html('This is test SMTP email. <br />If you received this message that means that your SMTP settings is Corrects.');
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
            // $config['useragent'] = 'UniqueCoder LTD';
//            $config['mailpath'] = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
            $config['wordwrap'] = TRUE;
            $config['mailtype'] = "html";
            $config['charset'] = 'utf-8';
            $config['newline'] = "\r\n";
            $config['crlf'] = "\r\n";
            $config['protocol'] = $post_data['protocol'];
            $config['smtp_host'] = $post_data['smtp_host'];
            $config['smtp_port'] = $post_data['smtp_port'];
            $config['smtp_timeout'] = '30';
            $config['smtp_user'] = $post_data['smtp_user'];
            $config['smtp_pass'] = decrypt($post_data['smtp_pass']);
            $config['smtp_crypto'] = $post_data['smtp_encryption'];
            $this->load->library('email', $config);
            $this->email->from($post_data['email'], $post_data['company_name']);
            $this->email->to($post_data['email']);
            $this->email->subject('SMTP Setup Testing');
            $this->email->message('This is test SMTP email. <br />If you received this message that means that your SMTP settings is Corrects.');
            $send = $this->email->send();
            $result['success'] = 1;
            return $result;
        }
    }

    public function hash($string)
    {
        $encryption_key = 'I6PnEPbQNLslYMj7ChKxDJ2yenuHLkXn';
        return hash('sha512', $string . $encryption_key);
    }

    public function register()
    {
        $data['title'] = lang('welcome_to') . ' ' . config_item('company_name');
        $data['subview'] = $this->load->view('login/register', $data, TRUE);
        $this->load->view('login', $data);
    }

    public function check_existing_user_name($username = null, $front = null)
    {
        if (!empty($this->input->post('name', true))) {
            $username = $this->input->post('name', true);
        }
        if (!empty($username)) {
            $check_user_name = $this->admin_model->check_user_name($username);
            if (!empty($check_user_name)) {
                $result['error'] = lang("name_already_exist");
            } else {
                $result['success'] = 1;
            }
            if (empty($front)) {
                echo json_encode($result);
                exit();
            } else {
                return $result;
            }
        }
    }

    public function check_subscription_email($email = null, $front = null)
    {
        if (!empty($this->input->post('name', true))) {
            $email = $this->input->post('name', true);
        }
        if (!empty($email)) {
            $where = array('email' => $email);
            $check_subs_email = $this->admin_model->check_by($where, 'tbl_subscriptions');
            $check_email_address = $this->admin_model->check_by(array('email' => $email), 'tbl_users');
            if (!empty($check_subs_email) || !empty($check_email_address)) {
                if (!empty($check_email_address)) {
                    $result['error'] = lang("this_email_already_exist_someone_already_login");
                } elseif ($check_subs_email->status != 'pending') {
                    $result['error'] = lang('your_email_already_used', $check_subs_email->status);
                } else {
                    $result['success'] = 1;
                }
            } else {
                $result['error'] = lang('we_did_not_found_your_email');
            }
            if (empty($front)) {
                echo json_encode($result);
                exit();
            } else {
                return $result;
            }
        }
    }

    public function check_existing_activation_token_new($activation_token = null, $front = null)
    {
        if (!empty($this->input->post('name', true))) {
            $activation_token = $this->input->post('name', true);
        }
        if (!empty($activation_token)) {
            $check_email = $this->admin_model->check_by(array('activation_tocken' => $activation_token), 'tbl_subscriptions');
            if (!empty($check_email)) {
                $this->db->select('name, email');
                $this->db->where('activation_tocken', $activation_token);
                $data = $this->db->get("tbl_subscriptions")->row();

                $result['success'] = 1;
                $result['name'] = $data->name;
                $result['email'] = $data->email;
            } else {
                $result['error'] = lang('we_did_not_found_your_token');
            }
            if (empty($front)) {
                echo json_encode($result);
                exit();
            } else {
                return $result;
            }
        }
    }

    public function check_existing_activation_token($activation_token = null, $email = null, $front = null)
    {
        if (!empty($this->input->post('name', true))) {
            $activation_token = $this->input->post('name', true);
            $email = $this->input->post('name_2', true);
        }
        if (!empty($email) || !empty($activation_token)) {
            $check_email = $this->admin_model->check_by(array('email' => $email, 'activation_tocken' => $activation_token), 'tbl_subscriptions');
            if (!empty($check_email)) {
                $this->db->select('name, email');
                $this->db->where('activation_tocken', $activation_token);
                $data = $this->db->get("tbl_subscriptions")->row();
                $result['success'] = 1;
                $result['name'] = $data->name;
                $result['email'] = $data->email;
            } else {
                $result['error'] = lang('we_did_not_found_your_activation_token');
            }
            if (empty($front)) {
                echo json_encode($result);
                exit();
            } else {
                return $result;
            }
        }
    }

    public function domain_not_available()
    {
        $sub_domain = is_subdomain($_SERVER['HTTP_HOST']);
        if (!empty($sub_domain)) {
            $domain_available = is_domain_available($sub_domain);
            if (!empty($domain_available)) {
                redirect(config_item('default_controller'));
            } else {
                $data['title'] = lang('welcome_to') . ' ' . config_item('company_name');
                $this->load->view('admin/settings/domain_not_registered', $data);
            }
        }
    }
}



