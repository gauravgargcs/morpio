<?= message_box('success'); ?>
<?= message_box('error'); ?>
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
$created = can_action('155', 'created');
$edited = can_action('155', 'edited');
$deleted = can_action('155', 'deleted');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                 <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="<?=base_url("admin/items/damage_items")?>" ><?= lang('damage_items') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab"><?= lang('new') . ' ' . lang('damage_items') ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <h4 class="card-title mb-4"><?= lang('damage_items')  ?></h4>
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="contentTable">
                                <thead>
                                    <tr>
                                        <?php super_admin_opt_th() ?>
                                        <th><?= lang('image') ?></th>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('decrease_from_stock') ?></th>
                                        <th><?= lang('notes') ?></th>
                                        <th><?= lang('date') ?></th>
                                        <?php if (!empty($edited) || !empty($deleted)) { ?>
                                            <th class="col-lg-2 col-md-2 col-sm-2"><?= lang('action') ?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <?php /* ?><tbody>
                                <?php
                                $all_damage_product = get_result('tbl_damage_product');
                                foreach ($all_damage_product as $v_damage_product):
                                    $items_info = $this->db->where('saved_items_id', $v_damage_product->saved_items_id)->get('tbl_saved_items')->row();
                                    if (!empty($items_info)) {
                                        ?>
                                        <tr id="table_items_<?= $v_damage_product->damage_product_id ?>">
                                            <?php super_admin_opt_td($v_damage_product->companies_id) ?>
                                            <td class="col-sm-1 custom-img ">
                                                <a data-bs-toggle="modal" data-target="#myModal_lg"
                                                   href="<?= base_url('admin/items/items_details/' . $v_damage_product->saved_items_id) ?>">
                                                    <img src="<?= product_image($v_damage_product->saved_items_id) ?>"
                                                         alt="<?= $items_info->item_name ?>">
                                                </a>
                                            </td>
                                            <td>
                                                <a data-bs-toggle="modal" data-target="#myModal_lg"
                                                   href="<?= base_url('admin/items/items_details/' . $v_damage_product->saved_items_id) ?>">
                                                    <strong
                                                            class="block"><?= $items_info->item_name ?></strong></a>
                                                <span class="black block"><?= lang('code') ?>:<?= $items_info->code ?></span>
                                                <hr class="m0 p0">
                                                <span class="black block"><?= lang('damage_quantity') ?>
                                                : <?= $v_damage_product->damage_quantity ?></span>
                                            </td>
                                            <td><?= $v_damage_product->decrease_from_stock; ?></td>
                                            <td><?= $v_damage_product->notes; ?></td>
                                            <td><?= display_datetime($v_damage_product->date); ?></td>
                                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                <td>
                                                    <?php if (!empty($edited)) { ?>
                                                        <?= btn_edit('admin/items/damage_items/' . $v_damage_product->damage_product_id) ?>
                                                    <?php }
                                                    if (!empty($deleted)) { ?>
                                                        <?php echo ajax_anchor(base_url("admin/items/delete_damage_items/" . $v_damage_product->damage_product_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_items_" . $v_damage_product->damage_product_id)); ?>
                                                    <?php } ?>
                                                
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <?php
                                    }
                                endforeach;
                                ?>
                                </tbody><?php */ ?>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($created) || !empty($edited)) {
                    if (!empty($damage_product)) {
                        $damage_product_id = $damage_product->damage_product_id;
                        $companies_id = $damage_product->companies_id;
                    } else {
                        $damage_product_id = null;
                        $companies_id = null;
                    }
                    $saved_items = $this->items_model->get_all_items();
                    ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                        <?php echo form_open(base_url('admin/items/save_damage_product/' . $damage_product_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <div class="row">
                            
                            <?php super_admin_form($companies_id, 3, 5) ?>
                            
                            <div class="row mb-3">
                                <label
                                        class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('select') . ' ' . lang('product') ?> </label>
                                <div class="col-lg-5 col-md-5 col-sm-7">
                                    <select name="saved_items_id" class="form-control select_box"
                                            style="width: 100%">
                                        <option value=""><?= lang('none') ?></option>
                                        <?php
                                        $all_items = by_company('tbl_saved_items', 'saved_items_id', null, $companies_id);
                                        if (!empty($all_items)) {
                                            foreach ($all_items as $v_item) {
                                                ?>
                                                <option value="<?php echo $v_item->saved_items_id; ?>"
                                                    <?php
                                                    if (!empty($damage_product)) {
                                                        echo $damage_product->saved_items_id == $v_item->saved_items_id ? 'selected' : null;
                                                    }
                                                    ?>
                                                        data-subtext="<?php echo strip_html_tags(mb_substr($v_item->item_desc, 0, 200)) . '...'; ?>">
                                                    (<?= $v_item->code; ?>
                                                    ) <?php echo $v_item->item_name; ?></option>
                                            <?php } ?>
                                            <?php
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
                                                url: '<?= base_url('admin/global_controller/json_by_company/tbl_saved_items/')?>' + companies_id,
                                                type: "GET",
                                                dataType: "json",
                                                success: function (data) {
                                                    $('select[name="saved_items_id"]').find('option').not(':first').remove();
                                                    $.each(data, function (key, value) {
                                                        $('select[name="saved_items_id"]').append('<option value="' + value.saved_items_id + '">(' + value.code + ')' + value.item_name + '</option>');
                                                    });
                                                }
                                            });
                                        } else {
                                            $('select[name="saved_items_id"]').find('option').not(':first').remove();
                                        }
                                    });
                                });
                            </script>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('damage_quantity') ?> <span
                                            class="text-danger">*</span></label>
                                <div class="col-lg-5 col-md-5 col-sm-7">
                                    <input type="text" data-parsley-type="number" class="form-control" value="<?php
                                    if (!empty($damage_product->damage_quantity)) {
                                        echo $damage_product->damage_quantity;
                                    }
                                    ?>" name="damage_quantity" required="">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('notes') ?></label>
                                <div class="col-lg-5 col-md-5 col-sm-7">
                                        <textarea name="notes" class="form-control"><?php
                                            if (!empty($damage_product)) {
                                                echo $damage_product->notes;
                                            }
                                            ?></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('decrease_from_stock') ?> <span
                                            class="text-danger">*</span></label>
                                <div class="col-lg-5 col-md-5 col-sm-7">
                                    <div class="form-check form-check-primary mb-3">
                                        <input data-bs-toggle="toggle"  name="decrease_from_stock" value="Yes" <?php
                                            if (!empty($damage_product->decrease_from_stock) && $damage_product->decrease_from_stock == 'Yes') {
                                                echo 'checked';
                                            }
                                            ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>"  data-onstyle="success btn-sm" data-offstyle="danger btn-sm" type="checkbox">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"></label>
                                <div class="col-lg-5 col-md-5 col-sm-7">
                                    <button type="submit" class="btn btn-xs btn-primary"><?= lang('updates') ?></button>
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

<!-- Script -->
 <script type="text/javascript">
     $(document).ready(function(){
        $('#contentTable').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
             'url':'<?=base_url()?>admin/datatable/damage_items'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_items_"+iDisplayIndex);
            return nRow;
          },
          'columns': [
             <?php if (is_company_column_ag()) { ?>
                { data: 'companies_id' },
             <?php } ?>             
             { data: 'image' },
             { data: 'name' },
             { data: 'decrease_from_stock' },
             { data: 'notes' },
             { data: 'date' },
             { data: 'action' },
          ]
        });
     });
 </script>