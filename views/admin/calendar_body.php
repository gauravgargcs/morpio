<?php $searchType = 'all'; ?>
<!-- Calendar -->
<link rel="stylesheet" href="<?=base_url();?>skote_assets/css/fullcalendar_main.css">
<link href="<?=base_url();?>skote_assets/libs/tui-calendar/tui-calendar.min.css" rel="stylesheet" type="text/css" />

<?php
echo message_box('success');
echo message_box('error');
?>
<div class="col-xl-3 col-lg-4">
    <div class="card">
        <div class="card-body">
            <div class="d-grid">
                <button class="btn font-16 btn-primary" id="btn-new-event"><i class="mdi mdi-plus-circle-outline"></i> <?=lang('Create_New_Event');?>
                </button>
            </div>

            <div id="external-events" class="mt-2">
                <br>
                <p class="text-muted"><?=lang('calendar_external_events');?></p>
                <div class="external-event fc-external-event text-success badge-soft-success" data-class="zoom">
                    <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i> <?= lang('Meeting_Event');?>
                </div>
                <div class="external-event fc-external-event text-info badge-soft-info" data-class="zoom">
                    <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i><?= lang('Video_Meeting');?>
                </div>
                <div class="external-event fc-external-event text-warning badge-soft-warning" data-class="zoom">
                    <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i><?= lang('Phone_Meeting');?>
                </div>
                <div class="external-event fc-external-event text-danger badge-soft-danger" data-class="tasks">
                    <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i><?= lang('task');?>
                </div>
                <div class="external-event fc-external-event text-dark badge-soft-dark" data-class="todo">
                    <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i><?= lang('reminder');?>
                </div>
                <div class="external-event fc-external-event text-danger badge-soft-danger" data-class="todo">
                    <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i><?= lang('Appointment_slots');?>
                </div>
                <div class="external-event fc-external-event text-info badge-soft-info" data-class="todo">
                    <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i><?= lang('to_do');?>
                </div>

            </div>

            <div class="row justify-content-center mt-5">
                <div class="col-lg-12 col-sm-6">
                    <img src="<?=base_url();?>skote_assets/images/verification-img.png" alt="" class="img-fluid d-block">
                </div>
            </div>

        </div>
    </div>
</div> <!-- end col-->

<div class="col-xl-9 col-lg-8">
    <div class="card">                   
        <div class="card-body">
            <div class="lnb-new-schedule float-sm-end ms-sm-3 mt-4 mt-sm-0">
                <div class="row">
                    <?php if ($this->session->userdata('user_type') == 1) { ?>
                    <a data-bs-toggle="modal" data-bs-target="#myModal_lg" href="<?= base_url() ?>admin/calendar/calendar_settings" class="text-default col-xl-1 col-1"><i class="fa fa-cogs"></i></a>
                    <?php } ?>
                    <div class="dropdown col-3 col-xl-4">
                        <button class="btn btn-primary dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?php if (!empty($searchType)) {
                                echo lang($searchType);
                            } else {
                                echo lang('all');
                            } ?>
                            <i class="mdi mdi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="<?= base_url() ?>admin/calendar/index/search/all"><?= lang('all') ?></a>
                            <a class="dropdown-item" href="<?= base_url() ?>admin/calendar/index/search/todo"><?= lang('to_do') ?></a> 
                            <a class="dropdown-item" href="<?= base_url() ?>admin/calendar/index/search/zoom"><?= lang('zoom') ?></a> 
                            <?php if (config_item('project_on_calendar') == 'on') { ?>
                                <a class="dropdown-item" href="<?= base_url() ?>admin/calendar/index/search/projects"><?= lang('project') ?></a> 
                            <?php } ?>
                            <?php if (config_item('milestone_on_calendar') == 'on') { ?>
                                <a class="dropdown-item" href="<?= base_url() ?>admin/calendar/index/search/milestones"><?= lang('milestone') ?></a> 
                            <?php } ?>
                            <?php if (config_item('tasks_on_calendar') == 'on') { ?>
                                <a class="dropdown-item" href="<?= base_url() ?>admin/calendar/index/search/tasks"><?= lang('tasks') ?></a> 
                            <?php } ?>
                            <?php if (config_item('bugs_on_calendar') == 'on') { ?>
                                <a class="dropdown-item" href="<?= base_url() ?>admin/calendar/index/search/bugs"><?= lang('bugs') ?></a> 
                            <?php } ?>
                            <?php if (config_item('invoice_on_calendar') == 'on') { ?>
                                <a class="dropdown-item" href="<?= base_url() ?>admin/calendar/index/search/invoices"><?= lang('invoice') ?></a> 
                            <?php } ?>
                            <?php if (config_item('payments_on_calendar') == 'on') { ?>
                                <a class="dropdown-item" href="<?= base_url() ?>admin/calendar/index/search/payments"><?= lang('payments') ?></a> 
                            <?php } ?>
                            <?php if (config_item('estimate_on_calendar') == 'on') { ?>
                                <a class="dropdown-item" href="<?= base_url() ?>admin/calendar/index/search/estimates"><?= lang('estimate') ?></a> 
                            <?php } ?>
                            <?php if (config_item('opportunities_on_calendar') == 'on') { ?>
                                <a class="dropdown-item" href="<?= base_url() ?>admin/calendar/index/search/opportunities"><?= lang('opportunities') ?></a> 
                            <?php } ?>
                            <?php if (config_item('goal_tracking_on_calendar') == 'on') { ?>
                                <a class="dropdown-item" href="<?= base_url() ?>admin/calendar/index/search/goal"><?= lang('goal_tracking') ?></a> 
                            <?php } ?>
                            <?php if (config_item('holiday_on_calendar') == 'on') { ?>
                                <a class="dropdown-item" href="<?= base_url() ?>admin/calendar/index/search/holiday"><?= lang('holiday') ?></a> 
                            <?php } ?>
                            <?php if (config_item('absent_on_calendar') == 'on') { ?>
                                <a class="dropdown-item" href="<?= base_url() ?>admin/calendar/index/search/absent"><?= lang('absent') ?></a> 
                            <?php } ?>
                            <?php if (config_item('on_leave_on_calendar') == 'on') { ?>
                                <a class="dropdown-item" href="<?= base_url() ?>admin/calendar/index/search/on_leave"><?= lang('on_leave') ?></a> 
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="calendarList" class="lnb-calendars-d1 mt-4 mt-sm-0 me-sm-0 mb-4"></div>

            <div id="calendar"></div>
           
            <!-- Add New Event MODAL -->
            <div class="modal fade" id="event-modal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header py-3 px-4 border-bottom-0">
                            <h5 class="modal-title" id="modal-title"><?=lang('event');?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <form class="needs-validation" name="event-form" id="form-event" novalidate>
                            <div class="modal-body wrap-modal wrap px-4">
                                <div class="row mb-3">
                                    <div class="col-xl-12">
                                        <select class="form-control form-select" name="category" id="event-category">
                                            <option selected value="todo"><?php echo lang('to_do'); ?></option>
                                            <option value="zoom"><?php echo lang('zoom'); ?></option>
                                            <?php if (config_item('tasks_on_calendar') == 'on') { ?>
                                            <option value="tasks"><?= lang('tasks') ?></option> 
                                            <?php } ?>
                                            <?php if (config_item('project_on_calendar') == 'on') { ?>
                                            <option value="projects"><?= lang('project') ?></option> 
                                            <?php } ?>
                                            <option value="allbiz_video_meet" disabled><?php echo lang('allbiz_video_meet'); ?> (Coming soon)</option>
                                            <option value="google_meeting" disabled><?php echo lang('google_meeting'); ?> (Coming soon)</option>
                                            
                                        </select>
                                        <input type="text" name="category" id="event-category-input" readonly class="form-control" value="">
                                        <div class="invalid-feedback"><?=lang('event_cat_error');?></div>
                                    </div>
                                </div>                                        
                                <div class="row mb-3">
                                    <div class="col-xl-12">
                                        <div class="input-group">
                                            <div class="input-group-text"><span class="tui-full-calendar-icon tui-full-calendar-ic-title"></span></div>
                                            <input class="form-control" placeholder="<?=lang('name');?>" type="text" name="title" id="event-title" required value="" />
                                            <input type="hidden" name="event-id" id="event-id" value="" class="form-control">
                                        </div>        
                                        <div class="invalid-feedback"><?=lang('event_name_error');?></div>
                                    </div>
                                </div>
                                <div class="row mb-3 event-notes">
                                    <div class="col-xl-12">
                                        <div class="input-group">
                                            <div class="input-group-text"><span class="tui-full-calendar-icon"><i class="far fa-sticky-note"></i></span></div>
                                            <textarea class="form-control" placeholder="<?=lang('notes');?>" name="notes" id="event-notes"></textarea>
                                        </div>        
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-xl-6">
                                        <div class="input-group"> 
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            <input type="text" name="start_date" id="event-start_date" autocomplete="off" required="" class="form-control" value="">
                                        </div>   
                                        <div class="invalid-feedback"><?=lang('event_start_date_error');?></div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-group"> 
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            <input type="text" name="end_date" id="event-end_date" autocomplete="off" required="" class="form-control" value="">
                                        </div>   
                                        <div class="invalid-feedback"><?=lang('event_end_date_error');?></div>
                                    </div>
                                 
                                </div>
                                <div class="row mb-3 view_link">
                                    <div class="col-xl-12">
                                        <div class="input-group">
                                            <div class="input-group-text"><a href="#" id="event-link-a"><i class="fas fa-external-link-alt"></i></a></div>
                                            <input type="text" readonly="true" name="event-link" id="event-link" value="" placeholder="<?=lang('link');?>" class="form-control">
                                            <input type="hidden" name="event-location" id="event-location" value="" class="form-control">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <button type="button" class="btn btn-danger" id="btn-delete-event"><?=lang('delete');?></button>
                                        </div>
                                        <div class="col-xl-6 text-end">
                                            <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal"><?=lang('close');?></button>
                                            <button type="submit" class="btn btn-success" id="btn-save-event"><?=lang('save');?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div> <!-- end modal-content-->
                </div> <!-- end modal dialog-->
            </div>
            <!-- end modal-->

        </div>
    </div>
</div>



<script src="<?=base_url();?>skote_assets/js/pages/calendars.js"></script>
<script src="<?=base_url();?>skote_assets/js/pages/fullcalendar-main.js"></script>
<!-- Calendar -->
<script src="<?=base_url();?>skote_assets/libs/moment/min/moment.min.js"></script>
<script src="<?=base_url();?>skote_assets/calentim-date-time-range-picker/build/js/calentim.min.js"></script>

<script src="<?=base_url();?>skote_assets/libs/chance/chance.min.js"></script>
<?php $this->load->view('admin/fullcalendar_js'); ?>
<style type="text/css">
    option:disabled {
       background-color: #ddd !important;
    }

</style>
<script type="text/javascript">
    $("#event-start_date").calentim({
        singleDate: true,
        calendarCount: 1,
        showHeader: false,
        showFooter: false,
        autoCloseOnSelect: true,
        format: "DD-MM-YYYY HH:mm",
    });
    $("#event-end_date").calentim({
        singleDate: true,
        calendarCount: 1,
        showHeader: false,
        showFooter: false,
        autoCloseOnSelect: true,
        format: "DD-MM-YYYY HH:mm",
    });

</script>