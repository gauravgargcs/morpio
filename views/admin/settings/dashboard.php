<style type="text/css">
    .dragger {
        background: url(../../skote_assets/images/dragger.png) 0px 15px no-repeat;
        cursor: pointer;
    }
</style>
<?php
$companies_id = $this->session->userdata('companies_id');
if (empty($companies_id)) {
    $companies_id = null;
}
?>
<?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4"><?= lang('admin') . ' ' . lang('dashboard') . ' ' . lang('settings') ?></h4>
        <div class="table-responsive">
            <table class="table table-striped dt-responsive w-100">
                <?php /*
                $all_report = $this->db->where(array('report' => 1, 'companies_id' => $companies_id))->order_by('order_no', 'ASC')->get('tbl_dashboard')->result();
                ?>
                <tbody id="report_menu">
                <?php
                foreach ($all_report as $v_report) {
                    ?>
                    <tr class="report_menu" id="<?= $v_report->id ?>">
                        <td class="dragger pl-lg">
                            <?= lang($v_report->name) ?>
                        </td>
                        <td class="pl-lg">
                            <input type="text" data-id="<?= $v_report->id ?>" name="col" value="<?= $v_report->col ?>"
                                   class="form-control change_status">
                        </td>
                        <td>
                            <label
                                class="col-lg-6 col-form-label"><?= lang('active') ?></label>
                            <div class="col-lg-5 checkbox change_status">
                                <input data-id="<?= $v_report->id ?>" data-bs-toggle="toggle"
                                       name="status"
                                       value="1" <?php if ($v_report->status == 1) {
                                    echo 'checked';
                                } ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>"
                                       data-onstyle="success btn-xs"
                                       data-offstyle="danger btn-xs" type="checkbox">
                            </div>
                        </td>

                    </tr>
                <?php } */
                ?>
                </tbody>
                <tbody id="menu">
                <?php $all_dashboard_data = $this->db->where(array( 'companies_id' => $companies_id))->order_by('order_no', 'ASC')->get('tbl_dashboard')->result();;
                foreach ($all_dashboard_data as $v_dashboard) {
                    ?>
                    <tr class="menu" id="<?= $v_dashboard->id ?>">
                        <td class="dragger pl-lg">
                            <?= lang($v_dashboard->name) ?>
                        </td>
                        <td class="pl-lg">
                            <input data-id="<?= $v_dashboard->id ?>" type="hidden" name="col" value="<?= $v_dashboard->col ?>" class="form-control column">
                        </td>
                        <td class="pl-lg">
                            <div class="row">
                                <label class="col-lg-4 col-form-label"><?= lang('active') ?></label>
                                <div class="col-lg-5 change_status form-check form-switch form-switch-lg">                           
                                    <input type="checkbox" id="SwitchCheckSizemd" <?php if ($v_dashboard->status == 1) {
                                        echo 'checked'; } ?> name="status"  value="1" data-id="<?= $v_dashboard->id ?>" class="form-check-input"/>
                                    <label for="SwitchCheckSizemd" class="form-check-label" data-on-label="<?= lang('yes') ?>" data-off-label="<?= lang('no') ?>"></label>

                                </div>
                            </div>
                        </td>

                        <td class="pl-lg">
                            <div class="row">
                                <label class="col-lg-4 col-form-label" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('staff_also_can_see') ?>"><?= lang('staff') ?>
                                <i class="fa fa-question-circle"></i></label>
                                <div class="col-lg-5 for_staff form-check form-switch form-switch-lg">
                                    <input type="checkbox" id="SwitchCheckSizemd1" <?php if ($v_dashboard->for_staff == 1) { echo 'checked'; } ?> name="for_staff"  value="1" data-id="<?= $v_dashboard->id ?>" class="form-check-input"/>
                                    <label for="SwitchCheckSizemd1" class="form-check-label" data-on-label="<?= lang('yes') ?>" data-off-label="<?= lang('no') ?>"></label>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4"><?= lang('client') . ' ' . lang('dashboard') . ' ' . lang('settings') ?></h4>
        <div class="table-responsive">
            <table class="table table-striped dt-responsive w-100">
                <?php
                $all_client_report = $this->db->where(array('report' => 2, 'companies_id' => $companies_id))->order_by('order_no', 'ASC')->get('tbl_dashboard')->result();
                ?>
                <tbody id="client_report_menu">
                <?php
                foreach ($all_client_report as $v_client_report) {
                    ?>
                    <tr class="client_report_menu" id="<?= $v_client_report->id ?>">
                        <td class="dragger pl-lg">
                            <?= lang($v_client_report->name) ?>
                        </td>
                        <td class="pl-lg hide">
                            <input data-id="<?= $v_client_report->id ?>" type="hidden" name="col"
                                   value="<?= $v_client_report->col ?>" class="form-control column">
                        </td>
                        <td class="pl-lg" colspan="2">
                            <div class="row">
                                <label class="col-lg-4 col-form-label"><?= lang('active') ?></label>
                                <div class="col-lg-6 change_status form-check form-switch form-switch-lg">                           
                                    <input type="checkbox" id="SwitchCheckSizemd3" <?php if ($v_client_report->status == 1) {
                                        echo 'checked'; } ?> name="status"  value="1" data-id="<?= $v_client_report->id ?>" class="form-check-input"/>
                                    <label for="SwitchCheckSizemd3" class="form-check-label" data-on-label="<?= lang('yes') ?>" data-off-label="<?= lang('no') ?>"></label>

                                </div>
                            </div>
                        </td>
                    </tr>
                <?php }
                ?>
                </tbody>
                <tbody id="client_menu">
                <?php $all_client_dashboard_data = $this->db->where(array('report' => 3, 'companies_id' => $companies_id))->order_by('order_no', 'ASC')->get('tbl_dashboard')->result();;
                foreach ($all_client_dashboard_data as $v_client_dashboard) {
                    ?>
                    <tr class="client_menu" id="<?= $v_client_dashboard->id ?>">
                        <td class="dragger pl-lg">
                            <?= lang($v_client_dashboard->name) ?>
                        </td>
                        <td class="pl-lg hide">
                            <input data-id="<?= $v_client_dashboard->id ?>" type="text" name="col"
                                   value="<?= $v_client_dashboard->col ?>" class="form-control hide column">
                        </td>
                        <td class="pl-lg" colspan="2">
                            <div class="row">
                                <label class="col-lg-4 col-form-label"><?= lang('active') ?></label>
                                <div class="col-lg-6 change_status form-check form-switch form-switch-lg">                           
                                    <input type="checkbox" id="SwitchCheckSizemd4" <?php if ($v_client_dashboard->status == 1) {
                                        echo 'checked'; } ?> name="status"  value="1" data-id="<?= $v_client_dashboard->id ?>" class="form-check-input"/>
                                    <label for="SwitchCheckSizemd4" class="form-check-label" data-on-label="<?= lang('yes') ?>" data-off-label="<?= lang('no') ?>"></label>

                                </div>
                            </div>
                        </td>
                    </tr>
                <?php }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.change_status input[type="checkbox"]').change(function () {
            var id = $(this).data().id;
            var status = $(this).is(":checked");
            if (status == true) {
                status = 1;
            } else {
                status = 2;
            }
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= base_url()?>admin/settings/save_dashboard/' + id + '/' + status, // the url where we want to POST
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
                success: function (res) {
                    if (res) {
//                        toastr[res.status](res.message);
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })

        });
        $('.for_staff input[type="checkbox"]').change(function () {
            var id = $(this).data().id;
            var status = $(this).is(":checked");
            if (status == true) {
                status = 's_' + 1;
            } else {
                status = 's_' + 0;
            }

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= base_url()?>admin/settings/save_dashboard/' + id + '/' + status, // the url where we want to POST
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
                success: function (res) {
                    if (res) {
//                        toastr[res.status](res.message);
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })

        });
        $('input[name="col"]').change(function () {
            var id = $(this).data().id;
            var col = $(this).val();
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= base_url()?>admin/settings/save_dashboard/' + id + '/' + col, // the url where we want to POST
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
                success: function (res) {
                    if (res) {
//                        toastr[res.status](res.message);
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })

        });
    })
    $(function () {
        $('tbody[id^="report_menu"]').sortable({
            connectWith: ".report_menu",
            placeholder: 'ui-state-highlight',
            forcePlaceholderSize: true,
            stop: function (event, ui) {
                var id = JSON.stringify(
                    $('tbody[id^="report_menu"]').sortable(
                        'toArray',
                        {
                            attribute: 'id'
                        }
                    )
                );
                var formData = {
                    'report_menu': id
                };
                $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: '<?= base_url()?>admin/settings/save_dashboard/', // the url where we want to POST
                    data: formData, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    encode: true,
                    success: function (res) {
                        if (res) {
//                            toastr[res.status](res.message);
                        } else {
                            alert('There was a problem with AJAX');
                        }
                    }
                })

            }
        });
        $(".report_menu").disableSelection();

        $('tbody[id^="menu"]').sortable({
            connectWith: ".menu",
            placeholder: 'ui-state-highlight',
            forcePlaceholderSize: true,
            stop: function (event, ui) {
                var mid = JSON.stringify(
                    $('tbody[id^="menu"]').sortable(
                        'toArray',
                        {
                            attribute: 'id'
                        }
                    )
                );

                var formData = {
                    'menu': mid
                };
                $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: '<?= base_url()?>admin/settings/save_dashboard/', // the url where we want to POST
                    data: formData, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    encode: true,
                    success: function (res) {
                        if (res) {
//                            toastr[res.status](res.message);
                        } else {
                            alert('There was a problem with AJAX');
                        }
                    }
                })
            }
        });
        $(".menu").disableSelection();
    });
    $(function () {
        $('tbody[id^="client_report_menu"]').sortable({
            connectWith: ".client_report_menu",
            placeholder: 'ui-state-highlight',
            forcePlaceholderSize: true,
            stop: function (event, ui) {
                var id = JSON.stringify(
                    $('tbody[id^="client_report_menu"]').sortable(
                        'toArray',
                        {
                            attribute: 'id'
                        }
                    )
                );
                var formData = {
                    'report_menu': id
                };
                $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: '<?= base_url()?>admin/settings/save_dashboard/', // the url where we want to POST
                    data: formData, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    encode: true,
                    success: function (res) {
                        if (res) {
//                            toastr[res.status](res.message);
                        } else {
                            alert('There was a problem with AJAX');
                        }
                    }
                })

            }
        });
        $(".client_report_menu").disableSelection();

        $('tbody[id^="client_menu"]').sortable({
            connectWith: ".client_menu",
            placeholder: 'ui-state-highlight',
            forcePlaceholderSize: true,
            stop: function (event, ui) {
                var mid = JSON.stringify(
                    $('tbody[id^="client_menu"]').sortable(
                        'toArray',
                        {
                            attribute: 'id'
                        }
                    )
                );

                var formData = {
                    'menu': mid
                };
                $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: '<?= base_url()?>admin/settings/save_dashboard/', // the url where we want to POST
                    data: formData, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    encode: true,
                    success: function (res) {
                        if (res) {
//                            toastr[res.status](res.message);
                        } else {
                            alert('There was a problem with AJAX');
                        }
                    }
                })
            }
        });
        $(".menu").disableSelection();
    });
</script>