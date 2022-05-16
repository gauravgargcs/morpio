<div class="modal-header">
    <h5 class="modal-title"><?= lang('create_new').' '.lang('folder') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php 

$is_get=false;
if(isset($_GET['folder_id']) && isset($_GET['parentId']) && isset($_GET['folder_name'])){
    $get_folder_id=$_GET['folder_id'];
    $get_parentId=$_GET['parentId'];
    $get_folder_name=$_GET['folder_name'];
    $is_get=true;
}
?>

<?php echo form_open(base_url('admin/docroom/create_folder'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
    <div class="modal-body wrap-modal wrap">    
        <div class="row mb-3">
            <div class="col-sm-12">
                <div class="input-group">
                    <span class="input-group-text" id="option-date">
                        <?=$folder_path; ?> 
                    </span>
                    <input type="text" class="form-control" required name="folder_name" value="" aria-describedby="option-date">
                    <input type="hidden" name="folder_id" required value="<?php if($is_get){ echo $get_folder_id; } ?>" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('save') ?></button>            
        </div>
    </div>
<?php echo form_close(); ?>

<script type="text/javascript">
$(document).ready(function () {
    $("#folder_name").validate({
        rules: {
            folder_name: {
                required: true,
            }
        }
    });
});
</script>