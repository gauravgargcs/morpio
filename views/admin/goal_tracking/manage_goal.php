<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('69', 'created');
$edited = can_action('69', 'edited');
$deleted = can_action('69', 'deleted');
?>
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
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                 <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="<?= base_url().'admin/goal_tracking' ?>"><?= lang('goal_tracking') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab">
                        <?= lang('new_goal') ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <h4 class="card-title mb-4"><?= lang('goal_tracking') ?></h4>
                        <table class="table table-striped dt-responsive nowrap w-100" id="list_goal_datatable">
                            <thead>
                            <tr>
                                <?php super_admin_opt_th() ?>
                                <th><?= lang('subject') ?></th>
                                <th><?= lang('type') ?></th>
                                <th><?= lang('achievement') ?></th>
                                <th><?= lang('start_date') ?></th>
                                <th><?= lang('end_date') ?></th>
                                <th><?= lang('progress') ?></th>
                                <th class="col-options no-sort"><?= lang('action') ?></th>
                            </tr>
                            </thead>
                            <tbody>

                                <?php
                                $all_goal_tracking = $this->items_model->get_permission('tbl_goal_tracking');
                                if (!empty($all_goal_tracking)):foreach ($all_goal_tracking as $v_goal_tracking):

                                    $goal_type_info = $this->db->where('goal_type_id', $v_goal_tracking->goal_type_id)->get('tbl_goal_type')->row();

                                    $progress = $this->items_model->get_progress($v_goal_tracking);

                                    $can_edit = $this->items_model->can_action('tbl_goal_tracking', 'edit', array('goal_tracking_id' => $v_goal_tracking->goal_tracking_id));
                                    $can_delete = $this->items_model->can_action('tbl_goal_tracking', 'delete', array('goal_tracking_id' => $v_goal_tracking->goal_tracking_id)); ?>

                                    <tr>
                                        <?php super_admin_opt_td($v_goal_tracking->companies_id) ?>
                                        <td><a class="text-info"
                                               href="<?= base_url() ?>admin/goal_tracking/goal_details/<?= $v_goal_tracking->goal_tracking_id ?>"><?php echo $v_goal_tracking->subject; ?></a>
                                        </td>
                                        <td>
                                            <span data-bs-toggle="tooltip" data-bs-placement="top"
                                          title="<?= $goal_type_info->description ?>"><?= lang($goal_type_info->type_name) ?></span>
                                        </td>
                                        <td><?= $v_goal_tracking->achievement ?></td>
                                        <td><?= display_datetime($v_goal_tracking->start_date); ?></td>
                                        <td><?= display_datetime($v_goal_tracking->end_date); ?></td>
                                        <td class="col-sm-1" style="padding-bottom: 0px;padding-top: 3px">
                                            <div class="inline ">
                                                <div class="easypiechart text-success"
                                                     data-percent="<?= $progress['progress'] ?>"
                                                     data-line-width="5" data-track-Color="#f0f0f0"
                                                     data-bar-color="#<?php
                                                     if ($progress['progress'] == 100) {
                                                         echo '8ec165';
                                                     } elseif ($progress['progress'] >= 40 && $progress['progress'] <= 50) {
                                                         echo '5d9cec';
                                                     } elseif ($progress['progress'] >= 51 && $progress['progress'] <= 99) {
                                                         echo '7266ba';
                                                     } else {
                                                         echo 'fb6b5b';
                                                     }
                                                     ?>" data-rotate="270" data-scale-Color="false"
                                                     data-size="50"
                                                     data-animate="2000">
                                                                                <span class="small "><?= $progress['progress'] ?>
                                                                                    %</span>
                                                    <span class="easypie-text"><strong><?= lang('done') ?></strong></span>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <?= btn_view('admin/goal_tracking/goal_details/' . $v_goal_tracking->goal_tracking_id) ?>
                                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                <?= btn_edit('admin/goal_tracking/index/' . $v_goal_tracking->goal_tracking_id) ?>
                                            <?php }
                                            if (!empty($can_delete) && !empty($deleted)) {
                                                ?>
                                                <?= btn_delete('admin/goal_tracking/delete_goal/' . $v_goal_tracking->goal_tracking_id) ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (!empty($created) || !empty($edited)){
                    if (!empty($goal_info)) {
                        $goal_tracking_id = $goal_info->goal_tracking_id;
                        $companies_id = $goal_info->companies_id;
                    } else {
                        $goal_tracking_id = null;
                        $companies_id = null;
                    }
                    ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                        <?php echo form_open(base_url('admin/goal_tracking/save_goal_tracking/' . $goal_tracking_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <?php super_admin_form($companies_id, 3, 5) ?>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('subject') ?> <span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-5 col-md-5 col-sm-7">
                                <input type="text" class="form-control" value="<?php
                                if (!empty($goal_info)) {
                                    echo $goal_info->subject;
                                }
                                ?>" name="subject" required="">
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('goal') . ' ' . lang('type') ?> <span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-5 col-md-5 col-sm-7">
                                <select name="goal_type_id" class="form-control select_box" style="width: 100%"
                                        id="goal_type_id"
                                        required="">
                                    <?php
                                    $goal_type = $this->db->get('tbl_goal_type')->result();
                                    if (!empty($goal_type)) {
                                        foreach ($goal_type as $v_goal_type) {
                                            ?>
                                            <option
                                                    value="<?= $v_goal_type->goal_type_id ?>" <?php
                                            if (!empty($goal_info->goal_type_id)) {
                                                echo $v_goal_type->goal_type_id == $goal_info->goal_type_id ? 'selected' : '';
                                            }
                                            ?>><?= lang($v_goal_type->type_name) ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>
                        <div class="row mb-3 account" id="account"
                             style="<?php if (!empty($goal_info->goal_type_id) && $goal_info->goal_type_id == 2 || !empty($goal_info->goal_type_id) && $goal_info->goal_type_id == 4) {
                                 echo '';
                             } else {
                                 echo 'display:none';
                             }; ?>">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label mt-lg"><?= lang('account') ?> <span
                                        class="text-danger">*</span> </label>
                            <div class="col-lg-5 col-md-5 col-sm-7 mt-lg">
                                <select class="form-control select_box account" style="width: 100%" name="account_id"
                                        id="account_id"
                                    <?php if (empty($goal_info->account_id)) {
                                        echo 'disabled';
                                    }; ?>>
                                    <?php
                                    $account_info = $this->items_model->get_permission('tbl_accounts');

                                    if (!empty($account_info)) {
                                        foreach ($account_info as $v_account) {
                                            ?>
                                            <option value="<?= $v_account->account_id ?>"
                                                <?php
                                                if (!empty($goal_info->account_id)) {
                                                    echo $goal_info->account_id == $v_account->account_id ? 'selected' : '';
                                                }
                                                ?>
                                            ><?= $v_account->account_name ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('achievement') ?> <span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-5 col-md-5 col-sm-7">
                                <input type="number" class="form-control" value="<?php
                                if (!empty($goal_info)) {
                                    echo $goal_info->achievement;
                                }
                                ?>" name="achievement" required="">
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('start_date') ?> <span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-5 col-md-5 col-sm-7">
                                <div class="input-group">
                                    <input type="text" name="start_date" class="form-control datepicker"
                                           value="<?php
                                           if (!empty($goal_info->start_date)) {
                                               echo date('d-m-Y H-i', strtotime($goal_info->start_date));
                                           } else {
                                               echo date('d-m-Y H-i');
                                           }
                                           ?>" data-date-format="<?= config_item('date_picker_format'); ?>">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>

                                </div>
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('end_date') ?> <span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-5 col-md-5 col-sm-7">
                                <div class="input-group">
                                    <input type="text" name="end_date" class="form-control datepicker"
                                           value="<?php
                                           if (!empty($goal_info->end_date)) {
                                               echo date('d-m-Y H-i', strtotime($goal_info->end_date));
                                           } else {
                                               echo date('d-m-Y H-i');
                                           }
                                           ?>" data-date-format="<?= config_item('date_picker_format'); ?>">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                
                                </div>
                            </div>

                        </div>
                        <!-- End discount Fields -->
                        <div class="row mb-3 terms">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('description') ?> </label>
                            <div class="col-lg-5 col-md-5 col-sm-7">
                            <textarea name="description" id="elm1" class="form-control"><?php
                                if (!empty($goal_info)) {
                                    echo $goal_info->description;
                                }
                                ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3" id="border-none">
                            <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('permission') ?> <span
                                        class="required">*</span></label>
                            <div class="col-lg-7 col-md-7 col-sm-7">
                                <div class="form-check form-radio-outline form-radio-primary mb-3">
                                    <input id="everyone" <?php
                                        if (!empty($goal_info->permission) && $goal_info->permission == 'all') {
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
                                        if (!empty($goal_info->permission) && $goal_info->permission != 'all') {
                                            echo 'checked';
                                        } elseif (empty($goal_info)) {
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
                            if (!empty($goal_info->permission) && $goal_info->permission != 'all') {
                                echo 'show';
                            }
                            ?>" id="permission_user_1">
                            <label for="field-1"
                                   class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('select') . ' ' . lang('users') ?>
                                <span  class="required">*</span></label>
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
                                            if (!empty($goal_info->permission) && $goal_info->permission != 'all') {
                                                $get_permission = json_decode($goal_info->permission);
                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        echo 'checked';
                                                    }
                                                }

                                            }
                                            ?>  value="<?= $v_user->user_id ?>" name="assigned_to[]" class="form-check-input" id ="user_<?= $v_user->user_id ?>" data-name="<?= $v_user->username;?>">
                                        <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?>
                                        </label>
                                    </div>
                                    <div class="action_1 p
                                        <?php

                                        if (!empty($goal_info->permission) && $goal_info->permission != 'all') {
                                            $get_permission = json_decode($goal_info->permission);

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
                                            <input  id="edit_<?= $v_user->user_id ?>" <?php

                                                    if (!empty($goal_info->permission) && $goal_info->permission != 'all') {
                                                        $get_permission = json_decode($goal_info->permission);

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
                                                <input id="delete_<?= $v_user->user_id ?>"
                                                    <?php

                                                    if (!empty($goal_info->permission) && $goal_info->permission != 'all') {
                                                        $get_permission = json_decode($goal_info->permission);
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
                                    <?php  } ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"></label>
                            <div class="col-lg-6 col-md-6 col-sm-8">
                                <div class="form-check form-check-primary mb-3">
                                    <input type="checkbox" <?php
                                        if (!empty($goal_info->notify_goal_achive) && $goal_info->notify_goal_achive == 'on') {
                                            echo "checked=\"checked\"";
                                        }
                                        ?> name="notify_goal_achive" class="form-check-input" id="notify_goal_achive">
                                    <label class="form-check-label" for="notify_goal_achive"> <?= lang('notify_goal_achive') ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"></label>
                            <div class="col-lg-6 col-md-6 col-sm-8">
                                <div class="form-check form-check-primary mb-3">
                                    <input type="checkbox" <?php
                                        if (!empty($goal_info->notify_goal_not_achive) && $goal_info->notify_goal_not_achive == 'on') {
                                            echo "checked=\"checked\"";
                                        }
                                        ?> name="notify_goal_not_achive" class="form-check-input" id="notify_goal_not_achive">
                                    <label class="form-check-label" for="notify_goal_not_achive"><?= lang('notify_goal_not_achive') ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"></label>
                            <div class="col-lg-5 col-md-5 col-sm-7">
                                <button type="submit" class="btn btn-xs btn-primary"><?= lang('update') ?></button>
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

<!-- end -->