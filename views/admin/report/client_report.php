<!-- START row-->
<?php
$client_payments = 0;
$client_outstanding = 0;
$total_estimate = 0;
$started = 0;
$in_progress = 0;
$cancel = 0;
$completed = 0;

$tickets_answered = 0;
$tickets_closed = 0;
$tickets_open = 0;
$tickets_in_progress = 0;

if (!empty($all_client_info)):foreach ($all_client_info as $v_client):

    $client_payments += $this->report_model->get_sum('tbl_payments', 'amount', $array = array('paid_by' => $v_client->client_id));
    $client_outstanding += $this->invoice_model->client_outstanding($v_client->client_id);
    $client_estimates = $this->db->where('client_id', $v_client->client_id)->get('tbl_estimates')->result();
    if (!empty($client_estimates)) {
        foreach ($client_estimates as $estimate) {
            $total_estimate += $this->estimates_model->estimate_calculation('estimate_amount', $estimate->estimates_id);
        }
    }
    $project_client = $this->db->where('client_id', $v_client->client_id)->get('tbl_project')->result();

    if (!empty($project_client)) {
        foreach ($project_client as $v_project) {
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
        }

    }
    $project_tickets =get_result('tbl_tickets');
    if (!empty($project_tickets)) {
        foreach ($project_tickets as $v_tickets) {
            $profile_info = $this->db->where(array('user_id' => $v_tickets->reporter))->get('tbl_account_details')->row();
            if (!empty($profile_info)) {
                if ($profile_info->company == $v_client->client_id) {
                    if ($v_tickets->status == 'answered') {
                        $tickets_answered += count($v_tickets->status);
                    }
                    if ($v_tickets->status == 'closed') {
                        $tickets_closed += count($v_tickets->status);
                    }
                    if ($v_tickets->status == 'open') {
                        $tickets_open += count($v_tickets->status);
                    }
                    if ($v_tickets->status == 'in_progress') {
                        $tickets_in_progress += count($v_tickets->status);
                    }
                }
            }
        }

    }

endforeach;
endif;

?>

<!-- START row-->
<?= message_box('success'); ?>
<?= message_box('error'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18"> <?= lang('Client') . ' ' . lang('report') ?> 
      </h4>

      <?php $this->load->view('admin/skote_layouts/title'); ?>

  </div>
</div>
</div>
<!-- end page title -->

<div class="row">
    <div class="card"> 
        <div class="card-body">
<div class="row">

    <div class="col-md-6">
        <div id="panelChart5" class="panel panel-custom">
            <div class="panel-heading">
                <div class="panel-title"><?= lang('client') . ' ' . lang('payment') . ' ' . lang('report') ?></div>
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
                <div class="panel-title"><?= lang('client') . ' ' . lang('payment') . ' ' . lang('status') ?></div>
            </div>
            <div class="panel-body">
                <!-- <canvas id="chartjs-polarchart"></canvas> -->
                                <canvas id="polarArea" height="300"> </canvas>

            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-6">
        <div id="panelChart5" class="panel panel-custom">
            <div class="panel-heading">
                <div class="panel-title"><?= lang('client') . ' ' . lang('project') . ' ' . lang('report') ?></div>
            </div>
            <div class="panel-body">
                <!-- <div class="project_chart-pie flot-chart"></div> -->
                   <div id="project_pie_chart" class="apex-charts" dir="ltr"></div>

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div id="panelChart5" class="panel panel-custom">
            <div class="panel-heading">
                <div class="panel-title"><?= lang('client') . ' ' . lang('tickets') . ' ' . lang('report') ?></div>
            </div>
            <div class="panel-body">
                <!-- <div class="tickets_chart-pie flot-chart"></div> -->
                   <div id="ticket_pie_chart" class="apex-charts" dir="ltr"></div>

            </div>
        </div>
    </div>
</div>

</div>
</div>
</div>
<!-- <script src="<?= base_url() ?>assets/plugins/Chart/Chart.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.tooltip.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.resize.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.pie.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.time.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.categories.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.spline.min.js"></script> -->
<!-- apexcharts -->
<script src="<?php echo base_url();?>skote_assets/libs/apexcharts/apexcharts.min.js"></script>

<script src="<?php echo base_url();?>skote_assets/libs/chart.js/Chart.bundle.min.js"></script>
<script src="<?php echo base_url();?>skote_assets/js/pages/chartjs.init.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        // pie chart


var options = {
  chart: {
      height: 320,
      type: 'pie',
  }, 
  series: [<?=$client_payments;?>, <?=$client_outstanding?>, <?=$client_payments + $client_outstanding?>, <?=$total_estimate?> ],
  labels: ["<?= lang('paid_amount') ?>", "<?= lang('due_amount') ?>", "<?= lang('invoice_amount') ?>", "<?= lang('estimates') . ' ' . lang('amount')?>"],
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



var options = {
  chart: {
      height: 320,
      type: 'pie',
  }, 
  series: [<?=$started;?>, <?=$in_progress?>, <?=$completed ?>, <?=$cancel?> ],
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


var options = {
  chart: {
      height: 320,
      type: 'pie',
  }, 
  series: [<?=$tickets_answered;?>, <?=$tickets_in_progress?>, <?=$tickets_closed ?>, <?=$tickets_open?> ],
  labels: ["<?= lang('answered') ?>", "<?= lang('in_progress') ?>", "<?= lang('closed') ?>", "<?= lang('open') ?>"],
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
  document.querySelector("#ticket_pie_chart"),
  options
  );

chart.render();

        //Polar area  chart
        var polarChart = {
            datasets: [{
                data: [
                    <?= $client_payments + $client_outstanding?>,
                    <?= $client_payments?>,
                    <?= $client_outstanding?>,
                    <?= $total_estimate?>
                ],
                backgroundColor: [
                    "#f46a6a",
                    "#34c38f",
                    "#f1b44c",
                    "#556ee6"
                ],
                label: 'My dataset', // for legend
                hoverBorderColor: "#fff"
            }],
            labels: [
                "<?= lang('invoice_amount')?>",
                "<?= lang('paid_amount')?>",
                "<?= lang('due_amount')?>",
                "<?= lang('estimates') . ' ' . lang('amount')?>"
            ]
        };
       
         $.ChartJs.respChart($("#polarArea"),'PolarArea',polarChart)
       
    });

</script>