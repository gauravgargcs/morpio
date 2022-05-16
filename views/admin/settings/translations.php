<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php if (!empty($active_language)) : ?>
<div class="card">
    <div class="card-body">
        <div class="pull-right float-end">
            <form action="<?php echo base_url() ?>admin/settings/add_language" method="post" class="form-inline">
                <select class="form-control select_box" name="language">
                    <?php if (!empty($availabe_language)): foreach ($availabe_language as $v_availabe_language) : ?>
                        <option
                            value="<?= str_replace(" ", "_", $v_availabe_language->locale) ?>"><?= ucwords($v_availabe_language->name) ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <button type="submit" id="add-translation"
                        class="btn btn-dark custom-btn"><?= lang('add_translation') ?></button>
            </form>
        </div>
        <h4 class="card-title mb-4"><?= lang('translations') ?></h4>
        <div class="row mt-5">
            <div class="table-responsive">
                <table class="table table-striped dt-responsive w-100" id="datatable-buttons">
                    <thead>
                    <tr>
                        <th class="col-xs-1  "><?= lang('icon') ?></th>
                        <th class="col-xs-2"><?= lang('language') ?></th>
                        <th class="col-xs-4"><?= lang('progress') ?></th>
                        <th class="col-xs-1"><?= lang('done') ?></th>
                        <th class="col-xs-1"><?= lang('total') ?></th>
                        <th class="col-options   col-xs-2"><?= lang('action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!empty($active_language)):
                        foreach ($active_language as $v_language) :
                            $st = $translation_stats;
                            $total_data = $st[$v_language->name]['total'];
                            $translated_data = $st[$v_language->name]['translated'];

                            $view_status = intval(($translated_data / $total_data) * 1000) / 10;
                            ?>
                            <tr>
                                <td class=""><img src="<?= base_url('asset/images/flags/' . $v_language->icon) ?>.gif"/>
                                </td>
                                <td class=""><a
                                        href="<?= base_url() ?>admin/settings/translations/<?= $v_language->name ?>"><?= ucwords(str_replace("_", " ", $v_language->name)) ?></a>
                                </td>
                                <td>
                                    <div class="progress">
                                        <?php
                                        $status = 'danger';
                                        if ($view_status > 20) {
                                            $status = 'warning';
                                        }
                                        if ($view_status > 50) {
                                            $status = 'primary';
                                        }
                                        if ($view_status > 80) {
                                            $status = 'success';
                                        }
                                        ?>
                                        <div class="progress-bar progress-bar-<?= $status ?>" role="progressbar"
                                             aria-valuenow="<?= $view_status ?>" aria-valuemin="0" aria-valuemax="100"
                                             style="width: <?= $view_status ?>%;">
                                            <?= $view_status ?>%
                                        </div>
                                    </div>
                                </td>
                                <td class=""><?= $translated_data ?></td>
                                <td class=""><?= $total_data ?></td>
                                <?php
                                if ($v_language->active == 1) {
                                    $status = 1;
                                } else {
                                    $status = 0;
                                }
                                ?>
                                <td class="">
                                    <a data-bs-toggle="tooltip"
                                       title="<?= ($v_language->active == 1 ? lang('deactivate') : lang('activate')) ?>"
                                       class="active-translation btn btn-sm btn-outline-<?= ($v_language->active == 0 ? 'secondary' : 'success') ?>"
                                       href="<?= base_url() ?>admin/settings/translations_status/<?= $v_language->name ?>/<?= ($v_language->active == 1 ? 0 : 1) ?>"><i
                                            class="fa fa-check"></i></a>
                                    <a data-bs-toggle="tooltip" title="<?= lang('edit') ?>" class="btn btn-sm btn-outline-primary"
                                       href="<?= base_url() ?>admin/settings/translations/<?= $v_language->name ?>"><i
                                            class="fa fa-edit"></i></a>
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
<?php elseif (!empty($language_files)) : ?>
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4"><i class="fa fa-cogs"></i> <?= lang('translations') ?> - <?= ucwords($language) ?></h4>
        <div class="row mt-5">
            <div class="table-responsive">
                <table id="table-translations-files" class="table table-striped b-t b-light AppendDataTables">
                    <thead>
                        <tr>
                            <th class="col-xs-2 no-sort"><?= lang('type') ?></th>
                            <th class="col-xs-3"><?= lang('file') ?></th>
                            <th class="col-xs-4"><?= lang('progress') ?></th>
                            <th class="col-xs-1"><?= lang('done') ?></th>
                            <th class="col-xs-1"><?= lang('total') ?></th>
                            <th class="col-options no-sort col-xs-1"><?= lang('options') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($language_files as $file => $altpath) :
                        $shortfile = str_replace("_lang.php", "", $file);
                        $st = $translation_stats[$language]['files'][$shortfile];
                        $fn = ucwords(str_replace("_", " ", $shortfile));
                        $total = $st['total'];
                        $translated = $st['translated'];
                        $pc = intval(($translated / $total) * 1000) / 10;
                        ?>
                        <tr>
                            <td class=""><?= ($altpath == './system/' ? 'System' : 'Application') ?></td>
                            <td class=""><a
                                    href="<?= base_url() ?>admin/settings/edit_translations/<?= $language ?>/<?= $shortfile ?>"><?= $fn ?></a>
                            </td>
                            <td>
                                <div class="progress">
                                    <?php $bar = 'danger';
                                    if ($pc > 20) {
                                        $bar = 'warning';
                                    }
                                    if ($pc > 50) {
                                        $bar = 'info';
                                    }
                                    if ($pc > 80) {
                                        $bar = 'success';
                                    } ?>
                                    <div class="progress-bar progress-bar-<?= $bar ?>" role="progressbar"
                                         aria-valuenow="<?= $pc ?>" aria-valuemin="0" aria-valuemax="100"
                                         style="width: <?= $pc ?>%;">
                                        <?= $pc ?>%
                                    </div>
                                </div>
                            </td>
                            <td class=""><?= $translated ?></td>
                            <td class=""><?= $total ?></td>
                            <td class="">
                                <a class="btn btn-sm btn-outline-primary"
                                   href="<?= base_url() ?>admin/settings/edit_translations/<?= $language ?>/<?= $shortfile ?>"><i  class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php elseif (!empty($current_languages)) : ?>
    <?php $attributes = array('class' => 'bs-example form-horizontal', 'id' => 'form-strings');
    echo form_open_multipart('admin/settings/set_translations/' . $current_languages . '/' . $active_language_files, $attributes); ?>
    <input type="hidden" name="_language" value="<?= $current_languages ?>">
    <input type="hidden" name="_file" value="<?= $active_language_files ?>">

    <div class="card">
        <div class="card-body">
            <div class="pull-right float-end">
                <button type="submit" id="save-translation"
                    class="btn btn-sm btn-primary pull-right"><?= lang('save_translation') ?></button>
            </div>
            <h4 class="card-title mb-4"><i class="fa fa-cogs"></i>
                <?php
                $fn = ucwords(str_replace("_", " ", $active_language_files));
                $total = count($english);
                $translated = 0;
                if ($language == 'english') {
                    $percent = 100;
                } else {
                    foreach ($english as $key => $value) {
                        if (isset($translation[$key]) && $translation[$key] != $value) {
                            $translated++;
                        }
                    }
                    $percent = intval(($translated / $total) * 100);
                }
                ?> <?= lang('translations') ?> | <a style="color: red" href="<?= base_url() ?>admin/settings/translations/<?= $current_languages ?>"><?= ucwords(str_replace("_", " ", $current_languages)) ?></a>
            | <?= $percent ?>% <?= mb_strtolower(lang('done')) ?> </h4>
            
            <div class="table-responsive">
                <table id="" class="table table-striped b-t b-light ">
                    <thead>
                    <tr>
                        <th  style="width: 50%;" >English</th>
                        <th ><?= ucwords(str_replace("_", " ", $language)) ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($english as $key => $value) : ?>
                        <tr>
                            <td><?= $value ?></td>
                            <td><textarea class="form-control" name="<?= $key ?>"><?= (isset($translation[$key]) ? $translation[$key] : $value) ?></textarea>
                                       </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- End details -->
        </div>
    </div>
    </form>
<?php endif; ?>
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