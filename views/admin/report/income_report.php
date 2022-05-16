
<!-- START row-->
<?= message_box('success'); ?>
<?= message_box('error'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18"> <?= $title ?> 
            </h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="card"> 
        <div class="card-body">
<div id="printReport">
    <div class="show_print">
        <div style="width: 100%; border-bottom: 2px solid black;">
            <table style="width: 100%; vertical-align: middle;">
                <tr>
                    <td style="width: 50px; border: 0px;">
                        <img style="width: 50px;height: 50px;margin-bottom: 5px;"
                             src="<?= base_url() . config_item('company_logo') ?>" alt="" class="img-circle"/>
                    </td>

                    <td style="border: 0px;">
                        <p style="margin-left: 10px; font: 14px lighter;"><?= config_item('company_name') ?></p>
                    </td>

                </tr>
            </table>
        </div>
        <br/>
    </div>
    <div class="panel panel-custom">
        <div class="panel-heading">
            <div class="panel-title">
                <strong><?= lang('income_report') ?></strong>
                <div class="pull-right hidden-print">
                    <a href="<?php echo base_url() ?>admin/report/income_report_pdf/" class="btn btn-xs btn-success"
                       data-toggle="tooltip" data-placement="top" title="<?= lang('pdf') ?>"><?= lang('pdf') ?></a>
                    <a onclick="print_sales_report('printReport')" class="btn btn-xs btn-danger" data-toggle="tooltip"
                       data-placement="top" title="<?= lang('print') ?>"><?= lang('print') ?></a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <h5><strong><?= lang('income_summary') ?></strong></h5>
            <hr>
            <strong>
                <p>
                    <?= lang('total_income') ?>: <?php
                    $curency = $this->report_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                    $mdate = date('Y-m-d H:i');
                    //first day of month
                    $first_day_month = date('Y-m-01 H:i');
                    //first day of Weeks
                    $this_week_start = date('Y-m-d H:i', strtotime('previous sunday'));
                    // 30 days before
                    $before_30_days = date('Y-m-d H:i', strtotime('today - 30 days'));

                    $total_income =$this->report_model->get_sum('tbl_transactions','amount',array('type' =>'Income'));			
                    $this_month = $this->report_model->get_sum('tbl_transactions','amount',array('type' =>'Income','date >=' => $first_day_month, 'date <=' => $mdate));				
                    $this_week = $this->report_model->get_sum('tbl_transactions','amount',array('type' =>'Income','date >=' => $this_week_start, 'date <=' => $mdate));				                   
                    $this_30_days = $this->report_model->get_sum('tbl_transactions','amount',array('type' =>'Income','date >=' => $before_30_days, 'date <=' => $mdate));				                   
                   
                    echo display_money($total_income, $curency->symbol);
                    ?></p>
                <p><?= lang('total_income_this_month') ?>
                    : <?= display_money($this_month, $curency->symbol) ?></p>
                <p><?= lang('total_income_this_week') ?>
                    : <?= display_money($this_week, $curency->symbol) ?></p>
                <p><?= lang('total_income_last_30') ?>
                    : <?= display_money($this_30_days, $curency->symbol) ?></p>
            </strong>

            <hr>

            <h4><?= lang('last_deposit_income') ?></h4>
            <hr>
            <table class="table table-striped table-bordered">
                <tbody>
                <tr>
                    <th><?= lang('date') ?></th>
                    <th><?= lang('account') ?></th>
                    <th><?= lang('deposit_category') ?></th>
                    <th><?= lang('paid_by') ?></th>
                    <th><?= lang('description') ?></th>
                    <th><?= lang('amount') ?></th>
                    <th><?= lang('credit') ?></th>
                    <th><?= lang('balance') ?></th>
                </tr>
                <?php
                $total_amount = 0;
                $total_credit = 0;
                $total_balance = 0;
                $all_deposit_info = get_order_by('tbl_transactions', array('type' => 'Income'), 'transactions_id', null, 20);

                foreach ($all_deposit_info as $v_deposit) :
                    $account_info = $this->report_model->check_by(array('account_id' => $v_deposit->account_id), 'tbl_accounts');
                    $client_info = $this->report_model->check_by(array('client_id' => $v_deposit->paid_by), 'tbl_client');
                    $category_info = $this->report_model->check_by(array('income_category_id' => $v_deposit->category_id), 'tbl_income_category');
                    if (!empty($client_info)) {
                        $client_name = $client_info->name;
                    } else {
                        $client_name = '-';
                    }
                    ?>
                    <tr>
                        <td><?= display_datetime($v_deposit->date); ?></td>
                        <td><?= !empty($account_info->account_name) ? $account_info->account_name : '-' ?></td>
                        <td><?php
                            if (!empty($category_info)) {
                                echo $category_info->income_category;
                            } else {
                                echo '-';
                            }
                            ?></td>
                        <td><?= $client_name ?></td>
                        <td><?= $v_deposit->notes ?></td>
                        <td><?= display_money($v_deposit->amount, $curency->symbol) ?></td>
                        <td><?= display_money($v_deposit->debit, $curency->symbol) ?></td>
                        <td><?= display_money($v_deposit->total_balance, $curency->symbol) ?></td>

                    </tr>
                    <?php
                    $total_amount += $v_deposit->amount;
                    $total_credit += $v_deposit->credit;
                    $total_balance += $v_deposit->total_balance;
                    ?>
                    <?php
                endforeach;
                ?>
                <tr class="custom-color-with-td">
                    <td style="text-align: right;" colspan="5"><strong><?= lang('total') ?>:</strong></td>
                    <td><strong><?= display_money($total_amount, $curency->symbol) ?></strong></td>
                    <td><strong><?= display_money($total_credit, $curency->symbol) ?></strong></td>
                    <td><strong><?= display_money($total_balance, $curency->symbol) ?></strong></td>
                </tr>
                </tbody>
            </table>
            <hr>

        </div>
    </div>
</div>

<div class="panel panel-custom ">
    <div class="panel-heading">
        <div class="panel-title">
            <strong><?= lang('income_report') . ' ' . lang('graph') . ' ' . date('F-Y') ?></strong>
        </div>
    </div>
    <div class="panel-body">
        <!-- <div id="morris-line"></div> -->
                            <div id="line_chart_datalabel" class="apex-charts" dir="ltr"></div>

    </div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
    $(function () {

        if (typeof Morris === 'undefined') return;

        var chartdata = [
            <?php foreach ($transactions_report as $days => $v_report){
            $total_expense = 0;
            $total_income = 0;
            $total_transfer = 0;
            foreach ($v_report as $Expense) {
                if ($Expense->type == 'Income') {
                    $total_income += $Expense->amount;
                }
            }
            ?>
            {
                y: "<?= $days ?>",
                income: <?= $total_income?>,
            },
            <?php }?>


        ];
    //     // Line Chart
    //     // -----------------------------------

    //     new Morris.Line({
    //         element: 'morris-line',
    //         data: chartdata,
    //         xkey: 'y',
    //         ykeys: ["income"],
    //         labels: ["<?= lang('Income')?>"],
    //         lineColors: ["#27c24c"],
    //         parseTime: false,
    //         resize: true
    //     });

    });
    function print_sales_report(printReport) {
        var printContents = document.getElementById(printReport).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

    $(document).ready(function() {

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
          name: "<?=lang('Income');?>",
          data:[
          <?php foreach ($transactions_report as $days => $v_report){
            $total_expense = 0;
            $total_income = 0;
            $total_transfer = 0;
            foreach ($v_report as $Expense) {
                if ($Expense->type == 'Income') {
                    $total_income += $Expense->amount;
                }
            }
            ?>
            
               
                <?= $total_income?>,
          
            <?php } ?>
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
                              categories: [ <?php foreach ($transactions_report as $days => $v_report){
            $total_expense = 0;
            $total_income = 0;
            $total_transfer = 0;
            foreach ($v_report as $Expense) {
                if ($Expense->type == 'Income') {
                    $total_income += $Expense->amount;
                }
            }
            ?>
           
               "<?= $days ?>",
           
            <?php }?> ],
                            title: {
                                text: 'Days'
                            }
                        },
                        yaxis: {
                          title: {
                            text: 'Income'
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
<style type="text/css">
    @media print
{
    
    .hidden-print, .hidden-print *{
        display: none !important;
        height: 0;
    }
}
</style>
<!-- apexcharts -->
<script src="<?php echo base_url();?>skote_assets/libs/apexcharts/apexcharts.min.js"></script>
