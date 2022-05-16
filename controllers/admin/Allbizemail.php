<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admistrator
 *
 * @author Jaraware Infosoft
 */
class Allbizemail extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index($action = NULL)
    {
        $data['title'] = config_item('company_name');
        $data['page'] = lang('dashboard');
        $data['role'] = $this->session->userdata('user_type');
        $data['user_id'] = $this->session->userdata('user_id');
        $data['profile_info'] = $this->db->where('user_id', $data['user_id'])->get('tbl_account_details')->row();
        $data['user_info'] = $this->db->where('user_id', $data['user_id'])->get('tbl_users')->row();
        $data['display'] = config_item('logo_or_icon');
        $data['user_role_id'] = $this->session->userdata('user_type');
        $data['subview'] = $this->load->view('admin/email_compaign', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data);
    }
}
