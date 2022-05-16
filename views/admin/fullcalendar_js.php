
<script type="text/javascript">
$(document).ready(function () {

    !function ($) {
        "use strict";
        var CalendarPage = function () { };
        CalendarPage.prototype.init = function () {

            var addEvent = $("#event-modal");
            var modalTitle = $("#modal-title");
            var formEvent = $("#form-event");
            var selectedEvent = null;
            var newEventData = null;
            var forms = document.getElementsByClassName('needs-validation');
            var selectedEvent = null;
            var newEventData = null;
            var eventObject = null;
            /* initialize the calendar */

            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();
            var dateTimeFormat="dd-mm-yyyy HH:MM".toUpperCase();
            var formattedDate= moment(date).format(dateTimeFormat);             
            var Draggable = FullCalendar.Draggable;
            var externalEventContainerEl = document.getElementById('external-events');

            var defaultEvents = [
                <?php 
                if($role == 1){
                if (config_item('payments_on_calendar') == 'on') {
                if (!empty($searchType) && ($searchType == 'payments' || $searchType == 'all')) {
                    $payments_info = $this->db->get('tbl_payments')->result();
                }
                if (!empty($payments_info)) {
                foreach ($payments_info as $v_payments) :
                $invoice = $this->db->where(array('invoices_id' => $v_payments->invoices_id))->get('tbl_invoices')->row();
                $client_info = $this->db->where(array('client_id' => $invoice->client_id))->get('tbl_client')->row();
                $currency = $this->admin_model->client_currency_sambol($invoice->client_id);
                if($client_info){
                ?>
                {   
                    id: String(chance.guid()),
                    title: '<?= $client_info->name . " (" . $currency->symbol . $v_payments->amount . ")" ?>',  
                    start: moment('<?= $v_payments->payment_date ?>').format(dateTimeFormat),
                    end: moment('<?= $v_payments->payment_date ?>').format(dateTimeFormat),
                    notes:'',
                    color: payments_color, 
                    link:'<?= base_url() ?>admin/invoice/manage_invoice/payments_details/<?= $v_payments->payments_id ?>',
                    location: '<a href="<?= base_url() ?>admin/invoice/manage_invoice/payments_details/<?= $v_payments->payments_id ?>"><?=lang('view_details');?></a>',
                    goingDuration: 30, 
                    comingDuration: 30, 
                    isVisible: true, 
                    bgColor: '#69BB2D', 
                    dragBgColor: '#69BB2D', 
                    borderColor: payments_color, 
                    calendarId: 'payments', 
                    id:'<?= $v_payments->payments_id ?>',
                    category: 'time', 
                    dueDateClass: '', 
                    customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, 
                    attendees: '', recurrenceRule: '', state: '', 
                },

                <?php } endforeach;
                } } } ?>
                
                <?php
                if (!empty($searchType) && ($searchType == 'todo' || $searchType == 'all')) {
                    // $todo_info = $this->admin_model->get_permission('tbl_todo');
                    $t_where = array('user_id' => $this->session->userdata('user_id'));
                    $todo_info = $this->db->where($t_where)->order_by('todo_id', 'DESC')->get('tbl_todo')->result();
                }
                if (!empty($todo_info)) {
                foreach ($todo_info as $v_todo) :
                    $todo_name = str_replace( array( '\'', '"',',' , ';', '<', '>','\n' ), ' ', $v_todo->title);
                ?>
                {
                    title: '<?php echo trim($todo_name) ?>',
                    start: '<?= date('Y-m-d', strtotime($v_todo->due_date)) ?>',
                    end: '<?= date('Y-m-d', strtotime($v_todo->due_date)) ?>',
                    color: tasks_color,
                    notes:"<?=str_replace( array( '\'', '"',',' , ';',"/[ \t]+/", '/\s*$^\s*/m', "/[\r\n]+/","/\s+/", "\r\n", "\r", "\n", "\t"), ' ', strip_tags($v_todo->notes)); ?>",
                    link:'',
                    location: '',
                    goingDuration: 30, 
                    comingDuration: 30, 
                    isVisible: true, 
                    bgColor: '#69BB2D', 
                    dragBgColor: '#69BB2D', 
                    borderColor: tasks_color, 
                    calendarId: 'todo', 
                    id:'<?= $v_todo->todo_id ?>',
                    category: 'time', 
                    dueDateClass: '', 
                    customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, 
                    attendees: '', recurrenceRule: '', state: '',editable: true,
                },
                <?php endforeach;
                } ?>

                <?php
                if (!empty($searchType) && ($searchType == 'zoom' || $searchType == 'all')) {
                    $zoom_info = $this->admin_model->get_permission('tbl_zoom_meeting');
                }
                if (!empty($zoom_info)) {
                foreach ($zoom_info as $v_zoom) :
                    $zoom_name = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', $v_zoom->topic);
                ?>
                {
                    title: '<?php echo trim($zoom_name) ?>',
                    start: '<?= date('Y-m-d', strtotime($v_zoom->meeting_time)) ?>',
                    end: '<?= date('Y-m-d', strtotime($v_zoom->meeting_time)) ?>',
                    color: tasks_color,
                    notes:"<?= str_replace( array( '\'', '"',',' , ';',"/[ \t]+/", '/\s*$^\s*/m', "/[\r\n]+/","/\s+/", "\r\n", "\r", "\n", "\t"), ' ', strip_tags($v_zoom->notes)); ?>",
                    link:'',
                    location: '',
                    goingDuration: 30, 
                    comingDuration: 30, 
                    isVisible: true, 
                    bgColor: '#69BB2D', 
                    dragBgColor: '#69BB2D', 
                    borderColor: tasks_color, 
                    calendarId: 'zoom', 
                    id:'<?= $v_zoom->zoom_meeting_id ?>',
                    category: 'time', 
                    dueDateClass: '', 
                    customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, 
                    attendees: '', recurrenceRule: '', state: '',editable: true,
                },
                <?php endforeach;
                } ?>


                <?php
                if (config_item('invoice_on_calendar') == 'on') {
                if (!empty($searchType) && ($searchType == 'invoices' || $searchType == 'all')) {
                    $invoice_info = $this->admin_model->get_permission('tbl_invoices');
                }
                if (!empty($invoice_info)) {
                foreach ($invoice_info as $v_invoice) :
                    $reference= $v_invoice->reference_no;
                    
                ?>
                {
                    id: String(chance.guid()),
                    title: '<?=trim($reference);?>',
                    start: '<?= date('Y-m-d', strtotime($v_invoice->due_date)) ?>',
                    end: '<?= date('Y-m-d', strtotime($v_invoice->due_date)) ?>',
                    notes:'',
                    color: invoice_color,
                    link:'<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_invoice->invoices_id ?>',
                    location: '<a href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_invoice->invoices_id ?>"><?=lang('view_details');?></a>',
                    goingDuration: 30, 
                    comingDuration: 30, 
                    isVisible: true, 
                    bgColor: '#69BB2D', 
                    dragBgColor: '#69BB2D', 
                    borderColor: invoice_color, 
                    calendarId: 'invoices', 
                    id:'<?= $v_invoice->invoices_id ?>',
                    category: 'time', 
                    dueDateClass: '', 
                    customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, 
                    attendees: '', recurrenceRule: '', state: '',
                },
                <?php endforeach;
                } } ?>

                <?php
                if (config_item('estimate_on_calendar') == 'on') {
                if (!empty($searchType) && ($searchType == 'estimates' || $searchType == 'all')) {
                    $estimates_info = $this->admin_model->get_permission('tbl_estimates');
                }
                if (!empty($estimates_info)) {
                foreach ($estimates_info as $v_estimates) :
                ?>
                {
                    id: String(chance.guid()),
                    title: '<?php echo $v_estimates->reference_no ?>',
                    start: '<?= date('Y-m-d', strtotime($v_estimates->due_date)) ?>',
                    end: '<?= date('Y-m-d', strtotime($v_estimates->due_date)) ?>',
                    color: estimate_color,
                    notes:'',
                    link:'<?= base_url() ?>admin/estimates/index/estimates_details/<?= $v_estimates->estimates_id ?>',
                    location: '<a href="<?= base_url() ?>admin/estimates/index/estimates_details/<?= $v_estimates->estimates_id ?>"><?=lang('view_details');?></a>',
                    goingDuration: 30, 
                    comingDuration: 30, 
                    isVisible: true, 
                    bgColor: '#69BB2D', 
                    dragBgColor: '#69BB2D', 
                    borderColor: estimate_color, 
                    calendarId: 'estimates', 
                    id:'<?= $v_estimates->estimates_id ?>',
                    category: 'time', 
                    dueDateClass: '', 
                    customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, 
                    attendees: '', recurrenceRule: '', state: '',
                },
                <?php  endforeach;
                } } ?>

                <?php
                if (config_item('project_on_calendar') == 'on') {
                if (!empty($searchType) && ($searchType == 'projects' || $searchType == 'milestones' || $searchType == 'all')) {
                    $project_info = $this->admin_model->get_permission('tbl_project');
                }
                if (!empty($project_info)) {
                foreach ($project_info as $v_project) :
                if (!empty($searchType) && ($searchType == 'projects' || $searchType == 'all')) {
                if(!empty($v_project)){
                ?>
                {
                    id: String(chance.guid()),
                    title: '<?php echo $v_project->project_name ?>',
                    start: '<?= date('Y-m-d', strtotime($v_project->end_date)) ?>',
                    end: '<?= date('Y-m-d', strtotime($v_project->end_date)) ?>',
                    notes:"<?=str_replace( array( '\'', '"',',' , ';',"/[ \t]+/", '/\s*$^\s*/m', "/[\r\n]+/","/\s+/", "\r\n", "\r", "\n", "\t"), ' ', strip_tags($v_project->description)); ?>",
                    color: project_color,
                    link:'<?= base_url() ?>admin/projects/project_details/<?= $v_project->project_id ?>',
                    location: '<a href="<?= base_url() ?>admin/projects/project_details/<?= $v_project->project_id ?>"><?=lang('view_details');?></a>',
                    goingDuration: 30, 
                    comingDuration: 30, 
                    isVisible: true, 
                    bgColor: '#69BB2D', 
                    dragBgColor: '#69BB2D', 
                    borderColor: project_color, 
                    calendarId: 'projects', 
                    id:'<?= $v_project->project_id ?>',
                    category: 'time', 
                    dueDateClass: '', 
                    customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, 
                    attendees: '', recurrenceRule: '', state: '',editable: true,
                },
                <?php } } endforeach; } }  ?>

                <?php  if (config_item('milestone_on_calendar') == 'on') {
                if (!empty($searchType) && ($searchType == 'milestones' || $searchType == 'all')) {
                    $milestone_info = $this->admin_model->get_permission('tbl_milestones');;
                }
                if (!empty($milestone_info)) {
                foreach ($milestone_info as $v_milestone) :
                ?>
                {
                    title: '<?php echo $v_milestone->milestone_name ?>',
                    start: '<?= date('Y-m-d', strtotime($v_milestone->end_date)) ?>',
                    end: '<?= date('Y-m-d', strtotime($v_milestone->end_date)) ?>',
                    color: milestone_color,
                    notes:'',
                    link:'<?= base_url() ?>admin/projects/project_details/<?= $v_project->project_id ?>/5',
                    location: '<a href="<?= base_url() ?>admin/projects/project_details/<?= $v_project->project_id ?>/5"><?=lang('view_details');?></a>',
                    goingDuration: 30, 
                    comingDuration: 30, 
                    isVisible: true, 
                    bgColor: '#69BB2D', 
                    dragBgColor: '#69BB2D', 
                    borderColor: milestone_color, 
                    calendarId: 'milestones', 
                    id:'<?= $v_project->project_id ?>',
                    category: 'time', 
                    dueDateClass: '', 
                    customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, 
                    attendees: '', recurrenceRule: '', state: '',
                },

                <?php endforeach;
                }
                } ?>

                <?php
                if (config_item('tasks_on_calendar') == 'on') {
                if (!empty($searchType) && ($searchType == 'tasks' || $searchType == 'all')) {
                    $task_info = $this->admin_model->get_permission('tbl_task');
                }
                if (!empty($task_info)) {
                foreach ($task_info as $v_task) :
                    $task_name = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', $v_task->task_name);
                    $task_description = str_replace( array( '\'', '"',',' , ';',"/[ \t]+/", '/\s*$^\s*/m', "/[\r\n]+/","/\s+/", "\r\n", "\r", "\n", "\t"), ' ', strip_tags($v_task->task_description));
                ?>
                {
                    title: '<?php echo $task_name ?>',
                    start: '<?= date('Y-m-d', strtotime($v_task->due_date)) ?>',
                    end: '<?= date('Y-m-d', strtotime($v_task->due_date)) ?>',
                    notes:"<?=strip_tags($task_description); ?>",
                    color: tasks_color,
                    link:'<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>',
                    location: '<a href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>"><?=lang('view_details');?></a>',
                    goingDuration: 30, 
                    comingDuration: 30, 
                    isVisible: true, 
                    bgColor: '#69BB2D', 
                    dragBgColor: '#69BB2D', 
                    borderColor: tasks_color, 
                    calendarId: 'tasks', 
                    id:'<?= $v_task->task_id ?>',
                    category: 'time', 
                    dueDateClass: '', 
                    customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, 
                    attendees: '', recurrenceRule: '', state: '',editable: true,
                },
                <?php endforeach;
                }
                } ?>

               <?php if (config_item('bugs_on_calendar') == 'on') {
                if (!empty($searchType) && ($searchType == 'bugs' || $searchType == 'all')) {
                    $bug_info = $this->admin_model->get_permission('tbl_bug');
                }
                if (!empty($bug_info)) {
                foreach ($bug_info as $v_bug) : ?>
                {
                    title: '<?php echo $v_bug->bug_title ?>',
                    start: '<?= date('Y-m-d', strtotime($v_bug->created_time)) ?>',
                    end: '<?= date('Y-m-d', strtotime($v_bug->created_time)) ?>',
                    color: bugs_color,
                    notes:'',
                    link:'<?= base_url() ?>admin/bugs/view_bug_details/<?= $v_bug->bug_id ?>',
                    location: '<a href="<?= base_url() ?>admin/bugs/view_bug_details/<?= $v_bug->bug_id ?>"><?=lang('view_details');?></a>',
                    goingDuration: 30, 
                    comingDuration: 30, 
                    isVisible: true, 
                    bgColor: '#69BB2D', 
                    dragBgColor: '#69BB2D', 
                    borderColor: bugs_color, 
                    calendarId: 'bugs', 
                    id:'<?= $v_bug->bug_id ?>',
                    category: 'time', 
                    dueDateClass: '', 
                    customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, 
                    attendees: '', recurrenceRule: '', state: '',
                },
                <?php endforeach;
                }
                } ?>

               <?php  if (config_item('opportunities_on_calendar') == 'on') {
                if (!empty($searchType) && ($searchType == 'opportunities' || $searchType == 'all')) {
                    $opportunity_info = $this->admin_model->get_permission('tbl_opportunities');
                }
                if (!empty($opportunity_info)) {
                foreach ($opportunity_info as $v_opportunity) : ?>
                {
                    title: '<?php echo $v_opportunity->opportunity_name ?>',
                    start: '<?= date('Y-m-d', strtotime($v_opportunity->close_date)) ?>',
                    end: '<?= date('Y-m-d', strtotime($v_opportunity->close_date)) ?>',
                    notes:'',
                    color: opportunities_color,
                    link:'<?= base_url() ?>admin/opportunities/opportunity_details/<?= $v_opportunity->opportunities_id ?>',
                    location: '<a href="<?= base_url() ?>admin/opportunities/opportunity_details/<?= $v_opportunity->opportunities_id ?>"><?=lang('view_details');?></a>',
                    goingDuration: 30, 
                    comingDuration: 30, 
                    isVisible: true, 
                    bgColor: '#69BB2D', 
                    dragBgColor: '#69BB2D', 
                    borderColor: opportunities_color, 
                    calendarId: 'opportunities', 
                    id:'<?= $v_opportunity->opportunities_id ?>',
                    category: 'time', 
                    dueDateClass: '', 
                    customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, 
                    attendees: '', recurrenceRule: '', state: '',
                },
                {
                    title: '<?php echo $v_opportunity->next_action ?>',
                    start: '<?= date('Y-m-d', strtotime($v_opportunity->next_action_date)) ?>',
                    end: '<?= date('Y-m-d', strtotime($v_opportunity->next_action_date)) ?>',
                    color: opportunities_color,
                    notes:'',
                    link:'<?= base_url() ?>admin/opportunities/opportunity_details/<?= $v_opportunity->opportunities_id ?>',
                    location: '<a href="<?= base_url() ?>admin/opportunities/opportunity_details/<?= $v_opportunity->opportunities_id ?>"><?=lang('view_details');?></a>',
                    goingDuration: 30, 
                    comingDuration: 30, 
                    isVisible: true, 
                    bgColor: '#69BB2D', 
                    dragBgColor: '#69BB2D', 
                    borderColor: opportunities_color, 
                    calendarId: 'opportunities', 
                    id:'<?= $v_opportunity->opportunities_id ?>',
                    category: 'time', 
                    dueDateClass: '', 
                    customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, 
                    attendees: '', recurrenceRule: '', state: '',
                },
                <?php endforeach;
                }
                } ?>
               <?php
                if (config_item('holiday_on_calendar') == 'on') {
                if (!empty($searchType) && ($searchType == 'holiday' || $searchType == 'all')) {
                    $holiday_info = $this->db->get('tbl_holiday')->result();
                }
                if (!empty($holiday_info)) {
                foreach ($holiday_info as $v_holiday) :
                ?>
                {
                    title: '<?php echo $v_holiday->event_name ?>',
                    start: '<?= date('Y-m-d', strtotime($v_holiday->start_date)) ?>',
                    end: '<?= date('Y-m-d', strtotime($v_holiday->end_date)) ?>',
                    notes:'',
                    color: '<?= $v_holiday->color?>',
                    link:'<?= base_url() ?>admin/holiday/index/<?= $v_holiday->holiday_id ?>',
                    location: '<a href="<?= base_url() ?>admin/holiday/index/<?= $v_holiday->holiday_id ?>"><?=lang('view_details');?></a>',
                    goingDuration: 30, 
                    comingDuration: 30, 
                    isVisible: true, 
                    bgColor: '#69BB2D', 
                    dragBgColor: '#69BB2D', 
                    borderColor: '<?= $v_holiday->color?>', 
                    calendarId: 'holiday', 
                    id:'<?= $v_holiday->holiday_id ?>',
                    category: 'time', 
                    dueDateClass: '', 
                    customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, 
                    attendees: '', recurrenceRule: '', state: '',
                },
                <?php  endforeach;
                }
                } ?>
               <?php if (config_item('goal_tracking_on_calendar') == 'on') {
                if (!empty($searchType) && ($searchType == 'goal' ||  $searchType == 'all')) {
                    $all_goal_tracking = $this->admin_model->get_permission('tbl_goal_tracking');
                }
                if (!empty($all_goal_tracking)){foreach ($all_goal_tracking as $v_goal_tracking):?>
                {
                    title: '<?php echo $v_goal_tracking->subject ?>',
                    start: '<?= date('Y-m-d', strtotime($v_goal_tracking->end_date)) ?>',
                    end: '<?= date('Y-m-d', strtotime($v_goal_tracking->end_date)) ?>',
                    notes:'',
                    color: goal_tracking_color,
                    link:'<?= base_url() ?>admin/goal_tracking/goal_details/<?= $v_goal_tracking->goal_tracking_id ?>',
                    location: '<a href="<?= base_url() ?>admin/goal_tracking/goal_details/<?= $v_goal_tracking->goal_tracking_id ?>"><?=lang('view_details');?></a>',
                    goingDuration: 30, 
                    comingDuration: 30, 
                    isVisible: true, 
                    bgColor: '#69BB2D', 
                    dragBgColor: '#69BB2D', 
                    borderColor: goal_tracking_color, 
                    calendarId: 'goal', 
                    id:'<?= $v_goal_tracking->goal_tracking_id ?>',
                    category: 'time', 
                    dueDateClass: '', 
                    customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, 
                    attendees: '', recurrenceRule: '', state: '',
                },
                <?php endforeach;
                }
                } ?>

                <?php  if (config_item('absent_on_calendar') == 'on') {
                if (!empty($searchType) && ($searchType == 'absent' || $searchType == 'all')) {
                    $absent_info = get_result('tbl_attendance', array('attendance_status' => 0));
                }
                if (!empty($absent_info)) {
                foreach ($absent_info as $v_absent) {
                $absent_user = $this->db->where('user_id', $v_absent->user_id)->get('tbl_account_details')->row();
                if (!empty($absent_user)) {?>
                {
                    title: '<?php echo $absent_user->fullname ?>',
                    start: '<?= date('Y-m-d', strtotime($v_absent->date_in)) ?>',
                    end: '<?= date('Y-m-d', strtotime($v_absent->date_in)) ?>',
                    color: absent_color,
                    notes:'',
                    link:'<?= base_url() ?>admin/user/user_details/<?= $absent_user->user_id ?>',
                    location: '<a href="<?= base_url() ?>admin/user/user_details/<?= $absent_user->user_id ?>"><?=lang('view_details');?></a>',
                    goingDuration: 30, 
                    comingDuration: 30, 
                    isVisible: true, 
                    bgColor: '#69BB2D', 
                    dragBgColor: '#69BB2D', 
                    borderColor: absent_color, 
                    calendarId: 'absent', 
                    id:'<?= $absent_user->user_id ?>',
                    category: 'time', 
                    dueDateClass: '', 
                    customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, 
                    attendees: '', recurrenceRule: '', state: '',
                },
                <?php  };
                }
                }
                } ?>

                <?php if (config_item('on_leave_on_calendar') == 'on') {
                if (!empty($searchType) && ($searchType == 'on_leave' || $searchType == 'all')) {
                    $leave_info = get_result('tbl_attendance', array('attendance_status' => 3));
                }
                if (!empty($leave_info)) {
                foreach ($leave_info as $v_leave) :
                $leave_user = $this->db->where('user_id', $v_leave->user_id)->get('tbl_account_details')->row();
                if(!empty($leave_user)){
                $l_start_day = date('d', strtotime($v_leave->date_in));
                $l_smonth = date('n', strtotime($v_leave->date_in));
                $l_start_month = $l_smonth - 1;
                $l_start_year = date('Y', strtotime($v_leave->date_in));
                $l_end_year = date('Y', strtotime($v_leave->date_in));
                $l_end_day = date('d', strtotime($v_leave->date_in));
                $l_emonth = date('n', strtotime($v_leave->date_in));
                $l_end_month = $l_emonth - 1; ?>
                {
                    title: '<?php echo $leave_user->fullname ?>',
                    start: '<?= date('Y-m-d', strtotime($v_leave->date_in)) ?>',
                    end: '<?= date('Y-m-d', strtotime($v_leave->date_in)) ?>',
                    color: on_leave_color,
                    notes:'',
                    link:'<?= base_url() ?>admin/user/user_details/<?= $leave_user->user_id ?>',
                    location: '<a href="<?= base_url() ?>admin/user/user_details/<?= $leave_user->user_id ?>"><?=lang('view_details');?></a>',
                    goingDuration: 30, 
                    comingDuration: 30, 
                    isVisible: true, 
                    bgColor: '#69BB2D', 
                    dragBgColor: '#69BB2D', 
                    borderColor: on_leave_color, 
                    calendarId: 'on_leave', 
                    id:'<?= $leave_user->user_id ?>',
                    category: 'time', 
                    dueDateClass: '', 
                    customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, 
                    attendees: '', recurrenceRule: '', state: '',
                },
                <?php }
                endforeach;
                }
                } ?>

                <?php if(!empty($gcal_id)){?>
                {
                    googleCalendarId: '<?=$gcal_id?>'
                }
                <?php }?>
            ];


            var calendarEl = document.getElementById('calendar');

            function addNewEvent(info) {
                $('.view_link').hide();
                $('#event-category-input').hide();
                addEvent.modal('show');
                formEvent.removeClass("was-validated");
                formEvent[0].reset();

                $("#event-title").val();
                $("#event-notes").val();
                $('#event-category').val();
                $('#event-location').val();
                $('#event-link').val();
                $('#event-start_date').val(formattedDate);
                $('#event-end_date').val(formattedDate);

                modalTitle.text('Add Event');
                newEventData = info;
            }
            function getInitialView() {
                if (window.innerWidth >= 768 && window.innerWidth < 1200) {
                    return 'timeGridWeek';
                } else if (window.innerWidth <= 768) {
                    return 'listMonth';
                } else {
                    return 'dayGridMonth';
                }
            }

            var calendar = new FullCalendar.Calendar(calendarEl, {
                timeZone: 'local',
                editable: true,
                droppable: true,
                selectable: true,
                initialView: getInitialView(),
                themeSystem: 'bootstrap',
                // responsive
                windowResize: function (view) {
                    var newView = getInitialView();
                    calendar.changeView(newView);
                },
                eventDidMount: function (info) {
                    if (info.event.extendedProps.status === 'done') {

                        // Change background color of row
                        info.el.style.backgroundColor = 'red';

                        // Change color of dot marker
                        var dotEl = info.el.getElementsByClassName('fc-event-dot')[0];
                        if (dotEl) {
                            dotEl.style.backgroundColor = 'white';
                        }
                    }
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                eventClick: function (info) {
                    
                    addEvent.modal('show');
                    formEvent[0].reset();
                    selectedEvent = info.event;
                    if(selectedEvent._def.extendedProps.link=="" || selectedEvent._def.extendedProps.link==undefined){
                        $('.view_link').hide();
                    }else{
                        $('.view_link').show();
                    }

                    
                    if(selectedEvent._def.extendedProps.calendarId=="todo" || selectedEvent._def.extendedProps.calendarId=="zoom" || selectedEvent._def.extendedProps.calendarId=="projects" || selectedEvent._def.extendedProps.calendarId=="tasks"){
                        $('#btn-save-event').removeAttr('disabled'); 
                        $('#btn-delete-event').removeAttr('disabled');
                        $('#event-category-input').hide();
                        $('#event-category').show();
                        $('.event-notes').show();
                    }else{
                        if(selectedEvent._def.extendedProps.notes=="" || selectedEvent._def.extendedProps.notes==undefined){
                            $('.event-notes').hide();
                        }else{
                            $('.event-notes').show();
                        }

                        $('#event-category').hide();
                        $('#event-category-input').show();
                        $('#btn-save-event').attr("disabled", true);
                        $('#btn-delete-event').attr("disabled", true);
                    }
                    $("#event-title").val(selectedEvent.title);
                    $("#event-notes").val(selectedEvent._def.extendedProps.notes);
                    $('#event-category').val(selectedEvent._def.extendedProps.calendarId);
                    $('#event-category-input').val(selectedEvent._def.extendedProps.calendarId);
                    $('#event-location').val('<a href="'+selectedEvent._def.extendedProps.link+'"><?=lang('view_details');?></a>');
                    $('#event-link').val(selectedEvent._def.extendedProps.link);
                    $('#event-link-a').prop("href", selectedEvent._def.extendedProps.link);
                    $('#event-start_date').val(moment(selectedEvent._instance.range.start).format(dateTimeFormat));
                    $('#event-end_date').val(moment(selectedEvent._instance.range.end).format(dateTimeFormat));
                    $('#event-id').val(selectedEvent._def.publicId);
                   
                    newEventData = null;
                    modalTitle.text('Edit Event');
                    newEventData = null;
                },
                dateClick: function (info) {
                    addNewEvent(info);
                },
                eventReceive: function(info) {
                    if (!confirm("Are you sure about this change?")) {
                      info.revert();
                    }
                    var formData = {
                        'title': info.event.title,
                        'notes': info.event._def.extendedProps.notes,
                        'start_date': moment(info.event._instance.range.start).format(dateTimeFormat),
                        'end_date': moment(info.event._instance.range.end).format(dateTimeFormat),
                        'calendarId': info.event._def.extendedProps.calendarId,
                        'id': '',
                    }
                    $.ajax({
                        url: '<?=base_url('admin/calendar/calendarEvent');?>',
                        data: formData,
                        type: 'POST',
                        success: function(res){
                            addEvent.modal('hide');
                            // window.location.reload(); 
                        },
                    }); 

                },                
                events: defaultEvents,
                googleCalendarApiKey: gcal_api_key,
             
            });
            calendar.render();

            // init dragable
            new Draggable(externalEventContainerEl, {
                itemSelector: '.external-event',
                eventData: function (eventEl) {
                    return {
                        title: eventEl.innerText,
                        calendarId: $(eventEl).data('class'),
                    };
                },
            });

            /*Add new event*/
            // Form to add new event

            $(formEvent).on('submit', function (ev) {
                ev.preventDefault();
                var inputs = $('#form-event :input');
                var updatedTitle = $("#event-title").val();
                var updatedNotes = $("#event-notes").val();
                var updatedCategory = $('#event-category').val();
                var updatedLink = $('#event-link').val();
                var updatedLocation = '<a href="'+updatedLink+'"><?=lang('view_details');?></a>';
                var updatedStartDate = $('#event-start_date').val();
                var updatedEndDate = $('#event-end_date').val();
                var updatedId = $('#event-id').val();
               
                // validation
                if (forms[0].checkValidity() === false) {
                    // event.preventDefault();
                    // event.stopPropagation();
                    forms[0].classList.add('was-validated');
                } else {
                    var formData={};
                    if (selectedEvent) {
                        selectedEvent.setProp("title", updatedTitle);
                        selectedEvent.setExtendedProp("notes", updatedNotes);
                        selectedEvent.setExtendedProp("calendarId", updatedCategory);
                        selectedEvent.setExtendedProp("location", updatedLocation);
                        selectedEvent.setExtendedProp("link", updatedLink);
                        selectedEvent.setStart(updatedStartDate);
                        selectedEvent.setEnd(updatedEndDate);
                        selectedEvent.setProp("id", updatedId);
                        var updateEvent = {
                            'title': updatedTitle,
                            'notes': updatedNotes,
                            'start_date': updatedStartDate,
                            'end_date': updatedEndDate,
                            'calendarId': updatedCategory,
                            'id': updatedId,
                        }
                        formData=updateEvent;

                    } else {
                        var newEvent = {
                            title: updatedTitle,
                            notes: updatedNotes,
                            start: updatedStartDate,
                            end: updatedEndDate,
                            calendarId: updatedCategory,
                            id: '',
                            location:updatedLocation,
                            link:updatedLink,
                        }
                        calendar.addEvent(newEvent);
                        
                        var newCalEvent = {
                            'title': updatedTitle,
                            'notes': updatedNotes,
                            'start_date': updatedStartDate,
                            'end_date': updatedEndDate,
                            'calendarId': updatedCategory,
                            'id': '',
                        }
                        formData=newCalEvent;
                    }
                    $.ajax({
                        url: '<?=base_url('admin/calendar/calendarEvent');?>',
                        data: formData,
                        type: 'POST',
                        success: function(res){
                            addEvent.modal('hide');
                            // window.location.reload(); 
                        },
                    }); 
                }
            });

            $("#btn-delete-event").on('click', function (e) {
                if (selectedEvent) {
                    var updatedCategory = $('#event-category').val();
                    var updatedId = $('#event-id').val();
                    var formData = {
                        'calendarId': updatedCategory,
                        'id': updatedId,
                    }
                    $.ajax({
                        url: '<?=base_url('admin/calendar/deleteCalendarEvent');?>',
                        data: formData,
                        type: 'POST',
                        success: function(res){
                            selectedEvent.remove();
                            selectedEvent = null;
                            addEvent.modal('hide');
                            //window.location.reload(); 
                        },
                    }); 
                }
            });

            $("#btn-new-event").on('click', function (e) {
                addNewEvent({ date: formattedDate });
            });
        },
        //init
        $.CalendarPage = new CalendarPage, $.CalendarPage.Constructor = CalendarPage
    }(window.jQuery),

    //initializing 
    function ($) {
        "use strict";
        $.CalendarPage.init()
    }(window.jQuery);


    // set calendars
    (function() {
        var calendarList = document.getElementById('calendarList');
        var html = [];
        CalendarList.forEach(function(calendar) {
            html.push('<div class="lnb-calendars-item"><label>' +
                '<input type="checkbox" class="tui-full-calendar-checkbox-round" value="' + calendar.id + '" checked>' +
                '<span style="border-color: ' + calendar.borderColor + '; background-color: ' + calendar.borderColor + ';"></span>' +
                '<span>' + calendar.name + '</span>' +
                '</label></div>'
            );
        });
        calendarList.innerHTML = html.join('\n');
    })();

});
</script>