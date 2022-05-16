<div class="modal-header">
    <h5 class="modal-title"><?= lang('clone') . ' ' . lang('invoice') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php echo form_open(base_url('admin/purchase/cloned_purchase/' . $purchase_info->purchase_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
    <div class="modal-body wrap-modal wrap">
        <?php
        $companies_id = $purchase_info->companies_id;
        super_admin_form_modal($companies_id, 3, 7) ?>
        <div class="row mb-3">
            <label class="col-lg-3 col-form-label"><?= lang('select') . ' ' . lang('supplier') ?> <span
                    class="text-danger">*</span>
            </label>
            <div class="col-lg-7">
                <select class="form-control modal_select_box" style="width: 100%" name="supplier_id" required>
                    <?php
                    if (!empty($all_supplier)) {
                        foreach ($all_supplier as $v_supplier) {
                            if (!empty($purchase_info->supplier_id)) {
                                $supplier_id = $purchase_info->supplier_id;
                            }
                            ?>
                            <option value="<?= $v_supplier->supplier_id ?>"
                                <?php
                                if (!empty($supplier_id)) {
                                    echo $supplier_id == $v_supplier->supplier_id ? 'selected' : null;
                                }
                                ?>
                            ><?= $v_supplier->name ?></option>
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
                            url: '<?= base_url('admin/global_controller/json_by_company/tbl_suppliers/')?>' + companies_id,
                            type: "GET",
                            dataType: "json",
                            success: function (data) {
                                $('select[name="supplier_id"]').find('option').not(':first').remove();
                                $.each(data, function (key, value) {
                                    $('select[name="supplier_id"]').append('<option value="' + value.supplier_id + '">' + value.name + '</option>');
                                });
                            }
                        });
                    } else {
                        $('select[name="supplier_id"]').find('option').not(':first').remove();
                    }
                });
            });
        </script>
        <div class="row mb-3">
            <label
                class="col-lg-3 col-form-label"><?= lang('purchase') . ' ' . lang('date') ?></label>
            <div class="col-lg-7">
                <div class="input-group">
                    <input type="text" name="purchase_date"
                           class="form-control datepicker"
                           value="<?php
                           if (!empty($purchase_info->purchase_date)) {
                               echo date('d-m-Y H-i', strtotime($purchase_info->purchase_date));
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
                           if (!empty($purchase_info->due_date)) {
                               echo date('d-m-Y H-i', strtotime($purchase_info->due_date));
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