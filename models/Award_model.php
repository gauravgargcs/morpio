<?php

/**
 * Description of award_model
 *
 * @author Ashraf
 */
class Award_Model extends MY_Model
{

    public $_table_name;
    public $_order_by;
    public $_primary_key;

    public function get_employee_award_by_id($id = NULL)
    {

        $this->db->select('tbl_employee_award.*', FALSE);
        $this->db->select('tbl_account_details.*', FALSE);
        $this->db->from('tbl_employee_award');
        $this->db->join('tbl_account_details', 'tbl_account_details.user_id = tbl_employee_award.user_id', 'left');
        $companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            if ($this->db->field_exists('companies_id', 'tbl_employee_award')) {
                $this->db->where('tbl_employee_award.companies_id', $companies_id);
            }
        }
        if (!empty($id)) {
            $this->db->where('tbl_employee_award.employee_award_id', $id);
            $query_result = $this->db->get();
            $result = $query_result->row();
        } else {
            $query_result = $this->db->get();
            $result = $query_result->result();
        }
        return $result;
    }

}
