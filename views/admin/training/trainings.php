<?php echo message_box('success'); ?>
<?php echo message_box('error');
$created = can_action('101', 'created');
$edited = can_action('101', 'edited');
$deleted = can_action('101', 'deleted');
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
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($created)) { ?>
                <div class="pull-right hidden-print float-end">
                    <a href="<?= base_url() ?>admin/training/new_training" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal_lg"> <i class="fa fa-plus "></i> <?= ' ' . lang('new') . ' ' . lang('training') ?></a>
                </div>
                <?php } ?>
                <h4 class="card-title mb-3"> <?= lang('training') . ' ' . lang('list') ?></h4>
                <table class="table table-striped dt-responsive nowrap w-100" id="contentTable">
                    <thead>
                        <tr>
                            <?php super_admin_opt_th() ?>
                            <th><?= lang('name') ?></th>
                            <th><?= lang('course_training') ?></th>
                            <th><?= lang('vendor') ?></th>
                            <th><?= lang('start_date') ?></th>
                            <th><?= lang('finish_date') ?></th>
                            <?php /* $show_custom_fields = custom_form_table(15, null);
                            if (!empty($show_custom_fields)) {
                                foreach ($show_custom_fields as $c_label => $v_fields) {
                                    if (!empty($c_label)) {
                                        ?>
                                        <th><?= $c_label ?> </th>
                                    <?php }
                                }
                            } */
                            ?>
                            <th><?= lang('status') ?></th>
                            <th><?= lang('action') ?></th>
                        </tr>
                    </thead>
                    <?php /* ?><tbody>
                        <?php if (!empty($all_training_info)):foreach ($all_training_info as $key => $v_training):
                            $profile_info = $this->db->where('user_id', $v_training->user_id)->get('tbl_account_details')->row();
                            $can_edit = $this->training_model->can_action('tbl_training', 'edit', array('training_id' => $v_training->training_id));
                            $can_delete = $this->training_model->can_action('tbl_training', 'delete', array('training_id' => $v_training->training_id));
                            if (!empty($profile_info->fullname)) {
                                $name = $profile_info->fullname . ' (' . $profile_info->employment_id . ')';
                            } else {
                                $name = '-';
                            }
                            ?>
                            <tr>
                                <?php super_admin_opt_td($v_training->companies_id) ?>
                                <td><?php echo $name; ?></td>
                                <td><?php echo $v_training->training_name; ?></td>
                                <td><?php echo $v_training->vendor_name; ?></td>
                                <td><?= display_datetime($v_training->start_date) ?></td>
                                <td><?= display_datetime($v_training->finish_date) ?></td>
                                <?php $show_custom_fields = custom_form_table(15, $v_training->training_id);
                                if (!empty($show_custom_fields)) {
                                    foreach ($show_custom_fields as $c_label => $v_fields) {
                                        if (!empty($c_label)) {
                                            ?>
                                            <td><?= $v_fields ?> </td>
                                        <?php }
                                    }
                                }
                                ?>
                                <td><?php
                                    if ($v_training->status == '0') {
                                        echo '<span class="badge badge-soft-warning">' . lang('pending') . ' </span>';
                                    } elseif ($v_training->status == '1') {
                                        echo '<span class="badge badge-soft-info">' . lang('started') . '</span>';
                                    } elseif ($v_training->status == '2') {
                                        echo '<span class="badge badge-soft-success"> ' . lang('completed') . ' </span>';
                                    } else {
                                        echo '<span class="badge badge-soft-danger"> ' . lang('terminated ') . '</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('edit') ?>">
                                    <a href="<?= base_url() ?>admin/training/new_training/<?= $v_training->training_id ?>"
                                       class="btn btn-outline-primary btn-sm"
                                       data-bs-toggle="modal"
                                       data-bs-placement="top" data-bs-target="#myModal_lg">
                                        <i class="fa fa-pencil-square-o"></i> </a>
                                        </span>
                                    <?php }
                                    if (!empty($can_delete) && !empty($deleted)) { ?>
                                        <?php echo btn_delete('admin/training/delete_training/' . $v_training->training_id) ?>
                                    <?php } ?>
                                    <?php echo btn_view_modal('admin/training/training_details/' . $v_training->training_id) ?>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody><?php */ ?>
                </table>
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
             'url':'<?=base_url()?>admin/datatable/training'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_deposit_"+iDisplayIndex);
            return nRow;
          },
          'columns': [
		  <?php if (is_company_column_ag()) { ?>
             { data: 'companies_id' },
		  <?php } ?>
             { data: 'name' },
             { data: 'training_name' },
             { data: 'vendor_name' },
             { data: 'start_date' },
             { data: 'finish_date' },
             // { data: 'label' },
             { data: 'status' },
             { data: 'action' },
          ]
        });
     });
 </script>