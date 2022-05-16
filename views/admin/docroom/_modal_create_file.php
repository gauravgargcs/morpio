<div class="modal-header">
    <h5 class="modal-title"><?= lang('create_new').' '.lang('file') ?></h5>
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

<?php echo form_open(base_url('admin/docroom/create_file'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
    <div class="modal-body wrap-modal wrap">    
        <div class="row mb-3">
            <div class="col-sm-12">
                <h3 class="mb-4 text-center folder-name"><?=$folder_path; ?> </h3>
                <input class="form-control" type="file" name="manualFiles[]" id="formFile" multiple="multiple" />
                <input type="hidden" name="folder_id" required value="<?php if($is_get){ echo $get_folder_id; } ?>" class="form-control">
            </div>
        </div>
       <!--  <div class="mb-3 row">
            <div class="col-sm-12">
                <div id="file-dropzone" class="dropzone mb15">

                </div>
                <div data-simplebar style="max-height: 280px;">  
                    <div id="file-dropzone-scrollbar">
                        <div id="file-previews" class="row">
                            <div id="file-upload-row" class="col-sm-6 mt file-upload-row">
                                <div class="preview box-content pr-lg" style="width:100px;">
                                    <img data-dz-thumbnail class="upload-thumbnail-sm"/>
                                    <div class="mb progress progress-striped upload-progress-sm active mt-sm"
                                         role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                         aria-valuenow="0">
                                        <div class="progress-bar progress-bar-success" style="width:0%;"
                                             data-dz-uploadprogress></div>
                                    </div>
                                </div>
                                <div class="box-content">
                                    <p class="clearfix mb0 p0">
                                        <span class="name pull-left" data-dz-name></span>
                                <span data-dz-remove class="pull-right" style="cursor: pointer">
                                <i class="fa fa-times"></i>
                            </span>
                                    </p>
                                    <p class="clearfix mb0 p0">
                                        <span class="size" data-dz-size></span>
                                    </p>
                                    <strong class="error text-danger" data-dz-errormessage></strong>
                                    <input class="file-count-field" type="hidden" name="files[]" value=""/>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
    <div class="modal-footer" id="file-modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button  type="submit"  class="btn btn-primary start-upload"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>            
        </div>
    </div>
<?php echo form_close(); ?>