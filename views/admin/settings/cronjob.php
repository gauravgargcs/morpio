<div class="card" xmlns="http://www.w3.org/1999/html">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
     <form role="form" id="form" action="<?php echo base_url(); ?>admin/settings/save_cronjob"  method="post" class="form-horizontal  ">
        <div class="card-body">
            <div class="pull-right float-end">
                <a href="<?= base_url() ?>cronjob/manually"><?= lang('run_cron_manually') ?></a>
            </div>
            <h4 class="card-title mb-4"><?= lang('cronjob') . ' ' . lang('settings') ?>  </h4>

                    <input type="hidden" name="settings" value="<?= $load_setting ?>">

                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label"><?= lang('active') . ' ' . lang('cronjob') ?></label>
                        <div class="col-lg-8 form-check form-check-primary">
                            <input type="checkbox" name="active_cronjob" <?php
                            if (config_item('active_cronjob') == 'on') {
                                echo "checked=\"checked\"";
                            }
                            ?> class="form-check-input">
                            <small><?= lang('cronjob_help') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label"><?= lang('automatic') . ' ' . lang('database_backup') ?></label>
                        <div class="col-lg-8 form-check form-check-primary">
                            <input type="checkbox" name="automatic_database_backup" <?php
                            if (config_item('automatic_database_backup') == 'on') {
                                echo "checked=\"checked\"";
                            }
                            ?> class="form-check-input">
                            <small><?= lang('database_backup_help') ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label"><?= lang('cronjob_link') ?></label>
                        <div class="col-lg-8">
                            <p class="form-control-static"><strong><i><?= base_url() ?>cronjob/index</i></strong></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label"><?= lang('last_cronjob_run') ?></label>
                        <div class="col-lg-8">
                            <p class="form-control-static"><?php
                                $last_cronjob_run = config_item('last_cronjob_run');
                                if (!empty($last_cronjob_run)) {
                                    echo date("d-m-Y H-i", config_item('last_cronjob_run'));
                                } else {
                                    echo "-";
                                } ?>
                            </p>
                        </div>

                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label"></label>
                        <div class="col-lg-8">
                            <button type="submit"
                                    class="btn btn-xs btn-primary"><?= lang('save_changes') ?></button>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <span>If cronjobs are not included in your hosting subscription, you can use a free
                                cronjob service
                                like <a href="http://www.easycron.com?ref=18097" target="_blank">Free Cronjob
                                    Service</a></span>
                        </div>
                    </div>

                </div>
            </section>
        </form>
    </div>
    <!-- End Form -->
</div>