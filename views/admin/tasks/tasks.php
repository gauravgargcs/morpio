<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<style>
    .note-editor .note-editable {
        height: 150px;
    }

    a:hover {
        text-decoration: none;
    }
    .tbl-action{
        padding-bottom: 15px;
    }
   
    #DataTables_filter label , #DataTables_filter input{
        display: inline-block !important;
    }
     .action_1{
        display: inline-flex;
    }
</style>
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
$all_goal_tracking = $this->tasks_model->get_permission('tbl_goal_tracking');

$all_goal = 0;
$bank_goal = 0;
$complete_achivement = 0;
if (!empty($all_goal_tracking)) {
    foreach ($all_goal_tracking as $v_goal_track) {
        $goal_achieve = $this->tasks_model->get_progress($v_goal_track, true);

        if ($v_goal_track->goal_type_id == 8) {

            if ($v_goal_track->end_date <= $mdate) { // check today is last date or not

                if ($v_goal_track->email_send == 'no') {// check mail are send or not
                    if ($v_goal_track->achievement <= $goal_achieve['achievement']) {
                        if ($v_goal_track->notify_goal_achive == 'on') {// check is notify is checked or not check
                            $this->tasks_model->send_goal_mail('goal_achieve', $v_goal_track);
                        }
                    } else {
                        if ($v_goal_track->notify_goal_not_achive == 'on') {// check is notify is checked or not check
                            $this->tasks_model->send_goal_mail('goal_not_achieve', $v_goal_track);
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
$last_weeks = 0;
for ($iDay = 7; $iDay >= 0; $iDay--) {
    $date = date('Y-m-d', strtotime('today - ' . $iDay . 'days'));
    $where = array('task_created_date >=' => $date . " 00:00:00", 'task_created_date <=' => $date . " 23:59:59", 'task_status' => 6);

    $invoice_result[$date] = count(get_result('tbl_task', $where));
    $last_weeks += count(get_result('tbl_task', $where));
}

$terget_achievement = get_result('tbl_goal_tracking', array('goal_type_id' => 8, 'start_date >=' => $last_7_days, 'end_date <=' => $mdate));

$total_terget = 0;
if (!empty($terget_achievement)) {
    foreach ($terget_achievement as $v_terget) {
        $total_terget += $v_terget->achievement;
    }
}
$tolal_goal = $all_goal + $bank_goal;
$curency = $this->tasks_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');

if ($this->session->userdata('user_type') == 1) {
    $margin = 'margin-bottom:20px';
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
                        <h5 class="mb-0"><?= $last_weeks ?></h5>
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
                        <p class="text-muted text-truncate mb-2"><?= lang('pending') . ' ' . lang('achievements') ?></p>
                        <h5 class="mb-0"><?php
                                        if ($tolal_goal < $complete_achivement) {
                                            $pending_goal = 0;
                                        } else {
                                            $pending_goal = $tolal_goal - $complete_achivement;
                                        } ?>
                                        <?= $pending_goal ?>                    
                        </h5>
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


<?php $complete = 0;
$not_started = 0;
if (!empty($all_task_info)):foreach ($all_task_info as $v_task):
    if ($v_task->task_status == 6) {
        $complete += count($v_task->task_id);
    }
    if ($v_task->task_status == 1) {
        $not_started += count($v_task->task_id);
    }
endforeach;
endif;
$created = can_action('54', 'created');

$edited = can_action('54', 'edited');
$deleted = can_action('54', 'deleted');

$kanban = $this->session->userdata('task_kanban');
$uri_segment = $this->uri->segment(4);
if (!empty($kanban)) {
    $tasks = 'kanban';    
} elseif ($uri_segment == 'kanban') {
    $tasks = 'kanban';
} else {
    $tasks = 'list';
}

if ($tasks == 'kanban') {
    $text = 'list';
    $btn = 'primary';

} else {
    $text = 'kanban';
    $btn = 'danger';
}

?>
<div class="row mb-3">
<div class="mb-lg pull-left">
    <div class="pull-left">
        <a href="<?= base_url() ?>admin/tasks/all_task/<?= $text ?>" class="btn btn-xs btn-<?= $btn ?> pull-right" data-bs-toggle="tooltip"  data-bs-placement="top" title="<?= lang('switch_to_' . $text) ?>"><i class="fa fa-undo"> </i><?= ' ' . lang('switch_to_' . $text) ?>
        </a>
    </div>
    <div class="float-end">
        <?php if($logged_user_role==1){ ?>
        <a class="btn btn-secondary waves-effect waves-light" href="<?= base_url() ?>admin/tasks/new_kanban_category" data-bs-toggle="modal" data-bs-target="#myModal"><?= lang('add').' '.lang('kanban_category') ?></a>
        <?php } ?>
        
        <?php if (!empty($created) || !empty($edited)) { ?>
        <a class="btn btn-xs btn-primary mr-1" href="<?= base_url() ?>admin/tasks/new_tasks"><?= lang('assign_task') ?></a>
        <a class="btn btn-xs btn-danger" href="<?= base_url() ?>admin/tasks/import"><?= lang('import') . ' ' . lang('tasks') ?></a>
        <?php } ?>
    </div>
</div>
</div>
<?php if ($tasks == 'kanban') { ?>
   <?php $this->load->view('admin/tasks/tasks_kanban'); ?>
<?php } else { ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs bg-light rounded" role="tablist">
                        <li class="nav-item waves-light"><a class="nav-link <?= $active == 0 ? 'active' : '' ?>" href="<?= base_url().'admin/tasks/all_task' ?>"><?= lang('all_task') ?></a>
                        </li>
                        <?php
                        foreach ($all_task_kanban_category as $v_status) {
                            $total_status = count($this->tasks_model->get_tasks($v_status->task_kanban_category_id)); ?>
                            
                            <li class="nav-item waves-light"><a class="nav-link pull-right <?= $active == $v_status->task_kanban_category_id ? 'active' : ''; ?>" href="<?= base_url() ?>admin/tasks/all_task/<?= $v_status->task_kanban_category_id ?>"><?= $v_status->kanban_category_name ?><small class="badge badge-soft-danger ml"><?php if ($total_status != 0) { echo $total_status; } ?></small></a></li>

                        <?php } ?>
                    </ul>
                    <div class="tab-content p-3 text-muted">
                        <!-- Stock Category List tab Starts -->
                        <div class="tab-pane <?= $active >=0 ? 'active' : '' ?>" id="task_list">
                            <!-- Table -->
                            <?php if (!empty($created) || !empty($edited)) { ?>
                            <div class="dropdown tbl-action">
                                <a class="btn btn-primary " onclick="return checkSelected()" data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/tasks/multiple_task_update_users" title="Users">Update users <span class="caret"></span></a>
                                <button class="btn btn-success dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_status') ?><i class="mdi mdi-chevron-down"></i></button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <?php
                                    foreach ($all_task_kanban_category as $v_status) { ?>
                                    <a class="dropdown-item" onclick="multipe_change_status('<?=$v_status->task_kanban_category_id;?>')" href="#"><?= $v_status->kanban_category_name; ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="table-responsive">

                            <table class="table table-striped dt-responsive nowrap w-100" id="contentTable">
                                <thead>
                                <tr>
                                    <th data-check-all>
                                    <?php if (!empty($created) || !empty($edited)) { ?>
                                        <div class="form-check font-size-16 check-all">
                                            <input type="checkbox" id="parent_present" class="form-check-input">
                                            <label for="parent_present" class="toggle form-check-label"></label>
                                        </div>
                                    <?php } ?>
                                    </th>
                                    <th class="col-sm-2"><?= lang('task_name') ?></th>
                                    <?php super_admin_opt_th() ?>
                                    <th class="col-sm-2"><?= lang('due_date') ?></th>
                                    <th class="col-sm-1"><?= lang('status') ?></th>
                                    <th class="col-sm-1"><?= lang('progress') ?></th>
                                    <th class="col-sm-2"><?= lang('assigned_to') ?></th>
                                    <?php /* $show_custom_fields = custom_form_table(3, null);
                                    if (!empty($show_custom_fields)) {
                                        foreach ($show_custom_fields as $c_label => $v_fields) {
                                            if (!empty($c_label)) {
                                                ?>
                                                <th><?= $c_label ?> </th>
                                            <?php }
                                        }
                                    } */
                                    ?>
                                    <th class="col-sm-3"><?= lang('changes/view') ?></th>
                                </tr>
                                </thead>
                                <?php /* ?><tbody>
                                <?php 
                                if (!empty($all_task_info)):foreach ($all_task_info as $key => $v_task):
                                    if ($v_task->task_status != 6 || !empty($completed)) {
                                    $task_status=$v_task->task_status;
                                    $can_edit = $this->tasks_model->can_action('tbl_task', 'edit', array('task_id' => $v_task->task_id));
                                    $can_delete = $this->tasks_model->can_action('tbl_task', 'delete', array('task_id' => $v_task->task_id));
                                    $task_kanban_category = $this->tasks_model->get_task_kanban_category($task_status);
                                   
                                    $task_status_name=$task_kanban_category[0]->kanban_category_name;
                                    ?>
                                        <tr id="table-tasks-<?= $v_task->task_id ?>">
                                                <td class="col-sm-1">
                                            <?php if ($can_edit) { ?>
                                                    <div class="form-check font-size-16">
                                                        <input class="action-check form-check-input" type="checkbox" data-id="<?= $v_task->task_id ?>" style="position: absolute;" name="task_id[]" value="<?= $v_task->task_id ?>">
                                                        <label class="form-check-label"></label>
                                                    </div>
                                            <?php } ?>
                                                </td>
                                            <td class="col-sm-2">
                                                <a style="<?php
                                                if ($v_task->task_progress >= 100) {
                                                    echo 'text-decoration: line-through;';
                                                }
                                                ?>"
                                                   href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
                                            </td>
                                            <?php super_admin_opt_td($v_task->companies_id) ?>
                                            <td><?php
                                                $due_date = $v_task->due_date;
                                                $due_time = strtotime($due_date);
                                                $current_time = time();
                                                if ($v_task->task_progress == 100) {
                                                    $c_progress = 100;
                                                } elseif ($task_status == 6) {
                                                    $c_progress = 100;
                                                } else {
                                                    $c_progress = 0;
                                                }

                                                ?>
                                                <?= display_datetime($due_date) ?>
                                                <?php if ($current_time > $due_time && $c_progress < 100) { ?>
                                                    <br><span  class="badge badge-soft-danger"><?= lang('overdue') ?></span>
                                                <?php } ?></td>
                                            <td>
                                                <?php
                                                if ($task_status == 6) {
                                                    $label = 'success';
                                                } elseif ($task_status == 1) {
                                                    $label = 'info';
                                                } elseif ($task_status == 5) {
                                                    $label = 'danger';
                                                } else {
                                                    $label = 'warning';
                                                }
                                                ?>
                                                <span class="badge badge-soft-<?= $label ?>"><?= $task_status_name; ?> </span>
                                            </td>
                                            <td class="col-sm-1" style="padding-bottom: 0px;padding-top: 3px">

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
                                                         data-size="50"
                                                         data-animate="2000">
                                                    <span class="small "><?= $v_task->task_progress ?>
                                                        %</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                               <div class="avatar-group">
                                                <?php
                                                if ($v_task->permission != 'all') {
                                                    $get_permission = json_decode($v_task->permission);
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
                                                            <a href="#" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="<?= $profile_info->fullname ?>" class="d-inline-block"><img
                                                                        src="<?= base_url() . $profile_info->avatar ?>"
                                                                        class="rounded-circle avatar-xs" alt="">
                                                                <span style="margin: 0px 0 8px -10px;"
                                                                      class="mdi mdi-circle <?= $label ?> font-size-10"></span>
                                                            </a>
                                                    </div>
                                                        <?php
                                                        $i=$i+1;
                                                            $total_users=$total_users+1;
                                                            } 
                                                        endforeach;
                                                            if($total_users>2){ ?>
                                                        <div class="avatar-group-item">
                                                        <a href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>" class="d-inline-block">
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
                                                <span class="mr-lg-2">
                                                    <strong><?= lang('everyone') ?></strong>
                                                    <i  title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                                </span>
                                                    <?php
                                                }
                                                ?>
                                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?= lang('add_more') ?>" class="mt-2">
                                                    <a  data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/tasks/update_users/<?= $v_task->task_id ?>" class="text-default"><i class="fa fa-plus"></i></a>                                                
                                                </span>
                                                <?php } ?>
                                            </td>
                                            <?php $show_custom_fields = custom_form_table(3, $v_task->task_id);
                                            if (!empty($show_custom_fields)) {
                                                foreach ($show_custom_fields as $c_label => $v_fields) {
                                                    if (!empty($c_label)) {
                                                        ?>
                                                        <td><?= $v_fields ?> </td>
                                                    <?php }
                                                }
                                            }
                                            ?>

                                            <td>
                                                <?php if (!empty($can_edit) && !empty($edited)) {
                                                    echo btn_edit('admin/tasks/new_tasks/' . $v_task->task_id) . ' ';
                                                } ?>
                                                <?php if (!empty($can_delete) && !empty($deleted)) {
                                                    echo ajax_anchor(base_url("admin/tasks/delete_task/" . $v_task->task_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table-tasks-" . $v_task->task_id));
                                                } ?>
                                                <?php

                                                if ($v_task->timer_status == 'on') { ?>
                                                    <a class="btn btn-outline-danger btn-sm"
                                                       href="<?= base_url() ?>admin/tasks/tasks_timer/off/<?= $v_task->task_id ?>"><?= lang('stop_timer') ?> </a>

                                                <?php } else { ?>
                                                    <a class="btn btn-outline-success btn-sm"
                                                       href="<?= base_url() ?>admin/tasks/tasks_timer/on/<?= $v_task->task_id ?>"><?= lang('start_timer') ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                endforeach; ?>
                                <?php endif; ?>
                                </tbody><?php */ ?>
                            </table>
                            </div>
                        </div>                    
                    </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script type="text/javascript">
    function checkSelected(){
        if($(".action-check").is(":checked")){
            return true;
        }
        toastr['error']('Please select any record');
        return false
    }
    function multipe_change_status(status){
        if(!$(".action-check").is(":checked")){
             toastr['error']('Please select any record');
          return false
        }
        if( !confirm("<?=lang('are_you_sure_want_to_update_status_for_selected_tasks');?>")){
            return false;
        }
        var task_id = [];
          $(".action-check:checked").each(function(){
             task_id.push($(this).val());
        });
        $('#loader-wrapper').show();

       // data = data.serialize()
        // console.log(data);
          $.ajax({
           url: '<?=site_url('admin/tasks/multiple_task_change_status');?>',
           data: {task_id:task_id, status:status },
          
           type: 'POST',
           success: function(data){
           data = jQuery.parseJSON(data);
              $('#loader-wrapper').hide();
             if(data['success']==true){
                 toastr['success'](data['message']);
                 window.location.reload();
             }else{
                toastr['erorr'](data['message']);
             }
             
        },
           error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus+" "+errorThrown);
          }
        });
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

<!-- Script -->
 <script type="text/javascript">
     $(document).ready(function(){
        $('#contentTable').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
             'url':'<?=base_url()?>admin/datatable/all_task?filter=<?=$filterBy;?>'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table-tasks-"+iDisplayIndex);
            return nRow;
          },
          'columns': [
             { data: 'checkbox' },
             { data: 'task_name' },
			 <?php if (is_company_column_ag()) { ?>
             { data: 'companies' },
			 <?php } ?>
             { data: 'due_date' },
             { data: 'status' },
             { data: 'progress' },
             { data: 'assigned_to' },
             // { data: 'label' },
             { data: 'action' },
          ]
        });
     });
 </script>