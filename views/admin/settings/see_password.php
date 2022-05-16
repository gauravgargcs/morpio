<div class="modal-header">
    <h5 class="modal-title"><?= lang('see_password') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="form-group">
        <div class="col-lg-12">
            <input type="password" class="form-control" placeholder="<?= lang('enter') . ' ' . lang('your') . ' ' . lang('current') . ' ' . lang('password') ?>"  name="my_password">
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="mb-3">
        <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
        <a class="btn btn-primary w-md waves-effect waves-light check_current_password"><?= lang('update') ?></a>            
    </div>
</div>

<script>
    function handle_error(element, error_res) {
        $(element).html(error_res);
        $(element).show();
    }

    function remove_error(element) {
        $(element).empty();
    }

    $('#myModal').on('loaded.bs.modal', function () {
        <?php if(empty($activationtoken)){?>
        setTimeout(function () {
            $('#show_password').fadeOut('fast');
        }, 10000);
        <?php } ?>
        
    });
    $('body').on('click', '.check_current_password', function () {
            var my_password = $('input[name="my_password"]').val();
            var encrypt_password = '<?= $password?>';
            var row = '<?= $row ?>';
           
            $.ajax({
                url: base_url + "admin/global_controller/check_current_password/",
                type: "POST",
                data: {
                    name: my_password,
                    row: row,
                    encrypt_password: encrypt_password,
                },
                dataType: 'json',
                success: function (res) {
                    console.log(res);
                    if (res.error) {
                        handle_error("#hosting_password", res.error);
                        return;
                    } else {
                        remove_error("#hosting_password");
                        handle_error("#show_password", res.password);
                        $('#myModal').modal('hide');
                        return;
                    }
                }
            });
        });
</script>