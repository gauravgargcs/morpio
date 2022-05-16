<?php
$comment_reply_type = 'new-tickets-reply';
if (!empty($comment_reply_details)) {
    foreach ($comment_reply_details as $v_reply) {
        $r_profile_info = $this->db->where(array('user_id' => $v_reply->replierid))->get('tbl_account_details')->row();
        $reply_info = $this->db->where(array('ticket_reply_id' => $v_reply->ticket_reply_id))->get('tbl_tickets_replies')->row();
        ?>
<div class="d-flex pt-3" id="<?php echo $comment_reply_type . "-comment-form-container-" . $v_reply->tickets_replies_id ?>">
    <div class="flex-shrink-0 me-3">
        <div class="avatar-xs">
            <img src="<?php echo base_url() . $r_profile_info->avatar ?>" alt="" class="img-fluid d-block rounded-circle">
        </div>
    </div>
    <div class="flex-grow-1">
        <h5 class="font-size-14 mb-1">
            <a href="<?= base_url() ?>admin/user/user_details/<?= $v_reply->replierid ?>"> <?= ($r_profile_info->fullname) ?></a>
            <small class="text-muted float-end">
                <?= time_ago($v_reply->time) ?>
                <?php if ($v_reply->replierid == $this->session->userdata('user_id')) { ?>
                     <?php echo ajax_anchor(base_url("admin/tickets/delete/delete_ticket_replay/" . $v_reply->tickets_id . '/' . $v_reply->tickets_replies_id), "<i class='text-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#" . $comment_reply_type . "-comment-form-container-" . $v_reply->tickets_replies_id)); ?>
                <?php } ?>
            </small></h5>
        <p class="text-muted"><?php if (!empty($v_reply->body)) echo $v_reply->body; ?></p>
    </div>
</div>
<?php }
}
?>