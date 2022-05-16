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
            <a href="<?= base_url() ?>admin/projects/" class="btn btn-xs btn-primary" data-bs-toggle="tooltip"  data-bs-placement="top" title="<?= lang('switch_to_list') ?>"><i class="fa fa-undo"> </i><?= ' ' . lang('switch_to_list') ?>
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

<div class="row" style="overflow-x: auto !important;">
    <?php
    $projects_status = $this->admin_model->get_projects_status();
    foreach ($projects_status as $v_status) {

        $all_project_info = $this->items_model->get_all_project($v_status['value']);
    ?>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
               <h4 class="card-title mb-2"><?= $v_status['name']?></h4>
                <div data-simplebar style="max-height: 600px;min-height: 600px;">  

                    <div id="<?= str_replace(' ','_',$v_status['name']);?>-task" class="pb-1 task-list" data-project-id="<?= $v_status['value'];?>">
                        <?php 
                        if (!empty($all_project_info)){
                            foreach ($all_project_info as $v_project){
                                $progress = $this->items_model->get_project_progress($v_project->project_id);

                                $can_edit = $this->items_model->can_action('tbl_project', 'edit', array('project_id' => $v_project->project_id));
                                $can_delete = $this->items_model->can_action('tbl_project', 'delete', array('project_id' => $v_project->project_id));

                                $text = null;
                                $style = null;
                                $end_date = $v_project->end_date;
                                $style = 'word-wrap: break-word;';
                                if ($v_project->project_status == 'completed') {
                                    $style .= 'text-decoration: line-through';
                                }
                                $client_info = $this->db->where('client_id', $v_project->client_id)->get('tbl_client')->row();
                                if (!empty($client_info)) {
                                    $name = $client_info->name;
                                } else {
                                    $name = '-';
                                }
                        ?>
                        <div class="card task-box" id="<?= str_replace(' ','_',$v_status['name']);?>-<?= $v_project->project_id ?>" data-id="<?= $v_project->project_id ?>">
                            <div class="card-body">
                                <?php if ((!empty($can_edit) && !empty($edited)) || (!empty($can_delete) && !empty($deleted))) { ?>
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                    </a>
                                    
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                        <a class="dropdown-item edittask-details" href="<?=base_url('admin/projects/new_project/'.$v_project->project_id);?>"><?=lang('Edit');?></a>
                                        <?php } ?>
                                        <?php  if (!empty($can_delete) && !empty($deleted)) {
 
                                            echo ajax_anchor(base_url("admin/dprojects/delete_project/" . $v_project->project_id), lang('Delete'), array("class" => "dropdown-item deletetask", "title" => lang('delete'), "data-fade-out-on-success" => "#".str_replace(' ','_',$v_status['name']).'-'.$v_project->project_id)); 
                                        }
                                         ?> 
                                    </div>
                                </div> <!-- end dropdown -->
                                <?php } ?>

                                <div class="d-flex">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="text-truncate font-size-15"><a href="<?= base_url() ?>admin/projects/project_details/<?= $v_project->project_id ?>" class="text-dark"><?= $v_project->project_name ?></a></h5>
                                        <p class="text-muted mb-4"><?= $name ?></p>
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
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 border-top">
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item me-3">
                                        <?php
                                        if (!empty($v_project->project_status)) {
                                            if ($v_project->project_status == 'completed') {
                                                $statusss = "<span class='badge badge-soft-success'>" . lang($v_project->project_status) . "</span>";
                                            } elseif ($v_project->project_status == 'in_progress') {
                                                $statusss = "<span class='badge badge-soft-primary'>" . lang($v_project->project_status) . "</span>";
                                            } elseif ($v_project->project_status == 'cancel') {
                                                $statusss = "<span class='badge badge-soft-danger'>" . lang($v_project->project_status) . "</span>";
                                            } else {
                                                $statusss = "<span class='badge badge-soft-warning'>" . lang($v_project->project_status) . "</span>";
                                            }
                                            echo $statusss;
                                        }
                                        ?> 
                                    </li>
                                    <li class="list-inline-item me-3">
                                        <i class="bx bx-calendar me-1"></i> <?= display_datetime($v_project->end_date) ?>
                                    </li>
                        
                                </ul>
                            </div>
                        </div>
                        <!-- end task card -->

                        <?php } } ?>
                        <div class="text-center d-grid">
                            <a href="<?= base_url() ?>admin/projects/new_project" class="btn btn-primary waves-effect waves-light addtask-btn"><i class="mdi mdi-plus me-1"></i> <?= lang('new_project') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end col -->
    <?php }  ?>
</div>
<!-- end row -->

<style type="text/css">
    .pr-25{
        padding-right: 10px;
        width: 25%;
    }
</style>

<!-- dragula plugins -->
<script src="<?=base_url();?>skote_assets/libs/dragula/dragula.min.js"></script>

<script type="text/javascript">


$(document).ready(function () {
    'use strict';

    dragula([<?php $projects_status = $this->admin_model->get_projects_status(); foreach ($projects_status as $v_status) { ?>document.getElementById("<?= str_replace(' ','_',$v_status['name']);?>-task"), <?php } ?> ]).on('drop', function (el, target, source, sibling) {
        var dragTaskId=$(el).attr('data-id');
        var newListID=target.getAttribute('data-project-id');
        var IDs = [];
        $(target).find('div.task-box').map(function() {
            IDs.push($(this).attr('data-id'));
        }).get();
        var formData = {
            'project_id': IDs,
            'status': newListID
        };
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?= base_url()?>admin/projects/change_status/' + IDs+'/'+newListID, // the url where we want to POST
            data: formData, // our data object
            dataType: 'json', // what type of data do we expect back from the server
            encode: true,
            success: function (res) {
                if (res) {
                    toastr[res.status](res.message);
                } else {
                    alert('There was a problem with AJAX');
                }
            }
        });
    });
});
</script>