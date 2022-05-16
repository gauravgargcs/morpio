<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
   
    <?php
    $email_error = $this->session->userdata('email_error');
    if (!empty($email_error)) {
        ?>
    <div class="card-body copyright-wrap" id="copyright-wrap">
        <h4 class="card-title mb-4"> Not Connected . Please Follow The instruction !
            <button type="button" class="close" data-bs-target="#copyright-wrap" data-bs-dismiss="alert"><span
                    aria-hidden="true">×</span><span class="sr-only">Close</span>

            </button>
        </h4>
        <div class="row">
            <pre>
                <?= $email_error ?>
            </pre>
        </div>
    </div>
    <?php } ?>
    <form method="post" action="<?php echo base_url() ?>admin/settings/update_email" class="form-horizontal">
        <div class="card-body">
            <h4 class="card-title mb-4"><?= lang('email_settings') ?></h4>
            <input type="hidden" name="settings" value="<?= $load_setting ?>">
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('company_email') ?> <span
                        class="text-danger">*</span></label>
                <div class="col-lg-6">
                    <input type="email" required="" class="form-control" value="<?= $this->config->item('company_email') ?>" name="company_email" data-type="email" data-required="true">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('use_postmark') ?></label>
                <div class="col-lg-6">
                    <div class="form-check form-check-primary">
                        <input type="hidden" value="off" name="use_postmark"/>
                        <input type="checkbox" <?php
                        if (config_item('use_postmark') == 'TRUE') {
                            echo "checked=\"checked\"";
                        }
                        ?> name="use_postmark" id="use_postmark" class="form-check-input">
                    </div>
                </div>
            </div>

            <div id="postmark_config" <?php echo (config_item('use_postmark') != 'TRUE') ? 'style="display:none"' : '' ?>>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label"><?= lang('postmark_api_key') ?></label>
                    <div class="col-lg-6">
                        <input type="text" class="form-control" placeholder="xxxxx" name="postmark_api_key"
                               value="<?= config_item('postmark_api_key') ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label"><?= lang('postmark_from_address') ?></label>
                    <div class="col-lg-6">
                        <input type="email" class="form-control" placeholder="xxxxx"
                               name="postmark_from_address" value="<?= config_item('postmark_from_address') ?>">
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('email_protocol') ?> <span
                        class="text-danger">*</span></label>
                <div class="col-lg-6">
                    <select name="protocol" required="" class="form-select">
                        <?php $prot = config_item('protocol'); ?>
                        <option
                            value="mail" <?= ($prot == "mail" ? ' selected="selected"' : '') ?>><?= lang('php_mail') ?></option>
                        <option
                            value="smtp" <?= ($prot == "smtp" ? ' selected="selected"' : '') ?>><?= lang('smtp') ?></option>
                        <option
                            value="sendmail" <?= ($prot == "sendmail" ? ' selected="selected"' : '') ?>><?= lang('sendmail') ?></option>
                    </select>
                </div>
            </div>
            <?php $prot = config_item('protocol'); ?>
            <div id="smtp_config">
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label"><?= lang('smtp_host') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" required="" class="form-control"
                               value="<?= $this->config->item('smtp_host') ?>" name="smtp_host">
                        <span class="help-block  ">SMTP Server Address</strong>.</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label"><?= lang('smtp_user') ?></label>
                    <div class="col-lg-6">
                        <input type="text" required="" class="form-control"
                               value="<?= $this->config->item('smtp_user') ?>" name="smtp_user">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label"><?= lang('smtp_pass') ?></label>
                    <div class="col-lg-6">
                        <?php
                        $password = strlen(decrypt(config_item('smtp_pass')));
                        ?>
                        <input type="password" name="smtp_pass" placeholder="<?php
                        if (!empty($password)) {
                            for ($p = 1; $p <= $password; $p++) {
                                echo '*';
                            }
                        } ?>" value="" class="form-control">
                        <strong id="show_password" class="required"></strong>
                    </div>
                    <div class="col-lg-3">
                        <a data-bs-toggle="modal" data-bs-target="#myModal"
                           href="<?= base_url('admin/client/see_password/smtp') ?>"
                           id="see_password"><?= lang('see_password') ?></a>
                        <strong id="hosting_password" class="required"></strong>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label"><?= lang('smtp_port') ?></label>
                    <div class="col-lg-6">
                        <input type="text" required="" class="form-control"
                               value="<?= $this->config->item('smtp_port') ?>" name="smtp_port">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label"><?= lang('email_encryption') ?></label>
                    <div class="col-lg-6">
                        <select name="smtp_encryption" class="form-select">
                            <?php $crypt = config_item('smtp_encryption'); ?>
                            <option
                                value=""<?= ($crypt == "" ? ' selected="selected"' : '') ?>><?= lang('none') ?></option>
                            <option value="ssl"<?= ($crypt == "ssl" ? ' selected="selected"' : '') ?>><?= lang('SSL')?>
                            </option>
                            <option value="tls"<?= ($crypt == "tls" ? ' selected="selected"' : '') ?>><?= lang('TLS')?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"></label>
                <div class="col-lg-6">
                    <button type="submit" class="btn btn-xs btn-primary"><?= lang('save_changes') ?></button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4"><?= lang('sent_test_email') ?></h4>
        <form method="post" action="<?php echo base_url() ?>admin/settings/sent_test_email" class="form-horizontal">
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('email') . ' ' . lang('address') ?></label>
                <div class="col-lg-6">
                    <input type="email" required="" class="form-control" value="" name="test_email">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"></label>
                <div class="col-lg-6">
                    <button type="submit" class="btn btn-xs btn-primary"><?= lang('sent_test_email') ?></button>
                </div>
            </div>
        </form>
    </div>
</div>