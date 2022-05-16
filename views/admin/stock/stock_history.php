<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
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
                <h4 class="card-title mb-4"><?= lang('select') . ' ' . lang('stock_category') ?></h4>

                <?php echo form_open(base_url('admin/stock/stock_history/'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                <div class="row mb-3">
                    <label for="field-1"
                           class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('select') . ' ' . lang('stock_category') ?> <span
                                class="required">*</span></label>

                    <div class="col-lg-5 col-md-5 col-sm-5">
                        <select name="stock_sub_category_id" class="form-control select_box">
                            <option value=""><?= lang('select') . ' ' . lang('stock_category') ?></option>
                            <?php if (!empty($all_category_info)): foreach ($all_category_info as $cate_name => $v_category_info) : ?>
                                <?php if (!empty($v_category_info)):
                                    if (!empty($cate_name)) {
                                        $cate_name = $cate_name;
                                    } else {
                                        $cate_name = lang('undefined_category');
                                    }
                                    ?>
                                    <optgroup label="<?php echo $cate_name; ?>">
                                        <?php foreach ($v_category_info as $sub_category) :
                                            if (!empty($sub_category->stock_sub_category)) {
                                                ?>
                                                <option value="<?php echo $sub_category->stock_sub_category_id; ?>"
                                                    <?php
                                                    if (!empty($sub_category_id)) {
                                                        echo $sub_category->stock_sub_category_id == $sub_category_id ? 'selected' : '';
                                                    }
                                                    ?>><?php echo $sub_category->stock_sub_category ?></option>
                                                <?php
                                            }
                                        endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <button type="submit" id="sbtn" value="1" name="flag"
                                class="btn btn-primary"><?= lang('go') ?></button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <br/>
        <?php if (!empty($flag)): ?>
            <?php if (!empty($item_history_info)): foreach ($item_history_info as $sub_category => $v_item_history) : ?>
                <?php if (!empty($v_item_history)): ?>
                    <div class="row">
                        <div class="col-lg-12" data-offset="0">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><?php echo $sub_category; ?></h4>
                                    
                                    <?php foreach ($v_item_history as $item_name => $item_history) : ?>
                                    <div class="card border">
                                        <div class="card-body">
                                            <h4 class="card-title mb-4" style="border-bottom: 1px solid #a0a0a0;padding-bottom:5px;">
                                                <?php echo $item_name ?>
                                            </h4>
                                  
                                            <table class="table table-striped dt-responsive nowrap w-100 list_stock_history_datatable">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-1"><?= lang('sl') ?></th>
                                                        <?php super_admin_opt_th() ?>
                                                        <th><?= lang('item_name') ?></th>
                                                        <th><?= lang('inventory') ?></th>
                                                        <th><?= lang('buying_date') ?></th>
                                                        <th class="col-lg-2 col-md-2 col-sm-2"><?= lang('action') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($item_history as $key => $v_item) : ?>

                                                    <tr id="table_history_<?= $v_item->item_history_id ?>">
                                                        <td><?php echo $key + 1 ?></td>
                                                        <?php super_admin_opt_td($v_item->companies_id) ?>
                                                        <td><?php echo $v_item->item_name ?></td>
                                                        <td><?php echo $v_item->inventory ?></td>
                                                        <td><?= display_datetime($v_item->purchase_date); ?></td>
                                                        <td>
                                                            <?php echo btn_edit('admin/stock/stock_list/' . $v_item->stock_id); ?>
                                                            <?php echo ajax_anchor(base_url("admin/stock/delete_stock_history/" . $v_item->stock_id . '/' . $v_item->item_history_id . '/' . $sub_category_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_history_" . $v_item->item_history_id)); ?>
                                                        </td>

                                                    </tr>
                                                    <?php
                                                    endforeach;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach;   else : ?>
                <div class="row">
                    <div class="col-lg-12" data-offset="0">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center text-sm font-size-18"><?= lang('nothing_to_display') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
    function assign_stock(assign_stock) {
        var printContents = document.getElementById(assign_stock).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>


