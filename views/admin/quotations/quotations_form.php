<link href="<?= base_url() ?>plugins/formbuilder/formbuilder.css" rel="stylesheet" />
<style>
    .fb-main {
        background-color: #fff;
        min-height: 600px;
    }

    input[type=text] {
        height: 26px;
        margin-bottom: 3px;
    }

    /*Hide Auto-save button*/
    .fb-save-wrapper .js-save-form {
        display: none;
    }
</style>
<?= message_box('success'); ?>
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
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="<?= base_url('admin/quotations/quotations_form') ?>"><?= lang('quotations_form') ?></a>
                    </li>
                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="<?= base_url('admin/quotations/quotations_form/new') ?>"><?= lang('new_quotations_form') ?></a>
                    </li>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <table class="table table-striped dt-responsive nowrap w-100" id="list_quotes_datatable">
                            <thead>
                                <tr>
                                    <?php super_admin_opt_th() ?>
                                    <th><?= lang('title') ?></th>
                                    <th><?= lang('created_by') ?></th>
                                    <th><?= lang('created_date') ?></th>
                                    <th><?= lang('status') ?></th>
                                    <th><?= lang('action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($all_quatations)) {
                                    foreach ($all_quatations as $v_quatations) {
                                        $user_info = $this->quotations_model->check_by(array('user_id' => $v_quatations->quotations_created_by_id), 'tbl_account_details');
                                ?>
                                <tr>
                                    <?php super_admin_opt_td($v_quatations->companies_id) ?>
                                    <td><?= $v_quatations->quotationforms_title; ?></td>
                                    <td><?= $user_info->fullname; ?></td>
                                    <td><?= display_datetime($v_quatations->quotationforms_date_created) ?></td>
                                    <td><?php
                                        if ($v_quatations->quotationforms_status == 'enabled') {
                                            echo '<span class="badge badge-soft-success"> Enabled</span>';
                                        } else {
                                            echo '<span class="badge badge-soft-danger">Disabled</span>';
                                        };
                                        ?></td>
                                    <td>
                                        <?= btn_edit('admin/quotations/quotations_form/edit_quotations_form/' . $v_quatations->quotationforms_id) ?>
                                        <?= btn_view('admin/quotations/quotations_form_details/' . $v_quatations->quotationforms_id) ?>
                                        <?= btn_delete('admin/quotations/quotations_form/delete_quotations_form/' . $v_quatations->quotationforms_id) ?>
                                    </td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="new">
                        <?php

                        echo form_open(base_url('admin/quotations/add_form/'), array('class' => 'form-horizontal', 'id' => 'addQuotationForm', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <!-- Sidebar ends -->
                        <!-- Main bar -->
                        <?php super_admin_form(null, 3, 8) ?>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" required="" style="height: 31px;margin-bottom: 10px;" class="form-control" name="quotationforms_title" autocomplete="off" placeholder="<?= lang('form_title') ?>">
                            </div>
                        </div>
                        <!--WI_QUOTATION_TITLE-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-primary">
                                    <div class="fb-main"></div>
                                </div>
                                <div class="panel-footer">
                                    <div class="pull-left">
                                        <input type="hidden" name="quotationforms_code" id="quotationforms_code">
                                        <input class="btn btn-primary" type="submit" value="<?= lang('save') ?>" id="" name="submit">
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close();
                        if ($active == 2) {
                        ?>

                            <script src="<?= base_url() ?>asset/vendor/js/vendor.js"></script>
                            <script src="<?= base_url() ?>plugins/formbuilder/formbuilder.js"></script>

                            <script>
                                $(function() {
                                    fb = new Formbuilder({
                                        selector: '.fb-main',
                                        bootstrapData: [{}]
                                    });

                                    fb.on('save', function(payload) {
                                        console.log(payload);
                                        $('#quotationforms_code').val(payload);
                                    })
                                });
                            </script>
                        <?php } ?>
                        <!-- Mainbar ends -->
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>