<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    <!-- Start Form -->
    <form action="<?php echo base_url() ?>admin/settings/save_purchases" enctype="multipart/form-data" class="form-horizontal" method="post">
        <div class="card-body">
            <h4 class="card-title mb-4"><?= lang('purchase_settings') ?></h4>
            <input type="hidden" name="settings" value="<?= $load_setting ?>">

            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('purchase_prefix') ?> <span
                        class="text-danger">*</span></label>
                <div class="col-lg-7">
                    <input type="text" name="purchase_prefix" class="form-control"
                           value="<?= config_item('purchase_prefix') ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('purchase_start_no') ?> <span
                        class="text-danger">*</span></label>
                <div class="col-lg-7">
                    <input type="text" name="purchase_start_no" class="form-control"
                           value="<?= config_item('purchase_start_no') ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('increment_purchase_number') ?></label>
                <div class="col-lg-7">
                    <div class="form-check form-check-primary">
                        <input type="hidden" value="off" name="increment_purchase_number"/>
                        <input type="checkbox" <?php
                            if (config_item('increment_purchase_number') == 'TRUE') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="increment_purchase_number">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('show_item_tax') ?></label>
                <div class="col-lg-7">
                    <div class="form-check form-check-primary">
                        <input type="hidden" value="off" name="show_purchase_tax"/>
                        <input type="checkbox" <?php
                            if (config_item('show_purchase_tax') == 'TRUE') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="show_purchase_tax">
                    </div>
                </div>
            </div>
            <div class="row mb-3 terms">
                <label class="col-lg-3 col-form-label"><?= lang('purchase_terms') ?></label>
                <div class="col-lg-7">
                    <textarea class="form-control elm1" name="purchase_terms"><?= config_item('purchase_terms') ?></textarea>
                </div>
            </div>
            <div class="row mb-3 terms">
                <label class="col-lg-3 col-form-label"><?= lang('purchase_footer') ?></label>
                <div class="col-lg-7">
                <textarea class="form-control elm1" name="purchase_footer"><?= config_item('purchase_footer') ?></textarea>
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
    <!-- End Form -->
</div>