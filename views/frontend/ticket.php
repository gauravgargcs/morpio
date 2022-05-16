<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4"><?= lang('new_ticket') ?></h4>
                <?php echo form_open(base_url('frontend/create_tickets/'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                <?php super_admin_form($companies_id, 3, 5) ?>
                <div class="form-group">
                    <label class="col-lg-3 col-md-3 col-sm-4 form-label"><?= lang('ticket_code') ?> <span class="text-danger">*</span></label>
                    <div class="col-lg-5 col-md-5 col-sm-5">
                        <input type="text" class="form-control" value="<?php $this->load->helper('string'); echo strtoupper(random_string('alnum', 7)); ?>" name="ticket_code">
                    </div>
                </div>
                <input type="hidden" value="open" class="form-control" name="status">


                <div class="form-group">
                    <label class="col-lg-3 col-md-3 col-sm-4 form-label"><?= lang('subject') ?> <span class="text-danger">*</span></label>
                    <div class="col-lg-5 col-md-5 col-sm-5">
                        <input type="text" value="" class="form-control" placeholder="Sample Ticket Subject" name="subject" required>
                    </div>
                </div>

                <script type="text/javascript">
                    $(document).ready(function() {
                        $('select[name="companies_id"]').on('change', function() {
                            var companies_id = $(this).val();
                            if (companies_id) {
                                $.ajax({
                                    url: '<?= base_url('admin/global_controller/json_by_company/tbl_project/') ?>' + companies_id,
                                    type: "GET",
                                    dataType: "json",
                                    success: function(data) {
                                        $('select[name="project_id"]').find('option').not(':first').remove();
                                        $.each(data, function(key, value) {
                                            $('select[name="project_id"]').append('<option value="' + value.project_id + '">' + value.project_name + '</option>');
                                        });
                                    }
                                });
                            } else {
                                $('select[name="project_id"]').empty();
                            }
                        });
                    });
                </script>

                <div class="form-group">
                    <label class="col-lg-3 col-md-3 col-sm-4 form-label"><?= lang('priority') ?> <span class="text-danger">*</span>
                    </label>
                    <div class="col-lg-5 col-md-5 col-sm-5">
                        <div class=" ">
                            <select name="priority" class="form-control">
                                <?php
                                $priorities = $this->db->get('tbl_priorities')->result();
                                if (!empty($priorities)) {
                                    foreach ($priorities as $v_priorities) :
                                ?>
                                        <option value="<?= $v_priorities->priority ?>"><?= lang(strtolower($v_priorities->priority)) ?></option>
                                <?php
                                    endforeach;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 col-md-3 col-sm-4 form-label"><?= lang('department') ?> <span class="text-danger">*</span>
                    </label>
                    <div class="col-lg-5 col-md-5 col-sm-5">
                        <div class=" ">
                            <select name="departments_id" class="form-control select_box" style="width: 100%" required>
                                <option value=""><?= lang('none') ?></option>
                                <?php
                                $all_departments = get_result('tbl_departments');
                                if (!empty($all_departments)) {
                                    foreach ($all_departments as $v_dept) :
                                ?>
                                        <option value="<?= $v_dept->departments_id ?>"><?= $v_dept->deptname ?></option>
                                <?php
                                    endforeach;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <?php $tickets_id = null; ?>
                <?= custom_form_Fields(7, $tickets_id); ?>


                <div class="form-group">
                    <label class="col-lg-3 col-md-3 col-sm-4 form-label"><?= lang('ticket_message') ?> </label>
                    <div class="col-lg-5 col-md-5 col-sm-5">
                        <textarea name="body" id="elm1" class="form-control textarea_" placeholder="<?= lang('message') ?>">
                            <?php echo set_value('body'); ?>  
                        </textarea>
                    </div>
                </div>
            
                <div class="form-group">
                    <label class="col-lg-3 col-md-3 col-sm-4 form-label"></label>
                    <div class="col-lg-6">
                        <button type="submit" id="file-save-button" class="btn btn-xs btn-primary"></i> <?= lang('create_ticket') ?></button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>