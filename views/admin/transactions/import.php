<?= message_box('success'); ?>
<?= message_box('error');
if ($type == 'Income') {
    $link='deposit';
    $header = lang('all_deposit');
} else {
    $link='expense';
    $header = lang('all_expense');
}
?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18">  <?php echo $title ?> 
            </h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row mb-3">
    <div class="mb-lg pull-left">
        <div class="float-end">
            <a class="btn btn-xs btn-danger" href="<?= base_url() ?>admin/transactions/<?=$link;?>"><?= $header;?></a>
        </div>
    </div>
</div>
<div class="row">
    <div class="card"> 
        <div class="card-body">
            <div class="pull-right pr-lg">
                <a href="<?php echo base_url() ?>assets/sample/transactions_sample.xlsx" class="btn btn-primary"><i class="fa fa-download"> <?= lang('download_sample') ?></i></a>
            </div>
            <h4 class="card-title mb-4"><?php echo $title ?></h4>

            <form role="form" enctype="multipart/form-data" data-parsley-validate="" novalidate="" action="<?php echo base_url(); ?>admin/transactions/save_imported" method="post" class="form-horizontal  ">
                <div class="row mb-3">
                    <label class="col-lg-3 col-md-3 col-sm-3 col-form-label">
                        <?= lang('choose_file') ?><span class="required">*</span></label>
                    <div class="col-xl-5">
                        <input class="form-control" type="file" id="formFile" name="upload_file" >
                    </div>   
                </div>
                <input type="hidden" name="type" value="<?= $type ?>">
                <div class="row mb-3 ">
                    <label class="col-lg-3 col-md-3 col-sm-3 col-form-label mt-lg"><?= lang('account') ?> <span
                            class="text-danger">*</span> </label>
                    <div class="col-lg-5 col-md-5 col-sm-5 mt-lg">
                        <select class="form-control select_box" style="width: 100%" name="account_id" required <?php
                        if (!empty($deposit_info)) {
                            echo 'disabled';
                        }
                        ?>>

                            <?php
                            $account_info = get_result('tbl_accounts');
                            if (!empty($account_info)) {
                                foreach ($account_info as $v_account) {
                                    ?>
                                    <option value="<?= $v_account->account_id ?>"
                                        <?php
                                        if (!empty($deposit_info->account_id)) {
                                            echo $deposit_info->account_id == $v_account->account_id ? 'selected' : '';
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
                <div class="row mb-3">
                    <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('deposit_category') ?> </label>
                    <div class="col-lg-5 col-md-5 col-sm-5">
                        <select class="form-control select_box" style="width: 100%" name="category_id">
                            <option value="0"><?= lang('none') ?></option>
                            <?php
                            $category_info = get_result('tbl_income_category');
                            if (!empty($category_info)) {
                                foreach ($category_info as $v_category) {
                                    ?>
                                    <option value="<?= $v_category->income_category_id ?>"
                                        <?php
                                        if (!empty($deposit_info->category_id)) {
                                            echo $deposit_info->category_id == $v_category->income_category_id ? 'selected' : '';
                                        }
                                        ?>
                                    ><?= $v_category->income_category ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('paid_by') ?> </label>
                    <div class="col-lg-5 col-md-5 col-sm-5">
                        <select class="form-control select_box" style="width: 100%" name="paid_by">
                            <option value="0"><?= lang('select_payer') ?></option>
                            <?php
                            $all_client = get_result('tbl_client');
                            if (!empty($all_client)) {
                                foreach ($all_client as $v_client) {
                                    ?>
                                    <option value="<?= $v_client->client_id ?>"
                                        <?php
                                        if (!empty($deposit_info)) {
                                            echo $deposit_info->paid_by == $v_client->client_id ? 'selected' : '';
                                        }
                                        ?>
                                    ><?= ucfirst($v_client->name); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('payment_method') ?> </label>
                    <div class="col-lg-5 col-md-5 col-sm-5">
                        <select class="form-control select_box" style="width: 100%" name="payment_methods_id">
                            <option value="0"><?= lang('select_payment_method') ?></option>
                            <?php
                            $payment_methods = get_result('tbl_payment_methods');
                            if (!empty($payment_methods)) {
                                foreach ($payment_methods as $p_method) {
                                    ?>
                                    <option value="<?= $p_method->payment_methods_id ?>" <?php
                                    if (!empty($deposit_info)) {
                                        echo $deposit_info->payment_methods_id == $p_method->payment_methods_id ? 'selected' : '';
                                    }
                                    ?>><?= $p_method->method_name ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div id="add_new">
                    <div class="row mb-3" style="margin-bottom: 0px">
                        <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('attachment') ?></label>
                        <div class="col-lg-5 col-md-5 col-sm-5">
                            <input class="form-control" type="file" id="formFile" name="attachement[]" >
                        </div>
                        <div class="col-xl-1">
                            <a href="javascript:void(0);" id="add_more" class="addCF" title="<?= lang('add_more') ?>"><i class="fa fa-plus mt"></i></a>
                        </div>
                        <div class="fileinput-new thumbnail col-xl-3">
                           <?php
                            if (!empty($deposit_info->attachement)) {
                                $attachement = json_decode($deposit_info->attachement);
                            }
                            if (!empty($attachement)):foreach ($attachement as $v_files): ?>
                            <div class="">
                                <input type="hidden" name="path[]"
                                       value="<?php echo $v_files->path ?>">
                                <input type="hidden" name="fileName[]"
                                       value="<?php echo $v_files->fileName ?>">
                                <input type="hidden" name="fullPath[]"
                                       value="<?php echo $v_files->fullPath ?>">
                                <input type="hidden" name="size[]"
                                       value="<?php echo $v_files->size ?>">
                                <span class=" btn btn-default btn-file">
                                    <span class="fileinput-filename"> <?php echo $v_files->fileName ?></span>
                                    <a href="javascript:void(0);" class="remCFile" style="float: none;">×</a>
                                </span>
                                <strong><a href="javascript:void(0);" class="RCF"><i class="fa fa-times"></i>&nbsp;Remove</a></strong>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
                <div class="row mb-3" id="border-none">
                    <label class="col-md-3 col-form-label"><?= lang('assined_to') ?> <span class="required">*</span></label>
                    <div class="col-md-8">
                        <div class="form-check form-radio-outline form-radio-primary mb-3">
                            <input id="everyone" <?php
                                if (!empty($deposit_info->permission) && $deposit_info->permission == 'all') {
                                    echo 'checked';
                                } 
                                ?> type="radio" name="permission" value="everyone" class="form-check-input">
                            <label class="form-check-label" for="everyone"><?= lang('everyone') ?>
                                <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip"
                                   data-bs-placement="top"></i>
                            </label>
                        </div>
                        <div class="form-check form-radio-outline form-radio-primary mb-3">
                            <input id="custom_permission" <?php
                                if (!empty($deposit_info->permission) && $deposit_info->permission != 'all') {
                                    echo 'checked';
                                }elseif (empty($deposit_info)) {
                                    echo 'checked';
                                }
                                ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                            <label class="form-check-label" for="custom_permission">
                                <?= lang('custom_permission') ?>
                                <i  title="<?= lang('permission_for_customization') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 <?php
                    if (!empty($deposit_info->permission) && $deposit_info->permission != 'all') {
                        echo 'show';
                    }
                    ?>" id="permission_user_1">
                    <label class="col-md-3 col-form-label"><?= lang('select') . ' ' . lang('users') ?> <span class="required">*</span></label>
                    <div class="col-md-5">
                    <?php
                    if (!empty($permission_user)) { ?>
                        <input type="text" name="search_assigned_user" value="" placeholder="<?=lang('search_by').' '.lang('username'); ?>" class="form-control mb-4" id="search_assigned_user" autocomplete="off">
                        <div data-simplebar style="max-height: 250px;">  
                            <?php 
                            foreach ($permission_user as $key => $v_user) {

                                if ($v_user->role_id == 1) {
                                    $role = '<strong class="badge btn-danger">' . lang('admin') . '</strong>';
                                } else {
                                    $role = '<strong class="badge btn-primary">' . lang('staff') . '</strong>';
                                }

                                ?>
                            <div class="form-check form-check-primary mb-3">
                                <input type="checkbox"
                                    <?php
                                    if (!empty($deposit_info->permission) && $deposit_info->permission != 'all') {
                                        $get_permission = json_decode($deposit_info->permission);
                                        foreach ($get_permission as $user_id => $v_permission) {
                                            if ($user_id == $v_user->user_id) {
                                                echo 'checked';
                                            }
                                        }

                                    }
                                    ?>  value="<?= $v_user->user_id ?>" name="assigned_to[]" data-name="<?= $v_user->username;?>"  class="form-check-input" id ="user_<?= $v_user->user_id ?>">
                                <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?>
                                </label>
                            </div>
                            <div class="action_1 p
                                <?php

                                if (!empty($deposit_info->permission) && $deposit_info->permission != 'all') {
                                    $get_permission = json_decode($deposit_info->permission);

                                    foreach ($get_permission as $user_id => $v_permission) {
                                        if ($user_id == $v_user->user_id) {
                                            echo 'show';
                                        }
                                    }

                                }
                                ?>" id="action_1<?= $v_user->user_id ?>">
                                <div class="form-check form-check-primary mb-3 mr">         
                                    <input id="view_<?= $v_user->user_id ?>" checked type="checkbox" name="action_1<?= $v_user->user_id ?>[]" disabled value="view" class="form-check-input">
                                    <label class="form-check-label" for="view_<?= $v_user->user_id ?>"><?= lang('view') ?></label>
                                </div>
                                <div class="form-check form-check-primary mb-3 mr">         
                                    <input <?php if (!empty($disable)) { echo 'disabled' . ' ' . 'checked'; } ?> id="edit_<?= $v_user->user_id ?>"
                                        <?php

                                        if (!empty($deposit_info->permission) && $deposit_info->permission != 'all') {
                                            $get_permission = json_decode($deposit_info->permission);

                                            foreach ($get_permission as $user_id => $v_permission) {
                                                if ($user_id == $v_user->user_id) {
                                                    if (in_array('edit', $v_permission)) {
                                                        echo 'checked';
                                                    };

                                                }
                                            }

                                        }
                                        ?> type="checkbox" value="edit" name="action_<?= $v_user->user_id ?>[]" class="form-check-input">
                                    <label class="form-check-label" for="edit_<?= $v_user->user_id ?>"><?= lang('edit') ?></label>
                                </div>
                                <div class="form-check form-check-primary mb-3 mr">         
                                    <input <?php if (!empty($disable)) { echo 'disabled' . ' ' . 'checked'; } ?> id="delete_<?= $v_user->user_id ?>"
                                        <?php

                                        if (!empty($deposit_info->permission) && $deposit_info->permission != 'all') {
                                            $get_permission = json_decode($deposit_info->permission);
                                            foreach ($get_permission as $user_id => $v_permission) {
                                                if ($user_id == $v_user->user_id) {
                                                    if (in_array('delete', $v_permission)) {
                                                        echo 'checked';
                                                    };
                                                }
                                            }

                                        }
                                        ?>  name="action_<?= $v_user->user_id ?>[]"  type="checkbox"  value="delete" class="form-check-input">
                                    <label class="form-check-label" for="delete_<?= $v_user->user_id ?>"><?= lang('delete') ?></label>
                                </div>
                                <input id="<?= $v_user->user_id ?>" type="hidden" name="action_<?= $v_user->user_id ?>[]" value="view">
                            </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"></label>
                    <div class="col-lg-6 col-md-6 col-sm-3">
                        <button type="submit" class="btn btn-sm btn-primary"></i> <?= lang('upload') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var maxAppend = 0;
        $("#add_more").click(function () {
            var add_new = $('<div class="row mb-3" style="margin-bottom: 0px">\n\
                <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('attachment') ?></label>\n\
                        <div class="col-sm-5">\n\
                            <input class="form-control" type="file" id="formFile" name="attachement[]" ></div>\n\<div class="col-sm-2">\n\<strong>\n\<a href="javascript:void(0);" class="remCF"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div>');
            maxAppend++;
            $("#add_new").append(add_new);
        });

        $("#add_new").on('click', '.remCF', function () {
            $(this).parent().parent().parent().remove();
        });

        $('a.RCF').click(function () {
            $(this).parent().parent().remove();
        });
    });
</script>
