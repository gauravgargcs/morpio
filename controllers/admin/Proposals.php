<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Proposals extends Admin_Controller
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

    public function index($action = NULL, $id = NULL, $item_id = NULL)
    {
        $data['page'] = lang('sales');
        $data['sub_active'] = lang('proposals');
        if (!empty($item_id)) {
            $can_edit = $this->proposal_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $id));
            if (!empty($can_edit)) {
                $data['item_info'] = $this->proposal_model->check_by(array('proposals_items_id' => $item_id), 'tbl_proposals_items');
            }
        }
        $companies_id = null;
        if ($action == 'edit_proposals') {
            $data['active'] = 2;
            $can_edit = $this->proposal_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $id));
            if (!empty($can_edit)) {
                $data['proposals_info'] = $this->proposal_model->check_by(array('proposals_id' => $id), 'tbl_proposals');
                if (empty($data['proposals_info'])) {
                    set_message('error', lang('there_in_no_value'));
                    redirect('admin/proposals');
                } else {
                    $companies_id = $data['proposals_info']->companies_id;
                }
            }
            $edit_proposal = array(
                'edit_proposal' => 1,
            );
            $this->session->set_userdata($edit_proposal);
        } else if ($action == 'client' || $action == 'leads') {
            $data['module_id'] = $id;
            $data['module'] = $action;
            $data['active'] = 2;
        } else {
            $data['active'] = 1;
        }
        // get all client
        // $data['all_client'] = by_company('tbl_client', 'client_id', null, $companies_id);
        $data['all_client'] = get_result('tbl_client');
        // get permission user
        // $data['permission_user'] = $this->proposal_model->allowad_user('140');
        $data['permission_user'] = $this->proposal_model->allowad_user('140');
        
        $type = $this->uri->segment(5);
        if (empty($type)) {
            $type = '_' . date('Y');
        }

        if (!empty($type) && !is_numeric($type)) {
            $filterBy = $type;
        } else {
            $filterBy = null;
        }
        // get all proposals
        $data['all_proposals_info'] = $this->proposal_model->get_proposals($filterBy);

        if ($action == 'proposals_details') {
            $data['title'] = lang('proposals') . ' ' . lang('details'); //Page title
            $data['proposals_info'] = $this->proposal_model->check_by(array('proposals_id' => $id), 'tbl_proposals');
            if (empty($data['proposals_info'])) {
                set_message('error', lang('there_in_no_value'));
                redirect('admin/proposals');
            }
            $subview = 'proposals_details';
        } elseif ($action == 'proposals_history') {
            $data['proposals_info'] = $this->proposal_model->check_by(array('proposals_id' => $id), 'tbl_proposals');
            if (empty($data['proposals_info'])) {
                set_message('error', lang('there_in_no_value'));
                redirect('admin/proposals');
            }
            $data['title'] = "proposals History"; //Page title
            $subview = 'proposals_history';
        } elseif ($action == 'email_proposals') {
            $data['proposals_info'] = $this->proposal_model->check_by(array('proposals_id' => $id), 'tbl_proposals');
            if (empty($data['proposals_info'])) {
                set_message('error', lang('there_in_no_value'));
                redirect('admin/proposals');
            }
            $data['title'] = "Email proposals"; //Page title
            $subview = 'email_proposals';
            $data['editor'] = $this->data;
        } elseif ($action == 'pdf_proposals') {
            $data['proposals_info'] = $this->proposal_model->check_by(array('proposals_id' => $id), 'tbl_proposals');
            if (empty($data['proposals_info'])) {
                set_message('error', lang('there_in_no_value'));
                redirect('admin/proposals');
            }
            $data['title'] = "proposals PDF"; //Page title
            $this->load->helper('dompdf');
            $viewfile = $this->load->view('admin/proposals/proposals_pdf', $data, TRUE);
            pdf_create($viewfile, 'proposals  # ' . $data['proposals_info']->reference_no);
        } else {
            $data['title'] = lang('proposals'); //Page title
            if ($action == 'edit_proposals') {
                if($data['proposals_info']->proposal_type == 'presentationwithoutquote') {
                    $subview = 'proposalswithoutquote';
                    $data['active'] = 3;
                    $data['title'] = "Edit Live Proposal";
                } else {
                    $subview = 'proposalswithquote';
                    $data['active'] = 2;
                    $data['title'] = "Edit Standard Proposal";
                }
            }  else if ($action == 'client' || $action == 'leads') {
                    $subview = 'proposals';
                    $data['active'] = 2;
            } else if($action == 'withoutquote') {
                $subview = 'proposalswithoutquote';
                $data['active'] = 3;
                $data['title'] = "Create A Live Proposal";
            } else if($action == 'withquote') {
                $subview = 'proposalswithquote';
                $data['active'] = 2;
                $data['title'] = "Create A Standard Proposal";
            } else {
                $subview = 'proposals';
                $data['active'] = 1;
            }
        }
        $data['unlayer_template_cat'] = $this->getUnlayerTempateCat();
        $data['subview'] = $this->load->view('admin/proposals/' . $subview, $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public function pdf_proposals($id)
    {
        $data['proposals_info'] = $this->proposal_model->check_by(array('proposals_id' => $id), 'tbl_proposals');
        if (empty($data['proposals_info'])) {
            set_message('error', lang('there_in_no_value'));
            redirect('admin/proposals');
        }
        $data['title'] = "proposals PDF"; //Page title
        $this->load->helper('dompdf');
        $viewfile = $this->load->view('admin/proposals/proposals_pdf', $data, TRUE);
        pdf_create($viewfile, 'proposals  # ' . $data['proposals_info']->reference_no);
    }

    public function save_proposals($id = NULL)
    {
        $created = can_action('140', 'created');
        $edited = can_action('140', 'edited');
        if (!empty($created) || !empty($edited)) {
            $data = $this->proposal_model->array_from_post(array('reference_no', 'discount_type', 'discount_percent', 'user_id', 'adjustment', 'discount_total', 'show_quantity_as'));
            $data['proposal_date'] = date('Y-m-d H-i', strtotime($this->input->post('proposal_date', TRUE)));
            if (empty($data['proposal_date'])) {
                $data['proposal_date'] = date('Y-m-d H:i');;
            }
            if (empty($data['discount_total'])) {
            $data['discount_total'] = 0;
            }
            $data['proposal_year'] = date('Y', strtotime($this->input->post('proposal_date', TRUE)));
            $data['proposal_month'] = date('Y-m', strtotime($this->input->post('proposal_date', TRUE)));
            $data['due_date'] = date('Y-m-d H-i', strtotime($this->input->post('due_date', TRUE)));
            
            $data['notes'] = $this->input->post('notes', TRUE);
            $temphtml = $this->input->post('unlayertemplatehtml', false);
            $data['proposal_type'] = $this->input->post('proposal_type', TRUE);
            $data['unlayertemplateid'] = ($this->input->post('unlayertemplateid', TRUE)) ? $this->input->post('unlayertemplateid', TRUE) : null ;
            $data['unlayertemplatejson'] = ($this->input->post('unlayertemplatejson', false)) ? $this->input->post('unlayertemplatejson', false) : null ;
            $data['unlayertemplatehtml'] = ($temphtml && $temphtml != '') ? ($temphtml) : null ;
            
            $tax['tax_name'] = $this->input->post('total_tax_name', TRUE);
            $tax['total_tax'] = $this->input->post('total_tax', TRUE);
            $data['total_tax'] = json_encode($tax);
            $i_tax = 0;
            if (!empty($tax['total_tax'])) {
                foreach ($tax['total_tax'] as $v_tax) {
                    $i_tax += $v_tax;
                }
            }
            $data['tax'] = $i_tax;
            $save_as_draft = $this->input->post('status', TRUE);
            if (!empty($save_as_draft)) {
                $data['status'] = $save_as_draft;
            } else {
                $data['status'] = 'draft';
            }
            $data['module'] = $this->input->post('module', true);
            if (!empty($data['module']) && $data['module'] == 'leads') {
                $data['module_id'] = $this->input->post('leads_id', true);
                $curren = $this->input->post('currency', true);
            } else {
                $data['module_id'] = $this->input->post('client_id', true);
                $currency = $this->proposal_model->client_currency_sambol($data['module_id']);
                if (!empty($currency->code)) {
                    $curren = $currency->code;
                } else {
                    $curren = config_item('default_currency');
                }

            }
            $data['currency'] = $curren;


            $permission = $this->input->post('permission', true);
            if (!empty($permission)) {
                if ($permission == 'everyone') {
                    $assigned = 'all';
                } else {
                    $assigned_to = $this->proposal_model->array_from_post(array('assigned_to'));
                    if (!empty($assigned_to['assigned_to'])) {
                        foreach ($assigned_to['assigned_to'] as $assign_user) {
                            $assigned[$assign_user] = $this->input->post('action_' . $assign_user, true);
                        }
                    }
                }
                if (!empty($assigned)) {
                    if ($assigned != 'all') {
                        $assigned = json_encode($assigned);
                    }
                } else {
                    $assigned = 'all';
                }
                $data['permission'] = $assigned;
            } else {
                set_message('error', lang('assigned_to') . ' Field is required');
                redirect($_SERVER['HTTP_REFERER']);
            }

            // get all client
            $this->proposal_model->_table_name = 'tbl_proposals';
            $this->proposal_model->_primary_key = 'proposals_id';
            if (!empty($id)) {
                $proposals_id = $id;
                $this->proposal_model->save($data, $id);
                $action = ('activity_proposals_updated');
                $msg = lang('proposals_updated');
                $description = 'not_proposal_updated';
            } else {
                $proposals_id = $this->proposal_model->save($data);
                $action = ('activity_proposals_created');
                $msg = lang('proposals_created');
                $description = 'not_proposal_created';
            }
            save_custom_field(11, $proposals_id);

            $removed_items = $this->input->post('removed_items', TRUE);
            if (!empty($removed_items)) {
                foreach ($removed_items as $r_id) {
                    if ($r_id != 'undefined') {
                        $this->db->where('proposals_items_id', $r_id);
                        $this->db->delete('tbl_proposals_items');
                    }
                }
            }
            $items_data = $this->input->post('items', true);

            if (!empty($items_data)) {
                $index = 0;
                foreach ($items_data as $items) {
                    $items['proposals_id'] = $proposals_id;
                    $tax = 0;
                    if (!empty($items['taxname'])) {
                        foreach ($items['taxname'] as $tax_name) {
                            $tax_rate = explode("|", $tax_name);
                            $tax += $tax_rate[1];

                        }
                        $items['item_tax_name'] = $items['taxname'];
                        unset($items['taxname']);
                        $items['item_tax_name'] = json_encode($items['item_tax_name']);
                    }
                    $price = $items['quantity'] * $items['unit_cost'];
                    $items['item_tax_total'] = ($price / 100 * $tax);
                    $items['total_cost'] = $price;
                    // get all client
                    $this->proposal_model->_table_name = 'tbl_proposals_items';
                    $this->proposal_model->_primary_key = 'proposals_items_id';
                    if (!empty($items['proposals_items_id'])) {
                        $items_id = $items['proposals_items_id'];
                        $this->proposal_model->save($items, $items_id);
                    } else {
                        $items_id = $this->proposal_model->save($items);
                    }
                    $index++;
                }
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'proposals',
                'module_field_id' => $proposals_id,
                'activity' => $action,
                'icon' => 'fa-shopping-cart',
                'link' => 'admin/proposals/index/proposals_details/' . $proposals_id,
                'value1' => $data['reference_no']
            );
            $this->proposal_model->_table_name = 'tbl_activities';
            $this->proposal_model->_primary_key = 'activities_id';
            $this->proposal_model->save($activity);

            $remove_proposal = array(
                'remove_proposal' => 1,
            );
            $this->session->set_userdata($remove_proposal);


            // send notification to client
            if (!empty($data['client_id'])) {
                $client_info = $this->proposal_model->check_by(array('client_id' => $data['client_id']), 'tbl_client');
                if (!empty($client_info->primary_contact)) {
                    $notifyUser = array($client_info->primary_contact);
                } else {
                    $user_info = $this->proposal_model->check_by(array('company' => $data['client_id']), 'tbl_account_details');
                    if (!empty($user_info)) {
                        $notifyUser = array($user_info->user_id);
                    }
                }
            }
            if (!empty($notifyUser)) {
                foreach ($notifyUser as $v_user) {
                    if ($v_user != $this->session->userdata('user_id')) {
                        add_notification(array(
                            'to_user_id' => $v_user,
                            'icon' => 'shopping-cart',
                            'description' => $description,
                            'link' => 'client/proposals/index/proposals_details/' . $proposals_id,
                            'value' => $data['reference_no'],
                        ));
                    }
                }
                show_notification($notifyUser);
            }

            // messages for user
            $type = "success";
            $message = $msg;
            set_message($type, $message);
            
            //replace proposal tags to make it dynemic
            $this->replaceproposaltags($proposals_id, $this->input->post('items', true));
            
        }
        redirect('admin/proposals/index/proposals_details/' . $proposals_id);

    }

    public function save_proposals_without_quote($id = NULL) {
        $created = can_action('140', 'created');
        $edited = can_action('140', 'edited');
        if (!empty($created) || !empty($edited)) {
            $data = $this->proposal_model->array_from_post(array('reference_no', 'discount_type', 'user_id', 'show_quantity_as'));
            $data['proposal_date'] = date('Y-m-d H-i', strtotime($this->input->post('proposal_date', TRUE)));
            if (empty($data['proposal_date'])) {
                $data['proposal_date'] = date('Y-m-d H:i');;
            }
            if (empty($data['discount_total'])) {
                $data['discount_total'] = 0;
            }
            $data['proposal_year'] = date('Y', strtotime($this->input->post('proposal_date', TRUE)));
            $data['proposal_month'] = date('Y-m', strtotime($this->input->post('proposal_date', TRUE)));
            $data['due_date'] = date('Y-m-d H-i', strtotime($this->input->post('due_date', TRUE)));
            
            $data['notes'] = $this->input->post('notes', TRUE);
            $temphtml = $this->input->post('unlayertemplatehtml', false);
            $data['proposal_type'] = $this->input->post('proposal_type', TRUE);
            $data['unlayertemplateid'] = ($this->input->post('unlayertemplateid', TRUE)) ? $this->input->post('unlayertemplateid', TRUE) : null ;
            $data['unlayertemplatejson'] = ($this->input->post('unlayertemplatejson', false)) ? $this->input->post('unlayertemplatejson', false) : null ;
            $data['unlayertemplatehtml'] = ($temphtml && $temphtml != '') ? ($temphtml) : null ;
            
            $tax['tax_name'] = $this->input->post('total_tax_name', TRUE);
            $tax['total_tax'] = $this->input->post('total_tax', TRUE);
            $data['total_tax'] = json_encode($tax);
            $i_tax = 0;
            if (!empty($tax['total_tax'])) {
                foreach ($tax['total_tax'] as $v_tax) {
                    $i_tax += $v_tax;
                }
            }
            $data['tax'] = $i_tax;
            $save_as_draft = $this->input->post('status', TRUE);
            if (!empty($save_as_draft)) {
                $data['status'] = $save_as_draft;
            } else {
                $data['status'] = 'draft';
            }
            $data['module'] = $this->input->post('module', true);
            if (!empty($data['module']) && $data['module'] == 'leads') {
                $data['module_id'] = $this->input->post('leads_id', true);
                $curren = $this->input->post('currency', true);
            } else {
                $data['module_id'] = $this->input->post('client_id', true);
                $currency = $this->proposal_model->client_currency_sambol($data['module_id']);
                if (!empty($currency->code)) {
                    $curren = $currency->code;
                } else {
                    $curren = config_item('default_currency');
                }

            }
            $data['currency'] = $curren;


            $permission = $this->input->post('permission', true);
            if (!empty($permission)) {
                if ($permission == 'everyone') {
                    $assigned = 'all';
                } else {
                    $assigned_to = $this->proposal_model->array_from_post(array('assigned_to'));
                    if (!empty($assigned_to['assigned_to'])) {
                        foreach ($assigned_to['assigned_to'] as $assign_user) {
                            $assigned[$assign_user] = $this->input->post('action_' . $assign_user, true);
                        }
                    }
                }
                if (!empty($assigned)) {
                    if ($assigned != 'all') {
                        $assigned = json_encode($assigned);
                    }
                } else {
                    $assigned = 'all';
                }
                $data['permission'] = $assigned;
            } else {
                set_message('error', lang('assigned_to') . ' Field is required');
                redirect($_SERVER['HTTP_REFERER']);
            }

            // get all client
            $this->proposal_model->_table_name = 'tbl_proposals';
            $this->proposal_model->_primary_key = 'proposals_id';
            if (!empty($id)) {
                $proposals_id = $id;
                $this->proposal_model->save($data, $id);
                $action = ('activity_proposals_updated');
                $msg = lang('proposals_updated');
                $description = 'not_proposal_updated';
            } else {
                $proposals_id = $this->proposal_model->save($data);
                $action = ('activity_proposals_created');
                $msg = lang('proposals_created');
                $description = 'not_proposal_created';
            }
            save_custom_field(11, $proposals_id);

            $removed_items = $this->input->post('removed_items', TRUE);
            if (!empty($removed_items)) {
                foreach ($removed_items as $r_id) {
                    if ($r_id != 'undefined') {
                        $this->db->where('proposals_items_id', $r_id);
                        $this->db->delete('tbl_proposals_items');
                    }
                }
            }
            $items_data = $this->input->post('items', true);

            if (!empty($items_data)) {
                $index = 0;
                foreach ($items_data as $items) {
                    $items['proposals_id'] = $proposals_id;
                    $tax = 0;
                    if (!empty($items['taxname'])) {
                        foreach ($items['taxname'] as $tax_name) {
                            $tax_rate = explode("|", $tax_name);
                            $tax += $tax_rate[1];

                        }
                        $items['item_tax_name'] = $items['taxname'];
                        unset($items['taxname']);
                        $items['item_tax_name'] = json_encode($items['item_tax_name']);
                    }
                    $price = $items['quantity'] * $items['unit_cost'];
                    $items['item_tax_total'] = ($price / 100 * $tax);
                    $items['total_cost'] = $price;
                    // get all client
                    $this->proposal_model->_table_name = 'tbl_proposals_items';
                    $this->proposal_model->_primary_key = 'proposals_items_id';
                    if (!empty($items['proposals_items_id'])) {
                        $items_id = $items['proposals_items_id'];
                        $this->proposal_model->save($items, $items_id);
                    } else {
                        $items_id = $this->proposal_model->save($items);
                    }
                    $index++;
                }
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'proposals',
                'module_field_id' => $proposals_id,
                'activity' => $action,
                'icon' => 'fa-shopping-cart',
                'link' => 'admin/proposals/index/proposals_details/' . $proposals_id,
                'value1' => $data['reference_no']
            );
            $this->proposal_model->_table_name = 'tbl_activities';
            $this->proposal_model->_primary_key = 'activities_id';
            $this->proposal_model->save($activity);

            $remove_proposal = array(
                'remove_proposal' => 1,
            );
            $this->session->set_userdata($remove_proposal);


            // send notification to client
            if (!empty($data['client_id'])) {
                $client_info = $this->proposal_model->check_by(array('client_id' => $data['client_id']), 'tbl_client');
                if (!empty($client_info->primary_contact)) {
                    $notifyUser = array($client_info->primary_contact);
                } else {
                    $user_info = $this->proposal_model->check_by(array('company' => $data['client_id']), 'tbl_account_details');
                    if (!empty($user_info)) {
                        $notifyUser = array($user_info->user_id);
                    }
                }
            }
            if (!empty($notifyUser)) {
                foreach ($notifyUser as $v_user) {
                    if ($v_user != $this->session->userdata('user_id')) {
                        add_notification(array(
                            'to_user_id' => $v_user,
                            'icon' => 'shopping-cart',
                            'description' => $description,
                            'link' => 'client/proposals/index/proposals_details/' . $proposals_id,
                            'value' => $data['reference_no'],
                        ));
                    }
                }
                show_notification($notifyUser);
            }

            // messages for user
            $type = "success";
            $message = $msg;
            set_message($type, $message);
            
            //replace proposal tags to make it dynemic
            $this->replaceproposaltags($proposals_id, $this->input->post('items', true));
            
        }
        redirect('admin/proposals/index/proposals_details/' . $proposals_id);

    }
    public function replaceproposaltags($proposals_id, $items_data) {
        $proposals_info = $this->proposal_model->check_by(array('proposals_id' => $proposals_id), 'tbl_proposals');
        
        if ($proposals_info->module == 'client') {
            $client_info = $this->proposal_model->check_by(array('client_id' => $proposals_info->module_id), 'tbl_client');
            $currency = $this->proposal_model->client_currency_sambol($proposals_info->module_id);
            $client_lang = $client_info->language;
        } else if ($proposals_info->module == 'leads') {
            $client_info = $this->proposal_model->check_by(array('leads_id' => $proposals_info->module_id), 'tbl_leads');
            if (!empty($client_info)) {
                $client_info->name = $client_info->contact_name;
                $client_info->zipcode = null;
            }
            $client_lang = 'english';
            $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        } else {
            $client_lang = 'english';
            $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        }
        $language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
        
        $html = $proposals_info->unlayertemplatehtml;
        $html = str_replace("{{proposal_ref}}", $proposals_info->reference_no, $html);
        $html = str_replace("{{proposal_date}}", $proposals_info->proposal_date, $html);
        $html = str_replace("{{due_date}}", $proposals_info->due_date, $html);
        $html = str_replace("{{assigned}}", fullname($proposals_info->user_id), $html);
        $html = str_replace("{{status}}", lang($proposals_info->status), $html);
        $html = str_replace("{{accept_url}}", base_url().'client/proposals/change_status/accepted/'.$proposals_id, $html);
        $html = str_replace("{{decline_url}}", base_url().'client/proposals/change_status/declined/'.$proposals_id, $html);
            
        //company information tag replace
        $html = str_replace("{{company_name}}", (config_item('company_legal_name_' . $client_lang) 
                ? config_item('company_legal_name_' . $client_lang) : config_item('company_legal_name')), $html);
        $html = str_replace("{{company_address}}", (config_item('company_address_' . $client_lang) 
                ? config_item('company_address_' . $client_lang) : config_item('company_address')), $html);
        $html = str_replace("{{company_city}}", (config_item('company_city_' . $client_lang) 
                ? config_item('company_city_' . $client_lang) : config_item('company_city')), $html);
        $html = str_replace("{{company_zip}}", config_item('company_zip_code'), $html);
        $html = str_replace("{{company_country}}", (config_item('company_country_' . $client_lang) 
                ? config_item('company_country_' . $client_lang) : config_item('company_country')), $html);
        $html = str_replace("{{company_phone}}", config_item('company_phone'), $html);
        $html = str_replace("{{company_vat}}", config_item('company_vat'), $html);
        
        if (!empty($client_info)) 
        {
            //client information tag replace
            $html = str_replace("{{client_name}}", $client_info->name, $html);
            $html = str_replace("{{client_address}}", $client_info->address, $html);
            $html = str_replace("{{client_city}}", $client_info->city.' '.$client_info->zipcode, $html);
            $html = str_replace("{{client_country}}", $client_info->country, $html);
            $html = str_replace("{{client_phone}}", $client_info->phone, $html);
            $html = str_replace("{{client_vat}}", $client_info->vat, $html);
        }
        
        //Product Section Tag Replacement
        
        if (!empty($items_data)) {
            $product_html = '<style type="text/css">#producttrow3{display: none !important;}'
                    . '#producttrow2{display: none !important;}#producttrow1{display: none !important;}</style>';
            $items['proposals_id'] = $proposals_id;
            $tax = 0;
            $itemchunks = array_chunk($items_data, 2);
            foreach ($itemchunks as $key => $value) {
                $product_html .= '<div id="producttrow_'.$key.'" class="u_row" style="background-color: rgba(0,0,0,0); padding: 40px 0px 20px;">'
                            . '<div class="container" style="max-width: 1000px;margin: 0 auto;">'
                            . '<div class="u-row">';
                if(!empty($value) && is_array($value[0]) && isset($value[0])) {
                    $price = $value[0]['quantity'] * $value[0]['unit_cost'];
                    $item_tax_total = 0;
                    if (isset($value[0]['taxname']) && !empty($value[0]['taxname'])) {
                        foreach ($value[0]['taxname'] as $tax_name) {
                            $tax_rate = explode("|", $tax_name);
                            $tax += $tax_rate[1];
                        }
                        $item_tax_total = ($price / 100 * $tax);
                    }
                    $total_cost = $price;
                    $itemname = $value[0]['item_name'];
                    $product_html = $this->appendProductimage($product_html, $value[0]['saved_items_id']);
                    $product_html = $this->appendProductDetail($product_html, $item_tax_total, $value[0]['quantity'], $price, $itemname, $currency);
                }
                if(!empty($value) && is_array($value[1]) && isset($value[1])) {
                    $price = $value[1]['quantity'] * $value[1]['unit_cost'];
                    $item_tax_total = 0;
                    if (isset($value[1]['taxname']) && !empty($value[1]['taxname'])) {
                        $tax = 0;
                        foreach ($value[1]['taxname'] as $tax_name) {
                            $tax_rate = explode("|", $tax_name);
                            $tax += $tax_rate[1];
                        }
                        $item_tax_total = ($price / 100 * $tax);
                    }
                    $total_cost = $price;
                    $itemname = $value[1]['item_name'];
                    $product_html = $this->appendProductimage($product_html, $value[1]['saved_items_id']);
                    $product_html = $this->appendProductDetail($product_html, $item_tax_total, $value[1]['quantity'], $price, $itemname, $currency);
                }
                $product_html .= '</div></div></div>';
            }
            $html = str_replace("{{product_section}}", $product_html, $html);
        }
        
        $html = str_replace("{{subtotal}}", display_money($this->proposal_model->proposal_calculation('proposal_cost', $proposals_info->proposals_id), $currency->symbol), $html);
        $html = str_replace("{{discount_percent}}", $proposals_info->discount_percent.'%', $html);
        $html = str_replace("{{discount_amount}}", display_money($this->proposal_model->proposal_calculation('discount', $proposals_info->proposals_id)), $html);
        
        $tax_info = json_decode($proposals_info->total_tax);
        $tax_total = 0;
        if (!empty($tax_info)) {
            $tax_name = $tax_info->tax_name;
            $total_tax = $tax_info->total_tax;
            if (!empty($tax_name)) {
                foreach ($tax_name as $t_key => $v_tax_info) {
                    $tax = explode('|', $v_tax_info);
                    $tax_total += $total_tax[$t_key];
                }
                $html = str_replace("{{tax_name}}", $tax[0].'('.$tax[1].'%)', $html);
                $html = str_replace("{{tax_amount}}", display_money($total_tax[$t_key], $currency->symbol), $html);
            }
        }
        $html = str_replace("{{tax_total}}", display_money($tax_total, $currency->symbol), $html);
        $html = str_replace("{{adjustment}}", display_money($proposals_info->adjustment), $html);
        $html = str_replace("{{net_total}}", display_money($this->proposal_model->proposal_calculation('total', $proposals_info->proposals_id), $currency->symbol), $html);
        $this->proposal_model->_table_name = 'tbl_proposals';
        $this->proposal_model->_primary_key = 'proposals_id';
        $data['unlayertemplatehtml'] = $html;
        $this->proposal_model->save($data, $proposals_id);
    }
    
    public function appendProductimage($product_html, $itemid)
    {
        $proposals_info = $this->proposal_model->check_by(array('saved_items_id' => $itemid), 'tbl_saved_items');
        $prodimage = base_url().'assets/img/user/product.png'; 
        if($proposals_info && isset($proposals_info->product_image)) {
            $prodimage = base_url().$proposals_info->product_image; 
        } 
        $product_html.= '<div id="u_column_37" class="u-col u-col-18p12 u_column">'
                . '<div class="v-col-padding" style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;">'
                . '<div id="u_content_image_7" class="u_content_image" style="overflow-wrap: break-word;padding: 10px;">'
                . '<div style="position:relative;line-height:0px;text-align:center">'
                . '<img alt="" src="'.$prodimage.'" style="width: 100%;max-width: 342px;" title=""/>'
                . '</div></div></div></div>';
        return $product_html;
    }
    
    public function appendProductDetail($product_html, $item_tax_total, $quantity, $price, $itemname, $currency) {
        $product_html.= '<div id="u_column_38" class="u-col u-col-32p26 u_column">'
                . '<div class="v-col-padding" style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;">'
                . '<div id="u_content_text_16" class="u_content_text" style="overflow-wrap: break-word;padding: 10px;">'
                . '<div style="line-height: 140%; text-align: left; word-wrap: break-word;"><p style="font-size: 14px; line-height: 140%;"><span style="font-size: 16px; line-height: 22.4px;"><strong>'.$itemname.'</strong></span></p></div>'
                . '</div>'
                . '<div id="u_content_text_17" class="u_content_text" style="overflow-wrap: break-word;padding: 10px;">'
                . '<div style="line-height: 140%; text-align: left; word-wrap: break-word;"><p style="font-size: 14px; line-height: 140%;"><span style="font-size: 16px; line-height: 22.4px;">'.display_money($price, $currency->symbol).' for '.$quantity.' qty</span></p></div>'
                . '</div>'
                . '<div id="u_content_text_24" class="u_content_text" style="overflow-wrap: break-word;padding: 10px;">'
                . '<div style="line-height: 140%; text-align: left; word-wrap: break-word;"><p style="font-size: 14px; line-height: 140%;"><span style="font-size: 16px; line-height: 22.4px;">Tax - '.$item_tax_total.'</span></p></div>'
                . '</div>'
                . '<div id="u_content_text_25" class="u_content_text" style="overflow-wrap: break-word;padding: 10px;">'
                . '<div style="line-height: 140%; text-align: left; word-wrap: break-word;"><p style="font-size: 14px; line-height: 140%;"><strong><span style="font-size: 16px; line-height: 22.4px;">Total - '.display_money($price + $item_tax_total, $currency->symbol).'</span><br /></strong></p></div>'
                . '</div></div></div>';
        return $product_html;
    }
    
    public function insert_items($proposals_id)
    {
        $edited = can_action('140', 'edited');
        $can_edit = $this->proposal_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $proposals_id));
        if (!empty($can_edit) && !empty($edited)) {
            $data['proposals_id'] = $proposals_id;
            $data['modal_subview'] = $this->load->view('admin/proposals/_modal_insert_items', $data, FALSE);
            $this->load->view('admin/_layout_skote_modal', $data);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function add_insert_items($proposals_id)
    {
        $can_edit = $this->proposal_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $proposals_id));
        $edited = can_action('140', 'edited');
        if (!empty($can_edit) && !empty($edited)) {
            $saved_items_id = $this->input->post('saved_items_id', TRUE);
            if (!empty($saved_items_id)) {
                foreach ($saved_items_id as $v_items_id) {
                    $items_info = $this->proposal_model->check_by(array('saved_items_id' => $v_items_id), 'tbl_saved_items');
                    $tax_info = json_decode($items_info->tax_rates_id);
                    $tax_name = array();
                    if (!empty($tax_info)) {
                        foreach ($tax_info as $v_tax) {
                            $all_tax = $this->db->where('tax_rates_id', $v_tax)->get('tbl_tax_rates')->row();
                            $tax_name[] = $all_tax->tax_rate_name . '|' . $all_tax->tax_rate_percent;

                        }
                    }
                    if (!empty($tax_name)) {
                        $tax_name = $tax_name;
                    } else {
                        $tax_name = array();
                    }

                    $data['quantity'] = 1;
                    $data['proposals_id'] = $proposals_id;
                    $data['item_name'] = $items_info->item_name;
                    $data['item_desc'] = $items_info->item_desc;
                    $data['hsn_code'] = $items_info->hsn_code;
                    $data['unit_cost'] = $items_info->unit_cost;
                    $data['item_tax_rate'] = '0.00';
                    $data['item_tax_name'] = json_encode($tax_name);
                    $data['item_tax_total'] = $items_info->item_tax_total;
                    $data['total_cost'] = $items_info->unit_cost;

                    $this->proposal_model->_table_name = 'tbl_proposals_items';
                    $this->proposal_model->_primary_key = 'proposals_items_id';
                    $items_id = $this->proposal_model->save($data);
                    $action = 'activity_proposal_items_added';
                    $msg = lang('proposals_item_save');
                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'proposals',
                        'module_field_id' => $items_id,
                        'activity' => $action,
                        'icon' => 'fa-shopping-cart',
                        'link' => 'admin/proposals/index/proposals_details/' . $proposals_id,
                        'value1' => $items_info->item_name
                    );
                    $this->proposal_model->_table_name = 'tbl_activities';
                    $this->proposal_model->_primary_key = 'activities_id';
                    $this->proposal_model->save($activity);
                }
                $type = "success";
                $this->update_invoice_tax($saved_items_id, $proposals_id);
            } else {
                $type = "error";
                $msg = 'Please Select a items';
            }
            $message = $msg;
            set_message($type, $message);
            redirect('admin/proposals/index/proposals_details/' . $proposals_id);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    function update_invoice_tax($saved_items_id, $proposals_id)
    {

        $invoice_info = $this->proposal_model->check_by(array('proposals_id' => $proposals_id), 'tbl_proposals');
        $tax_info = json_decode($invoice_info->total_tax);

        $tax_name = $tax_info->tax_name;
        $total_tax = $tax_info->total_tax;
        $invoice_tax = array();
        if (!empty($tax_name)) {
            foreach ($tax_name as $t_key => $v_tax_info) {
                array_push($invoice_tax, array('tax_name' => $v_tax_info, 'total_tax' => $total_tax[$t_key]));
            }
        }
        $all_tax_info = array();
        if (!empty($saved_items_id)) {
            foreach ($saved_items_id as $v_items_id) {
                $items_info = $this->proposal_model->check_by(array('saved_items_id' => $v_items_id), 'tbl_saved_items');

                $tax_info = json_decode($items_info->tax_rates_id);
                if (!empty($tax_info)) {
                    foreach ($tax_info as $v_tax) {
                        $all_tax = $this->db->where('tax_rates_id', $v_tax)->get('tbl_tax_rates')->row();
                        array_push($all_tax_info, array('tax_name' => $all_tax->tax_rate_name . '|' . $all_tax->tax_rate_percent, 'total_tax' => $items_info->unit_cost / 100 * $all_tax->tax_rate_percent));
                    }
                }
            }
        }
        if (!empty($invoice_tax) && is_array($invoice_tax) && !empty($all_tax_info)) {
            $all_tax_info = array_merge($all_tax_info, $invoice_tax);
        }

        $results = array();
        foreach ($all_tax_info as $value) {
            if (!isset($results[$value['tax_name']])) {
                $results[$value['tax_name']] = 0;
            }
            $results[$value['tax_name']] += $value['total_tax'];

        }
        if (!empty($results)) {
            foreach ($results as $key => $value) {
                $structured_results['tax_name'][] = $key;
                $structured_results['total_tax'][] = $value;
            }
            $invoice_data['tax'] = array_sum($structured_results['total_tax']);
            $invoice_data['total_tax'] = json_encode($structured_results);

            $this->proposal_model->_table_name = 'tbl_proposals';
            $this->proposal_model->_primary_key = 'proposals_id';
            $this->proposal_model->save($invoice_data, $proposals_id);
        }
        return true;
    }

    public function add_item($id = NULL)
    {
        $data = $this->proposal_model->array_from_post(array('proposals_id', 'item_order'));
        $can_edit = $this->proposal_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $data['proposals_id']));
        $edited = can_action('140', 'edited');
        if (!empty($can_edit) && !empty($edited)) {
            $quantity = $this->input->post('quantity', TRUE);
            $array_data = $this->proposal_model->array_from_post(array('item_name', 'item_desc', 'item_tax_rate', 'unit_cost'));
            if (!empty($quantity)) {
                foreach ($quantity as $key => $value) {
                    $data['quantity'] = $value;
                    $data['item_name'] = $array_data['item_name'][$key];
                    $data['item_desc'] = $array_data['item_desc'][$key];
                    $data['unit_cost'] = $array_data['unit_cost'][$key];
                    $data['item_tax_rate'] = $array_data['item_tax_rate'][$key];
                    $sub_total = $data['unit_cost'] * $data['quantity'];

                    $data['item_tax_total'] = ($data['item_tax_rate'] / 100) * $sub_total;
                    $data['total_cost'] = $sub_total + $data['item_tax_total'];

                    // get all client
                    $this->proposal_model->_table_name = 'tbl_proposals_items';
                    $this->proposal_model->_primary_key = 'proposals_items_id';
                    if (!empty($id)) {
                        $proposals_items_id = $id;
                        $this->proposal_model->save($data, $id);
                        $action = ('activity_proposals_items_updated');
                    } else {
                        $proposals_items_id = $this->proposal_model->save($data);
                        $action = 'activity_proposals_items_added';
                    }
                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'proposals',
                        'module_field_id' => $proposals_items_id,
                        'activity' => $action,
                        'icon' => 'fa-shopping-cart',
                        'value1' => $data['item_name']
                    );
                    $this->proposal_model->_table_name = 'tbl_activities';
                    $this->proposal_model->_primary_key = 'activities_id';
                    $this->proposal_model->save($activity);
                }
            }
            // messages for user
            $type = "success";
            $message = lang('proposals_item_save');
            set_message($type, $message);
            redirect('admin/proposals/index/proposals_details/' . $data['proposals_id']);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public
    function clone_proposal($proposals_id)
    {
        $edited = can_action('140', 'edited');
        $can_edit = $this->proposal_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $proposals_id));
        if (!empty($can_edit) && !empty($edited)) {
            $data['proposals_info'] = $this->proposal_model->check_by(array('proposals_id' => $proposals_id), 'tbl_proposals');
            if (empty($data['proposals_info'])) {
                set_message('error', lang('there_in_no_value'));
                redirect('admin/proposals');
            }
            // get all client
            $this->proposal_model->_table_name = 'tbl_client';
            $this->proposal_model->_order_by = 'client_id';
            $data['all_client'] = $this->proposal_model->get();

            $data['modal_subview'] = $this->load->view('admin/proposals/_modal_clone_proposals', $data, FALSE);
            $this->load->view('admin/_layout_skote_modal', $data);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public
    function cloned_proposals($id)
    {
        $edited = can_action('140', 'edited');
        $can_edit = $this->proposal_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $id));
        if (!empty($can_edit) && !empty($edited)) {
            if (config_item('increment_proposals_number') == 'FALSE') {
                $this->load->helper('string');
                $reference_no = config_item('proposal_prefix') . ' ' . random_string('nozero', 6);
            } else {
                $reference_no = config_item('proposal_prefix') . ' ' . $this->proposal_model->generate_proposal_number();
            }

            $invoice_info = $this->proposal_model->check_by(array('proposals_id' => $id), 'tbl_proposals');
            $module = $this->input->post('module', true);
            if (empty($module)) {
                $module = $invoice_info->module;
                $module_id = $invoice_info->module_id;
                $currency = $invoice_info->currency;
            } else {
                if ($module == 'leads') {
                    $module_id = $this->input->post('leads_id', true);
                    $currency = $this->input->post('currency', true);
                } else {
                    $module_id = $this->input->post('client_id', true);
                    $currenc = $this->proposal_model->client_currency_sambol($module_id);
                    $currency = $currenc->code;
                }
            }
            // save into invoice table
            $new_invoice = array(
                'reference_no' => $reference_no,
                'subject' => $invoice_info->subject,
                'module' => $module,
                'module_id' => $module_id,
                'proposal_date' => date('Y-m-d H:i', strtotime($this->input->post('proposal_date', TRUE))),
                'proposal_month' => date('Y-m', strtotime($this->input->post('proposal_date', TRUE))),
                'proposal_year' => date('Y', strtotime($this->input->post('proposal_date', TRUE))),
                'due_date' => date('Y-m-d H:i', strtotime($this->input->post('due_date', TRUE))),
                'notes' => $invoice_info->notes,
                'total_tax' => $invoice_info->total_tax,
                'tax' => $invoice_info->tax,
                'discount_type' => $invoice_info->discount_type,
                'discount_percent' => $invoice_info->discount_percent,
                'user_id' => $invoice_info->user_id,
                'adjustment' => $invoice_info->adjustment,
                'discount_total' => $invoice_info->discount_total,
                'show_quantity_as' => $invoice_info->show_quantity_as,
                'currency' => $currency,
                'status' => $invoice_info->status,
                'date_sent' => $invoice_info->date_sent,
                'emailed' => $invoice_info->emailed,
                'show_client' => $invoice_info->show_client,
                'convert' => $invoice_info->convert,
                'convert_module' => $invoice_info->convert_module,
                'convert_module_id' => $invoice_info->convert_module_id,
                'converted_date' => $invoice_info->converted_date,
                'permission' => $invoice_info->permission,
                'unlayertemplateid' => $invoice_info->unlayertemplateid,
                'unlayertemplatejson' => $invoice_info->unlayertemplatejson,
                'unlayertemplatehtml' => $invoice_info->unlayertemplatehtml,
            );
            $this->proposal_model->_table_name = "tbl_proposals"; //table name
            $this->proposal_model->_primary_key = "proposals_id";
            $new_invoice_id = $this->proposal_model->save($new_invoice);

            $invoice_items = $this->db->where('proposals_id', $id)->get('tbl_proposals_items')->result();

            if (!empty($invoice_items)) {
                foreach ($invoice_items as $new_item) {
                    $items = array(
                        'proposals_id' => $new_invoice_id,
                        'item_name' => $new_item->item_name,
                        'item_desc' => $new_item->item_desc,
                        'unit_cost' => $new_item->unit_cost,
                        'quantity' => $new_item->quantity,
                        'item_tax_rate' => $new_item->item_tax_rate,
                        'item_tax_name' => $new_item->item_tax_name,
                        'item_tax_total' => $new_item->item_tax_total,
                        'total_cost' => $new_item->total_cost,
                        'unit' => $new_item->unit,
                        'order' => $new_item->order,
                    );
                    $this->proposal_model->_table_name = "tbl_proposals_items"; //table name
                    $this->proposal_model->_primary_key = "proposals_items_id";
                    $this->proposal_model->save($items);
                }
            }
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'proposals',
                'module_field_id' => $new_invoice_id,
                'activity' => ('activity_clone_proposal'),
                'icon' => 'fa-shopping-cart',
                'link' => 'admin/proposals/index/proposals_details/' . $new_invoice_id,
                'value1' => ' from ' . $invoice_info->reference_no . ' to ' . $reference_no,
            );
            // Update into tbl_project
            $this->proposal_model->_table_name = "tbl_activities"; //table name
            $this->proposal_model->_primary_key = "activities_id";
            $this->proposal_model->save($activities);

            // messages for user
            $type = "success";
            $message = lang('proposals_created');
            set_message($type, $message);
            redirect('admin/proposals/index/proposals_details/' . $new_invoice_id);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function change_status($action, $id)
    {
        $can_edit = $this->proposal_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $id));
        $edited = can_action('140', 'edited');
        if (!empty($can_edit) && !empty($edited)) {
            $where = array('proposals_id' => $id);
            if ($action == 'hide') {
                $data = array('show_client' => 'No');
            } elseif ($action == 'show') {
                $data = array('show_client' => 'Yes');
            } elseif ($action == 'sent') {
                $data = array('emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()), 'status' => 'sent');
            } elseif (!empty($action)) {
                $data = array('status' => $action);
            } else {
                $data = array('show_client' => 'Yes');
            }
            $this->proposal_model->set_action($where, $data, 'tbl_proposals');
            // messages for user
            $type = "success";
            $message = lang('proposals_status', $action);
            set_message($type, $message);
            redirect('admin/proposals/index/proposals_details/' . $id);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public
    function delete($action, $proposals_id, $item_id = NULL)
    {
        $can_delete = $this->proposal_model->can_action('tbl_proposals', 'delete', array('proposals_id' => $proposals_id));
        $deleted = can_action('140', 'deleted');
        if (!empty($can_delete) && !empty($deleted)) {
            if ($action == 'delete_item') {
                $this->proposal_model->_table_name = 'tbl_proposals_items';
                $this->proposal_model->_primary_key = 'proposals_items_id';
                $this->proposal_model->delete($item_id);
            } elseif ($action == 'delete_proposals') {

                $this->proposal_model->_table_name = 'tbl_proposals_items';
                $this->proposal_model->delete_multiple(array('proposals_id' => $proposals_id));

                $this->proposal_model->_table_name = 'tbl_reminders';
                $this->proposal_model->delete_multiple(array('module' => 'proposal', 'module_id' => $proposals_id));

                $this->proposal_model->_table_name = 'tbl_proposals';
                $this->proposal_model->_primary_key = 'proposals_id';
                $this->proposal_model->delete($proposals_id);
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'proposals',
                'module_field_id' => $proposals_id,
                'activity' => ('activity_' . $action),
                'icon' => 'fa-shopping-cart',
                'link' => 'admin/proposals/index/proposals_details/' . $proposals_id,
                'value1' => $action
            );

            $this->proposal_model->_table_name = 'tbl_activities';
            $this->proposal_model->_primary_key = 'activities_id';
            $this->proposal_model->save($activity);
            $type = 'success';

            if ($action == 'delete_item') {
                $text = lang('proposals_item_deleted');
                echo json_encode(array("status" => $type, 'message' => $text));
                exit();
            } else {
                $text = lang('proposals_deleted');
                echo json_encode(array("status" => $type, 'message' => $text));
                exit();
            }
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
            exit();
        }
    }

    public function send_proposals_email($proposals_id, $row = null)
    {
        if (!empty($row)) {
            $proposals_info = $this->proposal_model->check_by(array('proposals_id' => $proposals_id), 'tbl_proposals');
            if ($proposals_info->module == 'client') {
                $client_info = $this->proposal_model->check_by(array('client_id' => $proposals_info->module_id), 'tbl_client');
                $client = $client_info->name;
                $currency = $this->proposal_model->client_currency_sambol($proposals_info->module_id);
            } else if ($proposals_info->module == 'leads') {
                $client_info = $this->proposal_model->check_by(array('leads_id' => $proposals_info->module_id), 'tbl_leads');
                $client = $client_info->contact_name;
                $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
            } else {
                $client = '-';
                $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
            }

            $amount = $this->proposal_model->proposal_calculation('total', $proposals_info->proposals_id);
            $currency = $currency->code;
            $email_template = $this->proposal_model->check_by(array('email_group' => 'proposal_email'), 'tbl_email_templates');
            $message = $email_template->template_body;
            $ref = $proposals_info->reference_no;
            $subject = $email_template->subject;
        } else {
            $message = $this->input->post('message', TRUE);
            $ref = $this->input->post('ref', TRUE);
            $subject = $this->input->post('subject', TRUE);
            $client = $this->input->post('client_name', TRUE);
            $amount = $this->input->post('amount', true);
            $currency = $this->input->post('currency', TRUE);
        }

      //  $client_name = str_replace("{client_name}", $client, $message);
        $client_name = str_replace("{CLIENT}", $client, $message);
        $Ref = str_replace("{PROPOSAL_REF}", $ref, $client_name);
        $Amount = str_replace("{AMOUNT}", $amount, $Ref);
        $Currency = str_replace("{CURRENCY}", $currency, $Amount);
        $unique_url= base_url().'frontend/proposals/'.url_encode($proposals_id); 
        $link = str_replace("{PROPOSAL_LINK}", $unique_url, $Currency);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $link);

        $this->send_email_proposals($proposals_id, $message, $subject); // Email proposals

        $data = array('status' => 'sent', 'emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));

        $this->proposal_model->_table_name = 'tbl_proposals';
        $this->proposal_model->_primary_key = 'proposals_id';
        $this->proposal_model->save($data, $proposals_id);

        // Log Activity
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'proposals',
            'module_field_id' => $proposals_id,
            'activity' => 'activity_proposals_sent',
            'icon' => 'fa-shopping-cart',
            'link' => 'admin/proposals/index/proposals_details/' . $proposals_id,
            'value1' => $ref
        );
        $this->proposal_model->_table_name = 'tbl_activities';
        $this->proposal_model->_primary_key = 'activities_id';
        $this->proposal_model->save($activity);

        $type = 'success';
        $text = lang('proposals_email_sent');
        set_message($type, $text);
        redirect('admin/proposals/index/proposals_details/' . $proposals_id);
    }

    function send_email_proposals($proposals_id, $message, $subject)
    {
        $proposals_info = $this->proposal_model->check_by(array('proposals_id' => $proposals_id), 'tbl_proposals');
        if ($proposals_info->module == 'client') {
            $client_info = $this->proposal_model->check_by(array('client_id' => $proposals_info->module_id), 'tbl_client');
            $email = $client_info->email;
        } else if ($proposals_info->module == 'leads') {
            $client_info = $this->proposal_model->check_by(array('leads_id' => $proposals_info->module_id), 'tbl_leads');
            $email = $client_info->email;
        } else {
            $email = '-';
        }
        $recipient = $email;

        $data['message'] = $message;

        $message = $this->load->view('email_template', $data, TRUE);
        $params = array(
            'recipient' => $recipient,
            'subject' => $subject,
            'message' => $message
        );
        $params['resourceed_file'] = 'uploads/' . lang('proposal') . '_' . $proposals_info->reference_no . '.pdf';
        $params['resourcement_url'] = base_url() . 'uploads/' . lang('proposal') . '_' . $proposals_info->reference_no . '.pdf';

        $this->attach_pdf($proposals_id);

        $this->proposal_model->send_email($params);
        //Delete estimate in tmp folder
        if (is_file('uploads/' . lang('proposal') . '_' . $proposals_info->reference_no . '.pdf')) {
            unlink('uploads/' . lang('proposal') . '_' . $proposals_info->reference_no . '.pdf');
        }
        // send notification to client
        if ($proposals_info->module == 'client') {
            if (!empty($client_info->primary_contact)) {
                $notifyUser = array($client_info->primary_contact);
            } else {
                $user_info = $this->proposal_model->check_by(array('company' => $proposals_info->module_id), 'tbl_account_details');
                if (!empty($user_info)) {
                    $notifyUser = array($user_info->user_id);
                }
            }
            if (!empty($notifyUser)) {
                foreach ($notifyUser as $v_user) {
                    if ($v_user != $this->session->userdata('user_id')) {
                        add_notification(array(
                            'to_user_id' => $v_user,
                            'icon' => 'shopping-cart',
                            'description' => 'not_email_send_alert',
                            'link' => 'client/proposals/index/proposals_details/' . $proposals_id,
                            'value' => lang('estimate') . ' ' . $proposals_info->reference_no,
                        ));
                    }
                }
                show_notification($notifyUser);
            }
        }
    }

    public function attach_pdf($id)
    {
        $data['page'] = lang('proposals');
        $data['sortable'] = true;
        $data['typeahead'] = true;
        $data['proposals_info'] = $this->proposal_model->check_by(array('proposals_id' => $id), 'tbl_proposals');
        $data['title'] = lang('proposals'); //Page title
        $this->load->helper('dompdf');
        $html = $this->load->view('admin/proposals/proposals_pdf', $data, TRUE);
        $result = pdf_create($html, lang('proposal') . '_' . $data['proposals_info']->reference_no, 1, null, true);
        return $result;
    }

    function proposals_email($proposals_id)
    {
        $data['proposals_info'] = $this->proposal_model->check_by(array('proposals_id' => $proposals_id), 'tbl_proposals');
        $proposals_info = $data['proposals_info'];
        $client_info = $this->proposal_model->check_by(array('client_id' => $data['proposals_info']->client_id), 'tbl_client');

        $recipient = $client_info->email;

        $message = $this->load->view('admin/proposals/proposals_pdf', $data, TRUE);

        $data['message'] = $message;

        $message = $this->load->view('email_template', $data, TRUE);
        $params = array(
            'recipient' => $recipient,
            'subject' => '[ ' . config_item('company_name') . ' ]' . ' New proposals' . ' ' . $data['proposals_info']->reference_no,
            'message' => $message
        );
        $params['resourceed_file'] = '';

        $this->proposal_model->send_email($params);

        $data = array('status' => 'sent', 'emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));

        $this->proposal_model->_table_name = 'tbl_proposals';
        $this->proposal_model->_primary_key = 'proposals_id';
        $this->proposal_model->save($data, $proposals_id);

        // Log Activity
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'proposals',
            'module_field_id' => $proposals_id,
            'activity' => 'activity_proposals_sent',
            'icon' => 'fa-shopping-cart',
            'link' => 'admin/proposals/index/proposals_details/' . $proposals_id,
            'value1' => $proposals_info->reference_no
        );
        $this->proposal_model->_table_name = 'tbl_activities';
        $this->proposal_model->_primary_key = 'activities_id';
        $this->proposal_model->save($activity);

        // send notification to client
        if (!empty($client_info->primary_contact)) {
            $notifyUser = array($client_info->primary_contact);
        } else {
            $user_info = $this->proposal_model->check_by(array('company' => $proposals_info->client_id), 'tbl_account_details');
            if (!empty($user_info)) {
                $notifyUser = array($user_info->user_id);
            }
        }
        if (!empty($notifyUser)) {
            foreach ($notifyUser as $v_user) {
                if ($v_user != $this->session->userdata('user_id')) {
                    add_notification(array(
                        'to_user_id' => $v_user,
                        'icon' => 'shopping-cart',
                        'description' => 'not_email_send_alert',
                        'link' => 'client/proposals/index/proposals_details/' . $proposals_id,
                        'value' => lang('estimate') . ' ' . $proposals_info->reference_no,
                    ));
                }
            }
            show_notification($notifyUser);
        }

        $type = 'success';
        $text = lang('proposals_email_sent');
        set_message($type, $text);
        redirect('admin/proposals/index/proposals_details/' . $proposals_id);
    }

    public
    function convert_to($type, $id)
    {

        $data['title'] = lang('convert') . ' ' . lang($type);
        $edited = can_action('140', 'edited');
        $can_edit = $this->proposal_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $id));
        if (!empty($can_edit) && !empty($edited)) {
            // get all client
            $this->proposal_model->_table_name = 'tbl_client';
            $this->proposal_model->_order_by = 'client_id';
            $data['all_client'] = $this->proposal_model->get();
            // get permission user
            $data['permission_user'] = $this->proposal_model->allowad_user('140');
            $data['proposals_info'] = $this->proposal_model->check_by(array('proposals_id' => $id), 'tbl_proposals');

            if (empty($data['proposals_info'])) {
                set_message('error', lang('there_in_no_value'));
                redirect('admin/proposals');
            }

            $data['modal_subview'] = $this->load->view('admin/proposals/convert_to_' . $type, $data, FALSE);
            $this->load->view('admin/_layout_skote_modal_extra_lg', $data);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function converted_to_invoice($proposal_id)
    {
        plan_capability('invoice');
        $data = $this->proposal_model->array_from_post(array('reference_no', 'client_id', 'project_id', 'discount_type', 'discount_percent', 'user_id', 'adjustment', 'discount_total', 'show_quantity_as'));

        $data['allow_paypal'] = ($this->input->post('allow_paypal') == 'Yes') ? 'Yes' : 'No';
        $data['allow_stripe'] = ($this->input->post('allow_stripe') == 'Yes') ? 'Yes' : 'No';
        $data['allow_2checkout'] = ($this->input->post('allow_2checkout') == 'Yes') ? 'Yes' : 'No';
        $data['allow_authorize'] = ($this->input->post('allow_authorize') == 'Yes') ? 'Yes' : 'No';
        $data['allow_ccavenue'] = ($this->input->post('allow_ccavenue') == 'Yes') ? 'Yes' : 'No';
        $data['allow_braintree'] = ($this->input->post('allow_braintree') == 'Yes') ? 'Yes' : 'No';
        $data['allow_mollie'] = ($this->input->post('allow_mollie') == 'Yes') ? 'Yes' : 'No';
        $data['allow_payumoney'] = ($this->input->post('allow_payumoney') == 'Yes') ? 'Yes' : 'No';
        $data['client_visible'] = ($this->input->post('client_visible') == 'Yes') ? 'Yes' : 'No';
        $data['invoice_date'] = date('Y-m-d H:i', strtotime($this->input->post('invoice_date', TRUE)));
        if (empty($data['invoice_date'])) {
            $data['invoice_date'] = date('Y-m-d H:i');
        }
        if (empty($data['discount_total'])) {
            $data['discount_total'] = 0;
        }
        $data['invoice_year'] = date('Y', strtotime($this->input->post('invoice_date', TRUE)));
        $data['invoice_month'] = date('Y-m', strtotime($this->input->post('invoice_date', TRUE)));
        $data['due_date'] = date('Y-m-d H:i', strtotime($this->input->post('due_date', TRUE)));
        $data['notes'] = $this->input->post('notes', TRUE);
        $tax['tax_name'] = $this->input->post('total_tax_name', TRUE);
        $tax['total_tax'] = $this->input->post('total_tax', TRUE);
        $data['total_tax'] = json_encode($tax);
        $i_tax = 0;
        if (!empty($tax['total_tax'])) {
            foreach ($tax['total_tax'] as $v_tax) {
                $i_tax += $v_tax;
            }
        }
        $data['tax'] = $i_tax;
        $save_as_draft = $this->input->post('save_as_draft', TRUE);
        if (!empty($save_as_draft)) {
            $data['status'] = 'draft';
        }
        $currency = $this->proposal_model->client_currency_sambol($data['client_id']);
        if (!empty($currency->code)) {
            $curren = $currency->code;
        } else {
            $curren = config_item('default_currency');
        }
        $data['currency'] = $curren;

        $permission = $this->input->post('permission', true);
        if (!empty($permission)) {
            if ($permission == 'everyone') {
                $assigned = 'all';
            } else {
                $assigned_to = $this->proposal_model->array_from_post(array('assigned_to'));
                if (!empty($assigned_to['assigned_to'])) {
                    foreach ($assigned_to['assigned_to'] as $assign_user) {
                        $assigned[$assign_user] = $this->input->post('action_' . $assign_user, true);
                    }
                }
            }
            if (!empty($assigned)) {
                if ($assigned != 'all') {
                    $assigned = json_encode($assigned);
                }
            } else {
                $assigned = 'all';
            }
            $data['permission'] = $assigned;
        } else {
            set_message('error', lang('assigned_to') . ' Field is required');
            redirect($_SERVER['HTTP_REFERER']);
        }

        // get all client
        $this->proposal_model->_table_name = 'tbl_invoices';
        $this->proposal_model->_primary_key = 'invoices_id';

        $invoice_id = $this->proposal_model->save($data);
        $recuring_frequency = $this->input->post('recuring_frequency', TRUE);

        if (!empty($recuring_frequency) && $recuring_frequency != 'none') {
            $recur_data = $this->proposal_model->array_from_post(array('recur_start_date', 'recur_end_date'));
            if(!empty($recur_data['recur_start_date'])){
                $recur_data['recur_start_date']=date('Y-m-d H:i', strtotime($recur_data['recur_start_date']));
            }

            if(!empty($recur_data['recur_end_date'])){
                $recur_data['recur_end_date']=date('Y-m-d H:i', strtotime($recur_data['recur_end_date']));
            }
            $recur_data['recuring_frequency'] = $recuring_frequency;
            $this->get_recuring_frequency($invoice_id, $recur_data); // set recurring
        }
        // save items
        $qty_calculation = config_item('qty_calculation_from_items');
        // save items
        $invoices_to_merge = $this->input->post('invoices_to_merge', TRUE);
        $cancel_merged_invoices = $this->input->post('cancel_merged_invoices', TRUE);
        if (!empty($invoices_to_merge)) {
            foreach ($invoices_to_merge as $inv_id) {
                if (empty($cancel_merged_invoices)) {
                    if (!empty($qty_calculation) && $qty_calculation == 'Yes') {
                        $all_items_info = $this->db->where('invoices_id', $inv_id)->get('tbl_items')->result();
                        if (!empty($all_items_info)) {
                            foreach ($all_items_info as $v_items) {
                                $this->return_items($v_items->items_id);
                            }
                        }
                    }
                    $this->db->where('invoices_id', $inv_id);
                    $this->db->delete('tbl_invoices');

                    $this->db->where('invoices_id', $inv_id);
                    $this->db->delete('tbl_items');

                } else {
                    $mdata = array('status' => 'Cancelled');
                    $this->proposal_model->_table_name = 'tbl_invoices';
                    $this->proposal_model->_primary_key = 'invoices_id';
                    $this->proposal_model->save($mdata, $inv_id);
                }
            }
        }

        $removed_items = $this->input->post('removed_items', TRUE);
        if (!empty($removed_items)) {
            foreach ($removed_items as $r_id) {
                if ($r_id != 'undefined') {
                    if (!empty($qty_calculation) && $qty_calculation == 'Yes') {
                        $this->return_items($r_id);
                    }

                    $this->db->where('items_id', $r_id);
                    $this->db->delete('tbl_items');
                }
            }
        }

        $items_data = $this->input->post('items', true);

        if (!empty($items_data)) {
            $index = 0;
            foreach ($items_data as $items) {
                $items['invoices_id'] = $invoice_id;
                $tax = 0;
                if (!empty($items['taxname'])) {
                    foreach ($items['taxname'] as $tax_name) {
                        $tax_rate = explode("|", $tax_name);
                        $tax += $tax_rate[1];

                    }
                    $items['item_tax_name'] = $items['taxname'];
                    unset($items['taxname']);
                    $items['item_tax_name'] = json_encode($items['item_tax_name']);
                }
                if (empty($items['saved_items_id'])) {
                    $items['saved_items_id'] = 0;
                }
                if (!empty($qty_calculation) && $qty_calculation == 'Yes') {
                    if (!empty($items['saved_items_id']) && $items['saved_items_id'] != 'undefined') {
                        $this->proposal_model->reduce_items($items['saved_items_id'], $items['quantity']);
                    }
                }
                $price = $items['quantity'] * $items['unit_cost'];
                $items['item_tax_total'] = ($price / 100 * $tax);
                $items['total_cost'] = $price;
                // get all client
                $this->proposal_model->_table_name = 'tbl_items';
                $this->proposal_model->_primary_key = 'items_id';
                $this->proposal_model->save($items);
                if (!empty($items['items_id'])) {
                    $items_id = $items['items_id'];
                    if (!empty($qty_calculation) && $qty_calculation == 'Yes') {
                        $this->check_existing_qty($items_id, $items['quantity']);
                    }
                }
                $index++;
            }
        }
        $remove_invoice = array(
            'remove_invoice' => 1,
        );
        $this->session->set_userdata($remove_invoice);

        $p_data = array('status' => 'accepted', 'convert' => 'Yes', 'convert_module' => 'invoice', 'convert_module_id' => $invoice_id);

        $this->proposal_model->_table_name = 'tbl_proposals';
        $this->proposal_model->_primary_key = 'proposals_id';
        $this->proposal_model->save($p_data, $proposal_id);

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'proposals',
            'module_field_id' => $invoice_id,
            'activity' => 'convert_to_invoice_from_proposal',
            'icon' => 'fa-shopping-cart',
            'link' => 'admin/proposals/index/proposals_details/' . $proposal_id,
            'value1' => $data['reference_no']
        );
        $this->proposal_model->_table_name = 'tbl_activities';
        $this->proposal_model->_primary_key = 'activities_id';
        $this->proposal_model->save($activity);

        // send notification to client
        if (!empty($data['client_id'])) {
            $client_info = $this->proposal_model->check_by(array('client_id' => $data['client_id']), 'tbl_client');
            if (!empty($client_info->primary_contact)) {
                $notifyUser = array($client_info->primary_contact);
            } else {
                $user_info = $this->proposal_model->check_by(array('company' => $data['client_id']), 'tbl_account_details');
                if (!empty($user_info)) {
                    $notifyUser = array($user_info->user_id);
                }
            }
        }
        if (!empty($notifyUser)) {
            foreach ($notifyUser as $v_user) {
                if ($v_user != $this->session->userdata('user_id')) {
                    add_notification(array(
                        'to_user_id' => $v_user,
                        'icon' => 'shopping-cart',
                        'description' => 'proposal_convert_to_invoice',
                        'link' => 'client/invoice/manage_invoice/invoice_details/' . $invoice_id,
                        'value' => $data['reference_no'],
                    ));
                }
            }
            show_notification($notifyUser);
        }
        // messages for user
        $type = "success";
        $message = lang('convert_to_invoice') . ' ' . lang('successfully');
        set_message($type, $message);
        redirect('admin/proposals/index/proposals_details/' . $proposal_id);
    }

    function return_items($items_id)
    {
        $items_info = $this->db->where('items_id', $items_id)->get('tbl_items')->row();
        if (!empty($items_info->saved_items_id)) {
            $this->proposal_model->return_items($items_info->saved_items_id, $items_info->quantity);
        }
        return true;

    }

    function check_existing_qty($items_id, $qty)
    {
        $items_info = $this->db->where('items_id', $items_id)->get('tbl_items')->row();
        if (!empty($items_info)) {
            if ($items_info->quantity != $qty) {
                if ($qty > $items_info->quantity) {
                    $reduce_qty = $qty - $items_info->quantity;
                    if (!empty($items_info->saved_items_id)) {
                        $this->proposal_model->reduce_items($items_info->saved_items_id, $reduce_qty);
                    }
                }
                if ($qty < $items_info->quantity) {
                    $return_qty = $items_info->quantity - $qty;
                    if (!empty($items_info->saved_items_id)) {
                        $this->proposal_model->return_items($items_info->saved_items_id, $return_qty);
                    }
                }
            }
        }
        return true;

    }

    function get_recuring_frequency($invoices_id, $recur_data)
    {
        $recur_days = $this->get_calculate_recurring_days($recur_data['recuring_frequency']);
        $due_date = $this->proposal_model->get_table_field('tbl_invoices', array('invoices_id' => $invoices_id), 'due_date');

        $next_date = date("Y-m-d H:i", strtotime($due_date . "+ " . $recur_days . " days"));

        if ($recur_data['recur_end_date'] == '') {
            $recur_end_date = '0000-00-00 00:00';
        } else {
            $recur_end_date = date('Y-m-d H:i', strtotime($recur_data['recur_end_date']));
        }
        $update_invoice = array(
            'recurring' => 'Yes',
            'recuring_frequency' => $recur_days,
            'recur_frequency' => $recur_data['recuring_frequency'],
            'recur_start_date' => date('Y-m-d H:i', strtotime($recur_data['recur_start_date'])),
            'recur_end_date' => $recur_end_date,
            'recur_next_date' => $next_date
        );
        $this->proposal_model->_table_name = 'tbl_invoices';
        $this->proposal_model->_primary_key = 'invoices_id';
        $this->proposal_model->save($update_invoice, $invoices_id);
        return TRUE;
    }

    function get_calculate_recurring_days($recuring_frequency)
    {
        switch ($recuring_frequency) {
            case '7D':
                return 7;
                break;
            case '1M':
                return 31;
                break;
            case '3M':
                return 90;
                break;
            case '6M':
                return 182;
                break;
            case '1Y':
                return 365;
                break;
        }
    }

    public function converted_to_estimate($proposal_id)
    {
        $data = $this->proposal_model->array_from_post(array('reference_no', 'client_id', 'project_id', 'discount_type', 'discount_percent', 'user_id', 'adjustment', 'discount_total', 'show_quantity_as'));

        $data['client_visible'] = ($this->input->post('client_visible') == 'Yes') ? 'Yes' : 'No';
        $data['estimate_date'] = date('Y-m-d H:i', strtotime($this->input->post('estimate_date', TRUE)));
        if (empty($data['estimate_date'])) {
            $data['estimate_date'] = date('Y-m-d H:i');
        }
        if (empty($data['discount_total'])) {
            $data['discount_total'] = 0;
        }
        $data['estimate_year'] = date('Y', strtotime($this->input->post('estimate_date', TRUE)));
        $data['estimate_month'] = date('Y-m', strtotime($this->input->post('estimate_date', TRUE)));
        $data['due_date'] = date('Y-m-d H:i', strtotime($this->input->post('due_date', TRUE)));
        $data['notes'] = $this->input->post('notes', TRUE);
        $tax['tax_name'] = $this->input->post('total_tax_name', TRUE);
        $tax['total_tax'] = $this->input->post('total_tax', TRUE);
        $data['total_tax'] = json_encode($tax);
        $i_tax = 0;
        if (!empty($tax['total_tax'])) {
            foreach ($tax['total_tax'] as $v_tax) {
                $i_tax += $v_tax;
            }
        }
        $data['tax'] = $i_tax;
        $save_as_draft = $this->input->post('status', TRUE);
        if (!empty($save_as_draft)) {
            $data['status'] = $save_as_draft;
        } else {
            $data['status'] = 'pending';
        }
        $currency = $this->proposal_model->client_currency_sambol($data['client_id']);
        if (!empty($currency->code)) {
            $curren = $currency->code;
        } else {
            $curren = config_item('default_currency');
        }
        $data['currency'] = $curren;

        $permission = $this->input->post('permission', true);
        if (!empty($permission)) {
            if ($permission == 'everyone') {
                $assigned = 'all';
            } else {
                $assigned_to = $this->proposal_model->array_from_post(array('assigned_to'));
                if (!empty($assigned_to['assigned_to'])) {
                    foreach ($assigned_to['assigned_to'] as $assign_user) {
                        $assigned[$assign_user] = $this->input->post('action_' . $assign_user, true);
                    }
                }
            }
            if (!empty($assigned)) {
                if ($assigned != 'all') {
                    $assigned = json_encode($assigned);
                }
            } else {
                $assigned = 'all';
            }
            $data['permission'] = $assigned;
        } else {
            set_message('error', lang('assigned_to') . ' Field is required');
            redirect($_SERVER['HTTP_REFERER']);
        }
        // get all client
        $this->proposal_model->_table_name = 'tbl_estimates';
        $this->proposal_model->_primary_key = 'estimates_id';
        if (!empty($id)) {
            $estimates_id = $id;
            $this->proposal_model->save($data, $id);
        } else {
            $estimates_id = $this->proposal_model->save($data);
        }
        // save items
        $invoices_to_merge = $this->input->post('invoices_to_merge', TRUE);
        $cancel_merged_invoices = $this->input->post('cancel_merged_estimate', TRUE);
        if (!empty($invoices_to_merge)) {
            foreach ($invoices_to_merge as $inv_id) {
                if (empty($cancel_merged_invoices)) {
                    $this->db->where('estimates_id', $inv_id);
                    $this->db->delete('tbl_estimates');

                    $this->db->where('estimate_items_id', $inv_id);
                    $this->db->delete('tbl_estimate_items');

                } else {
                    $mdata = array('status' => 'cancelled');
                    $this->proposal_model->_table_name = 'tbl_estimates';
                    $this->proposal_model->_primary_key = 'estimates_id';
                    $this->proposal_model->save($mdata, $inv_id);
                }
            }
        }

        $removed_items = $this->input->post('removed_items', TRUE);
        if (!empty($removed_items)) {
            foreach ($removed_items as $r_id) {
                if ($r_id != 'undefined') {
                    $this->db->where('estimate_items_id', $r_id);
                    $this->db->delete('tbl_estimate_items');
                }
            }
        }
        $items_data = $this->input->post('items', true);

        if (!empty($items_data)) {
            $index = 0;
            foreach ($items_data as $items) {
                $items['estimates_id'] = $estimates_id;
                if (!empty($items['taxname'])) {
                    $tax = 0;
                    foreach ($items['taxname'] as $tax_name) {
                        $tax_rate = explode("|", $tax_name);
                        $tax += $tax_rate[1];

                    }
                    $price = $items['quantity'] * $items['unit_cost'];
                    $items['item_tax_total'] = ($price / 100 * $tax);
                    $items['total_cost'] = $price;

                    $items['item_tax_name'] = $items['taxname'];
                    unset($items['taxname']);
                    $items['item_tax_name'] = json_encode($items['item_tax_name']);
                }
                // get all client
                $this->proposal_model->_table_name = 'tbl_estimate_items';
                $this->proposal_model->_primary_key = 'estimate_items_id';
                if (!empty($items['estimate_items_id'])) {
                    $items_id = $items['estimate_items_id'];
                    $this->proposal_model->save($items, $items_id);
                } else {
                    $items_id = $this->proposal_model->save($items);
                }
                $index++;
            }
        }

        $remove_estimate = array(
            'remove_estimate' => 1,
        );
        $this->session->set_userdata($remove_estimate);

        $p_data = array('status' => 'accepted', 'convert' => 'Yes', 'convert_module' => 'estimate', 'convert_module_id' => $estimates_id);
        $this->proposal_model->_table_name = 'tbl_proposals';
        $this->proposal_model->_primary_key = 'proposals_id';
        $this->proposal_model->save($p_data, $proposal_id);

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'proposals',
            'module_field_id' => $estimates_id,
            'activity' => 'convert_to_estimate_from_proposal',
            'icon' => 'fa-shopping-cart',
            'link' => 'admin/proposals/index/proposals_details/' . $proposal_id,
            'value1' => $data['reference_no']
        );
        $this->proposal_model->_table_name = 'tbl_activities';
        $this->proposal_model->_primary_key = 'activities_id';
        $this->proposal_model->save($activity);

        // send notification to client
        if (!empty($data['client_id'])) {
            $client_info = $this->proposal_model->check_by(array('client_id' => $data['client_id']), 'tbl_client');
            if (!empty($client_info->primary_contact)) {
                $notifyUser = array($client_info->primary_contact);
            } else {
                $user_info = $this->proposal_model->check_by(array('company' => $data['client_id']), 'tbl_account_details');
                if (!empty($user_info)) {
                    $notifyUser = array($user_info->user_id);
                }
            }
        }
        if (!empty($notifyUser)) {
            foreach ($notifyUser as $v_user) {
                if ($v_user != $this->session->userdata('user_id')) {
                    add_notification(array(
                        'to_user_id' => $v_user,
                        'icon' => 'shopping-cart',
                        'description' => 'proposal_convert_to_estimate',
                        'link' => 'client/estimates/index/estimates_details/' . $estimates_id,
                        'value' => $data['reference_no'],
                    ));
                }
            }
            show_notification($notifyUser);
        }
        // messages for user
        $type = "success";
        $message = lang('convert_to_estimate') . ' ' . lang('successfully');
        set_message($type, $message);
        redirect('admin/proposals/index/proposals_details/' . $proposal_id);
    }

    public function getUnlayerTempateCat() {
        $templatecat = get_old_result_group([], 'tbl_unlayer_template_category', 'category_name');
        if(!empty($templatecat)) {
            $unlayercat = [];
            foreach($templatecat as $key => $val) {
                $templateidsarr = get_old_result_group(['category_name' => $val['category_name']], 'tbl_unlayer_template_category', null, 'unlayer_template_id');
                $idstring = '';
                foreach ($templateidsarr as $key => $value) {
                    $idstring .= $value['unlayer_template_id'] . ' ';
                }
                 $unlayercat[$val['category_name']] = ['templateid' => trim($idstring), 'templatecount' => count($templateidsarr)];
            }
            return $unlayercat;
        } else {
            return false;
        }
    }

    public function template_list()
    {
        if(!is_subdomain() && admin()){
            $data['title'] = 'Templates';
            $data['template_list']  = $this->proposal_model->get_result_group([], 'tbl_unlayer_template_category', 'category_name');
            $data['subview'] = $this->load->view('admin/proposals/category_list' , $data, TRUE);
            $this->load->view('admin/_layout_skote_main', $data); //page load
        }else{
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
     public function template_add()
    {
        $id = $this->input->get('cname');
        if(is_subdomain() || !admin()){
             redirect($_SERVER['HTTP_REFERER']);
        }

    if($_POST){
         $id = $this->input->post('id');
         $data = $this->proposal_model->array_from_post(array('category_name', 'unlayer_template_id'));

         $unlayer_template_ids = $this->input->post('unlayer_template_id');
         // var_dump($unlayer_template_ids);die();
         if($id){

             $this->db->where('category_name', $id);
            $this->db->delete('tbl_unlayer_template_category');
         }
         foreach ($unlayer_template_ids as $key => $template_id) {
            $data_insert['category_name'] = $this->input->post('category_name');
            $data_insert['unlayer_template_id'] = $template_id;
               $this->proposal_model->_table_name = 'tbl_unlayer_template_category';
            $this->proposal_model->_primary_key = "id";
            $return_id = $this->proposal_model->save($data_insert);
         }
  
     
       
        if (!empty($id)) {
            $id = $id;
            $action = ('activity_added_new_template');
        } else {
            $id = $return_id;
            $action = ('activity_update_template');
        }
    

        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'proposal',
            'module_field_id' => $id,
            'activity' => $action,
            'icon' => 'fa-ticket',
            'value1' => $this->input->post('category_name')
        );
        $this->proposal_model->_table_name = 'tbl_activities';
        $this->proposal_model->_primary_key = "activities_id";
        $this->proposal_model->save($activities);
        // messages for user
        $type = "success";
        $message = lang('template_updated');
        set_message($type, $message);
      
         redirect('admin/proposals/template_list');
        
        }


        $data['title'] = 'Category';
        $data['template_list']  = $this->proposal_model->get_result(['category_name'=>$id], 'tbl_unlayer_template_category');
         $data['unlayer_template_list']  = getUnlayerTempateList();

       
       $data['id']= $id;
        $data['subview'] = $this->load->view('admin/proposals/category_add' , $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }
    function delete_template()
    {
         if(is_subdomain() || !admin()){
            exit();
        }
         $id = $this->input->get('cname');

          $deleted = can_action('140', 'deleted');
          
            $this->db->where('category_name', $id);
            $this->db->delete('tbl_unlayer_template_category');
         $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'proposal',
                'module_field_id' => $id,
                'activity' => ('activity_delete_template_category'),
                'icon' => 'fa fa-truck',
                'value1' => $id,

            );
            $this->proposal_model->_table_name = 'tbl_activities';
            $this->proposal_model->_primary_key = 'activities_id';
            $this->proposal_model->save($activity);

               echo json_encode(array("status" => 'success', 'message' => lang('delete_template')));
        exit();
    }
}
