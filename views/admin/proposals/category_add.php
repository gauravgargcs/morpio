<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            

        </div>
    </div>
</div>
<!-- end page title -->


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body bordered">
             

                    
                        <h4 class="card-title mb-4"><?= lang('Details') ?></h4>
                        <form data-parsley-validate="" novalidate=""
                                  action="<?php echo base_url() ?>admin/proposals/template_add"
                                  method="post" class="form-horizontal mb-3 rows-bordered">


                                <div class="row mb-3">
                                    <label class="col-sm-3 control-label"><?= lang('category_name') ?><span
                                            class="required">*</span></label>
                                    <div class="col-sm-5">
                                        <input type="hidden" name="id" value="<?=$id;?>">
                                        <input type="text" name="category_name" list="category_name_list" required class="form-control"
                                               value="<?php if (!empty($template_list[0]->category_name)) echo $template_list[0]->category_name; ?>"/>
                                                
                                    </div>
                                </div>
                                  <div class="row mb-3">
                                    <label class="col-sm-3 control-label"><?= lang('Template ID') ?><span
                                            class="required">*</span></label>
                                   <!--  <div class="col-sm-5">
                                        <input type="text" name="unlayer_template_id" id="unlayer_template_id" required class="form-control" onchange="preview_template(this)" onkeyup="preview_template(this)"
                                               value="<?php if (!empty($template->unlayer_template_id)) echo $template->unlayer_template_id; ?>"/>
                                                <div id="templist"></div>
                                    </div> -->
                                     <div class="col-sm-5">
                                        <!-- <input type="text" name="unlayer_template_id" id="unlayer_template_id" required class="form-control" onchange="preview_template(this)" onkeyup="preview_template(this)"
                                               value="<?php if (!empty($template->unlayer_template_id)) echo $template->unlayer_template_id; ?>"/> -->
                                               <select class="form-control select_box" data-placeholder="Select template" multiple name="unlayer_template_id[]">
                                                   <?php if($unlayer_template_list) {

                                                        foreach ($unlayer_template_list as $key => $template_unlayer) { 
                                                                    $selected = "";

                                                            if ($template_list) {
                                                             foreach ($template_list as $key => $tmp) {
                                                                 if($tmp->unlayer_template_id == $template_unlayer['id']){
                                                                    $selected = "selected";
                                                                    break;
                                                                 }
                                                             }
                                                            }
                                                            
                                                                
                                                            ?>
                                                            <option value="<?=$template_unlayer['id'];?>" <?= $selected ;?>><?=$template_unlayer['name'];?></option>
                                                        <?php }
                                                    } ?>
                                               </select>
                                              
                                    </div>
                                </div>
                               


                               


                                <div class="row">
                                    <div class="text-center">
                                        <button type="submit" id="sbtn"
                                                class="btn btn-primary"><?= lang('save') ?></button>
                                                  <a href="<?=base_url('admin/proposals/template_list');?>" id="sbtn"
                                                class="btn btn-secondary"><?= lang('cancel') ?></a>
                                    </div>
                                </div>
                            </form>
                    </div>
                  
            </div>
        </div>
    </div>
  
