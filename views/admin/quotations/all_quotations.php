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
                <h4 class="card-title"><?= lang('quotations') ?></h4>
                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100" id="contentTable">
                        <thead>
                            <tr>
                                <?php super_admin_opt_th() ?>
                                <th><?= lang('title') ?></th>
                                <th><?= lang('client') ?></th>
                                <th><?= lang('date') ?></th>
                                <th><?= lang('amount') ?></th>
                                <th><?= lang('status') ?></th>
                                <th><?= lang('generated_by') ?></th>
                                <th><?= lang('action') ?></th>
                            </tr>
                        </thead>
                        <?php /* ?><tbody>
                        <?php
                        if (!empty($all_quatations)) {

                            foreach ($all_quatations as $v_quatations) {
                                ?>
                                <tr>
                                    <?php
                                    $client_info = $this->quotations_model->check_by(array('client_id' => $v_quatations->client_id), 'tbl_client');

                                    $user_info = $this->quotations_model->check_by(array('user_id' => $v_quatations->user_id), 'tbl_users');
                                    if (!empty($user_info)) {
                                        if ($user_info->role_id == 1) {
                                            $user = '(admin)';
                                        } elseif ($user_info->role_id == 3) {
                                            $user = '(Staff)';
                                        } else {
                                            $user = '(client)';
                                        }
                                    } else {
                                        $user = ' ';
                                    }
                                    $currency = $this->quotations_model->client_currency_sambol($v_quatations->client_id);
                                    if (empty($currency)) {
                                        $currency = $this->quotations_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                    }

                                    ?>
                                    <?php super_admin_opt_td($v_quatations->companies_id) ?>
                                    <td>
                                        <a href="<?= base_url() ?>admin/quotations/quotations_details/<?= $v_quatations->quotations_id ?>"><?= $v_quatations->quotations_form_title; ?></a>
                                    </td>
                                    <td><?= $v_quatations->name; ?></td>
                                    <td><?= display_datetime($v_quatations->quotations_date) ?></td>
                                    <td>
                                        <?php
                                        if (!empty($v_quatations->quotations_amount)) {
                                            echo display_money($v_quatations->quotations_amount, $currency->symbol);
                                        }
                                        ?>
                                    </td>
                                    <td><?php
                                        if ($v_quatations->quotations_status == 'completed') {
                                            echo '<span class="label label-success">' . lang('completed') . '</span>';
                                        } else {
                                            echo '<span class="label label-danger">' . lang('pending') . '</span>';
                                        };
                                        ?></td>
                                    <td><?= (!empty($user_info->username) ? $user_info->username : '-') . ' ' . $user; ?> </td>
                                    <td>
                                        <?= btn_view('admin/quotations/quotations_details/' . $v_quatations->quotations_id) ?>
                                        <?= btn_delete('admin/quotations/index/delete_quotations/' . $v_quatations->quotations_id) ?>
                                    </td>
                                </tr>
                                <?php
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
             'url':'<?=base_url()?>admin/datatable/quotations'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_deposit_"+iDisplayIndex);
            return nRow;
          },
          'columns': [
		  <?php if (is_company_column_ag()) { ?>
             { data: 'companies_id' },
		  <?php } ?>
             { data: 'quotations_form_title' },
             { data: 'name' },
             { data: 'quotations_date' },
             { data: 'quotations_amount' },
             { data: 'quotations_status' },
             { data: 'generated_by' },
             { data: 'action' },
          ]
        });
     });
 </script>