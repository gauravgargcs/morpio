<?php
if (!empty($invoices_to_merge) && count($invoices_to_merge) > 0) { ?>
<div class="card">
    <div class="card-body">
        <h3 class="card-title mb-4"><strong><?php echo lang('invoices_available_for_merging'); ?></strong></h3>
        <div class="pl-lg">
            <?php foreach ($invoices_to_merge as $_inv) { ?>
                <?php $currency = $this->invoice_model->client_currency_sambol($_inv->client_id);
                if (empty($currency)) {
                    $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                }
                ?>
                <div class="form-check form-check-primary mb-3 mt-sm">
                    <i class="fa fa-hand-o-right text-danger pr-sm" aria-hidden="true"></i>
                        <input type="checkbox" name="invoices_to_merge[]" value="<?php echo $_inv->invoices_id; ?>" id="invoice_<?php echo $_inv->invoices_id; ?>" class="form-check-input"> 
                        <label class="form-check-label" for="invoice_<?php echo $_inv->invoices_id; ?>">
                           <a href="<?php echo base_url() . 'admin/invoice/manage_invoice/invoice_details/' . $_inv->invoices_id; ?>" data-bs-toggle="tooltip" data-title="<?php echo $this->invoice_model->get_payment_status($_inv->invoices_id); ?>" target="_blank"><?php echo $_inv->reference_no; ?></a> -
                            <strong><?php echo display_money($this->invoice_model->calculate_to('invoice_due', $_inv->invoices_id), $currency->symbol); ?></strong>
                        </label>
                </div>
                <?php if ($_inv->discount_total > 0) { ?>
                    <span style="margin-left: 21px;">
                    <?php echo lang('invoices_merge_discount', display_money($_inv->discount_total, $currency->symbol)) . '<br/>'; ?>
                    </span>
                <?php } else { ?>
                    <span></span>
                <?php } ?>
            <?php } ?>
            <p>
            <div class="form-check form-check-primary mb-3 mt-sm">
                <input type="checkbox" checked name="cancel_merged_invoices" id="cancel_merged_invoices" class="form-check-input">
                <label class="form-check-label" for="cancel_merged_invoices"><strong><?php echo lang('invoices_merge_cancel'); ?></strong></label>
            </div>
            </p>
        </div>
    </div>
</div> 
<?php } ?>
