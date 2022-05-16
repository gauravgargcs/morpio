<div class="modal-header">
    <h5 class="modal-title"><?= lang('users') . ' ' . lang('list') . ' ' . lang('by') . ' ' . lang('designation') . ':' . $designation_info->designations ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php if (!empty($users_info)) { ?>
<div class="modal-body wrap-modal wrap">
    <div class="row">
        <div class="col-sm-12">
            <div data-simplebar style="max-height: 500px;">  
                <table class="table">
                    <?php foreach ($users_info as $key => $v_user) {
                        $v_user = get_staff_details($v_user->user_id);
                        ?>
                        <tr>
                            <td><?= $key + 1 . '. <a href="' . base_url('admin/user/user_details/' . $v_user->user_id) . '">' . fullname($v_user->user_id) ?>
                                <?php
                                if ($v_user->role_id == 1) {
                                    echo '<span class="badge btn-danger">' . lang('admin') . '</span>';
                                } else if ($v_user->role_id == 3) {
                                    echo '<span class="badge btn-primary">' . lang('staff') . '</span>';
                                }
                                ?>
                                </a></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="mb-3">
        <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
    </div>
</div>
<?php } ?>