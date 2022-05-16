<div class="nav-tabs-custom">

    <!-- Tabs within a box -->

    <ul class="nav nav-tabs bg-light rounded">

        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="#manage"

                                                            data-bs-toggle="tab"><?= lang('all') . ' ' . lang('service') ?></a>
        </li>
    

        
    </ul>

    <div class="tab-content bg-white">

        <!-- ************** general *************-->

        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">

            <div class="table-responsive">

                <table class="table table-striped" id="">

                    <thead>

                    <tr>

                        <th><?= lang('title') ?></th>

                        <th><?= lang('description') ?></th>

                        <th class="col-options no-sort"><?= lang('action') ?></th>

                    </tr>

                    </thead>

                    <tbody id="coupon">

                    <?php

                    $all_services = get_order_by('tbl_services', 'id', true);

                    if (!empty($all_services)) {

                        foreach ($all_services as $services) {

                            ?>

                            <tr class="services" data-id="<?= $services->id ?>"

                                id="table_services_<?= $services->id ?>">

                                <td><?= $services->title ?></td>

                                <td><?= $services->description ?></td>


                                <td>

                                        <div class="checkbox ajax_change_status">

                                                <input data-id="<?= $services->id ?>" data-bs-toggle="toggle"

                                                name="status"

                                                value="1" <?php if ($services->status == 1) {

                                                        echo 'checked';

                                                } ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>"

                                                data-onstyle="success btn-xs"

                                                data-offstyle="danger btn-xs" type="checkbox">

                                        </div>

                                </td>

                                <td>

                                    <a href="<?= base_url() ?>admin/frontend/service/<?= $services->id ?>"

                                       class="btn btn-xs btn-primary"

                                       data-bs-toggle="tooltip" title="<?= lang('edit') ?>"

                                       data-bs-placement="top">

                                        <i class="fa fa-pencil-square-o"></i></a>

                                    <?php echo ajax_anchor(base_url("admin/frontend/delete/tbl_services/$services->id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child",  "data-fade-out-on-success" => "#table_services_" . $services->id)); ?>

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

            if (!empty($service_info)) {

                $id = $service_info->id;

            } else {

                $id = null;

            }

            echo form_open(base_url('admin/frontend/save_service/' . $id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>


            <div class="form-group">

                <label for="field-1" class="col-sm-3 control-label"><?= lang('title') ?>

                    <span class="required">*</span></label>

                <div class="col-sm-5">

                    <input required type="text" name="title"

                           placeholder="<?= lang('enter') . ' ' . lang('title') ?>"

                           class="form-control" value="<?php

                    if (!empty($service_info->title)) {

                        echo $service_info->title;

                    }

                    ?>"/>

                </div>

            </div>

            <div class="form-group">
                <label class="col-lg-3 control-label"><?= lang('description') ?>
                        <span class="required">*</span></label>
                </label>
                <div class="col-lg-5">

                        <textarea required name="description" class="form-control"><?php

                                if (!empty($service_info->description)) {
                                        echo $service_info->description;
                                }
                                ?>
                        </textarea>
                </div>
            </div>

            <div class="form-group">

                <label for="field-1" class="col-sm-3 control-label"><?= lang('status') ?>
                        <span class="required">*</span>
                </label>

                <div class="col-sm-5">

                    <div class="col-sm-6 row">
                        <div class="checkbox-inline c-checkbox">
                            <label>
                                <input <?= (!empty($service_info->status) && $service_info->status == '1' ? 'checked' : ''); ?>
                                    class="select_one" type="checkbox" name="status" value="1">
                                <span class="fa fa-check"></span> <?= lang('published') ?>
                            </label>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="checkbox-inline c-checkbox">
                            <label>
                                <input <?= (!empty($service_info->status) && $service_info->status == '0' ? 'checked' : ''); ?>

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

        </div>

    </div>

</div>





<?php echo form_close(); ?>



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

                url: '<?= base_url()?>admin/frontend/change_status/tbl_services/' + id + '/' + status, // the url where we want to POST

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