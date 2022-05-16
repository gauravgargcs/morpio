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
                            <a class="dropdown-item" href="<?= base_url() ?>admin/transactions/transfer_report/<?= $v_account->account_id ?>"><?= $v_account->account_name ?></a>
                        <?php endforeach; endif; ?>
                        </div>
                    </div>
                    <a href="<?php echo base_url() ?>admin/transactions/transactions_report_pdf/" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('pdf') ?>"><?= lang('pdf') ?></a>
                    <a onclick="print_sales_report('printReport')" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('print') ?>"><?= lang('print') ?></a>
                </div>

                <h4 class="card-title mb-4"><?= lang('transfer_report') ?></h4>

                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100" id="manage_transfer_report_datatable">
                        <thead>
                        <tr>
                            <th style="width: 15%"><?= lang('date') ?></th>
                            <th style="width: 15%"><?= lang('from_account') ?></th>
                            <th style="width: 15%"><?= lang('to_account') ?></th>
                            <th><?= lang('type') ?></th>
                            <th><?= lang('amount') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $curency = $this->transactions_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                        $total_amount = 0;
                        if (!empty($all_transfer_info)):
                        foreach ($all_transfer_info as $v_transfer) :
                            $from_account_info = $this->transactions_model->check_by(array('account_id' => $v_transfer->from_account_id), 'tbl_accounts');
                            $to_account_info = $this->transactions_model->check_by(array('account_id' => $v_transfer->to_account_id), 'tbl_accounts');
                            ?>
                            <tr class="custom-tr custom-font-print">
                                <td><?= display_datetime($v_transfer->date); ?></td>
                                <td class="vertical-td"><?= $from_account_info->account_name ?></td>
                                <td class="vertical-td"><?= $to_account_info->account_name ?></td>
                                <td class="vertical-td"><?= lang($v_transfer->type) ?> </td>
                                <td><?= display_money($v_transfer->amount, $curency->symbol) ?></td>
                            </tr>
                            <?php
                            $total_amount += $v_transfer->amount;
                            ?>
                        <?php endforeach; ?>
                        </tbody>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top text-muted">
                <strong class=""><?= lang('total') . ' ' . lang('transfer') ?>:<span
                    class="badge badge-soft-primary">
                    <?= display_money($total_amount, $curency->symbol) ?>
                    </span></span>
                </strong>
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
