<div class="card-body" id="panelChart4">
    <h4 class="card-title mb-4"><?= lang('total') . ' ' . lang('task') . ' ' . lang('time_spent') ?></h4>
    <div class="row mb-3">
        <?php
        $tasks_info = $this->user_model->my_permission('tbl_task', $profile_info->user_id);
        $task_time = 0;
        if (!empty($tasks_info)) {
            foreach ($tasks_info as $v_u_tasks) {
                $task_time += $this->user_model->task_spent_time_by_id($v_u_tasks->task_id);
            }
        }
        echo $this->user_model->get_time_spent_result($task_time)
        ?>
    </div>
</div>
<div id="panelChart5" class="card-body">
    <h4 class="card-title mb-4"><?= lang('task') . ' ' . lang('report') ?></h4>
    <div class="chart-pie flot-chart"></div>
</div>
<?php

$not_started = 0;
$in_progress = 0;
$completed = 0;
$deferred = 0;
$waiting_for_someone = 0;

if (!empty($tasks_info)):foreach ($tasks_info as $v_tasks):
    if ($v_tasks->task_status == 'not_started') {
        $not_started += count($v_tasks->task_status);
    }
    if ($v_tasks->task_status == 'in_progress') {
        $in_progress += count($v_tasks->task_status);
    }
    if ($v_tasks->task_status == 'completed') {
        $completed += count($v_tasks->task_status);
    }
    if ($v_tasks->task_status == 'deferred') {
        $deferred += count($v_tasks->task_status);
    }
    if ($v_tasks->task_status == 'waiting_for_someone') {
        $waiting_for_someone += count($v_tasks->task_status);
    }
endforeach;
endif;
?>
<?php if (!empty($not_started) || !empty($in_progress) || !empty($completed) || !empty($deferred) || !empty($waiting_for_someone)) { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            // CHART PIE
            // -----------------------------------
            (function (window, document, $, undefined) {

                $(function () {

                    var data = [{
                        "label": "<?= lang('not_started')?>",
                        "color": "#23b7e5",
                        "data": <?= $not_started?>
                    }, {
                        "label": "<?= lang('in_progress')?>",
                        "color": "#ff902b",
                        "data": <?= $in_progress?>
                    }, {
                        "label": "<?= lang('completed')?>",
                        "color": "#27c24c",
                        "data": <?= $completed?>
                    }, {
                        "label": "<?= lang('deferred')?>",
                        "color": "#f05050",
                        "data": <?= $deferred?>
                    }, {
                        "label": "<?= lang('waiting_for_someone')?>",
                        "color": "#ff902b",
                        "data": <?= $waiting_for_someone?>
                    },];

                    var options = {
                        series: {
                            pie: {
                                show: true,
                                innerRadius: 0,
                                label: {
                                    show: true,
                                    radius: 0.8,
                                    formatter: function (label, series) {
                                        return '<div class="flot-pie-label">' +
                                                //label + ' : ' +
                                            Math.round(series.percent) +
                                            '%</div>';
                                    },
                                    background: {
                                        opacity: 0.8,
                                        color: '#222'
                                    }
                                }
                            }
                        }
                    };

                    var chart = $('.chart-pie');
                    if (chart.length)
                        $.plot(chart, data, options);

                });

            })(window, document, window.jQuery);

        });
    </script>
<?php } ?>