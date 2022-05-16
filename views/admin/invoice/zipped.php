<div class="modal-header">
    <h5 class="modal-title"><?= lang('zip_invoice') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form role="form" id="from_items" action="<?php echo base_url(); ?>admin/invoice/zipped/<?= $module ?>" method="post" class="form-horizontal">
    <div class="modal-body wrap-modal wrap px-4">

        <div class="row mb-3">
            <label class="col-lg-3 col-form-label"><?= lang('status') ?></label>
            <div class="col-lg-7">
                <div class="form-check form-radio-outline form-radio-primary mt mr">
                    <input id="all" type="radio" name="invoice_status" value="all"  checked="" class="form-check-input">
                    <label class="form-check-label" for="all"><?= lang('all') ?></label>
                </div>
                <?php
                if ($module == 'invoice') {
                    $invoiceFilter = $this->invoice_model->get_invoice_filter();
                }
                if ($module == 'estimate') {
                    $invoiceFilter = $this->estimates_model->get_invoice_filter();
                }
                if ($module == 'proposal') {
                    $invoiceFilter = $this->proposal_model->get_invoice_filter();
                }
                if ($module == 'payment') {
                    $invoiceFilter = $this->invoice_model->get_invoice_payment();
                }
                if (!empty($invoiceFilter)) {
                    foreach ($invoiceFilter as $v_Filter) { ?>
                <div class="form-check form-radio-outline form-radio-primary mt mr">
                    <input id="<?= $v_Filter['value'] ?>" type="radio" name="invoice_status" value="<?= $v_Filter['value'] ?>" class="form-check-input">
                    <label class="form-check-label" for="<?= $v_Filter['value'] ?>"><?= $v_Filter['name'] ?></label>
                </div>
                <?php } } ?>
            </div>
        </div>
        <div class="period">
            <div class="row mb-3">
                <label
                    class="col-lg-3 col-form-label"><?= lang('from_date') ?></label>
                <div class="col-lg-7">
                    <div class="input-group">
                        <input class="form-control datepicker period" type="text" name="from_date" data-date-format="<?= config_item('date_picker_format'); ?>">
                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>                
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label
                    class="col-lg-3 col-form-label"><?= lang('to_date') ?></label>
                <div class="col-lg-7">
                    <div class="input-group">
                        <input class="form-control datepicker period" type="text" name="to_date" data-date-format="<?= config_item('date_picker_format'); ?>">
                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>                
                    </div>
                </div>
            </div>
        </div>
        <?php if (!empty($client_id)) { ?>
            <input type="hidden" name="client_id" value="<?= $client_id ?>">
        <?php } ?>
    </div>
    <div class="modal-footer px-4">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('upload') ?></button>            
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function () {
        $('[name="invoice_status"]').change(function () {
            var val = $(this).val();
            var year = val.split('_');
            if (val == 'last_month' || val == 'this_months' || $.isNumeric(year[1])) {
                $('.period').hide().attr('disabled', 'disabled');
            } else {
                $('.period').show().removeAttr('disabled');
            }
        });
    });

</script>