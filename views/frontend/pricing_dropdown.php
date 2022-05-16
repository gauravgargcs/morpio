<div class="col-sm-12">
    <div class="form-group">
        <select name="pricing_id" class="form-control select_box" onchange="get_package_details(this.value)"
                style="width: 100%">
            <?php
            if (!empty($c_pricing_info)) {
                foreach ($c_pricing_info as $key => $v_wise_price) {
                    if ($interval_type == 'annually') {
                        $amount = $v_wise_price->yearly . ' /' . lang('yr');
                    } else {
                        $amount = $v_wise_price->monthly . ' /' . lang('mo');
                    }
                    ?>
                    <option
                        value="<?= $v_wise_price->frontend_pricing_id ?>" <?= (!empty($packege_id) && $packege_id == $v_wise_price->frontend_pricing_id ? 'selected' : '') ?> ><?= $v_wise_price->name . ' ' . $v_wise_price->currency . $amount ?> </option>
                <?php }
            } ?>
        </select>
    </div>
</div>
<script type="text/javascript">
    function get_package_details(pricing_id) {
        $.ajax({
            url: "<?= base_url()?>admin/global_controller/get_package_details/" + pricing_id,
            type: "GET",
            dataType: 'json',
            success: function (result) {
                document.getElementById('package_name').innerHTML = result.package_name;
                document.getElementById('package_details').innerHTML = result.package_details;
            }
        });
    }
</script>
