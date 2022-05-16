<div class="modal-header">
    <h5 class="modal-title"><?= lang('clone') . ' ' . lang('invoice') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php echo form_open(base_url('admin/invoice/cloned_invoice/' . $invoice_info->invoices_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
<div class="modal-body wrap-modal wrap">
    <?php
    $companies_id = $invoice_info->companies_id;
    super_admin_form_modal($companies_id, 3, 7) ?>
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label"><?= lang('select') . ' ' . lang('client') ?> <span
                class="text-danger">*</span>
        </label>
        <div class="col-lg-7">
            <select class="form-control modal_select_box" style="width: 100%" name="client_id" required>
                <?php
                if (!empty($all_client)) {
                    foreach ($all_client as $v_client) {
                        ?>
                        <option value="<?= $v_client->client_id ?>"
                            <?php
                            if (!empty($invoice_info)) {
                                $invoice_info->client_id == $v_client->client_id ? 'selected' : '';
                            }
                            ?>
                        ><?= ($v_client->name) ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('select[name="companies_id"]').on('change', function () {
                var companies_id = $(this).val();
                if (companies_id) {
                    $.ajax({
                        url: '<?= base_url('admin/global_controller/json_by_company/tbl_client/')?>' + companies_id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('select[name="client_id"]').find('option').not(':first').remove();
                            $.each(data, function (key, value) {
                                $('select[name="client_id"]').append('<option value="' + value.client_id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('select[name="client_id"]').find('option').not(':first').remove();
                }
            });
        });
    </script>
    <div class="row mb-3">
        <label
            class="col-lg-3 col-form-label"><?= lang('invoice_date') ?></label>
        <div class="col-lg-7">
            <div class="input-group">
                <input type="text" name="invoice_date"
                       class="form-control datepicker"
                       value="<?php
                       if (!empty($invoice_info->invoice_date)) {
                           echo date('d-m-Y H-i', strtotime($invoice_info->invoice_date));
                       } else {
                           echo date('d-m-Y H-i');
                       }
                       ?>"
                       data-date-format="<?= config_item('date_picker_format'); ?>">
                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label"><?= lang('due_date') ?></label>
        <div class="col-lg-7">
            <div class="input-group">
                <input type="text" name="due_date"
                       class="form-control datepicker"
                       value="<?php
                       if (!empty($invoice_info->due_date)) {
                           echo date('d-m-Y H-i', strtotime($invoice_info->due_date));
                       } else {
                           echo strftime(date('d-m-Y H-i', strtotime("+" . config_item('invoices_due_after') . " days")));
                       }
                       ?>"
                       data-date-format="<?= config_item('date_picker_format'); ?>">
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="mb-3">
        <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
        <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('clone') ?></button>            
    </div>
</div>
<?php echo form_close(); ?>