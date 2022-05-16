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
$where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $leads_details->leads_id, 'module_name' => 'leads');
$check_existing = $this->items_model->check_by($where, 'tbl_pinaction');
if (!empty($check_existing)) {
    $url = 'remove_todo/' . $check_existing->pinaction_id;
    $btn = 'danger';
    $title = lang('remove_todo');
} else {
    $url = 'add_todo_list/leads/' . $leads_details->leads_id;
    $btn = 'warning';
    $title = lang('add_todo_list');
}

$can_edit = $this->items_model->can_action('tbl_leads', 'edit', array('leads_id' => $leads_details->leads_id));
$can_delete = $this->items_model->can_action('tbl_leads', 'delete', array('leads_id' => $leads_details->leads_id));
$all_calls_info = $this->db->where('leads_id', $leads_details->leads_id)->get('tbl_calls')->result();
$all_meetings_info = $this->db->where('leads_id', $leads_details->leads_id)->get('tbl_mettings')->result();

$comment_details = $this->db->where(array('leads_id' => $leads_details->leads_id, 'comments_reply_id' => '0', 'task_attachment_id' => '0', 'uploaded_files_id' => '0'))->order_by('comment_datetime', 'DESC')->get('tbl_task_comment')->result();
$all_task_info = $this->db->where('leads_id', $leads_details->leads_id)->order_by('leads_id', 'DESC')->get('tbl_task')->result();
$activities_info = $this->db->where(array('module' => 'leads', 'module_field_id' => $leads_details->leads_id))->order_by('activity_date', 'DESC')->get('tbl_activities')->result();
$all_proposals_info = $this->db->where(array('module' => 'leads', 'module_id' => $leads_details->leads_id))->order_by('proposals_id', 'DESC')->get('tbl_proposals')->result();
$edited = can_action('55', 'edited');
$deleted = can_action('55', 'deleted');
?>

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="btn-toolbar p-3" role="toolbar">
                    <?php
                    if ($leads_details->converted_client_id == 0) {
                        if (!empty($can_edit) && !empty($edited)) { ?>
                            <a href="<?= base_url() ?>admin/leads/index/<?= $leads_details->leads_id ?>" class="btn btn-primary" title="" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit"><i class="fa fa-pencil-square-o"></i></a>
                        <?php } ?>
                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                        <span class="pull-right text-sm">
                            <a data-bs-toggle="modal" data-bs-target="#myModal_lg"  onclick="return confirm('Are you sure to <?= lang('convert') ?> This <?= $leads_details->lead_name ?> ?')" href="<?= base_url() ?>admin/leads/convert/<?= $leads_details->leads_id ?>" class="btn-xs btn btn-secondary ml"><i class="fa fa-copy"></i> <?= lang('convert_to_client') ?></a>
                        </span>
                        <?php  }   }

                    $notified_reminder = count($this->db->where(array('module' => 'leads', 'module_id' => $leads_details->leads_id, 'notified' => 'No'))->get('tbl_reminders')->result());
                    ?>
                </div>
                <div class="<?php if ($leads_details->converted_client_id == 0) { echo 'mt'; } ?> nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    
                    <a class="nav-link mb-2 <?= $active == 1 ? 'active' : '' ?>" data-bs-toggle="pill" href="#task_details" role="tab" aria-controls="task_details" aria-selected="false"><?= lang('project_details')?></a>
         
                    <a class="nav-link mb-2 <?= $active == 2 ? 'active' : '' ?>" data-bs-toggle="pill" href="#call" role="tab" aria-controls="call" aria-selected="false"><?= lang('call')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_calls_info) ? count($all_calls_info) : null) ?></strong></a>
                 
                    <a class="nav-link mb-2 <?= $active == 3 ? 'active' : '' ?>" data-bs-toggle="pill" href="#mettings" role="tab" aria-controls="mettings" aria-selected="false"><?= lang('mettings')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_meetings_info) ? count($all_meetings_info) : null) ?></strong></a>
         
                    <a class="nav-link mb-2 <?= $active == 4 ? 'active' : '' ?>" data-bs-toggle="pill" href="#task_comments" role="tab" aria-controls="task_comments" aria-selected="false"><?= lang('comments')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($comment_details) ? count($comment_details) : null) ?></strong></a>
        
                    <a class="nav-link mb-2 <?= $active == 5 ? 'active' : '' ?>" data-bs-toggle="pill" href="#task_attachments" role="tab" aria-controls="task_attachments" aria-selected="false"><?= lang('attachment')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($project_files_info) ? count($project_files_info) : null) ?></strong></a>

          
                    <a class="nav-link mb-2 <?= $active == 8 ? 'active' : '' ?>" data-bs-toggle="pill" href="#tasks" role="tab" aria-controls="tasks" aria-selected="false"><?= lang('tasks')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_task_info) ? count($all_task_info) : null) ?></strong></a>

                    <a class="nav-link mb-2 <?= $active == 8 ? 'active' : '' ?>" data-bs-toggle="pill" href="#proposals" role="tab" aria-controls="proposals" aria-selected="false"><?= lang('proposals')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_proposals_info) ? count($all_proposals_info) : null) ?></strong></a>

                    <a class="nav-link mb-2 <?= $url == 'reminder' ? 'active' : '' ?>" data-bs-toggle="pill" href="#reminder" role="tab" aria-controls="reminder" aria-selected="false"><?= lang('reminder')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($notified_reminder) ? $notified_reminder : null) ?></strong></a>

                    <a class="nav-link mb-2 <?= $active == 6 ? 'active' : '' ?>" data-bs-toggle="pill" href="#activities" role="tab" aria-controls="activities" aria-selected="false"><?= lang('activities')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($activities_info) ? count($activities_info) : null) ?></strong></a>
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
                        <div class="card-body">
                            <div class="pull-right text-sm">
                                <?php
                                if ($leads_details->converted_client_id == 0) {
                                    if (!empty($can_edit) && !empty($edited)) { ?>
                                        <a class="btn btn-primary btn-sm " href="<?= base_url() ?>admin/leads/index/<?= $leads_details->leads_id ?>"><?= lang('edit') . ' ' . lang('leads') ?></a>
                                    <?php }
                                } else {
                                    $c_edited = can_action('4', 'edited');
                                    if (!empty($c_edited)) {
                                        ?>
                                        <a href="<?php echo base_url() ?>admin/client/manage_client/<?= $leads_details->converted_client_id ?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> <?= lang('edit') . ' ' . lang('client') ?></a>
                                    <?php }
                                } ?>

                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $title ?>" href="<?= base_url() ?>admin/projects/<?= $url ?>" class="btn-sm btn btn-<?= $btn ?>"><i class="fa fa-thumb-tack"></i></a>

                            </div>
                            <h4 class="card-title mb-4"> <?php if (!empty($leads_details->lead_name)) { echo $leads_details->lead_name; } ?></h4>
                            
                            <div class="row form-horizontal task_details">
                                 <div class="row">
                                    <div class="col-lg-6">
                                        <p class="lead bb"></p>
                                        <form class="form-horizontal p-20">

                                            <?php super_admin_details($leads_details->companies_id, 5,6) ?>

                                            <div class="row mb-3">
                                                <div class="col-md-5 col-5"><strong><?= lang('lead_source') ?> :</strong></div>
                                                <div class="col-md-6 col-6">
                                                    <?php
                                                    if (!empty($leads_details->lead_source_id)) {
                                                        $lead_source = $this->db->where('lead_source_id', $leads_details->lead_source_id)->get('tbl_lead_source')->row();
                                                        if (!empty($lead_source->lead_source)) {
                                                            ?>
                                                            <span class="badge badge-soft-info form-control-static"><?php echo $lead_source->lead_source; ?></span>

                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>


                                            <div class="row mb-3">
                                                <div class="col-md-5 col-5"><strong><?= lang('lead_status') ?>
                                                        :</strong></div>
                                                <div class="col-md-3 col-3">
                                                    <?php
                                                    if (!empty($leads_details->lead_status_id)) {
                                                        $lead_status = $this->db->where('lead_status_id', $leads_details->lead_status_id)->get('tbl_lead_status')->row();

                                                        if ($lead_status->lead_type == 'open') {
                                                            $status = "<span class='badge badge-soft-success'>" . lang($lead_status->lead_type) . "</span>";
                                                        } else {
                                                            $status = "<span class='badge badge-soft-warning'>" . lang($lead_status->lead_type) . "</span>";
                                                        } ?>
                                                        <span class="form-control-static"><?= $status . ' ' . $lead_status->lead_status ?></span>
                                                    <?php }
                                                    ?>
                                                </div>
                                                <?php
                                                if ($leads_details->converted_client_id == 0) {
                                                if (!empty($can_edit) && !empty($edited)) {
                                                    ?>
                                                <div class="col-md-1 col-1">
                                                    <div class="btn-group" role="group">
                                                        <button id="btnGroupVerticalDrop1" type="button" class="btn btn-success dropdown-toggle font-size-11 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?= lang('change') ?>"><i class="bx bxs-edit-alt"></i><i class="mdi mdi-chevron-down"></i></button>

                                                        <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                                            <?php
                                                            $status_info = get_result('tbl_lead_status');
                                                            if (!empty($status_info)) {
                                                                foreach ($status_info as $v_status) {
                                                                    ?>
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/leads/change_status/<?= $leads_details->leads_id ?>/<?= $v_status->lead_status_id ?>"><?= lang($v_status->lead_type) . '-' . $v_status->lead_status ?></a>
                                                             
                                                             <?php
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } } ?>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-5 col-5"><strong><?= lang('email') ?> : </strong></div>
                                                <div class="col-md-6 col-6">
                                                    <?php
                                                    if (!empty($leads_details->email)) {
                                                        echo $leads_details->email;
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-5 col-5"><strong><?= lang('mobile') ?> :</strong> </div>
                                                <div class="col-md-6 col-6">
                                                    <?php
                                                    if (!empty($leads_details->mobile)) {
                                                        echo $leads_details->mobile;
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-5 col-5"><strong><?= lang('city') ?>: </strong></div>
                                                <div class="col-md-6 col-6">
                                                    <?php
                                                    if (!empty($leads_details->city)) {
                                                        echo $leads_details->city;
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-5 col-5"><strong><?= lang('facebook_profile_link') ?>:</strong></div>
                                                <div class="col-md-6 col-6">
                                                    <a target="_blank" href="//<?php
                                                        if (!empty($leads_details->facebook)) {
                                                            echo $leads_details->facebook;
                                                        } ?>">
                                                        <?php
                                                        if (!empty($leads_details->facebook)) {
                                                            echo $leads_details->facebook;
                                                        }
                                                        ?>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-5 col-5"><strong><?= lang('skype_id') ?>: </strong></div>
                                                <div class="col-md-6 col-6">
                                                    <a href="skype:'<?php
                                                        if (!empty($leads_details->skype)) {
                                                        echo $leads_details->skype;
                                                        } ?>'">
                                                        <?php
                                                        if (!empty($leads_details->skype)) {
                                                            echo $leads_details->skype;
                                                        }
                                                        ?>        
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-lg-6">
                                        <p class="lead bb"></p>
                                        <form class="form-horizontal p-20">
                                            <div class="row mb-3">
                                                <div class="col-md-5 col-5"><strong><?= lang('lead_name') ?> :</strong></div>
                                                <div class="col-md-6 col-6">
                                                    <?php
                                                    if (!empty($leads_details->lead_name)) {
                                                        echo $leads_details->lead_name;
                                                    } ?>        
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-5 col-5"><strong><?= lang('contact_name') ?> :</strong></div>
                                                <div class="col-md-6 col-6">
                                                    <?php
                                                    if (!empty($leads_details->contact_name)) {
                                                        echo $leads_details->contact_name;
                                                    }
                                                    ?>
                                                </div>
                                            </div>


                                            <div class="row mb-3">
                                                <div class="col-md-5 col-5"><strong><?= lang('organization') ?> :</strong></div>
                                                <div class="col-md-6 col-6">
                                                    <?php
                                                    if (!empty($leads_details->organization)) {
                                                        echo $leads_details->organization;
                                                    }
                                                    ?>        
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-5 col-5"><strong><?= lang('phone') ?> : </strong></div>
                                                <div class="col-md-6 col-6">
                                                    <?php
                                                    if (!empty($leads_details->phone)) {
                                                        echo $leads_details->phone;
                                                    }
                                                    ?>
                                                </div>
                                            </div>


                                            <div class="row mb-3">
                                                <div class="col-md-5 col-5"><strong><?= lang('address') ?> :</strong></div>
                                                <div class="col-md-6 col-6">
                                                    <?php
                                                    if (!empty($leads_details->address)) {
                                                        echo $leads_details->address;
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-5 col-5"><strong><?= lang('state') ?>: </strong></div>
                                                <div class="col-md-6 col-6">
                                                    <?php
                                                    if (!empty($leads_details->state)) {
                                                        echo $leads_details->state;
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-5 col-5"><strong><?= lang('twitter_profile_link') ?> : </strong></div>
                                                <div class="col-md-6 col-6">
                                                    <a target="_blank" href="//<?php
                                                        if (!empty($leads_details->twitter)) {
                                                            echo $leads_details->twitter;
                                                            } ?>"><?php
                                                        if (!empty($leads_details->twitter)) {
                                                            echo $leads_details->twitter;
                                                        }
                                                        ?>        
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                              
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php $show_custom_fields = custom_form_label(5, $leads_details->leads_id);
                                        if (!empty($show_custom_fields)) {
                                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                                if (!empty($v_fields)) {
                                                    if (count($v_fields) == 1) {
                                                        $col = 'col-md-10';
                                                        $sub_col = 'col-md-3';
                                                        $style = 'padding-left:21px';
                                                    } else {
                                                        $col = 'col-md-6';
                                                        $sub_col = 'col-md-5 col-5';
                                                        $style = null;
                                                    }
                                                    ?>
                                                    <div class="row mb-3  <?= $col ?>" style="<?= $style ?>">
                                                        <div class="<?= $sub_col ?>"><strong><?= $c_label ?> :</strong></div>
                                                        <div class="col-md-6 col-6"><strong><?= $v_fields ?></strong></div>
                                                    </div>
                                                <?php }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php if ($leads_details->permission != '-') { ?>
                                    <div class="col-lg-6">
                                        <div class="row mb-3">
                                            <div class="col-md-5 col-5"><strong><?= lang('participants') ?> :</strong></div>
                                            <div class="col-md-6 col-6">
                                                <div class="avatar-group">
                                                    <?php
                                                    if ($leads_details->permission != 'all') {
                                                        $get_permission = json_decode($leads_details->permission);
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

                                                    <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($leads_details->converted_client_id == 0) { ?>
                                                    <?php
                                                    if (!empty($can_edit) && !empty($edited)) {
                                                    ?>
                                                    <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?= lang('add_more') ?>" class="mt-2">
                                                        <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/leads/update_users/<?= $leads_details->leads_id ?>" class="text-default"><i class="fa fa-plus"></i></a>
                                                    </span>
                                                       
                                                    <?php
                                                    }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-lg-6">
                                        <blockquote style="font-size: 12px;"><?php
                                        if (!empty($leads_details->notes)) {
                                            echo $leads_details->notes;
                                        }
                                        ?> </blockquote>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Details tab Ends -->
                    <!-- calls Panel Starts --->
                    <div class=" tab-pane <?= $active == 2 ? 'active' : '' ?>" id="call" style="position:relative;">
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
                                        <table class="table table-striped dt-responsive nowrap w-100" id="call_manage_datatable">
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
                                                    <tr id="leads_call_<?= $leads_details->leads_id ?>">
                                                        <td><?= display_datetime($v_calls->date) ?></td>
                                                        <td><?= $v_calls->call_summary ?></td>
                                                        <td>
                                                            <?php
                                                            if (!empty($client_info)) {
                                                                $client_info->name;
                                                            }
                                                            ?></td>
                                                        <td><?= $user->username ?></td>
                                                        <td>
                                                            <?= btn_edit('admin/leads/leads_details/' . $leads_details->leads_id . '/call/' . $v_calls->calls_id) ?>
                                                            <?php echo ajax_anchor(base_url("admin/leads/delete_leads_call/" . $leads_details->leads_id . '/' . $v_calls->calls_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#leads_call_" . $leads_details->leads_id)); ?>
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
                                   
                                    <form role="form" enctype="multipart/form-data" id="form" action="<?php echo base_url(); ?>admin/leads/saved_call/<?= $leads_details->leads_id ?>/<?php  if (!empty($call_info)) { echo $call_info->calls_id; } ?>" method="post"  class="form-horizontal">

                                        <div class="row mb-3">
                                            <label class="col-xl-3 col-form-label"><?= lang('date') ?><span class="text-danger"> *</span></label>
                                            <div class="col-xl-5">
                                                <div class="input-group" >
                                                    <input type="text" required="" name="date" class="form-control datepicker" autocomplete="off" data-date-format="<?= config_item('date_picker_format'); ?>" value="<?php
                                                           if (!empty($call_info->date)) {
                                                                echo date('d-m-Y H-i', strtotime($call_info->date));
                                                            } else {
                                                                echo date('d-m-Y H-i');
                                                            }
                                                           ?>">
                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- End discount Fields -->
                                        <div class="row mb-3 terms">
                                            <label class="col-xl-3 col-form-label"><?= lang('call_summary') ?><span class="text-danger"> *</span> </label>
                                            <div class="col-xl-5">
                                                <input type="text" required="" name="call_summary"
                                                       class="form-control"
                                                       value="<?php
                                                       if (!empty($call_info->call_summary)) {
                                                           echo $call_info->call_summary;
                                                       }
                                                       ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-xl-3 col-form-label"><?= lang('contact') ?></label>
                                            <div class="col-xl-5">
                                                <select name="client_id" class="form-control select_box"
                                                        style="width: 100%">
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
                                            <label class="col-xl-3 col-form-label"><?= lang('responsible') ?><span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-xl-5">
                                                <select name="user_id" class="form-control select_box"
                                                        style="width: 100%"
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
                                            <label class="col-xl-3 col-form-label"></label>
                                            <div class="col-xl-5">
                                                <button type="submit"  class="btn btn-xs btn-primary"><?= lang('updates') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Calls Panel Ends--->
                    <!-- meeting Panel Starts --->
                    <div class="tab-pane <?= $active == 3 ? 'active' : '' ?>" id="mettings"
                         style="position: relative;">
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
                                            <table class="table table-striped dt-responsive nowrap w-100" id="all_meeting_datatble">
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
                                                if (!empty($all_meetings_info)):
                                                    foreach ($all_meetings_info as $v_mettings):
                                                        $user = $this->items_model->check_by(array('user_id' => $v_mettings->user_id), 'tbl_users');
                                                        ?>
                                                        <tr id="leads_meetings_<?= $leads_details->leads_id ?>">
                                                            <td><?= $v_mettings->meeting_subject ?></td>
                                                            <td><?= display_datetime($v_mettings->start_date) ?></td>
                                                            <td><?= display_datetime($v_mettings->end_date) ?></td>
                                                            <td><?= $user->username ?></td>
                                                            <td>
                                                                <?= btn_edit('admin/leads/leads_details/' . $leads_details->leads_id . '/metting/' . $v_mettings->mettings_id) ?>
                                                                <?php echo ajax_anchor(base_url("admin/leads/delete_leads_mettings/" . $leads_details->leads_id . '/' . $v_mettings->mettings_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#leads_meetings_" . $leads_details->leads_id)); ?>
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
                                        <form role="form" enctype="multipart/form-data" id="form"
                                              action="<?php echo base_url(); ?>admin/leads/saved_metting/<?= $leads_details->leads_id ?>/<?php
                                              if (!empty($mettings_info)) {
                                                  echo $mettings_info->mettings_id;
                                              }
                                              ?>" method="post" class="form-horizontal  ">
                                            <div class="row mb-3 terms">
                                                <label class="col-xl-3 col-form-label"><?= lang('metting_subject') ?>
                                                    <span  class="text-danger"> *</span> </label>
                                                <div class="col-xl-9">
                                                    <input type="text" required="" name="meeting_subject"
                                                           class="form-control"
                                                           value="<?php
                                                           if (!empty($mettings_info->meeting_subject)) {
                                                               echo $mettings_info->meeting_subject;
                                                           }
                                                           ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-xl-3 col-form-label"><?= lang('start_date') ?><span class="text-danger"> *</span></label>
                                                <div class="col-xl-9">
                                                    <div class="input-group" id="start_date_datepicker">
                                                        <input type="text" name="start_date" class="form-control datepicker" autocomplete="off" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container='#start_date_datepicker' value="<?php
                                                              if (!empty($mettings_info->start_date)) {
                                                            echo date('d-m-Y H-i', strtotime($mettings_info->start_date));
                                                        } else {
                                                            echo date('d-m-Y H-i');
                                                        } ?>">
                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-xl-3 col-form-label"><?= lang('end_date') ?><span class="text-danger"> *</span></label>
                                                <div class="col-xl-9">
                                                    <div class="input-group" id="end_date_datepicker">
                                                        <input type="text" name="end_date" class="form-control datepicker" autocomplete="off" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container='#end_date_datepicker' value="<?php
                                                        if (!empty($mettings_info->end_date)) {
                                                            echo date('d-m-Y H-i', strtotime($mettings_info->end_date));
                                                        } ?>">
                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-xl-3 col-form-label"><?= lang('attendess') ?><span
                                                            class="text-danger"> *</span></label>
                                                <div class="col-xl-9">
                                                    <select multiple="multiple" name="attendees[]" style="width: 100%"
                                                            class="select_multi" required="">
                                                        <option
                                                                value=""><?= lang('select') . lang('attendess') ?></option>
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
                                                <label class="col-xl-3 col-form-label"><?= lang('responsible') ?><span
                                                            class="text-danger"> *</span></label>
                                                <div class="col-xl-9">
                                                    <select name="user_id" class="form-control select_box"
                                                            style="width: 100%"
                                                            required="">
                                                        <option value=""><?= lang('admin_staff') ?></option>
                                                        <?php
                                                        $responsible_user_info = get_result('tbl_users', array('role_id !=' => 2));
                                                        if (!empty($responsible_user_info)) {
                                                            foreach ($responsible_user_info as $v_responsible_user) {
                                                                ?>
                                                                <option
                                                                        value="<?= $v_responsible_user->user_id ?>" <?php
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
                                                <label class="col-xl-3 col-form-label"><?= lang('location') ?><span
                                                            class="text-danger"> *</span> </label>
                                                <div class="col-xl-9">
                                                    <input type="text" required="" name="location" class="form-control"
                                                           value="<?php
                                                           if (!empty($mettings_info->location)) {
                                                               echo $mettings_info->location;
                                                           }
                                                           ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3 terms">
                                                <label
                                                        class="col-xl-3 col-form-label"><?= lang('description') ?> </label>
                                                <div class="col-lg-8">
                                                        <textarea name="description" class="form-control"><?php
                                                            if (!empty($mettings_info)) {
                                                                echo $mettings_info->description;
                                                            }
                                                            ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-xl-3 col-form-label"></label>
                                                <div class="col-xl-5">
                                                    <button type="submit"
                                                            class="btn btn-xs btn-primary"><?= lang('updates') ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <!--  Comments Panel Starts --->
                    <?php $comment_type = 'leads'; ?>
                    <div class="tab-pane <?= $active == 4 ? 'active' : '' ?>" id="task_comments" style="position: relative;">
                        <div class="card-body">    
                            <h4 class="card-title mb-4"><?= lang('comments') ?></h4>
                            <div class="chat" id="chat-box">

                                <?php echo form_open(base_url("admin/leads/save_comments"), array("id" => $comment_type . "-comment-form", "class" => "form-horizontal general-form", "enctype" => "multipart/form-data", "role" => "form")); ?>

                                <input type="hidden" name="leads_id" value="<?php
                                if (!empty($leads_details->leads_id)) {
                                    echo $leads_details->leads_id;
                                }
                                ?>" class="form-control">

                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <?php
                                        echo form_textarea(array(
                                            "id" => "comment_description",
                                            "name" => "comment",
                                            "class" => "form-control comment_description",
                                            "placeholder" => $leads_details->lead_name . ' ' . lang('comments'),
                                            "data-rule-required" => true,
                                            "rows" => 4,
                                            "data-msg-required" => lang("field_required"),
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <div id="new_comments_attachement">
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
                                $comment_reply_type = 'leads-reply';
                                ?>
                                <?php $this->load->view('admin/leads/comments_list', array('comment_details' => $comment_details)) ?>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $("#<?php echo $comment_type; ?>-comment-form").appForm({
                                            isModal: false,
                                            onSuccess: function (result) {
                                                $(".comment_description").val("");
                                                $(".dz-complete").remove();
                                                $('#btn-<?php echo $comment_type ?>').removeClass("disabled").html('<?= lang('post_comment')?>');
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
                    <div class="tab-pane <?= $active == 5 ? 'active' : '' ?>" id="task_attachments">
                        <div class="card-body">    
                            <div class="row">
                                <div class="col-md-4 col-7">
                                    <h4 class="card-title mt"><?= lang('attach_file_list') ?> </h4>
                                </div>
                                <div class="col-md-8 col-5">
                                   <?php
                                    $attach_list = $this->session->userdata('leads_media_view');
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
                                            <a href="<?= base_url() ?>admin/leads/new_attachment/<?= $leads_details->leads_id ?>" class="text-purple text-sm" data-bs-toggle="modal" data-bs-placement="top"  data-bs-target="#myModal_extra_lg">
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
                                            var module = 'leads';
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
                                        <?php $this->load->view('admin/leads/attachment_list', array('project_files_info' => $project_files_info)) ?>
                                    </div>
                                </div>
                                <div class="media-list-container <?= (!empty($attach_list) && $attach_list == 'list_view' ? 'hidden' : '') ?>">
                                    <div class="col-md-12 pr0 mb-sm">
                                        <div class="card shadow border">
                                            <div class="card-body">
                                                <div class="accordion accordion-flush" id="accordionFlushExample"><?php
                                                if (!empty($project_files_info)) {
                                                    foreach ($project_files_info as $key => $v_files_info) {
                                                        ?>
                                                    <div class="accordion-item" id="media_list_container-<?= $files_info[$key]->task_attachment_id ?>">
                                                        <h2 class="card-title accordion-header" id="flush-headingOne">        
                                                            <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#attachment-<?php echo $key ?>" aria-expanded="true" aria-controls="flush-collapseOne">
                                                                <span style="width:80%"><?php echo $files_info[$key]->title; ?></span>
                                                                <div class="pull-right" style="margin-left:15%">
                                                                    <?php if ($files_info[$key]->user_id == $this->session->userdata('user_id')) { ?>
                                                                        <?php echo ajax_anchor(base_url("admin/leads/delete_files/" . $files_info[$key]->task_attachment_id), "<i class='text-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#media_list_container-" . $files_info[$key]->task_attachment_id)); ?>
                                                                    <?php } ?>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="attachment-<?php echo $key ?>" class="accordion-collapse collapse <?php if (!empty($in) && $files_info[$key]->files_id == $in) { echo 'show'; } ?>" aria-labelledby="flush-headingOne"  data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body text-muted">
                                                                <div class="table-responsive">
                                                                    <table id="leads_files_datatable" class="table table-striped dt-responsive nowrap w-100 ">
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
                                                                                                data-bs-target="#myModal_extra_lg"
                                                                                                href="<?= base_url() ?>admin/leads/attachment_details/r/<?= $files_info[$key]->task_attachment_id . '/' . $v_files->uploaded_files_id ?>">
                                                                                            <img
                                                                                                    style="width: 50px;border-radius: 5px;"
                                                                                                    src="<?= base_url() . $v_files->files ?>"/></a>
                                                                                    </div>
                                                                                <?php else : ?>
                                                                                    <div class="file-icon"><i
                                                                                                class="fa fa-file-o"></i>
                                                                                        <a data-bs-toggle="modal"
                                                                                           data-bs-target="#myModal_extra_lg"
                                                                                           href="<?= base_url() ?>admin/leads/attachment_details/r/<?= $files_info[$key]->task_attachment_id . '/' . $v_files->uploaded_files_id ?>"><?= $v_files->file_name ?></a>
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
                                                                                   href="<?= base_url() ?>admin/leads/download_files/<?= $v_files->uploaded_files_id ?>"><i
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
                                                <?php  } } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 7 ? 'active' : '' ?>" id="activities"
                         style="position: relative;">
                        <div class="card-body">
                            <?php
                            $role = $this->session->userdata('user_type');
                            if ($role == 1) {
                                ?>
                                <span class="btn-sm pull-right mt">
                            <a href="<?= base_url() ?>admin/tasks/claer_activities/leads/<?= $leads_details->leads_id ?>"><?= lang('clear') . ' ' . lang('activities') ?></a>
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
                                        <?php } } ?>
                                    </ul>
                                </div>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 8 ? 'active' : '' ?>" id="tasks" style="position: relative;">
                        <!-- Tabs within a box -->
                        <ul class="nav nav-tabs bg-light rounded">
                            <li class="nav-item">
                                <a class="nav-link <?= $sub_active == 1 ? 'active' : ''; ?>" href="#manageTasks" data-bs-toggle="tab">
                                    <?= lang('all_task') ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url() ?>admin/tasks/new_tasks/leads/<?= $leads_details->leads_id ?>">
                                    <?= lang('new_task') ?>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content p-3 text-muted">
                            <!-- ************** general *************-->
                            <div class="tab-pane <?= $sub_active == 1 ? 'active' : ''; ?>" id="manageTasks" style="position: relative;">
                                    <div class="table-responsive">
                                        <table id="leads_tasks_datatable" class="table table-striped dt-responsive nowrap w-100">
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
                                                <tr id="leads_tasks_<?= $v_task->task_id ?>">
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
                                                        ?>"
                                                           href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
                                                    </td>
                                                    <td><?php
                                                        $due_date = $v_task->due_date;
                                                        $due_time = strtotime($due_date);
                                                        $current_time = time();
                                                        ?>
                                                        <?= display_datetime($due_date) ?>
                                                        <?php if ($current_time > $due_time && $v_task->task_progress < 100) { ?>
                                                            <span
                                                                    class="badge badge-soft-danger"><?= lang('overdue') ?></span>
                                                        <?php } ?>
                                                    </td>
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
                                                        <span
                                                                class="badge badge-soft-<?= $label ?>"><?= lang($v_task->task_status) ?> </span>
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
                                                        <?php echo ajax_anchor(base_url("admin/tasks/delete_task/" . $v_task->task_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#leads_tasks_" . $v_task->task_id)); ?>
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
                    <div class="tab-pane <?= $active == 9 ? 'active' : '' ?>" id="proposals" style="position: relative;">
                        <!-- Tabs within a box -->
                        <ul class="nav nav-tabs bg-light rounded">
                            <li class="nav-item">
                                <a class="nav-link <?= $sub_active == 1 ? 'active' : ''; ?>" href="#manageProposals" data-bs-toggle="tab">
                                    <?= lang('all_proposals') ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url() ?>admin/proposals/index/leads/<?= $leads_details->leads_id ?>">
                                    <?= lang('create_proposal') ?>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content p-3 text-muted">
                            <!-- ************** general *************-->
                            <div class="tab-pane <?= $sub_active == 1 ? 'active' : ''; ?>" id="manageProposals" style="position: relative;">
                                <div class="table-responsive">
                                    <table class="table table-striped dt-responsive nowrap w-100" id="proposals_datatable">
                                        <thead>
                                        <tr>
                                            <th><?= lang('proposal') ?> #</th>
                                            <th><?= lang('proposal_date') ?></th>
                                            <th><?= lang('expire_date') ?></th>
                                            <th><?= lang('status') ?></th>
                                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                <th class="hidden-print"><?= lang('action') ?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($all_proposals_info)) {
                                                foreach ($all_proposals_info as $v_proposals) {
                                                    $can_edit = $this->items_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $v_proposals->proposals_id));
                                                    $can_delete = $this->items_model->can_action('tbl_proposals', 'delete', array('proposals_id' => $v_proposals->proposals_id));

                                                    if ($v_proposals->status == 'pending') {
                                                        $label = "info";
                                                    } elseif ($v_proposals->status == 'accepted') {
                                                        $label = "success";
                                                    } else {
                                                        $label = "danger";
                                                    }
                                                    ?>
                                            <tr id="leads_proposals_<?= $v_proposals->proposals_id ?>">
                                                <td>
                                                    <a class="text-info"
                                                       href="<?= base_url() ?>admin/proposals/index/proposals_details/<?= $v_proposals->proposals_id ?>"><?= $v_proposals->reference_no ?></a>
                                                    <?php if ($v_proposals->convert == 'Yes') {
                                                        if ($v_proposals->convert_module == 'invoice') {
                                                            $c_url = base_url() . 'admin/invoice/manage_invoice/invoice_details/' . $v_proposals->convert_module_id;
                                                            $text = lang('invoiced');
                                                        } else {
                                                            $text = lang('estimated');
                                                            $c_url = base_url() . 'admin/estimates/index/estimates_details/' . $v_proposals->convert_module_id;
                                                        }
                                                        if (!empty($c_url)) { ?>
                                                            <p class="text-sm m0 p0">
                                                                <a class="text-success"
                                                                   href="<?= $c_url ?>">
                                                                    <?= $text ?>
                                                                </a>
                                                            </p>
                                                        <?php }
                                                    } ?>
                                                </td>
                                                <td><?= display_datetime($v_proposals->proposal_date) ?></td>
                                                <td><?= display_datetime($v_proposals->due_date) ?>
                                                    <?php
                                                    if (strtotime($v_proposals->due_date) < time() AND $v_proposals->status == 'pending' || strtotime($v_proposals->due_date) < time() AND $v_proposals->status == ('draft')) { ?>
                                                        <span
                                                                class="badge badge-soft-danger "><?= lang('expired') ?></span>
                                                    <?php }
                                                    ?>
                                                </td>
                                                <td><span  class="badge badge-soft-<?= $label ?>"><?= lang($v_proposals->status) ?></span>
                                                </td>
                                                <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                <td>
                                                    <div class="dropdown">
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                        <?= btn_edit('admin/proposals/index/edit_proposals/' . $v_proposals->proposals_id) ?>
                                                    <?php }
                                                    if (!empty($can_delete) && !empty($deleted)) {
                                                        ?>
                                                        <?php echo ajax_anchor(base_url("admin/proposals/delete/delete_proposals/" . $v_proposals->proposals_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#leads_proposals_" . $v_proposals->proposals_id)); ?>
                                                    <?php } ?>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                         <a data-bs-toggle="tooltip" data-bs-placement="top"  href="<?= base_url() ?>admin/proposals/index/email_proposals/<?= $v_proposals->proposals_id ?>" title="<?= lang('send_email') ?>" class="btn btn-sm btn-outline-success"><i class="fa fa-envelope"></i></a>
                                                        
                                                        <a data-bs-toggle="tooltip" data-bs-placement="top" href="<?= base_url() ?>admin/proposals/index/proposals_details/<?= $v_proposals->proposals_id ?>" title="<?= lang('view_details') ?>" class="btn btn-sm btn-outline-primary"><i class="fa fa-list-alt"></i></a>

                                                        <a data-bs-toggle="tooltip" data-bs-placement="top" href="<?= base_url() ?>admin/proposals/index/proposals_history/<?= $v_proposals->proposals_id ?>" title="<?= lang('history') ?>" class="btn btn-sm btn-outline-secondary"><i class="fa fa-building"></i></a>

                                                        <button class="btn btn-outline-primary dropdown-toggle btn-sm" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_status') ?><i class="mdi mdi-chevron-down"></i></button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item"   href="<?= base_url() ?>admin/proposals/change_status/declined/<?= $v_proposals->proposals_id ?>"><?= lang('declined') ?></a>
                                                            <a class="dropdown-item"   href="<?= base_url() ?>admin/proposals/change_status/accepted/<?= $v_proposals->proposals_id ?>"><?= lang('accepted') ?></a>
                                                        </div>
                                                    <?php } ?>
                                                    </div>
                                                </td>
                                                <?php } ?>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 10 ? 'active' : '' ?>" id="reminder">
                        <!-- Tabs within a box -->
                        <ul class="nav nav-tabs bg-light rounded">
                            <li class="nav-item">
                                <a class="nav-link active" href="#reminder_manage" data-bs-toggle="tab">
                                    <?= lang('reminder') . ' ' . lang('list') ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#reminder_create" data-bs-toggle="tab">
                                    <?= lang('set') . ' ' . lang('reminder') ?>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content p-3 text-muted">
                            <!-- ************** general *************-->
                            <div class="tab-pane active" id="reminder_manage">
                                    <div class="table-responsive">
                                        <table class="table table-striped dt-responsive nowrap w-100" id="reminder_manage_datatable">            
                                            <thead>
                                            <tr>
                                                <th><?= lang('description') ?></th>
                                                <th><?= lang('date') ?></th>
                                                <th><?= lang('remind') ?></th>
                                                <th><?= lang('notified') ?></th>
                                                <th class="col-options no-sort"><?= lang('action') ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $all_reminder = $this->db->where(array('module' => 'leads', 'module_id' => $leads_details->leads_id))->get('tbl_reminders')->result();
                                                if (!empty($all_reminder)) {
                                                    foreach ($all_reminder as $v_reminder):
                                                        $remind_user_info = $this->db->where('user_id', $v_reminder->user_id)->get('tbl_account_details')->row();
                                                        ?>
                                                        <tr id="leads_reminder_<?= $v_reminder->reminder_id ?>">
                                                            <td><?= $v_reminder->description ?></td>
                                                            <td><?= display_datetime($v_reminder->date) ?></td>
                                                            <td>
                                                                <a href="<?= base_url() ?>admin/user/user_details/<?= $v_reminder->user_id ?>"> <?= $remind_user_info->fullname ?></a>
                                                            </td>
                                                            <td><?= $v_reminder->notified ?></td>
                                                            <td>
                                                                <?php echo ajax_anchor(base_url("admin/invoice/delete_reminder/" . $v_reminder->module . '/' . $v_reminder->module_id . '/' . $v_reminder->reminder_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#leads_reminder_" . $v_reminder->reminder_id)); ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; }  ?>
                                            </tbody>
                                        </table>
                                    </div>
                            </div>
                            <div class="tab-pane" id="reminder_create">
                                <div class="card-body">
                                    <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form" action="<?php echo base_url(); ?>admin/invoice/reminder/leads/<?= $leads_details->leads_id ?>/<?php if (!empty($reminder_info)) {
                                          echo $reminder_info->reminder_id; }  ?>" method="post" class="form-horizontal  ">
                                        <div class="row mb-3">
                                            <label class="col-xl-3 col-form-label"><?= lang('date_to_notified') ?> <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-xl-5">
                                                <div class="input-group"  id="date_datepicker">
                                                    <input type="text" name="date" class="form-control datepicker" autocomplete="off" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container='#date_datepicker' value="<?php
                                                           if (!empty($reminder_info->date)) {
                                                               echo date('d-m-Y H-i', strtotime($reminder_info->date));
                                                           } else {
                                                               echo date('d-m-Y H-i');
                                                           }
                                                           ?>" data-date-min-date="<?= date('d-m-Y H-i'); ?>">
                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End discount Fields -->
                                        <div class="row mb-3 terms">
                                            <label class="col-xl-3 col-form-label"><?= lang('description') ?> </label>
                                            <div class="col-xl-5">
                                                <textarea name="description" class="form-control"><?php
                                                    if (!empty($reminder_info)) {
                                                        echo $reminder_info->description;
                                                    }
                                                    ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-xl-3 col-form-label"><?= lang('set_reminder_to') ?> <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-xl-5">
                                                <select class="form-control select_box" name="user_id" style="width: 100%">
                                                    <?php
                                                    $all_user = get_result('tbl_users', array('role_id !=' => 2));;
                                                    foreach ($all_user as $v_users) {
                                                        ?>
                                                        <option <?php
                                                        if (!empty($reminder_info)) {
                                                            echo $reminder_info->user_id == $v_users->user_id ? 'selected' : null;
                                                        }
                                                        ?> value="<?= $v_users->user_id ?>"><?= fullname($v_users->user_id) ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3 terms">
                                            <label class="col-xl-3 col-form-label"></label>
                                            <div class="col-xl-5">
                                                <div class="form-check form-check-primary mb-3">
                                                    <input type="checkbox" value="Yes"
                                                            <?php if (!empty($reminder_info) && $reminder_info->notify_by_email == 'Yes') {
                                                                echo 'checked';
                                                            } ?> name="notify_by_email" class="form-check-input" id="send_reminder">
                                                    <label class="form-check-label" for="send_reminder">
                                                        <?= lang('send_also_email_this_reminder') ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-xl-3 col-form-label"></label>
                                            <div class="col-xl-5">
                                                <button type="submit" class="btn btn-secondary"><?= lang('upload') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('upload_file') ?></label>\n\
        <div class="col-md-5">\n\
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