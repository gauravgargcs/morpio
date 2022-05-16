

<link rel="stylesheet"  href="<?= base_url() ?>skote_assets/plugins/datetimepicker/jquery.datetimepicker.min.css" type="text/css">
<?php include_once 'skote_assets/plugins/datetimepicker/jquery.datetimepicker.full.php'; ?>

<!-- Modal -->
<div class="modal fade" id="myModal" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>
<?php $direction = $this->session->userdata('direction');
if (!empty($direction) && $direction == 'rtl') {
    $RTL = 'on';
} else {
    $RTL = config_item('RTL');
}
?>
<!--- dropzone ---->
<?php if (!empty($dropzone)) { ?>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>skote_assets/plugins/dropzone/dropzone.min.css">
    <script type="text/javascript" src="<?= base_url() ?>skote_assets/plugins/dropzone/dropzone.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>skote_assets/plugins/dropzone/dropzone.custom.min.js"></script>
    <script type="text/javascript">// Immediately after the js include
      Dropzone.autoDiscover = false;
    </script>
<?php } ?>
<script type="text/javascript">
    $('#myModal').on('shown.bs.modal', function (e) {
        e.preventDefault();
        var button = e.relatedTarget; // Button that triggered the modal
        var recipient = button.href; // Extract info from button
        var modalBody = $(button.dataset.bsTarget+' .modal-content');
        modalBody.load(recipient, function() { 
            $("div.action").show();
            $("#permission_user").show();
            $('#permission_user_1').show();
            $("div.action_1").show();
            if($('input[name="permission"]:checked').val()=="custom_permission"){
                $('#permission_user').show();
            }
            if($('input[name="permission"]:checked').val()=="everyone"){
                $("#permission_user").removeClass('show');
                $('#permission_user').hide();
            }

            $(".datepicker").attr("autocomplete", "off");
            $(".monthyear").attr("autocomplete", "off");
            $(".years").attr("autocomplete", "off");
            init_datepicker();
            // Date picker init with selected timeformat from settings
            function init_datepicker() {
                var datetimepickers = $('.datepicker');
                if (datetimepickers.length == 0) {
                    return;
                }
                var opt_time;
                // Datepicker with time
                $.each(datetimepickers, function () {
                    opt_time = {
                        singleDate: true,
                        calendarCount: 1,
                        showHeader: false,
                        showFooter: false,
                        autoCloseOnSelect: true,
                        format: "DD-MM-YYYY HH:mm",
                    };

                    opt_time.formatTime = 'HH:mm';
                    // Check in case the input have date-end-date or date-min-date
                    var max_date = $(this).data('date-end-date');
                    var min_date = $(this).data('date-min-date');
                    if (max_date) {
                        opt_time.maxDate = max_date;
                    }
                    if (min_date) {
                        opt_time.minDate = min_date;
                    }
                    // Init the picker
                    $(this).calentim(opt_time);

                });
            }
            $('.monthyear').calentim({
                singleDate: true,
                calendarCount: 1,
                showHeader: false,
                showFooter: false,
                autoCloseOnSelect: true,
                format: 'YYYY-MM',
            });
            $('.timepicker1').timepicker({
              icons: {
                up: 'mdi mdi-chevron-up',
                down: 'mdi mdi-chevron-down'
              },
              appendWidgetTo: "#timepicker-input-group1"
            });

            $('.timepicker2').timepicker({
              icons: {
                up: 'mdi mdi-chevron-up',
                down: 'mdi mdi-chevron-down'
              },
              appendWidgetTo: "#timepicker-input-group2"
            });

            $('.timepicker').timepicker({
              icons: {
                up: 'mdi mdi-chevron-up',
                down: 'mdi mdi-chevron-down'
              },
              appendWidgetTo: ".timepicker-input-group"
            });

            $('.years').calentim({
                singleDate: true,
                calendarCount: 1,
                showHeader: false,
                showFooter: false,
                autoCloseOnSelect: true,
                format: 'YYYY',
            });

            jQuery(document).on("click", "input[name='permission']", function(){
                $("#permission_user").removeClass('show');
                if ($(this).attr("value") == "custom_permission") {
                    $("#permission_user").show();
                } else {
                    Swal.fire({
                        title: are_you_sure,
                        text: '<?=lang('ALL_PERMISSION_WARNING_MSG');?>',
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#34c38f",
                        cancelButtonColor: "#f46a6a",
                        confirmButtonText: '<?=lang('yes');?>',
                    }).then(function (result) {
                        if (result.value) {
                            $("#permission_user").removeClass('show');
                            $('#permission_user').hide();
                            $('#permission_user_1').hide(); 
                            return true;
                        }else{
                            return false;
                        } 
                    });  
                    
                }
            });

            jQuery(document).on("click", "input[name='assigned_to[]']", function(){
                var user_id = $(this).val();
                $("#action_" + user_id).removeClass('show');
                if (this.checked) {
                    $("#action_" + user_id).show();
                } else {
                    $("#action_" + user_id).hide();
                }

            });        

            $(".modal_select_box").select2({
                dropdownParent: $("#myModal")
            });

            $(".modal_select_2_to").select2({
                dropdownParent: $("#myModal")
            });

            $(".modal_select_multi").select2({
                dropdownParent: $("#myModal")
            });

            if($("#elm1").length > 0){
                tinymce.init({
                    selector: "textarea#elm1",
                   images_upload_url:  base_url+"upload_tinymce/image",
                   relative_urls : false,
                   remove_script_host : false,
                    paste_data_images: true,
                   // images_reuse_filename: true,
                    branding: false,
                    height:300,
                    plugins: [
                        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                        "save table contextmenu directionality emoticons template paste textcolor"
                    ],
                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
                    style_formats: [
                        {title: 'Bold text', inline: 'b'},
                        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                        {title: 'Example 1', inline: 'span', classes: 'example1'},
                        {title: 'Example 2', inline: 'span', classes: 'example2'},
                        {title: 'Table styles'},
                        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
                    ]
                });
            }
            $('#search_assigned_user').keyup(function () {
                var searchTxt = $(this).val().toUpperCase();
                $("input[name='assigned_to[]']").each(function(){
                    var label=$(this).attr("data-name").toUpperCase();
                    if(label.match(searchTxt)){
                        $(this).closest("div").show();
                        $(this).closest("div").next('.action').show();
                    }else {
                        $(this).closest("div").hide();
                        $(this).closest("div").next('.action').hide();
                    }
                            
                })
            });

            $('.search_assigned_user').keyup(function () {
                var searchTxt = $(this).val().toUpperCase();
                $("input[name='assigned_to[]']").each(function(){
                    var label=$(this).attr("data-name").toUpperCase();
                    if(label.match(searchTxt)){
                        $(this).closest("div").show();
                        $(this).closest("div").next('.action').show();
                    }else {
                        $(this).closest("div").hide();
                        $(this).closest("div").next('.action').hide();
                    }
                            
                })
            });


            fileSerial = 0;
            // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
            var previewNode = document.querySelector("#file-upload-row");
            if(previewNode) {
                previewNode.id = "";
                var previewTemplate = previewNode.parentNode.innerHTML;
                previewNode.parentNode.removeChild(previewNode);
                Dropzone.autoDiscover = false;
                var projectFilesDropzone = new Dropzone("#file-dropzone", {
                    url: "<?= base_url() ?>admin/global_controller/upload_file",
                    thumbnailWidth: 80,
                    thumbnailHeight: 80,
                    parallelUploads: 20,
                    previewTemplate: previewTemplate,
                    dictDefaultMessage: '<div class="mb-3"><i class="display-4 text-muted bx bxs-cloud-upload"></i></div><h4>Drop files here or click to upload.</h4>',
                    autoQueue: true,
                    previewsContainer: "#file-previews",
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
                        $("#file-task_btn").prop("disabled", true);
                        $("#file-save-leads_btn").prop("disabled", true);
                        $("#file-save-btn").prop("disabled", true);
                        $("#file-save-bug-btn").prop("disabled", true);
                        $("#file-save-opp-btn").prop("disabled", true);


                    },
                    queuecomplete: function () {
                        $("#file-task_btn").prop("disabled", false);
                        $("#file-save-leads_btn").prop("disabled", false);
                        $("#file-save-btn").prop("disabled", false);
                        $("#file-save-bug-btn").prop("disabled", false);
                        $("#file-save-opp-btn").prop("disabled", false);

                    },
                    fallback: function () {
                        $("body").addClass("dropzone-disabled");
                        $('.modal-dialog').find('[type="submit"]').removeAttr('disabled');

                        $("#file-dropzone").hide();

                        $("#file-modal-footer").prepend("<button id='add-more-file-button' type='button' class='btn  btn-default pull-left'><i class='fa fa-plus-circle'></i> " + "<?php echo lang("add_more"); ?>" + "</button>");

                        $("#file-modal-footer").on("click", "#add-more-file-button", function () {
                            var newFileRow = "<div class='file-row pb pt10 b-b mb10'>" +
                                "<div class='pb clearfix '><button type='button' class='btn btn-xs btn-danger pull-left mr remove-file'><i class='fa fa-times'></i></button> <input class='pull-left' type='file' name='manualFiles[]' /></div>" +
                                "<div class='mb5 pb5'><input class='form-control description-field cursor-auto'  name='comment[]'  type='text' placeholder='<?php echo lang("comment") ?>' /></div>" +
                                "</div>";
                            $("#file-previews").prepend(newFileRow);
                        });
                        $("#add-more-file-button").trigger("click");
                        $("#file-previews").on("click", ".remove-file", function () {
                            $(this).closest(".file-row").remove();
                        });
                    },
                    success: function (file,response) {
                        var res=JSON.parse(response);
                        if(res['error'] && res.length != 0){
                            toastr['error'](res['error']);
                            toastr['error']("<?=str_replace('"', "'",lang('docroom_connect_msg')) ;?>");
                            $(file.previewElement).closest(".file-upload-row").remove();
                        }else{
                            var docroom_file_id=res['uploaded_file']['data'][0]['file_id'];
                            var docroom_file_id_html="<input class='form-control'  name='docroom_file_id[]'  type='hidden' value='"+docroom_file_id+"' />";
                            $("#file-previews").prepend(docroom_file_id_html);
                            setTimeout(function () {
                                $(file.previewElement).find(".progress-striped").removeClass("progress-striped").addClass("progress-bar-success");
                            }, 1000);
                        }
                    }
                });

                $(".start-upload").click(function () {
                    projectFilesDropzone.enqueueFiles(projectFilesDropzone.getFilesWithStatus(Dropzone.ADDED));
                });
                $(".cancel-upload").click(function () {
                    projectFilesDropzone.removeAllFiles(true);
                });  
            }
            $(".existing_image").click(function () {
                $(this).parent().remove();
            });
            // $("#myModal").modal("show"); 
        });

    });

    $('#myModal').on('hidden.bs.modal', function () {
        $('#myModal').removeData('bs.modal');
    });

</script>

<!-- form advanced init -->
<!-- <script src="<?php echo base_url(); ?>skote_assets/js/pages/form-advanced.init.js"></script> -->
