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
<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-body">

        <h4><?= lang('subscriptions') . ' ' . lang('list') ?></h4>
   
    <!-- ************** general *************-->
    <div class="table-responsive">
        <table class="table table-striped " id="contentTable">
            <thead>
            <tr>
                <th><?= lang('plan') ?></th>
                <th><?= lang('domain') ?></th>
                <th><?= lang('trial_period') ?></th>
                <th><?= lang('currency') ?></th>
                <th><?= lang('frequency') ?></th>
                <th><?= lang('status') ?></th>
                <th><?= lang('date') ?></th>
                <th><?= lang('Created By') ?></th>
                <th><?= lang('action') ?></th>
            </tr>
            </thead>
            <?php /* ?><tbody id="subscriptions_info">
            <?php
            $all_subscription = get_all_subscriptions();
            // echo "<pre>";
            // print_r($all_subscription);die();
            if (!empty($all_subscription)) {
                foreach ($all_subscription as $subscription) {
                    if (empty($subscription->currency)) {
                        $currency_code = config_item('default_currency');
                    } else {
                        $currency_code = $subscription->currency;
                    }
                    $currency = get_cache_data(array('code' => $currency_code), 'tbl_currencies');
                    
                    if ($subscription->status == 'pending') {
                        $label = 'primary';
                    } else if ($subscription->status == 'running') {
                        $label = 'success';
                    } else if ($subscription->status == 'expired') {
                        $label = 'warning';
                    } else {
                        $label = 'danger';
                    }
                    $created_by  = "Client";
                    if($subscription->created_by){
                        // $user= get_result('tbl_users',['user_id'=>$subscription->created_by],'row');
                        $created_by= '<a href="javascript://" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$subscription->creator_email.'" >'.$subscription->creator_username.'</a>';
                    }
                    ?>
                    <tr class="subscription" id="table_subscription_<?= $subscription->subscriptions_id ?>">
                        <td>
                            <a data-bs-toggle='modal' data-bs-target='#myModal'
                               href="<?= base_url('admin/global_controller/package_details/' . $subscription->pricing_id) ?>"
                               class="text-center"><?= $subscription->plan_name?></a>
                        </td>
                        <td><?= $subscription->domain ?></td>
                        <td><?= $subscription->trial_period . ' ' . lang('days') ?></td>
                        <td><?= $currency->name . '(' . $currency->symbol . ')' ?></td>
                        <td><?= lang($subscription->frequency) ?></td>
                        <td><span class="label label-<?= $label ?>"> <?= lang($subscription->status) ?></span></td>
                        <td><?= strftime(config_item('date_format'), strtotime($subscription->created_date)) ?></td>
                        <td><?= $created_by ?></td>
                        <td>
                            <?php if ($subscription->status == 'pending') { ?>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" class=" btn btn-outline-success btn-sm "
                                   title="<?= lang('send_activation_token') ?>"
                                   href="<?= base_url('admin/frontend/send_activation_token/' . $subscription->subscriptions_id) ?>" ><span class="fa fa-envelope-o"></span></a>
                            <?php } ?>
                            <?php echo ajax_anchor(base_url("admin/frontend/delete_subscriptions/$subscription->subscriptions_id"), "<i class=' fa fa-trash-o'></i>", array("class" => "btn btn-outline-danger btn-sm", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_subscription_" . $subscription->subscriptions_id)); ?>
                            <?= btn_view(base_url('admin/frontend/subscriptions_details/' . $subscription->subscriptions_id)) ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody><?php */ ?>
        </table>
    </div>
</div>
</div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var table = $('#table_date').DataTable( {
            // "ordering": true,
            // "order": [[ 6, "date-uk-desc" ]],
     columnDefs: [
       { type: 'date-uk', targets: 6 }
     ],
            
    } );
        setTimeout( function (){ table.order( [ 6, 'desc' ] ).draw() } , 600);

        jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "date-uk-pre": function ( a ) {
        if (a == null || a == "") {
            return 0;
        }
        var ukDatea = a.split('-');
        return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
    },
 
    "date-uk-asc": function ( a, b ) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },
 
    "date-uk-desc": function ( a, b ) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
} );
    });
    
</script>
<!-- <script src="<?php echo base_url() ?>skote_assets/plugins/dataTables/js/date-eu.js"></script> -->

<!-- Script -->
 <script type="text/javascript">
     $(document).ready(function(){
        $('#contentTable').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
             'url':'<?=base_url()?>admin/datatable/subscriptions'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_subscription_"+iDisplayIndex);
            $(nRow).addClass("subscription");
            $('td:eq(9)', nRow).css("display","none");
            return nRow;
          },
          'columns': [
             { data: 'plan_name' },
             { data: 'domain' },
             { data: 'trial_period' },
             { data: 'currency' },
             { data: 'frequency' },
             { data: 'status' },
             { data: 'date' },
             { data: 'created_by' },
             { data: 'action' },
          ]
        });
     });
 </script>