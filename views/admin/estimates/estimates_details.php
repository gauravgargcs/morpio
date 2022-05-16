<?= message_box('success') ?>
<?= message_box('error');
$edited = can_action('14', 'edited');
$deleted = can_action('14', 'deleted');
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
                    <label class="pull-left col-form-label col-xl-2"><?= lang('copy_unique_url') ?></label>
                    <div class="col-xl-10">
                        <input value="<?= base_url() ?>frontend/estimates/<?= url_encode($estimates_info->estimates_id); ?>" type="text" id="foo" class="form-control"/>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-xl-10">
                        <?php
                        $where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $estimates_info->estimates_id, 'module_name' => 'estimates');
                        $check_existing = $this->estimates_model->check_by($where, 'tbl_pinaction');
                        if (!empty($check_existing)) {
                            $url = 'remove_todo/' . $check_existing->pinaction_id;
                            $btn = 'danger';
                            $title = lang('remove_todo');
                        } else {
                            $url = 'add_todo_list/estimates/' . $estimates_info->estimates_id;
                            $btn = 'warning';
                            $title = lang('add_todo_list');
                        }

                        $can_edit = $this->estimates_model->can_action('tbl_estimates', 'edit', array('estimates_id' => $estimates_info->estimates_id));
                        $can_delete = $this->estimates_model->can_action('tbl_estimates', 'delete', array('estimates_id' => $estimates_info->estimates_id));
                        $client_info = $this->estimates_model->check_by(array('client_id' => $estimates_info->client_id), 'tbl_client');
                        if (!empty($client_info)) {
                            $currency = $this->estimates_model->client_currency_sambol($estimates_info->client_id);
                            $client_lang = $client_info->language;
                        } else {
                            $client_lang = 'english';
                            $currency = $this->estimates_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                        }
                        unset($this->lang->is_loaded[5]);
                        $language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
                        ?>

                        <?php if (!empty($can_edit) && !empty($edited)) { ?>

                            <a data-bs-toggle="modal" data-bs-target="#myModal_lg"
                               href="<?= base_url() ?>admin/estimates/insert_items/<?= $estimates_info->estimates_id ?>"
                               title="<?= lang('item_quick_add') ?>" class="btn btn-sm btn-primary">
                                <i class="fa fa-pencil text-white"></i> <?= lang('add_items') ?></a>

                            <?php if ($estimates_info->show_client == 'Yes') { ?>
                            <a class="btn btn-sm btn-success"
                               href="<?= base_url() ?>admin/estimates/change_status/hide/<?= $estimates_info->estimates_id ?>"
                               title="<?= lang('hide_to_client') ?>"><i class="fa fa-eye-slash"></i> <?= lang('hide_to_client') ?>
                                </a><?php } else { ?>
                            <a class="btn btn-sm btn-warning"
                               href="<?= base_url() ?>admin/estimates/change_status/show/<?= $estimates_info->estimates_id ?>"
                               title="<?= lang('show_to_client') ?>"><i class="fa fa-eye"></i> <?= lang('show_to_client') ?>
                                </a><?php } ?>

                            <a data-bs-toggle="modal" data-bs-target="#myModal_extra_lg" data-original-title="<?= lang('convert_to_invoice') ?>"
                               data-bs-toggle="tooltip" data-bs-placement="top"
                               class="btn btn-sm btn-primary <?php
                               if ($estimates_info->invoiced == 'Yes' OR $estimates_info->client_id == '0') {
                                   echo "disabled";
                               }
                               ?>" href="<?= base_url() ?>admin/estimates/convert_to_invoice/<?= $estimates_info->estimates_id ?>"
                               title="<?= lang('convert_to_invoice') ?>">
                                <?= lang('convert_to_invoice') ?></a>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('clone') . ' ' . lang('estimate') ?>">
                            <a data-bs-toggle="modal" data-bs-target="#myModal" title="<?= lang('clone') . ' ' . lang('estimate') ?>"
                               href="<?= base_url() ?>admin/estimates/clone_estimate/<?= $estimates_info->estimates_id ?>"
                               class="btn btn-sm btn-secondary">
                                <i class="fa fa-copy"></i> <?= lang('clone') ?></a>
                            </span>
                            <?php
                        }
                        ?>

                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                            <div class="btn-group" role="group">
                                <button id="btnGroupVerticalDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?= lang('more_actions') ?>"><?= lang('more_actions') ?><i class="mdi mdi-chevron-down"></i></button>

                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">

                                    <a class="dropdown-item" href="<?= base_url() ?>admin/estimates/index/email_estimates/<?= $estimates_info->estimates_id ?>"
                                           data-bs-toggle="ajaxModal"><?= lang('email_estimate') ?></a>

                                    <a class="dropdown-item" href="<?= base_url() ?>admin/estimates/index/estimates_history/<?= $estimates_info->estimates_id ?>"><?= lang('estimate_history') ?></a>
                
                                    <?php if ($estimates_info->status == 'expired' || $estimates_info->status == 'sent' || $estimates_info->status == 'cancelled' || $estimates_info->status == 'draft') { ?>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/estimates/change_status/pending/<?= $estimates_info->estimates_id ?>" title="<?= lang('mark_as_sent') ?>"><?= lang('mark_as_pending') ?></a>
                                    <?php } ?>
                                    <?php if ($estimates_info->status == 'draft') { ?>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/estimates/change_status/draft/<?= $estimates_info->estimates_id ?>"
                                               title="<?= lang('unmark_as_draft') ?>"><?= lang('mark_as_draft') ?></a>
                                    <?php } ?>
                                    <?php if ($estimates_info->status != 'sent' || $estimates_info->status == 'expired') { ?>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/estimates/change_status/sent/<?= $estimates_info->estimates_id ?>"
                                               title="<?= lang('mark_as_sent') ?>"><?= lang('mark_as_sent') ?></a>
                                    <?php } ?>
                                    <?php if ($estimates_info->status == 'pending' || $estimates_info->status == 'sent') { ?>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/estimates/change_status/expired/<?= $estimates_info->estimates_id ?>"
                                               title="<?= lang('mark_as_sent') ?>"><?= lang('mark_as_expired') ?></a>
                                    <?php } ?>
                                    <?php if ($estimates_info->status != 'cancelled') { ?>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/estimates/change_status/cancelled/<?= $estimates_info->estimates_id ?>"
                                               title="<?= lang('mark_as_cancelled') ?>"><?= lang('mark_as_cancelled') ?></a>
                                    <?php } ?>
                                    <?php if ($estimates_info->status == 'cancelled') { ?>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/estimates/change_status/draft/<?= $estimates_info->estimates_id ?>"
                                               title="<?= lang('unmark_as_cancelled') ?>"><?= lang('unmark_as_cancelled') ?></a>
                                    <?php } ?>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/estimates/change_status/declined/<?= $estimates_info->estimates_id ?>"><?= lang('declined') ?></a>
                                    
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/estimates/change_status/accepted/<?= $estimates_info->estimates_id ?>"><?= lang('accepted') ?></a>
            
                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/estimates/index/edit_estimates/<?= $estimates_info->estimates_id ?>"><?= lang('edit_estimate') ?></a>
            
                                    <?php } ?>

                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($estimates_info->invoiced == 'Yes') {
                            $invoice_info = $this->db->where('invoices_id', $estimates_info->invoices_id)->get('tbl_invoices')->row();
                            if (!empty($invoice_info)) {
                                ?>
                                <a href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $estimates_info->invoices_id ?>"
                                   class="btn btn-sm btn-primary">
                                    <i class="fa fa-hand-o-right"></i> <?= $invoice_info->reference_no ?></a>
                            <?php }
                        } ?>
                        <?php
                        $notified_reminder = count(get_result('tbl_reminders', array('module' => 'estimate', 'module_id' => $estimates_info->estimates_id, 'notified' => 'No')));
                        ?>
                        <a class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#myModal_lg"
                           href="<?= base_url() ?>admin/invoice/reminder/estimate/<?= $estimates_info->estimates_id ?>"><?= lang('reminder') ?>
                            <?= !empty($notified_reminder) ? '<span class="badge ml-sm" style="border-radius: 50%">' . $notified_reminder . '</span>' : '' ?>
                        </a>

                        <?php
                        if (!empty($estimates_info->project_id)) {
                            $project_info = $this->db->where('project_id', $estimates_info->project_id)->get('tbl_project')->row();
                            ?>
                            <strong><?= lang('project') ?>:</strong>
                            <a
                                href="<?= base_url() ?>admin/projects/project_details/<?= $estimates_info->project_id ?>"
                                class="">
                                <?= $project_info->project_name ?>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="col-xl-2 pull-right">
                        <a
                            href="<?= base_url() ?>admin/estimates/send_estimates_email/<?= $estimates_info->estimates_id . '/' . true ?>"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('send_email') ?>"
                            class="btn btn-sm btn-primary pull-right">
                            <i class="fa fa-envelope-o"></i>
                        </a>
                        <a onclick="print_estimates('print_estimates')" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title=""
                           data-original-title="Print" class="mr-5 btn btn-sm btn-danger pull-right">
                            <i class="fa fa-print"></i>
                        </a>

                        <a href="<?= base_url() ?>admin/estimates/pdf_estimates/<?= $estimates_info->estimates_id ?>"
                           data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="PDF"
                           class="btn btn-sm btn-success pull-right mr-5">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $title ?>"
                           href="<?= base_url() ?>admin/projects/<?= $url ?>"
                           class="mr-5 btn pull-right  btn-sm  btn-<?= $btn ?>"><i class="fa fa-thumb-tack"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Start Display Details -->
<?php
if (strtotime($estimates_info->due_date) < time() AND $estimates_info->status == 'draft') {
    $start = strtotime(date('Y-m-d'));
    $end = strtotime($estimates_info->due_date);

    $days_between = ceil(abs($end - $start) / 86400);
    ?>
    <div class="alert bg-danger-light hidden-print">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <i class="fa fa-warning"></i>
        <?= lang('estimate_overdue') . ' ' . lang('by') . ' ' . $days_between . ' ' . lang('days') ?>
    </div>
    <?php
}
?>
<!-- Main content -->
<div class="row">
    <div class="col-lg-12">
        <div class="card" id="print_estimates">
            <div class="card-body">
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
                            <h4 class="font-size-16"><?= lang('estimates') . ' : ' . $estimates_info->reference_no ?></h4>
                            <strong><?= $language_info['estimate_date'] ?>:</strong> <?= display_datetime($estimates_info->estimate_date); ?>
                            <br>
                            <strong><?= $language_info['due_date'] ?>:</strong> <?= display_datetime($estimates_info->due_date); ?>
                            <?php if (!empty($estimates_info->user_id)) { ?>
                            <br><strong><?= lang('sales') . ' ' . lang('agent') ?>:</strong><?php echo fullname($estimates_info->user_id); ?>
                                <?php
                            }
                            if ($estimates_info->status == 'accepted') {
                                $label = 'success';
                            } else {
                                $label = 'danger';
                            }
                            ?>
                            <br><strong><?= $language_info['estimate_status'] ?>:</strong> 
                            <span class="badge badge-soft-<?= $label ?>"><?= lang($estimates_info->status) ?></span>

                            <?php $show_custom_fields = custom_form_label(10, $estimates_info->estimates_id);
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
                                <br><?= (config_item('company_city_' . $client_lang) ? config_item('company_city_' . $client_lang) : config_item('company_city')) ?>      , <?= config_item('company_zip_code') ?>
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
                        <table class="table table-nowrap items invoice-items-preview" page-break-inside: auto;>
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
                                    if (isset($estimates_info) && $estimates_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                        $qty_heading = lang('hours');
                                    } else if (isset($estimates_info) && $estimates_info->show_quantity_as == 'qty_hours') {
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
                                $invoice_items = $this->estimates_model->ordered_items_by_id($estimates_info->estimates_id);

                                if (!empty($invoice_items)) :
                                    foreach ($invoice_items as $key => $v_item) :
                                        $item_name = $v_item->item_name ? $v_item->item_name : $v_item->item_desc;
                                        $item_tax_name = json_decode($v_item->item_tax_name);
                                        ?>
                                        <tr class="sortable item" data-item-id="<?= $v_item->estimate_items_id ?>">
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
                                <?= strip_tags($estimates_info->notes); ?>
                            </p>
                        </div>
                        <div class="col-xl-4 pv">
                            <div class="clearfix">
                                <p class="pull-left"><?= $language_info['sub_total'] ?></p>
                                <p class="pull-right mr">
                                    <?= display_money($this->estimates_model->estimate_calculation('estimate_cost', $estimates_info->estimates_id)); ?>
                                </p>
                            </div>
                            <?php if ($estimates_info->discount_total > 0): ?>
                                <div class="clearfix">
                                    <p class="pull-left"><?= $language_info['discount'] ?>
                                        (<?php echo $estimates_info->discount_percent; ?>
                                        %)</p>
                                    <p class="pull-right mr">
                                        <?= display_money($this->estimates_model->estimate_calculation('discount', $estimates_info->estimates_id)); ?>
                                    </p>
                                </div>
                            <?php endif ?>
                            <?php
                            $tax_info = json_decode($estimates_info->total_tax);
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
                                    <p class="pull-left"><?= $language_info['total'] . ' ' . $language_info['tax'] ?></p>
                                    <p class="pull-right mr">
                                        <?= display_money($tax_total); ?>
                                    </p>
                                </div>
                            <?php endif ?>
                            <?php if ($estimates_info->adjustment > 0): ?>
                                <div class="clearfix">
                                    <p class="pull-left"><?= $language_info['adjustment'] ?></p>
                                    <p class="pull-right mr">
                                        <?= display_money($estimates_info->adjustment); ?>
                                    </p>
                                </div>
                            <?php endif ?>

                            <div class="clearfix">
                                <p class="pull-left"><?= $language_info['total'] ?></p>
                                <p class="pull-right mr">
                                    <?php
                                    $total_amount = $this->estimates_model->estimate_calculation('total', $estimates_info->estimates_id);
                                    echo display_money($total_amount, $currency->symbol);
                                    ?>
                                </p>
                            </div>
                        </div>
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
<style type="text/css">
    .dragger {
        background: url(../../../../skote_assets/images/dragger.png) 0px 11px no-repeat;
        cursor: pointer;
    }

    .table > tbody > tr > td {
        vertical-align: initial;
    }
</style>
        
<?php include_once 'skote_assets/js/sales.php'; ?>
<script type="text/javascript">
    $(document).ready(function () {
        init_items_sortable(true);
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
    });
    function print_estimates(print_estimates) {
        var printContents = document.getElementById(print_estimates).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>