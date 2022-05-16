<?php 
$created = can_action('128', 'created');
$edited = can_action('128', 'edited');
$deleted = can_action('128', 'deleted');
?>
<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    <div class="card-body">
        <h4 class="card-title mb-4"><?= lang('lead_source') ?></h4>
        <div class="table-responsive">
            <table class="table table-striped dt-responsive w-100">
                <thead>
                <tr>

                    <th><?= lang('lead_source') ?></th>
                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                        <th><?= lang('action') ?></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($all_lead_source)) {
                    foreach ($all_lead_source as $lead_source) {
                        $total_lead_source = count($this->db->where('lead_source_id', $lead_source->lead_source_id)->get('tbl_leads')->result());
                        ?>
                        <tr id="lead_source_<?= $lead_source->lead_source_id?>">
                            <td>
                                <?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $lead_source->lead_source_id) { ?>
                                <form method="post"
                                      action="<?= base_url() ?>admin/settings/lead_source/update_lead_source/<?php
                                      if (!empty($lead_source_info)) {
                                          echo $lead_source_info->lead_source_id;
                                      }
                                      ?>" class="form-horizontal">
                                    <input type="text" name="lead_source" value="<?php
                                    if (!empty($lead_source_info)) {
                                        echo $lead_source_info->lead_source;
                                    }
                                    ?>" class="form-control" placeholder="<?= lang('lead_source') ?>" required>

                                <?php } else {
                                    echo $lead_source->lead_source . '<p class="text-sm text-info m0 p0">' . lang('total') . ' ' . lang('leads') . ' : ' . $total_lead_source . '</p>';
                                }
                                ?></td>
                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <td>
                                    <?php
                                    $id = $this->uri->segment(5);
                                    if (!empty($id) && $id == $lead_source->lead_source_id) { ?>
                                        <?= btn_update() ?>
                                        </form>
                                        <?= btn_cancel('admin/settings/lead_source/') ?>
                                    <?php } else {
                                        if (!empty($edited)) { ?>
                                            <?= btn_edit('admin/settings/lead_source/edit_lead_source/' . $lead_source->lead_source_id) ?>
                                        <?php }
                                        if (!empty($deleted)) { ?>
                                            <?php echo ajax_anchor(base_url("admin/settings/delete_lead_source/" . $lead_source->lead_source_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#lead_source_" . $lead_source->lead_source_id)); ?>
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
                    <form method="post" action="<?= base_url() ?>admin/settings/lead_source/update_lead_source"
                          class="form-horizontal" data-parsley-validate="" novalidate="">
                        <tr>
                            <td><input type="text" name="lead_source" value="" class="form-control" placeholder="<?= lang('lead_source') ?>" required></td>
                            <td>
                                <button type="submit" class="btn btn-sm btn-primary custom-btn"></i> <?= lang('add') ?></button>
                            </td>
                        </tr>
                    </form>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
