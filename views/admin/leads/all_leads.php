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
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 1 ? 'active' : ''; ?>"  href="<?=base_url('admin/leads');?>"><?= lang('all_leads') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)) { ?>
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="<?=base_url('admin/leads/index/new');?>">
                        <?php if($leads_id){ echo lang('edit').' '.lang('lead'); }else{ echo lang('new_leads'); } ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <?php if( $active == 1){ ?> 
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

                        <h4 class="card-title mb-4"><?= lang('all_leads') ?></h4>
                        <div class="table-responsive">
                            <table class="table table-striped nowrap w-100" id="contentTable">
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
                                    
                                    <th><?= lang('contact_name') ?></th>
                                    <?php super_admin_opt_th() ?>
                                    <th><?= lang('email') ?></th>
                                    <th><?= lang('phone') ?></th>
                                    <th><?= lang('lead_status') ?></th>
                                    <th><?= lang('lead_source') ?></th>
                                    <th class="col-sm-2"><?= lang('assigned_to') ?></th>
                                    <th class=""><?= lang('assigned_users_list'); ?></th>
                                    <?php /* $show_custom_fields = custom_form_table(5, null);
                                    if (!empty($show_custom_fields)) {
                                        foreach ($show_custom_fields as $c_label => $v_fields) {
                                            if (!empty($c_label)) {
                                                ?>
                                                <th><?= $c_label ?> </th>
                                            <?php }
                                        }
                                    }
                                    */ ?>
                                    <th class="col-options no-sort"><?= lang('action') ?></th>
                                </tr>
                                </thead>
                                <?php /* ?><tbody>
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
                                </tbody><?php */ ?>
                            </table>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if (!empty($created) || !empty($edited) && ($active == 2)) { ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                        <?php echo form_open(base_url('admin/leads/saved_leads/' . $leads_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <div class="card-body">
                            <h4 class="card-title mb-4"><?php if($leads_id){ echo lang('edit').' '.lang('lead'); }else{ echo lang('new_leads'); } ?></h4>
                            <div class="row">
                                <div class="col-xl-6">
                                    <?php super_admin_form($companies_id, 3, 7) ?>
                                    
                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"><?= lang('lead_source') ?> </label>
                                        <div class="col-xl-7">
                                            <div class="input-group">
                                                <select name="lead_source_id" class="form-control select_box" style="width:85%" id="inputGroup05" aria-describedby="inputGroupAddon05" required="">
                                                    <?php
                                                    $lead_source_info = get_order_by('tbl_lead_source', null, 'lead_source_id');
                                                    if (!empty($lead_source_info)) {
                                                        foreach ($lead_source_info as $v_lead_source) {
                                                            ?>
                                                            <option
                                                                value="<?= $v_lead_source->lead_source_id ?>" <?= (!empty($leads_info) && $leads_info->lead_source_id == $v_lead_source->lead_source_id ? 'selected' : '') ?>><?= $v_lead_source->lead_source ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    $_created = can_action('128', 'created');
                                                    ?>
                                                </select>
                                                <?php if (!empty($_created)) { ?>
                                                    <div class="input-group-text" id="inputGroupAddon05"
                                                         title="<?= lang('new') . ' ' . lang('lead_source') ?>"
                                                         data-bs-toggle="tooltip" data-bs-placement="top">
                                                        <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                           href="<?= base_url() ?>admin/leads/lead_source"><i
                                                                class="fa fa-plus"></i></a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"><?= lang('organization') ?> </label>
                                        <div class="col-xl-7">
                                            <input type="text" class="form-control" value="<?php
                                            if (!empty($leads_info)) {
                                                echo $leads_info->organization;
                                            }
                                            ?>" name="organization">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"><?= lang('email') ?></label>
                                        <div class="col-xl-7">
                                            <input type="email" class="form-control" value="<?php
                                            if (!empty($leads_info)) {
                                                echo $leads_info->email;
                                            }
                                            ?>" name="email" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"><?= lang('mobile') ?><span
                                                class="text-danger">*</span>
                                        </label>
                                        <div class="col-xl-7 mb">
                                            <input type="text" min="0" required="" class="form-control" value="<?php
                                            if (!empty($leads_info)) {
                                                echo $leads_info->mobile;
                                            }
                                            ?>" name="mobile"/>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"><?= lang('city') ?> </label>
                                        <div class="col-xl-7">
                                            <input type="text" class="form-control" value="<?php
                                            if (!empty($leads_info)) {
                                                echo $leads_info->city;
                                            }
                                            ?>" name="city">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"><?= lang('country') ?></label>
                                        <div class="col-xl-7">
                                            <select name="country" class="form-control person select_box"
                                                    style="width: 100%">
                                                <optgroup label="Default Country">
                                                    <?php if (!empty($leads_info->country)) { ?>
                                                        <option
                                                            value="<?= $leads_info->country ?>"><?= $leads_info->country ?></option>
                                                    <?php } else { ?>
                                                        <option
                                                            value="<?= $this->config->item('company_country') ?>"><?= $this->config->item('company_country') ?></option>
                                                    <?php } ?>
                                                </optgroup>
                                                <optgroup label="<?= lang('other_countries') ?>">
                                                    <?php
                                                    $countries = $this->db->get('tbl_countries')->result();
                                                    if (!empty($countries)): foreach ($countries as $country):
                                                        ?>
                                                        <option
                                                            value="<?= $country->value ?>"><?= $country->value ?></option>
                                                        <?php
                                                    endforeach;
                                                    endif;
                                                    ?>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"><?= lang('skype_id') ?> </label>
                                        <div class="col-xl-7">
                                            <input type="text" class="form-control" value="<?php
                                            if (!empty($leads_info)) {
                                                echo $leads_info->skype;
                                            }
                                            ?>" name="skype">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"><?= lang('lead_name') ?></label>
                                        <div class="col-xl-7">
                                            <input type="text" class="form-control" value="<?php
                                            if (!empty($leads_info)) {
                                                echo $leads_info->lead_name;
                                            }
                                            ?>" name="lead_name" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"><?= lang('lead_status') ?> </label>
                                        <div class="col-xl-7">
                                            <div class="input-group">
                                                <select name="lead_status_id" class="form-control select_box" style="width:85%"  id="inputGroup04" aria-describedby="inputGroupAddon04"  required="">
                                                    <?php

                                                    if (!empty($status_info)) {
                                                        foreach ($status_info as $type => $v_leads_status) {
                                                            if (!empty($v_leads_status)) {
                                                                ?>
                                                                <optgroup label="<?= lang($type) ?>">
                                                                    <?php foreach ($v_leads_status as $v_l_status) { ?>
                                                                        <option
                                                                            value="<?= $v_l_status->lead_status_id ?>" <?php
                                                                        if (!empty($leads_info->lead_status_id)) {
                                                                            echo $v_l_status->lead_status_id == $leads_info->lead_status_id ? 'selected' : '';
                                                                        }
                                                                        ?>><?= $v_l_status->lead_status ?></option>
                                                                    <?php } ?>
                                                                </optgroup>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    $created = can_action('127', 'created');
                                                    ?>
                                                </select>
                                                <?php if (!empty($created)) { ?>
                                                    <div class="input-group-text" id="inputGroupAddon04"
                                                         title="<?= lang('new') . ' ' . lang('lead_status') ?>"
                                                         data-bs-toggle="tooltip" data-bs-placement="top">
                                                        <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                           href="<?= base_url() ?>admin/leads/lead_status"><i
                                                                class="fa fa-plus"></i></a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3"> 
                                        <label class="col-xl-3 col-form-label"><?= lang('contact_name') ?> <span
                                                class="text-danger">*</span></label>
                                        <div class="col-xl-7">
                                            <input type="text" class="form-control" value="<?php
                                            if (!empty($leads_info)) {
                                                echo $leads_info->contact_name;
                                            }
                                            ?>" name="contact_name" required="">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                
                                        <label class="col-xl-3 col-form-label"><?= lang('phone') ?></label>
                                        <div class="col-xl-7">
                                            <input type="text" class="form-control" value="<?php
                                            if (!empty($leads_info)) {
                                                echo $leads_info->phone;
                                            }
                                            ?>" name="phone">
                                        </div>
                                    </div>

                                    <!-- End discount Fields -->
                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"><?= lang('address') ?> </label>
                                        <div class="col-xl-7">
                                            <textarea name="address" class="form-control" rows="2"><?php
                                                if (!empty($leads_info)) {
                                                    echo $leads_info->address;
                                                }
                                            ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"><?= lang('state') ?> </label>
                                        <div class="col-xl-7">
                                            <input type="text" class="form-control" value="<?php
                                            if (!empty($leads_info)) {
                                                echo $leads_info->state;
                                            }
                                            ?>" name="state">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"><?= lang('facebook_profile_link') ?> </label>
                                        <div class="col-xl-7">
                                            <input type="text" class="form-control" value="<?php
                                            if (!empty($leads_info)) {
                                                echo $leads_info->facebook;
                                            }
                                            ?>" name="facebook">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-xl-3 col-form-label"><?= lang('twitter_profile_link') ?> </label>
                                        <div class="col-xl-7">
                                            <input type="text" class="form-control" value="<?php
                                            if (!empty($leads_info)) {
                                                echo $leads_info->twitter;
                                            }
                                            ?>" name="twitter">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-9">
                                    <div class="row mb-3" id="border-none">
                                        <label class="col-xl-2 col-form-label"><?= lang('short_note') ?> </label>
                                        <div class="col-xl-10">
                                            <textarea name="notes" id="elm1" class="form-control textarea"><?php
                                            if (!empty($leads_info)) {
                                                echo $leads_info->notes;
                                            }
                                            ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-9">
                                    <?php
                                    if (!empty($leads_info)) {
                                        $leads_id = $leads_info->leads_id;
                                    } else {
                                        $leads_id = null;
                                    }
                                    ?>
                                    <?= custom_form_Fields(5, $leads_id, true); ?>
                                    <div class="row mb-3" id="border-none">
                                        <label class="col-xl-2 col-form-label"><?= lang('assined_to') ?> <span
                                                    class="required">*</span></label>
                                        <div class="col-xl-7">
                                            <div class="form-check form-radio-outline form-radio-primary mb-3">
                                                <input id="everyone" <?php
                                                    if (!empty($leads_info) && $leads_info->permission == 'all') {
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
                                                    if (!empty($leads_info) && $leads_info->permission != 'all') {
                                                        echo 'checked';
                                                    } elseif (empty($leads_info)) {
                                                        echo 'checked';
                                                    } ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                                                <label class="form-check-label" for="custom_permission">
                                                    <?= lang('custom_permission') ?>
                                                    <i  title="<?= lang('permission_for_customization') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3 <?php
                                        if (!empty($leads_info) && $leads_info->permission != 'all') {
                                            echo 'show';
                                        }
                                        ?>" id="permission_user_1">
                                        <label class="col-xl-2 col-form-label"><?= lang('select') . ' ' . lang('users') ?> <span class="required">*</span></label>
                                        <div class="col-xl-5">
                                            <?php
                                            if (!empty($assign_user)) { ?>
                                            <input type="text" name="search_assigned_user" value="" placeholder="<?=lang('search_by').' '.lang('username'); ?>" class="form-control mb-4 search_assigned_user" id="search_assigned_user" autocomplete="off">
                                            <div data-simplebar style="max-height: 250px;">  

                                                <?php foreach ($assign_user as $key => $v_user) {

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
                                                            if (!empty($leads_info) && $leads_info->permission != 'all') {
                                                                $get_permission = json_decode($leads_info->permission);
                                                                foreach ($get_permission as $user_id => $v_permission) {
                                                                    if ($user_id == $v_user->user_id) {
                                                                        echo 'checked';
                                                                    }
                                                                }

                                                            }
                                                            ?>  value="<?= $v_user->user_id ?>" name="assigned_to[]" class="form-check-input" id ="user_<?= $v_user->user_id ?>" data-name="<?= $v_user->username;?>">
                                                    <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?>
                                                    </label>
                                                </div>
                                                <div class="action_1 p
                                                        <?php
                                                            if (!empty($leads_info) && $leads_info->permission != 'all') {
                                                                $get_permission = json_decode($leads_info->permission);

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
                                                                if (!empty($leads_info) && $leads_info->permission != 'all') {
                                                                    $get_permission = json_decode($leads_info->permission);

                                                                    foreach ($get_permission as $user_id => $v_permission) {
                                                                        if ($user_id == $v_user->user_id) {
                                                                            if (in_array('edit', $v_permission)) {
                                                                                echo 'checked';
                                                                            };

                                                                        }
                                                                    }

                                                                } ?> type="checkbox" value="edit" name="action_<?= $v_user->user_id ?>[]" class="form-check-input">
                                                        <label class="form-check-label" for="edit_<?= $v_user->user_id ?>"><?= lang('edit') ?></label>
                                                    </div>
                                                    <div class="form-check form-check-primary mb-3 mr">         
                                                        <input <?php if (!empty($disable)) { echo 'disabled' . ' ' . 'checked'; } ?> id="delete_<?= $v_user->user_id ?>"
                                                                <?php

                                                                if (!empty($leads_info) && $leads_info->permission != 'all') {
                                                                    $get_permission = json_decode($leads_info->permission);
                                                                    foreach ($get_permission as $user_id => $v_permission) {
                                                                        if ($user_id == $v_user->user_id) {
                                                                            if (in_array('delete', $v_permission)) {
                                                                                echo 'checked';
                                                                            };
                                                                        }
                                                                    }

                                                                }
                                                                ?>  name="action_<?= $v_user->user_id ?>[]"  type="checkbox"  value="delete" class="form-check-input">
                                                        <label class="form-check-label" for="delete_<?= $v_user->user_id ?>"><?= lang('delete') ?></label>
                                                    </div>
                                                    
                                                    <input id="<?= $v_user->user_id ?>" type="hidden" name="action_<?= $v_user->user_id ?>[]" value="view">
                                                </div>
                                                <?php  } ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                        
                                    <div class="row mb-3">
                                        <label class="col-xl-2 col-form-label"></label>
                                        <?php if (empty($leads_info->converted_client_id) || $leads_info->converted_client_id == 0) { ?>
                                            <div class="col-lg-5">
                                                <button type="submit" class="btn btn-xs btn-primary"><?= lang('updates') ?></button>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <?php } ?>
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

<!-- Script -->
 <script type="text/javascript">
     $(document).ready(function(){
        $('#contentTable').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
             'url':'<?=base_url()?>admin/datatable/leads?filter=<?=@$filterBy;?>'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "leads_"+iDisplayIndex);
            return nRow;
          },
          'columns': [
             { data: 'checkbox' },
             { data: 'contact_name' },
			 <?php if (is_company_column_ag()) { ?>
             { data: 'companies' },
			 <?php } ?>
             { data: 'email' },
             { data: 'phone' },
             { data: 'lead_status' },
             { data: 'lead_source' },
             { data: 'assigned_to' },
             { data: 'assigned_users_list' },
             // { data: 'label' },
             { data: 'action' },
          ]
        });
     });
 </script>