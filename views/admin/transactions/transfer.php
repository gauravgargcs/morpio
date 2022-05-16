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

<?php
$created = can_action('30', 'created');
$edited = can_action('30', 'edited');
$deleted = can_action('30', 'deleted');
if (!empty($transfer_info)) {
    $transfer_id = $transfer_info->transfer_id;
    $companies_id = $transfer_info->companies_id;
} else {
    $transfer_id = null;
    $companies_id = null;
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body bordered">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="<?=base_url('admin/transactions/transfer')?>"><?= lang('all_transfer') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab"><?php if($transfer_id){ echo lang('edit').' '.lang('transfer'); }else{ echo lang('new_transfer'); } ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <h4 class="card-title mb-4"><?= lang('all_transfer') ?></h4>
                        <table class="table table-striped dt-responsive nowrap w-100" id="manage_transfer_datatable">
                            <thead>
                            <tr>
                                <?php super_admin_opt_th() ?>
                                <th><?= lang('from_account') ?></th>
                                <th><?= lang('to_account') ?></th>
                                <th class="col-currency"><?= lang('amount') ?></th>
                                <th><?= lang('date') ?></th>
                                <th><?= lang('attachment') ?></th>
                                <?php if (!empty($edited) || !empty($deleted)) { ?>
                                    <th><?= lang('action') ?></th>
                                <?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($all_transfer_info)):
                                foreach ($all_transfer_info as $v_transfer) :
                                    if ($v_transfer->transfer_id == $v_transfer->transfer_id) {
                                        $can_edit = $this->transactions_model->can_action('tbl_transfer', 'edit', array('transfer_id' => $v_transfer->transfer_id));
                                        $can_delete = $this->transactions_model->can_action('tbl_transfer', 'delete', array('transfer_id' => $v_transfer->transfer_id));

                                        $to_account_info = $this->transactions_model->check_by(array('account_id' => $v_transfer->to_account_id), 'tbl_accounts');
                                        $from_account_info = $this->transactions_model->check_by(array('account_id' => $v_transfer->from_account_id), 'tbl_accounts');
                                        $curency = $this->transactions_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                        ?>
                                        <tr id="table_transfer_<?= $v_transfer->transfer_id ?>">
                                            <?php super_admin_opt_td($v_transfer->companies_id) ?>
                                            <td class="vertical-td"><?php
                                                if (!empty($from_account_info->account_name)) {
                                                    echo $from_account_info->account_name;
                                                } else {
                                                    echo '-';
                                                }
                                                ?></td>
                                            <td class="vertical-td"><?php
                                                if (!empty($to_account_info->account_name)) {
                                                    echo $to_account_info->account_name;
                                                } else {
                                                    echo '-';
                                                }
                                                ?></td>
                                            <td><?= display_money($v_transfer->amount, $curency->symbol) ?></td>
                                            <td><?= display_datetime($v_transfer->date); ?></td>
                                            <td>
                                                <?php
                                                $attachement_info = json_decode($v_transfer->attachement);
                                                if (!empty($attachement_info)) { ?>
                                                    <a href="<?= base_url() ?>admin/transactions/download_transfer/<?= $v_transfer->transfer_id ?>"><?= lang('download') ?></a>
                                                <?php } ?>
                                            </td>
                                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                <td>
                                                    <?php if (!empty($edited) && !empty($can_edit)) { ?>
                                                        <?= btn_edit('admin/transactions/transfer/' . $v_transfer->transfer_id) ?>
                                                    <?php }
                                                    if (!empty($deleted) && !empty($can_delete)) {
                                                        ?>
                                                        <?php echo ajax_anchor(base_url("admin/transactions/delete_transfer/$v_transfer->transfer_id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_transfer_" . $v_transfer->transfer_id)); ?>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <?php
                                    }
                                endforeach;
                            endif;
                            ?>
                            </tbody>
                        </table>
                    </div>
        
                    <?php if (!empty($created) || !empty($edited)) { ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                        <?php echo form_open(base_url('admin/transactions/save_transfer/' . $transfer_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <div class="card-body">
                            <h4 class="card-title mb-4"><?php if($transfer_id){ echo lang('edit').' '.lang('transfer'); }else{ echo lang('new_transfer'); } ?></h4>
                        
                            <?php super_admin_form($companies_id, 3, 5) ?>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('from_account') ?> <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-5 col-md-5 col-sm-7">
                                    <select class="form-control select_box" style="width: 100%" name="from_account_id" required
                                        <?php
                                        if (!empty($transfer_info)) {
                                            echo 'disabled';
                                        }
                                        ?>>
                                        <option value=""><?= lang('choose_from_account') ?></option>
                                        <?php
                                        $f_account_info = by_company('tbl_accounts', 'account_id', null, $companies_id);
                                        if (!empty($f_account_info)) {
                                            foreach ($f_account_info as $v_f_account) {
                                                ?>
                                                <option value="<?= $v_f_account->account_id ?>"
                                                    <?php
                                                    if (!empty($transfer_info)) {
                                                        echo $transfer_info->from_account_id == $v_f_account->account_id ? 'selected' : '';
                                                    }
                                                    ?>><?= $v_f_account->account_name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('select[name="companies_id"]').on('change', function () {
                                        var companies_id = $(this).val();
                                        if (companies_id) {
                                            $.ajax({
                                                url: '<?= base_url('admin/global_controller/json_by_company/tbl_accounts/')?>' + companies_id,
                                                type: "GET",
                                                dataType: "json",
                                                success: function (data) {
                                                    // $('select[name="client_id"]').empty();
                                                    $('select[name="from_account_id"]').find('option').not(':first').remove();
                                                    $.each(data, function (key, value) {
                                                        $('select[name="from_account_id"]').append('<option value="' + value.account_id + '">' + value.account_name + '</option>');
                                                    });
                                                }
                                            });
                                        } else {
                                            $('select[name="from_account_id"]').find('option').not(':first').remove();
                                        }
                                    });
                                });
                            </script>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('to_account') ?> <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="col-lg-5 col-md-5 col-sm-7">
                                    <select class="form-control select_box" style="width: 100%" name="to_account_id" required
                                        <?php
                                        if (!empty($transfer_info)) {
                                            echo 'disabled';
                                        }
                                        ?>>
                                        <option value=""><?= lang('choose_to_account') ?></option>
                                        <?php
                                        $account_info = by_company('tbl_accounts', 'account_id', null, $companies_id);
                                        if (!empty($account_info)) {
                                            foreach ($account_info as $v_t_account) {
                                                ?>
                                                <option value="<?= $v_t_account->account_id ?>"
                                                    <?php
                                                    if (!empty($transfer_info)) {
                                                        echo $transfer_info->to_account_id == $v_t_account->account_id ? 'selected' : '';
                                                    }
                                                    ?>
                                                ><?= $v_t_account->account_name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('select[name="companies_id"]').on('change', function () {
                                        var companies_id = $(this).val();
                                        if (companies_id) {
                                            $.ajax({
                                                url: '<?= base_url('admin/global_controller/json_by_company/tbl_accounts/')?>' + companies_id,
                                                type: "GET",
                                                dataType: "json",
                                                success: function (data) {
                                                // $('select[name="client_id"]').empty();
                                                    $('select[name="to_account_id"]').find('option').not(':first').remove();
                                                    $.each(data, function (key, value) {
                                                        $('select[name="to_account_id"]').append('<option value="' + value.account_id + '">' + value.account_name + '</option>');
                                                    });
                                                }
                                            });
                                        } else {
                                            $('select[name="to_account_id"]').find('option').not(':first').remove();
                                        }
                                    });
                                });
                            </script>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('date') ?></label>
                                <div class="col-lg-5 col-md-5 col-sm-7">
                                    <div class="input-group">
                                        <input type="text" name="date" class="form-control datepicker" value="<?php
                                        if (!empty($transfer_info->date)) {
                                            echo display_datetime($transfer_info->date);
                                        } else {
                                            echo date('d-m-Y H-i');
                                        }
                                        ?>" data-date-format="<?= config_item('date_picker_format'); ?>">
                                        <div class="input-group-text">
                                            <i class="mdi mdi-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3 terms">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('notes') ?> </label>
                                <div class="col-lg-5 col-md-5 col-sm-7">
                                <textarea name="notes" id="elm1" class="form-control"><?php
                                    if (!empty($transfer_info)) {
                                        echo $transfer_info->notes;
                                    }
                                    ?></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('amount') ?> <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="col-lg-5 col-md-5 col-sm-7">
                                    <div class="input-group  ">
                                        <input class="form-control " data-parsley-type="number" type="text" value="<?php
                                        if (!empty($transfer_info)) {
                                            echo $transfer_info->amount;
                                        }
                                        ?>" name="amount" required="" <?php
                                        if (!empty($transfer_info)) {
                                            echo 'disabled';
                                        }
                                        ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('payment_method') ?> </label>
                                <div class="col-lg-5 col-md-5 col-sm-7">
                                    <select class="form-control select_box" style="width: 100%" name="payment_methods_id">
                                        <option value="0"><?= lang('select_payment_method') ?></option>
                                        <?php
                                        $payment_methods = get_result('tbl_payment_methods');
                                        if (!empty($payment_methods)) {
                                            foreach ($payment_methods as $p_method) {
                                                ?>
                                                <option value="<?= $p_method->payment_methods_id ?>" <?php
                                                if (!empty($transfer_info)) {
                                                    echo $transfer_info->payment_methods_id == $p_method->payment_methods_id ? 'selected' : '';
                                                }
                                                ?>><?= $p_method->method_name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('reference') ?> </label>
                                <div class="col-lg-5 col-md-5 col-sm-7">
                                    <input class="form-control " type="text" value="<?php
                                    if (!empty($transfer_info)) {
                                        echo $transfer_info->reference;
                                    }
                                    ?>" name="reference">
                                    <input class="form-control " type="hidden" value="<?php
                                    if (!empty($transfer_info)) {
                                        echo $transfer_info->from_account_id;
                                    }
                                    ?>" name="old_from_account_id">
                                    <input class="form-control " type="hidden" value="<?php
                                    if (!empty($transfer_info)) {
                                        echo $transfer_info->amount;
                                    }
                                    ?>" name="old_amount">
                                    <input class="form-control " type="hidden" value="<?php
                                    if (!empty($transfer_info)) {
                                        echo $transfer_info->to_account_id;
                                    }
                                    ?>" name="old_to_account_id">
                                    <span class="help-block"><?= lang('reference_example') ?></span>
                                </div>
                            </div>

                            <div class="row mb-3" style="margin-bottom: 0px">
                                <label 
                                       class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('attachment') ?></label>

                                <div class="col-sm-5">
                                    <div id="comments_file-dropzone" class="dropzone mb15"></div>
                                    <div data-simplebar style="max-height: 280px;">  
                                        <div id="comments_file-dropzone-scrollbar">
                                            <div id="comments_file-previews" class="row">
                                                <div id="file-upload-row" class="col-sm-6 mt file-upload-row">
                                                    <div class="preview box-content pr-lg" style="width:100px;">
                                                        <img data-dz-thumbnail class="upload-thumbnail-sm"/>
                                                        <div class="mb progress progress-striped upload-progress-sm active mt-sm"
                                                             role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                                             aria-valuenow="0">
                                                            <div class="progress-bar progress-bar-success" style="width:0%;"
                                                                 data-dz-uploadprogress></div>
                                                        </div>
                                                    </div>
                                                    <div class="box-content">
                                                        <p class="clearfix mb0 p0">
                                                            <span class="name pull-left" data-dz-name></span>
                                                    <span data-dz-remove class="pull-right" style="cursor: pointer">
                                                    <i class="fa fa-times"></i>
                                                </span>
                                                        </p>
                                                        <p class="clearfix mb0 p0">
                                                            <span class="size" data-dz-size></span>
                                                        </p>
                                                        <strong class="error text-danger" data-dz-errormessage></strong>
                                                        <input class="file-count-field" type="hidden" name="files[]" value=""/>
                                                        <div class="mb progress progress-striped upload-progress-sm active mt-sm" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                                            <div class="progress-bar progress-bar-success w-0" data-dz-uploadprogress></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                    
                                    <?php
                                    if (!empty($transfer_info->attachement)) {
                                        $uploaded_file = json_decode($transfer_info->attachement);
                                    }
                                    if (!empty($uploaded_file)) {
                                        foreach ($uploaded_file as $v_files_image) { ?>
                                            <div class="pull-left mt pr-lg mb" style="width:100px;">
                                                <span data-dz-remove class="pull-right existing_image" style="cursor: pointer"><i class="fa fa-times"></i></span>
                                                <?php if ($v_files_image->is_image == 1) { ?>
                                                <img data-dz-thumbnail src="<?php echo base_url() . $v_files_image->path ?>" class="upload-thumbnail-sm img-fluid"/>
                                                <?php } else { ?>
                                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $v_files_image->fileName ?>" class="mailbox-attachment-icon"><i class="fa fa-file-text-o"></i></span>
                                                <?php } ?>

                                                <input type="hidden" name="path[]"
                                                       value="<?php echo $v_files_image->path ?>">
                                                <input type="hidden" name="fileName[]"
                                                       value="<?php echo $v_files_image->fileName ?>">
                                                <input type="hidden" name="fullPath[]"
                                                       value="<?php echo $v_files_image->fullPath ?>">
                                                <input type="hidden" name="size[]"
                                                       value="<?php echo $v_files_image->size ?>">
                                                <input type="hidden" name="is_image[]"
                                                       value="<?php echo $v_files_image->is_image ?>">
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".existing_image").click(function () {
                                                $(this).parent().remove();
                                            });

                                            fileSerial = 0;
                                            // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
                                            var previewNode = document.querySelector("#file-upload-row");
                                            previewNode.id = "";
                                            var previewTemplate = previewNode.parentNode.innerHTML;
                                            previewNode.parentNode.removeChild(previewNode);
                                            Dropzone.autoDiscover = false;
                                            var projectFilesDropzone = new Dropzone("#comments_file-dropzone", {
                                                url: "<?= base_url() ?>admin/global_controller/upload_file",
                                                thumbnailWidth: 80,
                                                thumbnailHeight: 80,
                                                parallelUploads: 20,
                                                previewTemplate: previewTemplate,
                                                dictDefaultMessage: '<div class="mb-3"><i class="display-4 text-muted bx bxs-cloud-upload"></i></div><h4>Drop files here or click to upload.</h4>',
                                                autoQueue: true,
                                                previewsContainer: "#comments_file-previews",
                                                clickable: true,
                                                accept: function (file, done) {
                                                    if (file.name.length > 200) {
                                                        done("Filename is too long.");
                                                        $(file.previewTemplate).find(".description-field").remove();
                                                    }
                                                    $.ajax({
                                                        url: "<?= base_url() ?>admin/global_controller/validate_project_file",
                                                        data: {
                                                            file_name: file.name,
                                                            file_size: file.size
                                                        },
                                                        cache: false,
                                                        type: 'POST',
                                                        dataType: "json",
                                                        success: function (response) {
                                                            if (response.success) {
                                                                fileSerial++;
                                                                $(file.previewTemplate).find(".description-field").attr("name", "comment_" + fileSerial);
                                                                $(file.previewTemplate).append("<input type='hidden' name='file_name_" + fileSerial + "' value='" + file.name + "' />\n\
                                                                                        <input type='hidden' name='file_size_" + fileSerial + "' value='" + file.size + "' />");
                                                                $(file.previewTemplate).find(".file-count-field").val(fileSerial);
                                                                done();
                                                            } else {
                                                                $(file.previewTemplate).find("input").remove();
                                                                done(response.message);
                                                            }
                                                        }
                                                    });
                                                },
                                                processing: function () {
                                                    $("#file-save-button").prop("disabled", true);
                                                },
                                                queuecomplete: function () {
                                                    $("#file-save-button").prop("disabled", false);
                                                },
                                                fallback: function () {
                                                    $("body").addClass("dropzone-disabled");
                                                    $('.modal-dialog').find('[type="submit"]').removeAttr('disabled');

                                                    $("#comments_file-dropzone").hide();

                                                    $("#file-modal-footer").prepend("<button id='add-more-file-button' type='button' class='btn  btn-default pull-left'><i class='fa fa-plus-circle'></i> " + "<?php echo lang("e"); ?>" + "</button>");

                                                    $("#file-modal-footer").on("click", "#add-more-file-button", function () {
                                                        var newFileRow = "<div class='file-row pb pt10 b-b mb10'>" +
                                                            "<div class='pb clearfix '><button type='button' class='btn btn-sm btn-danger pull-left mr remove-file'><i class='fa fa-times'></i></button> <input class='pull-left' type='file' name='manualFiles[]' /></div>" +
                                                            "<div class='mb5 pb5'><input class='form-control description-field cursor-auto'  name='comment[]'  type='text' placeholder='<?php echo lang("comment") ?>' /></div>" +
                                                            "</div>";
                                                        $("#comments_file-previews").prepend(newFileRow);
                                                    });
                                                    $("#add-more-file-button").trigger("click");
                                                    $("#comments_file-previews").on("click", ".remove-file", function () {
                                                        $(this).closest(".file-row").remove();
                                                    });
                                                },
                                                success: function (file,response) {
                                                    var res=JSON.parse(response);
                                                    if(res['error'] && res.length != 0){
                                                        toastr['error'](res['error']);
                                                        toastr['error']('<?=lang('docroom_connect_msg');?>');
                                                        $(file.previewElement).closest(".file-upload-row").remove();
                                                    }else{
                                                        var docroom_file_id=res['uploaded_file']['data'][0]['file_id'];
                                                        var docroom_file_id_html="<input class='form-control'  name='docroom_file_id[]'  type='hidden' value='"+docroom_file_id+"' />";
                                                        $("#comments_file-previews").prepend(docroom_file_id_html);
                                                        setTimeout(function () {
                                                            $(file.previewElement).find(".progress-striped").removeClass("progress-striped").addClass("progress-bar-success");
                                                        }, 1000);
                                                    }
                                                }
                                            });
                                        })
                                    </script>
                                </div>
                            </div>
                            
                            <div class="row mb-3" id="border-none">
                                <label class="col-md-3 col-form-label"><?= lang('assined_to') ?> <span class="required">*</span></label>
                                <div class="col-md-8">
                                    <div class="form-check form-radio-outline form-radio-primary mb-3">
                                        <input id="everyone" <?php
                                            if (!empty($transfer_info->permission) && $transfer_info->permission == 'all') {
                                                echo 'checked';
                                            }
                                            ?> type="radio" name="permission" value="everyone" class="form-check-input">
                                        <label class="form-check-label" for="everyone"><?= lang('everyone') ?>
                                            <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip"
                                               data-bs-placement="top"></i>
                                        </label>
                                    </div>
                                    <div class="form-check form-radio-outline form-radio-primary mb-3">
                                        <input id="custom_permission" <?php
                                            if (!empty($transfer_info->permission) && $transfer_info->permission != 'all') {
                                                echo 'checked';
                                            } elseif (empty($transfer_info)) {
                                                echo 'checked';
                                            }
                                            ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                                        <label class="form-check-label" for="custom_permission">
                                            <?= lang('custom_permission') ?>
                                            <i  title="<?= lang('permission_for_customization') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3 <?php
                                if (!empty($transfer_info->permission) && $transfer_info->permission != 'all') {
                                    echo 'show';
                                }
                                ?>" id="permission_user_1">
                                <label class="col-md-3 col-form-label"><?= lang('select') . ' ' . lang('users') ?> <span class="required">*</span></label>
                                <div class="col-md-5">
                                    <?php
                                    if (!empty($permission_user)) { ?>
                                        <input type="text" name="search_assigned_user" value="" placeholder="<?=lang('search_by').' '.lang('username'); ?>" class="form-control mb-4" id="search_assigned_user" autocomplete="off">
                                        <div data-simplebar style="max-height: 250px;">  
                                            <?php 
                                        foreach ($permission_user as $key => $v_user) {

                                            if ($v_user->role_id == 1) {
                                                $role = '<strong class="badge btn-danger">' . lang('admin') . '</strong>';
                                            } else {
                                                $role = '<strong class="badge btn-primary">' . lang('staff') . '</strong>';
                                            }

                                            ?>
                                    <div class="form-check form-check-primary mb-3">
                                        <input type="checkbox"
                                            <?php
                                            if (!empty($transfer_info->permission) && $transfer_info->permission != 'all') {
                                                $get_permission = json_decode($transfer_info->permission);
                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        echo 'checked';
                                                    }
                                                }

                                            }
                                            ?>  value="<?= $v_user->user_id ?>" name="assigned_to[]" data-name="<?= $v_user->username;?>"  class="form-check-input" id ="user_<?= $v_user->user_id ?>">
                                        <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?>
                                        </label>
                                    </div>
                                    <div class="action_1 p
                                        <?php

                                        if (!empty($transfer_info->permission) && $transfer_info->permission != 'all') {
                                            $get_permission = json_decode($transfer_info->permission);

                                            foreach ($get_permission as $user_id => $v_permission) {
                                                if ($user_id == $v_user->user_id) {
                                                    echo 'show';
                                                }
                                            }

                                        }
                                        ?>" id="action_1<?= $v_user->user_id ?>">
                                        <div class="form-check form-check-primary mb-3 mr">         
                                            <input id="view_<?= $v_user->user_id ?>" checked type="checkbox" name="action_1<?= $v_user->user_id ?>[]" disabled value="view" class="form-check-input">
                                            <label class="form-check-label" for="view_<?= $v_user->user_id ?>"><?= lang('view') ?></label>
                                        </div>
                                        <div class="form-check form-check-primary mb-3 mr">         
                                            <input <?php if (!empty($disable)) { echo 'disabled' . ' ' . 'checked'; } ?> id="edit_<?= $v_user->user_id ?>"
                                                <?php

                                                if (!empty($transfer_info->permission) && $transfer_info->permission != 'all') {
                                                    $get_permission = json_decode($transfer_info->permission);

                                                    foreach ($get_permission as $user_id => $v_permission) {
                                                        if ($user_id == $v_user->user_id) {
                                                            if (in_array('edit', $v_permission)) {
                                                                echo 'checked';
                                                            };

                                                        }
                                                    }

                                                }
                                                ?> type="checkbox" value="edit" name="action_<?= $v_user->user_id ?>[]" class="form-check-input">
                                            <label class="form-check-label" for="edit_<?= $v_user->user_id ?>"><?= lang('edit') ?></label>
                                        </div>
                                        <div class="form-check form-check-primary mb-3 mr">         
                                            <input <?php if (!empty($disable)) { echo 'disabled' . ' ' . 'checked'; } ?> id="delete_<?= $v_user->user_id ?>"
                                                <?php

                                                if (!empty($transfer_info->permission) && $transfer_info->permission != 'all') {
                                                    $get_permission = json_decode($transfer_info->permission);
                                                    foreach ($get_permission as $user_id => $v_permission) {
                                                        if ($user_id == $v_user->user_id) {
                                                            if (in_array('delete', $v_permission)) {
                                                                echo 'checked';
                                                            };
                                                        }
                                                    }

                                                }
                                                ?>  name="action_<?= $v_user->user_id ?>[]"  type="checkbox"  value="delete" class="form-check-input">
                                            <label class="form-check-label" for="delete_<?= $v_user->user_id ?>"><?= lang('delete') ?></label>
                                        </div>
                                        <input id="<?= $v_user->user_id ?>" type="hidden" name="action_<?= $v_user->user_id ?>[]" value="view">
                                    </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"></label>
                                <div class="col-lg-5 col-md-5 col-sm-7">
                                    <button type="submit" id="file-save-button" class="btn btn-sm btn-primary"><i
                                            class="fa fa-check"></i> <?= lang('submit') ?></button>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var maxAppend = 0;
        $("#add_more").click(function () {
            if (maxAppend >= 4) {
                alert("Maximum 5 File is allowed");
            } else {
                var add_new = $('<div class="row mb-3" style="margin-bottom: 0px">\n\
                    <label for="field-1" class="col-lg-3 col-md-3 col-sm-3 col-sm-3 col-form-label"><?= lang('attachment') ?></label>\n\
                            <div class="col-lg-4 col-sm-4">\n\
                            <div class="fileinput fileinput-new" data-provides="fileinput">\n\
                    <span class="btn btn-default btn-file"><span class="fileinput-new" >Select file</span><span class="fileinput-exists" >Change</span><input type="file" name="attachement[]" ></span> <span class="fileinput-filename"></span><a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none;">&times;</a></div></div>\n\<div class="col-sm-2">\n\<strong>\n\
                    <a href="javascript:void(0);" class="remCF"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div>');
                maxAppend++;
                $("#add_new").append(add_new);
            }
        });

        $("#add_new").on('click', '.remCF', function () {
            $(this).parent().parent().parent().remove();
        });
        $('a.RCF').click(function () {
            $(this).parent().parent().remove();
        });
    });
</script>