<?= message_box('success'); ?>
<?= message_box('error'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>

        </div>
    </div>
</div>
<!-- end page title -->

<?php 
$eeror_message = $this->session->userdata('error');
if (!empty($eeror_message)):foreach ($eeror_message as $key => $message):
    ?>
    <div class="alert alert-danger">
        <?php echo $message; ?>
    </div>
<?php
endforeach;
endif;
$this->session->unset_userdata('error');
?>
<?php
$all_bug_info = $this->client_model->get_permission('tbl_bug');
$total_bugs = 0;
if (!empty($all_bug_info)) {
    foreach ($all_bug_info as $v_bugs) {
        if (!empty($v_bugs)) {
            $profile = get_row('tbl_account_details', array('user_id' => $v_bugs->reporter));
            if (!empty($profile)) {
                if ($profile->company == $client_details->client_id) {
                    $total_bugs += count($v_bugs->bug_id);
                }
            }
        }
    }
}
$recently_paid = get_order_by('tbl_payments', array('paid_by' => $client_details->client_id), 'created_date');
$all_tickets_info = $this->client_model->get_permission('tbl_tickets');
$total_tickets = 0;
if (!empty($all_tickets_info)) {
    foreach ($all_tickets_info as $v_tickets_info) {
        if (!empty($v_tickets_info)) {
            $profile_info = $this->db->where(array('user_id' => $v_tickets_info->reporter))->get('tbl_account_details')->row();
            if (!empty($profile_info->company))
                if ($profile_info->company == $client_details->client_id) {
                    $total_tickets += count($v_tickets_info->tickets_id);
                }
        }
    }
}
$all_project = get_result('tbl_project', array('client_id' => $client_details->client_id));
$client_notes = get_result('tbl_notes', array('user_id' => $client_details->client_id, 'is_client' => 'Yes'));

$client_outstanding = $this->invoice_model->client_outstanding($client_details->client_id);
$client_payments = $this->invoice_model->get_sum('tbl_payments', 'amount', $array = array('paid_by' => $client_details->client_id));
$client_payable = $client_payments + $client_outstanding;
$client_currency = $this->invoice_model->client_currency_sambol($client_details->client_id);
if (!empty($client_currency)) {
    $cur = $client_currency->symbol;
} else {
    $currency = get_row('tbl_currencies', array('code' => config_item('default_currency')));
    $cur = $currency->symbol;
}
if ($client_payable > 0 AND $client_payments > 0) {
    $perc_paid = round(($client_payments / $client_payable) * 100, 1);
    if ($perc_paid > 100) {
        $perc_paid = '100';
    }
} else {
    $perc_paid = 0;
}
$client_transactions = $this->db->where('paid_by', $client_details->client_id)->get('tbl_transactions')->result();
$all_proposals_info = $this->db->where(array('module' => 'client', 'module_id' => $client_details->client_id))->order_by('proposals_id', 'DESC')->get('tbl_proposals')->result();
$edited = can_action('4', 'edited');
$notified_reminder = count($this->db->where(array('module' => 'client', 'module_id' => $client_details->client_id, 'notified' => 'No'))->get('tbl_reminders')->result());
?>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">
                                    <?= lang('paid_amount') ?> <br>
                                    <a href="<?= base_url() ?>admin/invoice/all_payments" class="small-box-footer"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a>
                                </p>
                                <h4 class="mb-0"><?php
                                    if (!empty($client_payments)) {
                                        echo display_money($client_payments, $cur);
                                    } else {
                                        echo '0.00';
                                    }
                                    ?></h4>
                            </div>

                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="bx bx-money font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">
                                    <?= lang('due_amount') ?> <br>
                                    <a href="<?= base_url() ?>admin/invoice/manage_invoice" class="small-box-footer"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a>
                                </p>
                                <h4 class="mb-0"><?php
                                    if ($client_outstanding > 0) {
                                        echo display_money($client_outstanding, $cur);
                                    } else {
                                        echo '0.00';
                                    }
                                    ?></h4>
                            </div>

                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="fa fa-usd font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">
                                    <?= lang('invoice_amount') ?> <br>
                                    <a href="<?= base_url() ?>admin/invoice/manage_invoice" class="small-box-footer"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a>
                                </p>
                                <h4 class="mb-0"><?php
                                    if ($client_payable > 0) {
                                        echo display_money($client_payable, $cur);
                                    } else {
                                        echo '0.00';
                                    }
                                    ?></h4>
                            </div>

                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="bx bx-archive-in font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">
                                    <?= lang('paid') . ' ' . lang('percentage') ?> <br>
                                    <a href="<?= base_url() ?>admin/invoice/all_payments" class="small-box-footer"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a>
                                </p>
                                <h4 class="mb-0"><?= $perc_paid ?>%</h4>
                            </div>

                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $url = $this->uri->segment(5); ?>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link <?= empty($url) ? 'active' : '' ?>" href="#task_details" data-bs-toggle="tab" aria-expanded="true"><?= lang('details') ?></a>
                    
                    <a class="nav-link <?= $url == 'add_contacts' ? 'active' : '' ?>" href="#contacts" data-bs-toggle="tab"  aria-expanded="false"><?= lang('contacts') ?> <strong class="badge badge-soft-danger pull-right"><?= (!empty($client_contacts) ? count($client_contacts) : null) ?></strong></a>
                    
                    <a class="nav-link <?= $url == 'notes' ? 'active' : '' ?>" href="#notes" data-bs-toggle="tab" aria-expanded="false"><?= lang('notes') ?><strong class="badge badge-soft-danger pull-right"><?= (!empty($client_notes) ? count($client_notes) : null) ?></strong></a>
                    
                    <a class="nav-link <?= $url == 'invoice' ? 'active' : '' ?>" href="#invoices" data-bs-toggle="tab" aria-expanded="false"><?= lang('invoices') ?> <strong class="badge badge-soft-danger pull-right"><?= (!empty($client_invoices) ? count($client_invoices) : null) ?></strong></a>
                    
                    <a class="nav-link <?= $url == 'payment' ? 'active' : '' ?>" href="#payments" data-bs-toggle="tab" aria-expanded="false"><?= lang('payments') ?><strong class="badge badge-soft-danger pull-right"><?= (!empty($recently_paid) ? count($recently_paid) : null) ?></strong></a>
                    
                    <a class="nav-link <?= $url == 'estimate' ? 'active' : '' ?>" href="#estimates" data-bs-toggle="tab" aria-expanded="false"><?= lang('estimates') ?><strong class="badge badge-soft-danger pull-right"><?= (!empty($client_estimates) ? count($client_estimates) : null) ?></strong></a>
                    
                    <a class="nav-link <?= $url == 'proposal' ? 'active' : '' ?>" href="#proposals" data-bs-toggle="tab"><?= lang('proposals') ?><strong
                        class="badge badge-soft-danger pull-right"><?= (!empty($all_proposals_info) ? count($all_proposals_info) : null) ?></strong></a>
                    
                    <a class="nav-link" href="#transaction" data-bs-toggle="tab" aria-expanded="false"><?= lang('transactions') ?> <strong class="badge badge-soft-danger pull-right"><?= (!empty($client_transactions) ? count($client_transactions) : null) ?></strong></a>
                    
                    <a class="nav-link" href="#projects" data-bs-toggle="tab" aria-expanded="false"><?= lang('project') ?><strong
                        class="badge badge-soft-danger pull-right"><?= (!empty($all_project) ? count($all_project) : null) ?></strong></a>
                    
                    <a class="nav-link" href="#ticket" data-bs-toggle="tab" aria-expanded="false"><?= lang('tickets') ?><strong
                        class="badge badge-soft-danger pull-right"><?= (!empty($total_tickets) ? $total_tickets : null) ?></strong></a>
                    
                    <a class="nav-link" href="#bugs" data-bs-toggle="tab" aria-expanded="false"><?= lang('bugs') ?><strong
                        class="badge badge-soft-danger pull-right"><?= (!empty($total_bugs) ? $total_bugs : null) ?></strong></a>
                    
                    <a class="nav-link <?= $url == 'reminder' ? 'active' : '' ?>" href="#reminder" data-bs-toggle="tab" aria-expanded="false"><?= lang('reminder') ?> <strong class="badge badge-soft-danger pull-right"><?= (!empty($notified_reminder) ? $notified_reminder : null) ?></strong></a>
                    
                    <a class="nav-link <?= $url == 'filemanager' ? 'active' : '' ?>" href="#filemanager" data-bs-toggle="tab" aria-expanded="false"><?= lang('filemanager') ?></a>
                    
                    <a class="nav-link <?= $url == 'map' ? 'active' : '' ?>" href="<?= base_url() ?>admin/client/client_details/<?= $client_details->client_id ?>/map"><?= lang('map') ?></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
                    <!-- Task Details tab Starts -->

                    <div class="tab-pane <?= empty($url) ? 'active' : '' ?> " id="task_details" style="position: relative;">
                        <div class="card-body">
                            <div class="pull-right text-sm">
                                <?php
                                if ($client_details->leads_id != 0) {
                                    echo lang('converted_from')
                                    ?>
                                    <a href="<?= base_url() ?>admin/leads/leads_details/<?= $client_details->leads_id ?>"><?= lang('leads') ?></a>
                                <?php }
                                if (!empty($edited)) {
                                    ?>
                                    <a href="<?php echo base_url() ?>admin/client/manage_client/<?= $client_details->client_id ?>"
                                       class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> <?= lang('edit') ?></a>
                                <?php } ?>
                            </div>
                            <h4 class="card-title mb-4 mt"><?= $client_details->name ?> - <?= lang('details') ?> </h4>
                            <div class="row form-horizontal task_details">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <p class="lead bb"></p>
                                        <form class="form-horizontal p-20">
                                            <div class="row mb-3">
                                                <div class="col-md-4 col-4"><strong><?= lang('name') ?> :</strong></div>
                                                <div class="col-md-7 col-7"><?php echo $client_details->name; ?></div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-4 col-4"><strong><?= lang('contact_person') ?> :</strong></div>
                                                <div class="col-md-7 col-7">
                                                    <?php
                                                    if ($client_details->primary_contact != 0) {
                                                        $contacts = $client_details->primary_contact;
                                                    } else {
                                                        $contacts = NULL;
                                                    }
                                                    $primary_contact = $this->client_model->check_by(array('user_id' => $contacts), 'tbl_account_details');
                                                    if ($primary_contact) {
                                                        echo $primary_contact->fullname;
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-4 col-4"><strong><?= lang('email') ?></strong></div>
                                                <div class="col-md-7 col-7"><?= $client_details->email ?></div>
                                            </div>
                                
                                            <div class="row mb-3">
                                                <div class="col-md-4 col-4"><strong><?= lang('city') ?></strong></div>
                                                <div class="col-md-7 col-7"><?= $client_details->city ?></div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4 col-4"><strong><?= lang('zipcode') ?></strong></div>
                                                <div class="col-md-7 col-7"><?= $client_details->zipcode ?></div>
                                            </div>
                                            <?php $show_custom_fields = custom_form_label(12, $client_details->client_id);

                                            if (!empty($show_custom_fields)) {
                                                foreach ($show_custom_fields as $c_label => $v_fields) {
                                                    if (!empty($v_fields)) {
                                                    ?>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 col-4"><strong><?= $c_label ?></strong></div>
                                                    <div class="col-md-7 col-7"><?= $v_fields ?></div>
                                                </div>
                                            <?php } } } ?>

                                            <?php if (!empty($client_details->website)) { ?>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 col-4"><strong><?= lang('website') ?></strong></div>
                                                    <div class="col-md-7 col-7"><?= $client_details->website ?></div>
                                                </div>
                                            <?php } ?>
                                            <?php if (!empty($client_details->skype_id)) { ?>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 col-4"><strong><?= lang('skype_id') ?></strong></div>
                                                    <div class="col-md-7 col-7"><?= $client_details->skype_id ?></div>
                                                </div>
                                            <?php } ?>
                                            <?php if (!empty($client_details->facebook)) { ?>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 col-4"><strong><?= lang('facebook_profile_link') ?></strong></div>
                                                    <div class="col-md-7 col-7"><?= $client_details->facebook ?></div>
                                                </div>
                                            <?php } ?>
                                            <?php if (!empty($client_details->twitter)) { ?>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 col-4"><strong><?= lang('twitter_profile_link') ?></strong></div>
                                                    <div class="col-md-7 col-7"><?= $client_details->twitter ?></div>
                                                </div>
                                            <?php } ?>
                                            <?php if (!empty($client_details->linkedin)) { ?>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 col-4"><strong><?= lang('linkedin_profile_link') ?></strong></div>
                                                    <div class="col-md-7 col-7"><?= $client_details->linkedin ?></div>
                                                </div>
                                            <?php } ?>
                                        </form>
                                    </div>
                                    <div class="col-lg-6 rec-pay">
                                        <p class="lead bb"></p>
                                        <form class="form-horizontal p-20">
                                            <div class="row mb-3">
                                                <div class="col-md-4 col-4"><strong><?= lang('address') ?></strong></div>
                                                <div class="col-md-7 col-7"><?= $client_details->address ?></div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4 col-4"><strong><?= lang('phone') ?></strong></div>
                                                <div class="col-md-7 col-7"><a href="tel:<?= $client_details->phone ?>"><?= $client_details->phone ?></a>
                                                </div>
                                            </div>
                                            <?php if (!empty($client_details->mobile)) { ?>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 col-4"><strong><?= lang('mobile') ?></strong></div>
                                                    <div class="col-md-7 col-7"><a href="tel:<?= $client_details->mobile ?>"><?= $client_details->mobile ?></a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="row mb-3">
                                                <div class="col-md-4 col-4"><strong><?= lang('fax') ?></strong></div>
                                                <div class="col-md-7 col-7"><?= $client_details->fax ?>
                                                </div>
                                            </div>

                                            <?php if (!empty($client_details->hosting_company)) { ?>
                                            <div class="row mb-3">
                                                <div class="col-md-4 col-4"><strong><?= lang('hosting_company') ?></strong></div>
                                                <div class="col-md-7 col-7"><?= $client_details->hosting_company ?></div>
                                            </div>
                                            <?php } ?>
                                            <?php if (!empty($client_details->hostname)) { ?>
                                            <div class="row mb-3">
                                                <div class="col-md-4 col-4"><strong><?= lang('hostname') ?></strong></div>
                                                <div class="col-md-7 col-7"><?= $client_details->hostname ?></div>
                                            </div>
                                            <?php } ?>
                                            <?php if (!empty($client_details->username)) { ?>
                                            <div class="row mb-3">
                                                <div class="col-md-4 col-4"><strong><?= lang('username') ?></strong></div>
                                                <div class="col-md-7 col-7"><?= $client_details->username ?></div>
                                            </div>
                                            <?php } ?>
                                            <?php if (!empty($client_details->password)) {
                                                    $hosting_password = strlen(decrypt($client_details->password));
                                                    ?>
                                            <div class="row mb-3">
                                                <div class="col-md-4 col-4"><strong><?= lang('password') ?></strong></div>
                                                <div class="col-md-7 col-7">
                                                    <span id="show_password">
                                                    <?php
                                                    if (!empty($hosting_password)) {
                                                        for ($p = 1; $p <= $hosting_password; $p++) {
                                                            echo '*';
                                                        }
                                                    } ?>
                                                    </span>
                                                    <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                       href="<?= base_url('admin/client/see_password/c_' . $client_details->client_id) ?>"
                                                       id="see_password" class="ml"><?= lang('see_password') ?></a>
                                                    <strong id="hosting_password" class="required"></strong>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <?php if (!empty($client_details->port)) { ?>
                                            <div class="row mb-3">
                                                <div class="col-md-4 col-4"><strong><?= lang('port') ?></strong></div>
                                                <div class="col-md-7 col-7"><?= $client_details->port ?></div>
                                            </div>
                                            <?php } ?>
                                        
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                            
                                        <div class="text-center block mt">
                                            <h4 class="subdiv text-muted"><?= lang('received_amount') ?></h4>
                                            <h3 class="amount text-danger cursor-pointer"><strong>
                                                    <?php
                                                    $get_curency = $this->client_model->check_by(array('client_id' => $client_details->client_id), 'tbl_client');
                                                    $curency = $this->client_model->check_by(array('code' => $get_curency->currency), 'tbl_currencies');
                                                    ?><?= display_money($this->client_model->client_paid($client_details->client_id), $curency->symbol); ?>
                                                </strong></h3>
                                            <div style="display: inline-block">
                                                <div id="easypie3" data-percent="<?= $perc_paid ?>" class="easypie-chart">
                                                    <span class="h2"><?= $perc_paid ?>%</span>
                                                    <div class="easypie-text"><?= lang('paid') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Details END -->
                        <div class="card-footer bg-transparent border-top text-muted">
                            <span class="text-primary"><?= lang('invoice_amount') ?>: <strong class="badge badge-soft-primary"> <?= display_money($client_payable, $curency->symbol); ?></strong></span>
                            <span class="text-danger pull-right"><?= lang('outstanding') ?>: <strong class="badge badge-soft-danger"> <?= display_money($client_outstanding, $curency->symbol) ?></strong>
                            </span>
                        </div>
                    </div>

                    <!--            *************** contact tab start ************-->
                    <div class="tab-pane <?= $url == 'add_contacts' ? 'active' : '' ?>" id="contacts" style="position: relative;">
                        <div class="card-body">
                            <?php 
                            $edited = can_action('4', 'edited');

                            if (!empty($company)){ 

                            if (!empty($edited)) { ?>
                            <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form" action="<?php echo base_url(); ?>admin/client/save_contact/<?php if (!empty($account_details)) { echo $account_details->user_id;} ?>" method="post" class="form-horizontal">
                                <div class="pull-right text-sm">
                                    <a href="<?= base_url() ?>admin/client/client_details/<?= $client_details->client_id ?>" class="btn btn-primary btn-sm pull-right">Return to Details</a>
                                </div>
                                <h4 class="card-title mb-4 mt"><?= lang('add_contact') ?></h4>
                                
                                <div class="row">               
                                    <div class="col-xl-7">
                                        <input type="hidden" name="r_url"
                                               value="<?= base_url() ?>admin/client/client_details/<?= $company ?>">
                                        <input type="hidden" name="company" value="<?= $company ?>">
                                        <input type="hidden" name="role_id" value="2">
                                        <input type="hidden" name="companies_id"
                                               value="<?= $client_details->companies_id ?>">
                                        <input type="hidden" id="user_id" value="<?php
                                        if (!empty($account_details)) {
                                            echo $account_details->user_id;
                                        }
                                        ?>">
                                        <div class="row mb-3">
                                            <label class="col-xl-4 col-form-label"><?= lang('full_name') ?> <span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-xl-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($account_details)) {
                                                    echo $account_details->fullname;
                                                }
                                                ?>" placeholder="E.g John Doe" name="fullname" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-xl-4 col-form-label"><?= lang('email') ?><span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-xl-7">
                                                <input class="form-control" id="check_email_addrees" type="email"
                                                       value="<?php
                                                       if (!empty($user_info)) {
                                                           echo $user_info->email;
                                                       }
                                                       ?>" placeholder="me@domin.com" name="email" required>
                                                <span id="email_addrees_error" class="required"></span>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-xl-4 col-form-label"><?= lang('phone') ?> </label>
                                            <div class="col-xl-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($account_details)) {
                                                    echo $account_details->phone;
                                                }
                                                ?>" name="phone" placeholder="+52 782 983 434">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-xl-4 col-form-label"><?= lang('mobile') ?> <span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-xl-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($account_details)) {
                                                    echo $account_details->mobile;
                                                }
                                                ?>" name="mobile" placeholder="+8801723611125">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-xl-4 col-form-label"><?= lang('skype_id') ?> </label>
                                            <div class="col-xl-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($account_details)) {
                                                    echo $account_details->skype;
                                                }
                                                ?>" name="skype" placeholder="john">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-xl-4 col-form-label"><?= lang('language') ?></label>
                                            <div class="col-xl-7">
                                                <select name="language" class="form-control select_box">
                                                    <?php foreach ($languages as $lang) : ?>
                                                        <option value="<?= $lang->name ?>"<?php
                                                        if (!empty($account_details->language) && $account_details->language == $lang->name) {
                                                            echo 'selected="selected"';
                                                        } else {
                                                            echo($this->config->item('language') == $lang->name ? ' selected="selected"' : '');
                                                        }
                                                        ?>><?= ucfirst($lang->name) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-xl-4 col-form-label"><?= lang('locale') ?></label>
                                            <div class="col-xl-7">
                                                <select class="form-control select_box" name="locale">
                                                    <?php foreach ($locales as $loc) : ?>
                                                        <option lang="<?= $loc->code ?>"
                                                                value="<?= $loc->locale ?>"<?= ($this->config->item('locale') == $loc->locale ? ' selected="selected"' : '') ?>><?= $loc->name ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php

                                        if (!empty($account_details->direction)) {
                                            $direction = $account_details->direction;
                                        } else {
                                            $RTL = config_item('RTL');
                                            if (!empty($RTL)) {
                                                $direction = 'rtl';
                                            }
                                        }
                                        ?>
                                        <div class="row mb-3">
                                            <label for="direction"
                                                   class="control-label col-sm-4"><?= lang('direction') ?></label>
                                            <div class="col-sm-7">
                                                <select name="direction" class="form-control select_box" data-width="100%">
                                                    <option <?php
                                                    if (!empty($direction)) {
                                                        echo $direction == 'ltr' ? 'selected' : '';
                                                    }
                                                    ?> value="ltr"><?= lang('ltr') ?></option>
                                                    <option <?php
                                                    if (!empty($direction)) {
                                                        echo $direction == 'rtl' ? 'selected' : '';
                                                    }
                                                    ?> value="rtl"><?= lang('rtl') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <?php if (empty($account_details)): ?>
                                            <div class="row mb-3">
                                                <label class="col-xl-4 col-form-label"><?= lang('username') ?> <span
                                                            class="text-danger">*</span></label>
                                                <div class="col-xl-7">
                                                    <input class="form-control" id="check_username" type="text"
                                                           value="<?= set_value('username') ?>" placeholder="johndoe"
                                                           name="username" required>
                                                    <div class="required" id="check_username_error"></div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-xl-4 col-form-label"><?= lang('password') ?> <span
                                                            class="text-danger"> *</span></label>
                                                <div class="col-xl-7">
                                                    <input type="password" class="form-control" id="new_password"
                                                           value="<?= set_value('password') ?>" name="password"
                                                           required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-xl-4 col-form-label"><?= lang('confirm_password') ?>
                                                    <span
                                                            class="text-danger"> *</span></label>
                                                <div class="col-xl-7">
                                                    <input type="password" class="form-control"
                                                           data-parsley-equalto="#new_password"
                                                           value="<?= set_value('confirm_password') ?>"
                                                           name="confirm_password" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-xl-4 col-form-label"><?= lang('send_email') . ' ' . lang('password') ?></label>
                                                <div class="col-lg-6">
                                                    <div class="form-check form-check-primary mb-3">
                                                        <input type="checkbox" name="send_email_password" class="form-check-input">
                                                        <label class="form-check-label"> </label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-xl-5">
                                        <p class=""><strong><?= lang('permission') ?></strong></p>
                                        <hr>
                                        <?php
                                        $all_client_menu = $this->db->where(array('parent' => 0, 'companies_id' => $client_details->companies_id))->order_by('sort')->get('tbl_client_menu')->result();
                                        if (!empty($user_info)) {
                                            $user_menu = $this->db->where('user_id', $user_info->user_id)->get('tbl_client_role')->result();
                                        }
                                        foreach ($all_client_menu as $key => $v_menu) {
                                            ?>
                                            <div class="row mb-3">
                                                <label  class="col-xl-7 col-form-label"><?= lang($v_menu->label) ?></label>
                                                <div class="col-xl-3">
                                                    <div class="form-check form-switch mb-3">                    
                                                        <input type="checkbox" name="<?= $v_menu->label ?>" value="<?= $v_menu->menu_id ?>" <?php
                                                            if (!empty($user_menu)) {
                                                                foreach ($user_menu as $v_u_menu) {
                                                                    if ($v_u_menu->menu_id == $v_menu->menu_id) {
                                                                        echo 'checked';
                                                                    }
                                                                }
                                                            } ?> class="form-check-input" >
                                                        <label class="form-check-label" for="billable"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-xl-3 col-form-label"></label>
                                    <div class="col-lg-4">
                                        <button type="submit" id="new_uses_btn"
                                                class="btn btn-primary btn-block"><?= lang('save') . ' ' . lang('client_contact') ?></button>
                                    </div>
                                </div>
                            </form>
                            <?php } } else{ ?>
                            <div class="pull-right text-sm">
                                <?php
                                if (!empty($edited)) { ?>
                                    <a href="<?= base_url() ?>admin/client/client_details/<?= $client_details->client_id ?>/add_contacts" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?= lang('add_contact') ?></a>
                                <?php } ?>
                            </div>
                            <h4 class="card-title mb-4 mt"><?= lang('contacts') ?></h4>
                            <div class="table-responsive">
                                <table class="table table-striped dt-responsive nowrap w-100" id="list_contacts_dtable">
                                    <thead>
                                    <tr>
                                        <th><?= lang('full_name') ?></th>
                                        <th><?= lang('email') ?></th>
                                        <th><?= lang('phone') ?> </th>
                                        <th><?= lang('mobile') ?> </th>
                                        <th><?= lang('skype_id') ?></th>
                                        <th class="col-date"><?= lang('last_login') ?> </th>
                                        <th><?= lang('action') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($client_contacts)) {
                                        foreach ($client_contacts as $key => $contact) {
                                            ?>
                                            <tr>
                                                <td><?= $contact->fullname ?></td>
                                                <td class="text-info"><?= $contact->email ?> </td>
                                                <td><a href="tel:<?= $contact->phone ?>"><?= $contact->phone ?></a></td>
                                                <td><a href="tel:<?= $contact->mobile ?>"><?= $contact->mobile ?></a></td>
                                                <td><a href="skype:<?= $contact->skype ?>?call"><?= $contact->skype ?></a>
                                                </td>
                                                <?php
                                                if ($contact->last_login == '0000-00-00 00:00:00') {
                                                    $login_time = "-";
                                                } else {
                                                    $login_time = display_datetime($contact->last_login);
                                                } ?>
                                                <td><?= $login_time ?> </td>
                                                <td>
                                                    <a href="<?= base_url() ?>admin/client/make_primary/<?= $contact->user_id ?>/<?= $client_details->client_id ?>"
                                                       data-bs-toggle="tooltip" class="btn <?php
                                                    if ($client_details->primary_contact == $contact->user_id) {
                                                        echo "btn-outline-success";
                                                    } else {
                                                        echo "btn-outline-secondary";
                                                    }
                                                    ?> btn-sm" title="<?= lang('primary_contact') ?>">
                                                        <i class="fa fa-chain"></i> </a>
                                                    <a href="<?= base_url() ?>admin/client/client_details/<?= $client_details->client_id . '/add_contacts/' . $contact->user_id ?>"
                                                       class="btn btn-outline-primary btn-sm" title="<?= lang('edit') ?>">
                                                        <i class="fa fa-edit"></i> </a>
                                                    <a href="<?= base_url() ?>admin/client/delete_contacts/<?= $client_details->client_id . '/' . $contact->user_id ?>"
                                                       class="btn btn-outline-danger btn-sm" title="<?= lang('delete') ?>">
                                                        <i class="fa fa-trash-o"></i> </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="tab-pane <?= $url == 'notes' ? 'active' : '' ?>" id="notes" style="position: relative;">
                        <div class="card-body">
                            <div class="pull-right text-sm">
                                <button id="new_notes" class="btn btn-primary btn-sm"><?= lang('new') . ' ' . lang('notes') ?></button>
                            </div>
                            <h4 class="card-title mb-4 mt"><?= lang('notes') ?></h4>
                           
                            <?php
                            if ($url == 'notes') {
                                $notes_id = $this->uri->segment(6);
                                $notes_info = $this->db->where('notes_id', $notes_id)->get('tbl_notes')->row();
                            } else {
                                $notes_id = null;
                            }
                            ?>
                            <div class="row new_notes mb" style="display: <?= !empty($notes_info) ? 'block' : 'none' ?>">
                                <form action="<?php echo base_url() ?>admin/client/new_notes/<?= $notes_id ?>" method="post"  class="form-horizontal">
                                    <div class="row mb-3">
                                        <div class="col-xl-12">
                                            <textarea name="notes" class="form-control textarea-md"><?php if (!empty($notes_info)) {
                                            echo $notes_info->notes;  } ?></textarea>
                                            <input type="hidden" name="client_id" value="<?= $client_details->client_id ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-xl-2">
                                            <button class="btn btn-primary pull-left" type="submit"><?= lang('save') ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="clearfix"></div>
                            <script>
                                $(document).ready(function () {
                                    $('#new_notes').click(function () {
                                        $('.new_notes').toggle('slow');
                                    });
                                });
                            </script>
                            <div class="table-responsive">
                                <table class="table table-striped dt-responsive nowrap w-100 " id="client_notes_list_dtable">
                                    <thead>
                                    <tr>
                                        <th><?= lang('description') ?></th>
                                        <th><?= lang('added_by') ?></th>
                                        <th class="col-sm-2"><?= lang('date') ?> </th>
                                        <th class="col-sm-1"><?= lang('action') ?> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($client_notes)) {
                                        foreach ($client_notes as $v_notes) {
                                            $n_user = $this->db->where('user_id', $v_notes->added_by)->get('tbl_users')->row();
                                            if (empty($n_user)) {
                                                $n_user->fullname = '-';
                                                $n_url = '#';
                                            } else {
                                                $n_url = base_url() . 'admin/user/user_details/' . $n_user->user_id;
                                            }
                                            ?>
                                            <tr>
                                                <td><a class="text-info"
                                                       href="<?= base_url() ?>admin/client/client_details/<?= $client_details->client_id . '/notes/' . $v_notes->notes_id ?>"><?= $v_notes->notes ?></a>
                                                </td>
                                                <td>

                                                    <a href="<?= $n_url ?>"> <?= $n_user->username ?></a>
                                                </td>
                                                <td><?= display_datetime($v_notes->added_date); ?> </td>
                                                <td>
                                                    <?= btn_edit('admin/client/client_details/' . $client_details->client_id . '/notes/' . $v_notes->notes_id) ?>
                                                    <?php echo btn_delete('admin/client/delete_notes/' . $v_notes->notes_id . '/' . $client_details->client_id); ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- *************** invoice tab start ************-->

                    <div class="tab-pane <?= $url == 'invoice' ? 'active' : '' ?>" id="invoices">
                        <div class="card-body">
                            <div class="pull-right text-sm">
                                <?php
                                $in_created = can_action('13', 'created');
                                $in_edited = can_action('13', 'edited');
                                if (!empty($in_created) || !empty($in_edited)) {
                                    ?>
                                <a href="<?= base_url() ?>admin/invoice/manage_invoice/create_invoice/c_<?= $client_details->client_id ?>" class="btn btn-primary btn-sm"><?= lang('new_invoice') ?></a>
                                <?php } ?>
                                <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/invoice/zipped/invoice/<?= $client_details->client_id ?>" class="btn btn-success btn-sm"><?= lang('zip_invoice') ?></a>
                            </div>
                            <h4 class="card-title mb-4 mt"><?= lang('invoices') ?></h4>
                            
                            <div class="table-responsive">
                                <table class="table table-striped dt-responsive nowrap w-100" id="client_invoices_dtable">
                                    <thead>
                                    <tr>
                                        <th><?= lang('reference_no') ?></th>
                                        <th><?= lang('date_issued') ?></th>
                                        <th><?= lang('due_date') ?> </th>
                                        <th class="col-currency"><?= lang('amount') ?> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    setlocale(LC_ALL, config_item('locale') . ".UTF-8");
                                    $total_invoice = 0;
                                    if (!empty($client_invoices)) {
                                        foreach ($client_invoices as $key => $invoice) {
                                            $total_invoice += $this->invoice_model->invoice_payable($invoice->invoices_id);
                                            ?>
                                            <tr>
                                                <td><a class="text-info"
                                                       href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $invoice->invoices_id ?>"><?= $invoice->reference_no ?></a>
                                                </td>
                                                <td><?= display_datetime($invoice->date_saved); ?> </td>
                                                <td><?= display_datetime($invoice->due_date); ?> </td>
                                                <td>
                                                    <?= display_money($this->invoice_model->invoice_payable($invoice->invoices_id), $cur); ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top text-muted">
                            <strong><?= lang('invoice') . ' ' . lang('amount') ?>:</strong> <strong class="badge badge-soft-success">
                                <?php
                                echo display_money($total_invoice, $cur);
                                ?>
                            </strong>
                        </div>
                    </div>
                    <!--            *************** invoice tab start ************-->
                    <div class="tab-pane <?= $url == 'payment' ? 'active' : '' ?>" id="payments" style="position: relative;">
                        <div class="card-body">
                            <div class="pull-right text-sm">
                                <a data-bs-toggle="modal" data-bs-target="#myModal"   href="<?= base_url() ?>admin/invoice/zipped/payment/<?= $client_details->client_id ?>" class="btn btn-success btn-sm"><?= lang('zip_payment') ?></a>
                            </div>
                            <h4 class="card-title mb-4 mt"><?= lang('payments') ?></h4>

                            <div class="table-responsive">
                                <table class="table table-striped dt-responsive nowrap w-100" id="client_payments_dtable">
                                    <thead>
                                    <tr>
                                        <th><?= lang('payment_date') ?></th>
                                        <th><?= lang('invoice_date') ?></th>
                                        <th><?= lang('invoice') ?></th>
                                        <th><?= lang('amount') ?></th>
                                        <th><?= lang('payment_method') ?></th>
                                        <th><?= lang('action') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $total_amount = 0;
                                    if (!empty($recently_paid)) {
                                        foreach ($recently_paid as $key => $v_paid) {
                                            $invoice_info = $this->db->where(array('invoices_id' => $v_paid->invoices_id))->get('tbl_invoices')->row();
                                            $payment_method = $this->db->where(array('payment_methods_id' => $v_paid->payment_method))->get('tbl_payment_methods')->row();

                                            if ($v_paid->payment_method == '1') {
                                                $label = 'success';
                                            } elseif ($v_paid->payment_method == '2') {
                                                $label = 'danger';
                                            } else {
                                                $label = 'dark';
                                            }
                                            $total_amount += $v_paid->amount;
                                            ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= base_url() ?>admin/invoice/manage_invoice/payments_details/<?= $v_paid->payments_id ?>"> <?= display_datetime($v_paid->payment_date); ?></a>
                                                </td>
                                                <td><?= display_datetime($invoice_info->date_saved) ?></td>
                                                <td><a class="text-info"
                                                       href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_paid->invoices_id ?>"><?= $invoice_info->reference_no; ?></a>
                                                </td>
                                                <?php $currency = $this->invoice_model->client_currency_sambol($invoice_info->client_id); ?>
                                                <td><?= display_money($v_paid->amount, $currency->symbol) ?></td>
                                                <td><span
                                                            class="badge badge-soft-<?= $label ?>"><?= !empty($payment_method->method_name) ? $payment_method->method_name : '-'; ?></span>
                                                </td>
                                                <td>
                                                    <?= btn_edit('admin/invoice/all_payments/' . $v_paid->payments_id) ?>
                                                    <?= btn_view('admin/invoice/manage_invoice/payments_details/' . $v_paid->payments_id) ?>
                                                    <?= btn_delete('admin/invoice/delete/delete_payment/' . $v_paid->payments_id) ?>
                                                    <a data-bs-toggle="tooltip" data-bs-placement="top"
                                                       href="<?= base_url() ?>admin/invoice/send_payment/<?= $v_paid->payments_id . '/' . $v_paid->amount ?>"
                                                       title="<?= lang('send_email') ?>"
                                                       class="btn btn-sm btn-outline-success">
                                                        <i class="fa fa-envelope"></i> </a>
                                                </td>
                                            </tr>

                                            <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top text-muted">
                            <strong><?= lang('paid_amount') ?>:</strong> <strong class="badge badge-soft-success">
                                <?= display_money($total_amount, $cur); ?>
                            </strong>
                        </div>
                    </div>

                    <!--            *************** invoice tab start ************-->
                    <div class="tab-pane <?= $url == 'estimate' ? 'active' : '' ?>" id="estimates" style="position: relative;">
                        <div class="card-body">
                            <div class="pull-right text-sm">
                                <?php
                                $es_created = can_action('14', 'created');
                                $es_edited = can_action('14', 'edited');
                                if (!empty($es_created) || !empty($es_edited)) {
                                    ?>
                                    <a href="<?= base_url() ?>admin/estimates/index/edit_estimates/c_<?= $client_details->client_id ?>"
                                       class="btn btn-primary btn-sm"><?= lang('new_estimate') ?></a>
                                <?php } ?>
                                <a data-bs-toggle="modal" data-bs-target="#myModal"
                                   href="<?= base_url() ?>admin/invoice/zipped/estimate/<?= $client_details->client_id ?>"
                                   class="btn btn-success btn-sm"><?= lang('zip_estimate') ?></a>
                            </div>
                            <h4 class="card-title mb-4 mt"><?= lang('estimates') ?></h4>

                            <div class="table-responsive">
                                <table class="table table-striped dt-responsive nowrap w-100" id="client_estimate_dtable">
                                    <thead>
                                    <tr>
                                        <th><?= lang('reference_no') ?></th>
                                        <th><?= lang('date_issued') ?></th>
                                        <th><?= lang('due_date') ?> </th>
                                        <th class="col-currency"><?= lang('amount') ?> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    setlocale(LC_ALL, config_item('locale') . ".UTF-8");
                                    $total_estimate = 0;
                                    if (!empty($client_estimates)) {
                                        foreach ($client_estimates as $key => $estimate) {
                                            $total_estimate += $this->estimates_model->estimate_calculation('estimate_amount', $estimate->estimates_id);
                                            ?>
                                            <tr>
                                                <td><a class="text-info"
                                                       href="<?= base_url() ?>admin/estimates/index/estimates_details//<?= $estimate->estimates_id ?>"><?= $estimate->reference_no ?></a>
                                                </td>
                                                <td><?= display_datetime($estimate->date_saved); ?> </td>
                                                <td><?= display_datetime($estimate->due_date); ?> </td>
                                                <td>
                                                    <?php echo display_money($this->estimates_model->estimate_calculation('estimate_amount', $estimate->estimates_id), $cur); ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top text-muted">
                            <strong><?= lang('estimate') . ' ' . lang('amount') ?>:</strong> <strong
                                    class="badge badge-soft-success">
                                <?= display_money($total_estimate, $cur); ?>
                            </strong>
                        </div>
                    </div>
                    <div class="tab-pane <?= $url == 'proposal' ? 'active' : '' ?>" id="proposals" style="position: relative;">
                        <div class="card-body">
                            <div class="pull-right text-sm">
                                <?php
                                $prop_created = can_action('140', 'created');
                                $prop_edited = can_action('140', 'edited');
                                if (!empty($prop_created) || !empty($prop_edited)) {
                                    ?>
                                    <a href="<?= base_url() ?>admin/proposals/index/client/<?= $client_details->client_id ?>"
                                       class="btn btn-secondary btn-sm"><?= lang('create_proposal') ?></a>
                                <?php } ?>
                                <a data-bs-toggle="modal" data-bs-target="#myModal"
                                   href="<?= base_url() ?>admin/invoice/zipped/proposal/<?= $client_details->client_id ?>"
                                   class="btn btn-success btn-sm"><?= lang('zip_proposal') ?></a>
                            </div>
                            <h4 class="card-title mb-4 mt"><?= lang('all_proposals') ?></h4>

                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <table class="table table-striped dt-responsive nowrap w-100 " id="client_all_proposals_dtable">
                                        <thead>
                                        <tr>
                                            <th><?= lang('proposal') ?> #</th>
                                            <th><?= lang('proposal_date') ?></th>
                                            <th><?= lang('expire_date') ?></th>
                                            <th><?= lang('status') ?></th>
                                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                <th class="hidden-print"><?= lang('action') ?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        if (!empty($all_proposals_info)) {
                                            foreach ($all_proposals_info as $v_proposals) {
                                                $can_edit = $this->client_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $v_proposals->proposals_id));
                                                $can_delete = $this->client_model->can_action('tbl_proposals', 'delete', array('proposals_id' => $v_proposals->proposals_id));

                                                if ($v_proposals->status == 'pending') {
                                                    $label = "info";
                                                } elseif ($v_proposals->status == 'accepted') {
                                                    $label = "success";
                                                } else {
                                                    $label = "danger";
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <a class="text-info"
                                                           href="<?= base_url() ?>admin/proposals/index/proposals_details/<?= $v_proposals->proposals_id ?>"><?= $v_proposals->reference_no ?></a>
                                                        <?php if ($v_proposals->convert == 'Yes') {
                                                            if ($v_proposals->convert_module == 'invoice') {
                                                                $c_url = base_url() . 'admin/invoice/manage_invoice/invoice_details/' . $v_proposals->convert_module_id;
                                                                $text = lang('invoiced');
                                                            } else {
                                                                $text = lang('estimated');
                                                                $c_url = base_url() . 'admin/estimates/index/estimates_details/' . $v_proposals->convert_module_id;
                                                            }
                                                            if (!empty($c_url)) { ?>
                                                                <p class="text-sm m0 p0">
                                                                    <a class="text-success"
                                                                       href="<?= $c_url ?>">
                                                                        <?= $text ?>
                                                                    </a>
                                                                </p>
                                                            <?php }
                                                        } ?>
                                                    </td>
                                                    <td><?= display_datetime($v_proposals->proposal_date) ?></td>
                                                    <td><?= display_datetime($v_proposals->due_date) ?>
                                                        <?php
                                                        if (strtotime($v_proposals->due_date) < time() AND $v_proposals->status == 'pending' || strtotime($v_proposals->due_date) < time() AND $v_proposals->status == ('draft')) { ?>
                                                            <span
                                                                    class="badge badge-soft-danger "><?= lang('expired') ?></span>
                                                        <?php }
                                                        ?>
                                                    </td>
                                                    <?php ?>
                                                    <td><span
                                                                class="badge badge-soft-<?= $label ?>"><?= lang($v_proposals->status) ?></span>
                                                    </td>
                                                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                    <td>
                                                        <div class="dropdown">
                                                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                            <?= btn_edit('admin/proposals/index/edit_proposals/' . $v_proposals->proposals_id) ?>

                                                        <?php }
                                                        if (!empty($can_delete) && !empty($deleted)) {
                                                            ?>
                                                            <?= btn_delete('admin/proposals/delete/delete_proposals/' . $v_proposals->proposals_id) ?>
                                                        <?php } ?>
                                                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                            <a data-bs-toggle="tooltip" data-bs-placement="top"  href="<?= base_url() ?>admin/proposals/index/email_proposals/<?= $v_proposals->proposals_id ?>" title="<?= lang('send_email') ?>" class="btn btn-sm btn-outline-success"><i class="fa fa-envelope"></i></a>
                                                            
                                                            <a data-bs-toggle="tooltip" data-bs-placement="top" href="<?= base_url() ?>admin/proposals/index/proposals_details/<?= $v_proposals->proposals_id ?>" title="<?= lang('view_details') ?>" class="btn btn-sm btn-outline-primary"><i class="fa fa-list-alt"></i></a>

                                                            <a data-bs-toggle="tooltip" data-bs-placement="top" href="<?= base_url() ?>admin/proposals/index/proposals_history/<?= $v_proposals->proposals_id ?>" title="<?= lang('history') ?>" class="btn btn-sm btn-outline-secondary"><i class="fa fa-building"></i></a>

                                                            <button class="btn btn-outline-primary dropdown-toggle btn-sm" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_status') ?><i class="mdi mdi-chevron-down"></i></button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a class="dropdown-item"  href="<?= base_url() ?>admin/proposals/change_status/declined/<?= $v_proposals->proposals_id ?>"><?= lang('declined') ?></a>
                                                                <a class="dropdown-item"  href="<?= base_url() ?>admin/proposals/change_status/accepted/<?= $v_proposals->proposals_id ?>"><?= lang('accepted') ?></a>
                                                            </div>
                                                        <?php } ?>
                                                        </div>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--            *************** Transactions tab start ************-->
                    <div class="tab-pane" id="transaction" style="position: relative;">
                        <div class="card-body">
                            <h4 class="card-title mb-4 mt"><?= lang('transactions') ?></h4>
                               
                            <div class="table-responsive">
                                <table class="table table-striped dt-responsive nowrap w-100" id="client_transactions_dtable">
                                    <thead>
                                    <tr>
                                        <th><?= lang('date') ?></th>
                                        <th><?= lang('account') ?></th>
                                        <th><?= lang('type') ?> </th>
                                        <th><?= lang('amount') ?> </th>
                                        <th><?= lang('action') ?> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $total_income = 0;
                                    $total_expense = 0;
                                    $curency = $this->client_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                    if (!empty($client_transactions)):foreach ($client_transactions as $v_transactions) :
                                        $account_info = $this->client_model->check_by(array('account_id' => $v_transactions->account_id), 'tbl_accounts');
                                        ?>
                                        <tr>
                                            <td><?= display_datetime($v_transactions->date); ?></td>
                                            <td><?= (!empty($account_info->account_name) ? $account_info->account_name : '-') ?></td>
                                            <td><?= $v_transactions->type ?></td>
                                            <td><?= display_money($v_transactions->amount, $curency->symbol); ?></td>
                                            <td>
                                                <?php

                                                if ($v_transactions->type == 'Income') {
                                                    $total_income += $v_transactions->amount;
                                                    ?>
                                                    <?= btn_edit('admin/transactions/deposit/' . $v_transactions->transactions_id) ?>
                                                    <?= btn_delete('admin/transactions/delete_deposit/' . $v_transactions->transactions_id) ?>
                                                    <?php
                                                } else {
                                                    $total_expense += $v_transactions->amount;
                                                    ?>
                                                    <?= btn_edit('admin/transactions/expense/' . $v_transactions->transactions_id) ?>
                                                    <?= btn_delete('admin/transactions/delete_expense/' . $v_transactions->transactions_id) ?>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php
                                    endforeach;
                                        ?>

                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top text-muted">
                            <small><strong><?= lang('total_income') ?>:</strong><strong
                                        class="badge badge-soft-success"><?= display_money($total_income, $curency->symbol); ?></strong>
                            </small>
                            <small class="text-danger pull-right">
                                <strong><?= lang('total_expense') ?>:</strong>
                                <strong class="badge badge-soft-danger"><?= display_money($total_expense, $curency->symbol); ?></strong>
                            </small>
                        </div>
                    </div>
                    <!--            *************** Project tab start ************-->
                    <div class="tab-pane" id="projects" style="position: relative;">
                        <div class="card-body">
                            <div class="pull-right text-sm">
                                <?php
                                $pro_created = can_action('57', 'created');
                                $pro_edited = can_action('57', 'edited');
                                if (!empty($pro_created) || !empty($pro_edited)) {
                                    ?>
                                    <a href="<?= base_url() ?>admin/projects/new_project/client_project/<?= $client_details->client_id ?>" class="btn btn-primary btn-sm pull-right"><?= lang('new_project') ?></a>
                                <?php } ?>
                            </div>
                            <h4 class="card-title mb-4 mt"><?= lang('project') ?></h4>

                            <div class="table-responsive">
                                <table class="table table-striped dt-responsive nowrap w-100 " id="client_projects_list_dtable">
                                    <thead>
                                    <tr>
                                        <th><?= lang('project_name') ?></th>
                                        <th><?= lang('end_date') ?></th>
                                        <th><?= lang('status') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($all_project)):foreach ($all_project as $v_project):
                                        ?>
                                        <tr>
                                            <td><a class="text-info"  href="<?= base_url() ?>admin/projects/project_details/<?= $v_project->project_id ?>"><?= $v_project->project_name ?></a>
                                                <?php if (time() > strtotime($v_project->end_date) AND $v_project->progress < 100) { ?>
                                                    <span class="badge badge-soft-danger pull-right"><?= lang('overdue') ?></span>
                                                <?php } ?>
                                                 
                                                 <div class="mt progress progress-xs progress-striped active" style="">
                                                    <div class="progress-bar bar-<?php echo ($v_project->progress >= 100) ? 'success' : 'primary'; ?>" role="progressbar" style="width: <?= $v_project->progress ?>%;" aria-valuenow="<?= $v_project->progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </td>
                                            <td><?= display_datetime($v_project->end_date) ?></td>

                                            <td><?php
                                                if (!empty($v_project->project_status)) {
                                                    if ($v_project->project_status == 'completed') {
                                                        $status = "<span class='badge badge-soft-success'>" . lang($v_project->project_status) . "</span>";
                                                    } elseif ($v_project->project_status == 'in_progress') {
                                                        $status = "<span class='badge badge-soft-primary'>" . lang($v_project->project_status) . "</span>";
                                                    } elseif ($v_project->project_status == 'cancel') {
                                                        $status = "<span class='badge badge-soft-danger'>" . lang($v_project->project_status) . "</span>";
                                                    } else {
                                                        $status = "<span class='badge badge-soft-warning'>" . lang($v_project->project_status) . "</span>";
                                                    }
                                                    echo $status;
                                                }
                                                ?> 
                                            </td>
                                        </tr>
                                    <?php
                                    endforeach;
                                    endif;
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--            *************** Tickets tab start ************-->
                    <?php

                    if (!empty($client_details->primary_contact)) {
                        $primary_contact = 'c_' . $client_details->primary_contact;
                    } else {
                        $primary_contact = null;
                    }
                    ?>
                    <div class="tab-pane" id="ticket" style="position: relative;">
                        <div class="card-body">
                            <div class="pull-right text-sm">
                                <?php
                                $pro_created = can_action('57', 'created');
                                $pro_edited = can_action('57', 'edited');
                                if (!empty($pro_created) || !empty($pro_edited)) {
                                    ?>
                                    <a href="<?= base_url() ?>admin/tickets/index/edit_tickets/<?= $primary_contact ?>" class="btn btn-primary btn-sm pull-right"><?= lang('new_ticket') ?></a>
                                <?php } ?>
                            </div>
                            <h4 class="card-title mb-4 mt"><?= lang('tickets') ?></h4>

                            <div class="table-responsive">
                                <table class="table table-striped dt-responsive nowrap w-100" id="client_ticket_dtable">
                                    <thead>
                                    <tr>
                                        <th><?= lang('subject') ?></th>
                                        <th class="col-date"><?= lang('date') ?></th>
                                        <?php if ($this->session->userdata('user_type') == '1') { ?>
                                            <th><?= lang('reporter') ?></th>
                                        <?php } ?>
                                        <th><?= lang('status') ?></th>
                                        <th><?= lang('action') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($all_tickets_info)) {
                                        foreach ($all_tickets_info as $v_tickets_info) {
                                            if ($v_tickets_info->reporter != 0) {
                                                $profile_info = $this->db->where(array('user_id' => $v_tickets_info->reporter))->get('tbl_account_details')->row();
                                                if ($profile_info->company == $client_details->client_id) {
                                                    if ($v_tickets_info->status == 'open') {
                                                        $s_label = 'danger';
                                                    } elseif ($v_tickets_info->status == 'closed') {
                                                        $s_label = 'success';
                                                    } else {
                                                        $s_label = 'default';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><a class="text-info"
                                                               href="<?= base_url() ?>admin/tickets/index/tickets_details/<?= $v_tickets_info->tickets_id ?>"><?= $v_tickets_info->subject ?></a>
                                                        </td>
                                                        <td><?= display_datetime($v_tickets_info->created); ?></td>
                                                        <?php if ($this->session->userdata('user_type') == '1') { ?>

                                                            <td>
                                                                <a class="pull-left recect_task  ">
                                                                    <?php if (!empty($profile_info)) {
                                                                        ?>
                                                                        <img style="width: 30px;margin-left: 18px;
                                                             height: 29px;
                                                             border: 1px solid #aaa;"
                                                                             src="<?= base_url() . $profile_info->avatar ?>"
                                                                             class="img-circle">
                                                                    <?php } ?>

                                                                    <?=
                                                                    ($profile_info->fullname)
                                                                    ?>
                                                                </a>
                                                            </td>

                                                        <?php } ?>
                                                        <?php
                                                        if ($v_tickets_info->status == 'in_progress') {
                                                            $status = 'In Progress';
                                                        } else {
                                                            $status = $v_tickets_info->status;
                                                        }
                                                        ?>
                                                        <td><span
                                                                    class="badge badge-soft-<?= $s_label ?>"><?= ucfirst($status) ?></span>
                                                        </td>
                                                        <td>
                                                            <?= btn_edit('admin/tickets/index/edit_tickets/' . $v_tickets_info->tickets_id) ?>
                                                            <?= btn_delete('admin/tickets/delete/delete_tickets/' . $v_tickets_info->tickets_id) ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--            *************** Bugs tab start ************-->
                    <div class="tab-pane" id="bugs" style="position: relative;">
                        <div class="card-body">
                            <div class="pull-right text-sm">
                                <?php
                                $bugs_created = can_action('58', 'created');
                                $bugs_edited = can_action('58', 'edited');
                                if (!empty($bugs_created) || !empty($bugs_edited)) {
                                    ?>
                                    <a href="<?= base_url() ?>admin/bugs/index/<?= $primary_contact ?>"
                                       class="btn btn-primary btn-sm pull-right"><?= lang('new_bugs') ?></a>
                                <?php } ?>
                            </div>
                            <h4 class="card-title mb-4 mt"><?= lang('bugs') ?></h4>

                            <div class="table-responsive">
                                <table class="table table-striped dt-responsive nowrap w-100" id="client_bugs_dtable">
                                    <thead>
                                    <tr>
                                        <th><?= lang('bug_title') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <th><?= lang('priority') ?></th>
                                        <?php if ($this->session->userdata('user_type') == '1') { ?>
                                            <th><?= lang('reporter') ?></th>
                                        <?php } ?>
                                        <th><?= lang('assigned_to') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($all_bug_info)) {
                                            foreach ($all_bug_info as $v_bugs) {
                                                $profile = $this->db->where(array('user_id' => $v_bugs->reporter))->get('tbl_account_details')->row();
                                                if ($profile->company == $client_details->client_id) {
                                                    $total_bugs += count($v_bugs->bug_id);
                                                    $reporter = $this->db->where('user_id', $v_bugs->reporter)->get('tbl_users')->row();
                                                    if ($reporter->role_id == '1') {
                                                        $badge = 'danger';
                                                    } elseif ($reporter->role_id == '2') {
                                                        $badge = 'info';
                                                    } else {
                                                        $badge = 'primary';
                                                    }

                                                    if ($v_bugs->bug_status == 'unconfirmed') {
                                                        $label = 'warning';
                                                    } elseif ($v_bugs->bug_status == 'confirmed') {
                                                        $label = 'info';
                                                    } elseif ($v_bugs->bug_status == 'in_progress') {
                                                        $label = 'primary';
                                                    } elseif ($v_bugs->bug_status == 'resolved') {
                                                        $label = 'purple';
                                                    } else {
                                                        $label = 'success';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><a class="text-info" style="<?php
                                                            if ($v_bugs->bug_status == 'resolve') {
                                                                echo 'text-decoration: line-through;';
                                                            }
                                                            ?>"
                                                               href="<?= base_url() ?>admin/bugs/view_bug_details/<?= $v_bugs->bug_id ?>"><?php echo $v_bugs->bug_title; ?></a>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-soft-<?= $label ?>"><?= lang("$v_bugs->bug_status") ?></span>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($v_bugs->priority == 'High') {
                                                                $plabel = 'danger';
                                                            } elseif ($v_bugs->priority == 'Medium') {
                                                                $plabel = 'info';
                                                            } else {
                                                                $plabel = 'primary';
                                                            }
                                                            ?>
                                                            <span class="badge badge-soft-<?= $plabel ?>"><?= ucfirst($v_bugs->priority) ?></span>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-soft-<?= $badge ?> "><?= $reporter->username ?></span>
                                                        </td>
                                                        <td>
                                                            <div class="avatar-group">
                                                            <?php

                                                            if ($v_bugs->permission != 'all') {
                                                                $get_permission = json_decode($v_bugs->permission);

                                                                if (!empty($get_permission)) :
                                                                    foreach ($get_permission as $permission => $v_permission) :
                                                                        $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                                                                        if ($user_info->role_id == 1) {
                                                                            $label = 'text-danger';
                                                                        } else {
                                                                            $label = 'text-success';
                                                                        }
                                                                        $profile_info = $this->db->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                                                        ?>
                                                                <div class="avatar-group-item">
                                                                    <a href="#" data-bs-toggle="tooltip"
                                                                       data-bs-placement="top"
                                                                       title="<?= $profile_info->fullname ?>"><img   src="<?= base_url() . $profile_info->avatar ?>"  class="rounded-circle avatar-xs" alt="">
                                                                        <span style="margin: 0px 0 8px -10px;"  class="mdi mdi-circle <?= $label ?> font-size-10"></span>  </a>
                                                                </div>
                                                                    <?php
                                                                    endforeach;
                                                                endif;
                                                            } else { ?>
                                                            <span class="mr-lg-2 mt-2">
                                                                <strong><?= lang('everyone') ?></strong>
                                                                <i  title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                                            </span>

                                                                <?php
                                                            }
                                                            ?>
                                                            <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                            <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?= lang('add_more') ?>" class="mt-2">
                                                                <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/bugs/update_users/<?= $v_bugs->bug_id ?>" class="text-default"><i class="fa fa-plus"></i></a>
                                                            </span>

                                                            <?php } ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--            *************** Bugs tab start ************-->
                    <div class="tab-pane <?= $url == 'reminder' ? 'active' : '' ?>" id="reminder">
                        <div class="card-body">
                            <!-- Tabs within a box -->
                            <ul class="nav nav-tabs bg-light rounded">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#reminder_manage" data-bs-toggle="tab"><?= lang('reminder') . ' ' . lang('list') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#reminder_create" data-bs-toggle="tab"><?= lang('set') . ' ' . lang('reminder') ?></a>
                                </li>
                            </ul>
                            <div class="tab-content p-3 text-muted">
                                <!-- ************** general *************-->
                                <div class="tab-pane active" id="reminder_manage">
                                    <h4 class="card-title mb-4 mt"><?= lang('reminder') . ' ' . lang('list') ?></h4>

                                    <div class="table-responsive">
                                        <table class="table table-striped dt-responsive nowrap w-100" id="client_reminder_dtable">
                                            <thead>
                                            <tr>
                                                <th><?= lang('description') ?></th>
                                                <th><?= lang('date') ?></th>
                                                <th><?= lang('remind') ?></th>
                                                <th><?= lang('notified') ?></th>
                                                <th class="col-options no-sort"><?= lang('action') ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $all_reminder = $this->db->where(array('module' => 'client', 'module_id' => $client_details->client_id))->get('tbl_reminders')->result();
                                                if (!empty($all_reminder)) {
                                                foreach ($all_reminder as $v_reminder):
                                                    $remind_user_info = $this->db->where('user_id', $v_reminder->user_id)->get('tbl_account_details')->row();
                                                    ?>
                                                    <tr>
                                                        <td><?= $v_reminder->description ?></td>
                                                        <td><?= display_datetime($v_reminder->date) ?></td>
                                                        <td>
                                                            <a href="<?= base_url() ?>admin/user/user_details/<?= $v_reminder->user_id ?>"> <?= $remind_user_info->fullname ?></a>
                                                        </td>
                                                        <td><?= $v_reminder->notified ?></td>
                                                        <td>
                                                            <?= btn_delete('admin/invoice/delete_reminder/' . $v_reminder->module . '/' . $v_reminder->module_id . '/' . $v_reminder->reminder_id); ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                endforeach;
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="reminder_create">
                                    <h4 class="card-title mb-4 mt"><?= lang('set') . ' ' . lang('reminder') ?></h4>
                                    
                                    <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data"
                                          id="form"
                                          action="<?php echo base_url(); ?>admin/invoice/reminder/client/<?= $client_details->client_id ?>/<?php
                                          if (!empty($reminder_info)) {
                                              echo $reminder_info->reminder_id;
                                          }
                                          ?>" method="post" class="form-horizontal  ">
                                        <div class="row mb-3">
                                            <label class="col-xl-3 col-form-label"><?= lang('date_to_notified') ?> <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-xl-5">
                                                 <div class="input-group">
                                                    <input type="text" required="" name="date" class="form-control datepicker" autocomplete="off" data-date-format="<?= config_item('date_picker_format'); ?>" value="<?php
                                                           if (!empty($reminder_info->date)) {
                                                               echo date('d-m-Y H:i', strtotime($reminder_info->date));
                                                           } else {
                                                               echo date('d-m-Y H-i');
                                                           }
                                                           ?>" data-date-min-date="<?= date('d-m-Y H-i'); ?>">
                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End discount Fields -->
                                        <div class="row mb-3 terms">
                                            <label class="col-xl-3 col-form-label"><?= lang('description') ?> </label>
                                            <div class="col-xl-5">
                                                <textarea name="description" class="form-control"><?php
                                                    if (!empty($reminder_info)) {
                                                        echo $reminder_info->description;
                                                    }
                                                    ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-xl-3 col-form-label"><?= lang('set_reminder_to') ?> <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-xl-5">
                                                <select class="form-control select_box" name="user_id" style="width: 100%">
                                                    <?php
                                                    $all_user = get_result('tbl_users', array('role_id !=' => 2));
                                                    foreach ($all_user as $v_users) {
                                                        $profile = $this->db->where('user_id', $v_users->user_id)->get('tbl_account_details')->row();
                                                        if (!empty($profile)) {
                                                            ?>
                                                            <option <?php
                                                            if (!empty($reminder_info)) {
                                                                echo $reminder_info->user_id == $v_users->user_id ? 'selected' : null;
                                                            }
                                                            ?> value="<?= $v_users->user_id ?>"><?= $profile->fullname ?></option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3 terms">
                                            <label class="col-xl-3 col-form-label"></label>
                                            <div class="col-xl-5">
                                                <div class="form-check form-check-primary mb-3">
                                                     <input type="checkbox" value="Yes"
                                                            <?php if (!empty($reminder_info) && $reminder_info->notify_by_email == 'Yes') {
                                                                echo 'checked';
                                                            } ?> name="notify_by_email" class="form-check-input" id="reminder_noti">
                                                    <label class="form-check-label" for="reminder_noti">
                                                        <?= lang('send_also_email_this_reminder') ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-xl-3 col-form-label"></label>
                                            <div class="col-xl-5">
                                                <button type="submit" class="btn btn-primary"><?= lang('update') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--            *************** invoice tab start ************-->
                    <div class="tab-pane <?= $url == 'filemanager' ? 'active' : '' ?>" id="filemanager">
                        <div class="card-body">
                            <h4 class="card-title mb-4 mt"><?= lang('filemanager') ?></h4>
                         
                            <link rel="stylesheet" type="text/css"
                                  href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css"/>
                            <script type="text/javascript"
                                    src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
                            <script type="text/javascript"
                                    src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
                            <link rel="stylesheet" type="text/css" media="screen"
                                  href="<?php echo site_url('assets/plugins/elFinder/css/elfinder.min.css'); ?>">
                            <link rel="stylesheet" type="text/css" media="screen"
                                  href="<?php echo site_url('assets/plugins/elFinder/themes/windows-10/css/theme.css'); ?>">

                            <script  src="<?php echo site_url('assets/plugins/elFinder/js/elfinder.min.js'); ?>"></script>
                            <script type="text/javascript" charset="utf-8">
                                $().ready(function () {
                                    window.setTimeout(function () {
                                        var elf = $('#elfinder').elfinder({
                                            // lang: 'ru',             // language (OPTIONAL)
                                            url: '<?= site_url()?>admin/client/elfinder_init/<?= $client_details->client_id?>',  // connector URL (REQUIRED)
                                            height: 600,
                                            uiOptions: {
                                                // toolbar configuration
                                                toolbar: [
                                                    ['back', 'forward'],
                                                    ['mkdir', 'mkfile', 'upload'],
                                                    ['open', 'download', 'getfile'],
                                                    ['info'],
                                                    ['quicklook'],
                                                    ['copy', 'cut', 'paste'],
                                                    ['rm'],
                                                    ['duplicate', 'rename', 'edit', 'resize'],
                                                    ['extract', 'archive'],
                                                    ['search'],
                                                    ['view'],
                                                ],
                                            }

                                        }).elfinder('instance');
                                    }, 200);
                                });
                            </script>
                            <div class="">
                                <div id="elfinder"></div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane <?= $url == 'map' ? 'active' : '' ?>" id="client_map">
                        <div class="card-body">
                            <h4 class="card-title mb-4 mt"><?= lang('map') ?></h4>
                                  
                            <style type="text/css">
                                .client_map {
                                    height: 500px;
                                }
                            </style>
                            <?php
                            $google_api_key = config_item('google_api_key');
                            if ($google_api_key !== '') {
                                if ($client_details->longitude == '' && $client_details->latitude == '') {
                                    echo lang('map_notice');
                                } else {
                                    echo '<div id="map" class="client_map"></div>';
                                } ?>
                                <script>
                                    var latitude = '<?= $client_details->latitude?>';
                                    var longitude = '<?= $client_details->longitude?>';
                                    var marker = '<?= $client_details->name?>';
                                </script>
                                <script src="<?= base_url() ?>assets/plugins/map/map.js"></script>
                                <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= $google_api_key ?>&callback=initMap"></script>

                            <?php } else {
                                echo lang('setup_google_api_key_map');
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

