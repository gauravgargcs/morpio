<?php echo message_box('success'); ?>
<?php echo message_box('error');
$created = can_action('82', 'created');
$edited = can_action('82', 'edited');
$deleted = can_action('82', 'deleted');
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
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 1 ? 'active' : '' ?>" href="<?=base_url('admin/stock/assign_stock');?>"><?= lang('assign_stock_list') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 2 ? 'active' : '' ?>" href="#assign_task" data-bs-toggle="tab"><?= lang('assign_stock') ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <h4 class="card-title mb-4"><?= lang('assign_stock_list') ?></h4>
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="assign_stock_datatable">
                                <thead>
                                    <tr>
                                        <th class="col-lg-1 col-md-1 col-sm-1"><?= lang('sl') ?></th>
                                        <?php super_admin_opt_th() ?>
                                        <th><?= lang('item_name') ?></th>
                                        <th><?= lang('stock_category') ?></th>
                                        <th><?= lang('assign_quantity') ?></th>
                                        <th><?= lang('assign_date') ?></th>
                                        <th><?= lang('assigned_user') ?></th>
                                        <th class="col-lg-1 col-md-1 col-sm-1 hidden-print"><?= lang('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $all_assign_list = $this->stock_model->get_assign_stock_list();
                                    if (!empty($all_assign_list)) {
                                        foreach ($all_assign_list as $key => $v_assign_stock) { ?>

                                        <tr id="table_assign_stock_<?= $v_assign_stock->assign_item_id ?>">
                                            <td><?php echo $key + 1 ?></td>
                                            <?php super_admin_opt_td($v_assign_stock->companies_id) ?>
                                            <td><?php echo $v_assign_stock->item_name ?></td>
                                            <td><?php echo $v_assign_stock->stock_category . ' &succcurlyeq; ' . $v_assign_stock->stock_sub_category ?></td>
                                            <td><?php echo $v_assign_stock->assign_inventory ?></td>
                                            <td><?= display_datetime($v_assign_stock->assign_date); ?></td>
                                            <td><?= $v_assign_stock->fullname ?></td>
                                            <td class="hidden-print">
                                                <?php if (!empty($deleted)) { ?>
                                                    <?php echo ajax_anchor(base_url("admin/stock/delete_assign_stock/" . $v_assign_stock->assign_item_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_assign_stock_" . $v_assign_stock->assign_item_id)); ?>
                                                <?php } ?>
                                            </td>

                                        </tr>
                                        <?php
                                        };
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($created) || !empty($edited)){
                    if (!empty($assign_item)) {
                        $assign_item_id = $assign_item->assign_item_id;
                        $companies_id = $assign_item->companies_id;
                    } else {
                        $assign_item_id = null;
                        $companies_id = null;
                    }
                    ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="assign_task" style="position: relative;">
                        <?php echo form_open(base_url('admin/stock/set_assign_stock/' . $assign_item_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <?php super_admin_form($companies_id, 3, 5) ?>
                        <div class="row mb-3 ">
                            <label class="col-form-label col-lg-3 col-md-3 col-sm-4"><?= lang('stock_category') ?><span
                                        class="required">*</span></label>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <select name="stock_sub_category_id" style="width: 100%"
                                        class="form-control select_box"
                                        onchange="get_item_name_by_id(this.value)">
                                    <option
                                            value=""><?= lang('select') . ' ' . lang('stock_category') ?></option>
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
                                                        <option
                                                                value="<?php echo $sub_category->stock_sub_category_id; ?>"
                                                            <?php
                                                            if (!empty($stock_info->stock_sub_category_id)) {
                                                                echo $sub_category->stock_sub_category_id == $stock_info->stock_sub_category_id ? 'selected' : '';
                                                            }
                                                            ?>><?php echo $sub_category->stock_sub_category ?></option>
                                                        <?php
                                                    }
                                                endforeach;
                                                ?>
                                            </optgroup>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('select[name="companies_id"]').on('change', function () {
                                    var companies_id = $(this).val();
                                    if (companies_id) {
                                        $.ajax({
                                            url: '<?= base_url('admin/stock/json_get_stock/')?>' + companies_id,
                                            type: "GET",
                                            dataType: "json",
                                            success: function (data) {
                                                $('select[name="stock_sub_category_id"]').empty();
                                                $.each(data, function (key, value) {
                                                    $('select[name="stock_sub_category_id"]').append('<optgroup label="' + key + '">');
                                                    $.each(value, function (keys, values) {
                                                        $('select[name="stock_sub_category_id"]').append('<option value="' + values.stock_sub_category_id + '">' + values.stock_sub_category + '</option>');
                                                    });
                                                    $('select[name="stock_sub_category_id"]').append('</optgroup>');
                                                });
                                            }
                                        });
                                    } else {
                                        $('select[name="stock_sub_category_id"]').empty();
                                    }
                                });
                            });
                        </script>
                        <div class="row mb-3">
                            <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('item_name') ?><span  class="required">*</span></label>

                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <select required class="form-control" name="stock_id" id="item_name">
                                    <option value=""><?= lang('select') . ' ' . lang('item_name') ?></option>
                                    <?php if (!empty($stock_info)): ?>
                                        <?php foreach ($stock_info as $v_stock_info): ?>
                                            <option value="<?php echo $v_stock_info->stock_id ?>" <?php
                                            if (!empty($assign_item->stock_id)) {
                                                echo $v_stock_info->stock_id == $assign_item->stock_id ? 'selected' : '';
                                            }
                                            ?>><?php echo $v_stock_info->item_name ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="field-1"  class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('employee') . ' ' . lang('name') ?>
                                <span class="required"> *</span></label>

                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <select required class="form-control select_box" style="width: 100%" name="user_id">
                                    <option value=""><?= lang('select_employee') ?>...</option>
                                    <?php if (!empty($all_employee)): ?>
                                        <?php foreach ($all_employee as $dept_name => $v_all_employee) : ?>
                                            <optgroup label="<?php echo $dept_name; ?>">
                                                <?php if (!empty($v_all_employee)):foreach ($v_all_employee as $v_employee) : ?>
                                                    <option value="<?php echo $v_employee->user_id; ?>"
                                                        <?php
                                                        if (!empty($assign_item->user_id)) {
                                                            echo $v_employee->user_id == $assign_item->user_id ? 'selected' : '';
                                                        }
                                                        ?>><?php echo $v_employee->fullname . ' ( ' . $v_employee->designations . ' )' ?></option>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </optgroup>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('assign_date') ?><span
                                        class="required">*</span></label>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <div class="input-group">
                                    <input required type="text" name="assign_date"
                                           placeholder="<?= lang('enter') . ' ' . lang('assign_date') ?>"
                                           class="form-control datepicker" value="<?php
                                    if (!empty($assign_item->assign_date)) {
                                        echo date('d-m-Y H:i', strtotime($assign_item->assign_date));
                                    } else {
                                        echo date('d-m-Y H-i');
                                    }
                                    ?>" data-format="dd-mm-yyyy">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>

                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('assign_quantity') ?><span
                                        class="required"> *</span></label>

                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <input required type="text" data-parsley-type="number" name="assign_inventory"
                                       placeholder="<?= lang('enter') . ' ' . lang('assign_quantity') ?>"
                                       class="form-control" value="<?php
                                if (!empty($assign_item->assign_inventory)) {
                                    echo $assign_item->assign_inventory;
                                }
                                ?>">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 col-form-label"></label>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <button type="submit" id="sbtn"
                                        class="btn btn-primary"><?= lang('save') ?></button>
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