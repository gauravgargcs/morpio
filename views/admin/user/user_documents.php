 <form data-parsley-validate="" novalidate="" enctype="multipart/form-data" action="<?php echo base_url() ?>admin/user/update_documents/<?php if (!empty($profile_info->account_details_id)) echo $profile_info->account_details_id; ?>" method="post" class="form-horizontal">
    <div class="modal-header">
        <h5 class="modal-title"><?= lang('user_documents') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="row">
            <!-- CV Upload -->
            <div class="row mb-3">
                <label for="field-1" class="col-sm-4 col-form-label"><?= lang('resume') ?></label>
                <input type="hidden" name="resume_path" value="<?php
                if (!empty($document_info->resume_path)) {
                    echo $document_info->resume_path;
                }
                ?>">
                <input type="hidden" name="document_id" value="<?php
                if (!empty($document_info->document_id)) {
                    echo $document_info->document_id;
                }
                ?>">
                <div class="col-sm-8">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <?php if (!empty($document_info->resume)): ?>
                            <span class="btn btn-default btn-file">
                                <input type="hidden" name="resume" value="<?php echo $document_info->resume ?>">
                                <input type="file" name="resume" class="form-control">
                            </span>
                            <span class="fileinput-filename ml"><a href="<?php echo base_url() . $document_info->resume; ?>" target="_blank" style="text-decoration: underline;"><?php echo $document_info->resume_filename ?></a></span>
                        <?php else: ?>
                            <span class="btn btn-default btn-file">
                                <input type="file" name="resume" class="form-control">
                            </span>
                            <span class="fileinput-filename ml"></span>
                        <?php endif; ?>
                    </div>
                    <div id="msg_pdf" style="color: #e11221"></div>
                </div>
            </div>

            <!-- Offer Letter Upload -->
            <div class="row mb-3">
                <label for="field-1" class="col-sm-4 col-form-label"><?= lang('offer_latter') ?></label>
                <input type="hidden" name="offer_letter_path" value="<?php
                if (!empty($document_info->offer_letter_path)) {
                    echo $document_info->offer_letter_path;
                }
                ?>">
                <div class="col-sm-8">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <?php if (!empty($document_info->offer_letter)): ?>
                            <span class="btn btn-default btn-file">
                                <input type="hidden" name="offer_letter" value="<?php echo $document_info->offer_letter ?>">
                                <input type="file" name="offer_letter" class="form-control">
                            </span>
                            <span class="fileinput-filename ml"> <a href="<?php echo base_url() . $document_info->offer_letter; ?>" target="_blank" style="text-decoration: underline;"><?php echo $document_info->offer_letter_filename ?></a></span>
                        <?php else: ?>
                            <span class="btn btn-default btn-file">
                                <input type="file" name="offer_letter" class="form-control">
                            </span>
                            <span class="fileinput-filename ml"></span>
                        <?php endif; ?>
                    </div>
                    <div id="msg_pdf" style="color: #e11221"></div>
                </div>
            </div>

            <!-- Joining Letter Upload -->
            <div class="row mb-3">
                <label for="field-1" class="col-sm-4 col-form-label"><?= lang('joining_latter') ?></label>
                <input type="hidden" name="joining_letter_path" value="<?php
                if (!empty($document_info->joining_letter_path)) {
                    echo $document_info->joining_letter_path;
                }
                ?>">
                <div class="col-sm-8">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <?php if (!empty($document_info->joining_letter)): ?>
                            <span class="btn btn-default btn-file">
                                <input type="hidden" name="joining_letter" value="<?php echo $document_info->joining_letter ?>">
                                <input type="file" name="joining_letter" class="form-control">
                            </span>
                            <span class="fileinput-filename ml"> <a href="<?php echo base_url() . $document_info->joining_letter; ?>" target="_blank" style="text-decoration: underline;"><?php echo $document_info->joining_letter_filename ?></a></span>
                        <?php else: ?>
                            <span class="btn btn-default btn-file">
                                <input type="file" name="joining_letter" class="form-control">
                            </span>
                            <span class="fileinput-filename"></span>
                        <?php endif; ?>
                    </div>
                    <div id="msg_pdf" style="color: #e11221"></div>
                </div>
            </div>

            <!-- Contract Paper Upload -->
            <div class="row mb-3">
                <label for="field-1" class="col-sm-4 col-form-label"><?= lang('contract_paper') ?></label>
                <input type="hidden" name="contract_paper_path" value="<?php
                if (!empty($document_info->contract_paper_path)) {
                    echo $document_info->contract_paper_path;
                }
                ?>">
                <div class="col-sm-8">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <?php if (!empty($document_info->contract_paper)): ?>
                            <span class="btn btn-default btn-file">
                                <input type="hidden" name="contract_paper" value="<?php echo $document_info->contract_paper ?>">
                                <input type="file" name="contract_paper" class="form-control">
                            </span>
                            <span class="fileinput-filename ml"> <a href="<?php echo base_url() . $document_info->contract_paper; ?>" target="_blank" style="text-decoration: underline;"><?php echo $document_info->contract_paper_filename ?></a></span>
                        <?php else: ?>
                            <span class="btn btn-default btn-file">
                                <input type="file" name="contract_paper" class="form-control">
                            </span>
                            <span class="fileinput-filename"></span>
                        <?php endif; ?>
                    </div>
                    <div id="msg_pdf" style="color: #e11221"></div>
                </div>
            </div>

            <!-- ID / Proff Upload -->
            <div class="row mb-3">
                <label for="field-1" class="col-sm-4 col-form-label"><?= lang('id_prof') ?></label>
                <input type="hidden" name="id_proff_path" value="<?php
                if (!empty($document_info->id_proff_path)) {
                    echo $document_info->id_proff_path;
                }
                ?>">
                <div class="col-sm-8">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <?php if (!empty($document_info->id_proff)): ?>
                            <span class="btn btn-default btn-file">
                                <input type="hidden" name="id_proff"  value="<?php echo $document_info->id_proff ?>">
                                <input type="file" name="id_proff" class="form-control">
                            </span>
                            <span class="fileinput-filename ml"> <a href="<?php echo base_url() . $document_info->id_proff; ?>" target="_blank" style="text-decoration: underline;"><?php echo $document_info->id_proff_filename ?></a></span>
                        <?php else: ?>
                            <span class="btn btn-default btn-file">
                                <input type="file" name="id_proff" class="form-control">
                            </span>
                            <span class="fileinput-filename"></span>
                        <?php endif; ?>

                    </div>
                    <div id="msg_pdf" style="color: #e11221"></div>
                </div>
            </div>

            <!-- Medical Upload -->
            <div class="row mb-3" style="margin-bottom: 0px" id="add_new">
                <label for="field-1"  class="col-sm-4 col-form-label"><?= lang('other_document') ?></label>
                <div class="col-sm-8">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <?php
                        if (!empty($document_info->other_document)) {
                            $uploaded_file = json_decode($document_info->other_document);
                        }
                        if (!empty($uploaded_file)):foreach ($uploaded_file as $v_files_image): ?>
                            <div class="">
                                <input type="hidden" name="path[]"
                                       value="<?php echo $v_files_image->path ?>">
                                <input type="hidden" name="fileName[]"
                                       value="<?php echo $v_files_image->fileName ?>">
                                <input type="hidden" name="fullPath[]"
                                       value="<?php echo $v_files_image->fullPath ?>">
                                <input type="hidden" name="size[]"
                                       value="<?php echo $v_files_image->size ?>">
                                <input type="hidden" name="is_image[]"
                                       value="<?php echo $v_files_image->is_image ?>">
                                <span class=" btn btn-default btn-file">
                                <span class="fileinput-filename ml"> <a href="<?php echo base_url() . $v_files_image->path; ?>" target="_blank" style="text-decoration: underline;"><?php echo $v_files_image->fileName ?></a></span>
                                </span>
                                <strong>
                                    <a href="javascript:void(0);" class="RCF"><i class="fa fa-times"></i>&nbsp;Remove</a></strong>
                                <p></p>
                            </div>

                        <?php endforeach; ?>
                        <?php else: ?>
                            <span class="btn btn-default btn-file">
                                <input type="file" name="other_document[]" class="form-control">
                            </span>
                            <span class="fileinput-filename"></span>
                        <?php endif; ?>
                    </div>
                    <div id="msg_pdf" style="color: #e11221"></div>
                    <a href="javascript:void(0);" id="add_more" class="addCF ml" title="<?= lang('add_more') ?>"><i class="fa fa-plus"></i></a>

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

<script type="text/javascript">
    $(document).ready(function () {
        var maxAppend = 0;
        $("#add_more").click(function () {

            var add_new = $('<div class="row mb-3" style="margin-bottom: 0px">\n\
                    <label for="field-1" class="col-sm-4 col-form-label"><?= lang('other_document') ?></label>\n\<div class="col-sm-8">\n\<div class="fileinput fileinput-new" data-provides="fileinput">\n\ <span class="btn btn-default btn-file"><input type="file" name="other_document[]" class="form-control"></span> <span class="fileinput-filename"></span></div>\n\<strong>\n\<a href="javascript:void(0);" class="remCF ml"><i class="fa fa-times"></i>&nbsp;<?= lang('remove')?></a></strong></div>');
            maxAppend++;
            $("#add_new").append(add_new);

        });

        $("#add_new").on('click', '.remCF', function () {
            $(this).parent().parent().parent().remove();
        });
        $('a.RCF').click(function () {
            $(this).parent().parent().remove();
        });
    });
</script>
