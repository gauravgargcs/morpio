<?php
if (!empty($stock_category_info)) {
    $stock_category_id = $stock_category_info->stock_category_id;

    $companies_id = $stock_category_info->companies_id;
} else {
    $stock_category_id = null;

    $companies_id = null;
}
echo form_open(base_url('admin/stock/edit_stock_category/' . $stock_category_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
<div class="modal-header">
    <h5 class="modal-title"><?= lang('edit') . ' ' . lang('stock_category') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body wrap-modal wrap">
    <?php super_admin_form_modal($companies_id, 4, 7) ?>
    <div class="row mb-3" id="border-none">
        <label for="field-1" class="col-sm-4 col-form-label"><?= lang('edit') . ' ' . lang('stock_category') ?>
            <span class="required">*</span></label>
        <div class="col-sm-7">
            <input  type="text" name="stock_category" required class="form-control"  value="<?= (!empty($stock_category_info->stock_category) ? $stock_category_info->stock_category : '') ?>"/>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="mb-3">
        <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
        <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('update') ?></button>            
    </div>
</div>
<?php echo form_close(); ?>
