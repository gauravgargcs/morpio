<?= message_box('success') ?>
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
$can_edit = $this->tickets_model->can_action('tbl_tickets', 'edit', array('tickets_id' => $tickets_info->tickets_id));
$can_delete = $this->tickets_model->can_action('tbl_tickets', 'delete', array('tickets_id' => $tickets_info->tickets_id));
$where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $tickets_info->tickets_id, 'module_name' => 'tickets');
$check_existing = $this->tickets_model->check_by($where, 'tbl_pinaction');
if (!empty($check_existing)) {
    $url = 'remove_todo/' . $check_existing->pinaction_id;
    $btn = 'danger';
    $title = lang('remove_todo');
} else {
    $url = 'add_todo_list/tickets/' . $tickets_info->tickets_id;
    $btn = 'warning';
    $title = lang('add_todo_list');
}
$statusss = $tickets_info->status;
if (!empty($statusss)) {
    if ($statusss == 'answered') {
        $m_id = 8;
    }
    if ($statusss == 'open') {
        $m_id = 9;
    }
    if ($statusss == 'in_progress') {
        $m_id = 10;
    }
    if ($statusss == 'closed') {
        $m_id = 11;
    }
} else {
    $m_id = 7;
}
if (!empty($m_id)) {
    $edited = can_action($m_id, 'edited');
    $deleted = can_action($m_id, 'deleted');
}
?>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <ul class="list-group no-radius">
                    <?php
                    if ($tickets_info->status == 'open') {
                        $s_label = 'danger';
                    } elseif ($tickets_info->status == 'closed') {
                        $s_label = 'success';
                    } else {
                        $s_label = 'primary';
                    }
                    super_admin_invoice($tickets_info->companies_id,9);
                    ?>
                    <li class="list-group-item">
                        <?= lang('reporter') ?>
                        <span class="pull-right">
                            <a class="recect_task pull-left">
                                <?php
                                $profile_info = $this->db->where(array('user_id' => $tickets_info->reporter))->get('tbl_account_details')->row();
                                if (!empty($profile_info)) {
                                    ?>
                                    <img style="width: 18px;margin-left: 18px; height: 18px; border: 1px solid #aaa;" src="<?= base_url() . $profile_info->avatar ?>"  class="img-circle">
                                    <?= ($profile_info->fullname) ?>
                                <?php } else {
                                    echo '-';
                                } ?>
                            </a>
                        </span>
                    </li>

                    <li class="list-group-item">
                        <span class="pull-right">
                            <?php
                            $dept_info = $this->db->where(array('departments_id' => $tickets_info->departments_id))->get('tbl_departments')->row();
                            if (!empty($dept_info)) {
                                $dept_name = $dept_info->deptname;
                            } else {
                                $dept_name = '-';
                            }
                            echo $dept_name;
                            ?>
                        </span><?= lang('department') ?>
                    </li>
                    <?php
                    if ($tickets_info->status == 'in_progress') {
                        $status = 'In Progress';
                    } else {
                        $status = $tickets_info->status;
                    }
                    ?>
                    <li class="list-group-item">
                        <span class="pull-right"><label
                                class="label label-<?= $s_label ?>"><?= ($status) ?></label>
                        </span><?= lang('status') ?>
                    </li>
                    <li class="list-group-item"><span  class="pull-right"><?= $tickets_info->priority ?></span><?= lang('priority') ?></li>
                    <li class="list-group-item"><span   class="pull-right"><?= $tickets_info->created ?></span><?= lang('created') ?></li>
                    <?php $show_custom_fields = custom_form_label(7, $tickets_info->tickets_id);
                    if (!empty($show_custom_fields)) {
                        foreach ($show_custom_fields as $c_label => $v_fields) {
                            if (!empty($v_fields)) {
                                ?>
                                <li class="list-group-item"><span
                                        class="pull-right"><?= $v_fields ?></span><?= $c_label ?></li>
                            <?php }
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <div class="pull-right text-sm">
                    
                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                        <a href="<?= base_url() ?>admin/tickets/index/edit_tickets/<?= $tickets_info->tickets_id ?>"
                           class="btn btn-sm btn-primary">
                            <i class="fa fa-edit"></i></a>
                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                        <div class="dropdown btn-group">
                            <button class="btn btn-success dropdown-toggle btn-sm" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_status') ?><i class="mdi mdi-chevron-down"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <?php
                                $status_info = $this->db->get('tbl_status')->result();
                                if (!empty($status_info)) {
                                    foreach ($status_info as $v_status) {
                                ?>
                                <a class="dropdown-item" data-bs-toggle='modal' data-bs-target='#myModal' href="<?= base_url() ?>admin/tickets/change_status/<?= $tickets_info->tickets_id ?>/<?= $v_status->status ?>"><?= ucfirst($v_status->status) ?></a>
                                
                                <?php   }  }   ?>
                            </div>
                        </div>
                        <?php }
                        $all_tickets_info = $this->db->where(array('project_id' => $tickets_info->project_id))->get('tbl_project')->result();
                        ?>

                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $title ?>"
                           href="<?= base_url() ?>admin/projects/<?= $url ?>"
                           class="mr-sm btn btn-sm btn-<?= $btn ?>"><i class="fa fa-thumb-tack"></i></a>
                    <?php } ?>
                </div>
                <h4 class="card-title mb-4">
                    <?php

                    if ($tickets_info->project_id != '0') {
                        $project_info = $this->db->where('project_id', $tickets_info->project_id)->get('tbl_project')->row();

                        if (!empty($project_info)) {
                            ?>
                            <strong><?= lang('project') ?>:</strong>
                            <a href="<?= base_url() ?>admin/projects/project_details/<?= $tickets_info->project_id ?>" class="">
                                <?= $project_info->project_name ?>
                            </a>
                        <?php }
                    }
                    ?>
                </h4>
                <div class="card border">
                    <div class="card-body chat">
                        <h4 class="card-title mb-4"> [ <?= $tickets_info->ticket_code ?>
                            ] <?= $tickets_info->subject; ?></h4>
                        <?= convert_to_html($tickets_info->body); ?>
                        <ul class="mailbox-attachments clearfix mt">
                            <?php
                            $uploaded_file = json_decode($tickets_info->upload_file);

                            if (!empty($uploaded_file)):
                                foreach ($uploaded_file as $v_files):

                                    if (!empty($v_files)):

                                        ?>
                                        <li>
                                            <?php if ($v_files->is_image == 1) : ?>
                                                <span class="mailbox-attachment-icon has-img"><img
                                                        src="<?= base_url() . $v_files->path ?>"
                                                        alt="Attachment"></span>
                                            <?php else : ?>
                                                <span class="mailbox-attachment-icon"><i
                                                        class="fa fa-file-pdf-o"></i></span>
                                            <?php endif; ?>
                                            <div class="mailbox-attachment-info">
                                                <a href="<?= base_url() ?>admin/tickets/index/download_file/<?= $tickets_info->tickets_id . '/' . $v_files->fileName ?>"
                                                   class="mailbox-attachment-name"><i class="fa fa-paperclip"></i>
                                                    <?= $v_files->fileName ?></a>
                                <span class="mailbox-attachment-size">
                                  <?= $v_files->size ?> <?= lang('kb') ?>
                                    <a href="<?= base_url() ?>admin/tickets/index/download_file/<?= $tickets_info->tickets_id . '/' . $v_files->fileName ?>"
                                       class="btn btn-default btn-sm pull-right"><i class="fa fa-cloud-download"></i></a>
                                </span>
                                            </div>
                                        </li>
                                        <?php
                                    endif;
                                endforeach;
                            endif;
                            $comment_type = 'tickets';
                            ?>
                        </ul>
                        <button data-bs-toggle="collapse" data-bs-target="#topic-reply" class="btn btn-primary mb mt" aria-expanded="true"><?= lang('reply_ticket') ?>
                        </button>
                        <?php echo form_open(base_url("admin/tickets/save_tickets_reply/" . $tickets_info->tickets_id), array("id" => $comment_type . "-comment-form", "class" => "form-horizontal general-form", "enctype" => "multipart/form-data", "role" => "form")); ?>
                        <div id="topic-reply" class="collapse" aria-expanded="true">
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <textarea class="form-control no-border reply-body" name="body" rows="3" placeholder="<?= lang('tickets') ?> #<?= $tickets_info->ticket_code ?> <?= lang('reply') ?>"></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <div id="comments_file-dropzone" class="dropzone mb15">

                                    </div>
                                    <div id="comments_file-dropzone-scrollbar">
                                        <div id="comments_file-previews">
                                            <div id="file-upload-row" class="mt pull-left">
                                                <div class="preview box-content pr-lg" style="width:100px;">
                                                            <span data-dz-remove class="pull-right" style="cursor: pointer">
                                            <i class="fa fa-times"></i>
                                        </span>
                                                    <img data-dz-thumbnail class="upload-thumbnail-sm"/>
                                                    <input class="file-count-field" type="hidden" name="files[]"
                                                           value=""/>
                                                    <div
                                                        class="mb progress progress-striped upload-progress-sm active mt-sm"
                                                        role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                                        aria-valuenow="0">
                                                        <div class="progress-bar progress-bar-success" style="width:0%;"
                                                             data-dz-uploadprogress></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <div class="pull-right">
                                        <button type="submit" id="file-save-button" class="btn btn-primary"><?= lang('reply') ?></button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <hr/>
                        <?php echo form_close(); ?>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $("#<?php echo $comment_type; ?>-comment-form").appForm({
                                    isModal: false,
                                    onSuccess: function (result) {
                                        $(".reply-body").val("");
                                        $(".dz-complete").remove();
                                        $('#btn-<?php echo $comment_type ?>').removeClass("disabled").html('<?= lang('post_comment')?>');
                                        $(result.data).insertAfter("#<?php echo $comment_type; ?>-comment-form");
                                        toastr[result.status](result.message);
                                    }
                                });
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

                                        $("#file-modal-footer").prepend("<button id='add-more-file-button' type='button' class='btn  btn-default pull-left'><i class='fa fa-plus-circle'></i> " + "<?php echo lang("add_more"); ?>" + "</button>");

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
                        <?php
                        $ticket_replies = $this->db->where(array('tickets_id' => $tickets_info->tickets_id, 'ticket_reply_id' => 0))->order_by('time', 'DESC')->get('tbl_tickets_replies')->result();
                        $this->load->view('admin/tickets/tickets_reply', array('ticket_replies' => $ticket_replies)) ?>
                    </div><!-- /.panel body -->
                </div>
            </div>
        </div>     
    </div>
</div>