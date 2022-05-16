<?= message_box('success') ?>
<?= message_box('error') ?>
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
<?php
$edited = can_action('150', 'edited');
$deleted = can_action('150', 'deleted');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4"><?= lang('all_payments') ?></h4>
                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100" id="contentTable">
                        <thead>
                        <tr>
                            <th><?= lang('payment_date') ?></th>
                            <th><?= lang('purchase') . ' ' . lang('date') ?></th>
                            <th><?= lang('purchase') ?></th>
                            <th><?= lang('supplier') ?></th>
                            <th><?= lang('amount') ?></th>
                            <th><?= lang('payment_method') ?></th>
                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <th class="hidden-print"><?= lang('action') ?></th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <?php /* ?><tbody>
                        <?php
                        if (!empty($all_purchase)) {
                            foreach ($all_purchase as $v_purchase) {
                                if (!empty($v_purchase)) {
                                    $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $v_purchase->purchase_id));
                                    $can_delete = $this->purchase_model->can_action('tbl_purchases', 'delete', array('purchase_id' => $v_purchase->purchase_id));

                                    $all_payment_info = $this->db->where('purchase_id', $v_purchase->purchase_id)->get('tbl_purchase_payments')->result();
                                    if (!empty($all_payment_info)):foreach ($all_payment_info as $v_payments_info):
                                        ?>
                                        <tr id="table_payments_<?= $v_payments_info->payments_id ?>">
                                            <?php
                                            $client_info = $this->purchase_model->check_by(array('supplier_id' => $v_purchase->supplier_id), 'tbl_suppliers');
                                            $currency = $this->purchase_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                            if (!empty($client_info)) {
                                                $c_name = $client_info->name;
                                            } else {
                                                $c_name = '-';
                                            }
                                            $payment_methods = $this->purchase_model->check_by(array('payment_methods_id' => $v_payments_info->payment_method), 'tbl_payment_methods');
                                            ?>
                                            <td>
                                                <a href="<?= base_url() ?>admin/purchase/payments_details/<?= $v_payments_info->payments_id ?>"> <?= display_datetime($v_payments_info->payment_date); ?></a>
                                            </td>
                                            <td><?= display_datetime($v_purchase->purchase_date) ?></td>
                                            <td><a class="text-info"
                                                   href="<?= base_url() ?>admin/purchase/purchase_details/<?= $v_payments_info->purchase_id ?>"><?= $v_purchase->reference_no; ?></a>
                                            </td>

                                            <td><?= $c_name; ?></td>
                                            <td><?= display_money($v_payments_info->amount, $currency->symbol); ?> </td>
                                            <td><?= !empty($payment_methods->method_name) ? $payment_methods->method_name : '-' ?></td>
                                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                <td>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                        <?= btn_edit('admin/purchase/all_payments/' . $v_payments_info->payments_id) ?>
                                                    <?php }
                                                    if (!empty($can_delete) && !empty($deleted)) {
                                                        ?>
                                                        <?php echo ajax_anchor(base_url("admin/purchase/delete_payment/" . $v_payments_info->payments_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_payments_" . $v_payments_info->payments_id)); ?>
                                                    <?php } ?>
                                                    <?= btn_view('admin/purchase/payments_details/' . $v_payments_info->payments_id) ?>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                        <a data-toggle="tooltip" data-placement="top"
                                                           href="<?= base_url() ?>admin/purchase/send_payment/<?= $v_payments_info->payments_id . '/' . $v_payments_info->amount ?>"
                                                           title="<?= lang('send_email') ?>"
                                                           class="btn btn-xs btn-success">
                                                            <i class="fa fa-envelope"></i> </a>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <?php
                                    endforeach;
                                    endif;
                                }

                            }
                        }
                        ?>
                        </tbody><?php */ ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script -->
 <script type="text/javascript">
     $(document).ready(function(){
        $('#contentTable').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
             'url':'<?=base_url()?>admin/datatable/purchase_all_payments'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_payments_"+iDisplayIndex);
            return nRow;
          },
          'columns': [         
             { data: 'payment_date' },
             { data: 'purchase_date' },
             { data: 'purchase' },
             { data: 'supplier' },
             { data: 'amount' },
             { data: 'payment_method' },
             { data: 'action' },
          ]
        });
     });
 </script>