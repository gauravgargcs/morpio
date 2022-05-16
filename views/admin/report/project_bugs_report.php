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
                <h4 class="card-title mb-4"><?= lang('bugs') . ' ' . lang('report') ?></h4>
                <div id="pie_chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div id="panelChart4" class="card">
            <div class="card-body">
                <h4 class="card-title mb-4"><?= lang('bugs') . ' ' . lang('report') . ' ' . date('Y') ?></h4>
                <div id="line_chart_datalabel" class="apex-charts" dir="ltr"></div>
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
                <h4 class="card-title mb-4"><?= lang('bugs_r_assignment') ?></h4>
                <div id="morris-bar"></div>
            </div>
        </div>
    </div>
</div>
<!-- END row-->

<?php
$unconfirmed = 0;
$in_progress = 0;
$confirmed = 0;
$resolved = 0;
$verified = 0;
if (!empty($all_project)) :
    foreach ($all_project as $v_project) :
        $bugs_info = $this->db->where('project_id', $v_project->project_id)->get('tbl_bug')->result();
        if (!empty($bugs_info)) : foreach ($bugs_info as $v_bugs) :
                if ($v_bugs->bug_status == 'unconfirmed') {
                    $unconfirmed += count($v_bugs->bug_status);
                }
                if ($v_bugs->bug_status == 'in_progress') {
                    $in_progress += count($v_bugs->bug_status);
                }
                if ($v_bugs->bug_status == 'confirmed') {
                    $confirmed += count($v_bugs->bug_status);
                }
                if ($v_bugs->bug_status == 'resolved') {
                    $resolved += count($v_bugs->bug_status);
                }
                if ($v_bugs->bug_status == 'verified') {
                    $verified += count($v_bugs->bug_status);
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
            <?php if (!empty($user_bugs)) : foreach ($user_bugs as $user => $v_bugs_user) :
                    if ($user != 'all') {
                        if (!empty($assign_user)) {
                            foreach ($assign_user as $v_user) {
                                if ($v_user->user_id == $user) { ?> {
                                        y: "<?= $v_user->username ?>",
                                        <?php
                                        $aunconfirmed = 0;
                                        $inparogress = 0;
                                        $averified = 0;
                                        $aresolved = 0;
                                        foreach ($v_bugs_user as $status => $value) {
                                            if ($status == 'unconfirmed') {
                                                $aunconfirmed = count($value);
                                            } elseif ($status == 'in_progress') {
                                                $inparogress = count($value);
                                            } elseif ($status == 'verified') {
                                                $averified = count($value);
                                            } elseif ($status == 'resolved') {
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
                ykeys: ["a", "b", "c", 'd'],
                labels: ["<?= lang('unconfirmed') ?>", "<?= lang('in_progress') ?>", "<?= lang('verified') ?>", "<?= lang('resolved') ?>"],
                xLabelMargin: 2,
                barColors: ['#ff902b', '#5d9cec', '#27c24c', '#7266ba'],
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
                  series: [<?= $unconfirmed ?>, <?= $in_progress ?>, <?= $confirmed ?>, <?= $resolved ?>, <?= $verified ?>],
                  labels: ["<?= lang('unconfirmed') ?>", "<?= lang('in_progress') ?>", "<?= lang('confirmed') ?>", "<?= lang('resolved') ?>", "<?= lang('verified') ?>"],
                  colors: ["#f1b44c", "#556ee6","#f46a6a", "#50a5f1","#34c38f" ],
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
        
        // CHART BAR STACKED
        // -----------------------------------
        (function(window, document, $, undefined) {
            $(function() {
               //  line chart datalabel                   
                var options = {
                    chart: {
                      height: 380,
                      type: 'line',
                      zoom: {
                        enabled: false
                      },
                      toolbar: {
                        show: false
                      }
                    },
                    colors: ['#556ee6', '#34c38f'],
                    dataLabels: {
                      enabled: false,
                    },
                    stroke: {
                      width: [3, 3],
                      curve: 'straight'
                    },
                    series: [
                        {
                        
                        name: "<?= lang('unconfirmed') ?>",
                        data:   [
                                    <?php foreach ($yearly_report as $name => $v_report) : $month_name = date('M', strtotime(date('Y') . '-' . $name)); ?> 
                                        // "<?= $month_name ?>", 
                                        <?php  $y_not_started = 0;
                                        foreach ($v_report as $s_report) {
                                            if ($s_report->bug_status == 'unconfirmed') { $y_not_started += count($s_report->bug_status); }
                                        }
                                        echo $y_not_started; ?>,
                                    <?php endforeach; ?>
                                ]
                        },
                        {
                        
                        name: "<?= lang('in_progress') ?>",
                        data:   [
                                    <?php foreach ($yearly_report as $name => $v_report) : $month_name = date('M', strtotime(date('Y') . '-' . $name)); ?> 
                                        // "<?= $month_name ?>", 
                                        <?php  $y_not_started = 0;
                                        foreach ($v_report as $s_report) {
                                            if ($s_report->bug_status == 'in_progress') { $y_not_started += count($s_report->bug_status); }
                                        }
                                        echo $y_not_started; ?>,
                                    <?php endforeach; ?>
                                ]
                        },
                        {
                        
                        name: "<?= lang('confirmed') ?>",
                        data:   [
                                    <?php foreach ($yearly_report as $name => $v_report) : $month_name = date('M', strtotime(date('Y') . '-' . $name)); ?> 
                                        // "<?= $month_name ?>", 
                                        <?php  $y_not_started = 0;
                                        foreach ($v_report as $s_report) {
                                            if ($s_report->bug_status == 'confirmed') { $y_not_started += count($s_report->bug_status); }
                                        }
                                        echo $y_not_started; ?>,
                                    <?php endforeach; ?>
                                ]
                        },
                        {
                        
                        name: "<?= lang('resolved') ?>",
                        data:   [
                                    <?php foreach ($yearly_report as $name => $v_report) : $month_name = date('M', strtotime(date('Y') . '-' . $name)); ?> 
                                        // "<?= $month_name ?>", 
                                        <?php  $y_not_started = 0;
                                        foreach ($v_report as $s_report) {
                                            if ($s_report->bug_status == 'resolved') { $y_not_started += count($s_report->bug_status); }
                                        }
                                        echo $y_not_started; ?>,
                                    <?php endforeach; ?>
                                ]
                        },
                        {
                        
                        name: "<?= lang('verified') ?>",
                        data:   [
                                    <?php foreach ($yearly_report as $name => $v_report) : $month_name = date('M', strtotime(date('Y') . '-' . $name)); ?> 
                                        // "<?= $month_name ?>", 
                                        <?php  $y_not_started = 0;
                                        foreach ($v_report as $s_report) {
                                            if ($s_report->bug_status == 'verified') { $y_not_started += count($s_report->bug_status); }
                                        }
                                        echo $y_not_started; ?>,
                                    <?php endforeach; ?>
                                ]
                        },      
                    ],

                    title: {
                      text: '',
                      align: 'left',
                      style: {
                        fontWeight:  '500',
                      },
                    },
                    grid: {
                      row: {
                        colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.2
                      },
                      borderColor: '#f1f1f1'
                    },
                    markers: {
                      style: 'inverted',
                      size: 4
                    },
                    xaxis: {
                      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sep','Oct','Nov','Dec'],
                      title: {
                        text: 'Month'
                      }
                    },
                    yaxis: {
                      title: {
                        text: ''
                      },
                      min: 0,
                      max: 2.5
                    },
                    legend: {
                      position: 'top',
                      horizontalAlign: 'right',
                      floating: true,
                      offsetY: -25,
                      offsetX: -5
                    },
                    responsive: [{
                      breakpoint: 600,
                      options: {
                        chart: {
                          toolbar: {
                            show: false
                          }
                        },
                        legend: {
                          show: false
                        },
                      }
                    }]
                }
                  
                var chart = new ApexCharts(
                    document.querySelector("#line_chart_datalabel"),
                    options
                );
                  
                chart.render();

            });

        })(window, document, window.jQuery);

    });
</script>