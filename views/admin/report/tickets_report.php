
<!-- START row-->
<?= message_box('success'); ?>
<?= message_box('error'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18"> <?= lang('Ticket') . ' ' . lang('report') ?> 
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
                <div class="panel-title"><?= lang('tickets') . ' ' . lang('report') ?></div>
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
                <div class="panel-title"><?= lang('tickets') . ' ' . lang('report') . ' ' . date('Y') ?></div>
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
            <div class="panel-heading"><?= lang('tickets_r_assignment') ?></div>
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
$answered = 0;
$closed = 0;
$open = 0;
$in_progress = 0;

$tickets_info = $this->report_model->get_permission('tbl_tickets');
if (!empty($tickets_info)) : foreach ($tickets_info as $v_tickets) :
        if ($v_tickets->status == 'answered') {
            $answered += count($v_tickets->status);
        }
        if ($v_tickets->status == 'closed') {
            $closed += count($v_tickets->status);
        }
        if ($v_tickets->status == 'open') {
            $open += count($v_tickets->status);
        }
        if ($v_tickets->status == 'in_progress') {
            $in_progress += count($v_tickets->status);
        }
    endforeach;
endif;
?>

<script type="text/javascript">
    $(document).ready(function() {
       /* var chartdata = [
            <?php if (!empty($user_tickets)) : foreach ($user_tickets as $user => $v_ticket_user) :
                    if ($user != 'all') {
                        if (!empty($assign_user)) {
                            foreach ($assign_user as $v_user) {
                                if ($v_user->user_id == $user) {
            ?> {
                                        y: "<?= $v_user->username ?>",
                                        <?php
                                        $aanswered = 0;
                                        $inparogress = 0;
                                        $aopen = 0;
                                        $aclosed = 0;
                                        foreach ($v_ticket_user as $status => $value) {
                                            if ($status == 'answered') {
                                                $aanswered = count($value);
                                            } elseif ($status == 'in_progress') {
                                                $inparogress = count($value);
                                            } elseif ($status == 'open') {
                                                $aopen = count($value);
                                            } elseif ($status == 'closed') {
                                                $aclosed = count($value);
                                            }
                                        }
                                        ?>
                                        a: <?= $aanswered; ?>,

                                        b: <?= $inparogress ?>,
                                        c: <?= $aopen ?>,
                                        d: <?= $aclosed ?>
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
                labels: ["<?= lang('answered') ?>", "<?= lang('in_progress') ?>", "<?= lang('open') ?>", "<?= lang('closed') ?>"],
                xLabelMargin: 2,
                barColors: ['#ff902b', '#5d9cec', '#27c24c', '#7266ba'],
                resize: true,
                parseTime: false,
            });
        }*/


// column chart

var categories_val = [
<?php if (!empty($user_tickets)) : foreach ($user_tickets as $user => $v_ticket_user) :
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
var aanswered = [
<?php if (!empty($user_tickets)) : foreach ($user_tickets as $user => $v_ticket_user) :
    if ($user != 'all') {
        if (!empty($assign_user)) {
            foreach ($assign_user as $v_user) {
                if ($v_user->user_id == $user) {

                    ?>   <?php
                  $aanswered = 0;
                                        $inparogress = 0;
                                        $aopen = 0;
                                        $aclosed = 0;
                                        foreach ($v_ticket_user as $status => $value) {
                                            if ($status == 'answered') {
                                                $aanswered = count($value);
                                            } elseif ($status == 'in_progress') {
                                                $inparogress = count($value);
                                            } elseif ($status == 'open') {
                                                $aopen = count($value);
                                            } elseif ($status == 'closed') {
                                                $aclosed = count($value);
                                            }
                                        }
                    ?>
                    <?= $aanswered ?>,
                <?php } 
            }
        }
    }
endforeach;
endif;
?>];
var inparogress = [
<?php if (!empty($user_tickets)) : foreach ($user_tickets as $user => $v_ticket_user) :
    if ($user != 'all') {
        if (!empty($assign_user)) {
            foreach ($assign_user as $v_user) {
                if ($v_user->user_id == $user) {

                    ?>   <?php
                   $aanswered = 0;
                                        $inparogress = 0;
                                        $aopen = 0;
                                        $aclosed = 0;
                                        foreach ($v_ticket_user as $status => $value) {
                                            if ($status == 'answered') {
                                                $aanswered = count($value);
                                            } elseif ($status == 'in_progress') {
                                                $inparogress = count($value);
                                            } elseif ($status == 'open') {
                                                $aopen = count($value);
                                            } elseif ($status == 'closed') {
                                                $aclosed = count($value);
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
var aopen = [
<?php if (!empty($user_tickets)) : foreach ($user_tickets as $user => $v_ticket_user) :
    if ($user != 'all') {
        if (!empty($assign_user)) {
            foreach ($assign_user as $v_user) {
                if ($v_user->user_id == $user) {

                    ?>   <?php
                   $aanswered = 0;
                                        $inparogress = 0;
                                        $aopen = 0;
                                        $aclosed = 0;
                                        foreach ($v_ticket_user as $status => $value) {
                                            if ($status == 'answered') {
                                                $aanswered = count($value);
                                            } elseif ($status == 'in_progress') {
                                                $inparogress = count($value);
                                            } elseif ($status == 'open') {
                                                $aopen = count($value);
                                            } elseif ($status == 'closed') {
                                                $aclosed = count($value);
                                            }
                                        }
                    ?>
                    <?= $aopen ?>,
                <?php } 
            }
        }
    }
endforeach;
endif;
?>];
var aclosed = [
<?php if (!empty($user_tickets)) : foreach ($user_tickets as $user => $v_ticket_user) :
    if ($user != 'all') {
        if (!empty($assign_user)) {
            foreach ($assign_user as $v_user) {
                if ($v_user->user_id == $user) {

                    ?>   <?php
                    $aanswered = 0;
                    $inparogress = 0;
                    $aopen = 0;
                    $aclosed = 0;
                    foreach ($v_ticket_user as $status => $value) {
                        if ($status == 'answered') {
                            $aanswered = count($value);
                        } elseif ($status == 'in_progress') {
                            $inparogress = count($value);
                        } elseif ($status == 'open') {
                            $aopen = count($value);
                        } elseif ($status == 'closed') {
                            $aclosed = count($value);
                        }
                    }
                    ?>
                    <?= $aclosed ?>,
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
        name: '<?= lang('aanswered') ?>',
        data: aanswered
    }, {
        name: '<?= lang('in_progress') ?>',
        data: inparogress
    }, {
        name: '<?= lang('aopen') ?>',
        data: aopen
    },
    {
        name: '<?= lang('aclosed') ?>',
        data: aclosed
    }
    ],

    colors: ['#34c38f', '#556ee6', '#f46a6a'],
    xaxis: {
        categories:categories_val,
    },
    yaxis: {
        title: {
            text: '(Tickets)',
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
        // // CHART PIE

// pie chart


var options = {
  chart: {
      height: 320,
      type: 'pie',
  }, 
  series: [<?= $answered ?>, <?= $closed ?>, <?= $open ?>,  <?= $in_progress ?>],
  labels: ["<?= lang('answered') ?>", "<?= lang('closed') ?>", "<?= lang('open') ?>", "<?= lang('in_progress') ?>"],
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
          name: "<?=lang('answered');?>",
          data:[
                        <?php foreach ($yearly_report as $name => $v_report) :
                            $month_name = date('M', strtotime(date('Y') . '-' . $name)); // get full name of month by date query
                        ?> <?php
                                                    $y_not_started = 0;
                                                    foreach ($v_report as $s_report) {
                                                        if ($s_report->status == 'answered')
                                                            $y_not_started += count($s_report->status);
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
                                                        if ($s_report->status == 'in_progress')
                                                            $y_not_started += count($s_report->status);
                                                    }
                                                    echo $y_not_started; // view the total report in a  month
                                                    ?>,
                        <?php endforeach; ?>
                    ]
                                        },
                                        {
                                          name: "<?=lang('open');?>",
                                          data: [
                        <?php foreach ($yearly_report as $name => $v_report) :
                            $month_name = date('M', strtotime(date('Y') . '-' . $name)); // get full name of month by date query
                        ?> <?php
                                                    $y_not_started = 0;
                                                    foreach ($v_report as $s_report) {
                                                        if ($s_report->status == 'open')
                                                            $y_not_started += count($s_report->status);
                                                    }
                                                    echo $y_not_started; // view the total report in a  month
                                                    ?>,
                        <?php endforeach; ?>
                    ]
                                        }
                                        ,
                                        {
                                          name: "<?=lang('closed');?>",
                                          data:[
                        <?php foreach ($yearly_report as $name => $v_report) :
                            $month_name = date('M', strtotime(date('Y') . '-' . $name)); // get full name of month by date query
                        ?> <?php
                                                    $y_not_started = 0;
                                                    foreach ($v_report as $s_report) {
                                                        if ($s_report->status == 'closed')
                                                            $y_not_started += count($s_report->status);
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
                            text: 'Tickets'
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