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
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">  
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="#manage" data-bs-toggle="tab"><?= lang('all') . ' ' . lang('team') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab"><?= lang('new') . ' ' . lang('team') ?></a>
                    </li>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="all_team_datatable">
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
                                $all_partners = get_order_by('tbl_partners', array('type'=>'team'), 'id', true);
                                if (!empty($all_partners)) {
                                    foreach ($all_partners as $partners) {
                                        ?>
                                        <tr class="partners" data-id="<?= $partners->id ?>"
                                            id="table_partners_<?= $partners->id ?>">
                                            <td><?= $partners->name ?></td>
                                            <td><?= $partners->designation ?></td>
                                            <td><?= $partners->facebook ?></td>

                                            <td>
                                                <div class="form-check form-switch mb-3 ajax_change_status">
                                                    <input class="form-check-input" data-id="<?= $partners->id ?>" data-bs-toggle="toggle" name="status" value="Yes" <?php if ($partners->status == 1) { echo 'checked'; } ?>  type="checkbox">
                                                </div>
                                            </td>
                                            <td>
                                                <a href="<?= base_url() ?>admin/frontend/team/<?= $partners->id ?>"
                                                   class="btn btn-sm btn-outline-primary"
                                                   data-bs-toggle="tooltip" title="<?= lang('edit') ?>"
                                                   data-bs-placement="top">
                                                    <i class="fa fa-pencil-square-o"></i></a>
                                                <?php echo ajax_anchor(base_url("admin/frontend/delete/tbl_partners/$partners->id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child",  "data-fade-out-on-success" => "#table_partners_" . $partners->id)); ?>
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
                        echo form_open(base_url('admin/frontend/save_team/' . $id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <div class="row p-3">
                            <div class="row mb-3">
                                <label for="formFile"  class="col-xl-3 col-form-label">
                                  <?= lang('profile') ?><span class="required">*</span></label>
                                <div class="col-xl-5">
                                    <input class="form-control" type="file" id="formFile" name="profile" >
                                </div>    
                                <div class="fileinput-new thumbnail col-xl-3">
                                    <?php if (!empty($partner_info->profile)) : ?>
                                        <img src="<?php echo base_url() . $partner_info->profile; ?>" class="rounded-circle avatar-xs">
                                    <?php else: ?>
                                        <img src="http://placehold.it/350x260" alt="Please Connect Your Internet" class="rounded-circle avatar-xs">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-xl-3 col-form-label"><?= lang('name') ?>
                                    <span class="required">*</span></label>

                                <input type="hidden" name="type" value="team">

                                <div class="col-xl-5">
                                    <input required type="text" name="name"
                                           placeholder="<?= lang('enter') . ' ' . lang('name') ?>"
                                           class="form-control" value="<?php
                                    if (!empty($partner_info->name)) {
                                        echo $partner_info->name;
                                    }
                                    ?>"/>
                                </div>
                            </div>
                        <div class="row mb-3">
                            <label class="col-xl-3 col-form-label"><?= lang('designation') ?>
                                <span class="required">*</span></label>

                            <div class="col-xl-5">
                                <input required type="text" name="designation"
                                       placeholder="<?= lang('enter') . ' ' . lang('designation') ?>"
                                       class="form-control" value="<?php
                                if (!empty($partner_info)) {
                                    echo $partner_info->designation;
                                }
                                ?>"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label
                                class="col-lg-3 col-xl-3 col-form-label"><?= lang('facebook_profile_link') ?></label>
                            <div class="col-lg-5 col-xl-5">
                                <input type="text" class="form-control" value="<?php
                                if (!empty($partner_info->facebook)) {
                                    echo $partner_info->facebook;
                                }
                                ?>" name="facebook">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label
                                class="col-lg-3 col-xl-3 col-form-label"><?= lang('twitter_profile_link') ?></label>
                            <div class="col-lg-5 col-xl-5">
                                <input type="text" class="form-control" value="<?php
                                if (!empty($partner_info->twitter)) {
                                    echo $partner_info->twitter;
                                }
                                ?>" name="twitter">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label
                                class="col-lg-3 col-xl-3 col-form-label"><?= lang('linkedin_profile_link') ?></label>
                            <div class="col-lg-5 col-xl-5">
                                <input type="text" class="form-control" value="<?php
                                if (!empty($partner_info->linkedin)) {
                                    echo $partner_info->linkedin;
                                }
                                ?>" name="linkedin">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label
                                class="col-lg-3 col-xl-3 col-form-label"><?= lang('dribbble_profile_link') ?></label>
                            <div class="col-lg-5 col-xl-5">
                                <input type="text" class="form-control" value="<?php
                                if (!empty($partner_info->dribbble)) {
                                    echo $partner_info->dribbble;
                                }
                                ?>" name="dribbble">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label
                                class="col-lg-3 col-xl-3 col-form-label"><?= lang('description') ?></label>
                            <div class="col-lg-5 col-xl-5">
                                <textarea name="description" id="elm1" class="form-control"><?php
                                    if (!empty($partner_info->description)) {
                                        echo $partner_info->description;
                                    }
                                    ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-xl-3 col-form-label"><?= lang('status') ?><span
                                    class="required">*</span></label>

                            <div class="col-xl-5">
                                <div class="form-check form-check-primary mb-3 mr">
                                        <input <?= (!empty($partner_info->status) && $partner_info->status == '1' ? 'checked' : ''); ?>
                                                class="select_one form-check-input" type="checkbox" name="status" value="1" id="published">
                                        <label class="form-check-label" for="published"><?= lang('published') ?> </label>
                                    </div>
                                                                
                                    <div class="form-check form-check-primary mb-3 mr">
                                        <input <?= (!empty($partner_info->status) && $partner_info->status == '0' ? 'checked' : ''); ?>
                                                class="select_one form-check-input" type="checkbox" name="status" value="0" id="unpublished">
                                        <label class="form-check-label" for="unpublished"><?= lang('unpublished') ?> </label>
                                    </div>
                            
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="discount_type" class="col-form-label col-xl-3"></label>
                            <div class="col-xl-4">
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