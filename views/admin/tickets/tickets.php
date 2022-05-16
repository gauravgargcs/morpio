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
<style type="text/css">
    #sparkline2 canvas{
        width: 150px !important;
    }
</style>
<?php
$answered = 0;
$closed = 0;
$open = 0;
$in_progress = 0;

$progress_tickets_info = $this->tickets_model->get_permission('tbl_tickets');
// 30 days before

for ($iDay = 30; $iDay >= 0; $iDay--) {
    $date = date('Y-m-d', strtotime('today - ' . $iDay . 'days'));
    $where = array('created >=' => $date . " 00:00:00", 'created <=' => $date . " 23:59:59");
    $tickets_result[$date] = count(get_result('tbl_tickets', $where));
}
if (!empty($progress_tickets_info)) : foreach ($progress_tickets_info as $v_tickets) :
        $t_status=$v_tickets->status;
        if($t_status=='answered'){
            $answered += count($t_status);
        }
        if($t_status=='closed'){
            $closed += count($t_status);
        }
        if($t_status=='open'){
            $open += count($t_status);
        }
        if($t_status=='in_progress'){
            $in_progress += count($t_status);
        }
      
    endforeach;
endif;
if ($this->session->userdata('user_type') == 1) {
    $margin = 'margin-bottom:30px';
?>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
          
            <?php $all_status = get_result('tbl_status');
            foreach ($all_status as $key => $status) {
                $tick_status=$status->status; 
            ?>
            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-0"><?= $$tick_status; ?></h5>
                        <p class="text-muted text-truncate mb-2">
                            <small><a href="<?= base_url('admin/tickets/index/' . $status->status) ?>"> <?= $status->status . ' ' . lang('tickets') ?></a> </small>
                        </p>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                        <div class="col-xs-6 pt">
                            <div id="sparkline2">
                            </div>
                            <p class="m0">
                                <small>
                                    <?= lang('last_30_days') ?>
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }
$status = $this->uri->segment(3);

if (!empty($status)) {
    $m_id = 7;
    if ($status == 'answered') {
        $m_id = 8;
    }
    if ($status == 'open') {
        $m_id = 9;
    }
    if ($status == 'in_progress') {
        $m_id = 10;
    }
    if ($status == 'closed') {
        $m_id = 11;
    }
} else {
    $m_id = 7;
}

if (!empty($m_id)) {
    $created = can_action($m_id, 'created');
    $edited = can_action($m_id, 'edited');
    $deleted = can_action($m_id, 'deleted');
}
$uri_segment = $this->uri->segment(4);

?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="<?= base_url('admin/tickets') ?>"><?= lang('tickets') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)) { ?>
                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#new" data-bs-toggle="tab"><?= lang('new_ticket') ?></a>
                    </li>
                    <?php } ?>
                    <?php 
                    foreach ($all_status as $key => $status) { ?>
                    <li class="nav-item waves-light">
                            <a class="nav-link <?= $active == $status->status ? 'active' : ''; ?>"  href="<?= base_url('admin/tickets/index/' . $status->status) ?>" ><?= $status->status ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane <?= $active == 1 || $active == $ticket_status ? 'active' : '' ?>" id="manage">
                        <h4 class="card-title mb-4"><?php if(!empty($uri_segment)){ echo strtoupper($uri_segment); }else{ echo lang('all'); } ?> <?=lang('tickets') ?></h4>
                        <!-- <div class="table-responsive"> -->
                            <table class="table table-striped dt-responsive nowrap w-100" id="contentTable">
                                <thead>
                                    <tr>
                                        <?php super_admin_opt_th() ?>
                                        <th><?= lang('ticket_code') ?></th>
                                        <th><?= lang('subject') ?></th>
                                        <th class="col-date"><?= lang('date') ?></th>
                                        <?php if ($this->session->userdata('user_type') == '1') { ?>
                                            <th><?= lang('reporter') ?></th>
                                        <?php } ?>
                                        <th><?= lang('department') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <?php /* $show_custom_fields = custom_form_table(7, null);
                                        if (!empty($show_custom_fields)) {
                                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                                if (!empty($c_label)) {
                                        ?>
                                                    <th><?= $c_label ?> </th>
                                        <?php }
                                            }
                                        } */
                                        ?>

                                        <th><?= lang('action') ?></th>
                                    </tr>
                                </thead>
                                <?php /* ?><tbody>
                                    <?php
                                    if (!empty($all_tickets_info)) {
                                        foreach ($all_tickets_info as $v_tickets_info) {
                                            $can_edit = $this->tickets_model->can_action('tbl_tickets', 'edit', array('tickets_id' => $v_tickets_info->tickets_id));
                                            $can_delete = $this->tickets_model->can_action('tbl_tickets', 'delete', array('tickets_id' => $v_tickets_info->tickets_id));
                                            if ($v_tickets_info->status == 'open') {
                                                $s_label = 'danger';
                                            } elseif ($v_tickets_info->status == 'closed') {
                                                $s_label = 'success';
                                            } else {
                                                $s_label = 'primary';
                                            }
                                            $profile_info = $this->db->where(array('user_id' => $v_tickets_info->reporter))->get('tbl_account_details')->row();
                                            $dept_info = $this->db->where(array('departments_id' => $v_tickets_info->departments_id))->get('tbl_departments')->row();
                                            if (!empty($dept_info)) {
                                                $dept_name = $dept_info->deptname;
                                            } else {
                                                $dept_name = '-';
                                            }
                                            if (!empty($ticket_status)) {
                                                if ($ticket_status == $v_tickets_info->status) {
                                            ?>
                                                    <tr id="table_ticket_<?= $v_tickets_info->tickets_id ?>">
                                                        <?php super_admin_opt_td($v_tickets_info->companies_id) ?>
                                                        <td><span class="badge badge-soft-success"><?= $v_tickets_info->ticket_code ?></span>
                                                        </td>
                                                        <td><a class="text-info" href="<?= base_url() ?>admin/tickets/index/tickets_details/<?= $v_tickets_info->tickets_id ?>"><?= $v_tickets_info->subject ?></a>
                                                        </td>
                                                        <td><?= display_datetime($v_tickets_info->created); ?></td>
                                                        <?php if ($this->session->userdata('user_type') == '1') { ?>

                                                            <td>
                                                                <a class="pull-left recect_task  ">
                                                                    <?php if (!empty($profile_info)) {
                                                                    ?>
                                                                        <img style="width: 30px;margin-left: 18px;  height: 29px;  border: 1px solid #aaa;" src="<?= base_url() . $profile_info->avatar ?>" class="img-circle">
                                                                        <?= ($profile_info->fullname) ?>
                                                                    <?php } else {
                                                                        echo '-';
                                                                    } ?>
                                                                </a>
                                                            </td>

                                                        <?php } ?>
                                                        <td><?= $dept_name ?></td>
                                                        <?php
                                                        if ($v_tickets_info->status == 'in_progress') {
                                                            $status = 'In Progress';
                                                        } else {
                                                            $status = $v_tickets_info->status;
                                                        }
                                                        ?>
                                                        <td><span class="badge badge-soft-<?= $s_label ?>"><?= $status ?></span>
                                                        </td>
                                                        <?php $show_custom_fields = custom_form_table(7, $v_tickets_info->tickets_id);
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
                                                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                                <?= btn_edit('admin/tickets/index/edit_tickets/' . $v_tickets_info->tickets_id) ?>
                                                            <?php }
                                                            if (!empty($can_delete) && !empty($deleted)) { ?>
                                                                <?php echo ajax_anchor(base_url("admin/tickets/delete/delete_tickets/$v_tickets_info->tickets_id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_ticket_" . $v_tickets_info->tickets_id)); ?>
                                                            <?php } ?>
                                                            <?= btn_view('admin/tickets/index/tickets_details/' . $v_tickets_info->tickets_id) ?>
                                                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                            <div class="dropdown tbl-action mt">
                                                                <button class="btn btn-outline-success dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_status') ?><i class="mdi mdi-chevron-down"></i></button>
                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                    <?php
                                                                    $status_info = $this->db->get('tbl_status')->result();
                                                                    if (!empty($status_info)) {
                                                                        foreach ($status_info as $v_status) {
                                                                    ?>
                                                                    <a class="dropdown-item" data-bs-toggle='modal' data-bs-target='#myModal' href="<?= base_url() ?>admin/tickets/change_status/<?= $v_tickets_info->tickets_id ?>/<?= $v_status->status ?>"><?= ucfirst($v_status->status) ?></a>
                                                                    
                                                                    <?php   }  }   ?>
                                                                </div>
                                                            </div>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                            } else { ?>
                                                <tr id="table_ticket_<?= $v_tickets_info->tickets_id ?>">
                                                    <?php super_admin_opt_td($v_tickets_info->companies_id) ?>
                                                    <td><span class="badge badge-soft-success"><?= $v_tickets_info->ticket_code ?></span>
                                                    </td>
                                                    <td><a class="text-info" href="<?= base_url() ?>admin/tickets/index/tickets_details/<?= $v_tickets_info->tickets_id ?>"><?= $v_tickets_info->subject ?></a>
                                                    </td>
                                                    <td><?= display_datetime($v_tickets_info->created); ?></td>
                                                    <?php if ($this->session->userdata('user_type') == '1') { ?>

                                                        <td>
                                                            <a class="pull-left recect_task  ">
                                                                <?php if (!empty($profile_info)) {
                                                                ?>
                                                                    <img style="width: 30px;margin-left: 18px;  height: 29px; border: 1px solid #aaa;" src="<?= base_url() . $profile_info->avatar ?>" class="img-circle">


                                                                    <?=
                                                                    ($profile_info->fullname)
                                                                    ?>
                                                                <?php } else {
                                                                    echo '-';
                                                                } ?>
                                                            </a>
                                                        </td>

                                                    <?php } ?>
                                                    <td><?= $dept_name ?></td>
                                                    <?php
                                                    if ($v_tickets_info->status == 'in_progress') {
                                                        $status = 'In Progress';
                                                    } else {
                                                        $status = $v_tickets_info->status;
                                                    }
                                                    ?>
                                                    <td><span class="badge badge-soft-<?= $s_label ?>"><?= ucfirst($status) ?></span>
                                                    </td>
                                                    <?php $show_custom_fields = custom_form_table(7, $v_tickets_info->tickets_id);
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
                                                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                            <?= btn_edit('admin/tickets/index/edit_tickets/' . $v_tickets_info->tickets_id) ?>
                                                        <?php }
                                                        if (!empty($can_delete) && !empty($deleted)) { ?>
                                                            <?php echo ajax_anchor(base_url("admin/tickets/delete/delete_tickets/$v_tickets_info->tickets_id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_ticket_" . $v_tickets_info->tickets_id)); ?>
                                                        <?php } ?>
                                                        <?= btn_view('admin/tickets/index/tickets_details/' . $v_tickets_info->tickets_id) ?>
                                                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                        <div class="dropdown tbl-action mt">
                                                            <button class="btn btn-outline-success dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_status') ?><i class="mdi mdi-chevron-down"></i></button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <?php
                                                                $status_info = $this->db->get('tbl_status')->result();
                                                                if (!empty($status_info)) {
                                                                    foreach ($status_info as $v_status) {
                                                                ?>
                                                                <a class="dropdown-item" data-bs-toggle='modal' data-bs-target='#myModal' href="<?= base_url() ?>admin/tickets/change_status/<?= $v_tickets_info->tickets_id ?>/<?= $v_status->status ?>"><?= ucfirst($v_status->status) ?></a>
                                                                
                                                                <?php   }  }   ?>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                    <?php }
                                        }
                                    }
                                    ?>
                                </tbody><?php */ ?>
                            </table>
                        <!-- </div> -->
                    </div>
                    <?php if (!empty($created) || !empty($edited)) {
                        if (!empty($tickets_info)) {
                            $tickets_id = $tickets_info->tickets_id;
                            $companies_id = $tickets_info->companies_id;
                        } else {
                            $tickets_id = null;
                            $companies_id = null;
                        }
                    ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="new">
                        <h4 class="card-title mb-4"><?= lang('new_ticket') ?></h4>
                        <?php echo form_open(base_url('admin/tickets/create_tickets/' . $tickets_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <?php super_admin_form($companies_id, 3, 5) ?>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 control-label"><?= lang('ticket_code') ?> <span class="text-danger">*</span></label>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <input type="text" class="form-control" value="<?php $this->load->helper('string');
                                    if (!empty($tickets_info)) {
                                        echo $tickets_info->ticket_code;
                                    } else {
                                        echo strtoupper(random_string('alnum', 7));
                                    }
                                    ?>" name="ticket_code">
                            </div>
                        </div>
                        <?php $projects = $this->uri->segment(4);
                        if ($projects != 'project_tickets') {
                        ?>
                            <input type="hidden" value="<?php echo $this->uri->segment(3) ?>" class="form-control" name="status">
                        <?php } ?>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 control-label"><?= lang('subject') ?> <span class="text-danger">*</span></label>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <input type="text" value="<?php
                                if (!empty($tickets_info)) {
                                    echo $tickets_info->subject;
                                }
                                ?>" class="form-control" placeholder="Sample Ticket Subject" name="subject" required>
                            </div>
                        </div>
                        <?php if ($this->session->userdata('user_type') == '1') {
                            $type = $this->uri->segment(5);
                            if (!empty($type) && !is_numeric($type)) {
                                $ex = explode('_', $type);
                                if ($ex[0] == 'c') {
                                    $primary_contact = $ex[1];
                                }
                            }
                        ?>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-md-3 col-sm-4 control-label"><?= lang('reporter') ?> <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <div class="">
                                        <select class="form-control select_box" style="width:100%" name="reporter" required>
                                            <option value=""><?= lang('none') ?></option>
                                            <?php
                                            $users = get_result('tbl_users');
                                            if (!empty($users)) {
                                                foreach ($users as $v_user) :
                                                    $users_info = $this->db->where(array("user_id" => $v_user->user_id))->get('tbl_account_details')->row();
                                                    if (!empty($users_info)) {
                                                        if ($v_user->role_id == 1) {
                                                            $role = lang('admin');
                                                        } elseif ($v_user->role_id == 2) {
                                                            $role = lang('client');
                                                        } else {
                                                            $role = lang('staff');
                                                        }
                                            ?>
                                                        <option value="<?= $users_info->user_id ?>" <?php
                                                                                                    if (!empty($tickets_info) && $tickets_info->reporter == $users_info->user_id) {
                                                                                                        echo 'selected';
                                                                                                    } else if (!empty($primary_contact) && $primary_contact == $users_info->user_id) {
                                                                                                        echo 'selected';
                                                                                                    }
                                                                                                    ?>><?= $users_info->fullname . ' (' . $role . ')'; ?></option>
                                            <?php
                                                    }
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 control-label"><?= lang('project') ?> <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <div class=" ">
                                    <select class="form-control select_box" style="width:100%" name="project_id">
                                        <option><?= lang('none') ?></option>
                                        <?php
                                        $project = get_result('tbl_project');
                                        $project_id = $this->uri->segment(6);
                                        if (!empty($project)) {
                                            foreach ($project as $v_project) :
                                        ?>
                                                <option value="<?= $v_project->project_id ?>" <?php
                                                                                                if (!empty($tickets_info) && $tickets_info->project_id == $v_project->project_id) {
                                                                                                    echo 'selected';
                                                                                                }
                                                                                                if ($projects == 'project_tickets') {
                                                                                                    if (!empty($project_id) && $project_id == $v_project->project_id) {
                                                                                                        echo 'selected';
                                                                                                    }
                                                                                                }
                                                                                                ?>><?= $v_project->project_name; ?></option>
                                        <?php
                                            endforeach;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('select[name="companies_id"]').on('change', function() {
                                    var companies_id = $(this).val();
                                    if (companies_id) {
                                        $.ajax({
                                            url: '<?= base_url('admin/global_controller/json_by_company/tbl_project/') ?>' + companies_id,
                                            type: "GET",
                                            dataType: "json",
                                            success: function(data) {
                                                $('select[name="project_id"]').find('option').not(':first').remove();
                                                $.each(data, function(key, value) {
                                                    $('select[name="project_id"]').append('<option value="' + value.project_id + '">' + value.project_name + '</option>');
                                                });
                                            }
                                        });
                                    } else {
                                        $('select[name="project_id"]').empty();
                                    }
                                });
                            });
                        </script>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 control-label"><?= lang('priority') ?> <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <div class=" ">
                                    <select name="priority" class="form-control">
                                        <?php
                                        $priorities = $this->db->get('tbl_priorities')->result();
                                        if (!empty($priorities)) {
                                            foreach ($priorities as $v_priorities) :
                                        ?>
                                                <option value="<?= $v_priorities->priority ?>" <?php
                                                                                                if (!empty($tickets_info) && $tickets_info->priority == $v_priorities->priority) {
                                                                                                    echo 'selected';
                                                                                                }
                                                                                                ?>><?= lang(strtolower($v_priorities->priority)) ?></option>
                                        <?php
                                            endforeach;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 control-label"><?= lang('department') ?> <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <div class=" ">
                                    <select name="departments_id" class="form-control select_box" style="width: 100%" required>
                                        <option value=""><?= lang('none') ?></option>
                                        <?php
                                        $all_departments = get_result('tbl_departments');
                                        if (!empty($all_departments)) {
                                            foreach ($all_departments as $v_dept) :
                                        ?>
                                                <option value="<?= $v_dept->departments_id ?>" <?php
                                                                                                if (!empty($tickets_info) && $tickets_info->departments_id == $v_dept->departments_id) {
                                                                                                    echo 'selected';
                                                                                                }
                                                                                                ?>><?= $v_dept->deptname ?></option>
                                        <?php
                                            endforeach;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 control-label"><?= lang('status') ?> <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <div class=" ">
                                    <select name="status" class="form-control select_box" style="width: 100%" required>
                                        <option value=""><?= lang('none') ?></option>
                                        <?php
                                        $all_status = get_result('tbl_status');
                                        if (!empty($all_status)) {
                                            foreach ($all_status as $v_status) :
                                        ?>
                                                <option value="<?= $v_status->status ?>" <?php
                                                                                            if (!empty($tickets_info) && $tickets_info->status == $v_status->status) {
                                                                                                echo 'selected';
                                                                                            }
                                                                                            ?>><?= $v_status->status ?></option>
                                        <?php
                                            endforeach;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <?php
                        if (!empty($tickets_info)) {
                            $tickets_id = $tickets_info->tickets_id;
                        } else {
                            $tickets_id = null;
                        }
                        ?>
                        <?= custom_form_Fields(7, $tickets_id); ?>


                        <div class="row mb-3" style="margin-bottom: 0px">
                            <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 control-label"><?= lang('attachment') ?></label>

                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <div id="comments_file-dropzone" class="dropzone mb15">

                                </div>
                                <div id="comments_file-dropzone-scrollbar">
                                    <div id="comments_file-previews">
                                        <div id="file-upload-row" class="mt pull-left">

                                            <div class="preview box-content pr-lg" style="width:100px;">
                                                <span data-dz-remove class="pull-right" style="cursor: pointer">
                                                    <i class="fa fa-times"></i>
                                                </span>
                                                <img data-dz-thumbnail class="upload-thumbnail-sm img-thumbnail" />
                                                <input class="file-count-field" type="hidden" name="files[]" value="" />
                                                <div class="mb progress progress-striped upload-progress-sm active mt-sm" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                                    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if (!empty($tickets_info->upload_file)) {
                                    $uploaded_file = json_decode($tickets_info->upload_file);
                                }
                                if (!empty($uploaded_file)) {
                                    foreach ($uploaded_file as $v_files_image) { ?>
                                        <div class="pull-left mt pr-lg mb" style="width:100px;">
                                            <span data-dz-remove class="pull-right existing_image" style="cursor: pointer"><i class="fa fa-times"></i></span>
                                            <?php if ($v_files_image->is_image == 1) { ?>
                                                <img data-dz-thumbnail src="<?php echo base_url() . $v_files_image->path ?>" class="upload-thumbnail-sm img-thumbnail" />
                                            <?php } else { ?>
                                                <span data-bs-toggle="tooltip" data-placement="top" title="<?= $v_files_image->fileName ?>" class="mailbox-attachment-icon"><i class="fa fa-file-text-o"></i></span>
                                            <?php } ?>

                                            <input type="hidden" name="path[]" value="<?php echo $v_files_image->path ?>">
                                            <input type="hidden" name="fileName[]" value="<?php echo $v_files_image->fileName ?>">
                                            <input type="hidden" name="fullPath[]" value="<?php echo $v_files_image->fullPath ?>">
                                            <input type="hidden" name="size[]" value="<?php echo $v_files_image->size ?>">
                                            <input type="hidden" name="is_image[]" value="<?php echo $v_files_image->is_image ?>">
                                        </div>
                                    <?php }; ?>
                                <?php }; ?>
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $(".existing_image").click(function() {
                                            $(this).parent().remove();
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
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 control-label"><?= lang('ticket_message') ?> </label>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <textarea name="body" id="elm1" class="form-control textarea_" placeholder="<?= lang('message') ?>">
                                    <?php
                                    if (!empty($tickets_info)) {
                                        echo $tickets_info->body;
                                    } else {
                                        echo set_value('body');
                                    }
                                    ?>  
                                </textarea>
                            </div>
                        </div>
                        <div class="row mb-3" id="border-none">
                            <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 control-label"><?= lang('permission') ?> <span class="required">*</span></label>
                            <div class="col-lg-9 col-md-9 col-sm-8">
                                <div class="form-check form-radio-outline form-radio-primary mb-3">
                                    <input id="everyone" <?php
                                        if (!empty($tickets_info->permission) && $tickets_info->permission == 'all') {
                                            echo 'checked';
                                        }
                                        ?> type="radio" name="permission" value="everyone" class="form-check-input">
                                    <label class="form-check-label" for="everyone"><?= lang('everyone') ?>
                                        <i title="<?= lang('permission_for_all') ?>"
                                           class="fa fa-question-circle" data-bs-toggle="tooltip"
                                           data-bs-placement="top"></i>
                                    </label>
                                </div>
                                <div class="form-check form-radio-outline form-radio-primary mb-3">
                                    <input id="custom_permission" <?php
                                        if (!empty($tickets_info->permission) && $tickets_info->permission != 'all') {
                                            echo 'checked';
                                        } elseif (empty($tickets_info)) {
                                            echo 'checked';
                                        }
                                        ?>  type="radio" name="permission" value="custom_permission"  class="form-check-input">
                                    <label class="form-check-label" for="custom_permission"><?= lang('custom_permission') ?>
                                        <i   title="<?= lang('permission_for_customization') ?>"   class="fa fa-question-circle" data-bs-toggle="tooltip"   data-bs-placement="top"></i>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 <?php
                            if (!empty($tickets_info->permission) && $tickets_info->permission != 'all') {
                                echo 'show';
                            }
                            ?>" id="permission_user_1">
                            <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 control-label"><?= lang('select') . ' ' . lang('users') ?>
                                <span class="required">*</span></label>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <?php
                                if (!empty($permission_user)) { ?>
                                    <input type="text" name="search_assigned_user" value="" placeholder="<?=lang('search_by').' '.lang('username'); ?>" class="form-control mb-4" id="search_assigned_user" autocomplete="off">
                                    <div data-simplebar style="max-height: 250px;">  
                                        <?php 
                                        foreach ($permission_user as $key => $v_user) {

                                        if ($v_user->role_id == 1) {
                                            $role = '<strong class="badge btn-danger">' . lang('admin') . '</strong>';
                                        } else {
                                            $role = '<strong class="badge btn-primary">' . lang('staff') . '</strong>';
                                        }

                                        ?>
                                        <div class="form-check form-check-primary mb-3">
                                            <input type="checkbox" <?php
                                                if (!empty($tickets_info->permission) && $tickets_info->permission != 'all') {
                                                    $get_permission = json_decode($tickets_info->permission);
                                                    foreach ($get_permission as $user_id => $v_permission) {
                                                        if ($user_id == $v_user->user_id) {
                                                            echo 'checked';
                                                        }
                                                    }
                                                }
                                                ?>   value="<?= $v_user->user_id ?>"  name="assigned_to[]" data-name="<?= $v_user->username;?>"  class="form-check-input" id ="user_<?= $v_user->user_id ?>">
                                            <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?>
                                            </label>
                                        </div>
                                        <div class="action_1 p
                                            <?php
                                            if (!empty($tickets_info->permission) && $tickets_info->permission != 'all') {
                                                $get_permission = json_decode($tickets_info->permission);

                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        echo 'show';
                                                    }
                                                }
                                            }
                                            ?>  " id="action_1<?= $v_user->user_id ?>">
                                            <div class="form-check form-check-primary mb-3 mr">         
                                                <input id="view_<?= $v_user->user_id ?>" checked type="checkbox" name="action_1<?= $v_user->user_id ?>[]" disabled value="view" class="form-check-input">
                                                <label class="form-check-label" for="view_<?= $v_user->user_id ?>"><?= lang('view') ?></label>
                                            </div>
                                            <div class="form-check form-check-primary mb-3 mr">         
                                                <input id="edit_<?= $v_user->user_id ?>" 
                                                    <?php
                                                    if (!empty($tickets_info->permission) && $tickets_info->permission != 'all') {
                                                        $get_permission = json_decode($tickets_info->permission);

                                                        foreach ($get_permission as $user_id => $v_permission) {
                                                            if ($user_id == $v_user->user_id) {
                                                                if (in_array('edit', $v_permission)) {
                                                                    echo 'checked';
                                                                };
                                                            }
                                                        }
                                                    }
                                                    ?> type="checkbox" value="edit" name="action_<?= $v_user->user_id ?>[]" class="form-check-input">
                                                <label class="form-check-label" for="edit_<?= $v_user->user_id ?>"><?= lang('edit') ?></label>
                                            </div>
                                            <div class="form-check form-check-primary mb-3 mr">         
                                                <input id="delete_<?= $v_user->user_id;?>"
                                                    <?php
                                                    if (!empty($tickets_info->permission) && $tickets_info->permission != 'all') {
                                                        $get_permission = json_decode($tickets_info->permission);
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
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 control-label"></label>
                            <div class="col-lg-6">
                                <button type="submit" id="file-save-button" class="btn btn-xs btn-primary"></i> <?= lang('create_ticket') ?></button>
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
$(document).ready(function(){
    $("#sparkline2").sparkline([<?php if (!empty($tickets_result)) { foreach ($tickets_result as $v_tickets_result) { echo $v_tickets_result . ','; } } ?>], {
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
             'url':'<?=base_url()?>admin/datatable/tickets?ticket_status=<?php echo $ticket_status; ?>'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_ticket_"+iDisplayIndex);
            return nRow;
          },
          'columns': [
		  <?php if (is_company_column_ag()) { ?>
             { data: 'companies_id' },
		  <?php } ?>
             { data: 'ticket_code' },
             { data: 'subject' },
             { data: 'date' },
			 <?php if (is_company_column_ag()) { ?>
             { data: 'reporter' },
			 <?php } ?>
             { data: 'department' },
             { data: 'status' },
             // { data: 'label' },
             { data: 'action' },
          ]
        });
     });
 </script>