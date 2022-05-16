
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
        <!-- Default panel contents -->
        <div class="panel-heading clearfix my-3">
            <div class="panel-title">
                <strong><?= lang('transactions_report') ?></strong>

                <?php
                $all_transaction_info = get_result('tbl_transactions');
                if (!empty($all_transaction_info)):
                    ?>
                    <div class="pull-right hidden-print">
                        <a href="<?php echo base_url() ?>admin/transactions/transactions_report_pdf/"
                           class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top"
                           title="<?= lang('pdf') ?>"><?= lang('pdf') ?></a>
                        <a onclick="print_sales_report('printReport')" class="btn btn-xs btn-danger"
                           data-toggle="tooltip" data-placement="top"
                           title="<?= lang('print') ?>"><?= lang('print') ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th style="width: 15%"><?= lang('date') ?></th>
                        <th style="width: 15%"><?= lang('account') ?></th>
                        <th><?= lang('type') ?></th>
                        <th><?= lang('notes') ?></th>
                        <th><?= lang('amount') ?></th>
                        <th><?= lang('credit') ?></th>
                        <th><?= lang('debit') ?></th>
                        <th><?= lang('balance') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $total_amount = 0;
                    $total_debit = 0;
                    $total_credit = 0;
                    $total_balance = 0;
                    $curency = $this->report_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                    if (!empty($all_transaction_info)): foreach ($all_transaction_info as $v_transaction) :
                        $account_info = $this->report_model->check_by(array('account_id' => $v_transaction->account_id), 'tbl_accounts');
                        ?>
                        <tr class="custom-tr custom-font-print">
                            <td><?= display_datetime($v_transaction->date); ?></td>
                            <td><?= !empty($account_info->account_name) ? $account_info->account_name : '-' ?></td>
                            <td class="vertical-td"><?= lang($v_transaction->type) ?> </td>
                            <td class="vertical-td"><?= $v_transaction->notes ?></td>
                            <td><?= display_money($v_transaction->amount, $curency->symbol) ?></td>
                            <td><?= display_money($v_transaction->credit, $curency->symbol) ?></td>
                            <td><?= display_money($v_transaction->debit, $curency->symbol) ?></td>
                            <td><?= display_money($v_transaction->total_balance, $curency->symbol) ?></td>
                        </tr>
                        <?php
                        $total_amount += $v_transaction->amount;
                        $total_debit += $v_transaction->debit;
                        $total_credit += $v_transaction->credit;
                        $total_balance += $v_transaction->total_balance;
                        ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel-footer my-4">
        
              <div class="row">
        <div class="col-lg-3">
        <strong style=""><?= lang('total_amount') ?>: <span
                class="badge bg-info"><?= display_money($total_amount, $curency->symbol) ?></span>
        </strong>
        </div>
          <div class="col-lg-3">
        <strong class=""><?= lang('credit') ?>: <span
                class="badge bg-success">
                <?= display_money($total_credit, $curency->symbol) ?>
            </span>
        </strong>
    </div>
      <div class="col-lg-3">
        <strong class=""><?= lang('debit') ?>: <span
                class="badge bg-primary">
                <?= display_money($total_debit, $curency->symbol) ?>
            </span>
        </strong>
    </div>
      <div class="col-lg-3">
        <strong class=""><?= lang('balance') ?>: <span
                class="badge bg-danger">
                <?= display_money($total_credit - $total_debit, $curency->symbol) ?>
                </span>
        </strong>
    </div>

    </div>
        </div>
    </div>
</div>
<div class="panel panel-custom ">
    <div class="panel-heading">
        <div class="panel-title">
            <strong><?= lang('transactions_report') . ' ' . lang('graph') . ' ' . date('F-Y') ?></strong>
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
                if ($Expense->type == 'Expense') {
                    $total_expense += $Expense->amount;
                }
                if ($Expense->type == 'Income') {
                    $total_income += $Expense->amount;
                }
                if ($Expense->type == 'Transfer') {
                    $total_transfer += $Expense->amount / 2;
                }
            }
            ?>
            {
                y: "<?= $days ?>",
                income: <?= $total_income?>,
                expense: <?= $total_expense?>,
                transfer: <?= $total_transfer?>},
            <?php }?>


        ];
        // Line Chart
        // -----------------------------------

        new Morris.Line({
            element: 'morris-line',
            data: chartdata,
            xkey: 'y',
            ykeys: ["income", "expense", "transfer"],
            labels: ["<?= lang('Income')?>", "<?= lang('expense')?>", "<?= lang('transfer')?>"],
            lineColors: ["#27c24c", "#f05050", "#5d9cec"],
            parseTime: false,
            resize: true
        });

    });
    function print_sales_report(printReport) {
        var printContents = document.getElementById(printReport).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

</script>
<script type="text/javascript">
    
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
        colors: ['#556ee6', '#34c38f','#23b7e5'],
        dataLabels: {
          enabled: false,
      },
      stroke: {
          width: [3, 3],
          curve: 'straight'
      },
      series: [ {
          name: "<?=lang('Income');?>",
          data:[
            <?php foreach ($transactions_report as $days => $v_report){
            $total_expense = 0;
            $total_income = 0;
            $total_transfer = 0;
            foreach ($v_report as $Expense) {
                if ($Expense->type == 'Expense') {
                    $total_expense += $Expense->amount;
                }
                if ($Expense->type == 'Income') {
                    $total_income += $Expense->amount;
                }
                if ($Expense->type == 'Transfer') {
                    $total_transfer += $Expense->amount / 2;
                }
            }
            ?><?= $total_income?>,
               
            <?php }?>


                                            ]
                    },
                    { 
                     name: "<?=lang('Expense');?>",
          data:  [ <?php foreach ($transactions_report as $days => $v_report){
                      $total_expense = 0;
                      $total_income = 0;
                      $total_transfer = 0;
                      foreach ($v_report as $Expense) {
                          if ($Expense->type == 'Expense') {
                              $total_expense += $Expense->amount;
                          }
                          if ($Expense->type == 'Income') {
                              $total_income += $Expense->amount;
                          }
                          if ($Expense->type == 'Transfer') {
                              $total_transfer += $Expense->amount / 2;
                          }
                      }
                      ?><?= $total_expense?>,
                       
                      <?php }?>]
        }, { 
                     name: "<?=lang('Transfer');?>",
          data:  [ <?php foreach ($transactions_report as $days => $v_report){
            $total_expense = 0;
            $total_income = 0;
            $total_transfer = 0;
            foreach ($v_report as $Expense) {
                if ($Expense->type == 'Expense') {
                    $total_expense += $Expense->amount;
                }
                if ($Expense->type == 'Income') {
                    $total_income += $Expense->amount;
                }
                if ($Expense->type == 'Transfer') {
                    $total_transfer += $Expense->amount / 2;
                }
            }
            ?> <?= $total_transfer?>,
             
            <?php }?>]
        },
                       
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
                              categories: [ 
                              <?php foreach ($transactions_report as $days => $v_report){
                                $total_expense = 0;
                                $total_income = 0;
                                $total_transfer = 0;
                                foreach ($v_report as $Expense) {
                                    if ($Expense->type == 'Expense') {
                                        $total_expense += $Expense->amount;
                                    }
                                    if ($Expense->type == 'Income') {
                                        $total_income += $Expense->amount;
                                    }
                                    if ($Expense->type == 'Transfer') {
                                        $total_transfer += $Expense->amount / 2;
                                    }
                                }
                                ?>
                                "<?= $days ?>",
                            <?php }?>
                            ],
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

<!-- apexcharts -->
<script src="<?php echo base_url();?>skote_assets/libs/apexcharts/apexcharts.min.js"></script>
