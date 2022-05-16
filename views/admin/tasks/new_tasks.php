<?php 
if (!empty($task_info)) {
    $task_id = $task_info->task_id;
    $companies_id = $task_info->companies_id;
} else {
    $task_id = null;
    $companies_id = null;
}

?>
<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<?php 
$created = can_action('54', 'created');

$edited = can_action('54', 'edited');
$deleted = can_action('54', 'deleted');

$kanban = $this->session->userdata('task_kanban');
$uri_segment = $this->uri->segment(4);
if (!empty($kanban)) {
    $btn = 'danger';
    $text = 'kanban';
    $tasks = 'kanban';   
} elseif ($uri_segment == 'kanban') {
    $btn = 'danger';
    $text = 'kanban';
    $tasks = 'kanban';
} else {
    $text = 'list';
    $btn = 'primary';
    $tasks = 'list';
}
?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18">  <?php if(!$task_id){
              echo lang('add_task'); 
            }else{
              echo lang('edit_task'); 

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
            <a href="<?= base_url() ?>admin/tasks/all_task/<?= $text ?>" class="btn btn-xs btn-<?= $btn ?> pull-right" data-bs-toggle="tooltip"  data-bs-placement="top" title="<?= lang('switch_to_' . $text) ?>"><i class="fa fa-undo"> </i><?= ' ' . lang('switch_to_' . $text) ?>
            </a>
        </div>
        <div class="float-end">
            <?php if (!empty($created) || !empty($edited)) { ?>
            <a class="btn btn-xs btn-<?= $btn ?>" href="<?= base_url() ?>admin/tasks/import"><?= lang('import') . ' ' . lang('tasks') ?></a>
            <?php } ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

            <h4 class="card-title mb-4"><?= lang('task_details') ?></h4>

            <?php echo form_open(base_url('admin/tasks/save_task/' . $task_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
            <?php super_admin_form($companies_id, 2, 6) ?>
            <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('task_name') ?></label>
                <div class="col-md-6">
                    <input type="text" name="task_name" required class="form-control" value="<?php if (!empty($task_info->task_name)) echo $task_info->task_name; ?>"/>
                </div>
            </div>

            <?php
            if (!empty($task_info->project_id)) {
                $project_id = $task_info->project_id;
            } elseif (!empty($project_id)) {
                $project_id = $project_id; ?>
                <input type="hidden" name="un_project_id" required class="form-control" value="<?php echo $project_id ?>"/>
            <?php }
            if (!empty($task_info->opportunities_id)) {
                $opportunities_id = $task_info->opportunities_id;
            } elseif (!empty($opportunities_id)) {
                $opportunities_id = $opportunities_id; ?>
                <input type="hidden" name="un_opportunities_id" required class="form-control"   value="<?php echo $opportunities_id ?>"/>
            <?php }
            if (!empty($task_info->leads_id)) {
                $leads_id = $task_info->leads_id;
            } elseif (!empty($leads_id)) {
                $leads_id = $leads_id; ?>
                <input type="hidden" name="un_leads_id" required class="form-control"
                       value="<?php echo $leads_id ?>"/>
            <?php }
            if (!empty($task_info->bug_id)) {
                $bug_id = $task_info->bug_id;
            } elseif (!empty($bug_id)) {
                $bug_id = $bug_id; ?>
                <input type="hidden" name="un_bug_id" required class="form-control"
                       value="<?php echo $bug_id ?>"/>
            <?php }
            if (!empty($task_info->goal_tracking_id)) {
                $goal_tracking_id = $task_info->goal_tracking_id;
            } elseif (!empty($goal_tracking_id)) {
                $goal_tracking_id = $goal_tracking_id; ?>
                <input type="hidden" name="un_goal_tracking_id" required
                       class="form-control"
                       value="<?php echo $goal_tracking_id ?>"/>
            <?php } ?>
            <?php
            if (!empty($task_info->sub_task_id)) {
                $sub_task_id = $task_info->sub_task_id;
            } elseif (!empty($sub_task_id)) {
                $sub_task_id = $sub_task_id; ?>
            <input type="hidden" name="un_sub_task_id" required
                       class="form-control"
                       value="<?php echo $sub_task_id ?>"/>
            <?php } ?>
            <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('related_to') ?></label>
                <div class="col-md-6">
                    <select name="related_to" class="form-select" id="check_related"
                            onchange="get_related_moduleName(this.value)">
                        <option
                                value="0"> <?= lang('none') ?> </option>
                        <option
                                value="project" <?= (!empty($project_id) ? 'selected' : '') ?>> <?= lang('project') ?> </option>
                        <option
                                value="opportunities" <?= (!empty($opportunities_id) ? 'selected' : '') ?>> <?= lang('opportunities') ?> </option>
                        <option
                                value="leads" <?= (!empty($leads_id) ? 'selected' : '') ?>> <?= lang('leads') ?> </option>
                        <option
                                value="bug" <?= (!empty($bug_id) ? 'selected' : '') ?>> <?= lang('bugs') ?> </option>
                        <option
                                value="goal" <?= (!empty($goal_tracking_id) ? 'selected' : '') ?>> <?= lang('goal_tracking') ?> </option>
                        <option
                                value="sub_task" <?= (!empty($sub_task_id) ? 'selected' : '') ?>> <?= lang('tasks') ?> </option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row" id="related_to"></div>
            <?php if (empty($project_id)) { ?>
            <div class="mb-3 row company" id="milestone_show">
                <label class="col-md-2 col-form-label"><?= lang('milestones') ?></label>
                <div class="col-md-6">
                    <select name="milestones_id" id="milestone"  class="form-select company">
                        <option value="">None</option>
                        <?php
                        if (!empty($project_id)) {
                            $all_milestones_info = $this->db->where('project_id', $project_id)->get('tbl_milestones')->result();
                        } else {
                            $project_milestone = $this->db->get('tbl_project')->row();
                            $all_milestones_info = $this->db->where('project_id', $project_milestone->project_id)->get('tbl_milestones')->result();
                        }
                        if (!empty($all_milestones_info)) {
                            foreach ($all_milestones_info as $v_milestones) {
                                ?>
                                <option value="<?= $v_milestones->milestones_id ?>" <?php
                                if (!empty($task_info->milestones_id)) {
                                    echo $v_milestones->milestones_id == $task_info->milestones_id ? 'selected' : '';
                                }
                                ?>><?= $v_milestones->milestone_name ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <?php } ?>
            <?php

            if (!empty($project_id)):
            $project_info = $this->db->where('project_id', $project_id)->get('tbl_project')->row();
            ?>
            <div class="mb-3 row <?= $project_id ? 'project_module' : 'company' ?>">
                <label class="col-md-2 col-form-label"><?= lang('project') ?> <span
                            class="required">*</span></label>
                <div class="col-md-6">
                    <select name="project_id" style="width: 100%"
                            class="form-select select_box <?= $project_id ? 'project_module' : 'company' ?>"
                            required="1" onchange="get_milestone_by_id(this.value)">
                        <?php
                        $all_project = $this->tasks_model->get_permission('tbl_project');
                        if (!empty($all_project)) {
                            foreach ($all_project as $v_project) {
                                ?>
                                <option value="<?= $v_project->project_id ?>" <?php
                                if (!empty($project_id)) {
                                    echo $v_project->project_id == $project_id ? 'selected' : '';
                                }
                                ?>><?= $v_project->project_name ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                </div>
            </div>
            <div class="mb-3 row <?= $project_id ? 'milestone_module' : 'company' ?>"
                 id="milestone_show">
                <label class="col-md-2 col-form-label"><?= lang('milestones') ?>
                    <span class="required">*</span></label>
                <div class="col-md-6">
                    <select name="milestones_id" id="milestone"
                            class="form-select <?= $project_id ? 'milestone_module' : 'company' ?>">
                        <option><?= lang('none') ?></option>
                        <?php
                        $all_milestones_info = $this->db->where('project_id', $project_id)->get('tbl_milestones')->result();
                        if (!empty($all_milestones_info)) {
                            foreach ($all_milestones_info as $v_milestones) {
                                ?>
                                <option
                                        value="<?= $v_milestones->milestones_id ?>" <?php
                                if (!empty($task_info->milestones_id)) {
                                    echo $v_milestones->milestones_id == $task_info->milestones_id ? 'selected' : '';
                                }
                                ?>><?= $v_milestones->milestone_name ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <?php endif ?>
            <?php if (!empty($opportunities_id)): ?>
            <div class="mb-3 row <?= $opportunities_id ? 'opportunities_module' : 'company' ?>"
                    id="border-none">
                <label class="col-md-2 col-form-label"><?= lang('select') . ' ' . lang('opportunities') ?>
                    <span class="required">*</span></label>
                <div class="col-md-6">
                    <select name="opportunities_id" style="width: 100%"
                            class="select_box form-select <?= $opportunities_id ? 'opportunities_module' : 'company' ?>"
                            required="1">
                        <?php
                        $all_opportunities_info = $this->tasks_model->get_permission('tbl_opportunities');
                        if (!empty($all_opportunities_info)) {
                            foreach ($all_opportunities_info as $v_opportunities) {
                                ?>
                                <option
                                        value="<?= $v_opportunities->opportunities_id ?>" <?php
                                if (!empty($opportunities_id)) {
                                    echo $v_opportunities->opportunities_id == $opportunities_id ? 'selected' : '';
                                }
                                ?>><?= $v_opportunities->opportunity_name ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <?php endif ?>
            <?php if (!empty($leads_id)): ?>
            <div class="mb-3 row <?= $leads_id ? 'leads_module' : 'company' ?>"
                 id="border-none">
                <label class="col-md-2 col-form-label"><?= lang('select') . ' ' . lang('leads') ?>
                    <span class="required">*</span></label>
                <div class="col-md-6">
                    <select name="leads_id" style="width: 100%"
                            class="select_box form-select <?= $leads_id ? 'leads_module' : 'company' ?>"
                            required="1">
                        <?php
                        $all_leads_info = $this->tasks_model->get_permission('tbl_leads');
                        if (!empty($all_leads_info)) {
                            foreach ($all_leads_info as $v_leads) {
                                ?>
                                <option value="<?= $v_leads->leads_id ?>" <?php
                                if (!empty($leads_id)) {
                                    echo $v_leads->leads_id == $leads_id ? 'selected' : '';
                                }
                                ?>><?= $v_leads->lead_name ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <?php endif ?>

            <?php if (!empty($bug_id)): ?>
            <div class="mb-3 row <?= $bug_id ? 'bugs_module' : 'company' ?>"
                 id="border-none">
                <label class="col-md-2 col-form-label"><?= lang('select') . ' ' . lang('bugs') ?>
                    <span class="required">*</span></label>
                <div class="col-md-6">
                    <select name="bug_id" style="width: 100%"
                            class="select_box form-select <?= $bug_id ? 'bugs_module' : 'company' ?>"
                            required="1">
                        <?php
                        $all_bugs_info = $this->tasks_model->get_permission('tbl_bug');
                        if (!empty($all_bugs_info)) {
                            foreach ($all_bugs_info as $v_bugs) {
                                ?>
                                <option value="<?= $v_bugs->bug_id ?>" <?php
                                if (!empty($bug_id)) {
                                    echo $v_bugs->bug_id == $bug_id ? 'selected' : '';
                                }
                                ?>><?= $v_bugs->bug_title ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <?php endif ?>
            <?php if (!empty($goal_tracking_id)): ?>
            <div
                    class="mb-3 row <?= $goal_tracking_id ? 'goal_tracking' : 'company' ?>"
                    id="border-none">
                <label class="col-md-2 col-form-label"><?= lang('select') . ' ' . lang('goal_tracking') ?>
                    <span class="required">*</span></label>
                <div class="col-md-6">
                    <select name="goal_tracking_id" style="width: 100%"
                            class="select_box form-select <?= $goal_tracking_id ? 'goal_tracking' : 'company' ?>"
                            required="1">
                        <?php
                        $all_goal_info = $this->tasks_model->get_permission('tbl_goal_tracking');
                        if (!empty($all_goal_info)) {
                            foreach ($all_goal_info as $v_goal) {
                                ?>
                                <option value="<?= $v_goal->goal_tracking_id ?>" <?php
                                if (!empty($goal_tracking_id)) {
                                    echo $v_goal->goal_tracking_id == $goal_tracking_id ? 'selected' : '';
                                }
                                ?>><?= $v_goal->subject ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <?php endif ?>
            <?php if (!empty($sub_task_id)): ?>
            <div
                    class="mb-3 row <?= $sub_task_id ? 'sub_tasks' : 'company' ?>"
                    id="border-none">
                <label class="col-md-2 col-form-label"><?= lang('select') . ' ' . lang('sub_tasks') ?>
                    <span class="required">*</span></label>
                <div class="col-md-6">
                    <select name="sub_task_id" style="width: 100%"
                            class="select_box form-select <?= $sub_task_id ? 'sub_tasks' : 'company' ?>"
                            required="1">
                        <?php
                        $all_sub_tasks = $this->tasks_model->get_permission('tbl_task');
                        if (!empty($all_sub_tasks)) {
                            foreach ($all_sub_tasks as $v_s_tasks) {
                                ?>
                                <option value="<?= $v_s_tasks->task_id ?>" <?php
                                if (!empty($sub_task_id)) {
                                    echo $v_s_tasks->task_id == $sub_task_id ? 'selected' : '';
                                }
                                ?>><?= $v_s_tasks->task_name ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <?php endif ?>
            <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('start_date') ?></label>
                <div class="col-md-6">
                    <div class="input-group"> 
                        <input type="text" name="task_start_date" autocomplete="off" required="" class="form-control datepicker" value="<?php
                           if (!empty($task_info->task_start_date)) {
                               echo date('d-m-Y H-i',strtotime($task_info->task_start_date));
                           } else {
                               echo date('d-m-Y H-i');
                           }
                           ?>"
                           data-date-format="<?= config_item('date_picker_format'); ?>">
                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                    </div>
                </div>
            </div>
            
            <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('due_date') ?><span class="required">*</span></label>
                <div class="col-md-6">
                    <div class="input-group"> 
                        <input type="text" name="due_date" autocomplete="off" required="" class="form-control datepicker" value="<?php
                           if (!empty($task_info->due_date)) {
                               echo date('d-m-Y H-i',strtotime($task_info->due_date));
                           } 
                           ?>"
                           data-date-format="<?= config_item('date_picker_format'); ?>">
                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                    </div>

                </div>
            </div>

            <div class="mb-3 row">
                <label
                        class="col-md-2 col-form-label"><?= lang('project_hourly_rate') ?></label>
                <div class="col-md-6">
                    <input type="text" data-parsley-type="number" name="hourly_rate"
                           class="form-control"
                           value="<?php if (!empty($task_info->hourly_rate)) echo $task_info->hourly_rate; ?>"/>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('estimated_hour') ?></label>
                <div class="col-md-6">
                    <input type="number" step="0.01" data-parsley-type="number"
                           name="task_hour"
                           class="form-control"
                           value="<?php
                           if (!empty($task_info->task_hour)) {
                               $result = explode(':', $task_info->task_hour);
                               if (empty($result[1])) {
                                   $result1 = 0;
                               } else {
                                   $result1 = $result[1];
                               }
                               echo $result[0] . '.' . $result1;
                           }
                           ?>"/>
                </div>
            </div>
            <script src="<?= base_url() ?>skote_assets/js/jquery-ui.js"></script>
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
                <script type="text/javascript" src="<?= base_url() ?>skote_assets/plugins/jquery-ui/jquery.ui.slider-rtl.js"></script>
            <?php }
            ?>
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
            if (!empty($task_info)) {
                $value = $this->tasks_model->get_task_progress($task_info->task_id);
            } else {
                $value = 0;
            }
            ?>
            <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?php echo lang('progress'); ?> </label>
                <div class="col-md-6">
                    <?php echo form_hidden('task_progress', $value); ?>
                    <div class="project_progress_slider project_progress_slider_horizontal mbot15"></div>

                    <div class="input-group">
                        <span class="input-group-addon">
                             <div class="">
                                <div class="pull-left mt-3 mr">
                                     <?php echo lang('progress'); ?>
                                     <span class="label_progress "><?php echo $value; ?>%</span>
                                </div>
                                <div class="form-check pull-right mt-3 mr" data-bs-toggle="tooltip"   data-bs-placement="top" title="<?php echo lang('calculate_progress_through_sub_tasks'); ?>">
                                     <input class="select_one form-check-input" type="checkbox" <?php if ((!empty($task_info) && $task_info->calculate_progress == 'through_sub_tasks')) {
                                         echo 'checked';
                                     } ?> name="calculate_progress" value="through_sub_tasks"  id="through_sub_tasks">
                                     <label class="form-check-label" for="through_sub_tasks"><small><?php echo lang('through_sub_tasks'); ?></small></label>      
                                 </div>
                                 <div class="form-check pull-right mt-3 mr" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo lang('calculate_progress_through_task_hours'); ?>">
                                    <input class="select_one form-check-input"  type="checkbox" <?php if ((!empty($task_info) && $task_info->calculate_progress == 'through_tasks_hours')) {
                                             echo 'checked';
                                         } ?> name="calculate_progress" value="through_tasks_hours"
                                                id="through_tasks_hours">
                                     <label class="form-check-label" for="through_tasks_hours">
                                         <small><?php echo lang('through_tasks_hours'); ?></small>
                                     </label>
                                 </div>
                             </div>
                        </span>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    var progress_input = $('input[name="task_progress"]');
                    <?php if ((!empty($project_info) && $project_info->calculate_progress == 'through_tasks_hours')) {?>
                    var progress_from_tasks = $('#through_tasks_hours');
                    <?php }elseif ((!empty($project_info) && $project_info->calculate_progress == 'through_sub_tasks')){?>
                    var progress_from_tasks = $('#through_sub_tasks');
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
            <div class="mb-3 row" id="border-none">
                <label class="col-md-2 col-form-label"><?= lang('task_status') ?> <span
                            class="required">*</span></label>
                <div class="col-md-6">
                    <select name="task_status" class="form-select" required>
                         <?php foreach ($all_task_kanban_category as $v_status) { ?>
                            <option value="<?= $v_status->task_kanban_category_id;?>" <?= ($task_info->task_status == $v_status->task_kanban_category_id ? 'selected' : '') ?>> <?= $v_status->kanban_category_name; ?> </option>
                           <?php } ?>
                      
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('task_description') ?>
                </label>
                <div class="col-md-10">
                <textarea class="form-control textarea" id="elm1"  name="task_description"><?php if (!empty($task_info->task_description)) echo $task_info->task_description; ?></textarea>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('billable') ?>
                    <span class="required">*</span></label>
                <div class="col-md-6">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="billable" name="billable" value="Yes" <?php
                    if (!empty($task_info) && $task_info->billable == 'Yes') {
                        echo 'checked';
                    }
                    ?>>
                        <label class="form-check-label" for="billable"></label>
                    </div>
                </div>
            </div>
            <?php if (!empty($project_id)): ?>
            <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('visible_to_client') ?>
                    <span class="required">*</span></label>
                <div class="col-md-6">
                    <div class="form-check form-switch mb-3">
                        <input id="client_visible" name="client_visible" value="Yes" <?php
                        if (!empty($task_info) && $task_info->client_visible == 'Yes') {
                            echo 'checked';
                        }
                        ?> type="checkbox" class="form-check-input">
                        <label class="form-check-label" for="client_visible"></label>
                    </div>
                </div>
            </div>
            <?php endif ?>

            <?php
            if (!empty($task_info)) {
                $task_id = $task_info->task_id;
            } else {
                $task_id = null;
            }
            ?>
            <?= custom_form_Fields(3, $task_id, 2); ?>

            <div class="mb-3 row" id="border-none">
                <label class="col-md-2 col-form-label"><?= lang('assined_to') ?> <span
                            class="required">*</span></label>
                <div class="col-md-6">
                    <div class="form-check form-radio-outline form-radio-primary mb-3">
                        <input id="user_permission_1" <?php
                            if (!empty($task_info->permission) && $task_info->permission == 'all') {
                                echo 'checked';
                            }
                            ?> type="radio" name="permission" value="everyone" class="form-check-input">
                        <label for="user_permission_1" class="form-check-label"> <?= lang('everyone') ?> <i title="<?= lang('permission_for_all') ?>"
                               class="fa fa-question-circle" data-bs-toggle="tooltip"
                               data-bs-placement="top"></i>
                        </label>
                    </div>
                    <div class="form-check form-radio-outline form-radio-primary mb-3">
                        <input  <?php
                            if (!empty($task_info->permission) && $task_info->permission != 'all') {
                                echo 'checked';
                            } elseif (empty($task_info)) {
                                echo 'checked';
                            }
                            ?> type="radio" name="permission" value="custom_permission" class="form-check-input" id="user_permission_2">
                        <label for="user_permission_2" class="form-check-label"> <?= lang('custom_permission') ?>  
                            <i title="<?= lang('permission_for_customization') ?>"
                                    class="fa fa-question-circle" data-bs-toggle="tooltip"
                                    data-bs-placement="top"></i>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mb-3 row <?php
                if (!empty($task_info->permission) && $task_info->permission != 'all') {
                    echo 'show';
                }
                ?>" id="permission_user_1">
                <label class="col-md-2 col-form-label"><?= lang('select') . ' ' . lang('users') ?>
                    <span
                            class="required">*</span></label>
                <div class="col-md-6">
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
                                        if (!empty($task_info->permission) && $task_info->permission != 'all') {
                                            $get_permission = json_decode($task_info->permission);
                                            foreach ($get_permission as $user_id => $v_permission) {
                                                if ($user_id == $v_user->user_id) {
                                                    echo 'checked';
                                                }
                                            }

                                        }
                                        ?> value="<?= $v_user->user_id ?>" name="assigned_to[]" class="form-check-input" id ="user_<?= $v_user->user_id ?>"  data-name="<?= $v_user->username;?>">
                                <label for="user_<?= $v_user->user_id ?>" class="form-check-label"><?= $v_user->username . ' ' . $role ?></label>
                            </div>
                            <div class="action_1 p
                                <?php

                                    if (!empty($task_info->permission) && $task_info->permission != 'all') {
                                        $get_permission = json_decode($task_info->permission);

                                        foreach ($get_permission as $user_id => $v_permission) {
                                            if ($user_id == $v_user->user_id) {
                                                echo 'show';
                                            }
                                        }

                                    }  ?>" id="action_1<?= $v_user->user_id ?>">
                                <div class="form-check form-check-primary mb-3 mr">     
                                    <input id="view_<?= $v_user->user_id ?>" checked  type="checkbox" name="action_1<?= $v_user->user_id ?>[]"  disabled   value="view" class="form-check-input">
                                    <label class="form-check-label" for="view_<?= $v_user->user_id ?>"><?= lang('can') . ' ' . lang('view') ?>
                                    </label>
                                </div>
                                <div class="form-check form-check-primary mb-3 mr">     
                                    <input <?php if (!empty($disable)) {
                                            echo 'disabled' . ' ' . 'checked';
                                        } ?> id="edit_<?= $v_user->user_id ?>"
                                            <?php

                                            if (!empty($task_info->permission) && $task_info->permission != 'all') {
                                                $get_permission = json_decode($task_info->permission);

                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        if (in_array('edit', $v_permission)) {
                                                            echo 'checked';
                                                        };

                                                    }
                                                }

                                            }
                                            ?>  type="checkbox"  value="edit"  name="action_<?= $v_user->user_id ?>[]" class="form-check-input">
                                        
                                    <label class="form-check-label" for="edit_<?= $v_user->user_id ?>"><?= lang('can') . ' ' . lang('edit') ?></label>
                                </div>
                                <div class="form-check form-check-primary mb-3 mr">     
                                    <input <?php if (!empty($disable)) {
                                            echo 'disabled' . ' ' . 'checked';
                                        } ?> id="delete_<?= $v_user->user_id ?>"
                                            <?php

                                            if (!empty($task_info->permission) && $task_info->permission != 'all') {
                                                $get_permission = json_decode($task_info->permission);
                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        if (in_array('delete', $v_permission)) {
                                                            echo 'checked';
                                                        };
                                                    }
                                                }

                                            }
                                            ?> name="action_<?= $v_user->user_id ?>[]" type="checkbox" value="delete" class="form-check-input">
                                    <label class="form-check-label" for="delete_<?= $v_user->user_id ?>"><?= lang('can') . ' ' . lang('delete') ?>
                                    </label>
                                </div>

                                <input id="<?= $v_user->user_id ?>" type="hidden"  name="action_<?= $v_user->user_id ?>[]" value="view">
                            </div>
                            <?php } ?>
                        </div>
                    <?php } ?>


                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-md-2 col-form-label"></label>
                <div class="col-md-6">
                    <button type="submit" id="sbtn"
                            class="btn btn-primary"><?= lang('save') ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>