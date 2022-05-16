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
$created = can_action('140', 'created');
$edited = can_action('140', 'edited');
$deleted = can_action('140', 'deleted');
if (!empty($proposals_info)) {
    $proposals_id = $proposals_info->proposals_id;
    $companies_id = $proposals_info->companies_id;
    $unlayertemplate_id = $proposals_info->unlayertemplateid;
    $unlayertemplatejson = $proposals_info->unlayertemplatejson;
} else {
    $proposals_id = null;
    $companies_id = null;
    $unlayertemplate_id = null;
    $unlayertemplatejson = null;
}
echo form_open(base_url('admin/proposals/save_proposals/' . $proposals_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
<?php
$curency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
$currency = $this->db->where(array('code' => config_item('default_currency')))->get('tbl_currencies')->row();
if ($this->session->userdata('user_type') == 1) {
    $margin = 'margin-bottom:30px';
    $h_s = config_item('proposal_state');

    $expired = 0;
    $draft = 0;
    $total_draft = 0;
    $total_sent = 0;
    $total_declined = 0;
    $total_accepted = 0;
    $total_expired = 0;
    $sent = 0;
    $declined = 0;
    $accepted = 0;
    $pending = 0;
    $cancelled = 0;
    $all_proposals = $this->proposal_model->get_permission('tbl_proposals');
    if (!empty($all_proposals)) {
        $all_proposals = array_reverse($all_proposals);
        foreach ($all_proposals as $v_invoice) {
            if (strtotime($v_invoice->due_date) < time() AND $v_invoice->status == ('pending') || strtotime($v_invoice->due_date) < time() AND $v_invoice->status == ('draft')) {
                $total_expired += $this->proposal_model->proposal_calculation('total', $v_invoice->proposals_id);
                $expired += count($v_invoice->proposals_id);;
            }
            if ($v_invoice->status == ('draft')) {
                $draft += count($v_invoice->proposals_id);
                $total_draft += $this->proposal_model->proposal_calculation('total', $v_invoice->proposals_id);
            }
            if ($v_invoice->status == ('sent')) {
                $sent += count($v_invoice->proposals_id);
                $total_sent += $this->proposal_model->proposal_calculation('total', $v_invoice->proposals_id);
            }
            if ($v_invoice->status == ('declined')) {
                $declined += count($v_invoice->proposals_id);
                $total_declined += $this->proposal_model->proposal_calculation('total', $v_invoice->proposals_id);
            }
            if ($v_invoice->status == ('accepted')) {
                $accepted += count($v_invoice->proposals_id);
                $total_accepted += $this->proposal_model->proposal_calculation('total', $v_invoice->proposals_id);
            }
            if ($v_invoice->status == ('pending')) {
                $pending += count($v_invoice->proposals_id);
            }
            if ($v_invoice->status == ('cancelled')) {
                $cancelled += count($v_invoice->proposals_id);
            }
        }
    }
?>
<div class="row" id="state_report" style="display: <?= $h_s ?>">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('draft') ?></p>
                            <h5 class="mb-0"><?= display_money($total_draft, $currency->symbol) ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('sent') ?></p>
                            <h5 class="mb-0"><?= display_money($total_sent, $currency->symbol) ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('expired') ?></p>
                            <h5 class="mb-0"><?= display_money($total_expired, $currency->symbol) ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('declined') ?></p>
                            <h5 class="mb-0"><?= display_money($total_declined, $currency->symbol) ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('accepted') ?></p>
                            <h5 class="mb-0"><?= display_money($total_accepted, $currency->symbol) ?></h5>
                    </div>
                </div>
            </div>
        </div>
        <?php if (!empty($all_proposals)) { ?>
        <div class="row mb-3">
            <div class="col-lg-2 col-md-2 col-sm-5">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">
                            <strong><a class="text-primary" style="font-size: 15px"
                                       href="<?= base_url() ?>admin/proposals/index/filter_by/draft"><?= lang('draft') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $draft ?>
                                    / <?= count($all_proposals) ?></small>
                            </strong>
                        </h3>
                        <div class="mt progress progress-xs progress-striped active" style="">
                            <div class="progress-bar progress-bar-danger" role="progressbar" style="width: <?= ($draft / count($all_proposals)) * 100 ?>%;" aria-valuenow="<?= ($draft / count($all_proposals)) * 100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        
                        </div>
                    </div>
                    <!-- END widget-->
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-5">
                <div class="card">
                    <div class="card-body">
                        <!-- START widget-->
                        <h3 class="card-title mb-4">
                            <strong><a class="text-primary" style="font-size: 15px"
                                       href="<?= base_url() ?>admin/proposals/index/filter_by/sent"><?= lang('sent') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $sent ?>
                                    / <?= count($all_proposals) ?></small>
                            </strong>
                        </h3>
                        <div class="mt progress progress-xs progress-striped active" style="">
                            <div class="progress-bar progress-bar-danger" role="progressbar" style="width: <?= ($sent / count($all_proposals)) * 100 ?>%;" aria-valuenow="<?= ($sent / count($all_proposals)) * 100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        
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
                            <strong><a class="text-danger" style="font-size: 15px"
                                       href="<?= base_url() ?>admin/proposals/index/filter_by/expired"><?= lang('expired') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $expired ?>
                                    / <?= count($all_proposals) ?></small>
                            </strong>
                        </h3>
                        <div class="mt progress progress-xs progress-striped active" style="">
                            <div class="progress-bar progress-bar-danger" role="progressbar" style="width: <?= ($expired / count($all_proposals)) * 100 ?>%;" aria-valuenow="<?= ($expired / count($all_proposals)) * 100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        
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
                            <strong><a class="text-warning" style="font-size: 15px"
                                       href="<?= base_url() ?>admin/proposals/index/filter_by/declined"><?= lang('declined') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $declined ?>
                                    / <?= count($all_proposals) ?></small>
                            </strong>
                        </h3>
                        <div class="mt progress progress-xs progress-striped active" style="">
                            <div class="progress-bar progress-bar-danger" role="progressbar" style="width: <?= ($declined / count($all_proposals)) * 100 ?>%;" aria-valuenow="<?= ($declined / count($all_proposals)) * 100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        
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
                            <strong><a class="text-success" style="font-size: 15px"
                                       href="<?= base_url() ?>admin/proposals/index/filter_by/accepted"><?= lang('accepted') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $accepted ?>
                                    / <?= count($all_proposals) ?></small>
                            </strong>
                        </h3>
                        <div class="mt progress progress-xs progress-striped active" style="">
                            <div class="progress-bar progress-bar-danger" role="progressbar" style="width: <?= ($accepted / count($all_proposals)) * 100 ?>%;" aria-valuenow="<?= ($accepted / count($all_proposals)) * 100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        
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
$type = $this->uri->segment(5);

if (empty($type)) {
    $type = '_' . date('Y');
}

if (!empty($type) && !is_numeric($type)) {
    $filterBy = $type;
} else {
    $filterBy = null;
}
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
                        <a class="dropdown-item" href="<?= base_url() ?>admin/proposals/index/filter_by/all"><?= lang('all'); ?></a>
                        <?php
                        $invoiceFilter = $this->proposal_model->get_invoice_filter();
                        if (!empty($invoiceFilter)) {
                            foreach ($invoiceFilter as $v_Filter) {  ?>
                        <a class="dropdown-item <?php if ($v_Filter['value'] == $type) { echo 'class="active"'; } ?>" href="<?= base_url() ?>admin/proposals/index/filter_by/<?= $v_Filter['value'] ?>"><?= $v_Filter['name'] ?></a>
                        <?php } } ?>
                    </div>
                </div>
            
                <div class="float-end">
                
                    <?php
                    if ($this->session->userdata('user_type') == 1) {
                        $type = 'proposal';
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
                        <span onclick="slideToggle('#state_report')" id="quick_state" data-bs-toggle="tooltip" data-placement="top"
                             title="<?= $title ?>"
                             class="btn-sm btn btn-primary pull-left mr">
                            <i class="fa fa-bar-chart"></i>
                        </span>
                        <a class="btn btn-sm btn-info text-dark mr" id="change_report" href="<?= base_url() ?>admin/dashboard/change_report/<?= $url . '/' . $type ?>"><i class="<?= $icon ?>"></i> <span><?= ' ' . lang('quick_state') . ' ' . lang($url) . ' ' . lang('always') ?></span>
                        </a>
                    <?php }
                    if (!empty($created) || !empty($edited)){ ?>
                        <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/invoice/zipped/proposal" class="btn btn-success btn-sm ml-lg"><?= lang('zip_proposal') ?></a>
                    <?php } ?>
                </div>
            </div>
            <div class="mb-lg pull-right mb-3">
                <?php 
                 if(!is_subdomain() && admin()){
                    ?>
                 <a type="button" href="<?php echo base_url('admin/proposals/template_list'); ?>" class="btn btn-warning">Template Categories</a>
               <?php  } ?>
                <a type="button" href="<?php echo base_url('admin/proposals/index/withquote'); ?>" class="btn btn-primary">Create Standard Proposal</a>
                <a type="button" href="<?php echo base_url('admin/proposals/index/withoutquote'); ?>" class="btn btn-primary">Create Live Proposal</a>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-xl-12">
                    <!-- Tabs within a box -->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="contentTable">
                        <h3 class="card-title mb-4"><strong><?= lang('all_proposals') ?></strong></h3>
                        <table class="table table-striped dt-responsive nowrap w-100" id="all_proposals_manage_datatable">
                            <thead>
                                <tr>
                                    <?php super_admin_opt_th() ?>
                                    <th><?= lang('proposal') ?> #</th>
                                    <th><?= lang('proposal_date') ?></th>
                                    <th><?= lang('expire_date') ?></th>
                                    <th><?= strtoupper(lang('TO')) ?></th>
                                    <th><?= lang('amount') ?></th>
                                    <th><?= lang('status') ?></th>
                                    <?php $show_custom_fields = custom_form_table(11, null);
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

                                if (!empty($all_proposals_info)) {
                                    foreach ($all_proposals_info as $v_proposals) {
                                        $can_edit = $this->proposal_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $v_proposals->proposals_id));
                                        $can_delete = $this->proposal_model->can_action('tbl_proposals', 'delete', array('proposals_id' => $v_proposals->proposals_id));

                                        if ($v_proposals->status == 'pending') {
                                            $label = "info";
                                        } elseif ($v_proposals->status == 'accepted') {
                                            $label = "success";
                                        } else {
                                            $label = "danger";
                                        }
                                        ?>
                                        <tr id="table_proposal_<?= $v_proposals->proposals_id ?>">
                                            <?php super_admin_opt_td($v_proposals->companies_id) ?>
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
                                                    <span class="label label-danger "><?= lang('expired') ?></span>
                                                <?php }
                                                ?>
                                            </td>
                                            <?php
                                            if ($v_proposals->module == 'client') {
                                                $client_info = $this->proposal_model->check_by(array('client_id' => $v_proposals->module_id), 'tbl_client');
                                                if (!empty($client_info)) {
                                                    $client_name = $client_info->name;
                                                    $currency = $this->proposal_model->client_currency_sambol($v_proposals->module_id);
                                                } else {
                                                    $client_name = '-';
                                                    $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                                }
                                            } else if ($v_proposals->module == 'leads') {
                                                $client_info = $this->proposal_model->check_by(array('leads_id' => $v_proposals->module_id), 'tbl_leads');
                                                if (!empty($client_info)) {
                                                    $client_name = $client_info->contact_name;
                                                } else {
                                                    $client_name = '-';
                                                }

                                                $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                            } else {
                                                $client_name = '-';
                                                $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                            }
                                            ?>
                                            <td><?= $client_name ?></td>
                                            <?php ?>
                                            <td>
                                                <?= display_money($this->proposal_model->proposal_calculation('total', $v_proposals->proposals_id), $currency->symbol); ?>
                                            </td>
                                            <td><span
                                                        class="label label-<?= $label ?>"><?= lang($v_proposals->status) ?></span>
                                            </td>
                                            <?php $show_custom_fields = custom_form_table(11, $v_proposals->proposals_id);
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
                                                <td>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                        <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                           title="<?= lang('clone') . ' ' . lang('proposal') ?>"
                                                           href="<?= base_url() ?>admin/proposals/clone_proposal/<?= $v_proposals->proposals_id ?>"
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fa fa-copy"></i></a>

                                                        <?= btn_edit('admin/proposals/index/edit_proposals/' . $v_proposals->proposals_id) ?>
                                                    <?php }
                                                    if (!empty($can_delete) && !empty($deleted)) {
                                                        ?>
                                                        <?php echo ajax_anchor(base_url("admin/proposals/delete/delete_proposals/" . $v_proposals->proposals_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_proposal_" . $v_proposals->proposals_id)); ?>
                                                    <?php } ?>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                    <button class="btn btn-outline-success dropdown-toggle btn-sm" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_status') ?><i class="mdi mdi-chevron-down"></i></button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                        <a class="dropdown-item" href="<?= base_url() ?>admin/proposals/index/email_proposals/<?= $v_proposals->proposals_id ?>"><?= lang('send_email') ?></a>

                                                        <a class="dropdown-item" href="<?= base_url() ?>admin/proposals/index/proposals_details/<?= $v_proposals->proposals_id ?>"><?= lang('view_details') ?></a>

                                                        <a class="dropdown-item" href="<?= base_url() ?>admin/proposals/index/proposals_history/<?= $v_proposals->proposals_id ?>"><?= lang('history') ?></a>
                                                        <a class="dropdown-item" href="<?= base_url() ?>admin/proposals/change_status/declined/<?= $v_proposals->proposals_id ?>"><?= lang('declined') ?></a>
                                                        <a class="dropdown-item" href="<?= base_url() ?>admin/proposals/change_status/accepted/<?= $v_proposals->proposals_id ?>"><?= lang('accepted') ?></a>
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
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    function slideToggle($id) {
        $('#quick_state').attr('data-original-title', '<?= lang('view_quick_state') ?>');
        $($id).slideToggle("slow");
    }

    $(document).ready(function () {
        init_items_sortable();
    });
</script>
<?php
if (isset($proposals_info)) {
    $add_items = $this->proposal_model->ordered_items_by_id($proposals_info->proposals_id, true);
    if (!empty($add_items)) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                store('ProposalItems', JSON.stringify(<?= $add_items; ?>));
            });
        </script>
    <?php }
} ?>
<script type="text/javascript">
    var ProposalItems = {};
    if (localStorage.getItem('remove_proposal')) {
        if (localStorage.getItem('ProposalItems')) {
            localStorage.removeItem('ProposalItems');
        }
        localStorage.removeItem('remove_proposal');
    }
    
    $(document).ready(function () {
        <?php
        $editProposal = $this->uri->segment(5);
        $edit_proposal = $this->session->userdata('edit_proposal');
        if(empty($editProposal) && !empty($edit_proposal)){
        ?>
        if (get('ProposalItems')) {
            remove('ProposalItems');
        }
        <?php
        $this->session->unset_userdata('edit_proposal');
        }
        ?>
    });
</script>
<?php include_once 'skote_assets/js/proposal.php'; ?>


<!-- Script -->
 <script type="text/javascript">
     $(document).ready(function(){
        $('#contentTable').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
             'url':'<?=base_url()?>admin/datatable/proposals?filter=<?=$filterBy;?>'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_proposal_"+iDisplayIndex);
            return nRow;
          },
          'columns': [
             { data: 'subscriptions_id' },
             { data: 'plan_name' },
             { data: 'domain' },
             { data: 'industry_type' },
             { data: 'trial_period' },
             { data: 'currency' },
             { data: 'frequency' },
             { data: 'status' },
             { data: 'date' },
             { data: 'action' },
          ]
        });
     });
 </script>