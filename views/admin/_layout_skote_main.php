<?php $this->load->view('admin/skote_layouts/htmlheader'); 
$opened = $this->session->userdata('opened');
$this->session->unset_userdata('opened');
$time = date('h:i:s');
$r = display_time($time);
$time1 = explode(' ', $r);
$timezone = config_item('timezone');
if (empty($timezone)) {
    $timezone = 'Australia/Sydney';
}
$docroom_access_token=$this->session->userdata('docroom_access_token');
?>
<style type="text/css">
    .required{
        color: red;
    }
    .easypiechart {
        position: relative;
        text-align: center;
        margin: 10px;
    }

    .easypiechart span {
        line-height: 50px;
        padding-left: 14px;
        font-weight: bold;
        color: #888;
    }

    .easypiechart canvas {
        position: absolute;
        top: 0;
        left: 0;
    }

    .easypie-chart span {
        display: block;
        position: absolute;
        top: 30%;
        width: 100%;

    }

    .easypie-chart canvas {
        max-width: 100%;
    }

    .easypie-chart {
        display: inline-block;
        position: relative;
        padding: 0 6px;
    }

    .easypie-chart .easypie-text {
        position: absolute;
        z-index: 1;
        line-height: 1;
        width: 100%;
        top: 60%;
        margin-left: -4px;
    }
    .inline{
        display: inline-block !important;
    }
    .mr{
        margin-right: 10px;
    }
    .datepicker{
        border:  1px solid #ced4da;
    }
</style>
<script type="text/javascript">

    function startTime() {
        var c_time = new Date();
        var time = new Date(c_time.toLocaleString('en-US', {timeZone: '<?= $timezone?>'}));

        var date = time.getDate();
        var month = time.getMonth() + 1;
        var years = time.getFullYear();
        var hr = time.getHours();
        var hour = time.getHours();
        var min = time.getMinutes();
        var minn = time.getMinutes();
        var sec = time.getSeconds();
        var secc = time.getSeconds();
        if (date <= 9) {
            var dates = "0" + date;
        } else {
            dates = date;
        }
        if (month <= 9) {
            var months = "0" + month;
        } else {
            months = month;
        }
        <?php if(empty($time1[1])){?>
        var ampm = ' ';
        <?php }else{?>
        var ampm = " PM "
        if (hr < 12) {
            ampm = " AM "
        }
        if (hr > 12) {
            hr -= 12
        }
        <?php }?>

        if (hr < 10) {
            hr = " " + hr
        }
        if (min < 10) {
            min = "0" + min
        }
        if (sec < 10) {
            sec = "0" + sec
        }
       // document.getElementById('txt').innerHTML = hr + ":" + min + ":" + sec + ampm;
        var t = setTimeout(function () {
            startTime()
        }, 500);
    }
</script>
<body onload="startTime();" data-sidebar="colored" class="<?php if (!empty($opened)){ echo 'sidebar-enable vertical-collpsed'; } ?>">

<!-- Begin page -->
<div id="layout-wrapper">
    <?php $this->load->view('admin/skote_layouts/vertical-menu'); ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <?php
            $active_pre_loader = config_item('active_pre_loader');
            if (!empty($active_pre_loader) && $active_pre_loader == 1) {
                ?>
                <div id="loader-wrapper">
                    <div id="loader"></div>
                </div>
            <?php } ?>
            <div class="page-content">
                <div class="container-fluid">
                    <!-- end page title -->
                    <?php if(!$docroom_access_token){ ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-block-helper me-2"></i>
                        <?=lang('docroom_connect_msg');?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php } ?>
                    <?php echo $subview ?>
                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            
            <?php $this->load->view('admin/skote_layouts/footer'); ?>

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

<!-- Right Sidebar -->
<?php $this->load->view('admin/skote_layouts/right-sidebar'); ?>
<!-- /Right-bar -->

<!-- JAVASCRIPT -->
<?php $this->load->view('admin/skote_layouts/vendor-scripts'); ?>

<!-- Required datatable js -->
<script src="<?=base_url();?>skote_assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>skote_assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="<?=base_url();?>skote_assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>skote_assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="<?=base_url();?>skote_assets/libs/jszip/jszip.min.js"></script>
<script src="<?=base_url();?>skote_assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="<?=base_url();?>skote_assets/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="<?=base_url();?>skote_assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?=base_url();?>skote_assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?=base_url();?>skote_assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

<!-- Responsive examples -->
<script src="<?=base_url();?>skote_assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?=base_url();?>skote_assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>


<!-- Datatable init js -->
<script src="<?=base_url();?>skote_assets/js/pages/datatables.init.js"></script>


<!-- Sweet Alerts js -->
<script src="<?=base_url();?>skote_assets/libs/sweetalert2/sweetalert2.min.js"></script>

<script type="text/javascript">
    
    function confirmDeleteAlert(e){
        e.preventDefault();
        var urlToRedirect = e.currentTarget.getAttribute('href'); 
        Swal.fire({
            title: are_you_sure,
            text: ldelete_confirm,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#34c38f",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: Yes_delete_it,
        }).then(function (result) {
            if (result.value) {
                window.location.href=urlToRedirect;
                return true;
            }else{
                return false;
            } 
        });  
    }

</script>


<script src="<?php echo base_url(); ?>skote_assets/libs/select2/js/select2.min.js"></script>
<script src="<?php echo base_url(); ?>skote_assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(); ?>skote_assets/libs/spectrum-colorpicker2/spectrum.min.js"></script>
<script src="<?php echo base_url(); ?>skote_assets/libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url(); ?>skote_assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
<script src="<?php echo base_url(); ?>skote_assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
<script src="<?php echo base_url(); ?>skote_assets/libs/@chenfengyuan/datepicker/datepicker.min.js"></script>

<script src="<?php echo base_url() ?>skote_assets/plugins/parsleyjs/parsley.min.js"></script>

<!-- form advanced init -->
<script src="<?php echo base_url(); ?>skote_assets/js/pages/form-advanced.init.js"></script>

<!--tinymce js-->
<script src="<?=base_url()?>skote_assets/libs/tinymce/tinymce.min.js"></script>

<!-- init js -->
<script src="<?=base_url()?>skote_assets/js/pages/form-editor.init.js"></script>

<script src="<?=base_url();?>skote_assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>


<!-- jquery step -->
<script src="<?=base_url();?>skote_assets/libs/jquery-steps/build/jquery.steps.min.js"></script>


<link rel="stylesheet"  href="<?= base_url() ?>skote_assets/plugins/datetimepicker/jquery.datetimepicker.min.css" type="text/css">
<?php include_once 'skote_assets/plugins/datetimepicker/jquery.datetimepicker.full.php'; ?>


<!-- toastr plugin -->
<script src="<?=base_url();?>skote_assets/libs/toastr/build/toastr.min.js"></script>

<!-- toastr init -->
<script src="<?=base_url();?>skote_assets/js/pages/toastr.init.js"></script>
<!-- Calendar -->
<script src="<?=base_url();?>skote_assets/libs/moment/min/moment.min.js"></script>
<script src="<?=base_url();?>skote_assets/calentim-date-time-range-picker/build/js/calentim.min.js"></script>

<script src="<?=base_url();?>skote_assets/libs/chance/chance.min.js"></script>
<!-- ION Slider -->
<link href="<?=base_url();?>skote_assets/libs/ion-rangeslider/css/ion.rangeSlider.min.css" rel="stylesheet" type="text/css" />

<!-- Ion Range Slider-->
<script src="<?=base_url();?>skote_assets/libs/ion-rangeslider/js/ion.rangeSlider.min.js"></script>

<!-- App js -->
<script src="<?php echo base_url('skote_assets/js/app.js'); ?>"></script>

<?php 
$direction = $this->session->userdata('direction');
if (!empty($direction) && $direction == 'rtl') {
    $RTL = 'on';
} else {
    $RTL = config_item('RTL');
}
?>

<script type="text/javascript">
    
    $(document).ready(function () {

        $('#loader-wrapper').delay(0).fadeOut(function () {
            $('#loader-wrapper').hide();
        });
        $(".existing_image").click(function () {
            $(this).parent().remove();
        });
        $("button[name='clocktime']").click(function () {
            var ubtn = $(this);
            ubtn.html('Please wait...');
            ubtn.addClass('disabled');
        });
        $("div.action").show();
        $("#permission_user").show();
        $('#permission_user_1').show();
        $("div.action_1").show();
        if($('input[name="permission"]:checked').val()=="custom_permission"){
            $('#permission_user_1').show();
            $("div.action_1").show();
        }
        if($('input[name="permission"]:checked').val()=="everyone"){
            $("#permission_user_1").removeClass('show');
            $('#permission_user_1').hide();
        }

        $("input[name='permission']").click(function () {
            $("#permission_user_1").removeClass('show');
            if ($(this).attr("value") == "custom_permission") {
                $("#permission_user_1").show();
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
                        $('#permission_user_1').hide(); 
                        return true;
                    }else{
                        return false;
                    } 
                }); 

            }
        });
        $("input[name='assigned_to[]']").click(function () {
            var user_id = $(this).val();
            $("#action_1" + user_id).removeClass('show');
            if (this.checked) {
                $("#action_1" + user_id).show();
            } else {
                $("#action_1" + user_id).hide();
            }

        });

         /*
         * Multiple drop down select
         */
        $(".select_box").select2({
            <?php

            if (!empty($RTL)) {?>
            dir: "rtl",
            <?php }
            ?>
        });
        $(".select_2_to").select2({
            tags: true,
            <?php
            if (!empty($RTL)) {?>
            dir: "rtl",
            <?php }
            ?>
            allowClear: true,
            placeholder: 'To : Select or Write',
            tokenSeparators: [',', ' ']
        });
        $(".select_multi").select2({
            tags: true,
            <?php
            if (!empty($RTL)) {?>
            dir: "rtl",
            <?php }
            ?>
            allowClear: true,
            placeholder: 'Select Multiple',
            tokenSeparators: [',', ' ']
        });

        Parsley.on('form:submit', function() {
            $('#loader-wrapper').show();
        });

    });

    $(function () {
        $('#search_menu').keyup(function () {
            var that = this, $allListElements = $('ul.myUL > li');
            console.log($allListElements);
            var $matchingListElements = $allListElements.filter(function (em, li) {
                var listItemText = $(li).text().toUpperCase(), searchText = that.value.toUpperCase();
                return ~listItemText.indexOf(searchText);
            });
            $allListElements.hide();
            $matchingListElements.show();
        });
    });

    $(function () {
        $('#search_assigned_user').keyup(function () {
            var searchTxt = $(this).val().toUpperCase();
         
            $("input[name='assigned_to[]']").each(function(){
                var label=$(this).attr("data-name").toUpperCase();
                if(label.match(searchTxt)){
                    $(this).closest("div").show();
                    $(this).closest("div").next('.action_1').show();
                }else {
                    $(this).closest("div").hide();
                    $(this).closest("div").next('.action_1').hide();
                }
                        
            })
        });
    });


</script>
 

<?php $this->load->view('admin/_layout_skote_modal'); ?>
<?php $this->load->view('admin/_layout_skote_modal_lg'); ?>
<?php $this->load->view('admin/_layout_skote_modal_large'); ?>
<?php $this->load->view('admin/_layout_skote_modal_extra_lg'); ?>

</body>

</html>
