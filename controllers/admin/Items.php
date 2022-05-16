<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('items_model');


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

    public function items_list($id = NULL, $opt = null)
    {
        $this->load->model('stock_model');
        $data['title'] = lang('all_items');
        if (!empty($id)) {
            if (is_numeric($id)) {
                $data['active'] = 2;
                $data['items_info'] = $this->items_model->check_by(array('saved_items_id' => $id), 'tbl_saved_items');
            } else {
                $data['active'] = 3;
                $data['group_info'] = $this->items_model->check_by(array('customer_group_id' => $opt), 'tbl_customer_group');
            }
        } else {
            $data['active'] = 1;
        }
        // get payment info by id
        $this->items_model->_table_name = 'tbl_customer_group';
        $this->items_model->_order_by = 'customer_group_id';
        $data['all_customer_group'] = $this->items_model->get_by(array('type' => 'items'), FALSE);
        // retrive all data from department table
        $all_cate_info = by_company('tbl_stock_category', 'stock_category_id');;
        // get all category info and designation info
        foreach ($all_cate_info as $v_cate_info) {
            $data['all_category_info'][$v_cate_info->stock_category] = $this->items_model->get_sub_category_by_id($v_cate_info->stock_category_id);
        }

        $data['subview'] = $this->load->view('admin/items/items_list', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public function items_details($id = NULL)
    {
        $data['title'] = lang('items_details');
        if (!empty($id)) {
            $data['items_info'] = $this->items_model->check_by(array('saved_items_id' => $id), 'tbl_saved_items');
            $data['barcode'] = $this->product_barcode($data['items_info']->code, $data['items_info']->barcode_symbology, 60);
        }
        $data['subview'] = $this->load->view('admin/items/items_details', $data, FALSE);
        $this->load->view('admin/_layout_skote_modal_lg', $data); //page load
    }

    public function single_barcode($id)
    {
        $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
        $product = get_row('tbl_saved_items', array('saved_items_id' => $id));
        $total = $product->quantity - 1;
        $html = "";
        $html .= '<table class="table table-bordered table-centered mb0">
        <tbody><tr>';
        if ($product->quantity > 0) {
            for ($r = 0; $r <= $total; $r++) {
                if ($r % 4 == 0) {
                    $html .= '</tr><tr>';
                }
//                $rw = (bool)($r & 1);
//                    $html .= $rw ? '</tr><tr>' : '';
                $html .= '<td class="text-center"><h4 class="m-sm">' . config_item('website_name') . '</h4><strong>' . $product->item_name . '</strong><br>' . $this->product_barcode($product->code, $product->barcode_symbology, 60) . ' <br><span class="price">' . lang('price') . ': ' . display_money($product->unit_cost, $currency->symbol) . '</span></td>';
            }
        } else {
            for ($r = 0; $r <= 9; $r++) {
                if ($r != 1) {
                    $rw = (bool)($r & 1);
                    $html .= $rw ? '</tr><tr>' : '';
                }
                $html .= '<td><h4>' . config_item('website_name') . '</h4><strong class="text-center">' . $product->item_name . '</strong><br>' . $this->product_barcode($product->code, $product->barcode_symbology, 60) . ' <br><span class="price">' . lang('price') . ': ' . display_money($product->unit_cost, $currency->symbol) . '</span></td>';
            }
        }
        $html .= '</tr></tbody>
        </table>';
        $data['html'] = $html;
        $data['title'] = lang("print_barcodes") . ' (' . $product->item_name . ')';
        $data['subview'] = $this->load->view('admin/items/single_barcode', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    function product_barcode($product_code = NULL, $bcs = 'code128', $height = 60)
    {
        return "<img src='" . site_url('admin/items/gen_barcode/' . $product_code . '/' . $bcs . '/' . $height) . "' alt='{$product_code}' class='bcimg' />";
    }

    function gen_barcode($product_code = NULL, $bcs = 'code128', $height = 60, $text = 1)
    {
        $drawText = ($text != 1) ? FALSE : TRUE;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $product_code, 'barHeight' => $height, 'drawText' => $drawText, 'factor' => 1);
        $rendererOptions = array('imageType' => 'png', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle');
        echo Zend_Barcode::render($bcs, 'image', $barcodeOptions, $rendererOptions);
        exit();
    }


    public function saved_items($id = NULL)
    {

        $this->items_model->_table_name = 'tbl_saved_items';
        $this->items_model->_primary_key = 'saved_items_id';

        $data = $this->items_model->array_from_post(array('manufacturer_id', 'item_name', 'code', 'barcode_symbology', 'item_desc', 'hsn_code', 'unit_cost', 'cost_price', 'unit_type', 'customer_group_id', 'quantity'));
        // update root category
        $where = array('code' => $data['code']);
        // duplicate value check in DB
        if (!empty($id)) { // if id exist in db update data
            $saved_items_id = array('saved_items_id !=' => $id);
        } else { // if id is not exist then set id as null
            $saved_items_id = null;
        }
        // check whether this input data already exist or not
        $check_lead_status = $this->items_model->check_update('tbl_saved_items', $where, $saved_items_id);
        if (!empty($check_lead_status)) { // if input data already exist show error alert
            // massage for user
            $type = 'error';
            $msg = "<strong style='color:#000'>" . $data['code'] . '</strong>  ' . lang('already_exist');
        } else { // save and update query
            // save image Process
            if (!empty($_FILES['product_image']['name'])) { // if product name is not empty
                $old_path = $this->input->post('old_path'); // input from data
                if ($old_path) { // if old path is no empty
                    unlink($old_path);
                } // upload file
                $val = $this->items_model->uploadImage('product_image');
                $val == TRUE || redirect('admin/items/items_list'); // puload file or redirect

//            $image_data['filename'] = $val['path']; // input file name
//            $image_data['image_path'] = $val['fullPath']; // input pull path

                $data['product_image'] = $val['path'];
            }

            $tax_rates = $this->input->post('tax_rates_id', true);
            $total_tax = 0;
            if (!empty($tax_rates)) {
                foreach ($tax_rates as $tax_id) {
                    $tax_info = $this->db->where('tax_rates_id', $tax_id)->get('tbl_tax_rates')->row();
                    $total_tax += $tax_info->tax_rate_percent;
                }
            }
            if (!empty($tax_rates)) {
                $data['tax_rates_id'] = json_encode($tax_rates);
            } else {
                $data['tax_rates_id'] = '-';
            }

            // update root category
            $where = array('item_name' => $data['item_name']);
            // duplicate value check in DB
            if (!empty($id)) { // if id exist in db update data
                $saved_items_id = array('saved_items_id !=' => $id);
            } else { // if id is not exist then set id as null
                $saved_items_id = null;
            }
            // check whether this input data already exist or not
            $check_items = $this->items_model->check_update('tbl_saved_items', $where, $saved_items_id);
            if (!empty($check_items)) { // if input data already exist show error alert
                // massage for user
                $type = 'error';
                $msg = "<strong style='color:#000'>" . $data['item_name'] . '</strong>  ' . lang('already_exist');
            } else { // save and update query
                $sub_total = $data['unit_cost'] * $data['quantity'];
                $data['item_tax_total'] = ($total_tax / 100) * $sub_total;
                $data['total_cost'] = $sub_total + $data['item_tax_total'];
                $return_id = $this->items_model->save($data, $id);
                if (!empty($id)) {
                    $id = $id;
                    $action = 'activity_update_items';
                    $msg = lang('update_items');
                } else {
                    $id = $return_id;
                    $action = 'activity_save_items';
                    $msg = lang('save_items');
                }
                if (!empty($id)) {
                    $stock_sub_category_id = $this->input->post('stock_sub_category_id', true);
                    $assign_quantity = $this->input->post('assign_quantity', true);
                    $stock_id = $this->input->post('stock_id', true);
                    $remove_stock_id = $this->input->post('remove_stock_id', true);
                    if (!empty($remove_stock_id)) {
                        foreach ($remove_stock_id as $v_stock_id) {
                            $this->items_model->_table_name = 'tbl_stock';
                            $this->items_model->_primary_key = 'stock_id';
                            $this->items_model->delete($v_stock_id);
                        }
                    }
                    $all_sub_category_id = array();
                    $all_stock_id = array();
                    if (!empty($assign_quantity)) {
                        if (!empty($stock_sub_category_id)) {
                            foreach ($stock_sub_category_id as $skey => $sub_category_id) {
                                if (!empty($assign_quantity[$skey])) {
                                    $all_sub_category_id[$sub_category_id][] = $assign_quantity[$skey];
                                    $all_stock_id[$sub_category_id] = $stock_id[$skey];
                                }
                            }
                        }
                    }
                    $all_stock = array_map('array_sum', $all_sub_category_id);
                    if (!empty($all_stock)) {
                        foreach ($all_stock as $cate_id => $v_qty) {
                            $sdata['stock_sub_category_id'] = $cate_id;
                            $sdata['saved_items_id'] = $id;
                            $sdata['total_stock'] = $v_qty;

                            $this->items_model->_table_name = 'tbl_stock';
                            $this->items_model->_primary_key = 'stock_id';
                            if (!empty($all_stock_id[$cate_id])) {
                                $this->items_model->save($sdata, $all_stock_id[$cate_id]);
                            } else {
                                $this->items_model->save($sdata);
                            }

                        }
                    }
                }
                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'items',
                    'module_field_id' => $id,
                    'activity' => $action,
                    'icon' => 'fa-circle-o',
                    'value1' => $data['item_name']
                );
                $this->items_model->_table_name = 'tbl_activities';
                $this->items_model->_primary_key = 'activities_id';
                $this->items_model->save($activity);
                // messages for user
                $type = "success";
            }
        }
        $message = $msg;
        set_message($type, $message);
        redirect('admin/items/items_list');
    }

    public function delete_items($id)
    {
        $items_info = $this->items_model->check_by(array('saved_items_id' => $id), 'tbl_saved_items');
        $items_stock_info = get_result('tbl_stock', array('saved_items_id' => $id));
        if (!empty($items_stock_info)) {
            foreach ($items_stock_info as $item_stock) {
                $this->items_model->_table_name = 'tbl_stock';
                $this->items_model->_primary_key = 'stock_id';
                $this->items_model->delete_multiple(array('stock_id' => $item_stock->stock_id));
            }
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'items',
            'module_field_id' => $id,
            'activity' => 'activity_items_deleted',
            'icon' => 'fa-circle-o',
            'value1' => $items_info->item_name
        );
        $this->items_model->_table_name = 'tbl_activities';
        $this->items_model->_primary_key = 'activities_id';
        $this->items_model->save($activity);


        $this->items_model->_table_name = 'tbl_saved_items';
        $this->items_model->_primary_key = 'saved_items_id';
        $this->items_model->delete($id);
        $type = 'success';
        $message = lang('items_deleted');
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }

    public function items_group()
    {
        $data['title'] = lang('items_group');
        $data['subview'] = $this->load->view('admin/items/items_group', $data, FALSE);
        $this->load->view('admin/_layout_skote_modal', $data);
    }

    public function manufacturer()
    {
        $data['title'] = lang('manufacturer');
        $data['subview'] = $this->load->view('admin/items/manufacturer', $data, FALSE);
        $this->load->view('admin/_layout_skote_modal', $data);
    }

    public function update_manufacturer($id = null)
    {
        $this->items_model->_table_name = 'tbl_manufacturer';
        $this->items_model->_primary_key = 'manufacturer_id';

        $cate_data['manufacturer'] = $this->input->post('manufacturer', TRUE);
        // update root category
        $where = array('manufacturer' => $cate_data['manufacturer']);
        // duplicate value check in DB
        if (!empty($id)) { // if id exist in db update data
            $manufacturer_id = array('manufacturer_id !=' => $id);
        } else { // if id is not exist then set id as null
            $manufacturer_id = null;
        }
        // check whether this input data already exist or not
        $check_category = $this->items_model->check_update('tbl_manufacturer', $where, $manufacturer_id);
        if (!empty($check_category)) { // if input data already exist show error alert
            // massage for user
            $type = 'error';
            $msg = "<strong style='color:#000'>" . $cate_data['manufacturer'] . '</strong>  ' . lang('already_exist');
        } else { // save and update query
            $id = $this->items_model->save($cate_data, $id);

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('manufacturer_added'),
                'value1' => $cate_data['manufacturer']
            );
            $this->items_model->_table_name = 'tbl_activities';
            $this->items_model->_primary_key = 'activities_id';
            $this->items_model->save($activity);

            // messages for user
            $type = "success";
            $msg = lang('manufacturer_added');
        }

        if (!empty($id)) {
            $result = array(
                'id' => $id,
                'manufacturer' => $cate_data['manufacturer'],
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
    }

    public function update_group($id = null)
    {
        $this->items_model->_table_name = 'tbl_customer_group';
        $this->items_model->_primary_key = 'customer_group_id';

        $cate_data['customer_group'] = $this->input->post('customer_group', TRUE);
        $cate_data['description'] = $this->input->post('description', TRUE);
        $cate_data['type'] = 'items';
        $id = $this->items_model->save($cate_data, $id);

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $id,
            'activity' => ('customer_group_added'),
            'value1' => $cate_data['customer_group']
        );
        $this->items_model->_table_name = 'tbl_activities';
        $this->items_model->_primary_key = 'activities_id';
        $this->items_model->save($activity);

        // messages for user
        $type = "success";
        $msg = lang('customer_group_added');
        if (!empty($id)) {
            $result = array(
                'id' => $id,
                'group' => $cate_data['customer_group'],
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
    }

    public function saved_group($id = null)
    {
        $this->items_model->_table_name = 'tbl_customer_group';
        $this->items_model->_primary_key = 'customer_group_id';

        $cate_data['customer_group'] = $this->input->post('customer_group', TRUE);
        $cate_data['description'] = $this->input->post('description', TRUE);
        $cate_data['type'] = 'items';

        $id = $this->items_model->save($cate_data, $id);

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $id,
            'activity' => ('customer_group_added'),
            'value1' => $cate_data['customer_group']
        );
        $this->items_model->_table_name = 'tbl_activities';
        $this->items_model->_primary_key = 'activities_id';
        $this->items_model->save($activity);

        // messages for user
        $type = "success";
        $msg = lang('customer_group_added');
        $message = $msg;
        set_message($type, $message);
        redirect('admin/items/items_list/group');
    }

    public function delete_group($id)
    {
        $customer_group = $this->items_model->check_by(array('customer_group_id' => $id), 'tbl_customer_group');
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $id,
            'activity' => ('activity_delete_a_customer_group'),
            'value1' => $customer_group->customer_group,
        );
        $this->items_model->_table_name = 'tbl_activities';
        $this->items_model->_primary_key = 'activities_id';
        $this->items_model->save($activity);

        $this->items_model->_table_name = 'tbl_customer_group';
        $this->items_model->_primary_key = 'customer_group_id';
        $this->items_model->delete($id);
        // messages for user
        $type = "success";
        $message = lang('category_deleted');
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }


    public function damage_items($id = NULL)
    {
        $data['title'] = lang('damage_items');
        if ($id) {
            $data['active'] = 2;
            $can_edit = $this->items_model->can_action('tbl_damage_product', 'edit', array('damage_product_id' => $id));
            $edited = can_action('155', 'edited');
            if (!empty($can_edit) && !empty($edited)) {
                if (is_numeric($id)) {
                    $data['damage_product'] = $this->items_model->check_by(array('damage_product_id' => $id), 'tbl_damage_product');
                }
            }
        } else {
            $data['active'] = 1;
        }
        $data['subview'] = $this->load->view('admin/items/damage_items', $data, TRUE);
        $this->load->view('admin/_layout_skote_main', $data); //page load
    }

    public function save_damage_product($id = NULL)
    {
        $created = can_action('36', 'created');
        $edited = can_action('36', 'edited');
        if (!empty($created) && !empty($edited)) {
            $this->items_model->_table_name = 'tbl_damage_product';
            $this->items_model->_primary_key = 'damage_product_id';

            $data = $this->items_model->array_from_post(array('saved_items_id', 'damage_quantity', 'notes'));
            $items_info = $this->items_model->check_by(array('saved_items_id' => $data['saved_items_id']), 'tbl_saved_items');
            if (!empty($items_info)) {
                if (empty($id)) {
                    if (!empty($items_info)) {
                        if ($items_info->quantity < $data['damage_quantity']) {
                            set_message('error', lang('damage_quantity_exceed'));
                            redirect('admin/items/damage_items/new');
                        }
                    }
                } else {
                    $damage_product_info = $this->items_model->check_by(array('damage_product_id' => $id), 'tbl_damage_product');
                    $damage_qty = $data['damage_quantity'];
                    if (!empty($damage_product_info)) {
                        if ($damage_product_info->damage_quantity != $damage_qty) {
                            if ($damage_qty > $damage_product_info->damage_quantity) {
                                $_qty = $damage_qty - $damage_product_info->damage_quantity;
                            }
                            if ($damage_qty < $damage_product_info->damage_quantity) {
                                $_qty = $damage_product_info->damage_quantity - $damage_qty;
                            }
                        }
                    }
                    if (!empty($items_info)) {
                        if ($items_info->quantity < $_qty) {
                            set_message('error', lang('damage_quantity_exceed'));
                            redirect('admin/items/damage_items/new');
                        }
                    }
                }
                $data['decrease_from_stock'] = ($this->input->post('decrease_from_stock') == 'Yes') ? 'Yes' : 'No';
                if ($data['decrease_from_stock'] == 'Yes') {
                    $this->adjustment_qty($data, $id);
                }
                $return_id = $this->items_model->save($data, $id);
                if (!empty($id)) {
                    $action = 'activity_update_damage_product';
                    $message = lang('update_damage_product');
                    $row = null;
                } else {
                    $id = $return_id;
                    $action = 'activity_save_damage_product';
                    $message = lang('save_damage_product');
                }
                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'items',
                    'module_field_id' => $id,
                    'activity' => $action,
                    'icon' => 'fa-circle-o',
                    'value1' => $items_info->item_name
                );
                $this->items_model->_table_name = 'tbl_activities';
                $this->items_model->_primary_key = 'activities_id';
                $this->items_model->save($activity);
                // messages for user
                $type = "success";
            } else {
                $type = "error";
                $message = lang('please_select_items');
            }
            set_message($type, $message);
        }
        redirect('admin/items/damage_items');

    }

    function adjustment_qty($data, $damage_product_id = null)
    {
        $damage_qty = $data['damage_quantity'];
        $items_info = $this->db->where('saved_items_id', $data['saved_items_id'])->get('tbl_saved_items')->row();
        if (!empty($damage_product_id)) {
            $damage_product_info = $this->items_model->check_by(array('damage_product_id' => $damage_product_id), 'tbl_damage_product');
            if ($damage_product_info->damage_quantity != $damage_qty) {
                if ($damage_qty > $damage_product_info->damage_quantity) {
                    $reduce_qty = $damage_qty - $damage_product_info->damage_quantity;
                    if (!empty($items_info->saved_items_id)) {
                        $this->items_model->reduce_items($items_info->saved_items_id, $reduce_qty);
                    }
                }
                if ($damage_qty < $damage_product_info->damage_quantity) {
                    $return_qty = $damage_product_info->damage_quantity - $damage_qty;
                    if (!empty($items_info->saved_items_id)) {
                        $this->items_model->return_items($items_info->saved_items_id, $return_qty);
                    }
                }
            }
        } else {
            if (!empty($items_info->saved_items_id)) {
                $this->items_model->reduce_items($items_info->saved_items_id, $damage_qty);
            }
        }
        return true;

    }

    public function delete_damage_items($id)
    {
        $damage_items_info = $this->items_model->check_by(array('damage_product_id' => $id), 'tbl_damage_product');
        $items_info = $this->items_model->check_by(array('saved_items_id' => $damage_items_info->saved_items_id), 'tbl_saved_items');

        if ($damage_items_info->decrease_from_stock == 'Yes') {
            if (!empty($items_info->saved_items_id)) {
                $this->items_model->return_items($items_info->saved_items_id, $damage_items_info->damage_quantity);
            }
        }

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'items',
            'module_field_id' => $id,
            'activity' => 'activity_damage_items_deleted',
            'icon' => 'fa-circle-o',
            'value1' => $items_info->item_name
        );
        $this->items_model->_table_name = 'tbl_activities';
        $this->items_model->_primary_key = 'activities_id';
        $this->items_model->save($activity);

        $this->items_model->_table_name = 'tbl_damage_product';
        $this->items_model->_primary_key = 'damage_product_id';
        $this->items_model->delete($id);

        $type = 'success';
        $message = lang('damage_items_deleted');
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }


}
