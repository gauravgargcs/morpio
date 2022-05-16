<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>
            <?php $this->load->view('admin/skote_layouts/title'); ?>
        </div>
    </div>
</div>
<!-- end page title -->

<!-- START row-->
<div class="row">
    <div class="col-lg-6">
        <div id="panelChart5" class="card">
            <div class="card-body">
                <h4 class="card-title mb-4"><?= lang('task') . ' ' . lang('report') ?></h4>
                <div id="pie_chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div id="panelChart4" class="card">
            <div class="card-body">
                <h4 class="card-title mb-4"><?= lang('total') . ' ' . lang('task') . ' ' . lang('time_spent') ?></h4>
                <div class="row mb-3">
                    <?php
                    $tasks_info = $this->report_model->get_permission('tbl_task');
                    $task_time = 0;
                    if (!empty($tasks_info)) {
                        foreach ($tasks_info as $v_tasks) {
                            if (!empty($v_tasks->project_id)) {
                                $task_time += $this->report_model->task_spent_time_by_id($v_tasks->task_id);
                            }
                        }
                    }
                    echo $this->report_model->get_time_spent_result($task_time)
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END row-->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4"><?= lang('tasks_r_assignment') ?></h4>
                <div id="morris-bar"></div>
            </div>
        </div>
    </div>
</div>

<!-- END row-->

<?php
$not_started = 0;
$in_progress = 0;
$completed = 0;
$deferred = 0;
$waiting_for_someone = 0;

if (!empty($all_project)) :
    foreach ($all_project as $v_project) :
        $tasks_info = $this->db->where('project_id', $v_project->project_id)->get('tbl_task')->result();
        if (!empty($tasks_info)) : foreach ($tasks_info as $v_tasks) :
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
    endforeach;
endif;

?>

<!-- apexcharts -->
<script src="<?= base_url() ?>skote_assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- chartist -->
<link href="<?php echo base_url() ?>skote_assets/plugins/morris/morris.min.css" rel="stylesheet">

<script src="<?= base_url() ?>skote_assets/plugins/raphael/raphael.min.js"></script>
<script src="<?= base_url() ?>skote_assets/plugins/morris/morris.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var chartdata = [
            <?php if (!empty($user_tasks)) : foreach ($user_tasks as $user => $v_task_user) :
                    if ($user != 'all') {
                        if (!empty($assign_user)) {
                            foreach ($assign_user as $v_user) {
                                if ($v_user->user_id == $user) { ?> {
                                        y: "<?= $v_user->username ?>",
                                        <?php
                                        $inparogress = 0;
                                        $notstarted = 0;
                                        $deferred = 0;
                                        foreach ($v_task_user as $status => $value) {
                                            if ($status == 'not_started') {
                                                $notstarted = count($value);
                                            } elseif ($status == 'in_progress') {
                                                $inparogress = count($value);
                                            } elseif ($status == 'deferred') {
                                                $deferred = count($value);
                                            }
                                        }
                                        ?>
                                        a: <?= $notstarted; ?>,

                                        b: <?= $inparogress ?>,
                                        c: <?= $deferred ?>
                                    },
                                 <?php
                                }
                            }
                        };
                    }
                endforeach;
            endif;

            ?>
        ];
        if (chartdata.length){
            new Morris.Bar({
                element: 'morris-bar',
                data: chartdata,
                xkey: 'y',
                ykeys: ["a", "b", "c"],
                labels: ["<?= lang('not_started') ?>", "<?= lang('in_progress') ?>", "<?= lang('deferred') ?>"],
                xLabelMargin: 2,
                barColors: ['#23b7e5', '#ff902b', '#f05050'],
                resize: true,
                parseTime: false,
            });
        }
        // CHART PIE
        // -----------------------------------
        (function(window, document, $, undefined) {

            $(function() {
                // pie chart
                var options = {
                  chart: {
                      height: 320,
                      type: 'pie',
                  }, 
                  series: [<?= $not_started ?>, <?= $in_progress ?>, <?= $completed ?>, <?= $deferred ?>, <?= $waiting_for_someone ?>],
                  labels: ["<?= lang('not_started') ?>", "<?= lang('in_progress') ?>", "<?= lang('completed') ?>", "<?= lang('deferred') ?>", "<?= lang('waiting_for_someone') ?>"],
                  colors: ["#23b7e5", "#ff902b","#27c24c", "#f05050","#ff902b" ],
                  legend: {
                      show: true,
                      position: 'bottom',
                      horizontalAlign: 'center',
                      verticalAlign: 'middle',
                      floating: false,
                      fontSize: '14px',
                      offsetX: 0,
                  },
                  responsive: [{
                      breakpoint: 600,
                      options: {
                          chart: {
                              height: 240
                          },
                          legend: {
                              show: false
                          },
                      }
                  }]
                }
                var chart = new ApexCharts(
                  document.querySelector("#pie_chart"),
                  options
                );

                chart.render();

            });

        })(window, document, window.jQuery);

    });
</script>