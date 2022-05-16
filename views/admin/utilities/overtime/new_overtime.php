<?php include_once 'asset/admin-ajax.php'; ?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= lang('new') . ' ' . lang('overtime') ?></h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <?php
        if (!empty($overtime_info)) {
            $overtime_id = $overtime_info->overtime_id;
            $companies_id = $overtime_info->companies_id;
        } else {
            $overtime_id = null;
            $companies_id = null;
        }
        echo form_open(base_url('admin/utilities/save_overtime/' . $overtime_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
        <?php if ($this->session->userdata('user_type') == 1) { ?>
            <?php super_admin_form_modal($companies_id, 3, 7) ?>
            <div class="form-group" id="border-none">
                <label for="field-1" class="col-sm-3 control-label"><?= lang('employee') ?> <span
                            class="required">*</span></label>
                <div class="col-sm-7">
                    <select name="user_id" style="width: 100%" id="employee" class="form-control select_box"
                            required>
                        <option value="0"><?= lang('select_employee') ?>...</option>
                        <?php
                        $all_employee = $this->utilities_model->get_all_employee($companies_id);
                        if (!empty($all_employee)): ?>
                            <?php foreach ($all_employee as $dept_name => $v_all_employee) : ?>
                                <optgroup label="<?php echo $dept_name; ?>">
                                    <?php if (!empty($v_all_employee)):foreach ($v_all_employee as $v_employee) : ?>
                                        <option value="<?php echo $v_employee->user_id; ?>"
                                            <?php
                                            if (!empty($overtime_info->user_id)) {
                                                echo $v_employee->user_id == $overtime_info->user_id ? 'selected' : '';
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
            <script type="text/javascript">
                $(document).ready(function () {
                    $('select[name="companies_id"]').on('change', function () {
                        var companies_id = $(this).val();
                        if (companies_id) {
                            $.ajax({
                                    url: '<?= base_url('admin/global_controller/json_get_employee/')?>' + companies_id,
                                    type: "GET",
                                    dataType: "json",
                                    success: function (data) {
                                        $('select[name="user_id"]').empty();
                                        if (data) {
                                            $.each(data, function (key, value) {
                                                $('select[name="user_id"]').append('<optgroup label="' + key + '">');
                                                $.each(value, function (keys, values) {
                                                    $('select[name="user_id"]').append('<option value="' + values.user_id + '">' + values.fullname + '( ' + values.designations + ' )' + '</option>');
                                                });
                                                $('select[name="user_id"]').append('</optgroup>');
                                            });
                                        }
                                    }
                                }
                            );
                        } else {
                            $('select[name="user_id"]').empty();
                        }
                    })
                    ;
                })
                ;
            </script>
        <?php } ?>
        <div class="form-group">
            <label for="field-1" class="col-sm-3 control-label"><?= lang('date') ?> <span
                        class="required"> *</span></label>
            <div class="col-sm-7">
                <div class="input-group ">
                    <input class="form-control only_datepicker" required value="<?php
                    if (!empty($overtime_info->overtime_date)) {
                        echo date('d-m-Y H-i', strtotime($overtime_info->overtime_date));
                    } else {
                        echo date('d-m-Y H-i');
                    }
                    ?>" name="overtime_date" type="text">
                    <div class="input-group-addon">
                        <a href="#"><i class="fa fa-calendar"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="field-1" class="col-sm-3 control-label"><?= lang('overtime_hour') ?> <span
                        class="required"> *</span></label>
            <div class="col-sm-7">
                <div class="input-group ">
                    <input class="form-control timepicker2" required value="<?php
                    if (!empty($overtime_info->overtime_hours)) {
                        echo $overtime_info->overtime_hours;
                    }
                    ?>" name="overtime_hours" type="text">
                    <div class="input-group-addon">
                        <a href="#"><i class="fa fa-clock-o"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="field-1" class="col-sm-3 control-label"><?= lang('notes') ?></label>

            <div class="col-sm-7">
                <textarea name="notes" class="form-control" id="field-1"
                          placeholder="<?= lang('enter_your') . ' ' . lang('notes') ?>"><?php
                    if (!empty($overtime_info->notes)) {
                        echo $overtime_info->notes;
                    }
                    ?></textarea>
            </div>
        </div>

        <div class="form-group margin">
            <label for="field-1" class="col-sm-3 control-label"></label>
            <div class="col-sm-3">
                <button id="submit" type="submit" name="sbtn" value="1"
                        class="btn btn-primary btn-block"><?= lang('save') ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.only_datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayBtn: "linked",
        });
    });
</script>