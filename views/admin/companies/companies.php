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
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">  
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="#manage" data-bs-toggle="tab"><?= lang('all') . ' ' . lang('companies') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab"><?= lang('new') . ' ' . lang('company') ?></a>
                    </li>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <div class="table-responsive">
                            <!-- <table class="table table-striped dt-responsive nowrap w-100" id="companies_manage_datatable"> -->
                            <table class="table table-striped dt-responsive nowrap w-100" id="contentTable">
                                <thead>
                                <tr>
                                    <th><?= lang('name') ?></th>
                                    <th><?= lang('email') ?></th>
                                    <th><?= lang('phone') ?></th>
                                    <th><?= lang('city') ?></th>
                                    <th><?= lang('active') ?></th>
                                    <th class="col-options no-sort"><?= lang('action') ?></th>
                                </tr>
                                </thead>
                                <?php /* ?><tbody>
                                <?php if (!empty($all_companies)) {
                                    foreach ($all_companies as $v_companies) { ?>
                                        <tr id="table_<?= $v_companies->companies_id ?>">
                                            <td>
                                                <a href="<?= base_url('admin/companies/details/' . $v_companies->companies_id) ?>">
                                                    <?= $v_companies->name ?>
                                                </a>
                                            </td>
                                            <td><?= ($v_companies->email) ?></td>
                                            <td><?= ($v_companies->phone) ?></td>
                                            <td><?= ($v_companies->city) ?></td>
                                            <td>
                                                <div class="form-check form-switch mb-3 change_companies_status">
                                                    <input class="form-check-input" data-id="<?= $v_companies->companies_id ?>" data-bs-toggle="toggle" name="active" value="1" <?php if (!empty($v_companies->status) && $v_companies->status == '1') { echo 'checked'; } ?> type="checkbox">
                                                </div>
                                                <?php
                                                if ($v_companies->banned == 1) {
                                                    ?>
                                                    <span class="badge badge-soft-danger" data-bs-toggle='tooltip' data-bs-placement='top'
                                                          title="<?= $v_companies->ban_reason ?>"><?= lang('banned') ?></span>
                                                <?php }
                                                ?></td>
                                            <td>
                                                <?php if ($v_companies->banned == 1): ?>
                                                    <a data-bs-toggle="tooltip" data-bs-placement="top"
                                                       class="btn btn-success btn-sm"
                                                       title="Click to <?= lang('unbanned') ?> "
                                                       href="<?php echo base_url() ?>admin/companies/set_banned/0/<?php echo $v_companies->companies_id; ?>"><span
                                                            class="fa fa-check"></span></a>
                                                <?php else: ?>
                                                    <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                          title="Click to <?= lang('banned') ?> ">
                                                            <?php echo btn_banned_modal('admin/companies/change_banned/1/' . $v_companies->companies_id); ?>
                                                                </span>
                                                <?php endif; ?>
                                                <?php echo btn_edit('admin/companies/index/' . $v_companies->companies_id); ?>
                                                <?php echo ajax_anchor(base_url("admin/companies/delete_company/$v_companies->companies_id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_" . $v_companies->companies_id)); ?>
                                            </td>

                                        </tr>
                                        <?php
                                    };
                                }
                                ?>
                                </tbody><?php */ ?>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                        <?php
                        if (!empty($company_info)) {
                            $companies_id = $company_info->companies_id;
                        } else {
                            $companies_id = null;
                        }
                        echo form_open(base_url('admin/companies/save_companies/' . $companies_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                            <div class="row">
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('branch_name') ?> <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <input type="text" class="form-control" value="<?php
                                        if (!empty($company_info)) {
                                            echo $company_info->name;
                                        }
                                        ?>" name="name" required="">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('email') ?> <span
                                                class="text-danger">*</span></label>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <input type="text" class="form-control" value="<?php
                                        if (!empty($company_info)) {
                                            echo $company_info->email;
                                        }
                                        ?>" name="email" required="">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('phone') ?></label>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <input type="text" class="form-control" value="<?php
                                        if (!empty($company_info)) {
                                            echo $company_info->phone;
                                        }
                                        ?>" name="phone">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('address') ?> </label>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                    <textarea name="address" class="form-control"><?php
                                        if (!empty($company_info)) {
                                            echo $company_info->address;
                                        }
                                        ?></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('city') ?></label>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <input type="text" class="form-control" value="<?php
                                        if (!empty($company_info)) {
                                            echo $company_info->city;
                                        }
                                        ?>" name="city">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('country') ?></label>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <select name="country" class="form-control select_box"  style="width: 100%">
                                            <optgroup label="Default Country">
                                                <option value="<?= $this->config->item('company_country') ?>"><?= $this->config->item('company_country') ?></option>
                                            </optgroup>
                                            <?php if (!empty($countries)): ?>
                                            <optgroup label="<?= lang('other_countries') ?>">
                                                <?php  foreach ($countries as $country): ?>
                                                    <option value="<?= $country->value ?>" <?= (!empty($company_info->country) && $company_info->country == $country->value ? 'selected' : NULL) ?>><?= $country->value ?> </option>
                                                <?php
                                                endforeach;
                                                ?>
                                            </optgroup>
                                            <?php endif; ?>    
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('zip_code') ?></label>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <input type="text" class="form-control" value="<?php
                                        if (!empty($company_info)) {
                                            echo $company_info->zip_code;
                                        }
                                        ?>" name="zip_code">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"></label>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <button type="submit" id="new_company"
                                                class="btn btn-sm btn-primary"><?= lang('update') ?></button>
                                    </div>
                                </div>    
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on("change", function () {
//        alert('done');
        var check_username = $('#check_username').val();
        if (check_username) {
            id = 'check_username_error';
            btn = 'new_company';
            url = 'check_existing_user_name'
            value = check_username;
            $.ajax({
                url: base_url + "admin/global_controller/" + url,
                type: "POST",
                data: {
                    name: value,
                },
                dataType: 'json',
                success: function (res) {
                    if (res.error) {
                        handle_error("#" + id, res.error);
                        disable_button("#" + btn);
                        return;
                    } else {
                        remove_error("#" + id);
                        disable_remove("#" + btn);
                        return;
                    }
                }
            });
        }
    });
    $(document).ready(function () {
        $('.change_companies_status input[type="checkbox"]').change(function () {
            var companies_id = $(this).data().id;
            var status = $(this).is(":checked");
            if (status == true) {
                status = 1;
            } else {
                status = 0;
            }
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= base_url()?>admin/companies/change_status/' + status + '/' + companies_id, // the url where we want to POST
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
                success: function (res) {
                    if (res) {
                        toastr[res.status](res.message);
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })

        });

    })
</script>

<!-- Script -->
 <script type="text/javascript">
     $(document).ready(function(){
        $('#contentTable').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
             'url':'<?=base_url()?>admin/datatable/companies'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_"+iDisplayIndex);
            $('td:eq(6)', nRow).css("display","none");
            return nRow;
          },
          'columns': [
             { data: 'name' },
             { data: 'email' },
             { data: 'phone' },
             { data: 'city' },
             { data: 'active' },
             { data: 'action' },
          ]
        });
     });
 </script>