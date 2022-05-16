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
$created = can_action('149', 'created');
$edited = can_action('149', 'edited');
$deleted = can_action('149', 'deleted');
if (!empty($supplier_info)) {
    $supplier_id = $supplier_info->supplier_id;
    $companies_id = $supplier_info->companies_id;
} else {
    $supplier_id = null;
    $companies_id = null;
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                 <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="<?=base_url("admin/purchase/supplier")?>" ><?= lang('manage') . ' ' . lang('supplier') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab"><?= lang('new') . ' ' . lang('supplier') ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <h4 class="card-title mb-4"><?= lang('manage') . ' ' . lang('supplier') ?></h4>
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="contentTable">
                                <thead>
                                    <tr>
                                        <?php super_admin_opt_th() ?>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('address') ?></th>
                                        <th><?= lang('email') ?></th>
                                        <th><?= lang('phone') ?></th>
                                        <?php if (!empty($edited) || !empty($deleted)) { ?>
                                            <th class="col-options no-sort"><?= lang('action') ?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <?php /* ?><tbody>
                                    <?php
                                    if (!empty($all_supplier)) {
                                        foreach ($all_supplier as $v_supplier) {
                                            $can_edit = $this->purchase_model->can_action('tbl_suppliers', 'edit', array('supplier_id' => $v_supplier->supplier_id));
                                            $can_delete = $this->purchase_model->can_action('tbl_suppliers', 'delete', array('supplier_id' => $v_supplier->supplier_id));
                                            ?>
                                            <tr id="table_supplier_<?= $v_supplier->supplier_id ?>">
                                                <?php super_admin_opt_td($v_supplier->companies_id) ?>
                                                <td><?= $v_supplier->name ?></td>
                                                <td><?= $v_supplier->address ?></td>
                                                <td><?= $v_supplier->email ?></td>
                                                <td><?= $v_supplier->phone ?></td>
                                                <td>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                        <?= btn_edit('admin/purchase/supplier/' . $v_supplier->supplier_id) ?>
                                                    <?php }
                                                    if (!empty($can_delete) && !empty($deleted)) {
                                                        ?>
                                                        <?php echo ajax_anchor(base_url("admin/purchase/delete_supplier/$v_supplier->supplier_id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_supplier_" . $v_supplier->supplier_id)); ?>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        };
                                    }
                                    ?>
                                </tbody><?php */ ?>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($created) || !empty($edited)) { ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                        <?php echo form_open(base_url('admin/purchase/save_supplier/' . $supplier_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 br pv">
                                    <div class="row">
                                        <?php super_admin_form($companies_id, 4, 7) ?>
                                        <div class="row mb-3">
                                            <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('name') ?> <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($supplier_info)) {
                                                    echo $supplier_info->name;
                                                }
                                                ?>" name="name" required="">
                                            </div>

                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('email') ?> <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($supplier_info)) {
                                                    echo $supplier_info->email;
                                                }
                                                ?>" name="email" required="">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('phone') ?> <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($supplier_info)) {
                                                    echo $supplier_info->phone;
                                                }
                                                ?>" name="phone" required="">
                                            </div>
                                        </div>
                                        <!-- End discount Fields -->
                                        <div class="row mb-3 terms">
                                            <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('address') ?> </label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <textarea name="address" id="elm1" class="form-control"><?php
                                                    if (!empty($supplier_info)) {
                                                        echo $supplier_info->address;
                                                    }
                                                    ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('city') ?></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($supplier_info)) {
                                                    echo $supplier_info->city;
                                                }
                                                ?>" name="city">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 br pv">
                                    <div class="row">
                                        <div class="row mb-3">
                                            <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('state') ?></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($supplier_info)) {
                                                    echo $supplier_info->state;
                                                }
                                                ?>" name="state">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('zip_code') ?></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($supplier_info)) {
                                                    echo $supplier_info->zip_code;
                                                }
                                                ?>" name="zip_code">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label
                                                class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('country') ?></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <select name="country" class="form-control select_box"
                                                        style="width: 100%">
                                                    <optgroup label="Default Country">
                                                        <option
                                                            value="<?= $this->config->item('company_country') ?>"><?= $this->config->item('company_country') ?></option>
                                                    </optgroup>
                                                    <optgroup label="<?= lang('other_countries') ?>">
                                                        <?php if (!empty($countries)): foreach ($countries as $country): ?>
                                                            <option
                                                                value="<?= $country->value ?>" <?= (!empty($supplier_info->country) && $supplier_info->country == $country->value ? 'selected' : NULL) ?>><?= $country->value ?>
                                                            </option>
                                                            <?php
                                                        endforeach;
                                                        endif;
                                                        ?>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('tax') ?></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($supplier_info)) {
                                                    echo $supplier_info->tax;
                                                }
                                                ?>" name="tax">
                                            </div>
                                        </div>

                                        <div class="row mb-3" id="border-none">
                                            <label class="col-xl-4 col-form-label"><?= lang('permission') ?> <span
                                                        class="required">*</span></label>
                                            <div class="col-xl-7">
                                                <div class="form-check form-radio-outline form-radio-primary mb-3">
                                                    <input id="everyone" <?php
                                                        if (!empty($supplier_info->permission) && $supplier_info->permission == 'all') {
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
                                                        if (!empty($supplier_info->permission) && $supplier_info->permission != 'all') {
                                                            echo 'checked';
                                                        } elseif (empty($supplier_info)) {
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
                                            if (!empty($supplier_info->permission) && $supplier_info->permission != 'all') {
                                                echo 'show';
                                            }
                                            ?>" id="permission_user_1">
                                            <label class="col-lg-4 col-md-4 col-sm-4 col-form-label"><?= lang('select') . ' ' . lang('users') ?><span class="required">*</span></label>
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
                                                            if (!empty($supplier_info->permission) && $supplier_info->permission != 'all') {
                                                                $get_permission = json_decode($supplier_info->permission);
                                                                foreach ($get_permission as $user_id => $v_permission) {
                                                                    if ($user_id == $v_user->user_id) {
                                                                        echo 'checked';
                                                                    }
                                                                }

                                                            }
                                                            ?>  value="<?= $v_user->user_id ?>" name="assigned_to[]" data-name="<?= $v_user->username;?>"  class="form-check-input" id ="user_<?= $v_user->user_id ?>">
                                                        <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?>
                                                        </label>
                                                    </div>
                                                    <div class="action_1 p
                                                        <?php
                                                        if (!empty($supplier_info->permission) && $supplier_info->permission != 'all') {
                                                            $get_permission = json_decode($supplier_info->permission);

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

                                                                if (!empty($supplier_info->permission) && $supplier_info->permission != 'all') {
                                                                    $get_permission = json_decode($supplier_info->permission);

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

                                                                if (!empty($supplier_info->permission) && $supplier_info->permission != 'all') {
                                                                    $get_permission = json_decode($supplier_info->permission);
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
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="card-footer bg-transparent border-top text-muted">
                            <button type="submit" class="btn btn-xs btn-primary ml"><?= lang('update') ?></button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script -->
 <script type="text/javascript">
     $(document).ready(function(){
        $('#contentTable').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
             'url':'<?=base_url()?>admin/datatable/purchase_supplier'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_supplier_"+iDisplayIndex);
            return nRow;
          },
          'columns': [
             <?php if (is_company_column_ag()) { ?>
                { data: 'companies_id' },
             <?php } ?>             
             { data: 'name' },
             { data: 'address' },
             { data: 'email' },
             { data: 'phone' },
             { data: 'action' },
          ]
        });
     });
 </script>