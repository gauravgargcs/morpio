<link rel="stylesheet" href="<?php echo base_url(); ?>plugins/colorpicker/css/bootstrap-colorpicker.min.css">
<script src="<?php echo base_url(); ?>plugins/colorpicker/js/bootstrap-colorpicker.min.js"></script>

<!-- Modal -->
<style type="text/css">
    .bootstrap-timepicker-widget.dropdown-menu.open {
        display: inline-block;
        z-index: 99999 !important;
    }
</style>
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2.min.css">
<link rel="stylesheet"
      href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2-bootstrap.min.css">
<script src="<?= base_url() ?>assets/plugins/select2/dist/js/select2.min.js"></script>
<link rel="stylesheet"
      href="<?= base_url() ?>assets/plugins/datetimepicker/jquery.datetimepicker.min.css">
<?php include_once 'assets/plugins/datetimepicker/jquery.datetimepicker.full.php'; ?>
<!-- =============== Datepicker ===============-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/datepicker.min.css">
<?php include_once 'assets/js/bootstrap-datepicker.php'; ?>
<!-- =============== timepicker ===============-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/timepicker.min.css">
<script src="<?= base_url() ?>assets/js/timepicker.min.js"></script>

<script src="<?php echo base_url() ?>assets/plugins/parsleyjs/parsley.min.js"></script>
<?php $direction = $this->session->userdata('direction');
if (!empty($direction) && $direction == 'rtl') {
    $RTL = 'on';
} else {
    $RTL = config_item('RTL');
}
?>
<script type="text/javascript">
    $('#myModal').on('loaded.bs.modal', function () {
        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
            $('.selectpicker').selectpicker({});
            $('.select_box').select2({
                theme: 'bootstrap',
                <?php
                if (!empty($RTL)) {?>
                dir: "rtl",
                <?php }
                ?>
            });
            $('.select_multi').select2({
                theme: 'bootstrap',
                <?php
                if (!empty($RTL)) {?>
                dir: "rtl",
                <?php }
                ?>
            });
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
                        lazyInit: true,
                        scrollInput: false,
                        format: 'Y-m-d H:i',
                    };

                    opt_time.formatTime = 'H:i';
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
                    $(this).datetimepicker(opt_time);
                });
            }
            $('.monthyear').datepicker({
                singleDate: true,
                calendarCount: 1,
                showHeader: false,
                showFooter: false,
                autoCloseOnSelect: true,
                format: 'YYYY-MM',
            });
            $('.timepicker').timepicker();

            $('.timepicker2').timepicker({
                minuteStep: 1,
                showSeconds: false,
                showMeridian: false,
                defaultTime: false
            });
            $('.textarea').summernote({
                codemirror: {// codemirror options
                    theme: 'monokai'
                }
            });
            $('.note-toolbar .note-fontsize,.note-toolbar .note-help,.note-toolbar .note-fontname,.note-toolbar .note-height,.note-toolbar .note-table').remove();

            $('input.select_one').on('change', function () {
                $('input.select_one').not(this).prop('checked', false);
            });
            $('.colorpicker-component').colorpicker();
        });
        $(document).ready(function () {
            $('#permission_user').hide();
            $("div.action").hide();
            $("input[name$='permission']").click(function () {
                $("#permission_user").removeClass('show');
                if ($(this).attr("value") == "custom_permission") {
                    $("#permission_user").show();
                } else {
                    $("#permission_user").hide();
                }
            });

            $("input[name$='assigned_to[]']").click(function () {
                var user_id = $(this).val();
                $("#action_" + user_id).removeClass('show');
                if (this.checked) {
                    $("#action_" + user_id).show();
                } else {
                    $("#action_" + user_id).hide();
                }

            });
        });
    });

    $(document).on('hide.bs.modal', '#myModal', function () {
        $('#myModal').removeData('bs.modal');
    });


</script>
