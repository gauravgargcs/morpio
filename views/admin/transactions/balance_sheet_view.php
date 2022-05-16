<?php 
if (!empty($sheet_info)) {
    $sheet_id = $sheet_info->id;
    $companies_id = $sheet_info->companies_id;
} else {
    $sheet_id = null;
    $companies_id = null;
}

?>
<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<?php 
$created = can_action('30', 'created');

$edited = can_action('30', 'edited');
$deleted = can_action('30', 'deleted');

?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18">  <?php 
              echo lang('View Report'); 
            ?> 
            </h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    
            <h4 class="float-start card-title mb-4"><?= lang('Details') ?></h4>
            <div class="float-end hidden-print">
                   <!-- 
                    <a href="<?=base_url('admin/transactions/balance_sheet_report_pdf/'.$sheet_id);?>" class="btn btn-xs btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="PDF">PDF</a> -->

                    <a href="<?=base_url('admin/transactions/balance_sheet_add/'.$sheet_id);?>" class="btn btn-xs btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit">Edit</a>
                    <a onclick="print_sales_report('printReport')" class="btn btn-xs btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Print">Print</a>
                </div>

                </div>
                <div id="printReport">
        <div class="show_print  d-none d-print-block ">
            <div style="width: 100%; border-bottom: 2px solid black;">
                <table style="width: 100%; vertical-align: middle;">
                    <tr>
                        <td style="width: 50px; border: 0px;">
                            <img style="width: 100%;margin-bottom: 5px;"
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

            <div class="table-responsive">
                                    <table class="table table-bordered border-primary   mb-0 w-50">

                                      
                                        <tbody>
                                            <tr>
                                                <th scope="row"><?=lang('title');?></th>
                                                <td><?=$sheet_info->title;?></td>
                                               
                                            </tr>
                                              <tr>
                                                <th scope="row"><?=lang('companies');?></th>
                                                <td> <?= super_admin_opt_td($companies_id,true) ?></td>
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row"><?= lang('account') ?></th>
                                                <?php     $account_info = $this->transactions_model->check_by(array('account_id' => $sheet_info->account_id), 'tbl_accounts');?>
                                                <td><?php   if (!empty($account_info->account_name)) {
                                                        echo $account_info->account_name;
                                                    } else {
                                                        echo '-';
                                                    } ?></td>
                                               
                                            </tr>

                                            <tr>
                                                <th scope="row" colspan="2"><u> <?= lang('current_assets_caps') ?></u></th>
                                                
                                               
                                            </tr>
                                            <tr>
                                                <th scope="row" ><?= lang('cash') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->cash;?></td>
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" ><?= lang('accounts_receivable') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->accounts_receivable;?></td>
                                               
                                            </tr>
                                              <tr>
                                                <th scope="row" ><?= lang('inventory') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->inventory;?></td>
                                               
                                            </tr>
                                              <tr>
                                                <th scope="row" ><?= lang('prepaid_expenses') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->prepaid_expenses;?></td>
                                               
                                            </tr>
                                               <tr>
                                                <th scope="row" ><?= lang('notes_receivable') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->notes_receivable;?></td>
                                               
                                            </tr>
                                              <tr>
                                                <th scope="row" ><?= lang('other_current_assets') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->other_current_assets;?></td>
                                               
                                            </tr>
                                              <tr>
                                                <th scope="row" ><?= lang('TOTAL CURRENT ASSETS:') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$total_current_assets  = $sheet_info->other_current_assets+$sheet_info->cash+$sheet_info->accounts_receivable+$sheet_info->inventory+$sheet_info->prepaid_expenses+$sheet_info->notes_receivable;?></td>
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" colspan="2"><u> <?= lang('fixed_assets_caps') ?></u></th>
                                                
                                               
                                            </tr>
                                              <tr>
                                                <th scope="row" ><?= lang('long_term_investments') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->long_term_investments;?></td>
                                               
                                            </tr>  <tr>
                                                <th scope="row" ><?= lang('land') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->land;?></td>
                                               
                                            </tr>  <tr>
                                                <th scope="row" ><?= lang('building') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->building;?></td>
                                               
                                            </tr>  <tr>
                                                <th scope="row" ><?= lang('accumulated_building_depreciation') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->accumulated_building_depreciation;?></td>
                                               
                                            </tr>  <tr>
                                                <th scope="row" ><?= lang('machinery_and_equipment') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->machinery_and_equipment;?></td>
                                               
                                            </tr>  <tr>
                                                <th scope="row" ><?= lang('accumulated_machinery_and_equipment_depreciation') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->accumulated_machinery_and_equipment_depreciation;?></td>
                                               
                                            </tr>  <tr>
                                                <th scope="row" ><?= lang('furniture_and_fixtures') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->furniture_and_fixtures;?></td>
                                               
                                            </tr>  <tr>
                                                <th scope="row" ><?= lang('accumulated_furniture_and_fixtures_depreciation') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->accumulated_furniture_and_fixtures_depreciation;?></td>
                                               
                                            </tr>  <tr>
                                                <th scope="row" ><?= lang('other_fixed_assets') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->other_fixed_assets;?></td>
                                               
                                            </tr>
                                            <tr>
                                                <th scope="row" ><?= lang('NET FIXED ASSETS:') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$total_fix_assets = $sheet_info->long_term_investments+$sheet_info->land+$sheet_info->building+$sheet_info->accumulated_building_depreciation+$sheet_info->machinery_and_equipment+$sheet_info->accumulated_machinery_and_equipment_depreciation+$sheet_info->furniture_and_fixtures+$sheet_info->accumulated_furniture_and_fixtures_depreciation+$sheet_info->other_fixed_assets;?></td>
                                               
                                            </tr>
                                              <tr>
                                                <th scope="row" colspan="2"><u> <?= lang('other_assets_caps') ?></u></th>
                                                
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" ><?= lang('good_will') ?></th>
                                                <?php  ?>
                                                <td class="text-end"><?=$sheet_info->good_will;?></td>
                                               
                                            </tr>
                                            <tr>
                                                <th scope="row" ><?= lang('TOTAL ASSETS:') ?></th>
                                              
                                                <td class="text-end"><?=$total_assets = $total_fix_assets + $total_current_assets +$sheet_info->good_will ;?></td>
                                               
                                            </tr>
                                              <tr>
                                                <th scope="row" colspan="2"><u> <?= lang('current_liabilities_caps') ?></u></th>
                                                
                                               
                                            </tr>
                                            <tr>
                                                <th scope="row" ><?= lang('account_payable') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->account_payable ;?></td>
                                               
                                            </tr>
                                               <tr>
                                                <th scope="row" ><?= lang('accrued_wages') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->accrued_wages ;?></td>
                                               
                                            </tr>
                                               <tr>
                                                <th scope="row" ><?= lang('accrued_payroll_taxes') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->accrued_payroll_taxes ;?></td>
                                               
                                            </tr>
                                               <tr>
                                                <th scope="row" ><?= lang('accrued_employee_benefits') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->accrued_employee_benefits ;?></td>
                                               
                                            </tr>
                                               <tr>
                                                <th scope="row" ><?= lang('interest_payable') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->interest_payable ;?></td>
                                               
                                            </tr>
                                               <tr>
                                                <th scope="row" ><?= lang('short_term_notes') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->short_term_notes ;?></td>
                                               
                                            </tr>
                                               <tr>
                                                <th scope="row" ><?= lang('current_portion_of_long_term_debt') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->current_portion_of_long_term_debt ;?></td>
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" ><?= lang('TOTAL CURRENT LIABILITIES:') ?></th>
                                              
                                                <td class="text-end"><?=$total_current_liabilities = $sheet_info->account_payable+$sheet_info->accrued_wages+$sheet_info->accrued_payroll_taxes+$sheet_info->accrued_employee_benefits+$sheet_info->interest_payable+$sheet_info->short_term_notes+$sheet_info->current_portion_of_long_term_debt ;?></td>
                                               
                                            </tr>
                                              <tr>
                                                <th scope="row" colspan="2"><u> <?= lang('long_term_liability_caps') ?></u></th>
                                                
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" ><?= lang('mortgage') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->mortgage ;?></td>
                                               
                                            </tr> <tr>
                                                <th scope="row" ><?= lang('other_long_term_liabilities') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->other_long_term_liabilities ;?></td>
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" ><?= lang('TOTAL LONG-TERM LIABILITIES:') ?></th>
                                              
                                                <td class="text-end"><?=$total_long_term_liabilities = $sheet_info->mortgage+$sheet_info->accrued_wages+$sheet_info->other_long_term_liabilities ;?></td>
                                               
                                            </tr>
                                              <tr>
                                                <th scope="row" colspan="2"><u> <?= lang('shareholders_equity_caps') ?></u></th>
                                                
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" ><?= lang('common_stock') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->common_stock ;?></td>
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" ><?= lang('preferred_stock') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->preferred_stock ;?></td>
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" ><?= lang('additional_paid_in_capital') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->additional_paid_in_capital ;?></td>
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" ><?= lang('retained_earnings') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->retained_earnings ;?></td>
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" ><?= lang('treasury_stock') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->treasury_stock ;?></td>
                                               
                                            </tr>
                                            <tr>
                                                <th scope="row" ><?= lang('TOTAL EQUITY:') ?></th>
                                              
                                                <td class="text-end"><?=$total_equity = $sheet_info->retained_earnings + $sheet_info->treasury_stock
                                                + $sheet_info->common_stock+ $sheet_info->preferred_stock+ $sheet_info->additional_paid_in_capital  ;?></td>
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" colspan="2"><u> <?= lang('owners_equity_caps') ?></u></th>
                                                
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" ><?= lang('paid_in_capital') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->paid_in_capital ;?></td>
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" ><?= lang('net_income') ?></th>
                                              
                                                <td class="text-end"><?=$sheet_info->net_income ;?></td>
                                               
                                            </tr>
                                              <tr>
                                                <th scope="row" ><?= lang('TOTAL Owner\'s EQUITY') ?></th>
                                              
                                                <td class="text-end"><?=$total_owners_equity = $sheet_info->net_income+$sheet_info->paid_in_capital ;?></td>
                                               
                                            </tr>
                                               <tr>
                                                <th scope="row" ><?= lang('TOTAL LIABILITIES & EQUITY:') ?></th>
                                              
                                                <td class="text-end"><?=$total_liabilities =  $total_current_liabilities + $total_long_term_liabilities +$total_equity +$total_owners_equity;?></td>
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" colspan="2"><u> <?= lang("Please make sure that Total Assets equal Total Liabilities and Equity in your balance sheet. If the difference the two sides of the balance sheet is greater than 0, please review the values entered.") ?></u></th>
                                                
                                               
                                            </tr>
                                              <tr>
                                                <th scope="row" ><?= lang('TOTAL ASSETS') ?></th>
                                              
                                                <td class="text-end"><?=$total_assets ;?></td>
                                               
                                            </tr>
                                              <tr>
                                                <th scope="row" ><?= lang('TOTAL LIABILITIES & EQUITY:') ?></th>
                                              
                                                <td class="text-end"> - <?=$total_liabilities ;?></td>
                                               
                                            </tr>
                                             <tr>
                                                <th scope="row" ></th>
                                              
                                                <td class="text-end">  <?= $total_assets- $total_liabilities ;?></td>
                                               
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