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
$created = can_action('55', 'created');
$edited = can_action('55', 'edited');
$deleted = can_action('55', 'deleted');
$kanban = $_SESSION['leads_kanban'];

$uri_segment = $this->uri->segment(4);
if (!empty($kanban)) {
    $k_leads = 'kanban';
} elseif ($uri_segment == 'kanban') {
    $k_leads = 'kanban';
} else {
    $k_leads = 'list';
}

if ($k_leads == 'kanban') {
    $text = 'list';
    $btn = 'primary';
} else {
    $text = 'kanban';
    $btn = 'danger';
}
if (!empty($leads_info)) {
    $leads_id = $leads_info->leads_id;
    $companies_id = $leads_info->companies_id;
} else {
    $leads_id = null;
    $companies_id = null;
}

?>
<?php 
if ($text == 'kanban') {
    $type = $this->uri->segment(4);
    $id = $this->uri->segment(5); ?> 
<?php } ?>
<div class="row mb-3">
    <div class="mb-lg pull-left">
        <div class="pull-left pr-lg ">
            <a href="<?= base_url() ?>admin/leads/index/<?= $text ?>" class="btn btn-xs btn-<?= $btn ?> pull-right " data-bs-toggle="tooltip"  data-bs-placement="top" title="<?= lang('switch_to_' . $text) ?>"><i class="fa fa-undo"> </i><?= ' ' . lang('switch_to_' . $text) ?>
            </a>
              <?php if (!empty($created) || !empty($edited)) { ?>
                    
                        <?php if($leads_id){ ?>   <a href="<?= base_url() ?>admin/leads/index/new" class="btn btn-xs btn-primary pull-left pr-lg mr" data-bs-toggle="tooltip"  data-bs-placement="top" title="<?=   lang('new_leads') ?>"><?=   lang('new_leads') ?>
            </a>  <?php } ?>
                  
                    <?php } ?>
        </div>
        <div class="float-end">
            <?php if (!empty($created) || !empty($edited)) { ?>
            <a class="btn btn-xs btn-danger" href="<?= base_url() ?>admin/leads/import_leads"><?= lang('import_leads') ?></a>
            <?php } ?>
        </div>
    </div>
</div>
<?php if ($k_leads == 'kanban') { ?>
   <?php $this->load->view('admin/leads/leads_kanban'); ?>
<?php } else { ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                 <!-- Tabs within a box -->
                
                <div class="tab-content p-3 text-muted">

                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <div class="d-flex">
                            <div class="dropdown tbl-action mr">
                                <button class="btn btn-primary dropdown-toggle" id="dropdownButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php
                                    echo lang('by_source');
                                    if (!empty($type) && $type == 'by_source') {
                                        $key_name = $this->db->select('lead_source')->where('lead_source_id', $id)->get('tbl_lead_source')->row();
                                        echo ' : ' . $key_name->lead_source;
                                    } ?>
                                    <i class="mdi mdi-chevron-down"></i></button>
                                <div class="dropdown-menu" aria-labelledby="dropdownButton2">
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/leads/index/by_source/all"><?= lang('none'); ?></a>
                                    <?php
                                    $asource_info = get_result('tbl_lead_source');
                                    if (!empty($asource_info)) {
                                        foreach ($asource_info as $v_source) {
                                            ?>
                                    <a class="dropdown-item <?php if (!empty($type) && $type == 'by_source' && $v_source->lead_source_id == $id) {
                                                echo 'class="active"';
                                            } ?> " href="<?= base_url() ?>admin/leads/index/by_source/<?= $v_source->lead_source_id ?>"><?= $v_source->lead_source ?></a>
                                    <?php   } } ?>
                                </div>
                            </div>
                            <div class="dropdown tbl-action">
                                <button class="btn btn-success dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php
                                    echo lang('by_status');
                                    if (!empty($type) && $type == 'by_status') {
                                        $key_name = $this->db->select('lead_status')->where('lead_status_id', $id)->get('tbl_lead_status')->row();
                                        echo ' : ' . $key_name->lead_status;
                                    } ?><i class="mdi mdi-chevron-down"></i></button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/leads/index/by_status/all"><?= lang('none'); ?></a>
                                    <?php
                                    $astatus_info = get_result('tbl_lead_status');
                                    if (!empty($astatus_info)) {
                                        foreach ($astatus_info as $v_status) {
                                            ?>
                                    <a class="dropdown-item <?php if (!empty($type) && $type == 'by_status' && $v_status->lead_status_id == $id) {
                                                echo 'class="active"';
                                            } ?> " href="<?= base_url() ?>admin/leads/index/by_status/<?= $v_status->lead_status_id ?>"><?= lang($v_status->lead_type) . '-' . $v_status->lead_status ?></a>
                                    <?php   } } ?>
                                </div>
                            </div>
                            <?php if (!empty($created) || !empty($edited)) { ?>
                            <div class="dropdown tbl-action ml"> 
                                <button class="btn btn-secondary dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_status') ?><i class="mdi mdi-chevron-down"></i></button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <?php $astatus_info = get_result('tbl_lead_status');
                                        if (!empty($astatus_info)) {
                                            foreach ($astatus_info as $v_status) {
                                                ?>
                                    <a class="dropdown-item" href="#" onclick="multipe_change_status(<?= $v_status->lead_status_id ?>)"><?= lang($v_status->lead_type) . '-' . $v_status->lead_status ?></a>
                                
                                    <?php } } ?>
                                </div>
                                <a class="btn btn-primary " onclick="return checkSelected()" data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/leads/multiple_lead_update_users" title="Users">Update users <span class="caret"></span></a>
                            </div>
                            <?php } ?>
                        </div>

                        <!-- <h4 class="card-title mb-4"><?= lang('all_leads') ?></h4> -->
                        <div class="table-responsive">
                            <table class="table table-striped nowrap w-100 DataTables" id="">
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
                                    <!-- <th><?= lang('lead_name') ?></th> -->
                                    <th><?= lang('contact_name') ?></th>
                                    <?php super_admin_opt_th() ?>
                                    <th><?= lang('email') ?></th>
                                    <th><?= lang('phone') ?></th>
                                    <th><?= lang('lead_status') ?></th>
                                    <th><?= lang('lead_source') ?></th>
                                    <th class="col-sm-2"><?= lang('assigned_to') ?></th>
                                    <th class=""><?= lang('assigned_users_list'); ?></th>
                                    <?php $show_custom_fields = custom_form_table(5, null);
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
                                 $all_field = get_result('tbl_custom_field', array('form_id' => 5));
                                $table = get_row('tbl_form', array('form_id' => 5), 'tbl_name');
                                $astatus_info = get_result('tbl_lead_status');
                                $all_lead_source = get_result('tbl_lead_source');
                                if (!empty($all_leads)):foreach ($all_leads as $v_leads):
                                    if ($v_leads->converted_client_id == 0) {
                                        $user_list=array();
                                        $can_edit = $this->items_model->can_action('tbl_leads', 'edit', array('leads_id' => $v_leads->leads_id));
                                        $can_delete = $this->items_model->can_action('tbl_leads', 'delete', array('leads_id' => $v_leads->leads_id));
                                        ?>
                                        <tr id="leads_<?= $v_leads->leads_id ?>">
                                            <td class="col-sm-1">
                                            <?php if ($can_edit) { ?>
                                                <div class="form-check font-size-16">
                                                    <input class="action-check form-check-input" type="checkbox" data-id="<?= $v_leads->leads_id ?>" style="position: absolute;" name="leads_id[]" value="<?= $v_leads->leads_id ?>">
                                                    <label class="form-check-label"></label>
                                                </div>
                                            <?php } ?>
                                            </td>
                                           
                                             <td>
                                                <a href="<?= base_url() ?>admin/leads/leads_details/<?= $v_leads->leads_id ?>"><?= $v_leads->contact_name ?></a>
                                            </td>
                                          
                                            <?php super_admin_opt_td($v_leads->companies_id) ?>
                                            <td><?= $v_leads->email ?></td>
                                            <td><?= $v_leads->phone ?></td>
                                            <td><?php
                                                if (!empty($v_leads->lead_status_id)) {
                                                   $key = array_search($v_leads->lead_status_id, array_column($astatus_info, 'lead_status_id'));
                                                   $lead_status   = $astatus_info[$key];
                                                    if ($lead_status->lead_type == 'open') {
                                                        $status = "<span class='label label-success'>" . lang($lead_status->lead_type) . "</span>";
                                                    } else {
                                                        $status = "<span class='label label-warning'>" . lang($lead_status->lead_type) . "</span>";
                                                    }
                                                    echo $status . ' ' . $lead_status->lead_status;
                                                }
                                                ?>  
                                            </td>
                                            <td>
                                                <?php
                                                    if (!empty($v_leads->lead_source_id)) {
                                                        // $lead_source = $this->db->where('lead_source_id', $v_leads->lead_source_id)->get('tbl_lead_source')->row();
                                                         $l_key = array_search($v_leads->lead_source_id, array_column($all_lead_source, 'lead_source_id'));
                                                   $lead_source   = $all_lead_source[$l_key];
                                                        if (!empty($lead_source->lead_source)) {
                                                            ?>
                                                            <span class="badge badge-soft-info form-control-static"><?php echo $lead_source->lead_source; ?></span>

                                                            <?php
                                                        }
                                                    }
                                                    ?>

                                            </td>
                                            <td>
                                               <div class="avatar-group">
                                                <?php
                                                if ($v_leads->permission != 'all') {
                                                    $get_permission = json_decode($v_leads->permission);
                                                    if (!empty($get_permission)) :
                                                        $i=$total_users=0;
                                                        foreach ($get_permission as $permission => $v_permission) :
                                                            $profile_info = $this->db->select('fullname,avatar')->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                                        if (!empty($profile_info)) {
                                                           
                                                             $label = '';
                                                           
                                                            array_push($user_list, $profile_info->fullname);
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
                                                            <?php }
                                                            $i=$i+1;
                                                            $total_users=$total_users+1;
                                                            } 
                                                        endforeach;
                                                            if($total_users>2){ ?>
                                                        <div class="avatar-group-item">
                                                        <a href="<?= base_url() ?>admin/leads/leads_details/<?= $v_leads->leads_id ?>" class="d-inline-block">
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
                                                    <a  data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/leads/update_users/<?= $v_leads->leads_id ?>" class="text-default"><i class="fa fa-plus"></i></a>                                                
                                                </span>
                                                <?php } ?>

                                            </td>
                                            <td class="">
                                                <?php 
                                                if(!empty($user_list)){
                                                  echo implode(',', $user_list);
                                                }else{
                                                    if ($v_leads->permission != 'all') {
                                                        echo lang('not_assigned');                                                        
                                                    }else{
                                                        echo lang('everyone');

                                                    }
                                                }
                                                ?>
                                            </td>
                                            <?php $show_custom_fields = custom_form_table_for_mod($all_field, $table, $v_leads->leads_id);
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

                                                <?= btn_view('admin/leads/leads_details/' . $v_leads->leads_id) ?>
                                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                    <?= btn_edit('admin/leads/index/' . $v_leads->leads_id) ?>
                                                <?php }
                                                if (!empty($can_delete) && !empty($deleted)) {
                                                    ?>
                                                    <?php echo ajax_anchor(base_url("admin/leads/delete_leads/" . $v_leads->leads_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#leads_" . $v_leads->leads_id)); ?>
                                                <?php } ?>
                                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                <div class="dropdown tbl-action mt">
                                                    <button class="btn btn-outline-success dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_status') ?><i class="mdi mdi-chevron-down"></i></button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <?php
                                                            
                                                            if (!empty($astatus_info)) {
                                                                foreach ($astatus_info as $v_status) {
                                                                    ?>
                                                        <a class="dropdown-item" href="<?= base_url() ?>admin/leads/change_status/<?= $v_leads->leads_id ?>/<?= $v_status->lead_status_id ?>"><?= lang($v_status->lead_type) . '-' . $v_status->lead_status ?></a>
                                                    
                                                        <?php } } ?>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
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
        if( !confirm("<?=lang('are_you_sure_want_to_update_status_for_selected_leads');?>")){
            return false;
        }
        var leads_id = [];
          $(".action-check:checked").each(function(){
             leads_id.push($(this).val());
        });
        $('#loader-wrapper').show();

        $.ajax({
           url: '<?=site_url('admin/leads/multiple_lead_change_status');?>',
           data: {leads_id:leads_id, status:status },
          
           type: 'POST',
           success: function(data){
           data = jQuery.parseJSON(data);
           console.log(data);
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

   
</script>