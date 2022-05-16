<form data-parsley-validate="" novalidate=""  action="<?php echo base_url() ?>admin/user/update_details/<?php if (!empty($profile_info->account_details_id)) echo $profile_info->account_details_id; ?>" method="post" class="form-horizontal">
    <div class="modal-header">
        <h5 class="modal-title"><?= lang('user') . ' ' . lang('details') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label class="col-sm-5 form-label"><?= lang('joining_date') ?> <span  class="required">*</span></label>

                    <div class="col-sm-7">
                        <div class="input-group">
                            <input type="text" name="joining_date" required
                                   placeholder="<?= lang('enter') . ' ' . lang('joining_date') ?>"
                                   class="form-control datepicker" value="<?php
                            if (!empty($profile_info->joining_date)) {
                                echo date('d-m-Y H-i', strtotime($profile_info->joining_date));
                            } else {
                                echo date('d-m-Y H-i');
                            }
                            ?>">
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="field-1" class="col-sm-5 form-label"><?= lang('gender') ?>
                        <span class="required">*</span></label>
                    <div class="col-sm-7">
                        <select name="gender" class="form-control" required>
                            <option
                                    value="male" <?= (!empty($profile_info->gender) && $profile_info->gender == 'male' ? 'selected' : null) ?>><?= lang('male') ?></option>
                            <option
                                    value="female" <?= (!empty($profile_info->gender) && $profile_info->gender == 'female' ? 'selected' : null) ?>><?= lang('female') ?></option>

                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-5 form-label"><?= lang('date_of_birth') ?> <span
                                class="required">*</span></label>

                    <div class="col-sm-7">
                        <div class="input-group">
                            <input type="text" name="date_of_birth"
                                   placeholder="<?= lang('enter') . ' ' . lang('date_of_birth') ?>"
                                   class="form-control datepicker" required value="<?php
                            if (!empty($profile_info->date_of_birth)) {
                                echo date('d-m-Y H-i', strtotime($profile_info->date_of_birth));
                            } else {
                                echo date('d-m-Y H-i');
                            }
                            ?>">
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="field-1" class="col-sm-5 form-label"><?= lang('maratial_status') ?>
                        <span class="required">*</span></label>

                    <div class="col-sm-7">
                        <select name="maratial_status" class="form-control" required>
                            <option
                                    value="married" <?= (!empty($profile_info->maratial_status) && $profile_info->maratial_status == 'married' ? 'selected' : null) ?>><?= lang('married') ?></option>
                            <option
                                    value="unmarried" <?= (!empty($profile_info->maratial_status) && $profile_info->maratial_status == 'unmarried' ? 'selected' : null) ?>><?= lang('unmarried') ?></option>
                            <option
                                    value="widowed" <?= (!empty($profile_info->maratial_status) && $profile_info->maratial_status == 'widowed' ? 'selected' : null) ?>><?= lang('widowed') ?></option>
                            <option
                                    value="divorced" <?= (!empty($profile_info->maratial_status) && $profile_info->maratial_status == 'divorced' ? 'selected' : null) ?>><?= lang('divorced') ?></option>

                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-5 form-label"><?= lang('fathers_name') ?> <span
                                class="required">*</span></label>

                    <div class="col-sm-7">
                        <input type="text" name="father_name" required
                               placeholder="<?= lang('enter') . ' ' . lang('fathers_name') ?>"
                               class="form-control" value="<?php
                        if (!empty($profile_info->father_name)) {
                            echo $profile_info->father_name;
                        }
                        ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-5 form-label"><?= lang('mother_name') ?> <span
                                class="required">*</span></label>

                    <div class="col-sm-7">
                        <input type="text" name="mother_name" required
                               placeholder="<?= lang('enter') . ' ' . lang('mother_name') ?>"
                               class="form-control" value="<?php
                        if (!empty($profile_info->mother_name)) {
                            echo $profile_info->mother_name;
                        }
                        ?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label class="col-sm-5 form-label"><?= lang('phone') ?> </label>
                    <div class="col-sm-7">
                        <input type="text" class="input-sm form-control" value="<?php
                        if (!empty($profile_info)) {
                            echo $profile_info->phone;
                        }
                        ?>" name="phone" placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_phone') ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-5 form-label"><?= lang('mobile') ?> </label>
                    <div class="col-sm-7">
                        <input type="text" class="input-sm form-control" value="<?php
                        if (!empty($profile_info)) {
                            echo $profile_info->mobile;
                        }
                        ?>" name="mobile"
                               placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_mobile') ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-5 form-label"><?= lang('skype_id') ?> </label>
                    <div class="col-sm-7">
                        <input type="text" class="input-sm form-control" value="<?php
                        if (!empty($profile_info)) {
                            echo $profile_info->skype;
                        }
                        ?>" name="skype" placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_skype') ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-5 form-label"><?= lang('passport') ?> </label>
                    <div class="col-sm-7">
                        <input type="text" class="input-sm form-control" value="<?php
                        if (!empty($profile_info)) {
                            echo $profile_info->passport;
                        }
                        ?>" name="passport">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-5 form-label"><?= lang('present_address') ?> </label>
                    <div class="col-sm-7">
                            <textarea class="input-sm form-control" value="" name="present_address"><?php
                                if (!empty($profile_info)) {
                                    echo $profile_info->present_address;
                                }
                                ?></textarea>
                    </div>
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
</form>