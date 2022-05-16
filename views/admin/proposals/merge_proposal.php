<?php
if (count($estimate_to_merge) > 0) { ?>
<div class="card">
    <div class="card-body">
        <h3 class="card-title mb-4"><strong><?php echo lang('estimate_available_for_merging'); ?></strong></h3>
        <div class="pl-lg">
            <?php foreach ($estimate_to_merge as $_inv) { ?>
                <?php $currency = $this->estimates_model->client_currency_sambol($_inv->client_id);
                if (empty($currency)) {
                    $currency = $this->estimates_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                }
                ?>
            <div class="form-check form-check-primary mb-3 mt-sm">
                <i class="fa fa-hand-o-right text-danger pr-sm" aria-hidden="true"></i>
                <input type="checkbox" name="invoices_to_merge[]" value="<?php echo $_inv->estimates_id; ?>" id="estimate_<?php echo $_inv->estimates_id; ?>" class="form-check-input"> 
                <label class="form-check-label" for="estimate_<?php echo $_inv->estimates_id; ?>">
                    <a href="<?php echo base_url() . 'admin/estimates/index/estimates_details/' . $_inv->estimates_id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-title="<?php echo lang($_inv->status); ?>" target="_blank"><?php echo $_inv->reference_no; ?></a> -<?= display_money($this->estimates_model->estimate_calculation('total', $_inv->estimates_id), $currency->symbol); ?>
                </label>
            </div>
            <?php } ?>
            <p>
            <div class="form-check form-check-primary mb-3 mt-sm">
                <input type="checkbox" checked name="cancel_merged_estimate" id="cancel_merged_estimate" class="form-check-input">
                <label class="form-check-label" for="cancel_merged_estimate"><strong><?php echo lang('cancel_merged_estimate'); ?></strong></label>
            </div>
            </p>
        </div>
    </div>
</div> 
<?php } ?>
