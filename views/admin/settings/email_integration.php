<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>

    <form role="form" id="form" action="<?php echo base_url(); ?>admin/settings/save_email_integration"  method="post" class="form-horizontal">
        <div class="card-body">
            <h4 class="card-title mb-4"><?= lang('email_integration') ?></h4>
            <?php
            $trace_msg = $this->session->userdata('trace');
            if (!empty($trace_msg)) { ?>
                
                <div class="card border copyright-wrap" id="copyright-wrap">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <?= $this->session->userdata('header'); ?>

                            <button type="button" class="close" data-bs-target="#copyright-wrap"
                                    data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                                    class="sr-only">Close</span>

                            </button>
                        </h4>
                    
                        <?php
                        echo '<pre>';
                        print_r($trace_msg);
                        echo '</pre>';
                        ?>
                    </div>
                </div>
            <?php }

            $this->session->unset_userdata('trace');
            $this->session->unset_userdata('header');
            
            ?>
            <?php echo validation_errors(); ?>
            <input type="hidden" name="settings" value="<?= $load_setting ?>">

            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('encryption') ?></label>
                <div class="col-lg-9">
                    <div class="form-check form-check-primary">
                        <input class="select_one form-check-input" type="checkbox" value="tls" name="encryption" <?php
                        if (config_item('encryption') == 'tls') {
                            echo "checked=\"checked\"";
                        }
                        ?>>
                        <label class="form-check-label"><?= lang('tls') ?></label>
                    </div>
                    <div class="form-check form-check-primary">
                        <input class="select_one form-check-input" type="checkbox" value="ssl" name="encryption" <?php
                        if (config_item('encryption') == 'ssl') {
                            echo "checked=\"checked\"";
                        }
                        ?>>
                        <label class="form-check-label"><?= lang('ssl') ?></label>
                    </div>

                    <div class="form-check form-check-primary">
                        <input class="select_one form-check-input" type="checkbox" name="encryption" <?php
                        if (config_item('encryption') == 'on') {
                            echo "checked=\"checked\"";
                        }
                        ?>>
                        <label class="form-check-label"><?= lang('no') . ' ' . lang('encryption') ?></label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('host') ?></label>
                <div class="col-lg-6">
                    <input type="text" name="config_host" value="<?= config_item('config_host') ?>"
                           class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('username') ?></label>
                <div class="col-lg-6">
                    <input type="text" name="config_username" value="<?= config_item('config_username') ?>"
                           class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('password') ?></label>
                <div class="col-lg-6">
                    <?php $password = strlen(decrypt(config_item('config_password'))); ?>
                    <input type="password" name="config_password" value="" placeholder="<?php
                    if (!empty($password)) {
                        for ($p = 1; $p <= $password; $p++) {
                            echo '*';
                        }
                    } ?>" class="form-control">
                    <strong id="show_password" class="required"></strong>
                </div>
                <div class="col-lg-3">
                    <a data-bs-toggle="modal" data-bs-target="#myModal"
                       href="<?= base_url('admin/client/see_password/emin') ?>"
                       id="see_password"><?= lang('see_password') ?></a>
                    <strong id="hosting_password" class="required"></strong>
                </div>
            </div>
            <?php
            $mailbox = config_item('config_mailbox');
            $unread_email = config_item('unread_email');
            ?>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('mailbox') ?></label>
                <div class="col-lg-6">
                    <input type="text" name="config_mailbox"
                           value="<?= (!empty($mailbox) ? $mailbox : 'INBOX') ?>"
                           class="form-control">
                    <span class="help-block">e.g Gmail: INBOX</span>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"></label>
                <div class="col-lg-3">
                    <div class="form-check form-check-primary">
                        <input type="checkbox" name="unread_email" <?php
                        if ($unread_email == 'on') {
                            echo "checked=\"checked\"";
                        }
                        ?> class="form-check-input">
                        <label class="form-check-label"><?= lang('unread_email') ?></label>
                    </div>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-10">
                    <div class="form-check form-check-primary">
                        <input type="checkbox" name="delete_mail_after_import" <?php
                        if (config_item('delete_mail_after_import') == 'on') {
                            echo "checked=\"checked\"";
                        }
                        ?> class="form-check-input">
                        <label class="form-check-label"><?= lang('delete_mail_after_import') ?></label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('for_leads') ?></label>
                <div class="col-lg-6">
                    <div class="form-check form-check-primary">
                        <input type="checkbox" <?php if (config_item('for_leads') == 'on') { echo "checked=\"checked\""; } ?> name="for_leads" id="for_leads" class="form-check-input">
                    </div>
                </div>
            </div>
            <div id="imap_search_for_leads" <?php echo (config_item('for_leads') != 'on') ? 'style="display:none"' : '' ?>>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label"><?= lang('imap_search') ?></label>
                    <div class="col-lg-6">
                        <input type="text" name="imap_search_for_leads" class="form-control"
                               value="<?= config_item('imap_search_for_leads') ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label"><?= lang('search_keyword') ?></label>
                    <div class="col-lg-6">
                        <input type="text" name="leads_keyword" class="form-control"
                               value="<?= config_item('leads_keyword') ?>">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('for_tickets') ?></label>
                <div class="col-lg-6">
                    <div class="form-check form-check-primary">
                        <input type="checkbox" <?php
                            if (config_item('for_tickets') == 'on') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="for_tickets" id="for_tickets" class="form-check-input">
                    </div>
                </div>
            </div>
            <div id="imap_search_for_tickets" <?php echo (config_item('for_tickets') != 'on') ? 'style="display:none"' : '' ?>>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label"><?= lang('imap_search') ?></label>
                    <div class="col-lg-6">
                        <input type="text" name="imap_search_for_tickets" class="form-control" value="<?= config_item('imap_search_for_tickets') ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label"><?= lang('search_keyword') ?></label>
                    <div class="col-lg-6">
                        <input type="text" name="tickets_keyword" class="form-control" value="<?= config_item('tickets_keyword') ?>">
                    </div>
                </div>
            </div>
            <?php
            $all_user_info = get_result('tbl_users', array('role_id !=' => 2, 'activated' => 1))
            ?>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('notified_user') ?></label>
                <div class="col-lg-6">
                    <select name="notified_user[]" style="width: 100%" multiple class="form-control select_multi">
                        <?php
                        $user_id = json_decode(config_item('notified_user'));
                        if (!empty($all_user_info)) {
                            foreach ($all_user_info as $v_user) :
                                $profile_info = $this->db->where('user_id', $v_user->user_id)->get('tbl_account_details')->row();
                                if (!empty($profile_info)) {
                                    ?>
                                    <option value="<?= $v_user->user_id ?>"
                                        <?php if (!empty($user_id)) {
                                            foreach ($user_id as $v_id) {
                                                if ($v_id == $v_user->user_id) {
                                                    echo 'selected';
                                                }
                                            }
                                        } ?>
                                    ><?= $profile_info->fullname ?></option>
                                    <?php
                                }
                            endforeach;
                        }
                        ?>
                    </select>
                </div>
            </div>


            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('postmaster_link') ?></label>
                <div class="col-lg-9">
                    <p class="form-control-static">
                        <strong>wget <?= base_url() ?>postmaster -O /dev/null</strong>
                    </p>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('last_postmaster_run') ?></label>
                <div class="col-lg-6">
                    <p class="form-control-static">
                        <strong>
                            <?php
                            $last_postmaster_run = config_item('last_postmaster_run');
                            if (!empty($last_postmaster_run)) {
                                echo date('d-m-Y H-i', strtotime(config_item('last_postmaster_run')));
                            } else {
                                echo "-";
                            } ?>
                        </strong>
                    </p>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-lg-3 col-md-3 hidden-xs hidden-sm hidden-md col-form-label"></label>
                <div class="col-lg-9 col-md-9 row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <button type="submit"
                                class="btn btn-sm btn-primary"><?= lang('save_changes') ?></button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <p data-bs-toggle="tooltip" data-bs-placement="top"
                           title="<?= lang('save_email_then_test') ?>">
                            <a href="<?= base_url() ?>admin/settings/test_email"
                               class="btn btn-success"><?= lang('test_email_settings') ?></a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </form>
    <!-- End Form -->
</div>