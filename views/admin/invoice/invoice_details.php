<?= message_box('success') ?>
<?= message_box('error');
$edited = can_action('13', 'edited');
$deleted = can_action('13', 'deleted');
$paid_amount = $this->invoice_model->calculate_to('paid_amount', $invoice_info->invoices_id);
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
                        <input value="<?= base_url() ?>frontend/view_invoice/<?= url_encode($invoice_info->invoices_id); ?>" type="text" id="foo" class="form-control"/>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-xl-10">
                        <?php
                        $payment_status = $this->invoice_model->get_payment_status($invoice_info->invoices_id);

                        $where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $invoice_info->invoices_id, 'module_name' => 'invoice');
                        $check_existing = $this->invoice_model->check_by($where, 'tbl_pinaction');
                        if (!empty($check_existing)) {
                            $url = 'remove_todo/' . $check_existing->pinaction_id;
                            $btn = 'danger';
                            $title = lang('remove_todo');
                        } else {
                            $url = 'add_todo_list/invoice/' . $invoice_info->invoices_id;
                            $btn = 'warning';
                            $title = lang('add_todo_list');
                        }

                        $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');
                        if (!empty($client_info)) {
                            $currency = $this->invoice_model->client_currency_sambol($invoice_info->client_id);
                            $client_lang = $client_info->language;
                        } else {
                            $client_lang = 'english';
                            $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                        }

                        unset($this->lang->is_loaded[5]);
                        $language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
                        ?>
                        <?php $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $invoice_info->invoices_id));
                        if (!empty($can_edit) && !empty($edited)) { ?>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('from_items') ?>">
                            <a data-bs-toggle="modal" data-bs-target="#myModal_lg"
                               href="<?= base_url() ?>admin/invoice/insert_items/<?= $invoice_info->invoices_id ?>"
                               title="<?= lang('item_quick_add') ?>" class="btn btn-sm btn-primary">
                                <i class="fa fa-pencil text-white"></i> <?= lang('add_items') ?></a>
                                </span>
                        <?php }
                        ?>
                        <?php
                        if (!empty($can_edit) && !empty($edited)) { ?>
                            <?php if ($invoice_info->show_client == 'Yes') { ?>
                            <a class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top"
                               href="<?= base_url() ?>admin/invoice/change_status/hide/<?= $invoice_info->invoices_id ?>"
                               title="<?= lang('hide_to_client') ?>"><i class="fa fa-eye-slash"></i> <?= lang('hide_to_client') ?>
                                </a><?php } else { ?>
                            <a class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="top"
                               href="<?= base_url() ?>admin/invoice/change_status/show/<?= $invoice_info->invoices_id ?>"
                               title="<?= lang('show_to_client') ?>"><i class="fa fa-eye"></i> <?= lang('show_to_client') ?>
                                </a><?php }
                        } ?>

                        <?php
                        if (!empty($can_edit) && !empty($edited)) { ?>
                            <?php if ($this->invoice_model->get_invoice_cost($invoice_info->invoices_id) > 0) {
                                ?>
                                <?php if ($invoice_info->status == 'Cancelled') {
                                    $disable = 'disabled';
                                    $p_url = '';
                                } else {
                                    $disable = false;
                                    $p_url = base_url() . 'admin/invoice/manage_invoice/payment/' . $invoice_info->invoices_id;
                                } ?>
                                <a class="btn btn-sm btn-danger <?= $disable ?>" data-bs-toggle="tooltip" data-bs-placement="top"
                                   href="<?= $p_url ?>"
                                   title="<?= lang('add_payment') ?>"><i class="fa fa-credit-card"></i> <?= lang('pay_invoice') ?>
                                </a>
                                <?php
                            }
                            if (!empty($all_payments_history)) {
                                ?>
                                <a class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                   href="<?= base_url('admin/invoice/manage_invoice/payment_history/' . $invoice_info->invoices_id) ?>"
                                   title="<?= lang('payment_history_for_this_invoice') ?>"><i
                                            class="fa fa fa-money"></i> <?= lang('histories') ?>
                                </a>
                            <?php }
                        }
                        ?>
                        <?php
                        if (!empty($can_edit) && !empty($edited)) { ?>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('clone') . ' ' . lang('invoice') ?>">
                            <a data-bs-toggle="modal" data-bs-target="#myModal" title="<?= lang('clone') . ' ' . lang('invoice') ?>"
                               href="<?= base_url() ?>admin/invoice/clone_invoice/<?= $invoice_info->invoices_id ?>"
                               class="btn btn-sm btn-secondary">
                                <i class="fa fa-copy"></i> <?= lang('clone') ?></a>
                            </span>
                            <?php
                        }
                        ?>

                        <div class="btn-group" role="group">
                            <button id="btnGroupVerticalDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?= lang('more_actions') ?>"><?= lang('more_actions') ?><i class="mdi mdi-chevron-down"></i></button>

                            <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                <?php if ($this->invoice_model->get_invoice_cost($invoice_info->invoices_id) > 0) { ?>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/email_invoice/<?= $invoice_info->invoices_id ?>"  title="<?= lang('email_invoice') ?>"><?= lang('email_invoice') ?></a>
                                    
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/send_reminder/<?= $invoice_info->invoices_id ?>" title="<?= lang('send_reminder') ?>"><?= lang('send_reminder') ?></a>
                                    <?php if (strtotime($invoice_info->due_date) < time() AND $payment_status != lang('fully_paid')) { ?>
                                        <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/send_overdue/<?= $invoice_info->invoices_id ?>" title="<?= lang('send_invoice_overdue') ?>"><?= lang('send_invoice_overdue') ?></a>
                                    <?php } ?>
                                    <?php if ($invoice_info->emailed != 'Yes') { ?>
                                        <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/change_invoice_status/mark_as_sent/<?= $invoice_info->invoices_id ?>" title="<?= lang('mark_as_sent') ?>"><?= lang('mark_as_sent') ?></a>
                                    <?php }
                                    if ($paid_amount <= 0) {  ?>
                                        <?php if ($invoice_info->status != 'Cancelled') { ?>
                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/change_invoice_status/mark_as_cancelled/<?= $invoice_info->invoices_id ?>" title="<?= lang('mark_as_cancelled') ?>"><?= lang('mark_as_cancelled') ?></a>
                                        <?php } ?>
                                        <?php if ($invoice_info->status == 'Cancelled') { ?>
                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/change_invoice_status/unmark_as_cancelled/<?= $invoice_info->invoices_id ?>" title="<?= lang('unmark_as_cancelled') ?>"><?= lang('unmark_as_cancelled') ?></a>
                                        <?php }
                                    } ?>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_history/<?= $invoice_info->invoices_id ?>"><?= lang('invoice_history') ?></a>
                                    <?php 
                                } ?>
                                <?php
                                if (!empty($can_edit) && !empty($edited)) { ?>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/create_invoice/<?= $invoice_info->invoices_id ?>"><?= lang('edit_invoice') ?></a>
                                <?php } ?>
                            </div>
                        </div>

                        <?php
                        if (!empty($can_edit) && !empty($edited)) { ?>
                            <?php if ($invoice_info->recurring == 'Yes') { ?>
                                <a onclick="return confirm('<?= lang('stop_recurring_alert') ?>')" class="btn btn-sm btn-warning"
                                   href="<?= base_url() ?>admin/invoice/stop_recurring/<?= $invoice_info->invoices_id ?>"
                                   title="<?= lang('stop_recurring') ?>"><i class="fa fa-retweet"></i> <?= lang('stop_recurring') ?>
                                </a>
                            <?php }
                        } ?>
                        <?php
                        if (!empty($invoice_info->project_id)) {
                            $project_info = $this->db->where('project_id', $invoice_info->project_id)->get('tbl_project')->row();
                            ?>
                            <strong><?= lang('project') ?>:</strong>
                            <a
                                    href="<?= base_url() ?>admin/projects/project_details/<?= $invoice_info->project_id ?>"
                                    class="">
                                <?= $project_info->project_name ?>
                            </a>
                        <?php }
                        ?>

                        <?php
                        $notified_reminder = count($this->db->where(array('module' => 'invoice', 'module_id' => $invoice_info->invoices_id, 'notified' => 'No'))->get('tbl_reminders')->result());
                        ?>
                        <a class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#myModal_lg"
                           href="<?= base_url() ?>admin/invoice/reminder/invoice/<?= $invoice_info->invoices_id ?>"><?= lang('reminder') ?>
                            <?= !empty($notified_reminder) ? '<span class="badge ml-sm" style="border-radius: 50%">' . $notified_reminder . '</span>' : '' ?>
                        </a>
                    </div>
                    <div class="col-xl-2 pull-right">
                        <a  href="<?= base_url() ?>admin/invoice/send_invoice_email/<?= $invoice_info->invoices_id . '/' . true ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('send_email') ?>" class="btn btn-sm btn-primary pull-right mr-5"><i class="fa fa-envelope-o"></i></a>

                        <a onclick="print_invoice('print_invoice')" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="Print" class="btn btn-sm btn-danger pull-right mr-5"><i class="fa fa-print"></i></a>

                        <a href="<?= base_url() ?>admin/invoice/pdf_invoice/<?= $invoice_info->invoices_id ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="PDF" class="btn btn-sm btn-success pull-right mr-5"><i class="fa fa-file-pdf-o"></i></a>

                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $title ?>" href="<?= base_url() ?>admin/projects/<?= $url ?>"
                           class="mr-5 btn pull-right  btn-sm  btn-<?= $btn ?>"><i class="fa fa-thumb-tack"></i></a>
                    </div>
                </div>
            </div>
        </div>
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

<?php if (strtotime($invoice_info->due_date) < time() AND $payment_status != lang('fully_paid')) {
    $start = strtotime(date('Y-m-d'));
    $end = strtotime($invoice_info->due_date);

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
        <div class="card" id="print_invoice">
            <div class="card-body">
                <div class="invoice-title">
                    <div class="row mb-3">
                        <div class="col-xl-5">
                            <img src="<?= base_url() . config_item('invoice_logo') ?>" alt="logo" style="    max-width: 350px;" />
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
                            <h4 class="font-size-16"><?= lang('invoice') . ' : ' . $invoice_info->reference_no ?></h4>
                            <strong><?= $language_info['invoice_date'] ?>:</strong> <?= display_datetime($invoice_info->invoice_date); ?>
                            <br>
                            <strong><?= $language_info['due_date'] ?>:</strong> <?= display_datetime($invoice_info->due_date); ?>
                            <?php if (!empty($invoice_info->user_id)) { ?>
                            <br>
                            <strong><?= lang('sales') . ' ' . lang('agent') ?>:</strong> <?php echo fullname($invoice_info->user_id); ?>
                            <?php }
                            if ($payment_status == lang('fully_paid')) {
                                $label = "success";
                            } elseif ($payment_status == lang('draft')) {
                                $label = "default";
                                $text = lang('status_as_draft');
                            } elseif ($payment_status == lang('cancelled')) {
                                $label = "danger";
                            } elseif ($payment_status == lang('partially_paid')) {
                                $label = "warning";
                            } elseif ($invoice_info->emailed == 'Yes') {
                                $label = "info";
                                $payment_status = lang('sent');
                            } else {
                                $label = "danger";
                            }
                            ?>
                            <br><strong><?= $language_info['payment_status'] ?>:</strong> <span class="badge badge-soft-<?= $label ?>"><?= $payment_status ?></span>
                            <?php if (!empty($text)) { ?>
                            <br>
                            <p style="padding: 15px;margin-bottom: 20px;border: 1px solid transparent;border-radius: 4px;;background: color: #8a6d3b;background-color: #fcf8e3;border-color: #faebcc;"><?= $text ?></p>
                            <?php } ?>
                            <?php $show_custom_fields = custom_form_label(9, $invoice_info->invoices_id);

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
                            <br/><?=lang('Company ID') ?> : <?= config_item('company_vat') ?>
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
                                if (isset($invoice_info) && $invoice_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                    $qty_heading = lang('hours');
                                } else if (isset($invoice_info) && $invoice_info->show_quantity_as == 'qty_hours') {
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
                            $invoice_items = $this->invoice_model->ordered_items_by_id($invoice_info->invoices_id);

                            if (!empty($invoice_items)) :
                                foreach ($invoice_items as $key => $v_item) :
                                    $item_name = $v_item->item_name ? $v_item->item_name : $v_item->item_desc;
                                    $item_tax_name = json_decode($v_item->item_tax_name);
                                    ?>
                                    <tr class="sortable item" data-item-id="<?= $v_item->items_id ?>">
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
                            <?= $invoice_info->notes ?>
                        </p>
                    </div>
                    <div class="col-xl-4 pv">
                        <div class="clearfix">
                            <p class="pull-left"><?= $language_info['sub_total'] ?></p>
                            <p class="pull-right mr">
                                <?= display_money($this->invoice_model->calculate_to('invoice_cost', $invoice_info->invoices_id)); ?>
                            </p>
                        </div>
                        <?php if ($invoice_info->discount_total > 0): ?>
                            <div class="clearfix">
                                <p class="pull-left"><?= $language_info['discount'] ?>
                                    (<?php echo $invoice_info->discount_percent; ?>
                                    %)</p>
                                <p class="pull-right mr">
                                    <?= display_money($this->invoice_model->calculate_to('discount', $invoice_info->invoices_id)); ?>
                                </p>
                            </div>
                        <?php endif ?>
                        <?php
                        $tax_info = json_decode($invoice_info->total_tax);
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
                        <?php if ($invoice_info->adjustment > 0): ?>
                            <div class="clearfix">
                                <p class="pull-left"><?= $language_info['adjustment'] ?></p>
                                <p class="pull-right mr">
                                    <?= display_money($invoice_info->adjustment); ?>
                                </p>
                            </div>
                        <?php endif ?>

                        <div class="clearfix">
                            <p class="pull-left"><?= $language_info['total'] ?></p>
                            <p class="pull-right mr">
                                <?= display_money($this->invoice_model->calculate_to('total', $invoice_info->invoices_id), $currency->symbol); ?>
                            </p>
                        </div>

                        <?php

                        if ($paid_amount > 0) {
                            $total = $language_info['total_due'];
                            if ($paid_amount > 0) {
                                $text = 'text-danger';
                                ?>
                                <div class="clearfix">
                                    <p class="pull-left"><?= $language_info['paid_amount'] ?> </p>
                                    <p class="pull-right mr">
                                        <?= display_money($paid_amount, $currency->symbol); ?>
                                    </p>
                                </div>
                            <?php } else {
                                $text = '';
                            } ?>
                            <div class="clearfix">
                                <p class="pull-left h3 <?= $text ?>"><?= $total ?></p>
                                <p class="pull-right mr h3"><?= display_money($this->invoice_model->calculate_to('invoice_due', $invoice_info->invoices_id), $currency->symbol); ?></p>
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

<?php $all_payment_info = $this->db->where('invoices_id', $invoice_info->invoices_id)->get('tbl_payments')->result();
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
                            $payment_methods = $this->invoice_model->check_by(array('payment_methods_id' => $v_payments_info->payment_method), 'tbl_payment_methods');
                            ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url() ?>admin/invoice/manage_invoice/payments_details/<?= $v_payments_info->payments_id ?>"> <?= $v_payments_info->trans_id; ?></a>
                                </td>
                                <td>
                                    <a href="<?= base_url() ?>admin/invoice/manage_invoice/payments_details/<?= $v_payments_info->payments_id ?>"><?= strftime(config_item('date_format'), strtotime($v_payments_info->payment_date)); ?></a>
                                </td>
                                <td><?= display_money($v_payments_info->amount, $currency->symbol) ?></td>
                                <td><?= !empty($payment_methods->method_name) ? $payment_methods->method_name : '-'; ?></td>
                                <?php if (!empty($edited) || !empty($deleted)) { ?>
                                    <td>
                                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                            <?= btn_edit('admin/invoice/all_payments/' . $v_payments_info->payments_id) ?>
                                        <?php }
                                        if (!empty($can_delete) && !empty($deleted)) {
                                            ?>
                                            <?= btn_delete('admin/invoice/delete/delete_payment/' . $v_payments_info->payments_id) ?>
                                        <?php } ?>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top"
                                           href="<?= base_url() ?>admin/invoice/send_payment/<?= $v_payments_info->payments_id . '/' . $v_payments_info->amount ?>"
                                           title="<?= lang('send_email') ?>"
                                           class="btn btn-sm btn-success">
                                            <i class="fa fa-envelope"></i> </a>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top"
                                           href="<?= base_url() ?>admin/invoice/payments_pdf/<?= $v_payments_info->payments_id ?>"
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
        if ($this->session->userdata('remove_pos')) {
        ?>
        if (get('posItems')) {
            remove('posItems');
        }
        <?php
        $this->session->unset_userdata('remove_pos');
        }
        ?> <?php
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

    function print_invoice(print_invoice) {
        var printContents = document.getElementById(print_invoice).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>