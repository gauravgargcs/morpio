
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
                <strong><?= lang('income_expense_report') ?></strong>
                <div class="pull-right hidden-print">
                    <a href="<?php echo base_url() ?>admin/report/income_expense_pdf/" class="btn btn-xs btn-success"
                       data-toggle="tooltip" data-placement="top" title="<?= lang('pdf') ?>"><?= lang('pdf') ?></a>
                    <a onclick="print_sales_report('printReport')" class="btn btn-xs btn-danger" data-toggle="tooltip"
                       data-placement="top" title="<?= lang('print') ?>"><?= lang('print') ?></a>
                </div>
            </div>
        </div>
        <div class="panel-body">


            <h5><strong><?= lang('income_expense') ?></strong></h5>
            <?php
            $curency = $this->report_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
            $mdate = date('Y-m-d H:i');
            //first day of month
            $first_day_month = date('Y-m-01 H:i');
            //first day of Weeks
            $this_week_start = date('Y-m-d H:i', strtotime('previous sunday'));
            // 30 days before
            $before_30_days = date('Y-m-d H:i', strtotime('today - 30 days'));

			
			 $total_income =$this->report_model->get_sum('tbl_transactions','amount',array('type' =>'Income'));			
                    $income_this_month = $this->report_model->get_sum('tbl_transactions','amount',array('type' =>'Income','date >=' => $first_day_month, 'date <=' => $mdate));				
                    $income_this_week = $this->report_model->get_sum('tbl_transactions','amount',array('type' =>'Income','date >=' => $this_week_start, 'date <=' => $mdate));				                   
                    $income_this_30_days = $this->report_model->get_sum('tbl_transactions','amount',array('type' =>'Income','date >=' => $before_30_days, 'date <=' => $mdate));
					
			 $total_expense =$this->report_model->get_sum('tbl_transactions','amount',array('type' =>'Expense'));			
                    $expense_this_month = $this->report_model->get_sum('tbl_transactions','amount',array('type' =>'Expense','date >=' => $first_day_month, 'date <=' => $mdate));				
                    $expense_this_week = $this->report_model->get_sum('tbl_transactions','amount',array('type' =>'Expense','date >=' => $this_week_start, 'date <=' => $mdate));				                   
                    $expense_this_30_days = $this->report_model->get_sum('tbl_transactions','amount',array('type' =>'Expense','date >=' => $before_30_days, 'date <=' => $mdate));		
					
           
            $this_week = get_result('tbl_transactions', array('date >=' => $this_week_start, 'date <=' => $mdate));
            $last_30_days = get_result('tbl_transactions', array('date >=' => $before_30_days, 'date <=' => $mdate));
            ?>
            <strong>

                <hr>
                <p><?= lang('total_income') ?>
                    : <?= display_money($total_income, $curency->symbol) ?></p>
                <p><?= lang('total_expense') ?>
                    : <?= display_money($total_expense, $curency->symbol) ?></p>

                <hr>
                <p><strong><?= lang('Income') ?>
                        - <?= lang('Expense') ?> </strong>: <?= display_money($total_income - $total_expense, $curency->symbol); ?>
                </p>

                <hr>
                <p><?= lang('total_income_this_month') ?>
                    : <?= display_money($income_this_month, $curency->symbol) ?>
                <div class="hidden-print" data-sparkline="" data-bar-color="#27c24c" data-height="30"
                     data-bar-width="5" data-bar-spacing="2"
                     data-values="
                     <?php foreach ($transactions_report as $days => $v_report) {
                         foreach ($v_report as $Expense) {
                             if ($Expense->credit != '0.00') {
                                 echo $Expense->amount . ',';
                                 ?><?php }
                         }

                     } ?>"></div>
                </p>
                <p><?= lang('total_expense_this_month') ?>
                    : <?= display_money($expense_this_month, $curency->symbol) ?>
                <div class=" hidden-print
                " data-sparkline="" data-bar-color="#f05050" data-height="30"
                     data-bar-width="5" data-bar-spacing="2"
                     data-values="
                     <?php foreach ($transactions_report as $days => $v_report) {
                         foreach ($v_report as $Expense) {
                             if ($Expense->debit != '0.00') {
                                 echo $Expense->amount . ',';
                                 ?><?php }
                         }

                     } ?>"></div>
                </p>
                <p>
                    <strong><?= lang('total') ?></strong>:
                    <?= display_money($income_this_month - $expense_this_month, $curency->symbol) ?>
                </p>
                <hr>
                <p><?= lang('total_income_this_week') ?>
                    : <?= display_money($income_this_week, $curency->symbol) ?>

                <div class="hidden-print" data-sparkline="" data-bar-color="#27c24c" data-height="30"
                     data-bar-width="5" data-bar-spacing="2"
                     data-values="
                     <?php foreach ($this_week as $v_weeks) {
                         if ($v_weeks->credit != '0.00') {
                             echo $v_weeks->amount . ',';
                             ?><?php }

                     } ?>"></div>
                </p>
                <p><?= lang('total_expense_this_week') ?>
                    : <?= display_money($expense_this_week, $curency->symbol) ?>
                <div class=" hidden-print
                " data-sparkline="" data-bar-color="#f05050" data-height="30"
                     data-bar-width="5" data-bar-spacing="2"
                     data-values="
                     <?php foreach ($this_week as $v_weeks) {
                         if ($v_weeks->debit != '0.00') {
                             echo $v_weeks->amount . ',';
                             ?><?php }

                     } ?>"></div>
                </p>
                <p>
                    <strong><?= lang('total') ?></strong>:
                    <?= display_money($income_this_week - $expense_this_week, $curency->symbol) ?>
                </p>
                <hr>
                <p><?= lang('total_income_last_30') ?>
                    : <?= display_money($income_this_30_days, $curency->symbol) ?>
                <div class="hidden-print" data-sparkline="" data-bar-color="#27c24c" data-height="30"
                     data-bar-width="5" data-bar-spacing="2"
                     data-values="
                         <?php foreach ($last_30_days as $v_30Days) {
                         if ($v_30Days->credit != '0.00') {
                             echo $v_30Days->amount . ',';
                             ?><?php }
                     } ?>"></div>
                </p>
                <p><?= lang('total_expense_last_30') ?>
                    : <?= display_money($expense_this_30_days, $curency->symbol) ?>
                <div class=" hidden-print
                " data-sparkline="" data-bar-color="#f05050" data-height="30"
                     data-bar-width="5" data-bar-spacing="2"
                     data-values="
                     <?php foreach ($last_30_days as $v_30Days) {
                         if ($v_30Days->debit != '0.00') {
                             echo $v_30Days->amount . ',';
                             ?><?php }
                     } ?>"></div>
                </p>
                <p>
                    <strong><?= lang('total') ?></strong>:
                    <?= display_money($income_this_30_days - $expense_this_30_days, $curency->symbol) ?>
                </p>

                <hr>
            </strong>
        </div>
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
