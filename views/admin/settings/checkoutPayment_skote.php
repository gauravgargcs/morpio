
<!-- start page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18"><?= lang('payment') . '  ' . lang('summery') . ' - ' ?></h4>

         
    </div>
</div>
</div>


<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-body">
                  <h4 class="card-title"> <span
                id="plan_name"><?= $package_info->name ?></span></h4>
<?php
echo message_box('success');
echo message_box('error');

$subscription_id = null;
?>
<?php echo form_open(base_url('active_subscription'), array('class' => 'form-horizontal ', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
 <div class="row">
        <?php
        $super_admin = super_admin();
       $sub_domain = is_subdomain($_SERVER['HTTP_HOST']);
        if (!empty($super_admin) && !$sub_domain) { ?>
           
            <div class="col-sm-8 ">
                <div class="row">
                    <label for="discount_type"
                           class="col-form-label col-sm-3"><?= lang('select') . ' ' . lang('subscriber') ?><span
                            class="required">*</span></label>
                    <div class="col-sm-9">
                        <select name="subscriptions_id" id="subscriptions_id" class="select_box m0" data-width="100%"
                                data-none-selected-text="<?php echo lang('select') . ' ' . lang('subscriber'); ?>"
                                data-live-search="true" required="true">
                            <option value=""><?= lang('select') . ' ' . lang('subscriber') ?></option>
                            <?php
                            $all_subscriber = get_order_by('tbl_subscriptions', null, 'subscriptions_id');
                            if (!empty($all_subscriber)) {
                                foreach ($all_subscriber as $v_subscriber) { ?>
                                    <option value="<?php echo $v_subscriber->subscriptions_id; ?>"
                                            data-subtext="<?php echo lang('domain') . ':' . $v_subscriber->domain . ' ' . lang('status') . ':' . lang($v_subscriber->status) . ' ' . lang('trial_period') . ':' . lang($v_subscriber->is_trial) . '...'; ?>"><?php echo $v_subscriber->name . '(' . $v_subscriber->email . ')'; ?></option>
                                <?php } ?>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
              
            </div>
        <?php } else {
            ?>
            <input type="hidden" name="subscriptions_id" id="subscriptions_id"
                   value="<?= !empty($subs_info) ? $subs_info->subscriptions_id : '' ?>">
        <?php } ?>

        <div class="col-sm-8 ">
                <div class="row my-3">
          
                <label for="discount_type"
                       class="col-form-label col-sm-3"><?= lang('select') . ' ' . lang('package') ?><span
                        class="required">*</span></label>
                <div class="col-sm-9">
                    <select name="pricing_id" id="pricing_id" class="select_box m0" data-width="100%"
                            data-none-selected-text="<?php echo lang('select') . ' ' . lang('package'); ?>"
                            data-live-search="true">
                        <option value=""></option>
                        <?php
                        if (!empty($c_pricing_info)) {
                            foreach ($c_pricing_info as $c_pricing) {
                                if ($interval_type == 'annually') {
                                    $amount = $c_pricing->yearly . ' /' . lang('yr');
                                } else {
                                    $amount = $c_pricing->monthly . ' /' . lang('mo');
                                }
                                ?>
                                <option <?php
                                if (isset($package_info)) {
                                    if ($package_info->id == $c_pricing->frontend_pricing_id) {
                                        echo 'selected';
                                    }
                                } ?> value="<?php echo $c_pricing->frontend_pricing_id; ?>"
                                     data-subtext="<?php echo $c_pricing->currency . $amount . ' ' . strip_tags(mb_substr(!empty($c_pricing->description) ? $c_pricing->description : '', 0, 200)) . '...'; ?>"><?php echo $c_pricing->name; ?></option>
                            <?php } ?>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
           
        </div>
    </div>

        <script type="text/javascript">
            $(document).ready(function () {
                $('select[name="pricing_id"]').change(function () {
                    var pricing_id = $(this).val();
                    var pricewise_currency = $('#pricewise_currency').val();
                    var interval_type = $('#interval_type').val();
                    $.ajax({
                        type: 'GET', // define the type of HTTP verb we want to use (POST for our form)
                        url: '<?= base_url()?>admin/global_controller/pricing_change_data/' + pricing_id + '/' + pricewise_currency + '/' + interval_type, // the url where we want to POST
                        dataType: 'json', // what type of data do we expect back from the server
                        encode: true,
                        success: function (res) {
                            if (res) {
                                $('#plan_name').html(res.package_name);
                                $('#checkout_payment').html(res.set_merge_info);
                            } else {
                                alert('There was a problem with AJAX');
                            }
                        }
                    })
                });
            })
            ;
        </script>

        <div id="checkout_payment" class="row">
            <?php
            $this->load->view('admin/settings/checkout_payment_skote', array('package_info' => $package_info));
            ?>
        </div>
    </div>
</div>
</div>
</div>
