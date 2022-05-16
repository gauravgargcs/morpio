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



<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body bordered">
                <!-- Tabs within a box -->
               
                <div class="row mb-3">
                    <div class="mb-lg pull-left">

                        <div class="float-end">
                           

                            <a class="btn btn-xs btn-primary mr-1" href="<?=site_url('admin/version/add');?>">Add New Update</a>
                           
                        </div>
                    </div>
                </div>
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" id="version_list" style="position: relative;">                  
                       
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="all_bugs_datatable">
                                <thead>
                                    <tr>
                                       
                                        <th><?= lang('Version') ?></th>
                                        <th><?= lang('title') ?></th>
                                        <th><?= lang('description') ?></th>
                                      
                                        <th><?= lang('created') ?></th>
                                       
                                            <th><?= lang('action') ?></th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($all_version)):foreach ($all_version as $key => $version):
                                     
                                        ?>
                                        <tr id="tr-<?=$version->id;?>">
                                             <td><?php echo $version->version; ?>
                                            </td>
                                            <td><?php echo $version->title; ?>
                                            </td>
                                             <td><p ><?php echo $version->description; ?></p>
                                            </td>
                                            <td><p ><?php echo date('Y-m-d', strtotime($version->created_at)); ?></p>
                                            </td>
                                           
                                          
                                         
                                         
                                                <td>
                                                    <?php 
                                                        echo btn_edit('admin/version/edit/' . $version->id);

                                                    
                                                         echo ajax_anchor(base_url("admin/version/delete/" . $version->id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "mx-1", "title" => lang('delete'), "data-remove-on-success" => "#tr-".$version->id)); ?>
                                                  
                                                   
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
    </div>
</div>
