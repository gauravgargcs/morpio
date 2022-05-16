<?php $url = $this->uri->segment(5); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18">  <?php echo $title; ?> 
            </h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="card">
        <div class="card-body">  
            <div class="float-end">
                <a class="btn btn-sm btn-danger" href="<?= base_url() ?>admin/companies"><?= lang('manage') . ' ' . lang('companies') ?></a>
            </div>
            <h4 class="card-title mb-4"> <?= company_name($company_info->companies_id) . ' ' . lang('details') ?> </h4>
            <!-- Details START -->
            <div class="row">
                <div class="col-xl-6">
                    <div class="card border card-body">
                        <h4 class="subdiv text-muted card-title mt-0"><?= lang('contact_details') ?></h4>
                        <div class="row inline-fields">
                            <div class="col-xl-4"><?= lang('company_name') ?></div>
                            <div class="col-xl-6"><?= company_name($company_info->companies_id) ?></div>
                        </div>
                        <div class="row inline-fields">
                            <div class="col-xl-4"><?= lang('contact_person') ?></div>
                            <div class="col-xl-6">
                                <?php

                                if ($company_info->primary_contact != 0) {
                                    $contacts = $company_info->primary_contact;
                                } else {
                                    $contacts = NULL;
                                }
                                $primary_contact = $this->companies_model->check_by(array('user_id' => $contacts), 'tbl_account_details');
                                if (!empty($primary_contact)) {
                                    echo $primary_contact->fullname;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="row inline-fields">
                            <div class="col-xl-4"><?= lang('email') ?></div>
                            <div class="col-xl-6">
                                <a href="mailto:<?= $company_info->email ?>" target="_top"><?= $company_info->email ?></a>
                            </div>
                        </div>
                        <div class="row inline-fields">
                            <div class="col-xl-4"><?= lang('city') ?></div>
                            <div class="col-xl-6"><?= $company_info->city ?></div>
                        </div>
                        <div class="row inline-fields">
                            <div class="col-xl-4"><?= lang('zipcode') ?></div>
                            <div class="col-xl-6"><?= $company_info->zip_code ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 mb-lg">
                    <div class="card border card-body">
                        <div class="rec-pay col-xl-12">
                            <div class="row inline-fields">
                                <div class="col-xl-4"><?= lang('address') ?></div>
                                <div class="col-xl-6"><?= $company_info->address ?></div>
                            </div>
                            <div class="row inline-fields">
                                <div class="col-xl-4"><?= lang('phone') ?></div>
                                <div class="col-xl-6"><a
                                        href="tel:<?= $company_info->phone ?>"><?= $company_info->phone ?></a>
                                </div>
                            </div>
                            <?php if (!empty($company_info->mobile)) { ?>
                                <div class="row inline-fields">
                                    <div class="col-xl-4"><?= lang('mobile') ?></div>
                                    <div class="col-xl-6"><a
                                            href="tel:<?= $company_info->mobile ?>"><?= $company_info->mobile ?></a>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row inline-fields">
                                <div class="col-xl-4"><?= lang('fax') ?></div>
                                <div class="col-xl-6"><?= $company_info->fax ?>
                                </div>
                            </div>
                            <div class=" mt">
                                <?php if (!empty($company_info->skype_id)) { ?>
                                    <div class="row inline-fields">
                                        <div class="col-xl-4"><?= lang('skype_id') ?></div>
                                        <div class="col-xl-6"><?= $company_info->skype_id ?></div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($company_info->facebook)) { ?>
                                    <div class="row inline-fields">
                                        <div class="col-xl-4"><?= lang('facebook_profile_link') ?></div>
                                        <div class="col-xl-6"><?= $company_info->facebook ?></div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($company_info->twitter)) { ?>
                                    <div class="row inline-fields">
                                        <div class="col-xl-4"><?= lang('twitter_profile_link') ?></div>
                                        <div class="col-xl-6"><?= $company_info->twitter ?></div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($company_info->linkedin)) { ?>
                                    <div class="row inline-fields">
                                        <div class="col-xl-4"><?= lang('linkedin_profile_link') ?></div>
                                        <div class="col-xl-6"><?= $company_info->linkedin ?></div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Details END -->
            </div>
        </div>
    </div>
</div>

