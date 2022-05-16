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
             
                        <div class="clearfix">
                        <h4 class="card-title mb-4 float-start"><?= lang('Template Category') ?></h4>
                            <a href="<?=base_url('admin/proposals/template_add');?>" class="btn btn-primary float-end"><?=lang('New Category ');?></a>
                        </div>
                    

                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="list_project_datatable">
                                <thead>
                                    <tr>
                                     
                                        <th><?= lang('Category Name') ?></th>
                                       
                                 
                                        <th><?= lang('action') ?></th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($template_list)):foreach ($template_list as $key => $template):
                                     
                                        ?>
                                        <tr id="table-template-<?= str_replace(' ','', $template['category_name']) ?>">
                                            
                                            <td><?=$template['category_name'];?></a></td>
                                            
                                         
                                            <td>                                                    
                                                        <?php echo btn_edit('admin/proposals/template_edit?cname=' . $template['category_name']) ?>
                                                   
                                                        <?php echo ajax_anchor(base_url("admin/proposals/delete_template?cname=" . $template['category_name']), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child",  "data-fade-out-on-success" => "#table-template-" . str_replace(' ','', $template['category_name']))); ?>
                                                   
                                                   
                                                </td>
                                          
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                  
            </div>
        </div>
    </div>

