<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Datatable extends Admin_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->model('proposal_model');
        $this->load->library('gst');

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

    public function quote_request()
    {
        // POST data
        $postData = $this->input->post();

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (name like '%".$searchValue."%' or email like '%".$searchValue."%' or phone like '%".$searchValue."%' or message like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('tbl_request_quote')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('tbl_request_quote')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_request_quote')->result();

        $data = array();

        foreach($records as $key => $record ){

            $data[] = array( 
                "name"=>$record->name,
                "email"=>$record->email,
                "phone"=>$record->phone,
                "subject"=>$record->subject,
                "message"=>$record->message,
                "action"=>ajax_anchor(base_url("admin/frontend/delete/tbl_request_quote/".$record->id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_pricing_" . $key))
            ); 
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        echo json_encode($response);
    }

    public function coupon()
    {
        // POST data
        $postData = $this->input->post();

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (name like '%".$searchValue."%' or code like '%".$searchValue."%' or amount like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('tbl_frontend_coupon')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('tbl_frontend_coupon')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_frontend_coupon')->result();

        $data = array();

        foreach($records as $key => $record ){

            if (empty($record->pricing_id) || $record->pricing_id == 0) {
                $pricing_id = lang('all') . ' ' . lang('package');
            } else {
                $pricing_id = plan_name($record->pricing_id);
            }

            $published_checked = '';
            if ($record->status == 1) { $published_checked = 'checked'; }
            $published = '<div class="form-check form-switch mx-3 ajax_change_status">
                            <input data-id="'.$record->id.'" class="form-check-input" n name="status" value="1" '.$published_checked.' data-on="'.lang('yes').'" data-off="'.lang('no').'" type="checkbox" >
                            </div>';

            $action = '<a href="'.base_url().'admin/frontend/coupon_add/'.$record->id.'" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" title="" data-bs-placement="top" data-bs-original-title="Edit">
                                                 <i class="fa fa-pencil-square-o"></i></a>';
                                
            $action .= ajax_anchor(base_url("admin/frontend/delete/tbl_frontend_coupon/".$record->id), "<i class='fa fa-trash-o'></i>", array("class" => "btn btn-outline-danger btn-sm", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child",  "data-fade-out-on-success" => "#table_coupon_" . $key));

            $data[] = array( 
                "pricing_id"=>$pricing_id,
                "name"=>$record->name,
                "code"=>$record->code,
                "amount"=>$record->amount . ' ' . ($record->type == 1 ? '%' : lang('flat')),
                "end_date"=>strftime(config_item('date_format'), strtotime($record->end_date)),
                "published"=>$published,
                "action"=>$action
            ); 
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        echo json_encode($response);
    }

    public function subscriptions()
    {
        // POST data
        $postData = $this->input->post();

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (tbl_frontend_pricing.name like '%".$searchValue."%' or tbl_subscriptions.domain like '%".$searchValue."%' or tbl_subscriptions.frequency like '%".$searchValue."%' or tbl_subscriptions.status like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('tbl_subscriptions')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('tbl_subscriptions');
        $this->db->join('tbl_frontend_pricing', 'tbl_frontend_pricing.id = tbl_subscriptions.pricing_id', 'left');
        $this->db->join('tbl_users', 'tbl_users.user_id = tbl_subscriptions.created_by', 'left');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('tbl_subscriptions.*, tbl_frontend_pricing.name as plan_name, tbl_users.email as creator_email, tbl_users.username as creator_username');
        $this->db->from('tbl_subscriptions');
        $this->db->join('tbl_frontend_pricing', 'tbl_frontend_pricing.id = tbl_subscriptions.pricing_id', 'left');
        $this->db->join('tbl_users', 'tbl_users.user_id = tbl_subscriptions.created_by', 'left');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();

        $data = array();

        foreach($records as $key => $record ){

            if (empty($record->currency)) {
                $currency_code = config_item('default_currency');
            } else {
                $currency_code = $record->currency;
            }
            $currency = get_cache_data(array('code' => $currency_code), 'tbl_currencies');
            
            if ($record->status == 'pending') {
                $label = 'primary';
            } else if ($record->status == 'running') {
                $label = 'success';
            } else if ($record->status == 'expired') {
                $label = 'warning';
            } else {
                $label = 'danger';
            }
            $created_by  = "Client";
            if($record->created_by){
                // $user= get_result('tbl_users',['user_id'=>$record->created_by],'row');
                $created_by= '<a href="javascript://" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$record->creator_email.'" >'.$record->creator_username.'</a>';
            }

            $action = '';
            if ($record->status == 'pending') {
                $action .= '<a data-bs-toggle="tooltip" data-bs-placement="top" class=" btn btn-outline-success btn-sm " title="'.lang('send_activation_token').'" href="'.base_url('admin/frontend/send_activation_token/' . $record->subscriptions_id).'" ><span class="fa fa-envelope-o"></span></a>';
            }
            $action .= ajax_anchor(base_url("admin/frontend/delete_subscriptions/".$record->subscriptions_id), "<i class=' fa fa-trash-o'></i>", array("class" => "btn btn-outline-danger btn-sm", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", 
                "data-fade-out-on-success" => "#table_subscription_" . $key)); 
            $action .= btn_view(base_url('admin/frontend/subscriptions_details/' . $record->subscriptions_id));

            $data[] = array( 
                "plan_name"=>'<a data-bs-toggle="modal" data-bs-target="#myModal" href="'.base_url('admin/global_controller/package_details/' . $record->pricing_id).'" class="text-center">'.$record->plan_name.'</a>',
                "domain"=>$record->domain,
                "trial_period"=>$record->trial_period . ' ' . lang('days'),
                "currency"=>$currency->name . '(' . $currency->symbol . ')',
                "frequency"=>lang($record->frequency),
                "status"=>'<span class="label label-'.$label.'"> '.lang($record->status).'</span>',
                "date"=>strftime(config_item('date_format'), strtotime($record->created_date)),
                "created_by"=>$created_by,
                "action"=>$action
            ); 
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        echo json_encode($response);
    }

    public function subscriber()
    {
        // POST data
        $postData = $this->input->post();

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (email like '%".$searchValue."%' or ip like '%".$searchValue."%' or user_agent like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('tbl_subscribers')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('tbl_subscribers')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_subscribers')->result();

        $data = array();

        foreach($records as $key => $record ){

            if ($record->status == '1') {
                $label = 'success';
                $status = lang('subscribed');
                $type = 0;
                $type_label = 'warning';
                $type_text = lang('un_subscribed');
                $fa_icon = 'times';
            } else {
                $label = 'warning';
                $status = lang('un_subscribed');
                $type = 1;
                $type_label = 'success';
                $type_text = lang('subscribed');
                $fa_icon = 'check';
            }

            $action = '<a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-outline-'.$type_label.' btn-sm" title="Click to '.$type_text.' " href="'.base_url('admin/frontend/subscriber_action/' . $type . '/' . $record->subscribers_id).'"><span class="fa fa-'.$fa_icon.'"></span></a>';
                                
            $action .= ajax_anchor(base_url("admin/frontend/delete/tbl_subscribers/".$record->subscribers_id), "<i class=' fa fa-trash-o'></i>", array("class" => "btn btn-sm btn-outline-danger", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_pricing_" . $key));

            $data[] = array( 
                "email"=>$record->email,
                "status"=>'<span class="label label-'.$label.'">'.$status.'</span>',
                "ip"=>$record->ip,
                "user_agent"=>$record->user_agent,
                "created_at"=>display_datetime($record->created_at),
                "action"=>$action
            ); 
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        echo json_encode($response);
    }

    public function companies()
    {
        // POST data
        $postData = $this->input->post();

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (name like '%".$searchValue."%' or email like '%".$searchValue."%' or phone like '%".$searchValue."%' or city like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('tbl_companies')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('tbl_companies')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_companies')->result();

        $data = array();

        foreach($records as $key => $record ){

            if (!empty($record->status) && $record->status == '1') { $active_check = 'checked'; } else { $active_check = ''; }
            $active = '<div class="form-check form-switch mb-3 change_companies_status">
                            <input class="form-check-input" data-id="'.$record->companies_id.'" data-bs-toggle="toggle" name="active" value="1" '.$active_check.' type="checkbox">
                        </div>';

            if ($record->banned == 1) {
                $active .= '<span class="badge badge-soft-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                      title="'.$record->ban_reason.'">'.lang('banned').'</span>';
            }

            if ($record->banned == 1):
                $action = '<a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-success btn-sm" title="Click to '.lang('unbanned').' " href="'.base_url().'admin/companies/set_banned/0/'.$record->companies_id.'"><span class="fa fa-check"></span></a>';
            else:
                $action = '<span data-bs-toggle="tooltip" data-bs-placement="top" title="Click to '.lang('banned').' ">
                            '.btn_banned_modal('admin/companies/change_banned/1/' . $record->companies_id).'
                        </span>';
            endif;
            $action .= btn_edit('admin/companies/index/' . $record->companies_id);
            $action .= ajax_anchor(base_url("admin/companies/delete_company/".$record->companies_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_" . $key));

            $data[] = array( 
                "name"=>$record->name,
                "email"=>$record->email,
                "phone"=>$record->phone,
                "city"=>$record->city,
                "active"=>$active,
                "action"=>$action
            ); 
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        echo json_encode($response);
    }

    public function tenant()
    {
        // POST data
        $postData = $this->input->post();

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (tbl_frontend_pricing.name like '%".$searchValue."%' or tbl_subscriptions.domain like '%".$searchValue."%' or tbl_subscriptions.frequency like '%".$searchValue."%' or tbl_subscriptions.status like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('tbl_subscriptions')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('tbl_subscriptions');
        $this->db->join('tbl_frontend_pricing', 'tbl_frontend_pricing.id = tbl_subscriptions.pricing_id', 'left');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('tbl_subscriptions.*, tbl_frontend_pricing.name as plan_name');
        $this->db->from('tbl_subscriptions');
        $this->db->join('tbl_frontend_pricing', 'tbl_frontend_pricing.id = tbl_subscriptions.pricing_id', 'left');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();

        $data = array();

        foreach($records as $key => $record ){

            if (empty($record->currency)) {
                $currency_code = config_item('default_currency');
            } else {
                $currency_code = $record->currency;
            }
            $currency = get_cache_data(array('code' => $currency_code), 'tbl_currencies');
            if ($record->status == 'pending') {
                $label = 'primary';
            } else if ($record->status == 'running') {
                $label = 'success';
            } else if ($record->status == 'expired') {
                $label = 'warning';
            } else {
                $label = 'danger';
            }

            $checkbox = '<div class="form-check font-size-16">
                        <input class="action-check form-check-input" type="checkbox" data-id="'.$record->subscriptions_id.'" style="position: absolute;" name="tenant_id[]" value="'.$record->subscriptions_id.'">
                        <label class="form-check-label"></label>
                    </div>';

            $plan = '<a data-bs-toggle="modal" data-bs-target="#myModal"
                               href="'.base_url('admin/global_controller/package_details/' . $record->pricing_id).'"
                               class="text-center">'.$record->plan_name.'</a>';

            if ($record->status == 'pending') {
                $action = '<a data-bs-toggle="tooltip" data-bs-placement="top" class=" btn btn-outline-success btn-sm " title="'.lang('send_activation_token').'" href="'.base_url('admin/frontend/send_activation_token/' . $record->subscriptions_id).'" ><span class="fa fa-envelope-o"></span></a>';
            } else { 
                $action = '<a target="_blank" href="'.base_url('admin/frontend/get_access_for_admin/' . $record->subscriptions_id).'">Get Access</a>';
            }

            $data[] = array( 
                "subscriptions_id"=>$checkbox,
                "plan_name"=>'<a data-bs-toggle="modal" data-bs-target="#myModal" href="'.base_url('admin/global_controller/package_details/' . $record->pricing_id).'" class="text-center">'.$record->plan_name.'</a>',
                "domain"=>$record->domain,
                "industry_type"=>$record->industry_type,
                "trial_period"=>$record->trial_period . ' ' . lang('days'),
                "currency"=>$currency->name . '(' . $currency->symbol . ')',
                "frequency"=>lang($record->frequency),
                "status"=>'<span class="label label-'.$label.'"> '.lang($record->status).'</span>',
                "date"=>strftime(config_item('date_format'), strtotime($record->created_date)),
                "action"=>$action
            ); 
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        echo json_encode($response);
    }

    public function proposals()
    {
        // POST data
        $postData = $this->input->post();

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (tbl_frontend_pricing.name like '%".$searchValue."%' or tbl_subscriptions.domain like '%".$searchValue."%' or tbl_subscriptions.frequency like '%".$searchValue."%' or tbl_subscriptions.status like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('tbl_subscriptions')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('tbl_subscriptions');
        $this->db->join('tbl_frontend_pricing', 'tbl_frontend_pricing.id = tbl_subscriptions.pricing_id', 'left');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('tbl_subscriptions.*, tbl_frontend_pricing.name as plan_name');
        $this->db->from('tbl_subscriptions');
        $this->db->join('tbl_frontend_pricing', 'tbl_frontend_pricing.id = tbl_subscriptions.pricing_id', 'left');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();

        $data = array();

        $data['all_proposals_info'] = $this->proposal_model->get_proposals($filterBy);

        foreach($records as $key => $record ){

            $can_edit = $this->proposal_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $v_proposals->proposals_id));
            $can_delete = $this->proposal_model->can_action('tbl_proposals', 'delete', array('proposals_id' => $v_proposals->proposals_id));

            if ($v_proposals->status == 'pending') {
                $label = "info";
            } elseif ($v_proposals->status == 'accepted') {
                $label = "success";
            } else {
                $label = "danger";
            }

            $checkbox = '<div class="form-check font-size-16">
                        <input class="action-check form-check-input" type="checkbox" data-id="'.$record->subscriptions_id.'" style="position: absolute;" name="tenant_id[]" value="'.$record->subscriptions_id.'">
                        <label class="form-check-label"></label>
                    </div>';

            $plan = '<a data-bs-toggle="modal" data-bs-target="#myModal"
                               href="'.base_url('admin/global_controller/package_details/' . $record->pricing_id).'"
                               class="text-center">'.$record->plan_name.'</a>';

            if ($record->status == 'pending') {
                $action = '<a data-bs-toggle="tooltip" data-bs-placement="top" class=" btn btn-outline-success btn-sm " title="'.lang('send_activation_token').'" href="'.base_url('admin/frontend/send_activation_token/' . $record->subscriptions_id).'" ><span class="fa fa-envelope-o"></span></a>';
            } else { 
                $action = '<a target="_blank" href="'.base_url('admin/frontend/get_access_for_admin/' . $record->subscriptions_id).'">Get Access</a>';
            }

            $data[] = array( 
                "subscriptions_id"=>$checkbox,
                "plan_name"=>'<a data-bs-toggle="modal" data-bs-target="#myModal" href="'.base_url('admin/global_controller/package_details/' . $record->pricing_id).'" class="text-center">'.$record->plan_name.'</a>',
                "domain"=>$record->domain,
                "industry_type"=>$record->industry_type,
                "trial_period"=>$record->trial_period . ' ' . lang('days'),
                "currency"=>$currency->name . '(' . $currency->symbol . ')',
                "frequency"=>lang($record->frequency),
                "status"=>'<span class="label label-'.$label.'"> '.lang($record->status).'</span>',
                "date"=>strftime(config_item('date_format'), strtotime($record->created_date)),
                "action"=>$action
            ); 
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        echo json_encode($response);
    }
}
