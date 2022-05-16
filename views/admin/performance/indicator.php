<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            

        </div>
    </div>
</div>
<!-- end page title -->
<?php
$created = can_action('86', 'created');
$edited = can_action('86', 'edited');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                 <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="<?= base_url().'admin/performance/performance_indicator' ?>"><?= lang('indicator_list') ?></a>
                    </li>
               
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 2 ? 'active' : '' ?>" href="#set_indicator" data-bs-toggle="tab"><?= lang('set_indicator') ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="indicator_list">
                        <h4 class="card-title mt-4"><?= lang('indicator_list') ?></h4>                        
                        <div class="row">
                            <?php if (!empty($all_department_info)): foreach ($all_department_info as $akey => $v_department_info) : ?>
                            <?php if (!empty($v_department_info)):
                                if (!empty($all_dept_info[$akey]->deptname)) {
                                    $deptname = $all_dept_info[$akey]->deptname;
                                } else {
                                    $deptname = lang('undefined_department');
                                }
                                ?>
                                <div class="col-sm-6 mt-4">
                                    <div class="card-body border">
                                        <h4 class="card-title mb-4"><?php echo $deptname ?></h4>
                                        <table class="table table-striped dt-responsive nowrap w-100 indicator_dept_datatable">
                                            <thead>
                                                <tr>
                                                    <td class="text-bold col-sm-1">#</td>
                                                    <td class="text-bold"><?= lang('designation') ?></td>
                                                    <td class="text-bold col-sm-1"><?= lang('action') ?></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($v_department_info as $key => $v_department) :
                                                if (!empty($v_department->designations)) {
                                                    ?>

                                                    <tr>
                                                        <td><?php echo $key + 1 ?></td>
                                                        <td>
                                                            <a data-bs-toggle="modal" data-bs-target="#myModal_lg" href="<?= base_url() ?>admin/performance/indicator_details/<?= $v_department->designations_id ?>"> <?php echo $v_department->designations ?></a>
                                                        </td>
                                                        <td>
                                                            <?php echo btn_view_modal('admin/performance/indicator_details/' . $v_department->designations_id); ?>
                                                        </td>

                                                    </tr>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="3"><?= lang('no_designation_create_yet') ?></td>
                                                    </tr>
                                                <?php }
                                                endforeach;
                                                    ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Indicator List tab Ends -->
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <!-- Add Indicator Values tab Starts -->
                    <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="set_indicator" style="position: relative;">
                        <h4 class="card-title mt-4"><?= lang('set_indicator') ?></h4>
                        <div class="row">
                            <?php
                            if (!empty($indicator_info_by_id)) {
                                $performance_indicator_id = $indicator_info_by_id->performance_indicator_id;
                                $companies_id = $indicator_info_by_id->companies_id;
                            } else {
                                $performance_indicator_id = null;
                                $companies_id = null;
                            }
                            echo form_open(base_url('admin/performance/save_performance_indicator/' . $performance_indicator_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?php super_admin_form($companies_id, 3, 9) ?>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label"><?= lang('designation') ?>
                                            <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <select name="designations_id" class="form-control select_box" style="width:100%" required>
                                                <option value=""><?= lang('select') . ' ' . lang('designation') ?></option>
                                                <?php if (!empty($all_department_info)): foreach ($all_department_info as $dept_name => $v_department_info) : ?>
                                                    <?php if (!empty($v_department_info)):
                                                        if (!empty($all_dept_info[$dept_name]->deptname)) {
                                                            $deptname = $all_dept_info[$dept_name]->deptname;
                                                        } else {
                                                            $deptname = lang('undefined_department');
                                                        }
                                                        ?>
                                                        <optgroup label="<?php echo $deptname; ?>">
                                                            <?php foreach ($v_department_info as $designation) : ?>
                                                                <option
                                                                    value="<?php echo $designation->designations_id; ?>"
                                                                    <?php
                                                                    if (!empty($indicator_info_by_id->designations_id)) {
                                                                        echo $designation->designations_id == $indicator_info_by_id->designations_id ? 'selected' : '';
                                                                    }
                                                                    ?>><?php echo $designation->designations ?></option>
                                                            <?php endforeach; ?>
                                                        </optgroup>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('select[name="companies_id"]').on('change', function () {
                                        var companies_id = $(this).val();
                                        if (companies_id) {
                                            $.ajax({
                                                url: '<?= base_url('admin/global_controller/json_get_department/')?>' + companies_id,
                                                type: "GET",
                                                dataType: "json",
                                                success: function (data) {
                                                    $('select[name="designations_id"]').empty();
                                                    $.each(data, function (key, value) {
                                                        $('select[name="designations_id"]').append('<optgroup label="' + key + '">');
                                                        $.each(value, function (keys, values) {
                                                            $('select[name="designations_id"]').append('<option value="' + values.designations_id + '">' + values.designations + '</option>');
                                                        });
                                                        $('select[name="designations_id"]').append('</optgroup>');
                                                    });
                                                }
                                            });
                                        } else {
                                            $('select[name="designations_id"]').empty();
                                        }
                                    });
                                });
                            </script>
                            <!-- Technical Competency Starts ---->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h4 class="card-title mb-4"><?= lang('technical_competency') ?></h4>
                                            <div class="row mb-3" id="border-none">
                                                <label class="col-sm-6  col-form-label"><?= lang('customer_experience_management') ?></label>
                                                <div class="col-sm-5">
                                                    <select name="customer_experiece_management" class="form-select">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($indicator_info_by_id->customer_experiece_management) && $indicator_info_by_id->customer_experiece_management == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($indicator_info_by_id->customer_experiece_management) && $indicator_info_by_id->customer_experiece_management == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($indicator_info_by_id->customer_experiece_management) && $indicator_info_by_id->customer_experiece_management == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                        <option
                                                            value="4" <?= (!empty($indicator_info_by_id->customer_experiece_management) && $indicator_info_by_id->customer_experiece_management == 4 ? 'selected' : '') ?>> <?= lang('expert_leader') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="border-none">
                                                <label class="col-sm-6  col-form-label"><?= lang('marketing') ?> </label>
                                                <div class="col-sm-5">
                                                    <select name="marketing" class="form-select">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($indicator_info_by_id->marketing) && $indicator_info_by_id->marketing == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($indicator_info_by_id->marketing) && $indicator_info_by_id->marketing == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($indicator_info_by_id->marketing) && $indicator_info_by_id->marketing == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                        <option
                                                            value="4" <?= (!empty($indicator_info_by_id->marketing) && $indicator_info_by_id->marketing == 4 ? 'selected' : '') ?>> <?= lang('expert_leader') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="border-none">
                                                <label class="col-sm-6  col-form-label"><?= lang('management') ?> </label>
                                                <div class="col-sm-5">
                                                    <select name="management" class="form-select">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($indicator_info_by_id->management) && $indicator_info_by_id->management == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($indicator_info_by_id->management) && $indicator_info_by_id->management == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($indicator_info_by_id->management) && $indicator_info_by_id->management == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                        <option
                                                            value="4" <?= (!empty($indicator_info_by_id->management) && $indicator_info_by_id->management == 4 ? 'selected' : '') ?>> <?= lang('expert_leader') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="border-none">
                                                <label  class="col-sm-6  col-form-label"><?= lang('administration') ?>  </label>
                                                <div class="col-sm-5">
                                                    <select name="administration" class="form-select">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($indicator_info_by_id->administration) && $indicator_info_by_id->administration == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($indicator_info_by_id->administration) && $indicator_info_by_id->administration == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($indicator_info_by_id->administration) && $indicator_info_by_id->administration == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                        <option
                                                            value="4" <?= (!empty($indicator_info_by_id->administration) && $indicator_info_by_id->administration == 4 ? 'selected' : '') ?>> <?= lang('expert_leader') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="border-none">
                                                <label  class="col-sm-6  col-form-label"><?= lang('presentation_skill') ?> </label>
                                                <div class="col-sm-5">
                                                    <select name="presentation_skill" class="form-select">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($indicator_info_by_id->presentation_skill) && $indicator_info_by_id->presentation_skill == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($indicator_info_by_id->presentation_skill) && $indicator_info_by_id->presentation_skill == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($indicator_info_by_id->presentation_skill) && $indicator_info_by_id->presentation_skill == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                        <option
                                                            value="4" <?= (!empty($indicator_info_by_id->presentation_skill) && $indicator_info_by_id->presentation_skill == 4 ? 'selected' : '') ?>> <?= lang('expert_leader') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="border-none">
                                                <label class="col-sm-6  col-form-label"><?= lang('quality_of_work') ?></label>
                                                <div class="col-sm-5">
                                                    <select name="quality_of_work" class="form-select">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($indicator_info_by_id->quality_of_work) && $indicator_info_by_id->quality_of_work == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($indicator_info_by_id->quality_of_work) && $indicator_info_by_id->quality_of_work == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($indicator_info_by_id->quality_of_work) && $indicator_info_by_id->quality_of_work == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                        <option
                                                            value="4" <?= (!empty($indicator_info_by_id->quality_of_work) && $indicator_info_by_id->quality_of_work == 4 ? 'selected' : '') ?>> <?= lang('expert_leader') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="border-none">
                                                <label class="col-sm-6  col-form-label"><?= lang('efficiency') ?></label>
                                                <div class="col-sm-5">
                                                    <select name="efficiency" class="form-select">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($indicator_info_by_id->efficiency) && $indicator_info_by_id->efficiency == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($indicator_info_by_id->efficiency) && $indicator_info_by_id->efficiency == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($indicator_info_by_id->efficiency) && $indicator_info_by_id->efficiency == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                        <option
                                                            value="4" <?= (!empty($indicator_info_by_id->efficiency) && $indicator_info_by_id->efficiency == 4 ? 'selected' : '') ?>> <?= lang('expert_leader') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Technical Competency Ends ---->


                                <!-- Behavioural Competency Ends ---->
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h4 class="card-title mb-4"><?= lang('behavioural_competency') ?> </h4>
                                            <div class="row mb-3" id="border-none">
                                                <label class="col-sm-6  col-form-label"><?= lang('integrity') ?> </label>
                                                <div class="col-sm-5">
                                                    <select name="integrity" class="form-select">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($indicator_info_by_id->integrity) && $indicator_info_by_id->integrity == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($indicator_info_by_id->integrity) && $indicator_info_by_id->integrity == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($indicator_info_by_id->integrity) && $indicator_info_by_id->integrity == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="border-none">
                                                <label class="col-sm-6  col-form-label"><?= lang('professionalism') ?> </label>
                                                <div class="col-sm-5">
                                                    <select name="professionalism" class="form-select">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($indicator_info_by_id->professionalism) && $indicator_info_by_id->professionalism == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($indicator_info_by_id->professionalism) && $indicator_info_by_id->professionalism == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($indicator_info_by_id->professionalism) && $indicator_info_by_id->professionalism == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="border-none">
                                                <label class="col-sm-6  col-form-label"><?= lang('team_work') ?></label>
                                                <div class="col-sm-5">
                                                    <select name="team_work" class="form-select">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($indicator_info_by_id->team_work) && $indicator_info_by_id->team_work == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($indicator_info_by_id->team_work) && $indicator_info_by_id->team_work == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($indicator_info_by_id->team_work) && $indicator_info_by_id->team_work == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="border-none">
                                                <label  class="col-sm-6  col-form-label"><?= lang('critical_thinking') ?></label>
                                                <div class="col-sm-5">
                                                    <select name="critical_thinking" class="form-select">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($indicator_info_by_id->critical_thinking) && $indicator_info_by_id->critical_thinking == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($indicator_info_by_id->critical_thinking) && $indicator_info_by_id->critical_thinking == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($indicator_info_by_id->critical_thinking) && $indicator_info_by_id->critical_thinking == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="border-none">
                                                <label class="col-sm-6  col-form-label"><?= lang('conflict_management') ?></label>
                                                <div class="col-sm-5">
                                                    <select name="conflict_management" class="form-select">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($indicator_info_by_id->conflict_management) && $indicator_info_by_id->conflict_management == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($indicator_info_by_id->conflict_management) && $indicator_info_by_id->conflict_management == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($indicator_info_by_id->conflict_management) && $indicator_info_by_id->conflict_management == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="border-none">
                                                <label class="col-sm-6  col-form-label"><?= lang('attendance') ?></label>
                                                <div class="col-sm-5">
                                                    <select name="attendance" class="form-select">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($indicator_info_by_id->attendance) && $indicator_info_by_id->attendance == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($indicator_info_by_id->attendance) && $indicator_info_by_id->attendance == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($indicator_info_by_id->attendance) && $indicator_info_by_id->attendance == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="border-none">
                                                <label class="col-sm-6  col-form-label"><?= lang('ability_to_meet_deadline') ?></label>
                                                <div class="col-sm-5">
                                                    <select name="ability_to_meed_deadline" class="form-select">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($indicator_info_by_id->ability_to_meed_deadline) && $indicator_info_by_id->ability_to_meed_deadline == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($indicator_info_by_id->ability_to_meed_deadline) && $indicator_info_by_id->ability_to_meed_deadline == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($indicator_info_by_id->ability_to_meed_deadline) && $indicator_info_by_id->ability_to_meed_deadline == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Behavioural Competency Ends ---->
                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-sm-3 pull-right">
                                    <button id="btn_emp" type="submit" class="btn btn-primary btn-block"><?= lang('save') ?></button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- Add Indicator Values Ends --->
                </div>
            </div>   
        </div>
    </div>
</div>


