<?php 
$created = can_action('72', 'created');
$edited = can_action('72', 'edited');
$deleted = can_action('72', 'deleted');
$office_hours = config_item('office_hours');
?>
<?= message_box('success'); ?>
<?= message_box('error'); ?>
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

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="pull-right float-end">
                    <a class="btn btn-sm btn-info" href="<?= base_url() ?>admin/leave_management/apply_leave"  data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal_extra_lg">
                            <i class="fa fa-plus "></i> <?= lang('apply') . ' ' . lang('leave') ?>
                    </a>
                </div>
                <h4 class="card-title mb-4"><?=lang('leave_management');?></h4>
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 1 ? 'active' : '' ?>" href="#pending_approval" data-bs-toggle="tab"><?= lang('pending') . ' ' . lang('approval') ?></a>
                    </li>

                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 2 ? 'active' : '' ?>" href="#my_leave" data-bs-toggle="tab"><?= lang('my_leave') ?></a>
                    </li>

                    <?php if ($this->session->userdata('user_type') == 1) { ?>
                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 3 ? 'active' : '' ?>" href="#all_leave" data-bs-toggle="tab"><?= lang('all_leave') ?></a>
                    </li>
                    <?php } ?>
                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 4 ? 'active' : '' ?>" href="#leave_report" data-bs-toggle="tab"><?= lang('leave_report') ?></a>
                    </li>
                </ul>

                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="pending_approval">
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100 DataTables">
                                <thead>
                                    <tr>
                                        <?php super_admin_opt_th() ?>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('leave_category') ?></th>
                                        <th><?= lang('date') ?></th>
                                        <th><?= lang('duration') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <?php $show_custom_fields = custom_form_table(17, null);
                                        if (!empty($show_custom_fields)) {
                                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                                if (!empty($c_label)) {
                                        ?>
                                                    <th><?= $c_label ?> </th>
                                        <?php }
                                            }
                                        }
                                        ?>
                                        <th class="col-sm-2"><?= lang('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $my_details = MyDetails();

                                    $designation_info = $this->application_model->check_by(array('designations_id' => $my_details->designations_id), 'tbl_designations');
                                    if (!empty($designation_info)) {
                                        $dept_head = $this->application_model->check_by(array('departments_id' => $designation_info->departments_id), 'tbl_departments');
                                    }

                                    if ($this->session->userdata('user_type') == 1 || !empty($dept_head) && $dept_head->department_head_id == $my_details->user_id) {
                                        $all_pending_leave = get_result('tbl_leave_application', array('application_status' => 1));
                                    } else {
                                        $all_pending_leave = get_result('tbl_leave_application', array('application_status' => 1, 'user_id' => $this->session->userdata('user_id')));
                                    }

                                    if (!empty($all_pending_leave)) {
                                        foreach ($all_pending_leave as $v_pending) :
                                            if ($this->session->userdata('user_type') != 1 && !empty($dept_head) && $dept_head->department_head_id == $my_details->user_id) {
                                                $staff_details = MyDetails($v_pending->user_id);
                                                if ($staff_details->departments_id == $dept_head->departments_id) {
                                                    $v_pending = $v_pending;
                                                } else {
                                                    $v_pending = null;
                                                }
                                            }
                                            if (!empty($v_pending)) {
                                                $p_profile = $this->db->where('user_id', $v_pending->user_id)->get('tbl_account_details')->row();
                                                $p_leave_category = $this->db->where('leave_category_id', $v_pending->leave_category_id)->get('tbl_leave_category')->row();
                                    ?>
                                                <tr id="table_leave_m_<?= $v_pending->leave_application_id ?>">
                                                    <?php super_admin_opt_td($v_pending->companies_id) ?>
                                                    <td><?= $p_profile->fullname ?></td>
                                                    <td><?= $p_leave_category->leave_category ?></td>
                                                    <td><?= display_date($v_pending->leave_start_date) ?>
                                                        <?php
                                                        if ($v_pending->leave_type == 'multiple_days') {
                                                            if (!empty($v_pending->leave_end_date)) {
                                                                echo lang('TO') . ' ' . display_date($v_pending->leave_end_date);
                                                            }
                                                        } ?>
                                                    </td>
                                                    <td><?php
                                                        if ($v_pending->leave_type == 'single_day') {
                                                            echo ' 1 ' . lang('day') . ' (<span class="text-danger">' . $office_hours . '.00' . lang('hours') . '</span>)';
                                                        }
                                                        if ($v_pending->leave_type == 'multiple_days') {
                                                            $ge_days = 0;
                                                            $m_days = 0;

                                                            $month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($v_pending->leave_start_date)), date('Y', strtotime($v_pending->leave_start_date)));
                                                            $datetime1 = new DateTime($v_pending->leave_start_date);
                                                            if (empty($v_pending->leave_end_date)) {
                                                                $v_pending->leave_end_date = $v_pending->leave_start_date;
                                                            }
                                                            $datetime2 = new DateTime($v_pending->leave_end_date);
                                                            $difference = $datetime1->diff($datetime2);
                                                            if ($difference->m != 0) {
                                                                $m_days += $month;
                                                            } else {
                                                                $m_days = 0;
                                                            }
                                                            $ge_days += $difference->d + 1;
                                                            $total_token = $m_days + $ge_days;
                                                            echo $total_token . ' ' . lang('days') . ' (<span class="text-danger">' . $total_token * $office_hours . '.00' . lang('hours') . '</span>)';
                                                        }
                                                        if ($v_pending->leave_type == 'hours') {
                                                            $total_hours = ($v_pending->hours / $office_hours);
                                                            echo number_format($total_hours, 2) . ' ' . lang('days') . ' (<span class="text-danger">' . $v_pending->hours . '.00' . lang('hours') . '</span>)';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php
                                                        if ($v_pending->application_status == '1') {
                                                            echo '<span class="badge badge-soft-warning">' . lang('pending') . '</span>';
                                                        } elseif ($v_pending->application_status == '2') {
                                                            echo '<span class="badge badge-soft-success">' . lang('accepted') . '</span>';
                                                        } else {
                                                            echo '<span class="badge badge-soft-danger">' . lang('rejected') . '</span>';
                                                        }
                                                        ?></td>
                                                    <?php $show_custom_fields = custom_form_table(17, $v_pending->leave_application_id);
                                                    if (!empty($show_custom_fields)) {
                                                        foreach ($show_custom_fields as $c_label => $v_fields) {
                                                            if (!empty($c_label)) {
                                                    ?>
                                                                <td><?= $v_fields ?> </td>
                                                    <?php }
                                                        }
                                                    }
                                                    ?>
                                                    <td>
                                                        <?php echo btn_view_modal('admin/leave_management/view_details/' . $v_pending->leave_application_id) ?>
                                                        <?php if ($v_pending->application_status != '2') { ?>
                                                            <?php echo ajax_anchor(base_url("admin/leave_management/delete_application/" . $v_pending->leave_application_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_leave_m_" . $v_pending->leave_application_id)); ?>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                    <?php
                                            }
                                        endforeach;
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="my_leave">
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100 DataTables">
                                <thead>
                                    <tr>
                                        <?php super_admin_opt_th() ?>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('leave_category') ?></th>
                                        <th><?= lang('date') ?></th>
                                        <th><?= lang('duration') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <?php $show_custom_fields = custom_form_table(17, null);
                                        if (!empty($show_custom_fields)) {
                                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                                if (!empty($c_label)) {
                                        ?>
                                                    <th><?= $c_label ?> </th>
                                        <?php }
                                            }
                                        }
                                        ?>
                                        <?php if ($this->session->userdata('user_type') == 1) { ?>
                                            <th class="col-sm-2"><?= lang('action') ?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $my_leave_application = get_result('tbl_leave_application', array('user_id' => $this->session->userdata('user_id')));
                                    if (!empty($my_leave_application)) {
                                        foreach ($my_leave_application as $v_my_leave) :
                                            $my_profile = $this->db->where('user_id', $v_my_leave->user_id)->get('tbl_account_details')->row();
                                            $my_leave_category = $this->db->where('leave_category_id', $v_my_leave->leave_category_id)->get('tbl_leave_category')->row();
                                    ?>
                                            <tr id="table_leave_my_<?= $v_my_leave->leave_application_id ?>">
                                                <?php super_admin_opt_td($v_my_leave->companies_id) ?>
                                                <td><?= $my_profile->fullname ?></td>
                                                <td><?= $my_leave_category->leave_category ?></td>
                                                <td><?= display_date($v_my_leave->leave_start_date) ?>
                                                    <?php
                                                    if ($v_my_leave->leave_type == 'multiple_days') {
                                                        if (!empty($v_my_leave->leave_end_date)) {
                                                            echo lang('TO') . ' ' . display_date($v_my_leave->leave_end_date);
                                                        }
                                                    } ?>
                                                </td>
                                                <td><?php
                                                    if ($v_my_leave->leave_type == 'single_day') {
                                                        echo ' 1 ' . lang('day') . ' (<span class="text-danger">' . $office_hours . '.00' . lang('hours') . '</span>)';
                                                    }
                                                    if ($v_my_leave->leave_type == 'multiple_days') {
                                                        $ge_days = 0;
                                                        $m_days = 0;

                                                        $month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($v_my_leave->leave_start_date)), date('Y', strtotime($v_my_leave->leave_start_date)));
                                                        $datetime1 = new DateTime($v_my_leave->leave_start_date);
                                                        if (empty($v_my_leave->leave_end_date)) {
                                                            $v_my_leave->leave_end_date = $v_my_leave->leave_start_date;
                                                        }
                                                        $datetime2 = new DateTime($v_my_leave->leave_end_date);
                                                        $difference = $datetime1->diff($datetime2);
                                                        if ($difference->m != 0) {
                                                            $m_days += $month;
                                                        } else {
                                                            $m_days = 0;
                                                        }
                                                        $ge_days += $difference->d + 1;
                                                        $total_token = $m_days + $ge_days;
                                                        echo $total_token . ' ' . lang('days') . ' (<span class="text-danger">' . $total_token * $office_hours . '.00' . lang('hours') . '</span>)';
                                                    }
                                                    if ($v_my_leave->leave_type == 'hours') {
                                                        $total_hours = ($v_my_leave->hours / $office_hours);
                                                        echo number_format($total_hours, 2) . ' ' . lang('days') . ' (<span class="text-danger">' . $v_my_leave->hours . '.00' . lang('hours') . '</span>)';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php
                                                    if ($v_my_leave->application_status == '1') {
                                                        echo '<span class="badge badge-soft-warning">' . lang('pending') . '</span>';
                                                    } elseif ($v_my_leave->application_status == '2') {
                                                        echo '<span class="badge badge-soft-success">' . lang('accepted') . '</span>';
                                                    } else {
                                                        echo '<span class="badge badge-soft-danger">' . lang('rejected') . '</span>';
                                                    }
                                                    ?></td>
                                                <?php $show_custom_fields = custom_form_table(17, $v_my_leave->leave_application_id);
                                                if (!empty($show_custom_fields)) {
                                                    foreach ($show_custom_fields as $c_label => $v_fields) {
                                                        if (!empty($c_label)) {
                                                ?>
                                                            <td><?= $v_fields ?> </td>
                                                <?php }
                                                    }
                                                }
                                                ?>
                                                <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                    <td>
                                                        <?php echo btn_view_modal('admin/leave_management/view_details/' . $v_my_leave->leave_application_id) ?>
                                                        <?php if ($v_my_leave->application_status != '2') { ?>
                                                            <?php echo ajax_anchor(base_url("admin/leave_management/delete_application/" . $v_my_leave->leave_application_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_leave_my_" . $v_my_leave->leave_application_id)); ?>
                                                        <?php } ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                    <?php
                                        endforeach;
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if ($this->session->userdata('user_type') == 1) { ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="all_leave">
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100 DataTables">
                                <thead>
                                    <tr>
                                        <?php super_admin_opt_th() ?>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('leave_category') ?></th>
                                        <th><?= lang('date') ?></th>
                                        <th><?= lang('duration') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <?php $show_custom_fields = custom_form_table(17, null);
                                        if (!empty($show_custom_fields)) {
                                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                                if (!empty($c_label)) {
                                        ?>
                                                    <th><?= $c_label ?> </th>
                                        <?php }
                                            }
                                        }
                                        ?>
                                        <?php if ($this->session->userdata('user_type') == 1) { ?>
                                            <th class="col-sm-2"><?= lang('action') ?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $all_leave_application = get_result('tbl_leave_application');
                                    if (!empty($all_leave_application)) {
                                        foreach ($all_leave_application as $v_all_leave) :
                                            $my_profile = $this->db->where('user_id', $v_all_leave->user_id)->get('tbl_account_details')->row();
                                            $a_leave_category = $this->db->where('leave_category_id', $v_all_leave->leave_category_id)->get('tbl_leave_category')->row();
                                    ?>
                                            <tr id="table_leave_all_<?= $v_all_leave->leave_application_id ?>">
                                                <?php super_admin_opt_td($v_all_leave->companies_id) ?>
                                                <td><?= $my_profile->fullname ?></td>
                                                <td><?= $a_leave_category->leave_category ?></td>
                                                <td><?= display_date($v_all_leave->leave_start_date) ?>
                                                    <?php
                                                    if ($v_all_leave->leave_type == 'multiple_days') {
                                                        if (!empty($v_all_leave->leave_end_date)) {
                                                            echo lang('TO') . ' ' . display_date($v_all_leave->leave_end_date);
                                                        }
                                                    } ?>
                                                </td>
                                                <td><?php
                                                    if ($v_all_leave->leave_type == 'single_day') {
                                                        echo ' 1 ' . lang('day') . ' (<span class="text-danger">' . $office_hours . '.00' . lang('hours') . '</span>)';
                                                    }
                                                    if ($v_all_leave->leave_type == 'multiple_days') {
                                                        $ge_days = 0;
                                                        $m_days = 0;

                                                        $month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($v_all_leave->leave_start_date)), date('Y', strtotime($v_all_leave->leave_start_date)));
                                                        $datetime1 = new DateTime($v_all_leave->leave_start_date);
                                                        if (empty($v_all_leave->leave_end_date)) {
                                                            $v_all_leave->leave_end_date = $v_all_leave->leave_start_date;
                                                        }
                                                        $datetime2 = new DateTime($v_all_leave->leave_end_date);
                                                        $difference = $datetime1->diff($datetime2);
                                                        if ($difference->m != 0) {
                                                            $m_days += $month;
                                                        } else {
                                                            $m_days = 0;
                                                        }
                                                        $ge_days += $difference->d + 1;
                                                        $total_token = $m_days + $ge_days;
                                                        echo $total_token . ' ' . lang('days') . ' (<span class="text-danger">' . $total_token * $office_hours . '.00' . lang('hours') . '</span>)';
                                                    }
                                                    if ($v_all_leave->leave_type == 'hours') {
                                                        $total_hours = ($v_all_leave->hours / $office_hours);
                                                        echo number_format($total_hours, 2) . ' ' . lang('days') . ' (<span class="text-danger">' . $v_all_leave->hours . '.00' . lang('hours') . '</span>)';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php
                                                    if ($v_all_leave->application_status == '1') {
                                                        echo '<span class="badge badge-soft-warning">' . lang('pending') . '</span>';
                                                    } elseif ($v_all_leave->application_status == '2') {
                                                        echo '<span class="badge badge-soft-success">' . lang('accepted') . '</span>';
                                                    } else {
                                                        echo '<span class="badge badge-soft-danger">' . lang('rejected') . '</span>';
                                                    }
                                                    ?></td>
                                                <?php $show_custom_fields = custom_form_table(17, $v_all_leave->leave_application_id);
                                                if (!empty($show_custom_fields)) {
                                                    foreach ($show_custom_fields as $c_label => $v_fields) {
                                                        if (!empty($c_label)) {
                                                ?>
                                                            <td><?= $v_fields ?> </td>
                                                <?php }
                                                    }
                                                }
                                                ?>
                                                <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                    <td>
                                                        <?php echo btn_view_modal('admin/leave_management/view_details/' . $v_all_leave->leave_application_id) ?>
                                                        <?php if ($v_all_leave->application_status != '2') { ?>
                                                            <?php echo ajax_anchor(base_url("admin/leave_management/delete_application/" . $v_all_leave->leave_application_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_leave_all_" . $v_all_leave->leave_application_id)); ?>
                                                        <?php } ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                    <?php
                                        endforeach;
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="tab-pane <?= $active == 4 ? 'active' : '' ?>" id="leave_report" style="position: relative;">
                        <div class="card border">
                            <div class="card-body">
                                <?php if ($this->session->userdata('user_type') == 1) { ?>
                                    <div id="panelChart5">
                                        <div class="row card-title pl-lg pb-sm mb-4" style="border-bottom: 1px solid #a0a6ad"><?= lang('all') . ' ' . lang('leave_report') ?></div>
                                        <div id="pie_chart" class="apex-charts" dir="ltr"></div>
                                    </div>
                                <?php } ?>

                                <div id="panelChart5">
                                    <div class="row card-title pl-lg pb-sm mb-4" style="border-bottom: 1px solid #a0a6ad"><?= lang('my_leave') . ' ' . lang('report') ?></div>
                                    <div id="pie_chart_my" class="apex-charts" dir="ltr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$all_category = get_result('tbl_leave_category');
$color = array('37bc9b', '7266ba', 'f05050', 'ff902b', '7266ba', 'f532e5', '5d9cec', '7cd600', '91ca00', 'ff7400', '1cc200', 'bb9000', '40c400');
if (!empty($all_category)) {  ?>
    <script type="text/javascript">
        
        <?php if (!empty($leave_report)) { ?>

            // pie chart
            $(document).ready(function() {
                var options = {
                    chart: {
                      height: 320,
                      type: 'pie',
                    }, 
                    series: [ <?php if (!empty($all_category)) { foreach ($all_category as $key => $v_category) {
                            if (!empty($leave_report['leave_taken'][$key])) {
                                    $all_report = $leave_report['leave_taken'][$key]; ?> <?= $all_report ?> <?php }  }  } ?>],

                    labels: [ <?php if (!empty($all_category)) {  foreach ($all_category as $key => $v_category) {
                                if (!empty($leave_report['leave_taken'][$key])) {  ?> "<?= $v_category->leave_category . ' ( <small>' . lang('quota') . ': ' . $leave_report['leave_quota'][$key] . ' ' . lang('taken') . ': ' . $all_report . '</small>)' ?>", <?php } } } ?> ],

                    colors: [ <?php if (!empty($all_category)) { foreach ($all_category as $key => $v_category) {
                            if (!empty($leave_report['leave_taken'][$key])) {  ?> "#<?= $color[$key] ?>", <?php } } } ?> ],

                    legend: {
                      show: true,
                      position: 'bottom',
                      horizontalAlign: 'center',
                      verticalAlign: 'middle',
                      floating: false,
                      fontSize: '14px',
                      offsetX: 0,
                    },

                    responsive: [{
                      breakpoint: 600,
                      options: {
                          chart: {
                              height: 240
                          },
                          legend: {
                              show: false
                          },
                      }
                   }]
                }
                var chart = new ApexCharts(
                  document.querySelector("#pie_chart"),
                  options
                );
                chart.render();
            });

        <?php  } ?>

        <?php   if (!empty($my_leave_report)) { ?>
                
            // pie chart
            $(document).ready(function() {
                var options = {
                    chart: {
                      height: 320,
                      type: 'pie',
                    }, 
                    series: [ <?php if (!empty($all_category)) { foreach ($all_category as $key => $v_category) {
                            if (!empty($my_leave_report['leave_taken'][$key])) {
                                    $result = $my_leave_report['leave_taken'][$key]; ?> <?= $result ?> <?php }  }  } ?>],

                    labels: [ <?php if (!empty($all_category)) {  foreach ($all_category as $key => $v_category) {
                                if (!empty($my_leave_report['leave_taken'][$key])) {  ?> "<?= $v_category->leave_category . ' ( <small>' . lang('quota') . ': ' . $my_leave_report['leave_quota'][$key] . ' ' . lang('taken') . ': ' . $result . '</small>)' ?>", <?php } } } ?> ],

                    colors: [ <?php if (!empty($all_category)) { foreach ($all_category as $key => $v_category) {
                            if (!empty($my_leave_report['leave_taken'][$key])) { ?> "#<?= $color[$key] ?>", <?php } } } ?> ],

                    legend: {
                      show: true,
                      position: 'bottom',
                      horizontalAlign: 'center',
                      verticalAlign: 'middle',
                      floating: false,
                      fontSize: '14px',
                      offsetX: 0,
                    },

                    responsive: [{
                      breakpoint: 600,
                      options: {
                          chart: {
                              height: 240
                          },
                          legend: {
                              show: false
                          },
                      }
                   }]
                }
                var chart = new ApexCharts(
                  document.querySelector("#pie_chart_my"),
                  options
                );
                chart.render();
            });

        <?php  }  ?>
    </script>

<!-- apexcharts -->
<script src="<?php echo base_url();?>skote_assets/libs/apexcharts/apexcharts.min.js"></script>
<?php } ?>