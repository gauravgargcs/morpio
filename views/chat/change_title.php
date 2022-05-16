<div class="modal-header">
    <h5 class="modal-title"><?= lang('edit') . ' ' . lang('name') . ' ' . lang('for') . ' ' . fullname($chat_details->to_user_id) ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body wrap-modal wrap">
    <div class="row mb-3">
        <label class="col-lg-3 form-label"><?= lang('change') . ' ' . lang('title') ?></label>
        <div class="col-lg-8">
            <?php
            //$check_global = get_row('tbl_private_chat', array('private_chat_id' => $chat_details->private_chat_id));
            $strong = str_replace("<strong>", "", $chat_details->title);
            $title = str_replace("</strong>", "", $strong); ?>
            <input type="text" id="title" name="title" value="<?= $title ?>" class="form-control" placeholder="<?= fullname($chat_details->to_user_id) ?>">
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="mb-3">
        <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
         <button type="submit" data-chat_id="<?= $chat_details->private_chat_id ?>" data-id="<?= $chat_details->private_chat_users_id ?>"  class="btn btn-primary  w-md waves-effect waves-light changed"><?= lang('save') ?></button>           
    </div>
</div>
        
<script type="text/javascript">

    $(document).ready(function () {
        $(".arrow").click(function () {
            $(this).closest(".container").find(".box").slideToggle();
        });
        $('.changed').click(function () {
            var chat_users_id = $(this).data().id;
            var chat_id = $(this).data().chat_id;
            var title = $('#title').val();
            var formData = {
                'chat_users_id': chat_users_id,
                'title': title,
            };
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= base_url()?>chat/change_title/' + chat_users_id, // the url where we want to POST
                data: formData, // our data object
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
                success: function (res) {
                    if (res) {
                        $('#myModal').modal('hide');
                        $('#open_chat_box_' + chat_id).parent().find('.chat_title').html(title);
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })

        });

    })
    ;
</script>