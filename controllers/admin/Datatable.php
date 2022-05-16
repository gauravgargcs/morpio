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
        $this->load->model('client_model');
        $this->load->model('tasks_model');
        $this->load->model('invoice_model');
        $this->load->model('transactions_model');
        $this->load->model('quotations_model');
        $this->load->model('job_circular_model');
        $this->load->model('training_model');
        $this->load->model('tickets_model');
        $this->load->model('user_model');
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
	
	if( $columnName == 'date'){
		$columnName = 'created_date';
	}
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
		$CI = &get_instance();
		$user_data__ID = $CI->session->userdata('user_id');
        // POST data
		
		$fliter___ = $_GET['filter'];
        $postData = $this->input->post();

        $response = array();
        
        $filter = $this->input->get('filter');
        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = ""; // $postData['order'][0]['column']; // Column index
        $columnName = ""; // $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = ""; // $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            // $searchQuery = " (c_name like '%".$searchValue."%') ";
        }
       
        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
		
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
		
		if($fliter___ && $fliter___ != 'all'){

			$this->db->where('status', $fliter___);
		}
		
        $records = $this->db->get('tbl_proposals')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
		if($fliter___ && $fliter___ != 'all'){
			$this->db->where('status', $fliter___);
		}
        if($searchValue != ''){
			//$this->db->where($searchQuery);
			$this->db->where('tbl_proposals.reference_no LIKE', '%'.$searchValue.'%');
			
		}
        
        $records = $this->db->get('tbl_proposals')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*, tbl_companies.name as c_name');
        $this->db->join('tbl_companies','tbl_companies.companies_id = tbl_proposals.companies_id');
		
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(tbl_proposals.permission, '$.".$user_data__ID."[0]') != '') ");
		}
		if($fliter___ && $fliter___ != 'all'){
			$this->db->where('tbl_proposals.status', $fliter___);
		}
		
        if($searchValue != ''){
			$this->db->where('tbl_proposals.reference_no LIKE', '%'.$searchValue.'%');
			
        }
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_proposals')->result();

        $data = array();

        foreach($records as $key => $record ){
            
            $created = can_action('140', 'created');
            $edited = can_action('140', 'edited');
            $deleted = can_action('140', 'deleted');

            $can_edit = $this->proposal_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $record->proposals_id));
            $can_delete = $this->proposal_model->can_action('tbl_proposals', 'delete', array('proposals_id' => $record->proposals_id));

            if ($record->status == 'pending') {
                $label = "info";
            } elseif ($record->status == 'accepted') {
                $label = "success";
            } else {
                $label = "danger";
            }

            $proposal = '<a class="text-info" href="'.base_url().'admin/proposals/index/proposals_details/'.$record->proposals_id.'">'.$record->reference_no.'</a>';
            if ($record->convert == 'Yes') {
                if ($record->convert_module == 'invoice') {
                    $c_url = base_url() . 'admin/invoice/manage_invoice/invoice_details/' . $record->convert_module_id;
                    $text = lang('invoiced');
                } else {
                    $text = lang('estimated');
                    $c_url = base_url() . 'admin/estimates/index/estimates_details/' . $record->convert_module_id;
                }
                if (!empty($c_url)) { 
                    $proposal .= '<p class="text-sm m0 p0"> <a class="text-success" href="'.$c_url.'"> '.$text.'</a> </p>';
                }
            }

            $due_date = '';
            if (strtotime($record->due_date) < time() AND $record->status == 'pending' || strtotime($record->due_date) < time() AND $record->status == ('draft')) {
                $due_date = '<span class="label label-danger ">'.lang('expired').'</span>';
            }

            if ($record->module == 'client') {
                $client_info = $this->proposal_model->check_by(array('client_id' => $record->module_id), 'tbl_client');
                if (!empty($client_info)) {
                    $client_name = $client_info->name;
                    $currency = $this->proposal_model->client_currency_sambol($record->module_id);
                } else {
                    $client_name = '-';
                    $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                }
            } else if ($record->module == 'leads') {
                $client_info = $this->proposal_model->check_by(array('leads_id' => $record->module_id), 'tbl_leads');
                if (!empty($client_info)) {
                    $client_name = $client_info->contact_name;
                } else {
                    $client_name = '-';
                }

                $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
            } else {
                $client_name = '-';
                $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
            }

            $label_d = '';
            $show_custom_fields = custom_form_table(11, $record->proposals_id);
            if (!empty($show_custom_fields)) {
                foreach ($show_custom_fields as $c_label => $v_fields) {
                    if (!empty($c_label)) {
                        $label_d = $v_fields;
                    }
                }
            }

            $action = '';
            if (!empty($edited) || !empty($deleted)) {
                if (!empty($can_edit) && !empty($edited)) {
                    $action .= '<a data-bs-toggle="modal" data-bs-target="#myModal" title="'.lang('clone') . ' ' . lang('proposal').'" href="'.base_url().'admin/proposals/clone_proposal/'.$record->proposals_id.'" class="btn btn-sm btn-outline-primary"><i class="fa fa-copy"></i></a>';
                    $action .= btn_edit('admin/proposals/index/edit_proposals/' . $record->proposals_id);
                }
                if (!empty($can_delete) && !empty($deleted)) {
                    $action .= ajax_anchor(base_url("admin/proposals/delete/delete_proposals/" . $record->proposals_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_proposal_" . $key));
                }
                if (!empty($can_edit) && !empty($edited)) {
                    $action .= '<button class="btn btn-outline-success dropdown-toggle btn-sm" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">'.lang('change_status').'<i class="mdi mdi-chevron-down"></i></button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="'.base_url().'admin/proposals/index/email_proposals/'.$record->proposals_id.'">'.lang('send_email').'</a>
                        <a class="dropdown-item" href="'.base_url().'admin/proposals/index/proposals_details/'.$record->proposals_id.'">'. lang('view_details').'</a>
                        <a class="dropdown-item" href="'.base_url().'admin/proposals/index/proposals_history/'.$record->proposals_id.'">'.lang('history').'</a>
                        <a class="dropdown-item" href="'.base_url().'admin/proposals/change_status/declined/'.$record->proposals_id.'">'. lang('declined').'</a>
                        <a class="dropdown-item" href="'.base_url().'admin/proposals/change_status/accepted/'.$record->proposals_id.'">'.lang('accepted').'</a>
                    </div>';
                }
            }
            $company_name = '<td class="col-sm-1">' . $record->name . '</td>';
            $data[] = array( 
                // "companies"=>super_admin_opt_td($record->companies_id, 'no'),
                "companies"=>$company_name,
                "proposal"=>$proposal,
                "proposal_date"=>display_datetime($record->proposal_date),
                "expire_date"=>display_datetime($record->due_date) . $due_date,
                "to"=>$client_name,
                "amount"=>display_money($this->proposal_model->proposal_calculation('total', $record->proposals_id), $currency->symbol),
                "status"=>'<span class="label label-'.$label.'">'.lang($record->status).'</span>',
                "action"=>$action,
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

    public function leads()
    {
		$CI = &get_instance();
		$user_data__ID = $CI->session->userdata('user_id');
        // POST data
        $postData = $this->input->post();

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = ""; // $postData['order'][0]['column']; // Column index
        $columnName = ""; // $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = ""; // $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            // $searchQuery = " (name like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        $records = $this->db->get('tbl_leads')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('lead_name LIKE', '%'.$searchValue.'%');
			$this->db->or_where('email LIKE', '%'.$searchValue.'%');
			$this->db->or_where('phone LIKE', '%'.$searchValue.'%');
			$this->db->or_where('mobile LIKE', '%'.$searchValue.'%');
			$this->db->or_where('contact_name LIKE', '%'.$searchValue.'%');
			$this->db->or_where('country LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
		}
        
        $records = $this->db->get('tbl_leads')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('lead_name LIKE', '%'.$searchValue.'%');
			$this->db->or_where('email LIKE', '%'.$searchValue.'%');
			$this->db->or_where('phone LIKE', '%'.$searchValue.'%');
			$this->db->or_where('mobile LIKE', '%'.$searchValue.'%');
			$this->db->or_where('contact_name LIKE', '%'.$searchValue.'%');
			$this->db->or_where('country LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
		}
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_leads')->result();

        $data = array();

        $all_field = get_result('tbl_custom_field', array('form_id' => 5));
        $table = get_row('tbl_form', array('form_id' => 5), 'tbl_name');
        $astatus_info = get_result('tbl_lead_status');
        $all_lead_source = get_result('tbl_lead_source');

        $created = can_action('55', 'created');
        $edited = can_action('55', 'edited');
        $deleted = can_action('55', 'deleted');

        foreach($records as $key => $record ){

            if ($record->converted_client_id == 0) {

                $user_list = array();
                $can_edit = $this->items_model->can_action('tbl_leads', 'edit', array('leads_id' => $record->leads_id));
                $can_delete = $this->items_model->can_action('tbl_leads', 'delete', array('leads_id' => $record->leads_id));

                $checkbox = '';
                if ($can_edit) {
                    $checkbox = '<div class="form-check font-size-16">
                        <input class="action-check form-check-input" type="checkbox" data-id="'.$record->leads_id.'" style="position: absolute;" name="leads_id[]" value="'.$record->leads_id.'">
                        <label class="form-check-label"></label>
                    </div>';
                }

                $lead_status_td = '';
                if (!empty($record->lead_status_id)) {
                   $key = array_search($record->lead_status_id, array_column($astatus_info, 'lead_status_id'));
                   $lead_status   = $astatus_info[$key];
                    if ($lead_status->lead_type == 'open') {
                        $status = "<span class='label label-success'>" . lang($lead_status->lead_type) . "</span>";
                    } else {
                        $status = "<span class='label label-warning'>" . lang($lead_status->lead_type) . "</span>";
                    }
                    $lead_status_td = $status . ' ' . $lead_status->lead_status;
                }

                $lead_source_td = '';
                if (!empty($record->lead_source_id)) {
                    // $lead_source = $this->db->where('lead_source_id', $record->lead_source_id)->get('tbl_lead_source')->row();
                    $l_key = array_search($record->lead_source_id, array_column($all_lead_source, 'lead_source_id'));
                    $lead_source   = $all_lead_source[$l_key];
                    if (!empty($lead_source->lead_source)) {
                        $lead_source_td = '<span class="badge badge-soft-info form-control-static">'.$lead_source->lead_source.'</span>';
                    }
                }

                $assigned_to = '<div class="avatar-group">';
                    if ($record->permission != 'all') {
                        $get_permission = json_decode($record->permission);
                        if (!empty($get_permission)) :
                            $i=$total_users=0;
                            foreach ($get_permission as $permission => $v_permission) :
                                $profile_info = $this->db->select('fullname,avatar')->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                if (!empty($profile_info)) {
                               
                                    $label = '';
                                    array_push($user_list, $profile_info->fullname);
                                    if($total_users<2){
                                        $assigned_to .= '<div class="avatar-group-item">
                                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$profile_info->fullname.'" class="d-inline-block"><img src="'.base_url().$profile_info->avatar.'" class="rounded-circle avatar-xs" alt="">
                                                <span style="margin: 0px 0 8px -10px;" class="mdi mdi-circle '.$label.' font-size-10"></span>
                                                </a>
                                            </div>';
                                    }
                                    $i=$i+1;
                                    $total_users=$total_users+1;
                                } 
                            endforeach;
                            if($total_users>2){
                                $total_users_minustwo = $total_users - 2;
                                $assigned_to .= '<div class="avatar-group-item">
                                    <a href="'.base_url().'admin/leads/leads_details/'.$record->leads_id.'" class="d-inline-block">
                                        <div class="avatar-xs">
                                            <span class="avatar-title rounded-circle bg-info text-white font-size-16">
                                                '.$total_users_minustwo.'+
                                            </span>
                                        </div>
                                    </a>
                                </div>';
                            }
                        endif;
                    } else {
                        $assigned_to .= '<span class="mr-lg-2">
                                <strong>'.lang('everyone').'</strong>
                                <i  title="'.lang('permission_for_all').'" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                            </span>';
                    }
                    if (!empty($can_edit) && !empty($edited)) {
                        $assigned_to .= '<span data-bs-placement="top" data-bs-toggle="tooltip" title="'.lang('add_more').'" class="mt-2">
                            <a  data-bs-toggle="modal" data-bs-target="#myModal" href="'.base_url().'admin/leads/update_users/'.$record->leads_id.'" class="text-default"><i class="fa fa-plus"></i></a>                                                
                        </span>';
                    }
                $assigned_to .= '</div>';

                $assigned_users_list = '';
                if(!empty($user_list)){
                    $assigned_users_list = implode(',', $user_list);
                }else{
                    if ($record->permission != 'all') {
                        $assigned_users_list = lang('not_assigned');                                                        
                    }else{
                        $assigned_users_list = lang('everyone');
                    }
                }

                $action = btn_view('admin/leads/leads_details/' . $record->leads_id);
                if (!empty($can_edit) && !empty($edited)) {
                    $action .= btn_edit('admin/leads/index/' . $record->leads_id);
                }
                if (!empty($can_delete) && !empty($deleted)) {
                    $action .= ajax_anchor(base_url("admin/leads/delete_leads/" . $record->leads_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#leads_" . $key));
                }
                if (!empty($can_edit) && !empty($edited)) {
                    $action .= '<div class="dropdown tbl-action mt">
                        <button class="btn btn-outline-success dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">'.lang('change_status').'<i class="mdi mdi-chevron-down"></i></button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                            if (!empty($astatus_info)) {
                                foreach ($astatus_info as $v_status) {
                                    $action .= '<a class="dropdown-item" href="'.base_url().'admin/leads/change_status/'.$record->leads_id.'/'.$v_status->lead_status_id.'">'.lang($v_status->lead_type) . '-' . $v_status->lead_status.'</a>';
                                } 
                            }
                        $action .= '</div>
                    </div>';
                }

                $data[] = array( 
                    "checkbox"=>$checkbox,
                    "contact_name"=>'<a href="'.base_url().'admin/leads/leads_details/'.$record->leads_id.'">'.$record->contact_name.'</a>',
                    "companies"=>super_admin_opt_td($record->companies_id, 'no'),
                    "email"=>$record->email,
                    "phone"=>$record->phone,
                    "lead_status"=>$lead_status_td,
                    "lead_source"=>$lead_source_td,
                    "assigned_to"=>$assigned_to,
                    "assigned_users_list"=>$assigned_users_list,
                    "action"=>$action,
                ); 
            }
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

    public function manage_client()
    {
		$CI = &get_instance();
		$user_data__ID = $CI->session->userdata('user_id');
        // POST data
        $postData = $this->input->post();

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = ""; // $postData['order'][0]['column']; // Column index
        $columnName = ""; // $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = ""; // $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            // $searchQuery = " (name like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        $records = $this->db->get('tbl_client')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('name LIKE', '%'.$searchValue.'%');
			$this->db->or_where('email LIKE', '%'.$searchValue.'%');
			$this->db->or_where('short_note LIKE', '%'.$searchValue.'%');
			$this->db->or_where('phone LIKE', '%'.$searchValue.'%');
			$this->db->or_where('mobile LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
			
		}
        
        $records = $this->db->get('tbl_client')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('name LIKE', '%'.$searchValue.'%');
			$this->db->or_where('email LIKE', '%'.$searchValue.'%');
			$this->db->or_where('short_note LIKE', '%'.$searchValue.'%');
			$this->db->or_where('phone LIKE', '%'.$searchValue.'%');
			$this->db->or_where('mobile LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
			
		}
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_client')->result();

        $data = array();

        $created = can_action('4', 'created');
        $edited = can_action('4', 'edited');
        $deleted = can_action('4', 'deleted');

        foreach($records as $key => $record ){
            $user_list=array();
            $client_transactions = get_sum('tbl_transactions', array('paid_by' => $record->client_id), 'amount');
            $customer_group = get_row('tbl_customer_group', array('customer_group_id' => $record->customer_group_id));
            $client_outstanding = $this->invoice_model->client_outstanding($record->client_id);
            $client_currency = $this->invoice_model->client_currency_sambol($record->client_id);
            if (!empty($client_currency)) {
                $cur = $client_currency->symbol;
            } else {
                $currency = get_row('tbl_currencies', array('code' => config_item('default_currency')));
                $cur = $currency->symbol;
            }

            $checkbox = '';
            if ($edited) {
                $checkbox = '<div class="form-check font-size-16">
                    <input class="action-check form-check-input" type="checkbox" data-id="'.$record->client_id.'" style="position: absolute;" name="client_id[]" value="'.$record->client_id.'">
                    <label class="form-check-label"></label>
                </div>';
            }

            $primary_contact_td = '';
            if ($record->primary_contact != 0) {
                $contacts = $record->primary_contact;
            } else {
                $contacts = NULL;
            }
            $primary_contact = $this->client_model->check_by(array('account_details_id' => $contacts), 'tbl_account_details');
            if ($primary_contact) {
                $primary_contact_td = $primary_contact->fullname;
            }

            $status_td = '';
            if (!empty($record->status)) {
                $lead_status = $this->db->where('lead_status_id', $record->status)->get('tbl_lead_status')->row();

                if ($lead_status->lead_type == 'open') {
                    $status = "<span class='badge badge-soft-success'>" . lang($lead_status->lead_type) . "</span>";
                } else {
                    $status = "<span class='badge badge-soft-warning'>" . lang($lead_status->lead_type) . "</span>";
                }
                $status_td = $status . ' ' . $lead_status->lead_status;
            }

            $source_td = '';
            if (!empty($record->source)) {
                $lead_source = $this->db->where('lead_source_id', $record->source)->get('tbl_lead_source')->row();
                if (!empty($lead_source->lead_source)) {
                    $source_td = '<span class="badge badge-soft-info form-control-static">'.$lead_source->lead_source.'</span>';
                }
            }

            $assigned_to = '<div class="avatar-group">';
                if ($record->permission != 'all') {
                    $get_permission = json_decode($record->permission);
                    if (!empty($get_permission)) :
                        $i=$total_users=0;
                        foreach ($get_permission as $permission => $v_permission) :
                            $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                            if (!empty($user_info)) {
                                if ($user_info->role_id == 1) {
                                    $label = 'text-danger';
                                } else {
                                    $label = 'text-success';
                                }
                                $profile_info = $this->db->select('fullname,avatar')->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                $label = '';
                                array_push($user_list, $profile_info->fullname);
                                if($total_users<2){
                                    $assigned_to .= '<div class="avatar-group-item">
                                            <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$profile_info->fullname.'" class="d-inline-block"><img src="'.base_url().$profile_info->avatar.'" class="rounded-circle avatar-xs" alt="">
                                            <span style="margin: 0px 0 8px -10px;" class="mdi mdi-circle '.$label.' font-size-10"></span>
                                            </a>
                                        </div>';
                                }
                                $i=$i+1;
                                $total_users=$total_users+1;
                            }
                        endforeach;
                        if($total_users>2){
                            $total_users_minustwo = $total_users - 2;
                            $assigned_to .= '<div class="avatar-group-item">
                                <a href="'.base_url().'admin/client/client_details/'.$record->client_id.'" class="d-inline-block">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle bg-info text-white font-size-16">
                                            '.$total_users_minustwo.'+
                                        </span>
                                    </div>
                                </a>
                            </div>';
                        }
                    endif;
                } else {
                    $assigned_to .= '<span class="mr-lg-2">
                            <strong>'.lang('everyone').'</strong>
                            <i  title="'.lang('permission_for_all').'" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                        </span>';
                }
                if (!empty($created) && !empty($edited)) {
                    $assigned_to .= '<span data-bs-placement="top" data-bs-toggle="tooltip" title="'.lang('add_more').'" class="mt-2">
                        <a  data-bs-toggle="modal" data-bs-target="#myModal" href="'.base_url().'admin/client/update_users/'.$record->client_id.'" class="text-default"><i class="fa fa-plus"></i></a>                                                
                    </span>';
                }
            $assigned_to .= '</div>';

            $assigned_users_list = '';
            if(!empty($user_list)){
                $assigned_users_list = implode(',', $user_list);
            }else{
                if ($record->permission != 'all') {
                    $assigned_users_list = lang('not_assigned');                                                        
                }else{
                    $assigned_users_list = lang('everyone');
                }
            }

            $action = '';
            if (!empty($edited)) {
                $action .= btn_edit('admin/client/manage_client/' . $record->client_id);
            }
            if (!empty($deleted)) {
                $action .= btn_delete('admin/client/delete_client/' . $record->client_id);
            }
            $action .= btn_view('admin/client/client_details/' . $record->client_id);

            $data[] = array( 
                "checkbox"=>$checkbox,
                "name"=>'<a href="'.base_url().'admin/client/client_details/'.$record->client_id.'">'.$record->name.'</a>',
                "companies"=>super_admin_opt_td($record->companies_id, 'no'),
                "contacts"=>'<span class="badge badge-soft-success" data-bs-toggle="tooltip" data-palcement="top" title="'.lang('contacts').'">'.$this->client_model->count_rows('tbl_account_details', array('company' => $record->client_id)).'</span>',
                "primary_contact"=>$primary_contact_td,
                "projects"=>count(get_result('tbl_project', array('client_id' => $record->client_id))),
                "due_amount"=>($client_outstanding > 0) ? display_money($client_outstanding, $cur) : '0.00',
                "received_amount"=>display_money($this->client_model->client_paid($record->client_id), $cur),
                "expense"=>($client_transactions[0]->amount > 0) ? display_money($client_transactions[0]->amount, $cur) : '0.00',
                "group"=>'<span class="badge badge-soft-default">' . (!empty($customer_group->customer_group)) ? $customer_group->customer_group : '' .'</span>',
                "status"=>$status_td,
                "source"=>$source_td,
                "assigned_to"=>$assigned_to,
                "assigned_users_list"=>$assigned_users_list,
                "action"=>$action,
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

    public function all_task()
    {
		$CI = &get_instance();
		$user_data__ID = $CI->session->userdata('user_id');
		
		$fliter___ = $_GET['filter'];
		
        // POST data
        $postData = $this->input->post();

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = ""; // $postData['order'][0]['column']; // Column index
        $columnName = ""; // $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = ""; // $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            // $searchQuery = " (name like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
		
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
		
		if($fliter___){
			$this->db->where('task_status', $fliter___);
		}
		
		
        $records = $this->db->get('tbl_task')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
		if($fliter___){
			$this->db->where('task_status', $fliter___);
		}
		
        if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('task_name LIKE', '%'.$searchValue.'%');
			$this->db->or_where('task_description LIKE', '%'.$searchValue.'%');
			$this->db->or_where('task_status LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
		}
        $records = $this->db->get('tbl_task')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
		if($fliter___){
			$this->db->where('task_status', $fliter___);
		}
        if($searchValue != ''){
			$this->db->group_start();
			$this->db->where('task_name LIKE', '%'.$searchValue.'%');
			$this->db->or_where('task_description LIKE', '%'.$searchValue.'%');
			$this->db->or_where('task_status LIKE', '%'.$searchValue.'%');
			$this->db->group_end();
			
		}
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_task')->result();
		
		//print_r($this->db->last_query());    die;

        $data = array();

        $created = can_action('54', 'created');
        $edited = can_action('54', 'edited');
        $deleted = can_action('54', 'deleted');
        $completed = true;

        foreach($records as $key => $record ){
            if ($record->task_status != 6 || !empty($completed)) {
                $task_status=$record->task_status;
                $can_edit = $this->tasks_model->can_action('tbl_task', 'edit', array('task_id' => $record->task_id));
                $can_delete = $this->tasks_model->can_action('tbl_task', 'delete', array('task_id' => $record->task_id));
                $task_kanban_category = $this->tasks_model->get_task_kanban_category($task_status);
               
                $task_status_name=$task_kanban_category[0]->kanban_category_name;

                $checkbox = '';
                if ($edited) {
                    $checkbox = '<div class="form-check font-size-16">
                        <input class="action-check form-check-input" type="checkbox" data-id="'.$record->task_id.'" style="position: absolute;" name="task_id[]" value="'.$record->task_id.'">
                        <label class="form-check-label"></label>
                    </div>';
                }

                $due_date_td = '';
                $due_date = $record->due_date;
                $due_time = strtotime($due_date);
                $current_time = time();
                if ($record->task_progress == 100) {
                    $c_progress = 100;
                } elseif ($task_status == 6) {
                    $c_progress = 100;
                } else {
                    $c_progress = 0;
                }
                $due_date_td .= display_datetime($due_date);
                if ($current_time > $due_time && $c_progress < 100) {
                    $due_date_td .= '<br><span  class="badge badge-soft-danger">'.lang('overdue').'</span>';
                }

                $status_td = '';
                if ($task_status == 6) {
                    $label = 'success';
                } elseif ($task_status == 1) {
                    $label = 'info';
                } elseif ($task_status == 5) {
                    $label = 'danger';
                } else {
                    $label = 'warning';
                }
                $status_td .= '<span class="badge badge-soft-'.$label.'">'.$task_status_name.'</span>';

                $progress_color = ($record->task_progress == 100) ? '8ec165' : 'fb6b5b';
                $progress = '<div class="inline ">
                        <div class="easypiechart text-success" style="margin: 0px;" data-percent="'.$record->task_progress.'" data-line-width="5" data-track-Color="#f0f0f0" data-bar-color="#'.$progress_color.'" data-rotate="270" data-scale-Color="false" data-size="50" data-animate="2000">
                            <span class="small ">'.$record->task_progress.' %</span>
                            </div>
                        </div>';

                $assigned_to = '<div class="avatar-group">';
                    if ($record->permission != 'all') {
                        $get_permission = json_decode($record->permission);
                        if (!empty($get_permission)) :
                            $i=$total_users=0;
                            foreach ($get_permission as $permission => $v_permission) :
                                $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                                if ($user_info->role_id == 1) {
                                    $label = 'text-danger';
                                } else {
                                    $label = 'text-success';
                                }
                                $profile_info = $this->db->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                if($total_users<2){
                                    $assigned_to .= '<div class="avatar-group-item">
                                            <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="
                                            '.$profile_info->fullname.'" class="d-inline-block"><img src="'.base_url() . $profile_info->avatar.'" class="rounded-circle avatar-xs" alt="">
                                                <span style="margin: 0px 0 8px -10px;" class="mdi mdi-circle '.$label.' font-size-10"></span>
                                            </a>
                                    </div>';
                                    $i=$i+1;
                                    $total_users=$total_users+1;
                                } 
                            endforeach;
                            if($total_users>2){
                                $total_users_minustwo = $total_users-2;
                                $assigned_to .= '<div class="avatar-group-item">
                                    <a href="'.base_url().'admin/tasks/view_task_details/'.$record->task_id.'" class="d-inline-block">
                                        <div class="avatar-xs">
                                            <span class="avatar-title rounded-circle bg-info text-white font-size-16">
                                                '.$total_users_minustwo.'+
                                            </span>
                                        </div>
                                    </a>
                                </div>';
                            }
                        endif;
                    } else {
                        $assigned_to .= '<span class="mr-lg-2">
                            <strong>'.lang('everyone').'</strong>
                            <i  title="'.lang('permission_for_all').'" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                        </span>';
                    }
                    if (!empty($can_edit) && !empty($edited)) {
                        $assigned_to .= '<span data-bs-placement="top" data-bs-toggle="tooltip" title="'.lang('add_more').'" class="mt-2">
                            <a  data-bs-toggle="modal" data-bs-target="#myModal" href="'.base_url().'admin/tasks/update_users/'.$record->task_id.'" class="text-default"><i class="fa fa-plus"></i></a>
                        </span>';
                    }
                $assigned_to .= '</div>';

                $action = '';
                if (!empty($can_edit) && !empty($edited)) {
                    $action .= btn_edit('admin/tasks/new_tasks/' . $record->task_id) . ' ';
                }
                if (!empty($can_delete) && !empty($deleted)) {
                    $action .= ajax_anchor(base_url("admin/tasks/delete_task/" . $record->task_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table-tasks-" . $key));
                }
                if ($record->timer_status == 'on') {
                    $action .=  '<a class="btn btn-outline-danger btn-sm"
                       href="'.base_url().'admin/tasks/tasks_timer/off/'.$record->task_id.'">'.lang('stop_timer').'</a>';
                } else {
                    $action .= '<a class="btn btn-outline-success btn-sm"
                       href="'.base_url().'admin/tasks/tasks_timer/on/'.$record->task_id.'">'.lang('start_timer').'</a>';
                }

                $task_name_style = ($record->task_progress >= 100) ? 'text-decoration: line-through;' : '';
                $data[] = array( 
                    "checkbox"=>$checkbox,
                    "task_name"=>'<a style="'.$task_name_style.'" href="'.base_url().'admin/tasks/view_task_details/'.$record->task_id.'">'.$record->task_name.'</a>',
                    "companies"=>super_admin_opt_td($record->companies_id, 'no'),
                    "due_date"=>$due_date_td,
                    "status"=>$status_td,
                    "progress"=>$progress,
                    "assigned_to"=>$assigned_to,
                    "action"=>$action,
                ); 
            }
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

    public function projects()
    {
		$CI = &get_instance();
		$user_data__ID = $CI->session->userdata('user_id');
		
		$fliter___ = $_GET['filter'];
        // POST data
        $postData = $this->input->post();

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = ""; // $postData['order'][0]['column']; // Column index
        $columnName = ""; // $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = ""; // $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            // $searchQuery = " (name like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
		
		if($fliter___){
			$this->db->where('project_status', $fliter___);
		}
        $records = $this->db->get('tbl_project')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
		if($fliter___){
			$this->db->where('project_status', $fliter___);
		}
        if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('project_name LIKE', '%'.$searchValue.'%');
			$this->db->or_where('description LIKE', '%'.$searchValue.'%');
			$this->db->or_where('project_status LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
		}
        $records = $this->db->get('tbl_project')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
		if($fliter___){
			$this->db->where('project_status', $fliter___);
		}
        if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('project_name LIKE', '%'.$searchValue.'%');
			$this->db->or_where('description LIKE', '%'.$searchValue.'%');
			$this->db->or_where('project_status LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
		}
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_project')->result();

        $data = array();

        $created = can_action('57', 'created');
        $edited = can_action('57', 'edited');
        $deleted = can_action('57', 'deleted');

        foreach($records as $key => $record ){
            $progress = $this->items_model->get_project_progress($record->project_id);
            $can_edit = $this->items_model->can_action('tbl_project', 'edit', array('project_id' => $record->project_id));
            $can_delete = $this->items_model->can_action('tbl_project', 'delete', array('project_id' => $record->project_id));

            $progress_class = ($progress >= 100) ? 'bg-success' : '';
            $project_name = '<a class="text-info" href="'.base_url().'admin/projects/project_details/'.$record->project_id.'">'.$record->project_name.'</a>
                    <div class="progress progress-sm">
                        <div class="progress-bar '.$progress_class.'" role="progressbar" style="width:'.$record->progress.'%;" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>';

            $client_info = $this->db->where('client_id', $record->client_id)->get('tbl_client')->row();
            if (!empty($client_info)) {
                $client = $client_info->name;
            } else {
                $client = '-';
            }

            $due_date_td = '';
            $due_date = $record->due_date;
            $due_time = strtotime($due_date);
            $current_time = time();
            if ($record->task_progress == 100) {
                $c_progress = 100;
            } elseif ($task_status == 6) {
                $c_progress = 100;
            } else {
                $c_progress = 0;
            }
            $due_date_td .= display_datetime($due_date);
            if ($current_time > $due_time && $c_progress < 100) {
                $due_date_td .= '<br><span  class="badge badge-soft-danger">'.lang('overdue').'</span>';
            }

            $status_td = '';
            if ($task_status == 6) {
                $label = 'success';
            } elseif ($task_status == 1) {
                $label = 'info';
            } elseif ($task_status == 5) {
                $label = 'danger';
            } else {
                $label = 'warning';
            }
            $status_td .= '<span class="badge badge-soft-'.$label.'">'.$task_status_name.'</span>';

            $progress_color = ($record->task_progress == 100) ? '8ec165' : 'fb6b5b';
            $progress = '<div class="inline ">
                    <div class="easypiechart text-success" style="margin: 0px;" data-percent="'.$record->task_progress.'" data-line-width="5" data-track-Color="#f0f0f0" data-bar-color="#'.$progress_color.'" data-rotate="270" data-scale-Color="false" data-size="50" data-animate="2000">
                        <span class="small ">'.$record->task_progress.' %</span>
                        </div>
                    </div>';

            $assigned_to = '<div class="avatar-group">';
                if ($record->permission != 'all') {
                    $get_permission = json_decode($record->permission);
                    if (!empty($get_permission)) :
                        $i=$total_users=0;
                        foreach ($get_permission as $permission => $v_permission) :
                            $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                            if (!empty($user_info)) {
                                if ($user_info->role_id == 1) {
                                    $label = 'text-danger';
                                } else {
                                    $label = 'text-success';
                                }
                                $profile_info = $this->db->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                if($total_users<2){
                                    $assigned_to .= '<div class="avatar-group-item">
                                        <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$profile_info->fullname.'" class="d-inline-block"><img src="'.base_url() . $profile_info->avatar.'" class="rounded-circle avatar-xs" alt="">
                                            <span style="margin: 0px 0 8px -10px;" class="mdi mdi-circle '.$label.' font-size-10"></span>
                                        </a>
                                    </div>';
                                }  
                                $i=$i+1;
                                $total_users=$total_users+1;
                                } 
                        endforeach;
                        if($total_users>2){
                            $total_users_minustwo = $total_users-2;
                            $assigned_to .= '<div class="avatar-group-item">
                                <a href="'.base_url().'admin/projects/project_details/'.$record->project_id.'" class="d-inline-block">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle bg-info text-white font-size-16">
                                            '.$total_users_minustwo.'+
                                        </span>
                                    </div>
                                </a>
                            </div>';
                        }
                    endif;
                } else {
                    $assigned_to .= '<span class="mr-lg-2 mt-2">
                        <strong>'.lang('everyone').'</strong>
                        <i  title="'.lang('permission_for_all').'" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                    </span>';
                }
                if (!empty($can_edit) && !empty($edited)) {
                    $assigned_to .= '<span data-bs-placement="top" data-bs-toggle="tooltip"  title="'.lang('add_more').'" class="mt-2">
                        <a data-bs-toggle="modal" data-bs-target="#myModal" href="'.base_url().'admin/projects/update_users/'.$record->project_id.'" class="text-default"><i class="fa fa-plus"></i></a>
                    </span>';
                }
            $assigned_to .= '</div>';

            $status_td = '';
            if (!empty($record->project_status)) {
                if ($record->project_status == 'completed') {
                    $statusss = "<span class='badge badge-soft-success'>" . lang($record->project_status) . "</span>";
                } elseif ($record->project_status == 'in_progress') {
                    $statusss = "<span class='badge badge-soft-primary'>" . lang($record->project_status) . "</span>";
                } elseif ($record->project_status == 'checking') {
                    $statusss = "<span class='badge badge-soft-danger'>" . lang($record->project_status) . "</span>";
                } elseif ($record->project_status == 'started') {
                    $statusss = "<span class='badge badge-soft-warning'>" . lang('project_in_progress') . "</span>";
                } else {
                    $statusss = "<span class='badge badge-soft-warning'>" . lang($record->project_status) . "</span>";
                }
                $status_td .= $statusss;
            }
            if (time() > strtotime($record->end_date) AND $progress < 100) {
                $status_td .= '<br><span class="badge badge-soft-danger">'.lang('overdue').'</span><br>';
            }

            $action = btn_view('admin/projects/project_details/' . $record->project_id);
            if (!empty($can_edit) && !empty($edited)) {
                $action .= '<a data-bs-toggle="modal" data-bs-target="#myModal" title="'.lang('clone_project').'" href="'.base_url().'admin/projects/clone_project/'.$record->project_id.'"><i class="btn btn-outline-secondary btn-sm fa fa-copy" style="height:26px;"></i></a>';
                $action .= btn_edit('admin/projects/new_project/' . $record->project_id);
            }
            if (!empty($can_delete) && !empty($deleted)) {
                $action .= ajax_anchor(base_url("admin/projects/delete_project/" . $record->project_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table-project-" . $key));
            }
            if (!empty($can_edit) && !empty($edited)) {
                $action .= '<div class="dropdown tbl-action mt">
                    <button class="btn btn-outline-success dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">'.lang('change_status').'<i class="mdi mdi-chevron-down"></i></button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="'.base_url().'admin/projects/change_status/'.$record->project_id . '/in_progress">'.lang('in_progress').'</a>
                        <a class="dropdown-item" href="'.base_url().'admin/projects/change_status/'.$record->project_id . '/started">'.lang('project_in_progress').'</a>
                        <a class="dropdown-item" href="'.base_url().'admin/projects/change_status/'.$record->project_id . '/checking">'.lang('checking').'</a>
                        <a class="dropdown-item" href="'.base_url().'admin/projects/change_status/'.$record->project_id . '/completed">'.lang('completed').'</a>
                    </div>
                </div>';
            }

            $task_name_style = ($record->task_progress >= 100) ? 'text-decoration: line-through;' : '';
            $data[] = array( 
                "project_name"=>$project_name,
                "companies"=>super_admin_opt_td($record->companies_id, 'no'),
                "client"=>$client,
                "end_date"=>display_datetime($record->end_date),
                "assigned_to"=>$assigned_to,
                "status"=>$status_td,
                "action"=>$action,
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

    public function manage_invoice()
    {
		$CI = &get_instance();
		$user_data__ID = $CI->session->userdata('user_id');
        // POST data
        $postData = $this->input->post();

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = ""; // $postData['order'][0]['column']; // Column index
        $columnName = ""; // $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = ""; // $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            // $searchQuery = " (name like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        $records = $this->db->get('tbl_invoices')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('reference_no LIKE', '%'.$searchValue.'%');
			$this->db->or_where('notes LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
			
		}
        $records = $this->db->get('tbl_invoices')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('reference_no LIKE', '%'.$searchValue.'%');
			$this->db->or_where('notes LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
			
		}
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_invoices')->result();

        $data = array();

        $created = can_action('13', 'created');
        $edited = can_action('13', 'edited');
        $deleted = can_action('13', 'deleted');

        foreach($records as $key => $record ){
            $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $record->invoices_id));
            $can_delete = $this->invoice_model->can_action('tbl_invoices', 'delete', array('invoices_id' => $record->invoices_id));

            if ($this->invoice_model->get_payment_status($record->invoices_id) == lang('fully_paid')) {
                $invoice_status = lang('fully_paid');
                $label = "success";
            } elseif ($this->invoice_model->get_payment_status($record->invoices_id) == lang('draft')) {
                $invoice_status = lang('draft');
                $label = "default";
            } elseif ($this->invoice_model->get_payment_status($record->invoices_id) == lang('partially_paid')) {
                $invoice_status = lang('partially_paid');
                $label = "warning";
            } elseif ($record->emailed == 'Yes') {
                $invoice_status = lang('sent');
                $label = "info";
            } else {
                $invoice_status = $this->invoice_model->get_payment_status($record->invoices_id);
                $label = "danger";
            }

            $due_date = display_datetime($record->due_date);
            $payment_status = $this->invoice_model->get_payment_status($record->invoices_id);
            if (strtotime($record->due_date) < time() AND $payment_status != lang('fully_paid')) {
                $due_date .= '<span class="badge badge-soft-danger ">'.lang('overdue').'</span>';
            }

            $client_info = $this->invoice_model->check_by(array('client_id' => $record->client_id), 'tbl_client');
            if (!empty($client_info)) {
                $client_name = $client_info->name;
            } else {
                $client_name = '-';
            }

            $currency = $this->invoice_model->client_currency_sambol($record->client_id);
            if (empty($currency)) {
                $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
            }

            $status_td = '<span class="badge badge-soft-'.$label.'">'.$invoice_status.'</span>';
            if ($record->recurring == 'Yes') { 
                $status_td .= '<span data-bs-toggle="tooltip" data-bs-placement="top" title="'.lang('recurring').'"
                      class="badge badge-soft-primary"><i class="fa fa-retweet"></i></span>';
            }

            $action = '';
            if (!empty($edited) || !empty($deleted)) {
                if (!empty($can_edit) && !empty($edited)) {
                    $action .= '<a data-bs-toggle="modal" data-bs-target="#myModal" title="'.lang('clone') . ' ' . lang('invoice').'"
                       href="'.base_url().'admin/invoice/clone_invoice/'.$record->invoices_id.'" class="btn btn-sm btn-outline-primary"> <i class="fa fa-copy"></i></a>';

                    $action .= btn_edit('admin/invoice/manage_invoice/create_invoice/' . $record->invoices_id);
                }
                if (!empty($can_delete) && !empty($deleted)) {
                    $action .= ajax_anchor(base_url("admin/invoice/delete/delete_invoice/" . $record->invoices_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#invoice_table_" . $key));
                }
                if (!empty($can_edit) && !empty($edited)) {
                    $action .= '<button class="btn btn-outline-success dropdown-toggle btn-sm" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">'.lang('change_status').'<i class="mdi mdi-chevron-down"></i></button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="'.base_url().'admin/invoice/manage_invoice/invoice_details/'.$record->invoices_id.'">'.lang('preview_invoice').'</a>
                        <a class="dropdown-item" href="'.base_url().'admin/invoice/manage_invoice/payment/'.$record->invoices_id.'">'.lang('pay_invoice').'</a>
                        <a class="dropdown-item" href="'.base_url().'admin/invoice/manage_invoice/email_invoice/'.$record->invoices_id.'">'.lang('email_invoice').'</a>
                        <a class="dropdown-item" href="'.base_url().'admin/invoice/manage_invoice/send_reminder/'.$record->invoices_id.'">'.lang('send_reminder').'</a>
                        <a class="dropdown-item" href="'.base_url().'admin/invoice/manage_invoice/send_overdue/'.$record->invoices_id.'">'.lang('send_invoice_overdue').'</a>
                        <a class="dropdown-item" href="'.base_url().'admin/invoice/manage_invoice/invoice_history/'.$record->invoices_id.'">'.lang('invoice_history').'</a>
                        <a class="dropdown-item" href="'.base_url().'admin/invoice/pdf_invoice/'.$record->invoices_id.'">'.lang('pdf').'</a>
                    </div>';
                }
            }

            $data_array = array( 
                "companies"=>super_admin_opt_td($record->companies_id, 'no'),
                "invoice"=>'<a class="text-info" href="'.base_url().'admin/invoice/manage_invoice/invoice_details/'.$record->invoices_id.'">'.$record->reference_no.'</a>',
                "invoice_date"=>display_datetime($record->end_date),
                "due_date"=>$due_date,
                "client_name"=>$client_name,
                "due_amount"=>display_money($this->invoice_model->calculate_to('invoice_due', $record->invoices_id), $currency->symbol),
                "status"=>$status_td,
            ); 

            if (!empty($edited) || !empty($deleted)) {
                $data_array['action'] = $action;
            }

            $data[] = $data_array;
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

    public function recurring_invoice()
    {
		$CI = &get_instance();
		$user_data__ID = $CI->session->userdata('user_id');
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
        $searchQuery = "(recurring='Yes')";
        if($searchValue != ''){
            $searchQuery .= " AND (reference_no like '%".$searchValue."%' or due_date like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        $this->db->where("(recurring='Yes')");
        $records = $this->db->get('tbl_invoices')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('tbl_invoices')->result();
        $totalRecordwithFilter = $records[0]->allcount;
	
        ## Fetch records
        $this->db->select('*');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchQuery != '')
        $this->db->where($searchQuery);
	
	if (is_company_column_ag()) {
        $this->db->order_by($columnName, $columnSortOrder);
	}
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_invoices')->result();

        $data = array();

        $created = can_action('51', 'created');
        $edited = can_action('51', 'edited');
        $deleted = can_action('51', 'deleted');

        foreach($records as $key => $record ){
            $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $record->invoices_id));
            $can_delete = $this->invoice_model->can_action('tbl_invoices', 'delete', array('invoices_id' => $record->invoices_id));
            if ($this->invoice_model->get_payment_status($record->invoices_id) == lang('fully_paid')) {
                $invoice_status = lang('fully_paid');
                $label = "success";
            } elseif ($record->emailed == 'Yes') {
                $invoice_status = lang('sent');
                $label = "info";
            } else {
                $invoice_status = lang('draft');
                $label = "default";
            }

            $client_info = $this->invoice_model->check_by(array('client_id' => $record->client_id), 'tbl_client');
            if (empty($client_info)) {
                $client_name = '-';
            } else {
                $client_name = $client_info->name;
            }

            $currency = $this->invoice_model->client_currency_sambol($record->client_id); 

            $status_td = '<span class="label label-'.$label.'">'.$invoice_status.'</span>';
            if ($record->recurring == 'Yes') {
                $status_td .= '<span data-bs-toggle="tooltip" data-bs-placement="top" title="'.lang('recurring').'" class="label label-primary"><i class="fa fa-retweet"></i></span>';
            }

            $data_array = array( 
                "companies_id"=>super_admin_opt_td($record->companies_id, 'no'),
                "invoice"=>'<a class="text-info" href="'.base_url().'admin/invoice/manage_invoice/invoice_details/'.$record->invoices_id.'">'.$record->reference_no.'</a>',
                "due_date"=>display_datetime($record->due_date),
                "client_name"=>$client_name,
                "amount"=>display_money($this->invoice_model->calculate_to('invoice_cost', $record->invoices_id), $currency->symbol),
                "due_amount"=>display_money($this->invoice_model->calculate_to('invoice_due', $record->invoices_id), $currency->symbol),
                "status"=>$status_td,
            ); 

            if (!empty($edited) || !empty($deleted)) {
                $action = '';
                if (!empty($can_edit) && !empty($edited)) {
                    $action .= btn_edit('admin/invoice/manage_invoice/create_invoice/' . $record->invoices_id);
                }
                if (!empty($can_delete) && !empty($deleted)) {
                    $action .= ajax_anchor(base_url("admin/invoice/delete/delete_invoice/" . $record->invoices_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_recurr_" . $key));
                }
                if (!empty($can_edit) && !empty($edited)) {
                    $action .= '<div class="dropdown tbl-action mt">
                        <button class="btn btn-outline-success dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">'.lang('change_status').'<i class="mdi mdi-chevron-down"></i></button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="'.base_url().'admin/invoice/manage_invoice/invoice_details/'.$record->invoices_id.'">'.lang('preview_invoice').'</a>
                            <a class="dropdown-item" href="'.base_url().'admin/invoice/manage_invoice/payment/'.$record->invoices_id.'">'.lang('pay_invoice').'</a>
                            <a class="dropdown-item" href="'.base_url().'admin/invoice/manage_invoice/email_invoice/'.$record->invoices_id.'">'.lang('email_invoice').'</a>
                            <a class="dropdown-item" href="'.base_url().'admin/invoice/manage_invoice/send_reminder/'.$record->invoices_id.'">'.lang('send_reminder').'</a>
                            <a class="dropdown-item" href="'.base_url().'admin/invoice/manage_invoice/send_overdue/'.$record->invoices_id.'">'.lang('send_invoice_overdue').'</a>
                            <a class="dropdown-item" href="'.base_url().'admin/invoice/manage_invoice/invoice_history/'.$record->invoices_id.'">'.lang('invoice_history').'</a>
                            <a class="dropdown-item" href="'.base_url().'admin/invoice/manage_invoice/pdf_invoice/'.$record->invoices_id.'">'.lang('pdf').'</a>
                        </div>
                    </div>';
                }
                $data_array['action'] = $action;
            }

            $data[] = $data_array;
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

    public function all_payments()
    {
		$CI = &get_instance();
		$user_data__ID = $CI->session->userdata('user_id');
        // POST data
        $postData = $this->input->post();

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = ''; // $postData['order'][0]['column']; // Column index
        $columnName = ''; // $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = ''; // $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery .= " (reference_no like '%".$searchValue."%' or due_date like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        $records = $this->db->get('tbl_invoices')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('reference_no LIKE', '%'.$searchValue.'%');
			$this->db->or_where('notes LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
			
		}
        $records = $this->db->get('tbl_invoices')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('reference_no LIKE', '%'.$searchValue.'%');
			$this->db->or_where('notes LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
			
		}
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_invoices')->result();

        $data = array();

        $created = can_action('13', 'created');
        $edited = can_action('13', 'edited');
        $deleted = can_action('13', 'deleted');

        foreach($records as $key => $record ){
            $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $record->invoices_id));
            $can_delete = $this->invoice_model->can_action('tbl_invoices', 'delete', array('invoices_id' => $record->invoices_id));

            $all_payment_info = $this->db->where('invoices_id', $record->invoices_id)->get('tbl_payments')->result();
            if (!empty($all_payment_info)){ 
                foreach ($all_payment_info as $key => $v_payments_info){

                    $client_info = $this->invoice_model->check_by(array('client_id' => $record->client_id), 'tbl_client');
                    if (!empty($client_info)) {
                        $c_name = $client_info->name;
                        $currency = $this->invoice_model->client_currency_sambol($record->client_id);
                    } else {
                        $c_name = '-';
                        $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                    }
                    $payment_methods = $this->invoice_model->check_by(array('payment_methods_id' => $v_payments_info->payment_method), 'tbl_payment_methods');

                    $data_array = array( 
                        "payment_date"=>'<a href="'.base_url().'admin/invoice/manage_invoice/payments_details/'.$v_payments_info->payments_id.'">'.display_datetime($v_payments_info->payment_date).'</a>',
                        "invoice_date"=>display_datetime($record->invoice_date),
                        "invoice"=>'<a class="text-info" href="'.base_url().'admin/invoice/manage_invoice/invoice_details/'.$v_payments_info->invoices_id.'">'.$record->reference_no.'</a>',
                        "client"=>$c_name,
                        "amount"=>display_money($v_payments_info->amount, $currency->symbol),
                        "payment_method"=>!empty($payment_methods->method_name) ? $payment_methods->method_name : '-',
                    ); 

                    if (!empty($edited) || !empty($deleted)) {
                        $action = '';
                        if (!empty($can_edit) && !empty($edited)) {
                            $action .= btn_edit('admin/invoice/all_payments/' . $v_payments_info->payments_id);
                        }
                        if (!empty($can_delete) && !empty($deleted)) {
                            $action .= ajax_anchor(base_url("admin/invoice/delete/delete_payment/" . $v_payments_info->payments_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child",  "data-fade-out-on-success" => "#table_payments_" . $key));
                        }
                        $action .= btn_view('admin/invoice/manage_invoice/payments_details/' . $v_payments_info->payments_id);
                        if (!empty($can_edit) && !empty($edited)) {
                            $action .= '<a data-bs-toggle="tooltip" data-placement="top" href="'.base_url().'admin/invoice/send_payment/'.$v_payments_info->payments_id . '/' . $v_payments_info->amount.'" title="'.lang('send_email').'" class="btn btn-sm btn-success"> <i class="fa fa-envelope"></i> </a>';
                        }
                        $data_array['action'] = $action;
                    }

                    $data[] = $data_array;
                }
            }
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

    public function deposit()
    {
		$CI = &get_instance();
		$user_data__ID = $CI->session->userdata('user_id');
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
        $searchQuery = "(type='Income')";
        if($searchValue != ''){
            $searchQuery .= " AND (name like '%".$searchValue."%' or date like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        $this->db->where("(type='Income')");
        $records = $this->db->get('tbl_transactions')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('tbl_transactions')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_transactions')->result();

        $data = array();

        $created = can_action('30', 'created');
        $edited = can_action('30', 'edited');
        $deleted = can_action('30', 'deleted');

        $curency = $this->transactions_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        $total_amount = 0;
        $total_credit = 0;
        $total_balance = 0;

        foreach($records as $key => $record ){
            $can_edit = $this->transactions_model->can_action('tbl_transactions', 'edit', array('transactions_id' => $record->transactions_id));
            $can_delete = $this->transactions_model->can_action('tbl_transactions', 'delete', array('transactions_id' => $record->transactions_id));

            $account_info = $this->transactions_model->check_by(array('account_id' => $record->account_id), 'tbl_accounts');
            $client_info = $this->transactions_model->check_by(array('client_id' => $record->paid_by), 'tbl_client');
            $category_info = $this->transactions_model->check_by(array('income_category_id' => $record->category_id), 'tbl_income_category');
            if (!empty($client_info)) {
                $client_name = $client_info->name;
            } else {
                $client_name = '-';
            }

            $data_array = array( 
                "companies_id"=>super_admin_opt_td($record->companies_id, 'no'),
                "name"=>'<a data-bs-toggle="modal" data-bs-target="#myModal" href="'.base_url().'admin/transactions/view_expense/'.$record->transactions_id.'"> '.($record->name ? $record->name : '-').'</a>',
                "date"=>'<a data-bs-toggle="modal" data-bs-target="#myModal" href="'.base_url().'admin/transactions/view_expense/'.$record->transactions_id.'"> '.display_datetime($record->date).'</a>',
                "account"=>(!empty($account_info->account_name)) ? $account_info->account_name : '-',
                "paid_by"=>$client_name,
                "amount"=>display_money($record->amount, $curency->symbol),
                "balance"=>display_money($record->total_balance, $curency->symbol),
                "attachment"=>(!empty(json_decode($record->attachement))) ? '<a href="'.base_url().'admin/transactions/download/'.$record->transactions_id.'">'.lang('download').'</a>' : '',
            ); 

            if (!empty($edited) || !empty($deleted)) {
                $action = '<a data-bs-toggle="modal" data-bs-target="#myModal" class="btn btn-info btn-sm" href="'.base_url().'admin/transactions/view_expense/'.$record->transactions_id.'">
                        <span class="fa fa-list-alt"></span>
                    </a>';
                if (!empty($can_edit) && !empty($edited)) {
                    if (!empty($edited) && !empty($can_edit)) {
                        $action .= btn_edit('admin/transactions/deposit/' . $record->transactions_id);
                    }
                    if (!empty($deleted) && !empty($can_delete)) {
                        $action .= ajax_anchor(base_url("admin/transactions/delete_deposit/".$record->transactions_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_deposit_" . $key));
                    }
                }
                $data_array['action'] = $action;
            }
            $total_amount += $record->amount;
            $total_credit += $record->credit;
            $total_balance = $total_credit;

            $data[] = $data_array;
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

    public function quotations()
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
            $searchQuery = " (quotations_form_title like '%".$searchValue."%' or name like '%".$searchValue."%' or quotations_date like '%".$searchValue."%' or quotations_status like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('tbl_quotations')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('tbl_quotations')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_quotations')->result();

        $data = array();

        foreach($records as $key => $record ){

            $client_info = $this->quotations_model->check_by(array('client_id' => $record->client_id), 'tbl_client');
            $user_info = $this->quotations_model->check_by(array('user_id' => $record->user_id), 'tbl_users');
            if (!empty($user_info)) {
                if ($user_info->role_id == 1) {
                    $user = '(admin)';
                } elseif ($user_info->role_id == 3) {
                    $user = '(Staff)';
                } else {
                    $user = '(client)';
                }
            } else {
                $user = ' ';
            }
            $currency = $this->quotations_model->client_currency_sambol($record->client_id);
            if (empty($currency)) {
                $currency = $this->quotations_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
            }            

            $data[] = array( 
                "companies_id"=>super_admin_opt_td($record->companies_id, 'no'),
                "quotations_form_title"=>'<a href="'.base_url().'admin/quotations/quotations_details/'.$record->quotations_id.'">'.$record->quotations_form_title.'</a>',
                "name"=>$record->name,
                "quotations_date"=>display_datetime($record->quotations_date),
                "quotations_amount"=>(!empty($record->quotations_amount)) ? display_money($record->quotations_amount, $currency->symbol) : '',
                "quotations_status"=>($record->quotations_status == 'completed') ? '<span class="label label-success">' . lang('completed') . '</span>' : '<span class="label label-danger">' . lang('pending') . '</span>',
                "generated_by"=>(!empty($user_info->username) ? $user_info->username : '-') . ' ' . $user,
                "action"=>btn_view('admin/quotations/quotations_details/' . $record->quotations_id) . btn_delete('admin/quotations/index/delete_quotations/' . $record->quotations_id)
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

    public function jobs_posted()
    {
		$CI = &get_instance();
		$user_data__ID = $CI->session->userdata('user_id');
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
            $searchQuery = " (job_title like '%".$searchValue."%' or vacancy_no like '%".$searchValue."%' or last_date like '%".$searchValue."%' or status like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        $records = $this->db->get('tbl_job_circular')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('tbl_job_circular')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_job_circular')->result();

        $data = array();

        $created = can_action('103', 'created');
        $edited = can_action('103', 'edited');
        $deleted = can_action('103', 'deleted');

        foreach($records as $key => $record ){

            if (!empty($record->designations_id)) {
                $design_info = $this->db->where('designations_id', $record->designations_id)->get('tbl_designations')->row();
                if (!empty($design_info)) {
                    $designation = $design_info->designations;
                } else {
                    $designation = '-';
                }
            } else {
                $designation = '-';
            }
            $can_edit = $this->job_circular_model->can_action('tbl_job_circular', 'edit', array('job_circular_id' => $record->job_circular_id));
            $can_delete = $this->job_circular_model->can_action('tbl_job_circular', 'delete', array('job_circular_id' => $record->job_circular_id));    

            $action = '<a href="'.base_url().'admin/job_circular/jobs_applications/'.$record->job_circular_id.'" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="'.lang('all_application_for').'"> <i class="fa fa-list"></i> </a>';
            if (!empty($can_edit) && !empty($edited)) {
                if ($record->status == 'unpublished') {
                    $action .= btn_publish('admin/job_circular/change_status/published/' . $record->job_circular_id);
                } else {
                    $action .= btn_unpublish('admin/job_circular/change_status/unpublished/' . $record->job_circular_id);
                }
                $action .= '<span data-bs-toggle="tooltip" data-bs-placement="top" title="'.lang('edit').'">
                                <a href="'.base_url().'admin/job_circular/new_jobs_posted/'.$record->job_circular_id.'" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal_lg"> <i class="fa fa-pencil-square-o"></i> </a>
                            </span>';
            }
            if (!empty($can_delete) && !empty($deleted)) {
                $action .= btn_delete('admin/job_circular/delete_jobs_posted/' . $record->job_circular_id);
            }
            $action .= '<a target="_blank" href="'.base_url().'frontend/circular_details/'.$record->job_circular_id.'" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="'.lang('view_circular_details') . ' & ' . lang('apply_now').'"> <i class="fa fa-paper-plane"></i> </a> ';

            $data[] = array( 
                "companies_id"=>super_admin_opt_td($record->companies_id, 'no'),
                "job_title"=>'<a data-bs-toggle="modal" data-bs-target="#myModal_lg" href="'.base_url().'admin/job_circular/view_circular_details/'.$record->job_circular_id.'"> '.$record->job_title.'</a>',
                "designation"=>$designation,
                "vacancy_no"=>$record->vacancy_no,
                "last_date"=>display_datetime($record->last_date),
                "status"=>($record->status == 'unpublished') ? '<span class="badge badge-soft-danger">'.lang('unpublished').'</span>' : '<span class="badge badge-soft-success">'.lang('published').'</span>',
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

    public function jobs_applications()
    {
		$CI = &get_instance();
		$user_data__ID = $CI->session->userdata('user_id');
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
            $searchQuery = " (tbl_job_circular.job_title like '%".$searchValue."%' or tbl_job_appliactions.name like '%".$searchValue."%' or tbl_job_appliactions.email like '%".$searchValue."%' or tbl_job_appliactions.mobile like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('tbl_job_appliactions');
        $this->db->join('tbl_job_circular', 'tbl_job_circular.job_circular_id = tbl_job_appliactions.job_circular_id', 'left');
        if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(tbl_job_circular.permission, '$.".$user_data__ID."[0]') != '') ");
		}
		$companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            if ($this->db->field_exists('companies_id', 'tbl_job_circular')) {
                $this->db->where('tbl_job_circular.companies_id', $companies_id);
            }
        }
        $records = $this->db->get()->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('tbl_job_appliactions');
        $this->db->join('tbl_job_circular', 'tbl_job_circular.job_circular_id = tbl_job_appliactions.job_circular_id', 'left');
        if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(tbl_job_circular.permission, '$.".$user_data__ID."[0]') != '') ");
		}
		$companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            if ($this->db->field_exists('companies_id', 'tbl_job_circular')) {
                $this->db->where('tbl_job_circular.companies_id', $companies_id);
            }
        }
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('tbl_job_appliactions.*');
        $this->db->from('tbl_job_appliactions');
        $this->db->join('tbl_job_circular', 'tbl_job_circular.job_circular_id = tbl_job_appliactions.job_circular_id', 'left');
        if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(tbl_job_circular.permission, '$.".$user_data__ID."[0]') != '') ");
		}
		$companies_id = $this->session->userdata('companies_id');
        if (!empty($companies_id)) {
            if ($this->db->field_exists('companies_id', 'tbl_job_circular')) {
                $this->db->where('tbl_job_circular.companies_id', $companies_id);
            }
        }
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();

        $data = array();

        foreach($records as $key => $record ){

            if ($record->application_status == 0) {
                $application_status = '<span class="badge badge-soft-warning">' . lang('unread') . '</span>';
            } elseif ($record->application_status == 1) {
                $application_status = '<span class="badge badge-soft-success">' . lang('approved') . '</span>';
            } elseif ($record->application_status == 2) {
                $application_status = '<span class="badge badge-soft-primary">' . lang('primary_selected') . '</span>';
            } elseif ($record->application_status == 3) {
                $application_status = '<span class="badge badge-soft-purple">' . lang('call_for_interview') . '</span>';
            } else {
                $application_status = '<span class="badge badge-soft-danger">' . lang('rejected') . '</span>';
            }

            $action = '<a href="'.base_url().'admin/job_circular/download_resume/'.$record->job_appliactions_id.'" class="btn btn-outline-primary btn-sm" data-bs-placement="top" title="'.lang('download') . ' ' . lang('resume').'" data-bs-toggle="tooltip"><span class="fa fa-download"></span></a>';
            $action .= '<a href="'.base_url().'admin/job_circular/change_application_status/'.$record->job_appliactions_id.'" class="btn btn-outline-success btn-sm" title="'.lang('change_status').'" data-bs-toggle="modal" data-bs-target="#myModal"><span class="fa fa-pencil-square-o"></span> '.lang('status').'</a>';
            $action .= '<a href="'.base_url().'admin/job_circular/jobs_application_details/'.$record->job_appliactions_id.'" class="btn btn-outline-info btn-sm" title="View" data-bs-toggle="modal" data-bs-target="#myModal"><span class="fa fa-list-alt"></span></a>';
            $action .= btn_delete('admin/job_circular/delete_jobs_application/' . $record->job_appliactions_id);

            $data[] = array( 
                "companies_id"=>super_admin_opt_td($record->companies_id, 'no'),
                "job_title"=>$record->job_title,
                "name"=>$record->name,
                "email"=>$record->email,
                "mobile"=>$record->mobile,
                "apply_on"=>display_datetime($record->apply_date),
                "application_status"=>$application_status,
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

    public function training()
    {
		$CI = &get_instance();
		$user_data__ID = $CI->session->userdata('user_id');
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
            $searchQuery = " (training_name like '%".$searchValue."%' or vendor_name like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        $records = $this->db->get('tbl_training')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('tbl_training')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchQuery != '')
        $this->db->where($searchQuery);
	if (is_company_column_ag()) {
        $this->db->order_by($columnName, $columnSortOrder);
	}
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_training')->result();

        $data = array();

        $created = can_action('101', 'created');
        $edited = can_action('101', 'edited');
        $deleted = can_action('101', 'deleted');

        foreach($records as $key => $record ){

            $profile_info = $this->db->where('user_id', $record->user_id)->get('tbl_account_details')->row();
            $can_edit = $this->training_model->can_action('tbl_training', 'edit', array('training_id' => $record->training_id));
            $can_delete = $this->training_model->can_action('tbl_training', 'delete', array('training_id' => $record->training_id));
            if (!empty($profile_info->fullname)) {
                $name = $profile_info->fullname . ' (' . $profile_info->employment_id . ')';
            } else {
                $name = '-';
            }

            if ($record->status == '0') {
                $status = '<span class="badge badge-soft-warning">' . lang('pending') . ' </span>';
            } elseif ($record->status == '1') {
                $status = '<span class="badge badge-soft-info">' . lang('started') . '</span>';
            } elseif ($record->status == '2') {
                $status = '<span class="badge badge-soft-success"> ' . lang('completed') . ' </span>';
            } else {
                $status = '<span class="badge badge-soft-danger"> ' . lang('terminated ') . '</span>';
            }

            $action = '';
            if (!empty($can_edit) && !empty($edited)) {
                $action .= '<span data-bs-toggle="tooltip" data-bs-placement="top" title="'.lang('edit').'">
                            <a href="'.base_url().'admin/training/new_training/'.$record->training_id.'" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal_lg">
                                <i class="fa fa-pencil-square-o"></i> </a>
                        </span>';
            }
            if (!empty($can_delete) && !empty($deleted)) {
                $action .= btn_delete('admin/training/delete_training/' . $record->training_id);
            }
            $action .= btn_view_modal('admin/training/training_details/' . $record->training_id);

            $data[] = array( 
                "companies_id"=>super_admin_opt_td($record->companies_id, 'no'),
                "name"=>$name,
                "training_name"=>$record->training_name,
                "vendor_name"=>$record->vendor_name,
                "start_date"=>display_datetime($record->start_date),
                "finish_date"=>display_datetime($record->finish_date),
                "status"=>$status,
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

    public function tickets()
    {
		$CI = &get_instance();
		$user_data__ID = $CI->session->userdata('user_id');
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

        $status = $_GET['ticket_status'];
        ## Search 
        $searchQuery = "";
        
        if($searchValue != ''){
            //$searchQuery .= "(ticket_code like '%".$searchValue."%' or subject like '%".$searchValue."%') ";
        }
		if (($status == 'answered') || ($status == 'open') || ($status == 'in_progress') || ($status == 'closed')) {
            $searchQuery = "(status='".$status."')";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchQuery != '')
        $this->db->where($searchQuery);
	
		if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('ticket_code LIKE', '%'.$searchValue.'%');
			$this->db->or_where('subject LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
			
		}
        $records = $this->db->get('tbl_tickets')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchQuery != '')
        $this->db->where($searchQuery);
	
		if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('ticket_code LIKE', '%'.$searchValue.'%');
			$this->db->or_where('subject LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
			
		}
        $records = $this->db->get('tbl_tickets')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
		if( !is_company_column_ag() ){
			$this->db->where(" (JSON_VALUE(permission, '$.".$user_data__ID."[0]') != '') ");
		}
        if($searchQuery != '')
        $this->db->where($searchQuery);
	
	
		if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('ticket_code LIKE', '%'.$searchValue.'%');
			$this->db->or_where('subject LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
			
		}
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_tickets')->result();

        $data = array();

        if (!empty($status)) {
            $m_id = 7;
            if ($status == 'answered') {
                $m_id = 8;
            }
            if ($status == 'open') {
                $m_id = 9;
            }
            if ($status == 'in_progress') {
                $m_id = 10;
            }
            if ($status == 'closed') {
                $m_id = 11;
            }
        } else {
            $m_id = 7;
        }

        if (!empty($m_id)) {
            $created = can_action($m_id, 'created');
            $edited = can_action($m_id, 'edited');
            $deleted = can_action($m_id, 'deleted');
        }

        foreach($records as $key => $record ){

            $can_edit = $this->tickets_model->can_action('tbl_tickets', 'edit', array('tickets_id' => $record->tickets_id));
            $can_delete = $this->tickets_model->can_action('tbl_tickets', 'delete', array('tickets_id' => $record->tickets_id));
            if ($record->status == 'open') {
                $s_label = 'danger';
            } elseif ($record->status == 'closed') {
                $s_label = 'success';
            } else {
                $s_label = 'primary';
            }
            $profile_info = $this->db->where(array('user_id' => $record->reporter))->get('tbl_account_details')->row();
            $dept_info = $this->db->where(array('departments_id' => $record->departments_id))->get('tbl_departments')->row();
            if (!empty($dept_info)) {
                $dept_name = $dept_info->deptname;
            } else {
                $dept_name = '-';
            }

            if ($record->status == 'in_progress') {
                $status_td = 'In Progress';
            } else {
                $status_td = $record->status;
            }

            $action = '';
            if (!empty($can_edit) && !empty($edited)) {
                $action .= btn_edit('admin/tickets/index/edit_tickets/' . $record->tickets_id);
            }
            if (!empty($can_delete) && !empty($deleted)) {
                $action .= ajax_anchor(base_url("admin/tickets/delete/delete_tickets/".$record->tickets_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_ticket_" . $key));
            }
            $action .= btn_view('admin/tickets/index/tickets_details/' . $record->tickets_id);
            if (!empty($can_edit) && !empty($edited)) {
                $action .= '<div class="dropdown tbl-action mt">
                    <button class="btn btn-outline-success dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">'.lang('change_status').'<i class="mdi mdi-chevron-down"></i></button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                        $status_info = $this->db->get('tbl_status')->result();
                        if (!empty($status_info)) {
                            foreach ($status_info as $v_status) {
                            $action .= '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#myModal" href="'.base_url().'admin/tickets/change_status/'.$record->tickets_id.'/'.$v_status->status.'">'. ucfirst($v_status->status).'</a>';
                            }  
                        } 
                $action .= '</div>
                </div>';
            }

            $data_array = array( 
                "companies_id"=>super_admin_opt_td($record->companies_id, 'no'),
                "ticket_code"=>'span class="badge badge-soft-success">'.$record->ticket_code.'</span>',
                "subject"=>'<a class="text-info" href="'.base_url().'admin/tickets/index/tickets_details/'.$record->tickets_id.'">'.$record->subject.'</a>',
                "date"=>display_datetime($record->created),
                "department"=>$dept_name,
                "status"=>'<span class="badge badge-soft-'.$s_label.'">'.$status_td.'</span>',
                "action"=>$action
            ); 

            if ($this->session->userdata('user_type') == '1') {
                $data_array['reporter'] = '<a class="pull-left recect_task  ">';
                    if (!empty($profile_info)) {
                        $data_array['reporter'] .= '<img style="width: 30px;margin-left: 18px;  height: 29px;  border: 1px solid #aaa;" src="'.base_url() . $profile_info->avatar.'" class="img-circle">' . ($profile_info->fullname);
                    } else {
                        $data_array['reporter'] .= '-';
                    }
                $data_array['reporter'] .= '</a>';
            }

            $data[] = $data_array;
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

    public function user_list()
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
            //$searchQuery = " (username like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('tbl_users')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('username LIKE', '%'.$searchValue.'%');
			$this->db->or_where('email LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
			
		}
        $records = $this->db->get('tbl_users')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
        if($searchValue != ''){
			//$this->db->where($searchQuery);
            $this->db->group_start();
			$this->db->where('username LIKE', '%'.$searchValue.'%');
			$this->db->or_where('email LIKE', '%'.$searchValue.'%');
            $this->db->group_end();
			
		}
	if (is_company_column_ag()) {
        $this->db->order_by($columnName, $columnSortOrder);
	}
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('tbl_users')->result();

        $data = array();

        $created = can_action('24', 'created');
        $edited = can_action('24', 'edited');
        $deleted = can_action('24', 'deleted');

        foreach($records as $key => $record ){

            $account_info = $this->user_model->check_by(array('user_id' => $record->user_id), 'tbl_account_details');
            if (!empty($account_info)) {
                $can_edit = $this->user_model->can_action('tbl_users', 'edit', array('user_id' => $record->user_id));
                $can_delete = $this->user_model->can_action('tbl_users', 'delete', array('user_id' => $record->user_id));

                $active = '';
                if ($record->user_id != $this->session->userdata('user_id')):
                    if (!empty($can_edit) && !empty($edited)) {
                        $activated = '';
                        if (!empty($record->activated) && $record->activated == '1') {
                            $activated = 'checked';
                        }
                        $active .= '<div class="change_user_status form-check form-switch mb-3">
                                        <input class="form-check-input" data-id="'.$record->user_id.'" name="active" value="1" '.$activated.' data-on="'.lang('yes').'" data-off="'.lang('no').'" data-onstyle="success btn-sm" data-offstyle="danger btn-sm" type="checkbox">
                                    </div>';
                    } else {
                        if ($record->activated == 1):
                            $active .= '<span class="badge badge-soft-success">'.lang('active').'</span>';
                        else:
                            $active .= '<span class="badge badge-soft-danger">'.lang('deactive').'</span>';
                        endif;
                    }
                else:
                    if ($record->activated == 1):
                        $active .= '<span class="badge badge-soft-success">'.lang('active').'</span>';
                    else:
                        $active .= '<span class="badge badge-soft-danger">'.lang('deactive').'</span>';
                    endif;
                endif;
                if ($record->banned == 1) {
                    $active .= '<span class="badge badge-soft-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                          title="'.$record->ban_reason.'">'.lang('banned').'</span>';
                }

                $user_type = '';
                if ($record->role_id == 1 && $record->super_admin == 'Yes') {
                    $user_type .= '<span class="text-danger">' . lang('super_admin') . '</span>';
                } elseif ($record->role_id == 1 && $record->super_admin == 'owner') {
                    $user_type .= '<span class="text-danger">' . lang('owner') . '</span>';
                } elseif ($record->role_id == 1) {
                    $user_type .= lang('admin');
                } elseif ($record->role_id == 3) {
                    $user_type .= lang('staff');
                } else {
                    $user_type .= lang('client');
                }

                $action = '';
                if ($record->user_id != $this->session->userdata('user_id')):
                    if (!empty($can_edit) && !empty($edited)) {
                        if ($record->banned == 1):
                            $action .= '<a data-bs-toggle="tooltip" data-bs-placement="top"  class="btn btn-success btn-sm"  title="Click to '.lang('unbanned').' "  href="'.base_url().'admin/user/set_banned/0/'.$record->user_id.'"><span  class="fa fa-check"></span></a>';
                        else:
                            $action .= '<span data-bs-toggle="tooltip" data-bs-placement="top"  title="Click to '.lang('banned').'">
                                '.btn_banned_modal('admin/user/change_banned/' . $record->user_id).'
                            </span>';
                        endif;
                    }
                    $action .= '<a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-outline-info btn-sm" title="'.lang('send') . ' ' . lang('wellcome_email').'"  href="'.base_url().'admin/user/send_welcome_email/'.$record->user_id.'"><span class="fa fa-envelope-o"></span></a>';
                    if (!empty($can_edit) && !empty($edited)) {
                        $action .= btn_edit('admin/user/user_list/edit_user/' . $record->user_id);
                    }
                    if (!empty($can_delete) && !empty($deleted)) {
                        $action .= btn_delete('admin/user/delete_user/' . $record->user_id);
                    }
                endif;
                if( !$record->is_listing_connected){
                    $action .= '<a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-outline-info btn-sm" title="'.lang('Connect With Listing').' "  href="'.base_url().'admin/user/connect_listing/'.$record->user_id.'"><span class="fa fa-plug"></span></a>';
                }else{
                    $action .= '<a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-info btn-sm" title="'.lang('Connected with Listing').' "  href="'.base_url().'admin/listings"><span class="fa fa-plug"></span></a>';
                }

                $data[] = array( 
                    "companies_id"=>super_admin_opt_td($record->companies_id, 'no'),
                    "photo"=>'<img style="width: 36px;margin-right: 10px;" src="'.base_url().$account_info->avatar.'" class="img-circle">',
                    "fullname"=>($record->role_id != 2) ? '<a href="'.base_url().'admin/user/user_details/'.$record->user_id.'">'.$account_info->fullname.'</a>' : $account_info->fullname ,
                    "username"=>$record->username,
                    "active"=>$active,
                    "user_type"=>$user_type,
                    "action"=>$action
                );
            } 
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
