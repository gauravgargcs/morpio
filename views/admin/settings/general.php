<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    <form role="form" id="form" action="<?php echo base_url(); ?>admin/settings/save_settings" method="post" class="form-horizontal  ">
        <?php
        $can_do = can_do(111);
        if (!empty($can_do)) { ?>
            <div class="card-body">
                <h4 class="card-title mb-4"><?= lang('company_details') ?></h4>

                <input type="hidden" name="settings" value="<?= $load_setting ?>">
                <input type="hidden" name="languages" value="<?php
                if (!empty($translations)) {
                    echo implode(",", $translations);
                }
                ?>">
                <div class="row mb-3">
                    <label class="col-lg-3 form-label"><?= lang('company_name') ?> <span class="text-danger">*</span></label>
                    <div class="col-lg-7">
                        <input type="text" name="company_name" class="form-control" value="<?= $this->config->item('company_name') ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 form-label"><?= lang('company_legal_name') ?> <span  class="text-danger">*</span></label>
                    <div class="col-lg-7">
                        <input type="text" name="company_legal_name" class="form-control" value="<?= $this->config->item('company_legal_name') ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 form-label"><?= lang('contact_person') ?> </label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control" value="<?= $this->config->item('contact_person') ?>" name="contact_person">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 form-label"><?= lang('company_address') ?> <span  class="text-danger">*</span></label>
                    <div class="col-lg-7">
                    <textarea class="form-control" name="company_address" required><?= $this->config->item('company_address') ?></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 form-label"><?= lang('country') ?></label>
                    <div class="col-lg-7">
                        <select class="form-control select_box" style="width:100%" name="company_country">
                            <optgroup label="<?= lang('selected_country') ?>">
                                <option
                                    value="<?= $this->config->item('company_country') ?>"><?= $this->config->item('company_country') ?></option>
                            </optgroup>
                            <optgroup label="<?= lang('other_countries') ?>">
                                <?php foreach ($countries as $country): ?>
                                    <option value="<?= $country->value ?>"><?= $country->value ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 form-label"><?= lang('city') ?></label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control"
                               value="<?= $this->config->item('company_city') ?>"
                               name="company_city">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 form-label"><?= lang('zip_code') ?> </label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control"
                               value="<?= $this->config->item('company_zip_code') ?>" name="company_zip_code">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 form-label"><?= lang('company_phone') ?></label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control"
                               value="<?= $this->config->item('company_phone') ?>"
                               name="company_phone">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 form-label"><?= lang('company_email') ?></label>
                    <div class="col-lg-7">
                        <input type="email" class="form-control"
                               value="<?= $this->config->item('company_email') ?>"
                               name="company_email">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 form-label"><?= lang('company_domain') ?></label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control"
                               value="<?= $this->config->item('company_domain') ?>"
                               name="company_domain">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 form-label"><?= lang('Company ID') ?></label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control"
                               value="<?= $this->config->item('company_vat') ?>"
                               name="company_vat">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 form-label"></label>
                    <div class="col-lg-7">
                        <button type="submit" class="btn btn-xs btn-primary"><?= lang('save_changes') ?></button>
                    </div>
                </div>
            </div>
        <?php
        } else {
            // messages for user
            echo lang('nothing_to_display');
        }
        ?>
    </form>
    <!-- End Form -->
</div>

