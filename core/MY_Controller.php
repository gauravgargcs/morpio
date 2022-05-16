<?php

/**
 * Description of MY_Controller
 *
 * @author Nayeem
 */
class MY_Controller extends CI_Controller
{
    function __construct()
    {

        parent::__construct();

        if($this->input->get('lang')){

           $this->session->set_userdata('lang', $this->input->get('lang'));
            // $this->session->set_userdata("country",'US');

        }
        if(!$this->session->userdata('lang')){
           $this->session->set_userdata('lang',"english(AU)");

        }
        $sub_domain = is_subdomain($_SERVER['HTTP_HOST']);
        if (!empty($sub_domain)) {
            $domain_available = is_domain_available($sub_domain);
            if (empty($domain_available)) {
                redirect('domain-not-available');
            }
            $result = check_complete_setup($sub_domain);
            if (empty($result)) {
                $this->db = setup_database($sub_domain);
            }
        }
        $this->load->model('login_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->model('admin_model');
        $this->load->model('items_model');
        $this->load->model('invoice_model');
        $this->load->model('global_model');
        $this->load->helper('language');
    
        if (admin() && $this->input->get('skip_modules_load') && $this->input->get('skip_modules_load')) {
            $modules = [];
        } else {
            $modules = $this->module->get_active_module();
        }
        if (!empty($modules)) {
            foreach ($modules as $module) {
                require_once($module['init_file']);
            }
        }
        
        $companies_id = $this->session->userdata('companies_id');
        if (empty($companies_id)) {
            $companies_id = null;
        }
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
        $uri = null;
        for ($i = 1; $i <= $this->uri->total_segments(); $i++) {
            $uri .= $this->uri->segment($i) . '/';
        }
        $uriSegment = rtrim($uri, '/');
        $menu_uri['menu_active_id'] = $this->admin_model->select_menu_by_uri($uriSegment);
        // $menu_uri['menu_active_id'] == false || 
        $this->session->set_userdata($menu_uri);

        $timezone = config_item('timezone');
        if (empty($timezone)) {
            $timezone = 'Australia/Sydney';
        }
        $auto_loaded_vars = array(
            'unread_notifications' => count($this->db->where(array('to_user_id' => $this->session->userdata('user_id'), 'read' => 0))->get('tbl_notifications')->result()),
        );
        $this->load->vars($auto_loaded_vars);

        date_default_timezone_set($timezone);
        set_mysql_timezone($timezone);
        check_installation();
      
    }
}
