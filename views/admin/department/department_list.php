<?= message_box('success'); ?>
<?php echo message_box('error');
$created = can_action('70', 'created');
$edited = can_action('70', 'edited');
$deleted = can_action('70', 'deleted');
?>
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
<div class="row mb-3">
    <div class="mb-lg pull-left">
        <div class="pull-left">
            <h4 class="card-title mb-4"><?= lang('all') . ' ' . lang('department') ?></h4>
        </div>
      
        <div class="float-end">
            <?php if (!empty($created)) { ?>
            <a class="btn btn-sm btn-primary" href="<?= base_url() ?>admin/departments/details"><?= lang('new_department') ?></a>
            <?php } ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <?php
                    if (!empty($all_department_info)): foreach ($all_department_info as $akey => $v_department_info) : ?>
                        <?php if (!empty($v_department_info)):
                            if (!empty($all_dept_info[$akey]->deptname)) {
                                $deptname = $all_dept_info[$akey]->deptname;
                            } else {
                                $deptname = lang('undefined_department');
                            }
                            $cp_email_user=$all_dept_info[$akey]->cp_email_user;
                            $cp_email_domain=$all_dept_info[$akey]->cp_email_domain;
                            $cp_email_password=decrypt($all_dept_info[$akey]->cp_email_password);
                            ?>
                            <div class="col-sm-6 mb-lg" id="table_department_<?= $all_dept_info[$akey]->departments_id ?>">
                                <div class="card border">
                                    <div class="card-body">
                                        <?php if (!empty($edited)) { ?>
                                        <div class="pull-right">
                                                <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                      title="<?= lang('edit') ?>">
                                                <a href="<?= base_url() ?>admin/departments/edit_departments/<?= $all_dept_info[$akey]->departments_id ?>"
                                                   class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                   data-bs-placement="top" data-bs-target="#myModal"><span
                                                        class="fa fa-pencil-square-o"></span></a>
                                                    </span>
                                            <?php echo ajax_anchor(base_url("admin/departments/delete_department/" . $all_dept_info[$akey]->departments_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_department_" . $all_dept_info[$akey]->departments_id)); ?>
                                        </div>
                                        <?php } ?>
                                        <h4 class="card-title mb-4"><?php echo $deptname ?></h4>
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <?php if(!empty($cp_email_domain) && !empty($cp_email_user)) {
                                                    echo '<span class="text-sm"><strong>' . lang('email'). '</strong>: '. $cp_email_user.'@'.$cp_email_domain. '</span><br/>';
                                                }else{
                                                    echo '<span class="text-sm"><strong>' . lang('email'). '</strong>: Not Found </span><br/>';
                                                } ?>
                                                
                                                <?php if (!empty($all_dept_info[$akey]->department_head_id)) {
                                                    echo '<span class="text-sm"><strong>' . lang('department_head') . '</strong>: <a href="' . base_url('admin/user/user_details/' . $all_dept_info[$akey]->department_head_id) . '">' . fullname($all_dept_info[$akey]->department_head_id) . '</a></span>';
                                                }
                                                super_admin_invoice($all_dept_info[$akey]->companies_id,5);
                                                ?>
                                                
                                                <!-- Table -->
                                                <table class="table table-striped dt-responsive nowrap w-100 dept_roles">
                                                    <thead>
                                                        <tr>
                                                            <td class="text-bold col-sm-1">#</td>
                                                            <td class="text-bold"><?= lang('designation') ?></td>
                                                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                                <td class="text-bold col-sm-2"><?= lang('action') ?></td>
                                                            <?php } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($v_department_info as $key => $v_department) :
                                                        if (!empty($v_department->designations)) {
                                                            $total_employee = count(get_result('tbl_account_details', array('designations_id' => $v_department->designations_id)));
                                                        ?>
                                                        <tr id="table_designation_<?= $v_department->designations_id ?>">
                                                            <td><?php echo $key + 1 ?></td>
                                                            <td>
                                                                <a data-bs-toggle="tooltip" data-bs-placement="top"
                                                                   title="<?= lang('set_full_permission') ?>"
                                                                   href="<?= base_url() ?>admin/departments/details/<?= $v_department->designations_id ?>"> <?php echo $v_department->designations ?></a>
                                                                <p class="m0">
                                                                    <a data-bs-toggle="modal" data-bs-target="#myModal" style="color:#656565"
                                                                       href="<?= base_url() ?>admin/departments/user_by_designation/<?= $v_department->designations_id ?>">
                                                                        <strong>
                                                                            <small><?= lang('total') . ' ' . lang('users') . ': ' . $total_employee ?></small>
                                                                        </strong>
                                                                    </a>
                                                                </p>
                                                            </td>
                                                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                                <td>
                                                                    <?php if (!empty($edited)) { ?>
                                                                        <?php echo btn_edit('admin/departments/details/' . $v_department->designations_id); ?>
                                                                    <?php }
                                                                    if (!empty($deleted)) { ?>
                                                                        <?php echo ajax_anchor(base_url("admin/departments/delete_designations/" . $v_department->designations_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child",  "data-fade-out-on-success" => "#table_designation_" . $v_department->designations_id)); ?>
                                                                    <?php } ?>
                                                                </td>
                                                            <?php } ?>
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
                                    </div>
                                </div>
                            </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
