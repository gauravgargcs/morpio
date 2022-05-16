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
<style type="text/css">
    .dropdown-menu > li > a {
        white-space: normal;
    }

    .dragger {
        background: url(<?= base_url()?>skote_assets/images/dragger.png) 10px 32px no-repeat;
        cursor: pointer;
    }

    <?php if (!empty($purchase_info)) { ?>
    .dragger {
        background: url(<?= base_url()?>skote_assets/images/dragger.png) 10px 32px no-repeat;
        cursor: pointer;
    }

    <?php }?>
    .input-transparent {
        box-shadow: none;
        outline: 0;
        border: 0 !important;
        background: 0 0;
        padding: 3px;
    }

</style>
<?php
$edited = can_action('154', 'edited');
$deleted = can_action('154', 'deleted');
if (!empty($invoice_info)) {
    $invoices_id = $invoice_info->invoices_id;
    $companies_id = $invoice_info->companies_id;
} else {
    $invoices_id = null;
    $companies_id = null;
}
echo form_open(base_url('admin/invoice/save_invoice/' . $invoices_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="float-end">
                    <input type="submit" value="<?= lang('paid') ?>" name="Paid" class="btn btn-success">
                    <input type="submit" value="<?= lang('draft') ?>" name="save_as_draft" class="btn btn-warning">
                    <input type="hidden" value="1" name="pos">
                </div>

                <h4 class="card-title mb-4"> <?= lang('pos_sales') ?> </h4>                    
                <div class="row">                    
                    <div class="col-lg-6 br pv">
                        <div class="card border">
                            <div class="card-body">
                                <div class="row">
                                    <?php super_admin_form($companies_id, 5, 7) ?>
                                    <div class="row mb-3">
                                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('reference_no') ?> <span class="text-danger">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <input type="text" class="form-control" value="<?php
                                            if (!empty($invoice_info)) {
                                                echo $invoice_info->reference_no;
                                            } else {
                                                echo config_item('invoice_prefix');
                                                if (config_item('increment_invoice_number') == 'FALSE') {
                                                    $this->load->helper('string');
                                                    echo random_string('nozero', 6);
                                                } else {
                                                    echo $this->invoice_model->generate_invoice_number();
                                                }
                                            }
                                            ?>" name="reference_no">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('client') ?> <span
                                                    class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <select class="form-control select_box" style="width: 100%"
                                                    name="client_id" required="">
                                                <option
                                                        value=""><?= lang('select') . ' ' . lang('client') ?></option>
                                                <?php
                                                if (!empty($all_client)) {
                                                    foreach ($all_client as $v_client) {
                                                        if (!empty($project_info->client_id)) {
                                                            $client_id = $project_info->client_id;
                                                        } elseif (!empty($invoice_info->client_id)) {
                                                            $client_id = $invoice_info->client_id;
                                                        } elseif (!empty($c_id)) {
                                                            $client_id = $c_id;
                                                        }
                                                        ?>
                                                        <option value="<?= $v_client->client_id ?>"
                                                            <?php
                                                            if (!empty($client_id)) {
                                                                echo $client_id == $v_client->client_id ? 'selected' : null;
                                                            }
                                                            ?>
                                                        ><?= ucfirst($v_client->name) ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <script type="text/javascript">
                                            $(document).ready(function () {
                                                $('select[name="companies_id"]').on('change', function () {
                                                    var companies_id = $(this).val();
                                                    if (companies_id) {
                                                        $.ajax({
                                                            url: '<?= base_url('admin/global_controller/json_by_company/tbl_client/')?>' + companies_id,
                                                            type: "GET",
                                                            dataType: "json",
                                                            success: function (data) {
                                                                $('select[name="client_id"]').find('option').not(':first').remove();
                                                                $.each(data, function (key, value) {
                                                                    $('select[name="client_id"]').append('<option value="' + value.client_id + '">' + value.name + '</option>');
                                                                });
                                                            }
                                                        });
                                                    } else {
                                                        $('select[name="client_id"]').empty();
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('invoice_date') ?></label>
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <div class="input-group">
                                                <input type="text" name="invoice_date"
                                                       class="form-control datepicker"
                                                       value="<?php
                                                       if (!empty($invoice_info->invoice_date)) {
                                                           echo date('d-m-Y H-i', strtotime($invoice_info->invoice_date));
                                                       } else {
                                                           echo date('d-m-Y H-i');
                                                       }
                                                       ?>"
                                                       data-date-format="<?= config_item('date_picker_format'); ?>">
                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('due_date') ?></label>
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <div class="input-group">
                                                <input type="text" name="due_date"
                                                       class="form-control datepicker"
                                                       value="<?php
                                                       if (!empty($invoice_info->due_date)) {
                                                           echo date('d-m-Y H-i', strtotime($invoice_info->due_date));
                                                       } else {
                                                           echo strftime(date('d-m-Y H-i', strtotime("+" . config_item('invoices_due_after') . " days")));
                                                       }
                                                       ?>"
                                                       data-date-format="<?= config_item('date_picker_format'); ?>">
                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="discount_type"
                                               class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('discount_type') ?></label>
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <select name="discount_type" class="select_box form-control " data-width="100%">
                                                <option value=""
                                                        selected><?php echo lang('no') . ' ' . lang('discount'); ?></option>
                                                <option value="before_tax" <?php
                                                if (isset($invoice_info)) {
                                                    if ($invoice_info->discount_type == 'before_tax') {
                                                        echo 'selected';
                                                    }
                                                } ?>><?php echo lang('before_tax'); ?></option>
                                                <option value="after_tax" <?php if (isset($invoice_info)) {
                                                    if ($invoice_info->discount_type == 'after_tax') {
                                                        echo 'selected';
                                                    }
                                                } ?>><?php echo lang('after_tax'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="field-1"
                                               class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('sales') . ' ' . lang('agent') ?></label>
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <select class="form-control select_box" required style="width: 100%"
                                                    name="user_id">
                                                <option
                                                        value=""><?= lang('select') . ' ' . lang('sales') . ' ' . lang('agent') ?></option>
                                                <?php
                                                $all_user = get_staff_details();
                                                if (!empty($all_user)) {
                                                    foreach ($all_user as $v_user) {
                                                        $profile_info = $this->db->where('user_id', $v_user->user_id)->get('tbl_account_details')->row();
                                                        if (!empty($profile_info)) {
                                                            ?>
                                                            <option value="<?= $v_user->user_id ?>"
                                                                <?php
                                                                if (!empty($invoice_info->user_id)) {
                                                                    echo $invoice_info->user_id == $v_user->user_id ? 'selected' : null;
                                                                } else {
                                                                    echo $this->session->userdata('user_id') == $v_user->user_id ? 'selected' : null;
                                                                }
                                                                ?>
                                                            ><?= $profile_info->fullname ?></option>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-xl-12">
                                <input type="text" class="form-control" name="term"  placeholder="<?= lang('enter_product_name_and_code') ?>" id="search_text">
                                <hr class="m0 mb-lg mt-lg"/>
                            </div>
                            <script>
                                $(document).ready(function () {
                                    load_data();

                                    function load_data(query) {
                                        $.ajax({
                                            url: "<?php echo base_url(); ?>admin/global_controller/items_suggestions",
                                            method: "POST",
                                            data: {term: query},
                                            success: function (data) {
                                                $('#product_result').html(data);
                                            }
                                        })
                                    }

                                    $('#search_text').keyup(function () {
                                        var search = $(this).val();
                                        if (search != '') {
                                            load_data(search);
                                        } else {
                                            load_data();
                                        }
                                    });
                                });
                            </script>
                            <div id="product_result" class="product_result row"></div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row p-3">
                    <div class="col-xl-4">
                        <div class="row mb-3">
                            <input type="text" placeholder="<?= lang('search_product_by_name_code'); ?>"  id="pos_item" class="form-control">
                        </div>
                    </div>
                    <div class="col-xl-3">
                    </div>
                    <div class="col-xl-5">
                        <div class="row mb-3">
                            <label class="col-xl-4 col-form-label"><?php echo lang('show_quantity_as'); ?></label>
                            <div class="col-xl-8" style="display:inline-flex;">
                                <div class="form-check form-radio-outline form-radio-primary mt mr">
                                    <input type="radio" value="qty" id="<?php echo lang('qty'); ?>" name="show_quantity_as"
                                        <?php if (isset($purchase_info) && $purchase_info->show_quantity_as == 'qty') {
                                            echo 'checked';
                                        } else if (!isset($hours_quantity) && !isset($qty_hrs_quantity)) {
                                            echo 'checked';
                                        } ?> class="form-check-input">
                                    <label class="form-check-label" for="<?php echo lang('qty'); ?>"><?php echo lang('qty'); ?></label>
                                </div>
                                <div class="form-check form-radio-outline form-radio-primary mt mr">
                                    <input type="radio" value="hours" id="<?php echo lang('hours'); ?>" name="show_quantity_as" <?php if (isset($purchase_info) && $purchase_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                            echo 'checked';
                                        } ?> class="form-check-input">
                                    <label class="form-check-label" for="<?php echo lang('hours'); ?>"><?php echo lang('hours'); ?></label>
                                </div>
                                <div class="form-check form-radio-outline form-radio-primary mt mr">
                                    <input type="radio" value="qty_hours" id="<?php echo lang('qty') . '/' . lang('hours'); ?>" name="show_quantity_as"
                                        <?php if (isset($purchase_info) && $purchase_info->show_quantity_as == 'qty_hours' || isset($qty_hrs_quantity)) {
                                            echo 'checked';
                                        } ?> class="form-check-input">
                                    <label class="form-check-label" for="<?php echo lang('qty') . '/' . lang('hours'); ?>"><?php echo lang('qty') . '/' . lang('hours'); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="table-responsive s_table">
                        <table class="table table-editable table-nowrap align-middle table-edits invoice-items-table items">
                            <thead style="background: #e8e8e8">
                            <tr>
                                <th></th>
                                <th><?= lang('item_name') ?></th>
                                <th><?= lang('description') ?></th>
                                <?php
                                $purchase_view = config_item('purchase_view');
                                if (!empty($purchase_view) && $purchase_view == '2') {
                                    ?>
                                    <th class="col-lg-2 col-md-2 col-sm-2"><?= lang('hsn_code') ?></th>
                                <?php } ?>
                                <?php
                                $qty_heading = lang('qty');
                                if (isset($purchase_info) && $purchase_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                    $qty_heading = lang('hours');
                                } else if (isset($purchase_info) && $purchase_info->show_quantity_as == 'qty_hours') {
                                    $qty_heading = lang('qty') . '/' . lang('hours');
                                }
                                ?>
                                <th class="qty col-lg-2 col-md-2 col-sm-2"><?php echo $qty_heading; ?></th>
                                <th class="col-lg-2 col-md-2 col-sm-2"><?= lang('price') ?></th>
                                <th class="col-lg-2 col-md-2 col-sm-2"><?= lang('tax_rate') ?> </th>
                                <th class="col-lg-1 col-md-1 col-sm-1"><?= lang('total') ?></th>
                                <th class="col-lg-1 col-md-1 col-sm-1 hidden-print"><?= lang('action') ?></th>
                            </tr>
                            </thead>
                            <tbody id="posTable"></tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-xl-5"></div>
                        <div class="col-xl-7 pull-right">
                            <table class="table text-right">
                                <tbody>
                                <tr id="subtotal" class="text-end">
                                    <td><span class="bold"><?php echo lang('sub_total'); ?> :</span>
                                    </td>
                                    <td class="subtotal text-end">
                                    </td>
                                </tr>
                                <tr id="discount_percent" class="text-end">
                                    <td>
                                        <div class="row">
                                            <div class="col-md-7 mt"> <span class="bold"><?php echo lang('discount'); ?> (%)</span>
                                            </div>
                                            <div class="col-md-5 text-end">
                                                    <?php
                                                    $discount_percent = 0;
                                                    if (isset($purchase_info)) {
                                                        if ($purchase_info->discount_percent != 0) {
                                                            $discount_percent = $purchase_info->discount_percent;
                                                        }
                                                    }
                                                    ?>
                                                    <input type="number" step="0.01"
                                                       value="<?php echo $discount_percent; ?>"
                                                       class="form-control pull-left" min="0" max="100"
                                                       name="discount_percent">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="discount_percent text-end"></td>
                                </tr>
                                <tr class="text-end">
                                    <td>
                                        <div class="row">
                                            <div class="col-md-7 mt">
                                                <span class="bold"><?php echo lang('adjustment'); ?></span>
                                            </div>
                                            <div class="col-md-5 text-end">
                                                <input type="number" step="0.01"
                                                       value="<?php if (isset($purchase_info)) {
                                                           echo $purchase_info->adjustment;
                                                       } else {
                                                           echo 0;
                                                       } ?>" class="form-control pull-left"
                                                       name="adjustment">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="adjustment text-end"></td>
                                </tr>
                                <tr class="text-end">
                                    <td><span class="bold"><?php echo lang('total'); ?> :</span>
                                    </td>
                                    <td class="total text-end">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="removed-items"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    var posItems = {};
    if (localStorage.getItem('remove_pos')) {
        if (localStorage.getItem('posItems')) {
            localStorage.removeItem('posItems');
        }
        localStorage.removeItem('remove_pos');
    }
</script>
<?php include_once 'skote_assets/js/pos.php'; ?>