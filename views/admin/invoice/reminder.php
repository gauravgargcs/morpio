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
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="#manage" data-bs-toggle="tab"><?= lang('reminder') . ' ' . lang('list') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab"><?= lang('set') . ' ' . lang('reminder') ?></a>
                    </li>
                </ul>
                <div class="tab-content bg-white">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="invoice_reminder_datatable">
                                <thead>
                                <tr>
                                    <th><?= lang('description') ?></th>
                                    <th><?= lang('date') ?></th>
                                    <th><?= lang('remind') ?></th>
                                    <th><?= lang('notified') ?></th>
                                    <th class="col-options no-sort"><?= lang('action') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($all_reminder)) {
                                        foreach ($all_reminder as $v_reminder):
                                        $remind_user_info = $this->db->where('user_id', $v_reminder->user_id)->get('tbl_account_details')->row();
                                        ?>
                                        <tr id="table_reminder_<?= $v_reminder->reminder_id ?>">
                                            <td><?= $v_reminder->description ?></td>
                                            <td><?= display_datetime($v_reminder->date) ?></td>
                                            <td>
                                                <a href="<?= base_url() ?>admin/user/user_details/<?= $v_reminder->user_id ?>"> <?= $remind_user_info->fullname ?></a>
                                            </td>
                                            <td><?= $v_reminder->notified ?></td>
                                            <td>
                                                <?php echo ajax_anchor(base_url("admin/invoice/delete_reminder/" . $v_reminder->module . '/' . $v_reminder->module_id . '/' . $v_reminder->reminder_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child",  "data-fade-out-on-success" => "#table_reminder_" . $v_reminder->reminder_id)); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                        <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form"
                              action="<?php echo base_url(); ?>admin/invoice/reminder/<?= $module ?>/<?= $module_id ?>/<?php
                              if (!empty($reminder_info)) {
                                  echo $reminder_info->reminder_id;
                              }
                              ?>" method="post" class="form-horizontal  ">
                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label"><?= lang('date_to_notified') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <input type="text" name="date"
                                               class="form-control datepicker"
                                               value="<?php
                                               if (!empty($reminder_info->date)) {
                                                   echo date('d-m-Y H-i', strtotime($reminder_info->date));
                                               } else {
                                                   echo date('d-m-Y H-i');
                                               }
                                               ?>"
                                               data-date-min-date="<?= date('d-m-Y H-i'); ?>">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <!-- End discount Fields -->
                            <div class="row mb-3 terms">
                                <label class="col-lg-3 col-form-label"><?= lang('description') ?> </label>
                                <div class="col-lg-5">
                                    <textarea name="description" class="form-control"><?php
                                        if (!empty($reminder_info)) {
                                            echo $reminder_info->description;
                                        }
                                        ?></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label"><?= lang('set_reminder_to') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <select class="form-control select_box" name="user_id" style="width: 100%">
                                        <?php
                                        $all_user = get_result('tbl_users', array('role_id !=' => 2));
                                        foreach ($all_user as $v_users) {
                                            $profile = $this->db->where('user_id', $v_users->user_id)->get('tbl_account_details')->row();
                                            if (!empty($profile)) {
                                                ?>
                                                <option <?php
                                                if (!empty($reminder_info)) {
                                                    echo $reminder_info->user_id == $v_users->user_id ? 'selected' : null;
                                                }
                                                ?> value="<?= $v_users->user_id ?>"><?= $profile->fullname ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 terms">
                                <label class="col-lg-3 col-form-label"></label>
                                <div class="col-lg-5">
                                    <div class="form-check form-check mb-3 mr-5">
                                        <input type="checkbox" value="Yes"
                                                <?php if (!empty($reminder_info) && $reminder_info->notify_by_email == 'Yes') {
                                                    echo 'checked';
                                                } ?> name="notify_by_email" id="notify" class="form-check-input">
                                        <label class="form-check-label" for="notify"><?= lang('send_also_email_this_reminder') ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label"></label>
                                <div class="col-lg-5">
                                    <button type="submit" class="btn btn-primary"><?= lang('update') ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

