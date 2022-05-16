<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<style>
    .note-editor .note-editable {
        height: 150px;
    }

    a:hover {
        text-decoration: none;
    }
   
</style>
<?php
$edited = can_action('54', 'edited');

$can_edit = $this->tasks_model->can_action('tbl_task', 'edit', array('task_id' => $task_details->task_id));
// get all comments by tasks id
$comment_details = $this->db->where(array('task_id' => $task_details->task_id, 'comments_reply_id' => '0', 'task_attachment_id' => '0', 'uploaded_files_id' => '0'))->order_by('comment_datetime', 'DESC')->get('tbl_task_comment')->result();
// get all $total_timer by tasks id
$total_timer = $this->db->where(array('task_id' => $task_details->task_id, 'start_time !=' => 0, 'end_time !=' => 0,))->get('tbl_tasks_timer')->result();
$all_sub_tasks = $this->db->where(array('sub_task_id' => $task_details->task_id))->get('tbl_task')->result();
//echo "<pre>";
//print_r($all_sub_tasks);
//exit();
$activities_info = $this->db->where(array('module' => 'tasks', 'module_field_id' => $task_details->task_id))->order_by('activity_date', 'DESC')->get('tbl_activities')->result();

$where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $task_details->task_id, 'module_name' => 'tasks');
$check_existing = $this->tasks_model->check_by($where, 'tbl_pinaction');
if (!empty($check_existing)) {
    $url = 'remove_todo/' . $check_existing->pinaction_id;
    $btn = 'danger';
    $check_title = lang('remove_todo');
} else {
    $url = 'add_todo_list/tasks/' . $task_details->task_id;
    $btn = 'warning';
    $check_title = lang('add_todo_list');
}
$sub_tasks = config_item('allow_sub_tasks');

$task_kanban_category = $this->tasks_model->get_task_kanban_category($task_details->task_status);
$task_status_name=$task_kanban_category[0]->kanban_category_name;


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
    <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                    <a class="nav-link mb-2 <?= $active == 1 ? 'active' : '' ?>" data-bs-toggle="pill" href="#task_details" role="tab" aria-controls="task_details" aria-selected="false"><?= lang('tasks') . ' ' . lang('details') ?></a>
                    
                    <a class="nav-link mb-2 <?= $active == 2 ? 'active' : '' ?>" data-bs-toggle="pill" href="#task_comments" role="tab" aria-controls="task_comments" aria-selected="false"><?= lang('comments') ?><strong class="badge badge-soft-danger pull-right"><?= (!empty($comment_details) ? count($comment_details) : null) ?></strong></a>

                    <a class="nav-link mb-2 <?= $active == 3 ? 'active' : '' ?>" data-bs-toggle="pill" href="#task_attachments" role="tab" aria-controls="task_attachments" aria-selected="false"><?= lang('attachment')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($project_files_info) ? count($project_files_info) : null) ?></strong></a>

                    <a class="nav-link mb-2 <?= $active == 4 ? 'active' : '' ?>" data-bs-toggle="pill" href="#task_notes" role="tab" aria-controls="task_notes" aria-selected="false"><?= lang('notes')?></a>
                    
                    <a class="nav-link mb-2 <?= $active == 5 ? 'active' : '' ?>" data-bs-toggle="pill" href="#timesheet" role="tab" aria-controls="timesheet" aria-selected="false"><?= lang('timesheet')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($total_timer) ? count($total_timer) : null) ?></strong></a>

                   <?php if (!empty($sub_tasks)) {
                        ?>
                    <a class="nav-link mb-2 <?= $active == 7 ? 'active' : '' ?>" data-bs-toggle="pill" href="#sub_tasks" role="tab" aria-controls="sub_tasks" aria-selected="false"><?= lang('sub_tasks')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_sub_tasks) ? count($all_sub_tasks) : null) ?></strong></a>

                    <?php } ?>
                    
                    <a class="nav-link mb-2 <?= $active == 6 ? 'active' : '' ?>" data-bs-toggle="pill" href="#activities" role="tab" aria-controls="activities" aria-selected="false"><?= lang('activities')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($activities_info) ? count($activities_info) : null) ?></strong></a>

                </div>
            </div>
        </div>
    </div>
    <?php $comment_type = 'tasks'; ?>
    <div class="col-md-10">
        <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
            <!-- Task Details tab Starts -->
            <div class="tab-pane fade <?= $active == 1 ? 'show active' : '' ?>" id="task_details"
                 style="position: relative;">
                <div class="card">
                    <div class="card-body">
                        <div class="pull-right ml-sm mr ">
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $check_title; ?>"
                               href="<?= base_url() ?>admin/projects/<?= $url ?>"
                               class="btn-sm btn btn-<?= $btn ?>"><i class="fa fa-thumb-tack"></i></a>
                        </div>
                        <div class="pull-right ml-sm mr">
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('export_report') ?>"
                               href="<?= base_url() ?>admin/tasks/export_report/<?= $task_details->task_id ?>"
                               class="btn-sm btn btn-success"><i class="fa fa-file-pdf-o"></i></a>
                        </div>
                        <?php

                        if (!empty($can_edit) && !empty($edited)) {
                            ?>
                        <span class="pull-right mr"><a class="btn btn-sm  btn-info" href="<?= base_url() ?>admin/tasks/new_tasks/<?= $task_details->task_id ?>"><?= lang('edit') . ' ' . lang('task') ?></a> </span>
                        <?php } ?>
                        <h4 class="card-title"><?php if (!empty($task_details->task_name)) echo $task_details->task_name; ?></h4>                    
                        <?php $task_details_view = config_item('task_details_view');
                        if (!empty($task_details_view) && $task_details_view == '2') {   ?>
                        <div class="row">

                            <div class="col-lg-4">
                                <p class="lead bb"></p>
                                <form class="form-horizontal p-20">
                                    <?php super_admin_details($task_details->companies_id, 5,6,6,10) ?>
                                    <div class="mb-3 row">
                                        <div class="col-md-5"><strong><?= lang('task_name') ?> :</strong></div>
                                        <div class="col-md-6">
                                            <?php
                                            if (!empty($task_details->task_name)) {
                                                echo $task_details->task_name;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                    if (!empty($task_details->project_id)):
                                        $project_info = $this->db->where('project_id', $task_details->project_id)->get('tbl_project')->row();
                                        $milestones_info = $this->db->where('milestones_id', $task_details->milestones_id)->get('tbl_milestones')->row();
                                        ?>
                                        <div class="mb-3 row ">
                                            <div class="col-md-5"><strong><?= lang('project_name') ?>
                                                    :</strong></div>
                                            <div class="col-md-6 ">
                                                <?php if (!empty($project_info->project_name)) echo $project_info->project_name; ?>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-md-5"><strong><?= lang('milestone') ?>
                                                    :</strong></div>
                                            <div class="col-md-6 ">
                                                <?php if (!empty($milestones_info->milestone_name)) echo $milestones_info->milestone_name; ?>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php
                                    if (!empty($task_details->opportunities_id)):
                                        $opportunity_info = $this->db->where('opportunities_id', $task_details->opportunities_id)->get('tbl_opportunities')->row();
                                        ?>
                                        <div class="mb-3 row">
                                            <div class="col-md-5"><strong
                                                        class="mr-sm"><?= lang('opportunity_name') ?></strong></div>
                                            <div class="col-md-6">
                                                <?php if (!empty($opportunity_info->opportunity_name)) echo $opportunity_info->opportunity_name; ?>
                                            </div>
                                        </div>
                                    <?php endif ?>

                                    <?php
                                    if (!empty($task_details->leads_id)):
                                        $leads_info = $this->db->where('leads_id', $task_details->leads_id)->get('tbl_leads')->row();
                                        ?>
                                        <div class="mb-3 row">
                                            <div class="col-md-5"><strong
                                                        class="mr-sm"><?= lang('leads_name') ?></strong></div>
                                            <div class="col-md-6">
                                                <?php if (!empty($leads_info->lead_name)) echo $leads_info->lead_name; ?>
                                            </div>
                                        </div>
                                    <?php endif ?>

                                    <?php
                                    if (!empty($task_details->bug_id)):
                                        $bugs_info = $this->db->where('bug_id', $task_details->bug_id)->get('tbl_bug')->row();
                                        ?>
                                        <div class="mb-3 row">
                                            <div class="col-md-5"><strong
                                                        class="mr-sm"><?= lang('bug_title') ?></strong></div>
                                            <div class="col-md-6">
                                                <?php if (!empty($bugs_info->bug_title)) echo $bugs_info->bug_title; ?>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php
                                    if (!empty($task_details->goal_tracking_id)):
                                        $goal_tracking_info = $this->db->where('goal_tracking_id', $task_details->goal_tracking_id)->get('tbl_goal_tracking')->row();
                                        ?>
                                        <div class="mb-3 row">
                                            <div class="col-md-5"><strong
                                                        class="mr-sm"><?= lang('goal_tracking') ?></strong></div>
                                            <div class="col-md-6">
                                                <?php if (!empty($goal_tracking_info->subject)) echo $goal_tracking_info->subject; ?>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php
                                    if (!empty($task_details->sub_task_id)):
                                        $sub_task = $this->db->where('task_id', $task_details->sub_task_id)->get('tbl_task')->row();
                                        ?>
                                        <div class="mb-3 row">
                                            <div class="col-md-5"><strong
                                                        class="mr-sm"><?= lang('sub_tasks') ?></strong></div>
                                            <div class="col-md-6">
                                                <?php if (!empty($sub_task->task_name)) echo $sub_task->task_name; ?>
                                            </div>
                                        </div>
                                    <?php endif ?>

                                    <div class="mb-3 row">
                                        <div class="col-md-5"><strong><?= lang('start_date') ?> :</strong></div>
                                        <div class="col-md-6">
                                            <?php
                                            if (!empty($task_details->task_start_date)) {
                                                echo display_datetime($task_details->task_start_date);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                    $due_date = $task_details->due_date;
                                    $due_time = strtotime($due_date);
                                    $current_time = time();
                                    if ($current_time > $due_time && $task_details->task_status != 6) {
                                        $text = 'text-danger';
                                    } else {
                                        $text = null;
                                    }
                                    ?>
                                    <div class="mb-3 row">
                                        <div class="col-md-5"><strong class="<?= $text ?>"><?= lang('due_date') ?>
                                                :</strong></div>
                                        <div class="col-md-6 <?= $text ?>">
                                            <?php
                                            if (!empty($task_details->due_date)) {
                                                echo display_datetime($task_details->due_date);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-md-5"><strong><?= lang('task_status') ?>
                                                :</strong></div>
                                        <div class="col-md-6">
                                            <?php
                                            if ($task_details->task_status == 6) {
                                                $label = 'success';
                                            } elseif ($task_details->task_status == 1) {
                                                $label = 'info';
                                            } elseif ($task_details->task_status == 5) {
                                                $label = 'danger';
                                            } else {
                                                $label = 'warning';
                                            }
                                            ?>
                                            <div
                                                    class="badge badge-soft-<?= $label ?>  "><?= $task_status_name;?></div>
                                            <?php
                                            ?>
                                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                <div class="btn-group" role="group">
                                                    <button id="btnGroupVerticalDrop1" type="button" class="btn btn-success dropdown-toggle font-size-11 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?= lang('change') ?>"><i class="bx bxs-edit-alt"></i><i class="mdi mdi-chevron-down"></i></button>

                                                    <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                                        <?php foreach ($all_task_kanban_category as $v_status) { ?>
                                                           <a class="dropdown-item" href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id ;?>/<?= $v_status->task_kanban_category_id;?>' ?>"><?= $v_status->kanban_category_name; ?> </a>
                                                           <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-lg-4">
                                <p class="lead bb"></p>
                                <form class="form-horizontal p-20">
                                    <div class="mb-3 row">
                                        <div class="col-md-5"><strong><?= lang('timer_status') ?>:</strong></div>
                                        <div class="col-md-6">
                                            <?php if ($task_details->timer_status == 'on') { ?>
                                                <span class="badge badge-soft-success"><?= lang('on') ?></span>

                                                <a class="btn btn-outline-danger btn-sm "
                                                   href="<?= base_url() ?>admin/tasks/tasks_timer/off/<?= $task_details->task_id ?>/details"><?= lang('stop_timer') ?> </a>
                                            <?php } else { ?>
                                                <span class="badge badge-soft-danger"><?= lang('off') ?></span>
                                                <?php $this_permission = $this->tasks_model->can_action('tbl_task', 'view', array('task_id' => $task_details->task_id), true);
                                                if (!empty($this_permission)) { ?>
                                                    <a class="btn btn-sm btn-success"
                                                       href="<?= base_url() ?>admin/tasks/tasks_timer/on/<?= $task_details->task_id ?>/details"><?= lang('start_timer') ?> </a>
                                                <?php }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-md-5"><strong><?= lang('project_hourly_rate') ?> :</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <?php
                                            if (!empty($task_details->hourly_rate)) {
                                                echo $task_details->hourly_rate;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-md-5"><strong><?= lang('created_by') ?> :</strong></div>
                                        <div class="col-md-6">
                                            <?php
                                            if (!empty($task_details->created_by)) {
                                                echo $this->db->where('user_id', $task_details->created_by)->get('tbl_account_details')->row()->fullname;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-md-5">
                                            <small><?= lang('created_date') ?> :</small>
                                        </div>
                                        <div class="col-md-6">
                                            <?php
                                            if (!empty($task_details->task_created_date)) {
                                                echo display_datetime($task_details->task_created_date);
                                            }
                                            ?>
                                        </div>
                                    </div>

                                </form>
                            </div>

                            <div class="col-lg-4">
                                <p class="lead bb"></p>
                                <form class="form-horizontal p-20">

                                    <?php $show_custom_fields = custom_form_label(3, $task_details->task_id);

                                    if (!empty($show_custom_fields)) {
                                        foreach ($show_custom_fields as $c_label => $v_fields) {
                                            if (!empty($v_fields)) {
                                                ?>
                                                <div class="mb-3 row">
                                                    <div class="col-md-7"><strong><?= $c_label ?> :</strong></div>
                                                    <div class="col-md-5">
                                                        <?= $v_fields ?>
                                                    </div>
                                                </div>
                                            <?php }
                                        }
                                    }
                                    ?>

                                    <div class="mb-3 row">
                                        <div class="col-md-7"><strong><?= lang('estimated_hour') ?>
                                                :</strong></div>
                                        <div class="col-md-5">
                                            <?php if (!empty($task_details->task_hour)) echo $task_details->task_hour; ?> <?= lang('hours') ?>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-md-7"><strong><?= lang('billable') ?>
                                                :</strong></div>
                                        <div class="col-md-5">
                                            <?php if (!empty($task_details->billable)) {
                                                if ($task_details->billable == 'Yes') {
                                                    $billable = 'success';
                                                    $text = lang('yes');
                                                } else {
                                                    $billable = 'danger';
                                                    $text = lang('no');
                                                };
                                            } else {
                                                $billable = '';
                                                $text = '-';
                                            }; ?>
                                            <strong class="badge badge-soft-<?= $billable ?>">
                                                <?= $text ?>
                                            </strong>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-md-7"><strong><?= lang('participants') ?>
                                                :</strong></div>
                                        <div class="col-md-5">
                                            <div class="avatar-group">
                                            <?php
                                            if ($task_details->permission != 'all') {
                                                $get_permission = json_decode($task_details->permission);
                                                if (is_object($get_permission)) : ?>
                                                    <?php 
                                                    foreach ($get_permission as $permission => $v_permission) :
                                                        $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                                                        if ($user_info->role_id == 1) {
                                                            $label = 'text-danger';
                                                        } else {
                                                            $label = 'text-success';
                                                        }
                                                        $profile_info = $this->db->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                                        ?>

                                                    <div class="avatar-group-item">
                                                        <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $profile_info->fullname ?>" class="d-inline-block"><img src="<?= base_url() . $profile_info->avatar ?>" class="rounded-circle avatar-xs mr" alt=""><span style="margin: 0px 0 8px -10px;"
                                                              class="mdi mdi-circle <?= $label ?> font-size-10"></span></a>
                                                    </div>
                                                    <?php
                                                    endforeach; ?>
                                                <?php endif;
                                            } else { ?>
                                            <span class="mr-lg-2 mt-2">
                                            <strong><?= lang('everyone') ?></strong>
                                                <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                            </span>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            $can_edit = $this->tasks_model->can_action('tbl_task', 'edit', array('task_id' => $task_details->task_id));
                                            if (!empty($can_edit) && !empty($edited)) {
                                                ?>
                                                <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?= lang('add_more') ?>" class="mt-2">
                                                   <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/tasks/update_users/<?= $task_details->task_id ?>"  class="text-default"><i class="fa fa-plus"></i></a>
                                                </span>
                                                <?php
                                            }
                                            ?>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                    <div class="col-md-12">
                                        <strong><?= lang('completed') ?>:</strong>
                                    </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <?php
                                        if ($task_details->task_progress < 49) {
                                            $progress = 'progress-bar-danger';
                                        } elseif ($task_details->task_progress > 50 && $task_details->task_progress < 99) {
                                            $progress = 'progress-bar-primary';
                                        } else {
                                            $progress = 'progress-bar-success';
                                        }
                                        ?>
                               
                                        <div class="progress progress-xs progress-striped active" style="">
                                            <div class="progress-bar <?= $progress ?>" role="progressbar" style="width: <?= $task_details->task_progress ?>%;" aria-valuenow="<?= $task_details->task_progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-3">
                                <p class="lead bb"></p>
                                <form class="form-horizontal p-20">

                                    <?php

                                    $task_time = $this->tasks_model->task_spent_time_by_id($task_details->task_id);
                                    ?>
                                    <?= $this->tasks_model->get_time_spent_result($task_time) ?>
                                    <?php
                                    if (!empty($task_details->billable) && $task_details->billable == 'Yes') {
                                        $total_time = $task_time / 3600;
                                        $total_cost = $total_time * $task_details->hourly_rate;
                                        $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                                        ?>
                                        <h2 class="text-center"><?= lang('total_bill') ?>
                                            : <?= display_money($total_cost, $currency->symbol) ?></h2>
                                    <?php }
                                    $estimate_hours = $task_details->task_hour;
                                    $percentage = $this->tasks_model->get_estime_time($estimate_hours);

                                    if ($task_time < $percentage) {
                                        $total_time = $percentage - $task_time;
                                        $worked = '<storng style="font-size: 15px;"  class="required">' . lang('left_works') . '</storng>';
                                    } else {
                                        $total_time = $task_time - $percentage;
                                        $worked = '<storng style="font-size: 15px" class="required">' . lang('extra_works') . '</storng>';
                                    }

                                    ?>
                                    <div class="text-center">
                                        <div class="">
                                            <?= $worked ?>
                                        </div>
                                        <div class="">
                                            <?= $this->tasks_model->get_spent_time($total_time) ?>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-lg-5">
                                <p class="lead bb"></p>
                                <form class="form-horizontal p-20">
                                    <blockquote style="font-size: 12px;word-wrap: break-word;"><?php
                                        if (!empty($task_details->task_description)) {
                                            echo $task_details->task_description;
                                        }
                                        ?></blockquote>
                                </form>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3 row">
                                    <div class="col-lg-6">
                                        <label class="form-label col-md-5"><strong><?= lang('task_name') ?>
                                                :</strong></label>
                                        <p class="form-control-static"><?php if (!empty($task_details->task_name)) echo $task_details->task_name; ?></p>
                                    </div>
                                    <?php super_admin_details_p($task_details->companies_id, 6) ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3 row ">
                                    <div class="col-lg-6">
                                        <label class="form-label col-md-5"><strong><?= lang('task_status') ?>
                                                :</strong></label>
                                        <div class="pull-left mt">
                                            <?php
                                            if ($task_details->task_status == 6) {
                                                $label = 'success';
                                            } elseif ($task_details->task_status == 1) {
                                                $label = 'info';
                                            } elseif ($task_details->task_status == 5) {
                                                $label = 'danger';
                                            } else {
                                                $label = 'warning';
                                            }
                                            ?>
                                            <p class="form-control-static badge badge-soft-<?= $label ?>  "><?= $task_status_name; ?></p>
                                        </div>
                                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                            <div class="col-sm-1 mt">
                                                <div class="btn-group">
                                                     <button id="btnGroupVerticalDrop1" type="button" class="btn btn-success dropdown-toggle font-size-11 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?= lang('change') ?>"><i class="bx bxs-edit-alt"></i><i class="mdi mdi-chevron-down"></i></button>
                                                    <ul class="dropdown-menu animated zoomIn">
                                                        <?php foreach ($all_task_kanban_category as $v_status) { ?>
                                                           <li>
                                                            <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/'. $v_status->task_kanban_category_id ?>"><?= $v_status->kanban_category_name; ?> </a>
                                                        </li>
                                                           <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label col-md-5"><strong><?= lang('timer_status') ?>
                                                :</strong></label>
                                        <div class="col-md-6 mt">
                                            <?php if ($task_details->timer_status == 'on') { ?>
                                                <span class="badge badge-soft-success"><?= lang('on') ?></span>

                                                <a class="btn btn-outline-danger btn-sm "
                                                   href="<?= base_url() ?>admin/tasks/tasks_timer/off/<?= $task_details->task_id ?>/details"><?= lang('stop_timer') ?> </a>
                                            <?php } else {
                                                ?>
                                                <span class="badge badge-soft-danger"><?= lang('off') ?></span>
                                                <?php $this_permission = $this->tasks_model->can_action('tbl_task', 'view', array('task_id' => $task_details->task_id), true);
                                                if (!empty($this_permission)) { ?>
                                                    <a class="btn btn-sm btn-success"
                                                       href="<?= base_url() ?>admin/tasks/tasks_timer/on/<?= $task_details->task_id ?>/details"><?= lang('start_timer') ?> </a>
                                                <?php }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if (!empty($task_details->project_id)):
                            $project_info = $this->db->where('project_id', $task_details->project_id)->get('tbl_project')->row();
                            $milestones_info = $this->db->where('milestones_id', $task_details->milestones_id)->get('tbl_milestones')->row();
                            ?>
                            <div class="mb-3 row ">
                                <div class="col-sm-6">
                                    <label class="form-label col-md-5"><strong><?= lang('project_name') ?>
                                            :</strong></label>
                                    <div class="col-md-6">
                                        <p class="form-control-static"><?php if (!empty($project_info->project_name)) echo $project_info->project_name; ?></p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label col-md-5"><strong><?= lang('milestone') ?>
                                            :</strong></label>
                                    <div class="col-md-6 ">
                                        <p class="form-control-static"><?php if (!empty($milestones_info->milestone_name)) echo $milestones_info->milestone_name; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                        <?php
                        if (!empty($task_details->opportunities_id)):
                            $opportunity_info = $this->db->where('opportunities_id', $task_details->opportunities_id)->get('tbl_opportunities')->row();
                            ?>
                            <div class="mb-3 row ">
                                <div class="col-sm-6">
                                    <label class="form-label col-md-5 "><strong
                                                class="mr-sm"><?= lang('opportunity_name') ?></strong></label>
                                    <div class="col-md-6 " style="margin-left: -5px;">
                                        <p class="form-control-static"><?php if (!empty($opportunity_info->opportunity_name)) echo $opportunity_info->opportunity_name; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php
                        if (!empty($task_details->leads_id)):
                            $leads_info = $this->db->where('leads_id', $task_details->leads_id)->get('tbl_leads')->row();
                            ?>
                            <div class="mb-3 row">
                                <div class="col-sm-6">
                                    <label class="form-label col-md-5 "><strong
                                                class="mr-sm"><?= lang('leads_name') ?></strong></label>
                                    <div class="col-md-6 " style="margin-left: -5px;">
                                        <p class="form-control-static"><?php if (!empty($leads_info->lead_name)) echo $leads_info->lead_name; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php
                        if (!empty($task_details->bug_id)):
                            $bugs_info = $this->db->where('bug_id', $task_details->bug_id)->get('tbl_bug')->row();
                            ?>
                            <div class="mb-3 row">
                                <div class="col-sm-6">
                                    <label class="form-label col-md-5"><strong
                                                class="mr-sm"><?= lang('bug_title') ?></strong></label>
                                    <div class="col-md-6 " style="margin-left: -5px;">
                                        <p class="form-control-static"><?php if (!empty($bugs_info->bug_title)) echo $bugs_info->bug_title; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                        <?php
                        if (!empty($task_details->goal_tracking_id)):
                            $goal_tracking_info = $this->db->where('goal_tracking_id', $task_details->goal_tracking_id)->get('tbl_goal_tracking')->row();
                            ?>
                            <div class="mb-3 row">
                                <div class="col-sm-6">
                                    <label class="form-label col-md-5 "><strong
                                                class="mr-sm"><?= lang('goal_tracking') ?></strong></label>
                                    <div class="col-md-6 " style="margin-left: -5px;">
                                        <p class="form-control-static"><?php if (!empty($goal_tracking_info->subject)) echo $goal_tracking_info->subject; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                        <?php
                        if (!empty($task_details->sub_task_id)):
                            $sub_task = $this->db->where('task_id', $task_details->sub_task_id)->get('tbl_task')->row();
                            ?>
                            <div class="mb-3 row">
                                <div class="col-sm-6">
                                    <label class="form-label col-sm-3 "><strong
                                                class="mr-sm"><?= lang('sub_tasks') ?></strong></label>
                                    <div class="col-md-6">
                                        <p class="form-control-static"><?php if (!empty($sub_task->task_name)) echo $sub_task->task_name; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                        <div class="mb-3 row">
                            <div class="col-sm-6">
                                <label class="form-label col-md-5"><strong><?= lang('start_date') ?>
                                        :</strong></label>
                                <div class="col-md-6 ">
                                    <p class="form-control-static"><?php
                                        if (!empty($task_details->task_start_date)) {
                                            echo display_datetime($task_details->task_start_date);
                                        }
                                        ?></p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <?php
                                $due_date = $task_details->due_date;
                                $due_time = strtotime($due_date);
                                $current_time = time();
                                if ($current_time > $due_time) {
                                    $text = 'text-danger';
                                } else {
                                    $text = null;
                                }
                                ?>

                                <label class="form-label col-md-5"><strong
                                            class="<?= $text ?>"><?= lang('due_date') ?>
                                        :</strong></label>
                                <div class="col-md-6 ">
                                    <p class="form-control-static"><?php
                                        if (!empty($task_details->due_date)) {
                                            echo display_datetime($task_details->due_date);
                                        }
                                        ?></p>

                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-6">
                                <label class="form-label col-md-5"><strong><?= lang('created_by') ?>
                                        :</strong></label>
                                <div class="col-md-6 ">
                                    <p class="form-control-static"><?php
                                        if (!empty($task_details->created_by)) {
                                            echo $this->db->where('user_id', $task_details->created_by)->get('tbl_account_details')->row()->fullname;
                                        }
                                        ?></p>

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label col-md-5"><strong><?= lang('created_date') ?>
                                        :</strong></label>
                                <div class="col-md-6">
                                    <p class="form-control-static"><?php
                                        if (!empty($task_details->task_created_date)) {
                                            echo display_datetime($task_details->task_created_date);
                                        }
                                        ?></p>

                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-6">
                                <label class="form-label col-md-5"><strong><?= lang('project_hourly_rate') ?>
                                        :</strong></label>
                                <div class="col-md-6">
                                    <p class="form-control-static"><?php
                                        if (!empty($task_details->hourly_rate)) {
                                            echo $task_details->hourly_rate;
                                        }
                                        ?></p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label col-md-5"><strong><?= lang('estimated_hour') ?>
                                        :</strong></label>
                                <div class="col-md-6">
                                    <p class="form-control-static">
                                        <strong><?php if (!empty($task_details->task_hour)) echo $task_details->task_hour; ?> <?= lang('hours') ?></strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-6">
                                <label class="form-label col-md-5"><strong><?= lang('billable') ?>
                                        :</strong></label>
                                <div class="col-md-6 ">
                                    <p class="form-control-static">
                                        <?php if (!empty($task_details->billable)) {
                                            if ($task_details->billable == 'Yes') {
                                                $billable = 'success';
                                                $text = lang('yes');
                                            } else {
                                                $billable = 'danger';
                                                $text = lang('no');
                                            };
                                        } else {
                                            $billable = '';
                                            $text = '-';
                                        }; ?>
                                        <strong class="badge badge-soft-<?= $billable ?>">
                                            <?= $text ?>
                                        </strong>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label col-md-5"><strong><?= lang('participants') ?>
                                        :</strong></label>
                                <div class="col-md-6 ">
                                    <div class="avatar-group">
                                            <?php
                                            if ($task_details->permission != 'all') {
                                                $get_permission = json_decode($task_details->permission);
                                                if (is_object($get_permission)) :
                                                    foreach ($get_permission as $permission => $v_permission) :
                                                        $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                                                        if ($user_info->role_id == 1) {
                                                            $label = 'text-danger';
                                                        } else {
                                                            $label = 'text-success';
                                                        }
                                                        $profile_info = $this->db->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                                        ?>
                                                <div class="avatar-group-item">
                                                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                                       title="<?= $profile_info->fullname ?>" class="d-inline-block"><img
                                                                src="<?= base_url() . $profile_info->avatar ?>"
                                                                class="rounded-circle avatar-xs" alt="">
                                                        <span style="margin: 0px 0 8px -10px;"
                                                              class="mdi mdi-circle <?= $label ?> font-size-10"></span>
                                                    </a>
                                                </div>
                                                <?php
                                                endforeach;
                                                endif;
                                            } else { ?>
                                                <span class="mr-lg-2 mt-2">
                                                    <strong><?= lang('everyone') ?></strong>
                                                    <i  title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                                </span>
                                                <?php
                                                }
                                                ?>
                                                <?php
                                                $can_edit = $this->tasks_model->can_action('tbl_task', 'edit', array('task_id' => $task_details->task_id));
                                                if (!empty($can_edit) && !empty($edited)) {
                                                ?>
                                                <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?= lang('add_more') ?>" class="mt-2">
                                                    <a  data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/tasks/update_users/<?= $task_details->task_id ?>" class="text-default"><i class="fa fa-plus"></i></a>                                                
                                                </span>
                                        <?php
                                        }
                                        ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php $show_custom_fields = custom_form_label(3, $task_details->task_id);

                        if (!empty($show_custom_fields)) {
                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                if (!empty($v_fields)) {
                                    if (count($v_fields) == 1) {
                                        $col = 'col-sm-10';
                                        $sub_col = 'col-sm-3';
                                        $style = 'padding-left:8px';
                                    } else {
                                        $col = 'col-sm-6';
                                        $sub_col = 'col-md-5';
                                        $style = null;
                                    }

                                    ?>
                                    <div class="mb-3 row  <?= $col ?>" style="<?= $style ?>">
                                        <label class="form-label <?= $sub_col ?>"><strong><?= $c_label ?>
                                                :</strong></label>
                                        <div class="col-sm-7 ">
                                            <p class="form-control-static">
                                                <strong><?= $v_fields ?></strong>
                                            </p>
                                        </div>
                                    </div>
                                <?php }
                            }
                        }
                        ?>

                        <div class="mb-3 row  col-sm-10">
                            <label class="form-label col-sm-3 "><strong class="mr-sm"><?= lang('completed') ?>
                                    :</strong></label>
                            <div class="col-sm-9 " style="margin-left: -5px;">
                                <?php
                                if ($task_details->task_progress < 49) {
                                    $progress = 'progress-bar-danger';
                                } elseif ($task_details->task_progress > 50 && $task_details->task_progress < 99) {
                                    $progress = 'progress-bar-primary';
                                } else {
                                    $progress = 'progress-bar-success';
                                }
                                ?>
                                <span class="">
                            <div class="mt progress progress-striped ">
                                <div class="progress-bar <?= $progress ?> " data-bs-toggle="tooltip"
                                     data-original-title="<?= $task_details->task_progress ?>%"
                                     style="width: <?= $task_details->task_progress ?>%"></div>
                            </div>
                            </span>
                            </div>
                        </div>
                        <div class="mb-3 row col-sm-12">
                            <?php

                            $task_time = $this->tasks_model->task_spent_time_by_id($task_details->task_id);
                            ?>
                            <?= $this->tasks_model->get_time_spent_result($task_time) ?>
                            <?php
                            if (!empty($task_details->billable) && $task_details->billable == 'Yes') {
                                $total_time = $task_time / 3600;
                                $total_cost = $total_time * $task_details->hourly_rate;
                                $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                                ?>
                                <h2 class="text-center"><?= lang('total_bill') ?>
                                    : <?= display_money($total_cost, $currency->symbol) ?></h2>
                            <?php }
                            $estimate_hours = $task_details->task_hour;
                            $percentage = $this->tasks_model->get_estime_time($estimate_hours);

                            if ($task_time < $percentage) {
                                $total_time = $percentage - $task_time;
                                $worked = '<storng style="font-size: 15px;"  class="required">' . lang('left_works') . '</storng>';
                            } else {
                                $total_time = $task_time - $percentage;
                                $worked = '<storng style="font-size: 15px" class="required">' . lang('extra_works') . '</storng>';
                            }

                            ?>
                            <div class="text-center">
                                <div class="">
                                    <?= $worked ?>
                                </div>
                                <div class="">
                                    <?= $this->tasks_model->get_spent_time($total_time) ?>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-12">
                            <blockquote
                                    style="font-size: 12px; margin-top: 5px;word-wrap: break-word;width: 100%"><?php if (!empty($task_details->task_description)) echo $task_details->task_description; ?></blockquote>
                        </div>
                        <?php } ?>  
                    </div>
                </div>    
            </div>
            <!-- Task Details tab Ends -->
            <!-- Task Comments Panel Starts --->
            <div class="tab-pane fade <?= $active == 2 ? 'show active' : '' ?>" id="task_comments"
                 style="position: relative;">
                <div class="card">
                    <div class="card-body chat" id="chat-box">
                        <h4 class="card-title mb-4"><?= lang('comments') ?></h4>
    
                        <?php echo form_open(base_url("admin/tasks/save_comments"), array("id" => $comment_type . "-comment-form", "class" => "form-horizontal general-form", "enctype" => "multipart/form-data", "role" => "form")); ?>

                        <input type="hidden" name="task_id" value="<?php
                        if (!empty($task_details->task_id)) {
                            echo $task_details->task_id;
                        }
                        ?>" class="form-control">

                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                <?php
                                echo form_textarea(array(
                                    "id" => "comment_description",
                                    "name" => "comment",
                                    "class" => "form-control comment_description",
                                    "placeholder" => $task_details->task_name . ' ' . lang('comments'),
                                    "data-rule-required" => true,
                                    "rows" => 4,
                                    "data-msg-required" => lang("field_required"),
                                ));
                                ?>
                            </div>
                        </div>
                        <div id="new_comments_attachement">
                            <div class="mb-3 row">
                                <div class="col-sm-12">
                                    <div id="comments_file-dropzone" class="dropzone mb15">
                                    </div>
                                    <div id="comments_file-dropzone-scrollbar">
                                        <div id="comments_file-previews">
                                            <div id="file-upload-row" class="mt pull-left">
                                                
                                                <div class="preview box-content pr-lg w-100">
                                                    <span data-dz-remove class="pull-right pointer">
                                                        <i class="fa fa-times"></i>
                                                    </span>
                                                    <img data-dz-thumbnail class="upload-thumbnail-sm"/>
                                                    <input class="file-count-field" type="hidden" name="files[]" value=""/>

                                                    <div class="mb progress progress-striped upload-progress-sm active mt-sm"
                                                         role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                                        <div class="progress-bar progress-bar-success w-0" data-dz-uploadprogress></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                <div class="pull-right">
                                    <button type="submit" id="file-save-button"
                                            class="btn btn-primary"><?= lang('post_comment') ?></button>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <?php echo form_close();
                        $comment_reply_type = 'tasks-reply';
                        ?>
                        <?php $this->load->view('admin/tasks/comments_list', array('comment_details' => $comment_details)) ?>
                        
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $("#<?php echo $comment_type; ?>-comment-form").appForm({
                                    isModal: false,
                                    onSuccess: function (result) {
                                        $(".comment_description").val("");
                                        $(".dz-complete").remove();
                                        $(result.data).insertAfter("#<?php echo $comment_type; ?>-comment-form");
                                            $('#loader-wrapper').hide();

                                        toastr[result.status](result.message);
                                    },
                                    onSubmit: function () {
                                        $('#loader-wrapper').show();

                                   },
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
                                                "<div class='pb clearfix '><button type='button' class='btn btn-xs btn-danger pull-left mr remove-file'><i class='fa fa-times'></i></button> <input class='pull-left' type='file' name='manualFiles[]' /></div>" +
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
            <!-- Task Comments Panel Ends--->

            <!-- Task Attachment Panel Starts --->
            <div class="tab-pane fade <?= $active == 3 ? 'show active' : '' ?>" id="task_attachments">
                <div class="card">
                    <div class="card-body border-bottom">
                        <div class="row">
                            <div class="col-md-4 col-7">
                                <h4 class="card-title mt"><?= lang('attach_file_list') ?></h4>
                            </div>
                            <div class="col-md-8 col-5">
                                <?php
                                $attach_list = $this->session->userdata('tasks_media_view');
                                if (empty($attach_list)) {
                                    $attach_list = 'list_view';
                                }
                                ?>
                                <ul class="list-inline user-chat-nav text-end mb-0">
                    
                                    <li class="list-inline-item  d-sm-inline-block">
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-default toggle-media-view <?= (!empty($attach_list) && $attach_list == 'list_view' ? 'hidden' : '') ?>" data-bs-type="list_view" title="<?= lang('switch_to') . ' ' . lang('media_view') ?>"><i class="fa fa-image"></i></a>
                                    
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-default toggle-media-view <?= (!empty($attach_list) && $attach_list == 'media_view' ? 'hidden' : '') ?>" data-bs-type="media_view" title="<?= lang('switch_to') . ' ' . lang('list_view') ?>"><i class="fa fa-list"></i></a>
                                    </li>
                                    <li class="list-inline-item d-sm-inline-block">
                                        <a href="<?= base_url() ?>admin/tasks/new_attachment/<?= $task_details->task_id ?>" class="text-purple text-sm" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal_extra_lg">
                                            <span class="d-block d-sm-none"><i class="fa fa-plus "></i></span>
                                            <span class="d-none d-sm-block"><?= lang('new') . ' ' . lang('attachment') ?></span>
                                        </a>
                                    </li>

                                </ul>   
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $(".toggle-media-view").click(function () {
                                    $(".media-view-container").toggleClass('hidden');
                                    $(".toggle-media-view").toggleClass('hidden');
                                    $(".media-list-container").toggleClass('hidden');
                                    var type = $(this).data('bs-type');
                                    var module = 'tasks';
                                    $.get('<?= base_url()?>admin/global_controller/set_media_view/' + type + '/' + module, function (response) {
                                    });
                                });
                            });
                        </script>
                        <?php
                        $this->load->helper('file');
                        if (empty($project_files_info)) {
                            $project_files_info = array();
                        } ?>
                        <div class="p media-view-container <?= (!empty($attach_list) && $attach_list == 'media_view' ? 'hidden' : '') ?>">
                            <div class="row">
                                <?php $this->load->view('admin/tasks/attachment_list', array('project_files_info' => $project_files_info)); ?>
                            </div>
                        </div>
                        <div  class="media-list-container <?= (!empty($attach_list) && $attach_list == 'list_view' ? 'hidden' : '') ?>">
                            <div class="col-md-12 pr0 mb-sm">
                                <div class="card shadow border">
                                    <div class="card-body">
                                        <div class="accordion accordion-flush" id="accordionFlushExample">
                                        <?php if (!empty($project_files_info)) {
                                            foreach ($project_files_info as $key => $v_files_info) { ?>
                                            <div class="accordion-item" id="media_list_container-<?= $files_info[$key]->task_attachment_id ?>">
                                                <h2 class="card-title accordion-header" id="flush-headingOne">
                                                    
                                                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#attachment-<?php echo $key ?>" aria-expanded="true" aria-controls="flush-collapseOne">
                                                    <span style="width:80%"><?php echo $files_info[$key]->title; ?></span>
                                                    <div class="pull-right" style="margin-left:15%">
                                                    <?php if ($files_info[$key]->user_id == $this->session->userdata('user_id')) { ?>
                                                        <?php echo ajax_anchor(base_url("admin/tasks/delete_task_files/" . $files_info[$key]->task_attachment_id), "<i class='text-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#media_list_container-" . $files_info[$key]->task_attachment_id)); ?>
                                                    <?php } ?>            
                                                    </div>

                                                    </button>
                                                </h2>
                                                <div id="attachment-<?php echo $key ?>" class="accordion-collapse collapse <?php if (!empty($in) && $files_info[$key]->files_id == $in) { echo 'show'; } ?>" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                    <div class="accordion-body text-muted">
                                                        <div class="table-responsive">
                                                            <table id="table-files_datatable" class="table table-striped dt-responsive nowrap w-100 ">
                                                                <thead>
                                                                <tr>
                                                                    <th><?= lang('files') ?></th>
                                                                    <th class=""><?= lang('size') ?></th>
                                                                    <th><?= lang('date') ?></th>
                                                                    <th><?= lang('total') . ' ' . lang('comments') ?></th>
                                                                    <th><?= lang('uploaded_by') ?></th>
                                                                    <th><?= lang('action') ?></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $this->load->helper('file');

                                                                if (!empty($v_files_info)) {
                                                                    foreach ($v_files_info as $v_files) {
                                                                        $user_info = $this->db->where(array('user_id' => $files_info[$key]->user_id))->get('tbl_users')->row();
                                                                        $total_file_comment = count($this->db->where(array('uploaded_files_id' => $v_files->uploaded_files_id))->order_by('comment_datetime', 'DESC')->get('tbl_task_comment')->result());
                                                                        ?>
                                                                        <tr class="file-item">
                                                                            <td data-bs-toggle="tooltip"
                                                                                data-bs-placement="top"
                                                                                data-original-title="<?= $files_info[$key]->description ?>">
                                                                                <?php if ($v_files->is_image == 1) : ?>
                                                                                    <div class="file-icon"><a
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#myModal_extra_lg"
                                                                                                href="<?= base_url() ?>admin/tasks/attachment_details/r/<?= $files_info[$key]->task_attachment_id . '/' . $v_files->uploaded_files_id ?>">
                                                                                            <img
                                                                                                    style="width: 50px;border-radius: 5px;"
                                                                                                    src="<?= base_url() . $v_files->files ?>"/></a>
                                                                                    </div>
                                                                                <?php else : ?>
                                                                                    <div class="file-icon"><i
                                                                                                class="fa fa-file-o"></i>
                                                                                        <a data-bs-toggle="modal"
                                                                                           data-bs-target="#myModal_extra_lg"
                                                                                           href="<?= base_url() ?>admin/tasks/attachment_details/r/<?= $files_info[$key]->task_attachment_id . '/' . $v_files->uploaded_files_id ?>"><?= $v_files->file_name ?></a>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </td>

                                                                            <td class=""><?= $v_files->size ?>Kb</td>
                                                                            <td class="col-date"><?= date('Y-m-d' . "<br/> h:m A", strtotime($files_info[$key]->upload_time)); ?></td>
                                                                            <td class=""><?= $total_file_comment ?></td>
                                                                            <td>
                                                                                <?= $user_info->username ?>
                                                                            </td>
                                                                            <td>
                                                                                <a class="btn btn-sm btn-dark"
                                                                                   data-bs-toggle="tooltip"
                                                                                   data-bs-placement="top"
                                                                                   title="Download"
                                                                                   href="<?= base_url() ?>admin/tasks/download_files/<?= $v_files->uploaded_files_id ?>"><i
                                                                                            class="fa fa-download"></i></a>
                                                                            </td>

                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <tr>
                                                                        <td colspan="5">
                                                                            <?= lang('nothing_to_display') ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }   }   ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Task Attachment Panel Ends --->
            <div class="tab-pane fade <?= $active == 4 ? 'show active' : '' ?>" id="task_notes" style="position: relative;">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><?= lang('notes') ?></h4>

                        <form action="<?= base_url() ?>admin/tasks/save_tasks_notes/<?php
                        if (!empty($task_details)) {
                            echo $task_details->task_id;
                        }
                        ?>" enctype="multipart/form-data" method="post" id="form" class="form-horizontal">
                            <div class="mb-3 row">
                                <div class="col-lg-12">
                                    <textarea class="form-control textarea" id="elm1" name="tasks_notes"><?= $task_details->tasks_notes ?></textarea>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-sm-2">
                                    <button type="submit" id="sbtn" class="btn btn-primary"><?= lang('updates') ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade <?= $active == 5 ? 'show active' : '' ?>" id="timesheet" style="position: relative;">
                <div class="card shadow-none border">
                    <div class="card-body">
                        <style>
                            .tooltip-inner {
                                white-space: pre-wrap;
                            }
                        </style>
                        <!-- Tabs within a box -->
                        <ul class="nav nav-tabs bg-light rounded"  role="tablist">
                            <li class="nav-item">
                                <a class="nav-link <?= $time_active == 1 ? 'active' : ''; ?>" href="#general" data-bs-toggle="tab">
                                    <?= lang('timesheet') ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $time_active == 2 ? 'active' : ''; ?>" href="#contact" data-bs-toggle="tab">
                                    <?= lang('manual_entry') ?>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content p-3 text-muted">
                            <!-- ************** general *************-->
                            <div class="tab-pane <?= $time_active == 1 ? 'active' : ''; ?>" id="general" role="tabpanel">
                                <div class="table-responsive mb-0" data-pattern="priority-columns">
                                    <table id="table-tasks-timelog_datatable" class="table table-striped dt-responsive nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th><?= lang('user') ?></th>
                                            <th><?= lang('start_time') ?></th>
                                            <th><?= lang('stop_time') ?></th>
                                            <th><?= lang('task_name') ?></th>
                                            <th class="col-time"><?= lang('time_spend') ?></th>
                                            <th><?= lang('action') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($total_timer)) {
                                            foreach ($total_timer as $v_tasks) {
                                                $task_info = $this->db->where(array('task_id' => $v_tasks->task_id))->get('tbl_task')->row();
                                                if (!empty($task_info)) {
                                                    ?>
                                                    <tr id="table_tasks_timer-<?= $v_tasks->tasks_timer_id ?>">
                                                        <td class="small">

                                                            <a class="pull-left recect_task  ">
                                                                <?php
                                                                $profile_info = $this->db->where(array('user_id' => $v_tasks->user_id))->get('tbl_account_details')->row();
                                                                $user_info = $this->db->where(array('user_id' => $v_tasks->user_id))->get('tbl_users')->row();
                                                                if (!empty($user_info)) {
                                                                    ?>
                                                                    <img style="width: 30px;margin-left: 18px;
                                                                                 height: 29px;
                                                                                 border: 1px solid #aaa;"
                                                                         src="<?= base_url() . $profile_info->avatar ?>"
                                                                         class="img-circle">

                                                                    <?= ucfirst($user_info->username) ?>
                                                                <?php } else {
                                                                    echo '-';
                                                                } ?>
                                                            </a>


                                                        </td>

                                                        <td><span class="badge badge-soft-success"><?= display_datetime($v_tasks->start_time, true) ?></span>
                                                        </td>
                                                        <td><span class="badge badge-soft-danger"><?= display_datetime($v_tasks->end_time, true) ?></span>
                                                        </td>

                                                        <td>
                                                            <a href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_tasks->task_id ?>"
                                                               class="text-info small"><?= $task_info->task_name ?>
                                                                <?php
                                                                if (!empty($v_tasks->reason)) {
                                                                    $edit_user_info = $this->db->where(array('user_id' => $v_tasks->edited_by))->get('tbl_users')->row();
                                                                    echo '<i class="text-danger" data-html="true" data-bs-toggle="tooltip" data-bs-placement="top" title="Reason : ' . $v_tasks->reason . '<br>' . ' Edited By : ' . $edit_user_info->username . '">Edited</i>';
                                                                }
                                                                ?>
                                                            </a></td>
                                                        <td>
                                                            <small
                                                                    class="small text-muted"><?= $this->tasks_model->get_time_spent_result($v_tasks->end_time - $v_tasks->start_time) ?></small>
                                                        </td>
                                                        <td>
                                                            <?= btn_edit('admin/tasks/view_task_details/' . $v_tasks->tasks_timer_id . '/5/edit') ?>
                                                            <?php if ($v_tasks->user_id == $this->session->userdata('user_id')) { ?>
                                                                <?php echo ajax_anchor(base_url("admin/tasks/update_tasks_timer/" . $v_tasks->tasks_timer_id . '/delete_task_timmer'), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:27px'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_tasks_timer-" . $v_tasks->tasks_timer_id)); ?>
                                                            <?php } ?>
                                                        </td>

                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div> 
                            </div>
                            <div class="tab-pane <?= $time_active == 2 ? 'active' : ''; ?>" id="contact" role="tabpanel">
                                <div class="card-body">
                                    <form role="form" enctype="multipart/form-data" id="form" action="<?php echo base_url(); ?>admin/tasks/update_tasks_timer/<?php
                                          if (!empty($tasks_timer_info)) {
                                              echo $tasks_timer_info->tasks_timer_id;
                                          }
                                          ?>" method="post" class="form-horizontal">
                                        <?php
                                        if (!empty($tasks_timer_info)) {
                                            $start_date = date('d-m-Y H-i', strtotime($tasks_timer_info->start_time));
                                            $end_date = date('d-m-Y H-i', strtotime($tasks_timer_info->end_time));
                                        } else {
                                            $start_date = date('d-m-Y H-i');
                                            $end_date = date('d-m-Y H-i');
                                        }
                                        ?>
                                        <?php if ($this->session->userdata('user_type') == '1' && empty($tasks_timer_info->tasks_timer_id)) { ?>
                                            <div class="mb-3 row margin">
                                                <div class="col-md-6 center-block">
                                                    <label class="form-label"><?= lang('select') . ' ' . lang('tasks') ?>
                                                        <span class="required">*</span></label>
                                                    <select class="form-control select_box" name="task_id"
                                                            required="" style="width: 100%">
                                                        <?php
                                                        $all_tasks_info = get_result('tbl_task');
                                                        if (!empty($all_tasks_info)):foreach ($all_tasks_info as $v_task_info):
                                                            ?>
                                                            <option
                                                                    value="<?= $v_task_info->task_id ?>" <?= $v_task_info->task_id == $task_details->task_id ? 'selected' : null ?>><?= $v_task_info->task_name ?></option>
                                                        <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <input type="hidden" name="task_id" value="<?= $task_details->task_id ?>">
                                        <?php } ?>
                                        <div class="mb-3 row margin">
                                            <div class="col-md-6">
                                                <label class="form-label"><?= lang('start_date') ?> </label>
                                                <div class="input-group" id="datepicker1">
                                                    <input type="text" name="start_time" class="form-control datepicker" autocomplete="off" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container='#datepicker1' value="<?= $start_date ?>">
                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row margin">
                                            <div class="col-md-6">
                                                <label class="form-label"><?= lang('end_date') ?></label>
                                                <div class="input-group" id="datepicker2">
                                                    <input type="text" name="end_time" class="form-control datepicker" autocomplete="off" value="<?= $end_date ?>" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container='#datepicker2'>
                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row margin">
                                            <div class="col-md-6">
                                                <label class="form-label"><?= lang('edit_reason') ?><span
                                                            class="required">*</span></label>
                                                <div>
                                                        <textarea class="form-control" name="reason" required="" rows="6"><?php
                                                            if (!empty($tasks_timer_info)) {
                                                                echo $tasks_timer_info->reason;
                                                            }
                                                            ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row" style="margin-top: 20px;">
                                            <div class="col-lg-6">
                                                <button type="submit"
                                                        class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (!empty($sub_tasks)) { ?>
            <div class="tab-pane fade <?= $active == 7 ? 'show active' : '' ?>" id="sub_tasks">
                <div class="card shadow-none border">
                    <div class="card-body">
                        <!-- Tabs within a box -->
                        <ul class="nav nav-tabs bg-light rounded"  role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#sub_general" data-bs-toggle="tab">
                                    <?= lang('all') . ' ' . lang('sub_tasks') ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('admin/tasks/new_tasks/sub_tasks/' . $task_details->task_id) ?>">
                                    <?= lang('new') . ' ' . lang('sub_tasks') ?>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content p-3 text-muted">
                            <!-- ************** general *************-->
                            <div class="tab-pane <?= $time_active == 1 ? 'active' : ''; ?>" id="sub_general" role="tabpanel">
                                    <div class="table-responsive mb-0">
                                        <table id="table-tasks_datatable" class="table table-striped dt-responsive nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th data-check-all>
                                                    <div class="form-check font-size-16 check-all">
                                                        <input type="checkbox" id="parent_present" class="form-check-input">
                                                        <label for="parent_present" class="toggle form-check-label"></label>
                                                    </div>
                                                </th>
                                                <th><?= lang('task_name') ?></th>
                                                <th><?= lang('due_date') ?></th>
                                                <th class="col-sm-1"><?= lang('progress') ?></th>
                                                <th class="col-sm-1"><?= lang('status') ?></th>
                                                <th class="col-sm-2"><?= lang('changes/view') ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $t_edited = can_action('54', 'edited');
                                            if (!empty($all_sub_tasks)):foreach ($all_sub_tasks as $key => $v_task):
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="complete form-check font-size-16">
                                                            <input type="checkbox" data-id="<?= $v_task->task_id ?>"
                                                                       style="position: absolute;" <?php
                                                                if ($v_task->task_progress >= 100) {
                                                                    echo 'checked';
                                                                }
                                                                ?> class="form-check-input">
                                                            <label class="form-check-label"></label>
                                                        </div>
                                                    </td>
                                                    <td><a class="text-info" style="<?php
                                                        if ($v_task->task_progress >= 100) {
                                                            echo 'text-decoration: line-through;';
                                                        }
                                                        ?>"
                                                           href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
                                                    </td>

                                                    <td><?php
                                                        $due_date = $v_task->due_date;
                                                        $due_time = strtotime($due_date);
                                                        $current_time = time();
                                                        ?>
                                                        <?= display_datetime($due_date) ?>
                                                        <?php if ($current_time > $due_time && $v_task->task_progress < 100) { ?>
                                                            <span
                                                                    class="badge badge-soft-danger"><?= lang('overdue') ?></span>
                                                        <?php } ?></td>
                                                    <td>
                                                        <div class="inline ">
                                                            <div class="easypiechart text-success" style="margin: 0px;"
                                                                 data-percent="<?= $v_task->task_progress ?>"
                                                                 data-line-width="5" data-track-Color="#f0f0f0"
                                                                 data-bar-color="#<?php
                                                                 if ($v_task->task_progress == 100) {
                                                                     echo '8ec165';
                                                                 } else {
                                                                     echo 'fb6b5b';
                                                                 }
                                                                 ?>" data-rotate="270" data-scale-Color="false"
                                                                 data-size="50" data-animate="2000">
                                                                <span class="small text-muted"><?= $v_task->task_progress ?>
                                                                    %</span>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($v_task->task_status == 6) {
                                                            $label = 'success';
                                                        } elseif ($v_task->task_status == 1) {
                                                            $label = 'info';
                                                        } elseif ($v_task->task_status == 5) {
                                                            $label = 'danger';
                                                        } else {
                                                            $label = 'warning';
                                                        }
                                                        ?>
                                                        <span
                                                                class="badge badge-soft-<?= $label ?>"><?= $task_status_name; ?> </span>
                                                    </td>
                                                    <td>
                                                        <?php echo btn_view('admin/tasks/view_task_details/' . $v_task->task_id) ?>
                                                        <?php if (!empty($t_edited)) { ?>
                                                            <?php echo btn_edit('admin/tasks/new_tasks/' . $v_task->task_id) ?>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                            </div>
                            <div class="tab-pane fade <?= $time_active == 2 ? 'active' : ''; ?>" id="contact" role="tabpanel">
                                <div class="card-body"> 
                                    <form role="form" enctype="multipart/form-data" id="form"
                                          action="<?php echo base_url(); ?>admin/tasks/update_tasks_timer/<?php
                                          if (!empty($tasks_timer_info)) {
                                              echo $tasks_timer_info->tasks_timer_id;
                                          }
                                          ?>" method="post" class="form-horizontal">
                                        <?php
                                        if (!empty($tasks_timer_info)) {
                                            $start_date = date('d-m-Y H-i', strtotime($tasks_timer_info->start_time));
                                            $end_date = date('d-m-Y H-i', strtotime($tasks_timer_info->end_time));
                                        } else {
                                            $start_date = date('d-m-Y H-i');
                                            $end_date = date('d-m-Y H-i');
                                        }
                                        ?>
                                        <?php if ($this->session->userdata('user_type') == '1' && empty($tasks_timer_info->tasks_timer_id)) { ?>
                                            <div class="mb-3 row margin">
                                                <div class="col-md-6 center-block">
                                                    <label
                                                            class="form-label"><?= lang('select') . ' ' . lang('tasks') ?>
                                                        <span
                                                                class="required">*</span></label>
                                                    <select class="form-control select_box" name="task_id"
                                                            required="" style="width: 100%">
                                                        <?php
                                                        $all_tasks_info = get_result('tbl_task');
                                                        if (!empty($all_tasks_info)):foreach ($all_tasks_info as $v_task_info):
                                                            ?>
                                                            <option
                                                                    value="<?= $v_task_info->task_id ?>" <?= $v_task_info->task_id == $task_details->task_id ? 'selected' : null ?>><?= $v_task_info->task_name ?></option>
                                                        <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <input type="hidden" name="task_id"
                                                   value="<?= $task_details->task_id ?>">
                                        <?php } ?>
                                        <div class="mb-3 row margin">
                                            <div class="col-md-6">
                                                <label class="form-label"><?= lang('start_date') ?> </label>
                                                <div class="input-group">
                                                    <input type="text" name="start_time"
                                                           class="form-control datepicker" autocomplete="off"
                                                           value="<?= $start_date ?>"
                                                           data-date-format="<?= config_item('date_picker_format'); ?>">
                                                    <div class="input-group-addon">
                                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row margin">
                                            <div class="col-md-6">
                                                <label class="form-label"><?= lang('end_date') ?></label>
                                                <div class="input-group">
                                                    <input type="text" name="end_time"
                                                           class="form-control datepicker" autocomplete="off" value="<?= $end_date ?>"
                                                           data-date-format="<?= config_item('date_picker_format'); ?>">
                                                    <div class="input-group-addon">
                                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row margin">
                                            <div class="col-md-6">
                                                <label class="form-label"><?= lang('edit_reason') ?><span
                                                            class="required">*</span></label>
                                                <div>
                                                    <textarea class="form-control" name="reason" required="" rows="6"><?php
                                                        if (!empty($tasks_timer_info)) {
                                                            echo $tasks_timer_info->reason;
                                                        }
                                                        ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row" style="margin-top: 20px;">
                                            <div class="col-lg-6">
                                                <button type="submit"
                                                        class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="tab-pane fade " id="activities">
                <div class="card">
                    <div class="card-body">
                        <?php
                        $role = $this->session->userdata('user_type');
                        if ($role == 1) {
                            ?>
                        <span class="btn-sm pull-right">
                        <a href="<?= base_url() ?>admin/tasks/claer_activities/tasks/<?= $task_details->task_id ?>"><?= lang('clear') . ' ' . lang('activities') ?></a>
                        </span>
                        <?php } ?>
                        <h4 class="card-title mb-4 mt"><?= lang('activities') ?> </h4>
                        <div data-simplebar style="max-height: 800px;">  
                            <ul class="verti-timeline list-unstyled">
                                <?php
                                if (!empty($activities_info)) {
                                foreach ($activities_info as $v_activities) {
                                    $profile_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_account_details')->row();
                                    $user_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_users')->row();
                                    ?>
                                <li class="event-list">
                                    <div class="event-timeline-dot">
                                        <i class="bx bx-right-arrow-circle font-size-18"></i>
                                    </div>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <h5 class="font-size-14"><?php echo date('d', strtotime($v_activities->activity_date)) ?> <?php echo date('M', strtotime($v_activities->activity_date)) ?> <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div>
                                                <?php if (!empty($profile_info)) { ?>
                                                <h5 class="notice-calendar-heading-title">
                                                        <a href="<?= base_url() ?>admin/user/user_details/<?= $profile_info->user_id ?>"
                                                               class="text-info"><?= $profile_info->fullname ?></a>
                                                </h5>
                                                <?php } ?>
                                                
                                                <div class="notice-calendar-date">
                                                    <p><?= sprintf(lang($v_activities->activity)) ?>
                                                        <strong><?= $v_activities->value1 ?></strong>
                                                        <?php if (!empty($v_activities->value2)){ ?>
                                                        <p class="m0 p0"><strong><?= $v_activities->value2 ?></strong></p>
                                                        <?php } ?>
                                                    </p>
                                                </div>
                                                <span style="font-size: 10px;" class="">
                                                    <strong>
                                                        <?= time_ago($v_activities->activity_date); ?>
                                                    </strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php } }   ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
    </div>
</div>