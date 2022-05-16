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
            <div class="card-body">
               
                   
                    <ul class="nav nav-tabs bg-light rounded">
                        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="#manage"
                                                                            data-bs-toggle="tab"><?= lang('all') . ' ' . lang('partners') ?></a>
                        </li>
                        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="#create"
                                                                            data-bs-toggle="tab"><?= lang('new') . ' ' . lang('partners') ?></a>
                        </li>
                    </ul>
                    <div class="tab-content bg-white">
                     
                        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                            <div class="table-responsive">
                                <table class="table table-striped" id="">
                                    <thead>
                                    <tr>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('designation') ?></th>
                                        <th><?= lang('facebook_profile_link') ?></th>
                                        <th class="col-options no-sort"><?= lang('action') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody id="coupon">
                                    <?php
                                    $all_partners = get_order_by('tbl_partners', array('type'=>'partners'), 'id', true);
                                    if (!empty($all_partners)) {
                                        foreach ($all_partners as $partners) {
                                            ?>
                                            <tr class="partners" data-id="<?= $partners->id ?>"
                                                id="table_partners_<?= $partners->id ?>">
                                                <td><?= $partners->name ?></td>
                                                <td><?= $partners->designation ?></td>
                                                <td><?= $partners->facebook ?></td>
                                                <td>
                                                    <div class="checkbox ajax_change_status">
                                                        <input data-id="<?= $partners->id ?>" data-bs-toggle="toggle"
                                                               name="status"
                                                               value="1" <?php if ($partners->status == 1) {
                                                                echo 'checked';
                                                            } ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>"
                                                               data-onstyle="success btn-xs"
                                                               data-offstyle="danger btn-xs" type="checkbox"/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url() ?>admin/frontend/partners/<?= $partners->id ?>"
                                                       class="btn btn-xs btn-primary"
                                                       data-bs-toggle="tooltip" title="<?= lang('edit') ?>"
                                                       data-bs-placement="top">
                                                        <i class="fa fa-pencil-square-o"></i></a>
                                                    <?php echo ajax_anchor(base_url("admin/frontend/delete/tbl_partners/$partners->id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_partners_" . $partners->id)); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                            <?php
                            if (!empty($partner_info)) {
                                $id = $partner_info->id;
                            } else {
                                $id = null;
                            }
                            echo form_open(base_url('admin/frontend/save_partners/' . $id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>

                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label"><?= lang('profile') ?>
                                    <span class="required">*</span></label>

                                <div class="col-sm-5">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 210px;">
                                            <?php if (!empty($partner_info->profile)) : ?>
                                                <img src="<?php echo base_url() . $partner_info->profile; ?>">
                                            <?php else: ?>
                                                <img src="http://placehold.it/350x260" alt="Please Connect Your Internet">
                                            <?php endif; ?>
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="width: 210px;"></div>
                                        <div>
                                                    <span class="btn btn-default btn-file">
                                                        <span class="fileinput-new">
                                                            <input type="file" name="profile" value="upload"
                                                                   data-buttonText="<?= lang('choose_file') ?>" id="myImg"/>
                                                            <span class="fileinput-exists"><?= lang('change') ?></span>
                                                        </span>
                                                        <a href="#" class="btn btn-default fileinput-exists"
                                                           data-dismiss="fileinput"><?= lang('remove') ?></a>
                                                       </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label"><?= lang('name') ?>
                                    <span class="required">*</span></label>

                                <div class="col-sm-5">
                                    <input required type="text" name="name"
                                           placeholder="<?= lang('enter') . ' ' . lang('name') ?>"
                                           class="form-control" value="<?php
                                    if (!empty($partner_info->name)) {
                                        echo $partner_info->name;
                                    }
                                    ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label"><?= lang('designation') ?>
                                    <span class="required">*</span></label>

                                <div class="col-sm-5">
                                    <input required type="text" name="designation"
                                           placeholder="<?= lang('enter') . ' ' . lang('designation') ?>"
                                           class="form-control" value="<?php
                                    if (!empty($partner_info)) {
                                        echo $partner_info->designation;
                                    }
                                    ?>"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label
                                    class="col-lg-3 col-md-3 col-sm-3 control-label"><?= lang('facebook_profile_link') ?></label>
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($partner_info->facebook)) {
                                        echo $partner_info->facebook;
                                    }
                                    ?>" name="facebook">
                                </div>
                            </div>
                            <div class="form-group">
                                <label
                                    class="col-lg-3 col-md-3 col-sm-3 control-label"><?= lang('twitter_profile_link') ?></label>
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($partner_info->twitter)) {
                                        echo $partner_info->twitter;
                                    }
                                    ?>" name="twitter">
                                </div>
                            </div>
                            <div class="form-group">
                                <label
                                    class="col-lg-3 col-md-3 col-sm-3 control-label"><?= lang('linkedin_profile_link') ?></label>
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($partner_info->linkedin)) {
                                        echo $partner_info->linkedin;
                                    }
                                    ?>" name="linkedin">
                                </div>
                            </div>
                            <div class="form-group">
                                <label
                                    class="col-lg-3 col-md-3 col-sm-3 control-label"><?= lang('dribbble_profile_link') ?></label>
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($partner_info->dribbble)) {
                                        echo $partner_info->dribbble;
                                    }
                                    ?>" name="dribbble">
                                </div>
                            </div>
                            <div class="form-group">
                                <label
                                    class="col-lg-3 col-md-3 col-sm-3 control-label"><?= lang('description') ?></label>
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <textarea name="description" class="form-control"><?php
                                        if (!empty($partner_info->description)) {
                                            echo $partner_info->description;
                                        }
                                        ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label"><?= lang('status') ?><span
                                        class="required">*</span></label>

                                <div class="col-sm-8 row">
                                    <div class="col-sm-6">
                                        <div class="checkbox-inline c-checkbox">
                                            <label>
                                                <input <?= (!empty($partner_info->status) && $partner_info->status == '1' ? 'checked' : ''); ?>
                                                    class="select_one" type="checkbox" name="status" value="1">
                                                <span class="fa fa-check"></span> <?= lang('published') ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="checkbox-inline c-checkbox">
                                            <label>
                                                <input <?= (!empty($partner_info->status) && $partner_info->status == '0' ? 'checked' : ''); ?>
                                                    class="select_one" type="checkbox" name="status" value="0">
                                                <span class="fa fa-check"></span> <?= lang('unpublished') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="discount_type" class="control-label col-sm-3"></label>
                                <div class="col-sm-4">
                                    <button type="submit" id="sbtn" name="sbtn" value="1"
                                            class="btn btn-block btn-success"><?= lang('save') ?></button>
                                </div>
                            </div>

                            <?php echo form_close(); ?>
                        </div>
                    </div>
              

            </div>
        </div>
    </div>
</div>




<style type="text/css">
    .dragger {
        background: url(../../skote_assets/images/dragger.png) 0px 15px no-repeat;
        cursor: pointer;
    }
</style>

<script src="<?= base_url() ?>assets/plugins/jquery-ui/jquery-u.js"></script>
<script type="text/javascript">

    $(document).ready(function () {
        $('.ajax_change_status input[type="checkbox"]').change(function () {
            var id = $(this).data().id;
            var status = $(this).is(":checked");
            if (status == true) {
                status = 1;
            } else {
                status = 0;
            }
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= base_url()?>admin/frontend/change_status/tbl_partners/' + id + '/' + status, // the url where we want to POST
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
                success: function (res) {
//                    console.log(res);
                    if (res) {
//                        toastr[res.status](res.message);
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })
        });
    })
</script>