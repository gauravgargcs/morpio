<link href="<?= base_url() ?>plugins/formbuilder/formbuilder.css" rel="stylesheet" />
<style>
    .fb-main {
        background-color: #fff;
        border-radius: 5px;
        min-height: 600px;
    }

    input[type=text] {
        height: 26px;
        margin-bottom: 3px;
    }
</style>
<style>
    /*Hide Auto-save button*/
    .fb-save-wrapper .js-save-form {
        display: none;
    }
</style>
<?= message_box('success'); ?>

<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="#manage" data-toggle="tab"><?= lang('quotations') ?></a></li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="#new" data-toggle="tab"><?= lang('request_quotations') ?></a></li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?= lang('title') ?></th>
                        <th><?= lang('client') ?></th>
                        <th><?= lang('date') ?></th>
                        <th><?= lang('amount') ?></th>
                        <th><?= lang('status') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($all_quatations)) {
                        foreach ($all_quatations as $v_quatations) {
                    ?>
                            <tr>
                                <?php
                                $client_info = $this->quotations_model->check_by(array('client_id' => $v_quatations->client_id), 'tbl_client');
                                $user_info = $this->quotations_model->check_by(array('user_id' => $v_quatations->user_id), 'tbl_users');
                                if ($user_info->role_id == 1) {
                                    $user = '(' . lang('admin') . ')';
                                } elseif ($user_info->role_id == 2) {
                                    $user = '(' . lang('client') . ')';
                                } else {
                                    $user = '(' . lang('staff') . ')';
                                }
                                $currency = $this->quotations_model->client_currency_sambol($v_quatations->client_id);
                                ?>
                                <td>
                                    <a href="<?= base_url() ?>client/quotations/quotations_details/<?= $v_quatations->quotations_id ?>"><?= $v_quatations->quotations_form_title; ?></a>
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
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="new">
            <form class="form-horizontal" action="<?= base_url() ?>client/quotations/index/get_form" method="post" id="addQuotationForm">
                <!-- Sidebar ends -->
                <!-- Main bar -->
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('select_quotations_form') ?> <span class="text-danger">*</span> </label>
                    <div class="col-lg-5">
                        <div class=" ">
                            <select name="quotationforms_id" class="form-control select_box" style="width: 100%">
                                <?php
                                $all_quotationforms = get_result('tbl_quotationforms', array('quotationforms_status' => 'enabled'));
                                if (!empty($all_quotationforms)) {
                                    foreach ($all_quotationforms as $v_quotationforms) :
                                ?>
                                        <option value="<?= $v_quotationforms->quotationforms_id ?>"><?= $v_quotationforms->quotationforms_title ?></option>
                                <?php
                                    endforeach;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <button type="submit" class="btn btn-primary"><?= lang('go') ?></button>
                    </div>
                </div>
            </form>
            <?php if (!empty($formbuilder_data)) : ?>
                <div class="panel panel-custom">
                    <div class="panel-heading"> <?= $quotationforms_info->quotationforms_title ?></div>
                    <div class="panel-body">
                        <form method="post" action="<?= base_url() ?>client/quotations/set_quotations/<?= $quotationforms_info->quotationforms_id; ?>" enctype="multipart/form-data">
                            <?php
                            foreach ($formbuilder_data as $value) {
                                if (!empty($value)) {
                                    $field_type = $value['field_type'];
                                    $field_options = $value['field_options'];
                                    $required = '';
                                    if ($value['required'] == 1) {
                                        $required = 'true';
                                    }
                            ?>

                                    <?php if ($field_type == 'paragraph') : ?>
                                        <label class="control-label"><?php echo $value['label'] ?> <?php if (!empty($required)) { ?>
                                                <span class="text-danger">*</span><?php } ?></label>
                                        <div class="">
                                            <?php
                                            if ($field_options['size'] == 'small') {
                                                $height = 'min-height:60px';
                                            } elseif ($field_options['size'] == 'medium') {
                                                $height = 'min-height:100px';
                                            } else {
                                                $height = 'min-height:200px';
                                            }
                                            ?>
                                            <textarea <?= (!empty($required) ? 'required' : '') ?> name="<?php echo $value['cid'] ?>" style="<?= $height ?>" class="form-control"></textarea>
                                        </div>
                                        <br />
                                    <?php endif; ?>
                                    <?php if ($field_type == 'dropdown') : ?>
                                        <label class="control-label"><?php echo $value['label'] ?> <?php if (!empty($required)) { ?>
                                                <span class="text-danger">*</span><?php } ?></label>
                                        <div class="">
                                            <select class="form-control" <?= (!empty($required) ? 'required' : '') ?> style="font-size: 13px;" name="<?php echo $value['cid'] ?>">
                                                <?php
                                                $options = $field_options['options'];
                                                foreach ($options as $v_option) {
                                                    if ($v_option['checked'] == 1) {
                                                        $checked = 'selected';
                                                    } else {
                                                        $checked = '';
                                                    }
                                                ?>
                                                    <option <?= $checked ?>> <?= $v_option['label'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <br />
                                    <?php endif; ?>
                                    <?php if ($field_type == 'text') : ?>
                                        <label class="control-label"><?php echo $value['label'] ?> <?php if (!empty($required)) { ?>
                                                <span class="text-danger">*</span><?php } ?></label>
                                        <div class="">
                                            <input type="text" <?= (!empty($required) ? 'required' : '') ?> name="<?php echo $value['cid'] ?>" class="form-control" />
                                        </div>
                                        <br />
                                    <?php endif; ?>
                                    <?php if ($field_type == 'checkboxes') : ?>
                                        <label class="control-label"><?php echo $value['label'] ?> <?php if (!empty($required)) { ?>
                                                <span class="text-danger">*</span><?php } ?></label>
                                        <div class="">
                                            <?php
                                            $options = $field_options['options'];
                                            foreach ($options as $v_option) {
                                                if ($v_option['checked'] == 1) {
                                                    $checked = 'checked';
                                                } else {
                                                    $checked = '';
                                                }
                                            ?>
                                                <input type="checkbox" <?= (!empty($required) ? 'required' : '') ?> name="<?php echo $value['cid'] ?>[]" value="<?= $v_option['label'] ?>" style="width: 15px;height: 15px;margin-right: 5px" <?= $checked ?>><?= $v_option['label'] ?>
                                            <?php } ?>
                                        </div>
                                        <br />
                                    <?php endif; ?>
                                    <?php if ($field_type == 'radio') : ?>
                                        <label class="control-label"><?php echo $value['label'] ?> <?php if (!empty($required)) { ?>
                                                <span class="text-danger">*</span><?php } ?></label>
                                        <div class="">
                                            <?php
                                            $options = $field_options['options'];
                                            foreach ($options as $v_option) {
                                                if ($v_option['checked'] == 1) {
                                                    $checked = 'checked';
                                                } else {
                                                    $checked = '';
                                                }
                                            ?>
                                                <input <?= (!empty($required) ? 'required' : '') ?> name="<?php echo $value['cid'] ?>" type="radio" value="<?= $v_option['label'] ?>" style="width: 15px;height: 15px;margin-right: 5px" <?= $checked ?>><?= $v_option['label'] ?>
                                            <?php } ?>
                                        </div>
                                        <br />
                                    <?php endif; ?>

                            <?php
                                }
                            }
                            ?>
                            <div class="pull-right col-sm-3 row">
                                <input class="btn btn-primary btn-block " type="submit" value="<?= lang('request_quotation') ?>" id="" name="submit">
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
            <!-- Mainbar ends -->
            <div class="clearfix"></div>
        </div>

    </div>
</div>
<script type="text/javascript">
    $(function() {
        var requiredCheckboxes = $(':checkbox[required]');
        requiredCheckboxes.change(function() {
            if (requiredCheckboxes.is(':checked')) {
                requiredCheckboxes.removeAttr('required');
            } else {
                requiredCheckboxes.attr('required', 'required');
            }
        });
    });
</script>