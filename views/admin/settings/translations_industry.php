<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4"><i class="fa fa-cogs"></i> <?= lang('translations') ?> - <?= ucwords($language) ?></h4>
        <div class="row mt-5">
            <div class="table-responsive">
                <table id="table-translations-files" class="table table-striped b-t b-light AppendDataTables">
                    <thead>
                        <tr>
                            <th class="col-xs-2 no-sort"><?= lang('industries') ?></th>
                           <!--  <th class="col-xs-3"><?= lang('file') ?></th>
                            <th class="col-xs-4"><?= lang('progress') ?></th>
                            <th class="col-xs-1"><?= lang('done') ?></th>
                            <th class="col-xs-1"><?= lang('total') ?></th> -->
                            <th class="col-options no-sort col-xs-1"><?= lang('options') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($industries as $key => $industry) :
                        // $shortfile = str_replace("_lang.php", "", $file);
                        // $st = $translation_stats[$language]['files'][$shortfile];
                        // $fn = ucwords(str_replace("_", " ", $shortfile));
                        // $total = $st['total'];
                        // $translated = $st['translated'];
                        // $pc = intval(($translated / $total) * 1000) / 10;
                        ?>
                        <tr>
                            <td class=""><?=$industry ?></td>
                           
                          
                            <td class="">
                                <a class="btn btn-sm btn-outline-primary"
                                   href="<?= base_url() ?>admin/settings/industry_text_translations?industry=<?= $industry ?>"><i  class="fa fa-edit"></i></a>
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