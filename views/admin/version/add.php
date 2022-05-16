<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18">  <?php if(!$id){
              echo lang('Add New Update'); 
            }else{
              echo lang('update_veriosn'); 

            } ?> 
            </h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            	<h4>Details</h4>

            <?php echo form_open(base_url('admin/version/add/' . $id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
            	<div class="my-3 row">
                <label class="col-md-2 col-form-label text-end"><?= lang('Version') ?><span class="required">*</span></label>
                <div class="col-md-6">
                    
                        <input type="text" name="version"  required="" class="form-control " value="<?php
                           if (!empty($version->version)) {
                               echo $version->version;
                           } ?>" >
                   
                </div>
            </div>
            <div class="my-3 row">
                <label class="col-md-2 col-form-label text-end"><?= lang('title') ?><span class="required">*</span></label>
                <div class="col-md-6">
                    
                        <input type="text" name="title"  required="" class="form-control " value="<?php
                           if (!empty($version->title)) {
                               echo $version->title;
                           } ?>" >
                   
                </div>
            </div>
             <div class="my-3 row">
                <label class="col-md-2 col-form-label text-end"><?= lang('description') ?></label>
                <div class="col-md-6">
                    
                        <textarea name="description" id="elm1"  class="form-control textarea"><?php
                           if (!empty($version->description)) { echo $version->description;
                           } ?></textarea>
                   
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-md-2 col-form-label"></label>
                <div class="col-md-6">
                    <button type="submit" id="sbtn"
                            class="btn btn-primary"><?= lang('save') ?></button>
                             <a href="<?=site_url('admin/version');?>" 
                            class="btn btn-secondary"><?= lang('cancel') ?></a>
                </div>
            </div>
            <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>