<?= message_box('success') ?>
<?= message_box('error');
$edited = can_action('140', 'edited');
$deleted = can_action('140', 'deleted');
?>

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

$can_edit = $this->proposal_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $proposals_info->proposals_id));
$can_delete = $this->proposal_model->can_action('tbl_proposals', 'delete', array('proposals_id' => $proposals_info->proposals_id));
if ($proposals_info->module == 'client') {
    $client_info = $this->proposal_model->check_by(array('client_id' => $proposals_info->module_id), 'tbl_client');
    $currency = $this->proposal_model->client_currency_sambol($proposals_info->module_id);
    $client_lang = $client_info->language;
} else if ($proposals_info->module == 'leads') {
    $client_info = $this->proposal_model->check_by(array('leads_id' => $proposals_info->module_id), 'tbl_leads');
    if (!empty($client_info)) {
        $client_info->name = $client_info->contact_name;
        $client_info->zipcode = null;
    }
    $client_lang = 'english';
    $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
} else {
    $client_lang = 'english';
    $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
}
unset($this->lang->is_loaded[5]);
$language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);

?>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <label class="pull-left col-form-label col-xl-2"><?= lang('copy_unique_url') ?></label>
                    <div class="col-xl-10">
                        <input value="<?= base_url() ?>frontend/proposals/<?= url_encode($proposals_info->proposals_id); ?>" type="text" id="foo" class="form-control"/>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-xl-10">
                          <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                  
                                    <a class="btn btn-sm btn-primary" href="<?= base_url() ?>admin/proposals/index/edit_proposals/<?= $proposals_info->proposals_id ?>"><i class="fa fa-edit text-white"></i> <?= lang('edit') . ' ' . lang('proposal') ?></a>
                                        
                                    <?php } ?>
                        <?php if (!empty($can_edit) && !empty($edited)) { ?>

                            <!-- <a data-bs-toggle="modal" data-bs-target="#myModal_lg"
                               href="<?= base_url() ?>admin/proposals/insert_items/<?= $proposals_info->proposals_id ?>"
                               title="<?= lang('item_quick_add') ?>" class="btn btn-sm btn-primary">
                                <i class="fa fa-pencil text-white"></i> <?= lang('add_items') ?></a>
 -->
                            <?php if ($proposals_info->show_client == 'Yes') { ?>
                            <a class="btn btn-sm btn-success"
                               href="<?= base_url() ?>admin/proposals/change_status/hide/<?= $proposals_info->proposals_id ?>"
                               title="<?= lang('hide_to_client') ?>"><i class="fa fa-eye-slash"></i> <?= lang('hide_to_client') ?>
                                </a><?php } else { ?>
                            <a class="btn btn-sm btn-warning"
                               href="<?= base_url() ?>admin/proposals/change_status/show/<?= $proposals_info->proposals_id ?>"
                               title="<?= lang('show_to_client') ?>"><i class="fa fa-eye"></i> <?= lang('show_to_client') ?>
                                </a><?php }
                            if ($proposals_info->convert != 'Yes') {
                                ?>
                            <div class="btn-group" role="group">
                                <button id="btnGroup" type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?= lang('convert') . ' ' . lang('TO') ?>"><?= lang('convert') . ' ' . lang('TO') ?><i class="mdi mdi-chevron-down"></i></button>
                                <div class="dropdown-menu" aria-labelledby="btnGroup">
                                    <a class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#myModal_extra_lg" href="<?= base_url() ?>admin/proposals/convert_to/invoice/<?= $proposals_info->proposals_id ?>" title="<?= lang('invoice') ?>"><?= lang('invoice') ?></a>
                                    
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#myModal_extra_lg" href="<?= base_url() ?>admin/proposals/convert_to/estimate/<?= $proposals_info->proposals_id ?>"><?= lang('estimate') ?></a>
                                    
                                </div>
                            </div>
                            <?php } else {
                                if ($proposals_info->convert_module == 'invoice') {
                                    $convert_info = $this->proposal_model->check_by(array('invoices_id' => $proposals_info->convert_module_id), 'tbl_invoices');
                                    $c_url = base_url() . 'admin/invoice/manage_invoice/invoice_details/' . $proposals_info->convert_module_id;
                                } else {
                                    $convert_info = $this->proposal_model->check_by(array('estimates_id' => $proposals_info->convert_module_id), 'tbl_estimates');
                                    $c_url = base_url() . 'admin/estimates/index/estimates_details/' . $proposals_info->convert_module_id;
                                } ?>
                            <?php } ?>
                            <span data-bs-toggle="tooltip" data-placement="top" title="<?= lang('clone') . ' ' . lang('proposal') ?>">
                            <a data-bs-toggle="modal" data-bs-target="#myModal" title="<?= lang('clone') . ' ' . lang('proposal') ?>"
                               href="<?= base_url() ?>admin/proposals/clone_proposal/<?= $proposals_info->proposals_id ?>"
                               class="btn btn-sm btn-success">
                                <i class="fa fa-copy"></i> <?= lang('clone') ?></a>
                            </span>

                            <div class="btn-group" role="group">
                                <button id="btnGroupVerticalDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?= lang('more_actions') ?>"><?= lang('more_actions') ?><i class="mdi mdi-chevron-down"></i></button>

                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">

                                    <a class="dropdown-item" href="<?= base_url() ?>admin/proposals/index/email_proposals/<?= $proposals_info->proposals_id ?>"
                                           data-bs-toggle="ajaxModal"><?= lang('email_proposal') ?></a>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/proposals/index/proposals_history/<?= $proposals_info->proposals_id ?>"><?= lang('proposal_history') ?></a>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/proposals/change_status/draft/<?= $proposals_info->proposals_id ?>"
                                           title="<?= lang('unmark_as_draft') ?>"><?= lang('mark_as_draft') ?></a>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/proposals/change_status/sent/<?= $proposals_info->proposals_id ?>"
                                           title="<?= lang('mark_as_sent') ?>"><?= lang('mark_as_sent') ?></a>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/proposals/change_status/revised/<?= $proposals_info->proposals_id ?>"
                                           title="<?= lang('mark_as_revised') ?>"><?= lang('mark_as_revised') ?></a>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/proposals/change_status/open/<?= $proposals_info->proposals_id ?>"
                                           title="<?= lang('mark_as_open') ?>"><?= lang('mark_as_open') ?></a>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/proposals/change_status/declined/<?= $proposals_info->proposals_id ?>"><?= lang('declined') ?></a>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/proposals/change_status/accepted/<?= $proposals_info->proposals_id ?>"><?= lang('accepted') ?></a>
                                    
                                
                                </div>
                            </div>
                            <?php if (!empty($c_url)) { ?>
                                <a class="btn btn-secondary btn-sm" href="<?= $c_url ?>"><i class="fa fa-hand-o-right"></i> <?= $convert_info->reference_no ?></a>
                            <?php } ?>
                        <?php } ?>
                        <?php
                        $notified_reminder = count($this->db->where(array('module' => 'proposal', 'module_id' => $proposals_info->proposals_id, 'notified' => 'No'))->get('tbl_reminders')->result());
                        ?>
                        <a class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#myModal_lg"
                           href="<?= base_url() ?>admin/invoice/reminder/proposal/<?= $proposals_info->proposals_id ?>"><?= lang('reminder') ?>
                            <?= !empty($notified_reminder) ? '<span class="badge ml-sm" style="border-radius: 50%">' . $notified_reminder . '</span>' : '' ?>
                        </a>
                          
                    </div>
                    <div class="col-xl-2 pull-right">
                        <a href="<?= base_url() ?>admin/proposals/send_proposals_email/<?= $proposals_info->proposals_id . '/' . true ?>" data-bs-toggle="tooltip" data-placement="top" title="<?= lang('send_email') ?>" class="btn btn-sm btn-primary pull-right">
                            <i class="fa fa-envelope-o"></i>
                        </a>
                        <a onclick="print_proposals('print_proposals')" href="#" data-bs-toggle="tooltip" data-placement="top" title=""
                           data-original-title="Print" class="mr-5 btn btn-sm btn-danger pull-right">
                            <i class="fa fa-print"></i>
                        </a>

                        <a href="<?= base_url() ?>admin/proposals/pdf_proposals/<?= $proposals_info->proposals_id ?>" data-bs-toggle="tooltip" data-placement="top" title="" data-original-title="PDF" class="btn btn-sm btn-success pull-right mr-5">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Start Display Details -->
<?php
if (strtotime($proposals_info->due_date) < time() AND $proposals_info->status == 'pending') {
    $start = strtotime(date('Y-m-d'));
    $end = strtotime($proposals_info->due_date);
    $days_between = ceil(abs($end - $start) / 86400);
    ?>
    <div class="alert bg-danger-light hidden-print">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <i class="fa fa-warning"></i>
        <?= lang('proposal_overdue') . ' ' . lang('by') . ' ' . $days_between . ' ' . lang('days') ?>
    </div>
    <?php
}
?>
<style type="text/css">
    .dragger {
        background: url(../../../../skote_assets/images/dragger.png) 0px 11px no-repeat;
        cursor: pointer;
    }

    .table > tbody > tr > td {
        vertical-align: initial;
    }
</style>

<!-- Main content -->
<div class="row">
    <div class="col-lg-12">
        <div class="card" id="print_proposals">
            <div class="card-body">
                <iframe src="<?= base_url() ?>frontend/proposals/<?= url_encode($proposals_info->proposals_id); ?>" width="100%" height="600px"></iframe>
            </div>

            <div class="card-body d-none">
                <div class="invoice-title">
                    <div class="row mb-3">
                        <div class="col-xl-5">
                            <img src="<?= base_url() . config_item('invoice_logo') ?>" alt="logo" />
                        </div>
                        <?php

                        if (!empty($client_info)) {
                            $client_name = $client_info->name;
                            $address = $client_info->address;
                            $city = $client_info->city;
                            $zipcode = $client_info->zipcode;
                            $country = $client_info->country;
                            $phone = $client_info->phone;

                        } else {
                            $client_name = '-';
                            $address = '-';
                            $city = '-';
                            $zipcode = '-';
                            $country = '-';
                            $phone = '-';
                        }
                        ?>
                        <div class="text-sm-end col-xl-7">
                            <h4 class="font-size-16"><?= lang('proposals') . ' : ' . $proposals_info->reference_no ?></h4>
                            <strong><?= $language_info['proposal_date'] ?>:</strong> <?= display_datetime($proposals_info->proposal_date); ?>
                            <br>
                            <strong><?= $language_info['due_date'] ?>:</strong> <?= display_datetime($proposals_info->due_date); ?>
                            <?php if (!empty($proposals_info->user_id)) { ?>
                            <br><strong><?= lang('sales') . ' ' . lang('agent') ?>:</strong> <?php echo fullname($proposals_info->user_id); ?>
                                <?php
                            }
                            if ($proposals_info->status == 'accepted') {
                                $label = 'success';
                            } else {
                                $label = 'danger';
                            }
                            ?>
                            <br><strong><?= lang('proposals') . '  ' . lang('status') ?>:</strong> 
                            <span class="badge badge-soft-<?= $label ?>"><?= lang($proposals_info->status) ?></span>

                            <?php $show_custom_fields = custom_form_label(11, $proposals_info->proposals_id);
                            if (!empty($show_custom_fields)) {
                                foreach ($show_custom_fields as $c_label => $v_fields) {
                                    if (!empty($v_fields)) {
                                        ?>
                                        <br><strong><?= $c_label ?>:</strong> <?= $v_fields; ?>
                                    <?php }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <address>
                                <strong><?= lang('our_info') ?>:</strong><br>
                                <h4 class="mb0"><?= (config_item('company_legal_name_' . $client_lang) ? config_item('company_legal_name_' . $client_lang) : config_item('company_legal_name')) ?></h4>
                                <?= (config_item('company_address_' . $client_lang) ? config_item('company_address_' . $client_lang) : config_item('company_address')) ?>
                                <br><?= (config_item('company_city_' . $client_lang) ? config_item('company_city_' . $client_lang) : config_item('company_city')) ?>
                                , <?= config_item('company_zip_code') ?>
                                <br><?= (config_item('company_country_' . $client_lang) ? config_item('company_country_' . $client_lang) : config_item('company_country')) ?>
                                <br/><?= $language_info['phone'] ?> : <?= config_item('company_phone') ?>
                                <br/><?= lang('vat_number') ?> : <?= config_item('company_vat') ?>
                            </address>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <address class="mt-2 mt-sm-0">
                                <strong><?= lang('customer') ?>:</strong><br>
                                <?php
                                if (!empty($client_info)) {
                                    $client_name = $client_info->name;
                                    $address = $client_info->address;
                                    $city = $client_info->city;
                                    $zipcode = $client_info->zipcode;
                                    $country = $client_info->country;
                                    $phone = $client_info->phone;

                                } else {
                                    $client_name = '-';
                                    $address = '-';
                                    $city = '-';
                                    $zipcode = '-';
                                    $country = '-';
                                    $phone = '-';
                                }
                                ?>
                                <h4 class="mb0"><?= $client_name ?></h4>
                                <?= $address ?>
                                <br> <?= $city ?>, <?= $zipcode ?>
                                <br><?= $country ?>
                                <br><?= $language_info['phone'] ?>: <?= $phone ?>
                                <?php if (!empty($client_info->vat)) { ?>
                                    <br><?= lang('vat_number') ?>: <?= $client_info->vat ?>
                                <?php } ?>
                            </address>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-nowrap items proposal-items-preview" page-break-inside: auto;>
                            <thead class="bg-items">
                                <tr>
                                    <th>#</th>
                                    <th><?= $language_info['items'] ?></th>
                                    <?php
                                    $invoice_view = config_item('invoice_view');
                                    if (!empty($invoice_view) && $invoice_view == '2') {
                                        ?>
                                        <th><?= $language_info['hsn_code'] ?></th>
                                    <?php } ?>
                                    <?php
                                    $qty_heading = $language_info['qty'];
                                    if (isset($proposals_info) && $proposals_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                        $qty_heading = lang('hours');
                                    } else if (isset($proposals_info) && $proposals_info->show_quantity_as == 'qty_hours') {
                                        $qty_heading = lang('qty') . '/' . lang('hours');
                                    }
                                    ?>
                                    <th><?php echo $qty_heading; ?></th>
                                    <th class="col-sm-1"><?= $language_info['price'] ?></th>
                                    <th class="col-sm-2"><?= $language_info['tax'] ?></th>
                                    <th class="col-sm-1"><?= $language_info['total'] ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $invoice_items = $this->proposal_model->ordered_items_by_id($proposals_info->proposals_id);
                                if (!empty($invoice_items)) :
                                    foreach ($invoice_items as $key => $v_item) :
                                        $item_name = $v_item->item_name ? $v_item->item_name : $v_item->item_desc;
                                        $item_tax_name = json_decode($v_item->item_tax_name);
                                        ?>
                                        <tr class="sortable item" data-item-id="<?= $v_item->proposals_items_id ?>">
                                            <td class="item_no dragger pl-lg"><?= $key + 1 ?></td>
                                            <td><strong class="block"><?= $item_name ?></strong>
                                                <?= nl2br($v_item->item_desc) ?>
                                            </td>
                                            <?php
                                            $invoice_view = config_item('invoice_view');
                                            if (!empty($invoice_view) && $invoice_view == '2') {
                                                ?>
                                                <td><?= $v_item->hsn_code ?></td>
                                            <?php } ?>
                                            <td><?= $v_item->quantity . '   &nbsp' . $v_item->unit ?></td>
                                            <td><?= display_money($v_item->unit_cost) ?></td>
                                            <td><?php
                                                if (!empty($item_tax_name)) {
                                                    foreach ($item_tax_name as $v_tax_name) {
                                                        $i_tax_name = explode('|', $v_tax_name);
                                                        echo '<small class="pr-sm">' . $i_tax_name[0] . ' (' . $i_tax_name[1] . ' %)' . '</small>' . display_money($v_item->total_cost / 100 * $i_tax_name[1]) . ' <br>';
                                                    }
                                                }
                                                ?></td>
                                            <td><?= display_money($v_item->total_cost) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8"><?= lang('nothing_to_display') ?></td>
                                    </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt">
                        <div class="col-xl-8">
                            <p class="border mt p-3">
                                <?= $proposals_info->notes ?>
                            </p>
                        </div>
                        <div class="col-xl-4 pv">
                            <div class="clearfix">
                                <p class="pull-left"><?= $language_info['sub_total'] ?></p>
                                <p class="pull-right mr">
                                    <?= display_money($this->proposal_model->proposal_calculation('proposal_cost', $proposals_info->proposals_id)); ?>
                                </p>
                            </div>
                            <?php if ($proposals_info->discount_total > 0): ?>
                            <div class="clearfix">
                                <p class="pull-left"><?= $language_info['discount'] ?>
                                    (<?php echo $proposals_info->discount_percent; ?>
                                    %)</p>
                                <p class="pull-right mr">
                                    <?= display_money($this->proposal_model->proposal_calculation('discount', $proposals_info->proposals_id)); ?>
                                </p>
                            </div>
                            <?php endif ?>
                            <?php
                            $tax_info = json_decode($proposals_info->total_tax);
                            $tax_total = 0;
                            if (!empty($tax_info)) {
                            $tax_name = $tax_info->tax_name;
                            $total_tax = $tax_info->total_tax;
                            if (!empty($tax_name)) {
                                foreach ($tax_name as $t_key => $v_tax_info) {
                                    $tax = explode('|', $v_tax_info);
                                    $tax_total += $total_tax[$t_key];
                                    ?>
                            <div class="clearfix">
                                <p class="pull-left"><?= $tax[0] . ' (' . $tax[1] . ' %)' ?></p>
                                <p class="pull-right mr">
                                    <?= display_money($total_tax[$t_key]); ?>
                                </p>
                            </div>
                            <?php } } } ?>
                            <?php if ($tax_total > 0): ?>
                            <div class="clearfix">
                                <p class="pull-left"><?= $language_info['total'] . ' ' . $language_info['tax'] ?></p>
                                <p class="pull-right mr">
                                    <?= display_money($tax_total); ?>
                                </p>
                            </div>
                            <?php endif ?>
                            <?php if ($proposals_info->adjustment > 0): ?>
                            <div class="clearfix">
                                <p class="pull-left"><?= $language_info['adjustment'] ?></p>
                                <p class="pull-right mr">
                                    <?= display_money($proposals_info->adjustment); ?>
                                </p>
                            </div>
                            <?php endif ?>

                            <div class="clearfix">
                                <p class="pull-left"><?= $language_info['total'] ?></p>
                                <p class="pull-right mr">
                                    <?= display_money($this->proposal_model->proposal_calculation('total', $proposals_info->proposals_id), $currency->symbol); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
    <?= !empty($invoice_view) && $invoice_view > 0 ? $this->gst->summary($invoice_items) : ''; ?>
</div>
</div>
</div>
</div>
<?php include_once 'assets/js/sales.php'; ?>
<script type="text/javascript">
    $(document).ready(function () {
        init_items_sortable(true);
        <?php
        if ($this->session->userdata('remove_proposal')) {
        ?>
        if (get('ProposalItems')) {
            remove('ProposalItems');
        }
        <?php
        $this->session->unset_userdata('remove_proposal');
        }
        ?>
        <?php
        if ($this->session->userdata('remove_invoice')) {
        ?>
        if (get('InvoiceItems')) {
            remove('InvoiceItems');
        }
        <?php
        $this->session->unset_userdata('remove_invoice');
        }
        ?>
        <?php
        $remove_estimate = $this->session->userdata('remove_estimate');
        if (!empty($remove_estimate)) {
        ?>
        if (get('EstimateItems')) {
            remove('EstimateItems');
        }
        <?php
        $this->session->unset_userdata('remove_estimate');
        }
        ?>
    });

    function print_proposals(print_proposals) {
        var printContents = document.getElementById(print_proposals).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
<script type="text/javascript">
    var textBox = document.getElementById("foo");
    textBox.onfocus = function () {
        textBox.select();
        // Work around Chrome's little problem
        textBox.onmouseup = function () {
            // Prevent further mouseup intervention
            textBox.onmouseup = null;
            return false;
        };
    };
</script>
