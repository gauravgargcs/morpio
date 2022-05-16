<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); 
    $created = can_action('156', 'created');
    $edited = can_action('156', 'edited');
    $deleted = can_action('156', 'deleted');
    ?>
    <div class="card-body">
        <h4 class="card-title mb-4"><?= lang('manufacturer') ?></h4>
        <div class="table-responsive">
            <table class="table table-striped dt-responsive w-100" id="manuf_dtable">
                <thead>
                <tr>
                    <?= super_admin_opt_th() ?>
                    <th><?= lang('manufacturer') ?></th>
                    <th><?= lang('description') ?></th>
                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                        <th><?= lang('action') ?></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($all_manufacturer)) {
                    foreach ($all_manufacturer as $manufacturer) {
                        $total_manufacturer = count($this->db->where('manufacturer_id', $manufacturer->manufacturer_id)->get('tbl_saved_items')->result());
                        ?>
                        <tr id="manufacturer_<?= $manufacturer->manufacturer_id ?>">
                            <?php
                            $id = $this->uri->segment(5);
                            if (!empty($id) && $id == $manufacturer->manufacturer_id) { ?>
                                <form method="post" action="<?= base_url() ?>admin/settings/manufacturer/update_manufacturer/<?php if (!empty($manufacturer_info)) { echo $manufacturer_info->manufacturer_id;  }  ?>" class="form-horizontal">
                                <?= super_admin_inline($manufacturer_info->companies_id) ?>
                                </form>
                            <?php } else {  ?>
                                <?= super_admin_opt_td($manufacturer->companies_id) ?>
                            <?php } ?>
                                <td><?php
                                    $id = $this->uri->segment(5);
                                    if (!empty($id) && $id == $manufacturer->manufacturer_id) { ?>
                                        <input type="text" name="manufacturer" value="<?php
                                        if (!empty($manufacturer)) {
                                            echo $manufacturer->manufacturer;
                                        }
                                        ?>" class="form-control" placeholder="<?= lang('manufacturer') ?>" required>
                                    <?php } else {
                                        echo $manufacturer->manufacturer . '<p class="text-sm text-info m0 p0">' . lang('total') . ' ' . lang('purchase') . ' : ' . $total_manufacturer . '</p>';
                                    }
                                    ?>
                                </td>
                                <td><?php
                                    $id = $this->uri->segment(5);
                                    if (!empty($id) && $id == $manufacturer->manufacturer_id) { ?>
                                        <textarea name="description" rows="1" class="form-control"><?php
                                            if (!empty($manufacturer)) {
                                                echo $manufacturer->description;
                                            }
                                            ?></textarea>
                                    <?php } else {
                                        echo $manufacturer->description;
                                    }
                                    ?>
                                </td>
                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <td>
                                    <?php
                                    $id = $this->uri->segment(5);
                                    if (!empty($id) && $id == $manufacturer->manufacturer_id) { ?>
                                        <?= btn_update() ?>
                                        <?= btn_cancel('admin/settings/manufacturer/') ?>
                                    <?php } else {
                                    if (!empty($edited)) { ?>
                                        <?= btn_edit('admin/settings/manufacturer/edit_manufacturer/' . $manufacturer->manufacturer_id) ?>
                                    <?php }
                                    if (!empty($deleted)) { ?>
                                        <?php echo ajax_anchor(base_url("admin/settings/delete_manufacturer/" . $manufacturer->manufacturer_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#manufacturer_" . $manufacturer->manufacturer_id)); ?>
                                    <?php }
                                    } ?>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php }
                }
                if (!empty($created) || !empty($edited)) { ?>
                    <form method="post" action="<?= base_url() ?>admin/settings/manufacturer/update_manufacturer" class="form-horizontal" data-parsley-validate="" novalidate="">
                        <tr>
                            <?= super_admin_inline() ?>
                            <td><input type="text" name="manufacturer" class="form-control"
                                       placeholder="<?= lang('manufacturer') ?>" required></td>
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
