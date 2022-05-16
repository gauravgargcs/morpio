<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    <div class="card-body">
        <h4 class="card-title mb-4"><?= lang('payment_method') ?></h4>
        <div class="table-responsive">
            <table class="table table-striped dt-responsive w-100">
                <thead>
                <tr>

                    <th><?= lang('method_name') ?></th>
                    <th><?= lang('action') ?></th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($all_method_info)) {
                        foreach ($all_method_info as $v_method_info) {
                            ?>
                            <tr id="payment_methods_<?= $v_method_info->payment_methods_id ?>">
                                <td>
                                    <?php
                                    $id = $this->uri->segment(5);
                                    if (!empty($id) && $id == $v_method_info->payment_methods_id) { ?>
                                    <form method="post"
                                          action="<?= base_url() ?>admin/settings/payment_method/update_payment_method/<?php
                                          if (!empty($method_info)) {
                                              echo $method_info->payment_methods_id;
                                          }
                                          ?>" class="form-horizontal">
                                        <input type="text" name="method_name" value="<?php
                                        if (!empty($method_info)) {
                                            echo $method_info->method_name;
                                        }
                                        ?>" class="form-control" placeholder="<?= lang('method_name') ?>" required>
                                        <?php } else {
                                            echo $v_method_info->method_name;
                                        }
                                        ?>
                                </td>
                                <td>
                                    <?php
                                    $id = $this->uri->segment(5);
                                    if (!empty($id) && $id == $v_method_info->payment_methods_id) { ?>
                                        <?= btn_update() ?>
                                        </form>
                                        <?= btn_cancel('admin/settings/payment_method/') ?>
                                    <?php } else { ?>
                                        <?= btn_edit('admin/settings/payment_method/edit_payment_method/' . $v_method_info->payment_methods_id) ?>
                                        <?php echo ajax_anchor(base_url("admin/settings/delete_payment_method/" . $v_method_info->payment_methods_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#payment_methods_" . $v_method_info->payment_methods_id)); ?>
                                    <?php }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    <form method="post" action="<?= base_url() ?>admin/settings/payment_method/update_payment_method/<?php
                    if (!empty($method_info)) {
                        echo $method_info->payment_methods_id;
                    }
                    ?>" class="form-horizontal">
                        <tr>
                            <td><input type="text" name="method_name" class="form-control"
                                       placeholder="<?= lang('method_name') ?>" required></td>
                            <td><?= btn_add() ?></td>
                        </tr>
                    </form>
                </tbody>
            </table>
        </div>
    </div>
</div>
