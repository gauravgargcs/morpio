<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<style>
    .note-editor .note-editable {
        height: 150px;
    }

    a:hover {
        text-decoration: none;
    }
    .tbl-action{
        padding-bottom: 15px;
    }
   
    #DataTables_filter label , #DataTables_filter input{
        display: inline-block !important;
    }
     .action_1{
        display: inline-flex;
    }
</style>
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

$created = can_action('30', 'created');

$edited = can_action('30', 'edited');
$deleted = can_action('30', 'deleted');


?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="float-end pull-right">
                        <?php if (!empty($created) || !empty($edited)) { ?>
                        <a class="btn btn-sm btn-primary mr-1" href="<?= base_url() ?>admin/transactions/balance_sheet_add"><?= lang('Add New') ?></a>
                        <?php } ?>

                </div>
                <h4 class="card-title mb-4"><?php echo $title; ?></h4>

                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100" id="manage_balance_sheet_list_datatable">
                        <thead>
                        <tr>
                            <th class=""><?= lang('Sheet Name') ?></th>
                            <?php super_admin_opt_th() ?>
                            <th class=""><?= lang('Account') ?></th>
                           <!--  <th class=""><?= lang('Created at') ?></th> -->
                         
                          
                            <th class=""><?= lang('Action') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        if (!empty($all_balance_sheet)):foreach ($all_balance_sheet as $key => $b_sheet):
                                $account_info = $this->transactions_model->check_by(array('account_id' => $b_sheet->account_id), 'tbl_accounts');
                            ?>
                                <tr id="table-tasks-<?= $b_sheet->id ?>">
                                      
                                    <td class="col-sm-2">
                                        <a
                                           href="<?= base_url() ?>admin/transactions/view_balance_sheet/<?= $b_sheet->id ?>"><?php echo $b_sheet->title; ?></a>
                                    </td>
                                    <?php super_admin_opt_td($b_sheet->companies_id) ?>
                                    <td>  <?php if (!empty($account_info->account_name)) {
                                                echo $account_info->account_name;
                                            } else {
                                                echo '-';
                                            } ?></td>
                                    <!-- <td><?=$b_sheet->created_at;?></td> -->
                                   
                                
                                  

                                    <td>
                                        
                                        <?php if (!empty($deleted)) {
                                            echo ajax_anchor(base_url("admin/transactions/delete_balance_sheet/" . $b_sheet->id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "m-1", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table-tasks-" . $b_sheet->id));
                                             echo btn_edit('admin/transactions/balance_sheet_add/' . $b_sheet->id); 
                                        } ?>

                                          <a
                                           href="<?= base_url() ?>admin/transactions/view_balance_sheet/<?= $b_sheet->id ?>"><i class='btn btn-outline-success btn-sm fa fa-eye' style='height:26px;'></i></a>
                                       
                                    </td>
                                </tr>
                                <?php
                             endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>                            
            </div>
        </div>
    </div>
</div>

