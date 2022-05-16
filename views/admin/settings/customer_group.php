<?php 
$created = can_action('125', 'created');
$edited = can_action('125', 'edited');
$deleted = can_action('125', 'deleted');
?>
<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    <div class="card-body">
        <h4 class="card-title mb-4"><?= lang('customer_group') ?></h4>
        <div class="table-responsive">
            <table class="table table-striped dt-responsive w-100">
                <thead>
                <tr>
                    <th><?= lang('customer_group') ?></th>
                    <th><?= lang('description') ?></th>
                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                        <th><?= lang('action') ?></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($all_customer_group)) {
                    foreach ($all_customer_group as $customer_group) {
                        $total_client = count($this->db->where('customer_group_id', $customer_group->customer_group_id)->get('tbl_client')->result());
                        ?>
                        <tr id="customer_group_<?= $customer_group->customer_group_id ?>">
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $customer_group->customer_group_id) { ?>
                                <form method="post"
                                      action="<?= base_url() ?>admin/settings/customer_group/update_customer_group/<?php
                                      if (!empty($customer_group_info)) {
                                          echo $customer_group_info->customer_group_id;
                                      }
                                      ?>" class="form-horizontal">
                                    <input type="text" name="customer_group" value="<?php
                                    if (!empty($customer_group)) {
                                        echo $customer_group->customer_group;
                                    }
                                    ?>" class="form-control" placeholder="<?= lang('customer_group') ?>" required>
                                <?php } else {
                                    echo $customer_group->customer_group . '<p class="text-sm text-info m0 p0">' . lang('total') . ' ' . lang('client') . ' : ' . $total_client . '</p>';
                                }
                                ?></td>
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $customer_group->customer_group_id) { ?>
                                    <textarea name="description" rows="1" class="form-control"><?php
                                        if (!empty($customer_group)) {
                                            echo $customer_group->description;
                                        }
                                        ?></textarea>
                                <?php } else {
                                    echo $customer_group->description;
                                }
                                ?></td>
                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <td>
                                    <?php
                                    $id = $this->uri->segment(5);
                                    if (!empty($id) && $id == $customer_group->customer_group_id) { ?>
                                        <?= btn_update() ?>
                                        </form>
                                        <?= btn_cancel('admin/settings/customer_group/') ?>
                                    <?php } else {
                                        if (!empty($edited)) { ?>
                                            <?= btn_edit('admin/settings/customer_group/edit_customer_group/' . $customer_group->customer_group_id) ?>
                                        <?php }
                                        if (!empty($deleted)) { ?>
                                            <?php echo ajax_anchor(base_url("admin/settings/delete_customer_group/" . $customer_group->customer_group_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#customer_group_" . $customer_group->customer_group_id)); ?>
                                        <?php }
                                    } ?>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php }
                }
                if (!empty($created) || !empty($edited)) { ?>
                    <form method="post" action="<?= base_url() ?>admin/settings/customer_group/update_customer_group"
                          class="form-horizontal" data-parsley-validate="" novalidate="">
                        <tr>
                            <td><input type="text" name="customer_group" class="form-control"
                                       placeholder="<?= lang('customer_group') ?>" required></td>
                            <td>
                                <textarea name="description" rows="1" class="form-control"></textarea>
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
