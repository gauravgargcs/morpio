<div id="panelChart5" class="card-body">
    <h4 class="card-title mb-4"><?= lang('bugs') . ' ' . lang('report') ?></h4>
    <div id="bug_pie_chart" class="apex-charts" dir="ltr"></div>
</div>
<?php
$unconfirmed = 0;
$in_progress = 0;
$confirmed = 0;
$resolved = 0;
$verified = 0;

$bugs_info = $this->user_model->my_permission('tbl_bug', $profile_info->user_id);

if (!empty($bugs_info)):foreach ($bugs_info as $v_bugs):
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
<?php if (!empty($unconfirmed) || !empty($in_progress) || !empty($confirmed) || !empty($resolved) || !empty($verified)) {?>
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
                      series: [<?= $unconfirmed ?>, <?= $in_progress ?>, <?= $confirmed ?>, <?= $resolved ?>,<?= $verified ?>],
                      labels: ["<?= lang('unconfirmed') ?>", "<?= lang('in_progress') ?>", "<?= lang('confirmed') ?>", "<?= lang('resolved') ?>", "<?= lang('verified') ?>"],
                      colors: ["#34c38f", "#556ee6","#f46a6a", "#50a5f1","#27c24c"],
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
                      document.querySelector("#bug_pie_chart"),
                      options
                    );

                    chart.render();
                });

            })(window, document, window.jQuery);


        });

    </script>
<?php } ?>