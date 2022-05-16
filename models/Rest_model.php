<?php 
    class Rest_model extends MY_Model
    {
        public function checkuser($data)
        {
            $this->db->select('*'); 
            $this->db->from('tbl_users');
            $this->db->where($data);
            $query = $this->db->get();
            $newquery = $query->num_rows();
            if ($newquery > 0) {
                return $query->row_array();
            } else {
                return false;
            }
        }

        public function register($data)
        {
           $query = $this->db->insert('tbl_users',$data);
           return $query;
        }

        public function update_user($id,$random)
        {
            $this->db->where('user_id',$id);
            $query1 = $this->db->update('tbl_users',array('authentication_key' => $random));
            return $query1;
        }
        public function fetch_data($id)
        {   
            $this->db->select('*');
            $this->db->where('user_id',$id);
            $this->db->from('tbl_users');
            $query2 = $this->db->get();
            return $query2->row_array();
        }
    }




?>