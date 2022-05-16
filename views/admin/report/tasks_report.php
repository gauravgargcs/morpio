<!-- START row-->
<?= message_box('success'); ?>
<?= message_box('error'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18"> <?= lang('task') . ' ' . lang('report') ?> 
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
                    <div class="panel-title"><?= lang('task') . ' ' . lang('report') ?></div>
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
                <div class="panel-title"><?= lang('total') . ' ' . lang('task') . ' ' . lang('time_spent') ?></div>
            </div>
            <div class="panel-body">
                <div class="form-group col-sm-12">
                    <?php
                    $tasks_info = $this->report_model->get_permission('tbl_task');
                    $task_time = 0;
                    if (!empty($tasks_info)) {
                        foreach ($tasks_info as $v_tasks) {
                            $task_time += $this->report_model->task_spent_time_by_id($v_tasks->task_id);
                        }
                    }
                    echo $this->report_model->get_time_spent_result($task_time)

                    ?>

                </div>
            </div>
        </div>
    </div>
    <!-- END row-->

    <div class="col-lg-12">
        <div class="panel panel-custom">
            <div class="panel-heading"><?= lang('tasks_r_assignment') ?></div>
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
$not_started = 0;
$in_progress = 0;
$completed = 0;
$deferred = 0;
$waiting_for_someone = 0;

if (!empty($all_tasks)) : foreach ($all_tasks as $v_tasks) :
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
<script type="text/javascript">
    $(document).ready(function() {
     
// column chart

 var categories_val = [
            <?php if (!empty($user_tasks)) : foreach ($user_tasks as $user => $v_task_user) :
                    if ($user != 'all') {
                        if (!empty($assign_user)) {
                            foreach ($assign_user as $v_user) {
                                if ($v_user->user_id == $user) {
            ?> '<?= $v_user->username ?>',
            <?php } 
        }
        }
        }
          endforeach;
            endif;
             ?>];
 var notstarted = [
            <?php if (!empty($user_tasks)) : foreach ($user_tasks as $user => $v_task_user) :
                    if ($user != 'all') {
                        if (!empty($assign_user)) {
                            foreach ($assign_user as $v_user) {
                                if ($v_user->user_id == $user) {

            ?>  <?php
                                        $inparogress = 0;
                                        $notstarted = 0;
                                        $sdeferred = 0;
                                        foreach ($v_task_user as $status => $value) {
                                            if ($status == 'not_started') {
                                                $notstarted = count($value);
                                            } elseif ($status == 'in_progress') {
                                                $inparogress = count($value);
                                            } elseif ($status == 'deferred') {
                                                $sdeferred = count($value);
                                            }
                                        } ?>
                                        <?= $notstarted ?>,
            <?php } 
        }
        }
        }
          endforeach;
            endif;
             ?>];
              var inparogress = [
            <?php if (!empty($user_tasks)) : foreach ($user_tasks as $user => $v_task_user) :
                    if ($user != 'all') {
                        if (!empty($assign_user)) {
                            foreach ($assign_user as $v_user) {
                                if ($v_user->user_id == $user) {

            ?>  <?php
                                        $inparogress = 0;
                                        $notstarted = 0;
                                        $sdeferred = 0;
                                        foreach ($v_task_user as $status => $value) {
                                            if ($status == 'not_started') {
                                                $notstarted = count($value);
                                            } elseif ($status == 'in_progress') {
                                                $inparogress = count($value);
                                            } elseif ($status == 'deferred') {
                                                $sdeferred = count($value);
                                            }
                                        } ?>
                                        <?= $inparogress ?>,
            <?php } 
        }
        }
        }
          endforeach;
            endif;
             ?>];
              var sdeferred = [
            <?php if (!empty($user_tasks)) : foreach ($user_tasks as $user => $v_task_user) :
                    if ($user != 'all') {
                        if (!empty($assign_user)) {
                            foreach ($assign_user as $v_user) {
                                if ($v_user->user_id == $user) {

            ?>  <?php
                                        $inparogress = 0;
                                        $notstarted = 0;
                                        $sdeferred = 0;
                                        foreach ($v_task_user as $status => $value) {
                                            if ($status == 'not_started') {
                                                $notstarted = count($value);
                                            } elseif ($status == 'in_progress') {
                                                $inparogress = count($value);
                                            } elseif ($status == 'deferred') {
                                                $sdeferred = count($value);
                                            }
                                        } ?>
                                        <?= $sdeferred ?>,
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
        name: '<?= lang('not_started') ?>',
        data: notstarted
    }, {
        name: '<?= lang('in_progress') ?>',
        data: inparogress
    }, {
        name: '<?= lang('deferred') ?>',
        data: sdeferred
    }],
    colors: ['#34c38f', '#556ee6', '#f46a6a'],
    xaxis: {
        categories:categories_val,
    },
    yaxis: {
        title: {
            text: '(Tasks)',
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
  series: [<?= $not_started ?>, <?= $in_progress ?>, <?= $completed ?>,  <?= $deferred ?>,<?= $waiting_for_someone ?>],
  labels: ["<?= lang('not_started') ?>", "<?= lang('in_progress') ?>", "<?= lang('completed') ?>", "<?= lang('deferred') ?>", "<?= lang('waiting_for_someone') ?>"],
  colors: ["#34c38f", "#556ee6","#f46a6a", "#50a5f1", "#f1b44c","#ff902b"],
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
</script>
<!-- apexcharts -->
<script src="<?php echo base_url();?>skote_assets/libs/apexcharts/apexcharts.min.js"></script>