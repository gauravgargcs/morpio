
<?php
$created = can_action('122', 'created');
$edited = can_action('122', 'edited');
$deleted = can_action('122', 'deleted');
?>
<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    <div class="card-body">
        <h4 class="card-title mb-4"><?= lang('leave_category') ?></h4>
        <div class="table-responsive">
            <table class="table table-striped dt-responsive w-100" id="">
                <thead>
                <tr>
                    <?= super_admin_opt_th() ?>
                    <th><?= lang('leave_category') ?></th>
                    <th><?= lang('quota') ?></th>
                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                        <th><?= lang('action') ?></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $all_leave_category = get_result('tbl_leave_category');
                if (!empty($all_leave_category)) {
                    foreach ($all_leave_category as $leave_category) {
                        ?>
                        <tr id="leave_category_<?= $leave_category->leave_category_id ?>">
                            <?php
                            $id = $this->uri->segment(5);
                            if (!empty($id) && $id == $leave_category->leave_category_id) { ?>
                            <form method="post"
                                  action="<?= base_url() ?>admin/settings/leave_category/update_leave_category/<?php
                                  if (!empty($leave_category_info)) {
                                      echo $leave_category_info->leave_category_id;
                                  }
                                  ?>" class="form-horizontal">
                                <?= super_admin_inline($leave_category->companies_id) ?>
                                <?php } else {
                                    ?>
                                    <?= super_admin_opt_td($leave_category->companies_id) ?>
                                <?php } ?>
                                <td><?php
                                    $id = $this->uri->segment(5);
                                    if (!empty($id) && $id == $leave_category->leave_category_id) { ?>
                                        <input type="text" name="leave_category" value="<?php
                                        if (!empty($leave_category_info)) {
                                            echo $leave_category_info->leave_category;
                                        }
                                        ?>" class="form-control" placeholder="<?= lang('leave_category') ?>" required>
                                    <?php } else {
                                        echo $leave_category->leave_category;
                                    }
                                    ?></td>
                                <td><?php
                                    $id = $this->uri->segment(5);
                                    if (!empty($id) && $id == $leave_category->leave_category_id) { ?>
                                        <input type="text" name="leave_quota" class="form-control" value="<?php
                                        if (!empty($leave_category_info)) {
                                            echo $leave_category_info->leave_quota;
                                        }
                                        ?>"/>
                                    <?php } else {
                                        echo $leave_category->leave_quota;
                                    }
                                    ?></td>
                                <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <td>
                                    <?php
                                    $id = $this->uri->segment(5);
                                    if (!empty($id) && $id == $leave_category->leave_category_id) { ?>
                                    <?= btn_update() ?>
                            </form>
                        <?= btn_cancel('admin/settings/leave_category/') ?>
                        <?php } else { ?>
                            <?php if (!empty($edited)) { ?>
                                <?= btn_edit('admin/settings/leave_category/edit_leave_category/' . $leave_category->leave_category_id) ?>
                            <?php } ?>
                            <?php if (!empty($deleted)) { ?>
                                <?php echo ajax_anchor(base_url("admin/settings/delete_leave_category/" . $leave_category->leave_category_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#leave_category_" . $leave_category->leave_category_id)); ?>
                            <?php }
                        }
                        ?>
                            </td>
                        <?php } ?>
                        </tr>
                        <?php
                    }
                }
                ?>
                <?php if (!empty($created) || !empty($edited)) { ?>
                    <form method="post" action="<?= base_url() ?>admin/settings/leave_category/update_leave_category"
                          class="form-horizontal" data-parsley-validate="" novalidate="">
                        <tr>
                            <?= super_admin_inline() ?>
                            <td><input type="text" name="leave_category" class="form-control"
                                       placeholder="<?= lang('leave_category') ?>" required></td>
                            <td>
                                <input name="leave_quota" placeholder="<?= lang('days') . ' / ' . lang('years') ?>"
                                       class="form-control"/>
                            </td>
                            <td><?= btn_add() ?></td>
                        </tr>
                    </form>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>