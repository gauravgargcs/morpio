<?php echo message_box('success') ?>
<div class="row">
    <!-- Start Form test -->
    <div class="col-lg-12">
        <form method="post" action="<?php echo base_url() ?>admin/settings/save_server" class="form-horizontal">
            <div class="panel panel-custom">
                <header class="panel-heading "><?= lang('server') . ' ' . lang('configuration') ?></header>
                <div class="panel-body">
                    <input type="hidden" name="settings" value="<?= $load_setting ?>">

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('select') . ' ' . lang('server') ?> <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <select name="saas_server" id="type" class="form-control">
                                <?php $saas_server = config_item('saas_server'); ?>
                                <option
                                    value="local" <?= ($saas_server == "local" ? ' selected="selected"' : '') ?>><?= lang('local') ?></option>
                                <option
                                    value="cpanel" <?= ($saas_server == "cpanel" ? ' selected="selected"' : '') ?>><?= lang('cpanel') ?></option>
                                <option
                                    value="plesk" <?= ($saas_server == "plesk" ? ' selected="selected"' : '') ?>><?= lang('plesk') ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="saas_cpanel" style="display: <?= ($saas_server == 'cpanel' ? 'block' : 'none'); ?>">
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('cpanel') . ' ' . lang('host') ?> </label>
                            <div class="col-lg-6">
                                <input type="text" required="" class="form-control"
                                       value="<?= $this->config->item('saas_cpanel_host') ?>" name="saas_cpanel_host">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('cpanel') . ' ' . lang('port') ?></label>
                            <div class="col-lg-6">
                                <input type="text" required="" class="form-control"
                                       value="<?= $this->config->item('saas_cpanel_port') ?>" name="saas_cpanel_port">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('cpanel') . ' ' . lang('username') ?></label>
                            <div class="col-lg-6">
                                <input type="text" required="" class="form-control"
                                       value="<?= $this->config->item('saas_cpanel_username') ?>"
                                       name="saas_cpanel_username">
                            </div>
                        </div>

                        <div class="form-group">                           
                            <label class="col-lg-3 control-label"><?= lang('cpanel') . ' ' . lang('password') ?></label>
                            <div class="col-lg-6">
                                <?php
                                $password = strlen(decrypt(config_item('saas_cpanel_password')));
                                ?>
                                <input type="password" name="saas_cpanel_password" placeholder="<?php
                                if (!empty($password)) {
                                    for ($p = 1; $p <= $password; $p++) {
                                        echo '*';
                                    }
                                } ?>" value="" class="form-control">
                                <strong id="show_password" class="required"></strong>
                            </div>
                            <div class="col-lg-3">
                                <a data-toggle="modal" data-target="#myModal"
                                   href="<?= base_url('admin/client/see_password/cpanel') ?>"
                                   id="see_password"><?= lang('see_password') ?></a>
                                <strong id="hosting_password" class="required"></strong>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Set Output <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <select name="saas_cpanel_output" id="type" class="form-control">
                                    <?php $output = config_item('saas_cpanel_output'); ?>
                                    <option
                                        value="json" <?= ($output == "json" ? ' selected="selected"' : '') ?>>JSON</option>
                                    <option
                                        value="xml" <?= ($output == "xml" ? ' selected="selected"' : '') ?>>XML</option>
                                    <option
                                        value="array" <?= ($output == "simplexml" ? ' selected="selected"' : '') ?>>Simple XML</option>
                                    <option
                                        value="array" <?= ($output == "array" ? ' selected="selected"' : '') ?>>Array</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="saas_plesk" style="display: <?= ($saas_server == 'plesk' ? 'block' : 'none'); ?>">
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('plesk') . ' ' . lang('host') ?> </label>
                            <div class="col-lg-6">
                                <input type="text" required="" class="form-control"
                                       value="<?= $this->config->item('saas_plesk_host') ?>" name="saas_plesk_host">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('plesk') . ' ' . lang('username') ?></label>
                            <div class="col-lg-6">
                                <input type="text" required="" class="form-control"
                                       value="<?= $this->config->item('saas_plesk_username') ?>"
                                       name="saas_plesk_username">
                            </div>
                        </div>

                        <div class="form-group">                           
                            <label class="col-lg-3 control-label"><?= lang('plesk') . ' ' . lang('password') ?></label>
                            <div class="col-lg-6">
                                <?php
                                $password = strlen(decrypt(config_item('saas_plesk_password')));
                                ?>
                                <input type="password" name="saas_plesk_password" placeholder="<?php
                                if (!empty($password)) {
                                    for ($p = 1; $p <= $password; $p++) {
                                        echo '*';
                                    }
                                } ?>" value="" class="form-control">
                                <strong id="show_password" class="required"></strong>
                            </div>
                            <div class="col-lg-3">
                                <a data-toggle="modal" data-target="#myModal"
                                   href="<?= base_url('admin/client/see_password/plesk') ?>"
                                   id="see_password"><?= lang('see_password') ?></a>
                                <strong id="hosting_password" class="required"></strong>
                            </div>
                        </div>

                        <div class="form-group">
                            <label
                                class="col-lg-3 control-label"><?= lang('plesk') . ' ' . lang('webspace_id') ?></label>
                            <div class="col-lg-6">
                                <input type="text" required="" class="form-control"
                                       value="<?= $this->config->item('saas_plesk_webspace_id') ?>"
                                       name="saas_plesk_webspace_id">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"></label>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-sm btn-primary"><?= lang('save_changes') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#type").change(function () {
            $(this).find("option:selected").each(function () {
                if ($(this).attr("value") == "cpanel") {
                    $('.saas_cpanel').show();
                    $(".saas_cpanel :input").attr("disabled", false);
                    $('.saas_plesk').hide();
                    $(".saas_plesk :input").attr("disabled", true);
                }
                else if ($(this).attr("value") == "plesk") {
                    $('.saas_plesk').show();
                    $(".saas_plesk :input").attr("disabled", false);
                    $('.saas_cpanel').hide();
                    $(".saas_cpanel :input").attr("disabled", true);
                } else {
                    $('.saas_cpanel').hide();
                    $(".saas_cpanel :input").attr("disabled", true);
                    $('.saas_plesk').hide();
                    $(".saas_plesk :input").attr("disabled", true);
                }
            });
        }).change();

    });
</script>