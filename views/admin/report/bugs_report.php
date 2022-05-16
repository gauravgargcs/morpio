
<!-- START row-->
<?= message_box('success'); ?>
<?= message_box('error'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18"> <?= lang('Bugs') . ' ' . lang('report') ?> 
      </h4>

      <?php $this->load->view('admin/skote_layouts/title'); ?>

  </div>
</div>
</div>
<!-- end page title -->

<div class="row">
    <div class="card"> 
        <div class="card-body">

            <!-- START row-->

            <div class="row">

                <div class="col-md-6">
                    <div id="panelChart5" class="panel panel-custom">
                        <div class="panel-heading">
                            <div class="panel-title"><?= lang('bugs') . ' ' . lang('report') ?></div>
                        </div>
                        <div class="panel-body">
                            <!-- <div class="chart-pie flot-chart"></div> -->
                            <div id="pie_chart" class="apex-charts" dir="ltr"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">

                    <div id="panelChart4" class="panel panel-custom">
                        <div class="panel-heading">
                            <div class="panel-title"><?= lang('bugs') . ' ' . lang('report') . ' ' . date('Y') ?></div>
                        </div>
                        <div class="panel-body">
                            <!-- <div class="chart-line flot-chart"></div> -->
                            <div id="line_chart_datalabel" class="apex-charts" dir="ltr"></div>
                        </div>
                    </div>
                </div>
                <!-- END row-->

                <div class="col-lg-12">
                    <div class="panel panel-custom">
                        <div class="panel-heading"><?= lang('bugs_r_assignment') ?></div>
                        <div class="panel-body">
                            <!-- <div id="morris-bar"></div> -->
                            <div id="column_chart" class="apex-charts" dir="ltr"></div>
                        </div>
                    </div>
                </div>
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

$bugs_info = $this->report_model->get_permission('tbl_bug');

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
?>

<script type="text/javascript">
    $(document).ready(function() {


// column chart

var categories_val = [
<?php if (!empty($user_bugs)) : foreach ($user_bugs as $user => $v_bugs_user) :
    if ($user != 'all') {
        if (!empty($assign_user)) {
            foreach ($assign_user as $v_user) {
                if ($v_user->user_id == $user) {
                    ?> '<?=$v_user->username ?>',
                <?php } 
            }
        }
    }
endforeach;
endif;
?>];
var aunconfirmed = [
<?php if (!empty($user_bugs)) : foreach ($user_bugs as $user => $v_bugs_user) :
    if ($user != 'all') {
        if (!empty($assign_user)) {
            foreach ($assign_user as $v_user) {
                if ($v_user->user_id == $user) {

                    ?>   <?php
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
                    <?= $aunconfirmed ?>,
                <?php } 
            }
        }
    }
endforeach;
endif;
?>];
var inparogress = [
<?php if (!empty($user_bugs)) : foreach ($user_bugs as $user => $v_bugs_user) :
    if ($user != 'all') {
        if (!empty($assign_user)) {
            foreach ($assign_user as $v_user) {
                if ($v_user->user_id == $user) {

                    ?>   <?php
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
                    <?= $inparogress ?>,
                <?php } 
            }
        }
    }
endforeach;
endif;
?>];
var averified = [
<?php if (!empty($user_bugs)) : foreach ($user_bugs as $user => $v_bugs_user) :
    if ($user != 'all') {
        if (!empty($assign_user)) {
            foreach ($assign_user as $v_user) {
                if ($v_user->user_id == $user) {

                    ?>   <?php
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
                    <?= $averified ?>,
                <?php } 
            }
        }
    }
endforeach;
endif;
?>];
var aresolved = [
<?php if (!empty($user_bugs)) : foreach ($user_bugs as $user => $v_bugs_user) :
    if ($user != 'all') {
        if (!empty($assign_user)) {
            foreach ($assign_user as $v_user) {
                if ($v_user->user_id == $user) {

                    ?>   <?php
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
                    <?= $aresolved ?>,
                <?php } 
            }
        }
    }
endforeach;
endif;
?>];

var options = {
    chart: {
        height: 350,
        type: 'bar',
        toolbar: {
            show: false,
        }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '45%',
            endingShape: 'rounded'  
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    series: [{
        name: '<?= lang('averified') ?>',
        data: averified
    }, {
        name: '<?= lang('in_progress') ?>',
        data: inparogress
    }, {
        name: '<?= lang('aunconfirmed') ?>',
        data: aunconfirmed
    },
    {
        name: '<?= lang('aresolved') ?>',
        data: aresolved
    }
    ],

    colors: ['#34c38f', '#556ee6', '#f46a6a'],
    xaxis: {
        categories:categories_val,
    },
    yaxis: {
        title: {
            text: '(Bugs)',
            style: {
                fontWeight:  '500',
            },
        }
    },
    grid: {
        borderColor: '#f1f1f1',
    },
    fill: {
        opacity: 1

    },
    tooltip: {
        y: {
            formatter: function (val) {
                return " " + val + " "
            }
        }
    }
}

var chart = new ApexCharts(
    document.querySelector("#column_chart"),
    options
    );

chart.render();


// pie chart


var options = {
  chart: {
      height: 320,
      type: 'pie',
  }, 
  series: [<?= $unconfirmed ?>, <?= $in_progress ?>, <?= $confirmed ?>,  <?= $resolved ?>,<?= $verified ?>],
  labels: ["<?= lang('unconfirmed') ?>", "<?= lang('in_progress') ?>", "<?= lang('confirmed') ?>", "<?= lang('resolved') ?>", "<?= lang('verified') ?>"],
  colors: ["#34c38f", "#556ee6","#f46a6a", "#50a5f1", "#f1b44c",],
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
      series: [{
          name: "<?=lang('unconfirmed');?>",
          data:[
          <?php foreach ($yearly_report as $name => $v_report) :
                            $month_name = date('M', strtotime(date('Y') . '-' . $name)); // get full name of month by date query
                            ?> <?php
                            $y_not_started = 0;
                            foreach ($v_report as $s_report) {
                                if ($s_report->bug_status == 'unconfirmed')
                                    $y_not_started += count($s_report->bug_status);
                            }
                                                echo $y_not_started; // view the total report in a  month
                                                ?>,
                                            <?php endforeach; ?>
                                            ]
                                        },
                                        {
                                          name: "<?=lang('in_progress');?>",
                                          data: [
                                          <?php foreach ($yearly_report as $name => $v_report) :
                            $month_name = date('M', strtotime(date('Y') . '-' . $name)); // get full name of month by date query
                            ?> <?php
                            $y_not_started = 0;
                            foreach ($v_report as $s_report) {
                                if ($s_report->bug_status == 'in_progress')
                                    $y_not_started += count($s_report->bug_status);
                            }
                                                echo $y_not_started; // view the total report in a  month
                                                ?>,
                                            <?php endforeach; ?>
                                            ]
                                        },
                                        {
                                          name: "<?=lang('confirmed');?>",
                                          data: [
                                          <?php foreach ($yearly_report as $name => $v_report) :
                            $month_name = date('M', strtotime(date('Y') . '-' . $name)); // get full name of month by date query
                            ?> <?php
                            $y_not_started = 0;
                            foreach ($v_report as $s_report) {
                                if ($s_report->bug_status == 'confirmed')
                                    $y_not_started += count($s_report->bug_status);
                            }
                                                echo $y_not_started; // view the total report in a  month
                                                ?>,
                                            <?php endforeach; ?>
                                            ]
                                        }
                                        ,
                                        {
                                          name: "<?=lang('resolved');?>",
                                          data: [
                                          <?php foreach ($yearly_report as $name => $v_report) :
                            $month_name = date('M', strtotime(date('Y') . '-' . $name)); // get full name of month by date query
                            ?> <?php
                            $y_not_started = 0;
                            foreach ($v_report as $s_report) {
                                if ($s_report->bug_status == 'resolved')
                                    $y_not_started += count($s_report->bug_status);
                            }
                                                echo $y_not_started; // view the total report in a  month
                                                ?>,
                                            <?php endforeach; ?>
                                            ]
                                        }
                                        ,
                                        {
                                          name: "<?=lang('verified');?>",
                                          data: [
                                          <?php foreach ($yearly_report as $name => $v_report) :
                            $month_name = date('M', strtotime(date('Y') . '-' . $name)); // get full name of month by date query
                            ?> <?php
                            $y_not_started = 0;
                            foreach ($v_report as $s_report) {
                                if ($s_report->bug_status == 'verified')
                                    $y_not_started += count($s_report->bug_status);
                            }
                                                echo $y_not_started; // view the total report in a  month
                                                ?>,
                                            <?php endforeach; ?>
                                            ]
                                        }
                                        ],
                                        title: {
                                          text: 'Status',
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
                              size: 6
                            },
                            xaxis: {
                              categories: [  <?php foreach ($yearly_report as $name => $v_report) :
                                                $month_name = date('M', strtotime(date('Y') . '-' . $name)); // get full name of month by date query
                            ?>"<?= $month_name ?>",
                            <?php endforeach; ?>],
                            title: {
                                text: 'Month'
                            }
                        },
                        yaxis: {
                          title: {
                            text: 'Bugs'
                        },
                        min: 5,
                        max: 40
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
</script>
<!-- apexcharts -->
<script src="<?php echo base_url();?>skote_assets/libs/apexcharts/apexcharts.min.js"></script>