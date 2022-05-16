<div class="row" style="overflow-x: auto !important;">
    <?php

    $edited = can_action('54', 'edited');
    $deleted = can_action('54', 'deleted');
    foreach ($all_task_kanban_category as $v_status) {
        $all_tasks = $this->tasks_model->get_permission('tbl_task',false,['task_status'=> $v_status->task_kanban_category_id]);
        $total_status = count($this->tasks_model->get_tasks($v_status->task_kanban_category_id));

    ?>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <?php if($logged_user_role==1 && $v_status->task_kanban_category_id!=1 && $v_status->task_kanban_category_id!=6){ ?>
                <div class="dropdown float-end">
                    <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="<?= base_url() ?>admin/tasks/new_kanban_category/<?=$v_status->task_kanban_category_id; ?>" data-bs-toggle="modal" data-bs-target="#myModal"><?=lang('Edit');?></a> 

                        <a class="dropdown-item" href="<?= base_url() ?>admin/tasks/delete_kanban_category/<?=$v_status->task_kanban_category_id; ?>"><?=lang('Delete');?></a> 

                    </div>
                </div> <!-- end dropdown -->
                <?php } ?>
                <h4 class="card-title mb-2"><?= $v_status->kanban_category_name; ?><small class="badge badge-soft-danger ml"><?php if ($total_status != 0) { echo $total_status; } ?></small></h4>

                <div data-simplebar style="max-height: 600px;min-height: 600px;">  

                    <div id="<?= str_replace(' ','_',$v_status->kanban_category_name);?>-task" class="pb-1 task-list" data-taskstatus-id="<?= $v_status->task_kanban_category_id;?>">
                        <?php if (!empty($all_tasks)) {
                        foreach ($all_tasks as $v_task) {
                        $total_comments = count($this->db->where('task_id', $v_task->task_id)->get('tbl_task_comment')->result());
                        $total_attachment = count($this->db->where('task_id', $v_task->task_id)->get('tbl_task_attachment')->result());
                        $due_date = $v_task->due_date;
                        $due_time = strtotime($due_date);
                        $current_time = time();
                        $can_edit=NULL;
                        $can_delete=NULL;
                        $task_status=$v_task->task_status;
                        
                        //$all_tasks_statuses = $this->tasks_model->get_statuses($task_status);
                        $task_status_name= $v_status->kanban_category_name;
                        if ($task_status == 6) {
                            $label = 'success';
                        } elseif ($task_status == 1) {
                            $label = 'info';
                        } elseif ($task_status == 5) {
                            $label = 'danger';
                        } else {
                            $label = 'warning';
                        }

                        if ($task_status != 6 || !empty($completed)) {
                            $can_edit = $this->tasks_model->can_action('tbl_task', 'edit', array('task_id' => $v_task->task_id));
                            $can_delete = $this->tasks_model->can_action('tbl_task', 'delete', array('task_id' => $v_task->task_id));
                        }
                        ?>

                        <div class="card task-box" id="<?= str_replace(' ','_',$v_status->kanban_category_name);?>-<?= $v_task->task_id ?>" data-id="<?= $v_task->task_id ?>">
                            <div class="card-body">
                                <?php if ( (!empty($can_edit) && !empty($edited)) || (!empty($can_delete) && !empty($deleted)) ) { ?>
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <?php if (!empty($can_edit) && !empty($edited)) { ?> 
                                        <a class="dropdown-item edittask-details" href="<?=base_url('admin/tasks/new_tasks/'.$v_task->task_id);?>"><?=lang('Edit');?></a>
                                        <?php } ?> 
                                        <?php if (!empty($can_delete) && !empty($deleted)) {
                                        echo ajax_anchor(base_url("admin/tasks/delete_task/" . $v_task->task_id), lang('Delete'), array("class" => "dropdown-item deletetask", "title" => lang('delete'), "data-fade-out-on-success" => "#".str_replace(' ','_',$v_status->task_kanban_category_name).'-'.$v_task->task_id));
                                        } ?> 
                                    </div>
                                </div> <!-- end dropdown -->
                                <?php } ?> 

                                <?php if ($current_time > $due_time && $v_task->task_progress < 100) { ?>
                                <div class="float-end ml-2">
                                    <span class="badge rounded-pill badge-soft-danger font-size-12" id="task-status"><?=lang('overdue');?></span>
                                </div>
                                <?php } ?>
                                <div>
                                    <h5 class="font-size-15">
                                        <a href="<?=base_url();?>admin/tasks/view_task_details/<?=$v_task->task_id;?>" class="text-dark" id="task-name"><?=clear_textarea_breaks($v_task->task_name);?></a>
                                       <!--  <div class="progress progress-sm">
                                            <div class="progress-bar <?php echo ($v_task->task_progress >= 100) ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?= $v_task->task_progress ?>%;" aria-valuenow="<?= $v_task->task_progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div> -->
                                    </h5>
                                    <p class="text-muted mb-2"><?=date('d, M Y',strtotime($due_date));?></p>
                                    <span class="badge rounded-pill badge-soft-<?=$label;?> font-size-12 mb-3"><?=$task_status_name;?></span>
                                    
                                </div>
                                <div class="avatar-group float-start task-assigne">
                                <?php  if ($v_task->permission != 'all') {
                                    $get_permission = json_decode($v_task->permission);
                                    if (!empty($get_permission)) {
                                        $i=$total_task_users=0;

                                        foreach ($get_permission as $permission => $v_permission) {

                                           $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                                            if ($user_info->role_id == 1) {
                                                $label = 'circle-danger';
                                            } else {
                                                $label = 'circle-success';
                                            }
                                            $profile_info = $this->db->where(array('user_id' => $permission))->get('tbl_account_details')->row(); 
                                            if($total_task_users<2){
                                    ?>
                                    <div class="avatar-group-item">
                                        <a href="javascript: void(0);" class="d-inline-block">
                                            <img src="<?=base_url().$profile_info->avatar;?>" alt="" class="rounded-circle avatar-xs">
                                        </a>                                            
                                    </div>
                                    <?php  }  
                                    $i=$i+1;
                                    $total_task_users=$total_task_users+1;
                                    } 
                                    if($total_task_users>2){ ?>
                                    <div class="avatar-group-item">
                                        <a href="<?=base_url();?>admin/tasks/view_task_details/<?=$v_task->task_id;?>" class="d-inline-block">
                                            <div class="avatar-xs">
                                                <span class="avatar-title rounded-circle bg-info text-white font-size-16">
                                                    <?=$total_task_users-2?>+
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <?php  } } }else { ?>
                                    <div class="avatar-group-item">
                                        <a href="javascript: void(0);" class="d-inline-block">
                                            <div class="">
                                                <span class="avatar-title text-white font-size-10">
                                                    <?=lang('everyone');?>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <?php } ?>
                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                    <div class="">
                                        <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?= lang('add_more') ?>" style="margin-left: 20px;">
                                            <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/tasks/update_users/<?= $v_task->task_id ?>" class="text-default ml d-inline-block" style="margin-top: 3px;"><i class="fa fa-plus"></i></a>
                                        </span>
                                    </div>
                                    <?php } ?>
                                </div>

                                <div class="text-end">
                                    <h5 class="font-size-15 mb-1" id="task-budget"><i class="fa fa-comments"></i> <?=$total_comments;?></h5>
                                    <p class="mb-0 text-muted"><i class="fa fa-paperclip"></i> <?=$total_attachment;?></p>
                                </div>
                            </div>

                        </div>
                        <!-- end task card -->

                        <?php } } ?>
                        <div class="text-center d-grid">
                            <a href="<?= base_url() ?>admin/tasks/new_tasks" class="btn btn-primary waves-effect waves-light addtask-btn"><i class="mdi mdi-plus me-1"></i> <?=lang('new_tasks');?></a>
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

    dragula([<?php foreach ($all_task_kanban_category as $v_status) { ?>document.getElementById("<?= str_replace(' ','_',$v_status->kanban_category_name);?>-task"), <?php } ?> ]).on('drop', function (el, target, source, sibling) {
        var dragTaskId=$(el).attr('data-id');
        var newListID=target.getAttribute('data-taskstatus-id');
        var IDs = [];
        $(target).find('div.task-box').map(function() {
            IDs.push($(this).attr('data-id'));
        }).get();
        var formData = {
            'task_id': IDs,
            'task_status': newListID
        };
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?= base_url()?>admin/tasks/change_tasks_status/' + newListID+'/'+dragTaskId, // the url where we want to POST
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