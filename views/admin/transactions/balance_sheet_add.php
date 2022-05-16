<?php 
if (!empty($sheet_info)) {
    $sheet_id = $sheet_info->id;
    $companies_id = $sheet_info->companies_id;
} else {
    $sheet_id = null;
    $companies_id = null;
}

?>
<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<?php 
$created = can_action('30', 'created');

$edited = can_action('30', 'edited');
$deleted = can_action('30', 'deleted');

?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18">  <?php if(!$sheet_id){
              echo lang('create_new_balance_sheet'); 
            }else{
              echo lang('edit_balance_sheet'); 

            } ?> 
            </h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

            <h4 class="card-title mb-4"><?= lang('Details') ?></h4>

            <?php echo form_open(base_url('admin/transactions/save_balance_sheet/' . $sheet_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
            <?php super_admin_form($companies_id, 2, 6) ?>

             <div class="mb-3 row ">
                                <label class="col-md-2 col-form-label"><?= lang('account') ?> <span
                                        class="text-danger">*</span> </label>
                                <div class="col-md-6">
                                  
                                        <select class="form-control select_box" style="width: 100%"
                                                name="account_id"
                                                <?php
                                     
                                            echo  'required';
                                        
                                        ?>>
                                            <option value=""><?= lang('select') . ' ' . lang('account') ?></option>
                                            <?php
                                            $account_info = by_company('tbl_accounts', 'account_id', null, $companies_id);
                                            if (!empty($account_info)) {
                                                foreach ($account_info as $v_account) {
                                                    ?>
                                                    <option value="<?= $v_account->account_id ?>"
                                                        <?php
                                                        if (!empty($sheet_info->account_id)) {
                                                            echo $sheet_info->account_id == $v_account->account_id ? 'selected' : '';
                                                        }
                                                        ?>
                                                    ><?= $v_account->account_name ?></option>
                                                    <?php
                                                }
                                            }
                                          
                                            ?>
                                        </select>
                                       
                                   
                                </div>
                            </div>
            <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('title') ?></label>
                <div class="col-md-6">
                    <input type="text" name="title" required class="form-control" value="<?php if (!empty($sheet_info->title)) echo $sheet_info->title; ?>"/>
                </div>
            </div>
              <div class="mb-3 row">
                <h4 class="col-md-12 "><u><?= lang('current_assets_caps') ?></u></h4>
            </div>
             <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('cash') ?></label>
                <div class="col-md-6">
                    <input type="text" name="cash" required data-parsley-type="number" class="form-control" value="<?php if (!empty($sheet_info->cash)) echo $sheet_info->cash; ?>"/>
                </div>
            </div>
               <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('accounts_receivable') ?></label>
                <div class="col-md-6">
                    <input type="text" name="accounts_receivable" required data-parsley-type="number"  class="form-control" value="<?php if (!empty($sheet_info->accounts_receivable)) echo $sheet_info->accounts_receivable; ?>"/>
                </div>
            </div>
             <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('inventory') ?></label>
                <div class="col-md-6">
                    <input type="text" name="inventory" required data-parsley-type="number"  class="form-control" value="<?php if (!empty($sheet_info->inventory)) echo $sheet_info->inventory; ?>"/>
                </div>
            </div>

             <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('prepaid_expenses') ?></label>
                <div class="col-md-6">
                    <input type="text" name="prepaid_expenses" required data-parsley-type="number"  class="form-control" value="<?php if (!empty($sheet_info->prepaid_expenses)) echo $sheet_info->prepaid_expenses; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('notes_receivable') ?></label>
                <div class="col-md-6">
                    <input type="text" name="notes_receivable" required data-parsley-type="number"  class="form-control" value="<?php if (!empty($sheet_info->notes_receivable)) echo $sheet_info->notes_receivable; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('other_current_assets') ?></label>
                <div class="col-md-6">
                    <input type="text" name="other_current_assets" required data-parsley-type="number"  class="form-control" value="<?php if (!empty($sheet_info->other_current_assets)) echo $sheet_info->other_current_assets; ?>"/>
                </div>
            </div>
    
 <div class="mb-3 row">
                <h4 class="col-md-12 "><u><?= lang('fixed_assets_caps') ?></u></h4>
            </div>
        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('long_term_investments') ?></label>
                <div class="col-md-6">
                    <input type="text" name="long_term_investments" data-parsley-type="number" required class="form-control" value="<?php if (!empty($sheet_info->long_term_investments)) echo $sheet_info->long_term_investments; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('land') ?></label>
                <div class="col-md-6">
  <input type="text" name="land" data-parsley-type="number" required class="form-control" value="<?php if(!empty($sheet_info->land)) echo $sheet_info->land; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('building') ?></label>
                <div class="col-md-6">
            <input type="text" name="building" data-parsley-type="number" required class="form-control" value="<?php if(!empty($sheet_info->building)) echo $sheet_info->building; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('accumulated_building_depreciation') ?></label>
                <div class ="col-md-6">
                    <input type="text" name="accumulated_building_depreciation" data-parsley-type="number" required class="form-control" value="<?php if (!empty($sheet_info->accumulated_building_depreciation)) echo $sheet_info->accumulated_building_depreciation; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('machinery_and_equipment') ?></label>
                <div class="col-md-6">
                    <input type="text" name="machinery_and_equipment" data-parsley-type="number" required class="form-control" value="<?=isset($sheet_info->machinery_and_equipment)?$sheet_info->machinery_and_equipment : '';?>" />
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('accumulated_machinery_and_equipment_depreciation') ?></label>
                <div class="col-md-6">
                    <input type="text" data-parsley-type="number" required name="accumulated_machinery_and_equipment_depreciation"  class="form-control" value="<?php if (!empty($sheet_info->accumulated_machinery_and_equipment_depreciation)) echo $sheet_info->accumulated_machinery_and_equipment_depreciation; ?>"/>
                </div>
            </div>
    


        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('furniture_and_fixtures') ?></label>
                <div class="col-md-6">
                    <input type="text" name="furniture_and_fixtures" data-parsley-type="number" required  class="form-control" value="<?=isset($sheet_info->furniture_and_fixtures)?$sheet_info->furniture_and_fixtures:'';?>" />
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('accumulated_furniture_and_fixtures_depreciation') ?></label>
                <div class="col-md-6">
                    <input type="text" name="accumulated_furniture_and_fixtures_depreciation"  class="form-control" data-parsley-type="number" required value="<?php if (!empty($sheet_info->accumulated_furniture_and_fixtures_depreciation)) echo $sheet_info->accumulated_furniture_and_fixtures_depreciation; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('other_fixed_assets') ?></label>
                <div class="col-md-6">
                    <input type="text" name="other_fixed_assets" data-parsley-type="number" required class="form-control" value="<?php if (!empty($sheet_info->other_fixed_assets)) echo $sheet_info->other_fixed_assets; ?>"/>
                </div>
            </div>
    
 <div class="mb-3 row">
                <h4 class="col-md-12 "><u><?= lang('other_assets_caps') ?></u></h4>
            </div>
        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('good_will') ?></label>
                <div class="col-md-6">
  <input  type="text" name="good_will" data-parsley-type="number" required class="form-control" value="<?php if(!empty($sheet_info->good_will)) echo $sheet_info->good_will; ?>"/>
                </div>
            </div>
    <div class="mb-3 row">
                <h4 class="col-md-12 "><u><?= lang('current_liabilities_caps') ?></u></h4>
            </div>

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('account_payable') ?></label>
                <div class="col-md-6">
                    <input type="text" name="account_payable" data-parsley-type="number" required class="form-control" value="<?php if (!empty($sheet_info->account_payable)) echo $sheet_info->account_payable; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('accrued_wages') ?></label>
                <div class="col-md-6">
<input  type="text" name="accrued_wages" data-parsley-type="number" required class="form-control" value="<?php if (!empty($sheet_info->accrued_wages)) echo $sheet_info->accrued_wages; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('accrued_payroll_taxes') ?></label>
                <div class="col-md-6">
                    <input type="text" name="accrued_payroll_taxes"  data-parsley-type="number" required class="form-control" value="<?php if (!empty($sheet_info->accrued_payroll_taxes)) echo $sheet_info->accrued_payroll_taxes; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('accrued_employee_benefits') ?></label>
                <div class="col-md-6">
                    <input type="text" name="accrued_employee_benefits" data-parsley-type="number" required class="form-control" value="<?=isset($sheet_info->machinery_and_equipment)?$sheet_info->machinery_and_equipment:'';?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('interest_payable') ?></label>
                <div class="col-md-6">
                    <input type="text" name="interest_payable" data-parsley-type="number" required class="form-control" value="<?php if (!empty($sheet_info->interest_payable)) echo $sheet_info->interest_payable; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('short_term_notes') ?></label>
                <div class="col-md-6">
                    <input type="text" name="short_term_notes" data-parsley-type="number" required class="form-control" value="<?php if (!empty($sheet_info->short_term_notes)) echo $sheet_info->short_term_notes; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('current_portion_of_long_term_debt') ?></label>
                <div class ="col-md-6">
                    <input type="text" name="current_portion_of_long_term_debt" required class="form-control" value="<?php if (!empty($sheet_info->current_portion_of_long_term_debt)) echo $sheet_info->current_portion_of_long_term_debt; ?>"/>
                </div>
            </div>
    
   <div class="mb-3 row">
                <h4 class="col-md-12 "><u><?= lang('long_term_liability_caps') ?></u></h4>
            </div>
        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('mortgage') ?></label>
                <div class="col-md-6">
<input type="text" name="mortgage" data-parsley-type="number" required class="form-control" value="<?php if (!empty($sheet_info->mortgage)) echo $sheet_info->mortgage; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('other_long_term_liabilities') ?></label>
                <div class="col-md-6">
                    <input type="text" name="other_long_term_liabilities" data-parsley-type="number" required  class="form-control" value="<?php if (!empty($sheet_info->other_long_term_liabilities)) echo $sheet_info->other_long_term_liabilities; ?>"/>
                </div>
            </div>
    
  <div class="mb-3 row">
                <h4 class="col-md-12 "><u><?= lang('shareholders_equity_caps') ?></u></h4>
            </div>
        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('common_stock') ?></label>
                <div class="col-md-6">
<input type="text" name="common_stock" data-parsley-type="number" required class="form-control" value="<?php if (!empty($sheet_info->common_stock)) echo $sheet_info->common_stock; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('preferred_stock') ?></label>
                <div class="col-md-6">
                    <input type="text" name="preferred_stock" data-parsley-type="number"  required class="form-control" value="<?php if (!empty($sheet_info->preferred_stock)) echo $sheet_info->preferred_stock; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('additional_paid_in_capital') ?></label>
                <div class="col-md-6">
                    <input type="text" name="additional_paid_in_capital" data-parsley-type="number" required class="form-control" value="<?php if (!empty($sheet_info->additional_paid_in_capital)) echo $sheet_info->additional_paid_in_capital; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('retained_earnings') ?></label>
                <div class="col-md-6">
                    <input type="text" name="retained_earnings" data-parsley-type="number" required class="form-control" value="<?php if (!empty($sheet_info->retained_earnings)) echo $sheet_info->retained_earnings; ?>"/>
                </div>
            </div>
               <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('treasury_stock') ?></label>
                <div class="col-md-6">
                    <input type="text" name="treasury_stock" data-parsley-type="number" required class="form-control" value="<?php if (!empty($sheet_info->treasury_stock)) echo $sheet_info->treasury_stock; ?>"/>
                </div>
            </div>
              <div class="mb-3 row">
                <h4 class="col-md-12 "><u><?= lang('owners_equity_caps') ?></u></h4>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('paid_in_capital') ?></label>
                <div class="col-md-6">
                    <input type="text" name="paid_in_capital" data-parsley-type="number" required class="form-control" value="<?php if (!empty($sheet_info->paid_in_capital)) echo $sheet_info->paid_in_capital; ?>"/>
                </div>
            </div>
    

        <div class="mb-3 row">
                <label class="col-md-2 col-form-label"><?= lang('net_income') ?></label>
                <div class="col-md-6">
 <input type name="net_income" data-parsley-type="number" required class="form-control" value="<?php if(!empty($sheet_info->net_income)) echo $sheet_info->net_income; ?>"/>
                </div>
            </div>
    

              <div class="mb-3 row" id="border-none">
                <label class="col-md-2 col-form-label"><?= lang('assined_to') ?> <span
                            class="required">*</span></label>
                <div class="col-md-6">
                    <div class="form-check form-radio-outline form-radio-primary mb-3">
                        <input id="user_permission_1" <?php
                            if (!empty($sheet_info->permission) && $sheet_info->permission == 'all') {
                                echo 'checked';
                            }
                            ?> type="radio" name="permission" value="everyone" class="form-check-input">
                        <label for="user_permission_1" class="form-check-label"> <?= lang('everyone') ?> <i title="<?= lang('permission_for_all') ?>"
                               class="fa fa-question-circle" data-bs-toggle="tooltip"
                               data-bs-placement="top"></i>
                        </label>
                    </div>
                    <div class="form-check form-radio-outline form-radio-primary mb-3">
                        <input  <?php
                            if (!empty($sheet_info->permission) && $sheet_info->permission != 'all') {
                                echo 'checked';
                            } elseif (empty($sheet_info)) {
                                echo 'checked';
                            }
                            ?> type="radio" name="permission" value="custom_permission" class="form-check-input" id="user_permission_2">
                        <label for="user_permission_2" class="form-check-label"> <?= lang('custom_permission') ?>  
                            <i title="<?= lang('permission_for_customization') ?>"
                                    class="fa fa-question-circle" data-bs-toggle="tooltip"
                                    data-bs-placement="top"></i>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mb-3 row <?php
                if (!empty($sheet_info->permission) && $sheet_info->permission != 'all') {
                    echo 'show';
                }
                ?>" id="permission_user_1">
                <label class="col-md-2 col-form-label"><?= lang('select') . ' ' . lang('users') ?>
                    <span
                            class="required">*</span></label>
                <div class="col-md-6">
                <?php
                if (!empty($assign_user)) { ?>
                    <input type="text" name="search_assigned_user" value="" placeholder="<?=lang('search_by').' '.lang('username'); ?>" class="form-control mb-4" id="search_assigned_user" autocomplete="off">
                    <div data-simplebar style="max-height: 250px;">  
                        <?php 
                        foreach ($assign_user as $key => $v_user) {

                            if ($v_user->role_id == 1) {
                                $disable = true;
                                $role = '<strong class="badge btn-danger">' . lang('admin') . '</strong>';
                            } else {
                                $disable = false;
                                $role = '<strong class="badge btn-primary">' . lang('staff') . '</strong>';
                            }

                            ?>
                            <div class="form-check form-check-primary mb-3">
                                <input type="checkbox"
                                        <?php
                                        if (!empty($sheet_info->permission) && $sheet_info->permission != 'all') {
                                            $get_permission = json_decode($sheet_info->permission);
                                            foreach ($get_permission as $user_id => $v_permission) {
                                                if ($user_id == $v_user->user_id) {
                                                    echo 'checked';
                                                }
                                            }

                                        }
                                        ?> value="<?= $v_user->user_id ?>" name="assigned_to[]" data-name="<?= $v_user->username;?>"  class="form-check-input" id ="user_<?= $v_user->user_id ?>">
                                <label for="user_<?= $v_user->user_id ?>" class="form-check-label"><?= $v_user->username . ' ' . $role ?></label>
                            </div>
                            <div class="action_1 p
                                <?php

                                    if (!empty($sheet_info->permission) && $sheet_info->permission != 'all') {
                                        $get_permission = json_decode($sheet_info->permission);

                                        foreach ($get_permission as $user_id => $v_permission) {
                                            if ($user_id == $v_user->user_id) {
                                                echo 'show';
                                            }
                                        }

                                    }  ?>" id="action_1<?= $v_user->user_id ?>">
                                <div class="form-check form-check-primary mb-3 mr">     
                                    <input id="view_<?= $v_user->user_id ?>" checked  type="checkbox" name="action_1<?= $v_user->user_id ?>[]"  disabled   value="view" class="form-check-input">
                                    <label class="form-check-label" for="view_<?= $v_user->user_id ?>"><?= lang('can') . ' ' . lang('view') ?>
                                    </label>
                                </div>
                                <div class="form-check form-check-primary mb-3 mr">     
                                    <input <?php if (!empty($disable)) {
                                            echo 'disabled' . ' ' . 'checked';
                                        } ?> id="edit_<?= $v_user->user_id ?>"
                                            <?php

                                            if (!empty($sheet_info->permission) && $sheet_info->permission != 'all') {
                                                $get_permission = json_decode($sheet_info->permission);

                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        if (in_array('edit', $v_permission)) {
                                                            echo 'checked';
                                                        };

                                                    }
                                                }

                                            }
                                            ?>  type="checkbox"  value="edit"  name="action_<?= $v_user->user_id ?>[]" class="form-check-input">
                                        
                                    <label class="form-check-label" for="edit_<?= $v_user->user_id ?>"><?= lang('can') . ' ' . lang('edit') ?></label>
                                </div>
                                <div class="form-check form-check-primary mb-3 mr">     
                                    <input <?php if (!empty($disable)) {
                                            echo 'disabled' . ' ' . 'checked';
                                        } ?> id="delete_<?= $v_user->user_id ?>"
                                            <?php

                                            if (!empty($sheet_info->permission) && $sheet_info->permission != 'all') {
                                                $get_permission = json_decode($sheet_info->permission);
                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        if (in_array('delete', $v_permission)) {
                                                            echo 'checked';
                                                        };
                                                    }
                                                }

                                            }
                                            ?> name="action_<?= $v_user->user_id ?>[]" type="checkbox" value="delete" class="form-check-input">
                                    <label class="form-check-label" for="delete_<?= $v_user->user_id ?>"><?= lang('can') . ' ' . lang('delete') ?>
                                    </label>
                                </div>

                                <input id="<?= $v_user->user_id ?>" type="hidden"  name="action_<?= $v_user->user_id ?>[]" value="view">

                            </div>
                        <?php   } ?>
                    </div>
                <?php }  ?>
                </div>
            </div>

            <div class="text-center">
               
               
                    <button type="submit" id="sbtn"
                            class="btn btn-primary"><?= lang('save') ?></button>
                               <a href="<?=base_url('admin/transactions/balance_sheet');?>" 
                            class="btn btn-secondary"><?= lang('Cancel') ?></a>
               
            </div>
            <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

  <script type="text/javascript">
                                $(document).ready(function () {
                                    $('select[name="companies_id"]').on('change', function () {
                                        var companies_id = $(this).val();
                                        if (companies_id) {
                                            $.ajax({
                                                url: '<?= base_url('admin/global_controller/json_by_company/tbl_accounts/')?>' + companies_id,
                                                type: "GET",
                                                dataType: "json",
                                                success: function (data) {
                                                    $('select[name="account_id"]').find('option').not(':first').remove();
                                                    $.each(data, function (key, value) {
                                                        $('select[name="account_id"]').append('<option value="' + value.account_id + '">' + value.account_name + '</option>');
                                                    });
                                                }
                                            });

                                          

                                        } else {
                                            // $('select[name="account_id"]').find('option').not(':first').remove();
                                        }
                                    });
                                });
                            </script>
                            <div class="modal fade" id="preview-modal" role="dialog" aria-labelledby="preview-modalLabel" aria-hidden="true">
                                <div class="modal-dialog modal_extra_lg">
                                    <div class="modal-content">
                                      
   <div class="modal-header">
        <h5 class="modal-title">Report Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="row mb-3">
            <div class="col-xl-12"> 
                <div class="row">        
                
                </div>
            </div>
        </div>
       
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close">Close</button>
              
        </div>
    </div>

                                    </div>
                                </div>
                            </div>