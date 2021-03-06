<div class="card" xmlns="http://www.w3.org/1999/html">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    <!-- Start Form -->
    <form role="form" id="form" action="<?php echo base_url(); ?>admin/settings/save_system" method="post"  class="form-horizontal  ">
        <div class="card-body">
            <h4 class="card-title mb-4"><?= lang('system_settings') ?></h4>
            <?php echo validation_errors(); ?>
            <input type="hidden" name="settings" value="<?= $load_setting ?>">
            <div class="row mb-3">
                <label class="col-lg-3 form-label"><?= lang('default_language') ?></label>
                <div class="col-lg-7">
                    <select name="default_language" class="form-control select_box">

                        <?php
                        if (!empty($languages)) {
                            foreach ($languages as $lang) :
                                ?>
                                <option lang="<?= $lang->code ?>"
                                        value="<?= $lang->name ?>"<?= (config_item('default_language') == $lang->name ? ' selected="selected"' : '') ?>><?= ucfirst($lang->name) ?></option>
                                <?php
                            endforeach;
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 form-label"><?= lang('locale') ?></label>
                <div class="col-lg-7">
                    <select name="locale" class="form-control select_box" required>
                        <?php foreach ($locales as $loc) : ?>
                            <option lang="<?= $loc->code ?>"
                                    value="<?= $loc->locale ?>"<?= (config_item('locale') == $loc->locale ? ' selected="selected"' : '') ?>><?= $loc->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 form-label"><?= lang('timezone') ?> <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                    <select name="timezone" class="form-control select_box" required>
                        <?php foreach ($timezones as $timezone => $description) : ?>
                            <option
                                value="<?= $timezone ?>"<?= (config_item('timezone') == $timezone ? ' selected="selected"' : '') ?>><?= $description ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 form-label"><?= lang('default_currency') ?></label>
                <div class="col-lg-7">
                    <select name="default_currency" class="form-control select_box">
                        <?php $cur = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row(); ?>

                        <?php foreach ($currencies as $cur) : ?>
                            <option
                                value="<?= $cur->code ?>"<?= (config_item('default_currency') == $cur->code ? ' selected="selected"' : '') ?>><?= $cur->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($this->session->userdata('user_type') == '1') { ?>
                    <div class="col-sm-2 col-xs-4">
                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('new_currency'); ?>"
                        ></span>
                        <a data-bs-toggle="modal" data-bs-target="#myModal"
                           href="<?= base_url() ?>admin/settings/new_currency" class="btn btn-sm btn-success mt-btn-10">
                            <i class="fa fa-plus text-white"></i></a>
                    
                        <span data-bs-toggle="tooltip" data-bs-placement="top"
                              title="<?= lang('view_all_currency'); ?>">
                        </span>
                        <a href="<?= base_url() ?>admin/settings/all_currency" target="_blank" class="btn btn-sm btn-primary mt-btn-10">
                            <i class="fa fa-list-alt text-white"></i></a>
                    </div>
                <?php } ?>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 form-label"><?= lang('default_account') ?></label>
                <div class="col-lg-7">
                    <div class="input-group">
                        <select name="default_account" style="width:93%;" class="form-control select_box">
                            <?php
                            $account_info = get_result('tbl_accounts');
                            if (!empty($account_info)) {
                                foreach ($account_info as $v_account) : ?>
                                    <option value="<?= $v_account->account_id ?>"<?= (config_item('default_account') == $v_account->account_id ? ' selected="selected"' : '') ?>><?= $v_account->account_name ?></option>
                                <?php endforeach;
                            }
                            $acreated = can_action('36', 'created');
                            ?>
                        </select>
                        <?php if (!empty($acreated)) { ?>
                            <div class="input-group-text" title="<?= lang('new') . ' ' . lang('account') ?>" data-bs-toggle="tooltip" data-bs-placement="top">  <a data-bs-toggle="modal" data-bs-target="#myModal"  href="<?= base_url() ?>admin/account/new_account"><i class="fa fa-plus"></i></a> </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 form-label"><?= lang('attendance_report') ?></label>
                <div class="col-lg-7">
                    <?php $options = array(
                        '1' => lang('attendance_report') . ' 1',
                        '2' => lang('attendance_report') . ' 2',
                        '3' => lang('attendance_report') . ' 3',
                    );
                    echo form_dropdown('attendance_report', $options, config_item('attendance_report'), 'style="width:100%" class="form-select"'); ?>
                </div>
            </div>
           
            <div class="row mb-3">
                <label class="col-lg-3 form-label"><?= lang('currency_position') ?></label>
                <div class="col-lg-7">
                    <?php $options = array(
                        '1' => "$ 100",
                        '2' => "100 $",
                    );
                    echo form_dropdown('currency_position', $options, config_item('currency_position'), 'style="width:100%" class="form-select"'); ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-lg-3 form-label"><?= lang('default_tax') ?></label>
                <div class="col-lg-7">
                    <?php
                    $taxes = by_company('tbl_tax_rates', 'tax_rate_percent');
                    $default_tax = config_item('default_tax');
                    if (!is_numeric($default_tax)) {
                        $default_tax = unserialize($default_tax);
                    }
                    $select = '<select class="form-control select_multi" multiple="multiple"  name="default_tax[]"  data-placeholder="' . lang('no_tax') . '">';
                    foreach ($taxes as $tax) {
                        $selected = '';
                        if (!empty($default_tax) && is_array($default_tax)) {
                            if (in_array($tax->tax_rates_id, $default_tax)) {
                                $selected = ' selected ';
                            }
                        }
                        $select .= '<option value="' . $tax->tax_rates_id . '"' . $selected . 'data-taxrate="' . $tax->tax_rate_percent . '" data-taxname="' . $tax->tax_rate_name . '" data-subtext="' . $tax->tax_rate_name . '">' . $tax->tax_rate_percent . '%</option>';
                    }
                    $select .= '</select>';
                    echo $select;
                    ?>

                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 form-label"><?= lang('tables_pagination_limit') ?></label>
                <div class="col-lg-7">
                    <input type="text" class="form-control"
                           value="<?= config_item('tables_pagination_limit') ?>"
                           name="tables_pagination_limit">
                </div>
            </div>
            <?php
            $this->settings_model->set_locale();
            $date_format = config_item('date_format');
            ?>
            <div class="row mb-3">
                <label class="col-lg-3 form-label"><?= lang('date_format') ?></label>
                <div class="col-lg-7">
                    <select name="date_format" class="form-select">
                        <option
                            value="%d-%m-%Y"<?= ($date_format == "%d-%m-%Y" ? ' selected="selected"' : '') ?>><?= strftime("%d-%m-%Y", time()) ?></option>
                        <option
                            value="%m-%d-%Y"<?= ($date_format == "%m-%d-%Y" ? ' selected="selected"' : '') ?>><?= strftime("%m-%d-%Y", time()) ?></option>
                        <option
                            value="%Y-%m-%d"<?= ($date_format == "%Y-%m-%d" ? ' selected="selected"' : '') ?>><?= strftime("%Y-%m-%d", time()) ?></option>
                        <option
                            value="%d-%m-%y"<?= ($date_format == "%d-%m-%y" ? ' selected="selected"' : '') ?>><?= strftime("%d-%m-%y", time()) ?></option>
                        <option
                            value="%m-%d-%y"<?= ($date_format == "%m-%d-%y" ? ' selected="selected"' : '') ?>><?= strftime("%m-%d-%y", time()) ?></option>
                        <option
                            value="%m.%d.%Y"<?= ($date_format == "%m.%d.%Y" ? ' selected="selected"' : '') ?>><?= strftime("%m.%d.%Y", time()) ?></option>
                        <option
                            value="%d.%m.%Y"<?= ($date_format == "%d.%m.%Y" ? ' selected="selected"' : '') ?>><?= strftime("%d.%m.%Y", time()) ?></option>
                        <option
                            value="%Y.%m.%d"<?= ($date_format == "%Y.%m.%d" ? ' selected="selected"' : '') ?>><?= strftime("%Y.%m.%d", time()) ?></option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">

                <label class="col-lg-3 form-label"><?= lang('time_format') ?></label>
                <div class="col-lg-7">
                    <?php
                    $options = array(
                        'g:i a' => date("g:i a"),
                        'g:i A' => date("g:i A"),
                        'H:i' => date("H:i"),
                    );
                    echo form_dropdown('time_format', $options, config_item('time_format'), ' class="form-select"'); ?>
                </div>
            </div>
            <div class="row mb-3">

                <label class="col-lg-3 form-label"><?= lang('money_format') ?></label>
                <div class="col-lg-7">
                    <?php $options = array(
                        '1' => "1,234.56",
                        '2' => "1.234,56",
                        '3' => "1234.56",
                        '4' => "1234,56",
                        '5' => "1'234.56",
                        '6' => "1 234.56",
                        '7' => "1 234,56",
                        '8' => "1 234'56",
                    );
                    echo form_dropdown('money_format', $options, config_item('money_format'), 'style="width:100%" class="form-control select_box"'); ?>
                </div>
            </div>
            <div class="row mb-3">

                <label class="col-lg-3 form-label"><?= lang('allowed_files') ?></label>
                <div class="col-lg-7">
                    <input type="text" class="form-control" value="<?= config_item('allowed_files') ?>"
                           name="allowed_files">
                </div>
            </div>
            <div class="row mb-3">

                <label class="col-lg-3 form-label"><?= lang('max_file_size') ?></label>
                <div class="col-lg-7">
                    <input type="text" class="form-control" value="<?= config_item('max_file_size') ?>"
                           name="max_file_size">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-lg-3 form-label"><?= lang('google_api_key') ?></label>
                <div class="col-lg-7">
                    <input type="text" class="form-control" value="<?= config_item('google_api_key') ?>"
                           name="google_api_key">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-lg-3 form-label"><?= lang('recaptcha_site_key') ?></label>
                <div class="col-lg-7">
                    <input type="text" class="form-control" value="<?= config_item('recaptcha_site_key') ?>"
                           name="recaptcha_site_key">
                    <?= lang('recaptcha_help') ?>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 form-label"><?= lang('recaptcha_secret_key') ?></label>
                <div class="col-lg-7">
                    <input type="text" class="form-control" value="<?= config_item('recaptcha_secret_key') ?>"
                           name="recaptcha_secret_key">

                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 form-label"><?= lang('auto_close_ticket') ?></label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" value="<?= config_item('auto_close_ticket') ?>"
                           name="auto_close_ticket">
                </div>
                <div class="col-sm-3">
                    <?= lang('hours') . ' <span class="required" >' . lang('required_cron') . '</span>' ?>
                </div>
            </div>

                    <div class="row mb-3">
                        <label class="col-lg-3 form-label"><?= lang('enable_languages') ?></label>
                        <div class="col-lg-7">
                            <div class="form-check form-check-primary">
                                <input type="checkbox" <?php
                                    if (config_item('enable_languages') == 'TRUE') {
                                        echo "checked=\"checked\"";
                                    }
                                    ?> name="enable_languages" class="form-check-input">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 form-label"><?= lang('allow_sub_tasks') ?></label>
                        <div class="col-lg-6">
                            <div class="form-check form-check-primary">
                                    <input type="checkbox" <?php
                                    if (config_item('allow_sub_tasks') == 'TRUE') {
                                        echo "checked=\"checked\"";
                                    }
                                    ?> name="allow_sub_tasks" class="form-check-input">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 form-label"><?= lang('only_allowed_ip_can_clock') ?></label>
                        <div class="col-lg-6">
                            <div class="form-check form-check-primary">
                                    <input type="checkbox" <?php
                                    if (config_item('only_allowed_ip_can_clock') == 'TRUE') {
                                        echo "checked=\"checked\"";
                                    }
                                    ?> name="only_allowed_ip_can_clock"  class="form-check-input">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 form-label"><?= lang('allow_client_registration') ?></label>
                        <div class="col-lg-6">
                            <div class="form-check form-check-primary">
                                    <input type="checkbox" <?php
                                    if (config_item('allow_client_registration') == 'TRUE') {
                                        echo "checked=\"checked\"";
                                    }
                                    ?> name="allow_client_registration"  class="form-check-input">
                            </div>
                        </div>
                    </div>
                    <input name="client_default_menu[]" value="17" type="hidden">
                    <div class="row mb-3">
                        <label class="col-lg-3 form-label"><?= lang('client_default_menu_permission') ?><span
                                class="text-danger"> *</span></label>
                        <div class="col-lg-7">
                            <select multiple="multiple" name="client_default_menu[]" style="width: 100%"
                                    class="select_multi" >
                                <option
                                    value=""><?= lang('select') . ' ' . lang('client_default_menu_permission') ?></option>
                                <?php
                                $all_client_menu = result_by_company('tbl_client_menu', array('parent' => 0), 'sort');
                                if (!empty($all_client_menu)) {
                                    foreach ($all_client_menu as $v_client_menu) {
                                        if ($v_client_menu->label != 'dashboard') {
                                            ?>
                                            <option value="<?= $v_client_menu->menu_id ?>" <?php
                                            $client_menu = unserialize(config_item('client_default_menu'));
                                            if (!empty($client_menu['client_default_menu'])) {
                                                foreach ($client_menu['client_default_menu'] as $v_menu) {
                                                    echo $v_client_menu->menu_id == $v_menu ? 'selected' : '';
                                                }
                                            }
                                            ?>><?= lang($v_client_menu->label) ?></option>
                                            <?php
                                        }
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label class="col-lg-3 form-label"><?= lang('allow_client_project') ?></label>
                        <div class="col-lg-6">
                            <div class="form-check form-check-primary">
                                    <input type="checkbox" <?php
                                    if (config_item('allow_client_project') == 'TRUE') {
                                        echo "checked=\"checked\"";
                                    }
                                    ?> name="allow_client_project"  class="form-check-input">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 form-label"></label>
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-xs btn-primary"><?= lang('save_changes') ?></button>
                    </div>
                </div>
            </section>
        </form>
    </div>
    <!-- End Form -->
</div>