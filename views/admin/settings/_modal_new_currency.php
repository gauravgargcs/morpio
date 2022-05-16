<?php  echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<form role="form" id="from_items" action="<?php echo base_url(); ?>admin/settings/new_currency/save" method="post" class="form-horizontal">
    <div class="modal-header">
        <h5 class="modal-title"><?= lang('from_items') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row mb-3">
            <label class="col-lg-3 form-label"><?= lang('currency_code') ?></label>
            <div class="col-lg-7">
                <input type="text" class="form-control" placeholder="Please Enter Currency Code" name="code">
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-lg-3 form-label"><?= lang('name') ?> </label>
            <div class="col-lg-7">
                <input type="text" class="form-control" placeholder="Please Enter Currency Name" name="name">
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-lg-3 form-label"><?= lang('currency_symbol') ?> </label>
            <div class="col-lg-7">
                <input type="text" class="form-control" placeholder="Please Enter Currency Symbol"  name="symbol">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" class="btn btn-primary"><?= lang('save') ?></button>
        </div>
    </div>
</form>