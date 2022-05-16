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
$comment_type = 'projects';
$can_edit = $this->items_model->can_action('tbl_project', 'edit', array('project_id' => $project_details->project_id));
if (!empty($project_details->client_id)) {
    $currency = $this->items_model->client_currency_sambol($project_details->client_id);
} else {
    $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
}
$comment_details = $this->db->where(array('project_id' => $project_details->project_id, 'comments_reply_id' => '0', 'task_attachment_id' => '0', 'uploaded_files_id' => '0'))->order_by('comment_datetime', 'DESC')->get('tbl_task_comment')->result();
$all_milestones_info = $this->db->where('project_id', $project_details->project_id)->get('tbl_milestones')->result();
$all_task_info = $this->db->where('project_id', $project_details->project_id)->order_by('task_id', 'DESC')->get('tbl_task')->result();
$all_bugs_info = $this->db->where('project_id', $project_details->project_id)->order_by('bug_id', 'DESC')->get('tbl_bug')->result();
$total_timer = $this->db->where(array('project_id' => $project_details->project_id, 'start_time !=' => 0, 'end_time !=' => 0,))->get('tbl_tasks_timer')->result();
$all_invoice_info = $this->db->where(array('project_id' => $project_details->project_id))->get('tbl_invoices')->result();
$all_estimates_info = $this->db->where(array('project_id' => $project_details->project_id))->get('tbl_estimates')->result();

$all_tickets_info = $this->db->where(array('project_id' => $project_details->project_id))->get('tbl_tickets')->result();

$all_expense_info = $this->db->where(array('project_id' => $project_details->project_id, 'type' => 'Expense'))->get('tbl_transactions')->result();

$total_expense = $this->db->select_sum('amount')->where(array('project_id' => $project_details->project_id, 'type' => 'Expense'))->get('tbl_transactions')->row();
$billable_expense = $this->db->select_sum('amount')->where(array('project_id' => $project_details->project_id, 'type' => 'Expense', 'billable' => 'Yes'))->get('tbl_transactions')->row();
$not_billable_expense = $this->db->select_sum('amount')->where(array('project_id' => $project_details->project_id, 'type' => 'Expense', 'billable' => 'No'))->get('tbl_transactions')->row();

$activities_info = $this->db->where(array('module' => 'projects', 'module_field_id' => $project_details->project_id))->order_by('activity_date', 'DESC')->get('tbl_activities')->result();

$project_hours = $this->items_model->calculate_project('project_hours', $project_details->project_id);

if ($project_details->billing_type == 'tasks_hours' || $project_details->billing_type == 'tasks_and_project_hours') {
    $tasks_hours = $this->items_model->total_project_hours($project_details->project_id, '', true);
}
$project_cost = $this->items_model->calculate_project('project_cost', $project_details->project_id);
$where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $project_details->project_id, 'module_name' => 'project');
$check_existing = $this->items_model->check_by($where, 'tbl_pinaction');
if (!empty($check_existing)) {
    $url = 'remove_todo/' . $check_existing->pinaction_id;
    $btn = 'danger';
    $title = lang('remove_todo');
} else {
    $url = 'add_todo_list/project/' . $project_details->project_id;
    $btn = 'warning';
    $title = lang('add_todo_list');
}
$this->load->helper('date');
$totalDays = round((human_to_unix($project_details->end_date) - human_to_unix($project_details->start_date)) / 3600 / 24);
$TotalGone = $totalDays;
$tprogress = 100;
if (human_to_unix($project_details->start_date) < time() && human_to_unix($project_details->end_date) > time()) {
    $TotalGone = round((human_to_unix($project_details->end_date) - time()) / 3600 / 24);
    $tprogress = $TotalGone / $totalDays * 100;

}
if (human_to_unix($project_details->end_date) < time()) {
    $TotalGone = 0;
    $tprogress = 0;
}
if (strtotime(date('Y-m-d H:i')) > strtotime($project_details->end_date)) {
    $lang = lang('days_gone');
} else {
    $lang = lang('days_left');
}

$edited = can_action('57', 'edited');
?>

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="btn-toolbar p-3" role="toolbar">
                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                    <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?= lang('generate_invoice') ?>" class="me-1">
                     <a data-bs-toggle="modal" data-bs-target="#myModal"
                        href="<?= base_url() ?>admin/projects/invoice/<?= $project_details->project_id ?>"
                        class="mr-lg btn btn-info"><i class="fa fa-money"></i>
                     </a>
                    </span>

                    <?php } ?>
                    <div class="me-1">
                    <?php if ($project_details->timer_status == 'on') { ?>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('stop_timer') ?>" class="btn btn-danger "
                           href="<?= base_url() ?>admin/projects/tasks_timer/off/<?= $project_details->project_id ?>"><i
                                    class="fa fa-clock-o fa-spin"></i> </a>
                    <?php } else {
                        ?>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('start_timer') ?>" class="btn btn-success"
                           href="<?= base_url() ?>admin/projects/tasks_timer/on/<?= $project_details->project_id ?>"><i
                                    class="fa fa-clock-o"></i></a>
                    <?php }
                    ?>
                    </div>
                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                    <div class="me-1">
                        <a data-bs-toggle="modal" data-bs-target="#myModal" title="<?= lang('clone_project') ?>"
                           href="<?= base_url() ?>admin/projects/clone_project/<?= $project_details->project_id ?>"
                           class="btn btn-secondary pull-right"><i class="fa fa-copy"></i></a>
                    </div>
                    <?php } ?>

                    <div class="me-1">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $title ?>" href="<?= base_url() ?>admin/projects/<?= $url ?>" class="btn btn-<?= $btn ?>"><i class="fa fa-thumb-tack"></i></a>
                    </div>
                    <div>
                       <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('export_report') ?>" href="<?= base_url() ?>admin/projects/export_project/<?= $project_details->project_id ?>" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i></a>                 
                    </div>
                </div>
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    
                    <a class="nav-link mb-2 <?= $active == 1 ? 'active' : '' ?>"  href="#task_details" data-bs-toggle="tab" aria-controls="task_details" aria-selected="false"><?= lang('project_details')?></a>
                    
                    <a class="nav-link mb-2 <?= $active == 15 ? 'active' : '' ?>" href="<?= base_url() ?>admin/projects/project_details/<?= $project_details->project_id ?>/15"><?= lang('calendar')?></a>

                    <a class="nav-link mb-2 <?= $active == 3 ? 'active' : '' ?>"  href="#task_comments" data-bs-toggle="tab" aria-controls="task_comments" aria-selected="false"><?= lang('comments')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($comment_details) ? count($comment_details) : null) ?></strong></a>
                    
                    <a class="nav-link mb-2 <?= $active == 4 ? 'active' : '' ?>"  href="#task_attachments" data-bs-toggle="tab" aria-controls="task_attachments" aria-selected="false"><?= lang('attachment')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($project_files_info) ? count($project_files_info) : null) ?></strong></a>

                    <a class="nav-link mb-2 <?= $active == 5 ? 'active' : '' ?>"  href="#milestones" data-bs-toggle="tab" aria-controls="milestones" aria-selected="false"><?= lang('milestones')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_milestones_info) ? count($all_milestones_info) : null) ?></strong></a>

                    <a class="nav-link mb-2 <?= $active == 6 ? 'active' : '' ?>" href="#task" data-bs-toggle="tab" aria-controls="task" aria-selected="false"><?= lang('tasks')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_task_info) ? count($all_task_info) : null) ?></strong></a>
                    
                    <a class="nav-link mb-2 <?= $active == 9 ? 'active' : '' ?>" href="#bugs" data-bs-toggle="tab" aria-controls="bugs" aria-selected="false"><?= lang('bugs')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_bugs_info) ? count($all_bugs_info) : null) ?></strong></a>
                    
                    <a class="nav-link mb-2 <?= $active == 13 ? 'active' : '' ?>" href="<?= base_url() ?>admin/projects/project_details/<?= $project_details->project_id ?>/13"><?= lang('gantt')?></a>
                 
                    <a class="nav-link mb-2 <?= $active == 8 ? 'active' : '' ?>" href="#task_notes" data-bs-toggle="tab" aria-controls="task_notes" aria-selected="false"><?= lang('notes')?></a>
                   
                    <a class="nav-link mb-2 <?= $active == 7 ? 'active' : '' ?>" href="#timesheet" data-bs-toggle="tab" aria-controls="timesheet" aria-selected="false"><?= lang('timesheet')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($total_timer) ? count($total_timer) : null) ?></strong></a>
                    
              
                    <a class="nav-link mb-2 <?= $active == 14 ? 'active' : '' ?>" href="#project_tickets" data-bs-toggle="tab" aria-controls="project_tickets" aria-selected="false"><?= lang('tickets')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_tickets_info) ? count($all_tickets_info) : null) ?></strong></a>
                    
                    <?php if (!empty($all_invoice_info)) { ?>

                    <a class="nav-link mb-2 <?= $active == 11 ? 'active' : '' ?>" href="#invoice" data-bs-toggle="tab" aria-controls="invoice" aria-selected="false"><?= lang('invoice')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_invoice_info) ? count($all_invoice_info) : null) ?></strong></a>
                    <?php } ?>                
                 
                    <a class="nav-link mb-2 <?= $active == 12 ? 'active' : '' ?>" href="#estimates" data-bs-toggle="tab" aria-controls="estimates" aria-selected="false"><?= lang('estimates')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_estimates_info) ? count($all_estimates_info) : null) ?></strong></a>
                 
               
                    <a class="nav-link mb-2 <?= $active == 10 ? 'active' : '' ?>" href="#expense" data-bs-toggle="tab" aria-controls="expense" aria-selected="false"><?= lang('expense')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_expense_info) ? display_money($total_expense->amount, $currency->symbol) : null) ?></strong></a>
               
                
                    <a class="nav-link mb-2 <?= $active == 16 ? 'active' : '' ?>" href="#project_settings" data-bs-toggle="tab" aria-controls="project_settings" aria-selected="false"><?= lang('project_settings')?></a>
                  
                    <a class="nav-link mb-2 <?= $active == 2 ? 'active' : '' ?>" href="#activities" data-bs-toggle="tab" aria-controls="activities" aria-selected="false"><?= lang('activities')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($activities_info) ? count($activities_info) : null) ?></strong></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
                    <!-- Task Details tab Starts -->
                    <div class="tab-pane fade <?= $active == 1 ? 'show active' : '' ?>" id="task_details" style="position: relative;">
                        <div class="card-body">
                            <div class="pull-right text-sm">
                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                <a href="<?= base_url() ?>admin/projects/new_project/<?= $project_details->project_id ?>"><?= lang('edit') . ' ' . lang('project') ?></a>
                            <?php } ?>
                            </div>
                            <h4 class="card-title"> <?php if (!empty($project_details->project_name)) echo $project_details->project_name; ?>
                            </h4>
                            <div class="form-horizontal task_details">
                                <?php
                                $client_info = $this->db->where('client_id', $project_details->client_id)->get('tbl_client')->row();
                                if (!empty($client_info)) {
                                    $name = $client_info->name;
                                } else {
                                    $name = '-';
                                }
                                ?>
                                <?php $project_details_view = config_item('project_details_view');
                                if (!empty($project_details_view) && $project_details_view == '2') {
                                    ?>
                                <div class="row">
                                    <div class="col-lg-4 br">
                                        <p class="lead bb"></p>
                                        <form class="form-horizontal p-20">

                                            <?php super_admin_details($project_details->companies_id,5,6) ?>

                                            <div class="row mb-3">
                                                <div class="col-md-5"><strong><?= lang('project_name') ?> :</strong></div>
                                                <div class="col-md-6">
                                                    <?php
                                                    if (!empty($project_details->project_name)) {
                                                        echo $project_details->project_name;
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5"><strong><?= lang('client') ?> :</strong></div>
                                                <div class="col-md-6">
                                                    <strong><?php echo $name; ?></strong>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5"><strong><?= lang('start_date') ?> :</strong></div>
                                                <div class="col-md-6">
                                                    <strong><?= display_datetime($project_details->start_date) ?></strong>
                                                </div>
                                            </div>
                                            <?php
                                            $text = '';
                                            if ($project_details->project_status != 'completed') {
                                                if ($totalDays < 0) {
                                                    $overdueDays = $totalDays . ' ' . lang('days_gone');
                                                    $text = 'text-danger';
                                                }
                                            }
                                            ?>
                                            <div class="row mb-3">
                                                <div class="col-md-5"><strong><?= lang('end_date') ?> :</strong></div>
                                                <div class="col-md-6 <?= $text ?>">
                                                    <strong><?= display_datetime($project_details->end_date) ?>
                                                        <?php if (!empty($overdueDays)) {
                                                            echo lang('overdue') . ' ' . $overdueDays;
                                                        } ?></strong>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5"><strong><?= lang('demo_url') ?> :</strong></div>
                                                <div class="col-md-6">
                                                    <strong><?php
                                                        if (!empty($project_details->demo_url)) {
                                                            ?>
                                                            <a href="<?php echo $project_details->demo_url; ?>"
                                                               target="_blank"><?php echo $project_details->demo_url ?></a>
                                                            <?php
                                                        } else {
                                                            echo '-';
                                                        }
                                                        ?></strong>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5"><strong><?= lang('status') ?>
                                                        :</strong></div>
                                                <div class="col-md-6">
                                                    <?php
                                                    if (!empty($project_details->project_status)) {
                                                        if ($project_details->project_status == 'completed') {
                                                            $status = "<div class='badge badge-soft-success'>" . lang($project_details->project_status) . "</div>";
                                                        } elseif ($project_details->project_status == 'in_progress') {
                                                            $status = "<div class='badge badge-soft-primary'>" . lang($project_details->project_status) . "</div>";
                                                        } elseif ($project_details->project_status == 'cancel') {
                                                            $status = "<div class='badge badge-soft-danger'>" . lang($project_details->project_status) . "</div>";
                                                        } else {
                                                            $status = "<div class='badge badge-soft-warning'>" . lang($project_details->project_status) . "</div>";
                                                        } ?>
                                                        <?= $status; ?>
                                                    <?php }
                                                    ?>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                    <div class="btn-group" role="group">
                                                        <button id="btnGroupVerticalDrop1" type="button" class="btn btn-success dropdown-toggle font-size-11 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?= lang('change') ?>"><i class="bx bxs-edit-alt"></i><i class="mdi mdi-chevron-down"></i></button>

                                                        <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                        
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/started' ?>"><?= lang('started') ?></a>
                                                             
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/in_progress' ?>"><?= lang('in_progress') ?></a>
                                                           
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/on_hold' ?>"><?= lang('on_hold') ?></a>
                                                            
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/cancel' ?>"><?= lang('cancel') ?></a>
                                                            
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/completed' ?>"><?= lang('completed') ?></a>
                                                            
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-lg-4 br">
                                        <p class="lead bb"></p>
                                        <form class="form-horizontal p-20">
                                            <div class="row mb-3">
                                                <div class="col-md-5"><strong><?= lang('timer_status') ?>:</strong></div>
                                                <div class="col-md-6">
                                                    <?php if ($project_details->timer_status == 'on') { ?>
                                                        <span class="badge badge-soft-success" style="margin-right:35px"><?= lang('on') ?></span>
                                                        <a class="btn btn-outline-danger btn-sm ml"
                                                           href="<?= base_url() ?>admin/projects/tasks_timer/off/<?= $project_details->project_id ?>"><?= lang('stop_timer') ?> </a>
                                                    <?php } else {
                                                        ?>
                                                        <span class="badge badge-soft-danger" style="margin-right:35px"><?= lang('off') ?></span>
                                                        <?php $this_permission = $this->items_model->can_action('tbl_project', 'view', array('project_id' => $project_details->project_id), true);
                                                        if (!empty($this_permission)) { ?>
                                                            <a class="btn btn-outline-success btn-sm ml"
                                                               href="<?= base_url() ?>admin/projects/tasks_timer/on/<?= $project_details->project_id ?>"><?= lang('start_timer') ?> </a>
                                                        <?php }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5"><strong><?= lang('billing_type') ?> :</strong></div>
                                                <div class="col-md-6">
                                                    <strong><?= lang($project_details->billing_type); ?></strong>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <small><?= lang('estimate_hours') ?> :</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <?= ($project_details->estimate_hours); ?> m
                                                    <?php if (!empty($project_details) && $project_details->billing_type == 'project_hours' || !empty($project_details) && $project_details->billing_type == 'tasks_and_project_hours') { ?>
                                                    <small class="small text-muted">
                                                        <?= $project_details->hourly_rate . "/" . lang('hour') ?>
                                                        <?php } ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5"><strong><?= lang('project_cost') ?> :</strong></div>
                                                <div class="col-md-6">
                                                    <strong><?= display_money($project_cost, $currency->symbol); ?></strong>
                                                    <?php if (!empty($project_details) && $project_details->billing_type == 'project_hours' || !empty($project_details) && $project_details->billing_type == 'tasks_and_project_hours') { ?>
                                                    <small class="small text-muted">
                                                        <?= $project_details->hourly_rate . "/" . lang('hour') ?>
                                                        <?php } ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5"><strong><?= lang('participants') ?>:</strong></div>
                                                <div class="col-md-6">
                                                    <div class="avatar-group">
                                                    <?php
                                                    if ($project_details->permission != 'all') {
                                                        $get_permission = json_decode($project_details->permission);
                                                        if (!empty($get_permission)) : ?>
                                                        <?php foreach ($get_permission as $permission => $v_permission) :
                                                                $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                                                                if ($user_info->role_id == 1) {
                                                                    $label = 'text-danger';
                                                                } else {
                                                                    $label = 'text-success';
                                                                }
                                                                $profile_info = $this->db->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                                                ?>
                                                    <div class="avatar-group-item">
                                                        <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $profile_info->fullname ?>" class="d-inline-block"><img src="<?= base_url() . $profile_info->avatar ?>" class="rounded-circle avatar-xs" alt=""><span style="margin: 0px 0 8px -10px;" class="mdi mdi-circle <?= $label ?> font-size-10"></span></a>
                                                    </div>
                                                    <?php endforeach; ?>
                                                    <?php   endif;
                                                    } else { ?>
                                                    <span class="mr-lg-2">
                                                        <strong><?= lang('everyone') ?></strong>
                                                        <i  title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                                    </span>
                                                    <?php } ?>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                    <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?= lang('add_more') ?>" class="mt-2">
                                                        <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/projects/update_users/<?= $project_details->project_id ?>" class="text-default"><i class="fa fa-plus"></i></a></span>
                                                    <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $show_custom_fields = custom_form_label(4, $project_details->project_id);

                                            if (!empty($show_custom_fields)) {
                                                foreach ($show_custom_fields as $c_label => $v_fields) {
                                                    if (!empty($v_fields)) {
                                                        ?>
                                                        <div class="row mb-3">
                                                            <div class="col-md-5"><strong><?= $c_label ?> :</strong></div>
                                                            <div class="col-md-6">
                                                                <?= $v_fields ?>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                }
                                            }
                                            ?>
                                        </form>
                                    </div>
                                    <div class="col-lg-4 br">
                                        <?php
                                        $paid_expense = 0;
                                        foreach ($all_expense_info as $v_expenses) {
                                            if ($v_expenses->invoices_id != 0) {
                                                $paid_expense += $this->invoice_model->calculate_to('paid_amount', $v_expenses->invoices_id);
                                            }
                                        }
                                        ?>
                                        <p class="lead bb"></p>
                                        <form class="form-horizontal p-20">
                                            <div class="row mb-3">
                                                <div class="col-md-7">
                                                    <strong><?= lang('total') . ' ' . lang('expense') ?></strong>:
                                                </div>
                                                <div class="col-md-5">
                                                    <strong><?= display_money($total_expense->amount, $currency->symbol) ?></strong>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-7">
                                                    <strong><?= lang('billable') . ' ' . lang('expense') ?></strong>:
                                                </div>
                                                <div class="col-md-5">
                                                    <strong><?= display_money($billable_expense->amount, $currency->symbol) ?></strong>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-7">
                                                    <strong><?= lang('not_billable') . ' ' . lang('expense') ?></strong>:
                                                </div>
                                                <div class="col-md-5">
                                                    <strong><?= display_money($not_billable_expense->amount, $currency->symbol) ?></strong>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-7">
                                                    <strong><?= lang('billed') . ' ' . lang('expense') ?></strong>:
                                                </div>
                                                <div class="col-md-5">
                                                    <strong><?= display_money($paid_expense, $currency->symbol) ?></strong>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-7">
                                                    <strong><?= lang('unbilled') . ' ' . lang('expense') ?></strong>:
                                                </div>
                                                <div class="col-md-5">
                                                    <strong><?= display_money($billable_expense->amount - $paid_expense, $currency->symbol) ?></strong>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <p class="lead bb"></p>
                                        <form class="form-horizontal p-20">
                                            <?php
                                            $estimate_hours = $project_details->estimate_hours;
                                            $percentage = $this->items_model->get_estime_time($estimate_hours);
                                            $logged_hour = $this->items_model->calculate_project('project_hours', $project_details->project_id);
                                            if (!empty($tasks_hours)) {
                                                $logged_tasks_hours = $tasks_hours;
                                            } else {
                                                $logged_tasks_hours = 0;
                                            }
                                            $total_logged_hours = $logged_hour + $logged_tasks_hours;

                                            if ($total_logged_hours < $percentage) {
                                                $total_time = $percentage - $total_logged_hours;
                                                $worked = '<storng style="font-size: 15px;"  class="required">' . lang('left_works') . '</storng>';
                                            } else {
                                                $total_time = $total_logged_hours - $percentage;
                                                $worked = '<storng style="font-size: 15px" class="required">' . lang('extra_works') . '</storng>';
                                            }
                                            $completed = count($this->db->where(array('project_id' => $project_details->project_id, 'task_status' => 'completed'))->get('tbl_task')->result());

                                            $total_task = count($all_task_info);
                                            if (!empty($total_task)) {
                                                if ($total_task != 0) {
                                                    $task_progress = $completed / $total_task * 100;
                                                }
                                                if ($task_progress > 100) {
                                                    $task_progress = 100;
                                                }
                                                if ($tprogress > 50) {
                                                    $p_bar = 'bar-success';
                                                } else {
                                                    $p_bar = 'bar-danger';
                                                }
                                                if ($task_progress < 49) {
                                                    $t_bar = 'bar-danger';
                                                } elseif ($task_progress < 79) {
                                                    $t_bar = 'bar-warning';
                                                } else {
                                                    $t_bar = 'bar-success';
                                                }
                                            } else {
                                                $p_bar = 'bar-danger';
                                                $t_bar = 'bar-success';
                                                $task_progress = 0;

                                            }
                                            if (!empty($tasks_hours)) {
                                                $col_ = 'col-lg-3';
                                            } else {
                                                $col_ = '';
                                            }
                                            ?>
                                            <div class="<?= $col_ ?>">
                                                <?php if (!empty($col_)) { ?>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title"><?= lang('project_hours') ?></h4>
                                                    </div>
                                                    <?php } ?>
                                                    <?= $this->items_model->get_time_spent_result($project_hours); ?>

                                                    <?php if ($project_details->billing_type == 'tasks_and_project_hours') {
                                                        $total_hours = $project_hours + $tasks_hours;
                                                        ?>
                                                        <h2 style="font-size: 22px"><?= lang('total') ?>
                                                            <span  style="font-size: 20px">: <?= $this->items_model->get_spent_time($total_hours); ?></span>
                                                        </h2>

                                                    <?php } ?>
                                                    <?php if (!empty($col_)) { ?>
                                                </div>

                                            <?php } ?>
                                            </div>
                                            <div class="text-center">
                                                <div class="">
                                                    <?= $worked ?>
                                                </div>
                                                <div class="">
                                                    <?= $this->items_model->get_spent_time($total_time) ?>
                                                </div>
                                            </div>
                                            <div class="<?= $col_ ?>">
                                                <?php if (!empty($col_)) { ?>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title"><?= lang('task_hours') ?></h4>
                                                    </div>
                                                    <?= $this->items_model->get_time_spent_result($tasks_hours); ?>
                                                    <div class="ml-lg">
                                                        <p class="p0 m0">
                                                            <strong><?= lang('billable') ?></strong>: <?= $this->items_model->get_spent_time($tasks_hours) ?>
                                                        </p>
                                                        <p class="p0 m0"><strong><?= lang('not_billable') ?></strong>:
                                                            <?php
                                                            $non_billable_time = 0;
                                                            foreach ($all_task_info as $v_n_tasks) {
                                                                if (!empty($v_n_tasks->billable) && $v_n_tasks->billable == 'No') {
                                                                    $non_billable_time += $this->items_model->task_spent_time_by_id($v_n_tasks->task_id);
                                                                }
                                                            }
                                                            echo $this->items_model->get_spent_time($non_billable_time);
                                                            ?>
                                                        </p>
                                                    </div>
                                                    <?php } ?>
                                                    <h2 class="text-center mt"><?= lang('total_bill') ?>
                                                        : <?= display_money($project_cost, $currency->symbol) ?></h2>
                                                    <?php if (!empty($col_)) { ?>
                                                </div>
                                            <?php } ?>
                                            </div>
                                        </form>
                                    </div>
                               
                                    <div class="col-md-4 br ">
                                        <p class="lead bb"></p>
                                        <form class="form-horizontal p-20">
                                            <blockquote style="font-size: 12px;word-wrap: break-word;"><?php
                                                if (!empty($project_details->description)) {
                                                    echo $project_details->description;
                                                }
                                                ?></blockquote>
                                        </form>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="lead bb"></p>
                                        <form class="form-horizontal p-20">
                                            <div class="col-sm-12 mt">
                                                <strong><?= $TotalGone . ' / ' . $totalDays . ' ' . $lang . ' (' . round($tprogress, 2) . '% )'; ?></strong>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mt progress progress-xs progress-striped active" style="">
                                                    <div class="progress-bar <?= $p_bar ?>" role="progressbar" style="width: <?= round($tprogress, 2) ?>%;" aria-valuenow="<?= round($tprogress, 2) ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 mt">
                                                <strong><?= $completed . ' / ' . $total_task . ' ' . lang('open') . ' ' . lang('tasks') . ' (' . round($task_progress, 2) . '% )'; ?> </strong>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mt progress progress-xs progress-striped active" style="">
                                                    <div class="progress-bar <?= $t_bar ?>" role="progressbar" style="width: <?= $task_progress ?>%;" aria-valuenow="<?= $task_progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 mt">
                                                <strong><?= lang('completed') ?>:</strong>
                                            </div>
                                            <div class="col-sm-12">
                                                <?php
                                                $progress = $this->items_model->get_project_progress($project_details->project_id);

                                                if ($progress < 49) {
                                                    $progress_b = 'progress-bar-danger';
                                                } elseif ($progress > 50 && $progress < 99) {
                                                    $progress_b = 'progress-bar-primary';
                                                } else {
                                                    $progress_b = 'progress-bar-success';
                                                }
                                                ?>
                                                <span class="">
                                                    <div class="mt progress progress-xs progress-striped active" style="">
                                                        <div class="progress-bar <?= $progress_b ?>" role="progressbar" style="width: <?= $progress ?>%;" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php } else { ?>
                                    <div class="row mb-3 ">
                                        <div class="col-sm-6">
                                            <label class="col-lg-3 col-form-label col-md-5"><strong><?= lang('project_name') ?> :</strong></label>
                                            <p class="form-control-static">
                                                <?php
                                                if (!empty($project_details->project_name)) {
                                                    echo $project_details->project_name;
                                                }
                                                ?>
                                            </p>
                                        </div>
                                        <?php super_admin_details_p($project_details->companies_id, 4) ?>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <label class="col-lg-3 col-form-label col-md-5"><strong><?= lang('client') ?>
                                                    :</strong></label>
                                            <p class="form-control-static">
                                                <strong><?php echo $name; ?></strong>
                                            </p>
                                        </div>

                                        <div class="col-sm-6">
                                            <label class="col-lg-3 col-form-label col-md-5"><strong><?= lang('timer_status') ?>
                                                    :</strong></label>
                                            <div class="col-md-6 mt">
                                                <?php if ($project_details->timer_status == 'on') { ?>

                                                    <span class="badge badge-soft-success"><?= lang('on') ?></span>
                                                    <a class="btn btn-outline-danger btn-sm "
                                                       href="<?= base_url() ?>admin/projects/tasks_timer/off/<?= $project_details->project_id ?>"><?= lang('stop_timer') ?> </a>
                                                <?php } else {
                                                    ?>
                                                    <span class="badge badge-soft-danger"><?= lang('off') ?></span>
                                                    <?php $this_permission = $this->items_model->can_action('tbl_project', 'view', array('project_id' => $project_details->project_id), true);
                                                    if (!empty($this_permission)) { ?>
                                                        <a class="btn btn-outline-success btn-sm"
                                                           href="<?= base_url() ?>admin/projects/tasks_timer/on/<?= $project_details->project_id ?>"><?= lang('start_timer') ?> </a>
                                                    <?php }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <label class="col-lg-3 col-form-label col-md-5"><strong><?= lang('start_date') ?> :</strong>
                                            </label>
                                            <p class="form-control-static">
                                                <?= display_datetime($project_details->start_date) ?>
                                            </p>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-lg-3 col-form-label col-md-5"><strong><?= lang('end_date') ?>
                                                    :</strong></label>
                                            <?php
                                            $text = '';
                                            if ($project_details->project_status != 'completed') {
                                                if ($totalDays < 0) {
                                                    $overdueDays = $totalDays . ' ' . lang('days_gone');
                                                    $text = 'text-danger';
                                                }
                                            }
                                            ?>
                                            <p class="form-control-static <?= $text ?>">
                                                <?= display_datetime($project_details->end_date) ?>
                                                <?php if (!empty($overdueDays)) {
                                                    echo lang('overdue') . ' ' . $overdueDays;
                                                } ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <label class="col-lg-3 col-form-label col-md-5"><strong><?= lang('billing_type') ?> :</strong>
                                            </label>
                                            <p class="form-control-static">
                                                <strong><?= lang($project_details->billing_type); ?></strong>
                                            </p>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-lg-3 col-form-label col-md-5">
                                                <small><?= lang('estimate_hours') ?> :</small>
                                            </label>
                                            <p class="form-control-static">
                                                <strong><?= ($project_details->estimate_hours); ?> m
                                                </strong>
                                                <?php if (!empty($project_details) && $project_details->billing_type == 'project_hours' || !empty($project_details) && $project_details->billing_type == 'tasks_and_project_hours') { ?>
                                                <small class="small text-muted">
                                                    <?= $project_details->hourly_rate . "/" . lang('hour') ?>
                                                    <?php } ?>
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <label class="col-lg-3 col-form-label col-md-5"><strong><?= lang('demo_url') ?> :</strong>
                                            </label>
                                            <p class="form-control-static" style="overflow: hidden;">
                                                <?php
                                                if (!empty($project_details->demo_url)) {
                                                    ?>
                                                    <a href="<?php echo $project_details->demo_url; ?>"
                                                       target="_blank"><?php echo $project_details->demo_url ?></a>
                                                    <?php
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </p>
                                        </div>

                                        <div class="col-sm-6">
                                            <label class="col-lg-3 col-form-label col-md-5"><strong><?= lang('project_cost') ?> :</strong>
                                            </label>
                                            <p class="form-control-static">
                                                <strong><?php display_money($project_cost, $currency->symbol); ?></strong>
                                                <?php if (!empty($project_details) && $project_details->billing_type == 'project_hours' || !empty($project_details) && $project_details->billing_type == 'tasks_and_project_hours') { ?>
                                                <small class="small text-muted">
                                                    <?= $project_details->hourly_rate . "/" . lang('hour') ?>
                                                    <?php } ?>
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <label class="col-lg-3 col-form-label col-md-5"><strong><?= lang('status') ?>
                                                    :</strong></label>
                                            <div class="pull-left">
                                                <?php
                                                if (!empty($project_details->project_status)) {
                                                    if ($project_details->project_status == 'completed') {
                                                        $status = "<span class='badge badge-soft-success'>" . lang($project_details->project_status) . "</span>";
                                                    } elseif ($project_details->project_status == 'in_progress') {
                                                        $status = "<span class='badge badge-soft-primary'>" . lang($project_details->project_status) . "</span>";
                                                    } elseif ($project_details->project_status == 'cancel') {
                                                        $status = "<span class='badge badge-soft-danger'>" . lang($project_details->project_status) . "</span>";
                                                    } else {
                                                        $status = "<span class='badge badge-soft-warning'>" . lang($project_details->project_status) . "</span>";
                                                    } ?>
                                                    <p class="form-control-static"><?= $status; ?></p>
                                                <?php }
                                                ?>
                                            </div>
                                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                <div class="col-sm-1 mt">
                                                    <div class="btn-group">
                                                        <button class="btn btn-outline-success btn-sm dropdown-toggle"
                                                                data-bs-toggle="dropdown">
                                                            <?= lang('change') ?>
                                                            <span class="caret"></span></button>
                                                        <ul class="dropdown-menu animated zoomIn">
                                                            <li>
                                                                <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/started' ?>"><?= lang('started') ?></a>
                                                            </li>
                                                            <li>
                                                                <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/in_progress' ?>"><?= lang('in_progress') ?></a>
                                                            </li>
                                                            <li>
                                                                <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/on_hold' ?>"><?= lang('on_hold') ?></a>
                                                            </li>
                                                            <li>
                                                                <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/cancel' ?>"><?= lang('cancel') ?></a>
                                                            </li>
                                                            <li>
                                                                <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/completed' ?>"><?= lang('completed') ?></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-lg-3 col-form-label col-md-5"><strong><?= lang('participants') ?>
                                                    :</strong></label>
                                            <div class="col-md-6 ">
                                                <?php
                                                if ($project_details->permission != 'all') {
                                                    $get_permission = json_decode($project_details->permission);
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


                                                            <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                                               title="<?= $profile_info->fullname ?>"><img src="<?= base_url() . $profile_info->avatar ?>" class="rounded-circle avatar-xs" alt=""><span style="margin: 0px 0 8px -10px;" class="mdi mdi-circle <?= $label ?> font-size-10"></span>
                                                            </a>
                                                        <?php
                                                        endforeach;
                                                    endif;
                                                } else { ?>
                                                <p class="form-control-static"><strong><?= lang('everyone') ?></strong>
                                                    <i
                                                            title="<?= lang('permission_for_all') ?>"
                                                            class="fa fa-question-circle" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"></i>

                                                    <?php
                                                    }
                                                    ?>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                    <span data-bs-placement="top" data-bs-toggle="tooltip"
                                                          title="<?= lang('add_more') ?>">
                                                    <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                       href="<?= base_url() ?>admin/projects/update_users/<?= $project_details->project_id ?>"
                                                       class="text-default ml"><i class="fa fa-plus"></i></a>
                                                        </span>
                                                </p>
                                            <?php
                                            }
                                            ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $show_custom_fields = custom_form_label(4, $project_details->project_id);

                                    if (!empty($show_custom_fields)) {
                                        foreach ($show_custom_fields as $c_label => $v_fields) {
                                            if (!empty($v_fields)) {
                                                if (count($v_fields) == 1) {
                                                    $col = 'col-sm-12';
                                                    $sub_col = 'col-sm-2';
                                                    $style = null;
                                                } else {
                                                    $col = 'col-sm-6';
                                                    $sub_col = 'col-md-5';
                                                    $style = null;
                                                }

                                                ?>
                                                <div class="row mb-3  <?= $col ?>" style="<?= $style ?>">
                                                    <label class="col-lg-3 col-form-label <?= $sub_col ?>"><strong><?= $c_label ?>
                                                            :</strong></label>
                                                    <div class="col-md-6 ">
                                                        <p class="form-control-static">
                                                            <strong><?= $v_fields ?></strong>
                                                        </p>
                                                    </div>
                                                </div>
                                            <?php }
                                        }
                                    }
                                    ?>
                                    <div class="row mb-3  col-sm-12 mt">
                                        <label class="col-lg-3 col-form-label col-sm-2 "><strong class="mr-sm"><?= lang('completed') ?>
                                                :</strong></label>
                                        <div class="col-md-6 " style="margin-left: -5px;">
                                            <?php
                                            $progress = $this->items_model->get_project_progress($project_details->project_id);

                                            if ($progress < 49) {
                                                $progress_b = 'progress-bar-danger';
                                            } elseif ($progress > 50 && $progress < 99) {
                                                $progress_b = 'progress-bar-primary';
                                            } else {
                                                $progress_b = 'progress-bar-success';
                                            }
                                            ?>
                                            <span class="">
                                        <div class="mt progress progress-striped ">
                                            <div class="progress-bar <?= $progress_b ?> " data-bs-toggle="tooltip"
                                                 data-original-title="<?= $progress ?>%"
                                                 style="width: <?= $progress ?>%"></div>
                                        </div>
                                        </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3 col-sm-12">
                                        <?php
                                        $estimate_hours = $project_details->estimate_hours;
                                        $percentage = $this->items_model->get_estime_time($estimate_hours);
                                        $logged_hour = $this->items_model->calculate_project('project_hours', $project_details->project_id);
                                        if (!empty($tasks_hours)) {
                                            $logged_tasks_hours = $tasks_hours;
                                        } else {
                                            $logged_tasks_hours = 0;
                                        }
                                        $total_logged_hours = $logged_hour + $logged_tasks_hours;

                                        if ($total_logged_hours < $percentage) {
                                            $total_time = $percentage - $total_logged_hours;
                                            $worked = '<storng style="font-size: 15px;"  class="required">' . lang('left_works') . '</storng>';
                                        } else {
                                            $total_time = $total_logged_hours - $percentage;
                                            $worked = '<storng style="font-size: 15px" class="required">' . lang('extra_works') . '</storng>';
                                        }

                                        $completed = count($this->db->where(array('project_id' => $project_details->project_id, 'task_status' => 'completed'))->get('tbl_task')->result());

                                        $total_task = count($all_task_info);
                                        if (!empty($total_task)) {
                                            if ($total_task != 0) {
                                                $task_progress = $completed / $total_task * 100;
                                            }
                                            if ($task_progress > 100) {
                                                $task_progress = 100;
                                            }
                                            if ($tprogress > 50) {
                                                $p_bar = 'bar-success';
                                            } else {
                                                $p_bar = 'bar-danger';
                                            }
                                            if ($task_progress < 49) {
                                                $t_bar = 'bar-danger';
                                            } elseif ($task_progress < 79) {
                                                $t_bar = 'bar-warning';
                                            } else {
                                                $t_bar = 'bar-success';
                                            }
                                        } else {
                                            $p_bar = 'bar-danger';
                                            $t_bar = 'bar-success';
                                            $task_progress = 0;

                                        }
                                        if (!empty($tasks_hours)) {
                                            $col_ = 'col-sm-6';
                                        } else {
                                            $col_ = '';
                                        }
                                        ?>
                                        <div class="<?= $col_ ?>">
                                            <?php if (!empty($col_)) { ?>
                                            <div class="panel panel-custom">
                                                <div class="panel-heading">
                                                    <div class="card-title"><?= lang('project_hours') ?></div>
                                                </div>
                                                <?php } ?>
                                                <?= $this->items_model->get_time_spent_result($project_hours); ?>
                                                <?php
                                                $paid_expense = 0;
                                                foreach ($all_expense_info as $v_expenses) {
                                                    if ($v_expenses->invoices_id != 0) {
                                                        $paid_expense += $this->invoice_model->calculate_to('paid_amount', $v_expenses->invoices_id);
                                                    }
                                                }


                                                ?>
                                                <div class="ml-lg mb-lg text-center">
                                                    <p class="p0 m0">
                                                        <strong><?= lang('total') . ' ' . lang('expense') ?></strong>: <?= display_money($total_expense->amount, $currency->symbol) ?>
                                                    </p>
                                                    <p class="p0 m0">
                                                        <strong><?= lang('billable') . ' ' . lang('expense') ?></strong>: <?= display_money($billable_expense->amount, $currency->symbol) ?>
                                                    </p>
                                                    <p class="p0 m0">
                                                        <strong><?= lang('not_billable') . ' ' . lang('expense') ?></strong>: <?= display_money($not_billable_expense->amount, $currency->symbol) ?>
                                                    </p>
                                                    <p class="p0 m0">
                                                        <strong><?= lang('billed') . ' ' . lang('expense') ?></strong>: <?= display_money($paid_expense, $currency->symbol) ?>
                                                    </p>
                                                    <p class="p0 m0">
                                                        <strong><?= lang('unbilled') . ' ' . lang('expense') ?></strong>: <?= display_money($billable_expense->amount - $paid_expense, $currency->symbol) ?>
                                                    </p>

                                                </div>

                                                <?php if ($project_details->billing_type == 'tasks_and_project_hours') {
                                                    $total_hours = $project_hours + $tasks_hours;
                                                    ?>
                                                    <h2 style="font-size: 22px"><?= lang('total') ?>
                                                        <span
                                                                style="font-size: 20px">: <?= $this->items_model->get_spent_time($total_hours); ?></span>
                                                    </h2>

                                                <?php } ?>
                                                <?php if (!empty($col_)) { ?>
                                            </div>

                                        <?php } ?>
                                        </div>
                                        <div class="<?= $col_ ?>">
                                            <?php if (!empty($col_)) { ?>
                                            <div class="panel panel-custom mb-lg">
                                                <div class="panel-heading">
                                                    <div class="card-title"><?= lang('task_hours') ?></div>
                                                </div>
                                                <?= $this->items_model->get_time_spent_result($tasks_hours); ?>
                                                <div class="ml-lg">
                                                    <p class="p0 m0">
                                                        <strong><?= lang('billable') ?></strong>: <?= $this->items_model->get_spent_time($tasks_hours) ?>
                                                    </p>
                                                    <p class="p0 m0"><strong><?= lang('not_billable') ?></strong>:
                                                        <?php
                                                        $non_billable_time = 0;
                                                        foreach ($all_task_info as $v_n_tasks) {
                                                            if (!empty($v_n_tasks->billable) && $v_n_tasks->billable == 'No') {
                                                                $non_billable_time += $this->items_model->task_spent_time_by_id($v_n_tasks->task_id);
                                                            }
                                                        }
                                                        echo $this->items_model->get_spent_time($non_billable_time);
                                                        ?>
                                                    </p>
                                                </div>
                                                <?php } ?>
                                                <h2 class="text-center mt"><?= lang('total_bill') ?>
                                                    : <?= display_money($project_cost, $currency->symbol) ?></h2>
                                                <?php if (!empty($col_)) { ?>
                                            </div>
                                        <?php } ?>
                                        </div>
                                        <div class="col-sm-12 mt-lg">
                                            <div class="col-md-5">
                                                <strong><?= $TotalGone . ' / ' . $totalDays . ' ' . $lang . ' (' . round($tprogress, 2) . '% )'; ?></strong>
                                                <div class="mt progress progress-striped progress-xs">
                                                    <div class="progress-bar progress-<?= $p_bar ?> " data-bs-toggle="tooltip"
                                                         data-original-title="<?= round($tprogress, 2) ?>%"
                                                         style="width: <?= round($tprogress, 2) ?>%"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="text-center">
                                                    <div class="">
                                                        <?= $worked ?>
                                                    </div>
                                                    <div class="">
                                                        <?= $this->items_model->get_spent_time($total_time) ?>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-5">
                                                <strong><?= $completed . ' / ' . $total_task . ' ' . lang('open') . ' ' . lang('tasks') . ' (' . round($task_progress, 2) . '% )'; ?> </strong>
                                                <div class="mt progress progress-striped progress-xs">
                                                    <div class="progress-bar progress-<?= $t_bar ?> " data-bs-toggle="tooltip"
                                                         data-original-title="<?= $task_progress ?>%"
                                                         style="width: <?= $task_progress ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3 col-sm-12">
                                        <div class="col-sm-12">
                                            <blockquote style="font-size: 12px;word-wrap: break-word;"><?php
                                                if (!empty($project_details->description)) {
                                                    echo $project_details->description;
                                                }
                                                ?></blockquote>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 15 ? 'active' : '' ?>" id="project_calendar" style="position: relative;">
                        <div class="card-body">
                            <div id="lnb">
                                <div id="right">
                                    <div id="menu" class="mb-3">
                                        <span id="menu-navi" class="d-sm-flex flex-wrap text-center text-sm-start justify-content-sm-between">
                                            <div class="d-sm-flex flex-wrap gap-1">
                                                <div class="btn-group mb-2" role="group" aria-label="Basic example">
                                                    <button type="button" class="btn btn-primary move-day" data-action="move-prev">
                                                        <i class="calendar-icon ic-arrow-line-left mdi mdi-chevron-left" data-action="move-prev"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-primary move-day" data-action="move-next">
                                                        <i class="calendar-icon ic-arrow-line-right mdi mdi-chevron-right" data-action="move-next"></i>
                                                    </button>
                                                </div>


                                                <button type="button" class="btn btn-primary move-today mb-2" data-action="move-today">Today</button>
                                            </div>

                                            <h4 id="renderRange" class="render-range fw-bold pt-1 mx-3"></h4>

                                            <div class="dropdown align-self-start mt-3 mt-sm-0 mb-2">
                                                <button id="dropdownMenu-calendarType" class="btn btn-primary" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <i id="calendarTypeIcon" class="calendar-icon ic_view_month" style="margin-right: 4px;"></i>
                                                    <span id="calendarTypeName">Dropdown</span>&nbsp;
                                                    <i class="calendar-icon tui-full-calendar-dropdown-arrow"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" role="menu" aria-labelledby="dropdownMenu-calendarType">
                                                    <li role="presentation">
                                                        <a class="dropdown-item" role="menuitem" data-action="toggle-daily">
                                                            <i class="calendar-icon ic_view_day"></i>Daily
                                                        </a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a class="dropdown-item" role="menuitem" data-action="toggle-weekly">
                                                            <i class="calendar-icon ic_view_week"></i>Weekly
                                                        </a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a class="dropdown-item" role="menuitem" data-action="toggle-monthly">
                                                            <i class="calendar-icon ic_view_month"></i>Month
                                                        </a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a class="dropdown-item" role="menuitem" data-action="toggle-weeks2">
                                                            <i class="calendar-icon ic_view_week"></i>2 weeks
                                                        </a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a class="dropdown-item" role="menuitem" data-action="toggle-weeks3">
                                                            <i class="calendar-icon ic_view_week"></i>3 weeks
                                                        </a>
                                                    </li>
                                                    <li role="presentation" class="dropdown-divider"></li>
                                                    <li role="presentation">
                                                        <a class="dropdown-item" role="menuitem" data-action="toggle-workweek">
                                                            <input type="checkbox" class="tui-full-calendar-checkbox-square" value="toggle-workweek" checked>
                                                            <span class="checkbox-title"></span>Show weekends
                                                        </a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a class="dropdown-item" role="menuitem" data-action="toggle-start-day-1">
                                                            <input type="checkbox" class="tui-full-calendar-checkbox-square" value="toggle-start-day-1">
                                                            <span class="checkbox-title"></span>Start Week on Monday
                                                        </a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a class="dropdown-item" role="menuitem" data-action="toggle-narrow-weekend">
                                                            <input type="checkbox" class="tui-full-calendar-checkbox-square" value="toggle-narrow-weekend">
                                                            <span class="checkbox-title"></span>Narrower than weekdays
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </span>
                                    </div>
                                </div>

                                <div id="calendarList" class="lnb-calendars-d1 mt-4 mt-sm-0 me-sm-0 mb-4"></div>
                                <div id="calendar" style="height: 800px;"></div>
                            </div>
                        </div>
                        <?php $this->load->view('admin/projects/project_calendar_js'); ?>
                    </div>
                    <?php
                    $type = $this->uri->segment(6);
                    $category_id = $this->uri->segment(7);
                    $expense_category = get_result('tbl_expense_category');
                    ?>
                    <!-- Task Details tab Ends -->
                    <div class="tab-pane <?= $active == 10 ? 'active' : '' ?>" id="expense" style="position: relative;">
                        <div class="card-body">
                            <div class="btn-group pull-right btn-with-tooltip-group" data-bs-toggle="tooltip"
                                 data-title="<?php echo lang('filter_by'); ?>">
                                <button type="button" class="btn btn-default dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-filter" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-left"
                                    style="width:300px;<?php if (!empty($type) && $type == 'category') {
                                        echo 'display:block';
                                    } ?>">
                                    <li class="<?php
                                    if (empty($type)) {
                                        echo 'active';
                                    } ?>"><a
                                                href="<?= base_url() ?>admin/projects/project_details/<?= $project_details->project_id ?>/10"><?php echo lang('all'); ?></a>
                                    </li>
                                    <li class="divider"></li>
                                    <?php if (count($expense_category) > 0) { ?>
                                        <?php foreach ($expense_category as $v_category) {
                                            ?>
                                            <li class="<?php if (!empty($category_id)) {
                                                if ($type == 'category') {
                                                    if ($category_id == $v_category->expense_category_id) {
                                                        echo 'active';
                                                    }
                                                }
                                            } ?>">
                                                <a href="<?= base_url() ?>admin/projects/project_details/<?= $project_details->project_id ?>/10/category/<?php echo $v_category->expense_category_id; ?>"><?php echo $v_category->expense_category; ?></a>
                                            </li>
                                        <?php }
                                        ?>
                                        <div class="clearfix"></div>
                                        <li class="divider"></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <!-- Tabs within a box -->
                            <ul class="nav nav-tabs bg-light rounded"  role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#manage_expense" data-bs-toggle="tab">
                                        <?= lang('expense') ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= base_url() ?>admin/transactions/expense/project_expense/<?= $project_details->project_id ?>">
                                        <?= lang('new_expense') ?>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-3 text-muted">
                                <!-- ************** general *************-->
                                <div class="tab-pane active" id="manage_expense">
                                    <div class="table-responsive">
                                        <table class="table table-striped dt-responsive nowrap w-100" id="project_expense_datatable">
                                            <thead>
                                            <tr>
                                                <th class="col-date"><?= lang('name') . '/' . lang('title') ?></th>
                                                <th><?= lang('date') ?></th>
                                                <th><?= lang('categories') ?></th>
                                                <th class="col-currency"><?= lang('amount') ?></th>
                                                <th><?= lang('attachment') ?></th>
                                                <th class="col-options no-sort"><?= lang('action') ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if (!empty($type) && $type == 'category') {
                                                $cate_expense_info = array();
                                                $expense_id = $this->uri->segment(7);
                                                if (!empty($all_expense_info)) {
                                                    foreach ($all_expense_info as $v_expense) {
                                                        if ($v_expense->type == 'Expense' && $v_expense->category_id == $expense_id) {
                                                            array_push($cate_expense_info, $v_expense);
                                                        }
                                                    }
                                                }
                                                $all_expense_info = $cate_expense_info;
                                            }
                                            $all_expense_info = array_reverse($all_expense_info);
                                            if (!empty($all_expense_info)):
                                                foreach ($all_expense_info as $v_expense) :
                                                    if ($v_expense->type == 'Expense'):
                                                        $category_info = $this->db->where('expense_category_id', $v_expense->category_id)->get('tbl_expense_category')->row();
                                                        if (!empty($category_info)) {
                                                            $category = $category_info->expense_category;
                                                        } else {
                                                            $category = lang('undefined_category');
                                                        }

                                                        $can_edit = $this->items_model->can_action('tbl_transactions', 'edit', array('transactions_id' => $v_expense->transactions_id));
                                                        $can_delete = $this->items_model->can_action('tbl_transactions', 'delete', array('transactions_id' => $v_expense->transactions_id));
                                                        $e_edited = can_action('31', 'edited');
                                                        $e_deleted = can_action('31', 'deleted');

                                                        $account_info = $this->items_model->check_by(array('account_id' => $v_expense->account_id), 'tbl_accounts');
                                                        ?>
                                                        <tr id="table-expense-<?= $v_expense->transactions_id ?>">
                                                            <td>
                                                                <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                                   href="<?= base_url() ?>admin/transactions/view_expense/<?= $v_expense->transactions_id ?>">
                                                                    <?= (!empty($v_expense->name) ? $v_expense->name : '-') ?>
                                                                </a>
                                                            </td>
                                                            <td><?= display_datetime($v_expense->date); ?></td>
                                                            <td><?= $category ?></td>
                                                            <td><?= display_money($v_expense->amount, $currency->symbol) ?></td>

                                                            <td>
                                                                <?php
                                                                $attachement_info = json_decode($v_expense->attachement);
                                                                if (!empty($attachement_info)) { ?>
                                                                    <a href="<?= base_url() ?>admin/transactions/download/<?= $v_expense->transactions_id ?>"><?= lang('download') ?></a>
                                                                <?php } ?>
                                                            </td>

                                                            <td class="">
                                                                <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                                   class="btn btn-info btn-sm"
                                                                   href="<?= base_url() ?>admin/transactions/view_expense/<?= $v_expense->transactions_id ?>">
                                                                    <span class="fa fa-list-alt"></span>
                                                                </a>
                                                                <?php if (!empty($can_edit) && !empty($e_edited)) { ?>
                                                                    <?= btn_edit('admin/transactions/expense/' . $v_expense->transactions_id) ?>
                                                                <?php }
                                                                if (!empty($can_delete) && !empty($e_deleted)) {
                                                                    ?>
                                                                    <?php echo ajax_anchor(base_url("admin/transactions/delete_expense/" . $v_expense->transactions_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table-expense-" . $v_expense->transactions_id)); ?>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    endif;
                                                endforeach;
                                            endif;
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- End Tasks Management-->

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="activities" style="position: relative;">
                        <div class="card-body">
                            <?php
                            $role = $this->session->userdata('user_type');
                            if ($role == 1) {
                                ?>
                                <span class="btn-sm pull-right mt">
                            <a href="<?= base_url() ?>admin/tasks/claer_activities/project/<?= $project_details->project_id ?>"><?= lang('clear') . ' ' . lang('activities') ?></a>
                            </span>
                            <?php } ?>
                            <h4 class="card-title"><?= lang('activities') ?></h4>
                            <div class="card-body">
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
                                    <?php }  }  ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 3 ? 'active' : '' ?>" id="task_comments" style="position: relative;">
                        <div class="card-body">    
                            <h4 class="card-title mb-4"><?= lang('comments') ?></h4>
                            <div class="chat">
                                <?php echo form_open(base_url("admin/projects/save_comments"), array("id" => $comment_type . "-comment-form", "class" => "form-horizontal general-form", "enctype" => "multipart/form-data", "role" => "form")); ?>
                                <input type="hidden" name="project_id" value="<?php
                                if (!empty($project_details->project_id)) {
                                    echo $project_details->project_id;
                                }
                                ?>" class="form-control">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <?php
                                        echo form_textarea(array(
                                            "id" => "comment_description",
                                            "name" => "description",
                                            "class" => "form-control comment_description",
                                            "placeholder" => $project_details->project_name . ' ' . lang('comments'),
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
                                $comment_reply_type = 'projects-reply';
                                ?>
                                <?php $this->load->view('admin/projects/comments_list', array('comment_details' => $comment_details)) ?>
                            </div>
                        </div>
                    </div>
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

                    <div class="tab-pane <?= $active == 4 ? 'active' : '' ?>" id="task_attachments" style="position: relative;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-7">
                                    <h4 class="card-title mt"><?= lang('attach_file_list') ?> </h4>
                                </div>
                                <div class="col-md-8 col-5">
                                    <?php
                                    $attach_list = $this->session->userdata('projects_media_view');
                                    if (empty($attach_list)) {
                                        $attach_list = 'media_view';
                                    }
                                    ?>
                                    <ul class="list-inline user-chat-nav text-end mb-0">
                        
                                        <li class="list-inline-item  d-sm-inline-block">
                                            <a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-default toggle-media-view <?= (!empty($attach_list) && $attach_list == 'list_view' ? 'hidden' : '') ?>" data-bs-type="list_view" title="<?= lang('switch_to') . ' ' . lang('media_view') ?>"><i class="fa fa-image"></i></a>
                                        
                                            <a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-default toggle-media-view <?= (!empty($attach_list) && $attach_list == 'media_view' ? 'hidden' : '') ?>" data-bs-type="media_view" title="<?= lang('switch_to') . ' ' . lang('list_view') ?>"><i class="fa fa-list"></i></a>
                                        </li>
                                        <li class="list-inline-item d-sm-inline-block">
                                            <a href="<?= base_url() ?>admin/projects/new_attachment/<?= $project_details->project_id ?>" class="text-purple text-sm" data-bs-toggle="modal" data-bs-placement="top"  data-bs-target="#myModal_extra_lg">
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
                                            var module = 'projects';
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
                                <div  class="p media-view-container <?= (!empty($attach_list) && $attach_list == 'media_view' ? 'hidden' : '') ?>">
                                    <div class="row">
                                        <?php $this->load->view('admin/projects/attachment_list', array('project_files_info' => $project_files_info)) ?>
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
                                                                        <?php echo ajax_anchor(base_url("admin/projects/delete_files/" . $files_info[$key]->task_attachment_id), "<i class='text-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#media_list_container-" . $files_info[$key]->task_attachment_id)); ?>
                                                                    <?php } ?>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="attachment-<?php echo $key ?>" class="accordion-collapse collapse <?php if (!empty($in) && $files_info[$key]->files_id == $in) { echo 'show'; } ?>" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body text-muted">
                                                                <div class="table-responsive">
                                                                    <table id="files_datatable" class="table table-striped dt-responsive nowrap w-100 ">
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
                                                                                                    href="<?= base_url() ?>admin/projects/attachment_details/r/<?= $files_info[$key]->task_attachment_id . '/' . $v_files->uploaded_files_id ?>">
                                                                                                <img
                                                                                                        style="width: 50px;border-radius: 5px;"
                                                                                                        src="<?= base_url() . $v_files->files ?>"/></a>
                                                                                        </div>
                                                                                    <?php else : ?>
                                                                                        <div class="file-icon"><i
                                                                                                    class="fa fa-file-o"></i>
                                                                                            <a data-bs-toggle="modal"
                                                                                               data-bs-target="#myModal_extra_lg"
                                                                                               href="<?= base_url() ?>admin/projects/attachment_details/r/<?= $files_info[$key]->task_attachment_id . '/' . $v_files->uploaded_files_id ?>"><?= $v_files->file_name ?></a>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </td>

                                                                                <td class=""><?= $v_files->size ?>Kb</td>
                                                                                <td class="col-date"><?= date('Y-m-d' . "<br/> h:m A", strtotime($files_info[$key]->upload_time)); ?></td>
                                                                                <td class=""><?= $total_file_comment ?></td>
                                                                                <td>
                                                                                    <?= $user_info->username ?>
                                                                                </td>
                                                                                <td>
                                                                                    <a class="btn btn-sm btn-dark"
                                                                                       data-bs-toggle="tooltip"
                                                                                       data-bs-placement="top"
                                                                                       title="Download"
                                                                                       href="<?= base_url() ?>admin/projects/download_files/<?= $v_files->uploaded_files_id ?>"><i
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
                                                <?php }  } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane <?= $active == 5 ? 'active' : '' ?>" id="milestones" style="position: relative;">
                        <div class="card-body">
                            <?php
                            $kanban = $this->session->userdata('milestone_kanban');
                            $uri_segment = $this->uri->segment(6);

                            if (!empty($kanban)) {
                                $k_milestone = 'kanban';
                            } elseif ($uri_segment == 'kanban') {
                                $k_milestone = 'kanban';
                            } else {
                                $k_milestone = 'list';
                            }
                            if ($k_milestone == 'kanban') {
                                $text = 'list';
                                $btn = 'primary';
                            } else {
                                $text = 'kanban';
                                $btn = 'danger';
                            }
                            ?>
                            <div class="row mb-3">
                                <div class="pull-left pr-lg">
                                    <a href="<?= base_url() ?>admin/projects/project_details/<?= $project_details->project_id ?>/5/<?= $text ?>"
                                       class="btn btn-sm btn-<?= $btn ?> "
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top" title="<?= lang('switch_to_' . $text) ?>">
                                        <i class="fa fa-undo"> </i><?= ' ' . lang('switch_to_' . $text) ?>
                                    </a>
                                </div>
                            </div>
                            <div class="row mb-3">
                            <?php if ($k_milestone == 'kanban') { ?>
                                <?php $this->load->view('admin/projects/milestones_kanban'); ?>
                            <?php } else { ?>
                                <!-- Tabs within a box -->
                                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link <?= $miles_active == 1 ? 'active' : ''; ?>" href="#manage_milestone" data-bs-toggle="tab">
                                            <?= lang('milestones') ?>
                                        </a>
                                    </li>
                                    <li  class="nav-item">
                                        <a class="nav-link <?= $miles_active == 2 ? 'active' : ''; ?>" href="#create_milestone" data-bs-toggle="tab">
                                            <?= lang('add_milestone') ?>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content p-3 text-muted">
                                    <!-- ************** general *************-->
                                    <div class="tab-pane <?= $miles_active == 1 ? 'active' : ''; ?>" id="manage_milestone" role="tabpanel">
                                            <div class="table-responsive mb-0" data-pattern="priority-columns">
                                                <table id="timelog_datatable" class="table table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                    <tr>
                                                        <th><?= lang('milestone_name') ?></th>
                                                        <th class="col-date"><?= lang('start_date') ?></th>
                                                        <th class="col-date"><?= lang('due_date') ?></th>
                                                        <th><?= lang('progress') ?></th>
                                                        <th><?= lang('action') ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    if (!empty($all_milestones_info)) {
                                                        foreach ($all_milestones_info as $key => $v_milestones) {
                                                            $progress = $this->items_model->calculate_milestone_progress($v_milestones->milestones_id);
                                                        $all_milestones_task = $this->db->where('milestones_id', $v_milestones->milestones_id)->get('tbl_task')->result();
                                                        ?>
                                                        <tr id="table-milestones-<?= $v_milestones->milestones_id ?>">
                                                            <td><a class="text-info" href="#"
                                                                   data-original-title="<?= $v_milestones->description ?>"
                                                                   data-bs-toggle="tooltip" data-bs-placement="top"
                                                                   title=""><?= $v_milestones->milestone_name ?></a></td>
                                                            <td><?= display_datetime($v_milestones->start_date) ?></td>
                                                            <td><?php
                                                                $due_date = $v_milestones->end_date;
                                                                $due_time = strtotime($due_date);
                                                                $current_time = time();
                                                                ?>
                                                                <?= display_datetime($due_date) ?>
                                                                <?php if ($current_time > $due_time && $progress < 100) { ?>
                                                                    <span
                                                                            class="badge badge-soft-danger"><?= lang('overdue') ?></span>
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <div class="inline ">
                                                                    <div class="easypiechart text-success"
                                                                         style="margin: 0px;"
                                                                         data-percent="<?= $progress ?>" data-line-width="5"
                                                                         data-track-Color="#f0f0f0" data-bar-color="#<?php
                                                                    if ($progress >= 100) {
                                                                        echo '8ec165';
                                                                    } else {
                                                                        echo 'fb6b5b';
                                                                    }
                                                                    ?>" data-rotate="270" data-scale-Color="false"
                                                                         data-size="50" data-animate="2000">
                                                                        <span class="small text-muted"><?= $progress ?>
                                                                            %</span>
                                                                    </div>
                                                                </div>

                                                            </td>
                                                            <td>
                                                                <?php echo btn_edit('admin/projects/project_details/' . $v_milestones->project_id . '/milestone/' . $v_milestones->milestones_id) ?>
                                                                <?php echo ajax_anchor(base_url("admin/projects/delete_milestones/" . $v_milestones->project_id . '/' . $v_milestones->milestones_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table-milestones-" . $v_milestones->milestones_id)); ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                    </div>
                                    <div class="tab-pane <?= $miles_active == 2 ? 'active' : ''; ?>" id="create_milestone">
                                        <div class="card-body">
                                            <form role="form" enctype="multipart/form-data" id="form"
                                              action="<?php echo base_url(); ?>admin/projects/save_milestones/<?php
                                              if (!empty($milestones_info)) {
                                                  echo $milestones_info->milestones_id;
                                              }
                                              ?>" method="post" class="form-horizontal  ">

                                            <div class="row mb-3">
                                                <label class="col-lg-3 col-form-label"><?= lang('milestone_name') ?> <span
                                                            class="text-danger">*</span></label>
                                                <div class="col-lg-6">
                                                    <input type="hidden" class="form-control"
                                                           value="<?= $project_details->project_id ?>"
                                                           name="project_id">
                                                    <input type="text" class="form-control" value="<?php
                                                    if (!empty($milestones_info)) {
                                                        echo $milestones_info->milestone_name;
                                                    }
                                                    ?>" placeholder="<?= lang('milestone_name') ?>" name="milestone_name"
                                                           required>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-lg-3 col-form-label"><?= lang('description') ?> <span
                                                            class="text-danger">*</span></label>
                                                <div class="col-lg-6">
                                                    <textarea name="description" class="form-control"
                                                              placeholder="<?= lang('description') ?>" required><?php
                                                        if (!empty($milestones_info->description)) {
                                                            echo $milestones_info->description;
                                                        }
                                                        ?></textarea>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-lg-3 col-form-label"><?= lang('start_date') ?> <span
                                                            class="text-danger">*</span></label>
                                                <div class="col-lg-6">
                                                    <div class="input-group" id="datepicker1">
                                                        <input type="text" name="start_date" class="form-control datepicker" autocomplete="off" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container='#datepicker1' value="<?php
                                                               if (!empty($milestones_info->start_date)) {
                                                                   echo date('d-m-Y H-i', strtotime($milestones_info->start_date));
                                                               } else {
                                                                   echo date('d-m-Y H:i');
                                                               }
                                                               ?>">
                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-lg-3 col-form-label"><?= lang('end_date') ?> <span
                                                            class="text-danger">*</span></label>
                                                <div class="col-lg-6">
                                                    <div class="input-group" id="datepicker2">
                                                        <input type="text" name="end_date" class="form-control datepicker" autocomplete="off" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container='#datepicker2' value="<?php
                                                               if (!empty($milestones_info->end_date)) {
                                                                   echo date('d-m-Y H-i', strtotime($milestones_info->end_date));
                                                               } else {
                                                                   echo date('d-m-Y H:i');
                                                               }
                                                               ?>">
                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="project_id" value="<?php
                                            if (!empty($project_details->project_id)) {
                                                echo $project_details->project_id;
                                            }
                                            ?>" class="form-control">
                                            <div class="row mb-3">
                                                <label class="col-lg-3 col-form-label"><?= lang('responsible') ?> <span
                                                            class="text-danger">*</span></label>
                                                <div class="col-lg-6">
                                                    <select name="user_id" style="width: 100%" class="select_box"
                                                            required="">
                                                        <optgroup label="<?= lang('admin_staff') ?>">
                                                            <?php
                                                            $user_info = $this->items_model->allowad_user('57');
                                                            if (!empty($user_info)) {
                                                                foreach ($user_info as $key => $v_user) {
                                                                    ?>
                                                                    <option value="<?= $v_user->user_id ?>" <?php
                                                                    if (!empty($milestones_info->user_id)) {
                                                                        echo $v_user->user_id == $milestones_info->user_id ? 'selected' : '';
                                                                    }
                                                                    ?>><?= ucfirst($v_user->username) ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="client_visible" class="form-check-label col-lg-3 col-form-label"><?= lang('visible_to_client') ?><span class="required">*</span></label>
                                                <div class="col-sm-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="client_visible" value="Yes" <?php
                                                            if (!empty($milestones_info) && $milestones_info->client_visible == 'Yes') {
                                                                echo 'checked';
                                                            }
                                                        ?> type="checkbox" id="client_visible">                                           
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-lg-3"></div>
                                                <div class="col-lg-6">
                                                    <button type="submit" class="btn btn-xs btn-primary"><?= lang('add_milestone') ?></button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- End milestones-->

                    <!-- Start Tasks Management-->
                    <div class="tab-pane <?= $active == 6 ? 'active' : '' ?>" id="task" style="position: relative;">
                        <div class="card-body">
                            <ul class="nav nav-tabs bg-light rounded" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link <?= $task_active == 1 ? 'active' : ''; ?>" href="#manage_task" data-bs-toggle="tab">
                                        <?= lang('task') ?>
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= base_url() ?>admin/tasks/new_tasks/project/<?= $project_details->project_id ?>">
                                        <?= lang('new_task') ?>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-3 text-muted">
                                <!-- ************** general *************-->
                                <div class="tab-pane <?= $task_active == 1 ? 'active' : ''; ?>" id="manage_task">
                                        <div class="table-responsive">
                                            <table id="project_tasks_datatable" class="table table-striped dt-responsive nowrap w-100">
                                                <thead>
                                                <tr>
                                                    <th data-check-all>
                                                        <div class="form-check font-size-16 check-all">
                                                            <input type="checkbox" id="parent_present" class="form-check-input">
                                                            <label for="parent_present" class="toggle form-check-label"></label>
                                                        </div>
                                                    </th>
                                                    <th><?= lang('task_name') ?></th>
                                                    <th><?= lang('due_date') ?></th>
                                                    <th class="col-sm-1"><?= lang('progress') ?></th>
                                                    <th class="col-sm-1"><?= lang('status') ?></th>
                                                    <th class="col-sm-2"><?= lang('changes/view') ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $t_edited = can_action('54', 'edited');
                                                if (!empty($all_task_info)):foreach ($all_task_info as $key => $v_task):
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <div class="complete form-check font-size-16">
                                                                <input type="checkbox" data-id="<?= $v_task->task_id ?>"
                                                                           style="position: absolute;" <?php
                                                                    if ($v_task->task_progress >= 100) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?> class="form-check-input">
                                                                <label class="form-check-label"></label>
                                                            </div>
                                                        </td>
                                                        <td><a class="text-info" style="<?php
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
                                                                <span class="badge badge-soft-danger"><?= lang('overdue') ?></span>
                                                            <?php } ?></td>
                                                        <td>
                                                            <div class="inline ">
                                                                <div class="easypiechart text-success" style="margin: 0px;"
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
                                                            <?php
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
                                                            <?php echo btn_view('admin/tasks/view_task_details/' . $v_task->task_id) ?>
                                                            <?php if (!empty($t_edited)) { ?>
                                                                <?php echo btn_edit('admin/tasks/new_tasks/' . $v_task->task_id) ?>
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
                            <!-- End Tasks Management-->
                        </div>
                    </div>

                    <div class="tab-pane <?= $active == 9 ? 'active' : '' ?>" id="bugs" style="position: relative;">
                        <div class="card-body">
                            <!-- Tabs within a box -->
                            <ul class="nav nav-tabs bg-light rounded"  role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link <?= $bugs_active == 1 ? 'active' : ''; ?>" href="#manage_bugs" data-bs-toggle="tab">
                                        <?= lang('all_bugs') ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= base_url() ?>admin/bugs/index/project/<?= $project_details->project_id ?>">
                                        <?= lang('new_bugs') ?>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-3 text-muted">
                                <!-- ************** general *************-->
                                <div class="tab-pane <?= $bugs_active == 1 ? 'active' : ''; ?>" id="manage_bugs">
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
                                            $b_edited = can_action('58', 'edited');
                                            $b_deleted = can_action('58', 'deleted');

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
                                                        ?>"
                                                           href="<?= base_url() ?>admin/bugs/view_bug_details/<?= $v_bugs->bug_id ?>"><?php echo $v_bugs->bug_title; ?></a>
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
                                                        <span
                                                                class="badge badge-soft-<?= $label ?>"><?= lang("$v_bugs->bug_status") ?></span>
                                                    </td>
                                                    <td><?= ucfirst($v_bugs->priority) ?></td>
                                                    <td>

                                                        <span
                                                                class="badge btn-<?= $badge ?> "><?= $reporter->username ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($b_edited)) { ?>
                                                            <?php echo btn_edit('admin/bugs/index/' . $v_bugs->bug_id) ?>
                                                        <?php }
                                                        if (!empty($b_deleted)) { ?>
                                                            <?php echo ajax_anchor(base_url("admin/bugs/delete_bug/" . $v_bugs->bug_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table-bugs-" . $v_bugs->bug_id)); ?>
                                                        <?php } ?>
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


                    <?php
                    $direction = $this->session->userdata('direction');
                    if (!empty($direction) && $direction == 'rtl') {
                        $RTL = 'on';
                    } else {
                        $RTL = config_item('RTL');
                    }
                    if (!empty($RTL)) {
                        ?>
                    <link href="<?php echo base_url() ?>skote_assets/plugins/ganttView/jquery.ganttViewRTL.css?ver=3.0.0" rel="stylesheet">
                    <script src="<?php echo base_url() ?>skote_assets/plugins/ganttView/jquery.ganttViewRTL.js"></script>
                    <?php }else{   ?>
                    <link href="<?php echo base_url() ?>skote_assets/plugins/ganttView/jquery.ganttView.css?ver=3.0.0" rel="stylesheet">
                    <script src="<?php echo base_url() ?>skote_assets/plugins/ganttView/jquery.ganttView.js"></script>
                    <?php } ?>
                    <div class="tab-pane <?= $active == 13 ? 'active' : '' ?>" id="gantt" style="position: relative;">
                        <div class="card-body">
                            <span class="pull-right">
                                <div class="btn-group pull-right-responsive margin-right-3">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        <?= lang('show_gantt_by'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu animated zoomIn pull-right" role="menu">
                                        <li><a href="#"
                                               class="resize-gantt"><?= lang('milestones'); ?></a></li>
                                        <li><a href="#" class="user-gantt"><?= lang('project_members'); ?></a></li>
                                        <li><a href="#" class="status-gantt"><?= lang('status'); ?></a></li>
                                    </ul>
                                </div>
                            </span>
                            <h4 class="card-title"><?= lang('gantt') ?></h4>
                            <div class="">
                                <?php
                                //get gantt data for Milestones
                                $gantt_data = '{name: "' . $project_details->project_name . '", desc: "", values: [{
                                        label: "", from: "' . $project_details->start_date . '", to: "' . $project_details->end_date . '", customClass: "gantt-headerline"
                                        }]},  ';
                                foreach ($all_task_info as $g_task) {
                                    if (!empty($g_task)) {
                                        if ($g_task->milestones_id == 0) {
                                            $tasks_result['uncategorized'][] = $g_task->task_id;
                                        } else {
                                            $milestones_info = $this->db->where('milestones_id', $g_task->milestones_id)->get('tbl_milestones')->row();
                                            $tasks_result[$milestones_info->milestone_name][] = $g_task->task_id;
                                        }
                                    }
                                }
                                if (!empty($tasks_result)) {
                                    foreach ($tasks_result as $cate => $tasks_info):
                                        $counter = 0;
                                        if (!empty($tasks_info)) {
                                            foreach ($tasks_info as $tasks_id):
                                                $task = $this->db->where('task_id', $tasks_id)->get('tbl_task')->row();
                                                if ($cate != 'uncategorized') {
                                                    $milestone = $this->db->where('milestones_id', $task->milestones_id)->get('tbl_milestones')->row();
                                                    if (!empty($milestone)) {
                                                        $m_start_date = $milestone->start_date;
                                                        $m_end_date = $milestone->end_date;
                                                    }
                                                    $classs = 'gantt-timeline';
                                                } else {
                                                    $cate = lang($cate);
                                                    $m_start_date = null;
                                                    $m_end_date = null;
                                                    $classs = '';
                                                }
                                                $milestone_Name = "";
                                                if ($counter == 0) {
                                                    $milestone_Name = $cate;
                                                    $gantt_data .= '
                                        {
                                          name: "' . $milestone_Name . '", desc: "", values: [';

                                                    $gantt_data .= '{
                                        label: "", from: "' . $m_start_date . '", to: "' . $m_end_date . '", customClass: "' . $classs . '"
                                        }';
                                                    $gantt_data .= ']
                                        },  ';
                                                }

                                                $counter++;
                                                $start = ($task->task_start_date) ? $task->task_start_date : $m_end_date;
                                                $end = ($task->due_date) ? $task->due_date : $m_end_date;
                                                if ($task->task_status == "completed") {
                                                    $class = "ganttGrey";
                                                } elseif ($task->task_status == "in_progress") {
                                                    $class = "ganttin_progress";
                                                } elseif ($task->task_status == "not_started") {
                                                    $class = "gantt_not_started";
                                                } elseif ($task->task_status == "deferred") {
                                                    $class = "gantt_deferred";
                                                } else {
                                                    $class = "ganttin_progress";
                                                }
                                                $gantt_data .= '
                                  {
                                    name: "", desc: "' . $task->task_name . '", values: [';

                                                $gantt_data .= '{
                                  label: "' . $task->task_name . '", from: "' . $start . '", to: "' . $end . '", customClass: "' . $class . '"
                                  }';
                                                $gantt_data .= ']
                                  },  ';
                                            endforeach;
                                        }
                                    endforeach;
                                }

                                //get gantt data for status
                                $tasks_status = array('not_started', 'in_progress', 'completed', 'deferred', 'waiting_for_someone');
                                $all_task = $this->db->where('project_id', $project_details->project_id)->get('tbl_task')->result();

                                foreach ($tasks_status as $key => $t_status) {
                                    foreach ($all_task as $v_task) {
                                        if ($v_task->task_status == $t_status) {
                                            $task_with_status[$t_status][] = $v_task;
                                        }

                                        # code...
                                    }
                                }

                                $gantt_data2 = '{name: "' . $project_details->project_name . '", desc: "", values: [{
                                        label: "", from: "' . $project_details->start_date . '", to: "' . $project_details->end_date . '", customClass: "gantt-headerline"
                                        }]},  ';
                                if (!empty($task_with_status)) {
                                    foreach ($task_with_status as $status => $task_info):
                                        $counter = 0;
                                        foreach ($task_info as $t_info):

                                            if ($counter == 0) {
                                                $user_name = $status;
                                                $gantt_data2 .= '
                                        {
                                          name: "' . lang($status) . '", desc: "", values: [';

                                                $gantt_data2 .= '{
                                        label: "", from: "' . $project_details->start_date . '", to: "' . $project_details->end_date . '", customClass: "gantt-timeline"
                                        }';
                                                $gantt_data2 .= ']
                                        },  ';
                                            }
                                            $counter++;
                                            $start = ($t_info->task_start_date) ? $t_info->task_start_date : " ";
                                            $end = ($t_info->due_date) ? $t_info->due_date : " ";
                                            if ($t_info->task_status == "completed") {
                                                $class = "ganttGrey";
                                            } elseif ($t_info->task_status == "in_progress") {
                                                $class = "ganttin_progress";
                                            } elseif ($t_info->task_status == "not_started") {
                                                $class = "gantt_not_started";
                                            } elseif ($t_info->task_status == "deferred") {
                                                $class = "gantt_deferred";
                                            } else {
                                                $class = "ganttin_progress";
                                            }
                                            $gantt_data2 .= '
                                  {
                                    name: "", desc: "' . $t_info->task_name . '", values: [';

                                            $gantt_data2 .= '{
                                  label: "' . $t_info->task_name . '", from: "' . $start . '", to: "' . $end . '", customClass: "' . $class . '"
                                  }';
                                            $gantt_data2 .= ']
                                  },  ';
                                        endforeach;
                                    endforeach;
                                }
                                // all task wise user id
                                $all_task = $this->db->where('project_id', $project_details->project_id)->get('tbl_task')->result();
                                if (!empty($all_task)) {
                                    foreach ($all_task as $v_task) {
                                        if ($v_task->permission == 'all') {
                                            $t_permission_user = $this->items_model->all_permission_user('54');
                                            // get all admin user
                                            $admin_user = get_result('tbl_users', array('role_id' => 1));
                                            // if not exist data show empty array.
                                            if (!empty($permission_user)) {
                                                $permission_user = $permission_user;
                                            } else {
                                                $permission_user = array();
                                            }
                                            if (!empty($admin_user)) {
                                                $admin_user = $admin_user;
                                            } else {
                                                $admin_user = array();
                                            }
                                            $t_assign_user = array_merge($admin_user, $t_permission_user);
                                            foreach ($t_assign_user as $t_users) {
                                                $user_id[] = $t_users->user_id;
                                            }
                                            $task_user[$v_task->task_id] = array_unique($user_id);

                                        } else {
                                            $task_permission = json_decode($v_task->permission);
                                            foreach ($task_permission as $t_user_id => $v_permission) {
                                                $task_user[$v_task->task_id][] = $t_user_id;

                                            }
                                        }
                                    }
                                    foreach ($task_user as $task_id => $users_id) {
                                        foreach ($users_id as $key => $u_id) {
                                            $all_task_by_user[$u_id][] = $task_id;
                                        }
                                    }
                                    // print_r($value);
                                    $permission = $project_details->permission;
                                    if ($permission == 'all') {
                                        $permission_user = $this->items_model->all_permission_user('57');
                                        // get all admin user
                                        $admin_user = get_result('tbl_users', array('role_id' => 1));
                                        // if not exist data show empty array.
                                        if (!empty($permission_user)) {
                                            $permission_user = $permission_user;
                                        } else {
                                            $permission_user = array();
                                        }
                                        if (!empty($admin_user)) {
                                            $admin_user = $admin_user;
                                        } else {
                                            $admin_user = array();
                                        }
                                        $assign_user = array_merge($admin_user, $permission_user);
                                        foreach ($assign_user as $users) {
                                            $p_user[] = $users->user_id;
                                        }
                                        $project_user = array_unique($p_user);
                                    } else {
                                        $get_permission = json_decode($project_details->permission);
                                        foreach ($get_permission as $p_user_id => $v_permission) {
                                            $project_user[] = $p_user_id;
                                        }
                                    }

                                    $gantt_data3 = '{name: "' . $project_details->project_name . '", desc: "", values: [{
                                        label: "", from: "' . $project_details->start_date . '", to: "' . $project_details->end_date . '", customClass: "gantt-headerline"
                                        }]},  ';
                                    foreach ($project_user as $key => $user):
                                        $counter = 0;
                                        foreach ($all_task_by_user as $t_userid => $task_by_user):
                                            if ($user == $t_userid) {
                                                $user_info = $this->db->where('user_id', $user)->get('tbl_account_details')->row();
                                                if ($counter == 0) {
                                                    $user_name = $status;
                                                    $gantt_data3 .= '
                                        {
                                          name: "' . $user_info->fullname . '", desc: "", values: [';

                                                    $gantt_data3 .= '{
                                        label: "", from: "' . $project_details->start_date . '", to: "' . $project_details->end_date . '", customClass: "gantt-timeline"
                                        }';
                                                    $gantt_data3 .= ']
                                        },  ';
                                                }
                                                $counter++;
                                                foreach ($task_by_user as $task_id) {
                                                    $t_info = $this->db->where('task_id', $task_id)->get('tbl_task')->row();
                                                    $start = ($t_info->task_start_date) ? $t_info->task_start_date : " ";
                                                    $end = ($t_info->due_date) ? $t_info->due_date : " ";
                                                    if ($t_info->task_status == "completed") {
                                                        $class = "ganttGrey";
                                                    } elseif ($t_info->task_status == "in_progress") {
                                                        $class = "ganttin_progress";
                                                    } elseif ($t_info->task_status == "not_started") {
                                                        $class = "gantt_not_started";
                                                    } elseif ($t_info->task_status == "deferred") {
                                                        $class = "gantt_deferred";
                                                    } else {
                                                        $class = "ganttin_progress";
                                                    }
                                                    $gantt_data3 .= '
                                  {
                                    name: "", desc: "' . $t_info->task_name . '", values: [';

                                                    $gantt_data3 .= '{
                                  label: "' . $t_info->task_name . '", from: "' . $start . '", to: "' . $end . '", customClass: "' . $class . '"
                                  }';
                                                    $gantt_data3 .= ']
                                  },  ';
                                                }
                                            }
                                        endforeach;
                                    endforeach;
                                }
                                ?>

                                <div class="gantt"></div>
                                <div id="gantData">

                                    <script type="text/javascript">
                                        function ganttChart(ganttData) {
                                            $(function () {
                                                "use strict";
                                                $(".gantt").gantt({
                                                    source: ganttData,
                                                    minScale: "years",
                                                    maxScale: "years",
                                                    navigate: "scroll",
                                                    itemsPerPage: 30,
                                                    onItemClick: function (data) {
                                                        console.log(data.id);
                                                    },
                                                    onAddClick: function (dt, rowId) {
                                                    },
                                                    onRender: function () {
                                                        console.log("chart rendered");
                                                    }
                                                });

                                            });
                                        }

                                        ganttData = [<?=$gantt_data;?>];
                                        ganttChart(ganttData);

                                        $(document).on("click", '.resize-gantt', function (e) {
                                            ganttData = [<?=$gantt_data;?>];
                                            ganttChart(ganttData);
                                        });
                                        $(document).on("click", '.status-gantt', function (e) {
                                            ganttData = [<?=$gantt_data2;?>];
                                            ganttChart(ganttData);
                                        });
                                        <?php if(!empty($gantt_data3)){?>
                                        $(document).on("click", '.user-gantt', function (e) {
                                            ganttData = [<?=$gantt_data3;?>];
                                            ganttChart(ganttData);
                                        });
                                        <?php }?>

                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 17 ? 'active' : '' ?>" id="project_settings" style="position: relative;">
                        <div class="card-body">
                            <h4 class="card-title"><?= lang('project_settings') ?></h4>
                            <div class="card-body">

                                <form action="<?= base_url() ?>admin/projects/update_settings/<?php
                                    if (!empty($project_details)) {
                                        echo $project_details->project_id;
                                    }
                                    ?>" enctype="multipart/form-data" method="post" id="form" class="form-horizontal">
                                    <?php
                                    $project_permissions = $this->db->get('tbl_project_settings')->result();
                                    if (!empty($project_details->project_settings)) {
                                        $current_permissions = $project_details->project_settings;
                                        if ($current_permissions == NULL) {
                                            $current_permissions = '{"settings":"on"}';
                                        }
                                        $get_permissions = json_decode($current_permissions);
                                    }

                                    foreach ($project_permissions as $v_permissions) {
                                        ?>
                                        <div class="form-check form-check-primary mb-3">
                                                <input name="<?= $v_permissions->settings_id ?>"
                                                       value="<?= $v_permissions->settings ?>" <?php
                                                if (!empty($project_details->project_settings)) {
                                                    if (in_array($v_permissions->settings, $get_permissions)) {
                                                        echo "checked=\"checked\"";
                                                    }
                                                } else {
                                                    echo "checked=\"checked\"";
                                                }
                                                ?> type="checkbox" class="form-check-input" id="settings-<?= $v_permissions->settings_id ?>">
                                            <label class="form-check-label" for="settings-<?= $v_permissions->settings_id ?>"> <?= lang($v_permissions->settings) ?></label>
                                        </div>
                                        <hr class="mt-sm mb-sm"/>
                                    <?php } ?>

                                    <div class="row mb-3">
                                        <div class="col-sm-2">
                                            <button type="submit" id="sbtn"
                                                    class="btn btn-primary"><?= lang('updates') ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 8 ? 'active' : '' ?>" id="task_notes" style="position: relative;">
                        <div class="card-body">
                        
                            <h4 class="card-title mb-4"><?= lang('notes') ?></h4>

                            <form action="<?= base_url() ?>admin/projects/save_project_notes/<?php
                            if (!empty($project_details)) {
                                echo $project_details->project_id;
                            }
                            ?>" enctype="multipart/form-data" method="post" id="form" class="form-horizontal">
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <textarea class="form-control textarea" id="elm1" name="notes"><?= $project_details->notes ?></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-2">
                                        <button type="submit" id="sbtn"
                                                class="btn btn-primary"><?= lang('updates') ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="tab-pane <?= $active == 14 ? 'active' : '' ?>" id="project_tickets" style="position: relative;">
                        <div class="card-body">
                            <!-- Tabs within a box -->
                            <ul class="nav nav-tabs bg-light rounded"  role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link <?= $task_active == 1 ? 'active' : ''; ?>" href="#manage_tickets" data-bs-toggle="tab">
                                        <?= lang('tickets') ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"  href="<?= base_url() ?>admin/tickets/index/project_tickets/0/<?= $project_details->project_id ?>">
                                        <?= lang('new_ticket') ?>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-3 text-muted">
                                <!-- ************** general *************-->
                                <div class="tab-pane <?= $task_active == 1 ? 'active' : ''; ?>" id="manage_tickets">
                                    <div class="table-responsive">
                                        <table id="table-tickets" class="table table-striped dt-responsive nowrap w-100 ">
                                            <thead>
                                            <tr>
                                                <th><?= lang('ticket_code') ?></th>
                                                <th><?= lang('subject') ?></th>
                                                <th class="col-date"><?= lang('date') ?></th>
                                                <?php if ($this->session->userdata('user_type') == '1') { ?>
                                                    <th><?= lang('reporter') ?></th>
                                                <?php } ?>
                                                <th><?= lang('department') ?></th>
                                                <th><?= lang('status') ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if (!empty($all_tickets_info)) {
                                                foreach ($all_tickets_info as $v_tickets_info) {
                                                    $can_edit = $this->items_model->can_action('tbl_tickets', 'edit', array('tickets_id' => $v_tickets_info->tickets_id));
                                                    $can_delete = $this->items_model->can_action('tbl_tickets', 'delete', array('tickets_id' => $v_tickets_info->tickets_id));
                                                    if ($v_tickets_info->status == 'open') {
                                                        $s_label = 'danger';
                                                    } elseif ($v_tickets_info->status == 'closed') {
                                                        $s_label = 'success';
                                                    } else {
                                                        $s_label = 'default';
                                                    }
                                                    $profile_info = $this->db->where(array('user_id' => $v_tickets_info->reporter))->get('tbl_account_details')->row();
                                                    $dept_info = $this->db->where(array('departments_id' => $v_tickets_info->departments_id))->get('tbl_departments')->row();
                                                    if (!empty($dept_info)) {
                                                        $dept_name = $dept_info->deptname;
                                                    } else {
                                                        $dept_name = '-';
                                                    } ?>

                                                    <tr>

                                                        <td><span
                                                                    class="badge badge-soft-success"><?= $v_tickets_info->ticket_code ?></span>
                                                        </td>
                                                        <td><a class="text-info"
                                                               href="<?= base_url() ?>admin/tickets/index/tickets_details/<?= $v_tickets_info->tickets_id ?>"><?= $v_tickets_info->subject ?></a>
                                                        </td>
                                                        <td><?= display_datetime($v_tickets_info->created); ?></td>
                                                        <?php if ($this->session->userdata('user_type') == '1') { ?>

                                                            <td>
                                                                <a class="pull-left recect_task  ">
                                                                    <?php if (!empty($profile_info)) {
                                                                        ?>
                                                                        <img style="width: 30px;margin-left: 18px;
                                                             height: 29px;
                                                             border: 1px solid #aaa;"
                                                                             src="<?= base_url() . $profile_info->avatar ?>"
                                                                             class="img-circle">


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
                                                        <td><span
                                                                    class="badge badge-soft-<?= $s_label ?>"><?= ucfirst($status) ?></span>
                                                        </td>

                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- End Tasks Management-->

                            </div>
                        </div>
                    </div>
                    <?php if (!empty($all_invoice_info)) { ?>
                    <div class="tab-pane <?= $active == 11 ? 'active' : '' ?>" id="invoice" style="position: relative;">
                        <div class="card-body">
                            <h4 class="card-title"><?= lang('invoice') ?></h4>
                    
                            <div class="table-responsive">
                                <table id="table-invoice" class="table table-striped dt-responsive nowrap w-100 ">
                                    <thead>
                                    <tr>
                                        <th><?= lang('invoice') ?></th>
                                        <th class="col-date"><?= lang('due_date') ?></th>
                                        <th class="col-currency"><?= lang('amount') ?></th>
                                        <th class="col-currency"><?= lang('due_amount') ?></th>
                                        <th><?= lang('status') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($all_invoice_info as $v_invoices) {
                                        if ($this->invoice_model->get_payment_status($v_invoices->invoices_id) == lang('fully_paid')) {
                                            $invoice_status = lang('fully_paid');
                                            $label = "success";
                                        } elseif ($v_invoices->emailed == 'Yes') {
                                            $invoice_status = lang('sent');
                                            $label = "info";
                                        } else {
                                            $invoice_status = lang('draft');
                                            $label = "default";
                                        }
                                        ?>
                                        <tr>
                                            <td><a class="text-info"
                                                   href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_invoices->invoices_id ?>"><?= $v_invoices->reference_no ?></a>
                                            </td>
                                            <td><?= display_datetime($v_invoices->due_date) ?>
                                                <?php
                                                $payment_status = $this->invoice_model->get_payment_status($v_invoices->invoices_id);
                                                if (strtotime($v_invoices->due_date) < time() AND $payment_status != lang('fully_paid')) { ?>
                                                    <span
                                                            class="badge badge-soft-danger "><?= lang('overdue') ?></span>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                            <td><?= display_money($this->invoice_model->calculate_to('invoice_cost', $v_invoices->invoices_id), $currency->symbol) ?></td>
                                            <td><?= display_money($this->invoice_model->calculate_to('invoice_due', $v_invoices->invoices_id), $currency->symbol) ?></td>
                                            <td><span
                                                        class="badge badge-soft-<?= $label ?>"><?= $invoice_status ?></span>
                                                <?php if ($v_invoices->recurring == 'Yes') { ?>
                                                    <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                          title="<?= lang('recurring') ?>"
                                                          class="badge badge-soft-primary"><i
                                                                class="fa fa-retweet"></i></span>
                                                <?php } ?>

                                            </td>

                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="tab-pane <?= $active == 12 ? 'active' : '' ?>" id="estimates" style="position: relative;">
                        <div class="card-body">
                            <!-- Tabs within a box -->
                            <ul class="nav nav-tabs bg-light rounded"  role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link <?= $estimate == 1 ? 'active' : ''; ?>" href="#manage_estimates" data-bs-toggle="tab">
                                        <?= lang('estimates') ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= base_url() ?>admin/estimates/index/project/<?= $project_details->project_id ?>">
                                        <?= lang('new_estimate') ?>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-3 text-muted">
                                <!-- ************** general *************-->
                                <div class="tab-pane <?= $estimate == 1 ? 'active' : ''; ?>" id="manage_estimates">
                                    <div class="table-responsive">
                                        <table id="table-estimates" class="table table-striped dt-responsive nowrap w-100 ">
                                            <thead>
                                            <tr>
                                                <th><?= lang('estimate') ?></th>
                                                <th><?= lang('due_date') ?></th>
                                                <th><?= lang('amount') ?></th>
                                                <th><?= lang('status') ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($all_estimates_info as $v_estimates) {
                                                if ($v_estimates->status == 'Pending') {
                                                    $label = "info";
                                                } elseif ($v_estimates->status == 'Accepted') {
                                                    $label = "success";
                                                } else {
                                                    $label = "danger";
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <a class="text-info"
                                                           href="<?= base_url() ?>admin/estimates/index/estimates_details/<?= $v_estimates->estimates_id ?>"><?= $v_estimates->reference_no ?></a>
                                                    </td>
                                                    <td><?= display_datetime($v_estimates->due_date) ?>
                                                        <?php
                                                        if (strtotime($v_estimates->due_date) < time() AND $v_estimates->status == 'Pending') { ?>
                                                            <span
                                                                    class="badge badge-soft-danger "><?= lang('expired') ?></span>
                                                        <?php }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?= display_money($this->estimates_model->estimate_calculation('estimate_amount', $v_estimates->estimates_id), $currency->symbol); ?>
                                                    </td>
                                                    <td><span
                                                                class="badge badge-soft-<?= $label ?>"><?= lang(strtolower($v_estimates->status)) ?></span>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 7 ? 'active' : '' ?>" id="timesheet" style="position: relative;">
                        <div class="card-body">
                            <style>
                                .tooltip-inner {
                                    white-space: pre-wrap;
                                }
                            </style>
                            <!-- Tabs within a box -->
                            <ul class="nav nav-tabs bg-light rounded"  role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link <?= $time_active == 1 ? 'active' : ''; ?>" href="#general" data-bs-toggle="tab">
                                        <?= lang('timesheet') ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= $time_active == 2 ? 'active' : ''; ?>" href="#contact"  data-bs-toggle="tab">
                                        <?= lang('manual_entry') ?>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-3 text-muted">
                                <!-- ************** general *************-->
                                <div class="tab-pane <?= $time_active == 1 ? 'active' : ''; ?>" id="general">
                                    <div class="table-responsive">
                                        <table id="table-tasks-timelog" class="table table-striped dt-responsive nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th><?= lang('user') ?></th>
                                                <th><?= lang('start_time') ?></th>
                                                <th><?= lang('stop_time') ?></th>

                                                <th><?= lang('project_name') ?></th>
                                                <th class="col-time"><?= lang('time_spend') ?></th>
                                                <th><?= lang('action') ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if (!empty($total_timer)) {
                                                foreach ($total_timer as $v_timer) {
                                                    $aproject_info = $this->db->where(array('project_id' => $v_timer->project_id))->get('tbl_project')->row();
                                                    if (!empty($aproject_info)) {
                                                        if ($v_timer->start_time != 0 && $v_timer->end_time != 0) {
                                                            ?>
                                                            <tr id="table-timesheet-<?= $v_timer->tasks_timer_id ?>">
                                                                <td class="small">

                                                                    <a class="pull-left recect_task  ">
                                                                        <?php
                                                                        $profile_info = $this->db->where(array('user_id' => $v_timer->user_id))->get('tbl_account_details')->row();
                                                                        $user_info = $this->db->where(array('user_id' => $v_timer->user_id))->get('tbl_users')->row();
                                                                        if (!empty($user_info)) {
                                                                            ?>
                                                                            <img style="width: 30px;margin-left: 18px;
                                                                                     height: 29px;
                                                                                     border: 1px solid #aaa;"
                                                                                 src="<?= base_url() . $profile_info->avatar ?>"
                                                                                 class="img-circle">
                                                                        <?php } else {
                                                                            echo '-';
                                                                        } ?>
                                                                    </a>


                                                                </td>

                                                                <td><span
                                                                            class="badge badge-soft-success"><?= display_datetime($v_timer->start_time, true) ?></span>
                                                                </td>
                                                                <td><span
                                                                            class="badge badge-soft-danger"><?= display_datetime($v_timer->end_time, true) ?></span>
                                                                </td>

                                                                <td>
                                                                    <a href="<?= base_url() ?>admin/projects/project_details/<?= $v_timer->project_id ?>"
                                                                       class="text-info small"><?= $project_details->project_name ?>
                                                                        <?php
                                                                        if (!empty($v_timer->reason)) {
                                                                            $edit_user_info = $this->db->where(array('user_id' => $v_timer->edited_by))->get('tbl_users')->row();
                                                                            echo '<i class="text-danger" data-html="true" data-bs-toggle="tooltip" data-bs-placement="top" title="Reason : ' . $v_timer->reason . '<br>' . ' Edited By : ' . $edit_user_info->username . '">Edited</i>';
                                                                        }
                                                                        ?>
                                                                    </a></td>
                                                                <td>
                                                                    <small
                                                                            class="small text-muted"><?= $this->items_model->get_time_spent_result($v_timer->end_time - $v_timer->start_time) ?></small>
                                                                </td>
                                                                <td>
                                                                    <?= btn_edit('admin/projects/project_details/' . $v_timer->project_id . '/7/' . $v_timer->tasks_timer_id) ?>
                                                                    <?php echo ajax_anchor(base_url("admin/projects/update_project_timer/" . $v_timer->tasks_timer_id . '/delete_task_timmer'), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table-timesheet-" . $v_timer->tasks_timer_id)); ?>
                                                                </td>

                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane <?= $time_active == 2 ? 'active' : ''; ?>" id="contact">
                                    <div class="card-body">
                                    <form role="form" enctype="multipart/form-data" id="form"
                                          action="<?php echo base_url(); ?>admin/projects/update_project_timer/<?php
                                          if (!empty($project_timer_info)) {
                                              echo $project_timer_info->tasks_timer_id;
                                          }
                                          ?>" method="post" class="form-horizontal">
                                        <?php
                                        if (!empty($project_timer_info)) {
                                            $start_date = date('d-m-Y H-i', strtotime($project_timer_info->start_time));
                                            $end_date = date('d-m-Y H-i', strtotime($project_timer_info->end_time));
                                        } else {
                                            $start_date = date('Y-m-d H:i');
                                            $end_date = date('Y-m-d H:i');
                                        }
                                        ?>
                                        <?php if ($this->session->userdata('user_type') == '1' && empty($project_timer_info->tasks_timer_id)) { ?>
                                            <div class="row mb-3 margin">
                                                <div class="col-md-6 center-block">
                                                    <label class="col-form-label"><?= lang('select') . ' ' . lang('project') ?>
                                                        <span
                                                                class="required">*</span></label>
                                                    <select class="form-control select_box" name="project_id"
                                                            required="" style="width: 100%">
                                                        <?php
                                                        $all_tasks_info = get_result('tbl_project');
                                                        if (!empty($all_tasks_info)):foreach ($all_tasks_info as $v_task_info):
                                                            ?>
                                                            <option
                                                                    value="<?= $v_task_info->project_id ?>" <?= $v_task_info->project_id == $project_details->project_id ? 'selected' : null ?>><?= $v_task_info->project_name ?></option>
                                                        <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <input type="hidden" name="project_id"
                                                   value="<?= $project_details->project_id ?>">
                                        <?php } ?>
                                        <div class="row mb-3 margin">
                                            <div class="col-md-6">
                                                <label class="col-form-label"><?= lang('start_date') ?> </label>
                                                <div class="input-group"  id="datepicker1">
                                                    <input type="text" name="start_time" class="form-control" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container='#datepicker1' data-provide="datepicker" value="<?= $start_date ?>">
                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3 margin">
                                            <div class="col-md-6">
                                                <label class="col-form-label"><?= lang('end_date') ?></label>
                                                <div class="input-group" id="datepicker2">
                                                    <input type="text" name="end_time" class="form-control" value="<?= $end_date ?>"
                                                           data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container='#datepicker2' data-provide="datepicker" >
                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3 margin">
                                            <div class="col-md-6">
                                                <label class="col-form-label"><?= lang('edit_reason') ?> <span
                                                            class="required">*</span></label>
                                                <div>
                                                        <textarea class="form-control" name="reason" required="" rows="6"><?php
                                                            if (!empty($project_timer_info)) {
                                                                echo $project_timer_info->reason;
                                                            }
                                                            ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3" style="margin-top: 20px;">
                                            <div class="col-lg-6">
                                                <button type="submit"
                                                        class="btn btn-xs btn-primary"><?= lang('updates') ?></button>
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
</div>