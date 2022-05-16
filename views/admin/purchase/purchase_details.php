<?= message_box('success') ?>
<?= message_box('error');
$edited = can_action('150', 'edited');
$deleted = can_action('150', 'deleted');
$paid_amount = $this->purchase_model->calculate_to('paid_amount', $purchase_info->purchase_id);
$payment_status = $this->purchase_model->get_payment_status($purchase_info->purchase_id);
$currency = $this->purchase_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
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
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-xl-10">

                        <?php $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $purchase_info->purchase_id));
                        if (!empty($can_edit) && !empty($edited)) { ?>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('from_items') ?>">
                            <a data-bs-toggle="modal" data-bs-target="#myModal_lg"
                               href="<?= base_url() ?>admin/purchase/insert_items/<?= $purchase_info->purchase_id ?>"
                               title="<?= lang('item_quick_add') ?>" class="btn btn-sm btn-primary">
                                <i class="fa fa-pencil text-white"></i> <?= lang('add_items') ?></a>
                                </span>
                        <?php }
                        ?>
                        <?php
                        if (!empty($can_edit) && !empty($edited)) { ?>
                            <?php if ($this->purchase_model->get_purchase_cost($purchase_info->purchase_id) > 0) {
                                ?>
                                <?php if ($purchase_info->status == 'Cancelled') {
                                    $disable = 'disabled';
                                    $p_url = '';
                                } else {
                                    $disable = false;
                                    $p_url = base_url() . 'admin/purchase/payment/' . $purchase_info->purchase_id;
                                } ?>
                                <a class="btn btn-sm btn-danger <?= $disable ?>" data-bs-toggle="tooltip" data-bs-placement="top"
                                   href="<?= $p_url ?>"
                                   title="<?= lang('add_payment') ?>"><i
                                            class="fa fa-credit-card"></i> <?= lang('pay') . ' ' . lang('purchase') ?>
                                </a>
                                <?php
                            }
                            if (!empty($all_payments_history)) {
                                ?>
                                <a class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                   href="<?= base_url('admin/purchase/payment_history/' . $purchase_info->purchase_id) ?>"
                                   title="<?= lang('payment_history_for_this_purchase') ?>"><i
                                            class="fa fa fa-money"></i> <?= lang('histories') ?>
                                </a>
                            <?php }
                        }
                        ?>
                        <?php
                        if (!empty($can_edit) && !empty($edited)) { ?>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('clone') . ' ' . lang('purchase') ?>">
                            <a data-bs-toggle="modal" data-bs-target="#myModal" title="<?= lang('clone') . ' ' . lang('purchase') ?>"
                               href="<?= base_url() ?>admin/purchase/clone_purchase/<?= $purchase_info->purchase_id ?>"
                               class="btn btn-sm btn-primary">
                                <i class="fa fa-copy"></i> <?= lang('clone') ?></a>
                            </span>
                            <?php
                        }
                        ?>
                        <div class="btn-group" role="group">
                            <button id="btnGroupVerticalDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?= lang('more_actions') ?>"><?= lang('more_actions') ?><i class="mdi mdi-chevron-down"></i></button>

                            <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                <?php if ($this->purchase_model->get_purchase_cost($purchase_info->purchase_id) > 0) { ?>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/purchase/send_purchase_email/<?= $purchase_info->purchase_id ?>"  title="<?= lang('email_invoice') ?>"><?= lang('email_invoice') ?></a>
                                    
                                    <?php if ($purchase_info->emailed != 'Yes') { ?>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/purchase/change_status/mark_as_sent/<?= $purchase_info->purchase_id ?>"  title="<?= lang('mark_as_sent') ?>"><?= lang('mark_as_sent') ?></a>
                                    <?php } ?>
                                    <?php if ($purchase_info->status != 'received') { ?>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/purchase/change_status/received/<?= $purchase_info->purchase_id ?>" title="<?= lang('mark_as_received') ?>"><?= lang('mark_as_received') ?></a>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/purchase/change_status/pending/<?= $purchase_info->purchase_id ?>"  title="<?= lang('mark_as_pending') ?>"><?= lang('mark_as_pending') ?></a>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/purchase/change_status/ordered/<?= $purchase_info->purchase_id ?>" title="<?= lang('mark_as_ordered') ?>"><?= lang('mark_as_ordered') ?></a>
                                    <?php } ?>
                                    <?php if ($paid_amount <= 0) {
                                        ?>
                                        <?php if ($purchase_info->status != 'Cancelled') { ?>
                                    <a class="dropdown-item"  href="<?= base_url() ?>admin/purchase/change_status/mark_as_cancelled/<?= $purchase_info->purchase_id ?>"  title="<?= lang('mark_as_cancelled') ?>"><?= lang('mark_as_cancelled') ?></a>
                                    <?php } ?>
                                    <?php if ($purchase_info->status == 'Cancelled') { ?>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/purchase/change_status/unmark_as_cancelled/<?= $purchase_info->purchase_id ?>"  title="<?= lang('unmark_as_cancelled') ?>"><?= lang('unmark_as_cancelled') ?></a>
                                    <?php }
                                    }
                                    ?>
                                <?php } ?>

                                <?php
                                if (!empty($can_edit) && !empty($edited)) { ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= base_url() ?>admin/purchase/manage_purchase/<?= $purchase_info->purchase_id ?>"><?= lang('edit') . ' ' . lang('purchase') ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 pull-right">
                        <a  href="<?= base_url() ?>admin/purchase/send_purchase_email/<?= $purchase_info->purchase_id ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('send_email') ?>"  class="btn btn-sm btn-primary pull-right  mr-5">
                            <i class="fa fa-envelope-o"></i>
                        </a>
                        <a onclick="print_purchase('print_purchase')" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title=""  data-original-title="Print" class="mr-sm btn btn-sm btn-danger pull-right  mr-5">
                            <i class="fa fa-print"></i>
                        </a>
                        <a href="<?= base_url() ?>admin/purchase/pdf_purchase/<?= $purchase_info->purchase_id ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="PDF" class="btn btn-sm btn-success pull-right  mr-5">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a href="<?= base_url() ?>admin/purchase/manage_purchase/<?= $purchase_info->purchase_id ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="<?= lang('edit') ?>"  class="btn btn-sm btn-primary pull-right  mr-5">
                            <i class="fa fa-pencil-square-o"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>        
<?php if (strtotime($purchase_info->due_date) < time() AND $payment_status != lang('fully_paid')) {
    $start = strtotime(date('Y-m-d H:i'));
    $end = strtotime($purchase_info->due_date);

    $days_between = ceil(abs($end - $start) / 86400);
    ?>
   
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-warning"></i>
        <?= lang('invoice_overdue') . ' ' . lang('by') . ' ' . $days_between . ' ' . lang('days') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card" id="print_purchase">
            <div class="card-body">
                <div class="invoice-title">
                    <div class="row mb-3">
                        <div class="col-xl-5">
                            <img class="pl-lg" style="width: 233px;height: 120px;"  src="<?= base_url() . config_item('invoice_logo') ?>">
                            <?= super_admin_invoice($purchase_info->companies_id,6) ?>
                        </div>
                        <div class="text-sm-end col-xl-7">
                            <h4 class="font-size-16"><?= lang('purchase') . ' : ' . $purchase_info->reference_no ?></h4>
                            <strong><?= lang('purchase') . ' ' . lang('date') ?>: </strong> <?= display_datetime($purchase_info->purchase_date); ?>
                            <br>
                            <strong><?= lang('due_date') ?>: </strong> <?= display_datetime($purchase_info->due_date); ?>
                            <?php if (!empty($purchase_info->user_id)) { ?>
                                <br><strong><?= lang('sales') . ' ' . lang('agent') ?>: </strong><?php echo fullname($purchase_info->user_id); ?>
                            <?php }
                            if ($payment_status == ('fully_paid')) {
                                $label = "success";
                            } elseif ($payment_status == ('draft')) {
                                $label = "secondary";
                            } elseif ($payment_status == ('cancelled')) {
                                $label = "danger";
                            } elseif ($payment_status == ('partially_paid')) {
                                $label = "warning";
                            } elseif ($purchase_info->emailed == 'Yes') {
                                $label = "info";
                                $payment_status = ('sent');
                            } else {
                                $label = "danger";
                            }
                            if ($purchase_info->status == 'received') {
                                $slabel = "success";
                            } elseif ($purchase_info->status == 'ordered') {
                                $slabel = "warning";
                            } elseif ($purchase_info->status == 'pending') {
                                $slabel = "info";
                            } elseif ($purchase_info->status == 'draft') {
                                $slabel = "secondary";
                            } else {
                                $slabel = "danger";
                            }
                            ?>
                            <br><strong><?= lang('payment_status') ?>: </strong><span
                                    class="badge badge-soft-<?= $label ?>"><?= lang($payment_status) ?></span>
                            <br><strong><?= lang('status') ?>: </strong><span
                                    class="badge badge-soft-<?= $slabel ?>"><?= lang($purchase_info->status) ?></span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <address>
                            <strong><?= lang('our_info') ?>:</strong><br>
                            <h4 class="mb0"><?= config_item('company_legal_name') ?></h4>
                            <?= config_item('company_address') ?>
                            <br><?= config_item('company_city') ?>
                            , <?= config_item('company_zip_code') ?>
                            <br><?= config_item('company_country') ?>
                            <br/><?= lang('phone') ?> : <?= config_item('company_phone') ?>
                            <br/><?= lang('vat_number') ?> : <?= config_item('company_vat') ?>
                        </address>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <address class="mt-2 mt-sm-0">
                            <strong><?= lang('supplier') . ' ' . lang('info') ?>:</strong><br>
                            <?php
                            $supplier_info = get_row('tbl_suppliers', array('supplier_id' => $purchase_info->supplier_id));
                            if (!empty($supplier_info)) {
                                $client_name = $supplier_info->name;
                                $address = $supplier_info->address;
                                $city = $supplier_info->city;
                                $zipcode = $supplier_info->zip_code;
                                $country = $supplier_info->country;
                                $phone = $supplier_info->phone;

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
                            <br><?= lang('phone') ?>: <?= $phone ?>
                            <?php if (!empty($supplier_info->tax)) { ?>
                                <br><?= lang('tax') ?>: <?= $supplier_info->tax ?>
                            <?php } ?>
                        </address>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-nowrap items purchase-items-preview" page-break-inside: auto;>
                        <thead class="bg-items">
                            <tr>
                                <th>#</th>
                                <th><?= lang('items') ?></th>
                                <?php
                                $invoice_view = config_item('invoice_view');
                                if (!empty($invoice_view) && $invoice_view == '2') {
                                    ?>
                                    <th><?= lang('hsn_code') ?></th>
                                <?php } ?>
                                <?php
                                $qty_heading = lang('qty');
                                if (isset($purchase_info) && $purchase_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                    $qty_heading = lang('hours');
                                } else if (isset($purchase_info) && $purchase_info->show_quantity_as == 'qty_hours') {
                                    $qty_heading = lang('qty') . '/' . lang('hours');
                                }
                                ?>
                                <th><?php echo $qty_heading; ?></th>
                                <th class="col-sm-1"><?= lang('price') ?></th>
                                <th class="col-sm-2"><?= lang('tax') ?></th>
                                <th class="col-sm-1"><?= lang('total') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $invoice_items = $this->purchase_model->ordered_items_by_id($purchase_info->purchase_id);
                            if (!empty($invoice_items)) :
                                foreach ($invoice_items as $key => $v_item) :
                                    $item_name = $v_item->item_name ? $v_item->item_name : strip_html_tags($v_item->item_desc);
                                    $item_tax_name = json_decode($v_item->item_tax_name);
                                    ?>
                                    <tr class="sortable item" data-item-id="<?= $v_item->items_id ?>">
                                        <td class="item_no dragger pl-lg"><?= $key + 1 ?></td>
                                        <td><strong class="block"><?= $item_name ?></strong>
                                            <?= strip_html_tags($v_item->item_desc) ?>
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
                            <?= $purchase_info->notes ?>
                        </p>
                    </div>
                    <div class="col-xl-4 pv">
                        <div class="clearfix">
                            <p class="pull-left"><?= lang('sub_total') ?></p>
                            <p class="pull-right mr">
                                <?= display_money($this->purchase_model->calculate_to('invoice_cost', $purchase_info->purchase_id)); ?>
                            </p>
                        </div>
                        <?php if ($purchase_info->discount_total > 0): ?>
                        <div class="clearfix">
                            <p class="pull-left"><?= lang('discount') ?>
                                (<?php echo $purchase_info->discount_percent; ?>
                                %)</p>
                            <p class="pull-right mr">
                                <?= display_money($this->purchase_model->calculate_to('discount', $purchase_info->purchase_id)); ?>
                            </p>
                        </div>
                        <?php endif ?>
                        <?php
                        $tax_info = json_decode($purchase_info->total_tax);
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
                                <?php }
                            }
                        } ?>
                        <?php if ($tax_total > 0): ?>
                            <div class="clearfix">
                                <p class="pull-left"><?= lang('total') . ' ' . lang('tax') ?></p>
                                <p class="pull-right mr">
                                    <?= display_money($tax_total); ?>
                                </p>
                            </div>
                        <?php endif ?>
                        <?php if ($purchase_info->adjustment > 0): ?>
                            <div class="clearfix">
                                <p class="pull-left"><?= lang('adjustment') ?></p>
                                <p class="pull-right mr">
                                    <?= display_money($purchase_info->adjustment); ?>
                                </p>
                            </div>
                        <?php endif ?>

                        <div class="clearfix">
                            <p class="pull-left"><?= lang('total') ?></p>
                            <p class="pull-right mr">
                                <?= display_money($this->purchase_model->calculate_to('total', $purchase_info->purchase_id), $currency->symbol); ?>
                            </p>
                        </div>

                        <?php

                        if ($paid_amount > 0) {
                            $total = lang('total_due');
                            if ($paid_amount > 0) {
                                $text = 'text-danger';
                                ?>
                                <div class="clearfix">
                                    <p class="pull-left"><?= lang('paid_amount') ?> </p>
                                    <p class="pull-right mr">
                                        <?= display_money($paid_amount, $currency->symbol); ?>
                                    </p>
                                </div>
                            <?php } else {
                                $text = '';
                            } ?>
                            <div class="clearfix">
                                <p class="pull-left h3 <?= $text ?>"><?= $total ?></p>
                                <p class="pull-right mr h3"><?= display_money($this->purchase_model->calculate_to('purchase_due', $purchase_info->purchase_id), $currency->symbol); ?></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt">
    <div class="col-lg-12">
        <?= !empty($invoice_view) && $invoice_view > 0 ? $this->gst->summary($invoice_items) : ''; ?>
    </div>
</div>
<?php $all_payment_info = $this->db->where('purchase_id', $purchase_info->purchase_id)->get('tbl_purchase_payments')->result();
if (!empty($all_payment_info)) { ?>
<div class="row mt">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"> <?= lang('payment') . ' ' . lang('details') ?></h4>
                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th><?= lang('transaction_id') ?></th>
                                <th><?= lang('payment_date') ?></th>
                                <th><?= lang('amount') ?></th>
                                <th><?= lang('payment_mode') ?></th>
                                <th><?= lang('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($all_payment_info as $v_payments_info) {
                                $payment_methods = $this->purchase_model->check_by(array('payment_methods_id' => $v_payments_info->payment_method), 'tbl_payment_methods');
                                ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url() ?>admin/purchase/payments_details/<?= $v_payments_info->payments_id ?>"> <?= $v_payments_info->trans_id; ?></a>
                                </td>
                                <td>
                                    <a href="<?= base_url() ?>admin/purchase/payments_details/<?= $v_payments_info->payments_id ?>"><?= display_datetime($v_payments_info->payment_date); ?></a>
                                </td>
                                <td><?= display_money($v_payments_info->amount, $currency->symbol) ?></td>
                                <td><?= !empty($payment_methods->method_name) ? $payment_methods->method_name : '-'; ?></td>
                                <?php if (!empty($edited) || !empty($deleted)) { ?>
                                    <td>
                                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                            <?= btn_edit('admin/purchase/all_payments/' . $v_payments_info->payments_id) ?>
                                        <?php }
                                        if (!empty($can_delete) && !empty($deleted)) {
                                            ?>
                                            <?= btn_delete('admin/purchase/delete_payment/' . $v_payments_info->payments_id) ?>
                                        <?php } ?>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top"
                                           href="<?= base_url() ?>admin/purchase/send_payment/<?= $v_payments_info->payments_id . '/' . $v_payments_info->amount ?>"
                                           title="<?= lang('send_email') ?>"
                                           class="btn btn-sm btn-success">
                                            <i class="fa fa-envelope"></i> </a>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top"
                                           href="<?= base_url() ?>admin/purchase/payments_pdf/<?= $v_payments_info->payments_id ?>"
                                           title="<?= lang('pdf') ?>"
                                           class="btn btn-sm btn-warning">
                                            <i class="fa fa-file-pdf-o"></i></a>
                                    </td>
                                <?php } ?>
                            </tr>
                            <?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php include_once 'skote_assets/js/sales.php'; ?>

<script type="text/javascript">
    $(document).ready(function () {
        init_items_sortable(true);
        <?php
        $remove_purchase = $this->session->userdata('remove_purchase');
        if (!empty($remove_purchase)) {
        ?>
        if (get('purchaseItems')) {
            remove('purchaseItems');
        }
        <?php
        $this->session->unset_userdata('remove_purchase');
        }
        ?>
    });

    function print_purchase(print_purchase) {
        var printContents = document.getElementById(print_purchase).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
<style type="text/css">
    .dragger {
        background: url(<?= base_url()?>skote_assets/images/dragger.png) 0px 11px no-repeat;
        cursor: pointer;
    }

    .table > tbody > tr > td {
        vertical-align: initial;
    }
</style>
