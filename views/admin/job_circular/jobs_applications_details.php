<div class="modal-header">
    <h5 class="modal-title"><?= lang('jobs_application_details') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body wrap-modal wrap">
    <?php super_admin_details_p($job_application_info->companies_id, 4, 12) ?>
    <div class="col-md-12">
        <div class="col-sm-4 text-right">
            <label class="form-label"><strong><?= lang('job_title') ?> : </strong></label>
        </div>
        <div class="col-sm-8">
            <p class="form-control-static"><?php echo $job_application_info->job_title; ?></p>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-sm-4 text-right">
            <label class="form-label"><strong><?= lang('designation') ?> : </strong></label>
        </div>
        <div class="col-sm-8">
            <p class="form-control-static"><?php
                if (!empty($job_application_info->designations_id)) {
                    $design_info = $this->db->where('designations_id', $job_application_info->designations_id)->get('tbl_designations')->row();
                    $designation = $design_info->designations;
                } else {
                    $designation = '-';
                }
                echo $designation;
                ?></p>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-sm-4 text-right">
            <label class="form-label"><strong><?= lang('name') ?> : </strong></label>
        </div>
        <div class="col-sm-8">
            <p class="form-control-static"><?php echo $job_application_info->name ?></p>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-sm-4 text-right">
            <label class="form-label"><strong><?= lang('email') ?> : </strong></label>
        </div>
        <div class="col-sm-8">
            <p class="form-control-static"><?php echo $job_application_info->email ?></p>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-sm-4 text-right">
            <label class="form-label"><strong><?= lang('mobile') ?> : </strong></label>
        </div>
        <div class="col-sm-8">
            <p class="form-control-static"><?php echo $job_application_info->mobile ?></p>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-sm-4 text-right">
            <label class="form-label"><strong><?= lang('apply_date') ?> : </strong></label>
        </div>
        <div class="col-sm-8">
            <p class="form-control-static text-justify"><?= display_datetime($job_application_info->apply_date) ?></p>
        </div>
    </div>
    <?php if (!empty($job_application_info->send_email)) { ?>
        <div class="col-md-12">
            <div class="col-sm-4 text-right">
                <label class="form-label"><strong><?= lang('send_email') ?> : </strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static text-justify"><?= $job_application_info->send_email ?></p>
            </div>
        </div>
    <?php } ?>
    <?php if (!empty($job_application_info->interview_date)) { ?>
        <div class="col-md-12">
            <div class="col-sm-4 text-right">
                <label class="form-label"><strong><?= lang('interview_date') ?> : </strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static text-justify"><?= display_datetime($job_application_info->interview_date) ?></p>
            </div>
        </div>
    <?php } ?>
    <div class="col-md-12">
        <div class="col-sm-4 text-right">
            <label class="form-label"><strong><?= lang('resume') ?> : </strong></label>
        </div>
        <div class="col-sm-8">
            <p class="form-control-static pull-left">
                <a class="badge badge-soft-info"
                   href="<?php echo base_url(); ?>admin/job_circular/download_resume/<?= $job_application_info->job_appliactions_id ?>"
                   style="text-decoration: none;background: #22313F;"><?= lang('download') . ' ' . lang('resume') ?></a>
            </p>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="mb-3">
        <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
    </div>
</div>