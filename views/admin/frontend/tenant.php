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
<!-- end page title --><div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-body" style="min-height: 700px;">

        <h4><?= lang('Tenant') . ' ' . lang('list') ?></h4>
   
    <!-- ************** general *************-->

                            <div class="dropdown tbl-action">
                               
                                <button class="btn btn-success dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('Change Industry') ?><i class="mdi mdi-chevron-down"></i></button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                      <?php if($industries = get_industries()){
                            foreach ($industries as $key => $industry_name) {
                                ?>
                                  <a class="dropdown-item" onclick="multipe_change_industry('<?=$industry_name;?>')" href="#"><?= $industry_name; ?></a>
                                <?php
                            }
                          } ?>
                                    
                                </div>
                            </div>
                         
    <div class="table-responsive">
        <table class="table table-striped" id="contentTable">
            <thead>
            <tr>
                 <th data-check-all>
                                    
                                        <div class="form-check font-size-16 check-all">
                                            <input type="checkbox" id="parent_present" class="form-check-input">
                                            <label for="parent_present" class="toggle form-check-label"></label>
                                        </div>
                                    
                                    </th>
                <th><?= lang('plan') ?></th>
                <th><?= lang('domain') ?></th>
                <th><?= lang('industry') ?></th>
                <th><?= lang('trial_period') ?></th>
                <th><?= lang('currency') ?></th>
                <th><?= lang('frequency') ?></th>
                <th><?= lang('status') ?></th>
                <th><?= lang('date') ?></th>
                <th><?= lang('action') ?></th>
            </tr>
            </thead>
            <?php /* ?><tbody id="subscriptions_info">
            <?php
            $all_pricing = get_result('tbl_subscriptions');
            if (!empty($all_pricing)) {
                foreach ($all_pricing as $pricing) {
                    if (empty($pricing->currency)) {
                        $currency_code = config_item('default_currency');
                    } else {
                        $currency_code = $pricing->currency;
                    }
                    $currency = $this->account_model->check_by(array('code' => $currency_code), 'tbl_currencies');
                    if ($pricing->status == 'pending') {
                        $label = 'primary';
                    } else if ($pricing->status == 'running') {
                        $label = 'success';
                    } else if ($pricing->status == 'expired') {
                        $label = 'warning';
                    } else {
                        $label = 'danger';
                    }
                    ?>
                    <tr class="pricing" id="table_pricing_<?= $pricing->subscriptions_id ?>">
                        <td>
                            <div class="form-check font-size-16">
                                                        <input class="action-check form-check-input" type="checkbox" data-id="<?= $pricing->subscriptions_id ?>" style="position: absolute;" name="tenant_id[]" value="<?= $pricing->subscriptions_id ?>">
                                                        <label class="form-check-label"></label>
                                                    </div>
                        </td>
                        <td>
                            <a data-bs-toggle='modal' data-bs-target='#myModal'
                               href="<?= base_url('admin/global_controller/package_details/' . $pricing->pricing_id) ?>"
                               class="text-center"><?= $this->db->where(array('id' => $pricing->pricing_id))->get('tbl_frontend_pricing')->row()->name ?></a>
                        </td>
                        <td><?= $pricing->domain ?></td>
                        <td><?= $pricing->industry_type ?></td>
                        <td><?= $pricing->trial_period . ' ' . lang('days') ?></td>
                        <td><?= $currency->name . '(' . $currency->symbol . ')' ?></td>
                        <td><?= lang($pricing->frequency) ?></td>
                        <td><span class="label label-<?= $label ?>"> <?= lang($pricing->status) ?></span></td>
                        <td><?= strftime(config_item('date_format'), strtotime($pricing->created_date)) ?></td>
                        <td>
                            <?php if ($pricing->status == 'pending') { ?>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" class=" btn btn-outline-success btn-sm "
                                   title="<?= lang('send_activation_token') ?>"
                                   href="<?= base_url('admin/frontend/send_activation_token/' . $pricing->subscriptions_id) ?>" ><span class="fa fa-envelope-o"></span></a>
                            <?php }else{ ?> 
                                <a target="_blank" href="<?= base_url('admin/frontend/get_access_for_admin/' . $pricing->subscriptions_id) ?>">Get Access</a>
                            <?php } ?>
                           
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
       { type: 'date-uk', targets: 8 }
     ],
            
    } );
        setTimeout( function (){ table.order( [ 8, 'desc' ] ).draw() } , 600);

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


     function multipe_change_industry(industry){
        if(!$(".action-check").is(":checked")){
             toastr['error']('Please select any record');
          return false
        }
        if( !confirm("<?=lang('are_you_sure_want_to_update');?>")){
            return false;
        }
        var tenant_id = [];
          $(".action-check:checked").each(function(){
             tenant_id.push($(this).val());
        });
        $('#loader-wrapper').show();

       // data = data.serialize()
        // console.log(data);
          $.ajax({
           url: '<?=site_url('admin/frontend/multiple_tenant_change_industry');?>',
           data: {tenant_id:tenant_id, industry:industry },
          
           type: 'POST',
           success: function(data){
           data = jQuery.parseJSON(data);
              $('#loader-wrapper').hide();
             if(data['success']==true){
                 toastr['success'](data['message']);
                 window.location.reload();
             }else{
                toastr['erorr'](data['message']);
             }
             
        },
           error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus+" "+errorThrown);
          }
        });
    }
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
             'url':'<?=base_url()?>admin/datatable/tenant'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_pricing_"+iDisplayIndex);
            $(nRow).addClass("pricing");
            return nRow;
          },
          'columns': [
             { data: 'subscriptions_id' },
             { data: 'plan_name' },
             { data: 'domain' },
             { data: 'industry_type' },
             { data: 'trial_period' },
             { data: 'currency' },
             { data: 'frequency' },
             { data: 'status' },
             { data: 'date' },
             { data: 'action' },
          ]
        });
     });
 </script>