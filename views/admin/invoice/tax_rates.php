<?= message_box('success'); ?>
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
$created = can_action('16', 'created');
$edited = can_action('16', 'edited');
$deleted = can_action('16', 'deleted');
?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="#manage" data-bs-toggle="tab"><?= lang('tax_rates') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#new" data-bs-toggle="tab"><?= lang('new_tax_rate') ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content bg-white">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        
                        <h4 class="card-title mb-4 mt"><strong><?= lang('tax_rates') ?></strong></h4>
                            
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="invoice_reminder_datatable">
                                <thead>
                                    <tr>
                                        <?php super_admin_opt_th() ?>
                                        <th><?= lang('tax_rate_name') ?></th>
                                        <th><?= lang('tax_rate_percent') ?></th>
                                        <?php if (!empty($edited) || !empty($deleted)) { ?>
                                            <th class="hidden-print"><?= lang('action') ?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $all_tax_rates = get_result('tbl_tax_rates');
                                    if (!empty($all_tax_rates)) {
                                        foreach ($all_tax_rates as $v_tax_rates) {
                                            $can_delete = $this->invoice_model->can_action('tbl_tax_rates', 'delete', array('tax_rates_id' => $v_tax_rates->tax_rates_id));
                                            $can_edit = $this->invoice_model->can_action('tbl_tax_rates', 'edit', array('tax_rates_id' => $v_tax_rates->tax_rates_id));
                                            ?>
                                            <tr id="tax_table_<?= $v_tax_rates->tax_rates_id ?>">
                                                <?php super_admin_opt_td($v_tax_rates->companies_id) ?>
                                                <td><?= $v_tax_rates->tax_rate_name ?></td>
                                                <td><?= $v_tax_rates->tax_rate_percent ?> %</td>
                                                <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                    <td>
                                                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                            <?= btn_edit('admin/invoice/tax_rates/edit_tax_rates/' . $v_tax_rates->tax_rates_id) ?>
                                                        <?php }
                                                        if (!empty($can_delete) && !empty($deleted)) {
                                                            ?>
                                                            <?php echo ajax_anchor(base_url("admin/invoice/tax_rates/delete_tax_rates/" . $v_tax_rates->tax_rates_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#tax_table_" . $v_tax_rates->tax_rates_id)); ?>
                                                        <?php } ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($created) || !empty($edited)) {
                        if (!empty($tax_rates_info)) {
                            $tax_rates_id = $tax_rates_info->tax_rates_id;
                            $companies_id = $tax_rates_info->companies_id;
                        } else {
                            $tax_rates_id = null;
                            $companies_id = null;
                        }
                    ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="new">
                        <div class="card-body">
                            <?php echo form_open(base_url('admin/invoice/save_tax_rate/' . $tax_rates_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                            <?php super_admin_form($companies_id,4,5) ?>
                            <div class="row mb-3">
                                <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('tax_rate_name') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <input type="text" class="form-control" required value="<?php
                                    if (!empty($tax_rates_info)) {
                                        echo $tax_rates_info->tax_rate_name;
                                    }
                                    ?>" name="tax_rate_name">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('tax_rate_percent') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <input type="text" data-parsley-type="number" class="form-control" required value="<?php
                                    if (!empty($tax_rates_info)) {
                                        echo $tax_rates_info->tax_rate_percent;
                                    }
                                    ?>" name="tax_rate_percent">
                                </div>
                            </div>
                            <div class="row mb-3" id="border-none">
                                <label for="field-1" class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('permission') ?> <span
                                        class="required">*</span></label>
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <div class="form-check form-radio-outline form-radio-primary mt mr">
                                        <input id="everyone" <?php
                                            if (!empty($tax_rates_info) && $tax_rates_info->permission == 'all') {
                                                echo 'checked';
                                            }
                                            ?> type="radio" name="permission" value="everyone"  class="form-check-input">
                                        <label class="form-check-label" for="everyone"><?= lang('everyone') ?>
                                            <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                        </label>
                                    </div>
                                    <div class="form-check form-radio-outline form-radio-primary mt mr">
                                        <input id="custom_permission" <?php
                                            if (!empty($tax_rates_info) && $tax_rates_info->permission != 'all') {
                                                echo 'checked';
                                            } elseif (empty($tax_rates_info)) {
                                                echo 'checked';
                                            }
                                            ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                                        <label class="form-check-label" for="custom_permission"><?= lang('custom_permission') ?>
                                            <i title="<?= lang('permission_for_customization') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3 <?php
                                if (!empty($tax_rates_info) && $tax_rates_info->permission != 'all') {
                                    echo 'show';
                                }
                                ?>" id="permission_user_1">
                                <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('select') . ' ' . lang('users') ?>
                                    <span class="required">*</span></label>
                                <div class="col-lg-5 col-md-5 col-sm-5">
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
                                        <div class="form-check form-check mb-3 mr">
                                            <input type="checkbox"
                                                <?php
                                                if (!empty($tax_rates_info) && $tax_rates_info->permission != 'all') {
                                                    $get_permission = json_decode($tax_rates_info->permission);
                                                    foreach ($get_permission as $user_id => $v_permission) {
                                                        if ($user_id == $v_user->user_id) {
                                                            echo 'checked';
                                                        }
                                                    }

                                                }
                                                ?> value="<?= $v_user->user_id ?>" name="assigned_to[]"  data-name="<?= $v_user->username;?>"  class="form-check-input" id="user_<?= $v_user->user_id ?>">
                                            <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?>
                                            </label>
                                        </div>
                                        <div class="action_1 p
                                            <?php
                                            if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                                $get_permission = json_decode($invoice_info->permission);

                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        echo 'show';
                                                    }
                                                }

                                            }
                                            ?> " id="action_1<?= $v_user->user_id ?>">
                                            <div class="form-check form-check mb-3 mr-5">
                                                <input class="form-check-input" type="checkbox" id="view_<?= $v_user->user_id ?>" checked name="action_<?= $v_user->user_id ?>[]" disabled  value="view">
                                                <label class="form-check-label" for="view_<?= $v_user->user_id ?>">
                                                    <?= lang('can') . ' ' . lang('view') ?>
                                                </label>
                                            </div>
                                            
                                            <div class="form-check form-check mb-3 mr-5">
                                                <input class="form-check-input" type="checkbox" value="edit" name="action_<?= $v_user->user_id ?>[]" id="edit_<?= $v_user->user_id ?>"
                                                    <?php

                                                    if (!empty($tax_rates_info) && $tax_rates_info->permission != 'all') {
                                                        $get_permission = json_decode($tax_rates_info->permission);

                                                        foreach ($get_permission as $user_id => $v_permission) {
                                                            if ($user_id == $v_user->user_id) {
                                                                if (in_array('edit', $v_permission)) {
                                                                    echo 'checked';
                                                                };

                                                            }
                                                        }

                                                    }
                                                    ?>>
                                                <label class="form-check-label" for="edit_<?= $v_user->user_id ?>">
                                                    <?= lang('can') . ' ' . lang('edit') ?>
                                                </label>
                                            </div>

                                            <div class="form-check form-check mb-3 mr-5">
                                                <input class="form-check-input" name="action_<?= $v_user->user_id ?>[]" type="checkbox" value="delete" id="delete_<?= $v_user->user_id ?>"
                                                    <?php

                                                    if (!empty($tax_rates_info) && $tax_rates_info->permission != 'all') {
                                                        $get_permission = json_decode($tax_rates_info->permission);
                                                        foreach ($get_permission as $user_id => $v_permission) {
                                                            if ($user_id == $v_user->user_id) {
                                                                if (in_array('delete', $v_permission)) {
                                                                    echo 'checked';
                                                                };
                                                            }
                                                        }

                                                    }
                                                    ?>>
                                                <label class="form-check-label" for="delete_<?= $v_user->user_id ?>">
                                                    <?= lang('can') . ' ' . lang('delete') ?>
                                                </label>
                                            </div>

                                            <input id="<?= $v_user->user_id ?>" type="hidden"  name="action_<?= $v_user->user_id ?>[]" value="view">
                                        </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"></label>
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <button type="submit" name="save" value="1"
                                            class="btn btn-primary "><?php echo !empty($tax_rates_info->tax_rate_name) ? lang('update') : lang('save') ?></button>
                                    <button type="submit" name="save" value="2"
                                            class="btn btn-primary "><?php echo !empty($tax_rates_info->tax_rate_name) ? lang('update') . ' & ' . lang('add_more') : lang('save') . ' & ' . lang('add_more') ?></button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>