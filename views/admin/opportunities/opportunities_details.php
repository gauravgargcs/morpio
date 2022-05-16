<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<style>
    .note-editor .note-editable {
        height: 150px;
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
$edited = can_action('56', 'edited');
$can_edit = $this->items_model->can_action('tbl_opportunities', 'edit', array('opportunities_id' => $opportunity_details->opportunities_id));
$comment_details = $this->db->where(array('opportunities_id' => $opportunity_details->opportunities_id, 'comments_reply_id' => '0', 'task_attachment_id' => '0', 'uploaded_files_id' => '0'))->order_by('comment_datetime', 'DESC')->get('tbl_task_comment')->result();
$all_mettings_info = $this->db->where('opportunities_id', $opportunity_details->opportunities_id)->get('tbl_mettings')->result();
$all_calls_info = $this->db->where('opportunities_id', $opportunity_details->opportunities_id)->get('tbl_calls')->result();
$activities_info = $this->db->where(array('module' => 'opportunities', 'module_field_id' => $opportunity_details->opportunities_id))->order_by('activity_date', 'DESC')->get('tbl_activities')->result();
$all_task_info = $this->db->where('opportunities_id', $opportunity_details->opportunities_id)->order_by('task_id', 'DESC')->get('tbl_task')->result();
$all_bugs_info = $this->db->where('opportunities_id', $opportunity_details->opportunities_id)->order_by('bug_id', 'DESC')->get('tbl_bug')->result();
?>

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    
                    <a class="nav-link mb-2 <?= $active == 1 ? 'active' : '' ?>" href="#task_details" data-bs-toggle="pill" role="tab" aria-controls="task_details" aria-selected="false"><?= lang('opportunity_details') ?></a>
                    
                    <a class="nav-link mb-2 <?= $active == 2 ? 'active' : '' ?>" href="#call" data-bs-toggle="pill" role="tab" aria-controls="call" aria-selected="false"><?= lang('call') ?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_calls_info) ? count($all_calls_info) : null) ?></strong></a>
                    
                    <a class="nav-link mb-2 <?= $active == 3 ? 'active' : '' ?>" href="#mettings" data-bs-toggle="pill" role="tab" aria-controls="mettings" aria-selected="false"><?= lang('mettings') ?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_mettings_info) ? count($all_mettings_info) : null) ?></strong></a>
                    
                    <a class="nav-link mb-2 <?= $active == 4 ? 'active' : '' ?>" href="#task_comments" data-bs-toggle="pill" role="tab" aria-controls="task_comments" aria-selected="false"><?= lang('comments') ?><strong class="badge badge-soft-danger pull-right"><?= (!empty($comment_details) ? count($comment_details) : null) ?></strong></a>
                    
                    <a class="nav-link mb-2 <?= $active == 5 ? 'active' : '' ?>" href="#task_attachments" data-bs-toggle="pill" role="tab" aria-controls="task_attachments" aria-selected="false"><?= lang('attachment') ?><strong class="badge badge-soft-danger pull-right"><?= (!empty($project_files_info) ? count($project_files_info) : null) ?></strong></a>
                    
                    <a class="nav-link mb-2 <?= $active == 6 ? 'active' : '' ?>" href="#task" data-bs-toggle="pill" role="tab" aria-controls="task" aria-selected="false"><?= lang('tasks') ?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_task_info) ? count($all_task_info) : null) ?></strong></a>
                    
                    <a class="nav-link mb-2 <?= $active == 9 ? 'active' : '' ?>" href="#bugs" data-bs-toggle="pill" role="tab" aria-controls="bugs" aria-selected="false"><?= lang('bugs') ?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_bugs_info) ? count($all_bugs_info) : null) ?></strong></a>
                    
                    <a class="nav-link mb-2 <?= $active == 7 ? 'active' : '' ?>" href="#activities" data-bs-toggle="pill" role="tab" aria-controls="activities" aria-selected="false"><?= lang('activities') ?><strong class="badge badge-soft-danger pull-right"><?= (!empty($activities_info) ? count($activities_info) : null) ?></strong></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
                    <!-- Details tab Starts -->
                    <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="task_details" style="position: relative;">
                        <div class="pull-right text-sm">
                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                            <a class="btn btn-primary btn-sm " href="<?= base_url() ?>admin/opportunities/index/<?= $opportunity_details->opportunities_id ?>"><?= lang('edit') . ' ' . lang('opportunities') ?></a>
                            <?php } ?>
                        </div>
                        <h4 class="card-title mb-4"> 
                            <?php
                            if (!empty($opportunity_details->opportunity_name)) {
                                echo $opportunity_details->opportunity_name;
                            }
                            ?>
                        </h4>
                        <div class="row form-horizontal task_details">
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <?php super_admin_details_p($opportunity_details->companies_id, 6) ?>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label col-sm-6"><strong><?= lang('probability') ?></strong>
                                    </label>
                                    <p class="form-control-static">
                                        <?php
                                        if (!empty($opportunity_details->probability)) {
                                            echo $opportunity_details->probability . ' %';
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <label class="form-label col-sm-6"><strong><?= lang('opportunity_name') ?>
                                            :</strong>
                                    </label>
                                    <p class="form-control-static">
                                        <?php
                                        if (!empty($opportunity_details->opportunity_name)) {
                                            echo $opportunity_details->opportunity_name;
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label col-sm-6"><strong><?= lang('stages') ?>
                                            :</strong></label>
                                    <p class="form-control-static">
                                        <?php
                                        if (!empty($opportunity_details->stages)) {
                                            echo $opportunity_details->stages;
                                        }
                                        ?>
                                    </p>
                                </div>

                            </div>
                            <div class="row mb-3 ">
                                <div class="col-sm-6">
                                    <label class="form-label col-sm-6"><strong><?= lang('expected_revenue') ?>
                                            : </strong></label>
                                    <p class="form-control-static">
                                        <strong>
                                            <?php
                                            $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                                            if (!empty($opportunity_details->expected_revenue)) {
                                                echo display_money($opportunity_details->expected_revenue, $currency->symbol);
                                            }
                                            ?>
                                        </strong>
                                    </p>
                                </div>
                                <?php
                                if (strtotime($opportunity_details->close_date) < time() AND $opportunity_details->probability < 100) {
                                    $danger = 'text-danger';
                                } else {
                                    $danger = null;
                                } ?>
                                <div class="col-sm-6">
                                    <label
                                            class="form-label col-sm-6 <?= $danger ?>"><strong><?= lang('close_date') ?>
                                            :</strong></label>
                                    <p class="form-control-static <?= $danger ?>">
                                        <?= display_datetime($opportunity_details->close_date) ?>
                                    </p>
                                </div>

                            </div>
                            <?php
                            $opportunities_state_info = $this->db->where('opportunities_state_reason_id', $opportunity_details->opportunities_state_reason_id)->get('tbl_opportunities_state_reason')->row();
                            if ($opportunities_state_info->opportunities_state == 'open') {
                                $label = 'primary';
                            } elseif ($opportunities_state_info->opportunities_state == 'won') {
                                $label = 'success';
                            } elseif ($opportunities_state_info->opportunities_state == 'suspended') {
                                $label = 'info';
                            } else {
                                $label = 'danger';
                            }
                            ?>
                            <div class="row mb-3 ">
                                <div class="col-sm-6">
                                    <label class="form-label col-sm-6"><strong><?= lang('state') ?>
                                            : </strong></label>
                                    <p class="form-control-static">
                                    <span
                                            class="badge badge-soft-<?= $label ?>"><?= lang($opportunities_state_info->opportunities_state) ?></span>
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label col-sm-6"><strong><?= lang('next_action') ?> :</strong></label>
                                    <p class="form-control-static">
                                        <?php
                                        if (!empty($opportunity_details->next_action)) {
                                            echo $opportunity_details->next_action;
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-3 ">
                                <div class="col-sm-6">
                                    <label class="form-label col-sm-6"><strong><?= lang('new_link') ?> :</strong>
                                    </label>
                                    <p class="form-control-static">
                                        <?php
                                        if (!empty($opportunity_details->new_link)) {
                                            echo $opportunity_details->new_link;
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label col-sm-6"><strong><?= lang('next_action_date') ?>
                                            : </strong></label>
                                    <p class="form-control-static">
                                        <?= display_datetime($opportunity_details->next_action_date) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-3 ">
                                <?php if ($opportunity_details->permission != '-') { ?>
                                    <div class="col-sm-6">
                                        <label class="form-label col-sm-6"><strong><?= lang('participants') ?>
                                                : </strong></label>
                                        <div class="avatar-group">
                                            <?php
                                            if ($opportunity_details->permission != 'all') {
                                                $get_permission = json_decode($opportunity_details->permission);
                                                if (!empty($get_permission)) :
                                                    foreach ($get_permission as $permission => $v_permission) :
                                                        $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                                                        if ($user_info->role_id == 1) {
                                                            $label = 'text-danger';
                                                        } else {
                                                            $label = 'text-success';
                                                        }
                                                        $profile_info = $this->db->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                                        ?>
                                                <div class="avatar-group-item">
                                                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $profile_info->fullname ?>" class="d-inline-block"><img src="<?= base_url() . $profile_info->avatar ?>" class="rounded-circle avatar-xs" alt=""> <span style="margin: 0px 0 8px -10px;" class="mdi mdi-circle <?= $label ?> font-size-10"></span>
                                                    </a>
                                                </div>
                                                <?php
                                                endforeach;
                                                endif;
                                            } else { ?>
                                                <span class="mr-lg-2 mt-2">
                                                    <strong><?= lang('everyone') ?></strong>
                                                    <i  title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                                </span>
                                            <?php } ?>
                                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?= lang('add_more') ?>" class="mt-2 ml">
                                                    <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/opportunities/update_users/<?= $opportunity_details->opportunities_id ?>" class="text-default"><i class="fa fa-plus"></i></a>
                                                </span>
                                            <?php  } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php $show_custom_fields = custom_form_label(8, $opportunity_details->opportunities_id);

                            if (!empty($show_custom_fields)) {
                                foreach ($show_custom_fields as $c_label => $v_fields) {
                                    if (!empty($v_fields)) {
                                        if (count($v_fields) == 1) {
                                            $col = 'col-sm-12';
                                            $sub_col = 'col-sm-3';
                                            $style = null;
                                        } else {
                                            $col = 'col-sm-6';
                                            $sub_col = 'col-sm-5';
                                            $style = null;
                                        }

                                        ?>
                                        <div class="row mb-3  <?= $col ?>" style="<?= $style ?>">
                                            <label class="form-label <?= $sub_col ?>"><strong><?= $c_label ?>
                                                    :</strong></label>
                                            <div class="col-sm-7 ">
                                                <p class="form-control-static">
                                                    <strong><?= $v_fields ?></strong>
                                                </p>
                                            </div>
                                        </div>
                                    <?php }
                                }
                            }
                            ?>
                            <div class="row mb-3 col-sm-12">
                                <label class="col-sm-2 form-label"></label>
                                <div class="col-sm-10">
                                    <blockquote style="font-size: 12px;"><?php
                                        if (!empty($opportunity_details->notes)) {
                                            echo $opportunity_details->notes;
                                        }
                                        ?></blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <!-- Task Comments Panel Starts --->
                    <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="call" style="position: relative;">
                        <!-- Tabs within a box -->
                        <ul class="nav nav-tabs bg-light rounded">
                            <li class="nav-item">
                                <a class="nav-link <?= $sub_active == 1 ? 'active' : ''; ?>" href="#manage" data-bs-toggle="tab">
                                    <?= lang('all_call') ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $sub_active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab">
                                    <?= lang('new_call') ?>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content p-3 text-muted">
                            <!-- ************** general *************-->
                            <div class="tab-pane <?= $sub_active == 1 ? 'active' : ''; ?>" id="manage">
                                   
                                <h4 class="card-title mb-4"><?= lang('all_call') ?></h4>

                                <div class="table-responsive">
                                    <table class="table table-striped dt-responsive nowrap w-100" id="call_opp_manage_datatable">
                                        <thead>
                                            <tr>
                                                <th><?= lang('date') ?></th>
                                                <th><?= lang('call_summary') ?></th>
                                                <th><?= lang('contact') ?></th>
                                                <th><?= lang('responsible') ?></th>
                                                <th class="col-options no-sort"><?= lang('action') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($all_calls_info)):
                                            foreach ($all_calls_info as $v_calls):
                                                $client_info = $this->items_model->check_by(array('client_id' => $v_calls->client_id), 'tbl_client');
                                                $user = $this->items_model->check_by(array('user_id' => $v_calls->user_id), 'tbl_users');
                                                ?>
                                                <tr id="table-call-<?= $v_calls->calls_id ?>">
                                                    <td><?= display_datetime($v_calls->date) ?></td>
                                                    <td><?= $v_calls->call_summary ?></td>
                                                    <td><?= $client_info->name; ?></td>
                                                    <td><?= $user->username ?></td>
                                                    <td>
                                                        <?= btn_edit('admin/opportunities/opportunity_details/' . $opportunity_details->opportunities_id . '/call/' . $v_calls->calls_id) ?>
                                                        <?php echo ajax_anchor(base_url("admin/opportunities/delete_opportunity_call/" . $opportunity_details->opportunities_id . '/' . $v_calls->calls_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table-call-" . $v_calls->calls_id)); ?>
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
                            <div class="tab-pane <?= $sub_active == 2 ? 'active' : ''; ?>" id="create">
                                <div class="card-body"> 
                                    <h4 class="card-title mb-4"><?= lang('new_call') ?></h4>
                            
                                    <form role="form" enctype="multipart/form-data" id="form" action="<?php echo base_url(); ?>admin/opportunities/saved_call/<?= $opportunity_details->opportunities_id ?>/<?php
                                      if (!empty($call_info)) {
                                          echo $call_info->calls_id;
                                      }
                                      ?>" method="post" class="form-horizontal  ">
                                        <div class="row mb-3">
                                            <label class="col-lg-3 form-label"><?= lang('date') ?><span class="text-danger"> *</span></label>
                                            <div class="col-lg-5">
                                                <div class="input-group">
                                                    <input type="text" required="" name="date"
                                                           class="form-control datepicker" value="<?php
                                                    if (!empty($call_info->date)) {
                                                        echo date('d-m-Y H-i', strtotime($call_info->date));
                                                    } else {
                                                        echo date('d-m-Y H-i');
                                                    }
                                                    ?>" data-date-format="<?= config_item('date_picker_format'); ?>">
                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                    
                                                </div>
                                            </div>

                                        </div>
                                        <!-- End discount Fields -->
                                        <div class="row mb-3 terms">
                                            <label class="col-lg-3 form-label"><?= lang('call_summary') ?><span
                                                        class="text-danger"> *</span> </label>
                                            <div class="col-lg-5">
                                                <input type="text" required="" name="call_summary" class="form-control"
                                                       value="<?php
                                                       if (!empty($call_info->call_summary)) {
                                                           echo $call_info->call_summary;
                                                       }
                                                       ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-lg-3 form-label"><?= lang('contact') ?><span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-lg-5">
                                                <select name="client_id" class="form-control select_box" style="width: 100%"
                                                        required="">
                                                    <option value=""><?= lang('select_client') ?></option>
                                                    <?php
                                                    $all_client = get_result('tbl_client');
                                                    if (!empty($all_client)) {
                                                        foreach ($all_client as $v_client) {
                                                            ?>
                                                            <option value="<?= $v_client->client_id ?>" <?php
                                                            if (!empty($call_info) && $call_info->client_id == $v_client->client_id) {
                                                                echo 'selected';
                                                            }
                                                            ?>><?= $v_client->name ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-lg-3 form-label"><?= lang('responsible') ?><span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-lg-5">
                                                <select name="user_id" class="form-control select_box" style="width: 100%"
                                                        required="">
                                                    <option value=""><?= lang('admin_staff') ?></option>
                                                    <?php
                                                    $user_info = get_result('tbl_users', array('role_id !=' => 2));
                                                    if (!empty($user_info)) {
                                                        foreach ($user_info as $key => $v_user) {
                                                            ?>
                                                            <option value="<?= $v_user->user_id ?>" <?php
                                                            if (!empty($call_info) && $call_info->user_id == $v_user->user_id) {
                                                                echo 'selected';
                                                            }
                                                            ?>><?= $v_user->username ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-lg-3 form-label"></label>
                                            <div class="col-lg-5">
                                                <button type="submit"
                                                        class="btn btn-xs btn-primary"><?= lang('updates') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Task Comments Panel Ends--->

                    <!-- Task Attachment Panel Starts --->
                    <div class="tab-pane <?= $active == 3 ? 'active' : '' ?>" id="mettings" style="position: relative;">
                        <!-- Tabs within a box -->
                        <ul class="nav nav-tabs bg-light rounded">
                            <li class="nav-item">
                                <a class="nav-link <?= $sub_metting == 1 ? 'active' : ''; ?>" href="#all_metting" data-bs-toggle="tab">
                                    <?= lang('all_metting') ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link <?= $sub_metting == 2 ? 'active' : ''; ?>" href="#new_metting" data-bs-toggle="tab">
                                    <?= lang('new_metting') ?>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content p-3 text-muted">
                            <!-- ************** general *************-->
                            <div class="tab-pane <?= $sub_metting == 1 ? 'active' : ''; ?>" id="all_metting">
                                <h4 class="card-title mb-4"><?= lang('all_metting') ?></h4>
                                <div class="table-responsive">
                                    <table class="table table-striped dt-responsive nowrap w-100" id="all_opp_meeting_datatble">
                                        <thead>
                                            <tr>
                                                <th><?= lang('subject') ?></th>
                                                <th><?= lang('start_date') ?></th>
                                                <th><?= lang('end_date') ?></th>
                                                <th><?= lang('responsible') ?></th>
                                                <th class="col-options no-sort"><?= lang('action') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($all_mettings_info)):
                                            foreach ($all_mettings_info as $v_mettings):
                                                $user = $this->items_model->check_by(array('user_id' => $v_mettings->user_id), 'tbl_users');
                                                ?>
                                                <tr id="table-meeting-<?= $v_mettings->mettings_id ?>">
                                                    <td><?= $v_mettings->meeting_subject ?></td>
                                                    <td><?= display_datetime($v_mettings->start_date) ?></td>
                                                    <td><?= display_datetime($v_mettings->end_date) ?></td>
                                                    <td><?= $user->username ?></td>
                                                    <td>
                                                        <?= btn_edit('admin/opportunities/opportunity_details/' . $opportunity_details->opportunities_id . '/metting/' . $v_mettings->mettings_id) ?>
                                                        <?php echo ajax_anchor(base_url("admin/opportunities/delete_opportunity_mettings/" . $opportunity_details->opportunities_id . '/' . $v_mettings->mettings_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table-meeting-" . $v_mettings->mettings_id)); ?>
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
                            <div class="tab-pane <?= $sub_metting == 2 ? 'active' : ''; ?>" id="new_metting">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><?= lang('new_metting') ?></h4>
                                    <form role="form" enctype="multipart/form-data" id="form" action="<?php echo base_url(); ?>admin/opportunities/saved_metting/<?= $opportunity_details->opportunities_id ?>/<?php
                                      if (!empty($mettings_info)) {
                                          echo $mettings_info->mettings_id;
                                      }
                                      ?>" method="post" class="form-horizontal  ">
                                        <div class="row mb-3 terms">
                                            <label class="col-lg-3 form-label"><?= lang('metting_subject') ?><span
                                                        class="text-danger"> *</span> </label>
                                            <div class="col-lg-9">
                                                <input type="text" required="" name="meeting_subject" class="form-control"
                                                       value="<?php
                                                       if (!empty($mettings_info->meeting_subject)) {
                                                           echo $mettings_info->meeting_subject;
                                                       }
                                                       ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-lg-3 form-label"><?= lang('start_date') ?><span class="text-danger"> *</span></label>
                                            <div class="col-lg-9">
                                                <div class="input-group">
                                                    <input type="text" required="" name="start_date"
                                                           class="form-control datepicker" value="<?php
                                                    if (!empty($mettings_info->start_date)) {
                                                        echo date('d-m-Y H-i', strftime($mettings_info->start_date));
                                                    } else {
                                                        echo date('d-m-Y H-i');
                                                    }
                                                    ?>"  data-date-format="<?= config_item('date_picker_format'); ?>">
                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-lg-3 form-label"><?= lang('end_date') ?><span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-lg-9">
                                                <div class="input-group">
                                                    <input type="text" required="" name="end_date"
                                                           class="form-control datepicker" value="<?php
                                                    if (!empty($mettings_info->end_date)) {
                                                        echo date('d-m-Y H-i', strftime($mettings_info->end_date));
                                                    } else {
                                                        echo date('d-m-Y H-i');
                                                    }
                                                    ?>"
                                                           data-date-format="<?= config_item('date_picker_format'); ?>">
                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-lg-3 form-label"><?= lang('attendess') ?><span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-lg-9">
                                                <select multiple="multiple" name="attendees[]" style="width: 100%"
                                                        class="select_multi form-control" required="">
                                                    <option value=""><?= lang('select') . lang('attendess') ?></option>
                                                    <?php
                                                    $all_user_attendees = get_result('tbl_users');
                                                    if (!empty($all_user_attendees)) {
                                                        foreach ($all_user_attendees as $v_user_attendees) {
                                                            ?>
                                                            <option value="<?= $v_user_attendees->user_id ?>" <?php
                                                            if (!empty($mettings_info->attendees)) {
                                                                $user_id = unserialize($mettings_info->attendees);
                                                                foreach ($user_id['attendees'] as $assding_id) {
                                                                    echo $v_user_attendees->user_id == $assding_id ? 'selected' : '';
                                                                }
                                                            }
                                                            ?>><?= $v_user_attendees->username ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-lg-3 form-label"><?= lang('responsible') ?><span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-lg-9">
                                                <select name="user_id" class="form-control select_box" style="width: 100%"
                                                        required="">
                                                    <option value=""><?= lang('admin_staff') ?></option>
                                                    <?php
                                                    $responsible_user_info = get_result('tbl_users', array('role_id !=' => 2));
                                                    if (!empty($responsible_user_info)) {
                                                        foreach ($responsible_user_info as $v_responsible_user) {
                                                            ?>
                                                            <option value="<?= $v_responsible_user->user_id ?>" <?php
                                                            if (!empty($mettings_info) && $mettings_info->user_id == $v_responsible_user->user_id) {
                                                                echo 'selected';
                                                            }
                                                            ?>><?= $v_responsible_user->username ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row mb-3 terms">
                                            <label class="col-lg-3 form-label"><?= lang('location') ?><span
                                                        class="text-danger"> *</span> </label>
                                            <div class="col-lg-9">
                                                <input type="text" required="" name="location" class="form-control"
                                                       value="<?php
                                                       if (!empty($mettings_info->location)) {
                                                           echo $mettings_info->location;
                                                       }
                                                       ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3 terms">
                                            <label class="col-lg-3 form-label"><?= lang('description') ?> </label>
                                            <div class="col-lg-9">
                                                <textarea name="description" class="form-control"><?php
                                                    if (!empty($mettings_info)) {
                                                        echo $mettings_info->description;
                                                    }
                                                    ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-lg-3 form-label"></label>
                                            <div class="col-lg-5">
                                                <button type="submit"
                                                        class="btn btn-xs btn-primary"><?= lang('updates') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Task Comments Panel Starts --->
                    <?php $comment_type = 'opportunities'; ?>
                    <div class="tab-pane <?= $active == 4 ? 'active' : '' ?>" id="task_comments" style="position: relative;">
                        <div class="card-body">    
                            <h4 class="card-title mb-4"><?= lang('comments') ?></h4>
                            <div class="chat" id="chat-box">
                                <?php echo form_open(base_url("admin/opportunities/save_comments"), array("id" => $comment_type . "-comment-form", "class" => "form-horizontal general-form", "enctype" => "multipart/form-data", "role" => "form")); ?>
                                <input type="hidden" name="opportunities_id" value="<?php
                                if (!empty($opportunity_details->opportunities_id)) {
                                    echo $opportunity_details->opportunities_id;
                                }
                                ?>" class="form-control">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <?php
                                        echo form_textarea(array(
                                            "id" => "comment_description",
                                            "name" => "comment",
                                            "class" => "form-control comment_description",
                                            "placeholder" => $opportunity_details->opportunity_name . ' ' . lang('comments'),
                                            "data-rule-required" => true,
                                            "rows" => 4,
                                            "data-msg-required" => lang("field_required"),
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <div id="comments_file-dropzone" class="dropzone mb15">
                                        </div>
                                        <div id="comments_file-dropzone-scrollbar">
                                            <div id="comments_file-previews">
                                                <div id="file-upload-row" class="mt pull-left">
                                                    
                                                    <div class="preview box-content pr-lg w-100">
                                                        <span data-dz-remove class="pull-right pointer">
                                                            <i class="fa fa-times"></i>
                                                        </span>
                                                        <img data-dz-thumbnail class="upload-thumbnail-sm"/>
                                                        <input class="file-count-field" type="hidden" name="files[]" value=""/>

                                                        <div class="mb progress progress-striped upload-progress-sm active mt-sm"
                                                             role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                                            <div class="progress-bar progress-bar-success w-0" data-dz-uploadprogress></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <button type="submit" id="file-save-button"
                                                    class="btn btn-primary"><?= lang('post_comment') ?></button>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <?php echo form_close();
                                $comment_reply_type = 'opportunities-reply';
                                ?>
                                <?php $this->load->view('admin/opportunities/comments_list', array('comment_details' => $comment_details)) ?>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $("#<?php echo $comment_type; ?>-comment-form").appForm({
                                            isModal: false,
                                            onSuccess: function (result) {
                                                $(".comment_description").val("");
                                                $(".dz-complete").remove();
                                                $(result.data).insertAfter("#<?php echo $comment_type; ?>-comment-form");
                                                toastr[result.status](result.message);
                                            }
                                        });
                                        fileSerial = 0;
                                        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
                                        var previewNode = document.querySelector("#file-upload-row");
                                        previewNode.id = "";
                                        var previewTemplate = previewNode.parentNode.innerHTML;
                                        previewNode.parentNode.removeChild(previewNode);
                                        Dropzone.autoDiscover = false;
                                        var projectFilesDropzone = new Dropzone("#comments_file-dropzone", {
                                                url: "<?= base_url() ?>admin/global_controller/upload_file",
                                                thumbnailWidth: 80,
                                                thumbnailHeight: 80,
                                                parallelUploads: 20,
                                                previewTemplate: previewTemplate,
                                                dictDefaultMessage: '<div class="mb-3"><i class="display-4 text-muted bx bxs-cloud-upload"></i></div><h4>Drop files here or click to upload.</h4>',
                                                autoQueue: true,
                                                previewsContainer: "#comments_file-previews",
                                                clickable: true,
                                                accept: function (file, done) {
                                                    if (file.name.length > 200) {
                                                        done("Filename is too long.");
                                                        $(file.previewTemplate).find(".description-field").remove();
                                                    }
                                                    $.ajax({
                                                        url: "<?= base_url() ?>admin/global_controller/validate_project_file",
                                                        data: {
                                                            file_name: file.name,
                                                            file_size: file.size
                                                        },
                                                        cache: false,
                                                        type: 'POST',
                                                        dataType: "json",
                                                        success: function (response) {
                                                            if (response.success) {
                                                                fileSerial++;
                                                                $(file.previewTemplate).find(".description-field").attr("name", "comment_" + fileSerial);
                                                                $(file.previewTemplate).append("<input type='hidden' name='file_name_" + fileSerial + "' value='" + file.name + "' />\n\
                                                                                        <input type='hidden' name='file_size_" + fileSerial + "' value='" + file.size + "' />");
                                                                $(file.previewTemplate).find(".file-count-field").val(fileSerial);
                                                                done();
                                                            } else {
                                                                $(file.previewTemplate).find("input").remove();
                                                                done(response.message);
                                                            }
                                                        }
                                                    });
                                                },
                                                processing: function () {
                                                    $("#file-save-button").prop("disabled", true);
                                                },
                                                queuecomplete: function () {
                                                    $("#file-save-button").prop("disabled", false);
                                                },
                                                fallback: function () {
                                                    $("body").addClass("dropzone-disabled");
                                                    $('.modal-dialog').find('[type="submit"]').removeAttr('disabled');

                                                    $("#comments_file-dropzone").hide();

                                                    $("#file-modal-footer").prepend("<button id='add-more-file-button' type='button' class='btn  btn-default pull-left'><i class='fa fa-plus-circle'></i> " + "<?php echo lang("add_more"); ?>" + "</button>");

                                                    $("#file-modal-footer").on("click", "#add-more-file-button", function () {
                                                        var newFileRow = "<div class='file-row pb pt10 b-b mb10'>" +
                                                            "<div class='pb clearfix '><button type='button' class='btn btn-sm btn-danger pull-left mr remove-file'><i class='fa fa-times'></i></button> <input class='pull-left' type='file' name='manualFiles[]' /></div>" +
                                                            "<div class='mb5 pb5'><input class='form-control description-field cursor-auto'  name='comment[]'  type='text' placeholder='<?php echo lang("comment") ?>' /></div>" +
                                                            "</div>";
                                                        $("#comments_file-previews").prepend(newFileRow);
                                                    });
                                                    $("#add-more-file-button").trigger("click");
                                                    $("#comments_file-previews").on("click", ".remove-file", function () {
                                                        $(this).closest(".file-row").remove();
                                                    });
                                                },
                                                success: function (file,response) {
                                                    var res=JSON.parse(response);
                                                    if(res['error'] && res.length != 0){
                                                        toastr['error'](res['error']);
                                                        toastr['error']('<?=lang('docroom_connect_msg');?>');
                                                        $(file.previewElement).closest(".file-upload-row").remove();
                                                    }else{
                                                        var docroom_file_id=res['uploaded_file']['data'][0]['file_id'];
                                                        var docroom_file_id_html="<input class='form-control'  name='docroom_file_id[]'  type='hidden' value='"+docroom_file_id+"' />";
                                                        $("#comments_file-previews").prepend(docroom_file_id_html);
                                                        setTimeout(function () {
                                                            $(file.previewElement).find(".progress-striped").removeClass("progress-striped").addClass("progress-bar-success");
                                                        }, 1000);
                                                    }
                                                }
                                            });

                                    })
                                </script>
                            </div>
                        </div>
                    </div>
                    <!-- Task Comments Panel Ends--->

                    <!-- Task Attachment Panel Starts --->
                    <div class="tab-pane <?= $active == 5 ? 'active' : '' ?>" id="task_attachments" style="position: relative;">
                        <div class="card-body">    
                            <div class="row">
                                <div class="col-md-4 col-7">
                                    <h4 class="card-title mt"><?= lang('attach_file_list') ?> </h4>
                                </div>
                                <div class="col-md-8 col-5">
                                    <?php
                                    $attach_list = $this->session->userdata('opportunities_media_view');
                                    if (empty($attach_list)) {
                                        $attach_list = 'list_view';
                                    }
                                    ?>
                                    <ul class="list-inline user-chat-nav text-end mb-0">
                                            <li class="list-inline-item  d-sm-inline-block">
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-default toggle-media-view <?= (!empty($attach_list) && $attach_list == 'list_view' ? 'hidden' : '') ?>" data-bs-type="list_view" title="<?= lang('switch_to') . ' ' . lang('media_view') ?>"><i class="fa fa-image"></i></a>
                                            
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-default toggle-media-view <?= (!empty($attach_list) && $attach_list == 'media_view' ? 'hidden' : '') ?>" data-bs-type="media_view" title="<?= lang('switch_to') . ' ' . lang('list_view') ?>"><i class="fa fa-list"></i></a>
                                            </li>
                                            <li class="list-inline-item d-sm-inline-block">
                                                <a href="<?= base_url() ?>admin/opportunities/new_attachment/<?= $opportunity_details->opportunities_id ?>" class="text-purple text-sm" data-bs-toggle="modal" data-bs-placement="top"  data-bs-target="#myModal_extra_lg">
                                                    <span class="d-block d-sm-none"><i class="fa fa-plus "></i></span>
                                                    <span class="d-none d-sm-block"><?= lang('new') . ' ' . lang('attachment') ?></span>
                                                </a>
                                             </li>
                                        </ul>
                                </div>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $(".toggle-media-view").click(function () {
                                            $(".media-view-container").toggleClass('hidden');
                                            $(".toggle-media-view").toggleClass('hidden');
                                            $(".media-list-container").toggleClass('hidden');
                                            var type = $(this).data('bs-type');
                                            var module = 'opportunities';
                                            $.get('<?= base_url()?>admin/global_controller/set_media_view/' + type + '/' + module, function (response) {
                                            });
                                        });
                                    });
                                </script>
                                <?php
                                $this->load->helper('file');
                                if (empty($project_files_info)) {
                                    $project_files_info = array();
                                } ?>
                                <div class="p media-view-container <?= (!empty($attach_list) && $attach_list == 'media_view' ? 'hidden' : '') ?>">
                                    <div class="row">
                                        <?php $this->load->view('admin/opportunities/attachment_list', array('project_files_info' => $project_files_info)) ?>
                                    </div>
                                </div>
                                <div class="media-list-container <?= (!empty($attach_list) && $attach_list == 'list_view' ? 'hidden' : '') ?>">
                                    <div class="col-md-12 pr0 mb-sm">
                                        <div class="card shadow border">
                                            <div class="card-body">
                                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                                    <?php
                                                    if (!empty($project_files_info)) {
                                                        foreach ($project_files_info as $key => $v_files_info) {
                                                        ?>
                                                    <div class="accordion-item" id="media_list_container-<?= $files_info[$key]->task_attachment_id ?>">
                                                        <h2 class="card-title accordion-header" id="flush-headingOne">        
                                                            <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#attachment-<?php echo $key ?>" aria-expanded="true" aria-controls="flush-collapseOne">
                                                                <span style="width:80%"><?php echo $files_info[$key]->title; ?></span>
                                                                <div class="pull-right" style="margin-left:15%">
                                                                    <?php if ($files_info[$key]->user_id == $this->session->userdata('user_id')) { ?>
                                                                        <?php echo ajax_anchor(base_url("admin/opportunities/delete_files/" . $files_info[$key]->task_attachment_id), "<i class='text-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#media_list_container-" . $files_info[$key]->task_attachment_id)); ?>
                                                                    <?php } ?>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="attachment-<?php echo $key ?>" class="accordion-collapse collapse <?php if (!empty($in) && $files_info[$key]->files_id == $in) { echo 'show'; } ?>" aria-labelledby="flush-headingOne"  data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body text-muted">
                                                                <div class="table-responsive">
                                                                    <table id="opp_files_datatable" class="table table-striped dt-responsive nowrap w-100">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?= lang('files') ?></th>
                                                                                <th class=""><?= lang('size') ?></th>
                                                                                <th><?= lang('date') ?></th>
                                                                                <th><?= lang('total') . ' ' . lang('comments') ?></th>
                                                                                <th><?= lang('uploaded_by') ?></th>
                                                                                <th><?= lang('action') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $this->load->helper('file');

                                                                            if (!empty($v_files_info)) {
                                                                                foreach ($v_files_info as $v_files) {
                                                                                    $user_info = $this->db->where(array('user_id' => $files_info[$key]->user_id))->get('tbl_users')->row();
                                                                                    $total_file_comment = count($this->db->where(array('uploaded_files_id' => $v_files->uploaded_files_id))->order_by('comment_datetime', 'DESC')->get('tbl_task_comment')->result());
                                                                                    ?>
                                                                                    <tr class="file-item">
                                                                                        <td data-bs-toggle="tooltip"
                                                                                            data-bs-placement="top"
                                                                                            data-original-title="<?= $files_info[$key]->description ?>">
                                                                                            <?php if ($v_files->is_image == 1) : ?>
                                                                                                <div class="file-icon"><a
                                                                                                            data-bs-toggle="modal"
                                                                                                            data-target="#myModal_extra_lg"
                                                                                                            href="<?= base_url() ?>admin/opportunities/attachment_details/r/<?= $files_info[$key]->task_attachment_id . '/' . $v_files->uploaded_files_id ?>">
                                                                                                        <img
                                                                                                                style="width: 50px;border-radius: 5px;"
                                                                                                                src="<?= base_url() . $v_files->files ?>"/></a>
                                                                                                </div>
                                                                                            <?php else : ?>
                                                                                                <div class="file-icon"><i
                                                                                                            class="fa fa-file-o"></i>
                                                                                                    <a data-bs-toggle="modal"
                                                                                                       data-target="#myModal_extra_lg"
                                                                                                       href="<?= base_url() ?>admin/opportunities/attachment_details/r/<?= $files_info[$key]->task_attachment_id . '/' . $v_files->uploaded_files_id ?>"><?= $v_files->file_name ?></a>
                                                                                                </div>
                                                                                            <?php endif; ?>
                                                                                        </td>

                                                                                        <td class=""><?= $v_files->size ?>Kb</td>
                                                                                        <td class="col-date"><?= date('d-m-Y H-i', strtotime($files_info[$key]->upload_time)); ?></td>
                                                                                        <td class=""><?= $total_file_comment ?></td>
                                                                                        <td>
                                                                                            <?= $user_info->username ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <a class="btn btn-sm btn-dark"
                                                                                               data-bs-toggle="tooltip"
                                                                                               data-bs-placement="top"
                                                                                               title="Download"
                                                                                               href="<?= base_url() ?>admin/opportunities/download_files/<?= $v_files->uploaded_files_id ?>"><i
                                                                                                        class="fa fa-download"></i></a>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                            } ?> 
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php } } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- Task Attachment Panel Ends --->

                    <!-- Start Tasks Management-->
                    <div class="tab-pane <?= $active == 6 ? 'active' : '' ?>" id="task" style="position: relative;">
                        <!-- Tabs within a box -->
                        <ul class="nav nav-tabs bg-light rounded">
                            <li class="nav-item">
                                <a class="nav-link <?= $task_active == 1 ? 'active' : ''; ?>" href="#manageTasks" data-bs-toggle="tab">
                                    <?= lang('all_task') ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url() ?>admin/tasks/new_tasks/opportunities/<?= $opportunity_details->opportunities_id ?>">
                                    <?= lang('new_task') ?>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content p-3 text-muted">
                            <!-- ************** general *************-->
                            <div class="tab-pane <?= $task_active == 1 ? 'active' : ''; ?>" id="manageTasks" style="position: relative;">
                                <div class="table-responsive">
                                    <table id="opp_tasks_datatable" class="table table-striped dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th data-check-all>
                                                    <div class="form-check font-size-16 check-all">
                                                        <input type="checkbox" id="parent_present" class="form-check-input">
                                                        <label for="parent_present" class="toggle form-check-label"></label>
                                                    </div>
                                                </th>
                                                <th class="col-sm-4"><?= lang('task_name') ?></th>
                                                <th class="col-sm-2"><?= lang('due_date') ?></th>
                                                <th class="col-sm-1"><?= lang('status') ?></th>
                                                <th class="col-sm-1"><?= lang('progress') ?></th>
                                                <th class="col-sm-3"><?= lang('changes/view') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($all_task_info)):foreach ($all_task_info as $key => $v_task):
                                                ?>
                                                <tr id="table-tasks-<?= $v_task->task_id ?>">
                                                    <td class="col-sm-1">
                                                        <div class="complete form-check font-size-16">
                                                            <input type="checkbox"
                                                                   data-id="<?= $v_task->task_id ?>"
                                                                   style="position: absolute;" <?php
                                                            if ($v_task->task_progress >= 100) {
                                                                echo 'checked';
                                                            }
                                                            ?> class="form-check-input">
                                                            <label class="form-check-label">
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a style="<?php
                                                        if ($v_task->task_progress >= 100) {
                                                            echo 'text-decoration: line-through;';
                                                        }
                                                        ?>" href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
                                                    </td>
                                                    <td><?php
                                                        $due_date = $v_task->due_date;
                                                        $due_time = strtotime($due_date);
                                                        $current_time = time();
                                                        ?>
                                                        <?= display_datetime($due_date) ?>
                                                        <?php if ($current_time > $due_time && $v_task->task_progress < 100) { ?>
                                                            <span class="badge badge-soft-danger"><?= lang('overdue') ?></span>
                                                        <?php } ?></td>
                                                    <td><?php
                                                        if ($v_task->task_status == 'completed') {
                                                            $label = 'success';
                                                        } elseif ($v_task->task_status == 'not_started') {
                                                            $label = 'info';
                                                        } elseif ($v_task->task_status == 'deferred') {
                                                            $label = 'danger';
                                                        } else {
                                                            $label = 'warning';
                                                        }
                                                        ?>
                                                        <span class="badge badge-soft-<?= $label ?>"><?= lang($v_task->task_status) ?> </span>
                                                    </td>
                                                    <td>
                                                        <div class="inline ">
                                                            <div class="easypiechart text-success"
                                                                 style="margin: 0px;"
                                                                 data-percent="<?= $v_task->task_progress ?>"
                                                                 data-line-width="5" data-track-Color="#f0f0f0"
                                                                 data-bar-color="#<?php
                                                                 if ($v_task->task_progress == 100) {
                                                                     echo '8ec165';
                                                                 } else {
                                                                     echo 'fb6b5b';
                                                                 }
                                                                 ?>" data-rotate="270" data-scale-Color="false"
                                                                 data-size="50" data-animate="2000">
                                                                <span class="small text-muted"><?= $v_task->task_progress ?>
                                                                    %</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php echo ajax_anchor(base_url("admin/tasks/delete_task/" . $v_task->task_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table-tasks-" . $v_task->task_id)); ?>
                                                        <?php echo btn_edit('admin/tasks/all_task/' . $v_task->task_id) ?>
                                                        <?php

                                                        if ($v_task->timer_status == 'on') { ?>
                                                            <a class="btn btn-sm btn-outline-danger"
                                                               href="<?= base_url() ?>admin/tasks/tasks_timer/off/<?= $v_task->task_id ?>"><?= lang('stop_timer') ?> </a>

                                                        <?php } else { ?>
                                                            <a class="btn btn-sm btn-outline-success"
                                                               href="<?= base_url() ?>admin/tasks/tasks_timer/on/<?= $v_task->task_id ?>"><?= lang('start_timer') ?> </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Task Details tab Ends -->
                    <div class="tab-pane <?= $active == 7 ? 'active' : '' ?>" id="activities" style="position: relative;">
                        <div class="card-body">
                            <?php
                            $role = $this->session->userdata('user_type');
                            if ($role == 1) { ?>
                            <span class="btn-sm pull-right mt">
                                <a href="<?= base_url() ?>admin/tasks/claer_activities/opportunity/<?= $opportunity_details->opportunities_id ?>"><?= lang('clear') . ' ' . lang('activities') ?></a>
                            </span>
                            <?php } ?>
                            <h4 class="card-title mb-4 mt"><?= lang('activities') ?></h4>
                            <div data-simplebar style="max-height: 800px;">  
                                <ul class="verti-timeline list-unstyled">
                                    <?php
                                    if (!empty($activities_info)) {
                                    foreach ($activities_info as $v_activities) {
                                        $profile_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_account_details')->row();
                                        $user_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_users')->row();
                                        ?>
                                    <li class="event-list">
                                        <div class="event-timeline-dot">
                                            <i class="bx bx-right-arrow-circle font-size-18"></i>
                                        </div>
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <h5 class="font-size-14"><?php echo date('d', strtotime($v_activities->activity_date)) ?> <?php echo date('M', strtotime($v_activities->activity_date)) ?> <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div>
                                                    <?php if (!empty($profile_info)) { ?>
                                                    <h5 class="notice-calendar-heading-title">
                                                            <a href="<?= base_url() ?>admin/user/user_details/<?= $profile_info->user_id ?>"
                                                                   class="text-info"><?= $profile_info->fullname ?></a>
                                                    </h5>
                                                    <?php } ?>
                                                    
                                                    <div class="notice-calendar-date">
                                                        <p><?= sprintf(lang($v_activities->activity)) ?>
                                                            <strong><?= $v_activities->value1 ?></strong>
                                                            <?php if (!empty($v_activities->value2)){ ?>
                                                            <p class="m0 p0"><strong><?= $v_activities->value2 ?></strong></p>
                                                            <?php } ?>
                                                        </p>
                                                    </div>
                                                    <span style="font-size: 10px;" class="">
                                                        <strong>
                                                            <?= time_ago($v_activities->activity_date); ?>
                                                        </strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>         
                                    <?php  }  } ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane <?= $active == 9 ? 'active' : '' ?>" id="bugs" style="position: relative;">
                        <div class="card-body">
                            <!-- Tabs within a box -->
                            <ul class="nav nav-tabs bg-light rounded"  role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#manage_bugs" data-bs-toggle="tab">
                                        <?= lang('all_bugs') ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= base_url() ?>admin/bugs/index/opportunities/<?= $opportunity_details->opportunities_id ?>">
                                        <?= lang('new_bugs') ?>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-3 text-muted">
                                <!-- ************** general *************-->
                                <div class="tab-pane active" id="manage_bugs">
                                    <div class="table-responsive">
                                        <table id="bug_datatable" class="table table-striped dt-responsive nowrap w-100 ">
                                            <thead>
                                                <tr>
                                                    <th><?= lang('bug_title') ?></th>
                                                    <th><?= lang('status') ?></th>
                                                    <th><?= lang('priority') ?></th>
                                                    <th><?= lang('reporter') ?></th>
                                                    <th><?= lang('action') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($all_bugs_info)):foreach ($all_bugs_info as $key => $v_bugs):
                                                    $reporter = $this->db->where('user_id', $v_bugs->reporter)->get('tbl_users')->row();
                                                    if ($reporter->role_id == '1') {
                                                        $badge = 'danger';
                                                    } elseif ($reporter->role_id == '2') {
                                                        $badge = 'info';
                                                    } else {
                                                        $badge = 'primary';
                                                    }
                                                    ?>
                                                    <tr id="table-bugs-<?= $v_bugs->bug_id ?>">
                                                        <td><a class="text-info" style="<?php
                                                            if ($v_bugs->bug_status == 'resolve') {
                                                                echo 'text-decoration: line-through;';
                                                            }
                                                            ?>" href="<?= base_url() ?>admin/bugs/view_bug_details/<?= $v_bugs->bug_id ?>"><?php echo $v_bugs->bug_title; ?></a>
                                                        </td>
                                                        </td>
                                                        <td><?php
                                                            if ($v_bugs->bug_status == 'unconfirmed') {
                                                                $label = 'warning';
                                                            } elseif ($v_bugs->bug_status == 'confirmed') {
                                                                $label = 'info';
                                                            } elseif ($v_bugs->bug_status == 'in_progress') {
                                                                $label = 'primary';
                                                            } else {
                                                                $label = 'success';
                                                            }
                                                            ?>
                                                            <span class="badge badge-soft-<?= $label ?>"><?= lang("$v_bugs->bug_status") ?></span>
                                                        </td>
                                                        <td><?= ucfirst($v_bugs->priority) ?></td>
                                                        <td>
                                                            <span class="badge btn-<?= $badge ?> "><?= $reporter->username ?></span>
                                                        </td>
                                                        <td>
                                                            <?php echo btn_edit('admin/bugs/index/' . $v_bugs->bug_id) ?>
                                                            <?php echo ajax_anchor(base_url("admin/bugs/delete_bug/" . $v_bugs->bug_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table-bugs-" . $v_bugs->bug_id)); ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- End Tasks Management-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var maxAppend = 0;
        $("#add_more").click(function () {
            if (maxAppend >= 4) {
                alert("Maximum 5 File is allowed");
            } else {
                var add_new = $('<div class="row mb-3" style="margin-bottom: 0px">\n\
                    <label for="field-1" class="col-sm-3 form-label"><?= lang('upload_file') ?></label>\n\
        <div class="col-sm-5">\n\
        <div class="fileinput fileinput-new" data-provides="fileinput">\n\
<span class="btn btn-default btn-file"><span class="fileinput-new" >Select file</span><span class="fileinput-exists" >Change</span><input type="file" name="task_files[]" ></span> <span class="fileinput-filename"></span><a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none;">&times;</a></div></div>\n\<div class="col-sm-2">\n\<strong>\n\
<a href="javascript:void(0);" class="remCF"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div>');
                maxAppend++;
                $("#add_new").append(add_new);
            }
        });

        $("#add_new").on('click', '.remCF', function () {
            $(this).parent().parent().parent().remove();
        });
    });
</script>