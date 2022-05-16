<?php include_once 'asset/admin-ajax.php'; ?>
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

<?php
$created = can_action('81', 'created');
$edited = can_action('81', 'edited');
$deleted = can_action('81', 'deleted');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 1 ? 'active' : '' ?>" href="<?=site_url('admin/stock/stock_list');?>"><?= lang('all') . ' ' . lang('stock') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)){  ?> 

                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 2 ? 'active' : '' ?>" href="#assign_task" data-bs-toggle="tab"><?= lang('new') . ' ' . lang('stock') ?></a>
                    </li>
                    <?php } ?> 
                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <div class="row">
                            <?php $key = 0;
                            $category = lang('undefined_category');
                            $companies_id = null;
                            if (!empty($all_stock_info)) : ?>
                                <?php foreach ($all_stock_info as $category_id => $v_stock_info):
                                    if (!empty($category_id)) {
                                        $category_info = get_row('tbl_stock_category', array('stock_category_id' => $category_id));
                                        if (!empty($category_info)) {
                                            $category = $category_info->stock_category;
                                            $companies_id = $category_info->companies_id;
                                        }
                                    }
                                    ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                                <div class="accordion" id="accordion<?=$category_id;?>" >
                                    <?php if (!empty($v_stock_info)): ?>
                                    <div class="accordion-item">
                                        <div class="accordion-header" role="tab" id="headingOne">
                                            <a class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordion<?=$category_id;?><?php echo $key ?>" aria-expanded="true" aria-controls="collapseOne">  <?php echo $category; ?>
                                            </a>
                                        </div>
                                        <div id="accordion<?=$category_id;?><?php echo $key ?>" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                                            <div class="accordion-body">
                                            <?php foreach ($v_stock_info as $sub_category => $v_stock): ?>
                                                <table class="table table-bordered" style="margin-bottom: 0px;">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="5"
                                                                style="background-color: #E3E5E6;color: #000000 ">
                                                                <strong><?php echo $sub_category; ?></strong></th>
                                                        </tr>
                                                        <tr style="font-size: 13px;color: #000000">
                                                            <th><?= lang('item_name') ?></th>
                                                            <th><?= lang('code') ?></th>
                                                            <th><?= lang('price') ?></th>
                                                            <th><?= lang('total_stock') ?></th>
                                                            <?php if (!empty($deleted) || !empty($edited)) { ?>
                                                                <th class="col-lg-2 col-md-2 col-sm-2"><?= lang('action') ?></th>
                                                            <?php } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody style="margin-bottom: 0px;background: #FFFFFF;font-size: 12px;">
                                                        <?php foreach ($v_stock as $stock) : ?>
                                                            <tr>
                                                                <td><?php echo $stock->item_name; ?></td>
                                                                <td><?php echo $stock->code ?></td>
                                                                <td><?php echo $stock->price ?></td>
                                                                <td><?php echo $stock->total_stock ?></td>
                                                                <?php if (!empty($deleted) || !empty($edited)) { ?>
                                                                    <td>
                                                                        <?php if (!empty($edited)) { ?>
                                                                            <?php echo btn_edit('admin/stock/stock_list/' . $stock->stock_id); ?>
                                                                        <?php }
                                                                        if (!empty($deleted)) { ?>
                                                                            <?php echo btn_delete('admin/stock/delete_stock/' . $stock->stock_id); ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                <?php } ?>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php $key++; ?>
                            <?php endforeach; else : ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 mb-2" data-offset="0">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center text-sm font-size-18"><?= lang('nothing_to_display') ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($created) || !empty($edited)) {
                        $item_history_id = null;
                    if (!empty($stock_info)) {
                        // $item_history_id = $stock_info->item_history_id;
                        $id = $stock_info->stock_id;
                        $companies_id = $stock_info->companies_id;
                    } else {
                        
                        $companies_id = null;
                         $id = null;
                    }
                    ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="assign_task">
                        <?php echo form_open(base_url('admin/stock/save_stock/' . $id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <?php super_admin_form($companies_id, 3, 5) ?>
                        <div class="row mb-3">
                            <label class="col-form-label col-lg-3 col-md-3 col-sm-4"><?= lang('stock_category') ?><span
                                        class="required">*</span></label>
                            <div class="col-lg-5 col-md-5 col-sm-5">

                                <select name="stock_sub_category_id" style="width: 100%"
                                        class="form-control select_box" onchange="get_item_name_by_id(this.value)">
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
                        <?php $direction = $this->session->userdata('direction');
                        if (!empty($direction) && $direction == 'rtl') {
                            $RTL = 'on';
                        } else {
                            $RTL = config_item('RTL');
                        }
                        ?>
                         <div class="row mb-3">
                            <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('item_name') ?><span
                                        class="required"> * </span></label>

                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <input required type="text" <?php
                                if (!empty($RTL)) { ?>
                                    dir="rtl"
                                <?php }
                                ?> name="item_name" class="form-control" placeholder=""
                                       id="query"
                                       value="<?php
                                       if (!empty($stock_info->item_name)) {
                                           echo $stock_info->item_name;
                                       }
                                       ?>"/>
                            </div>
                        </div> 
                        <div class="row mb-3">
                            <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('code') ?><span
                                        class="required"> * </span></label>

                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <input required type="text" <?php
                                if (!empty($RTL)) { ?>
                                    dir="rtl"
                                <?php }
                                ?> name="code" class="form-control" placeholder=""
                                       id="code"
                                       value="<?php
                                       if (!empty($stock_info->code)) {
                                           echo $stock_info->code;
                                       }
                                       ?>"/>
                            </div>
                        </div> 

                         <div class="row mb-3">
                            <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('price') ?><span
                                        class="required"> * </span></label>

                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <input required type="text" data-parsley-type="number" <?php
                                if (!empty($RTL)) { ?>
                                    dir="rtl"
                                <?php }
                                ?> name="price" class="form-control" placeholder=""
                                       id="price"
                                       value="<?php
                                       if (!empty($stock_info->price)) {
                                           echo $stock_info->price;
                                       }
                                       ?>"/>
                            </div>
                        </div> 
                        

                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('purchase_date') ?><span
                                        class="required">*</span></label>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                               
                                    <input required type="text" name="purchase_date"
                                           placeholder="<?= lang('enter') . ' ' . lang('purchase_date') ?>"
                                           class="form-control datepicker" value="<?php
                                    if (!empty($assign_item->purchase_date)) {
                                        echo date('d-m-Y H:i', strtotime($assign_item->purchase_date));
                                    } else {
                                        echo date('d-m-Y H-i');
                                    }
                                    ?>" >
                                   
                               
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
                            <?php if (!empty($from_history) || empty($stock_info)) { ?>
                                <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('inventory') ?> <span
                                            class="required">*</span></label>

                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <input required type="text" data-parsley-type="number" name="inventory"
                                           placeholder=""
                                           class="form-control"
                                           value="<?php
                                           if (!empty($stock_info->inventory)) {
                                               echo $stock_info->inventory;
                                           }
                                           ?>">
                                </div>
                            <?php } elseif (!empty($stock_info)) { ?>
                                <label for="field-1" class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('total_stock') ?> <span
                                            class="required">*</span></label>

                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <input required type="text" readonly
                                           placeholder=""
                                           class="form-control"
                                           name="inventory"
                                           value="<?php
                                           if (!empty($stock_info->total_stock)) {
                                               echo $stock_info->total_stock;
                                           }
                                           ?>">
                                </div>
                            <?php } ?>
                        </div>
                        <div class="row mb-3">
                        <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 col-form-label"></label>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <button type="submit" id="sbtn" class="btn btn-primary"
                                        id="i_submit"><?= lang('save') ?></button>
                            </div>
                        </div>
                        <!-- Hidden input field-->
                        <input type="hidden" name="item_history_id" value="<?php
                        if (!empty($stock_info->item_history_id)) {
                            echo $stock_info->item_history_id;
                        }
                        ?>">
                        <?php echo form_close(); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<link href="<?php echo base_url() ?>assets/plugins/typehead/typehead.css" rel="stylesheet"/>
<script src="<?php echo base_url() ?>assets/plugins/typehead/typehead.js"></script>

<?php $all_stock = get_result('tbl_stock'); ?>
<script type="text/javascript">
    $('#query').typeahead({
        local: [<?php if(!empty($all_stock)){ foreach($all_stock as $v_stock){?>"<?= $v_stock->item_name ?>",<?php }}?>]
    });
</script>