<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of client_model
 *
 * @author NaYeM
 */
class Client_Model extends MY_Model
{

    public $_table_name;
    public $_order_by;
    public $_primary_key;

    function get_primary_contatc($user, $field)
    {
        $companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            if ($this->db->field_exists('companies_id', 'tbl_account_details')) {
                $this->db->where('companies_id', $companies_id);
            }
        }
        $this->db->where('user_id', $user);
        $this->db->select($field);
        $query = $this->db->get('tbl_account_details');

        if ($query->num_rows() > 0) {
            $row = $query->row();

            return $row->$field;
        }
    }

    public function client_paid($client_id)
    {
        $query = get_sum('tbl_payments', array('paid_by' => $client_id), 'amount', true);
        return $query->amount;
    }

    public function get_client_contacts($client_id)
    {

        $this->db->select('tbl_account_details.*', FALSE);
        $this->db->select('tbl_users.*', FALSE);
        $this->db->from('tbl_account_details');
        $this->db->join('tbl_users', 'tbl_users.user_id = tbl_account_details.user_id', 'left');
        $companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            if ($this->db->field_exists('companies_id', 'tbl_account_details')) {
                $this->db->where('companies_id', $companies_id);
            }
        }
        $this->db->where('tbl_account_details.company', $client_id);
        $query_result = $this->db->get();

        $result = $query_result->result();
        return $result;
    }


}
