<?php
$gcal_api_key = config_item('gcal_api_key');
$gcal_id = config_item('gcal_id');
?>
<!-- Calendar -->
<link rel="stylesheet" type="text/css" href="<?=base_url();?>skote_assets/libs/tui-time-picker/tui-time-picker.min.css">
<link rel="stylesheet" type="text/css" href="<?=base_url();?>skote_assets/libs/tui-date-picker/tui-date-picker.min.css">
<link href="<?=base_url();?>skote_assets/libs/tui-calendar/tui-calendar.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">

.ml{
    margin-left: -32px !important;
}
.mlr{
    margin-right: 90px !important;
    margin-top: -25px !important;
}

</style>
<!-- Calendar -->
<script src="https://uicdn.toast.com/tui.code-snippet/latest/tui-code-snippet.min.js"></script>
<script src="<?=base_url();?>skote_assets/libs/tui-dom/tui-dom.min.js"></script>

<script src="<?=base_url();?>skote_assets/libs/tui-time-picker/tui-time-picker.min.js"></script>
<script src="<?=base_url();?>skote_assets/libs/tui-date-picker/tui-date-picker.min.js"></script>

<script src="<?=base_url();?>skote_assets/libs//moment/min/moment.min.js"></script>
<script src="<?=base_url();?>skote_assets/libs/chance/chance.min.js"></script>

<script src="<?=base_url();?>skote_assets/libs/tui-calendar/tui-calendar.min.js"></script>

<script src="<?=base_url();?>skote_assets/js/pages/calendars.js"></script>
<script src="<?=base_url();?>skote_assets/js/pages/schedules.js"></script>

<script type="text/javascript">
$(document).ready(function () {
(function(window, Calendar) {
    var cal, resizeThrottled;
    var useCreationPopup = true;
    var useDetailPopup = true;
    var datePicker, selectedCalendar;

    cal = new Calendar('#calendar', {
        defaultView: 'month',
        googleCalendarApiKey: gcal_api_key,
        // useCreationPopup: useCreationPopup,
        useDetailPopup: useDetailPopup,
        calendars: CalendarList,
        template: {
            milestone: function(model) {
                return '<span class="calendar-font-icon ic-milestone-b"></span> <span style="background-color: ' + model.bgColor + '">' + model.title + '</span>';
            },
            allday: function(schedule) {
                return getTimeTemplate(schedule, true);
            },
            time: function(schedule) {
                return getTimeTemplate(schedule, false);
            }
        }
    });

    // event handlers
    cal.on({
        'clickMore': function(e) {
           // console.log('clickMore', e);
        },
        'clickSchedule': function(e) {
          //  console.log('clickSchedule', e);
        },
        'clickDayname': function(date) {
           // console.log('clickDayname', date);
        },
        // 'beforeCreateSchedule': function(e) {
        //     console.log('beforeCreateSchedule', e);
        // },
        // 'beforeUpdateSchedule': function(e) {
        //     var schedule = e.schedule;
        //     var changes = e.changes;

        //     console.log('beforeUpdateSchedule', e);
        // },
        // 'beforeDeleteSchedule': function(e) {
        //     console.log('beforeDeleteSchedule', e);
        // },
        'afterRenderSchedule': function(e) {
            var schedule = e.schedule;
            // var element = cal.getElement(schedule.id, schedule.calendarId);
            // console.log('afterRenderSchedule', element);
        },
        'clickTimezonesCollapseBtn': function(timezonesCollapsed) {
            // console.log('timezonesCollapsed', timezonesCollapsed);

            if (timezonesCollapsed) {
                cal.setTheme({
                    'week.daygridLeft.width': '77px',
                    'week.timegridLeft.width': '77px'
                });
            } else {
                cal.setTheme({
                    'week.daygridLeft.width': '60px',
                    'week.timegridLeft.width': '60px'
                });
            }

            return true;
        }
    });

    /**
     * Get time template for time and all-day
     * @param {Schedule} schedule - schedule
     * @param {boolean} isAllDay - isAllDay or hasMultiDates
     * @returns {string}
     */
    function getTimeTemplate(schedule, isAllDay) {
        var html = [];
        var start = moment(schedule.start.toUTCString());
        if (!isAllDay) {
            html.push('<strong>' + start.format('HH:mm') + '</strong> ');
        }
        if (schedule.isPrivate) {
            html.push('<span class="calendar-font-icon ic-lock-b"></span>');
            html.push(' Private');
        } else {
            if (schedule.isReadOnly) {
                html.push('<span class="calendar-font-icon ic-readonly-b"></span>');
            } else if (schedule.recurrenceRule) {
                html.push('<span class="calendar-font-icon ic-repeat-b"></span>');
            } else if (schedule.attendees.length) {
                html.push('<span class="calendar-font-icon ic-user-b"></span>');
            } else if (schedule.location) {
                html.push('<span class="calendar-font-icon ic-location-b"></span>');
            }
            html.push(' ' + schedule.title);
        }

        return html.join('');
    }

    /**
     * A listener for click the menu
     * @param {Event} e - click event
     */
    function onClickMenu(e) {
        var target = $(e.target).closest('a[role="menuitem"]')[0];
        var action = getDataAction(target);
        var options = cal.getOptions();
        var viewName = '';

        // console.log(target);
        // console.log(action);
        switch (action) {
            case 'toggle-daily':
                viewName = 'day';
                break;
            case 'toggle-weekly':
                viewName = 'week';
                break;
            case 'toggle-monthly':
                options.month.visibleWeeksCount = 0;
                viewName = 'month';
                break;
            case 'toggle-weeks2':
                options.month.visibleWeeksCount = 2;
                viewName = 'month';
                break;
            case 'toggle-weeks3':
                options.month.visibleWeeksCount = 3;
                viewName = 'month';
                break;
            case 'toggle-narrow-weekend':
                options.month.narrowWeekend = !options.month.narrowWeekend;
                options.week.narrowWeekend = !options.week.narrowWeekend;
                viewName = cal.getViewName();

                target.querySelector('input').checked = options.month.narrowWeekend;
                break;
            case 'toggle-start-day-1':
                options.month.startDayOfWeek = options.month.startDayOfWeek ? 0 : 1;
                options.week.startDayOfWeek = options.week.startDayOfWeek ? 0 : 1;
                viewName = cal.getViewName();

                target.querySelector('input').checked = options.month.startDayOfWeek;
                break;
            case 'toggle-workweek':
                options.month.workweek = !options.month.workweek;
                options.week.workweek = !options.week.workweek;
                viewName = cal.getViewName();

                target.querySelector('input').checked = !options.month.workweek;
                break;
            default:
                break;
        }

        cal.setOptions(options, true);
        cal.changeView(viewName, true);

        setDropdownCalendarType();
        setRenderRangeText();
        setSchedules();
    }

    function onClickNavi(e) {
        var action = getDataAction(e.target);

        switch (action) {
            case 'move-prev':
                cal.prev();
                break;
            case 'move-next':
                cal.next();
                break;
            case 'move-today':
                cal.today();
                break;
            default:
                return;
        }

        setRenderRangeText();
        setSchedules();
    }

    function onChangeNewScheduleCalendar(e) {
        var target = $(e.target).closest('a[role="menuitem"]')[0];
        var calendarId = getDataAction(target);
        changeNewScheduleCalendar(calendarId);
    }

    function changeNewScheduleCalendar(calendarId) {
        var calendarNameElement = document.getElementById('calendarName');
        var calendar = findCalendar(calendarId);
        var html = [];

        html.push('<span class="calendar-bar" style="background-color: ' + calendar.bgColor + '; border-color:' + calendar.borderColor + ';"></span>');
        html.push('<span class="calendar-name">' + calendar.name + '</span>');

        calendarNameElement.innerHTML = html.join('');

        selectedCalendar = calendar;
    }

    function onChangeCalendars(e) {
        var calendarId = e.target.value;
        var checked = e.target.checked;
        var viewAll = document.querySelector('.lnb-calendars-item input');
        var calendarElements = Array.prototype.slice.call(document.querySelectorAll('#calendarList input'));
        var allCheckedCalendars = true;

        if (calendarId === 'all') {
            allCheckedCalendars = checked;

            calendarElements.forEach(function(input) {
                var span = input.parentNode;
                input.checked = checked;
                span.style.backgroundColor = checked ? span.style.borderColor : 'transparent';
            });

            CalendarList.forEach(function(calendar) {
                calendar.checked = checked;
            });
        } else {
            findCalendar(calendarId).checked = checked;

            allCheckedCalendars = calendarElements.every(function(input) {
                return input.checked;
            });

            if (allCheckedCalendars) {
                viewAll.checked = true;
            } else {
                viewAll.checked = false;
            }
        }

        refreshScheduleVisibility();
    }

    function refreshScheduleVisibility() {
        var calendarElements = Array.prototype.slice.call(document.querySelectorAll('#calendarList input'));

        CalendarList.forEach(function(calendar) {
            cal.toggleSchedules(calendar.id, !calendar.checked, false);
        });

        cal.render(true);

        calendarElements.forEach(function(input) {
            var span = input.nextElementSibling;
            span.style.backgroundColor = input.checked ? span.style.borderColor : 'transparent';
        });
    }

    function setDropdownCalendarType() {
        var calendarTypeName = document.getElementById('calendarTypeName');
        var calendarTypeIcon = document.getElementById('calendarTypeIcon');
        var options = cal.getOptions();
        var type = cal.getViewName();
        var iconClassName;

        if (type === 'day') {
            type = 'Daily';
            iconClassName = 'calendar-icon ic_view_day';
        } else if (type === 'week') {
            type = 'Weekly';
            iconClassName = 'calendar-icon ic_view_week';
        } else if (options.month.visibleWeeksCount === 2) {
            type = '2 weeks';
            iconClassName = 'calendar-icon ic_view_week';
        } else if (options.month.visibleWeeksCount === 3) {
            type = '3 weeks';
            iconClassName = 'calendar-icon ic_view_week';
        } else {
            type = 'Monthly';
            iconClassName = 'calendar-icon ic_view_month';
        }

        calendarTypeName.innerHTML = type;
        calendarTypeIcon.className = iconClassName;
    }

    function setRenderRangeText() {
        var renderRange = document.getElementById('renderRange');
        var options = cal.getOptions();
        var viewName = cal.getViewName();
        var html = [];
        if (viewName === 'day') {
            html.push(moment(cal.getDate().getTime()).format('YYYY.MM.DD'));
        } else if (viewName === 'month' &&
            (!options.month.visibleWeeksCount || options.month.visibleWeeksCount > 4)) {
            html.push(moment(cal.getDate().getTime()).format('YYYY.MM'));
        } else {
            html.push(moment(cal.getDateRangeStart().getTime()).format('YYYY.MM.DD'));
            html.push(' ~ ');
            html.push(moment(cal.getDateRangeEnd().getTime()).format(' MM.DD'));
        }
        renderRange.innerHTML = html.join('');
    }

    function setSchedules() {
        cal.clear();
      
        var schedules = [
            <?php
            $invoice_info = $this->db->where('project_id', $project_details->project_id)->get('tbl_invoices')->result();
            if (!empty($invoice_info)) {
            foreach ($invoice_info as $v_invoice) :
            $start_day = date('d', strtotime($v_invoice->due_date));
            $smonth = date('n', strtotime($v_invoice->due_date));
            $start_month = $smonth - 1;
            $start_year = date('Y', strtotime($v_invoice->due_date));
            $end_year = date('Y', strtotime($v_invoice->due_date));
            $end_day = date('d', strtotime($v_invoice->due_date));
            $emonth = date('n', strtotime($v_invoice->due_date));
            $end_month = $emonth - 1;
            ?>
            {
                id: String(chance.guid()),
                title: "<?php echo $v_invoice->reference_no ?>",
                start: new Date(<?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>),
                end: new Date(<?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>),
                color: invoice_color,
                location: '<a href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_invoice->invoices_id ?>"><?=lang('view_details');?></a>',
                goingDuration: 30, 
                comingDuration: 30, 
                isVisible: true, 
                bgColor: '#69BB2D', 
                dragBgColor: '#69BB2D', 
                borderColor: project_color, 
                calendarId: 'logged-workout', 
                category: 'time', 
                dueDateClass: '', 
                customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: true, isPrivate: false, 
                attendees: '', recurrenceRule: '', state: '',
            },
            <?php endforeach; } ?>

            <?php  
            $estimates_info = $this->db->where('project_id', $project_details->project_id)->get('tbl_estimates')->result();;
            if (!empty($estimates_info)) {
            foreach ($estimates_info as $v_estimates) :
            $start_day = date('d', strtotime($v_estimates->due_date));
            $smonth = date('n', strtotime($v_estimates->due_date));
            $start_month = $smonth - 1;
            $start_year = date('Y', strtotime($v_estimates->due_date));
            $end_year = date('Y', strtotime($v_estimates->due_date));
            $end_day = date('d', strtotime($v_estimates->due_date));
            $emonth = date('n', strtotime($v_estimates->due_date));
            $end_month = $emonth - 1;
            ?>
            {
                title: "<?php echo $v_estimates->reference_no ?>",
                start: new Date(<?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>),
                end: new Date(<?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>),
                color: estimate_color,
                location: '<a href="<?= base_url() ?>admin/estimates/index/estimates_details/<?= $v_estimates->estimates_id ?>"><?=lang('view_details');?></a>',
                goingDuration: 30, 
                comingDuration: 30, 
                isVisible: true, 
                bgColor: '#69BB2D', 
                dragBgColor: '#69BB2D', 
                borderColor: milestone_color, 
                calendarId: 'logged-workout', 
                category: 'time', 
                dueDateClass: '', 
                customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: true, isPrivate: false, 
                attendees: '', recurrenceRule: '', state: '',
            },

            <?php endforeach; } ?>

            <?php
            $start_day = date('d', strtotime($project_details->end_date));
            $smonth = date('n', strtotime($project_details->end_date));
            $start_month = $smonth - 1;
            $start_year = date('Y', strtotime($project_details->end_date));
            $end_year = date('Y', strtotime($project_details->end_date));
            $end_day = date('d', strtotime($project_details->end_date));
            $emonth = date('n', strtotime($project_details->end_date));
            $end_month = $emonth - 1;
            ?>
            {
                title: "<?php echo $project_details->project_name ?>",
                start: new Date(<?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>),
                end: new Date(<?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>),
                color: project_color,
                location: '<a href="<?= base_url() ?>admin/projects/project_details/<?= $project_details->project_id ?>"><?=lang('view_details');?></a>',
                goingDuration: 30, 
                comingDuration: 30, 
                isVisible: true, 
                bgColor: '#69BB2D', 
                dragBgColor: '#69BB2D', 
                borderColor: tasks_color, 
                calendarId: 'logged-workout', 
                category: 'time', 
                dueDateClass: '', 
                customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: true, isPrivate: false, 
                attendees: '', recurrenceRule: '', state: '',
            },
            <?php
            $milestone_info = $this->db->where(array('project_id' => $project_details->project_id))->get('tbl_milestones')->result();
            if (!empty($milestone_info)) {
            foreach ($milestone_info as $v_milestone) :
            $start_day = date('d', strtotime($v_milestone->end_date));
            $smonth = date('n', strtotime($v_milestone->end_date));
            $start_month = $smonth - 1;
            $start_year = date('Y', strtotime($v_milestone->end_date));
            $end_year = date('Y', strtotime($v_milestone->end_date));
            $end_day = date('d', strtotime($v_milestone->end_date));
            $emonth = date('n', strtotime($v_milestone->end_date));
            $end_month = $emonth - 1;
            ?>
            {
                title: '<?php echo $v_milestone->milestone_name ?>',
                start: new Date(<?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>),
                end: new Date(<?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>),
                color: milestone_color,
                location: '<a href="<?= base_url() ?>admin/projects/project_details/<?= $project_details->project_id ?>/5"><?=lang('view_details');?></a>',
                goingDuration: 30, 
                comingDuration: 30, 
                isVisible: true, 
                bgColor: '#69BB2D', 
                dragBgColor: '#69BB2D', 
                borderColor: tasks_color, 
                calendarId: 'logged-workout', 
                category: 'time', 
                dueDateClass: '', 
                customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: true, isPrivate: false, 
                attendees: '', recurrenceRule: '', state: '',
            },
            <?php
            endforeach;
            }
            $task_info = $this->db->where(array('project_id' => $project_details->project_id))->get('tbl_task')->result();
            if (!empty($task_info)) {
            foreach ($task_info as $v_task) :
            $start_day = date('d', strtotime($v_task->due_date));
            $smonth = date('n', strtotime($v_task->due_date));
            $start_month = $smonth - 1;
            $start_year = date('Y', strtotime($v_task->due_date));
            $end_year = date('Y', strtotime($v_task->due_date));
            $end_day = date('d', strtotime($v_task->due_date));
            $emonth = date('n', strtotime($v_task->due_date));
            $end_month = $emonth - 1;
            ?>
            {
                title: "<?php echo $v_task->task_name ?>",
                start: new Date(<?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>),
                end: new Date(<?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>),
                color: tasks_color,
                location: '<a href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>"><?=lang('view_details');?></a>',
                goingDuration: 30, 
                comingDuration: 30, 
                isVisible: true, 
                bgColor: '#69BB2D', 
                dragBgColor: '#69BB2D', 
                borderColor: tasks_color, 
                calendarId: 'logged-workout', 
                category: 'time', 
                dueDateClass: '', 
                customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: true, isPrivate: false, 
                attendees: '', recurrenceRule: '', state: '',
            },
            <?php
            endforeach;
            }
            $bug_info = $this->db->where(array('project_id' => $project_details->project_id))->get('tbl_bug')->result();
            if (!empty($bug_info)) {
            foreach ($bug_info as $v_bug) :
            $start_day = date('d', strtotime($v_bug->created_time));
            $smonth = date('n', strtotime($v_bug->created_time));
            $start_month = $smonth - 1;
            $start_year = date('Y', strtotime($v_bug->created_time));
            $end_year = date('Y', strtotime($v_bug->created_time));
            $end_day = date('d', strtotime($v_bug->created_time));
            $emonth = date('n', strtotime($v_bug->created_time));
            $end_month = $emonth - 1;
            ?>
            {
                title: "<?php echo $v_bug->bug_title ?>",
                start: new Date(<?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>),
                end: new Date(<?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>),
                color: bugs_color,
                location: '<a href="<?= base_url() ?>admin/bugs/view_bug_details/<?= $v_bug->bug_id ?>"><?=lang('view_details');?></a>',
                goingDuration: 30, 
                comingDuration: 30, 
                isVisible: true, 
                bgColor: '#69BB2D', 
                dragBgColor: '#69BB2D', 
                borderColor: tasks_color, 
                calendarId: 'logged-workout', 
                category: 'time', 
                dueDateClass: '', 
                customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: true, isPrivate: false, 
                attendees: '', recurrenceRule: '', state: '',
            },
            <?php
            endforeach;
            }
            ?>
        ];
        cal.createSchedules(schedules);
        refreshScheduleVisibility();
    }

    function setEventListener() {
        $('#menu-navi').on('click', onClickNavi);
        $('.dropdown-menu a[role="menuitem"]').on('click', onClickMenu);
        $('#lnb-calendars').on('change', onChangeCalendars);

        $('#dropdownMenu-calendars-list').on('click', onChangeNewScheduleCalendar);

        window.addEventListener('resize', resizeThrottled);
    }

    function getDataAction(target) {
        return target.dataset ? target.dataset.action : target.getAttribute('data-action');
    }

    resizeThrottled = tui.util.throttle(function() {
        cal.render();
    }, 50);

    window.cal = cal;

    setDropdownCalendarType();
    setRenderRangeText();
    setSchedules();
    setEventListener();
})(window, tui.Calendar);

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
 
