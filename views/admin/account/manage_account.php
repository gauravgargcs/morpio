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
$created = can_action('36', 'created');
$edited = can_action('36', 'edited');
$deleted = can_action('36', 'deleted');
if (!empty($account_info)) {
    $account_id = $account_info->account_id;
    $companies_id = $account_info->companies_id;
} else {
    $account_id = null;
    $companies_id = null;
}

?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                 <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="<?= base_url().'admin/account/manage_account' ?>"><?= lang('manage_account') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab"><?= lang('new_account') ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <h4 class="card-title mb-4"><?= lang('manage_account') ?></h4>
                        <!-- <div class="table-responsive"> -->
                            <table class="table table-striped dt-responsive nowrap w-100" id="list_manage_account_datatable">
                                <thead>
                                    <tr>
                                        <?php super_admin_opt_th() ?>
                                        <th><?= lang('account') ?></th>
                                        <th><?= lang('description') ?></th>
                                        <?php if (!empty($edited) || !empty($deleted)) { ?>
                                            <th class="col-options no-sort"><?= lang('action') ?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                                $total_balance = 0;
                                if (!empty($all_account)) {
                                    foreach ($all_account as $v_account) {
                                        $can_edit = $this->account_model->can_action('tbl_accounts', 'edit', array('account_id' => $v_account->account_id));
                                        $can_delete = $this->account_model->can_action('tbl_accounts', 'delete', array('account_id' => $v_account->account_id));
                                        $total_balance += $v_account->balance;
                                        ?>
                                        <tr id="table_account_<?= $v_account->account_id ?>">
                                            <?php super_admin_opt_td($v_account->companies_id) ?>
                                            <td><?= $v_account->account_name ?></td>
                                            <td><?= $v_account->description ?></td>
                                            <td><?= display_money($v_account->balance, $currency->symbol); ?></td>
                                            <td>
                                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                    <?= btn_edit('admin/account/manage_account/' . $v_account->account_id) ?>
                                                <?php }
                                                if (!empty($can_delete) && !empty($deleted)) {
                                                    ?>
                                                    <?php echo ajax_anchor(base_url("admin/account/delete_account/$v_account->account_id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_account_" . $v_account->account_id)); ?>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    };
                                }
                                if (!empty(super_admin())) {
                                    $col = 3;
                                } else {
                                    $col = 2;
                                }
                                ?>
                                <tr class="total_amount">
                                    <td colspan="<?= $col?>" style="text-align: right;"><strong><?= lang('total_amount') ?> : </strong>
                                    </td>
                                    <td colspan="2" style="padding-left: 8px;">
                                        <strong><?= display_money($total_balance, $currency->symbol); ?></strong></td>
                                </tr>
                                </tbody>
                            </table>
                        <!-- </div> -->
                    </div>
            
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                        <?php echo form_open(base_url('admin/account/save_account/' . $account_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <?php super_admin_form($companies_id, 3, 5) ?>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('account_name') ?> <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-5 col-md-5 col-sm-7">
                                <input type="text" class="form-control" value="<?php
                                if (!empty($account_info)) {
                                    echo $account_info->account_name;
                                }
                                ?>" name="account_name" required="">
                            </div>

                        </div>
                        <!-- End discount Fields -->
                        <div class="row mb-3 terms">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('description') ?> </label>
                            <div class="col-lg-5 col-md-5 col-sm-7">
                            <textarea name="description" id="elm1" class="form-control"><?php
                                if (!empty($account_info)) {
                                    echo $account_info->description;
                                }
                                ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('initial_balance') ?> <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-5 col-md-5 col-sm-7">
                                <input type="text" data-parsley-type="number" class="form-control" value="<?php
                                if (!empty($account_info)) {
                                    echo $account_info->balance;
                                }
                                ?>" name="balance" required="">
                            </div>

                        </div>
                        <div class="row mb-3" id="border-none">
                            <label class="col-xl-3 col-form-label"><?= lang('permission') ?> <span
                                        class="required">*</span></label>
                            <div class="col-xl-5">
                                <div class="form-check form-radio-outline form-radio-primary mb-3">
                                    <input id="everyone" <?php
                                        if (!empty($account_info->permission) && $account_info->permission == 'all') {
                                            echo 'checked';
                                        } 
                                        ?> type="radio" name="permission" value="everyone" class="form-check-input">
                                    <label class="form-check-label" for="everyone"><?= lang('everyone') ?>
                                        <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip"
                                           data-bs-placement="top"></i>
                                    </label>
                                </div>
                                <div class="form-check form-radio-outline form-radio-primary mb-3">
                                    <input id="custom_permission" <?php
                                        if (!empty($account_info->permission) && $account_info->permission != 'all') {
                                            echo 'checked';
                                        }elseif (empty($account_info)) {
                                            echo 'checked';
                                        }
                                        ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                                    <label class="form-check-label" for="custom_permission">
                                        <?= lang('custom_permission') ?>
                                        <i  title="<?= lang('permission_for_customization') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 <?php
                            if (!empty($account_info->permission) && $account_info->permission != 'all') {
                                echo 'show';
                            }
                            ?>" id="permission_user_1">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('select') . ' ' . lang('users') ?><span class="required">*</span></label>
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
                                <div class="form-check form-check-primary mb-3">
                                    <input type="checkbox"
                                        <?php
                                        if (!empty($account_info->permission) && $account_info->permission != 'all') {
                                            $get_permission = json_decode($account_info->permission);
                                            foreach ($get_permission as $user_id => $v_permission) {
                                                if ($user_id == $v_user->user_id) {
                                                    echo 'checked';
                                                }
                                            }

                                        }
                                        ?>  value="<?= $v_user->user_id ?>" name="assigned_to[]"  data-name="<?= $v_user->username;?>" class="form-check-input" id ="user_<?= $v_user->user_id ?>">
                                    <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?>
                                    </label>
                                </div>
                                <div class="action_1 p
                                    <?php
                                    if (!empty($account_info->permission) && $account_info->permission != 'all') {
                                        $get_permission = json_decode($account_info->permission);

                                        foreach ($get_permission as $user_id => $v_permission) {
                                            if ($user_id == $v_user->user_id) {
                                                echo 'show';
                                            }
                                        }
                                    }
                                    ?>" id="action_1<?= $v_user->user_id ?>">
                                    <div class="form-check form-check-primary mb-3 mr">         
                                        <input id="view_<?= $v_user->user_id ?>" checked type="checkbox" name="action_1<?= $v_user->user_id ?>[]" disabled value="view" class="form-check-input">
                                        <label class="form-check-label" for="view_<?= $v_user->user_id ?>"><?= lang('view') ?></label>
                                    </div>
                                    <div class="form-check form-check-primary mb-3 mr">         
                                        <input id="edit_<?= $v_user->user_id ?>"
                                            <?php

                                            if (!empty($account_info->permission) && $account_info->permission != 'all') {
                                                $get_permission = json_decode($account_info->permission);

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

                                            if (!empty($account_info->permission) && $account_info->permission != 'all') {
                                                $get_permission = json_decode($account_info->permission);
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
                                    <input id="<?= $v_user->user_id ?>" type="hidden" name="action_<?= $v_user->user_id ?>[]" value="view">
                                </div>
                                <?php } ?>
                                </div>
                            <?php } ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"></label>
                            <div class="col-lg-5 col-md-5 col-sm-7">
                                <button type="submit"  class="btn btn-primary"><?= lang('create_acount') ?></button>
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