<?= message_box('success'); ?>
<?= message_box('error'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            

        </div>
    </div>
</div>
<!-- end page title -->
<?php
$created = can_action('150', 'created');
$edited = can_action('150', 'edited');
$deleted = can_action('150', 'deleted');
if (!empty($purchase_info)) {
    $purchase_id = $purchase_info->purchase_id;
    $companies_id = $purchase_info->companies_id;
} else {
    $purchase_id = null;
    $companies_id = null;
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="<?=base_url('admin/purchase/manage_purchase');?>"><?= lang('manage') . ' ' . lang('purchase') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)){ ?>

                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab"><?= lang('new') . ' ' . lang('purchase') ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <h4 class="card-title mb-4"><?= lang('manage_account') ?></h4>
                        <!-- <div class="table-responsive"> -->
                            <table class="table table-striped dt-responsive nowrap w-100" id="contentTable">
                                <thead>
                                <tr>
                                    <?php super_admin_opt_th() ?>
                                    <th><?= lang('reference_no') ?></th>
                                    <th><?= lang('supplier') ?></th>
                                    <th><?= lang('purchase_date') ?></th>
                                    <th><?= lang('due_amount') ?></th>
                                    <th><?= lang('status') ?></th>
                                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                                        <th class="col-options no-sort"><?= lang('action') ?></th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <?php /* ?><tbody>
                                <?php
                                if (!empty($all_purchases)) {
                                    foreach ($all_purchases as $v_purchase) {
                                        $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $v_purchase->purchase_id));
                                        $can_delete = $this->purchase_model->can_action('tbl_purchases', 'delete', array('purchase_id' => $v_purchase->purchase_id));
                                        $suppliers_info = get_row('tbl_suppliers', array('supplier_id' => $v_purchase->supplier_id));
                                        $currency = $this->purchase_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                        ?>
                                        <tr id="table_purchase_<?= $v_purchase->purchase_id ?>">
                                            <?php super_admin_opt_td($v_purchase->companies_id) ?>
                                            <td><a class="text-info"
                                                   href="<?= base_url() ?>admin/purchase/purchase_details/<?= $v_purchase->purchase_id ?>"><?= $v_purchase->reference_no ?></a>
                                            </td>
                                            <td><?= (!empty($suppliers_info) ? $suppliers_info->name : '-') ?></td>
                                            <td><?= display_datetime($v_purchase->purchase_date) ?></td>
                                            <td><?= display_money($this->purchase_model->calculate_to('purchase_due', $v_purchase->purchase_id), $currency->symbol) ?></td>
                                            <td><?php
                                                $status = $this->purchase_model->get_payment_status($v_purchase->purchase_id);
                                                if ($status == ('fully_paid')) {
                                                    $label = "success";
                                                } elseif ($status == ('draft')) {
                                                    $label = "default";
                                                } elseif ($status == ('partially_paid')) {
                                                    $label = "warning";
                                                } elseif ($v_purchase->emailed == 'Yes') {
                                                    $label = "info";
                                                } else {
                                                    $label = "danger";
                                                }
                                                ?>
                                                <span class="label label-<?= $label ?>"><?= lang($status) ?></span>
                                            </td>
                                            <td>
                                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                    <?= btn_edit('admin/purchase/manage_purchase/' . $v_purchase->purchase_id) ?>
                                                <?php }
                                                if (!empty($can_delete) && !empty($deleted)) {
                                                    ?>
                                                    <?php echo ajax_anchor(base_url("admin/purchase/delete_purchase/$v_purchase->purchase_id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_purchase_" . $v_purchase->purchase_id)); ?>
                                                <?php } ?>
                                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                <div class="dropdown btn-group">

                                                    <button class="btn btn-outline-success dropdown-toggle btn-sm" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('more') ?><i class="mdi mdi-chevron-down"></i></button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item" href="<?= base_url() ?>admin/purchase/purchase_details/<?= $v_purchase->purchase_id ?>"><?= lang('preview') ?></a>
                                                        <a class="dropdown-item" href="<?= base_url() ?>admin/purchase/payment/<?= $v_purchase->purchase_id ?>"><?= lang('pay') ?></a>
                                                        <a class="dropdown-item" href="<?= base_url() ?>admin/purchase/pdf_purchase/<?= $v_purchase->purchase_id ?>"><?= lang('pdf') ?></a>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    };
                                }
                                ?>
                                </tbody><?php */ ?>
                            </table>
                        <!-- </div> -->
                    </div>
                
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                        <?php echo form_open(base_url('admin/purchase/save_purchase/' . $purchase_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <div class="mb-lg purchase accounting-template">
                            <div class="row">
                                <div class="col-lg-6 br pv">
                                    <div class="row">
                                        <?php super_admin_form($companies_id, 4 , 7) ?>
                                        <div class="row mb-3">
                                            <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('reference_no') ?> <span class="text-danger">*</span></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($purchase_info)) {
                                                    echo $purchase_info->reference_no;
                                                } else {
                                                    echo config_item('purchase_prefix');
                                                    if (config_item('increment_purchase_number') == 'FALSE') {
                                                        $this->load->helper('string');
                                                        echo random_string('nozero', 6);
                                                    } else {
                                                        echo $this->purchase_model->generate_purchase_number();
                                                    }
                                                }
                                                ?>" name="reference_no">
                                            </div>
                                        </div>
                                        <div class="row mb-3 f_supplier_id">
                                            <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('supplier') ?> <span
                                                        class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <div class="input-group">
                                                    <select class="form-control select_box" style="width: 87%"
                                                            name="supplier_id" required="">
                                                        <option
                                                                value=""><?= lang('select') . ' ' . lang('supplier') ?></option>
                                                        <?php
                                                        if (!empty($all_supplier)) {
                                                            foreach ($all_supplier as $v_supplier) {
                                                                if (!empty($purchase_info->supplier_id)) {
                                                                    $supplier_id = $purchase_info->supplier_id;
                                                                }
                                                                ?>
                                                                <option value="<?= $v_supplier->supplier_id ?>"
                                                                    <?php
                                                                    if (!empty($supplier_id)) {
                                                                        echo $supplier_id == $v_supplier->supplier_id ? 'selected' : null;
                                                                    }
                                                                    ?>
                                                                ><?= $v_supplier->name ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        $_created = can_action('149', 'created');
                                                        ?>
                                                    </select>
                                                    <?php if (!empty($_created)) { ?>
                                                        <div class="input-group-text"
                                                             title="<?= lang('new') . ' ' . lang('supplier') ?>"
                                                             data-bs-toggle="tooltip" data-bs-placement="top">
                                                            <a data-bs-toggle="modal" data-bs-target="#myModal_extra_lg"
                                                               href="<?= base_url() ?>admin/purchase/new_supplier"><i
                                                                        class="fa fa-plus"></i></a>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label
                                                    class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('purchase') . ' ' . lang('date') ?></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <div class="input-group">
                                                    <input type="text" name="purchase_date"
                                                           class="form-control datepicker"
                                                           value="<?php
                                                           if (!empty($purchase_info->purchase_date)) {
                                                               echo date('d-m-Y H-i', strtotime($purchase_info->purchase_date));
                                                           } else {
                                                               echo date('d-m-Y H-i');
                                                           }
                                                           ?>"
                                                           data-date-format="<?= config_item('date_picker_format'); ?>">
                                                    <div class="input-group-text">
                                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('due_date') ?></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <div class="input-group">
                                                    <input type="text" name="due_date"
                                                           class="form-control datepicker"
                                                           value="<?php
                                                           if (!empty($purchase_info->due_date)) {
                                                               echo date('d-m-Y H-i', strtotime($purchase_info->due_date));
                                                           } else {
                                                               echo strftime(date('d-m-Y H-i'));
                                                           }
                                                           ?>"
                                                           data-date-format="<?= config_item('date_picker_format'); ?>">
                                                    <div class="input-group-text">
                                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3" id="border-none">
                                            <label for="field-1"
                                                   class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('permission') ?> <span
                                                        class="required">*</span></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <div class="form-check form-radio-outline form-radio-primary mb-3">
                                                    <input id="everyone" <?php
                                                        if (!empty($purchase_info->permission) && $purchase_info->permission == 'all') {
                                                            echo 'checked';
                                                        }
                                                        ?> type="radio" name="permission" value="everyone" class="form-check-input">
                                                    <label class="form-check-label" for="everyone"><?= lang('everyone') ?>
                                                        <i title="<?= lang('permission_for_all') ?>"
                                                           class="fa fa-question-circle" data-bs-toggle="tooltip"
                                                           data-bs-placement="top"></i>
                                                    </label>
                                                </div>
                                                <div class="form-check form-radio-outline form-radio-primary mb-3">
                                                    <input id="custom_permission" <?php
                                                        if (!empty($purchase_info->permission) && $purchase_info->permission != 'all') {
                                                            echo 'checked';
                                                        } elseif (empty($purchase_info)) {
                                                            echo 'checked';
                                                        }
                                                        ?> type="radio" name="permission" value="custom_permission"  class="form-check-input">
                                                    <label class="form-check-label" for="custom_permission"><?= lang('custom_permission') ?>
                                                        <i   title="<?= lang('permission_for_customization') ?>"   class="fa fa-question-circle" data-bs-toggle="tooltip"   data-bs-placement="top"></i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 <?php
                                        if (!empty($purchase_info->permission) && $purchase_info->permission != 'all') {
                                            echo 'show';
                                        }
                                        ?>" id="permission_user_1">
                                            <label for="field-1"
                                                   class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('select') . ' ' . lang('users') ?>
                                                <span
                                                        class="required">*</span></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                 <?php
                                                if (!empty($permission_user)) { ?>
                                                    <input type="text" name="search_assigned_user" value="" placeholder="<?=lang('search_by').' '.lang('username'); ?>" class="form-control mb-4" id="search_assigned_user" autocomplete="off">
                                                    <div data-simplebar style="max-height: 250px;">  
                                                    <?php
                                                    foreach ($permission_user as $key => $v_user) {

                                                        if ($v_user->role_id == 1) {
                                                            $role = '<strong class="badge btn-danger">' . lang('admin') . '</strong>';
                                                        } else {
                                                            $role = '<strong class="badge btn-primary">' . lang('staff') . '</strong>';
                                                        }

                                                        ?>
                                                        <div class="form-check form-check-primary mb-3">
                                                            <input type="checkbox"
                                                                    <?php
                                                                    if (!empty($purchase_info->permission) && $purchase_info->permission != 'all') {
                                                                        $get_permission = json_decode($purchase_info->permission);
                                                                        foreach ($get_permission as $user_id => $v_permission) {
                                                                            if ($user_id == $v_user->user_id) {
                                                                                echo 'checked';
                                                                            }
                                                                        }

                                                                    }
                                                                    ?>  value="<?= $v_user->user_id ?>"  name="assigned_to[]" data-name="<?= $v_user->username;?>"  class="form-check-input" id ="user_<?= $v_user->user_id ?>">
                                                            <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?>
                                                            </label>

                                                        </div>
                                                        <div class="action_1 p
                                                            <?php

                                                            if (!empty($purchase_info->permission) && $purchase_info->permission != 'all') {
                                                                $get_permission = json_decode($purchase_info->permission);

                                                                foreach ($get_permission as $user_id => $v_permission) {
                                                                    if ($user_id == $v_user->user_id) {
                                                                        echo 'show';
                                                                    }
                                                                }

                                                            }
                                                            ?>  " id="action_1<?= $v_user->user_id ?>">
                                                            <div class="form-check form-check-primary mb-3 mr">         
                                                                <input id="view_<?= $v_user->user_id ?>" checked type="checkbox" name="action_1<?= $v_user->user_id ?>[]" disabled value="view" class="form-check-input">
                                                                <label class="form-check-label" for="view_<?= $v_user->user_id ?>"><?= lang('view') ?></label>
                                                            </div>
                                                            <div class="form-check form-check-primary mb-3 mr">         
                                                                <input id="edit_<?= $v_user->user_id ?>"
                                                                        <?php

                                                                        if (!empty($purchase_info->permission) && $purchase_info->permission != 'all') {
                                                                            $get_permission = json_decode($purchase_info->permission);

                                                                            foreach ($get_permission as $user_id => $v_permission) {
                                                                                if ($user_id == $v_user->user_id) {
                                                                                    if (in_array('edit', $v_permission)) {
                                                                                        echo 'checked';
                                                                                    };

                                                                                }
                                                                            }

                                                                        }
                                                                        ?> type="checkbox" value="edit" name="action_<?= $v_user->user_id ?>[]" class="form-check-input">
                                                                <label class="form-check-label" for="edit_<?= $v_user->user_id ?>"><?= lang('edit') ?></label>
                                                            </div>
                                                            <div class="form-check form-check-primary mb-3 mr">         
                                                                <input id="delete_<?= $v_user->user_id;?>"
                                                                        <?php

                                                                        if (!empty($purchase_info->permission) && $purchase_info->permission != 'all') {
                                                                            $get_permission = json_decode($purchase_info->permission);
                                                                            foreach ($get_permission as $user_id => $v_permission) {
                                                                                if ($user_id == $v_user->user_id) {
                                                                                    if (in_array('delete', $v_permission)) {
                                                                                        echo 'checked';
                                                                                    };
                                                                                }
                                                                            }

                                                                        }
                                                                        ?>  name="action_<?= $v_user->user_id ?>[]"  type="checkbox"  value="delete" class="form-check-input">
                                                                <label class="form-check-label" for="delete_<?= $v_user->user_id ?>"><?= lang('delete') ?></label>
                                                            </div>             
                                                            <input id="<?= $v_user->user_id ?>" type="hidden"  name="action_<?= $v_user->user_id ?>[]"  value="view">
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 br pv">

                                    <div class="row">
                                        <div class="row mb-3">
                                            <label
                                                    class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('status') ?>
                                                <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <?php
                                                if (!empty($purchase_info->status)) {
                                                    $purchase_status = $purchase_info->status;
                                                } else {
                                                    $purchase_status = null;
                                                }
                                                $all_status = array('draft' => lang('draft'), 'received' => lang('received'), 'pending' => lang('pending'), 'ordered' => lang('ordered'));
                                                echo form_dropdown('status', $all_status, set_value('status', $purchase_status), 'class="form-control select_box" id="status" required="required" style="width:100%;"');
                                                ?>
                                            </div>

                                        </div>
                                        <div class="row mb-3">
                                            <label for="field-1"
                                                   class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('sales') . ' ' . lang('agent') ?></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <select class="form-control select_box" required style="width: 100%"
                                                        name="user_id">
                                                    <option
                                                            value=""><?= lang('select') . ' ' . lang('sales') . ' ' . lang('agent') ?></option>
                                                    <?php
                                                    $all_user = get_staff_details();
                                                    if (!empty($all_user)) {
                                                        foreach ($all_user as $v_user) {
                                                            $profile_info = $this->db->where('user_id', $v_user->user_id)->get('tbl_account_details')->row();
                                                            if (!empty($profile_info)) {
                                                                ?>
                                                                <option value="<?= $v_user->user_id ?>"
                                                                    <?php
                                                                    if (!empty($purchase_info->user_id)) {
                                                                        echo $purchase_info->user_id == $v_user->user_id ? 'selected' : null;
                                                                    } else {
                                                                        echo $this->session->userdata('user_id') == $v_user->user_id ? 'selected' : null;
                                                                    }
                                                                    ?>
                                                                ><?= $profile_info->fullname ?></option>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="discount_type"
                                                   class="col-form-label col-lg-4 col-md-4 col-sm-4"><?= lang('update_stock') ?></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <div class="form-check form-radio-outline form-radio-primary mb-3">
                                                    <input type="radio" value="Yes" name="update_stock"
                                                        <?php if (isset($purchase_info) && $purchase_info->update_stock == 'Yes') {
                                                            echo 'checked';
                                                        } elseif (empty($purchase_info)) {
                                                            echo 'checked';
                                                        } ?> id="yes" class="form-check-input">
                                                    <label class="form-check-label" for="yes"><?php echo lang('yes'); ?>
                                                    </label>
                                                </div>
                                                <div class="form-check form-radio-outline form-radio-primary mb-3">
                                                        <input type="radio" value="No"
                                                               name="update_stock" <?php if (isset($purchase_info) && $purchase_info->update_stock == 'No') {
                                                            echo 'checked';
                                                        } ?> id="no" class="form-check-input">
                                                        <label class="form-check-label" for="no"><?php echo lang('no'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="discount_type"
                                                   class="col-form-label col-lg-4 col-md-4 col-sm-4"><?= lang('discount_type') ?></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <select name="discount_type" class="select_box form-control" data-width="100%">
                                                    <option value=""
                                                            selected><?php echo lang('no') . ' ' . lang('discount'); ?></option>
                                                    <option value="before_tax" <?php
                                                    if (isset($purchase_info)) {
                                                        if ($purchase_info->discount_type == 'before_tax') {
                                                            echo 'selected';
                                                        }
                                                    } ?>><?php echo lang('before_tax'); ?></option>
                                                    <option value="after_tax" <?php if (isset($purchase_info)) {
                                                        if ($purchase_info->discount_type == 'after_tax') {
                                                            echo 'selected';
                                                        }
                                                    } ?>><?php echo lang('after_tax'); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="field-1" class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('attachment') ?></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <div id="comments_file-dropzone" class="dropzone mb15">

                                                </div>
                                                <div id="comments_file-dropzone-scrollbar">
                                                    <div id="comments_file-previews">
                                                        <div id="file-upload-row" class="mt pull-left">

                                                            <div class="preview box-content pr-lg" style="width:100px;">
                                                                <span data-dz-remove class="pull-right" style="cursor: pointer">
                                                                    <i class="fa fa-times"></i>
                                                                </span>
                                                                <img data-dz-thumbnail class="upload-thumbnail-sm"/>
                                                                <input class="file-count-field" type="hidden"
                                                                       name="files[]"
                                                                       value=""/>
                                                                <div
                                                                        class="mb progress progress-striped upload-progress-sm active mt-sm"
                                                                        role="progressbar" aria-valuemin="0"
                                                                        aria-valuemax="100"
                                                                        aria-valuenow="0">
                                                                    <div class="progress-bar progress-bar-success"
                                                                         style="width:0%;"
                                                                         data-dz-uploadprogress></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                if (!empty($purchase_info->attachement)) {
                                                    $uploaded_file = json_decode($purchase_info->attachement);
                                                }
                                                if (!empty($uploaded_file)) {
                                                    foreach ($uploaded_file as $v_files_image) { ?>
                                                        <div class="pull-left mt pr-lg mb" style="width:100px;">
                                                        <span data-dz-remove class="pull-right existing_image"
                                                              style="cursor: pointer"><i
                                                                    class="fa fa-times"></i></span>
                                                            <?php if ($v_files_image->is_image == 1) { ?>
                                                                <img data-dz-thumbnail
                                                                     src="<?php echo base_url() . $v_files_image->path ?>"
                                                                     class="upload-thumbnail-sm"/>
                                                            <?php } else { ?>
                                                                <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                                      title="<?= $v_files_image->fileName ?>"
                                                                      class="mailbox-attachment-icon"><i
                                                                            class="fa fa-file-text-o"></i></span>
                                                            <?php } ?>

                                                            <input type="hidden" name="path[]"
                                                                   value="<?php echo $v_files_image->path ?>">
                                                            <input type="hidden" name="fileName[]"
                                                                   value="<?php echo $v_files_image->fileName ?>">
                                                            <input type="hidden" name="fullPath[]"
                                                                   value="<?php echo $v_files_image->fullPath ?>">
                                                            <input type="hidden" name="size[]"
                                                                   value="<?php echo $v_files_image->size ?>">
                                                            <input type="hidden" name="is_image[]"
                                                                   value="<?php echo $v_files_image->is_image ?>">
                                                        </div>
                                                    <?php }; ?>
                                                <?php }; ?>
                                                <script type="text/javascript">
                                                    $(document).ready(function () {
                                                        $(".existing_image").click(function () {
                                                            $(this).parent().remove();
                                                        });

                                                        fileSerial = 0;
                                                        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
                                                        var previewNode = document.querySelector("#file-upload-row");
                                                        previewNode.id = "";
                                                        var previewTemplate = previewNode.parentNode.innerHTML;
                                                        previewNode.parentNode.removeChild(previewNode);
                                                        Dropzone.autoDiscover = false;
                                                        var projectFilesDropzone = new Dropzone("#comments_file-dropzone", {
                                                            url: "<?= base_url() ?>admin/global_controller/upload_file",
                                                            thumbnailWidth: 80,
                                                            thumbnailHeight: 80,
                                                            parallelUploads: 20,
                                                            previewTemplate: previewTemplate,
                                                            dictDefaultMessage: '<div class="mb-3"><i class="display-4 text-muted bx bxs-cloud-upload"></i></div><h4>Drop files here or click to upload.</h4>',
                                                            autoQueue: true,
                                                            previewsContainer: "#comments_file-previews",
                                                            clickable: true,
                                                            accept: function (file, done) {
                                                                if (file.name.length > 200) {
                                                                    done("Filename is too long.");
                                                                    $(file.previewTemplate).find(".description-field").remove();
                                                                }
                                                                $.ajax({
                                                                    url: "<?= base_url() ?>admin/global_controller/validate_project_file",
                                                                    data: {
                                                                        file_name: file.name,
                                                                        file_size: file.size
                                                                    },
                                                                    cache: false,
                                                                    type: 'POST',
                                                                    dataType: "json",
                                                                    success: function (response) {
                                                                        if (response.success) {
                                                                            fileSerial++;
                                                                            $(file.previewTemplate).find(".description-field").attr("name", "comment_" + fileSerial);
                                                                            $(file.previewTemplate).append("<input type='hidden' name='file_name_" + fileSerial + "' value='" + file.name + "' />\n\
                                                                                                    <input type='hidden' name='file_size_" + fileSerial + "' value='" + file.size + "' />");
                                                                            $(file.previewTemplate).find(".file-count-field").val(fileSerial);
                                                                            done();
                                                                        } else {
                                                                            $(file.previewTemplate).find("input").remove();
                                                                            done(response.message);
                                                                        }
                                                                    }
                                                                });
                                                            },
                                                            processing: function () {
                                                                $("#file-save-button").prop("disabled", true);
                                                            },
                                                            queuecomplete: function () {
                                                                $("#file-save-button").prop("disabled", false);
                                                            },
                                                            fallback: function () {
                                                                $("body").addClass("dropzone-disabled");
                                                                $('.modal-dialog').find('[type="submit"]').removeAttr('disabled');

                                                                $("#comments_file-dropzone").hide();

                                                                $("#file-modal-footer").prepend("<button id='add-more-file-button' type='button' class='btn  btn-default pull-left'><i class='fa fa-plus-circle'></i> " + "<?php echo lang("add_more"); ?>" + "</button>");

                                                                $("#file-modal-footer").on("click", "#add-more-file-button", function () {
                                                                    var newFileRow = "<div class='file-row pb pt10 b-b mb10'>" +
                                                                        "<div class='pb clearfix '><button type='button' class='btn btn-sm btn-danger pull-left mr remove-file'><i class='fa fa-times'></i></button> <input class='pull-left' type='file' name='manualFiles[]' /></div>" +
                                                                        "<div class='mb5 pb5'><input class='form-control description-field cursor-auto'  name='comment[]'  type='text' placeholder='<?php echo lang("comment") ?>' /></div>" +
                                                                        "</div>";
                                                                    $("#comments_file-previews").prepend(newFileRow);
                                                                });
                                                                $("#add-more-file-button").trigger("click");
                                                                $("#comments_file-previews").on("click", ".remove-file", function () {
                                                                    $(this).closest(".file-row").remove();
                                                                });
                                                            },
                                                            success: function (file,response) {
                                                                var res=JSON.parse(response);
                                                                if(res['error'] && res.length != 0){
                                                                    toastr['error'](res['error']);
                                                                    toastr['error']('<?=lang('docroom_connect_msg');?>');
                                                                    $(file.previewElement).closest(".file-upload-row").remove();
                                                                }else{
                                                                    var docroom_file_id=res['uploaded_file']['data'][0]['file_id'];
                                                                    var docroom_file_id_html="<input class='form-control'  name='docroom_file_id[]'  type='hidden' value='"+docroom_file_id+"' />";
                                                                    $("#comments_file-previews").prepend(docroom_file_id_html);
                                                                    setTimeout(function () {
                                                                        $(file.previewElement).find(".progress-striped").removeClass("progress-striped").addClass("progress-bar-success");
                                                                    }, 1000);
                                                                }
                                                            }
                                                        });
                                                    })
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-6 br pv">
                                    <div class="terms row mb-3">
                                        <label class="col-xl-2 col-form-label col-md-2 col-sm-2"><?= lang('notes') ?> </label>
                                        <div class="col-xl-9 col-md-9 col-sm-9">
                                            <textarea name="notes" id="elm1" class="textarea_ form-control"><?php
                                            if (!empty($purchase_info)) {
                                                echo $purchase_info->notes;
                                            } else {
                                                echo $this->config->item('purchase_terms');
                                            }
                                            ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 br pv ">
                                    <div class="col-lg-12 col-md-12">
                                        <input type="text" class="form-control mt-lg" name="term"
                                               placeholder="<?= lang('enter_product_name_and_code') ?>"
                                               id="search_text">
                                        <hr class="m0 mb-lg mt-lg"/>
                                    </div>
                                    <script>
                                        $(document).ready(function () {
                                            load_data();

                                            function load_data(query) {
                                                $.ajax({
                                                    url: "<?php echo base_url(); ?>admin/global_controller/items_suggestions/12",
                                                    method: "POST",
                                                    data: {term: query},
                                                    success: function (data) {
                                                        $('#product_result').html(data);
                                                    }
                                                })
                                            }

                                            $('#search_text').keyup(function () {
                                                var search = $(this).val();
                                                if (search != '') {
                                                    load_data(search);
                                                } else {
                                                    load_data();
                                                }
                                            });
                                        });
                                    </script>
                                    <div id="product_result" class="product_result row"></div>
                                </div>
                            </div>
                            <style type="text/css">
                                .dropdown-menu > li > a {
                                    white-space: normal;
                                }

                                .dragger {
                                    background: url(<?= base_url()?>skote_assets/images/dragger.png) 10px 32px no-repeat;
                                    cursor: pointer;
                                }

                                <?php if (!empty($purchase_info)) { ?>
                                .dragger {
                                    background: url(<?= base_url()?>skote_assets/images/dragger.png) 10px 32px no-repeat;
                                    cursor: pointer;
                                }

                                <?php }?>
                                .input-transparent {
                                    box-shadow: none;
                                    outline: 0;
                                    border: 0 !important;
                                    background: 0 0;
                                    padding: 3px;
                                }
                            </style>
                            <hr class="row"/>
                            <div class="row p-3">
                                <div class="col-xl-4">
                                    <div class="row mb-3">
                                        <input type="text" placeholder="<?= lang('search_product_by_name_code'); ?>"
                                           id="purchase_item" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                </div>
                                <div class="col-xl-5">
                                    <div class="row mb-3">
                                        <label class="col-xl-4 col-form-label"><?php echo lang('show_quantity_as'); ?></label>
                                        <div class="col-xl-8" style="display:inline-flex;">
                                            <div class="form-check form-radio-outline form-radio-primary mt mr">
                                                <input type="radio" value="qty" id="<?php echo lang('qty'); ?>"
                                                       name="show_quantity_as"
                                                <?php if (isset($purchase_info) && $purchase_info->show_quantity_as == 'qty') {
                                                    echo 'checked';
                                                } else if (!isset($hours_quantity) && !isset($qty_hrs_quantity)) {
                                                    echo 'checked';
                                                } ?> class="form-check-input">
                                                <label class="form-check-label" for="<?php echo lang('qty'); ?>"><?php echo lang('qty'); ?></label>
                                            </div>
                                            <div class="form-check form-radio-outline form-radio-primary mt mr">
                                                <input type="radio" value="hours" id="<?php echo lang('hours'); ?>"
                                                       name="show_quantity_as" <?php if (isset($purchase_info) && $purchase_info->show_quantity_as == 'hours' || isset($hours_quantity)) { echo 'checked';  } ?> class="form-check-input">
                                                <label class="form-check-label" for="<?php echo lang('hours'); ?>"><?php echo lang('hours'); ?></label>
                                            </div>
                                            <div class="form-check form-radio-outline form-radio-primary mt mr">
                                                <input type="radio" value="qty_hours"
                                                       id="<?php echo lang('qty') . '/' . lang('hours'); ?>"
                                                       name="show_quantity_as"
                                                   <?php if (isset($purchase_info) && $purchase_info->show_quantity_as == 'qty_hours' || isset($qty_hrs_quantity)) {
                                                    echo 'checked';
                                                } ?>  class="form-check-input">
                                                <label class="form-check-label" for="<?php echo lang('qty') . '/' . lang('hours'); ?>"><?php echo lang('qty') . '/' . lang('hours'); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive s_table">
                                <table class="table table-editable table-nowrap align-middle table-edits invoice-items-table items">
                                    <thead style="background: #e8e8e8">
                                    <tr>
                                        <th></th>
                                        <th><?= lang('item_name') ?></th>
                                        <th><?= lang('description') ?></th>
                                        <?php
                                        $purchase_view = config_item('purchase_view');
                                        if (!empty($purchase_view) && $purchase_view == '2') {
                                            ?>
                                            <th class="col-lg-2 col-md-2 col-sm-2"><?= lang('hsn_code') ?></th>
                                        <?php } ?>
                                        <?php
                                        $qty_heading = lang('qty');
                                        if (isset($purchase_info) && $purchase_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                            $qty_heading = lang('hours');
                                        } else if (isset($purchase_info) && $purchase_info->show_quantity_as == 'qty_hours') {
                                            $qty_heading = lang('qty') . '/' . lang('hours');
                                        }
                                        ?>
                                        <th class="qty col-sm-1"><?php echo $qty_heading; ?></th>
                                        <th class="col-lg-2 col-md-2 col-sm-2"><?= lang('price') ?></th>
                                        <th class="col-lg-2 col-md-2 col-sm-2"><?= lang('tax_rate') ?> </th>
                                        <th class="col-lg-1 col-md-1 col-sm-1"><?= lang('total') ?></th>
                                        <th class="col-lg-1 col-md-1 col-sm-1 hidden-print"><?= lang('action') ?></th>
                                    </tr>
                                    </thead>
                                    <?php if (isset($purchase_info)) {
                                        echo form_hidden('merge_current_purchase', $purchase_info->purchase_id);
                                        echo form_hidden('isedit', $purchase_info->purchase_id);
                                    }
                                    ?>
                                    <tbody id="purchaseTable">
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-xl-5"></div>
                                <div class="col-xl-7 pull-right">
                                    <table class="table text-right">
                                        <tbody>
                                            <tr id="subtotal" class="text-end">
                                                <td><span class="bold"><?php echo lang('sub_total'); ?> :</span>
                                                </td>
                                                <td class="subtotal text-end">
                                                </td>
                                            </tr>
                                            <tr id="discount_percent" class="text-end">
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-7 mt"> <span class="bold"><?php echo lang('discount'); ?> (%)</span>
                                                        </div>
                                                        <div class="col-md-5 text-end">
                                                        <?php
                                                        $discount_percent = 0;
                                                        if (isset($purchase_info)) {
                                                            if ($purchase_info->discount_percent != 0) {
                                                                $discount_percent = $purchase_info->discount_percent;
                                                            }
                                                        }
                                                        ?>
                                                        <input type="text" data-parsley-type="number"
                                                               value="<?php echo $discount_percent; ?>"
                                                               class="form-control pull-left" min="0" max="100"
                                                               name="discount_percent">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="discount_percent text-end"></td>
                                            </tr>
                                            <tr class="text-end">
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-7 mt">
                                                            <span class="bold"><?php echo lang('adjustment'); ?></span>
                                                        </div>
                                                        <div class="col-md-5 text-end">
                                                            <input type="text" data-parsley-type="number"
                                                                   value="<?php if (isset($purchase_info)) {
                                                                       echo $purchase_info->adjustment;
                                                                   } else {
                                                                       echo 0;
                                                                   } ?>" class="form-control pull-left"
                                                                   name="adjustment">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="adjustment text-end"></td>
                                            </tr>
                                            <tr class="text-end">
                                                <td><span class="bold"><?php echo lang('total'); ?> :</span>
                                                </td>
                                                <td class="total text-end">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="removed-items"></div>
                            
                            <div class="modal-footer">
                                <input type="submit" value="<?= lang('update') ?>" name="update" class="btn btn-primary btn-block">
                                <button type="button" id="PurchaseReset" class="btn btn-danger pull-left"><?= lang('reset') ?></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        init_items_sortable();
    });
</script>
<?php
if (isset($purchase_info)) {
    $add_items = $this->purchase_model->ordered_items_by_id($purchase_info->purchase_id, true);
    if (!empty($add_items)) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                store('purchaseItems', JSON.stringify(<?= $add_items; ?>));
            });
        </script>
    <?php }
} ?>
<script type="text/javascript">
    var purchaseItems = {};
    if (localStorage.getItem('remove_purchase')) {
        if (localStorage.getItem('purchaseItems')) {
            localStorage.removeItem('purchaseItems');
        }
        localStorage.removeItem('remove_purchase');
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        <?php
        $editPurchase = $this->uri->segment(4);
        $edit_purchase = $this->session->userdata('edit_purchase');
        if(empty($editPurchase) && !empty($edit_purchase)){
        ?>
        if (get('purchaseItems')) {
            remove('purchaseItems');
        }
        <?php
        $this->session->unset_userdata('edit_purchase');
        }
        ?>
    });
</script>
<?php include_once 'assets/js/purchase.php'; ?>

<!-- Script -->
 <script type="text/javascript">
     $(document).ready(function(){
        $('#contentTable').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
             'url':'<?=base_url()?>admin/datatable/manage_purchase'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_purchase_"+iDisplayIndex);
            return nRow;
          },
          'columns': [
             <?php if (is_company_column_ag()) { ?>
                { data: 'companies_id' },
             <?php } ?>             
             { data: 'reference_no' },
             { data: 'supplier' },
             { data: 'purchase_date' },
             { data: 'due_amount' },
             { data: 'status' },
             { data: 'action' },
          ]
        });
     });
 </script>