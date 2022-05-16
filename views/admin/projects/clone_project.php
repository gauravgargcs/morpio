 <?php echo form_open(base_url('admin/projects/cloned_project/' . $project_info->project_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
        
    <div class="modal-header">
        <h5 class="modal-title"><?= lang('clone') . ' ' . lang('invoice') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="row mb-3">
            <label class="col-xl-3 col-form-label"><?= lang('select') . ' ' . lang('client') ?> <span class="text-danger">*</span>
            </label>
            <div class="col-xl-9">
                <select class="form-control modal_select_box" style="width: 100%" name="client_id" required>
                    <?php
                    if (!empty($all_client)) {
                        foreach ($all_client as $v_client) {
                            ?>
                            <option value="<?= $v_client->client_id ?>"
                                <?php
                                if (!empty($project_info)) {
                                    $project_info->client_id == $v_client->client_id ? 'selected' : '';
                                }
                                ?>
                            ><?= ($v_client->name) ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-xl-3 col-form-label"><?= lang('start_date') ?> <span
                        class="text-danger">*</span></label>
            <div class="col-xl-9">
                <div class="input-group" id="clone_datepicker1">
                    <input required type="text" id="start_date" name="start_date" class="form-control datepicker" autocomplete="off" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container="#clone_datepicker1"  value="<?php
                           if (!empty($project_info->start_date)) {
                               echo date('d-m-Y H-i', strtotime($project_info->start_date));
                           } else {
                               echo date('d-m-Y H-i');
                           }
                           ?>">
                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-xl-3 col-form-label"><?= lang('end_date') ?> <span
                        class="text-danger">*</span></label>
            <div class="col-xl-9">
                <div class="input-group" id="clone_datepicker2">
                    <input required type="text" id="end_date" name="end_date" class="form-control datepicker" autocomplete="off" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container="#clone_datepicker2"  data-rule-required="true" data-msg-greaterThanOrEqual="end_date_must_be_equal_or_greater_than_start_date" data-rule-greaterThanOrEqual="#start_date" value="<?php
                           if (!empty($project_info->start_date)) {
                               echo date('d-m-Y H-i', strtotime($project_info->start_date));
                           } else {
                               echo date('d-m-Y H-i');
                           }
                           ?>">
                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-xl-3 col-form-label"><?= lang('also_added') ?></label>
            <div class="col-xl-9">
                <div class="form-check form-check-primary mb-3">
                    <input name="milestones" value="1" <?php
                        if (!empty($milestone_info)) {
                            echo "checked=\"checked\"";
                        }
                        ?> type="checkbox" class="form-check-input" id="milestones">
                    <label class="form-check-label" for="milestones">
                        <?= lang('milestones') ?>
                    </label>
                </div>
                <div class="form-check form-check-primary mb-3">
                        <input name="tasks" value="1" <?php
                        if (!empty($task_info)) {
                            echo "checked=\"checked\"";
                        }
                        ?> type="checkbox" class="form-check-input" id="tasks">
                    <label class="form-check-label" for="tasks">
                        <?= lang('tasks') ?>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('clone') ?></button>            
        </div>
    </div>
<?php echo form_close(); ?>