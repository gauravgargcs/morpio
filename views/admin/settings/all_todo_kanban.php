<div class="row" style="overflow-x: auto !important;">
    
    <?php 
    $todo_status = $this->admin_model->get_todo_status();
    foreach ($todo_status as $v_status) {
        $status=$v_status['value'];
        if($user_id>0){
            $t_where = array('user_id' => $user_id, 'status' => $status);
        }else{
            $t_where = array('status' => $status);
        }
        if (!empty($where)) {
            $t_where = array_merge($t_where, $where);
        }

        $all_todo = $this->db->where($t_where)->order_by('todo_id', 'DESC')->get('tbl_todo')->result();
        $total_status = count($all_todo);
    ?>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
               <h4 class="card-title mb-2"><?= $v_status['name']?><small class="badge badge-soft-danger ml"><?php if ($total_status != 0) { echo $total_status; } ?></small></h4>
                <div data-simplebar style="max-height: 600px;min-height: 600px;">  

                    <div id="<?= str_replace(' ','_',$v_status['name']);?>-task" class="pb-1 task-list" data-todo-id="<?= $v_status['id'];?>">
                        <?php if (!empty($all_todo)) {
                        foreach ($all_todo as $v_todo) {
                        $due_date = $v_todo->due_date;
                        $due_time = strtotime($due_date);
                        $current_time = time();

                        $todo_user_id=$v_todo->user_id;
                        $todo_status=$v_todo->status;
                        $todo_status_arr = $this->admin_model->get_todo_status($todo_status);

                        $todo_status_name= $todo_status_arr[0]['name'];
                        $todo_status_label= $todo_status_arr[0]['label'];
                        if (!$todo_status_label) {
                            $todo_status_label = 'primary';
                        }
                        $totaldaysleft=daysleft($due_date);

                        ?>

                        <div class="card task-box" id="<?= str_replace(' ','_',$v_status['name']);?>-<?= $v_todo->todo_id ?>" data-id="<?= $v_todo->todo_id ?>">
                            <div class="card-body">
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item edittask-details" href="<?=base_url('admin/dashboard/new_todo/'.$v_todo->todo_id);?>" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal"><?=lang('Edit');?></a>
                                        <?php 
                                        echo ajax_anchor(base_url("admin/dashboard/delete_todo/" . $v_todo->todo_id), lang('Delete'), array("class" => "dropdown-item deletetask", "title" => lang('delete'), "data-fade-out-on-success" => "#".str_replace(' ','_',$v_status['name']).'-'.$v_todo->todo_id));
                                         ?> 
                                    </div>
                                </div> <!-- end dropdown -->
                                <?php if ($current_time > $due_time) { ?>
                                <div class="float-end ml-2">
                                    <span class="badge rounded-pill badge-soft-danger font-size-12" id="task-status"><?=lang('overdue');?></span>
                                </div>
                                <?php }else{ ?>
                                <div class="float-end ml-2">
                                    <span class="badge rounded-pill badge-soft-warning font-size-12" id="task-status"><?=$totaldaysleft;?></span>
                                </div>
                                <?php } ?>
                                <div>
                                    <h5 class="font-size-15">
                                        <a href="<?=base_url();?>admin/dashboard/new_todo/<?=$v_todo->todo_id;?>" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal" style="' . $style . '" class="text-dark" id="task-name"><?=clear_textarea_breaks($v_todo->title);?></a>
                                    </h5>
                                    <p class="text-muted mb-2">
                                        <?=date('d, M Y',strtotime($due_date));?>
                                       <small> (<?= date("l", strtotime($due_date)) ?>) </small>
                                        
                                    </p>
                                    
                                    <!-- <span class="badge rounded-pill badge-soft-<?=$todo_status_label;?> font-size-12 mb-3"><?=$todo_status_name;?></span> -->
                                    <?php if (!empty(clear_textarea_breaks($v_todo->notes))) { ?>

                                    <p class="text-muted mb-2"><?=clear_textarea_breaks($v_todo->notes);?></p>
                                    <?php } ?>

                                    <?php if ($v_todo->assigned != 0) {
                                    $a_userinfo = $this->db->where('user_id', $v_todo->assigned)->get('tbl_account_details')->row();
                                    ?>
                                    <p class="text-muted mb-2">
                                        <?=lang('assign_by');?>
                                        <a class="text-danger" href="<?=base_url('admin/user/user_details/'.$v_todo->assigned);?>"><?=$a_userinfo->fullname;?></a>
                                    </p>
                                    <?php } ?>

                                    <?php if ($todo_user_id != 0) {
                                    $todo_userinfo = $this->db->where('user_id', $todo_user_id)->get('tbl_account_details')->row();
                                    ?>
                                    <div class="avatar-group float-start">
                                        <div class="avatar-group-item">
                                            <a href="<?=base_url('admin/user/user_details/'.$todo_user_id);?>" class="d-inline-block" data-bs-placement="top" data-bs-toggle="tooltip" title="<?=$todo_userinfo->fullname;?>" >
                                                <img src="<?=base_url().$todo_userinfo->avatar;?>" alt="" class="rounded-circle avatar-xs">
                                            </a>                                            
                                        </div>
                                    </div>
                                   
                                    <?php } ?>


                                </div>
                        
                            </div>

                        </div>
                        <!-- end task card -->

                        <?php } } ?>
                        <div class="text-center d-grid">
                            <a href="<?= base_url() ?>admin/dashboard/new_todo" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal" class="btn btn-primary waves-effect waves-light addtask-btn"><i class="mdi mdi-plus me-1"></i> <?=lang('add_new');?></a>
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

    dragula([<?php $todo_status = $this->admin_model->get_todo_status(); foreach ($todo_status as $v_status) { ?>document.getElementById("<?= str_replace(' ','_',$v_status['name']);?>-task"), <?php } ?> ]).on('drop', function (el, target, source, sibling) {
        var dragTaskId=$(el).attr('data-id');
        var newListID=target.getAttribute('data-todo-id');
        var IDs = [];
        $(target).find('div.task-box').map(function() {
            IDs.push($(this).attr('data-id'));
        }).get();
        var formData = {
            'todo_id': IDs,
            'status': newListID
        };
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?= base_url()?>admin/dashboard/change_todo_status/' + newListID, // the url where we want to POST
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