<div class="row" style="overflow-x: auto !important;">
<?php
$all_milestones_info = $this->db->where('project_id', $project_details->project_id)->get('tbl_milestones')->result();

$all_task_info = $this->db->where('project_id', $project_details->project_id)->order_by('task_id', 'DESC')->get('tbl_task')->result();

if (!empty($all_milestones_info)) {
    foreach ($all_milestones_info as $key => $v_milestones) {
?>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-2"><?= $v_milestones->milestone_name ?></h4>
                <div data-simplebar style="max-height: 600px;min-height: 600px;">  
                    <div id="<?= str_replace(' ','_',$v_milestones->milestone_name);?>-task" class="pb-1 task-list" data-milestones-id="<?= $v_milestones->milestones_id;?>">
                        <?php
                        $t_edited = can_action('54', 'edited');
                        if (!empty($all_task_info)){
                        foreach ($all_task_info as $key => $mv_task){
                        $total_comments = count($this->db->where('task_id', $mv_task->task_id)->get('tbl_task_comment')->result());
                        $total_attachment = count($this->db->where('task_id', $mv_task->task_id)->get('tbl_task_attachment')->result());
                        $text = null;
                        $due_date = $mv_task->due_date;
                        $due_time = strtotime($due_date);
                        $current_time = time(); ?>
                        <div class="card task-box" id="<?= str_replace(' ','_',$v_milestones->milestone_name);?>-<?= $mv_task->task_id ?>" data-id="<?= $mv_task->task_id ?>">
                            <div class="card-body">
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="<?=base_url('admin/tasks/view_task_details/' . $mv_task->task_id);?>"><?=lang('view');?></a>
                                        <?php if (!empty($t_edited)) { ?>
                                        <a class="dropdown-item edittask-details" href="<?=base_url('admin/tasks/new_tasks/' . $mv_task->task_id);?>"><?=lang('Edit');?></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php if ($current_time > $due_time && $mv_task->task_progress < 100) { ?>
                                <div class="float-end ml-2">
                                    <span class="badge rounded-pill badge-soft-danger font-size-12" id="task-status"><?=lang('overdue');?></span>
                                </div>
                                <?php } ?>
                                <div>
                                    <h5 class="font-size-15">
                                        <a href="<?=base_url();?>admin/tasks/view_task_details/<?=$mv_task->task_id;?>" class="text-dark" id="task-name"><?=clear_textarea_breaks($mv_task->task_name);?></a>
                                        <div class="progress progress-sm mt-2">
                                            <div class="progress-bar <?php echo ($mv_task->task_progress >= 100) ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?= $mv_task->task_progress ?>%;" aria-valuenow="<?= $mv_task->task_progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </h5>
                                    <p class="text-muted mb-4"><?=date('d, M Y',strtotime($due_date));?></p>
                                    
                                </div>
                                <div class="avatar-group float-start task-assigne">
                                <?php  if ($mv_task->permission != 'all') {
                                    $get_permission = json_decode($mv_task->permission);
                                    if (!empty($get_permission)) {
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
                                        <a href="<?=base_url();?>admin/tasks/view_task_details/<?=$mv_task->task_id;?>" class="d-inline-block">
                                            <div class="avatar-xs">
                                                <span class="avatar-title rounded-circle bg-info text-white font-size-16">
                                                    <?=$total_task_users-2?>+
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <?php  } } }else { ?>
                                    <span class="mr-lg-2">
                                        <strong><?= lang('everyone') ?></strong>
                                        <i  title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                    </span>
                                    <?php } ?>
                                    <?php if (!empty($t_edited)) { ?>
                                    <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?= lang('add_more') ?>" style="margin-left: 20px;" class="mt-2">
                                        <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/tasks/update_users/<?= $mv_task->task_id ?>" class="text-default" style="margin-top: 3px;"><i class="fa fa-plus"></i></a>
                                    </span>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end col -->
    <?php } }  ?>
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

    dragula([<?php if (!empty($all_milestones_info)) { foreach ($all_milestones_info as $key => $v_milestones) { ?> document.getElementById("<?= str_replace(' ','_',$v_milestones->milestone_name);?>-task"), <?php } } ?> ]).on('drop', function (el, target, source, sibling) {
        var dragTaskId=$(el).attr('data-id');
        var newListID=target.getAttribute('data-milestones-id');
    
        var IDs = [];
        $(target).find('div.task-box').map(function() {
            IDs.push($(this).attr('data-id'));
        }).get();
        var formData = {
            'task_id': IDs,
        };
        console.log(IDs);
        console.log(newListID);
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?= base_url()?>admin/projects/change_milestones/' + newListID, // the url where we want to POST
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