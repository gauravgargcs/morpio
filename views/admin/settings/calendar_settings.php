<?php  echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<form role="form" id="from_items" action="<?php echo base_url(); ?>admin/calendar/save_settings" method="post" class="form-horizontal form-groups-bordered">
    <div class="modal-header">
        <h5 class="modal-title"><?= lang('calendar_settings') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="mb-3">
                <label class="form-label"><?= lang('google_api') ?></label>
                <input type="text" class="form-control" value="<?= config_item('gcal_api_key') ?>" name="gcal_api_key">
            </div>
        </div>
        <div class="row">
            <div class="mb-3">
                <label class="form-label"><?= lang('calendar_id') ?></label>
                <input type="text" class="form-control" value="<?= config_item('gcal_id') ?>" name="gcal_id">
            </div>
        </div>

        <h6 class="mb0"><?php echo lang('show_on_calendar'); ?></h6>
        <hr class="mt-sm"/>

        <div class="row mb-3">
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-check form-check-right mb-3">
                            <input class="form-check-input" type="checkbox" id="project" <?php
                                    if (config_item('project_on_calendar') == 'on') {
                                        echo "checked=\"checked\"";
                                    }
                                    ?> name="project_on_calendar">
                            <label class="form-check-label" for="project">
                                <?= lang('project') ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <input class="form-control form-control-color mw-100" type="color" value="<?= config_item('project_color') ?>" id="example-color-input" name="project_color">
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-check form-check-right mb-3">
                            <input class="form-check-input" type="checkbox" id="milestone" <?php
                            if (config_item('milestone_on_calendar') == 'on') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="milestone_on_calendar">
                            <label class="form-check-label" for="milestone">
                                <?= lang('milestone') ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <input class="form-control form-control-color mw-100" type="color" value="<?= config_item('milestone_color') ?>" id="example-color-input" name="milestone_color">
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-check form-check-right mb-3">
                            <input class="form-check-input" type="checkbox" id="tasks"  <?php
                            if (config_item('tasks_on_calendar') == 'on') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="tasks_on_calendar">
                            <label class="form-check-label" for="tasks">
                                <?= lang('tasks') ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <input class="form-control form-control-color mw-100" type="color" value="<?= config_item('tasks_color') ?>" id="example-color-input" name="tasks_color">
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-check form-check-right mb-3">
                            <input class="form-check-input" type="checkbox" id="bugs" <?php
                            if (config_item('bugs_on_calendar') == 'on') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="bugs_on_calendar">
                            <label class="form-check-label" for="bugs">
                                <?= lang('bugs') ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <input class="form-control form-control-color mw-100" type="color" value="<?= config_item('bugs_color') ?>" id="example-color-input" name="bugs_color">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-check form-check-right mb-3">
                            <input class="form-check-input" type="checkbox" id="invoice" <?php
                            if (config_item('invoice_on_calendar') == 'on') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="invoice_on_calendar">
                            <label class="form-check-label" for="invoice">
                                <?= lang('invoice') ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <input class="form-control form-control-color mw-100" type="color" value="<?= config_item('invoice_color') ?>" id="example-color-input" name="invoice_color">
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-check form-check-right mb-3">
                            <input class="form-check-input" type="checkbox" id="payments" <?php
                            if (config_item('payments_on_calendar') == 'on') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="payments_on_calendar">
                            <label class="form-check-label" for="payments">
                                <?= lang('payments') ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <input class="form-control form-control-color mw-100" type="color" value="<?= config_item('payments_color') ?>" id="example-color-input" name="payments_color">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3"> 
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-check form-check-right mb-3">
                            <input class="form-check-input" type="checkbox" id="estimate" <?php
                            if (config_item('estimate_on_calendar') == 'on') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="estimate_on_calendar">
                            <label class="form-check-label" for="estimate">
                                <?= lang('estimate') ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <input class="form-control form-control-color mw-100" type="color" value="<?= config_item('estimate_color') ?>" id="example-color-input" name="estimate_color">
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-check form-check-right mb-3">
                            <input class="form-check-input" type="checkbox" id="opportunities" <?php
                            if (config_item('opportunities_on_calendar') == 'on') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="opportunities_on_calendar">
                            <label class="form-check-label" for="opportunities">
                                <?= lang('opportunities') ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <input class="form-control form-control-color mw-100" type="color" value="<?= config_item('opportunities_color') ?>" id="example-color-input" name="opportunities_color">
                    </div>
                </div>
            </div>               
        </div>
        <div class="row mb-3"> 
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-check form-check-right mb-3">
                            <input class="form-check-input" type="checkbox" id="goal_tracking" <?php
                            if (config_item('goal_tracking_on_calendar') == 'on') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="goal_tracking_on_calendar">
                            <label class="form-check-label" for="goal_tracking">
                                <?= lang('goal_tracking') ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <input class="form-control form-control-color mw-100" type="color" value="<?= config_item('goal_tracking_color') ?>" id="example-color-input" name="goal_tracking_color">
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-check form-check-right mb-3">
                            <input class="form-check-input" type="checkbox" id="holiday" <?php
                            if (config_item('holiday_on_calendar') == 'on') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="holiday_on_calendar">
                            <label class="form-check-label" for="holiday">
                                <?= lang('holiday') ?>
                            </label>
                        </div>
                    </div>
                    
                </div>
            </div>         
        </div>
        <div class="row mb-3"> 
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-check form-check-right mb-3">
                            <input class="form-check-input" type="checkbox" id="absent" <?php
                            if (config_item('absent_on_calendar') == 'on') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="absent_on_calendar">
                            <label class="form-check-label" for="absent">
                                <?= lang('absent') ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <input class="form-control form-control-color mw-100" type="color" value="<?= config_item('absent_color') ?>" id="example-color-input" name="absent_color">
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-check form-check-right mb-3">
                            <input class="form-check-input" type="checkbox" id="on_leave" <?php
                            if (config_item('on_leave_on_calendar') == 'on') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="on_leave_on_calendar">
                            <label class="form-check-label" for="on_leave">
                                <?= lang('on_leave') ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <input class="form-control form-control-color mw-100" type="color" value="<?= config_item('on_leave_color') ?>" id="example-color-input" name="on_leave_color">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('save') ?></button>            
        </div>
    </div>
</form>
