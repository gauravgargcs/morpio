<style type="text/css">
    .dragger {
        background: url(../../skote_assets/images/dragger.png) 0px 15px no-repeat;
        cursor: pointer;
    }
</style>
<div class="">
    <div class="col-md-12">
        <?php echo form_open(base_url('admin/frontend/saved_config/'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
        <div class="panel panel-custom">
            <header class="panel-heading "><?= lang('frontend') . ' ' . lang('settings') ?>
                <div class="pull-right hidden-print" style="padding-top: 0px;padding-bottom: 8px">
                    <button type="submit" class="btn btn-xs btn-primary"><?= lang('update') ?></button>
                </div>
            </header>
            <div class="panel-body">

                <!-- CV Upload -->
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('address') ?> <span class="text-danger">*</span></label>
                    <input required type="text" name="office_address" value="<?= config_item('office_address') ?>" class="form-control">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('office_time') ?></label>
                    <input type="text" name="office_time" value="<?= config_item('office_time') ?>" class="form-control">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('contacts') . ' ' . lang('no') ?> <span class="text-danger">*</span></label>
                    <input required type="text" name="office_contact_no" value="<?= config_item('office_contact_no') ?>" class="form-control">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('email') ?> <span class="text-danger">*</span></label>
                    <input required type="text" name="office_email" value="<?= config_item('office_email') ?>" class="form-control">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('facebook') ?></label>
                    <input type="text" name="office_facebook" value="<?= config_item('office_facebook') ?>" class="form-control">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('instagram') ?></label>
                    <input type="text" name="office_instagram" value="<?= config_item('office_instagram') ?>" class="form-control">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('google_plus') ?></label>
                    <input type="text" name="office_google_plus" value="<?= config_item('office_google_plus') ?>" class="form-control">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('twitter') ?></label>
                    <input type="text" name="office_twitter" value="<?= config_item('office_twitter') ?>" class="form-control">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('linkedin') ?></label>
                    <input type="text" name="office_linkedin" value="<?= config_item('office_linkedin') ?>" class="form-control">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('happy_clients') ?> </label>
                    <input type="text" name="happy_clients" value="<?= config_item('happy_clients') ?>" class="form-control">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('awards_win') ?></label>
                    <input type="text" name="awards_win" value="<?= config_item('awards_win') ?>" class="form-control">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('finished_work') ?></label>
                    <input type="text" name="finished_work" value="<?= config_item('finished_work') ?>" class="form-control">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('team_members') ?></label>
                    <input type="text" name="team_members" value="<?= config_item('team_members') ?>" class="form-control">
                </div>

                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('site_description') ?></label>
                    <textarea class="form-control" name="company_description"><?= config_item('company_description') ?></textarea>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('copyright_name') ?></label>
                    <input type="text" name="copyright_name" value="<?= config_item('copyright_name') ?>" class="form-control">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('copyright_url') ?></label>
                    <input type="text" name="copyright_url" value="<?= config_item('copyright_url') ?>" class="form-control">
                </div>

                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('features_heading') ?></label>
                    <input type="text" name="features_heading" value="<?= config_item('features_heading') ?>" class="form-control">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="control-label"><?= lang('features_description') ?></label>
                    <textarea class="form-control" name="features_description"><?= config_item('features_description') ?></textarea>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>

    <div class="row col-md-12">
        <div class="col-sm-6">
            <div class="panel panel-custom">
                <header class="panel-heading "><?= lang('slider') . ' ' . lang('settings') ?>
                    <div class="pull-right hidden-print" style="padding-top: 0px;padding-bottom: 8px">
                        <a href="<?= base_url() ?>admin/frontend/new_slider" class="btn btn-xs btn-info" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal_lg">
                            <i class="fa fa-plus "></i> <?= ' ' . lang('new') . ' ' . lang('slider') ?></a>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped ">
                            <?php $all_slider = $this->db->order_by('sort', 'ASC')->get('tbl_frontend_slider')->result(); ?>
                            <tbody>
                                <?php
                                foreach ($all_slider as $v_slider) {
                                ?>
                                    <tr id="table_slider_<?= $v_slider->id ?>">
                                        <td class="">
                                            <a title="<?= lang('edit') ?>" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal_lg" href="<?= base_url('admin/frontend/new_slider/' . $v_slider->id) ?>"> <?= $v_slider->title ?></a>
                                        </td>
                                        <td>
                                            <div class="pull-left">
                                                <label class="col-lg-6 control-label"><a title="<?= lang('edit') ?>" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal_lg" href="<?= base_url('admin/frontend/new_slider/' . $v_slider->id) ?>">
                                                        <?= lang('active') ?>
                                                    </a>
                                                </label>
                                                <div class="col-lg-5 checkbox change_slider_status">
                                                    <input data-id="<?= $v_slider->id ?>" data-bs-toggle="toggle" name="status" value="1" <?php if ($v_slider->status == 1) {
                                                                                                                                            echo 'checked';
                                                                                                                                        } ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" type="checkbox">
                                                </div>
                                            </div>
                                            <a href="<?= base_url() ?>admin/frontend/new_features/<?= $v_slider->id ?>" class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal_lg">
                                                <i class="fa fa-pencil-square-o"></i></a>
                                            <?php echo ajax_anchor(base_url("admin/frontend/delete/tbl_frontend_slider/$v_slider->id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_slider_" . $v_slider->id)); ?>
                                        </td>
                                    </tr>
                                <?php }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="panel panel-custom">
                <header class="panel-heading "><?= lang('features') . ' ' . lang('settings') ?>
                    <div class="pull-right hidden-print" style="padding-top: 0px;padding-bottom: 8px">
                        <a href="<?= base_url() ?>admin/frontend/new_features/features" class="btn btn-xs btn-info" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal_lg">
                            <i class="fa fa-plus "></i> <?= ' ' . lang('new') . ' ' . lang('features') ?></a>
                    </div>
                </header>
                <div class="table-responsive">
                    <table class="table table-striped DataTables " id="">
                        <thead>
                            <tr>
                                <th><?= lang('title') ?></th>
                                <th><?= lang('icon') ?></th>
                                <th class="col-options no-sort"><?= lang('action') ?></th>
                            </tr>
                        </thead>

                        <tbody id="features">
                            <?php
                            $all_features = get_order_by('tbl_frontend_features', array('type' => 'features'), 'sort', true);
                            if (!empty($all_features)) {
                                foreach ($all_features as $features) {
                            ?>
                                    <tr class="features" data-id="<?= $features->id ?>" id="table_features_<?= $features->id ?>">
                                        <td class="dragger pl-lg"><?= $features->title ?></td>
                                        <td><?= $features->icon ?></td>
                                        <td>
                                            <?php
                                            if ($features->status == 1) {
                                                echo '<span class="label label-success">' . lang('published') . '</span>';
                                            } else {
                                                echo '<span class="label label-danger">' . lang('unpublished') . '</span>';
                                            }
                                            ?>
                                            <a href="<?= base_url() ?>admin/frontend/new_features/<?= $features->id ?>" class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal_lg">
                                                <i class="fa fa-pencil-square-o"></i></a>
                                            <?php echo ajax_anchor(base_url("admin/frontend/delete/tbl_frontend_features/$features->id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child",  "data-fade-out-on-success" => "#table_features_" . $features->id)); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="panel panel-custom">
                <header class="panel-heading "><?= lang('customer_testimonials') . ' ' . lang('settings') ?>
                    <div class="pull-right hidden-print" style="padding-top: 0px;padding-bottom: 8px">
                        <a href="<?= base_url() ?>admin/frontend/new_ratings" class="btn btn-xs btn-info" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal_lg">
                            <i class="fa fa-plus "></i> <?= ' ' . lang('new') . ' ' . lang('testimonials') ?></a>
                    </div>
                </header>
                <div class="table-responsive">
                    <table class="table table-striped DataTables " id="">
                        <thead>
                            <tr>
                                <th><?= lang('name') ?></th>
                                <th><?= lang('position') ?></th>
                                <th><?= lang('status') ?></th>
                                <th class="col-options no-sort"><?= lang('action') ?></th>
                            </tr>
                        </thead>

                        <tbody id="features">
                            <?php
                            $all_ratings = get_order_by('tbl_customer_ratings', '0', 'id', true);
                            if (!empty($all_ratings)) {
                                foreach ($all_ratings as $ratings) {
                            ?>
                                    <tr class="features" data-id="<?= $ratings->id ?>" id="table_features_<?= $ratings->id ?>">
                                        <td class="pl-lg"><?= $ratings->name ?></td>
                                        <td class="pl-lg"><?= $ratings->position ?></td>
                                        <td class="pl-lg">
                                            <?php
                                            if ($ratings->status == 1) {
                                                echo '<span class="label label-success">' . lang('published') . '</span>';
                                            } else {
                                                echo '<span class="label label-danger">' . lang('unpublished') . '</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url() ?>admin/frontend/new_ratings/<?= $ratings->id ?>" class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal_lg">
                                                <i class="fa fa-pencil-square-o"></i></a>
                                            <?php echo ajax_anchor(base_url("admin/frontend/delete/tbl_customer_ratings/$ratings->id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_features_" . $ratings->id)); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="panel panel-custom">
                <?php echo form_open(base_url('admin/frontend/saved_config/'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                <header class="panel-heading "><?= lang('about_us') ?>
                    <div class="pull-right hidden-print" style="padding-top: 0px;padding-bottom: 8px">
                        <button type="submit" class="btn btn-xs btn-primary"><?= lang('update') ?></button>
                    </div>
                </header>
                <div class="table-responsive">
                    <textarea class="textarea_" name="about_us"><?= config_item('about_us') ?></textarea>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-custom">
                <?php echo form_open(base_url('admin/frontend/saved_config/'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                <header class="panel-heading "><?= lang('privacy') ?>
                    <div class="pull-right hidden-print" style="padding-top: 0px;padding-bottom: 8px">
                        <button type="submit" class="btn btn-xs btn-primary"><?= lang('update') ?></button>
                    </div>
                </header>
                <div class="table-responsive">
                    <textarea class="textarea_" name="privacy"><?= config_item('privacy') ?></textarea>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-custom">
                <?php echo form_open(base_url('admin/frontend/saved_config/'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                <header class="panel-heading "><?= lang('tos') ?>
                    <div class="pull-right hidden-print" style="padding-top: 0px;padding-bottom: 8px">
                        <button type="submit" class="btn btn-xs btn-primary"><?= lang('update') ?></button>
                    </div>
                </header>
                <div class="table-responsive">
                    <textarea class="textarea_" name="tos"><?= config_item('tos') ?></textarea>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>assets/plugins/jquery-ui/jquery-u.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.change_slider_status input[type="checkbox"]').change(function() {
            var id = $(this).data().id;
            var status = $(this).is(":checked");
            if (status == true) {
                status = 1;
            } else {
                status = 0;
            }
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= base_url() ?>admin/frontend/change_status/tbl_frontend_slider/' + id + '/' + status, // the url where we want to POST
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
                success: function(res) {
                    console.log(res);
                    if (res) {
                        //                        toastr[res.status](res.message);
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })
        });
    })

    $(function() {
        $('tbody[id^="features"]').sortable({
            connectWith: ".features",
            placeholder: 'ui-state-highlight',
            forcePlaceholderSize: true,
            stop: function(event, ui) {
                var id = JSON.stringify(
                    $('tbody[id^="features"]').sortable(
                        'toArray', {
                            attribute: 'data-id'
                        }
                    )
                );
                var formData = {
                    'features': id
                };
                $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: '<?= base_url() ?>admin/frontend/save_features/', // the url where we want to POST
                    data: formData, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    encode: true,
                    success: function(res) {
                        if (res) {
                            //                            toastr[res.status](res.message);
                        } else {
                            alert('There was a problem with AJAX');
                        }
                    }
                })

            }
        });
    });
</script>