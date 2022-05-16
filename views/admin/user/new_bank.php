<form data-parsley-validate="" novalidate="" enctype="multipart/form-data" action="<?php echo base_url() ?>admin/user/update_bank_info/<?php echo $profile_info->account_details_id; ?>/<?php if (!empty($bank_info->employee_bank_id)) {  echo $bank_info->employee_bank_id; }  ?>" method="post" class="form-horizontal">
    <div class="modal-header">
        <h5 class="modal-title"><?= lang('new_bank') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="row">
            <div class="col-lg-12">
                <!-- CV Upload -->
                <div class="row mb-3">
                    <label class="col-sm-4 form-label"><?= lang('bank') . ' ' . lang('name') ?> <span class="text-danger">*</span></label>
                    <div class="col-sm-7">
                        <input required type="text" name="bank_name" value="<?php
                        if (!empty($bank_info->bank_name)) {
                            echo $bank_info->bank_name;
                        }
                        ?>" class="form-control">
                        <input type="hidden" required name="employee_bank_id" value="<?php
                        if (!empty($bank_info->employee_bank_id)) {
                            echo $bank_info->employee_bank_id;
                        }
                        ?>" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 form-label"><?= lang('routing_number') ?><span
                            class="text-danger">*</span></label>
                    <div class="col-sm-7">
                        <input type="text" required name="routing_number" value="<?php
                        if (!empty($bank_info->routing_number)) {
                            echo $bank_info->routing_number;
                        }
                        ?>" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-4 form-label"><?= lang('name_of_account') ?><span class="text-danger">*</span></label>
                    <div class="col-sm-7">
                        <input required type="text" name="account_name" value="<?php
                        if (!empty($bank_info->account_name)) {
                            echo $bank_info->account_name;
                        }
                        ?>" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 form-label"><?= lang('account_number') ?><span class="text-danger">*</span></label>
                    <div class="col-sm-7">
                        <input required type="text" name="account_number" value="<?php
                        if (!empty($bank_info->account_number)) {
                            echo $bank_info->account_number;
                        }
                        ?>" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 form-label"><?= lang('type_of_account') ?></label>
                    <div class="col-sm-7">
                        <div class="form-check form-check-primary mb-3">
                            <input type="checkbox" value="checking" <?php
                                if (!empty($bank_info->type_of_account) && $bank_info->type_of_account == 'checking') {
                                    echo 'checked';
                                }
                                ?> class="custom-checkbox select_one form-check-input" name="type_of_account" id="checking">
                            <label class="form-check-label" for="checking"><?= lang('checking') ?></label>
                        </div>
                        <div class="form-check form-check-primary mb-3">
                            <input type="checkbox" value="savings" <?php
                                if (!empty($bank_info->type_of_account) && $bank_info->type_of_account == 'savings') {
                                    echo 'checked';
                                }
                                ?> class="custom-checkbox select_one form-check-input" name="type_of_account" id="savings">
                            <label class="form-check-label" for="savings"><?= lang('savings') ?></label>
                        </div>
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
    