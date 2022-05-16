<div class="modal-header">
    <h5 class="modal-title"><?= lang('new') . ' ' . lang('status') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs bg-light rounded" role="tablist">
            <li class="nav-item waves-light">
                <a class="nav-link <?= $active == 1 ? 'active' : ''; ?>"  href="#manage" data-bs-toggle="tab"><?= lang($status) . ' ' . lang('list') ?></a>
            </li>
            <li class="nav-item waves-light">
                <a class="nav-link <?= $active == 2 ? 'active' : ''; ?>"  href="#create" data-bs-toggle="tab"><?= lang('new') . ' ' . lang($status) ?></a>
            </li>
        </ul>
        <div class="tab-content p-3 text-muted">
            <!-- ************** general *************-->
            <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100" id="list_status_datatable">
                        <thead>
                        <tr>
                            <th><?= $status ?></th>
                            <th class="col-options no-sort"><?= lang('action') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($all_status)) {
                            foreach ($all_status as $v_status):

                                $v_status->id = (!empty($v_status->status) ? $v_status->status_id : $v_status->priority_id);

                                ?>
                                <tr>
                                    <td><?= !empty($v_status->status) ? $v_status->status : $v_status->priority ?></td>
                                    <td>
                                        <?= btn_delete('admin/settings/delete_status/' . $status . '/' . $v_status->id); ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        } 
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form"
                      action="<?php echo base_url(); ?>admin/settings/manage_status/<?= $status ?>/<?php
                      if (!empty($reminder_info)) {
                          echo $reminder_info->reminder_id;
                      }
                      ?>" method="post" class="form-horizontal  ">
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label"><?= lang($status) ?> <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-5">
                            <input type="text" name="status" class="form-control" value="">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label"></label>
                        <div class="col-lg-5">
                            <button type="submit" class="btn btn-primary"><?= lang('save') ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="mb-3">
        <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
    </div>
</div>
<script type="text/javascript">
    $('#list_status_datatable').DataTable();

</script>