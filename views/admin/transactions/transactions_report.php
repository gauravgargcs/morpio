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
<div class="row" id="printReport">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="pull-right hidden-print float-end">
                    <div class="btn-group" role="group">
                        <button id="btnGroupVerticalDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?= lang('search_by') ?>"><?= lang('search_by') ?><i class="mdi mdi-chevron-down"></i></button>

                        <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">      
                        <?php
                        $all_account = get_result('tbl_accounts');
                        if (!empty($all_account)):
                            foreach ($all_account as $v_account): ?>
                            <a class="dropdown-item" href="<?= base_url() ?>admin/transactions/transactions_report/<?= $v_account->account_id ?>"><?= $v_account->account_name ?></a>            
                        <?php endforeach; endif; ?>
                        </div>
                    </div>
                    <a href="<?php echo base_url() ?>admin/transactions/transactions_report_pdf/" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('pdf') ?>"><?= lang('pdf') ?></a>
                    <a onclick="print_sales_report('printReport')" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('print') ?>"><?= lang('print') ?></a>
                </div>

                <h4 class="card-title mb-4"><?php echo $title; ?></h4>
                
                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100" id="manage_transactions_report_datatable">
                        <thead>
                        <tr>
                            <?php super_admin_opt_th() ?>
                            <th style="width: 15%"><?= lang('date') ?></th>
                            <th style="width: 15%"><?= lang('account') ?></th>
                            <th><?= lang('type') ?></th>
                            <th><?= lang('name') . '/' . lang('title') ?></th>
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
                        $curency = $this->transactions_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');

                        if (!empty($all_transaction_info)): foreach ($all_transaction_info as $v_transaction) :
                            $account_info = $this->transactions_model->check_by(array('account_id' => $v_transaction->account_id), 'tbl_accounts');
                            ?>
                            <tr class="custom-tr custom-font-print">
                                <?php super_admin_opt_td($v_transaction->companies_id) ?>
                                <td><?= display_datetime($v_transaction->date); ?></td>
                                <td class="vertical-td"><?php
                                    if (!empty($account_info->account_name)) {
                                        echo $account_info->account_name;
                                    } else {
                                        echo '-';
                                    }
                                    ?></td>
                                <td class="vertical-td"><?= lang($v_transaction->type) ?> </td>
                                <td><?= ($v_transaction->name ? $v_transaction->name : '-'); ?></td>
                                <td><?= display_money($v_transaction->amount, $curency->symbol) ?></td>
                                <td><?= display_money($v_transaction->credit, $curency->symbol) ?></td>
                                <td><?= display_money($v_transaction->debit, $curency->symbol) ?></td>
                                <td><?= display_money($v_transaction->total_balance, $curency->symbol) ?></td>
                            </tr>
                            <?php
                            $total_amount += $v_transaction->amount;
                            $total_credit += $v_transaction->credit;
                            $total_debit += $v_transaction->debit;
                            ?>
                        <?php endforeach; ?>

                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top text-muted">
                <div class="row">
                    <strong class="col-sm-3"><?= lang('total_amount') ?>:<span
                            class="badge badge-soft-success"><?= display_money($total_amount, $curency->symbol) ?></span></span>
                    </strong>
                    <strong class="col-sm-3"><?= lang('credit') ?>:<span
                            class="badge badge-soft-primary"><?= display_money($total_credit, $curency->symbol) ?></span></span>
                    </strong>
                    <strong class="col-sm-3"><?= lang('debit') ?>:<span
                            class="badge badge-soft-danger"><?= display_money($total_debit, $curency->symbol) ?></span></span>
                    </strong>
                    <strong class="col-sm-3"><?= lang('balance') ?>:<span
                            class="badge badge-soft-info"><?= display_money($total_credit - $total_debit, $curency->symbol) ?></span></span>
                    </strong>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4"> <?= lang('transactions_report') . ' ' . lang('graph') . ' ' . date('F-Y') ?></h4>
                <div id="morris-line"></div>
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
