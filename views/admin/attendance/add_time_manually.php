<div class="modal-header">
    <h5 class="modal-title"><?= lang('add_time_manually') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php echo form_open(base_url('admin/attendance/saved_manual_time/'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
<div class="modal-body wrap-modal wrap">    
        <?php

        $check_head = $this->db->where('department_head_id', $this->session->userdata('user_id'))->get('tbl_departments')->row();

        $role = $this->session->userdata('user_type');

        if ($role == 1 || !empty($check_head)) { $companies_id = null; ?>

            <?php super_admin_form_modal($companies_id, 3, 9) ?>

            <div class="row mb-3" id="border-none">

                <label for="field-1" class="col-sm-3 col-form-label"><?= lang('employee') ?> <span

                            class="required">*</span></label>

                <div class="col-sm-9">

                    <select name="user_id" style="width: 100%" id="employee"

                            class="form-control modal_select_box">

                        <option value="0"><?= lang('select_employee') ?>...</option>

                        <?php if (!empty($all_employee)): ?>

                            <?php foreach ($all_employee as $dept_name => $v_all_employee) : ?>

                                <optgroup label="<?php echo $dept_name; ?>">

                                    <?php if (!empty($v_all_employee)):foreach ($v_all_employee as $v_employee) : ?>

                                        <option value="<?php echo $v_employee->user_id; ?>"

                                            <?php

                                            if (!empty($user_id)) {

                                                $user_id = $user_id;

                                            } else {

                                                $user_id = $this->session->userdata('user_id');

                                            }

                                            if (!empty($user_id)) {

                                                echo $v_employee->user_id == $user_id ? 'selected' : '';

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

                                        $('select[name="user_id"]').find('optgroup').remove();

                                        $('select[name="user_id"]').find('option').not(':first').remove();

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

                            $('select[name="user_id"]').find('optgroup').remove();

                            $('select[name="user_id"]').find('option').not(':first').remove();

                        }

                    })

                    ;

                })

                ;

            </script>

        <?php } else { ?>

        <input type="hidden" name="user_id" class="form-control"

               value="<?= $this->session->userdata('user_id') ?>">

        <?php } ?>

        <div class="row mb-3" id="border-none">

            <label class="col-form-label col-sm-3"><?= lang('date_in') ?> </label>

            <div class="col-sm-9">

                <div class="input-group">

                    <input type="text" name="date_in" class="form-control datepicker"

                           value="<?= date('d-m-Y H-i') ?>" required>

                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        

                </div>

            </div>

        </div>

        <div class="row mb-3" id="border-none">

            <label class="col-form-label col-sm-3"><?= lang('day_out') ?> </label>

            <div class="col-sm-9">

                <div class="input-group">

                    <input type="text" name="date_out" class="form-control datepicker"

                           value="<?= date('d-m-Y H-i') ?>" required>

                   <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        

                </div>

            </div>

        </div>
    </div>

    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('update') ?></button>            
        </div>
    </div>
<?php echo form_close(); ?>