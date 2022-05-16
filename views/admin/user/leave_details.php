<div class="card-body">
    <h4 class="card-title mb-4"><?= lang('leave') . ' ' . lang('details_of') . ' ' . $profile_info->fullname ?></h4>
    <table class="table">
        <tbody>
            <?php
            $total_taken = 0;
            $total_quota = 0;
            $leave_report = leave_report($profile_info->user_id);

            if (!empty($leave_report['leave_category'])) {
                foreach ($leave_report['leave_category'] as $lkey => $v_l_report) {
                    $total_quota += $leave_report['leave_quota'][$lkey];
                    $total_taken += $leave_report['leave_taken'][$lkey];
                    ?>
                    <tr>
                        <td><strong> <?= $leave_report['leave_category'][$lkey] ?></strong>:</td>
                        <td>
                            <?= $leave_report['leave_taken'][$lkey] ?>/<?= $leave_report['leave_quota'][$lkey]; ?> </td>
                    </tr>
                <?php }
            }
            ?>

            <tr>
                <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;">
                    <strong> <?= lang('total') ?></strong>:
                </td>
                <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;"> <?= $total_taken; ?>
                    /<?= $total_quota; ?> </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="card-body">
    <h4 class="card-title mb-4"><?= lang('leave_report') ?></h4>
    <div id="leave_pie_chart" class="apex-charts" dir="ltr"></div>
</div>
<?php
$all_category = get_result('tbl_leave_category');
$color = array('37bc9b', '7266ba', 'f05050', 'ff902b', '7266ba', 'f532e5', '5d9cec', '7cd600', '91ca00', 'ff7400', '1cc200', 'bb9000', '40c400');
foreach ($all_category as $key => $v_category) {
    if (!empty($my_leave_report['leave_taken'][$key])) {
        $a = $my_leave_report['leave_taken'][$key];
    }
}
if (!empty($a)) {
    ?>
    <script type="text/javascript">
      
        // CHART PIE -----------------------------------
            (function(window, document, $, undefined) {
                $(function() {
                    // pie chart
                    var options = {
                      chart: {
                          height: 320,
                          type: 'pie',
                      }, 
                      series: [
                                <?php
                                if(!empty($all_category)){
                                foreach ($all_category as $key => $v_category) {
                                if (!empty($my_leave_report['leave_taken'][$key])) {
                                $result = $my_leave_report['leave_taken'][$key];
                                ?> <?= $result?> <?php } } } ?>
                            ],
                      labels: [ <?php
                                if(!empty($all_category)){
                                foreach ($all_category as $key => $v_category) {
                                if (!empty($my_leave_report['leave_taken'][$key])) {
                                $result = $my_leave_report['leave_taken'][$key];
                                ?>
                                "<?= $v_category->leave_category . ' ( <small>' . lang('quota') . ': ' . $my_leave_report['leave_quota'][$key] . ' ' . lang('taken') . ': ' . $result . '</small>)'?>", <?php } } } ?>
                              ],
                      colors: [
                                <?php
                                if(!empty($all_category)){
                                foreach ($all_category as $key => $v_category) {
                                if (!empty($my_leave_report['leave_taken'][$key])) {
                                $result = $my_leave_report['leave_taken'][$key];
                                ?>

                                "#<?=$color[$key] ?>",

                                <?php } } } ?>
                              ],
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
                      document.querySelector("#leave_pie_chart"),
                      options
                    );

                    chart.render();
                });

            })(window, document, window.jQuery);


    </script>
<?php } ?>
