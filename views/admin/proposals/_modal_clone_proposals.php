<div class="modal-header">
    <h5 class="modal-title"><?= lang('clone') . ' ' . lang('proposal') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form role="form" data-parsley-validate="" novalidate="" action="<?php echo base_url(); ?>admin/proposals/cloned_proposals/<?= $proposals_info->proposals_id ?>" method="post" class="form-horizontal">
    <div class="modal-body wrap-modal wrap">
        <div class="row mb-3" id="border-none">
            <label class="col-sm-5 col-form-label"><?= lang('related_to') ?> </label>
            <div class="col-sm-7">
                <select name="module" class="form-control modal_select_box"
                        id="check_related" required
                        onchange="get_related_moduleName(this.value,true,true)" style="width: 100%">
                    <option> <?= lang('none') ?> </option>
                    <option
                        value="leads" <?= (!empty($leads_id) ? 'selected' : '') ?>> <?= lang('leads') ?> </option>
                    <option
                        value="client" <?= (!empty($client_id) ? 'selected' : '') ?>> <?= lang('client') ?> </option>
                </select>
            </div>
        </div>
        <div class="" id="related_to"> </div>
        <div class="row mb-3">
            <label
                class="col-lg-5 col-form-label"><?= lang('proposal_date') ?></label>
            <div class="col-lg-7">
                <div class="input-group">
                    <input type="text" name="proposal_date"
                           class="form-control datepicker"
                           value="<?php
                           if (!empty($proposals_info->proposal_date)) {
                               echo date('d-m-Y H-i', strtotime($proposals_info->proposal_date));
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
            <label class="col-lg-5 col-form-label"><?= lang('expire_date') ?></label>
            <div class="col-lg-7">
                <div class="input-group">
                    <input type="text" name="due_date"
                           class="form-control datepicker"
                           value="<?php
                           if (!empty($proposals_info->due_date)) {
                               echo date('d-m-Y H-i', strtotime($proposals_info->due_date));
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