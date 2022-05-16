<?= message_box('success'); ?>
<?= message_box('error'); ?>
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


<div class="row">
    <div class="card"> 
        <div class="card-body">
            <div class="mb-lg pull-left float-end">
                <div class="pull-left float-end">
                    <a class="btn btn-xs btn-danger" href="<?= base_url() ?>admin/client/manage_client"><?= lang('client_list') ?></a>
                </div>
                <div class="pull-right pr-lg">
                    <a href="<?php echo base_url() ?>assets/sample/client_sample.xlsx" class="btn btn-primary"><i class="fa fa-download"> <?= lang('download_sample') ?></i></a>
                </div>

            </div>
            
            <h4 class="card-title mb-4"><?= lang('import') . ' ' . lang('client') ?></h4>

            <form role="form" enctype="multipart/form-data" id="form"
                  action="<?php echo base_url(); ?>admin/client/save_imported" method="post"
                  class="form-horizontal  ">
                
                <div class="row mb-3">
                    <label for="formFile"  class="col-xl-3 col-form-label">
                        <?= lang('choose_file') ?><span class="required">*</span></label>
                    <div class="col-xl-5">
                        <input class="form-control" type="file" id="formFile" name="upload_file" >
                    </div>
                </div>

                <div class="row mb-3">
                    <label
                        class="col-xl-3 col-form-label"><?= lang('customer_group') ?></label>
                    <div class="col-xl-5">
                        <select name="customer_group_id" class="form-control select_box"
                                style="width: 100%">
                            <?php
                            $all_customer_group = get_result('tbl_customer_group', array('type' => 'client'));
                            if (!empty($all_customer_group)) {
                                foreach ($all_customer_group as $customer_group) : ?>
                                    <option
                                        value="<?= $customer_group->customer_group_id ?>"<?php
                                    if (!empty($client_info->customer_group_id) && $client_info->customer_group_id == $customer_group->customer_group_id) {
                                        echo 'selected';
                                    } ?>
                                    ><?= $customer_group->customer_group; ?></option>
                                <?php endforeach;
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label
                        class="col-xl-3 col-form-label"><?= lang('language') ?></label>
                    <div class="col-xl-5">
                        <select name="language" class="form-control person select_box"
                                style="width: 100%">
                            <?php foreach ($languages as $lang) : ?>
                                <option
                                    value="<?= $lang->name ?>"<?php
                                if (!empty($client_info->language) && $client_info->language == $lang->name) {
                                    echo 'selected';
                                } elseif (empty($client_info->language) && $this->config->item('language') == $lang->name) {
                                    echo 'selected';
                                } ?>
                                ><?= ucfirst($lang->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label
                        class="col-xl-3 col-form-label"><?= lang('currency') ?></label>
                    <div class="col-xl-5">
                        <select name="currency" class="form-control person select_box"
                                style="width: 100%">

                            <?php if (!empty($currencies)): foreach ($currencies as $currency): ?>
                                <option
                                    value="<?= $currency->code ?>"
                                    <?php
                                    if (!empty($client_info->currency) && $client_info->currency == $currency->code) {
                                        echo 'selected';
                                    } elseif (empty($client_info->currency) && $this->config->item('default_currency') == $currency->code) {
                                        echo 'selected';
                                    } ?>
                                ><?= $currency->name ?></option>
                                <?php
                            endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-xl-3 col-form-label"><?= lang('country') ?></label>
                    <div class="col-xl-5">
                        <select name="country" class="form-control person select_box"
                                style="width: 100%">
                            <optgroup label="Default Country">
                                <option
                                    value="<?= $this->config->item('company_country') ?>"><?= $this->config->item('company_country') ?></option>
                            </optgroup>
                            <optgroup label="<?= lang('other_countries') ?>">
                                <?php if (!empty($countries)): foreach ($countries as $country): ?>
                                    <option
                                        value="<?= $country->value ?>" <?= (!empty($client_info->country) && $client_info->country == $country->value ? 'selected' : NULL) ?>><?= $country->value ?>
                                    </option>
                                    <?php
                                endforeach;
                                endif;
                                ?>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-xl-3 col-form-label"></label>
                    <div class="col-xl-5">
                        <button type="submit" class="btn btn-xs btn-primary"><?= lang('upload') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>