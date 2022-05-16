<div class="modal-header">
    <h5 class="modal-title"><?= lang('clone') . ' ' . lang('estimate') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form role="form" id="from_items"  data-parsley-validate="" novalidate="" action="<?php echo base_url(); ?>admin/estimates/cloned_estimate/<?= $estimate_info->estimates_id ?>" method="post" class="form-horizontal">
<div class="modal-body wrap-modal wrap">
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label"><?= lang('select') . ' ' . lang('client') ?> <span
                class="text-danger">*</span>
        </label>
        <div class="col-lg-7">
            <select class="form-control modal_select_box" style="width: 100%" name="client_id" required>
                <?php
                if (!empty($all_client)) {
                    foreach ($all_client as $v_client) {
                        ?>
                        <option value="<?= $v_client->client_id ?>"
                            <?php
                            if (!empty($estimate_info)) {
                                $estimate_info->client_id == $v_client->client_id ? 'selected' : '';
                            }
                            ?>
                        ><?= ucfirst($v_client->name) ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <label
            class="col-lg-3 col-form-label"><?= lang('estimate_date') ?></label>
        <div class="col-lg-7">
            <div class="input-group">
                <input required type="text" name="estimate_date"
                       class="form-control datepicker"
                       value="<?php
                       if (!empty($estimate_info->estimate_date)) {
                           echo date('d-m-Y H:i', strtotime($estimate_info->estimate_date));
                       } else {
                           echo date('d-m-Y H:i');
                       }
                       ?>"
                       data-date-format="<?= config_item('date_picker_format'); ?>">
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label"><?= lang('due_date') ?></label>
        <div class="col-lg-7">
            <div class="input-group">
                <input required type="text" name="due_date"
                       class="form-control datepicker"
                       value="<?php
                       if (!empty($estimate_info->due_date)) {
                           echo date('d-m-Y H:i', strtotime($estimate_info->due_date));
                       } else {
                           echo date('d-m-Y H-i');
                       }
                       ?>"
                       data-date-format="<?= config_item('date_picker_format'); ?>">   
                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
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
</form>