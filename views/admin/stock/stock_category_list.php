<?php echo message_box('success'); ?>
<?php echo message_box('error');?>
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
$created = can_action('76', 'created');
$edited = can_action('76', 'edited');
$deleted = can_action('76', 'deleted');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 1 ? 'active' : '' ?>" href="<?=base_url('admin/stock/stock_category');?>"><?= lang('all') . ' ' . lang('stock_category') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 2 ? 'active' : '' ?>" href="#assign_task" data-bs-toggle="tab"><?= lang('new') . ' ' . lang('stock_category') ?></a>
                    </li>
                    <?php } ?>

                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <?php if (!empty($all_stock_category_info)): foreach ($all_stock_category_info as $akey => $v_stock_category_info) : ?>
                        <?php if (!empty($v_stock_category_info)):
                            if (!empty($stock_category_info[$akey]->stock_category)) {
                                $category = $stock_category_info[$akey]->stock_category;
                            } else {
                                $category = lang('undefined_category');
                            }
                            ?>
                       
                        <h4 class="card-title mb-4"><?php echo $category ?></h4>
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100 list_stock_category_datatable">
                                <thead>
                                    <tr>
                                        <td colspan="2"><span class="text-sm"><?php super_admin_invoice($stock_category_info[$akey]->companies_id,3); ?></span></td>
                                        <td> 
                                            <div class="pull-right float-end">
                                                <?php if (!empty($edited)) { ?>
                                                    <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                          title="<?= lang('edit') ?>">
                                                    <a href="<?= base_url() ?>admin/stock/edit_stock_category/<?= $stock_category_info[$akey]->stock_category_id ?>"
                                                       class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                       data-bs-placement="top" data-bs-target="#myModal"><span
                                                                class="fa fa-pencil-square-o"></span></a>
                                                        </span>
                                                <?php }
                                                if (!empty($deleted)) { ?>
                                                    <a data-original-title="<?= lang('delete') ?>"
                                                       href="<?php echo base_url() ?>admin/stock/delete_stock_category/<?php echo $stock_category_info[$akey]->stock_category_id; ?>"
                                                       class="btn btn-danger btn-sm" title=""
                                                       data-bs-toggle="tooltip"
                                                       data-bs-placement="top"
                                                       onclick="return confirm('<?= lang("alert_delete_category") ?>');"><i
                                                                class="fa fa-trash-o"></i></a>
                                                <?php }
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-bold col-lg-1 col-md-1 col-sm-1">#</th>
                                        <th class="text-bold"><?= lang('stock_sub_category') ?></th>
                                        <th class="text-bold col-lg-2 col-md-2 col-sm-2"><?= lang('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($v_stock_category_info as $key => $v_stock_category) :
                                    if (!empty($v_stock_category->stock_sub_category)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $key + 1 ?></td>
                                            <td>
                                                <a data-bs-toggle="modal" data-bs-target="#myModal"  href="<?= base_url('admin/stock/items_details_stock_category/' . $v_stock_category->stock_sub_category_id) ?>"> <?php echo $v_stock_category->stock_sub_category ?></a>
                                            </td>
                                            <td>
                                                <?php if (!empty($edited)) { ?>
                                                    <?php echo btn_edit('admin/stock/stock_category/' . $stock_category_info[$akey]->stock_category_id . '/' . $v_stock_category->stock_sub_category_id); ?>
                                                <?php }
                                                if (!empty($deleted)) { ?>
                                                    <?php echo btn_delete('admin/stock/delete_stock_sub_category/' . $v_stock_category->stock_sub_category_id); ?>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }  endforeach;
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <?php endif;
                          endforeach; ?>
                        <?php endif; ?>
                    </div>

                       
                    <?php if (!empty($created) || !empty($edited)) {
                        if (!empty($category_info)) {
                            $stock_category_id = $category_info->stock_category_id;
                            $companies_id = $category_info->companies_id;
                        } else {
                            $stock_category_id = null;
                            $companies_id = null;
                        }
                    ?>
                    <!-- Add Stock Category tab Starts -->
                    <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="assign_task" style="position: relative;">
                        <?php echo form_open(base_url('admin/stock/save_stock_category/' . $stock_category_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php super_admin_form($companies_id, 3, 5) ?>
                                <div class="row mb-3">
                                    <label
                                            class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('select') . ' ' . lang('categories') ?>
                                        <span
                                                class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                        <select class="form-control select_box" style="width: 100%"
                                                name="stock_category_id"
                                                id="new_departments">
                                            <option value=""><?= lang('new_category') ?></option>
                                            <?php $all_stock_category = by_company('tbl_stock_category', 'stock_category_id', null, $companies_id);
                                            if (!empty($all_stock_category)) {
                                                foreach ($all_stock_category as $v_stock_category) { ?>
                                                    <option <?= (!empty($category_info->stock_category_id) && $category_info->stock_category_id == $v_stock_category->stock_category_id ? 'selected' : null) ?>
                                                            value="<?= $v_stock_category->stock_category_id ?>"><?php
                                                        if (!empty($v_stock_category->stock_category)) {
                                                            $stock_category = $v_stock_category->stock_category;
                                                        } else {
                                                            $stock_category = lang('undefined_category');
                                                        }
                                                        echo $stock_category;
                                                        ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $('select[name="companies_id"]').on('change', function () {
                                            var companies_id = $(this).val();
                                            if (companies_id) {
                                                $.ajax({
                                                    url: '<?= base_url('admin/global_controller/json_by_company/tbl_stock_category/')?>' + companies_id,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success: function (data) {
                                                        $('select[name="stock_category_id"]').find('option').not(':first').remove();
                                                        $.each(data, function (key, value) {
                                                            $('select[name="stock_category_id"]').append('<option value="' + value.stock_category_id + '">' + value.stock_category + '</option>');
                                                        });
                                                    }
                                                });
                                            } else {
                                                $('select[name="stock_category_id"]').find('option').not(':first').remove();
                                            }
                                        });
                                    });
                                </script>

                                <div class="row mb-3 new_departments"
                                     style="display: <?= (!empty($category_info->stock_category_id) ? 'none' : '') ?>">
                                    <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('new_category') ?></label>
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                        <input <?= (!empty($category_info->stock_category_id) ? 'disabled' : '') ?>
                                                type="text" name="stock_category" class="form-control new_departments"
                                                value=""/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('sub_category') ?><span
                                                class="required">*</span></label>
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                        <input type="text" name="stock_sub_category" required class="form-control"
                                               value="<?php if (!empty($sub_category_info->stock_sub_category)) echo $sub_category_info->stock_sub_category; ?>"/>
                                    </div>
                                </div>
                                <input type="hidden" name="stock_sub_category_id" class="form-control"
                                       value="<?php if (!empty($sub_category_info->stock_sub_category_id)) echo $sub_category_info->stock_sub_category_id; ?>"/>

                                <div class="row mb-3 margin">
                                    <div class="col-sm-offset-3 col-lg-5 col-md-5 col-sm-5">
                                        <button type="submit" id="sbtn"
                                                class="btn btn-primary btn-block"><?php echo !empty($sub_category_info->stock_sub_category_id) ? lang('update') : lang('save') ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

