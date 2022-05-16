
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
<div class="panel panel-custom">
   
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?= lang('date') ?></th>
                    <th><?= lang('account_name') ?></th>
                    <th class="col-date"><?= lang('notes') ?></th>
                    <th class="col-currency"><?= lang('amount') ?></th>
                    <th class="col-currency"><?= lang('credit') ?></th>
                    <th class="col-currency"><?= lang('debit') ?></th>
                    <th class="col-currency"><?= lang('balance') ?></th>
                    <th class="col-options no-sort"><?= lang('action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $curency = $this->report_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                $total_amount = 0;
                $total_credit = 0;
                $total_debit = 0;
                $total_balance = 0;
                $all_expense_info = get_order_by('tbl_transactions', array('type' => 'Income'), 'transactions_id');
                foreach ($all_expense_info as $v_income) :
                    $account_info = $this->report_model->check_by(array('account_id' => $v_income->account_id), 'tbl_accounts');
                    ?>
                    <tr id="table_tr_income_<?=$v_income->transactions_id;?>">
                        <td><?= display_datetime($v_income->date); ?></td>
                        <td><?= !empty($account_info->account_name) ? $account_info->account_name : '-' ?></td>
                        <td><?= $v_income->notes ?></td>
                        <td><?= display_money($v_income->amount, $curency->symbol) ?></td>
                        <td><?= display_money($v_income->credit, $curency->symbol) ?></td>
                        <td><?= display_money($v_income->debit, $curency->symbol) ?></td>
                        <td><?= display_money($v_income->total_balance, $curency->symbol) ?></td>
                        <td><?= btn_edit('admin/transactions/expense/' . $v_income->transactions_id) ?>
                          
                                  <?php echo ajax_anchor(base_url("admin/transactions/delete_expense/$v_income->transactions_id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_tr_income_" . $v_income->transactions_id)); ?>

                                                      
                            </td>
                    </tr>
                    <?php
                    $total_amount += $v_income->amount;
                    $total_credit += $v_income->credit;
                    $total_debit += $v_income->debit;
                    $total_balance += $v_income->total_balance;
                    ?>
                    <?php
                endforeach;
                ?>

                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
        <strong style=""><?= lang('balance') ?>: <span
                class="badge bg-info"><?= display_money($total_credit - $total_debit, $curency->symbol) ?></span>
        </strong>
        </div>
          <div class="col-lg-3">
        <strong class=""><?= lang('total_amount') ?>: <span
                class="badge bg-success">
                <?= display_money($total_amount, $curency->symbol) ?>
            </span>
        </strong>
    </div>
      <div class="col-lg-3">
        <strong class=""><?= lang('credit') ?>: <span
                class="badge bg-primary">
                <?= display_money($total_credit, $curency->symbol) ?>
            </span>
        </strong>
    </div>
      <div class="col-lg-3">
        <strong class=""><?= lang('debit') ?>: <span
                class="badge bg-danger">
                <?= display_money($total_debit, $curency->symbol) ?>
                </span>
        </strong>
    </div>

    </div>
</div>
</div>
</div>
</div>
