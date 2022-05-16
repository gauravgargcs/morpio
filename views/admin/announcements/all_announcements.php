<?php echo message_box('success'); ?>

<?php echo message_box('error');

$created = can_action('100', 'created');

$edited = can_action('100', 'edited');

$deleted = can_action('100', 'deleted');

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
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($created)) { ?>
                <div class="pull-right hidden-print float-end">
                    <a href="<?= base_url() ?>admin/announcements/new_announcements" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal_lg">
                        <i class="fa fa-plus "></i> <?= ' ' . lang('new') . ' ' . lang('announcements') ?>
                    </a>
                </div>
                <?php } ?>
                <h4 class="card-title mb-4"><?= lang('all'). ' ' . lang('announcements')  ?></h4>
                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100" id="all_announcements_datatable">
                        <thead>

                            <tr>

                                <?php super_admin_opt_th() ?>

                                <th><?= lang('title') ?></th>

                                <th><?= lang('created_by') ?></th>

                                <th><?= lang('start_date') ?></th>

                                <th><?= lang('end_date') ?></th>

                                <?php $show_custom_fields = custom_form_table(16, null);

                                if (!empty($show_custom_fields)) {

                                    foreach ($show_custom_fields as $c_label => $v_fields) {

                                        if (!empty($c_label)) {

                                            ?>

                                            <th><?= $c_label ?> </th>

                                        <?php }

                                    }

                                }

                                ?>

                                <th><?= lang('status') ?></th>

                                <th><?= lang('action') ?></th>

                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($all_announcements)): 
                            foreach ($all_announcements as $v_announcements): ?>

                            <tr>

                                <?php super_admin_opt_td($v_announcements->companies_id) ?>

                                <td><?php echo $v_announcements->title; ?></td>

                                <td><?= fullname($v_announcements->user_id) ?></td>

                                <td><?= display_datetime($v_announcements->start_date) ?></td>

                                <td><?= display_datetime($v_announcements->end_date) ?></td>



                                <?php $show_custom_fields = custom_form_table(16, $v_announcements->announcements_id);

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

                                    <?php if ($v_announcements->status == 'unpublished') : ?>

                                        <span class="badge badge-soft-danger"><?= lang('unpublished') ?></span>

                                    <?php else : ?>

                                        <span class="badge badge-soft-success"><?= lang('published') ?></span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php echo btn_view_modal('admin/announcements/announcements_details/' . $v_announcements->announcements_id); ?>

                                    <?php if (!empty($edited)) { ?>

                                        <a href="<?= base_url() ?>admin/announcements/new_announcements/<?= $v_announcements->announcements_id ?>"  class="btn btn-primary btn-sm" title="<?= lang('edit') ?>" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal_lg"><span class="fa fa-pencil-square-o"></span>
                                        </a>

                                    <?php }

                                    if (!empty($deleted)) { ?>

                                        <?php echo btn_delete('admin/announcements/delete_announcements/' . $v_announcements->announcements_id); ?>

                                    <?php } ?>

                                </td>

                            </tr>

                            <?php
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

