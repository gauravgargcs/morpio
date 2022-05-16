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
$all_customer_group = get_result('tbl_customer_group', array('type' => 'client'));
$mdate = date('Y-m-d H:i');
$last_7_days = date('Y-m-d H:i', strtotime('today - 7 days'));
$all_goal_tracking = $this->client_model->get_permission('tbl_goal_tracking');

$all_goal = 0;
$wthout_all_goal = 0;
$direct_complete_achivement = 0;
$without_complete_achivement = 0;
if (!empty($all_goal_tracking)) {
    foreach ($all_goal_tracking as $v_goal_track) {
        $goal_achieve = $this->client_model->get_progress($v_goal_track, true);

        if ($v_goal_track->goal_type_id == 11) {

            if ($v_goal_track->end_date <= $mdate) { // check today is last date or not

                if ($v_goal_track->email_send == 'no') {// check mail are send or not
                    if ($v_goal_track->achievement <= $goal_achieve['achievement']) {
                        if ($v_goal_track->notify_goal_achive == 'on') {// check is notify is checked or not check
                            $this->client_model->send_goal_mail('goal_achieve', $v_goal_track);
                        }
                    } else {
                        if ($v_goal_track->notify_goal_not_achive == 'on') {// check is notify is checked or not check
                            $this->client_model->send_goal_mail('goal_not_achieve', $v_goal_track);
                        }
                    }
                }
            }
            $all_goal += $v_goal_track->achievement;
            $direct_complete_achivement += $goal_achieve['achievement'];
        }
        if ($v_goal_track->goal_type_id == 10) {

            if ($v_goal_track->end_date <= $mdate) { // check today is last date or not

                if ($v_goal_track->email_send == 'no') {// check mail are send or not
                    if ($v_goal_track->achievement <= $goal_achieve['achievement']) {
                        if ($v_goal_track->notify_goal_achive == 'on') {// check is notify is checked or not check
                            $this->client_model->send_goal_mail('goal_achieve', $v_goal_track);
                        }
                    } else {
                        if ($v_goal_track->notify_goal_not_achive == 'on') {// check is notify is checked or not check
                            $this->client_model->send_goal_mail('goal_not_achieve', $v_goal_track);
                        }
                    }
                }
            }
            $wthout_all_goal += $v_goal_track->achievement;
            $without_complete_achivement += $goal_achieve['achievement'];
        }
    }
}
// 30 days before

for ($iDay = 7; $iDay >= 0; $iDay--) {
    $date = date('Y-m-d', strtotime('today - ' . $iDay . 'days'));
    $where = array('date_added >=' => $date . " 00:00:00", 'date_added <=' => $date . " 23:59:59");
    $invoice_result[$date] = count(get_result('tbl_client', $where));
}

$all_terget_achievement = get_result('tbl_goal_tracking', array('goal_type_id' => 11, 'start_date >=' => $last_7_days, 'end_date <=' => $mdate));
$without_terget_achievement = get_result('tbl_goal_tracking', array('goal_type_id' => 10, 'start_date >=' => $last_7_days, 'end_date <=' => $mdate));
if (!empty($all_terget_achievement)) {
    $all_terget_achievement = $all_terget_achievement;
} else {
    $all_terget_achievement = array();
}
if (!empty($without_terget_achievement)) {
    $without_terget_achievement = $without_terget_achievement;
} else {
    $without_terget_achievement = array();
}
$terget_achievement = array_merge($all_terget_achievement, $without_terget_achievement);
$total_terget = 0;
if (!empty($terget_achievement)) {
    foreach ($terget_achievement as $v_terget) {
        $total_terget += $v_terget->achievement;
    }
}

$curency = $this->client_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
if (!empty($client_info)) {
    $clients_id = $client_info->client_id;
    $companies_id = $client_info->companies_id;
} else {
    $clients_id = null;
    $companies_id = null;
}
if ($this->session->userdata('user_type') == 1) {
$margin = 'margin-bottom:30px';
?>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('without_converted') ?></p>
                            <h5 class="mb-0"><?= $all_goal ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('completed') . ' ' . lang('achievements') ?></p>
                            <h5 class="mb-0"><?= $direct_complete_achivement ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('converted_client') ?></p>
                            <h5 class="mb-0"><?= $wthout_all_goal ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('completed') . ' ' . lang('achievements') ?></p>
                            <h5 class="mb-0"><?= $without_complete_achivement ?></h5>
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
                    $total_goal = $all_goal + $wthout_all_goal;
                    $complete_achivement = $direct_complete_achivement + $without_complete_achivement;
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

$id = $this->uri->segment(5);
$search_by = $this->uri->segment(4);
$created = can_action('4', 'created');
$edited = can_action('4', 'edited');
$deleted = can_action('4', 'deleted');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body bordered">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 1 ? 'active' : '' ?>" href="<?= base_url().'admin/client/manage_client' ?>"><?= lang('client_list') ?></a>
                    </li>
            
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 2 ? 'active' : '' ?>" href="#new_client" data-bs-toggle="tab">
                        <?php if($clients_id){ echo lang('edit').' '.lang('client'); }else{ echo lang('new_client'); } ?></a>
                    </li>
                    <li class="nav-item waves-light"><a class="nav-link" href="<?= base_url() ?>admin/client/import"><?= lang('import') . ' ' . lang('client') ?></a></li>
                    <?php } ?>
                </ul>

                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane  <?= $active == 1 ? 'active' : '' ?>" id="client_list">
                        <div class="d-flex">
                            <?php if (!empty($edited)) { ?>
                            <div class="btn-group dropdown tbl-action">     
                                <a class="btn btn-primary " onclick="return checkSelected()" data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/client/multiple_client_update_users" title="Users">Update users <span class="caret"></span></a>
                            </div>
                            <?php if ($this->session->userdata('user_type') == 1) { ?>
                            <div class="btn-group dropdown tbl-action ml">     
                                <a class="btn btn-danger " href="#" onclick="multiple_delete_users()" title="Users"><?=lang('delete').' '.lang('users');?><span class="caret"></span></a>
                            </div>
                            <?php } ?>
                            <div class="btn-group dropdown tbl-action ml"> 
                                <button class="btn btn-success dropdown-toggle" id="dropdownMenuButton4" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_status') ?><i class="mdi mdi-chevron-down"></i></button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
                                    <?php $astatus_info = get_result('tbl_lead_status');
                                        if (!empty($astatus_info)) {
                                            foreach ($astatus_info as $v_status) {
                                                ?>
                                    <a class="dropdown-item" href="#" onclick="multiple_status_change_status('<?= $v_status->lead_status_id ?>')"><?= lang($v_status->lead_type) . '-' . $v_status->lead_status ?></a>
                                    <?php } } ?>
                                </div>
                            </div>
                            <div class="btn-group dropdown tbl-action ml">     
                                <button class="btn btn-secondary dropdown-toggle" id="dropdownMenuButton5" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_source') ?><i class="mdi mdi-chevron-down"></i></button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton5">
                                    <?php $asource_info = get_result('tbl_lead_source');
                                    if (!empty($asource_info)) {
                                        foreach ($asource_info as $v_source) {  ?>
                                    <a class="dropdown-item" href="#" onclick="multiple_source_change_status('<?= $v_source->lead_source_id ?>')"><?= $v_source->lead_source ?></a>
                                    <?php } } ?>
                                </div>

                            </div>
                            
                            <?php } ?>

                            <div class="btn-group dropstart dropdown tbl-action ml">
                                <button class="btn btn-info dropdown-toggle" id="dropdownButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php
                                    echo lang('by_source');
                                    if (!empty($type) && $type == 'by_source') {
                                        $key_name = $this->db->where('lead_source_id', $id)->get('tbl_lead_source')->row();
                                        echo ' : ' . $key_name->lead_source;
                                    } ?>
                                    <i class="mdi mdi-chevron-down"></i></button>
                                <div class="dropdown-menu" aria-labelledby="dropdownButton2">
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/client/manage_client/by_source/all"><?= lang('none'); ?></a>
                                    <?php
                                    $asource_info = get_result('tbl_lead_source');
                                    if (!empty($asource_info)) {
                                        foreach ($asource_info as $v_source) {
                                            ?>
                                    <a class="dropdown-item <?php if (!empty($type) && $type == 'by_source' && $v_source->lead_source_id == $id) {
                                                echo 'class="active"';
                                            } ?> " href="<?= base_url() ?>admin/client/manage_client/by_source/<?= $v_source->lead_source_id ?>"><?= $v_source->lead_source ?></a>
                                    <?php   } } ?>
                                </div>
                            </div>
                            <div class="btn-group dropup dropdown tbl-action ml">
                                <button class="btn btn-info dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php
                                    echo lang('by_status');
                                    if (!empty($type) && $type == 'by_status') {
                                        $key_name = $this->db->where('lead_status_id', $id)->get('tbl_lead_status')->row();
                                        echo ' : ' . $key_name->lead_status;
                                    } ?><i class="mdi mdi-chevron-down"></i></button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/client/manage_client/by_status/all"><?= lang('none'); ?></a>
                                    <?php
                                    $astatus_info = get_result('tbl_lead_status');
                                    if (!empty($astatus_info)) {
                                        foreach ($astatus_info as $v_status) {
                                            ?>
                                    <a class="dropdown-item <?php if (!empty($type) && $type == 'by_status' && $v_status->lead_status_id == $id) {  echo 'class="active"';  } ?> " href="<?= base_url() ?>admin/client/manage_client/by_status/<?= $v_status->lead_status_id ?>"><?= lang($v_status->lead_type) . '-' . $v_status->lead_status ?></a>
                                    <?php   } } ?>
                                </div>
                            </div>
                            <?php if (count($all_customer_group) > 0) { ?>
                            <div class="btn-group dropup dropdown tbl-action ml" data-bs-toggle="tooltip" data-bs-title="<?php echo lang('filter_by'); ?>">
                                <button class="btn btn-xs btn-info dropdown-toggle" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false"><?php echo lang('customer_group'); ?><i class="mdi mdi-chevron-down"></i></button>
                                    
                                <div class="dropdown-menu dropdown-menu-end" style="" aria-labelledby="dropdownMenuButton3">
                                    <a class="<?php if (empty($search_by)) { echo 'active'; } ?> dropdown-item"  href="<?= base_url() ?>admin/client/manage_client"><?php echo lang('all'); ?></a>
                                    <div class="dropdown-divider"></div>
                        
                                    <?php foreach ($all_customer_group as $group) { ?>
                                        <a class="<?php if (!empty($id)) { if ($search_by == 'group') { if ($id == $group->customer_group_id) { echo 'active';  } } } ?> dropdown-item" href="<?= base_url() ?>admin/client/manage_client/group/<?php echo $group->customer_group_id; ?>"><?php echo $group->customer_group; ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>


                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped w-100" style="table-layout: auto;" id="manage_client_datatable">
                                <thead>
                                <tr>
                                    <th data-check-all>
                                    <?php if (!empty($edited)) { ?>
                                        <div class="form-check font-size-16 check-all">
                                            <input type="checkbox" id="parent_present" class="form-check-input">
                                            <label for="parent_present" class="toggle form-check-label"></label>
                                        </div>
                                    <?php } ?>
                                    </th>
                                    <th class="col-sm-1"><?= lang('name') ?> </th>
                                    <?php super_admin_opt_th() ?>
                                    <th class="col-sm-1"><?= lang('contacts') ?></th>
                                    <th class="hidden-sm col-sm-1"><?= lang('primary_contact') ?></th>
                                    <th class="col-sm-1"><?= lang('projects') ?> </th>
                                    <th class="col-sm-1"><?= lang('due_amount') ?> </th>
                                    <th class="col-sm-1"><?= lang('received_amount') ?> </th>
                                    <th class="col-sm-1"><?= lang('expense') ?> </th>
                                    <th class="col-sm-1"><?= lang('group') ?> </th>
                                    <th class="col-sm-1"><?= lang('status') ?> </th>
                                    <th class="col-sm-1"><?= lang('source') ?> </th>
                                    <th class="col-sm-2"><?= lang('assigned_to') ?></th>
                                    <th class=""><?= lang('assigned_users_list'); ?></th>
                                    <?php $show_custom_fields = custom_form_table(12, null);
                                    if (!empty($show_custom_fields)) {
                                        foreach ($show_custom_fields as $c_label => $v_fields) {
                                            if (!empty($c_label)) {
                                                ?>
                                                <th><?= $c_label ?> </th>
                                            <?php }
                                        }
                                    }
                                    ?>
                                    <th class="hidden-print col-sm-1"><?= lang('action') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($all_client_info)) {
                                    foreach ($all_client_info as $client_details) {
                                        $user_list=array();
                                        $client_transactions = get_sum('tbl_transactions', array('paid_by' => $client_details->client_id), 'amount');
                                        $customer_group = get_row('tbl_customer_group', array('customer_group_id' => $client_details->customer_group_id));
                                        $client_outstanding = $this->invoice_model->client_outstanding($client_details->client_id);
                                        $client_currency = $this->invoice_model->client_currency_sambol($client_details->client_id);
                                        if (!empty($client_currency)) {
                                            $cur = $client_currency->symbol;
                                        } else {
                                            $currency = get_row('tbl_currencies', array('code' => config_item('default_currency')));
                                            $cur = $currency->symbol;
                                        }
                                        ?>
                                        <tr>
                                            <td class="col-sm-1">
                                            <?php if (!empty($edited)) { ?>
                                                <div class="form-check font-size-16">
                                                    <input class="action-check form-check-input" type="checkbox" data-id="<?= $client_details->client_id ?>" style="position: absolute;" name="client_id[]" value="<?= $client_details->client_id ?>">
                                                    <label class="form-check-label"></label>
                                                </div>
                                            <?php } ?>
                                            </td>
                                            <td class="col-sm-1">
                                                <a href="<?= base_url() ?>admin/client/client_details/<?= $client_details->client_id ?>"
                                                   class="text-info">
                                                    <?= $client_details->name ?></a></td>
                                            <?php super_admin_opt_td($client_details->companies_id) ?>
                                            <td><span class="badge badge-soft-success" data-bs-toggle="tooltip"
                                                      data-palcement="top"
                                                      title="<?= lang('contacts') ?>"><?= $this->client_model->count_rows('tbl_account_details', array('company' => $client_details->client_id)) ?></span>
                                            </td>
                                            <td class="hidden-sm"><?php
                                                if ($client_details->primary_contact != 0) {
                                                    $contacts = $client_details->primary_contact;
                                                } else {
                                                    $contacts = NULL;
                                                }
                                                $primary_contact = $this->client_model->check_by(array('account_details_id' => $contacts), 'tbl_account_details');
                                                if ($primary_contact) {
                                                    echo $primary_contact->fullname;
                                                }
                                                ?></td>
                                            <td><?= count(get_result('tbl_project', array('client_id' => $client_details->client_id))) ?></td>
                                            <td><?php
                                                if ($client_outstanding > 0) {
                                                    echo display_money($client_outstanding, $cur);
                                                } else {
                                                    echo '0.00';
                                                }
                                                ?></td>
                                            <td><?= display_money($this->client_model->client_paid($client_details->client_id), $cur); ?></td>
                                            <td><?php
                                                if ($client_transactions[0]->amount > 0) {
                                                    echo display_money($client_transactions[0]->amount, $cur);
                                                } else {
                                                    echo '0.00';
                                                }
                                                ?></td>

                                            <td><span class="badge badge-soft-default"><?php
                                                    if (!empty($customer_group->customer_group)) {
                                                        echo $customer_group->customer_group;
                                                    }
                                                    ?></span></td>
                                            <td><?php
                                                if (!empty($client_details->status)) {
                                                    $lead_status = $this->db->where('lead_status_id', $client_details->status)->get('tbl_lead_status')->row();

                                                    if ($lead_status->lead_type == 'open') {
                                                        $status = "<span class='badge badge-soft-success'>" . lang($lead_status->lead_type) . "</span>";
                                                    } else {
                                                        $status = "<span class='badge badge-soft-warning'>" . lang($lead_status->lead_type) . "</span>";
                                                    }
                                                    echo $status . ' ' . $lead_status->lead_status;
                                                }
                                                ?>  
                                            </td>
                                            <td>
                                                <?php
                                                    if (!empty($client_details->source)) {
                                                        $lead_source = $this->db->where('lead_source_id', $client_details->source)->get('tbl_lead_source')->row();
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
                                                if ($client_details->permission != 'all') {
                                                    $get_permission = json_decode($client_details->permission);
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
                                                        <a href="<?= base_url() ?>admin/client/client_details/<?= $client_details->client_id ?>" class="d-inline-block">
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
                                                <?php if (!empty($created) && !empty($edited)) { ?>
                                                <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?= lang('add_more') ?>" class="mt-2">
                                                    <a  data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/client/update_users/<?= $client_details->client_id ?>" class="text-default"><i class="fa fa-plus"></i></a>                                                
                                                </span>
                                                <?php } ?>

                                            </td>
                                            <td class="">
                                                <?php 
                                                if(!empty($user_list)){
                                                  echo implode(',', $user_list);
                                                }else{
                                                    if ($client_details->permission != 'all') {
                                                        echo lang('not_assigned');                                                        
                                                    }else{
                                                        echo lang('everyone');

                                                    }
                                                }
                                                ?>
                                            </td>
                                            <?php $show_custom_fields = custom_form_table(12, $client_details->client_id);
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
                                                <?php if (!empty($edited)) { ?>
                                                    <?php echo btn_edit('admin/client/manage_client/' . $client_details->client_id) ?>
                                                <?php }
                                                if (!empty($deleted)) {
                                                    ?>
                                                    <?php echo btn_delete('admin/client/delete_client/' . $client_details->client_id) ?>
                                                <?php } ?>
                                                <?php echo btn_view('admin/client/client_details/' . $client_details->client_id) ?>
                                            </td>
                                        </tr>
                                        <?php
                                    } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($created) || !empty($edited)) {  ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="new_client" style="position: relative;">
                        <?php echo form_open(base_url('admin/client/save_client/' . $clients_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <div class="card-body manage_client">
                            <h4 class="card-title mb-4"><?php if($clients_id){ echo lang('edit').' '.lang('client'); }else{ echo lang('new_client'); } ?></h4>
                            <div id="basic-example">
                                <!-- ************** general *************-->
                                <h3><?= lang('general') ?></h3>
                                <section>
                                    <div class="row">                                        
                                        <?php super_admin_form_section($companies_id, 6) ?>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('company_name') ?><span class="text-danger"> *</span></label>
                                                <input type="text" class="form-control" required="" value="<?php if (!empty($client_info->name)) { echo $client_info->name;  } ?>" name="name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('company_email') ?><span class="text-danger"> *</span></label>
                                                                                                
                                                <input type="email" class="form-control" required=""
                                                       value="<?php
                                                       if (!empty($client_info->email)) {
                                                           echo $client_info->email;
                                                       }
                                                       ?>" name="email">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('company_vat') ?></label>
                                                                                                
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($client_info->vat)) {
                                                    echo $client_info->vat;
                                                }
                                                ?>" name="vat">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('customer_group') ?></label>
                                                                                                
                                                <div class="input-group">
                                                    <select name="customer_group_id"
                                                            class="form-control select_box"
                                                            style="width: 84%" id="customer_group_inputGroup" aria-describedby="customer_group_inputGroupAddon">
                                                        <?php
                                                        if (!empty($all_customer_group)) {
                                                            foreach ($all_customer_group as $customer_group) : ?>
                                                                <option
                                                                    value="<?= $customer_group->customer_group_id ?>"<?php
                                                                if (!empty($client_info->customer_group_id) && $client_info->customer_group_id == $customer_group->customer_group_id) {
                                                                    echo 'selected';
                                                                } ?>
                                                                ><?= $customer_group->customer_group; ?></option>
                                                            <?php endforeach;
                                                        }
                                                        $created = can_action('125', 'created');
                                                        ?>
                                                    </select>
                                                    <?php if (!empty($created)) { ?>
                                                        <div class="input-group-text" id="customer_group_inputGroupAddon"
                                                             title="<?= lang('new') . ' ' . lang('customer_group') ?>"
                                                             data-bs-toggle="tooltip" data-bs-placement="top">
                                                            <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                               href="<?= base_url() ?>admin/client/customer_group"><i
                                                                    class="fa fa-plus"></i></a>
                                                        </div>
                                                    
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('language') ?></label>
                                                                                                
                                                <select name="language" class="form-control select_box"
                                                        style="width: 100%">
                                                    <?php
                                                    foreach ($languages as $lang) : ?>
                                                        <option
                                                            value="<?= $lang->name ?>"<?php
                                                        if (!empty($client_info->language) && $client_info->language == $lang->name) {
                                                            echo 'selected';
                                                        } elseif (empty($client_info->language) && $this->config->item('language') == $lang->name) {
                                                            echo 'selected';
                                                        } ?>
                                                        ><?= ucfirst($lang->name) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('currency') ?></label>
                                                                                                
                                                <select name="currency" class="form-control select_box"
                                                        style="width: 100%">

                                                    <?php if (!empty($currencies)): foreach ($currencies as $currency): ?>
                                                        <option
                                                            value="<?= $currency->code ?>"
                                                            <?php
                                                            if (!empty($client_info->currency) && $client_info->currency == $currency->code) {
                                                                echo 'selected';
                                                            } elseif (empty($client_info->currency) && $this->config->item('default_currency') == $currency->code) {
                                                                echo 'selected';
                                                            } ?>
                                                        ><?= $currency->name ?></option>
                                                        <?php
                                                    endforeach;
                                                    endif;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('short_note') ?></label>
                                                                                                
                                                <textarea class="form-control" name="short_note"><?php
                                                    if (!empty($client_info->short_note)) {
                                                        echo $client_info->short_note;
                                                    }
                                                    ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="col-form-label"><?= lang('status') ?> </label>
                                                <div class="input-group">
                                                    <select name="status" class="form-control select_box" style="width:85%"  id="inputGroup04" aria-describedby="inputGroupAddon04"  required="">
                                                        <?php

                                                        if (!empty($status_info)) {
                                                            foreach ($status_info as $type => $v_leads_status) {
                                                                if (!empty($v_leads_status)) {
                                                                    ?>
                                                                    <optgroup label="<?= lang($type) ?>">
                                                                        <?php foreach ($v_leads_status as $v_l_status) { ?>
                                                                            <option
                                                                                value="<?= $v_l_status->lead_status_id ?>" <?php
                                                                            if (!empty($client_info->status)) {
                                                                                echo $v_l_status->lead_status_id == $client_info->status ? 'selected' : '';
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
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="col-form-label"><?= lang('source') ?> </label>
                                                <div class="input-group">
                                                    <select name="source" class="form-control select_box" style="width:85%" id="inputGroup05" aria-describedby="inputGroupAddon05" required="">
                                                        <?php
                                                        $lead_source_info = get_order_by('tbl_lead_source', null, 'lead_source_id');
                                                        if (!empty($lead_source_info)) {
                                                            foreach ($lead_source_info as $v_lead_source) {
                                                                ?>
                                                                <option
                                                                    value="<?= $v_lead_source->lead_source_id ?>" <?= (!empty($client_info) && $client_info->source == $v_lead_source->lead_source_id ? 'selected' : '') ?>><?= $v_lead_source->lead_source ?></option>
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
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3" id="border-none">
                                                <label class="col-form-label"><?= lang('assined_to') ?> <span class="required">*</span></label>
                                                <div class="form-check form-radio-outline form-radio-primary mb-3">
                                                    <input id="everyone" <?php
                                                        if (!empty($client_info) && $client_info->permission == 'all') {
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
                                                        if (!empty($client_info) && $client_info->permission != 'all') {
                                                            echo 'checked';
                                                        } elseif (empty($client_info)) {
                                                            echo 'checked';
                                                        } ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                                                    <label class="form-check-label" for="custom_permission">
                                                        <?= lang('custom_permission') ?>
                                                        <i  title="<?= lang('permission_for_customization') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3 <?php
                                                if (!empty($client_info) && $client_info->permission != 'all') {
                                                    echo 'show';
                                                }
                                                ?>" id="permission_user_1">
                                                <label class="col-form-label"><?= lang('select') . ' ' . lang('users') ?> <span class="required">*</span></label>
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
                                                                    if (!empty($client_info) && $client_info->permission != 'all') {
                                                                        $get_permission = json_decode($client_info->permission);
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
                                                                    if (!empty($client_info) && $client_info->permission != 'all') {
                                                                        $get_permission = json_decode($client_info->permission);

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
                                                                        if (!empty($client_info) && $client_info->permission != 'all') {
                                                                            $get_permission = json_decode($client_info->permission);

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

                                                                        if (!empty($client_info) && $client_info->permission != 'all') {
                                                                            $get_permission = json_decode($client_info->permission);
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
                                    </div>
                                    <?php
                                    if (!empty($client_info)) {
                                        $client_id = $client_info->client_id;
                                    } else {
                                        $client_id = null;
                                    }
                                    ?>
                                    <?= custom_form_Fields(12, $client_id,'6'); ?>

                                </section>

                                <!-- ************** Contact *************-->
                                <h3><?= lang('client_contact') . ' ' . lang('details') ?></h3>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                            <label
                                               ><?= lang('company_phone') ?></label>
                                                                                                
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($client_info->phone)) {
                                                    echo $client_info->phone;
                                                }
                                                ?>" name="phone">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('company_mobile') ?></label>
                                                                                                
                                                <input type="text" class="form-control"
                                                       value="<?php
                                                       if (!empty($client_info->mobile)) {
                                                           echo $client_info->mobile;
                                                       }
                                                       ?>" name="mobile">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">

                                            <div class="mb-3">
                                                <label><?= lang('zipcode') ?></label>
                                                                                                
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($client_info->zipcode)) {
                                                    echo $client_info->zipcode;
                                                }
                                                ?>" name="zipcode">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">

                                            <div class="mb-3">
                                                <label><?= lang('company_city') ?></label>
                                                                                                
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($client_info->city)) {
                                                    echo $client_info->city;
                                                }
                                                ?>" name="city">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('company_country') ?></label>
                                                                                                
                                                <select name="country" class="form-control select_box"
                                                        style="width: 100%">
                                                    <optgroup label="Default Country">
                                                        <option
                                                            value="<?= $this->config->item('company_country') ?>"><?= $this->config->item('company_country') ?></option>
                                                    </optgroup>
                                                    <optgroup label="<?= lang('other_countries') ?>">
                                                        <?php if (!empty($countries)): foreach ($countries as $country): ?>
                                                            <option
                                                                value="<?= $country->value ?>" <?= (!empty($client_info->country) && $client_info->country == $country->value ? 'selected' : NULL) ?>><?= $country->value ?>
                                                            </option>
                                                            <?php
                                                        endforeach;
                                                        endif;
                                                        ?>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('company_fax') ?></label>
                                                                                                
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($client_info->fax)) {
                                                    echo $client_info->fax;
                                                }
                                                ?>" name="fax">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('company_address') ?></label>
                                                                                                
                                                <textarea class="form-control" name="address"><?php
                                                    if (!empty($client_info->address)) {
                                                        echo $client_info->address;
                                                    }
                                                    ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">

                                            <div class="mb-3">
                                                <label>
                                                <a href="#"
                                                   onclick="fetch_lat_long_from_google_cprofile(); return false;"
                                                   data-bs-toggle="tooltip"
                                                   data-title="<?php echo lang('fetch_from_google') . ' - ' . lang('customer_fetch_lat_lng_usage'); ?>"><i
                                                        id="gmaps-search-icon" class="fa fa-google"
                                                        aria-hidden="true"></i></a>
                                                <?= lang('latitude') . '( ' . lang('google_map') . ' )' ?>
                                                </label>
                                                                                                
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($client_info->latitude)) {
                                                    echo $client_info->latitude;
                                                }
                                                ?>" name="latitude">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">

                                            <div class="mb-3">
                                                <label><?= lang('longitude') . '( ' . lang('google_map') . ' )' ?></label>
                                                                                                
                                                <input type="text" class="form-control"
                                                       value="<?php
                                                       if (!empty($client_info->longitude)) {
                                                           echo $client_info->longitude;
                                                       }
                                                       ?>" name="longitude">
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <!-- ************** Web *************-->
                                <h3><?= lang('web') ?></h3>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('company_domain') ?></label>
                                                                                                
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($client_info->website)) {
                                                    echo $client_info->website;
                                                }
                                                ?>" name="website">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">

                                            <div class="mb-3">
                                                <label><?= lang('skype_id') ?></label>
                                                                                                
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($client_info->skype_id)) {
                                                    echo $client_info->skype_id;
                                                }
                                                ?>" name="skype_id">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('facebook_profile_link') ?></label>
                                                                                                
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($client_info->facebook)) {
                                                    echo $client_info->facebook;
                                                }
                                                ?>" name="facebook">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('twitter_profile_link') ?></label>
                                                                                                
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($client_info->twitter)) {
                                                    echo $client_info->twitter;
                                                }
                                                ?>" name="twitter">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('linkedin_profile_link') ?></label>
                                                                                                
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($client_info->linkedin)) {
                                                    echo $client_info->linkedin;
                                                }
                                                ?>" name="linkedin">
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <!-- ************** Hosting *************-->
                                <h3><?= lang('hosting') ?></h3>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('hosting_company') ?></label>
                                                                                                
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($client_info->hosting_company)) {
                                                    echo $client_info->hosting_company;
                                                }
                                                ?>" name="hosting_company">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('hostname') ?></label>
                                                                                                
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($client_info->hostname)) {
                                                    echo $client_info->hostname;
                                                }
                                                ?>" name="hostname">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('username') ?> </label>
                                                                                                
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($client_info->username)) {
                                                    echo $client_info->username;
                                                }
                                                ?>" name="username">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('password') ?></label>
                                                                                                
                                                <?php
                                                if (!empty($client_info->password)) {
                                                    $password = strlen(decrypt($client_info->password));
                                                }
                                                ?>
                                                <input type="password" name="password" value=""
                                                       placeholder="<?php
                                                       if (!empty($password)) {
                                                           for ($p = 1; $p <= $password; $p++) {
                                                               echo '*';
                                                           }
                                                       }
                                                       ?>" class="form-control">
                                                <strong id="show_password" class="required"></strong>
                                            </div>
                                            <?php if (!empty($client_info->password)) { ?>
                                                <div class="col-lg-3 col-md-3 col-sm-3">
                                                    <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                       href="<?= base_url('admin/client/see_password/c_' . $client_info->client_id) ?>"
                                                       id="see_password"><?= lang('see_password') ?></a>
                                                    <strong id="hosting_password" class="required"></strong>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label><?= lang('port') ?></label>
                                                                                                
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($client_info->port)) {
                                                    echo $client_info->port;
                                                }
                                                ?>" name="port">
                                            </div>
                                        </div>
                                    </div>
                                </section>

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
<script type="text/javascript">
    function fetch_lat_long_from_google_cprofile() {
        var data = {};
        data.address = $('textarea[name="address"]').val();
        data.city = $('input[name="city"]').val();
        data.country = $('select[name="country"] option:selected').text();
        $('#gmaps-search-icon').removeClass('fa-google').addClass('fa-spinner fa-spin');
        $.post('<?= base_url()?>admin/global_controller/fetch_address_info_gmaps', data).done(function (data) {
            data = JSON.parse(data);
            $('#gmaps-search-icon').removeClass('fa-spinner fa-spin').addClass('fa-google');
            if (data.response.status == 'OK') {
                $('input[name="latitude"]').val(data.lat);
                $('input[name="longitude"]').val(data.lng);
            } else {
                if (data.response.status == 'ZERO_RESULTS') {
                    toastr.warning("<?php echo lang('g_search_address_not_found'); ?>");
                } else {
                    toastr.warning(data.response.status);
                }
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

        $("#basic-example").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "slide",
            onInit: function (event, currentIndex, priorIndex) {
                $(".actions a[href='#finish']").hide();
                var $input = $('<li aria-hidden="true"><button type="submit" class="btn btn-primary" role="menuitem"><?= lang('save') ?></button></li><li aria-hidden="true"><button type="submit" class="btn btn-primary" role="menuitem" name="save_and_create_contact" value="1"><?= lang('save_and_create_contact') ?></button></li>');
                $input.appendTo($('ul[aria-label=Pagination]'));
                
            }

        });
        function checkSelected(){
        if($(".action-check").is(":checked")){
            return true;
        }
        toastr['error']('Please select any record');
        return false
    }

    
    });

    function multiple_status_change_status(status){
        if(!$(".action-check").is(":checked")){
             toastr['error']('Please select any record');
          return false
        }
        if( !confirm("<?=lang('are_you_sure');?>")){
            return false;
        }
       
        var client_id = [];
          $(".action-check:checked").each(function(){
             client_id.push($(this).val());
        });
        $('#loader-wrapper').show();

        $.ajax({
           url: '<?=site_url('admin/client/multiple_client_change_status');?>',
           data: {client_id:client_id, status:status, field:'status' },
          
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
    function multiple_source_change_status(status){
        if(!$(".action-check").is(":checked")){
             toastr['error']('Please select any record');
          return false
        }
        if( !confirm("<?=lang('are_you_sure');?>")){
            return false;
        }
        var client_id = [];
          $(".action-check:checked").each(function(){
             client_id.push($(this).val());
        });
        $('#loader-wrapper').show();

        $.ajax({
           url: '<?=site_url('admin/client/multiple_client_change_status');?>',
           data: {client_id:client_id, status:status, field:'source' },
          
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
    function multiple_delete_users() {
        if(!$(".action-check").is(":checked")){
             toastr['error']('Please select any record');
          return false
        }
        if( !confirm("<?=lang('are_you_sure');?>")){
            return false;
        }
       
        var client_id = [];
          $(".action-check:checked").each(function(){
             client_id.push($(this).val());
        });
        $('#loader-wrapper').show();

        $.ajax({
           url: '<?=site_url('admin/client/multiple_client_delete_users');?>',
           data: {client_id:client_id },
          
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
<style type="text/css">
  /*  .actions ul li {
        margin-top: .25rem!important;
    }*/
</style>