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
<?php if (empty($switch)) { ?>
<div class="row">
    <div class="col-lg-12">
        <div class="row mb-3">
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="pull-left float-left">
                            <?php echo form_open(base_url('admin/payroll/advance_salary'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                                <div class="col-sm-8 input-group position-relative mb-3">
                                    <div class="col-sm-8">
                                     <input type="text" required name="year" class="form-control years" value="<?php if (!empty($year)) {  echo $year; } ?>" data-format="yyyy">
                                    </div>
                                    <button type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Search" class="btn btn-primary mt-sm-10 mlt-15-10">  <i class="fa fa-search"></i></button>
                                </div>
                            <?php echo form_close(); ?>
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
                            </div><!--  show when print start-->
                            
                            <div class="mt nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <?php
                                foreach ($advance_salary_info as $key => $v_advance_salary):
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
                        foreach ($advance_salary_info as $key => $v_advance_salary):
                            $month_name = date('F', strtotime($year . '-' . $key)); // get full name of month by date query
                            ?>
                        <div id="<?php echo $month_name ?>" class="tab-pane <?php if ($current_month == $key) { echo 'active'; } ?>">
                            <div class="card-body">
                                <div class="pull-right float-end">
                                    <span class="hidden-print"><?php echo btn_pdf('admin/payroll/advance_salary_pdf/' . $year . '/' . $key); ?></span>
                                    <a href="<?= base_url() ?>admin/payroll/add_advance_salary" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                      data-bs-placement="top" data-bs-target="#myModal">
                                    <span class="fa fa-plus ">
                                        <?php if ($this->session->userdata('user_type') == 1) {
                                            $request = lang('new');
                                        } else {
                                            $request = lang('apply');
                                        } ?>
                                        <?= $request . ' ' . lang('advance_salary') ?>
                                    </span></a>
                          
                                    <a href="<?= base_url() ?>admin/payroll/advance_salary/true" style="margin-right: 21px"
                                       class="btn btn-sm btn-info pull-right float-none"
                                       data-bs-toggle="tooltip"
                                      data-bs-placement="top" title="<?= lang('switch_to_details') ?>">
                                        <i class="fa fa-undo"> </i><?= ' ' . lang('switch') ?>
                                    </a>
                                </div>
                                <h4 class="card-title mb-4"><i class="fa fa-calendar"></i> <?php echo $month_name . ' ' . $year; ?></h4>
                                <!-- Table -->
                                <table class="table table-striped dt-responsive nowrap w-100 adv_slry_dtable"> 
                                    <thead>
                                    <tr>
                                        <?php super_admin_opt_th() ?>
                                        <th><?= lang('emp_id') ?></th>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('amount') ?></th>
                                        <th><?= lang('deduct_month') ?></th>
                                        <th><?= lang('request_date') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <?php if ($this->session->userdata('user_type') == 1) { ?>
                                            <th><?= lang('action') ?></th>
                                        <?php } ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                                        $total_amount = 0;
                                        if (!empty($v_advance_salary)): foreach ($v_advance_salary as $advance_salary) : ?>
                                            <tr>
                                                <?php super_admin_opt_td($advance_salary->companies_id) ?>
                                                <td><?php echo $advance_salary->employment_id ?></td>
                                                <td><?php echo $advance_salary->fullname ?></td>
                                                <td><?php echo display_money($advance_salary->advance_amount, $curency->symbol);
                                                    $total_amount += $advance_salary->advance_amount;
                                                    ?></td>
                                                <td><?php echo date('Y M', strtotime($advance_salary->deduct_month)) ?></td>
                                                <td><?= display_datetime($advance_salary->request_date) ?></td>

                                                <td><?php
                                                    if ($advance_salary->status == '0') {
                                                        echo '<span class="badge badge-soft-warning">' . lang('pending') . '</span>';
                                                    } elseif ($advance_salary->status == '1') {
                                                        echo '<span class="badge badge-soft-success"> ' . lang('accepted') . '</span>';
                                                    } elseif ($advance_salary->status == '2') {
                                                        echo '<span class="badge badge-soft-danger">' . lang('rejected') . '</span>';
                                                    } else {
                                                        echo '<span class="badge badge-soft-info">' . lang('paid') . '</span>';
                                                    }
                                                    ?></td>
                                                <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                    <td>
                                                        <a href="<?= base_url() ?>admin/payroll/advance_salary_details/<?= $advance_salary->advance_salary_id ?>"
                                                           class="btn btn-info btn-sm" title="<?= lang('view') ?>"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#myModal"><span
                                                                class="fa fa-list-alt"></span></a>
                                                    </td>
                                                <?php } ?>

                                            </tr>
                                            <?php
                                            $key++;
                                        endforeach;
                                        ?>
                                        <tr class="total_amount">
                                            <td class="hidden-print"></td>
                                            <td colspan="2" style="text-align: right;">
                                                <strong><?= lang('total') . ' ' . lang('advance_salary') ?>
                                                    : </strong></td>
                                            <td colspan="3" style="padding-left: 8px;">
                                                <strong><?php echo display_money($total_amount, $curency->symbol); ?></strong>
                                            </td>
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
<?php } ?>

<?php if (!empty($switch)) { ?>
<div class="row">
    <div class="col-lg-12">
        <div class="row mb-3">
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <!-- Tabs within a box -->
                        <div class="pull-left float-end">
                            <a href="<?= base_url() ?>admin/payroll/add_advance_salary/true" class="btn btn-sm btn-danger mr" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal">
                                <span class="fa fa-plus">
                                    <?php if ($this->session->userdata('user_type') == 1) {
                                        $request = lang('new');
                                    } else {
                                        $request = lang('apply');
                                    } ?>
                                    <?= $request . ' ' . lang('advance_salary') ?>
                                </span> 
                            </a>
                            <a href="<?= base_url() ?>admin/payroll/advance_salary" style="margin-right: 16px" class="btn btn-sm btn-primary pull-right" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('switch_to_previous') ?>"> 
                               <i class="fa fa-undo"> </i> <?= ' ' . lang('switch') ?>
                            </a>
                        </div>
                        <div class="row">
                           <div class="mt nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link mb-2 <?= $active == 1 ? 'active' : '' ?>" data-bs-toggle="pill" role="tab" href="#leave_report"> <?= lang('advance_salary_report') ?> </a>
                                <a class="nav-link mb-2 <?= $active == 2 ? 'active' : '' ?>" data-bs-toggle="pill" role="tab" href="#my_leave"> <?= lang('advance_salary') ?> </a>
                                <?php if ($this->session->userdata('user_type') == 1) { ?>
                                <a class="nav-link mb-2 <?= $active == 2 ? 'active' : '' ?>" data-bs-toggle="pill" role="tab" href="#all_leave"> <?= lang('all_advance_salary') ?> </a>
                                <?php } ?>
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
                            <div id="my_leave" class="tab-pane <?= $active == 2 ? 'active' : '' ?>">
                                <div class="card-body">
                                    <h3 class="card-title mb-4"> <?= lang('advance_salary') ?> </h3>
                                    <!-- Table -->
                                    <table class="table table-striped dt-responsive nowrap w-100" id="my_leave_dtable" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <?php super_admin_opt_th() ?>
                                                <th><?= lang('emp_id') ?></th>
                                                <th><?= lang('name') ?></th>
                                                <th><?= lang('amount') ?></th>
                                                <th><?= lang('request_date') ?></th>
                                                <th><?= lang('deduct_month') ?></th>
                                                <th><?= lang('status') ?></th>
                                                <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                    <th><?= lang('action') ?></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                                            $my_total = 0;
                                            $my_advance_salary = $this->payroll_model->my_advance_salary_info();
                                            if (!empty($my_advance_salary)) {
                                                foreach ($my_advance_salary as $my_salary) { ?>
                                                    <tr>
                                                        <?php super_admin_opt_td($my_salary->companies_id) ?>
                                                        <td><?php echo $my_salary->employment_id ?></td>
                                                        <td><?php echo $my_salary->fullname ?></td>
                                                        <td><?php echo display_money($my_salary->advance_amount, $curency->symbol);
                                                            $my_total += $my_salary->advance_amount;
                                                            ?></td>
                                                        <td><?= display_datetime($my_salary->request_date) ?></td>
                                                        <td><?php echo date('Y M', strtotime($my_salary->deduct_month)) ?></td>
                                                        <td><?php
                                                            if ($my_salary->status == '0') {
                                                                echo '<span class="badge badge-soft-warning">' . lang('pending') . '</span>';
                                                            } elseif ($my_salary->status == '1') {
                                                                echo '<span class="badge badge-soft-success"> ' . lang('accepted') . '</span>';
                                                            } elseif ($my_salary->status == '2') {
                                                                echo '<span class="badge badge-soft-danger">' . lang('rejected') . '</span>';
                                                            } else {
                                                                echo '<span class="badge badge-soft-info">' . lang('paid') . '</span>';
                                                            }
                                                            ?></td>
                                                        <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                            <td>
                                                                <a href="<?= base_url() ?>admin/payroll/advance_salary_details/<?= $my_salary->advance_salary_id ?>" class="btn btn-info btn-sm" title="<?= lang('view') ?>" data-bs-toggle="modal" data-bs-target="#myModal"><span class="fa fa-list-alt"></span></a>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                    <?php
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <strong><?= lang('total').' '.lang('advance_salary') ?>: <span class="badge badge-soft-info"><?php echo display_money($my_total, $curency->symbol); ?></span>
                                    </strong>
                                </div>
                            </div>
                            <?php if ($this->session->userdata('user_type') == 1) { ?>
                            <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="all_leave">
                                <div class="card-body">
                                    <h3 class="card-title mb-4"><?= lang('all_advance_salary') ?></h3>

                                    <div class="task_details">
                                        <!-- Table -->
                                        <table class="table table-striped dt-responsive nowrap w-100" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <?php super_admin_opt_th() ?>
                                                    <th><?= lang('emp_id') ?></th>
                                                    <th><?= lang('name') ?></th>
                                                    <th><?= lang('amount') ?></th>
                                                    <th><?= lang('request_date') ?></th>
                                                    <th><?= lang('deduct_month') ?></th>
                                                    <th><?= lang('status') ?></th>
                                                    <th><?= lang('action') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                                                $all_total = 0;
                                                $all_advance_salary = $this->payroll_model->my_advance_salary_info(true);
                                                if (!empty($all_advance_salary)) {
                                                    foreach ($all_advance_salary as $all_salary) { ?>
                                                        <tr>
                                                            <?php super_admin_opt_td($all_salary->companies_id) ?>
                                                            <td><?php echo $all_salary->employment_id ?></td>
                                                            <td><?php echo $all_salary->fullname ?></td>
                                                            <td><?php echo display_money($all_salary->advance_amount, $curency->symbol);
                                                                $all_total += $all_salary->advance_amount;
                                                                ?></td>
                                                            <td><?= display_datetime($all_salary->request_date) ?></td>
                                                            <td><?php echo date('Y M', strtotime($all_salary->deduct_month)) ?></td>
                                                            <td><?php
                                                                if ($all_salary->status == '0') {
                                                                    echo '<span class="badge badge-soft-warning">' . lang('pending') . '</span>';
                                                                } elseif ($all_salary->status == '1') {
                                                                    echo '<span class="badge badge-soft-success"> ' . lang('accepted') . '</span>';
                                                                } elseif ($all_salary->status == '2') {
                                                                    echo '<span class="badge badge-soft-danger">' . lang('rejected') . '</span>';
                                                                } else {
                                                                    echo '<span class="badge badge-soft-info">' . lang('paid') . '</span>';
                                                                }
                                                                ?></td>
                                                            <td>
                                                                <a href="<?= base_url() ?>admin/payroll/advance_salary_details/<?= $all_salary->advance_salary_id ?>" class="btn btn-info btn-sm" title="<?= lang('view') ?>" data-bs-toggle="modal" data-bs-target="#myModal"><span class="fa fa-list-alt"></span></a>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <strong>
                                        <?= lang('total') . ' ' . lang('advance_salary') ?>: <span class="badge badge-soft-info"><?php echo display_money($all_total, $curency->symbol); ?></span>
                                    </strong>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="leave_report">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title mb-4"><?= lang('advance_salary_report') ?></h3>

                                            <div class="col-lg-6 col-md-6 mb-10">
                                                <form class="row" action="<?php echo base_url() ?>admin/payroll/advance_salary" method="post">
                                                    <div class="col-lg-6 col-md-6 text-right text-left mb-10">
                                                        <label for="field-1" class="control-label holiday-vertical"><strong><?= lang('year') ?>
                                                            :</strong></label>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="" style="margin-top: -7px">
                                                            <input type="text" name="year" class="form-control years" value="<?php
                                                            if (!empty($year)) {
                                                                echo $year;
                                                            }
                                                            ?>" data-format="yyyy">
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    
                                                </form>
                                            </div>
                                            <div class="col-lg-2 col-md-2">
                                                <button style="margin-top: -5px" type="submit" data-bs-toggle="tooltip"
                                                       data-bs-placement="top" title="Search"
                                                        class="btn btn-purple ">
                                                    <i class="fa fa-search"></i></button>
                                            </div>
                                            
                                            
                                        </h3>
                                    </div>
                                    <div class="panel-body">

                                        <?php if ($this->session->userdata('user_type') == 1) { ?>
                                            <div id="">
                                                <div class="row mb panel-title pl-lg pb-sm"
                                                     style="border-bottom: 1px solid #a0a6ad"><?= lang('all') . ' ' . lang('advance_salary_report') ?>

                                                </div>
                                                <div id="morris_line_all"></div>
                                            </div>
                                        <?php } ?>
                                        <div class="mt-lg ">
                                            <div class="row mb panel-title pl-lg pb-sm"
                                                 style="border-bottom: 1px solid #a0a6ad"><?= lang('my_report') ?></div>
                                            <div id="morris_line_my"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            
        </div>
    </div>
    <?php if ($this->session->userdata('user_type') == 1) { ?>
    <script type="text/javascript">
        $(function () {
            if (typeof Morris === 'undefined') return;
            var chartdata = [
                <?php foreach ($advance_salary_info as $key => $v_advance_salary){
                $month_name = date('F', strtotime($year . '-' . $key)); // get full name of month by date query
                $total_amount = 0;
                foreach ($v_advance_salary as $advance_salary) {
                    $total_amount += $advance_salary->advance_amount;
                }
                ?>
                {
                    y: "<?= $month_name ?>",
                    all_report: <?= $total_amount?>,
                },
                <?php }?>
            ];
            // Line Chart
            // -----------------------------------
            new Morris.Line({
                element: 'morris_line_all',
                data: chartdata,
                xkey: 'y',
                ykeys: ["all_report"],
                labels: ["<?= lang('advance_salary')?>"],
                lineColors: ["#7266ba"],
                parseTime: false,
                resize: true
            });
        });
    </script>
    <?php } ?>
    <script type="text/javascript">
        $(function () {
            if (typeof Morris === 'undefined') return;
            var my_chartdata = [
                <?php foreach ($advance_salary_info as $mkey => $my_advance_salary){
                $my_month_name = date('F', strtotime($year . '-' . $mkey)); // get full name of month by date query
                $my_total = 0;
                foreach ($my_advance_salary as $my_advance_salary) {
                    if ($my_advance_salary->user_id == $this->session->userdata('user_id')) {
                        $my_total += $my_advance_salary->advance_amount;
                    }
                }
                ?>
                {
                    y: "<?= $my_month_name ?>",
                    my_report: <?= $my_total?>,
                },
                <?php }?>
            ];
            // Line Chart
            // -----------------------------------
            new Morris.Line({
                element: 'morris_line_my',
                data: my_chartdata,
                xkey: 'y',
                ykeys: ["my_report"],
                labels: ["<?= lang('my_advance_salary')?>"],
                lineColors: ["#23b7e5"],
                parseTime: false,
                resize: true
            });
        });
    </script>
<?php } ?>