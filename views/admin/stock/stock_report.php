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
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form role="form" id="sales_report" action="<?php echo base_url() ?>admin/stock/report" method="post">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="row mb-3">
                                <label class="col-form-label"><?= lang('start_date') ?><span class="required"> *</span></label>
                                <div class="input-group">
                                    <input type="text" value="<?php if (!empty($date['start_date'])) {
                                        echo date('d-m-Y H:i', strtotime($date['start_date']));
                                    } ?>" class="form-control datepicker" name="start_date">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="row mb-3">
                                <label class="col-form-label"><?= lang('end_date') ?><span class="required"> *</span></label>
                                <div class="input-group">
                                    <input type="text" value="<?php if (!empty($date['end_date'])) {
                                        echo date('d-m-Y H:i', strtotime($date['end_date']));
                                    } ?>" class="form-control datepicker" name="end_date">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
            
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="mt-4">
                                <button type="submit" name="flag" value="1" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('search') ?>" class="btn btn-primary btn-sm mt"><i  class="fa fa-search fa-2x"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .custom-bg {
        background: #f0f0f0;
    }
</style>

<?php
if (!empty($assign_stock) && !empty($purchase_stock)) {
    $col = 'col-md-12';
} else {
    $col = 'col-md-12';
}
if (!empty($assign_stock) || !empty($purchase_stock)): ?>
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
            <div style="background: #E0E5E8;padding: 5px;">
                <!-- Default panel contents -->
                <div style="font-size: 15px;padding: 5px 0px 0px 0px"><?= lang('stock') . ' ' . lang('report_from') ?>
                    :<strong>
                    <?= display_datetime($date['start_date']); ?></strong>
                </div>
                <div style="font-size: 15px;padding: 0px 0px 0px 0px">
                    <?= lang('stock') . ' ' . lang('report_to') ?>
                    : <strong><?= display_datetime($date['end_date']); ?></strong>
                </div>
            </div>
            <br/>
        </div>
        
        <div class="row">
            <div class="card">
                <!-- Default panel contents -->
                <div class="card-body">
                    <div class="pull-right hidden-print">
                        <span><?php echo btn_pdf('admin/stock/assign_report_pdf/' . $date['start_date'] . '/' . $date['end_date']); ?></span>
                        <a onclick="print_sales_report('printReport')" class="btn btn-xs btn-danger"
                           data-bs-toggle="tooltip"
                           data-bs-placement="top" title="<?= lang('print') ?>"><?= lang('print') ?></a>
                    </div>

                    <h4 class="hidden-print card-title mb-4"><?= lang('report_list') ?></h4>
                     
                    <?php if (!empty($aassign_stock)) { ?>
                    <div class="<?= $col ?>">
                        <div class="custom-bg p text-center"><strong><?= lang('assign_stock_list') ?></strong></div>
                        <table class="table table-bordered table-hover">
                            <?php foreach ($assign_stock as $item_name => $v_assign_report) :
                            ?>
                            <thead>
                            <tr class="color-black heading_print" style="background: #e7f0f5">
                                <th colspan="3"><strong><?php echo $item_name; ?></strong></th>
                            </tr>

                            <tr>
                                <th><?= lang('assigned_user') ?></th>
                                <th><?= lang('assign_date') ?></th>
                                <th><?= lang('assign_quantity') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $total_assign_inventory = 0;
                            if (!empty($v_assign_report)): foreach ($v_assign_report as $v_report) :
                                ?>

                                <tr class="custom-tr custom-font-print">
                                    <td class="vertical-td"><?php echo $v_report->fullname ?></td>
                                    <td><?= display_datetime($v_report->assign_date); ?></td>
                                    <td class="vertical-td"><?php echo $v_report->assign_inventory; ?> </td>
                                    <?php
                                    $total_assign_inventory += $v_report->assign_inventory;
                                    ?>

                                </tr>
                            <?php endforeach; ?>
                                <tr class="custom-bg">
                                    <th style="text-align: right;" colspan="2">
                                        <strong><?= lang('total') ?> <?php echo $v_report->item_name ?>
                                            :</strong>
                                    </th>
                                    <td align=""><?php
                                        echo $total_assign_inventory;
                                        ?>
                                        <span class="pull-right"><?= lang('available_stock') ?>
                                            :<strong> <?php echo $v_report->total_stock; ?></strong></span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                    <?php if (!empty($purchase_stock)) { ?>
                    <div class="<?= $col ?>">
                        <div class="custom-bg p text-center"><strong><?= lang('stock_list') ?></strong></div>
                        <table class="table table-bordered table-hover">
                            <?php foreach ($purchase_stock as $item_name => $v_purchase_stoc) :
                            ?>
                            <thead>
                            <tr class="color-black heading_print" style="background: #e7f0f5">
                                <th colspan="3"><strong><?php echo $item_name; ?></strong></th>
                            </tr>

                            <tr>
                                <th><?= lang('item_name') ?></th>
                                <th><?= lang('inventory') ?></th>
                                <th><?= lang('buying_date') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $total_inventory = 0;
                            if (!empty($v_purchase_stoc)): foreach ($v_purchase_stoc as $v_stock) :
                                ?>

                                <tr class="custom-tr custom-font-print">
                                    <td class="vertical-td"><?php echo $v_stock->item_name ?></td>
                                    <td class="vertical-td"><?php echo $v_stock->inventory; ?> </td>
                                    <td><?= display_datetime($v_stock->purchase_date); ?></td>
                                </tr>
                                <?php
                                $total_inventory += $v_stock->inventory;
                            endforeach; ?>
                                <tr class="custom-bg">
                                    <th style="text-align: right;">
                                        <strong><?= lang('total') . ' ' . lang('assigned') . ': ' ?></strong>
                                    </th>
                                    <td><?= $total_inventory - $v_stock->total_stock ?></td>
                                    <td align="">
                                <span class="pull-right"><?= lang('available_stock') ?>
                                    :<strong> <?php echo $v_stock->total_stock; ?></strong></span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<script type="text/javascript">

    function print_sales_report(printReport) {
        var printContents = document.getElementById(printReport).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

</script>
