<div class="modal-header">
    <h5 class="modal-title"><?= lang('view_circular_details') ?></h5>
    <div class="pull-right ml">
        <?= btn_pdf('admin/job_circular/jobs_posted_pdf/' . $job_posted->job_circular_id) ?>
    </div>    
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body wrap-modal wrap">
    <div class="row form-horizontal">
        <div class="col-md-6">
            <?php super_admin_details($job_posted->companies_id, 5, 6) ?>
            <div class="row mb-3 notice-details-margin">
                <div class="col-sm-6 text-right">
                    <label class="form-label"><strong><?= lang('job_title') ?> :</strong></label>
                </div>
                <div class="col-sm-6">
                    <p class="form-control-static"><?= $job_posted->job_title; ?></p>
                </div>
            </div>
            <div class="row mb-3 notice-details-margin">
                <div class="col-sm-6 text-right">
                    <label class="form-label"><strong><?= lang('designation') ?> :</strong></label>
                </div>
                <div class="col-sm-6">
                    <p class="form-control-static"><?php
                        if (!empty($v_job_post->designations_id)) {
                            $design_info = $this->db->where('designations_id', $job_posted->designations_id)->get('tbl_designations')->row();
                            $designation = $design_info->designations;
                        } else {
                            $designation = '-';
                        }
                        echo $designation;
                        ?></p>
                </div>
            </div>


            <div class="row mb-3 notice-details-margin">
                <div class="col-sm-6 text-right">
                    <label class="form-label"><strong><?= lang('employment_type') ?> :</strong></label>
                </div>
                <div class="col-sm-6">
                    <p class="form-control-static"><?= lang($job_posted->employment_type); ?></p>
                </div>
            </div>
            <div class="row mb-3 notice-details-margin">
                <div class="col-sm-6 text-right">
                    <label class="form-label"><strong><?= lang('experience') ?> :</strong></label>
                </div>
                <div class="col-sm-6">
                    <p class="form-control-static"><?= $job_posted->experience; ?></p>
                </div>
            </div>
           
            <div class="row mb-3 notice-details-margin">
                <div class="col-sm-6 text-right">
                    <label class="form-label"><strong><?= lang('salary_range') ?> :</strong></label>
                </div>
                <div class="col-sm-6">
                    <p class="form-control-static"><?= $job_posted->salary_range; ?></p>
                </div>
            </div>
            <div class="row mb-3 notice-details-margin">
                <div class="col-sm-6 text-right">
                    <label class="form-label"><strong><?= lang('vacancy_no') ?> :</strong></label>
                </div>
                <div class="col-sm-6">
                    <p class="form-control-static"><?= $job_posted->vacancy_no; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row mb-3 notice-details-margin">
                <div class="col-sm-6 text-right">
                    <label class="form-label"><strong><?= lang('posted_date') ?> :</strong></label>
                </div>
                <div class="col-sm-6">
                    <p class="form-control-static"><?= display_datetime($job_posted->posted_date) ?></p>
                </div>
            </div>
            <div class="row mb-3 notice-details-margin">
                <div class="col-sm-6 text-right">
                    <label class="form-label"><strong><?= lang('last_date_to_apply') ?> :</strong></label>
                </div>
                <div class="col-sm-6">
                    <p class="form-control-static"><?= display_datetime($job_posted->last_date) ?></p>
                </div>
            </div>
            <?php $show_custom_fields = custom_form_label(14, $job_posted->job_circular_id);

            if (!empty($show_custom_fields)) {
                foreach ($show_custom_fields as $c_label => $v_fields) {
                    if (!empty($v_fields)) {
                        ?>
                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-6 text-right">
                                <label class="form-label"><strong><?= $c_label ?> :</strong></label>
                            </div>
                            <div class="col-sm-6">
                                <p class="form-control-static"><?= $v_fields ?></p>
                            </div>
                        </div>
                    <?php }
                }
            }
            ?>
            <div class="row mb-3 notice-details-margin">
                <div class="col-sm-6 text-right">
                    <label class="form-label"><strong><?= lang('status') ?> :</strong></label>
                </div>
                <div class="col-sm-6">
                    <p class="form-control-static"><?php
                        if ($job_posted->status == 'unpublished') : ?>
                            <span class="label label-danger"><?= lang('unpublished') ?></span>
                        <?php else : ?>
                            <span class="label label-success"><?= lang('published') ?></span>
                        <?php endif; ?></p>
                </div>
            </div>

            <div class="row mb-3 notice-details-margin">
                <div class="col-sm-6 text-right">
                    <label class="form-label"><strong><?= lang('description') ?> :</strong></label>
                </div>
                <div class="col-sm-6">
                    <blockquote style="font-size: 12px"><?php echo $job_posted->description; ?></blockquote>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="mb-3">
        <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
    </div>
</div>