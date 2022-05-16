<div class="modal-header">
    <h5 class="modal-title"><?= lang('items') . ' ' . lang('details') . ' ' . lang('of') . ' ' . $items_info->item_name ?></h5>
    <button type="button" class="pull-right btn btn-danger btn-sm mr ml" onclick="print_items('print_items')">
        <i class="fa fa-print"></i>
    </button>
    <a class="pull-right btn btn-primary btn-sm mr" href="<?= base_url('admin/items/items_list/' . $items_info->saved_items_id)?>"><i class="fa fa-pencil-square-o"></i></a>    
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

</div>
<div class="modal-body wrap-modal wrap">
    <?php
    $group = $this->db->where('customer_group_id', $items_info->customer_group_id)->get('tbl_customer_group')->row();
    $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
    $manufacturer_info = get_row('tbl_manufacturer', array('manufacturer_id' => $items_info->manufacturer_id));
    ?>
    <div class="row">
        <div class="col-xs-4">
            <img id="pr-image" src="<?= product_image($items_info->saved_items_id) ?>" alt="<?= $items_info->item_name ?>" class="img-fluid img-thumbnail">
        </div>
        <div class="col-xs-8">
            <div class="table-responsive">
                <table class="table table-striped ">
                    <tbody>
                    <?php super_admin_pdf($items_info->companies_id,'','text-align:text-right') ?>
                    <tr class="hidden-print">
                        <td class="col-xs-4 text-right"><strong><?= lang('barcode') ?> : </strong></td>
                        <td class="col-xs-8"><?= $barcode ?></td>
                    </tr>
                    <tr>
                        <td class="col-xs-4 text-right"><strong><?= lang('name') ?> : </strong></td>
                        <td class="col-xs-8"><?= $items_info->item_name ?></td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong><?= lang('code') ?> : </strong></td>
                        <td><?= $items_info->code ?></td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong><?= lang('barcode_symbology') ?> : </strong></td>
                        <td><?= $items_info->barcode_symbology ?></td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong><?= lang('group') ?> : </strong></td>
                        <td><?= (!empty($group->customer_group) ? $group->customer_group : '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong><?= lang('manufacturer') ?> : </strong></td>
                        <td><?= (!empty($manufacturer_info->manufacturer) ? $manufacturer_info->manufacturer : '-'); ?></td>
                    </tr>
                    <?php
                    $invoice_view = config_item('invoice_view');
                    if (!empty($invoice_view) && $invoice_view == '2') {
                        ?>
                        <tr>
                            <td class="text-right"><strong><?= lang('hsn_code') ?> : </strong></td>
                            <td><?= $items_info->hsn_code ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="text-right"><strong><?= lang('cost_price') ?> : </strong></td>
                        <td><?= display_money($items_info->cost_price, $currency->symbol); ?></td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong><?= lang('unit_price') ?> : </strong></td>
                        <td><?= display_money($items_info->unit_cost, $currency->symbol); ?></td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong><?= lang('unit') . ' ' . lang('type') ?> : </strong></td>
                        <td><?= $items_info->unit_type; ?></td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong><?= lang('tax') ?> : </strong></td>
                        <td>
                            <?php
                            if (!is_numeric($items_info->tax_rates_id)) {
                                $tax_rates = json_decode($items_info->tax_rates_id);
                            } else {
                                $tax_rates = null;
                            }
                            if (!empty($tax_rates)) {
                                foreach ($tax_rates as $key => $tax_id) {
                                    $taxes_info = $this->db->where('tax_rates_id', $tax_id)->get('tbl_tax_rates')->row();
                                    if (!empty($taxes_info)) {
                                        echo $key + 1 . '. ' . $taxes_info->tax_rate_name . '&nbsp;&nbsp; (' . $taxes_info->tax_rate_percent . '% ) <br>';
                                    }
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if (!empty($items_info->item_desc)) { ?>
        <div class="col-xs-12 mt-lg">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4"><?= lang('description') ?></h4>
                    <?= strip_html_tags($items_info->item_desc) ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<div class="modal-footer">
    <div class="mb-3">
        <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>            
    </div>
</div>


<script type="text/javascript">
    function print_items(print_items) {
        var printContents = document.getElementById(print_items).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

