<div class="row" style="overflow-x: auto !important;">
    <?php

    $edited = can_action('54', 'edited');
    $deleted = can_action('54', 'deleted');

    $leads_status = get_result('tbl_lead_status');
    
    foreach ($leads_status as $key => $v_leads_status) {
    $k_all_leads = $this->db->where('lead_status_id', $v_leads_status->lead_status_id)->get('tbl_leads')->result();
    if (!empty($v_leads_status->lead_type)) {
        $lead_type = '(' . lang($v_leads_status->lead_type) . ')';
    } else {
        $lead_type = null;
    }

    ?>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
               <h4 class="card-title mb-2"><?= $v_leads_status->lead_status?></h4>
                <div data-simplebar style="max-height: 600px;min-height: 600px;">  

                    <div id="<?= str_replace(' ','_',$v_leads_status->lead_status);?>-task" class="pb-1 task-list" data-leadstatus-id="<?= $v_leads_status->lead_status_id;?>">
                        <?php if (!empty($k_all_leads)) {
                        foreach ($k_all_leads as $v_k_leads) {
                            if ($v_k_leads->converted_client_id == 0) {
                                $k_lead_source = $this->db->where('lead_source_id', $v_k_leads->lead_source_id)->get('tbl_lead_source')->row();
                                $total_calls = count($this->db->where('leads_id', $v_k_leads->leads_id)->get('tbl_calls')->result());
                                $total_meetings = count($this->db->where('leads_id', $v_k_leads->leads_id)->get('tbl_mettings')->result());
                                $total_comments = count($this->db->where('leads_id', $v_k_leads->leads_id)->get('tbl_task_comment')->result());
                                $total_tasks = count($this->db->where('leads_id', $v_k_leads->leads_id)->order_by('leads_id', 'DESC')->get('tbl_task')->result());
                                $total_attachment = count($this->db->where('leads_id', $v_k_leads->leads_id)->get('tbl_task_attachment')->result()); 
                            
                        ?>

                        <div class="card task-box" id="<?= str_replace(' ','_',$v_leads_status->lead_status);?>-<?= $v_k_leads->leads_id ?>" data-id="<?= $v_k_leads->leads_id ?>">
                            <div class="card-body">
                                <?php if ( !empty($edited) || !empty($deleted) ) { ?>
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <?php if (!empty($edited)) { ?> 
                                        <a class="dropdown-item edittask-details" href="<?=base_url('admin/leads/index/'.$v_k_leads->leads_id);?>"><?=lang('Edit');?></a>
                                        <?php } ?> 
                                        <?php if (!empty($deleted)) {
                                        echo ajax_anchor(base_url("admin/leads/delete_leads/" . $v_k_leads->leads_id), lang('Delete'), array("class" => "dropdown-item deletetask", "title" => lang('delete'), "data-fade-out-on-success" => "#".str_replace(' ','_',$v_leads_status->lead_status).'-'.$v_k_leads->leads_id));
                                        } ?> 
                                    </div>
                                </div> <!-- end dropdown -->
                                <?php } ?> 

                                <div class="float-end ml-2">
                                    <?=lang('source');?>: <span class="badge rounded-pill badge-soft-danger font-size-12" id="task-status"><?=clear_textarea_breaks($k_lead_source->lead_source);?></span>
                                </div>
                                
                                <div>
                                    <h5 class="font-size-15">
                                        <a href="<?=base_url();?>admin/leads/leads_details/<?=$v_k_leads->leads_id;?>" class="text-dark" id="task-name"><?=clear_textarea_breaks($v_k_leads->contact_name);?></a>
                                    </h5>
                                    <p class="text-muted mb-4"><?=date('d, M Y',strtotime($v_k_leads->created_time));?></p>  
                                </div>
                                <div class="avatar-group float-start task-assigne">
                                <?php  if ($v_k_leads->permission != 'all') {
                                    $get_permission = json_decode($v_k_leads->permission);
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
                                        <a href="<?=base_url();?>admin/leads/view_task_details/<?=$v_k_leads->leads_id;?>" class="d-inline-block">
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
                                    <?php if (!empty($edited)) { ?>
                                    <div class="">
                                        <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?= lang('add_more') ?>" style="margin-left: 20px;">
                                            <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/leads/update_users/<?= $v_k_leads->leads_id ?>" class="text-default d-inline-block" style="margin-top: 3px;"><i class="fa fa-plus"></i></a>
                                        </span>
                                    </div>
                                    <?php } ?>
                                </div>

                                <div class="pull-right text-sm mt-sm" style="display:inline-flex;">
                                    <p class="mb-0 text-muted mr"><i class="fa fa-comments"></i> <?=$total_comments;?></p>
                                    <p class="mb-0 text-muted mr"><i class="fa fa-phone"></i> <?=$total_calls;?></p>
                                    <p class="mb-0 text-muted mr"><i class="fa fa-delicious"></i> <?=$total_meetings;?></p>
                                    <p class="mb-0 text-muted mr"><i class="fa fa-tasks"></i> <?=$total_tasks;?></p>
                                    <p class="mb-0 text-muted"><i class="fa fa-paperclip"></i> <?=$total_attachment;?></p>
                                </div>
                            </div>

                        </div>
                        <!-- end task card -->

                        <?php } } } ?>
                        
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

    dragula([<?php $leads_status = get_order_by('tbl_lead_status', null, 'order_no', true); foreach ($leads_status as $key => $v_leads_status) { ?>document.getElementById("<?= str_replace(' ','_',$v_leads_status->lead_status);?>-task"), <?php } ?> ]).on('drop', function (el, target, source, sibling) {
        var dragTaskId=$(el).attr('data-id');
        var newListID=target.getAttribute('data-leadstatus-id');
        var IDs = [];
        $(target).find('div.task-box').map(function() {
            IDs.push($(this).attr('data-id'));
        }).get();
        var formData = {
            'leads_id': IDs,
        };
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?= base_url()?>admin/leads/change_leads_status/' + newListID+'/'+dragTaskId, // the url where we want to POST
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