<?php
$edited = can_action('86', 'edited');
?>
<form action="<?php echo base_url() ?>admin/performance/performance_indicator/<?php if (!empty($performance_indicator_details->performance_indicator_id)) { echo $performance_indicator_details->performance_indicator_id; } ?>" method="post" class="form-horizontal">
    <div class="modal-header">
        <h5 class="modal-title"><?= lang('performance_indicator_details') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body wrap-modal wrap">    
        <div class="card-body">
        <div class="row">
            <div class="col-sm-6 row">
                <!-- Technical Competency Starts ---->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><?= lang('technical_competency') ?></h4>
                    
                        <div class="row mb-3">
                            <label class=" col-sm-7 form-label"><?= lang('customer_experience_management') ?>
                                : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static" style="text-align: justify;"><?php
                                    if (!empty($performance_indicator_details->customer_experiece_management) && $performance_indicator_details->customer_experiece_management == 1) {
                                        echo lang('beginner');
                                    } elseif (!empty($performance_indicator_details->customer_experiece_management) && $performance_indicator_details->customer_experiece_management == 2) {
                                        echo lang('intermediate');
                                    } elseif (!empty($performance_indicator_details->customer_experiece_management) && $performance_indicator_details->customer_experiece_management == 3) {
                                        echo lang('advanced');
                                    } elseif (!empty($performance_indicator_details->customer_experiece_management) && $performance_indicator_details->customer_experiece_management == 4) {
                                        echo lang('expert_leader');
                                    } else {
                                        echo lang('none');
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="field-1" class=" col-sm-7 form-label"><?= lang('marketing') ?>
                                : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static" style="text-align: justify;"><?php
                                    if (!empty($performance_indicator_details->marketing) && $performance_indicator_details->marketing == 1) {
                                        echo lang('beginner');
                                    } elseif (!empty($performance_indicator_details->marketing) && $performance_indicator_details->marketing == 2) {
                                        echo lang('intermediate');
                                    } elseif (!empty($performance_indicator_details->marketing) && $performance_indicator_details->marketing == 3) {
                                        echo lang('advanced');
                                    } elseif (!empty($performance_indicator_details->marketing) && $performance_indicator_details->marketing == 4) {
                                        echo lang('expert_leader');
                                    } else {
                                        echo lang('none');
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="field-1" class=" col-sm-7 form-label"><?= lang('management') ?>
                                : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static" style="text-align: justify;"><?php
                                    if (!empty($performance_indicator_details->management) && $performance_indicator_details->management == 1) {
                                        echo lang('beginner');
                                    } elseif (!empty($performance_indicator_details->management) && $performance_indicator_details->management == 2) {
                                        echo lang('intermediate');
                                    } elseif (!empty($performance_indicator_details->management) && $performance_indicator_details->management == 3) {
                                        echo lang('advanced');
                                    } elseif (!empty($performance_indicator_details->management) && $performance_indicator_details->management == 4) {
                                        echo lang('expert_leader');
                                    } else {
                                        echo lang('none');
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="field-1"
                                   class=" col-sm-7 form-label"><?= lang('administration') ?>
                                : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static" style="text-align: justify;"><?php
                                    if (!empty($performance_indicator_details->administration) && $performance_indicator_details->administration == 1) {
                                        echo lang('beginner');
                                    } elseif (!empty($performance_indicator_details->administration) && $performance_indicator_details->administration == 2) {
                                        echo lang('intermediate');
                                    } elseif (!empty($performance_indicator_details->administration) && $performance_indicator_details->administration == 3) {
                                        echo lang('advanced');
                                    } elseif (!empty($performance_indicator_details->administration) && $performance_indicator_details->administration == 4) {
                                        echo lang('expert_leader');
                                    } else {
                                        echo lang('none');
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="field-1"
                                   class=" col-sm-7 form-label"><?= lang('presentation_skill') ?>
                                : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static" style="text-align: justify;"><?php
                                    if (!empty($performance_indicator_details->presentation_skill) && $performance_indicator_details->presentation_skill == 1) {
                                        echo lang('beginner');
                                    } elseif (!empty($performance_indicator_details->presentation_skill) && $performance_indicator_details->presentation_skill == 2) {
                                        echo lang('intermediate');
                                    } elseif (!empty($performance_indicator_details->presentation_skill) && $performance_indicator_details->presentation_skill == 3) {
                                        echo lang('advanced');
                                    } elseif (!empty($performance_indicator_details->presentation_skill) && $performance_indicator_details->presentation_skill == 4) {
                                        echo lang('expert_leader');
                                    } else {
                                        echo lang('none');
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="field-1"
                                   class=" col-sm-7 form-label"><?= lang('quality_of_work') ?>
                                : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static" style="text-align: justify;"><?php
                                    if (!empty($performance_indicator_details->quality_of_work) && $performance_indicator_details->quality_of_work == 1) {
                                        echo lang('beginner');
                                    } elseif (!empty($performance_indicator_details->quality_of_work) && $performance_indicator_details->quality_of_work == 2) {
                                        echo lang('intermediate');
                                    } elseif (!empty($performance_indicator_details->quality_of_work) && $performance_indicator_details->quality_of_work == 3) {
                                        echo lang('advanced');
                                    } elseif (!empty($performance_indicator_details->quality_of_work) && $performance_indicator_details->quality_of_work == 4) {
                                        echo lang('expert_leader');
                                    } else {
                                        echo lang('none');
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="field-1" class=" col-sm-7 form-label"><?= lang('efficiency') ?>
                                : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static" style="text-align: justify;"><?php
                                    if (!empty($performance_indicator_details->efficiency) && $performance_indicator_details->efficiency == 1) {
                                        echo lang('beginner');
                                    } elseif (!empty($performance_indicator_details->efficiency) && $performance_indicator_details->efficiency == 2) {
                                        echo lang('intermediate');
                                    } elseif (!empty($performance_indicator_details->efficiency) && $performance_indicator_details->efficiency == 3) {
                                        echo lang('advanced');
                                    } elseif (!empty($performance_indicator_details->efficiency) && $performance_indicator_details->efficiency == 4) {
                                        echo lang('expert_leader');
                                    } else {
                                        echo lang('none');
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <!-- Technical Competency Ends ---->
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <!-- Behavioural Competency Starts ---->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><?= lang('behavioural_competency') ?></h4>
                    
                        <div class="row mb-3">
                            <label for="field-1" class=" col-sm-7 form-label"><?= lang('integrity') ?>
                                : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static" style="text-align: justify;"><?php
                                    if (!empty($performance_indicator_details->integrity) && $performance_indicator_details->integrity == 1) {
                                        echo lang('beginner');
                                    } elseif (!empty($performance_indicator_details->integrity) && $performance_indicator_details->integrity == 2) {
                                        echo lang('intermediate');
                                    } elseif (!empty($performance_indicator_details->integrity) && $performance_indicator_details->integrity == 3) {
                                        echo lang('advanced');
                                    } else {
                                        echo lang('none');
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="field-1"
                                   class=" col-sm-7 form-label"><?= lang('professionalism') ?>
                                : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static" style="text-align: justify;"><?php
                                    if (!empty($performance_indicator_details->professionalism) && $performance_indicator_details->professionalism == 1) {
                                        echo lang('beginner');
                                    } elseif (!empty($performance_indicator_details->professionalism) && $performance_indicator_details->professionalism == 2) {
                                        echo lang('intermediate');
                                    } elseif (!empty($performance_indicator_details->professionalism) && $performance_indicator_details->professionalism == 3) {
                                        echo lang('advanced');
                                    } else {
                                        echo lang('none');
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="field-1" class=" col-sm-7 form-label"><?= lang('team_work') ?>
                                : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static" style="text-align: justify;"><?php
                                    if (!empty($performance_indicator_details->team_work) && $performance_indicator_details->team_work == 1) {
                                        echo lang('beginner');
                                    } elseif (!empty($performance_indicator_details->team_work) && $performance_indicator_details->team_work == 2) {
                                        echo lang('intermediate');
                                    } elseif (!empty($performance_indicator_details->team_work) && $performance_indicator_details->team_work == 3) {
                                        echo lang('advanced');
                                    } else {
                                        echo lang('none');
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="field-1"
                                   class=" col-sm-7 form-label"><?= lang('critical_thinking') ?>
                                : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static" style="text-align: justify;"><?php
                                    if (!empty($performance_indicator_details->critical_thinking) && $performance_indicator_details->critical_thinking == 1) {
                                        echo lang('beginner');
                                    } elseif (!empty($performance_indicator_details->critical_thinking) && $performance_indicator_details->critical_thinking == 2) {
                                        echo lang('intermediate');
                                    } elseif (!empty($performance_indicator_details->critical_thinking) && $performance_indicator_details->critical_thinking == 3) {
                                        echo lang('advanced');
                                    } else {
                                        echo lang('none');
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="field-1"
                                   class=" col-sm-7 form-label"><?= lang('conflict_management') ?>
                                : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static" style="text-align: justify;"><?php
                                    if (!empty($performance_indicator_details->conflict_management) && $performance_indicator_details->conflict_management == 1) {
                                        echo lang('beginner');
                                    } elseif (!empty($performance_indicator_details->conflict_management) && $performance_indicator_details->conflict_management == 2) {
                                        echo lang('intermediate');
                                    } elseif (!empty($performance_indicator_details->conflict_management) && $performance_indicator_details->conflict_management == 3) {
                                        echo lang('advanced');
                                    } else {
                                        echo lang('none');
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="field-1" class=" col-sm-7 form-label"><?= lang('attendance') ?>
                                : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static" style="text-align: justify;"><?php
                                    if (!empty($performance_indicator_details->attendance) && $performance_indicator_details->attendance == 1) {
                                        echo lang('beginner');
                                    } elseif (!empty($performance_indicator_details->attendance) && $performance_indicator_details->attendance == 2) {
                                        echo lang('intermediate');
                                    } elseif (!empty($performance_indicator_details->attendance) && $performance_indicator_details->attendance == 3) {
                                        echo lang('advanced');
                                    } else {
                                        echo lang('none');
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="field-1"
                                   class=" col-sm-7 form-label"><?= lang('ability_to_meet_deadline') ?>
                                : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static" style="text-align: justify;"><?php
                                    if (!empty($performance_indicator_details->ability_to_meed_deadline) && $performance_indicator_details->ability_to_meed_deadline == 1) {
                                        echo lang('beginner');
                                    } elseif (!empty($performance_indicator_details->ability_to_meed_deadline) && $performance_indicator_details->ability_to_meed_deadline == 2) {
                                        echo lang('intermediate');
                                    } elseif (!empty($performance_indicator_details->ability_to_meed_deadline) && $performance_indicator_details->ability_to_meed_deadline == 3) {
                                        echo lang('advanced');
                                    } else {
                                        echo lang('none');
                                    }
                                    ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Behavioural Competency Ends ---->
            </div>
        </div></div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <?php if (!empty($performance_indicator_details->performance_indicator_id)) { ?>
                    <?php if (!empty($edited)) { ?>
            <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('edit') ?></button>   
            <?php } ?>
            <?php } else { ?>         
            <span class="text-danger mt-4 ml"><?= lang('indicator_value_not_set') ?></span>   
            <?php } ?>
        </div>
    </div>
</form>