<?php
echo message_box('success');
$comment_details = $this->db->where('goal_tracking_id', $goal_info->goal_tracking_id)->get('tbl_task_comment')->result();
$all_task_info = $this->db->where('goal_tracking_id', $goal_info->goal_tracking_id)->order_by('task_id', 'DESC')->get('tbl_task')->result();
$activities_info = $this->db->where(array('module' => 'goal_tracking', 'module_field_id' => $goal_info->goal_tracking_id))->order_by('activity_date', 'desc')->get('tbl_activities')->result();
$goal_type_info = $this->db->where('goal_type_id', $goal_info->goal_type_id)->get('tbl_goal_type')->row();

$progress = $this->items_model->get_progress($goal_info);

$can_edit = $this->items_model->can_action('tbl_goal_tracking', 'edit', array('goal_tracking_id' => $goal_info->goal_tracking_id));

$end_date = $goal_info->end_date;
$due_time = strtotime($end_date);
$current_time = time();
if ($current_time > $due_time) {
    $text = 'text-danger';
    $ribbon = 'danger';

} else {
    $text = null;
}

if ($progress['progress'] == 100) {
    $prgs = '8ec165';
    $p_text = 'success';
    $ribbon = 'success';
    $text = null;
    $status = lang('achieved');
} elseif ($progress['progress'] >= 40 && $progress['progress'] <= 50) {
    $prgs = '5d9cec';
    $p_text = 'primary';
} elseif ($progress['progress'] >= 51 && $progress['progress'] <= 99) {
    $prgs = '7266ba';
    $p_text = 'purple';
} else {
    $prgs = 'fb6b5b';
    $p_text = 'primary';
}
$edited = can_action('69', 'edited');
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
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    
                    <a class="nav-link mb-2 <?= $active == 1 ? 'active' : '' ?>"  href="#task_details" data-bs-toggle="tab" aria-controls="task_details" aria-selected="false"><?= lang('goal') . ' ' . lang('details') ?></a>

                    <a class="nav-link mb-2 <?= $active == 2 ? 'active' : '' ?>" href="#task_comments" data-bs-toggle="tab" aria-controls="task_comments" aria-selected="false"><?= lang('comments') ?> 
                        <strong class="badge badge-soft-danger pull-right"><?= (!empty($comment_details) ? count($comment_details) : null) ?></strong>
                    </a>
                    
                    <a class="nav-link mb-2 <?= $active == 6 ? 'active' : '' ?>" href="#task" data-bs-toggle="tab" aria-controls="task" aria-selected="false"><?= lang('tasks') ?>
                        <strong class="badge badge-soft-danger pull-right"><?= (!empty($all_task_info) ? count($all_task_info) : null) ?></strong>
                    </a>
                    
                    <a class="nav-link mb-2 <?= $active == 6 ? 'active' : '' ?>" href="#activities"  data-bs-toggle="tab" aria-controls="activities" aria-selected="false"><?= lang('activities') ?>
                        <strong class="badge badge-soft-danger pull-right"><?= (!empty($activities_info) ? count($activities_info) : null) ?></strong>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
                    <!-- Task Details tab Starts -->
                    <div class="tab-pane fade <?= $active == 1 ? 'show active' : '' ?>" id="task_details" style="position: relative;">
                        <div class="card-body">
                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                            <div class="col-sm-2 pull-right float-end">
                                <a href="<?php echo base_url() ?>admin/goal_tracking/index/<?= $goal_info->goal_tracking_id ?>"
                                   class="btn-sm"><i class="fa fa-edit"></i> <?= lang('edit') ?></a>
                                <div class="product-ribbon bg-<?php if (!empty($ribbon)) {  echo $ribbon; } else { echo 'primary'; } ?>">
                                    <span>
                                        <?php
                                        if (!empty($text)) {
                                            echo lang('failed');
                                        } elseif (!empty($status)) {
                                            echo $status;
                                        } else {
                                            echo lang('ongoing');
                                        } ?>
                                    </span>
                                </div>
                            </div>
                            <?php } ?>
                            <h4 class="card-title mb-4"><?= $goal_info->subject ?> - <?= lang('details') ?></h4>                         
                            <div class="form-horizontal row task_details">
                                <!-- Details START -->
                                <div class="row mb-3  col-sm-6">
                                    <label class="form-label col-sm-4"><strong><?= lang('subject') ?>
                                            :</strong></label>
                                    <div class="col-sm-8 ">
                                        <p class="form-control-static"><?= $goal_info->subject ?></p>

                                    </div>
                                </div>
                                <div class="row mb-3  col-sm-6">
                                    <label class="form-label col-sm-4"><strong><?= lang('type') ?>:</strong></label>
                                    <div class="col-sm-8 ">
                                        <p class="form-control-static"><span data-bs-toggle="tooltip" data-bs-placement="top"
                                                                             title="<?= $goal_type_info->description ?>"><?= lang($goal_type_info->type_name) ?></span>
                                        </p>

                                    </div>
                                </div>
                                <div class="row mb-3  col-sm-6">
                                    <label class="form-label col-sm-4"><strong><?= lang('start_date') ?>
                                            :</strong></label>
                                    <div class="col-sm-8 ">
                                        <p class="form-control-static">
                                            <?= display_datetime($goal_info->start_date); ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="row mb-3  col-sm-6">
                                    <label class="form-label col-sm-4 <?= $text ?>"><strong><?= lang('end_date') ?>
                                            :</strong></label>
                                    <div class="col-sm-8 ">
                                        <p class="form-control-static <?= $text ?>">
                                            <?= display_datetime($goal_info->end_date); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="row mb-3  col-sm-6">
                                    <label class="form-label col-sm-4 <?php if (!empty($text)) { echo $text;  } else { echo 'text-' . $p_text;  } ?>">
                                        <strong><?= lang('status') ?> :</strong>
                                    </label>
                                    <div class="col-sm-8 ">
                                        <div class="row">
                                            <p class="form-control-static col-sm-4 <?php if (!empty($text)) { echo $text;  } else {  echo 'text-' . $p_text;  } ?>">
                                                <?php
                                                if (!empty($text)) {
                                                    echo lang('failed');
                                                } elseif (!empty($status)) {
                                                    echo $status;
                                                } else {
                                                    echo lang('ongoing');
                                                }
                                                ?>
                                            </p>
                                            <span class="pull-left col-sm-6">
                                                <?php
                                                if (!empty($text)) { ?>
                                                    <a class="btn btn-outline-danger btn-sm"
                                                       href="<?= base_url() ?>admin/goal_tracking/send_notifier/<?= $goal_info->goal_tracking_id; ?>/field"><?= lang('send_notifier') ?></a>
                                                <?php } elseif (!empty($status)) { ?>
                                                    <a class="btn btn-outline-success btn-sm"
                                                       href="<?= base_url() ?>admin/goal_tracking/send_notifier/<?= $goal_info->goal_tracking_id; ?>/success"><?= lang('send_notifier') ?></a>
                                                <?php } ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3  col-sm-6">
                                    <label class="form-label col-sm-4"><strong><?= lang('participants') ?> :</strong></label>
                                    <div class="col-sm-8 ">
                                        <div class="avatar-group">
                                            <?php
                                            if ($goal_info->permission != 'all') {
                                                $get_permission = json_decode($goal_info->permission);
                                                if (!empty($get_permission)) :
                                                foreach ($get_permission as $permission => $v_permission) :
                                                    $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                                                    if ($user_info->role_id == 1) {
                                                        $label = 'circle-danger';
                                                    } else {
                                                        $label = 'circle-success';
                                                    }
                                                    $profile_info = $this->db->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                                    ?>
                                                <div class="avatar-group-item">
                                                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $profile_info->fullname ?>" class="d-inline-block"><img src="<?= base_url() . $profile_info->avatar ?>" class="rounded-circle avatar-xs" alt=""><span style="margin: 0px 0 8px -10px;" class="mdi mdi-circle <?= $label ?> font-size-10"></span></a>
                                                </div>

                                                <?php
                                                endforeach;
                                                endif;
                                            } else { ?>
                                                <span class="mr-lg-2">
                                                    <strong><?= lang('everyone') ?></strong>
                                                    <i  title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                                </span>
                                            <?php }  ?>
                                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                            <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?= lang('add_more') ?>" class="mt-2">
                                                <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/goal_tracking/update_users/<?= $goal_info->goal_tracking_id ?>" class="text-default"><i class="fa fa-plus"></i></a>
                                            </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3  col-sm-12 text-center mt">
                                    <h4>
                                        <small> <?= lang('completed') . ' ' . lang('achievements') ?> :</small>
                                        <?= $progress['achievement'] ?>
                                    </h4>
                                    <small class="text-center block">
                                        <?= lang('achievement') ?>:
                                        <?= $goal_info->achievement ?>

                                    </small>
                                    <div class="text-center block mt-lg">
                                        <div class="inline">
                                            <div class="easypiechart text-success" style="margin: 0px;" data-percent="<?= $progress['progress'] ?>" data-line-width="5" data-track-Color="#f0f0f0" data-bar-color="#<?= $prgs ?>" data-rotate="270" data-scale-Color="false" data-size="50" data-animate="2000">
                                                <span class="large text-muted"><?= $progress['progress'] ?> %</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row mb-3 col-sm-12">
                                    <div class="col-sm-12">
                                        <blockquote style="font-size: 12px; height: 100px;"><?php
                                            if (!empty($goal_info->description)) {
                                                echo $goal_info->description;
                                            }
                                            ?></blockquote>
                                    </div>
                                </div>
                                <!-- Details END -->
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="task_comments" style="position: relative;">
                        <div class="card-body">    
                            <h4 class="card-title mb-4"><?= lang('comments') ?></h4>
                            <div class="chat">
                                <form id="form_validation" action="<?php echo base_url() ?>admin/goal_tracking/save_comments"
                                      method="post" class="form-horizontal">
                                    <input type="hidden" name="goal_tracking_id" value="<?php
                                    if (!empty($goal_info->goal_tracking_id)) {
                                        echo $goal_info->goal_tracking_id;
                                    }
                                    ?>" class="form-control">
                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <textarea class="form-control textarea" id="elm1" placeholder="<?= $goal_info->subject . ' ' . lang('comments') ?>" name="comment" style="height: 70px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <div class="pull-right">
                                                <button type="submit" id="sbtn" class="btn btn-primary"><?= lang('post_comment') ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr/>
                                <?php $comment_type = 'goals';
                                if (!empty($comment_details)):foreach ($comment_details as $key => $v_comment):
                                    $user_info = $this->db->where(array('user_id' => $v_comment->user_id))->get('tbl_users')->row();
                                    $profile_info = $this->db->where(array('user_id' => $v_comment->user_id))->get('tbl_account_details')->row();
                                    if ($user_info->role_id == 1) {
                                        $label = '<small style="font-size:10px;padding:2px;" class="badge badge-soft-danger ">' . lang('admin') . '</small>';
                                    } elseif ($user_info->role_id == 3) {
                                        $label = '<small style="font-size:10px;padding:2px;" class="badge badge-soft-primary">' . lang('staff') . '</small>';
                                    } else {
                                        $label = '<small style="font-size:10px;padding:2px;" class="badge badge-soft-success">' . lang('client') . '</small>';
                                    }

                                    ?>
                                <div class="d-flex py-3 border-top item" id="<?php echo $comment_type . "-comment-form-container-" . $v_comment->task_comment_id ?>">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-xs">
                                            <img src="<?php echo base_url() . $profile_info->avatar ?>" alt="user image" class="img-fluid d-block rounded-circle">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="font-size-14 mb-1">
                                            <a href="<?= base_url() ?>admin/user/user_details/<?= $v_comment->user_id ?>"> <?= ($profile_info->fullname) ?></a> 
                                            <small class="text-muted float-end">
                                                <?= time_ago($v_comment->comment_datetime) ?>
                                                <?php if ($v_comment->user_id == $this->session->userdata('user_id')) { ?>
                                                    <?php echo ajax_anchor(base_url("admin/goal_tracking/delete_comments/"  . $v_comment->goal_tracking_id . '/' . $v_comment->task_comment_id), "<i class='text-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#" . $comment_type . "-comment-form-container-" . $v_comment->task_comment_id)); ?>
                                                <?php } ?>
                                            </small>
                                        </h5>
                                        <p class="text-muted"><?php if (!empty($v_comment->comment)) echo nl2br($v_comment->comment); ?></p>
                                    </div>                              
                                </div><!-- /.item -->
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- Task Comments Panel Ends--->
                    <!-- Start Tasks Management-->
                    <div class="tab-pane <?= $active == 4 ? 'active' : '' ?>" id="task" style="position: relative;">
                        <div class="card-body">
                            <ul class="nav nav-tabs bg-light rounded" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link <?= $task_active == 1 ? 'active' : ''; ?>" href="#manage_task" data-bs-toggle="tab">
                                        <?= lang('task') ?>
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= base_url() ?>admin/tasks/new_tasks/goal/<?= $goal_info->goal_tracking_id ?>">
                                        <?= lang('new_task') ?>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-3 text-muted">
                                <!-- ************** general *************-->
                                <div class="tab-pane <?= $task_active == 1 ? 'active' : ''; ?>" id="manage_task">
                                    <div class="table-responsive">
                                        <table id="goal_tasks_datatable" class="table table-striped dt-responsive nowrap w-100">
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
                                            if (!empty($all_task_info)):foreach ($all_task_info as $key => $v_task):
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="complete form-check font-size-16">
                                                            <input type="checkbox" data-id="<?= $v_task->task_id ?>" style="position: absolute;" <?php if ($v_task->task_progress >= 100) { echo 'checked'; } ?> class="form-check-input">
                                                            <label class="form-check-label"></label>
                                                        </div>
                                                    </td>
                                                    <td><a class="text-info" style="<?php
                                                        if ($v_task->task_progress >= 100) {  echo 'text-decoration: line-through;';  } ?>"  href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
                                                    </td>

                                                    <td><?php
                                                        $due_date = $v_task->due_date;
                                                        $due_time = strtotime($due_date);
                                                        $current_time = time();
                                                        ?>
                                                        <?= display_datetime($due_date) ?>
                                                        <?php if ($current_time > $due_time && $v_task->task_progress < 100) { ?>
                                                            <span class="badge badge-soft-danger"><?= lang('overdue') ?></span>
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
                                                        if ($v_task->task_status == 'completed') {
                                                            $label = 'success';
                                                        } elseif ($v_task->task_status == 'not_started') {
                                                            $label = 'info';
                                                        } elseif ($v_task->task_status == 'deferred') {
                                                            $label = 'danger';
                                                        } else {
                                                            $label = 'warning';
                                                        }
                                                        ?>
                                                        <span class="badge badge-soft-<?= $label ?>"><?= lang($v_task->task_status) ?> </span>
                                                    </td>
                                                    <td>
                                                        <?php echo btn_view('admin/tasks/view_task_details/' . $v_task->task_id) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- End Tasks Management-->
                            </div>
                        </div>
                    </div>
                    <!-- Task Comments Panel Starts --->
                    <div class="tab-pane <?= $active == 3 ? 'active' : '' ?>" id="activities" style="position: relative;">
                        <div class="card-body">
                            <?php $role = $this->session->userdata('user_type'); if ($role == 1) {  ?>
                            <span class="btn-sm pull-right float-end">
                            <a href="<?= base_url() ?>admin/tasks/claer_activities/goal_tracking/<?= $goal_info->goal_tracking_id ?>"><?= lang('clear') . ' ' . lang('activities') ?></a>
                            </span>
                            <?php } ?>
                            <h4 class="card-title"><?= lang('activities') ?></h4>
                                <div class="card-body">
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
                                        <?php } } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>