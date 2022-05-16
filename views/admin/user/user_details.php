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
$activities_info = $this->db->where(array('user' => $profile_info->user_id))->order_by('activity_date', 'DESC')->get('tbl_activities')->result();

$edited = can_action('24', 'edited');
$user_info = $this->db->where('user_id', $profile_info->user_id)->get('tbl_users')->row();
$designation = $this->db->where('designations_id', $profile_info->designations_id)->get('tbl_designations')->row();
if (!empty($designation)) {
    $department = $this->db->where('departments_id', $designation->departments_id)->get('tbl_departments')->row();
}
$all_project_info = $this->user_model->my_permission('tbl_project', $profile_info->user_id);
$p_started = 0;
$p_in_progress = 0;
$p_completed = 0;
$project_time = 0;
if (!empty($all_project_info)) {
    foreach ($all_project_info as $v_user_project) {
        if ($v_user_project->project_status == 'started') {
            $p_started += count($v_user_project->project_status);
        }
        if ($v_user_project->project_status == 'in_progress') {
            $p_in_progress += count($v_user_project->project_status);
        }
        if ($v_user_project->project_status == 'completed') {
            $p_completed += count($v_user_project->project_status);
        }
        $project_time += $this->user_model->task_spent_time_by_id($v_user_project->project_id, true);
    }
}

$tasks_info = $this->user_model->my_permission('tbl_task', $profile_info->user_id);

$t_not_started = 0;
$t_in_progress = 0;
$t_completed = 0;
$t_deferred = 0;
$t_waiting_for_someone = 0;
$task_time = 0;
if (!empty($tasks_info)):foreach ($tasks_info as $v_tasks):
    if ($v_tasks->task_status == 'not_started') {
        $t_not_started += count($v_tasks->task_status);
    }
    if ($v_tasks->task_status == 'in_progress') {
        $t_in_progress += count($v_tasks->task_status);
    }
    if ($v_tasks->task_status == 'completed') {
        $t_completed += count($v_tasks->task_status);
    }
    $task_time += $this->user_model->task_spent_time_by_id($v_tasks->task_id);
endforeach;
endif;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card bg-gray-dark text-white">
            <div class="card-body">
                <div class="cover-photo bg-cover">
                    <div class="p-xl text-white row">
                        <div class="col-lg-4 col-md-4">
                            <div class="pull-left">
                                <div class="row-table row-flush">
                                    <div class="pull-left text-white ">
                                        <div class="mt-lg">
                                            <h4 class="mt-sm mb0 text-white"><?php
                                                echo $p_in_progress + $p_started;
                                                ?>
                                            </h4>
                                            <p class="mb0 text-muted"><?= lang('open') . ' ' . lang('project') ?></p>
                                            <small><a href="<?= base_url() ?>admin/projects"  class="mt0 mb0"><?= lang('more_info') ?> <i  class="fa fa-arrow-circle-right"></i></a></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-lg row-table row-flush">
                                    <div class="pull-left">
                                        <div class="mt-lg">
                                            <h4 class="mt-sm mb0 text-white"><?php
                                                echo $p_completed;
                                                ?>
                                            </h4>
                                            <p class="mb0 text-muted"><?= lang('complete') . ' ' . lang('project') ?></p>
                                            <small><a href="<?= base_url() ?>admin/projects/index/completed" class="mt0 mb0"><?= lang('more_info') ?> <i  class="fa fa-arrow-circle-right"></i></a></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right">
                                <div class="row-table row-flush">
                                    <div class="pull-left text-white ">
                                        <div class="mt-lg">
                                            <h4 class="mt-sm mb0 text-white"><?php
                                                echo $t_in_progress + $t_not_started;
                                                ?>
                                            </h4>
                                            <p class="mb0 text-muted"><?= lang('open') . ' ' . lang('tasks') ?></p>
                                            <small><a href="<?= base_url() ?>admin/tasks/all_task"  class="mt0 mb0"><?= lang('more_info') ?> <i  class="fa fa-arrow-circle-right"></i></a></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-lg row-table row-flush">
                                    <div class="pull-left">
                                        <div class="mt-lg">
                                            <h4 class="mt-sm mb0 text-white"><?php
                                                echo $t_in_progress;
                                                ?>
                                            </h4>
                                            <p class="mb0 text-muted"><?= lang('complete') . ' ' . lang('tasks') ?></p>
                                            <small><a href="<?= base_url() ?>admin/tasks/all_task/completed" class="mt0 mb0"><?= lang('more_info') ?><i  class="fa fa-arrow-circle-right"></i></a></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="text-center ">
                                <?php 
                                $img = base_url() . $profile_info->avatar;
                                if ($profile_info->avatar): ?>
                                    <img src="<?php echo base_url() . $profile_info->avatar; ?>"
                                         class="rounded-circle avatar-xl">
                                <?php else: ?>
                                    <img src="<?php echo base_url() ?>skote_assets/images/users/01.png" alt="Employee_Image"
                                         class="rounded-circle avatar-xl">
                                <?php endif; ?>
                            </div>

                            <h3 class="m0 text-center text-white"><?= $profile_info->fullname ?>
                            </h3>
                            <p class="text-center text-white"><?= lang('emp_id') ?>: <?php echo $profile_info->employment_id ?></p>
                            <p class="text-center text-white"><?php
                                if (!empty($department)) {
                                    $dname = $department->deptname;
                                } else {
                                    $dname = lang('undefined_department');
                                }
                                if (!empty($designation->designations)) {
                                    $des = ' &rArr; ' . $designation->designations;
                                } else {
                                    $des = '& ' . lang('designation');;
                                }
                                echo $dname . ' ' . $des;
                                if (!empty($department->department_head_id) && $department->department_head_id == $profile_info->user_id) { ?>
                                    <strong
                                        class="label label-warning"><?= lang('department_head') ?></strong>
                                <?php }
                                ?>

                            </p>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="pull-left">
                                <div class="row-table row-flush">
                                    <div class="pull-left text-white ">
                                        <div class="mt-lg">
                                            <h4 class="mt-sm mb0 text-white"><?php
                                                if (!empty($total_attendance)) {
                                                    echo $total_attendance;
                                                } else {
                                                    echo '0';
                                                }
                                                ?> / <?php echo $total_days; ?>
                                            </h4>
                                            <p class="mb0 text-muted"><?= lang('monthly') . ' ' . lang('attendance') ?></p>
                                            <small><a href="<?= base_url() ?>admin/attendance/attendance_report"
                                                      class="mt0 mb0"><?= lang('more_info') ?> <i
                                                        class="fa fa-arrow-circle-right"></i></a></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-lg row-table row-flush">
                                    <div class="pull-left">
                                        <div class="mt-lg">
                                            <h4 class="mt-sm mb0 text-white"><?php
                                                if (!empty($total_leave)) {
                                                    echo $total_leave;
                                                } else {
                                                    echo '0';
                                                }
                                                ?>
                                            </h4>
                                            <p class="mb0 text-muted"><?= lang('monthly') . ' ' . lang('leave') ?></p>
                                            <small><a href="<?= base_url() ?>admin/leave_management"
                                                      class="mt0 mb0"><?= lang('more_info') ?> <i
                                                        class="fa fa-arrow-circle-right"></i></a></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right">
                                <div class="row-table row-flush">
                                    <div class="pull-left text-white ">
                                        <div class="mt-lg">
                                            <h4 class="mt-sm mb0 text-white"><?php
                                                if (!empty($total_absent)) {
                                                    echo $total_absent;
                                                } else {
                                                    echo '0';
                                                }
                                                ?>
                                            </h4>
                                            <p class="mb0 text-muted"><?= lang('monthly') . ' ' . lang('absent') ?></p>
                                            <small><a href="<?= base_url() ?>admin/attendance/attendance_report"
                                                      class="mt0 mb0"><?= lang('more_info') ?> <i
                                                        class="fa fa-arrow-circle-right"></i></a></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-lg row-table row-flush">
                                    <div class="pull-left">
                                        <div class="mt-lg">
                                            <h4 class="mt-sm mb0 text-white"><?php
                                                if (!empty($total_award)) {
                                                    echo $total_award;
                                                } else {
                                                    echo '0';
                                                }
                                                ?>
                                            </h4>
                                            <p class="mb0 text-muted"><?= lang('total') . ' ' . lang('award') ?></p>
                                            <small><a href="<?= base_url() ?>admin/award"
                                                      class="mt0 mb0"><?= lang('more_info') ?> <i
                                                        class="fa fa-arrow-circle-right"></i></a></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top text-muted text-center">
                <div class="row">
                    <style type="text/css">
                        .user-timer ul.timer {
                            margin: 0px;
                        }

                        .user-timer ul.timer > li.dots {
                            padding: 6px 2px;
                            font-size: 14px;
                        }

                        .user-timer ul.timer li {
                            color: #fff;
                            font-size: 24px;
                            font-weight: bold;
                        }

                        .user-timer ul.timer li span {
                            display: none;
                        }
                    </style>
                    <div class="col-lg-3 col-md-3 col-xs-6 mb-10 br user-timer ">
                        <h3 class="m0"><?= $this->user_model->get_time_spent_result($project_time) ?></h3>
                        <p class="m0">
                            <span class="hidden-xs"><?= lang('project') . ' ' . lang('hours') ?></span>
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-6 mb-10 br user-timer">
                        <h3 class="m0"><?= $this->user_model->get_time_spent_result($task_time) ?></h3>
                        <span class="hidden-xs"><?= lang('tasks') . ' ' . lang('hours') ?></span>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-6 mb-10 br user-timer">
                        <h3 class="m0 text-white"><?php
                            $m_min = 0;
                            $m_hour = 0;

                            if (!empty($this_month_working_hour)) {
                                foreach ($this_month_working_hour as $v_month_hour) {
                                    $m_min += $v_month_hour['minute'];
                                    $m_hour += $v_month_hour['hour'];
                                }
                            }
                            if ($m_min > 60) {
                                $m_hour += intval($m_min / 60);
                                $m_min = intval($m_min % 60);
                            }
                            echo round($m_hour) . " : " . round($m_min) . " m";;
                            ?></h3>
                        <span class="hidden-xs"><?= lang('this_month_working') . ' ' . lang('hours') ?></span>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-6 mb-10 user-timer">
                        <h3 class="m0 text-white"><?php
                            $min = 0;
                            $hour = 0;
                            if (!empty($all_working_hour)) {
                                foreach ($all_working_hour as $v_all_hours) {
                                    $min += $v_all_hours['minute'];
                                    $hour += $v_all_hours['hour'];

                                }
                            }
                            if ($min > 60) {
                                $hour += intval($min / 60);
                                $min = intval($min % 60);
                            }
                            echo round($hour) . " : " . round($min) . " m";;
                            ?></h3>
                        <span class="hidden-xs"><?= lang('working') . ' ' . lang('hours') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    
                    <a class="nav-link mb-2 <?= $active == 1 ? 'active' : '' ?>" href="#basic_info" data-bs-toggle="tab" aria-controls="basic_info" aria-selected="false"><?= lang('basic_info') ?></a>

                    <a class="nav-link mb-2 <?= $active == 3 ? 'active' : '' ?>" href="#bank_details" data-bs-toggle="tab" aria-controls="bank_details" aria-selected="false"><?= lang('bank_details') ?></a>

                    <a class="nav-link mb-2 <?= $active == 4 ? 'active' : '' ?>" href="#document_details" data-bs-toggle="tab" aria-controls="document_details" aria-selected="false"><?= lang('document_details') ?></a>

                    <a class="nav-link mb-2 <?= $active == 5 ? 'active' : '' ?>" href="#salary_details"  data-bs-toggle="tab" aria-controls="salary_details" aria-selected="false"><?= lang('salary_details') ?></a>

                    <a class="nav-link mb-2 <?= $active == 6 ? 'active' : '' ?>" href="#timecard_details" data-bs-toggle="tab" aria-controls="timecard_details" aria-selected="false"><?= lang('timecard_details') ?></a>
                    
                    <a class="nav-link mb-2 <?= $active == 7 ? 'active' : '' ?>" href="#leave_details" data-bs-toggle="tab" aria-controls="leave_details" aria-selected="false"><?= lang('leave_details') ?></a>

                    <a class="nav-link mb-2 <?= $active == 8 ? 'active' : '' ?>" href="#provident_found" data-bs-toggle="tab" aria-controls="provident_found" aria-selected="false"><?= lang('provident_found') ?></a>

                    <a class="nav-link mb-2 <?= $active == 9 ? 'active' : '' ?>" href="#Overtime_details" data-bs-toggle="tab" aria-controls="Overtime_details" aria-selected="false"><?= lang('Overtime_details') ?></a>

                    <a class="nav-link mb-2 <?= $active == 10 ? 'active' : '' ?>" href="#tasks_details" data-bs-toggle="tab" aria-controls="tasks_details" aria-selected="false"><?= lang('tasks') ?></a>

                    <a class="nav-link mb-2 <?= $active == 11 ? 'active' : '' ?>" href="#projects_details" data-bs-toggle="tab" aria-controls="projects_details" aria-selected="false"><?= lang('projects') ?></a>

                    <a class="nav-link mb-2 <?= $active == 12 ? 'active' : '' ?>" href="#bugs_details" data-bs-toggle="tab" aria-controls="bugs_details" aria-selected="false"><?= lang('bugs') ?></a>

                    <a class="nav-link mb-2 <?= $active == 13 ? 'active' : '' ?>" href="#activities" data-bs-toggle="tab" aria-controls="activities" aria-selected="false"><?= lang('activities') ?><strong class="badge badge-soft-danger pull-right"><?= (!empty($activities_info) ? count($activities_info) : null) ?></strong></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
                    <div class="tab-pane fade <?= $active == 1 ? 'show active' : '' ?>" id="basic_info" style="position: relative;">
                        <div class="card-body">
                            <!-- Default panel contents -->
                            <?php if (!empty($edited)) { ?>
                            <div class="pull-right float-end">
                                <span data-bs-placement="top" data-bs-toggle="tooltip"  title="<?= lang('update') ?>"> <a data-bs-toggle="modal" data-bs-target="#myModal_lg"  href="<?= base_url() ?>admin/user/update_contact/1/<?= $profile_info->account_details_id ?>"  class="text-primary text-sm ml"><?= lang('update') ?></a> </span>
                            </div>
                            <?php } ?>
                            <h4 class="card-title mb-4"><?= $profile_info->fullname ?></h4>
                                  
                            <div class="form-horizontal row">
                                <div class="col-lg-5">
                                    <div class="row mb-3">
                                        <label class="form-label col-sm-6"><?= lang('emp_id') ?>:</label>
                                        <div class="col-sm-6 ">
                                            <p class="form-control-static"><?= $profile_info->employment_id ?></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="form-label col-sm-6"><?= lang('fullname') ?>:</label>
                                        <div class="col-sm-6 ">
                                            <p class="form-control-static"><?= $profile_info->fullname ?></p>
                                        </div>
                                    </div>
                                    <?php if ($this->session->userdata('user_type') == 1) { ?>
                                        <div class="row mb-3">
                                            <label class="form-label col-sm-6"><?= lang('username') ?>:</label>
                                            <div class="col-sm-6 ">
                                                <p class="form-control-static"><?= $user_info->username ?></p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="form-label col-sm-6"><?= lang('password') ?>:</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static"><a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/user/reset_password/<?= $user_info->user_id ?>"><?= lang('reset_password') ?></a>
                                                </p>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="row mb-3">
                                        <label class="col-sm-6 form-label"><?= lang('joining_date') ?>: </label>
                                        <div class="col-sm-6">
                                            <?php if (!empty($profile_info->joining_date)) { ?>
                                                <p class="form-control-static"><?php echo display_datetime($profile_info->joining_date); ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-6 form-label"><?= lang('gender') ?>:</label>
                                        <div class="col-sm-6">
                                            <?php if (!empty($profile_info->gender)) { ?>
                                                <p class="form-control-static"><?php echo lang($profile_info->gender); ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">

                                        <label class="col-sm-6 form-label"><?= lang('date_of_birth') ?>: </label>
                                        <div class="col-sm-6">
                                            <?php if (!empty($profile_info->date_of_birth)) { ?>
                                                <p class="form-control-static"><?php echo display_datetime($profile_info->date_of_birth); ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-6 form-label"><?= lang('maratial_status') ?>:</label>
                                        <div class="col-sm-6">
                                            <?php if (!empty($profile_info->maratial_status)) { ?>
                                                <p class="form-control-static"><?php echo lang($profile_info->maratial_status); ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="row mb-3">
                                        <label class="col-sm-5 form-label"><?= lang('fathers_name') ?>: </label>
                                        <div class="col-sm-7">
                                            <?php if (!empty($profile_info->father_name)) { ?>
                                                <p class="form-control-static"><?php echo "$profile_info->father_name"; ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-5 form-label"><?= lang('mother_name') ?>: </label>
                                        <div class="col-sm-7">
                                            <?php if (!empty($profile_info->mother_name)) { ?>
                                                <p class="form-control-static"><?php echo "$profile_info->mother_name"; ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-5 form-label"><?= lang('email') ?> : </label>
                                        <div class="col-sm-7">
                                            <p class="form-control-static"><?php echo "$user_info->email"; ?></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-5 form-label"><?= lang('phone') ?> : </label>
                                        <div class="col-sm-7">
                                            <p class="form-control-static"><?php echo "$profile_info->phone"; ?></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-5 form-label"><?= lang('mobile') ?> : </label>
                                        <div class="col-sm-7">
                                            <p class="form-control-static"><?php echo "$profile_info->mobile"; ?></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-5 form-label"><?= lang('skype_id') ?> : </label>
                                        <div class="col-sm-7">
                                            <p class="form-control-static"><?php echo "$profile_info->skype"; ?></p>
                                        </div>
                                    </div>
                                    <?php if (!empty($profile_info->passport)) { ?>
                                        <div class="row mb-3">
                                            <label class="col-sm-5 form-label"><?= lang('passport') ?>
                                                : </label>
                                            <div class="col-sm-7">
                                                <p class="form-control-static"><?php echo "$profile_info->passport"; ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="row mb-3">
                                        <label class="col-sm-5 form-label"><?= lang('present_address') ?>
                                            : </label>
                                        <div class="col-sm-7">
                                            <p class="form-control-static"><?php echo "$profile_info->present_address"; ?></p>
                                        </div>
                                    </div>
                                    <?php $show_custom_fields = custom_form_label(13, $profile_info->user_id);

                                    if (!empty($show_custom_fields)) {
                                        foreach ($show_custom_fields as $c_label => $v_fields) {
                                            if (!empty($v_fields)) {
                                                ?>
                                                <div class="row mb-3">
                                                    <label class="col-sm-5 form-label"><?= $c_label ?> : </label>
                                                    <div class="col-sm-7">
                                                        <p class="form-control-static"><?= $v_fields ?></p>
                                                    </div>
                                                </div>
                                            <?php }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 3 ? 'active' : '' ?>" id="bank_details" style="position: relative;">
                        <div class="card-body">
                            <?php if (!empty($edited)) { ?>
                            <div class="pull-right hidden-print float-end">
                                 <span data-bs-placement="top" data-bs-toggle="tooltip"
                                       title="<?= lang('new_bank') ?>">
                                    <a data-bs-toggle="modal" data-bs-target="#myModal"
                                       href="<?= base_url() ?>admin/user/new_bank/<?= $profile_info->user_id ?>"
                                       class="text-primary text-sm ml"><?= lang('update') ?></a>
                                        </span>
                            </div>
                            <?php } ?>
                            <h4 class="card-title mb-4"><?= lang('bank_information') ?></h4>
                            <?php
                            $all_bank_info = $this->db->where('user_id', $profile_info->user_id)->get('tbl_employee_bank')->result();
                            ?>
                            <div class="table-responsive">
                                <table class="table table-striped dt-responsive nowrap w-100" id="user_bank_datatable">
                                    <thead>
                                        <tr>
                                            <th><?= lang('bank') ?></th>
                                            <th><?= lang('name_of_account') ?></th>
                                            <th><?= lang('routing_number') ?></th>
                                            <th><?= lang('account_number') ?></th>
                                            <th class="hidden-print"><?= lang('action') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($all_bank_info)) {
                                        foreach ($all_bank_info as $v_bank_info) { ?>
                                            <tr>
                                                <td><?= $v_bank_info->bank_name ?></td>
                                                <td><?= $v_bank_info->routing_number ?></td>
                                                <td><?= $v_bank_info->account_name ?></td>
                                                <td><?= $v_bank_info->account_number ?></td>
                                                <td class="hidden-print">
                                                    <?= btn_edit_modal('admin/user/new_bank/' . $v_bank_info->user_id . '/' . $v_bank_info->employee_bank_id) ?>
                                                    <?= btn_delete('admin/user/delete_user_bank/' . $v_bank_info->user_id . '/' . $v_bank_info->employee_bank_id) ?>
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 4 ? 'active' : '' ?>" id="document_details" style="position: relative;">
                        <div class="card-body">
                            <?php if (!empty($edited)) { ?>
                            <div class="pull-right hidden-print">
                                    <span data-bs-placement="top" data-bs-toggle="tooltip"  title="<?= lang('update') ?>"> <a data-bs-toggle="modal" data-bs-target="#myModal"  href="<?= base_url() ?>admin/user/user_documents/<?= $profile_info->user_id ?>" class="text-primary text-sm ml"><?= lang('update') ?></a> </span>
                            </div>
                            <?php } ?>
                            <h4 class="card-title mb-4"><?= lang('user_documents') ?></h4>

                            <div class="form-horizontal row">
                                <!-- CV Upload -->
                                <?php
                                $document_info = $this->db->where('user_id', $profile_info->user_id)->get('tbl_employee_document')->row();
                                if (!empty($document_info->resume)): ?>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 form-label"><?= lang('resume') ?> : </label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static">
                                                <a href="<?php echo base_url() . $document_info->resume; ?>" target="_blank" style="text-decoration: underline;"><?= lang('view') . ' ' . lang('resume') ?></a>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($document_info->offer_letter)): ?>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 form-label"><?= lang('offer_latter') ?> : </label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static">
                                                <a href="<?php echo base_url() . $document_info->offer_letter; ?>"  target="_blank"  style="text-decoration: underline;"><?= lang('view') . ' ' . lang('offer_latter') ?></a>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($document_info->joining_letter)): ?>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 form-label"><?= lang('joining_latter') ?>
                                            : </label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static">
                                                <a href="<?php echo base_url() . $document_info->joining_letter; ?>"
                                                   target="_blank"
                                                   style="text-decoration: underline;"><?= lang('view') . ' ' . lang('joining_latter') ?></a>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($document_info->contract_paper)): ?>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 form-label"><?= lang('contract_paper') ?>
                                            : </label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static">
                                                <a href="<?php echo base_url() . $document_info->contract_paper; ?>"
                                                   target="_blank"
                                                   style="text-decoration: underline;"><?= lang('view') . ' ' . lang('contract_paper') ?></a>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($document_info->id_proff)): ?>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 form-label"><?= lang('id_prof') ?> : </label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static">
                                                <a href="<?php echo base_url() . $document_info->id_proff; ?>"
                                                   target="_blank"
                                                   style="text-decoration: underline;"><?= lang('view') . ' ' . lang('id_prof') ?></a>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($document_info->other_document)): ?>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 form-label"><?= lang('other_document') ?>
                                            : </label>
                                        <div class="col-sm-8">
                                            <?php
                                            $uploaded_file = json_decode($document_info->other_document);

                                            if (!empty($uploaded_file)):
                                                foreach ($uploaded_file as $sl => $v_files):

                                                    if (!empty($v_files)):
                                                        ?>
                                                        <p class="form-control-static">
                                                            <a href="<?php echo base_url() . 'uploads/' . $v_files->fileName; ?>"
                                                               target="_blank"
                                                               style="text-decoration: underline;"><?= $sl + 1 . '. ' . lang('view') . ' ' . lang('other_document') ?></a>
                                                        </p>
                                                        <?php
                                                    endif;
                                                endforeach;
                                            endif;
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 5 ? 'active' : '' ?>" id="salary_details" style="position: relative;">
                        <?php $this->load->view('admin/user/salary_details') ?>
                    </div>
                    <div class="tab-pane <?= $active == 6 ? 'active' : '' ?>" id="timecard_details" style="position: relative;">
                        <?php $this->load->view('admin/user/timecard_details') ?>
                    </div>
                    <div class="tab-pane <?= $active == 7 ? 'active' : '' ?>" id="leave_details" style="position: relative;">
                        <?php $this->load->view('admin/user/leave_details') ?>
                    </div>
                    <div class="tab-pane <?= $active == 8 ? 'active' : '' ?>" id="provident_found"
                         style="position: relative;">
                        <?php $this->load->view('admin/user/provident_found') ?>
                    </div>
                    <div class="tab-pane <?= $active == 9 ? 'active' : '' ?>" id="Overtime_details"
                         style="position: relative;">
                        <?php $this->load->view('admin/user/Overtime_details') ?>
                    </div>
                    <div class="tab-pane <?= $active == 10 ? 'active' : '' ?>" id="tasks_details"
                         style="position: relative;">
                        <?php $this->load->view('admin/user/tasks_details') ?>
                    </div>
                    <div class="tab-pane <?= $active == 11 ? 'active' : '' ?>" id="projects_details"
                         style="position: relative;">
                        <?php $this->load->view('admin/user/projects_details') ?>
                    </div>

                    <div class="tab-pane <?= $active == 12 ? 'active' : '' ?>" id="bugs_details"
                         style="position: relative;">
                        <?php $this->load->view('admin/user/bugs_details') ?>
                    </div>
                    <div class="tab-pane <?= $active == 'notifications' ? 'active' : '' ?>" id="notifications">
                        <div class="card-body">
                            <h4 class="card-title mb-4"><?= lang('all') . ' ' . lang('notification'); ?></h4>
                            <table class="table" id="Transation_DataTables">
                                <thead>
                                <tr>
                                    <th><a href="#"
                                           onclick="mark_all_as_read(); return false;"><?php echo lang('mark_all_as_read'); ?></a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $user_notifications = $this->global_model->get_user_notifications(false, true);
                                if (!empty($user_notifications)) {
                                    foreach ($user_notifications as $notification) {
                                        if (!empty($notification->link)) {
                                            $link = base_url() . $notification->link;
                                        } else {
                                            $link = '#';
                                        }
                                        ?>
                                        <tr>
                                            <td class="<?php if ($notification->read_inline == 0) {
                                                echo 'unread';
                                            } ?>"><?php
                                                $description = lang($notification->description, $notification->value);
                                                if ($notification->from_user_id != 0) {
                                                    $description = fullname($notification->from_user_id) . ' - ' . $description;
                                                }
                                                echo '<span class="n-title text-sm block">' . $description . '</span>'; ?>
                                                <small class="text-muted pull-left" style="margin-top: -4px"><i
                                                        class="fa fa-clock-o"></i> <?php echo time_ago($notification->date); ?>
                                                </small>
                                                <?php if ($notification->read_inline == 0) { ?>
                                                    <span class="text-muted pull-right mark-as-read-inline"
                                                          onclick="read_inline(<?php echo $notification->notifications_id; ?>);"
                                                          data-bs-placement="top"
                                                          data-bs-toggle="tooltip"
                                                          data-title="<?php echo lang('mark_as_read'); ?>">
                                                    <small><i class="fa fa-circle-thin"></i></small>
                                                </span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 13 ? 'active' : '' ?>" id="activities" style="position: relative;">
                        <div class="card-body">
                            <h4 class="card-title mb-4"><?= lang('all_activities'); ?></h4>
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
                                                        <p><?= lang($v_activities->module) ?></p>
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
                                    <?php  }  } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#Transation_DataTables').dataTable({
            paging: false,
            "bSort": false
        });
    });
</script>
<?php
$color = array('37bc9b', '7266ba', 'f05050', 'ff902b', '7266ba', 'f532e5', '5d9cec', '7cd600', '91ca00', 'ff7400', '1cc200', 'bb9000', '40c400');
?>

<!-- apexcharts -->
<script src="<?= base_url() ?>skote_assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- chartist -->
<link href="<?php echo base_url() ?>skote_assets/plugins/morris/morris.min.css" rel="stylesheet">

<script src="<?= base_url() ?>skote_assets/plugins/raphael/raphael.min.js"></script>
<script src="<?= base_url() ?>skote_assets/plugins/morris/morris.min.js"></script>