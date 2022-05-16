<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('purchase_model');
        $this->load->library('gst');
    }

    public function supplier($id = NULL)
    {
        $data['title'] = lang('manage') . ' ' . lang('supplier');
        if ($id) {
            $data['active'] = 2;
            $can_edit = $this->purchase_model->can_action('tbl_suppliers', 'edit', array('supplier_id' => $id));
            $edited = can_action('149', 'edited');
            if (!empty($can_edit) && !empty($edited)) {
                $data['supplier_info'] = $this->purchase_model->check_by(array('supplier_id' => $id), 'tbl_suppliers');
            }
        } else {
            $data['active'] = 1;
        }
        $data['permission_user'] = $this->purchase_model->allowad_user('149');
        // get all country
        $this->purchase_model->_table_name = "tbl_countries"; //table name
        $this->purchase_model->_order_by = "id";
        $data['countries'] = $this->purchase_model->get();

        $data['all_supplier'] = $this->purchase_model->get_permission('tbl_suppliers');

        $data['subview'] = $this->load->view('admin/purchase/manage_supplier', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public function new_supplier()
    {
        $data['title'] = lang('new') . ' ' . lang('supplier');
        $data['permission_user'] = $this->purchase_model->allowad_user('149');
        // get all country
        $this->purchase_model->_table_name = "tbl_countries"; //table name
        $this->purchase_model->_order_by = "id";
        $data['countries'] = $this->purchase_model->get();

        $data['subview'] = $this->load->view('admin/purchase/new_supplier', $data, FALSE);
        $this->load->view('admin/_layout_skote_modal_extra_lg', $data);
    }

    public function save_supplier($id = NULL)
    {
        $created = can_action('149', 'created');
        $edited = can_action('149', 'edited');
        if (!empty($created) && !empty($edited)) {

            if (!empty($id) && $id == 'inline') {
                $id = null;
                $inline = true;
            }
            $this->purchase_model->_table_name = 'tbl_suppliers';
            $this->purchase_model->_primary_key = 'supplier_id';

            $data = $this->purchase_model->array_from_post(array('name', 'email', 'phone', 'address', 'city', 'state', 'zip_code', 'country', 'tax'));

            // update root category
            $where = array('email' => $data['email']);
            // duplicate value check in DB
            if (!empty($id)) { // if id exist in db update data
                $supplier_id = array('supplier_id !=' => $id);
            } else { // if id is not exist then set id as null
                $supplier_id = null;
            }
            // check whether this input data already exist or not
            $check_account = $this->purchase_model->check_update('tbl_suppliers', $where, $supplier_id);
            if (!empty($check_account)) { // if input data already exist show error alert
                // massage for user
                $type = 'error';
                $msg = "<strong style='color:#000'>" . $data['email'] . '</strong>  ' . lang('already_exist');
            } else { // save and update query
                $permission = $this->input->post('permission', true);
                if (!empty($permission)) {
                    if ($permission == 'everyone') {
                        $assigned = 'all';
                    } else {
                        $assigned_to = $this->purchase_model->array_from_post(array('assigned_to'));
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
                $return_id = $this->purchase_model->save($data, $id);
                if (!empty($id)) {
                    $id = $id;
                    $action = 'update_supplier';
                    $msg = lang('update_supplier');
                } else {
                    $id = $return_id;
                    $action = 'save_supplier';
                    $msg = lang('save_supplier');
                }
                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'account',
                    'module_field_id' => $id,
                    'activity' => $action,
                    'icon' => 'fa-circle-o',
                    'value1' => $data['name']
                );
                $this->purchase_model->_table_name = 'tbl_activities';
                $this->purchase_model->_primary_key = 'activities_id';
                $this->purchase_model->save($activity);
                // messages for user
                $type = "success";
            }
            if (!empty($inline)) {
                if (!empty($return_id)) {
                    $result = array(
                        'id' => $id,
                        'name' => $data['name'],
                        'status' => $type,
                        'message' => $msg,
                    );
                } else {
                    $result = array(
                        'status' => $type,
                        'message' => $msg,
                    );
                }
                echo json_encode($result);
                exit();
            } else {
                $message = $msg;
                set_message($type, $message);
            }
        }
        redirect('admin/purchase/supplier');
    }

    public function manage_purchase($id = NULL)
    {
        $data['title'] = lang('manage') . ' ' . lang('purchase');
        if ($id) {

            $data['active'] = 2;
            $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $id));
            $edited = can_action('150', 'edited');
            if (!empty($can_edit) && !empty($edited)) {
                $data['purchase_info'] = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchases');
            }
            $edit_purchase = array(
                'edit_purchase' => 1,
            );
            $this->session->set_userdata($edit_purchase);
        } else {
            $data['active'] = 1;
        }

        $data['dropzone'] = true;
        $data['all_purchases'] = $this->purchase_model->get_permission('tbl_purchases');
        $data['permission_user'] = $this->purchase_model->allowad_user('150');
        $data['all_supplier'] = $this->purchase_model->get_permission('tbl_suppliers');
        $data['subview'] = $this->load->view('admin/purchase/manage_purchase', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public function save_purchase($id = NULL)
    {

        $data = $this->purchase_model->array_from_post(array('reference_no', 'supplier_id', 'status', 'discount_type', 'discount_percent', 'user_id', 'adjustment', 'discount_total', 'show_quantity_as'));

        $data['update_stock'] = ($this->input->post('update_stock') == 'Yes') ? 'Yes' : 'No';
        $data['purchase_date'] = date('Y-m-d H:i', strtotime($this->input->post('purchase_date', TRUE)));
        if (empty($data['purchase_date'])) {
            $data['purchase_date'] = date('Y-m-d H:i');
        }
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

        $upload_file = array();
        $files = $this->input->post("files", true);
        $target_path = getcwd() . "/uploads/";
        //process the fiiles which has been uploaded by dropzone
        if (!empty($files) && is_array($files)) {
            foreach ($files as $key => $file) {
                if (!empty($file)) {
                    $file_name = $this->input->post('file_name_' . $file);
                    $new_file_name = move_temp_file($file_name, $target_path);
                    $file_ext = explode(".", $new_file_name);
                    $is_image = check_image_extension($new_file_name);
                    $size = $this->input->post('file_size_' . $file) / 1000;
                    if ($new_file_name) {
                        $up_data = array(
                            "fileName" => $new_file_name,
                            "path" => "uploads/" . $new_file_name,
                            "fullPath" => getcwd() . "/uploads/" . $new_file_name,
                            "ext" => '.' . end($file_ext),
                            "size" => round($size, 2),
                            "is_image" => $is_image,
                        );
                        array_push($upload_file, $up_data);
                    }
                }
            }
        }

        $fileName = $this->input->post('fileName');
        $path = $this->input->post('path');
        $fullPath = $this->input->post('fullPath');
        $size = $this->input->post('size');
        $is_image = $this->input->post('is_image');

        if (!empty($fileName)) {
            foreach ($fileName as $key => $name) {
                $old['fileName'] = $name;
                $old['path'] = $path[$key];
                $old['fullPath'] = $fullPath[$key];
                $old['size'] = $size[$key];
                $old['is_image'] = $is_image[$key];

                array_push($upload_file, $old);
            }
        }
        if (!empty($upload_file)) {
            $data['attachement'] = json_encode($upload_file);
        } else {
            $data['attachement'] = null;
        }

        $permission = $this->input->post('permission', true);
        if (!empty($permission)) {
            if ($permission == 'everyone') {
                $assigned = 'all';
            } else {
                $assigned_to = $this->purchase_model->array_from_post(array('assigned_to'));
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
        $this->purchase_model->_table_name = 'tbl_purchases';
        $this->purchase_model->_primary_key = 'purchase_id';
        $old_purchases = '';
        if (!empty($id)) {
            $purchase_id = $id;
            $old_purchases = get_any_field('tbl_purchases', array('purchase_id' => $id), 'status');

            $this->purchase_model->save($data, $id);
            $action = ('purchase_updated');
            $msg = lang('purchase_updated');
        } else {
            plan_capability('purchase');
            $data['created_by'] = my_id();
            $purchase_id = $this->purchase_model->save($data);
            $action = ('purchase_created');
            $msg = lang('purchase_created');
        }

        $removed_items = $this->input->post('removed_items', TRUE);
        if (!empty($removed_items)) {
            foreach ($removed_items as $r_id) {
                if ($r_id != 'undefined') {
                    $this->return_items($r_id);
                    $this->db->where('items_id', $r_id);
                    $this->db->delete('tbl_purchase_items');
                }
            }
        }
        $items_data = $this->input->post('items', true);

        if (!empty($items_data)) {
            $index = 0;
            foreach ($items_data as $items) {
                $items['purchase_id'] = $purchase_id;
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
                if ($data['status'] == 'received' && $data['update_stock'] == 'Yes') {
                    if (!empty($items['saved_items_id']) && $items['saved_items_id'] != 'undefined') {
                        if (!empty($items['items_id'])) {
                            $old_quantity = get_any_field('tbl_purchase_items', array('items_id' => $items['items_id']), 'quantity');
                            if ($old_quantity != $items['quantity']) {
                                // $a < $b	Less than TRUE if $a is strictly less than $b.
                                // $a > $b	Greater than TRUE if $a is strictly greater than $b.
                                if ($old_quantity > $items['quantity']) {
                                    $quantity = $old_quantity - $items['quantity'];
                                    $this->purchase_model->reduce_items($items['saved_items_id'], $quantity);
                                } else {
                                    $quantity = $items['quantity'] - $old_quantity;
                                    $this->purchase_model->return_items($items['saved_items_id'], $quantity);
                                }
                            } else {
                                if ($old_purchases != 'received') {
                                    $this->purchase_model->return_items($items['saved_items_id'], $items['quantity']);
                                }
                            }
                        } else {
                            $this->purchase_model->return_items($items['saved_items_id'], $items['quantity']);
                        }
                    }
                }

                $price = $items['quantity'] * $items['unit_cost'];
                $items['item_tax_total'] = ($price / 100 * $tax);
                $items['total_cost'] = $price;
                // get all client
                $this->purchase_model->_table_name = 'tbl_purchase_items';
                $this->purchase_model->_primary_key = 'items_id';
                if (!empty($items['items_id'])) {
                    $items_id = $items['items_id'];
                    $this->purchase_model->save($items, $items_id);
                } else {
                    $items_id = $this->purchase_model->save($items);
                }
                $index++;
            }
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'purchase',
            'module_field_id' => $purchase_id,
            'activity' => $action,
            'icon' => 'fa fa-truck',
            'link' => 'admin/purchase/purchase_details/' . $purchase_id,
            'value1' => $data['reference_no']
        );
        $this->purchase_model->_table_name = 'tbl_activities';
        $this->purchase_model->_primary_key = 'activities_id';
        $this->purchase_model->save($activity);

        $remove_purchase = array(
            'remove_purchase' => 1,
        );
        $this->session->set_userdata($remove_purchase);
        // messages for user
        $type = "success";
        $message = $msg;
        set_message($type, $message);
        redirect('admin/purchase/purchase_details/' . $purchase_id);
    }

    function return_items($items_id)
    {
        $items_info = $this->db->where('items_id', $items_id)->get('tbl_purchase_items')->row();
        if (!empty($items_info->saved_items_id)) {
            $this->purchase_model->return_items($items_info->saved_items_id, $items_info->quantity);
        }
        return true;
    }

    public function purchase_details($id)
    {
        $data['title'] = lang('purchase') . ' ' . lang('details'); //Page title
        $data['purchase_info'] = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchases');
        if (empty($data['purchase_info'])) {
            set_message('error', lang('there_in_no_value'));
            redirect('admin/purchase/manage_purchase');
        }
        $data['subview'] = $this->load->view('admin/purchase/purchase_details', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load

    }

    public
    function insert_items($purchase_id)
    {
        $edited = can_action('150', 'edited');
        $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $purchase_id));
        if (!empty($can_edit) && !empty($edited)) {
            $data['purchase_id'] = $purchase_id;
            $data['modal_subview'] = $this->load->view('admin/purchase/_modal_insert_items', $data, FALSE);
            $this->load->view('admin/_layout_skote_modal', $data);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public
    function add_insert_items($purchase_id)
    {
        $edited = can_action('150', 'edited');
        $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $purchase_id));
        $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $purchase_id), 'tbl_purchases');
        if (!empty($can_edit) && !empty($edited)) {
            $saved_items_id = $this->input->post('saved_items_id', TRUE);
            if (!empty($saved_items_id)) {

                foreach ($saved_items_id as $v_items_id) {
                    if ($purchase_info->status == 'received' && $purchase_info->update_stock == 'Yes') {
                        $this->purchase_model->return_items($v_items_id, 1);
                    }
                    $items_info = $this->purchase_model->check_by(array('saved_items_id' => $v_items_id), 'tbl_saved_items');

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
                    $data['purchase_id'] = $purchase_id;
                    $data['item_name'] = $items_info->item_name;
                    $data['item_desc'] = $items_info->item_desc;
                    $data['hsn_code'] = $items_info->hsn_code;
                    $data['unit_cost'] = $items_info->unit_cost;
                    $data['item_tax_rate'] = '0.00';
                    $data['item_tax_name'] = json_encode($tax_name);
                    $data['item_tax_total'] = $items_info->item_tax_total;
                    $data['total_cost'] = $items_info->unit_cost;
                    // get all client
                    $this->purchase_model->_table_name = 'tbl_purchase_items';
                    $this->purchase_model->_primary_key = 'items_id';
                    $items_id = $this->purchase_model->save($data);

                    $action = ('activity_purchase_items_added');
                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'purchase',
                        'module_field_id' => $items_id,
                        'activity' => $action,
                        'icon' => 'fa-circle-o',
                        'value1' => $items_info->item_name
                    );
                    $this->purchase_model->_table_name = 'tbl_activities';
                    $this->purchase_model->_primary_key = 'activities_id';
                    $this->purchase_model->save($activity);
                }
                $this->update_purchase_tax($saved_items_id, $purchase_id);

                $type = "success";
                $msg = lang('item_added');
            } else {
                $type = "error";
                $msg = 'please Select an items';
            }
            $message = $msg;
            set_message($type, $message);
            redirect('admin/purchase/purchase_details/' . $purchase_id);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    function update_purchase_tax($saved_items_id, $purchase_id)
    {
        $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $purchase_id), 'tbl_purchases');
        $tax_info = json_decode($purchase_info->total_tax);

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
                $items_info = $this->purchase_model->check_by(array('saved_items_id' => $v_items_id), 'tbl_saved_items');

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
            $purchase_data['tax'] = array_sum($structured_results['total_tax']);
            $purchase_data['total_tax'] = json_encode($structured_results);

            $this->purchase_model->_table_name = 'tbl_purchases';
            $this->purchase_model->_primary_key = 'purchase_id';
            $this->purchase_model->save($purchase_data, $purchase_id);
        }
        return true;
    }

    public
    function clone_purchase($purchase_id)
    {
        $edited = can_action('13', 'edited');
        $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $purchase_id));
        if (!empty($can_edit) && !empty($edited)) {
            $data['purchase_info'] = $this->purchase_model->check_by(array('purchase_id' => $purchase_id), 'tbl_purchases');

            $data['permission_user'] = $this->purchase_model->allowad_user('150');
            $data['all_supplier'] = $this->purchase_model->get_permission('tbl_suppliers');

            $data['modal_subview'] = $this->load->view('admin/purchase/_modal_clone_purchase', $data, FALSE);
            $this->load->view('admin/_layout_skote_modal', $data);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public
    function cloned_purchase($id)
    {
        plan_capability('purchase');
        $edited = can_action('150', 'edited');
        $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $id));
        if (!empty($can_edit) && !empty($edited)) {
            if (config_item('increment_purchase_number') == 'FALSE') {
                $this->load->helper('string');
                $reference_no = config_item('purchase_prefix') . ' ' . random_string('nozero', 6);
            } else {
                $reference_no = config_item('purchase_prefix') . ' ' . $this->purchase_model->generate_purchase_number();
            }
            $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchases');
            // save into invoice table
            $new_invoice = array(
                'reference_no' => $reference_no,
                'supplier_id' => $this->input->post('supplier_id', true),
                'purchase_date' => date('Y-m-d H:i', strtotime($this->input->post('purchase_date', true))),
                'due_date' => date('Y-m-d H:i', strtotime($this->input->post('due_date', true))),
                'notes' => $purchase_info->notes,
                'total_tax' => $purchase_info->total_tax,
                'tax' => $purchase_info->tax,
                'discount_type' => $purchase_info->discount_type,
                'discount_percent' => $purchase_info->discount_percent,
                'user_id' => $purchase_info->user_id,
                'created_by' => my_id(),
                'adjustment' => $purchase_info->adjustment,
                'discount_total' => $purchase_info->discount_total,
                'show_quantity_as' => $purchase_info->show_quantity_as,
                'status' => $purchase_info->status,
                'update_stock' => $purchase_info->update_stock,
                'emailed' => $purchase_info->emailed,
                'permission' => $purchase_info->permission,
            );

            $this->purchase_model->_table_name = "tbl_purchases"; //table name
            $this->purchase_model->_primary_key = "purchase_id";
            $new_purchase_id = $this->purchase_model->save($new_invoice);

            $purchase_items = $this->db->where('purchase_id', $id)->get('tbl_purchase_items')->result();
            if (!empty($purchase_items)) {
                foreach ($purchase_items as $new_item) {
                    if ($purchase_info->status == 'received' && $purchase_info->update_stock == 'Yes') {
                        if (!empty($new_item->saved_items_id) && $new_item->saved_items_id != 'undefined') {
                            $this->purchase_model->return_items($new_item->saved_items_id, $new_item->quantity);
                        }
                    }
                    $items = array(
                        'purchase_id' => $new_purchase_id,
                        'saved_items_id' => $new_item->saved_items_id,
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
                        'date_saved' => $new_item->date_saved,
                    );
                    $this->purchase_model->_table_name = "tbl_purchase_items"; //table name
                    $this->purchase_model->_primary_key = "items_id";
                    $this->purchase_model->save($items);
                }
            }
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'purchase',
                'module_field_id' => $new_purchase_id,
                'activity' => ('activity_cloned_purchase'),
                'icon' => 'fa-shopping-cart',
                'link' => 'admin/purchase/purchase_details/' . $new_purchase_id,
                'value1' => ' from ' . $purchase_info->reference_no . ' to ' . $reference_no,
            );
            // Update into tbl_project
            $this->purchase_model->_table_name = "tbl_activities"; //table name
            $this->purchase_model->_primary_key = "activities_id";
            $this->purchase_model->save($activities);

            // messages for user
            $type = "success";
            $message = lang('purchase_created');
            set_message($type, $message);
            redirect('admin/purchase/purchase_details/' . $new_purchase_id);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }


    public function payment($id)
    {
        $data['title'] = lang('purchase') . ' ' . lang('payment');
        // get payment info by id
        $this->purchase_model->_table_name = 'tbl_purchase_payments';
        $this->purchase_model->_order_by = 'payments_id';
        $data['all_payments_history'] = $this->purchase_model->get_by(array('purchase_id' => $id), FALSE);
        $data['purchase_info'] = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchases');

        $data['all_purchases'] = $this->purchase_model->get_permission('tbl_purchases');
        $data['subview'] = $this->load->view('admin/purchase/payment', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public
    function get_payemnt($purchase_id)
    {
        $edited = can_action('150', 'edited');
        $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $purchase_id));
        if (!empty($can_edit) && !empty($edited)) {
            $due = round($this->purchase_model->calculate_to('purchase_due', $purchase_id), 2);
            $paid_amount = $this->input->post('amount', TRUE);
            if ($paid_amount != 0) {
                if ($paid_amount > $due) {
                    // messages for user
                    $type = "error";
                    $message = lang('overpaid_amount');
                    set_message($type, $message);
                    redirect('admin/purchase/payment/' . $purchase_id);
                } else {
                    $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $purchase_id), 'tbl_purchases');
                    $data = array(
                        'purchase_id' => $purchase_id,
                        'paid_to' => $purchase_info->supplier_id,
                        'paid_by' => my_id(),
                        'payment_method' => $this->input->post('payment_methods_id', TRUE),
                        'currency' => $this->input->post('currency', TRUE),
                        'amount' => $paid_amount,
                        'payment_date' => date('Y-m-d H:i', strtotime($this->input->post('payment_date', TRUE))),
                        'trans_id' => $this->input->post('trans_id'),
                        'notes' => $this->input->post('notes'),
                        'month_paid' => date("m", strtotime($this->input->post('payment_date', TRUE))),
                        'year_paid' => date("Y", strtotime($this->input->post('payment_date', TRUE))),
                    );
                    $this->purchase_model->_table_name = 'tbl_purchase_payments';
                    $this->purchase_model->_primary_key = 'payments_id';
                    $payments_id = $this->purchase_model->save($data);

                    if ($paid_amount < $due) {
                        $status = 'partially_paid';
                    }
                    if ($paid_amount == $due) {
                        $status = 'Paid';
                    }

                    $purchase_data['status'] = $status;
                    update('tbl_purchases', array('purchase_id' => $purchase_id), $purchase_data);
                    $currency = $this->purchase_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'purchase',
                        'module_field_id' => $purchase_id,
                        'activity' => ('activity_new_payment'),
                        'icon' => 'fa-shopping-cart',
                        'link' => 'admin/purchase/purchase_details/' . $purchase_id,
                        'value1' => display_money($paid_amount, $currency->symbol),
                        'value2' => $purchase_info->reference_no,
                    );
                    $this->purchase_model->_table_name = 'tbl_activities';
                    $this->purchase_model->_primary_key = 'activities_id';
                    $this->purchase_model->save($activity);

                    if ($this->input->post('deduct_from_account') == 'on') {
                        $account_id = $this->input->post('account_id', true);
                        if (empty($account_id)) {
                            $account_id = config_item('default_account');
                        }
                        if (!empty($account_id)) {
                            $reference = lang('purchase') . ' ' . lang('reference_no') . ": <a href='" . base_url('admin/purchase/purchase_details/' . $purchase_info->purchase_id) . "' >" . $purchase_info->reference_no . "</a> and " . lang('trans_id') . ": <a href='" . base_url('admin/purchase/payments_details/' . $payments_id) . "'>" . $this->input->post('trans_id', true) . "</a>";
                            $trans_id = $this->input->post('trans_id', true);
                            // save into tbl_transaction
                            $tr_data = array(
                                'name' => lang('purchase_payment', lang('trans_id') . '# ' . $trans_id),
                                'type' => 'Expense',
                                'amount' => $paid_amount,
                                'debit' => $paid_amount,
                                'credit' => 0,
                                'date' => date('Y-m-d H:i', strtotime($this->input->post('payment_date', TRUE))),
                                'paid_by' => $purchase_info->supplier_id,
                                'payment_methods_id' => $this->input->post('payment_methods_id', TRUE),
                                'reference' => $trans_id,
                                'notes' => lang('this_expense_from_purchase_payment', $reference),
                                'permission' => 'all',
                            );
                            $account_info = $this->purchase_model->check_by(array('account_id' => $account_id), 'tbl_accounts');
                            if (!empty($account_info)) {
                                $ac_data['balance'] = $account_info->balance - $tr_data['amount'];
                                $this->purchase_model->_table_name = "tbl_accounts"; //table name
                                $this->purchase_model->_primary_key = "account_id";
                                $this->purchase_model->save($ac_data, $account_info->account_id);

                                $aaccount_info = $this->purchase_model->check_by(array('account_id' => $account_id), 'tbl_accounts');

                                $tr_data['total_balance'] = $aaccount_info->balance;
                                $tr_data['account_id'] = $account_id;

                                // save into tbl_transaction
                                $this->purchase_model->_table_name = "tbl_transactions"; //table name
                                $this->purchase_model->_primary_key = "transactions_id";
                                $return_id = $this->purchase_model->save($tr_data);

                                $deduct_account['account_id'] = $account_id;
                                $this->purchase_model->_table_name = 'tbl_purchase_payments';
                                $this->purchase_model->_primary_key = 'payments_id';
                                $this->purchase_model->save($deduct_account, $payments_id);

                                // save into activities
                                $activities = array(
                                    'user' => $this->session->userdata('user_id'),
                                    'module' => 'transactions',
                                    'module_field_id' => $return_id,
                                    'activity' => 'activity_new_deposit',
                                    'icon' => 'fa-building-o',
                                    'link' => 'admin/transactions/deposit',
                                    'value1' => $account_info->account_name,
                                    'value2' => $paid_amount,
                                );
                                // Update into tbl_project
                                $this->purchase_model->_table_name = "tbl_activities"; //table name
                                $this->purchase_model->_primary_key = "activities_id";
                                $this->purchase_model->save($activities);
                            }
                        }
                    }
                    if ($this->input->post('send_thank_you') == 'on') {
                        $this->send_payment_email($purchase_id, $paid_amount); //send thank you email
                    }
                }
            }
            // messages for user
            $type = "success";
            $message = lang('generate_payment');
            set_message($type, $message);
            redirect('admin/purchase/purchase_details/' . $purchase_id);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function all_payments($id = NULL)
    {
        if (!empty($id)) {
            $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $id));
            if (!empty($can_edit)) {
                $payments_info = $this->purchase_model->check_by(array('payments_id' => $id), 'tbl_purchase_payments');
                $data['purchase_info'] = $this->purchase_model->check_by(array('purchase_id' => $payments_info->purchase_id), 'tbl_purchases');
            }
            $data['title'] = lang('edit') . ' ' . lang('purchase') . ' ' . lang('payment'); //Page title
            $subview = 'edit_payments';
        } else {
            $data['title'] = lang('all') . ' ' . lang('purchase') . ' ' . lang('payment'); //Page title
            $subview = 'all_payments';
        }
        $data['all_purchase'] = $this->purchase_model->get_permission('tbl_purchases');
        // get payment info by id
        if (!empty($id)) {
            $can_edit = $this->purchase_model->can_action('tbl_purchase_payments', 'edit', array('payments_id' => $id));
            if (!empty($can_edit)) {
                $data['payments_info'] = $this->purchase_model->check_by(array('payments_id' => $id), 'tbl_purchase_payments');
            } else {
                set_message('error', lang('no_permission_to_access'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        $data['subview'] = $this->load->view('admin/purchase/' . $subview, $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public function payments_details($id)
    {
        $data['all_purchases'] = $this->purchase_model->get_permission('tbl_purchases');
        $data['title'] = lang('purchase') . ' ' . lang('payment') . ' ' . lang('details'); //Page title
        $subview = 'payments_details';
        // get payment info by id
        $data['payments_info'] = $this->purchase_model->check_by(array('payments_id' => $id), 'tbl_purchase_payments');
        $data['subview'] = $this->load->view('admin/purchase/' . $subview, $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public
    function update_payemnt($payments_id)
    {
        $data = array(
            'amount' => $this->input->post('amount', TRUE),
            'payment_method' => $this->input->post('payment_methods_id', TRUE),
            'payment_date' => date('Y-m-d H:i', strtotime($this->input->post('payment_date', TRUE))),
            'notes' => $this->input->post('notes', TRUE),
            'month_paid' => date("m", strtotime($this->input->post('payment_date', TRUE))),
            'year_paid' => date("Y", strtotime($this->input->post('payment_date', TRUE))),
        );

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'purchase',
            'module_field_id' => $payments_id,
            'activity' => ('activity_update_payment'),
            'icon' => 'fa-shopping-cart',
            'link' => 'admin/purchase/purchase_details/' . $payments_id,
            'value1' => $data['amount'],
            'value2' => $data['payment_date'],
        );
        $this->purchase_model->_table_name = 'tbl_activities';
        $this->purchase_model->_primary_key = 'activities_id';
        $this->purchase_model->save($activity);

        $this->purchase_model->_table_name = 'tbl_purchase_payments';
        $this->purchase_model->_primary_key = 'payments_id';
        $this->purchase_model->save($data, $payments_id);


        // messages for user
        $type = "success";
        $message = lang('generate_payment');
        set_message($type, $message);
        redirect('admin/purchase/all_payments');
    }

    public
    function send_payment($purchase_id, $paid_amount)
    {
        $this->send_payment_email($purchase_id, $paid_amount); //send email

        $type = "success";
        $message = lang('payment_information_send');
        set_message($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    function send_payment_email($purchase_id, $paid_amount)
    {
        $currency = $this->purchase_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        $email_template = $this->purchase_model->check_by(array('email_group' => 'payment_email'), 'tbl_email_templates');
        $message = $email_template->template_body;
        $subject = $email_template->subject;

        $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $purchase_id), 'tbl_purchases');
        $currency = $currency->symbol;
        $reference = $purchase_info->reference_no;

        $invoice_currency = str_replace("{
        INVOICE_CURRENCY}", $currency, $message);
        $reference = str_replace("{
        INVOICE_REF}", $reference, $invoice_currency);
        $amount = str_replace("{
        PAID_AMOUNT}", $paid_amount, $reference);
        $message = str_replace("{
        SITE_NAME}", config_item('company_name'), $amount);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);
        $supplier_info = $this->purchase_model->check_by(array('supplier_id' => $purchase_info->supplier_id), 'tbl_suppliers');
        $address = $supplier_info->email;
        $params['recipient'] = $address;

        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'purchase',
            'module_field_id' => $purchase_id,
            'activity' => ('activity_send_payment'),
            'icon' => 'fa-shopping-cart',
            'link' => 'admin/purchase/purchase_details/' . $purchase_id,
            'value1' => $reference,
            'value2' => $currency . ' ' . $amount,
        );
        $this->purchase_model->_table_name = 'tbl_activities';
        $this->purchase_model->_primary_key = 'activities_id';
        $this->purchase_model->save($activity);

        $this->purchase_model->send_email($params);
    }

    public
    function change_status($action, $id)
    {
        $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchases');        
        $purchase_items = $this->db->where('purchase_id', $id)->get('tbl_purchase_items')->result();
        if ($action == 'mark_as_sent') {
            $data = array('emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));
        } elseif ($action == 'mark_as_cancelled') {
            if ($purchase_info->status == 'received' && $purchase_info->update_stock == 'Yes') {
                if (!empty($purchase_items)) {
                    foreach ($purchase_items as $new_item) {
                        if (!empty($new_item->saved_items_id) && $new_item->saved_items_id != 'undefined') {
                            $this->purchase_model->reduce_items($new_item->saved_items_id, $new_item->quantity);
                        }
                    }
                }
            }
            $data = array('status' => 'Cancelled');
        } elseif ($action == 'unmark_as_cancelled') {
            if ($purchase_info->status == 'received' && $purchase_info->update_stock == 'Yes') {
                if (!empty($purchase_items)) {
                    foreach ($purchase_items as $new_item) {
                        if (!empty($new_item->saved_items_id) && $new_item->saved_items_id != 'undefined') {
                            $this->purchase_model->return_items($new_item->saved_items_id, $new_item->quantity);
                        }
                    }
                }
            }
            $data = array('status' => 'pending');
        } elseif ($action == 'received') {
            if ($purchase_info->status != 'received' && $purchase_info->update_stock == 'Yes') {
                if (!empty($purchase_items)) {
                    foreach ($purchase_items as $new_item) {
                        if (!empty($new_item->saved_items_id) && $new_item->saved_items_id != 'undefined') {
                            $this->purchase_model->return_items($new_item->saved_items_id, $new_item->quantity);
                        }
                    }
                }
            }
            $data = array('status' => 'received');
        } else {
            $data = array('status' => $action);
        }
        $this->purchase_model->_table_name = 'tbl_purchases';
        $this->purchase_model->_primary_key = 'purchase_id';
        $this->purchase_model->save($data, $id);

        // messages for user
        $type = "success";
        $imessage = lang('purchase_update');
        set_message($type, $imessage);
        redirect('admin/purchase/purchase_details/' . $id);
    }

    function send_purchase_email($purchase_id)
    {
        $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $purchase_id), 'tbl_purchases');
        $supplier_info = $this->purchase_model->check_by(array('supplier_id' => $purchase_info->supplier_id), 'tbl_suppliers');
        $currency = $this->purchase_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        $message = " < p>Hello $supplier_info->name </p >
<p >&nbsp;</p >

<p > This is a purchase details of " . display_money($this->purchase_model->calculate_to('total', $purchase_info->purchase_id), $currency->symbol) . " < br />
Please check the attachment bellow:<br />
<br />
Best Regards,<br />
The " . config_item('company_name') . " Team </p > ";
        $params = array(
            'recipient' => $supplier_info->email,
            'subject' => '[ ' . config_item('company_name') . ' ]' . ' Purchase' . ' ' . $purchase_info->reference_no,
            'message' => $message
        );
        $params['resourceed_file'] = 'uploads/' . lang('purchase') . '_' . $purchase_info->reference_no . '.pdf';
        $params['resourcement_url'] = base_url() . 'uploads/' . lang('purchase') . '_' . $purchase_info->reference_no . '.pdf';
        $this->attach_pdf($purchase_id);
        $this->purchase_model->send_email($params);
        //Delete invoice in tmp folder
        if (is_file('uploads/' . lang('purchase') . '_' . $purchase_info->reference_no . '.pdf')) {
            unlink('uploads/' . lang('purchase') . '_' . $purchase_info->reference_no . '.pdf');
        }

        $data = array('emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));

        $this->purchase_model->_table_name = 'tbl_purchases';
        $this->purchase_model->_primary_key = 'purchase_id';
        $this->purchase_model->save($data, $purchase_info->purchase_id);

        // Log Activity
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'purchase',
            'module_field_id' => $purchase_info->purchase_id,
            'activity' => ('activity_purchase_sent'),
            'icon' => 'fa-shopping-cart',
            'link' => 'admin/purchase/purchase_details/' . $purchase_info->purchase_id,
            'value1' => $purchase_info->reference_no,
            'value2' => display_money($this->purchase_model->calculate_to('total', $purchase_info->purchase_id), $currency->symbol),
        );
        $this->purchase_model->_table_name = 'tbl_activities';
        $this->purchase_model->_primary_key = 'activities_id';
        $this->purchase_model->save($activity);
        // messages for user
        $type = "success";
        $imessage = lang('invoice_sent');
        set_message($type, $imessage);
        redirect('admin/purchase/purchase_details/' . $purchase_info->purchase_id);
    }

    public function attach_pdf($id)
    {
        $data['page'] = lang('purchase');
        $data['purchase_info'] = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchases');
        $data['title'] = lang('invoices'); //Page title
        $this->load->helper('dompdf');
        $html = $this->load->view('admin/purchase/purchase_pdf', $data, TRUE);
        $result = pdf_create($html, lang('purchase') . '_' . $data['purchase_info']->reference_no, 1, null, true);
        return $result;
    }


    public function payments_pdf($id)
    {
        $data['title'] = "Payments PDF"; //Page title
        // get payment info by id
        $this->purchase_model->_table_name = 'tbl_purchase_payments';
        $this->purchase_model->_order_by = 'payments_id';
        $data['payments_info'] = $this->purchase_model->check_by(array('payments_id' => $id), 'tbl_purchase_payments');
        $this->load->helper('dompdf');
        $viewfile = $this->load->view('admin/purchase/payments_pdf', $data, TRUE);
        pdf_create($viewfile, lang('purchase') . ' ' . lang('payment') . '# ' . $data['payments_info']->trans_id);
    }

    public function pdf_purchase($id)
    {
        $data['purchase_info'] = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchases');
        $data['title'] = "Invoice PDF"; //Page title
        $this->load->helper('dompdf');
        $this->load->library('Pdf');
        $viewfile = $this->load->view('admin/purchase/purchase_pdf', $data, TRUE);

        pdf_create($viewfile, lang('purchase') . '# ' . $data['purchase_info']->reference_no);
    }

    public function delete_purchase($id)
    {
        $deleted = can_action('150', 'deleted');
        $can_delete = $this->purchase_model->can_action('tbl_purchases', 'delete', array('purchase_id' => $id));
        if (!empty($can_delete) && !empty($deleted)) {
            $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchases');
            $purchase_items_info = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchase_items');
            if ($purchase_info->status == 'received' && $purchase_info->update_stock == 'Yes') {
                if (!empty($purchase_items_info)) {
                    foreach ($purchase_items_info as $v_items) {
                        if ($v_items->saved_items_id != 0) {
                            $this->purchase_model->reduce_items($v_items->saved_items_id, $v_items->quantity);
                        }
                    }
                }
            }
            $this->purchase_model->_table_name = 'tbl_purchase_items';
            $this->purchase_model->delete_multiple(array('purchase_id' => $id));

            $this->purchase_model->_table_name = 'tbl_purchase_payments';
            $this->purchase_model->delete_multiple(array('purchase_id' => $id));

            $this->purchase_model->_table_name = 'tbl_purchases';
            $this->purchase_model->_primary_key = 'purchase_id';
            $this->purchase_model->delete($id);

            $type = "success";
            if (!empty($purchase_info->reference_no)) {
                $val = $purchase_info->reference_no;
            } else {
                $val = NULL;
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'purchase',
                'module_field_id' => $id,
                'activity' => ('activity_delete_purchase'),
                'icon' => 'fa fa-truck',
                'value1' => $val,

            );
            $this->purchase_model->_table_name = 'tbl_activities';
            $this->purchase_model->_primary_key = 'activities_id';
            $this->purchase_model->save($activity);

            echo json_encode(array("status" => $type, 'message' => lang('activity_delete_purchase')));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
            exit();
        }
    }

    public function delete_supplier($id)
    {
        $deleted = can_action('149', 'deleted');
        $can_delete = $this->purchase_model->can_action('tbl_suppliers', 'delete', array('supplier_id' => $id));
        if (!empty($can_delete) && !empty($deleted)) {
            $supplier_info = $this->purchase_model->check_by(array('supplier_id' => $id), 'tbl_suppliers');

            $this->purchase_model->_table_name = 'tbl_suppliers';
            $this->purchase_model->_primary_key = 'supplier_id';
            $this->purchase_model->delete($id);

            $type = "success";
            if (!empty($supplier_info->name)) {
                $val = $supplier_info->name;
            } else {
                $val = NULL;
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'purchase',
                'module_field_id' => $id,
                'activity' => ('activity_delete_supplier'),
                'icon' => 'fa fa-truck',
                'value1' => $val,

            );
            $this->purchase_model->_table_name = 'tbl_activities';
            $this->purchase_model->_primary_key = 'activities_id';
            $this->purchase_model->save($activity);

            echo json_encode(array("status" => $type, 'message' => lang('activity_delete_supplier')));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
            exit();
        }
    }
}
