<?php echo message_box('success'); ?>
<?php echo message_box('error');
?>
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

<!-- ************ Expense Report List start ************-->
<div class="row">
    <div class="col-lg-12">
        <div class="row mb-3">
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="pull-left float-left">
                            <form id="existing_customer" action="<?php echo base_url() ?>admin/payroll/provident_fund" method="post" data-parsley-validate="" novalidate="" >
                                <div class="col-sm-8 input-group position-relative mb-3">
                                    <div class="col-sm-8">
                                     <input type="text" required name="year" class="form-control years" value="<?php if (!empty($year)) {  echo $year; } ?>" data-format="yyyy">
                                    </div>
                                    <button type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Search" class="btn btn-primary mt-sm-10 mlt-15-10">  <i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="row" id="advance_salary">
                            <div class="show_print" style="width: 100%; border-bottom: 2px solid black;margin-bottom: 20px;">
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
                            </div><!--show when print start-->

                            <div class="mt nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <?php
                                foreach ($provident_fund_info as $key => $v_provident_fund):
                                    $month_name = date('F', strtotime($year . '-' . $key)); // get full name of month by date query
                                    ?>
                                    <a class="nav-link mb-2 <?php if ($current_month == $key) { echo 'active'; } ?>" aria-selected="<?php if ($current_month == $key) { echo 'true'; } else { echo 'false'; } ?>" data-bs-toggle="pill" role="tab" href="#<?php echo $month_name ?>" aria-controls="<?php echo $month_name ?>"><i class="fa fa-calendar fa-fw"></i> <?php echo $month_name; ?> </a>

                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9 col-md-9 col-sm-9">
                <div class="card">
                    <div class="card-body">
                        <!-- Tabs within a box -->
                        <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
                            <?php
                            foreach ($provident_fund_info as $key => $v_provident_fund):
                                $month_name = date('F', strtotime($year . '-' . $key)); // get full name of month by date query
                                ?>
                            <div id="<?php echo $month_name ?>" class="tab-pane <?php if ($current_month == $key) { echo 'active'; } ?>">
                                <div class="card-body">
                                    <div class="pull-right float-end">
                                        <span class="hidden-print"><?php echo btn_pdf('admin/payroll/provident_fund_pdf/' . $year . '/' . $key); ?></span>
                                    </div>
                                    <h4 class="card-title mb-4"><i class="fa fa-calendar"></i> <?php echo $month_name . ' ' . $year; ?></h4>
                                    <!-- Table -->
                                    <table class="table table-striped dt-responsive nowrap w-100 prov_fund_dtable">
                                        <thead>
                                            <tr>
                                                <?php super_admin_opt_th() ?>
                                                <th><?= lang('emp_id') ?></th>
                                                <th><?= lang('name') ?></th>
                                                <th><?= lang('payment_date') ?></th>
                                                <th><?= lang('amount') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total_amount = 0;
                                            $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                                            ?>
                                            <?php if (!empty($v_provident_fund)): foreach ($v_provident_fund as $provident_fund) : ?>
                                                <tr>
                                                    <?php super_admin_opt_td($provident_fund->companies_id) ?>
                                                    <td><?php echo $provident_fund->employment_id ?></td>
                                                    <td><?php echo $provident_fund->fullname ?></td>
                                                    <td><?= display_datetime($provident_fund->paid_date) ?></td>
                                                    <td><?php echo display_money($provident_fund->salary_payment_deduction_value, $curency->symbol);
                                                        $total_amount += $provident_fund->salary_payment_deduction_value;
                                                        ?></td>

                                                </tr>
                                                <?php $key++; endforeach; ?>
                                                <tr class="total_amount">
                                                    <td colspan="3" style="text-align: right;">
                                                        <strong><?= lang('total') . ' ' . lang('provident_fund') ?>
                                                            : </strong></td>
                                                    <td colspan="3" style="padding-left: 8px;"><strong><?php
                                                            echo display_money($total_amount, $curency->symbol);
                                                            ?></strong></td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
<script type="text/javascript">
    function advance_salary(advance_salary) {
        var printContents = document.getElementById(advance_salary).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
