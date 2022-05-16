<?= message_box('success'); ?>
<?= message_box('error'); ?>
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
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <div class="pull-right float-end">
                    <a href="<?= base_url() ?>admin/docroom" class="btn btn-sm btn-primary"><i class="fa fa-undo"> </i><?= ' ' . lang('docroom') ?></a>
                </div>
                <h4 class="card-title mb-4"><?=lang('docroom_settings');?></h4>
                <p class="text-info"> 
                    <em>Note: You can generate your API keys in your docroom account. </em>
                    <a href="https://www.thedocroom.com/account/edit" target="_blank">Click here</a>
                </p>
                <form role="form" data-parsley-validate="" novalidate=""  action="<?php echo base_url(); ?>admin/docroom/settings" method="post" class="form-horizontal mb-3 rows-bordered">
                    <?php
                    $form_error = $this->session->userdata('form_error');
                    $profile_info_2 = MyDetails();
                    if (!empty($form_error)) { ?>
                        <div role="alert" class="alert alert-danger alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <?= $this->session->userdata('form_error') ?>
                        </div>
                    <?php }
                    $this->session->unset_userdata('form_error');
                    ?>
                    <div class="mb-3 row">
                        <label class="col-lg-1 col-form-label"><?= lang('api_key1') ?></label>
                        <div class="col-lg-9">
                            <input type="text" name="api_key1" required value="<?php echo set_value('', $profile_info_2->docroom_api_key1); ?>" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-lg-1 col-form-label"><?= lang('api_key2') ?></label>
                        <div class="col-lg-9">
                            <input type="text" name="api_key2" required value="<?php echo set_value('', $profile_info_2->docroom_api_key2); ?>" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-lg-1 col-form-label"></label>
                        <div class="col-lg-9">
                            <div class="pull-left">
                                <button type="submit" class="btn btn-xs btn-primary"><?= lang('save_changes'); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>