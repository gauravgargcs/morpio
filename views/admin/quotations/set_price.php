<div class="modal-header">
    <h5 class="modal-title"><?= lang('quotations') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form data-parsley-validate="" novalidate="" action="<?php echo base_url(); ?>admin/quotations/set_price_quotations/<?= $quotations_id ?>" method="post" class="form-horizontal">
    <div class="modal-body wrap-modal wrap">
        <div class="row mb-3">
            <div class="col-sm-12">
                <label class="col-form-label"><?= lang('amount') ?></label>
                <input type="number" min="0" name="quotations_amount" value="<?php
                if (!empty($quotations_info->quotations_amount)) {
                    echo $quotations_info->quotations_amount;
                }
                ?>" required="" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12">
                <label class="col-form-label"><?= lang('notes') ?></label>
                <textarea name="notes" value="" required="" rows="5" class="form-control"><?php
                    if (!empty($quotations_info->notes)) {
                        echo $quotations_info->notes;
                    }
                    ?></textarea>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12">
                <label class="col-form-label"><?= lang('send_email') ?></label>
                <div class="form-check mb-3 col-xl-3">
                    <input type="checkbox" checked="true" name="send_email" class="form-check-input">
                    <label class="form-check-label"> </label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('update') ?></button>            
        </div>
    </div>
</form>