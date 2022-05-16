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
$all_goal_tracking = $this->items_model->get_permission('tbl_goal_tracking');
$all_goal = 0;
$bank_goal = 0;
$complete_achivement = 0;
if (!empty($all_goal_tracking)) {
    foreach ($all_goal_tracking as $v_goal_track) {
        $goal_achieve = $this->items_model->get_progress($v_goal_track, true);

        if ($v_goal_track->goal_type_id == 12) {

            if ($v_goal_track->end_date <= $mdate) { // check today is last date or not

                if ($v_goal_track->email_send == 'no') {// check mail are send or not
                    if ($v_goal_track->achievement <= $goal_achieve['achievement']) {
                        if ($v_goal_track->notify_goal_achive == 'on') {// check is notify is checked or not check
                            $this->items_model->send_goal_mail('goal_achieve', $v_goal_track);
                        }
                    } else {
                        if ($v_goal_track->notify_goal_not_achive == 'on') {// check is notify is checked or not check
                            $this->items_model->send_goal_mail('goal_not_achieve', $v_goal_track);
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
    $where = array('created_time >=' => $date . " 00:00:00", 'created_time <=' => $date . " 23:59:59", 'project_status' => 'completed');
    $invoice_result[$date] = count(get_result('tbl_project', $where));
}

$terget_achievement = get_result('tbl_goal_tracking', array('goal_type_id' => 12, 'start_date >=' => $last_7_days, 'end_date <=' => $mdate));

$total_terget = 0;
if (!empty($terget_achievement)) {
    foreach ($terget_achievement as $v_terget) {
        $total_terget += $v_terget->achievement;
    }
}
$tolal_goal = $all_goal + $bank_goal;
$curency = $this->items_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
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
                            <h5 class="mb-0"><?= ($tolal_goal) ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('completed') ?></p>
                            <h5 class="mb-0"><?= ($complete_achivement) ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('pending') ?></p>
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
                            <p class="text-muted text-truncate mb-2"><?= lang('last_weeks') . ' ' . lang('created') ?></p>
                            <h5 class="mb-0"><?= ($total_terget) ?></h5>
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
<?php } ?>
<?= message_box('success'); ?>
<?= message_box('error');
$complete = 0;
$in_progress = 0;
$started = 0;
$checking = 0;
$overdue = 0;
$all_project = $this->items_model->get_permission('tbl_project');
if (!empty($all_project)):foreach ($all_project as $v_project):
    $aprogress = $this->items_model->get_project_progress($v_project->project_id);
    if ($v_project->project_status == 'completed') {
        $complete += count($v_project->project_id);
    }
    if ($v_project->project_status == 'in_progress') {
        $in_progress += count($v_project->project_id);
    }
    if ($v_project->project_status == 'checking') {
        $checking += count($v_project->project_id);
    }
    if ($v_project->project_status == 'started') {
        $started += count($v_project->project_id);
    }
    if (time() > strtotime($v_project->end_date) AND $aprogress < 100) {
        $overdue += count($v_project->project_id);

    }
endforeach;
endif;
$created = can_action('57', 'created');
$edited = can_action('57', 'edited');
$deleted = can_action('57', 'deleted');
?>
<div class="row mb-3">
    <div class="mb-lg pull-left">
        <div class="pull-left">
            <a href="<?= base_url() ?>admin/projects/projects_kanban" class="btn btn-xs btn-danger" data-bs-toggle="tooltip"  data-bs-placement="top" title="<?= lang('switch_to_kanban') ?>"><i class="fa fa-undo"> </i><?= ' ' . lang('switch_to_kanban') ?>
            </a>
        </div>
      
        <div class="float-end">
            <?php if (!empty($created) || !empty($edited)) { ?>
            <a class="btn btn-xs btn-primary mr-1" href="<?= base_url() ?>admin/projects/new_project"><?= lang('new_project') ?></a>
            <a class="btn btn-xs btn-danger" href="<?= base_url() ?>admin/projects/import"><?= lang('import') . ' ' . lang('projects') ?></a>
            <?php } ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="<?= base_url().'admin/projects' ?>" >
                            <?= lang('all_project') ?>
                        </a>
                    </li>
                    <li class="nav-item waves-light">
                        <a class="nav-link pull-right <?= $active == 'in_progress' ? 'active' : ''; ?>" href="<?= base_url() ?>admin/projects/index/in_progress">
                            <?= lang('in_progress')?><small class="badge badge-soft-primary ml"><?php if ($in_progress != 0) { echo $in_progress; } ?></small>
                        </a>
                    </li>
                    <li class="nav-item waves-light">
                        <a class="nav-link pull-right <?= $active == 'started' ? 'active' : ''; ?>" href="<?= base_url() ?>admin/projects/index/started">
                            <?= lang('project_in_progress') ?><small class="badge badge-soft-warning ml"><?php if ($started != 0) { echo $started; } ?></small>
                        </a>
                    </li>
                    <li class="nav-item waves-light">
                        <a class="nav-link pull-right <?= $active == 'checking' ? 'active' : ''; ?>" href="<?= base_url() ?>admin/projects/index/checking">
                            <?= lang('checking') ?><small class="badge badge-soft-warning ml"><?php if ($checking != 0) { echo $checking; } ?></small>
                        </a>
                    </li>

                    <li class="nav-item waves-light">
                        <a class="nav-link pull-right <?=$active=='completed'?'active':'';?>" href="<?= base_url() ?>admin/projects/index/completed">
                            <?= lang('completed') ?><small class="badge badge-soft-success ml"><?php if ($complete != 0) { echo $complete; } ?></small>
                        </a>
                    </li>
                    <li class="nav-item waves-light">
                        <a class="nav-link pull-right <?= $active == 'overdue' ? 'active' : ''; ?>" href="<?= base_url() ?>admin/projects/index/overdue">
                            <?= lang('overdue') ?><small class="badge badge-soft-danger ml"><?php if ($overdue != 0) { echo $overdue; } ?></small>
                        </a>
                    </li> 
                  
                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 || $active == 'overdue' || $active == 'started' || $active == 'checking' || $active == 'in_progress' || $active == 'completed' ? 'active' : ''; ?> mb-3" id="manage">

                        <h4 class="card-title mb-3"><?php if($tab=='projects'){ ?><?=lang('all'). '  ' .lang($tab) ?> <?php } else{ ?><?=lang($tab). '  ' .lang('projects') ?><?php } ?></h4>
                       
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="list_project_datatable">
                                <thead>
                                <tr>
                                    <th width="20"><?= lang('project_name') ?></th>
                                    <?php  super_admin_opt_th() ?>
                                    <th class="col-sm-1"><?= lang('client') ?></th>
                                    <th class="col-sm-1"><?= lang('end_date') ?></th>
                                    <th class="col-sm-1"><?= lang('assigned_to') ?></th>
                                    <th class="col-sm-1"><?= lang('status') ?></th>
                                    <?php $show_custom_fields = custom_form_table(4, null);
                                    if (!empty($show_custom_fields)) {
                                        foreach ($show_custom_fields as $c_label => $v_fields) {
                                            if (!empty($c_label)) {
                                                ?>
                                                <th><?= $c_label ?> </th>
                                            <?php }
                                        }
                                    }
                                    ?>
                                    <th class="col-options no-sort"><?= lang('action') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($all_project_info)):foreach ($all_project_info as $v_project):
                                    $progress = $this->items_model->get_project_progress($v_project->project_id);

                                    $can_edit = $this->items_model->can_action('tbl_project', 'edit', array('project_id' => $v_project->project_id));
                                    $can_delete = $this->items_model->can_action('tbl_project', 'delete', array('project_id' => $v_project->project_id));
                                    ?>
                                    <tr id="table-project-<?= $v_project->project_id ?>">
                                        <?php
                                        $client_info = $this->db->where('client_id', $v_project->client_id)->get('tbl_client')->row();
                                        if (!empty($client_info)) {
                                            $name = $client_info->name;
                                        } else {
                                            $name = '-';
                                        }
                                        ?>
                                        <td width="2%">
                                            <a class="text-info" href="<?= base_url() ?>admin/projects/project_details/<?= $v_project->project_id ?>"><?= $v_project->project_name ?></a>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar <?php echo ($progress >= 100) ? 'bg-success' : ''; ?>" role="progressbar" style="width:<?= $v_project->progress; ?>%;" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </td>
                                        <?php super_admin_opt_td($v_project->companies_id) ?>
                                        <td class="col-sm-1"><?= $name ?></td>
                                        <td class="col-sm-1"><?= display_datetime($v_project->end_date) ?></td>
                                        <td class="col-sm-1">
                                            <div class="avatar-group">
                                                <?php
                                            if ($v_project->permission != 'all') {
                                                $get_permission = json_decode($v_project->permission);
                                                if (!empty($get_permission)) :
                                                    $i=$total_users=0;
                                                    foreach ($get_permission as $permission => $v_permission) :
                                                        $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                                                        if (!empty($user_info)) {
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
                                                               title="<?= $profile_info->fullname ?>" class="d-inline-block"><img src="<?= base_url() . $profile_info->avatar ?>" class="rounded-circle avatar-xs" alt="">
                                                                <span style="margin: 0px 0 8px -10px;" class="mdi mdi-circle <?= $label ?> font-size-10"></span>
                                                            </a>
                                                    </div>
                                                            <?php }  
                                                            $i=$i+1;
                                                            $total_users=$total_users+1;
                                                            } 
                                                            endforeach;
                                                            if($total_users>2){ ?>
                                                   <div class="avatar-group-item">
                                                        <a href="<?= base_url() ?>admin/projects/project_details/<?= $v_project->project_id ?>" class="d-inline-block">
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
                                                <span class="mr-lg-2 mt-2">
                                                    <strong><?= lang('everyone') ?></strong>
                                                    <i  title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                                </span>
                                                <?php } ?>
                                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                <span data-bs-placement="top" data-bs-toggle="tooltip"  title="<?= lang('add_more') ?>" class="mt-2">
                                                    <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/projects/update_users/<?= $v_project->project_id ?>" class="text-default"><i class="fa fa-plus"></i></a>
                                                </span>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td class="col-sm-1">
                                            <?php
                                            if (!empty($v_project->project_status)) {
                                                if ($v_project->project_status == 'completed') {
                                                    $statusss = "<span class='badge badge-soft-success'>" . lang($v_project->project_status) . "</span>";
                                                } elseif ($v_project->project_status == 'in_progress') {
                                                    $statusss = "<span class='badge badge-soft-primary'>" . lang($v_project->project_status) . "</span>";
                                                } elseif ($v_project->project_status == 'checking') {
                                                    $statusss = "<span class='badge badge-soft-danger'>" . lang($v_project->project_status) . "</span>";
                                                } elseif ($v_project->project_status == 'started') {
                                                    $statusss = "<span class='badge badge-soft-warning'>" . lang('project_in_progress') . "</span>";
                                                } else {
                                                    $statusss = "<span class='badge badge-soft-warning'>" . lang($v_project->project_status) . "</span>";
                                                }
                                                echo $statusss;
                                            }
                                            ?> 
                                             <?php if (time() > strtotime($v_project->end_date) AND $progress < 100) { ?>
                                                <br><span class="badge badge-soft-danger"><?= lang('overdue') ?></span><br>
                                            <?php } ?>
                                        </td>

                                        <?php $custom_form_table = custom_form_table(4, $v_project->project_id);

                                        if (!empty($custom_form_table)) {
                                            foreach ($custom_form_table as $c_label => $v_fields) {
                                                ?>
                                                <td><?= $v_fields ?> </td>
                                                <?php
                                            }
                                        }
                                        ?>
                                        <td class="col-sm-1">
                                            <?= btn_view('admin/projects/project_details/' . $v_project->project_id) ?>

                                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                <a data-bs-toggle="modal" data-bs-target="#myModal" title="<?= lang('clone_project') ?>" href="<?= base_url() ?>admin/projects/clone_project/<?= $v_project->project_id ?>"><i class="btn btn-outline-secondary btn-sm fa fa-copy" style="height:26px;"></i></a>

                                                <?= btn_edit('admin/projects/new_project/' . $v_project->project_id) ?>
                                            <?php }
                                            if (!empty($can_delete) && !empty($deleted)) { ?>
                                                <?php echo ajax_anchor(base_url("admin/projects/delete_project/" . $v_project->project_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table-project-" . $v_project->project_id)); ?>
                                            <?php } ?>
                                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                            <div class="dropdown tbl-action mt">

                                                <button class="btn btn-outline-success dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_status') ?><i class="mdi mdi-chevron-down"></i></button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="<?= base_url() ?>admin/projects/change_status/<?= $v_project->project_id . '/in_progress' ?>"><?= lang('in_progress') ?></a>
                                                    <a class="dropdown-item" href="<?= base_url() ?>admin/projects/change_status/<?= $v_project->project_id . '/started' ?>"><?= lang('project_in_progress') ?></a>
                                            
                                                    <a class="dropdown-item" href="<?= base_url() ?>admin/projects/change_status/<?= $v_project->project_id . '/checking' ?>"><?= lang('checking') ?></a>
                                                    
                                                    <a class="dropdown-item" href="<?= base_url() ?>admin/projects/change_status/<?= $v_project->project_id . '/completed' ?>"><?= lang('completed') ?></a>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php
                                endforeach;
                                endif;
                                ?>
                                </tbody>
                            </table>
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('select[name="companies_id"]').on('change', function () {
            var companies_id = $(this).val();
            if (companies_id) {
                $.ajax({
                    url: '<?= base_url('admin/global_controller/json_by_company/tbl_client/')?>' + companies_id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('select[name="client_id"]').find('option').not(':first').remove();
                        $.each(data, function (key, value) {
                            $('select[name="client_id"]').append('<option value="' + value.client_id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('select[name="client_id"]').empty();
            }
        });
    });
</script>

<script type="text/javascript">
    <?php if(empty($project_info)){?>
    $('.hourly_rate').hide();
    <?php }?>
    function get_billing_value(val) {

        if (val == 'fixed_rate') {
            $('.fixed_rate').show();
            $(".fixed_rate").removeAttr('disabled');
            $('.hourly_rate').hide();
            $(".hourly_rate").attr('disabled', 'disabled');
            $('.based_on_tasks_hour').hide();
        } else if (val == 'tasks_hours') {
            $('.hourly_rate').hide();
            $(".hourly_rate").attr('disabled', 'disabled');
            $('.fixed_rate').hide();
            $(".fixed_rate").attr('disabled', 'disabled');
            $('.based_on_tasks_hour').show();
        } else {
            $('.hourly_rate').show();
            $(".hourly_rate").removeAttr('disabled');
            $('.fixed_rate').hide();
            $(".fixed_rate").attr('disabled', 'disabled');
            $('.based_on_tasks_hour').show();
        }
        if (val == 'project_hours') {
            $('.based_on_tasks_hour').hide();
        }
    }

    $(document).ready(function(){
        $("#sparkline2").sparkline([<?php if (!empty($invoice_result)) { foreach ($invoice_result as $v_invoice_result) { echo $v_invoice_result . ','; } } ?>], {
            type: 'bar',
            height: '20',
            barWidth: 8,
            barSpacing: 6,
            barColor: '#23b7e5'
        });

    });

</script>