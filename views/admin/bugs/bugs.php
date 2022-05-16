<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
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
$mdate = date('Y-m-d H:i');
$last_7_days = date('Y-m-d H:i', strtotime('today - 7 days'));
$all_goal_tracking = $this->bugs_model->get_permission('tbl_goal_tracking');

$all_goal = 0;
$bank_goal = 0;
$complete_achivement = 0;
if (!empty($all_goal_tracking)) {
    foreach ($all_goal_tracking as $v_goal_track) {
        $goal_achieve = $this->bugs_model->get_progress($v_goal_track, true);

        if ($v_goal_track->goal_type_id == 9) {

            if ($v_goal_track->end_date <= $mdate) { // check today is last date or not

                if ($v_goal_track->email_send == 'no') {// check mail are send or not
                    if ($v_goal_track->achievement <= $goal_achieve['achievement']) {
                        if ($v_goal_track->notify_goal_achive == 'on') {// check is notify is checked or not check
                            $this->bugs_model->send_goal_mail('goal_achieve', $v_goal_track);
                        }
                    } else {
                        if ($v_goal_track->notify_goal_not_achive == 'on') {// check is notify is checked or not check
                            $this->bugs_model->send_goal_mail('goal_not_achieve', $v_goal_track);
                        }
                    }
                }
            }
            $all_goal += $v_goal_track->achievement;
            $complete_achivement += $goal_achieve['achievement'];
        }
    }
}
// 30 days before

for ($iDay = 7; $iDay >= 0; $iDay--) {
    $date = date('Y-m-d', strtotime('today - ' . $iDay . 'days'));
    $where = array('update_time >=' => $date . " 00:00:00", 'update_time <=' => $date . " 23:59:59", 'bug_status' => 'resolved');

    $invoice_result[$date] = count(get_result('tbl_bug', $where));
}

$terget_achievement = get_result('tbl_goal_tracking', array('goal_type_id' => 9, 'start_date >=' => $last_7_days, 'end_date <=' => $mdate));
$total_terget = 0;
if (!empty($terget_achievement)) {
    foreach ($terget_achievement as $v_terget) {
        $total_terget += $v_terget->achievement;
    }
}
$tolal_goal = $all_goal + $bank_goal;
$curency = $this->bugs_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');

if ($this->session->userdata('user_type') == 1) {
    $margin = 'margin-bottom:30px';
    ?>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('achievement') ?></p>
                            <h5 class="mb-0"><?= $tolal_goal ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('last_weeks') . ' ' . lang('created') ?></p>
                            <h5 class="mb-0"><?= $total_terget ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('completed') . ' ' . lang('achievements') ?></p>
                            <h5 class="mb-0"><?= $complete_achivement ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('pending') . ' ' . lang('achievements') ?></p>
                            <h5 class="mb-0"><?php
                        if ($tolal_goal < $complete_achivement) {
                            $pending_goal = 0;
                        } else {
                            $pending_goal = $tolal_goal - $complete_achivement;
                        } ?>
                        <?= $pending_goal ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                        <div class="col-xs-6 pt">
                            <div id="sparkline2">
                            </div>
                            <p class="m0">
                                <small>
                                    <?php
                                    if (!empty($invoice_result)) {
                                        foreach ($invoice_result as $date => $v_invoice_result) {
                                            echo date('d', strtotime($date)) . ' ';
                                        }
                                    }
                                    ?>
                                </small>
                            </p>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                     <?php
                        if (!empty($tolal_goal)) {
                            if ($tolal_goal <= $complete_achivement) {
                                $total_progress = 100;
                            } else {
                                $progress = ($complete_achivement / $tolal_goal) * 100;
                                $total_progress = round($progress);
                            }
                        } else {
                            $total_progress = 0;
                        }
                        ?>
                    <div class="inline ">
                            <div class="easypiechart text-success"
                                 data-percent="<?= $total_progress ?>"
                                 data-line-width="5" data-track-Color="#f0f0f0"
                                 data-bar-color="#<?php
                                 if ($total_progress == 100) {
                                     echo '8ec165';
                                 } elseif ($total_progress >= 40 && $total_progress <= 50) {
                                     echo '5d9cec';
                                 } elseif ($total_progress >= 51 && $total_progress <= 99) {
                                     echo '7266ba';
                                 } else {
                                     echo 'fb6b5b';
                                 }
                                 ?>" data-rotate="270" data-scale-Color="false"
                                 data-size="50"
                                 data-animate="2000">
                                                            <span class="small "><?= $total_progress ?>
                                                                %</span>
                                <span class="easypie-text"><strong><?= lang('done') ?></strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }
$created = can_action('58', 'created');
$edited = can_action('58', 'edited');
$deleted = can_action('58', 'deleted');
if (!empty($bug_info)) {
    $bug_id = $bug_info->bug_id;
    $companies_id = $bug_info->companies_id;
} else {
    $bug_id = null;
    $companies_id = null;
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body bordered">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 1 ? 'active' : '' ?>" href="<?= base_url().'admin/bugs' ?>"><?= lang('all_bugs') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 2 ? 'active' : '' ?>" href="#assign_task" data-bs-toggle="tab">
                        <?php if($bug_id){ echo lang('edit').' '.lang('bug'); }else{ echo lang('new_bugs'); } ?></a>
                    </li>
                    <?php } ?>
                </ul>

                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="task_list" style="position: relative;">                  
                        <h4 class="card-title mb-4"><?= lang('all_bugs') ?></h4>
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="all_bugs_datatable">
                                <thead>
                                    <tr>
                                        <?php super_admin_opt_th() ?>
                                        <th><?= lang('bug_title') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <th><?= lang('priority') ?></th>
                                        <?php if ($this->session->userdata('user_type') == '1') { ?>
                                            <th><?= lang('reporter') ?></th>
                                        <?php } ?>
                                        <th><?= lang('assigned_to') ?></th>
                                        <?php $show_custom_fields = custom_form_table(6, null);
                                        if (!empty($show_custom_fields)) {
                                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                                if (!empty($c_label)) {
                                                    ?>
                                                    <th><?= $c_label ?> </th>
                                                <?php }
                                            }
                                        }
                                        ?>
                                        <?php if (!empty($edited) || !empty($deleted)) { ?>
                                            <th><?= lang('action') ?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($all_bugs_info)):foreach ($all_bugs_info as $key => $v_bugs):
                                        $can_edit = $this->bugs_model->can_action('tbl_bug', 'edit', array('bug_id' => $v_bugs->bug_id));
                                        $can_delete = $this->bugs_model->can_action('tbl_bug', 'delete', array('bug_id' => $v_bugs->bug_id));
                                        $reporter = $this->db->where('user_id', $v_bugs->reporter)->get('tbl_users')->row();
                                        $user_info = $this->db->where('user_id', $v_bugs->reporter)->get('tbl_account_details')->row();

                                        if ($reporter->role_id == '1') {
                                            $badge = 'danger';
                                        } elseif ($reporter->role_id == '2') {
                                            $badge = 'info';
                                        } else {
                                            $badge = 'primary';
                                        }
                                        ?>
                                        <tr id="table-bugs-<?= $v_bugs->bug_id ?>">
                                            <?php super_admin_opt_td($v_bugs->companies_id) ?>
                                            <td><a class="text-info" style="<?php
                                                if ($v_bugs->bug_status == 'resolve') {
                                                    echo 'text-decoration: line-through;';
                                                }
                                                ?>"
                                                   href="<?= base_url() ?>admin/bugs/view_bug_details/<?= $v_bugs->bug_id ?>"><?php echo $v_bugs->bug_title; ?></a>
                                            </td>
                                            <td><?php
                                                if ($v_bugs->bug_status == 'unconfirmed') {
                                                    $label = 'warning';
                                                } elseif ($v_bugs->bug_status == 'confirmed') {
                                                    $label = 'info';
                                                } elseif ($v_bugs->bug_status == 'in_progress') {
                                                    $label = 'primary';
                                                } else {
                                                    $label = 'success';
                                                }
                                                ?>
                                                <span
                                                    class="label label-<?= $label ?>"><?= lang("$v_bugs->bug_status") ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                if ($v_bugs->priority == 'High') {
                                                    $plabel = 'danger';
                                                } elseif ($v_bugs->priority == 'Medium') {
                                                    $plabel = 'info';
                                                } else {
                                                    $plabel = 'primary';
                                                }
                                                ?>
                                                <span
                                                    class="badge btn-<?= $plabel ?>"><?= ucfirst($v_bugs->priority) ?></span>
                                            </td>
                                            <?php if ($this->session->userdata('user_type') == '1') { ?>
                                                <td>
                                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $reporter->user_id ?>"> <span
                                                            class="badge btn-<?= $badge ?> "><?= $user_info->fullname ?></span></a>
                                                </td>
                                            <?php } ?>
                                            <td>
                                                <div class="avatar-group">
                                                    <?php
                                                    if ($v_bugs->permission != 'all') {
                                                    $get_permission = json_decode($v_bugs->permission);

                                                    if (!empty($get_permission)) :
                                                         $i=$total_users=0;
                                                        foreach ($get_permission as $permission => $v_permission) :
                                                            $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                                                            if ($user_info->role_id == 1) {
                                                                $label = 'text-danger';
                                                            } else {
                                                                $label = 'text-success';
                                                            }
                                                        $profile_info = $this->db->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                                        if($total_users<2){
                                                        ?>
                                                        <div class="avatar-group-item">
                                                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                                                   title="<?= $profile_info->fullname ?>" class="d-inline-block"><img
                                                                        src="<?= base_url() . $profile_info->avatar ?>"
                                                                        class="rounded-circle avatar-xs" alt="">
                                                                <span style="margin: 0px 0 8px -10px;" class="mdi mdi-circle <?= $label ?> font-size-10"></span>
                                                                </a>
                                                        </div>
                                                        <?php }  
                                                        $i=$i+1;
                                                        $total_users=$total_users+1; 
                                                        endforeach;
                                                        if($total_users>2){ ?>
                                                        <div class="avatar-group-item">
                                                            <a href="<?= base_url() ?>admin/view_bug_details/<?= $v_bugs->bug_id ?>" class="d-inline-block">
                                                                    <div class="avatar-xs">
                                                                        <span class="avatar-title rounded-circle bg-info text-white font-size-16">
                                                                            <?=$total_users-2?>+
                                                                        </span>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <?php  }
                                                        endif;
                                                        } else { ?>
                                                        <span class="mr-lg-2 mt-2 mr">
                                                            <strong><?= lang('everyone') ?></strong>
                                                            <i  title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                                        </span>
                                                        <?php } ?>
                                                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                        <span data-bs-placement="top" data-bs-toggle="tooltip"  title="<?= lang('add_more') ?>" class="mt-2">
                                                            <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/bugs/update_users/<?= $v_bugs->bug_id ?>" class="text-default"><i class="fa fa-plus"></i></a>
                                                        </span>
                                                        <?php } ?>
                                                </div>
                                            </td>
                                            <?php $show_custom_fields = custom_form_table(6, $v_bugs->bug_id);
                                            if (!empty($show_custom_fields)) {
                                                foreach ($show_custom_fields as $c_label => $v_fields) {
                                                    if (!empty($c_label)) {
                                                        ?>
                                                        <td><?= $v_fields ?> </td>
                                                    <?php }
                                                }
                                            }
                                            ?>
                                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                <td>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                        <?php echo btn_edit('admin/bugs/index/' . $v_bugs->bug_id) ?>
                                                    <?php }
                                                    if (!empty($can_delete) && !empty($deleted)) { ?>
                                                        <?php echo ajax_anchor(base_url("admin/bugs/delete_bug/" . $v_bugs->bug_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child",  "data-fade-out-on-success" => "#table-bugs-" . $v_bugs->bug_id)); ?>
                                                    <?php } ?>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                    <div class="dropdown tbl-action mt">
                                                        <button class="btn btn-outline-success dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_status') ?><i class="mdi mdi-chevron-down"></i></button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/bugs/change_status/<?= $v_bugs->bug_id ?>/unconfirmed"><?= lang('unconfirmed') ?></a>
                                                        
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/bugs/change_status/<?= $v_bugs->bug_id ?>/confirmed"><?= lang('confirmed') ?></a>
                                                        
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/bugs/change_status/<?= $v_bugs->bug_id ?>/in_progress"><?= lang('in_progress') ?></a>
                                                            
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/bugs/change_status/<?= $v_bugs->bug_id ?>/resolved"><?= lang('resolved') ?></a>
                                                            
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/bugs/change_status/<?= $v_bugs->bug_id ?>/verified"><?= lang('verified') ?></a>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($created) || !empty($edited)) { ?>
                    <!-- Add Stock Category tab Starts -->
                    <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="assign_task" style="position: relative;">
                        <div class="card-body">
                            <h4 class="card-title mb-4"><?php if($bug_id){ echo lang('edit').' '.lang('bug'); }else{ echo lang('new_bugs'); } ?></h4>

                            <?php echo form_open(base_url('admin/bugs/save_bug/' . $bug_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                            <div class="row">
                                <div class="col-xl-6">
                                    <?php super_admin_form($companies_id, 3, 7) ?>
                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"><?= lang('bug_title') ?><span class="required">*</span></label>
                                        <div class="col-xl-7">
                                            <input type="text" name="bug_title" required class="form-control"
                                                   value="<?php if (!empty($bug_info->bug_title)) echo $bug_info->bug_title; ?>"/>
                                        </div>
                                    </div>
                                    <?php
                                    if (!empty($bug_info->project_id)) {
                                        $project_id = $bug_info->project_id;
                                    } elseif (!empty($project_id)) {
                                        $project_id = $project_id; ?>
                                        <input type="hidden" name="un_project_id" required class="form-control"
                                               value="<?php echo $project_id ?>"/>
                                    <?php }
                                    if (!empty($bug_info->opportunities_id)) {
                                        $opportunities_id = $bug_info->opportunities_id;
                                    } elseif (!empty($opportunities_id)) {
                                        $opportunities_id = $opportunities_id; ?>
                                        <input type="hidden" name="un_opportunities_id" required
                                               class="form-control"
                                               value="<?php echo $opportunities_id ?>"/>
                                    <?php }
                                    ?>
                                    <div class="row mb-3" id="border-none">
                                        <label class="col-xl-3 col-form-label"><?= lang('related_to') ?> </label>
                                        <div class="col-xl-7">
                                            <select name="related_to" class="form-control" id="check_related" onchange="get_related_moduleName(this.value)">
                                                <option
                                                    value="0"> <?= lang('none') ?> </option>
                                                <option
                                                    value="project" <?= (!empty($project_id) ? 'selected' : '') ?>> <?= lang('project') ?> </option>
                                                <option
                                                    value="opportunities" <?= (!empty($opportunities_id) ? 'selected' : '') ?>> <?= lang('opportunities') ?> </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3" id="related_to">
                                    </div>
                                    <?php
                                    if (!empty($project_id)):?>
                                    <div class="row mb-3 <?= !empty($project_id) ? '' : 'company' ?>">
                                        <label 
                                               class="col-xl-3 col-form-label"><?= lang('project') ?>
                                            <span
                                                class="required">*</span></label>
                                        <div class="col-xl-7">
                                            <select name="project_id" style="width: 100%" class="select_box <?= !empty($project_id) ? '' : 'company' ?>" required="">
                                                <?php
                                                $all_project = $this->bugs_model->get_permission('tbl_project');
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
                                        <div id="milestone"></div>
                                    </div>
                                    <?php endif ?>
                                    <?php if (!empty($opportunities_id)): ?>
                                    <div class="row mb-3 <?= !empty($opportunities_id) ? '' : 'company' ?>">
                                        <label class="col-xl-3 col-form-label"><?= lang('opportunities') ?>
                                            <span class="required">*</span></label>
                                        <div class="col-xl-7">
                                            <select name="opportunities_id" style="width: 100%"  class="select_box <?= !empty($opportunities_id) ? '' : 'company' ?>" required="">
                                                <?php
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
                                    <div class="row mb-3" id="border-none">
                                        <label  class="col-xl-3 col-form-label"><?= lang('reporter') ?>
                                            <span class="required">*</span></label>
                                        <div class="col-xl-7">
                                            <select name="reporter" style="width: 100%" class="select_box" required="">
                                                <?php
                                                $type = $this->uri->segment(4);
                                                if (!empty($type) && !is_numeric($type)) {
                                                    $ex = explode('_', $type);
                                                    if ($ex[0] == 'c') {
                                                        $primary_contact = $ex[1];
                                                    }
                                                }
                                                $reporter_info = get_result('tbl_users');
                                                if (!empty($reporter_info)) {
                                                    foreach ($reporter_info as $key => $v_reporter) {
                                                        $users_info = $this->db->where(array("user_id" => $v_reporter->user_id))->get('tbl_account_details')->row();
                                                        if (!empty($users_info)) {
                                                            if ($v_reporter->role_id == 1) {
                                                                $role = lang('admin');
                                                            } elseif ($v_reporter->role_id == 2) {
                                                                $role = lang('client');
                                                            } else {
                                                                $role = lang('staff');
                                                            }
                                                            ?>
                                                            <option value="<?= $users_info->user_id ?>" <?php
                                                            if (!empty($bug_info->reporter)) {
                                                                echo $v_reporter->user_id == $bug_info->reporter ? 'selected' : '';
                                                            } else if (!empty($primary_contact) && $primary_contact == $users_info->user_id) {
                                                                echo 'selected';
                                                            }
                                                            ?>><?= $users_info->fullname . ' (' . $role . ')'; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"><?= lang('priority') ?> <span
                                                class="text-danger">*</span> </label>
                                        <div class="col-xl-7">
                                            <div class=" ">
                                                <select name="priority" class="form-control">
                                                    <?php
                                                    $priorities = $this->db->get('tbl_priorities')->result();
                                                    if (!empty($priorities)) {
                                                        foreach ($priorities as $v_priorities):
                                                            ?>
                                                            <option value="<?= $v_priorities->priority ?>" <?php
                                                            if (!empty($bug_info) && $bug_info->priority == $bug_info->priority) {
                                                                echo 'selected';
                                                            }
                                                            ?>><?= lang(strtolower($v_priorities->priority)) ?></option>
                                                            <?php
                                                        endforeach;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label 
                                               class="col-xl-3 col-form-label"><?= lang('description') ?> </label>
                                        <div class="col-xl-7">
                                        <textarea class="form-control textarea" name="bug_description" id="" ><?php if (!empty($bug_info->bug_description)) echo $bug_info->bug_description; ?></textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3" id="border-none">
                                        <label 
                                               class="col-xl-3 col-form-label"><?= lang('bug_status') ?>
                                            <span
                                                class="required">*</span></label>
                                        <div class="col-xl-7">

                                            <select name="bug_status" class="form-control" required>
                                                <option
                                                    value="unconfirmed" <?php if (!empty($bug_info->bug_status)) echo $bug_info->bug_status == 'unconfirmed' ? 'selected' : '' ?>> <?= lang('unconfirmed') ?> </option>
                                                <option
                                                    value="confirmed" <?php if (!empty($bug_info->bug_status)) echo $bug_info->bug_status == 'confirmed' ? 'selected' : '' ?>> <?= lang('confirmed') ?> </option>
                                                <option
                                                    value="in_progress" <?php if (!empty($bug_info->bug_status)) echo $bug_info->bug_status == 'in_progress' ? 'selected' : '' ?>> <?= lang('in_progress') ?> </option>
                                                <option
                                                    value="resolved" <?php if (!empty($bug_info->bug_status)) echo $bug_info->bug_status == 'resolved' ? 'selected' : '' ?>> <?= lang('resolved') ?> </option>
                                                <option
                                                    value="verified" <?php if (!empty($bug_info->bug_status)) echo $bug_info->bug_status == 'verified' ? 'selected' : '' ?>> <?= lang('verified') ?> </option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php if (!empty($project_id)): ?>
                                    <div class="row mb-3">
                                        <label 
                                               class="col-xl-3 col-form-label"><?= lang('visible_to_client') ?>
                                            <span class="required">*</span></label>
                                        <div class="col-xl-7">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="client_visible" name="client_visible" value="Yes" <?php
                                                    if (!empty($bug_info) && $bug_info->client_visible == 'Yes') {
                                                        echo 'checked';
                                                    } ?>>
                                                <label class="form-check-label" for="billable"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif ?>
                                    <?php
                                    if (!empty($bug_info)) {
                                        $bug_id = $bug_info->bug_id;
                                    } else {
                                        $bug_id = null;
                                    }
                                    ?>
                                    <?= custom_form_Fields(5, $bug_id); ?>
                                    <div class="row mb-3" id="border-none">
                                        <label class="col-xl-3 col-form-label"><?= lang('assined_to') ?>
                                            <span
                                                class="required">*</span></label>
                                        <div class="col-xl-7">
                                            <div class="form-check form-radio-outline form-radio-primary mb-3">
                                                <input id="everyone" <?php
                                                    if (!empty($bug_info->permission) && $bug_info->permission == 'all') {
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
                                                    if (!empty($bug_info->permission) && $bug_info->permission != 'all') {
                                                        echo 'checked';
                                                    } elseif (empty($bug_info)) {
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
                                        if (!empty($bug_info->permission) && $bug_info->permission != 'all') {
                                            echo 'show';
                                        }
                                        ?>" id="permission_user_1">
                                        <label class="col-xl-3 col-form-label"><?= lang('select') . ' ' . lang('users') ?>
                                            <span class="required">*</span></label>
                                        <div class="col-xl-5">
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
                                                        if (!empty($bug_info->permission) && $bug_info->permission != 'all') {
                                                            $get_permission = json_decode($bug_info->permission);
                                                            foreach ($get_permission as $user_id => $v_permission) {
                                                                if ($user_id == $v_user->user_id) {
                                                                    echo 'checked';
                                                                }
                                                            }

                                                        }
                                                        ?> value="<?= $v_user->user_id ?>" name="assigned_to[]" class="form-check-input" id ="user_<?= $v_user->user_id ?>"   data-name="<?= $v_user->username;?>" >
                                                <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?>
                                                </label>
                                            </div>
                                            <div class="action_1 p
                                                <?php

                                                    if (!empty($bug_info->permission) && $bug_info->permission != 'all') {
                                                        $get_permission = json_decode($bug_info->permission);

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

                                                            if (!empty($bug_info->permission) && $bug_info->permission != 'all') {
                                                                $get_permission = json_decode($bug_info->permission);

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
                                                    <input <?php if (!empty($disable)) { echo 'disabled' . ' ' . 'checked'; } ?> id="delete_<?= $v_user->user_id ?>"
                                                            <?php

                                                            if (!empty($bug_info->permission) && $bug_info->permission != 'all') {
                                                                $get_permission = json_decode($bug_info->permission);
                                                                foreach ($get_permission as $user_id => $v_permission) {
                                                                    if ($user_id == $v_user->user_id) {
                                                                        if (in_array('delete', $v_permission)) {
                                                                            echo 'checked';
                                                                        };
                                                                    }
                                                                }

                                                            }
                                                            ?> name="action_<?= $v_user->user_id ?>[]"  type="checkbox"  value="delete" class="form-check-input">
                                                    <label class="form-check-label" for="delete_<?= $v_user->user_id ?>"><?= lang('delete') ?></label>
                                                </div>
                                                
                                                <input id="<?= $v_user->user_id ?>" type="hidden" name="action_<?= $v_user->user_id ?>[]" value="view">
                                            </div>
                                            <?php } ?>
                                            </div>
                                            <?php  }  ?>
                                        </div>
                                    </div>


                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"></label>
                                        <div class="col-xl-7">
                                            <button type="submit" id="sbtn" class="btn btn-xs btn-primary"><?= lang('save') ?></button>
                                        </div>
                                    </div>
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
