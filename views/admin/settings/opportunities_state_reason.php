<?php 
$created = can_action('129', 'created');
$edited = can_action('129', 'edited');
$deleted = can_action('129', 'deleted');
?>
<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    <div class="card-body">
        <h4 class="card-title mb-4"><?= lang('opportunities_state_reason') ?></h4>
        <div class="table-responsive">
            <table class="table table-striped dt-responsive w-100">
                <thead>
                <tr>
                    <th class="col-sm-3"><?= lang('opportunities_state') ?></th>
                    <th><?= lang('reason') ?></th>
                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                        <th class="col-sm-2"><?= lang('action') ?></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $all_opportunities_state_reason = get_result('tbl_opportunities_state_reason');
                if (!empty($all_opportunities_state_reason)) {
                    foreach ($all_opportunities_state_reason as $opportunities_state) {
                        ?>
                        <tr id="state_reason_<?= $opportunities_state->opportunities_state_reason_id ?>">
                            <td>
                                <?php
                                $id = $this->uri->segment(4);
                                if (!empty($id) && $id == $opportunities_state->opportunities_state_reason_id) { ?>
                                <form method="post"
                                      action="<?= base_url() ?>admin/settings/update_state_reason/<?= $opportunities_state->opportunities_state_reason_id ?>">
                                    <select name="opportunities_state" class="form-select">
                                        <option
                                            value="open" <?= $state_info->opportunities_state == 'open' ? 'selected' : '' ?>><?= lang('open') ?></option>
                                        <option
                                            value="won" <?= $state_info->opportunities_state == 'won' ? 'selected' : '' ?>><?= lang('won') ?></option>
                                        <option
                                            value="abandoned" <?= $state_info->opportunities_state == 'abandoned' ? 'selected' : '' ?>><?= lang('abandoned') ?></option>
                                        <option
                                            value="suspended" <?= $state_info->opportunities_state == 'suspended' ? 'selected' : '' ?>><?= lang('suspended') ?></option>
                                        <option
                                            value="lost" <?= $state_info->opportunities_state == 'lost' ? 'selected' : '' ?>><?= lang('lost') ?></option>
                                    </select>
                                <?php } else {
                                    echo lang($opportunities_state->opportunities_state);
                                }
                                ?></td>
                            <td>
                                <?php
                                $id = $this->uri->segment(4);
                                if (!empty($id) && $id == $opportunities_state->opportunities_state_reason_id) { ?>
                                    <input
                                        name="opportunities_state_reason"
                                        value="<?= $state_info->opportunities_state_reason ?>"
                                        class="form-control"/>
                                <?php } else {
                                    echo $opportunities_state->opportunities_state_reason;
                                }
                                ?>

                            </td>
                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <td>
                                    <?php
                                    $id = $this->uri->segment(4);
                                    if (!empty($id) && $id == $opportunities_state->opportunities_state_reason_id) { ?>
                                        <?= btn_update() ?>
                                        </form>
                                        <?= btn_cancel('admin/settings/opportunities_state_reason/') ?>
                                    <?php } else {
                                        if (!empty($edited)) { ?>
                                            <?= btn_edit('admin/settings/opportunities_state_reason/' . $opportunities_state->opportunities_state_reason_id) ?>
                                        <?php }
                                        if (!empty($deleted)) { ?>
                                            <?php echo ajax_anchor(base_url("admin/settings/delete_state_reason/" . $opportunities_state->opportunities_state_reason_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#state_reason_" . $opportunities_state->opportunities_state_reason_id)); ?>
                                        <?php }
                                    }
                                    ?>
                                </td>
                            <?php } ?>
                        </tr>
                        <?php
                    }
                }
                if (!empty($created) || !empty($edited)) { ?>
                    <form method="post"
                          action="<?= base_url() ?>admin/settings/update_state_reason">
                        <tr>
                            <td><select name="opportunities_state" class="form-select">
                                    <option value="open"><?= lang('open') ?></option>
                                    <option value="won"><?= lang('won') ?></option>
                                    <option value="abandoned"><?= lang('abandoned') ?></option>
                                    <option value="suspended"><?= lang('suspended') ?></option>
                                    <option value="lost"><?= lang('lost') ?></option>
                                </select></td>
                            <td>
                                <input name="opportunities_state_reason" class="form-control"/>
                            </td>
                            <td>
                                <?= btn_add() ?>
                            </td>
                        </tr>
                    </form>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
