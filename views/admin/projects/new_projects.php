<?php
$client_project = $this->uri->segment(4);
if ($client_project == 'client_project') {
    $client_id = $this->uri->segment(5);
}
if (!empty($project_info)) {
    $projects_id = $project_info->project_id;
    $companies_id = $project_info->companies_id;
} else {
    $projects_id = null;
    $companies_id = null;
}
?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18">  <?php if(!$projects_id){
              echo lang('new_project'); 
            }else{
              echo lang('edit_project'); 

            } ?> 
            </h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row mb-3">
    <div class="mb-lg pull-left">
        <div class="pull-left pr-lg">
            <a href="<?= base_url() ?>admin/projects" class="btn btn-xs btn-primary pull-right" data-bs-toggle="tooltip"  data-bs-placement="top" title="<?= lang('all_project') ?>"><i class="fa fa-undo"> </i><?= ' ' . lang('all_project') ?>
            </a>
        </div>
        <div class="float-end">
            <a class="btn btn-xs btn-danger" href="<?= base_url() ?>admin/projects/import"><?= lang('import') . ' ' . lang('project') ?></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="card"> 
        <div class="card-body">
            <h4 class="card-title mb-4"><?= lang('project_details') ?></h4>
            <?php echo form_open(base_url('admin/projects/saved_project/' . $projects_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
            <div class="row">
                <div class="col-md-7">
                    <?php super_admin_form($companies_id, 3, 8) ?>
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label"><?= lang('select_client') ?> <span  class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <select name="client_id" class="form-control select_box" style="width: 100%" required="">
                                <option value=""><?= lang('select_client') ?></option>
                                <?php
                                $all_client = by_company('tbl_client', 'client_id', null, $companies_id);
                                if (!empty($all_client)) {
                                    foreach ($all_client as $v_client) {
                                        ?>
                                        <option value="<?= $v_client->client_id ?>" <?php
                                        if (!empty($project_info) && $project_info->client_id == $v_client->client_id) {
                                            echo 'selected';
                                        } else if (!empty($client_id) && $client_id == $v_client->client_id) {
                                            echo 'selected';
                                        }
                                        ?>><?= $v_client->name ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label"><?= lang('project_name') ?> <span
                                    class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?php
                            if (!empty($project_info)) {
                                echo $project_info->project_name;
                            }
                            ?>" name="project_name" required="">
                        </div>
                    </div>
                    <script src="<?= base_url() ?>assets/js/jquery-ui.js"></script>
                    <?php $direction = $this->session->userdata('direction');
                    if (!empty($direction) && $direction == 'rtl') {
                        $RTL = 'on';
                    } else {
                        $RTL = config_item('RTL');
                    }
                    ?>
                    <?php
                    if (!empty($RTL)) { ?>
                        <!-- bootstrap-editable -->
                        <script type="text/javascript"
                                src="<?= base_url() ?>assets/plugins/jquery-ui/jquery.ui.slider-rtl.js"></script>
                    <?php }  ?>
                    <style>

                        .ui-widget.ui-widget-content {
                            border: 1px solid #dde6e9;
                        }

                        .ui-corner-all, .ui-corner-bottom, .ui-corner-left, .ui-corner-bl {
                            border: 7px solid #28a9f1;
                        }

                        .ui-widget-content {
                            border: 1px solid #dddddd;
                            /*background: #E1E4E9;*/
                            color: #333333;
                        }

                        .ui-slider {
                            position: relative;
                            text-align: left;
                        }

                        .ui-slider-horizontal {
                            height: 1em;
                        }

                        .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default, .ui-button, html .ui-button.ui-state-disabled:hover, html .ui-button.ui-state-disabled:active {
                            border: 1px solid #1797be;
                            background: #1797be;
                            font-weight: normal;
                            color: #454545;
                        }

                        .ui-slider-horizontal .ui-slider-handle {
                            top: -.3em;
                            margin-left: -.1em;;
                            margin-right: -.1em;;
                        }

                        .ui-slider .ui-slider-handle:hover {
                            background: #1797be;
                        }

                        .ui-slider .ui-slider-handle {
                            position: absolute;
                            z-index: 2;
                            width: 1.2em;;
                            height: 1.5em;
                            cursor: default;
                            -ms-touch-action: none;
                            touch-action: none;

                        }

                        .ui-state-disabled, .ui-widget-content .ui-state-disabled, .ui-widget-header .ui-state-disabled {
                            opacity: .35;
                            filter: Alpha(Opacity=35);
                            background-image: none;
                        }

                        .ui-state-disabled {
                            cursor: default !important;
                            pointer-events: none;
                        }

                        .ui-slider.ui-state-disabled .ui-slider-handle, .ui-slider.ui-state-disabled .ui-slider-range {
                            filter: inherit;
                        }

                        .ui-slider-range, .ui-widget-header, .ui-slider-handle:before, .list-group-item.active, .list-group-item.active:hover, .list-group-item.active:focus, .icon-frame {
                            background-image: none;
                            background: #28a9f1;
                        }

                    </style>
                    <?php
                    if (!empty($project_info)) {
                        $value = $this->items_model->get_project_progress($project_info->project_id);
                    } else {
                        $value = 0;
                    }
                    ?>
                    <div class="row mb-3">
                        <label
                                class="col-md-3 col-form-label"><?php echo lang('progress'); ?> </label>
                        <div class="col-md-8">
                            <?php echo form_hidden('progress', $value); ?>
                            <div class="project_progress_slider project_progress_slider_horizontal mbot15"></div>

                            <div class="input-group">
                                <span class="input-group-addon">
                                     <div class="">
                                         <div class="pull-left mt-3 mr">
                                             <?php echo lang('progress'); ?>
                                             <span class="label_progress "><?php echo $value; ?>%</span>
                                         </div>
                                         <div class="form-check pull-right mt-3 mr" data-bs-toggle="tooltip"
                                              data-bs-placement="top"
                                              title="<?php echo lang('calculate_progress_through_tasks'); ?>">
                                                 <input class="select_one form-check-input"
                                                        type="checkbox" <?php if ((!empty($project_info) && $project_info->calculate_progress == 'through_tasks')) {
                                                     echo 'checked'; } ?> name="calculate_progress" value="through_tasks" id="progress_from_tasks">
                                             <label class="form-check-label" for="progress_from_tasks">
                                                 <small><?php echo lang('through_tasks'); ?></small>
                                             </label>
                                         </div>
                                         <div class="form-check pull-right mt-3 mr" data-bs-toggle="tooltip"
                                              data-bs-placement="top"
                                              title="<?php echo lang('calculate_progress_through_project_hours'); ?>">
                                                 <input class="select_one form-check-input"
                                                        type="checkbox" <?php if ((!empty($project_info) && $project_info->calculate_progress == 'through_project_hours')) {
                                                     echo 'checked'; } ?> name="calculate_progress" value="through_project_hours" id="through_project_hours">
                                             <label class="form-check-label" for="through_project_hours">
                                                 <small><?php echo lang('through_project_hours'); ?></small>
                                             </label>
                                         </div>
                                     </div>
                                </span>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function () {
                            var progress_input = $('input[name="progress"]');
                            <?php if ((!empty($project_info) && $project_info->calculate_progress == 'through_project_hours')) {?>
                            var progress_from_tasks = $('#through_project_hours');
                            <?php }elseif ((!empty($project_info) && $project_info->calculate_progress == 'through_tasks')){?>
                            var progress_from_tasks = $('#progress_from_tasks');
                            <?php }else{?>
                            var progress_from_tasks = $('.select_one');
                            <?php } ?>

                            var progress = progress_input.val();
                            $('.project_progress_slider').slider({
                                range: "min",
                                <?php
                                if (!empty($RTL)) { ?>
                                isRTL: true,
                                <?php }
                                ?>
                                min: 0,
                                max: 100,
                                value: progress,
                                disabled: progress_from_tasks.prop('checked'),
                                slide: function (event, ui) {
                                    progress_input.val(ui.value);
                                    $('.label_progress').html(ui.value + '%');
                                }
                            });
                            progress_from_tasks.on('change', function () {
                                var _checked = $(this).prop('checked');
                                $('.project_progress_slider').slider({
                                    disabled: _checked,
                                });
                            });
                        })
                        ;
                    </script>

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label"><?= lang('start_date'); ?> <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <div class="input-group" id="datepicker1"> 
                                <input required type="text" id="start_date" name="start_date" class="form-control datepicker" autocomplete="off" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container="#datepicker1" value="<?php
                                       if (!empty($project_info) && $project_info->start_date) {
                                           echo date('d-m-Y H-i', strtotime($project_info->start_date));
                                       } else {
                                           echo date('d-m-Y H-i');
                                       }
                                       ?>">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label"><?= lang('end_date') ?> <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <div class="input-group" id="datepicker2">
                                <input required type="text" id="end_date" name="end_date" data-rule-required="true" data-msg-greaterThanOrEqual="end_date_must_be_equal_or_greater_than_start_date"  data-rule-greaterThanOrEqual="#start_date" class="form-control datepicker" autocomplete="off" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container="#datepicker2" value="<?php
                                       if (!empty($project_info) && $project_info->end_date != "0000-00-00 00:00:00" ) {
                                           echo date('d-m-Y H-i', strtotime($project_info->end_date));
                                       } else {
                                           echo date('d-m-Y H-i');
                                       }
                                       ?>">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label"><?= lang('billing_type') ?> <span
                                    class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <select name="billing_type" onchange="get_billing_value(this.value)"
                                    class="form-control select_box" style="width: 100%" required="">
                                <option
                                    <?php
                                    if (!empty($project_info) && !empty($project_info->billing_type)) {
                                        echo $project_info->billing_type == 'fixed_rate' ? 'selected' : null;
                                    } ?>
                                        value="fixed_rate"><?= lang('fixed_rate') ?></option>
                                <option
                                    <?php
                                    if (!empty($project_info) && !empty($project_info->billing_type)) {
                                        echo $project_info->billing_type == 'project_hours' ? 'selected' : null;
                                    } ?>
                                        value="project_hours"><?= lang('only') . ' ' . lang('project_hours') ?></option>
                                <option
                                    <?php
                                    if (!empty($project_info) && !empty($project_info->billing_type)) {
                                        echo $project_info->billing_type == 'tasks_hours' ? 'selected' : null;
                                    } ?>
                                        value="tasks_hours"><?= lang('only') . ' ' . lang('tasks_hours') ?></option>
                                <option
                                    <?php
                                    if (!empty($project_info) && !empty($project_info->billing_type)) {
                                        echo $project_info->billing_type == 'tasks_and_project_hours' ? 'selected' : null;
                                    } ?>
                                        value="tasks_and_project_hours"><?= lang('tasks_and_project_hours') ?></option>
                            </select>
                            <small class="based_on_tasks_hour" <?php
                            if (!empty($project_info) && $project_info->billing_type == 'tasks_hours' || !empty($project_info) && $project_info->billing_type == 'tasks_and_project_hours') {
                                echo 'style=""';
                            } else {
                                echo 'style="display: none;"';
                            } ?> ><?php echo lang('based_on_hourly_rate') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3 fixed_rate " <?php
                        if (!empty($project_info) && $project_info->billing_type != 'fixed_rate') { echo 'style="display: none;"'; } ?>>
                        <label class="col-md-3 col-form-label"><?= lang('fixed_price') ?></label>
                        <div class="col-md-8">
                            <input data-parsley-type="number" type="text" class="form-control fixed_rate" value="<?php
                                   if (!empty($project_info->project_cost)) { echo $project_info->project_cost; } ?>" placeholder="50" name="project_cost">
                        </div>
                    </div>

                    <div class="row mb-3 hourly_rate " <?php
                        if (!empty($project_info) && $project_info->billing_type == 'fixed_rate' || !empty($project_info) && $project_info->billing_type == 'tasks_hours') {
                            echo 'style="display: none;"';
                        }
                        ?>>
                        <label
                                class="col-md-3 col-form-label"><?= lang('project_hourly_rate') ?></label>
                        <div class="col-md-8">
                            <input data-parsley-type="number" type="text"
                                   class="form-control hourly_rate"
                                   value="<?php
                                   if (!empty($project_info) && !empty($project_info->hourly_rate)) {
                                       echo $project_info->hourly_rate;
                                   }
                                   ?>" placeholder="50" name="hourly_rate">
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label"><?= lang('estimate_hours') ?></label>
                        <div class="col-md-8">
                            <input type="number" step="0.01" value="<?php
                            if (!empty($project_info) && !empty($project_info->estimate_hours)) {
                                $result = explode(':', $project_info->estimate_hours);
                                echo $result[0] . '.' . $result[1];
                            }
                            ?>" class="form-control" name="estimate_hours">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label"><?= lang('status') ?> <span
                                    class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <select name="project_status" id="project_status" class="form-control" style="width: 100%" required="">
                                <option <?php
                                if (!empty($project_info) && !empty($project_info->project_status)) {
                                    echo $project_info->project_status == 'started' ? 'selected' : null;
                                } ?>
                                        value="started"><?= lang('started') ?></option>
                                <option <?php
                                if (!empty($project_info) && !empty($project_info->project_status)) {
                                    echo $project_info->project_status == 'in_progress' ? 'selected' : null;
                                } ?>
                                        value="in_progress"><?= lang('in_progress') ?></option>
                                <option <?php
                                if (!empty($project_info) && !empty($project_info->project_status)) {
                                    echo $project_info->project_status == 'on_hold' ? 'selected' : null;
                                } ?>
                                        value="on_hold"><?= lang('on_hold') ?></option>
                                <option <?php
                                if (!empty($project_info) && !empty($project_info->project_status)) {
                                    echo $project_info->project_status == 'cancel' ? 'selected' : null;
                                } ?>
                                        value="cancel"><?= lang('cancel') ?></option>
                                <option <?php
                                if (!empty($project_info) && !empty($project_info->project_status)) {
                                    echo $project_info->project_status == 'completed' ? 'selected' : null;
                                } ?>
                                        value="completed"><?= lang('completed') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label"><?= lang('demo_url') ?></label>
                        <div class="col-md-8">
                            <input type="text" value="<?php
                            if (!empty($project_info) && !empty($project_info->demo_url)) {
                                echo $project_info->demo_url;
                            }
                            ?>" class="form-control" placeholder="http://www.demourl.com"
                                   name="demo_url">
                        </div>
                    </div>
                    <?php
                    if (!empty($project_info)) {
                        $project_id = $project_info->project_id;
                    } else {
                        $project_id = null;
                    }
                    ?>
                    <?= custom_form_Fields(4, $project_id, 3); ?>
                    <div class="row mb-3" id="border-none">
                        <label class="col-md-3 col-form-label"><?= lang('assined_to') ?> <span
                                    class="required">*</span></label>
                        <div class="col-md-8">
                            <div class="form-check form-radio-outline form-radio-primary mb-3">
                                <input id="everyone" <?php
                                    if (!empty($project_info) && $project_info->permission == 'all') {
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
                                    if (!empty($project_info) && $project_info->permission != 'all') {
                                        echo 'checked';
                                    }  elseif (empty($project_info)) {
                                        echo 'checked';
                                    } ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                                <label class="form-check-label" for="custom_permission">
                                    <?= lang('custom_permission') ?>
                                    <i  title="<?= lang('permission_for_customization') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 <?php
                        if (!empty($project_info) && $project_info->permission != 'all') {
                            echo 'show';
                        }
                        ?>" id="permission_user_1">
                        <label class="col-md-3 col-form-label"><?= lang('select') . ' ' . lang('users') ?> <span class="required">*</span></label>
                        <div class="col-md-8">
                        <?php
                        if (!empty($assign_user)) { ?>
                            <input type="text" name="search_assigned_user" value="" placeholder="<?=lang('search_by').' '.lang('username'); ?>" class="form-control mb-4" id="search_assigned_user" autocomplete="off">
                            <div data-simplebar style="max-height: 250px;">  
                                <?php 
                                foreach ($assign_user as $key => $v_user) {

                                    if ($v_user->role_id == 1) {
                                        $disable = true;
                                        $role = '<strong class="badge btn-danger">' . lang('admin') . '</strong>';
                                    } else {
                                        $disable = false;
                                        $role = '<strong class="badge btn-primary">' . lang('staff') . '</strong>';
                                    }

                                    ?>
                                <div class="form-check form-check-primary mb-3">
                                    <input type="checkbox"
                                            <?php
                                            if (!empty($project_info) && $project_info->permission != 'all') {
                                                $get_permission = json_decode($project_info->permission);
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
                                            if (!empty($project_info) && $project_info->permission != 'all') {
                                                $get_permission = json_decode($project_info->permission);

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
                                        <input <?php if (!empty($disable)) { echo 'disabled' . ' ' . 'checked'; } ?> id="edit_<?= $v_user->user_id ?>"
                                                <?php
                                                if (!empty($project_info) && $project_info->permission != 'all') {
                                                    $get_permission = json_decode($project_info->permission);

                                                    foreach ($get_permission as $user_id => $v_permission) {
                                                        if ($user_id == $v_user->user_id) {
                                                            if (in_array('edit', $v_permission)) {
                                                                echo 'checked';
                                                            };

                                                        }
                                                    }

                                                } ?> type="checkbox" value="edit" name="action_<?= $v_user->user_id ?>[]" class="form-check-input">
                                        <label class="form-check-label" for="edit_<?= $v_user->user_id ?>"><?= lang('edit') ?></label>
                                    </div>
                                    <div class="form-check form-check-primary mb-3 mr">         
                                        <input <?php if (!empty($disable)) { echo 'disabled' . ' ' . 'checked'; } ?> id="delete_<?= $v_user->user_id ?>"
                                                <?php

                                                if (!empty($project_info) && $project_info->permission != 'all') {
                                                    $get_permission = json_decode($project_info->permission);
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
                            <?php }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <!-- checkbox -->
                    <?php
                    $project_permissions = $this->db->get('tbl_project_settings')->result();
                    if (!empty($project_info) && !empty($project_info->project_settings)) {
                        $current_permissions = $project_info->project_settings;
                        if ($current_permissions == NULL) {
                            $current_permissions = '{"settings":"on"}';
                        }
                        $get_permissions = json_decode($current_permissions);
                    }

                    foreach ($project_permissions as $v_permissions) {
                        ?>
                        <div class="form-check form-check-primary mb-3">
                            <input name="<?= $v_permissions->settings_id ?>" id="setting_<?= $v_permissions->settings_id ?>" value="<?= $v_permissions->settings ?>" <?php if (!empty($project_info) && !empty($project_info->project_settings)) {
                                    if (in_array($v_permissions->settings, $get_permissions)) {
                                        echo "checked=\"checked\"";
                                    }
                                } else {
                                    echo "checked=\"checked\"";
                                } ?> type="checkbox" class="form-check-input">
                            <label class="form-check-label" for="setting_<?= $v_permissions->settings_id ?>"><?= lang($v_permissions->settings) ?>
                            </label>
                        </div>
                        <hr class="mt-sm mb-sm"/>
                    <?php }  ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label"><?= lang('description') ?> <span class="text-danger">*</span></label>
                        <div class="col-md-9">

                        <textarea id="elm1" name="description" class="form-control textarea"  placeholder="<?= lang('description') ?>"><?php
                            if (!empty($project_info) && !empty($project_info->description)) {
                                echo $project_info->description;
                            }
                            ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3 mt-lg">
                        <label class="col-lg-2 control-label"></label>
                        <div class="col-md-8">
                            <button type="submit"
                                    class="btn btn-block btn-primary"><?= lang('updates') ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function () {
    $("#project_status").select2({
        <?php

        if (!empty($RTL)) {?>
        dir: "rtl",
        <?php }
        ?>
    });
});
</script>