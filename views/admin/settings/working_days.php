<script type="text/javascript">
    $(document).ready(function () {
        $(".different_time_input").attr('disabled', 'disabled');
        $(".different_time_hours").hide();
        $(".same_time").attr('disabled', 'disabled');
    });
</script>
<?php

if (config_item('office_time') == 'different_time') {
    $d_working_days = get_result('tbl_working_days', array('flag' => 1));
    if (!empty($d_working_days)) {
        foreach ($d_working_days as $v_d_days) {
            ?>
            <script type="text/javascript">
                $(function () {
                    $(".different_time_hours_" + <?= $v_d_days->day_id?>).removeClass('disabled');
                    $(".different_time_hours_" + <?= $v_d_days->day_id?>).removeAttr('disabled');
                    $("#different_time_" + <?= $v_d_days->day_id?>).show();
                    $(".different_time_input").removeAttr('disabled');
                });
            </script>
            <?php
        }
    }
} ?>

<?php

if (config_item('office_time') == 'same_time') {
    $s_working_days = get_result('tbl_working_days', array('flag' => 1));

    if (!empty($s_working_days)) {
        foreach ($s_working_days as $v_s_days) {
            ?>
            <script type="text/javascript">
                $(function () {
                    $(".same_time").removeAttr('disabled');
                });
            </script>
            <?php
        }
    }
} ?>
<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    <!-- Start Form -->
    <form role="form" id="form" action="<?php echo base_url(); ?>admin/settings/save_working_days" method="post" class="form-horizontal">
        <div class="card-body">
            <h4 class="card-title mb-4"><?= lang('working_days') ?></h4>
    
            <div class="row mb-3">
                <label class="col-lg-3 col-md-3 col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('office_time') ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 row">
                    <div class="col-lg-6 form-check form-check-primary">
                        <input class="select_one form-check-input" value="same_time" <?php
                            if (config_item('office_time') == 'same_time') {
                                echo "checked=\"checked\"";
                            }
                            ?> id="same_time" type="checkbox" name="office_time"/>
                        <label class="form-check-label ml" for="same_time"> <?= lang('every_days_same_time') ?></label>
                    </div>
                    <div class="col-lg-6 form-check form-check-primary">
                        <input class="select_one form-check-input" <?php
                            if (config_item('office_time') == 'different_time') {
                                echo "checked=\"checked\"";
                            }
                            ?> value="different_time" id="different_time" type="checkbox"
                                   name="office_time"/>
                        <label class="form-check-label ml" for="different_time"> <?= lang('set_different_time') ?></label>
                    </div>
                </div>
            </div>
            <div class="same_time" style="display: <?php
            if (config_item('office_time') == 'same_time') {
                echo 'block';
            } else {
                echo 'none';
            }
            ?>">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('start_hours') ?></label>
                            <div class="col-lg-7 col-md-7 col-sm-7">

                                <div class="input-group">
                                    <input type="text" name="start_hours[]" class="form-control timepicker same_time"
                                           value="<?php
                                           if (!empty($s_working_days)) {
                                               echo date('h:i A', strtotime($s_working_days[0]->start_hours));
                                           }
                                           ?>">
                                    <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><strong><?= lang('end_hours') ?> <span
                                        class="required"> *</span></strong></label>
                            <div class="col-lg-7 col-md-7 col-sm-7">
                                <div class="input-group">
                                    <input type="text" name="end_hours[]" class="form-control timepicker same_time"
                                           value="<?php
                                           if (!empty($s_working_days)) {
                                               echo date('h:i A', strtotime($s_working_days[0]->end_hours));
                                           }
                                           ?>">
                                    <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3 ml">
                    <!-- List  of days -->
                    <?php
                    $days = $this->db->get('tbl_days')->result();

                    foreach ($days as $v_day): ?><!--Retrieve Days from Database -->
                    <div class="col-lg-3 form-check form-check-primary mb-3">
                        <input type="checkbox" class="same_time form-check-input" name="day[]" id="day_<?php echo $v_day->day_id ?>" value="<?php echo $v_day->day_id ?>"

                            <?php
                            if (!empty($s_working_days)) {
                                foreach ($s_working_days as $v_s_work) {
                                    if ($v_s_work->flag == 1 && $v_s_work->day_id == $v_day->day_id) {
                                        ?>
                                        checked
                                        <?php
                                    }
                                }
                            }
                            ?>/>
                        <label for="day_<?php echo $v_day->day_id ?>" class="form-check-label ml"><strong><?= lang($v_day->day) ?></strong></label>
                        <input type="hidden" name="day_id[]" value="<?php echo $v_day->day_id ?>"/>
                    </div>
                    <?php endforeach; ?>
                    <div class=" pull-right mr-sm">
                        <button type="submit" class="btn btn-primary"><?= lang('save') ?></button>
                    </div>
                </div>

            </div>
            <div class="different_time" style="display:<?php
            if (config_item('office_time') == 'different_time') {
                echo 'block';
            } else {
                echo 'none';
            }
            ?>">
                <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>asset/css/kendo.common.min.css"/>
                <?php
                $days = $this->db->get('tbl_days')->result();

                foreach ($days as $v_day): ?><!--Retrieve Days from Database -->
                <div class="row mb-3">
                    <label class="col-lg-3 col-md-3 col-sm-3 form-check-label" for="diff_day_<?php echo $v_day->day_id ?>"><strong><?= lang($v_day->day) ?> </strong></label>
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        <div class=" form-check form-check-primary">
                            <input class="different_time_input ml0 form-check-input"
                                <?php
                                if (!empty($d_working_days)) {
                                    foreach ($d_working_days as $v_d_work) {
                                        if ($v_d_work->flag == 1 && $v_d_work->day_id == $v_day->day_id) {
                                            ?>
                                            checked
                                            <?php
                                        }
                                    }
                                }
                                ?>
                                   type="checkbox" name="day[]"
                                   value="<?php echo $v_day->day_id ?>" id="diff_day_<?php echo $v_day->day_id ?>"/>
                        </div>
                    </div>
                    <div class="different_time_hours col-lg-8 row mb-3" id="different_time_<?= $v_day->day_id ?>">
                        <label class="col-lg-2 col-md-2 col-lg-2 col-sm-2 col-form-label"><?= lang('start') ?></label>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <input type="text" name="start_hours[]"
                                   class="disabled form-control timepicker different_time_hours_<?= $v_day->day_id ?>"
                                   value="<?php
                                   if (!empty($d_working_days)) {
                                       foreach ($d_working_days as $v_d_work) {
                                           if ($v_d_work->day_id == $v_day->day_id) {
                                               echo display_time($v_d_work->start_hours);
                                           }
                                       }
                                   }
                                   ?>">
                        </div>
                        <label class="col-lg-2 col-md-2 col-sm-2 col-form-label"><strong><?= lang('end') ?> </strong></label>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <input type="text" name="end_hours[]"
                                   class="disabled different_time_hours_<?= $v_day->day_id ?> form-control timepicker "
                                   value="<?php
                                   if (!empty($d_working_days)) {
                                       foreach ($d_working_days as $v_d_work) {
                                           if ($v_d_work->day_id == $v_day->day_id) {
                                               echo display_time($v_d_work->end_hours);
                                           }
                                       }
                                   }
                                   ?>">
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <div class="row mb-3">
                    <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"></label>
                    <div class="col-lg-5 col-md-5 col-sm-5">
                        <button type="submit"
                                class="btn btn-primary btn-block"><?= lang('save') ?></button>
                    </div>
                </div>
                <!-- List  of days -->
            </div>


        </form>
    </div>
</div>
