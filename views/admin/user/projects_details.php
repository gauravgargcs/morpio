<div id="panelChart4"  class="card-body">
    <h4 class="card-title mb-4"><?= lang('total') . ' ' . lang('project') . ' ' . lang('time_spent') ?></h4>
    <div class="row mb-3">
        <?php
        $project_info = $this->user_model->my_permission('tbl_project', $profile_info->user_id);
        $project_time = 0;
        if (!empty($project_info)) {
            foreach ($project_info as $v_projects) {
                $project_time += $this->user_model->task_spent_time_by_id($v_projects->project_id, true);
            }
        }
        echo $this->user_model->get_time_spent_result($project_time)

        ?>
    </div>
</div>
<div id="panelChart5" class="card-body">
    <h4 class="card-title mb-4"><?= lang('project') . ' ' . lang('report') ?></h4>
    <div id="project_pie_chart" class="apex-charts" dir="ltr"></div>
</div>

<?php

$started = 0;
$in_progress = 0;
$cancel = 0;
$completed = 0;
if (!empty($project_info)):
    foreach ($project_info as $v_project) :
        if ($v_project->project_status == 'started') {
            $started += count($v_project->project_status);
        }
        if ($v_project->project_status == 'in_progress') {
            $in_progress += count($v_project->project_status);
        }
        if ($v_project->project_status == 'completed') {
            $completed += count($v_project->project_status);
        }
        if ($v_project->project_status == 'cancel') {
            $cancel += count($v_project->project_status);
        }
    endforeach;
endif;
?>
<?php if (!empty($started) || !empty($in_progress) || !empty($completed) || !empty($cancel)) { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            // CHART PIE -----------------------------------
            (function(window, document, $, undefined) {
                $(function() {
                    // pie chart
                    var options = {
                      chart: {
                          height: 320,
                          type: 'pie',
                      }, 
                      series: [<?= $started ?>, <?= $in_progress ?>, <?= $completed ?>, <?= $cancel ?>],
                      labels: ["<?= lang('started') ?>", "<?= lang('in_progress') ?>", "<?= lang('completed') ?>", "<?= lang('cancel') ?>"],
                      colors: ["#34c38f", "#556ee6","#f46a6a", "#50a5f1"],
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
                      document.querySelector("#project_pie_chart"),
                      options
                    );

                    chart.render();
                });

            })(window, document, window.jQuery);


        });

    </script>
<?php } ?>