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
<style type="text/css">
    .dropdown-menu > li > a {
        white-space: normal;
    }

    .dragger {
        background: url(<?= base_url()?>skote_assets/images/dragger.png) 10px 32px no-repeat;
        cursor: pointer;
    }

    .input-transparent {
        box-shadow: none;
        outline: 0;
        border: 0 !important;
        background: 0 0;
        padding: 3px;
    }

</style>
<?php
if (!empty($invoice_info)) {
    $invoices_id = $invoice_info->invoices_id;
    $companies_id = $invoice_info->companies_id;
} else {
    $invoices_id = null;
    $companies_id = null;
}
echo form_open(base_url('admin/invoice/save_invoice/' . $invoices_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card <?php if (!isset($invoice_info) || (isset($invoice_info) && !empty($invoices_to_merge) && count($invoices_to_merge) == 0)) { echo ' hide'; } ?>" id="invoice_top_info">
            <div class="card-body">
                <div class="row">
                    <div id="merge" class="col-md-8">
                        <?php if (isset($invoice_info) && !empty($invoices_to_merge)) {
                            $this->load->view('admin/invoice/merge_invoice', array('invoices_to_merge' => $invoices_to_merge));
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$mdate = date('Y-m-d');
$last_7_days = date('Y-m-d', strtotime('today - 7 days'));
$all_goal_tracking = $this->invoice_model->get_permission('tbl_goal_tracking');

$all_goal = 0;
$bank_goal = 0;
$complete_achivement = 0;
if (!empty($all_goal_tracking)) {
    foreach ($all_goal_tracking as $v_goal_track) {
        $goal_achieve = $this->invoice_model->get_progress($v_goal_track, true);
        if ($v_goal_track->goal_type_id == 5) {
            if ($v_goal_track->end_date <= $mdate) { // check today is last date or not

                if ($v_goal_track->email_send == 'no') {// check mail are send or not
                    if ($v_goal_track->achievement <= $goal_achieve['achievement']) {
                        if ($v_goal_track->notify_goal_achive == 'on') {// check is notify is checked or not check
                            $this->invoice_model->send_goal_mail('goal_achieve', $v_goal_track);
                        }
                    } else {
                        if ($v_goal_track->notify_goal_not_achive == 'on') {// check is notify is checked or not check
                            $this->invoice_model->send_goal_mail('goal_not_achieve', $v_goal_track);
                        }
                    }
                }
            }
            $all_goal += $v_goal_track->achievement;
            $complete_achivement += $goal_achieve['achievement'];
        }
    }
}
// 30 days before

for ($iDay = 7; $iDay >= 0; $iDay--) {
    $date = date('Y-m-d', strtotime('today - ' . $iDay . 'days'));
    $where = array('date_saved >=' => $date . " 00:00:00", 'date_saved <=' => $date . " 23:59:59");
    $invoice_result[$date] = count($this->db->where($where)->get('tbl_invoices')->result());
}

$terget_achievement = $this->db->where(array('goal_type_id' => 5, 'start_date >=' => $last_7_days, 'end_date <=' => $mdate))->get('tbl_goal_tracking')->result();

$total_terget = 0;
if (!empty($terget_achievement)) {
    foreach ($terget_achievement as $v_terget) {
        $total_terget += $v_terget->achievement;
    }
}
$tolal_goal = $all_goal + $bank_goal;
$currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
if ($this->session->userdata('user_type') == 1) {
$margin = 'margin-bottom:30px';
$h_s = config_item('invoice_state');
?>
<div class="row" id="state_report" style="display: <?= $h_s ?>">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('achievement') ?></p>
                            <h5 class="mb-0"><?= $tolal_goal ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('last_weeks') . ' ' . lang('created') ?></p>
                            <h5 class="mb-0"><?= $total_terget ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('completed') . ' ' . lang('achievements') ?></p>
                            <h5 class="mb-0"><?= $complete_achivement ?></h5>
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
                            <p class="text-muted text-truncate mb-2"><?= lang('pending') . ' ' . lang('achievements') ?></p>
                            <h5 class="mb-0">
                                <?php
                                if ($tolal_goal < $complete_achivement) {
                                    $pending_goal = 0;
                                } else {
                                    $pending_goal = $tolal_goal - $complete_achivement;
                                } ?>
                            <?= $pending_goal ?>
                            </h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                        <?php
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
<?php
$client_outstanding = $this->invoice_model->all_outstanding();
$currency = $this->db->where(array('code' => config_item('default_currency')))->get('tbl_currencies')->row();
?>
<div class="row">
    <div class="col-lg-12">
        <div class="row mb-3">
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                    <!-- START widget-->
                        <h3 class="card-title mb-4"><?php
                            if ($client_outstanding > 0) {
                                echo display_money($client_outstanding, $currency->symbol);
                            } else {
                                echo display_money(0, $currency->symbol);
                            }
                            ?></h3>
                        <p class="text-warning m0"><?= lang('total') . ' ' . lang('outstanding') . ' ' . lang('invoice') ?></p>
                    </div>
                </div>
            </div>
            <!-- END widget-->
            <?php
            $past_overdue = 0;
            $all_paid_amount = 0;
            $not_paid = 0;
            $fully_paid = 0;
            $draft = 0;
            $partially_paid = 0;
            $overdue = 0;
            $all_invoices = $this->invoice_model->get_permission('tbl_invoices');

            if (!empty($all_invoices)) {
                $all_invoices = array_reverse($all_invoices);
                foreach ($all_invoices as $v_invoice) {
                    $payment_status = $this->invoice_model->get_payment_status($v_invoice->invoices_id);
                    if (strtotime($v_invoice->due_date) < time() AND $payment_status != lang('fully_paid')) {
                        $past_overdue += $this->invoice_model->calculate_to('invoice_due', $v_invoice->invoices_id);
                    }
                    $all_paid_amount += $this->invoice_model->calculate_to('paid_amount', $v_invoice->invoices_id);

                    if ($this->invoice_model->get_payment_status($v_invoice->invoices_id) == lang('not_paid')) {
                        $not_paid += count($v_invoice->invoices_id);
                    }
                    if ($this->invoice_model->get_payment_status($v_invoice->invoices_id) == lang('fully_paid')) {
                        $fully_paid += count($v_invoice->invoices_id);
                    }
                    if ($this->invoice_model->get_payment_status($v_invoice->invoices_id) == lang('draft')) {
                        $draft += count($v_invoice->invoices_id);
                    }
                    if ($this->invoice_model->get_payment_status($v_invoice->invoices_id) == lang('partially_paid')) {
                        $partially_paid += count($v_invoice->invoices_id);
                    }
                    if (strtotime($v_invoice->due_date) < time() AND $payment_status != lang('fully_paid')) {
                        $overdue += count($v_invoice->invoices_id);
                    }
                }
            }

            ?>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <!-- START widget-->
                        <h3 class="card-title mb-4"><?= display_money($all_paid_amount + $client_outstanding, $currency->symbol) ?></h3>
                        <p class="text-primary m0"><?= lang('total') . ' ' . lang('invoice_amount') ?></p>
                    </div>
                </div>
                <!-- END widget-->
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <!-- START widget-->
                        <h3 class="card-title mb-4"><?= display_money($past_overdue, $currency->symbol) ?></h3>
                        <p class="text-danger m0"><?= lang('past') . ' ' . lang('overdue') . ' ' . lang('invoice') ?></p>
                    </div>
                </div>
                <!-- END widget-->
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <!-- START widget-->
                        <h3 class="card-title mb-4"><?= display_money($all_paid_amount, $currency->symbol) ?></h3>
                        <p class="text-success m0"><?= lang('paid') . ' ' . lang('invoice') ?></p>
                    </div>
                </div>
                <!-- END widget-->
            </div>
        </div>
        <?php if (!empty($all_invoices)) { ?>
        <div class="row mb-3">
            <div class="col-lg-2 col-md-2 col-sm-5">
                <div class="card">
                    <div class="card-body">
                        <!-- START widget-->
                        <h3 class="card-title mb-4">
                            <strong>
                                <a style="font-size: 15px" href="<?= base_url() ?>admin/invoice/manage_invoice/filter_by/not_paid"><?= lang('unpaid') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $not_paid ?> / <?= count($all_invoices) ?></small>
                            </strong>
                        </h3>
                        <div class="mt progress progress-xs progress-striped active" style="">
                            <div class="progress-bar progress-bar-danger" role="progressbar" style="width: <?= ($not_paid / count($all_invoices)) * 100 ?>%;" aria-valuenow="<?= ($not_paid / count($all_invoices)) * 100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <!-- END widget-->
            </div>

            <div class="col-lg-3 col-md-3 col-sm-5">
                <div class="card">
                    <div class="card-body">
                        <!-- START widget-->
                        <h3 class="card-title mb-4">
                            <strong><a style="font-size: 15px"
                                       href="<?= base_url() ?>admin/invoice/manage_invoice/filter_by/paid"><?= lang('paid') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $fully_paid ?>
                                    / <?= count($all_invoices) ?></small>
                            </strong>
                        </h3>
                        <div class="mt progress progress-xs progress-striped active" style="">
                            <div class="progress-bar progress-bar-success" role="progressbar" style="width: <?= ($fully_paid / count($all_invoices)) * 100 ?>%;" aria-valuenow="<?= ($fully_paid / count($all_invoices)) * 100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <!-- END widget-->
            </div>
            <div class="col-lg-3 col-md-3 col-sm-5">
                <div class="card">
                    <div class="card-body">
                        <!-- START widget-->
                        <h3 class="card-title mb-4">
                            <strong><a style="font-size: 15px"
                                       href="<?= base_url() ?>admin/invoice/manage_invoice/filter_by/partially_paid"><?= lang('partially_paid') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $partially_paid ?>
                                    / <?= count($all_invoices) ?></small>
                            </strong>
                        </h3>
                        <div class="mt progress progress-xs progress-striped active" style="">
                            <div class="progress-bar progress-bar-primary" role="progressbar" style="width: <?= ($partially_paid / count($all_invoices)) * 100 ?>%;" aria-valuenow="<?= ($partially_paid / count($all_invoices)) * 100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <!-- END widget-->
            </div>
            <div class="col-lg-2 col-md-2 col-sm-5">
                <div class="card">
                    <div class="card-body">
                        <!-- START widget-->
                        <h3 class="card-title mb-4">
                            <strong><a style="font-size: 15px"
                                       href="<?= base_url() ?>admin/invoice/manage_invoice/filter_by/overdue"><?= lang('overdue') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $overdue ?>
                                    / <?= count($all_invoices) ?></small>
                            </strong>
                        </h3>
                        <div class="mt progress progress-xs progress-striped active" style="">
                            <div class="progress-bar progress-bar-warning" role="progressbar" style="width: <?= ($overdue / count($all_invoices)) * 100 ?>%;" aria-valuenow="<?= ($overdue / count($all_invoices)) * 100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <!-- END widget-->
            </div>
            <div class="col-lg-2 col-md-2 col-sm-5">
                <div class="card">
                    <div class="card-body">
                        <!-- START widget-->
                        <h3 class="card-title mb-4">
                            <strong><a style="font-size: 15px"
                                       href="<?= base_url() ?>admin/invoice/manage_invoice/filter_by/draft"><?= lang('draft') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $draft ?>
                                    / <?= count($all_invoices) ?></small>
                            </strong>
                        </h3>
                        <div class="mt progress progress-xs progress-striped active" style="">
                            <div class="progress-bar progress-bar-aqua" role="progressbar" style="width: <?= ($draft / count($all_invoices)) * 100 ?>%;" aria-valuenow="<?= ($draft / count($all_invoices)) * 100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <!-- END widget-->
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<?php }

$type = $id;
if (!empty($type) && !is_numeric($type)) {
    $ex = explode('_', $type);
    if ($ex[0] == 'c') {
        $c_id = $ex[1];
        $type = '_' . date('Y');
    }
}
if (empty($type)) {
    $type = '_' . date('Y');
}
 
$created = can_action('13', 'created');
$edited = can_action('13', 'edited');
$deleted = can_action('13', 'deleted');

?>
<div class="row">
    <div class="card">
        <div class="card-body">  
            <div class="mb-lg pull-left mb-3">
                <div class="dropdown tbl-action mr pull-left">
                    <button class="btn btn-primary dropdown-toggle" id="dropdownButton2" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        echo lang('filter_by');
                        if (!empty($type) && !is_numeric($type)) {
                            $ex = explode('_', $type);
                            if (!empty($ex)) {
                                if (!empty($ex[1]) && is_numeric($ex[1])) {
                                    echo ' : ' . $ex[1];
                                } else {
                                    echo ' : ' . lang($type);
                                }
                            } else {
                                echo ' : ' . lang($type);
                            }

                        } ?>
                        <i class="mdi mdi-chevron-down"></i></button>
                    <div class="dropdown-menu" aria-labelledby="dropdownButton2">
                        <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/filter_by/all"><?= lang('all'); ?></a>
                        <?php
                        $invoiceFilter = $this->invoice_model->get_invoice_filter();
                        if (!empty($invoiceFilter)) {
                            foreach ($invoiceFilter as $v_Filter) {
                            ?>
                        <a class="dropdown-item <?php if ($v_Filter['value'] == $type) {
                                echo 'class="active"';
                            } ?> " href="<?= base_url() ?>admin/invoice/manage_invoice/filter_by/<?= $v_Filter['value'] ?>"><?= $v_Filter['name'] ?></a>
                        <?php   } } ?>
                    </div>
                </div>
            
                <div class="float-end">
                    <?php
                    if ($this->session->userdata('user_type') == 1) {
                        if ($h_s == 'block') {
                            $title = lang('hide_quick_state');
                            $url = 'hide';
                            $icon = 'fa fa-eye-slash';
                        } else {
                            $title = lang('view_quick_state');
                            $url = 'show';
                            $icon = 'fa fa-eye';
                        }
                        ?>
                    <span onclick="slideToggle('#state_report')" id="quick_state" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $title ?>" class="btn btn-sm btn-primary mr"> <i class="fa fa-bar-chart"></i>
                    </span>

                    <a class="btn btn-sm btn-info text-dark mr" id="change_report" href="<?= base_url() ?>admin/dashboard/change_report/<?= $url ?>">
                        <i class="<?= $icon ?>"></i><span><?= ' ' . lang('quick_state') . ' ' . lang($url) . ' ' . lang('always') ?></span>
                    </a>
                    <?php } ?>
                    <?php  if (!empty($created) || !empty($edited)){ ?>
                        <a data-bs-toggle="modal" data-bs-target="#myModal"
                           href="<?= base_url() ?>admin/invoice/zipped/invoice"
                           class="btn btn-success btn-sm ml-lg"><?= lang('zip_invoice') ?></a>
                    <?php } ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-xl-12">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs bg-light rounded" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="<?=base_url('admin/invoice/manage_invoice');?>"><?= lang('all_invoices') ?></a>
                        </li>
                        <?php if (!empty($created) || !empty($edited)){ ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab"><?= lang('create_invoice') ?></a>
                        </li>
                        <?php } ?>
                    </ul>
                    <div class="tab-content p-3 text-muted">
                        <!-- ************** general *************-->
                        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                            <h3 class="card-title mb-4"><strong><?= lang('all_invoices') ?></strong></h3>
                                <table class="table table-striped dt-responsive nowrap w-100" id="invoice_manage_datatable">
                                    <thead>
                                    <tr>
                                        <?php super_admin_opt_th() ?>
                                        <th><?= lang('invoice') ?></th>
                                        <th class="col-date"><?= lang('invoice_date') ?></th>
                                        <th class="col-date"><?= lang('due_date') ?></th>
                                        <th><?= lang('client_name') ?></th>
                                        <th class="col-currency"><?= lang('due_amount') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <?php $show_custom_fields = custom_form_table(9, null);
                                        if (!empty($show_custom_fields)) {
                                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                                if (!empty($c_label)) {
                                                    ?>
                                                    <th><?= $c_label ?> </th>
                                                <?php }
                                            }
                                        }
                                        ?>
                                        <?php if (!empty($edited) || !empty($deleted)) { ?>
                                            <th class="hidden-print"><?= lang('action') ?></th>
                                        <?php } ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($all_invoices_info)) {
                                        $all_invoices_info = array_reverse($all_invoices_info);
                                        foreach ($all_invoices_info as $v_invoices) {
                                            $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $v_invoices->invoices_id));
                                            $can_delete = $this->invoice_model->can_action('tbl_invoices', 'delete', array('invoices_id' => $v_invoices->invoices_id));

                                            if ($this->invoice_model->get_payment_status($v_invoices->invoices_id) == lang('fully_paid')) {
                                                $invoice_status = lang('fully_paid');
                                                $label = "success";
                                            } elseif ($this->invoice_model->get_payment_status($v_invoices->invoices_id) == lang('draft')) {
                                                $invoice_status = lang('draft');
                                                $label = "default";
                                            } elseif ($this->invoice_model->get_payment_status($v_invoices->invoices_id) == lang('partially_paid')) {
                                                $invoice_status = lang('partially_paid');
                                                $label = "warning";
                                            } elseif ($v_invoices->emailed == 'Yes') {
                                                $invoice_status = lang('sent');
                                                $label = "info";
                                            } else {
                                                $invoice_status = $this->invoice_model->get_payment_status($v_invoices->invoices_id);
                                                $label = "danger";
                                            }
                                            ?>
                                            <tr id="invoice_table_<?= $v_invoices->invoices_id ?>">
                                                <?php super_admin_opt_td($v_invoices->companies_id) ?>
                                                <td><a class="text-info"
                                                       href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_invoices->invoices_id ?>"><?= $v_invoices->reference_no ?></a>
                                                </td>
                                                <td><?= display_datetime($v_invoices->invoice_date) ?></td>
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
                                                <?php
                                                $client_info = $this->invoice_model->check_by(array('client_id' => $v_invoices->client_id), 'tbl_client');
                                                if (!empty($client_info)) {
                                                    $client_name = $client_info->name;
                                                } else {
                                                    $client_name = '-';
                                                }
                                                ?>
                                                <td><?= $client_name; ?></td>
                                                <?php $currency = $this->invoice_model->client_currency_sambol($v_invoices->client_id);
                                                if (empty($currency)) {
                                                    $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                                }
                                                ?>
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
                                                <?php $show_custom_fields = custom_form_table(9, $v_invoices->invoices_id);
                                                if (!empty($show_custom_fields)) {
                                                    foreach ($show_custom_fields as $c_label => $v_fields) {
                                                        if (!empty($c_label)) {
                                                            ?>
                                                            <td><?= $v_fields ?> </td>
                                                        <?php }
                                                    }
                                                }
                                                ?>
                                                <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                    <td class="hidden-print">
                                                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                            <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                               title="<?= lang('clone') . ' ' . lang('invoice') ?>"
                                                               href="<?= base_url() ?>admin/invoice/clone_invoice/<?= $v_invoices->invoices_id ?>"
                                                               class="btn btn-sm btn-outline-primary">
                                                                <i class="fa fa-copy"></i></a>

                                                            <?= btn_edit('admin/invoice/manage_invoice/create_invoice/' . $v_invoices->invoices_id) ?>
                                                        <?php }
                                                        if (!empty($can_delete) && !empty($deleted)) {
                                                            ?>
                                                            <?php echo ajax_anchor(base_url("admin/invoice/delete/delete_invoice/" . $v_invoices->invoices_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#invoice_table_" . $v_invoices->invoices_id)); ?>
                                                        <?php } ?>
                                                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                        <button class="btn btn-outline-success dropdown-toggle btn-sm" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_status') ?><i class="mdi mdi-chevron-down"></i></button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_invoices->invoices_id ?>"><?= lang('preview_invoice') ?></a>

                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/payment/<?= $v_invoices->invoices_id ?>"><?= lang('pay_invoice') ?></a>
                                                               
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/email_invoice/<?= $v_invoices->invoices_id ?>"><?= lang('email_invoice') ?></a>
                                                            
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/send_reminder/<?= $v_invoices->invoices_id ?>"><?= lang('send_reminder') ?></a>
                                                            
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/send_overdue/<?= $v_invoices->invoices_id ?>"><?= lang('send_invoice_overdue') ?></a>
                                                            
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_history/<?= $v_invoices->invoices_id ?>"><?= lang('invoice_history') ?></a>
                                                            
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/pdf_invoice/<?= $v_invoices->invoices_id ?>"><?= lang('pdf') ?></a>
                                                        </div>
                                                        <?php } ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                        </div>
                        <?php if (!empty($created) || !empty($edited)) { ?>
                        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                            <div class="card">
                                <?php
                                if (!empty($invoice_info)) {
                                    $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');
                                    if (!empty($client_info)) {
                                        $client_lang = $client_info->language;
                                        $currency = $this->invoice_model->client_currency_sambol($invoice_info->client_id);
                                    } else {
                                        $client_lang = 'english';
                                        $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                    }
                                } else {
                                    $client_lang = 'english';
                                    $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                }
                                unset($this->lang->is_loaded[5]);
                                $language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
                                ?>
                                <div class="row invoice accounting-template">                    
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6 br pv">
                                                    <?php super_admin_form($companies_id, 5, 7) ?>
                                                    <div class="row mb-3">
                                                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('reference_no') ?> <span class="text-danger">*</span></label>
                                                        <div class="col-xl-4 col-md-4 col-sm-4">
                                                            <input type="text" class="form-control" value="<?php
                                                            if (!empty($invoice_info)) {
                                                                echo $invoice_info->reference_no;
                                                            } else {
                                                                echo config_item('invoice_prefix');
                                                                if (config_item('increment_invoice_number') == 'FALSE') {
                                                                    $this->load->helper('string');
                                                                    echo random_string('nozero', 6);
                                                                } else {
                                                                    echo $this->invoice_model->generate_invoice_number();
                                                                }
                                                            }
                                                            ?>" name="reference_no">
                                                        </div>
                                                        <div class="col-xl-1 col-md-1 col-sm-1">
                                                            <div class="btn btn-sm btn-info" id="start_recurring"><?= lang('recurring') ?></div>
                                                        </div>
                                                    </div>
                                                    <div id="recurring" class="<?= (!empty($invoice_info) && $invoice_info->recurring == 'Yes' ? '' : 'hide') ?> row mb-3">
                                                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('recur_frequency') ?> </label>
                                                        <div class="col-md-7 col-sm-7">
                                                            <select name="recuring_frequency" id="recuring_frequency"  class="form-control">
                                                                <option value="none"><?= lang('none') ?></option>
                                                                <option
                                                                        value="7D" <?= (!empty($invoice_info) && $invoice_info->recur_frequency == '7D' ? 'selected' : '') ?>><?= lang('week') ?></option>
                                                                <option
                                                                        value="1M" <?= (!empty($invoice_info) && $invoice_info->recur_frequency == '1M' ? 'selected' : '') ?>><?= lang('month') ?></option>
                                                                <option
                                                                        value="3M" <?= (!empty($invoice_info) && $invoice_info->recur_frequency == '3M' ? 'selected' : '') ?>><?= lang('quarter') ?></option>
                                                                <option
                                                                        value="6M" <?= (!empty($invoice_info) && $invoice_info->recur_frequency == '6M' ? 'selected' : '') ?>><?= lang('six_months') ?></option>
                                                                <option
                                                                        value="1Y" <?= (!empty($invoice_info) && $invoice_info->recur_frequency == '1Y' ? 'selected' : '') ?>><?= lang('1year') ?></option>
                                                                <option
                                                                        value="2Y" <?= (!empty($invoice_info) && $invoice_info->recur_frequency == '2Y' ? 'selected' : '') ?>><?= lang('2year') ?></option>
                                                                <option
                                                                        value="3Y" <?= (!empty($invoice_info) && $invoice_info->recur_frequency == '3Y' ? 'selected' : '') ?>><?= lang('3year') ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                        <div class="row mb-3">
                                                            <label  class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('start_date') ?></label>
                                                            <div class="col-xl-7 col-md-7 col-sm-7">
                                                                <?php
                                                                if (!empty($invoice_info) && $invoice_info->recurring == 'Yes') {
                                                                    $recur_start_date = date('d-m-Y H-i', strtotime($invoice_info->recur_start_date));
                                                                    $recur_end_date = date('d-m-Y H-i', strtotime($invoice_info->recur_end_date));
                                                                } else {
                                                                    $recur_start_date = date('d-m-Y H-i');
                                                                    $recur_end_date = date('d-m-Y H-i');
                                                                }
                                                                ?>
                                                                <div class="input-group">
                                                                    <input class="form-control datepicker" type="text"
                                                                           value="<?= $recur_start_date; ?>"
                                                                           name="recur_start_date"
                                                                           data-date-format="<?= config_item('date_picker_format'); ?>">
                                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label
                                                                    class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('end_date') ?></label>
                                                            <div class="col-xl-7 col-md-7 col-sm-7">
                                                                <div class="input-group">
                                                                    <input class="form-control datepicker" type="text"
                                                                           value="<?= $recur_end_date; ?>"
                                                                           name="recur_end_date"
                                                                           data-date-format="<?= config_item('date_picker_format'); ?>">
                                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <div class="row mb-3 f_client_id">
                                                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('client') ?> <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="col-xl-7 col-md-7 col-sm-7">
                                                            <select class="form-control select_box" style="width: 100%"
                                                                    name="client_id"
                                                                    onchange="get_project_by_id(this.value)" required="">
                                                                <option
                                                                        value=""><?= lang('select') . ' ' . lang('client') ?></option>
                                                                <?php
                                                                if (!empty($all_client)) {
                                                                    foreach ($all_client as $v_client) {
                                                                        if (!empty($project_info->client_id)) {
                                                                            $client_id = $project_info->client_id;
                                                                        } elseif (!empty($invoice_info->client_id)) {
                                                                            $client_id = $invoice_info->client_id;
                                                                        } elseif (!empty($c_id)) {
                                                                            $client_id = $c_id;
                                                                        }
                                                                        ?>
                                                                        <option value="<?= $v_client->client_id ?>"
                                                                            <?php
                                                                            if (!empty($client_id)) {
                                                                                echo $client_id == $v_client->client_id ? 'selected' : null;
                                                                            }
                                                                            ?>
                                                                        ><?= ucfirst($v_client->name) ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('project') ?></label>
                                                        <div class="col-xl-7 col-md-7 col-sm-7">
                                                            <select class="form-control" style="width: 100%"
                                                                    name="project_id"
                                                                    id="client_project">
                                                                <option value=""><?= lang('none') ?></option>
                                                                <?php

                                                                if (!empty($client_id)) {

                                                                    if (!empty($project_info->project_id)) {
                                                                        $project_id = $project_info->project_id;
                                                                    } elseif ($invoice_info->project_id) {
                                                                        $project_id = $invoice_info->project_id;
                                                                    }
                                                                    $all_project = $this->db->where('client_id', $client_id)->get('tbl_project')->result();
                                                                    if (!empty($all_project)) {
                                                                        foreach ($all_project as $v_project) {
                                                                            ?>
                                                                            <option value="<?= $v_project->project_id ?>" <?php
                                                                            if (!empty($project_id)) {
                                                                                echo $v_project->project_id == $project_id ? 'selected' : '';
                                                                            }
                                                                            ?>><?= $v_project->project_name ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label
                                                                class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('invoice_date') ?></label>
                                                        <div class="col-xl-7 col-md-7 col-sm-7">
                                                            <div class="input-group">
                                                                <input type="text" name="invoice_date"
                                                                       class="form-control datepicker"
                                                                       value="<?php
                                                                       if (!empty($invoice_info->invoice_date)) {
                                                                           echo date('d-m-Y H-i', strtotime($invoice_info->invoice_date));
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
                                                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('due_date') ?></label>
                                                        <div class="col-xl-7 col-md-7 col-sm-7">
                                                            <div class="input-group">
                                                                <input type="text" name="due_date"
                                                                       class="form-control datepicker"
                                                                       value="<?php
                                                                       if (!empty($invoice_info->due_date)) {
                                                                           echo date('d-m-Y H-i', strtotime($invoice_info->due_date));
                                                                       } else {
                                                                           echo strftime(date('d-m-Y H-i', strtotime("+" . config_item('invoices_due_after') . " days")));
                                                                       }
                                                                       ?>"
                                                                       data-date-format="<?= config_item('date_picker_format'); ?>">
                                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="discount_type"
                                                               class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('discount_type') ?></label>
                                                        <div class="col-xl-7 col-md-7 col-sm-7">
                                                            <select name="discount_type" class="select_box form-control " data-width="100%">
                                                                <option value=""
                                                                        selected><?php echo lang('no') . ' ' . lang('discount'); ?></option>
                                                                <option value="before_tax" <?php
                                                                if (isset($invoice_info)) {
                                                                    if ($invoice_info->discount_type == 'before_tax') {
                                                                        echo 'selected';
                                                                    }
                                                                } ?>><?php echo lang('before_tax'); ?></option>
                                                                <option value="after_tax" <?php if (isset($invoice_info)) {
                                                                    if ($invoice_info->discount_type == 'after_tax') {
                                                                        echo 'selected';
                                                                    }
                                                                } ?>><?php echo lang('after_tax'); ?></option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3" id="border-none">
                                                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('permission') ?> <span class="required">*</span></label>
                                                        <div class="col-xl-7 col-md-7 col-sm-7">
                                                            <div class="form-check form-radio-outline form-radio-primary mt mr">
                                                                <input id="everyone" <?php
                                                                    if (!empty($invoice_info->permission) && $invoice_info->permission == 'all') {
                                                                        echo 'checked';
                                                                    }
                                                                    ?> type="radio" name="permission" value="everyone" class="form-check-input">
                                                                <label class="form-check-label" for="everyone"><?= lang('everyone') ?>
                                                                    <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-radio-outline form-radio-primary mt mr">
                                                                <input id="custom_permission" <?php
                                                                    if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                                                        echo 'checked';
                                                                    } elseif (empty($invoice_info)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                                                                <label class="form-check-label" for="custom_permission"><?= lang('custom_permission') ?>
                                                                    <i title="<?= lang('permission_for_customization') ?>"
                                                                            class="fa fa-question-circle" data-bs-toggle="tooltip"
                                                                            data-bs-placement="top"></i>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3 <?php if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') { echo 'show'; } ?>" id="permission_user_1">
                                                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('select') . ' ' . lang('users') ?>
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
                                                                    <div class="form-check form-check mb-3 mr">
                                                                        <input type="checkbox"
                                                                        <?php
                                                                        if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                                                            $get_permission = json_decode($invoice_info->permission);
                                                                            foreach ($get_permission as $user_id => $v_permission) {
                                                                                if ($user_id == $v_user->user_id) {
                                                                                    echo 'checked';
                                                                                }
                                                                            }

                                                                        }
                                                                        ?> value="<?= $v_user->user_id ?>" name="assigned_to[]" data-name="<?= $v_user->username;?>"  class="form-check-input" id="user_<?= $v_user->user_id ?>">
                                                                        <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?>
                                                                        </label>
                                                                    </div>
                                                                    <div class="action_1 p
                                                                        <?php
                                                                        if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                                                            $get_permission = json_decode($invoice_info->permission);

                                                                            foreach ($get_permission as $user_id => $v_permission) {
                                                                                if ($user_id == $v_user->user_id) {
                                                                                    echo 'show';
                                                                                }
                                                                            }

                                                                        }
                                                                        ?> " id="action_1<?= $v_user->user_id ?>">
                                                                        <div class="form-check form-check mb-3 mr-5">
                                                                            <input class="form-check-input" type="checkbox" id="view_<?= $v_user->user_id ?>" checked name="action_<?= $v_user->user_id ?>[]" disabled  value="view">
                                                                            <label class="form-check-label" for="view_<?= $v_user->user_id ?>">
                                                                                <?= lang('can') . ' ' . lang('view') ?>
                                                                            </label>
                                                                        </div>
                                                                        
                                                                        <div class="form-check form-check mb-3 mr-5">
                                                                            <input class="form-check-input" type="checkbox" value="edit" name="action_<?= $v_user->user_id ?>[]" id="edit_<?= $v_user->user_id ?>"

                                                                                <?php

                                                                                if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                                                                    $get_permission = json_decode($invoice_info->permission);

                                                                                    foreach ($get_permission as $user_id => $v_permission) {
                                                                                        if ($user_id == $v_user->user_id) {
                                                                                            if (in_array('edit', $v_permission)) {
                                                                                                echo 'checked';
                                                                                            };

                                                                                        }
                                                                                    }

                                                                                }
                                                                                ?>>
                                                                            <label class="form-check-label" for="edit_<?= $v_user->user_id ?>">
                                                                                <?= lang('can') . ' ' . lang('edit') ?>
                                                                            </label>
                                                                        </div>

                                                                        <div class="form-check form-check mb-3 mr-5">
                                                                            <input class="form-check-input" name="action_<?= $v_user->user_id ?>[]" type="checkbox" value="delete" id="delete_<?= $v_user->user_id ?>"
                                                                                <?php

                                                                                if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                                                                    $get_permission = json_decode($invoice_info->permission);
                                                                                    foreach ($get_permission as $user_id => $v_permission) {
                                                                                        if ($user_id == $v_user->user_id) {
                                                                                            if (in_array('delete', $v_permission)) {
                                                                                                echo 'checked';
                                                                                            };
                                                                                        }
                                                                                    }

                                                                                }
                                                                                ?>>
                                                                            <label class="form-check-label" for="delete_<?= $v_user->user_id ?>">
                                                                                <?= lang('can') . ' ' . lang('delete') ?>
                                                                            </label>
                                                                        </div>

                                                                        <input id="<?= $v_user->user_id ?>" type="hidden"  name="action_<?= $v_user->user_id ?>[]" value="view">
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } ?>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if (!empty($invoice_info)) {
                                                        $invoices_id = $invoice_info->invoices_id;
                                                    } else {
                                                        $invoices_id = null;
                                                    }
                                                    ?>
                                                    <?= custom_form_Fields(9, $invoices_id); ?>
                                                </div>
                                                <div class="col-lg-6 br pv">
                                                    <div class="row mb-3">
                                                        <label class="col-xl-4 col-form-label col-md-4 col-sm-4 "><?= lang('sales') . ' ' . lang('agent') ?></label>
                                                        <div class="col-xl-7 col-md-7 col-sm-7">
                                                            <select class="form-control select_box" required style="width: 100%"
                                                                    name="user_id">
                                                                <option
                                                                        value=""><?= lang('select') . ' ' . lang('sales') . ' ' . lang('agent') ?></option>
                                                                <?php
                                                                $all_user = get_staff_details();
                                                               
                                                                if (!empty($all_user)) {
                                                                    foreach ($all_user as $v_user) {
                                                                        $profile_info = $this->db->where('user_id', $v_user->user_id)->get('tbl_account_details')->row();
                                                                        if (!empty($profile_info)) {
                                                                            ?>
                                                                            <option value="<?= $v_user->user_id ?>"
                                                                                <?php
                                                                                if (!empty($invoice_info->user_id)) {
                                                                                    echo $invoice_info->user_id == $v_user->user_id ? 'selected' : null;
                                                                                } else {
                                                                                    echo $this->session->userdata('user_id') == $v_user->user_id ? 'selected' : null;
                                                                                }
                                                                                ?>
                                                                            ><?= $profile_info->fullname ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <?php if (config_item('paypal_status') == 'active'): ?>
                                                        <div class="row mb-3">
                                                            <label class="col-xl-4 col-form-label col-md-4 col-sm-4 "><?= lang('allow_paypal') ?></label>
                                                            <div class="col-xl-7 col-md-7 col-sm-7">
                                                                <div class="form-check form-check mb-3 mr">
                                                                    <input type="checkbox" value="Yes"
                                                                            <?php if (!empty($invoice_info) && $invoice_info->allow_paypal == 'Yes') {
                                                                                echo 'checked';
                                                                            } ?> name="allow_paypal" id="allow_paypal" class="form-check-input">
                                                                    <label class="form-check-label" for="allow_paypal"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif ?>
                                                    <?php if (config_item('stripe_status') == 'active'): ?>
                                                        <div class="row mb-3">
                                                            <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('allow_stripe') ?></label>
                                                            <div class="col-xl-7 col-md-7 col-sm-7">
                                                                <div class="form-check form-check mb-3 mr">
                                                                    <input type="checkbox" value="Yes"
                                                                            <?php if (!empty($invoice_info) && $invoice_info->allow_stripe == 'Yes') {
                                                                                echo 'checked';
                                                                            } ?>
                                                                               name="allow_stripe" class="form-check-input">
                                                                    <label class="form-check-label"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (config_item('2checkout_status') == 'active'): ?>
                                                        <div class="row mb-3">
                                                            <label class="col-xl-4 col-form-label col-md-4 col-sm-4 "><?= lang('allow_2checkout') ?></label>

                                                            <div class="col-xl-7 col-md-7 col-sm-7">

                                                                <div class="form-check form-check mb-3 mr">
                                                                    <input type="checkbox" value="Yes"
                                                                            <?php if (!empty($invoice_info) && $invoice_info->allow_2checkout == 'Yes') {
                                                                                echo 'checked';
                                                                            } ?>
                                                                               name="allow_2checkout" class="form-check-input">
                                                                    <label class="form-check-label"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (config_item('authorize_status') == 'active'): ?>
                                                        <div class="row mb-3">
                                                            <label class="col-xl-4 col-form-label col-md-4 col-sm-4 "><?= lang('allow_authorize.net') ?></label>

                                                            <div class="col-xl-7 col-md-7 col-sm-7 col-md-7 col-sm-7 col-sm-7">
                                                                <div class="form-check form-check mb-3 mr">
                                                                    <input type="checkbox" value="Yes"
                                                                            <?php if (!empty($invoice_info) && $invoice_info->allow_authorize == 'Yes') {
                                                                                echo 'checked';
                                                                            } ?>
                                                                               name="allow_authorize" class="form-check-input">
                                                                    <label class="form-check-label"></label>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (config_item('ccavenue_status') == 'active'): ?>
                                                        <div class="row mb-3">
                                                            <label class="col-xl-4 col-form-label col-md-4 col-sm-4 "><?= lang('allow_ccavenue') ?></label>

                                                            <div class="col-xl-7 col-md-7 col-sm-7 col-md-7 col-sm-7 col-sm-7">
                                                                <div class="form-check form-check mb-3 mr">
                                                                    <input type="checkbox" value="Yes"
                                                                            <?php if (!empty($invoice_info) && $invoice_info->allow_ccavenue == 'Yes') {
                                                                                echo 'checked';
                                                                            } ?>
                                                                               name="allow_ccavenue" class="form-check-input">
                                                                    <label class="form-check-label"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (config_item('braintree_status') == 'active'): ?>
                                                        <div class="row mb-3">
                                                            <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('allow_braintree') ?></label>

                                                            <div class="col-xl-7 col-md-7 col-sm-7 col-md-7 col-sm-7 col-sm-7">
                                                                <div class="form-check form-check mb-3 mr">
                                                                    <input type="checkbox" value="Yes"
                                                                            <?php if (!empty($invoice_info) && $invoice_info->allow_braintree == 'Yes') {
                                                                                echo 'checked';
                                                                            } ?>
                                                                               name="allow_braintree" class="form-check-input">
                                                                    <label class="form-check-label"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (config_item('mollie_status') == 'active'): ?>
                                                        <div class="row mb-3">
                                                            <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('allow_mollie') ?></label>

                                                            <div class="col-xl-7 col-md-7 col-sm-7 col-md-7 col-sm-7 col-sm-7">
                                                                <div class="form-check form-check mb-3 mr">
                                                                    <input type="checkbox" value="Yes"
                                                                            <?php if (!empty($invoice_info) && $invoice_info->allow_mollie == 'Yes') {
                                                                                echo 'checked';
                                                                            } ?>
                                                                               name="allow_mollie" class="form-check-input">
                                                                    <label class="form-check-label"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (config_item('payumoney_status') == 'active'): ?>
                                                        <div class="row mb-3">
                                                            <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('allow_payumoney') ?></label>

                                                            <div class="col-xl-7 col-md-7 col-sm-7 col-md-7 col-sm-7 col-sm-7">
                                                                <div class="form-check form-check mb-3 mr">
                                                                    <input type="checkbox" value="Yes"
                                                                            <?php if (!empty($invoice_info) && $invoice_info->allow_payumoney == 'Yes') {
                                                                                echo 'checked';
                                                                            } ?>
                                                                               name="allow_payumoney" class="form-check-input">
                                                                    <label class="form-check-label"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if (!empty($project_id)): ?>
                                                        <div class="row mb-3">
                                                            <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('visible_to_client') ?>
                                                                <span class="required">*</span></label>
                                                            <div class="col-xl-7 col-md-7 col-sm-7">
                                                                <div class="form-check form-switch mb-3">
                                                                    <input class="form-check-input" name="client_visible" value="Yes" <?php
                                                                        if (!empty($invoice_info->client_visible) && $invoice_info->client_visible == 'Yes') {
                                                                        echo 'checked';
                                                                        }
                                                                    ?> type="checkbox" id="client_visible">                                           
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-lg-6 br pv">
                                                    <div class="terms row mb-3">
                                                        <label class="col-xl-2 col-form-label col-md-2 col-sm-2"><?= lang('notes') ?> </label>
                                                        <div class="col-xl-9 col-md-9 col-sm-9">
                                                              <textarea name="notes" id="elm1" class="textarea_"><?php
                                                                if (!empty($invoice_info)) {
                                                                    echo $invoice_info->notes;
                                                                } else {
                                                                    echo $this->config->item('default_terms');
                                                                }
                                                                ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12 br pv ">
                                                    <div class="col-lg-12 col-md-12">
                                                        <input type="text" class="form-control" name="term"
                                                               placeholder="<?= lang('enter_product_name_and_code') ?>"
                                                               id="search_text">
                                                        <hr class="m0 mb-lg mt-lg"/>
                                                    </div>
                                                    <script>
                                                        $(document).ready(function () {
                                                            load_data();

                                                            function load_data(query) {
                                                                $.ajax({
                                                                    url: "<?php echo base_url(); ?>admin/global_controller/items_suggestions/12",
                                                                    method: "POST",
                                                                    data: {term: query},
                                                                    success: function (data) {
                                                                        $('#product_result').html(data);
                                                                    }
                                                                })
                                                            }

                                                            $('#search_text').keyup(function () {
                                                                var search = $(this).val();
                                                                if (search != '') {
                                                                    load_data(search);
                                                                } else {
                                                                    load_data();
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                    <div id="product_result" class="product_result row"></div>
                                                </div>
                                            </div>
                                            <hr class="row"/>
                                            <div class="row p-3">
                                                <div class="col-xl-4">
                                                    <div class="row mb-3">
                                                        <input type="text" placeholder="<?= lang('search_product_by_name_code'); ?>"  id="invoice_item" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-xl-3">
                                                </div>
                                                <div class="col-xl-5">
                                                    <div class="row mb-3">
                                                        <label class="col-xl-4 col-form-label"><?php echo lang('show_quantity_as'); ?></label>
                                                        <div class="col-xl-8" style="display:inline-flex;">
                                                            <div class="form-check form-radio-outline form-radio-primary mt mr">
                                                                <input type="radio" value="qty" id="<?php echo lang('qty'); ?>"
                                                                       name="show_quantity_as"
                                                                    <?php if (isset($invoice_info) && $invoice_info->show_quantity_as == 'qty') {
                                                                        echo 'checked';
                                                                    } else if (!isset($hours_quantity) && !isset($qty_hrs_quantity)) {
                                                                        echo 'checked';
                                                                    } ?> class="form-check-input">
                                                                <label class="form-check-label" for="<?php echo lang('qty'); ?>"><?php echo lang('qty'); ?></label>
                                                            </div>
                                                            <div class="form-check form-radio-outline form-radio-primary mt mr">
                                                                <input type="radio" value="hours" id="<?php echo lang('hours'); ?>"
                                                                       name="show_quantity_as" <?php if (isset($invoice_info) && $invoice_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                                                    echo 'checked';
                                                                } ?> class="form-check-input">
                                                                <label class="form-check-label" for="<?php echo lang('hours'); ?>"><?php echo lang('hours'); ?></label>
                                                            </div>
                                                            <div class="form-check form-radio-outline form-radio-primary mt mr">
                                                                <input type="radio" value="qty_hours"
                                                                       id="<?php echo lang('qty') . '/' . lang('hours'); ?>"
                                                                       name="show_quantity_as"
                                                                    <?php if (isset($invoice_info) && $invoice_info->show_quantity_as == 'qty_hours' || isset($qty_hrs_quantity)) {
                                                                        echo 'checked';
                                                                    } ?>  class="form-check-input">
                                                                <label class="form-check-label" for="<?php echo lang('qty') . '/' . lang('hours'); ?>"><?php echo lang('qty') . '/' . lang('hours'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive s_table">
                                                <table class="table table-editable table-nowrap align-middle table-edits invoice-items-table items">
                                                    <thead style="background: #e8e8e8">
                                                        <tr>
                                                            <th></th>
                                                            <th><?= $language_info['item_name'] ?></th>
                                                            <th><?= $language_info['description'] ?></th>
                                                            <?php
                                                            $invoice_view = config_item('invoice_view');
                                                            if (!empty($invoice_view) && $invoice_view == '2') {
                                                                ?>
                                                                <th class="col-lg-2 col-md-2 col-sm-2"><?= $language_info['hsn_code'] ?></th>
                                                            <?php } ?>
                                                            <?php
                                                            $qty_heading = $language_info['qty'];
                                                            if (isset($invoice_info) && $invoice_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                                                $qty_heading = lang('hours');
                                                            } else if (isset($invoice_info) && $invoice_info->show_quantity_as == 'qty_hours') {
                                                                $qty_heading = lang('qty') . '/' . lang('hours');
                                                            }
                                                            ?>
                                                            <th class="qty col-lg-2 col-md-2 col-sm-2"><?php echo $qty_heading; ?></th>
                                                            <th class="col-lg-2 col-md-2 col-sm-2"><?= $language_info['price'] ?></th>
                                                            <th class="col-lg-2 col-md-2 col-sm-2"><?= $language_info['tax_rate'] ?> </th>
                                                            <th class="col-lg-1 col-md-1 col-sm-1"><?= $language_info['total'] ?></th>
                                                            <th class="col-lg-1 col-md-1 col-sm-1 hidden-print"><?= $language_info['action'] ?></th>
                                                        </tr>
                                                    </thead>
                                                    <?php if (isset($invoice_info)) {
                                                        echo form_hidden('merge_current_invoice', $invoice_info->invoices_id);
                                                        echo form_hidden('isedit', $invoice_info->invoices_id);
                                                    }
                                                    ?>
                                                    <tbody id="InvoiceTable"></tbody>
                                                </table>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-5"></div>
                                                <div class="col-xl-7 pull-right">
                                                    <table class="table text-right">
                                                        <tbody>
                                                            <tr id="subtotal" class="text-end">
                                                                <td><span class="bold"><?php echo lang('sub_total'); ?> :</span>
                                                                </td>
                                                                <td class="subtotal text-end">
                                                                </td>
                                                            </tr>
                                                            <tr id="discount_percent" class="text-end">
                                                                <td>
                                                                    <div class="row">
                                                                        <div class="col-md-7 mt"> <span class="bold"><?php echo lang('discount'); ?> (%)</span>
                                                                        </div>
                                                                        <div class="col-md-5 text-end">
                                                                            <?php
                                                                            $discount_percent = 0;
                                                                            if (isset($invoice_info)) {
                                                                                if ($invoice_info->discount_percent != 0) {
                                                                                    $discount_percent = $invoice_info->discount_percent;
                                                                                }
                                                                            }
                                                                            ?>
                                                                            <input type="text" data-parsley-type="number" value="<?php echo $discount_percent; ?>" class="form-control pull-left" min="0" max="100" name="discount_percent">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="discount_percent text-end"></td>
                                                            </tr>
                                                            <tr class="text-end">
                                                                <td>
                                                                    <div class="row">
                                                                        <div class="col-md-7 mt">
                                                                            <span class="bold"><?php echo lang('adjustment'); ?></span>
                                                                        </div>
                                                                        <div class="col-md-5 text-end">
                                                                            <input type="text" data-parsley-type="number"
                                                                                   value="<?php if (isset($invoice_info)) {
                                                                                       echo $invoice_info->adjustment;
                                                                                   } else {
                                                                                       echo 0;
                                                                                   } ?>" class="form-control pull-left"
                                                                                   name="adjustment">
                                                                         </div>
                                                                    </div>
                                                                </td>
                                                                <td class="adjustment text-end"></td>
                                                            </tr>
                                                            <tr class="text-end">
                                                                <td><span class="bold"><?php echo lang('total'); ?> :</span>
                                                                </td>
                                                                <td class="total text-end">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div id="removed-items"></div>
                                            
                                            <div class="modal-footer">
                                                <input type="button" id="InvoiceReset" value="<?= lang('reset') ?>" name="update"
                                                       class="btn btn-danger">
                                                <input type="submit" value="<?= lang('save_as_draft') ?>" name="save_as_draft"
                                                       class="btn btn-primary">
                                                <input type="submit" value="<?= lang('update') ?>" name="update"
                                                       class="btn btn-success">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo form_close(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('select[name="companies_id"]').on('change', function () {
            var companies_id = $(this).val();
            if (companies_id) {
                $.ajax({
                    url: '<?= base_url('admin/global_controller/json_by_company/tbl_client/')?>' + companies_id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {

                        $('select[name="client_id"]').find('option').not(':first').remove();
                        $.each(data, function (key, value) {
                            $('select[name="client_id"]').append('<option value="' + value.client_id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('select[name="client_id"]').empty();
            }
        });
    });
</script>
<script type="text/javascript">
    function slideToggle($id) {
        $('#quick_state').attr('data-original-title', '<?= lang('view_quick_state') ?>');
        $($id).slideToggle("slow");
    }

    $(document).ready(function () {
        $("#select_all_tasks").click(function () {
            $(".tasks_list").prop('checked', $(this).prop('checked'));
        });
        $("#select_all_expense").click(function () {
            $(".expense_list").prop('checked', $(this).prop('checked'));
        });
        $('[data-bs-toggle="popover"]').popover();

    });
</script>
<script type="text/javascript">
    $(document).ready(function () {

        init_items_sortable();
        $('#start_recurring').click(function () {

            if ($('#recurring').is(":visible")) {
                $('#recuring_frequency').prop('disabled', true);
            } else {
                $('#recuring_frequency').prop('disabled', false);
            }
            $('#recurring').slideToggle("fast");
            $('#recurring').removeClass("hide");
            $('#recurring').css("display", "inherit");

        });
    });
</script>
<?php
if (isset($invoice_info)) {
    $add_items = $this->invoice_model->ordered_items_by_id($invoice_info->invoices_id, 'invoices', true);
    if (!empty($add_items)) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                store('InvoiceItems', JSON.stringify(<?= $add_items; ?>));
            });
        </script>
    <?php }
} ?>
<script type="text/javascript">
    var InvoiceItems = {};
    if (localStorage.getItem('remove_invoice')) {
        if (localStorage.getItem('InvoiceItems')) {
            localStorage.removeItem('InvoiceItems');
        }
        localStorage.removeItem('remove_invoice');
    }

    $(document).ready(function () {
        <?php
        $editInvoice = $this->uri->segment(5);
        $edit_invoice = $this->session->userdata('edit_invoice');
        if(empty($editInvoice) && !empty($edit_invoice)){
        ?>
        if (get('InvoiceItems')) {
            remove('InvoiceItems');
        }
        <?php
        $this->session->unset_userdata('edit_invoice');
        }
        ?>
        $("#sparkline2").sparkline([<?php if (!empty($invoice_result)) { foreach ($invoice_result as $v_invoice_result) { echo $v_invoice_result . ','; } } ?>], {
            type: 'bar',
            height: '20',
            barWidth: 8,
            barSpacing: 6,
            barColor: '#23b7e5'
       });
    });
</script>
<?php include_once 'skote_assets/js/invoice.php'; ?>