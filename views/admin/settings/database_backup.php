<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    <div class="card-body">
        <div class="pull-right float-end">
            <a href="<?= base_url() ?>admin/settings/restore_database" class="btn btn-sm btn-primary"
               data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal">
                <i class="fa fa-upload "></i> <?= lang('restore_database') ?>
            </a>
            <a href="<?= base_url() ?>admin/settings/db_backup" class="btn btn-sm btn-secondary"><i class="fa fa-download"></i> <?= lang('database_backup') ?></a>
        </div>
        <h4 class="card-title mb-4"><?= lang('database_backup') ?></h4>
        
        <div class="table-responsive">
            <table class="table table-striped dt-responsive w-100" id="datatable-buttons" cellspacing="0" width="100%">
                <thead>
                    <th><?= lang('date'); ?></th>
                    <th><?= lang('file_name'); ?></th>
                    <th><?= lang('Type'); ?></th>
                    <th><?= lang('action'); ?></th>
                </thead>
                <tbody>
                    <?php
                    
                    if (isset($backups)) {
                        arsort($backups);
                        foreach ($backups as $key => $file):
                            // $filename = explode("_", $file);
                            $file =  (array)$file;
                            ?>
                            <tr>
                                <td><?php echo date('d-m-Y H-i', strtotime($file['created_at'])); ?></td>
                                <td><?php echo str_replace('-', ' &nbsp ', $file['file']); ?></td>
                                <td><?php echo str_replace('-', ' &nbsp ', $file['type']); ?></td>
                                <td>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-outline-secondary btn-sm"  href="<?= base_url() ?>admin/settings/download_backup/<?=  $file['id'] ?>" target="_blank"  title="<?= lang('download'); ?>"><i class="fa fa-download"></i></a>
                                    <?= btn_delete('admin/settings/delete_backup/' . $file['id']) ?>
                                </td>
                            </tr>
                        
                        <?php endforeach; 
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>