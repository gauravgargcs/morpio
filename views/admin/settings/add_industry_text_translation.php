<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
   
    
    <form method="post" action="<?php echo base_url() ?>admin/settings/add_industry_text_translation/<?=$id;?>" class="form-horizontal">
        <div class="card-body">
            <h4 class="card-title mb-4"><?= lang('translations') ?></h4>
            <input type="hidden" name="settings" value="<?= $load_setting ?>">
             <div class="row mb-3">
                    <label class="col-lg-3 col-form-label"><?= lang('industry') ?></label>
                    <div class="col-lg-6">
                        <select name="industry_type" class="form-select">
                           
                             <?php if($industries = get_industries()){
                            foreach ($industries as $key => $industry_name) {
                                ?>
                                   <option value="<?=$industry_name;?>" <?=($industry_name==$industry)?'selected':'';?> ><?=$industry_name;?></option>
                                <?php
                            }
                          } ?>
                        </select>
                    </div>
                </div>
                 <div class="row mb-3">
                    <label class="col-lg-3 col-form-label"><?= lang('language') ?></label>
                    <div class="col-lg-6">
                        <select name="language" class="form-select">
                               <?php if($active_language){
                            foreach ($active_language as $key => $lang_data) {
                                ?>
                                   <option value="<?=$lang_data->name;?>" <?=($lang==$lang_data->name)?'selected':'';?> ><?=ucfirst($lang_data->name);?></option>
                                <?php
                            }
                          } ?>
                           
                        </select>
                    </div>
                </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('Word') ?> <span
                        class="text-danger">*</span></label>
                <div class="col-lg-6">
                    <input type="text" required="" class="form-control" value="<?=$word;?>" name="word" data-type="Word" data-required="true">
                </div>
            </div>
             <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('translation') ?> <span
                        class="text-danger">*</span></label>
                <div class="col-lg-6">
                    <input type="text" required="" class="form-control" value="<?=$translation;?>" name="translation"  data-required="true">
                </div>
            </div>
            

           

            
               
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"></label>
                <div class="col-lg-6">
                    <button type="submit" class="btn btn-xs btn-primary"><?= lang('save_changes') ?></button>
                </div>
            </div>
        </div>
    </form>
</div>
