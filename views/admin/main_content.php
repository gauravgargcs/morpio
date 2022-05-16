<style type="text/css">
    .tbl-overflow{
        overflow: inherit !important;
    }
</style>
<?php
$user_id = $this->session->userdata('user_id');
$profile_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
$user_info = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
$display = config_item('logo_or_icon');
$user_role_id= $this->session->userdata('user_type');

echo message_box('success');
echo message_box('error');
$curency = $this->admin_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
if (empty($curency)) {
    $curency = $this->admin_model->check_by(array('code' => 'AUD'), 'tbl_currencies');
}
$all_report =0 ; // result_by_company('tbl_dashboard', array('report' => 1), 'order_no', true);

if ($user_role_id == 1) {
    $where = array( 'status' => 1);
} else {
    $where = array( 'status' => 1, 'for_staff' => 1);
}

if ($user_role_id == 1 && $user_info->super_admin == 'Yes') {
    $user_role= lang('super_admin');
} elseif ($user_role_id == 1 && $user_info->super_admin == 'owner') {
    $user_role= lang('owner');
} elseif ($user_role_id == 1) {
    $user_role=lang('admin');
} elseif ($user_role_id == 3) {
    $user_role=lang('staff');
} else {
    $user_role=lang('client');
}
                                        
$all_order_data = result_by_company('tbl_dashboard', $where,'order_no', true);

$all_project = $this->admin_model->get_permission('tbl_project');

$project_overdue = 0;
if (!empty($all_project)) {
    foreach ($all_project as $v_project) {
        if (time() > strtotime($v_project->end_date) AND $v_project->progress < 100) {
            $project_overdue += count($v_project->project_id);
        }
    }
}
// tasks
$task_all_info = $this->admin_model->get_permission('tbl_task');

$task_overdue = 0;

if (!empty($task_all_info)):
    foreach ($task_all_info as $v_task_info):
        $due_date = $v_task_info->due_date;
        $due_time = strtotime($due_date);
        $current_time = time();
        if ($current_time > $due_time && $v_task_info->task_progress < 100) {
            $task_overdue += count($v_task_info->task_id) ? count($v_task_info->task_id) : 0;
        }
    endforeach;
endif;

// invoice
$all_invoices_info = $this->admin_model->get_permission('tbl_invoices');
$invoice_overdue = 0;
$total_invoice_amount = 0;
if (!empty($all_invoices_info)) {
    foreach ($all_invoices_info as $v_invoices) {
        $payment_status = $this->invoice_model->get_payment_status($v_invoices->invoices_id);
        if (strtotime($v_invoices->due_date) < time() AND $payment_status != lang('fully_paid')) {
            $invoice_overdue += count($v_invoices->invoices_id);
        }
        $total_invoice_amount += $this->invoice_model->calculate_to('total', $v_invoices->invoices_id);
    }
}
// estimate
$all_estimates_info = $this->admin_model->get_permission('tbl_estimates');
$estimate_overdue = 0;
$total_estimate_amount = 0;
if (!empty($all_estimates_info)) {
    foreach ($all_estimates_info as $v_estimates) {
        if (strtotime($v_estimates->due_date) < time() AND $v_estimates->status == 'Pending') {
            $estimate_overdue += count($v_estimates->estimates_id);
        }
        $total_estimate_amount += $this->estimates_model->estimate_calculation('total', $v_estimates->estimates_id);
    }
}
// bugs
$all_bugs_info = $this->admin_model->get_permission('tbl_bug');
$bug_unconfirmed = 0;
if (!empty($all_bugs_info)):foreach ($all_bugs_info as $key => $v_bugs):
    if ($v_bugs->bug_status == 'unconfirmed') {
        $bug_unconfirmed += count($v_bugs->bug_id);
    }
endforeach;
endif;
$all_opportunity = $this->admin_model->get_permission('tbl_opportunities');
$opportunity_overdue = 0;
if (!empty($all_opportunity)) {
    foreach ($all_opportunity as $v_opportunity) {
        if (time() > strtotime($v_opportunity->close_date) AND $v_opportunity->probability < 100) {
            $opportunity_overdue += count($v_opportunity->opportunities_id);
        }
    }
}
$my_project = $this->admin_model->my_permission('tbl_project', $this->session->userdata('user_id'));
$my_task = $this->tasks_model->get_tasks(null);


$total_projects=count($my_project);
$total_tasks=count($my_task);
?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $this->breadcrumbs->build_breadcrumbs(); ?></h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            
        </div>
    </div>
</div>
<!-- end page title -->

<?php if (!empty($all_order_data)) { ?>

<?php /* foreach ($all_order_data as $v_order) {    ?>


<?php if ($v_order->name == 'my_calendar' && $v_order->status == 1) {
?>
<div class="row main-content-page">
    <?php $this->load->view('admin/calendar_body'); ?>    
</div>
<?php } } */ ?>
    
<!-- end row -->
<div class="row main-content-page">
    <div class="mb-lg mb-3">
                                 <a type="button" href="javascript://" onclick="enableDragg()" class="btn btn-primary" id="dragg-start" ><?=lang('Customize_dashboard');?> </a>
                                 <a type="button" href="javascript://" onclick="disableDragg()" class="btn btn-secondary" style="display:none;" id="dragg-stop"><?=lang('save_layout');?></a>
                               
               
            </div>
    <div class="row dash-list" id="dash_menu">
        <?php foreach ($all_order_data as $v_order) {    ?>

            <?php if ($v_order->name == 'my_calendar' && $v_order->status == 1) {
?>
<div class="col-12 item-gg">
       <div class="task-box no-drag" id="report-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
        <div class="card-body">
            <div class="row main-content-page">
                <?php $this->load->view('admin/calendar_body'); ?>    
            </div>
        </div>
    </div>
</div>
<?php }  ?>
            <?php if ($v_order->name == 'income_expenses_report' && $v_order->status == 1 &&  can_action('46', 'view') ) { ?>
                 <div class="col-sm-6 item-gg">
            <!--        ******** transactions ************** -->
                <div class="task-box card" id="report-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
                    <div class="card-body">
                        <div class="row mb-2">  
                            <div class="dropdown float-end">
                                <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item deletereport" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                                </div>
                            </div> <!-- end dropdown -->
      
                            <div class="col">
                                <div class="social-source text-center">
                                    <div class="avatar-xs mx-auto">
                                        <span>
                                            <i class="fa fa-plus fa-2x"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-12"><?= lang('income_today') ?></h5>
                                    <p class="text-muted mb-0"><?php   if (!empty($today_income)) {
                                                    $today_income = $today_income;
                                                } else {
                                                    $today_income = '0';
                                                }
                                                echo display_money($today_income, $curency->symbol);
                                        ?></p>
                                    <span class="ms-2 text-truncate"><a href="<?= base_url() ?>admin/transactions/deposit" class="mt0 mb0"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a></span> 
                                </div>
                            </div>
                            <div class="col">
                                <div class="social-source text-center">
                                    <div class="avatar-xs mx-auto ">
                                        <span>
                                            <i class="fa fa-minus fa-2x"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-12"><?= lang('expense_today') ?></h5>
                                    <p class="text-muted mb-0"><?php if (!empty($today_expense)) {
                                            $today_expense = $today_expense;
                                        } else {
                                            $today_expense = '0';
                                        }
                                        echo display_money($today_expense, $curency->symbol);
                                        ?> </p>
                                    <span class="ms-2 text-truncate"><a href="<?= base_url() ?>admin/transactions/expense" class="mt0 mb0"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a></span> 
                                </div>
                            </div>
                            <div class="col">
                                <div class="social-source text-center">
                                    <div class="avatar-xs mx-auto ">
                                        <span>
                                            <i class="fa fa-plus fa-2x"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-12"><?= lang('total_income') ?></h5>
                                    <p class="text-muted mb-0"><?php
                                        if (!empty($total_income)) {
                                            $total_income = $total_income;
                                        } else {
                                            $total_income = '0';
                                        }

                                        echo display_money($total_income, $curency->symbol);
                                        ?></p>
                                    <span class="ms-2 text-truncate"><a href="<?= base_url() ?>admin/transactions/deposit" class="mt0 mb0"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a></span> 
                                </div>
                            </div>
                            <div class="col">
                                <div class="social-source text-center">
                                    <div class="avatar-xs mx-auto ">
                                        <span>
                                            <i class="fa fa-minus fa-2x"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-12"><?= lang('total_expense') ?></h5>
                                    <p class="text-muted mb-0"><?php
                                    if (!empty($total_expense)) {
                                        $total_expense = $total_expense;
                                    } else {
                                        $total_expense = '0';
                                    }
                                    echo display_money($total_expense, $curency->symbol);
                                    ?></p>
                                    <span class="ms-2 text-truncate"><a href="<?= base_url() ?>admin/transactions/expense" class="mt0 mb0"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a></span> 
                                </div>
                            </div>
                        </div>
                  
                        <div class="row mb-2"> 
                        <hr> 
                           <!--  <div class="dropdown float-end">
                                <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item deletereport" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                                </div>
                            </div>  --><!-- end dropdown -->
                            <div class="col">
                                <div class="social-source text-center">
                                    <div class="avatar-xs mx-auto ">
                                        <span>
                                            <i class="fa fa-shopping-cart fa-2x"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-12"><?= lang('invoice_today') ?></h5>
                                    <p class="text-muted mb-0"><?php
                                    $date = date('d-m-Y H-i');
                                    $all_items = $this->db->get('tbl_items')->result();
                                    $today_invoice = 0;
                                    if (!empty($all_items)) {
                                        foreach ($all_items as $in_items) {
                                            $invoice_date = date('d-m-Y H-i', strtotime($in_items->date_saved));
                                            if ($invoice_date == $date) {
                                                $today_invoice += $in_items->total_cost;
                                            }
                                        }

                                    }
                                    echo display_money($today_invoice, $curency->symbol);

                                    ?></p>
                                    <span class="ms-2 text-truncate"><a href="<?= base_url() ?>admin/invoice/manage_invoice" class="mt0 mb0"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a></span> 
                                </div>
                            </div>

                            <div class="col">
                                <div class="social-source text-center">
                                    <div class="avatar-xs mx-auto ">
                                        <span>
                                            <i class="fa fa-money fa-2x"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-12"><?= lang('payment_today') ?></h5>
                                    <p class="text-muted mb-0"><?php
                                                                echo display_money($this->db->select_sum('amount')->where('payment_date', $date)->get('tbl_payments')->row()->amount, $curency->symbol);

                                                                ?></p>
                                    <span class="ms-2 text-truncate"><a href="<?= base_url() ?>admin/invoice/all_payments" class="mt0 mb0"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a></span> 
                                </div>
                            </div>

                            <div class="col">
                                <div class="social-source text-center">
                                    <div class="avatar-xs mx-auto ">
                                        <span>
                                            <i class="fa fa-money fa-2x"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-12"><?= lang('paid_amount') ?></h5>
                                    <p class="text-muted mb-0"><?php
                                                                if (!empty($invoce_total)) {
                                                                    if (!empty($invoce_total['paid'])) {
                                                                        $paid = 0;
                                                                        foreach ($invoce_total['paid'] as $cur => $total) {
                                                                            $paid += $total;
                                                                        }
                                                                        echo display_money($paid, $curency->symbol);
                                                                    } else {
                                                                        echo '0.00';
                                                                    }
                                                                } else {
                                                                    echo '0.00';
                                                                }
                                                                ?></p>
                                    <span class="ms-2 text-truncate"><a href="<?= base_url() ?>admin/invoice/all_payments" class="mt0 mb0"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a></span> 
                                </div>
                            </div>

                            <div class="col">
                                <div class="social-source text-center">
                                    <div class="avatar-xs mx-auto ">
                                        <span>
                                            <i class="fa fa-usd fa-2x"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-12"><?= lang('due_amount') ?></h5>
                                    <p class="text-muted mb-0"><?php
                                                                if (!empty($invoce_total)) {
                                                                    $total_due = 0;
                                                                    if (!empty($invoce_total['due'])) {
                                                                        foreach ($invoce_total['due'] as $cur => $d_total) {
                                                                            $total_due += $d_total;
                                                                        }
                                                                        echo display_money($total_due, $curency->symbol);
                                                                    } else {
                                                                        echo '0.00';
                                                                    }
                                                                } else {
                                                                    echo '0.00';
                                                                }
                                                                ?></p>
                                    <span class="ms-2 text-truncate"><a href="<?= base_url() ?>admin/invoice/manage_invoice" class="mt0 mb0"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a></span> 
                                </div>
                            </div>
                        </div>
               
                        <div class="row mb-2">  
                            <hr>
                            <!-- <div class="dropdown float-end">
                                <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item deletereport" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                                </div>
                            </div> --> <!-- end dropdown -->
                            <div class="col">
                                <div class="social-source text-center">
                                    <div class="avatar-xs mx-auto ">
                                        <span>
                                            <i class="fa fa-tasks fa-2x"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-12"><?= lang('in_progress') . ' ' . lang('task') ?></h5>
                                    <p class="text-muted mb-0"><?php  echo count(result_by_company('tbl_task', array('task_status' => 'in_progress')));  ?></p>
                                    <span class="ms-2 text-truncate"><a href="<?= base_url() ?>admin/tasks/all_task" class="mt0 mb0"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a></span> 
                                </div>
                            </div>

                            <div class="col">
                                <div class="social-source text-center">
                                    <div class="avatar-xs mx-auto ">
                                        <span>
                                            <i class="fa fa-ticket fa-2x"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-12"><?= lang('open') . ' ' . lang('tickets') ?></h5>
                                    <p class="text-muted mb-0"><?= count(result_by_company('tbl_tickets', array('status' => 'open'))); ?></p>
                                    <span class="ms-2 text-truncate"><a href="<?= base_url() ?>admin/tickets/open" class="mt0 mb0"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a></span> 
                                </div>
                            </div>

                            <div class="col">
                                <div class="social-source text-center">
                                    <div class="avatar-xs mx-auto ">
                                        <span>
                                            <i class="fa fa-bug fa-2x"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-12"><?= lang('in_progress') . ' ' . lang('bugs') ?></h5>
                                    <p class="text-muted mb-0"><?php echo count(result_by_company('tbl_bug', array('bug_status' => 'in_progress')));  ?></p>
                                    <span class="ms-2 text-truncate"><a href="<?= base_url() ?>admin/bugs" class="mt0 mb0"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a></span> 
                                </div>
                            </div>

                            <div class="col">
                                <div class="social-source text-center">
                                    <div class="avatar-xs mx-auto ">
                                        <span>
                                            <i class="fa fa-folder-open-o fa-2x"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-12"><?= lang('in_progress') . ' ' . lang('project') ?></h5>
                                    <p class="text-muted mb-0"><?php echo count(result_by_company('tbl_project', array('project_status' => 'in_progress')));  ?></p>
                                    <span class="ms-2 text-truncate"><a href="<?= base_url() ?>admin/projects" class="mt0 mb0"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a></span> 
                                </div>
                            </div>     
                        </div> 
                    </div>
                </div>
            </div>
            <?php } ?>
        <?php if ($v_order->name == 'my_tasks' && $v_order->status == 1 ) { ?>
        <div class="col-sm-6 item-gg">
            <div class="card task-box" id="dash-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item deletedash" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                        </div>
                    </div> <!-- end dropdown -->

                    <h4 class="card-title mb-2"><?= lang('my_tasks') ?></h4>
                    <div data-simplebar style="max-height: 350px;">  
                        <div class="table-responsive">
                            <table class="table table-nowrap align-middle mb-0" style="table-layout: auto;" id="my_task_table">
                                <thead>
                                    <tr>
                                        <!-- <th data-check-all> 
                                            <div class="form-check font-size-16 check-all">
                                                <input type="checkbox" id="parent_present" class="form-check-input">
                                                <label for="parent_present" class="toggle form-check-label"></label>
                                            </div>
                                        </th> -->
                                        <th></th>
                                        <th><?= lang('task_name') ?></th>
                                        <th><?= lang('end_date') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($my_task)):foreach ($my_task as $v_my_task):
                                    if ($v_my_task->task_status != 6) {
                                        $due_date = $v_my_task->due_date;
                                        $due_time = strtotime($due_date);
                                        $current_time = time();
                                        ?>
                                    <tr>
                                        <td style="width: 40px;"> 
                                            <div class="complete form-check font-size-16" style="width: 40px;" data-bs-toggle="tooltip" data-bs-placement="top" title="<?=lang('complete');?>">
                                                <input class="form-check-input" type="checkbox" id="mytaskCheck<?= $v_my_task->task_id ?>" data-id="<?= $v_my_task->task_id ?>" style="position: absolute;" <?php if ($v_my_task->task_progress >= 100) { echo 'checked'; } ?>>
                                                <label class="form-check-label" for="mytaskCheck<?= $v_my_task->task_id ?>"></label>
                                            </div>
                                        </td>
                                        <td style="width: 200px;">
                                            <div style="width: 200px;">
                                                <h5 class="text-truncate font-size-12 m-0" style="word-wrap: break-word;"><a class="text-dark" href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_my_task->task_id ?>"><?php echo $v_my_task->task_name; ?></a></h5>
                                                
                                                 <?php if ($current_time > $due_time && $v_my_task->task_progress < 100) { ?>
                                                    <span class="badge rounded-pill badge-soft-danger"><?= lang('overdue') ?></span>
                                                    <?php } ?>
                                                    <br>
                                                <div class="progress progress-xs progress-striped active" style="">
                                                    <div class="progress-bar progress-bar-<?php echo ($v_my_task->task_progress >= 100) ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?= $v_my_task->task_progress ?>%;" aria-valuenow="<?= $v_my_task->task_progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>

                                        </td>
                                        
                                        <td>
                                            <?= display_datetime($due_date) ?>
                                        </td>

                                    </tr>
                                <?php
                                    }
                                endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                  <div class="card-footer bg-transparent border-top">
                    <div class="text-center">
                        <a class="btn btn-primary waves-effect waves-light" target="_blank" href="<?= base_url() ?>admin/tasks/all_task"><?= lang('view_all') ?></a>
                        
                        <a class="btn btn-primary waves-effect waves-light" href="<?= base_url() ?>admin/tasks/new_tasks" ><?= lang('add_new') ?></a>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($v_order->name == 'my_project' && $v_order->status == 1  &&  can_action('57', 'view')) { ?>
        <div class="col-sm-6 item-gg">
            <div class="card task-box" id="dash-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item deletedash" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                        </div>
                    </div> <!-- end dropdown -->

                    <h4 class="card-title mb-2"><?= lang('my_project') ?></h4>
                    <div data-simplebar style="max-height: 350px;">  
                        <div class="table-responsive">
                            <table class="table table-nowrap align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th><?= lang('project_name') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <th><?= lang('end_date') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($my_project)) {
                                    foreach ($my_project as $v_my_project):
                                        if ($v_my_project->project_status != 'completed' AND $v_my_project->progress < 100) {
                                            ?>
                                    <tr>
                                        <td>
                                            <h5 class="text-truncate font-size-14 m-0"><a class="text-dark" href="<?= base_url() ?>admin/projects/project_details/<?= $v_my_project->project_id ?>"><?= $v_my_project->project_name ?></a></h5>
                                            
                                            <div class="progress progress-xs progress-striped active">
                                                <div class="progress-bar progress-bar-<?php echo ($v_my_project->progress >= 100) ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?= $v_my_project->progress ?>%;" aria-valuenow="<?= $v_my_project->progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>

                                        </td>
                                        <td>
                                            <?php if (time() > strtotime($v_my_project->end_date) AND $v_my_project->progress < 100) { ?>
                                            <span class="badge rounded-pill badge-soft-danger pull-right"><?= lang('overdue') ?></span>
                                            <?php } ?>
                                        </td>
                                        <td><?php if (!empty($v_my_project->end_date) && $v_my_project->end_date!='0000-00-00 00:00:00'){
                                                                echo display_datetime($v_my_project->end_date);    
                                                        }else{ echo '-'; }  ?>
                                        </td>

                                    </tr>
                                <?php
                                    }
                                endforeach; ?>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($v_order->name == 'todo_list' && $v_order->status == 1) {
        $othertodo_where=array('user_id'=>$this->session->userdata('user_id'), 'status!='=> 3);
        $my_todo_list = $this->db->where($othertodo_where)->order_by('order', 'ASC')->get('tbl_todo')->result();
        $completedtodo_where=array('user_id'=>$this->session->userdata('user_id'), 'status'=> 3);
        $my_completed_todo_list = $this->db->where($completedtodo_where)->order_by('order', 'ASC')->get('tbl_todo')->result();
        $todo_status = $this->admin_model->get_todo_status();
        
        ?>
        <div class="col-sm-6 item-gg">
            <div class="card task-box" id="dash-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item deletedash" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                        </div>
                    </div> <!-- end dropdown -->
                    <h4 class="card-title mb-2"><?= lang('to_do') . ' ' . lang('list') ?></h4>
                    <ul class="nav nav-tabs bg-light rounded bg-light rounded">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#todo_progress" role="tab"><?= lang('in_process') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#todo_done" role="tab"><?= lang('completed') ?></a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content text-muted">
                        <div class="tab-pane active mt-4" id="todo_progress" role="tabpanel">
                            <div data-simplebar style="max-height: 350px;">  
                                <div class="table-responsive">
                                    <table class="table table-nowrap align-middle mb-0" style="table-layout: auto;" >
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th style="width: 20%;" class="text-wrap"><?= lang('what') . ' ' . lang('to_do') ?></th>
                                                <th><?= lang('status') ?></th>
                                                <th><?= lang('end_date') ?></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($my_todo_list)){ 
                                            foreach ($my_todo_list as $tkey => $my_todo):
                                                $mytodo_status=$my_todo->status;

                                                $mytodo_status_arr = $this->admin_model->get_todo_status($mytodo_status);

                                                $mytodo_status_name= $mytodo_status_arr[0]['name'];
                                                $mytodo_status_label= $mytodo_status_arr[0]['label'];
                                                if (!$mytodo_status_label) {
                                                    $mytodo_status_label = 'primary';
                                                }

                                                $todo_label = '<small style="font-size:10px;padding:2px;" class="font-size-11 badge badge-pill  badge-soft-'.$mytodo_status_label.'">' .$mytodo_status_name. '</small>';

                                                if (!empty($my_todo->due_date)) {
                                                    $due_date = display_datetime($my_todo->due_date);
                                                } else {
                                                    $due_date = date('d-m-Y H-i');
                                                }
                                                $totaldaysleft=daysleft($due_date);
                                            ?>
                                            <tr>
                                                <td style="width: 5%;">
                                                    <div class="complete-todo form-check" data-bs-toggle="tooltip" data-bs-placement="top" title="<?=lang('complete');?>">
                                                        <input class="form-check-input" type="checkbox" data-id="<?= $my_todo->todo_id ?>" style="position: absolute;" <?php
                                                            if ($my_todo->status == 3) { echo 'checked';  } ?>>
                                                        <label class="form-check-label" for="<?= $my_todo->todo_id ?>"></label>
                                                    </div>
                                                </td>
                                                <td style="width: 20%;" class="text-break">
                                                    <h5 class="text-truncate font-size-12 mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $my_todo->notes;?>" style="word-wrap: break-word;"><a  href="#" class="text-dark"><?php echo $my_todo->title; ?></a></h5>
                                                    <?php if (!empty($my_todo->assigned) && $my_todo->assigned != 0) {
                                                        $a_userinfo = $this->db->where('user_id', $my_todo->assigned)->get('tbl_account_details')->row();
                                                        ?>
                                                    <p class="text-muted mb-0"><?= lang('assign_by') ?> <a class="text-danger" href="<?= base_url() ?>admin/user/user_details/<?= $my_todo->assigned ?>"> <?= $a_userinfo->fullname ?></a></p>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?= $todo_label ?>
                                                </td>
                                                <td style="width: 10%;">
                                                    <strong data-bs-toggle="tooltip" data-bs-placement="top"  title="<?= date("l", strtotime($due_date)) ?>"><?= display_datetime($due_date) ?>
                                                    <br>
                                                        <span class="block"><?php if($totaldaysleft){ echo "- ".$totaldaysleft; } ?></span>

                                                    </strong>
                                                </td>
                                                <td style="width: 5%;">
                                                    <?= btn_edit_modal('admin/dashboard/new_todo/' . $my_todo->todo_id) ?>
                                                    <?= btn_delete('admin/dashboard/delete_todo/' . $my_todo->todo_id) ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php }else{ ?> 
                                            <tr><td colspan="5"  class="text-center"><?=lang('no_data');?></td></tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane mt-4" id="todo_done" role="tabpanel">
                            <div data-simplebar style="max-height: 350px;">  
                                <div class="table-responsive">
                                    <table class="table table-nowrap align-middle mb-0" style="table-layout: auto;" >
                                        <thead>
                                            <tr>
                                                <th style="width: 20%;"><?= lang('what') . ' ' . lang('to_do') ?></th>
                                                <th><?= lang('end_date') ?></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($my_completed_todo_list)){ 
                                            foreach ($my_completed_todo_list as $tkey => $my_todo):
                                                    $mytodo_status=$my_todo->status;

                                                    $mytodo_status_arr = $this->admin_model->get_todo_status($mytodo_status);

                                                    $mytodo_status_name= $mytodo_status_arr[0]['name'];
                                                    $mytodo_status_label= $mytodo_status_arr[0]['label'];
                                                    if (!$mytodo_status_label) {
                                                        $mytodo_status_label = 'primary';
                                                    }

                                                    $todo_label = '<small style="font-size:10px;padding:2px;" class="font-size-11 badge badge-pill  badge-soft-'.$mytodo_status_label.'">' .$mytodo_status_name. '</small>';

                                                    if (!empty($my_todo->due_date)) {
                                                        $due_date = $my_todo->due_date;
                                                    } else {
                                                        $due_date = date('D-M-Y');
                                                    }
                                                    $totaldaysleft=daysleft($due_date);

                                            ?>
                                            <tr>
                                                <td style="width: 20%;" class="text-break">
                                                    <h5 class="text-truncate font-size-12 mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $my_todo->notes;?>" style="word-wrap: break-word; text-decoration: line-through;"><a  href="#" class="text-dark"><?php echo $my_todo->title; ?></a></h5>
                                                    <?php if (!empty($my_todo->assigned) && $my_todo->assigned != 0) {
                                                        $a_userinfo = $this->db->where('user_id', $my_todo->assigned)->get('tbl_account_details')->row();
                                                        ?>
                                                    <p class="text-muted mb-0"><?= lang('assign_by') ?> <a class="text-danger" href="<?= base_url() ?>admin/user/user_details/<?= $my_todo->assigned ?>"> <?= $a_userinfo->fullname ?></a></p>
                                                    <?php } ?>
                                                </td>
                                                
                                                <td style="width: 10%;">
                                                    <strong data-bs-toggle="tooltip" data-bs-placement="top"  title="<?= date("l", strtotime($due_date)) ?>"><?= display_datetime($due_date) ?>
                                                    <br>
                                                        <span class="block"><?php if($totaldaysleft){ echo "- ".$totaldaysleft; } ?></span>

                                                    </strong>
                                                </td>
                                                <td style="width: 5%;">
                                                    <?= btn_edit_modal('admin/dashboard/new_todo/' . $my_todo->todo_id) ?>
                                                    <?= btn_delete('admin/dashboard/delete_todo/' . $my_todo->todo_id) ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php }else{ ?> 
                                            <tr><td colspan="3" class="text-center"><?=lang('no_data');?></td></tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent border-top">
                    <div class="text-center">
                        <a class="btn btn-primary waves-effect waves-light" target="_blank" href="<?= base_url() ?>admin/dashboard/all_todo"><?= lang('view_all') ?></a>
                        
                        <a class="btn btn-primary waves-effect waves-light" href="<?= base_url() ?>admin/dashboard/new_todo" data-bs-toggle="modal" data-bs-target="#myModal"><?= lang('add_new') ?></a>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($v_order->name == 'finance_overview' && $v_order->status == 1  &&  can_action('53', 'view')) {
            $finance_overview_order = true;         ?>
        <div class="col-sm-6 item-gg">
            <div class="card task-box" id="dash-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
            <div class="card-body">
                <div class="dropdown float-end">
                    <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="#" class="dropdown-item deletedash" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                    </div>
                </div> <!-- end dropdown -->

                <div class="pull-right mt-0" style="margin-top: -8px; width: 30%;">
                    <form class="" role="form" id="form" action="<?php echo base_url(); ?>admin/dashboard/index/finance_overview" method="post">
                        <div class="input-group position-relative" id="finance_overviewDate">
                            <input type="text" class="form-control years" data-provide="datepicker" data-date-container="#finance_overviewDate" data-date-format="yyyy" data-date-min-view-mode="2" data-date-autoclose="true" placeholder="Search" name="finance_overview" value="<?php
                            if (!empty($finance_year)) { echo $finance_year; } ?>">
                            <button type="submit" title="Search" class="btn btn-primary mt-sm-10" id="inputGroupFileAddon03"><span class="bx bx-search-alt"></span></button>
                        </div>                                
                    </form>
                </div>
                <h4 class="card-title mb-2"><?= lang('finance') . ' ' . lang('overview') ?></h4>

                <table class="table_overview" style="position:absolute;top:40px;font-size:smaller;color:#545454">
                    <tbody>
                    <tr>
                        <td class="legendColorBox">
                            <div style="border:1px solid #ccc;padding:1px">
                                <div style="width:4px;height:0;border:5px solid #4e96cdc7;overflow:hidden"></div>
                            </div>
                        </td>
                        <td class="legendLabel"><?=lang('expense'); ?></td>
                    </tr>
                    <tr>
                        <td class="legendColorBox">
                            <div style="border:1px solid #ccc;padding:1px">
                                <div style="width:4px;height:0;border:5px solid #3d9e78;overflow:hidden"></div>
                            </div>
                        </td>
                        <td class="legendLabel"><?=lang('income'); ?></td>
                    </tr>
                    </tbody>
                </table>
                <!--End select input year -->
                <div class="text-center">
                    <!--Sales Chart Canvas -->
                    <canvas id="finance_overview"></canvas>
                </div>
            
                <div class="row mt-4">
                    <div class="col-4">
                        <div class="social-source text-center mt-3">
                            <div class="avatar-xs mx-auto mb-3">
                                <span class="avatar-title rounded-circle bg-primary font-size-16">
                                    <i class="fas fa-money-check-alt text-white"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15"><?= lang('total_annual') . ' ' . lang('income') ?></h5>
                            <p class="text-muted mb-0"><?= display_money($total_annual['total_income'], $curency->symbol); ?></p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="social-source text-center mt-3">
                            <div class="avatar-xs mx-auto mb-3">
                                <span class="avatar-title rounded-circle bg-info font-size-16">
                                    <i class="far fa-money-bill-alt text-white"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15"><?= lang('total_annual') . ' ' . lang('expense') ?></h5>
                            <p class="text-muted mb-0"><?= display_money($total_annual['total_expense'], $curency->symbol); ?></p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="social-source text-center mt-3">
                            <div class="avatar-xs mx-auto mb-3">
                                <span class="avatar-title rounded-circle bg-pink font-size-16">
                                    <i class="fas fa-money-bill-alt text-white"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15"><?= lang('total_annual') . ' ' . lang('profit') ?></h5>
                            <p class="text-muted mb-0"><?= display_money($total_annual['total_profit'], $curency->symbol); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($v_order->name == 'recently_paid_invoices' && $v_order->status == 1  &&  can_action('50', 'view')) { ?>
        <div class="col-sm-6 item-gg">
            <div class="card task-box" id="dash-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item deletedash" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                        </div>
                    </div> <!-- end dropdown -->
                    <h4 class="card-title mb-2"><?= lang('recently_paid_invoices') ?></h4>

                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap">
                            <tbody>
                            <?php
                            // $recently_paid = $this->db
                            //     ->order_by('created_date', 'desc')
                            //     ->get('tbl_payments', 5)
                            //     ->result();
                                 $recently_paid = result_by_company('tbl_payments', array(), 'created_date', false);

                            if (!empty($recently_paid)) {
                                foreach ($recently_paid as $key => $v_paid) {

                                    $invoices_info = $this->db->where(array('invoices_id' => $v_paid->invoices_id))->get('tbl_invoices')->row();

                                    $payment_method = $this->db->where(array('payment_methods_id' => $v_paid->payment_method))->get('tbl_payment_methods')->row();
                                    $currency = $this->admin_model->client_currency_sambol($invoices_info->client_id);

                                    if ($v_paid->payment_method == '1') {
                                        $label = 'success';
                                    } elseif ($v_paid->payment_method == '2') {
                                        $label = 'danger';
                                    } else {
                                        $label = 'dark';
                                    }
                                    ?>
                                <tr>
                                    <td>
                                        <h5 class="font-size-14 m-0"><a href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_paid->invoices_id ?>" class="text-dark"><?= !empty($invoices_info->reference_no) ? $invoices_info->reference_no : '' ?></a></h5>
                                    </td>
                                    <td>
                                        <div>
                                            <a href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_paid->invoices_id ?>" class="badge bg-<?= $label ?> bg-soft text-primary font-size-11"><?= display_money($v_paid->amount, $v_paid->currency) ?> <?= !empty($payment_method->method_name) ? $payment_method->method_name : ''; ?></a>
                                        </div>
                                    </td>
                                </tr>
                                 <?php
                                }
                            }
                            ?>
                                <tr>
                                    <td colspan="2">
                                        <div class="text-center mt-4">
                                            <small><?= lang('total_receipts') ?>: <strong>
                                                <?php

                                                if (!empty($invoce_total)) {
                                                    if (!empty($invoce_total['paid'])) {
                                                        foreach ($invoce_total['paid'] as $symbol => $v_total) {
                                                            $total_paid [] = display_money($v_total, $symbol);
                                                        }
                                                        echo implode(", ", $total_paid);
                                                    } else {
                                                        echo '0.00';
                                                    }
                                                } else {
                                                    echo '0.00';
                                                }
                                                ?>
                                            </strong></small>
                                        </div>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    
        <?php if ($v_order->name == 'recent_activities' && $v_order->status == 1) { ?>
        <div class="col-md-6 item-gg">
            <div class="card task-box" id="dash-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item deletedash" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                        </div>
                    </div> <!-- end dropdown -->
                    <h4 class="card-title mb-2"><?= lang('recent_activities') ?></h4>
                    <ul class="verti-timeline list-unstyled">
                        <?php
                        if ($this->session->userdata('user_type') == '1') {
                            $activities = $this->db
                                ->order_by('activity_date', 'desc')
                                ->get('tbl_activities', 5)
                                ->result();
                        } else {
                            $activities = $this->db
                                ->where('user', $this->session->userdata('user_id'))
                                ->order_by('activity_date', 'desc')
                                ->get('tbl_activities', 4)
                                ->result();
                        }
                        if (!empty($activities)) {
                            foreach ($activities as $v_activities) {
                                $profile_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_account_details')->row();
                                ?>
                        <li class="event-list">
                            <div class="event-timeline-dot">
                                <i class="bx bx-right-arrow-circle font-size-18"></i>
                                <?php if (!empty($profile_info)) { ?>
                                <img src="<?= base_url() . $profile_info->avatar ?>" class="rounded-circle avatar-xs">
                                <?php } ?>
                            </div>
                            
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3 ms-4">
                                    <strong><?= $profile_info->fullname ?></strong>
                                    <h5 class="font-size-14"><?php echo time_ago($v_activities->activity_date);?> <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                </div>
                                <div class="flex-grow-1">
                                    <div>
                                        <?= lang($v_activities->activity) ?> <br>
                                        <strong> <?= $v_activities->value1 . ' ' . $v_activities->value2 ?></strong>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php } }else{ ?> 
                            <div class="text-center">No activities available.</div>
                        <?php } ?>
                    </ul>
                    <?php if (!empty($activities)) { ?>
                    <div class="text-center mt-4"> <a href="<?= base_url('admin/settings/activities') ?>" class="btn btn-primary waves-effect waves-light btn-sm"><?=lang('View_More');?> <i class="mdi mdi-arrow-right ms-1"></i> </a></div>
                <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($v_order->name == 'announcements' && $v_order->status == 1 &&   can_action('100', 'view')) { ?>
        <?php $all_announcements = result_by_company('tbl_announcements', array(), 'created_date', false);
        if (!empty($all_announcements)): ?>
        <div class="col-md-4 item-gg">
            <div class="card task-box" id="dash-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item deletedash" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                        </div>
                    </div> <!-- end dropdown -->
                    <h4 class="card-title mb-2"><?= lang('announcements') ?></h4>
                    <div data-simplebar style="max-height: 300px;">  

                        <ul class="verti-timeline list-unstyled">
                        <?php
                        foreach ($all_announcements as $v_announcements): ?>
                            <li class="event-list">
                                <div class="event-timeline-dot">
                                    <i class="bx bx-right-arrow-circle font-size-18"></i>
                                </div>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <h5 class="font-size-14"><?php echo date('d', strtotime($v_announcements->created_date)) ?> <?php echo date('M', strtotime($v_announcements->created_date)) ?> <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div>
                                            <h5 class="notice-calendar-heading-title">
                                                    <a href="<?php echo base_url() ?>admin/announcements/announcements_details/<?php echo $v_announcements->announcements_id; ?>" title="View" data-bs-toggle="modal" data-bs-target="#myModal_lg"><?php echo $v_announcements->title ?></a>
                                            </h5>
                                            <div class="notice-calendar-date">
                                                <?php
                                                echo strip_tags(mb_substr($v_announcements->description, 0, 200)) . '...';
                                                ?>
                                            </div>
                                            <span style="font-size: 10px;" class="">
                                                <strong>
                                                    <a href="<?php echo base_url() ?>admin/announcements/announcements_details/<?php echo $v_announcements->announcements_id; ?>" title="View" data-bs-toggle="modal" data-bs-target="#myModal_lg"><?= lang('view_details') ?></a>
                                                </strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php
                            endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php } ?>
        <?php if ($v_order->name == 'overdue_report' && $v_order->status == 1  &&  can_action('53', 'view')) { ?>
       
        <div class="col-md-12 item-gg">
            <div class="card task-box" id="dash-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item deletedash" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                        </div>
                    </div> <!-- end dropdown -->
                    <ul class="nav nav-tabs bg-light rounded" role="tablist">
                        <li class="nav-item font-size-10">
                            <a href="#projects" class="nav-link active" data-bs-toggle="tab" role="tab">
                             <?= lang('overdue') . ' ' . lang('project') ?>
                            <span class="d-block">(<?= $project_overdue ?>)</span>
                            </a>
                        </li>
                        <li class="nav-item font-size-10">
                            <a href="#tasks_list" class="nav-link" data-bs-toggle="tab" role="tab">
                                <?= lang('overdue') . ' ' . lang('tasks') ?>
                                <span class="d-block">(<?= $task_overdue ?>)</span>
                            </a>
                        </li>
                        <li class="nav-item font-size-10">
                            <a href="#invoice" class="nav-link" data-bs-toggle="tab" role="tab">
                                <?= lang('overdue') . ' ' . lang('invoice') ?>
                                <span class="d-block">(<?= $invoice_overdue ?>)</span>
                            </a>
                        </li>
                        <li class="nav-item font-size-10">
                            <a href="#estimate" class="nav-link" data-bs-toggle="tab" role="tab">
                                <?= lang('expired') . ' ' . lang('estimate') ?>
                                <span class="d-block">(<?= $estimate_overdue ?>)</span>
                            </a>
                        </li>
                        <li class="nav-item font-size-10">
                            <a href="#bugs" class="nav-link" data-bs-toggle="tab" role="tab">
                                <?= lang('unconfirmed') . ' ' . lang('bugs') ?>
                                <span class="d-block">(<?= $bug_unconfirmed ?>)</span>
                            </a>
                        </li>
                        <li class="nav-item font-size-10">
                            <a href="#recent_opportunities" class="nav-link" data-bs-toggle="tab" role="tab">
                                <?= lang('overdue') . ' ' . lang('opportunities') ?>
                                <span class="d-block">(<?= $opportunity_overdue ?>)</span>
                            </a>
                        </li>
                    </ul>
                    <div data-simplebar style="max-height: 350px;">  
                        <div class="tab-content p-3 text-muted dashboard">
                            <div class="tab-pane active" id="projects" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table dt-responsive nowrap w-100 m-b-none text-sm" id="projects_datatable">
                                        <thead>
                                        <tr>
                                            <th><?= lang('project_name') ?></th>
                                            <th><?= lang('client') ?></th>
                                            <th><?= lang('end_date') ?></th>
                                            <th><?= lang('status') ?></th>
                                            <th class="col-options no-sort"><?= lang('action') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($all_project)) {
                                            foreach ($all_project as $v_project):
                                                if (time() > strtotime($v_project->end_date) AND $v_project->progress < 100) {
                                                    ?>
                                                    <tr>
                                                        <?php
                                                        $client_info = $this->db->where('client_id', $v_project->client_id)->get('tbl_client')->row();
                                                        if (!empty($client_info)) {
                                                            $name = $client_info->name;
                                                        } else {
                                                            $name = '-';
                                                        }
                                                        ?>
                                                        <td>
                                                            <a class="text-info"
                                                               href="<?= base_url() ?>admin/projects/project_details/<?= $v_project->project_id ?>"><?= $v_project->project_name ?></a>
                                                            <?php if (time() > strtotime($v_project->end_date) AND $v_project->progress < 100) { ?>
                                                                <span
                                                                        class="badge rounded-pill badge-soft-danger pull-right"><?= lang('overdue') ?></span>
                                                            <?php } ?>

                                                            <div
                                                                    class="progress progress-xs progress-striped active">
                                                                <div
                                                                        class="progress-bar progress-bar-<?php echo ($v_project->progress >= 100) ? 'success' : 'primary'; ?>"
                                                                        data-bs-toggle="tooltip"
                                                                        data-original-title="<?= $v_project->progress ?>%"
                                                                        style="width: <?= $v_project->progress; ?>%"></div>
                                                            </div>

                                                        </td>
                                                        <td><?= $name ?></td>

                                                        <td><?php if (!empty($v_project->end_date) && $v_project->end_date!='0000-00-00 00:00:00'){
                                                                echo display_datetime($v_project->end_date);    
                                                        }else{ echo '-'; }  ?></td>
                                                        <td><?php
                                                            if (!empty($v_project->project_status)) {
                                                                if ($v_project->project_status == 'completed') {
                                                                    $status = "<span class='badge rounded-pill badge-soft-success'>" . lang($v_project->project_status) . "</span>";
                                                                } elseif ($v_project->project_status == 'in_progress') {
                                                                    $status = "<span class='badge rounded-pill badge-soft-primary'>" . lang($v_project->project_status) . "</span>";
                                                                } elseif ($v_project->project_status == 'cancel') {
                                                                    $status = "<span class='badge rounded-pill badge-soft-danger'>" . lang($v_project->project_status) . "</span>";
                                                                } else {
                                                                    $status = "<span class='badge rounded-pill badge-soft-warning'>" . lang($v_project->project_status) . "</span>";
                                                                }
                                                                echo $status;
                                                            }
                                                            ?>      </td>
                                                        <td>
                                                            <?= btn_view(base_url() . 'admin/projects/project_details/' . $v_project->project_id) ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            endforeach;
                                        }
                                        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tasks_list" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table dt-responsive nowrap w-100 m-b-none text-sm" id="tasks_datatable">
                                        <thead>
                                        <tr>
                                            <th><?= lang('task_name') ?></th>
                                            <th><?= lang('end_date') ?></th>
                                            <th><?= lang('progress') ?></th>
                                            <th class="col-options no-sort col-md-1"><?= lang('action') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($task_all_info)):foreach ($task_all_info as $v_task):
                                            $due_date = $v_task->due_date;
                                            $due_time = strtotime($due_date);
                                            $current_time = time();
                                            if ($current_time > $due_time && $v_task->task_progress < 100) {
                                                ?>
                                                <tr>
                                                    <td><a class="text-info" style="<?php
                                                        if ($v_task->task_progress >= 100) {
                                                            echo 'text-decoration: line-through;';
                                                        }
                                                        ?>"
                                                           href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
                                                    </td>
                                                    <td>
                                                        <?= display_datetime($due_date) ?>
                                                        <?php if ($current_time > $due_time && $v_task->task_progress < 100) { ?>
                                                            <span
                                                                    class="badge rounded-pill badge-soft-danger"><?= lang('overdue') ?></span>
                                                        <?php } ?></td>
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
                                                                 data-size="50"
                                                                 data-animate="2000">
                                                    <span class="small text-muted"><?= $v_task->task_progress ?>
                                                        %</span>
                                                            </div>
                                                        </div>

                                                    </td>

                                                    <td><?= btn_view('admin/tasks/view_task_details/' . $v_task->task_id) ?></td>
                                                </tr>
                                                <?php
                                            }
                                        endforeach; ?>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="invoice" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table dt-responsive nowrap w-100 m-b-none text-sm" id="invoice_datatable">
                                        <thead>
                                        <tr>
                                            <th><?= lang('invoice') ?></th>
                                            <th class="col-date"><?= lang('due_date') ?></th>
                                            <th><?= lang('client_name') ?></th>
                                            <th class="col-currency"><?= lang('due_amount') ?></th>
                                            <th><?= lang('status') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        if (!empty($all_invoices_info)) {
                                            foreach ($all_invoices_info as $v_invoices) {
                                                $payment_status = $this->invoice_model->get_payment_status($v_invoices->invoices_id);
                                                if (strtotime($v_invoices->due_date) < time() AND $payment_status != lang('fully_paid')) {
                                                    if ($payment_status == lang('fully_paid')) {
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
                                                               href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_invoices->invoices_id ?>"><?= $v_invoices->reference_no ?>

                                                            </a>
                                                        </td>
                                                        <td><?= display_datetime($v_invoices->due_date) ?>
                                                            <span
                                                                    class="badge rounded-pill badge-soft-danger "><?= lang('overdue') ?></span>
                                                        </td>
                                                        <?php
                                                        $client_info = $this->invoice_model->check_by(array('client_id' => $v_invoices->client_id), 'tbl_client');
                                                        if (!empty($client_info)) {
                                                            $client_name = $client_info->name;
                                                        } else {
                                                            $client_name = '-';
                                                        }
                                                        ?>
                                                        <td><?= $client_name; ?></td>
                                                        <?php $currency = $this->invoice_model->client_currency_sambol($v_invoices->client_id); ?>
                                                        <td><?= display_money($this->invoice_model->calculate_to('invoice_due', $v_invoices->invoices_id), $curency->symbol); ?></td>
                                                        <td><span
                                                                    class="badge rounded-pill badge-soft-<?= $label ?>"><?= $invoice_status ?></span>
                                                            <?php if ($v_invoices->recurring == 'Yes') { ?>
                                                                <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                                      title="<?= lang('recurring') ?>"
                                                                      class="badge rounded-pill badge-soft-primary"><i
                                                                            class="fa fa-retweet"></i></span>
                                                            <?php } ?>

                                                        </td>

                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="estimate" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table dt-responsive nowrap w-100 m-b-none text-sm" id="estimate_datatable">
                                        <thead>
                                        <tr>
                                            <th><?= lang('estimate') ?></th>
                                            <th><?= lang('due_date') ?></th>
                                            <th><?= lang('client_name') ?></th>
                                            <th><?= lang('amount') ?></th>
                                            <th><?= lang('status') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($all_estimates_info)) {
                                            foreach ($all_estimates_info as $v_estimates) {
                                                if (strtotime($v_estimates->due_date) < time() AND $v_estimates->status == 'Pending') {
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
                                                                        class="badge rounded-pill badge-soft-danger "><?= lang('expired') ?></span>
                                                            <?php }
                                                            ?>
                                                        </td>
                                                        <?php
                                                        $client_info = $this->estimates_model->check_by(array('client_id' => $v_estimates->client_id), 'tbl_client');
                                                        ?>
                                                        <td><?= $client_info->name; ?></td>
                                                        <?php $currency = $this->estimates_model->client_currency_sambol($v_estimates->client_id); ?>
                                                        <td><?= display_money($this->estimates_model->estimate_calculation('estimate_amount', $v_estimates->estimates_id), $curency->symbol); ?></td>
                                                        <td><span
                                                                    class="badge rounded-pill badge-soft-<?= $label ?>"><?= lang(strtolower($v_estimates->status)) ?></span>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="bugs" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table dt-responsive nowrap w-100 m-b-none text-sm" id="bugs_datatable">
                                        <thead>
                                        <tr>
                                            <th><?= lang('bug_title') ?></th>
                                            <th><?= lang('status') ?></th>
                                            <th><?= lang('priority') ?></th>
                                            <?php if ($this->session->userdata('user_type') == '1') { ?>
                                                <th><?= lang('reporter') ?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($all_bugs_info)):foreach ($all_bugs_info as $key => $v_bugs):
                                            if ($v_bugs->bug_status == 'unconfirmed') {
                                                $reporter = $this->db->where('user_id', $v_bugs->reporter)->get('tbl_users')->row();

                                                if ($reporter->role_id == '1') {
                                                    $badge = 'danger';
                                                } elseif ($reporter->role_id == '2') {
                                                    $badge = 'info';
                                                } else {
                                                    $badge = 'primary';
                                                }
                                                ?>
                                                <tr>
                                                    <td><a class="text-info" style="<?php
                                                        if ($v_bugs->bug_status == 'resolve') {
                                                            echo 'text-decoration: line-through;';
                                                        }
                                                        ?>"
                                                           href="<?= base_url() ?>admin/bugs/view_bug_details/<?= $v_bugs->bug_id ?>"><?php echo $v_bugs->bug_title; ?></a>
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
                                                                class="badge rounded-pill badge-soft-<?= $label ?>"><?= lang("$v_bugs->bug_status") ?></span>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($v_bugs->priority == 'High') {
                                                            $plabel = 'danger';
                                                        } elseif ($v_bugs->priority == 'Medium') {
                                                            $plabel = 'info';
                                                        } else {
                                                            $plabel = 'primary';
                                                        }
                                                        ?>
                                                        <span
                                                                class="badge btn-<?= $plabel ?>"><?= ucfirst($v_bugs->priority) ?></span>
                                                    </td>
                                                    <?php if ($this->session->userdata('user_type') == '1') { ?>
                                                        <td>
                                                <span
                                                        class="badge btn-<?= $badge ?> "><?= $reporter->username ?></span>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                                <?php
                                            } endforeach; ?>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="recent_opportunities" role="tabpanel">
                                <div class="table-responsive">  
                                    <table class="table dt-responsive nowrap w-100 m-b-none text-sm" id="recent_opportunities_datatable">
                                        <thead>
                                        <tr>
                                            <th><?= lang('opportunity_name') ?></th>
                                            <th><?= lang('state') ?></th>
                                            <th><?= lang('stages') ?></th>
                                            <th><?= lang('expected_revenue') ?></th>
                                            <th><?= lang('next_action') ?></th>
                                            <th><?= lang('next_action_date') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $all_opportunity = $this->admin_model->get_permission('tbl_opportunities');
                                        if (!empty($all_opportunity)) {
                                            foreach ($all_opportunity as $v_opportunity) {
                                                if (time() > strtotime($v_opportunity->close_date) AND $v_opportunity->probability < 100) {
                                                    $opportunities_state_info = $this->db->where('opportunities_state_reason_id', $v_opportunity->opportunities_state_reason_id)->get('tbl_opportunities_state_reason')->row();
                                                    if ($opportunities_state_info->opportunities_state == 'open') {
                                                        $label = 'primary';
                                                    } elseif ($opportunities_state_info->opportunities_state == 'won') {
                                                        $label = 'success';
                                                    } elseif ($opportunities_state_info->opportunities_state == 'suspended') {
                                                        $label = 'info';
                                                    } else {
                                                        $label = 'danger';
                                                    }
                                                    $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <a class="text-info"
                                                               href="<?= base_url() ?>admin/opportunities/opportunity_details/<?= $v_opportunity->opportunities_id ?>"><?= $v_opportunity->opportunity_name ?></a>
                                                            <?php if (time() > strtotime($v_opportunity->close_date) AND $v_opportunity->probability < 100) { ?>
                                                                <span
                                                                        class="badge rounded-pill badge-soft-danger pull-right"><?= lang('overdue') ?></span>
                                                            <?php } ?>
                                                            <div
                                                                    class="progress progress-xs progress-striped active">
                                                                <div
                                                                        class="progress-bar progress-bar-<?php echo ($v_opportunity->probability >= 100) ? 'success' : 'primary'; ?>"
                                                                        data-bs-toggle="tooltip"
                                                                        data-original-title="<?= lang('probability') . ' ' . $v_opportunity->probability ?>%"
                                                                        style="width: <?= $v_opportunity->probability ?>%"></div>
                                                            </div>
                                                        </td>
                                                        <td><?= lang($v_opportunity->stages) ?></td>
                                                        <td><span
                                                                    class="badge rounded-pill badge-soft-<?= $label ?>"><?= lang($opportunities_state_info->opportunities_state) ?></span>
                                                        </td>
                                                        <td><?php
                                                            if (!empty($v_opportunity->expected_revenue)) {
                                                                echo display_money($v_opportunity->expected_revenue, $currency->symbol);
                                                            }
                                                            ?></td>
                                                        <td><?= $v_opportunity->next_action ?></td>
                                                        <td><?= display_datetime($v_opportunity->next_action_date) ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
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

        <?php if ($v_order->name == 'payments_report' && $v_order->status == 1  &&  can_action('50', 'view')) {
        $payments_report_order = true; ?>
        <div class="col-md-8 item-gg">
            <div class="card task-box" id="dash-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item deletedash" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                        </div>
                    </div> <!-- end dropdown -->
                    <div class="pull-right mt-0" style="margin-top: -8px; width: 30%;">
                        <form class="" role="form" id="form" action="<?php echo base_url(); ?>admin/dashboard/index/payments" method="post">
                            <div class="input-group position-relative" id="paymentsDate">
                                <input type="text" class="form-control years" data-provide="datepicker" data-date-container="#paymentsDate" data-date-format="yyyy" data-date-min-view-mode="2" data-date-autoclose="true" placeholder="<?= lang('select') . ' ' . lang('year') ?>" name="yearly" value="<?php if (!empty($yearly)) { echo $yearly; } ?>">
                                <button type="submit" title="Search" class="btn btn-primary mt-sm-10"><span class="bx bx-search-alt"></span></button>
                            </div>                                
                        </form>
                    </div>
                    <h4 class="card-title mb-2"><?= lang('payments_report') ?></h4>
            
                    <canvas id="yearly_report" class="col-sm-12"></canvas>

                </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($v_order->name == 'income_report' && $v_order->status == 1  &&  can_action('44', 'view')) {
        $income_report_order = true; ?>
        <div class="col-md-6 item-gg">
            <div class="card task-box" id="dash-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item deletedash" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                        </div>
                    </div> <!-- end dropdown -->
                    <div class="pull-right mt-0" style="margin-top: -8px; width: 30%;">
                        <form class="" role="form" id="form" action="<?php echo base_url(); ?>admin/dashboard/index/Income" method="post">
                            <div class="input-group position-relative" id="IncomeDate">
                                <input type="text" class="form-control years" data-provide="datepicker" data-date-container="#IncomeDate" data-date-format="yyyy" data-date-min-view-mode="2" data-date-autoclose="true" placeholder="<?= lang('select') . ' ' . lang('year') ?>" name="Income" value="<?php if (!empty($Income)) { echo $Income; } ?>">
                                <button type="submit" title="Search" class="btn btn-primary mt-sm-10"><span class="bx bx-search-alt"></span></button>
                            </div>                                
                        </form>
                    </div>
                    <h4 class="card-title mb-2"><?= lang('income_report') ?></h4>
            
                    <canvas id="income" class="col-sm-12"></canvas>

                </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($v_order->name == 'expense_report' && $v_order->status == 1  &&  can_action('45', 'view')) {
        $expense_report_order = true;
        ?>
        <div class="col-md-6 item-gg">
            <div class="card task-box" id="dash-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item deletedash" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                        </div>
                    </div> <!-- end dropdown -->
                    <div class="pull-right mt-0" style="margin-top: -8px; width: 30%;">
                        <form class="" role="form" id="form" action="<?php echo base_url(); ?>admin/dashboard" method="post">
                            <div class="input-group position-relative" id="expenseDate">
                                <input type="text" class="form-control years" data-provide="datepicker" data-date-container="#expenseDate" data-date-format="yyyy" data-date-min-view-mode="2" data-date-autoclose="true" placeholder="<?= lang('select') . ' ' . lang('year') ?>" name="year" value="<?php if (!empty($year)) { echo $year; } ?>">
                                <button type="submit" title="Search" class="btn btn-primary mt-sm-10"><span class="bx bx-search-alt"></span></button>
                            </div>                                
                        </form>
                    </div>

                    <h4 class="card-title mb-2"><?= lang('expense_report') ?></h4>
                   
                    <canvas id="buyers" class="col-sm-12"></canvas>

                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php if ($v_order->name == 'income_expense' && $v_order->status == 1  &&  can_action('46', 'view')) {
        $income_expense_order = true; ?>
        <div class="col-md-6 item-gg">
            <div class="card task-box" id="dash-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item deletedash" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                        </div>
                    </div> <!-- end dropdown -->
                    <div class="pull-right mt-0" style="margin-top: -8px; width: 30%;">
                        <form class="" role="form" id="form" action="<?php echo base_url(); ?>admin/dashboard/index/month" method="post">
                            <div class="input-group position-relative" id="incomeExpenseDate">
                                <input type="text" class="form-control monthyear" data-provide="datepicker" data-date-container="#incomeExpenseDate" data-date-format="MM yyyy" data-date-min-view-mode="1" data-date-autoclose="true" placeholder="<?= lang('select') . ' ' . lang('month') ?>" name="month" value="<?php if (!empty($month)) { echo $month; } ?>">
                                <button type="submit" title="Search" class="btn btn-primary mt-sm-10"><span class="bx bx-search-alt"></span></button>
                            </div>                                
                        </form>
                    </div>

                    <h4 class="card-title mb-2"><?= lang('income_expense') ?></h4>
                    
                    <canvas id="sales-chart" height="260"></canvas>

                </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($v_order->name == 'goal_report' && $v_order->status == 1  &&  can_action('47', 'view')) {
        $goal_report_order = true;
        ?>
        <div class="col-md-4 item-gg">
            <div class="card task-box" id="dash-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item deletedash" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                        </div>
                    </div> <!-- end dropdown -->
                    <div class="pull-right mt-0" style="margin-top: -8px; width: 30%;">
                        <form class="" role="form" id="form" action="<?php echo base_url(); ?>admin/dashboard/index/goal_month" method="post">
                            <div class="input-group position-relative" id="goal_reportDate">
                                <input type="text" class="form-control monthyear" data-provide="datepicker" data-date-container="#goal_reportDate" data-date-format="MM yyyy" data-date-min-view-mode="1" data-date-autoclose="true" placeholder="<?= lang('select') . ' ' . lang('month') ?>" name="goal_month" value="<?php if (!empty($goal_month)) { echo $goal_month; } ?>">
                                <button type="submit" title="Search" class="btn btn-primary mt-sm-10"><span class="bx bx-search-alt"></span></button>
                            </div>                                
                        </form>
                    </div>

                    <h4 class="card-title mb-2"><?= lang('goal') . ' ' . lang('report') ?></h4>
                    <canvas id="goal_report" height="300"></canvas>

                </div>
            </div>
        </div>        
        <?php } ?>
        <?php } ?>
        <!--Added By jaraware infosfot 07-Dece-21-->
        <?php if(count($proposal_history)>0){ ?>
        <div class="col-md-8 item-gg">
            <div class="card task-box" id="dash-<?= $v_order->id ?>" data-id="<?= $v_order->id ?>">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical m-0 text-muted h5"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item deletedash" title="<?=lang('inactive')?>" data-id="<?= $v_order->id ?>"><i class="fas fa-times-circle m-0 text-muted h5"></i> <?=lang('inactive')?></a>
                        </div>
                    </div> <!-- end dropdown -->

                    <h4 class="card-title mb-2">Proposal View History</h4>
                    <div data-simplebar style="max-height: 350px;">  
                        <div class="table-responsive">
                            <table class="table table-nowrap align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>URL</th>
                                        <th>Total Hit</th>
                                        
                                        <th>First Hit Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $i=1;
                                    foreach ($proposal_history as $row):
                                ?>
                                    <tr>
                                        <td><?php echo $i++;?>
                                        <td>
                                            <h5 class="text-truncate font-size-14 m-0"><a target="_blank" class="text-dark" href="<?=  $row->proposal_url; ?>" title="<?=  $row->proposal_url; ?>"><?= base64_encode($row->proposal_id);?></a></h5>
                                        </td>
                                        <td>
                                            <h5 class="text-truncate font-size-14 m-0"><?=  $row->total_view; ?></h5>
                                        </td>
                                        
                                        <td>
                                            <h5 class="text-truncate font-size-14 m-0"><?=  date('d-m-Y H-i:s A',strtotime($row->proposal_open_time)); ?></h5>
                                        </td>
                                    </tr>
                                <?php
                                        
                                endforeach; ?>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 item-gg">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-2">Proposal History Chart</h4>
                        
                        <div id="bar_chart" class="apex-charts" dir="ltr"></div>
                    </div>
                </div><!--end card-->
            </div>
        <?php } ?>
        
    </div>
</div>
<?php } ?>

<!-- dragula plugins -->
<script src="<?=base_url();?>skote_assets/libs/dragula/dragula.min.js"></script>

 
<script src="<?php echo base_url('skote_assets/js/pages/dashboard.init.js'); ?>"></script>

<!-- Chart JS -->
<script src="<?=base_url();?>skote_assets/libs/chart.js/Chart.bundle.min.js"></script>

<?php
if (!empty($goal_report)) {
    foreach ($goal_report as $type_id => $v_goal_report) {
        $total_target = 0;
        $total_achievement = 0;
        $goal_type = $this->db->where('goal_type_id', $type_id)->get('tbl_goal_type')->row()->type_name;

        foreach ($v_goal_report['target'] as $v_target) {
            $total_target += $v_target;
        }
        foreach ($v_goal_report['achievement'] as $v_achievement) {
            $total_achievement += $v_achievement['achievement'];
        }


        ?>
    <?php }
}
?>
<?php if (!empty($finance_overview_order)) { $result = ''; ?>
<script type="text/javascript">    
// Line chart
// -----------------------------------
var lineData = {
    labels: [
        <?php
        // yearle result name = month name
        foreach ($finance_overview_by_year as $m_name => $v_finance_overview):
        $overview_month = date('F', strtotime($year . '-' . $m_name)); // get full name of month by date query
        ?>
        "<?php echo $overview_month; ?>", // echo the whole month of the year
        <?php endforeach; ?>
    ],
    datasets: [
        {
            label: "<?=lang('Expense');?>",
            fill: true,
            lineTension: 0.5,
            backgroundColor: "rgba(85, 110, 230, 0.2)",
            borderColor: "#556ee6",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "#556ee6",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "#556ee6",
            pointHoverBorderColor: "#fff",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: [<?php
                // get monthly result report
                foreach ($finance_overview_by_year as $v_finance_overview):
                ?>
                "<?php
                    if (!empty($v_finance_overview)) { // if the report result is exist
                        $f_total_expense = 0;
                        foreach ($v_finance_overview as $f_expense) {
                            if ($f_expense->type == 'Expense') {
                                $f_total_expense += $f_expense->amount;
                            }

                        }
                        echo $f_total_expense; // view the total report in a  month
                    }
                    ?>",
                <?php
                endforeach;
                ?>]
        },
        {
            label: "<?=lang('Income');?>",
            fill: true,
            lineTension: 0.5,
            backgroundColor: "rgba(85, 110, 230, 0.2)",
            borderColor: "#556ee6",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "#556ee6",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "#556ee6",
            pointHoverBorderColor: "#fff",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: [<?php
                // get monthly result report
                foreach ($finance_overview_by_year as $v_finance_overview):
                ?>
                "<?php
                    if (!empty($v_finance_overview)) { // if the report result is exist
                        $f_total_income = 0;
                        foreach ($v_finance_overview as $f_income) {
                            if ($f_income->type == 'Income') {
                                $f_total_income += $f_income->amount;
                            }

                        }
                        echo $f_total_income; // view the total report in a  month
                    }
                    ?>",
                <?php
                endforeach;
                ?>]
        }
    ]
};
<?php if(!empty($result)){?>
var lineOptions = {
    scaleShowGridLines: true,
    scaleGridLineColor: 'rgba(0,0,0,.05)',
    scaleGridLineWidth: 2,
    bezierCurve: true,
    bezierCurveTension: 0.4,
    pointDot: true,
    pointDotRadius: 3,
    pointDotStrokeWidth: 2,
    pointHitDetectionRadius: 20,
    datasetStroke: true,
    datasetStrokeWidth: 2,
    datasetFill: true,
    responsive: true
};
var linectx = document.getElementById("finance_overview").getContext("2d");
new Chart(linectx, {type: 'line', data: lineData, options: lineOptions});
<?php } ?>
</script>
<?php } ?>
<?php if (!empty($goal_report) && !empty($goal_report_order)) { ?>
<script type="text/javascript">

</script> 
<?php } ?>
<?php
$gcal_api_key = config_item('gcal_api_key');
$gcal_id = config_item('gcal_id');
?>

<?php if ($this->session->userdata('user_type') == '1') { ?>
<?php if (!empty($all_income) && !empty($income_report_order)) { ?>
<script>
    
    // line chart data
    var buyerData = {

        labels: [
            <?php
            // yearle result name = month name
            foreach ($all_income as $name => $v_income):
            $month_name = date('F', strtotime($year . '-' . $name)); // get full name of month by date query
            ?>
            "<?php echo $month_name; ?>", // echo the whole month of the year
            <?php endforeach; ?>
        ],
        datasets: [
            {
                label: "<?=lang('Income');?>",
                fill: true,
                lineTension: 0.5,
                backgroundColor: "rgba(85, 110, 230, 0.2)",
                borderColor: "#556ee6",
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: "#556ee6",
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "#556ee6",
                pointHoverBorderColor: "#fff",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [
                    <?php
                    // get monthly result report
                    foreach ($all_income as $v_income):
                    ?>
                    "<?php
                        if (!empty($v_income)) { // if the report result is exist
                            $total_income = 0;
                            foreach ($v_income as $income) {
                                $total_income += $income->amount;
                            }

                            echo $total_income; // view the total report in a  month
                        }
                        ?>",
                    <?php
                    endforeach;
                    ?>
                ]
            }
        ]
    }
    var buyerDatalineOpts = {
        scales: {
            yAxes: [{
                ticks: {
                    max: 4000,
                    min: 0,
                    stepSize: 500
                }
            }]
        }
    };
    // get line chart canvas
    var buyers = document.getElementById('income').getContext('2d');
    // draw line chart
    new Chart(buyers, {type: 'line', data: buyerData, options: buyerDatalineOpts});
</script>
<?php } ?>
<?php if (!empty($all_expense) && !empty($expense_report_order)) { ?>
<script>
   // line chart data
    var buyerData = {

        labels: [
            <?php
            // yearle result name = month name
            foreach ($all_expense as $name => $v_expense):
            $month_name = date('F', strtotime($year . '-' . $name)); // get full name of month by date query
            ?>
            "<?php echo $month_name; ?>", // echo the whole month of the year
            <?php endforeach; ?>
        ],
        datasets: [
            {
                label: "<?=lang('Expense');?>",
                fill: true,
                lineTension: 0.5,
                backgroundColor: "rgba(85, 110, 230, 0.2)",
                borderColor: "#556ee6",
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: "#556ee6",
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "#556ee6",
                pointHoverBorderColor: "#fff",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [
                    <?php
                    // get monthly result report
                    foreach ($all_expense as $v_expense):
                    ?>
                    "<?php
                        if (!empty($v_expense)) { // if the report result is exist
                            $total_expense = 0;
                            foreach ($v_expense as $exoense) {
                                $total_expense += $exoense->amount;
                            }
                            echo $total_expense; // view the total report in a  month
                        }
                        ?>",
                    <?php
                    endforeach;
                    ?>
                ]
            }
        ]
    }

    var buyerDatalineOpts = {
        scales: {
            yAxes: [{
                ticks: {
                    max: 1,
                    min: 0,
                    stepSize: 1
                }
            }]
        }
    };


    // get line chart canvas
    var buyers = document.getElementById('buyers').getContext('2d');
    // draw line chart
    new Chart(buyers, {type: 'line', data: buyerData, options: buyerDatalineOpts});
</script>
<?php } ?>
<?php if (!empty($yearly_overview) && !empty($payments_report_order)) { ?>
<script>
    // line chart data
    var buyerData = {

        labels: [
            <?php
            // yearle result name = month name
            for ($i = 1; $i <= 12; $i++) {
            $month_name = date('F', strtotime($year . '-' . $i)); // get full name of month by date query
            ?>
            "<?php echo $month_name; ?>", // echo the whole month of the year
            <?php }; ?>
        ],
        datasets: [
            {
                label: "<?=lang('Payments');?>",
                fill: true,
                lineTension: 0.5,
                backgroundColor: "rgba(85, 110, 230, 0.2)",
                borderColor: "#556ee6",
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: "#556ee6",
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "#556ee6",
                pointHoverBorderColor: "#fff",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [
                    <?php
                    // get monthly result report
                    foreach ($yearly_overview as $v_overview):
                    ?>
                    "<?php
                        echo $v_overview; // view the total report in a  month
                        ?>",
                    <?php
                    endforeach;
                    ?>
                ]
            }
        ]
    }

    var buyerDatalineOpts = {
        scales: {
            yAxes: [{
                ticks: {
                    max: 4000,
                    min: 0,
                    stepSize: 500
                }
            }]
        }
    };

    // get line chart canvas
    var buyers = document.getElementById('yearly_report').getContext('2d');
    // draw line chart
    new Chart(buyers, {type: 'line', data: buyerData, options: buyerDatalineOpts});
</script>
<?php } ?>
<?php if (!empty($income_expense) && !empty($income_expense_order)) { ?>
<script type="text/javascript">
    //donut chart
    var donutChart = {
        labels: [
            "<?= lang('Income') ?>",
            "<?= lang('Expense') ?>"
        ],
        datasets: [
            {
                data: [<?php
                            $total_vincome = 0;
                            if (!empty($income_expense)):foreach ($income_expense as $v_income_expense):
                            if ($v_income_expense->type == 'Income') {

                            $total_vincome += $v_income_expense->amount;
                            ?>

                            <?php
                            }
                            endforeach;
                            endif;
                            echo $total_vincome;
                            ?>, <?php
                            $total_vexpense = 0;
                            if (!empty($income_expense)):foreach ($income_expense as $v_income_expense):
                            if ($v_income_expense->type == 'Expense') {
                            $total_vexpense += $v_income_expense->amount;
                            ?>

                            <?php
                            }
                            endforeach;
                            endif;
                            echo $total_vexpense;
                            ?>],
                backgroundColor: [
                    "#556ee6",
                    "#ebeff2"
                ],
                hoverBackgroundColor: [
                    "#556ee6",
                    "#ebeff2"
                ],
                hoverBorderColor: "#fff"
            }]
    };
     // get line chart canvas
    var sales = document.getElementById('sales-chart').getContext('2d');
    // draw line chart
    new Chart(sales, {type: 'doughnut', data: donutChart});
</script>
<?php } ?>
<?php } ?>
<!-- Ravi code start -->
<!-- apexcharts -->
<script src="<?php echo base_url();?>skote_assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- apexcharts init -->
<script type="text/javascript">
    // Bar chart

var options = {
    chart: {
        height: 350,
        type: 'bar',
        toolbar: {
            show: false,
        }
    },
    plotOptions: {
        bar: {
            horizontal: true,
        }
    },
    dataLabels: {
        enabled: false
    },
    series: [{
        data: [
            <?php
            foreach ($proposal_history as $pr):?>
                "<?php
                        echo $pr->total_view; // view the total report in a  month
                        ?>",
            <?php endforeach;
            ?>
        ]
    }],
    colors: ['#34c38f'],
    grid: {
        borderColor: '#f1f1f1',
    },
    xaxis: {
        categories: [
        <?php
        foreach ($proposal_history as $pr):?>
            "<?php
                $url_path = parse_url($pr->proposal_url, PHP_URL_PATH);

                    echo pathinfo($url_path, PATHINFO_BASENAME);

                    ?>",
        <?php endforeach;
        ?>
        ],
    },
     tooltip: {
    custom: function({ series, seriesIndex, dataPointIndex, w }) {
      return (
        '<div class="arrow_box">' +
        "<span>" +
        w.globals.labels[dataPointIndex] +
        ": " +
        series[seriesIndex][dataPointIndex] +
        "</span>" +
        "</div>"
      );
    }
  }
}

var chart = new ApexCharts(
    document.querySelector("#bar_chart"),
    options
);

chart.render();
</script>
<!-- Ravi code end -->
<style type="text/css">
    .shake-div:hover {
  /* Start the shake animation and make the animation last for 0.5 seconds */
  animation: shake 0.5s;

  /* When the animation is finished, start again */
  animation-iteration-count: 1;
}

@keyframes shake {
  0% { transform: translate(1px, 1px) rotate(0deg); }
  10% { transform: translate(-1px, -2px) rotate(-1deg); }
  20% { transform: translate(-3px, 0px) rotate(1deg); }
  30% { transform: translate(3px, 2px) rotate(0deg); }
  40% { transform: translate(1px, -1px) rotate(1deg); }
  50% { transform: translate(-1px, 2px) rotate(-1deg); }
  60% { transform: translate(-3px, 1px) rotate(0deg); }
  70% { transform: translate(3px, 1px) rotate(-1deg); }
  80% { transform: translate(-1px, -1px) rotate(1deg); }
  90% { transform: translate(1px, 2px) rotate(0deg); }
  100% { transform: translate(1px, -2px) rotate(-1deg); }
}
.task-box .card-body { min-height: 365px; }
</style>
