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
        <div class="card" id="panelChart5">
            <div class="card-body">
                <h4 class="card-title mb-4"><?= lang('project') . ' ' . lang('report') ?></h4>
                <div id="pie_chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card" id="panelChart4">
            <div class="card-body">        
                <h4 class="card-title mb-4"><?= lang('total') . ' ' . lang('project') . ' ' . lang('time_spent') ?></h4>
                <?php
                $project_info = $this->report_model->get_permission('tbl_project');
                $project_time = 0;
                if (!empty($project_info)) {
                    foreach ($project_info as $v_projects) {
                        $project_time += $this->report_model->task_spent_time_by_id($v_projects->project_id, true);
                    }
                }
                echo $this->report_model->get_time_spent_result($project_time)
                ?>
            </div>
        </div>
    </div>
</div>
<!-- END row-->
<!-- START row-->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4"><?= lang('project_assignment') ?></h4>
                <div id="morris-bar"></div>
            </div>
        </div>
    </div>
</div>

<!-- END row-->

<?php
$started = 0;
$in_progress = 0;
$cancel = 0;
$completed = 0;
if (!empty($all_project)) :
    foreach ($all_project as $v_project) :
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

<!-- apexcharts -->
<script src="<?= base_url() ?>skote_assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- chartist -->
<link href="<?php echo base_url() ?>skote_assets/plugins/morris/morris.min.css" rel="stylesheet">

<script src="<?= base_url() ?>skote_assets/plugins/raphael/raphael.min.js"></script>
<script src="<?= base_url() ?>skote_assets/plugins/morris/morris.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var chartdata = [
            <?php if (!empty($user_project)) : foreach ($user_project as $user => $v_project_user) :
                    if ($user != 'all') {
                        if (!empty($assign_user)) {
                            foreach ($assign_user as $v_user) {
                                if ($v_user->user_id == $user) {
            ?> {
                                        y: "<?= $v_user->username ?>",
                                        <?php
                                        $aunconfirmed = 0;
                                        $inparogress = 0;
                                        $averified = 0;
                                        $aresolved = 0;
                                        foreach ($v_project_user as $status => $value) {
                                            if ($status == 'started') {
                                                $aunconfirmed = count($value);
                                            } elseif ($status == 'in_progress') {
                                                $inparogress = count($value);
                                            } elseif ($status == 'cancel') {
                                                $aresolved = count($value);
                                            }
                                        }
                                        ?>
                                        a: <?= $aunconfirmed; ?>,

                                        b: <?= $inparogress ?>,
                                        c: <?= $averified ?>,
                                        d: <?= $aresolved ?>
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
        if (chartdata.length) {
            new Morris.Bar({
                element: 'morris-bar',
                data: chartdata,
                xkey: 'y',
                ykeys: ["a", "b", "c"],
                labels: ["<?= lang('started') ?>", "<?= lang('in_progress') ?>", "<?= lang('cancel') ?>"],
                xLabelMargin: 2,
                barColors: ['#ff902b', '#5d9cec', '#27c24c', '#7266ba'],
                resize: true,
                parseTime: false,
            });
        }

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
                  document.querySelector("#pie_chart"),
                  options
                );

                chart.render();
            });

        })(window, document, window.jQuery);
    });
</script>