<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>

    <form action="<?php echo base_url() ?>admin/settings/save_invoice" enctype="multipart/form-data"  class="form-horizontal" method="post">
        <div class="card-body">
            <h4 class="card-title mb-4"><?= lang('invoice_settings') ?></h4>
            
            <input type="hidden" name="settings" value="<?= $load_setting ?>">

            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('invoice_prefix') ?> <span
                        class="text-danger">*</span></label>
                <div class="col-lg-7">
                    <input type="text" name="invoice_prefix" class="form-control"
                           value="<?= config_item('invoice_prefix') ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('invoices_due_after') ?> <span
                        class="text-danger">*</span></label>
                <div class="col-lg-7">
                    <input type="text" name="invoices_due_after" class="form-control" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="<?= lang('days') ?>" value="<?= config_item('invoices_due_after') ?>" required>

                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('invoice_start_no') ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                    <input type="text" name="invoice_start_no" class="form-control"  value="<?= config_item('invoice_start_no') ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('qty_calculation_from_items') ?></label>
                <div class="col-lg-7">
                    <div class="form-check form-check-primary">
                        <input value="Yes" type="checkbox" <?php
                            if (config_item('qty_calculation_from_items') == 'Yes') {
                                echo "checked=\"checked\"";
                            } ?> name="qty_calculation_from_items" class="form-check-input">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('allow_customer_edit_amount') ?>
                <i title="" class="fa fa-question-circle"  data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="<?= lang('allow_customer_edit_amount_help') ?>"></i></label>
                <div class="col-lg-7">
                    <div class="form-check form-check-primary">
                            <input value="Yes" type="checkbox" <?php
                            if (config_item('allow_customer_edit_amount') == 'Yes') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="allow_customer_edit_amount" class="form-check-input">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('increment_invoice_number') ?></label>
                <div class="col-lg-7">
                    <div class="form-check form-check-primary">
                        <input type="hidden" value="off" name="increment_invoice_number"/>
                        <input type="checkbox" <?php
                            if (config_item('increment_invoice_number') == 'TRUE') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="increment_invoice_number" class="form-check-input">
                    </div>
                </div>
            </div>


            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('show_item_tax') ?></label>
                <div class="col-lg-7">
                    <div class="form-check form-check-primary">
                        <input type="hidden" value="off" name="show_invoice_tax"/>
                        <input type="checkbox" <?php
                            if (config_item('show_invoice_tax') == 'TRUE') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="show_invoice_tax" class="form-check-input">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('invoice_view') ?></label>
                <div class="col-lg-7">
                    <?php
                    $opt_inv = array(1 => lang('tax_invoice'), 0 => lang('standard'), 2 => lang('indian_gst'));
                    echo form_dropdown('invoice_view', $opt_inv, config_item('invoice_view'), 'class="form-select" required="required" id="invoice_view"');
                    ?>
                </div>
            </div>
            <div class="row mb-3" id="states" style="display: none;">
                <label class="col-lg-3 col-form-label"><?= lang('gst_state') ?></label>
                <div class="col-lg-7">
                    <?php
                    $states = $this->gst->getIndianStates();
                    echo form_dropdown('gst_state', $states, config_item('gst_state'), 'class="form-select tip" required="required" id="state" style="width:100%;"');
                    ?>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('send_email_when_recur') ?></label>
                <div class="col-lg-7">
                    <div class="form-check form-check-primary">
                        <input type="hidden" value="off" name="send_email_when_recur"/>
                        <input type="checkbox" <?php
                            if (config_item('send_email_when_recur') == 'TRUE') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="send_email_when_recur" class="form-check-input">
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('invoice_logo') ?></label>
                <div class="col-lg-7">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div>
                            <input class="form-control" type="file" id="formFile"  name="invoice_logo" value="upload"/>
                        </div>
                        <div class="fileinput-new thumbnail" style="width: 210px;">
                            <?php if (config_item('invoice_logo') != '') : ?>
                                <img src="<?php echo base_url() . config_item('invoice_logo'); ?>" class="img-fluid">
                            <?php else: ?>
                                <img src="<?=base_url('skote_assets/images/placeholder_350_260.png');?>" alt="Sample image" class="img-fluid">
                            <?php endif; ?>
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="width: 210px;"></div>
                        
                        <div id="valid_msg" style="color: #e11221"></div>
                    </div>
                </div>
            </div>
            <div class="row mb-3 terms">
                <label class="col-lg-3 col-form-label"><?= lang('default_terms') ?></label>
                <div class="col-lg-7">
                <textarea class="form-control elm1" id="" 
                          name="default_terms"><?= config_item('default_terms') ?></textarea>
                </div>
            </div>
            <div class="row mb-3 terms">
                <label class="col-lg-3 col-form-label"><?= lang('invoice_footer') ?></label>
                <div class="col-lg-7">
                <textarea class="form-control elm1" id="" 
                          name="invoice_footer"><?= config_item('invoice_footer') ?></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-3 col-form-label"></div>
                <div class="col-lg-7">
                    <button type="submit" class="btn btn-xs btn-primary"><?= lang('save_changes') ?></button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function () {
        $('#invoice_view').change(function (e) {
            if ($(this).val() == 2) {
                $('#states').show();
            } else {
                $('#states').hide();
            }
        });
        if ($('#invoice_view').val() == 2) {
            $('#states').show();
        } else {
            $('#states').hide();
        }
    });
</script>