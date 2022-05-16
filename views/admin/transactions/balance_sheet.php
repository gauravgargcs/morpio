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
                <?php
                $all_transaction_info = get_result('tbl_transactions');
                if (!empty($all_transaction_info)): ?>
                <div class="pull-right hidden-print float-end">
                    <a href="<?php echo base_url() ?>admin/transactions/balance_sheet_pdf/" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('pdf') ?>"><?= lang('pdf') ?></a>
                    <a onclick="print_sales_report('printReport')" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('print') ?>"><?= lang('print') ?></a>
                </div>
                <?php endif; ?>

                <h4 class="card-title mb-4"><?php echo $title; ?></h4>
                
                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100" id="manage_balance_sheet_datatable">
                        <thead>
                        <tr>
                            <?php super_admin_opt_th() ?>
                            <th><?= lang('account') ?></th>
                            <th><?= lang('balance') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $curency = $this->transactions_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                        $total_amount = 0;
                        $all_account = get_result('tbl_accounts');
                        foreach ($all_account as $v_account):
                            ?>
                            <tr>
                                <?php super_admin_opt_td($v_account->companies_id) ?>
                                <td class="vertical-td"><?php
                                    if (!empty($v_account->account_name)) {
                                        echo $v_account->account_name;
                                    } else {
                                        echo '-';
                                    }
                                    ?></td>
                                <td><?= display_money($v_account->balance, $curency->symbol) ?></td>
                            </tr>
                            <?php
                            $total_amount += $v_account->balance;
                        endforeach;
                        if (!empty(super_admin())) {
                            $col = 2;
                        } else {
                            $col = 1;
                        }
                        ?>
                        <tr class="custom-color-with-td">
                            <th style="text-align: right;" colspan="<?= $col ?>">
                                <strong><?= lang('total') ?>:</strong></th>
                            <td><strong><?= display_money($total_amount, $curency->symbol) ?></strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function print_sales_report(printReport) {
        var printContents = document.getElementById(printReport).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

</script>
