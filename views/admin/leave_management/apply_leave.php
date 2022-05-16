<?php include_once 'asset/admin-ajax.php';
$office_hours = config_item('office_hours');
?>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/plugins/dropzone/dropzone.min.css">
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/dropzone/dropzone.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/dropzone/dropzone.custom.js"></script>
<div class="modal-header">
    <h5 class="modal-title"><?= lang('apply') . ' ' . lang('leave'); ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php echo form_open(base_url('admin/leave_management/save_leave_application'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
    <div class="modal-body wrap-modal wrap">
        <div class="row">
            <div class="col-sm-8">
             
                <div class="panel_controls">
                    <?php if ($this->session->userdata('user_type') == 1) { ?>
                        <?php
                        $companies_id = null;
                        super_admin_form_modal($companies_id, 3, 8) ?>
                        <div class="row mb-3">
                            <label for="field-1" class="col-sm-3 col-form-label"><?= lang('select') . ' ' . lang('employee') ?> <span class="required"> *</span> </label>

                            <div class="col-sm-8">
                                <select name="user_id" style="width: 100%" onchange="get_leave_details(this.value)" class="form-control modal_select_box" id="users_id" required>
                                    <option value=""><?= lang('select') . ' ' . lang('employee') ?></option>
                                    <?php
                                    $all_employee = $this->application_model->get_all_employee($companies_id);
                                    if (!empty($all_employee)) : ?>
                                        <?php foreach ($all_employee as $dept_name => $v_all_employee) : ?>
                                            <optgroup label="<?php echo $dept_name; ?>">
                                                <?php if (!empty($v_all_employee)) : foreach ($v_all_employee as $v_employee) : ?>
                                                        <option value="<?php echo $v_employee->user_id; ?>" <?= ($v_employee->user_id == $this->session->userdata('user_id')) ? 'selected' : '' ?>><?php echo $v_employee->fullname . ' ( ' . $v_employee->designations . ' )' ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </optgroup>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-10">
                                <div class="required" id="username_result"></div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('select[name="companies_id"]').on('change', function() {
                                    var companies_id = $(this).val();
                                    if (companies_id) {
                                        $.ajax({
                                            url: '<?= base_url('admin/global_controller/json_get_employee/') ?>' + companies_id,
                                            type: "GET",
                                            dataType: "json",
                                            success: function(data) {
                                                $('select[name="user_id"]').find('optgroup').remove();
                                                $('select[name="user_id"]').find('option').not(':first').remove();
                                                if (data) {
                                                    $.each(data, function(key, value) {
                                                        $('select[name="user_id"]').append('<optgroup label="' + key + '">');
                                                        $.each(value, function(keys, values) {
                                                            $('select[name="user_id"]').append('<option value="' + values.user_id + '">' + values.fullname + '( ' + values.designations + ' )' + '</option>');
                                                        });
                                                        $('select[name="user_id"]').append('</optgroup>').trigger('change');
                                                    });
                                                }
                                            }
                                        });
                                        $.ajax({
                                            url: '<?= base_url('admin/global_controller/json_by_company/tbl_leave_category/') ?>' + companies_id,
                                            type: "GET",
                                            dataType: "json",
                                            success: function(data) {
                                                $('select[name="leave_category_id"]').find('option').not(':first').remove();
                                                $.each(data, function(key, value) {
                                                    $('select[name="leave_category_id"]').append('<option value="' + value.leave_category_id + '">' + value.leave_category + '</option>');
                                                });
                                            }
                                        });
                                    } else {
                                        $('select[name="user_id"]').empty();
                                        $('select[name="leave_category_id"]').empty();
                                    }
                                });
                            });
                        </script>
                    <?php } else { ?>
                        <input type="hidden" id="user_id" value="<?php echo $this->session->userdata('user_id') ?>">
                    <?php } ?>
                    <div class="row mb-3">
                        <label for="field-1" class="col-sm-3 col-form-label"><?= lang('leave_category') ?>
                            <span class="required"> *</span></label>

                        <div class="col-sm-8">
                            <div class="input-group">
                                <select name="leave_category_id" style="width: 85%" class="form-control modal_select_box" id="leave_category" required>
                                    <option value=""><?= lang('select') . ' ' . lang('leave_category') ?></option>
                                    <?php
                                    $all_leave_category = by_company('tbl_leave_category', 'leave_category_id');
                                    if (!empty($all_leave_category)) {
                                        foreach ($all_leave_category as $v_category) : ?>
                                            <option value="<?php echo $v_category->leave_category_id ?>">
                                                <?php echo $v_category->leave_category ?></option>
                                    <?php endforeach;
                                    }
                                    ?>
                                </select>
                                <div class="input-group-text" id="inputGroupAddon05"
                                     title="<?= lang('new') ?>"
                                     data-bs-toggle="tooltip" data-bs-placement="top">
                                    <a target="_blank" href="<?= base_url() ?>admin/settings/leave_category"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3"></div>
                        <div class="col-sm-8">
                            <div class="required" id="username_result"></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><?= lang('duration') ?>
                            <span class="required"> *</span></label>
                        <div class="col-sm-8">
                            <div class="form-check form-radio-outline form-radio-primary mb-3">
                                <input type="radio" name="leave_type" value="single_day" checked="" id="single_day" class="form-check-input">
                                <label class="form-check-label" for="single_day"><?= lang('single_day') ?></label>
                            </div>
                            <div class="form-check form-radio-outline form-radio-primary mb-3">
                                <input type="radio" name="leave_type" value="multiple_days" id="multiple_days" class="form-check-input">
                                <label class="form-check-label" for="multiple_days"><?= lang('multiple_days') ?></label>
                            </div>
                            <div class="form-check form-radio-outline form-radio-primary mb-3">
                                <input type="radio" name="leave_type" value="hours"  id="hours" class="form-check-input">
                                <label class="form-check-label" for="hours"><?= lang('hours') ?></label>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3" id="single_day">
                        <label class="col-sm-3 col-form-label"><?= lang('start_date') ?>
                            <span class="required"> *</span></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" name="single_day_start_date" id="single_day_start_date" onchange="check_available_leave(this.value)" class="form-control datepicker" value="">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div id="multiple_days">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><?= lang('start_date') ?>
                                <span class="required"> *</span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" name="multiple_days_start_date" id="multiple_days_start_date" class="form-control datepicker" value="">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><?= lang('end_date') ?> <span class="required"> *</span></label>

                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" name="multiple_days_end_date" id="multiple_days_end_date" onchange="check_available_leave(this.value)" class="form-control datepicker" value="" data-format="dd-mm-yyyy">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3" id="hours">
                        <label class="col-sm-3 col-form-label"><?= lang('start_date') ?>
                            <span class="required"> *</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" name="hours_start_date" id="hours_start_date" onchange="check_available_leave(this.value)" class="form-control datepicker" value="" data-format="dd-mm-yyyy">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="row">
                                <label class="col-sm-4 col-form-label"><?= lang('hours') ?>
                                    <span class="required"> *</span></label>
                                <div class="col-sm-5 pr0">
                                    <select name="hours" class="form-control">
                                        <?php for ($i = 1; $i <= $office_hours; $i++) {
                                        ?>
                                            <option value="<?= $i ?>"><?php if ($office_hours <= 9) {
                                                                            echo '0';
                                                                        }
                                                                        echo $i ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="field-1" class="col-sm-3 col-form-label"><?= lang('reason') ?></label>
                        <div class="col-sm-8"><textarea id="present" name="reason" class="form-control" rows="6"></textarea></div>
                    </div>

                    <?= custom_form_Fields(17, null); ?>

                    <div class="row mb-3" style="margin-bottom: 0px">
                        <label for="field-1" class="col-sm-3 col-form-label"><?= lang('attachment') ?></label>
                        <div class="col-sm-8">
                            <div id="file-dropzone" class="dropzone mb15">
                            </div>
                            <div data-simplebar style="max-height: 280px;">  
                                <div id="file-dropzone-scrollbar">
                                    <div id="file-previews" class="row">
                                        <div id="file-upload-row" class="col-sm-6 mt file-upload-row">
                                            <div class="preview box-content pr-lg" style="width:100px;">
                                                <img data-dz-thumbnail class="upload-thumbnail-sm"/>
                                                <div class="mb progress progress-striped upload-progress-sm active mt-sm"
                                                     role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                                     aria-valuenow="0">
                                                    <div class="progress-bar progress-bar-success" style="width:0%;"
                                                         data-dz-uploadprogress></div>
                                                </div>
                                            </div>
                                            <div class="box-content">
                                                <p class="clearfix mb0 p0">
                                                    <span class="name pull-left" data-dz-name></span>
                                            <span data-dz-remove class="pull-right" style="cursor: pointer">
                                            <i class="fa fa-times"></i>
                                        </span>
                                                </p>
                                                <p class="clearfix mb0 p0">
                                                    <span class="size" data-dz-size></span>
                                                </p>
                                                <strong class="error text-danger" data-dz-errormessage></strong>
                                                <input class="file-count-field" type="hidden" name="files[]" value=""/>
                                                <div class="row mb-3">
                                                    <div class="col-sm-5">
                                                        <textarea class="form-control description-field" type="text" style="cursor: auto;" placeholder="<?php echo lang("comments") ?>"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4" id="leave_details">
                <div class="panel panel-custom">
                    <!-- Default panel contents -->
                    <div class="panel-heading">
                        <div class="panel-title">
                            <strong><?= lang('my_leave') . ' ' . lang('details') ?></strong>
                        </div>
                    </div>
                    <table class="table">
                        <tbody>
                            <?php
                            $total_taken = 0;
                            $total_quota = 0;
                            $leave_report = leave_report($this->session->userdata('user_id'));
                        
                            if (!empty($leave_report['leave_category'])) {
                                foreach ($leave_report['leave_category'] as $lkey => $v_l_report) {
                                    $total_quota += $leave_report['leave_quota'][$lkey];
                                    $total_taken += $leave_report['leave_taken'][$lkey];
                            ?>
                                    <tr>
                                        <td><strong> <?= $leave_report['leave_category'][$lkey] ?></strong>:</td>
                                        <td>
                                            <?= leave_days_hours($leave_report['leave_taken'][$lkey]) ?>
                                            /<?= $leave_report['leave_quota'][$lkey]; ?> </td>
                                    </tr>
                            <?php }
                            }
                            ?>
                            <tr>
                                <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;">
                                    <strong> <?= lang('total') ?></strong>:
                                </td>
                                <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;"> <?= leave_days_hours($total_taken); ?>
                                    /<?= $total_quota; ?> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" id="file-save-button" name="sbtn" value="1" class="btn btn-primary w-md waves-effect waves-light">Submit </button>           
        </div>
    </div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $('body').on('hidden.bs.modal', '.modal', function() {
        location.reload();
    });
    $(document).ready(function() {
        $('div#single_day').show();
        $('div#multiple_days').hide();
        $('div#hours').hide();
        $('input[name="leave_type"]').click(function() {
            var leave_type = this.value;
            if (leave_type == 'single_day') {
                $('div#single_day').show().attr('required', true);
                $('div#multiple_days').hide().removeAttr('required');
                $('div#hours').hide().removeAttr('required');
            } else if (leave_type == 'multiple_days') {
                $('div#multiple_days').show().attr('required', true);
                $('div#single_day').hide().removeAttr('required');
                $('div#hours').hide().removeAttr('required');
            } else if (leave_type == 'hours') {
                $('div#hours').show().attr('required', true);
                $('div#single_day').hide().removeAttr('required');
                $('div#multiple_days').hide().removeAttr('required');
            } else {
                $('div#single_day').show().attr('required', true);
                $('div#multiple_days').hide().removeAttr('required');
                $('div#hours').hide().removeAttr('required');
            }
        });
        $('#leave_category').on('change', function() {
            $('#single_day_start_date').val('');
            $('#multiple_days_start_date').val('');
            $('#multiple_days_end_date').val('');
            $('#hours_start_date').val('');
        });
        <?php if ($this->session->userdata('user_type') == 1) { ?>
            $('#users_id').on('change', function() {
                $('#single_day_start_date').val('');
                $('#multiple_days_start_date').val('');
                $('#multiple_days_end_date').val('');
                $('#hours_start_date').val('');
            });
        <?php } ?>
        $('#multiple_days_start_date').on('change', function() {
            $('#multiple_days_end_date').val('');
        });
        $(".datepicker").attr("autocomplete", "off");
       
    });
</script>