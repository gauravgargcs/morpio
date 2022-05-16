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
<style>
    .note-editor .note-editable {
        height: 150px;
    }
     blockquote img{
        width: 100% !important;
    }
</style>
<?php
$edited = can_action('58', 'edited');
$can_edit = $this->bugs_model->can_action('tbl_bug', 'edit', array('bug_id' => $bug_details->bug_id));
$comment_details = $this->db->where(array('bug_id' => $bug_details->bug_id, 'comments_reply_id' => '0', 'task_attachment_id' => '0', 'uploaded_files_id' => '0'))->order_by('comment_datetime', 'DESC')->get('tbl_task_comment')->result();
$activities_info = $this->db->where(array('module' => 'bugs', 'module_field_id' => $bug_details->bug_id))->order_by('activity_date', 'DESC')->get('tbl_activities')->result();
$all_task_info = $this->db->where('bug_id', $bug_details->bug_id)->order_by('task_id', 'DESC')->get('tbl_task')->result();

$where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $bug_details->bug_id, 'module_name' => 'bugs');
$check_existing = $this->bugs_model->check_by($where, 'tbl_pinaction');
if (!empty($check_existing)) {
    $url = 'remove_todo/' . $check_existing->pinaction_id;
    $btn = 'danger';
    $title = lang('remove_todo');
} else {
    $url = 'add_todo_list/bugs/' . $bug_details->bug_id;
    $btn = 'warning';
    $title = lang('add_todo_list');
}
?>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link mb-2 <?= $active == 1 ? 'active' : '' ?>" data-bs-toggle="pill" href="#task_details" role="tab" aria-controls="task_details" aria-selected="false"><?= lang('details')?></a>
                    
                    <a class="nav-link mb-2 <?= $active == 2 ? 'active' : '' ?>" data-bs-toggle="pill" href="#task_comments" role="tab" aria-controls="task_comments" aria-selected="false"><?= lang('comments')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($comment_details) ? count($comment_details) : null) ?></strong></a>

                    <a class="nav-link mb-2 <?= $active == 3 ? 'active' : '' ?>" data-bs-toggle="pill" href="#task_attachments" role="tab" aria-controls="task_attachments" aria-selected="false"><?= lang('attachment')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($project_files_info) ? count($project_files_info) : null) ?></strong></a>

                    <a class="nav-link mb-2 <?= $active == 4 ? 'active' : '' ?>" data-bs-toggle="pill" href="#task_notes" role="tab" aria-controls="task_notes" aria-selected="false"><?= lang('notes')?></a>
                    
                    <a class="nav-link mb-2 <?= $active == 5 ? 'active' : '' ?>" data-bs-toggle="pill" href="#activities" role="tab" aria-controls="activities" aria-selected="false"><?= lang('activities')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($activities_info) ? count($activities_info) : null) ?></strong></a>

                    <a class="nav-link mb-2 <?= $active == 6 ? 'active' : '' ?>" data-bs-toggle="pill" href="#tasks" role="tab" aria-controls="tasks" aria-selected="false"><?= lang('tasks')?><strong class="badge badge-soft-danger pull-right"><?= (!empty($all_task_info) ? count($all_task_info) : null) ?></strong></a>

                    
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">

                    <!-- Details tab Starts -->
                    <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="task_details" style="position: relative;">
                        <div class="card-body">
                            <div class="pull-right text-sm">
                               
                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                    <a class="btn btn-primary btn-sm" href="<?= base_url() ?>admin/bugs/index/<?= $bug_details->bug_id ?>"><?= lang('edit') . ' ' . lang('bugs') ?></a>
                                <?php } ?>

                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $title ?>" href="<?= base_url() ?>admin/projects/<?= $url ?>" class="btn-sm btn btn-<?= $btn ?>"><i class="fa fa-thumb-tack"></i></a>

                            </div>
                            <h4 class="card-title mb-4"><?php if (!empty($bug_details->bug_title)) { echo $bug_details->bug_title; } ?></h4>
                            
                            <div class="row form-horizontal task_details">
                                <div class="col-lg-6">
                                    <p class="lead bb"></p>
                                    <form class="form-horizontal p-20">

                                        <?php super_admin_details($bug_details->companies_id, 5,6) ?>
                                        <?php
                                        if (!empty($bug_details->opportunities_id)):
                                        $opportunity_info = $this->db->where('opportunities_id', $bug_details->opportunities_id)->get('tbl_opportunities')->row();
                                        ?>
                                        <div class="row mb-3">
                                            <div class="col-md-5 col-5"><strong><?= lang('opportunity_name') ?> :</strong></div>
                                            <div class="col-md-6 col-6">
                                                <?php if (!empty($opportunity_info->opportunity_name)) echo $opportunity_info->opportunity_name; ?>
                                            </div>
                                        </div>

                                        <?php endif ?>

                                        <div class="row mb-3">
                                            <div class="col-md-5 col-5"><strong><?= lang('reporter') ?> :</strong></div>
                                            <div class="col-md-6 col-6">
                                                <?php if (!empty($bug_details->reporter)) {
                                                $users_info = $this->db->where('user_id', $bug_details->reporter)->get('tbl_account_details')->row();
                                                ?>
                                                <a href="<?= base_url() ?>admin/user/user_details/<?= $users_info->user_id ?>"> <span
                                                        class="badge btn-primary "><?= $users_info->fullname ?></span></a>
                                            <?php } ?>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-5 col-5"><strong><?= lang('update_on') ?> :</strong></div>
                                            <div class="col-md-6 col-6">
                                                <?= display_datetime($bug_details->update_time) ?>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-5 col-5"><strong><?= lang('participants') ?> :</strong></div>
                                            <div class="col-md-6 col-6">
                                                <div class="avatar-group">
                                                    <?php
                                                    if ($bug_details->permission != 'all') {
                                                        $get_permission = json_decode($bug_details->permission);
                                                        if (!empty($get_permission)) :
                                                        foreach ($get_permission as $permission => $v_permission) :
                                                            $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                                                            if ($user_info->role_id == 1) {
                                                                $label = 'text-danger';
                                                            } else {
                                                                $label = 'text-success';
                                                            }
                                                            $profile_info = $this->db->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                                            ?>

                                                    <div class="avatar-group-item">
                                                        <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $profile_info->fullname ?>" class="d-inline-block"><img src="<?= base_url() . $profile_info->avatar ?>" class="rounded-circle avatar-xs" alt=""> <span style="margin: 0px 0 8px -10px;" class="mdi mdi-circle <?= $label ?> font-size-10"></span>
                                                        </a>
                                                    </div>
                                                        <?php
                                                        endforeach;
                                                        endif;
                                                    } else { ?>
                                                    <span class="mr-lg-2 mt-2">
                                                        <strong><?= lang('everyone') ?></strong>
                                                        <i  title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                                    </span>

                                                    <?php
                                                    }
                                                    ?>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                    <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?= lang('add_more') ?>" class="mt-2">
                                                        <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/bugs/update_users/<?= $bug_details->bug_id ?>" class="text-default"><i class="fa fa-plus"></i></a>
                                                    </span>
                                                       
                                                    <?php  } ?>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                                <div class="col-lg-6">
                                    <p class="lead bb"></p>
                                    <form class="form-horizontal p-20">
                                        <div class="row mb-3">
                                            <div class="col-md-5 col-5"><strong><?= lang('bug_title') ?> :</strong></div>
                                            <div class="col-md-6 col-6">
                                                <?php
                                                if (!empty($bug_details->bug_title)) {
                                                    echo $bug_details->bug_title;
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php if (!empty($bug_details->project_id)):
                                        $project_info = $this->db->where('project_id', $bug_details->project_id)->get('tbl_project')->row();
                                        ?>
                                        <div class="row mb-3">
                                            <div class="col-md-5 col-5"><strong><?= lang('project_name') ?> :</strong></div>
                                            <div class="col-md-6 col-6">
                                                <?php if (!empty($project_info->project_name)) echo $project_info->project_name; ?>
                                            </div>
                                        </div>
                                        <?php endif ?>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-5 col-5"><strong><?= lang('bug_status') ?> :</strong></div>
                                            <div class="col-md-3 col-3">
                                                <?php
                                                    if ($bug_details->bug_status == 'unconfirmed') {
                                                        $label = 'warning';
                                                    } elseif ($bug_details->bug_status == 'confirmed') {
                                                        $label = 'info';
                                                    } elseif ($bug_details->bug_status == 'in_progress') {
                                                        $label = 'primary';
                                                    } elseif ($bug_details->bug_status == 'resolved') {
                                                        $label = 'purple';
                                                    } else {
                                                        $label = 'success';
                                                    }
                                                    $user_info = $this->db->where('user_id', $bug_details->reporter)->get('tbl_users')->row();
                                                    ?>
                                                <span class="badge badge-soft-<?= $label ?>"><?php if (!empty($bug_details->bug_status)) echo lang($bug_details->bug_status); ?></span>
                                            </div>
                                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                            <div class="col-md-1 col-1">
                                                <div class="btn-group" role="group">
                                                    <button id="btnGroupVerticalDrop1" type="button" class="btn btn-success dropdown-toggle font-size-11 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?= lang('change') ?>"><i class="bx bxs-edit-alt"></i><i class="mdi mdi-chevron-down"></i></button>

                                                    <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                    
                                                        <a class="dropdown-item" href="<?= base_url() ?>admin/bugs/change_status/<?= $bug_details->bug_id ?>/unconfirmed"><?= lang('unconfirmed') ?></a>
                                                        
                                                        <a class="dropdown-item" href="<?= base_url() ?>admin/bugs/change_status/<?= $bug_details->bug_id ?>/confirmed"><?= lang('confirmed') ?></a>

                                                        <a class="dropdown-item" href="<?= base_url() ?>admin/bugs/change_status/<?= $bug_details->bug_id ?>/in_progress"><?= lang('in_progress') ?></a>

                                                        <a class="dropdown-item" href="<?= base_url() ?>admin/bugs/change_status/<?= $bug_details->bug_id ?>/resolved"><?= lang('resolved') ?></a>

                                                        <a class="dropdown-item" href="<?= base_url() ?>admin/bugs/change_status/<?= $bug_details->bug_id ?>/verified"><?= lang('verified') ?></a>

                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-5 col-5"><strong><?= lang('priority') ?> :</strong></div>
                                            <div class="col-md-6 col-6">
                                                <?php
                                                if ($bug_details->priority == 'High') {
                                                    $label = 'danger';
                                                } elseif ($bug_details->priority == 'Medium') {
                                                    $label = 'info';
                                                } else {
                                                    $label = 'primary';
                                                }
                                                ?>
                                                <span class="badge btn-<?= $label ?>"><?php if (!empty($bug_details->priority)) echo $bug_details->priority; ?></span>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-5 col-5"><strong><?= lang('created_date') ?> :</strong></div>
                                            <div class="col-md-6 col-6">
                                                <?= display_datetime($bug_details->created_time) ?>
                                            </div>
                                        </div>


                                    </form>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php $show_custom_fields = custom_form_label(6, $bug_details->bug_id);
                                    if (!empty($show_custom_fields)) {
                                        foreach ($show_custom_fields as $c_label => $v_fields) {
                                            if (!empty($v_fields)) {
                                                if (count($v_fields) == 1) {
                                                    $col = 'col-md-10';
                                                    $sub_col = 'col-md-3';
                                                    $style = 'padding-left:21px';
                                                } else {
                                                    $col = 'col-md-6';
                                                    $sub_col = 'col-md-5 col-5';
                                                    $style = null;
                                                }
                                                ?>
                                                <div class="row mb-3  <?= $col ?>" style="<?= $style ?>">
                                                    <div class="<?= $sub_col ?>"><strong><?= $c_label ?> :</strong></div>
                                                    <div class="col-md-6 col-6"><strong><?= $v_fields ?></strong></div>
                                                </div>
                                            <?php }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <blockquote style="font-size: 12px;word-wrap: break-word;"><?php
                                            if (!empty($bug_details->bug_description)) {
                                                echo $bug_details->bug_description;
                                            }
                                    ?></blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Details tab Ends -->

                    <?php $comment_type = 'bugs'; ?>
                    <!-- Comments Panel Starts --->
                    <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="task_comments" style="position: relative;">
                        <div class="card-body">    
                            <h4 class="card-title mb-4"><?= lang('comments') ?></h4>
                            <div class="chat" id="chat-box">

                                <?php echo form_open(base_url("admin/bugs/save_comments"), array("id" => $comment_type . "-comment-form", "class" => "form-horizontal general-form", "enctype" => "multipart/form-data", "role" => "form")); ?>
                                <input type="hidden" name="bug_id" value="<?php
                                if (!empty($bug_details->bug_id)) {
                                    echo $bug_details->bug_id;
                                }
                                ?>" class="form-control">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <?php
                                        echo form_textarea(array(
                                            "id" => "comment_description",
                                            "name" => "comment",
                                            "class" => "form-control comment_description",
                                            "placeholder" => $bug_details->bug_title . ' ' . lang('comments'),
                                            "data-rule-required" => true,
                                            "rows" => 4,
                                            "data-msg-required" => lang("field_required"),
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <div id="new_comments_attachement">
                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <div id="comments_file-dropzone" class="dropzone mb15">
                                            </div>
                                            <div id="comments_file-dropzone-scrollbar">
                                                <div id="comments_file-previews">
                                                    <div id="file-upload-row" class="mt pull-left">
                                                        
                                                        <div class="preview box-content pr-lg w-100">
                                                            <span data-dz-remove class="pull-right pointer">
                                                                <i class="fa fa-times"></i>
                                                            </span>
                                                            <img data-dz-thumbnail class="upload-thumbnail-sm"/>
                                                            <input class="file-count-field" type="hidden" name="files[]" value=""/>

                                                            <div class="mb progress progress-striped upload-progress-sm active mt-sm"
                                                                 role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                                                <div class="progress-bar progress-bar-success w-0" data-dz-uploadprogress></div>
                                                            </div>
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
                                            <button type="submit" id="file-save-button"
                                                    class="btn btn-primary"><?= lang('post_comment') ?></button>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <?php echo form_close();
                                $comment_reply_type = 'bugs-reply';
                                ?>
                                <?php $this->load->view('admin/bugs/comments_list', array('comment_details' => $comment_details)) ?>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $("#<?php echo $comment_type; ?>-comment-form").appForm({
                                            isModal: false,
                                            onSuccess: function (result) {
                                                $(".comment_description").val("");
                                                $(".dz-complete").remove();
                                                $(result.data).insertAfter("#<?php echo $comment_type; ?>-comment-form");
                                                toastr[result.status](result.message);
                                            }
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
                            </div>
                        </div>
                    </div>
                    <!-- Comments Panel Ends--->

                    <!-- Attachment Panel Starts --->
                    <div class="tab-pane <?= $active == 3 ? 'active' : '' ?>" id="task_attachments">
                        <div class="card-body">    
                            <div class="row">
                                <div class="col-md-4 col-7">
                                    <h4 class="card-title mt"><?= lang('attach_file_list') ?> </h4>
                                </div>
                                <div class="col-md-8 col-5">
                                    <?php
                                    $attach_list = $this->session->userdata('leads_media_view');
                                    if (empty($attach_list)) {
                                        $attach_list = 'list_view';
                                    }
                                    ?>
                                    <ul class="list-inline user-chat-nav text-end mb-0">
                                        <li class="list-inline-item  d-sm-inline-block">
                                            <a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-default toggle-media-view <?= (!empty($attach_list) && $attach_list == 'list_view' ? 'hidden' : '') ?>" data-bs-type="list_view" title="<?= lang('switch_to') . ' ' . lang('media_view') ?>"><i class="fa fa-image"></i></a>
                                        
                                            <a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-default toggle-media-view <?= (!empty($attach_list) && $attach_list == 'media_view' ? 'hidden' : '') ?>" data-bs-type="media_view" title="<?= lang('switch_to') . ' ' . lang('list_view') ?>"><i class="fa fa-list"></i></a>
                                        </li>
                                        <li class="list-inline-item d-sm-inline-block">
                                            <a href="<?= base_url() ?>admin/bugs/new_attachment/<?= $bug_details->bug_id ?>" class="text-primary text-sm" data-bs-toggle="modal" data-bs-placement="top"  data-bs-target="#myModal_extra_lg">
                                                <span class="d-block d-sm-none"><i class="fa fa-plus "></i></span>
                                                <span class="d-none d-sm-block"><?= lang('new') . ' ' . lang('attachment') ?></span>
                                            </a>
                                         </li>
                                    </ul>
                                </div>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $(".toggle-media-view").click(function () {
                                            $(".media-view-container").toggleClass('hidden');
                                            $(".toggle-media-view").toggleClass('hidden');
                                            $(".media-list-container").toggleClass('hidden');
                                            var type = $(this).data('bs-type');
                                            var module = 'bugs';
                                            $.get('<?= base_url()?>admin/global_controller/set_media_view/' + type + '/' + module, function (response) {
                                            });
                                        });
                                    });
                                </script>
                                <?php
                                $this->load->helper('file');
                                if (empty($project_files_info)) {
                                    $project_files_info = array();
                                } ?>
                                <div class="p media-view-container <?= (!empty($attach_list) && $attach_list == 'media_view' ? 'hidden' : '') ?>">
                                    <div class="row">
                                        <?php $this->load->view('admin/bugs/attachment_list', array('project_files_info' => $project_files_info)); ?>
                                    </div>
                                </div>
                                <div class="media-list-container <?= (!empty($attach_list) && $attach_list == 'list_view' ? 'hidden' : '') ?>">
                                    <div class="col-md-12 pr0 mb-sm">
                                        <div class="card shadow border">
                                            <div class="card-body">
                                                <div class="accordion accordion-flush" id="accordionFlushExample">

                                                <?php
                                                if (!empty($project_files_info)) {
                                                    foreach ($project_files_info as $key => $v_files_info) {
                                                        ?>
                                                    <div class="accordion-item" id="media_list_container-<?= $files_info[$key]->task_attachment_id ?>">
                                                        <h2 class="card-title accordion-header" id="flush-headingOne">        
                                                            <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#attachment-<?php echo $key ?>" aria-expanded="true" aria-controls="flush-collapseOne">
                                                                <span style="width:80%"><?php echo $files_info[$key]->title; ?></span>
                                                                <div class="pull-right" style="margin-left:15%">
                                                                    <?php if ($files_info[$key]->user_id == $this->session->userdata('user_id')) { ?>
                                                                        <?php echo ajax_anchor(base_url("admin/bugs/delete_bug_files/" . $files_info[$key]->task_attachment_id), "<i class='text-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#media_list_container-" . $files_info[$key]->task_attachment_id)); ?>
                                                                    <?php } ?>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="attachment-<?php echo $key ?>" class="accordion-collapse collapse <?php if (!empty($in) && $files_info[$key]->files_id == $in) { echo 'show'; } ?>" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body text-muted">
                                                                <div class="table-responsive">
                                                                    <table id="bugs_files_datatable" class="table table-striped dt-responsive nowrap w-100 "> 
                                                                        <thead>
                                                                        <tr>
                                                                            <th><?= lang('files') ?></th>
                                                                            <th class=""><?= lang('size') ?></th>
                                                                            <th><?= lang('date') ?></th>
                                                                            <th><?= lang('total') . ' ' . lang('comments') ?></th>
                                                                            <th><?= lang('uploaded_by') ?></th>
                                                                            <th><?= lang('action') ?></th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                        $this->load->helper('file');

                                                                        if (!empty($v_files_info)) {
                                                                            foreach ($v_files_info as $v_files) {
                                                                                $user_info = $this->db->where(array('user_id' => $files_info[$key]->user_id))->get('tbl_users')->row();
                                                                                $total_file_comment = count($this->db->where(array('uploaded_files_id' => $v_files->uploaded_files_id))->order_by('comment_datetime', 'DESC')->get('tbl_task_comment')->result()); ?>
                                                                            <tr class="file-item">
                                                                                <td data-bs-toggle="tooltip"  data-bs-placement="top" data-original-title="<?= $files_info[$key]->description ?>">
                                                                                    <?php if ($v_files->is_image == 1) : ?>
                                                                                        <div class="file-icon"><a
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#myModal_extra_lg"
                                                                                                href="<?= base_url() ?>admin/bugs/attachment_details/r/<?= $files_info[$key]->task_attachment_id . '/' . $v_files->uploaded_files_id ?>">
                                                                                                <img
                                                                                                    style="width: 50px;border-radius: 5px;"
                                                                                                    src="<?= base_url() . $v_files->files ?>"/></a>
                                                                                        </div>
                                                                                    <?php else : ?>
                                                                                        <div class="file-icon"><i
                                                                                                class="fa fa-file-o"></i>
                                                                                            <a data-bs-toggle="modal"
                                                                                               data-bs-target="#myModal_extra_lg"
                                                                                               href="<?= base_url() ?>admin/bugs/attachment_details/r/<?= $files_info[$key]->task_attachment_id . '/' . $v_files->uploaded_files_id ?>"><?= $v_files->file_name ?></a>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </td>

                                                                                <td class=""><?= $v_files->size ?>Kb</td>
                                                                                <td class="col-date"><?= date('d-m-Y H-i', strtotime($files_info[$key]->upload_time)); ?></td>
                                                                                <td class=""><?= $total_file_comment ?></td>
                                                                                <td>
                                                                                    <?= $user_info->username ?>
                                                                                </td>
                                                                                <td>
                                                                                    <a class="btn btn-xs btn-dark"
                                                                                       data-bs-toggle="tooltip"
                                                                                       data-bs-placement="top"
                                                                                       title="Download"
                                                                                       href="<?= base_url() ?>admin/bugs/download_files/<?= $v_files->uploaded_files_id ?>"><i
                                                                                            class="fa fa-download"></i></a>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php  }  } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane <?= $active == 6 ? 'active' : '' ?>" id="tasks" style="position: relative;">
                        <ul class="nav nav-tabs bg-light rounded">
                            <li class="nav-item">
                                <a class="nav-link active" href="#manageTasks" data-bs-toggle="tab"><?= lang('all_task') ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url() ?>admin/tasks/all_task/bugs/<?= $bug_details->bug_id ?>"><?= lang('new_task') ?></a>
                            </li>
                        </ul>
                        <div class="tab-content p-3 text-muted">
                            <!-- ************** general *************-->
                            <div class="tab-pane active" id="manageTasks" style="position: relative;">
                                <div class="table-responsive">
                                    <table id="bugs_tasks_datatable" class="table table-striped dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th data-check-all>
                                                    <div class="form-check font-size-16 check-all">
                                                        <input type="checkbox" id="parent_present" class="form-check-input">
                                                        <label for="parent_present" class="toggle form-check-label"></label>
                                                    </div>
                                                </th>
                                                <th class="col-sm-4"><?= lang('task_name') ?></th>
                                                <th class="col-sm-2"><?= lang('due_date') ?></th>
                                                <th class="col-sm-1"><?= lang('status') ?></th>
                                                <th class="col-sm-1"><?= lang('progress') ?></th>
                                                <th class="col-sm-3"><?= lang('changes/view') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($all_task_info)):foreach ($all_task_info as $key => $v_task):
                                            ?>
                                            <tr>
                                                <td class="col-sm-1">
                                                    <div class="complete form-check font-size-16">
                                                        <input type="checkbox" data-id="<?= $v_task->task_id ?>" style="position: absolute;" <?php
                                                            if ($v_task->task_progress >= 100) {
                                                                echo 'checked';
                                                            }
                                                            ?> class="form-check-input">
                                                        <label class="form-check-label">
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a style="<?php
                                                    if ($v_task->task_progress >= 100) {
                                                        echo 'text-decoration: line-through;';
                                                    }
                                                    ?>"
                                                       href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
                                                </td>
                                                <td><?php
                                                    $due_date = $v_task->due_date;
                                                    $due_time = strtotime($due_date);
                                                    $current_time = time();
                                                    ?>
                                                    <?= display_datetime($due_date) ?>
                                                    <?php if ($current_time > $due_time && $v_task->task_progress < 100) { ?>
                                                        <span class="badge badge-soft-danger"><?= lang('overdue') ?></span>
                                                    <?php } ?></td>
                                                <td><?php
                                                    if ($v_task->task_status == 'completed') {
                                                        $label = 'success';
                                                    } elseif ($v_task->task_status == 'not_started') {
                                                        $label = 'info';
                                                    } elseif ($v_task->task_status == 'deferred') {
                                                        $label = 'danger';
                                                    } else {
                                                        $label = 'warning';
                                                    }
                                                    ?>
                                                    <span
                                                        class="badge badge-soft-<?= $label ?>"><?= lang($v_task->task_status) ?> </span>
                                                </td>
                                                <td>
                                                    <div class="inline ">
                                                        <div class="easypiechart text-success"
                                                             style="margin: 0px;"
                                                             data-percent="<?= $v_task->task_progress ?>"
                                                             data-line-width="5" data-track-Color="#f0f0f0"
                                                             data-bar-color="#<?php
                                                             if ($v_task->task_progress == 100) {
                                                                 echo '8ec165';
                                                             } else {
                                                                 echo 'fb6b5b';
                                                             }
                                                             ?>" data-rotate="270" data-scale-Color="false"
                                                             data-size="50" data-animate="2000">
                                                            <span class="small text-muted"><?= $v_task->task_progress ?>
                                                                %</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php echo btn_delete('admin/tasks/delete_task/' . $v_task->task_id) ?>
                                                    <?php echo btn_edit('admin/tasks/all_task/' . $v_task->task_id) ?>
                                                    <?php

                                                    if ($v_task->timer_status == 'on') { ?>
                                                        <a class="btn btn-outline-danger btn-sm"
                                                           href="<?= base_url() ?>admin/tasks/tasks_timer/off/<?= $v_task->task_id ?>"><?= lang('stop_timer') ?> </a>

                                                    <?php } else { ?>
                                                        <a class="btn btn-outline-success btn-sm"
                                                           href="<?= base_url() ?>admin/tasks/tasks_timer/on/<?= $v_task->task_id ?>"><?= lang('start_timer') ?> </a>
                                                    <?php } ?>
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

                    <!-- Task Attachment Panel Ends --->
                    <div class="tab-pane <?= $active == 4 ? 'active' : '' ?>" id="task_notes" style="position: relative;">
                        <div class="card-body">
                            <h4 class="card-title mb-4 mt"><?= lang('notes') ?></h4>                         
                            <div class="row">
                                <form action="<?= base_url() ?>admin/bugs/save_bugs_notes/<?php if (!empty($bug_details)) { echo $bug_details->bug_id; } ?>" enctype="multipart/form-data" method="post" id="form" class="form-horizontal">
                                    <div class="row mb-3">
                                        <div class="col-xl-12">
                                            <textarea class="form-control textarea" id="elm1" name="notes"><?= $bug_details->notes ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-xl-2">
                                            <button type="submit" id="sbtn" class="btn btn-primary pull-left"><?= lang('updates') ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 7 ? 'active' : '' ?>" id="activities" style="position: relative;">
                        <div class="card-body">
                            <?php
                            $role = $this->session->userdata('user_type');
                            if ($role == 1) { ?>
                            <div class="pull-right text-sm">
                                <span class="btn-xs pull-right">
                                <a href="<?= base_url() ?>admin/tasks/claer_activities/bugs/<?= $bug_details->bug_id ?>"><?= lang('clear') . ' ' . lang('activities') ?></a>
                                </span>
                            </div>
                            <?php } ?>
                            <h4 class="card-title mb-4 mt"><?= lang('activities') ?></h4>
                            <div data-simplebar style="max-height: 800px;">  
                                <ul class="verti-timeline list-unstyled">
                                    <?php
                                    if (!empty($activities_info)) {
                                        foreach ($activities_info as $v_activities) {
                                            $profile_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_account_details')->row();
                                            $user_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_users')->row();
                                            ?>
                                    <li class="event-list">
                                        <div class="event-timeline-dot">
                                            <i class="bx bx-right-arrow-circle font-size-18"></i>
                                        </div>
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <h5 class="font-size-14"><?php echo date('d', strtotime($v_activities->activity_date)) ?> <?php echo date('M', strtotime($v_activities->activity_date)) ?> <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div>
                                                    <?php if (!empty($profile_info)) { ?>
                                                    <h5 class="notice-calendar-heading-title">
                                                            <a href="<?= base_url() ?>admin/user/user_details/<?= $profile_info->user_id ?>"
                                                                   class="text-info"><?= $profile_info->fullname ?></a>
                                                    </h5>
                                                    <?php } ?>
                                                    
                                                    <div class="notice-calendar-date">
                                                        <p><?= sprintf(lang($v_activities->activity)) ?>
                                                            <strong><?= $v_activities->value1 ?></strong>
                                                            <?php if (!empty($v_activities->value2)){ ?>
                                                            <p class="m0 p0"><strong><?= $v_activities->value2 ?></strong></p>
                                                            <?php } ?>
                                                        </p>
                                                    </div>
                                                    <span style="font-size: 10px;" class="">
                                                        <strong>
                                                            <?= time_ago($v_activities->activity_date); ?>
                                                        </strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>               
                                    <?php } } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>