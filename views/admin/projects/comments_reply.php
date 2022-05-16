<?php
$comment_reply_type = 'new-projects-reply';
if (!empty($comment_reply_details)) {
    foreach ($comment_reply_details as $v_reply) {
        $r_profile_info = $this->db->where(array('user_id' => $v_reply->user_id))->get('tbl_account_details')->row();
        $reply_info = $this->db->where(array('task_comment_id' => $v_reply->comments_reply_id))->get('tbl_task_comment')->row();
        ?>
<div class="d-flex pt-3" id="<?php echo $comment_reply_type . "-comment-form-container-" . $v_reply->task_comment_id ?>">
    <div class="flex-shrink-0 me-3">
        <div class="avatar-xs">
            <img src="<?php echo base_url() . $r_profile_info->avatar ?>" alt="" class="img-fluid d-block rounded-circle">
        </div>
    </div>
    <div class="flex-grow-1">
        <h5 class="font-size-14 mb-1">
            <a href="<?= base_url() ?>admin/user/user_details/<?= $v_reply->user_id ?>"> <?= ($r_profile_info->fullname) ?></a>
            <small class="text-muted float-end">
                <?= time_ago($v_reply->comment_datetime) ?>
                <?php if ($v_reply->user_id == $this->session->userdata('user_id')) { ?>
                    <?php echo ajax_anchor(base_url("admin/projects/delete_comments/" . $v_reply->task_comment_id), "<i class='text-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#" . $comment_reply_type . "-comment-form-container-" . $v_reply->task_comment_id)); ?>
                <?php } ?>
            </small></h5>
        <p class="text-muted"><?php if (!empty($v_reply->comment)) echo $v_reply->comment; ?></p>
    </div>
</div>
<?php } } ?>