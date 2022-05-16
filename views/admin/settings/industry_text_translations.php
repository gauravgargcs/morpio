<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<div class="card">
    <div class="card-body">
        <div class="clearfix">
            
        <h4 class="card-title mb-4 float-start"><i class="fa fa-cogs"></i> <?= lang('translations') ?> - <?= ucwords($industry) ?></h4>
        <a href="<?= base_url() ?>admin/settings/add_industry_text_translation?industry=<?= $industry ?>" class="btn btn-primary float-end"><?=lang('Add New');?></a>
        </div>
        <div class="row mt-5">
            <div class="table-responsive">
                <table id="table-translations-files" class="table table-striped b-t b-light AppendDataTables">
                    <thead>
                        <tr>
                            <!-- <th class="col-xs-2 no-sort"><?= lang('industries') ?></th> -->
                            <th class="col-xs-1"><?= lang('language') ?></th>
                            <th class="col-xs-3"><?= lang('Word') ?></th>
                            <th class="col-xs-4"><?= lang('Translation') ?></th>
                            
                            <th class="col-options no-sort col-xs-1"><?= lang('options') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($industry_text_translations as $key => $translation) :
                        
                        ?>
                        <tr>
                            

                            <td class=""><?=$translation['language'] ?></td>
                            <td class=""><?=$translation['word'] ?></td>
                            <td class=""><?=$translation['translation'] ?></td>
                           
                          
                            <td class="">
                                <a class="btn btn-sm btn-outline-primary"
                                   href="<?= base_url() ?>admin/settings/add_industry_text_translation/<?=$translation['id'] ?>?industry=<?= $industry ?>&lang=<?=$translation['language'] ?>"><i  class="fa fa-edit"></i></a>
                                     <a onclick="return confirm('Are you sure want to delete?')" class="btn btn-sm btn-outline-danger"
                                   href="<?= base_url() ?>admin/settings/delete_industry_text_translation/<?=$translation['id'] ?>"><i  class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $('#save-translation').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: base_url + 'admin/settings/set_translations/',
            type: 'POST',
            data: {json: JSON.stringify($('#form-strings').serializeArray())},
            success: function () {
                toastr.success("Translation Updated Successfully", "Response Status");
                location.reload();
            },
            error: function (xhr) {
                alert('Error: ' + JSON.stringify(xhr));
            }
        });
    });
    $(document).ready(function () {
        $('#Transation_DataTables').dataTable({
            paging: false
        });
    });
</script>