<?php
if (!empty($project_files_info)) {
    foreach ($project_files_info as $key => $v_files_info) {
        $uploaded_by = $this->db->where('user_id', $files_info[$key]->user_id)->get('tbl_account_details')->row();
        ?>
<div class="col-md-4 pr0 mb-sm" id="attachment_container-<?= $files_info[$key]->task_attachment_id ?>">
    <div class="card shadow border">
        <div class="card-body p-3">
            <div class="row">
                <div class="col-md-9 col-9">
                    <a href="<?= base_url() ?>admin/bugs/attachment_details/g/<?= $files_info[$key]->task_attachment_id ?>" data-bs-toggle="modal" data-bs-target="#myModal_extra_lg"><small class="text-gray-dark"><?= '<b style="color:#000">' . $uploaded_by->fullname . '</b><br/>' . ' ' . lang('uploaded') . '  ' . count($v_files_info) . ' ' . lang('attachment') ?> - <?= $files_info[$key]->title ?></small></a>
                </div>
                <div class="col-md-3 col-3">
                    <ul class="list-inline user-chat-nav text-end mb-0">
                            
                        <li class="list-inline-item  d-sm-inline-block">
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('download') . ' ' . lang('all') ?>" href="<?= base_url() ?>admin/bugs/download_all_files/<?= $files_info[$key]->task_attachment_id ?>" class="pull-right"><i class="fa fa-cloud-download"></i></a>
                        </li>

                        <?php if ($files_info[$key]->user_id == $this->session->userdata('user_id')) { ?>
                        <li class="list-inline-item  d-sm-inline-block">
                            <?php echo ajax_anchor(base_url("admin/bugs/delete_bug_files/" . $files_info[$key]->task_attachment_id), "<i class='text-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#attachment_container-" . $files_info[$key]->task_attachment_id)); ?>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php
            $limit = 3;
            shuffle($v_files_info);
            if (!empty($v_files_info)) {
                foreach ($v_files_info as $l_key => $v_files) {

                    if (!empty($v_files)) {
                        if ($l_key <= $limit) {
                            ?>
            <div class="col-xl-9 col-sm-6">
                <div class="card-body p-3">
                    <?php if ($v_files->is_image == 1) : ?>
                    <div class="me-3 mb-3">
                        <a data-bs-toggle="modal" data-bs-target="#myModal_extra_lg" href="<?= base_url() ?>admin/bugs/attachment_details/r/<?= $files_info[$key]->task_attachment_id . '/' . $v_files->uploaded_files_id ?>">
                            <img class="img-responsive avatar-sm" src="<?= base_url() . $v_files->files ?>" alt="">
                        </a>
                    </div>
                    <?php else : ?>
                    <div class="avatar-xs me-3 mb-3">
                        <div class="avatar-title bg-transparent rounded">
                            <a data-bs-toggle="modal" data-bs-target="#myModal_extra_lg" href="<?= base_url() ?>admin/bugs/attachment_details/r/<?= $files_info[$key]->task_attachment_id . '/' . $v_files->uploaded_files_id ?>">
                                <i class="bx bxs-folder font-size-24 text-warning"></i>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="d-flex">
                        <div class="overflow-hidden me-auto">
                            <h5 class="font-size-14 text-truncate mb-1">
                                <a data-bs-toggle="modal" data-bs-target="#myModal_large" href="<?= base_url() ?>admin/bugs/attachment_details/r/<?= $files_info[$key]->task_attachment_id . '/' . $v_files->uploaded_files_id ?>" class="name  text-body">
                                    <?php
                                    $fileName = (strlen($v_files->file_name) > 11) ? strip_tags(mb_substr($v_files->file_name, 0, 11)) . '...' : $v_files->file_name;
                                    echo $fileName;
                                    ?>
                                </a>
                            </h5>
                            <p class="text-muted text-truncate mb-0"><?= date('d-m-Y H-i', strtotime($files_info[$key]->upload_time)); ?></p>
                        </div>
                        <div class="align-self-end ms-2">
                            <p class="text-muted mb-0"><?= $v_files->size ?> <?= lang('kb') ?><a href="<?= base_url() ?>admin/bugs/download_files/<?= $v_files->uploaded_files_id ?>" class="pull-right"><i class="fa fa-cloud-download"></i></a></p>
                   
                            <?php if ($l_key == 3) {
                                $more = count($v_files_info) - 4;
                                if (!empty($more)) {
                                    ?><a
                                    data-bs-toggle="modal"
                                    data-bs-target="#myModal_extra_lg"
                                    href="<?= base_url() ?>admin/bugs/attachment_details/g/<?= $files_info[$key]->task_attachment_id ?>">
                                    <span
                                        class="more">+<?= $more ?></span>
                                    </a>
                                    <?php
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php } } } ?>
        </div>
    </div>
</div>            
<?php } ?>
<?php } ?>