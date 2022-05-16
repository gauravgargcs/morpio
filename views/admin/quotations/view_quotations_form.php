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
<div class="row" id="printableArea">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="pull-right text-sm">
                    <div class="float-end">
                        
                        <a href="<?= base_url() ?>admin/quotations/quotations_form_details_pdf/<?= $quotationforms_info->quotationforms_id ?>"
                           class="btn btn-primary btn-sm mr-5 mt" data-bs-toggle="tooltip" data-bs-placement="top"
                           title="PDF" style="margin-top: -6px;"><i class="fa fa-file-pdf-o"></i>
                        </a>
                        <button class="btn btn-sm pull-left btn-danger btn-print mr-5 mt" type="button" data-bs-toggle="tooltip" title="Print" onclick="printDiv('printableArea')"><i class="fa fa-print"></i>
                        </button>
                   
                    </div>
                </div>
                <h4 class="card-title"><?= $quotationforms_info->quotationforms_title ?></h4>        
                <div class="mb-3 mt row">

                    <?php super_admin_invoice($quotationforms_info->companies_id) ?>
                    <?php
                    if (!empty($formbuilder_data)) {
                        foreach ($formbuilder_data as $value) {
                            if (!empty($value)) {
                                $field_type = $value['field_type'];
                                $field_options = $value['field_options'];
                                $required = '';
                                if ($value['required'] == 1) {
                                    $required = 'true';
                                }
                                ?>

                                <?php if ($field_type == 'paragraph'): ?>
                                    <label
                                        class="col-form-label"><?php echo $value['label'] ?> <?php if (!empty($required)) { ?>
                                            <span class="text-danger">*</span><?php } ?></label>
                                    <div class="">
                                        <?php
                                        if ($field_options['size'] == 'small') {
                                            $height = 'min-height:60px';
                                        } elseif ($field_options['size'] == 'medium') {
                                            $height = 'min-height:100px';
                                        } else {
                                            $height = 'min-height:200px';
                                        }
                                        ?>
                                        <textarea style="<?= $height ?>" class="form-control"></textarea>
                                    </div>
                                    <br/>
                                <?php endif; ?>
                                <?php if ($field_type == 'dropdown'): ?>
                                    <label
                                        class="col-form-label"><?php echo $value['label'] ?> <?php if (!empty($required)) { ?>
                                            <span class="text-danger">*</span><?php } ?></label>
                                    <div class="">
                                        <select class="form-control">
                                            <?php
                                            $options = $field_options['options'];
                                            foreach ($options as $v_option) {
                                                if ($v_option['checked'] == 1) {
                                                    $checked = 'selected';
                                                } else {
                                                    $checked = '';
                                                }
                                                ?>
                                                <option <?= $checked ?>> <?= $v_option['label'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <br/>
                                <?php endif; ?>
                                <?php if ($field_type == 'text'): ?>
                                    <label  class="col-form-label"><?php echo $value['label'] ?> <?php if (!empty($required)) { ?>
                                            <span class="text-danger">*</span><?php } ?></label>
                                    <div class="">
                                        <input type="text" class="form-control"/>
                                    </div>
                                    <br/>
                                <?php endif; ?>
                                <?php if ($field_type == 'checkboxes'): ?>
                                    <label class="col-form-label"><?php echo $value['label'] ?> <?php if (!empty($required)) { ?> <span class="text-danger">*</span><?php } ?></label>
                                    <div class="">
                                        <?php
                                        $options = $field_options['options'];
                                        foreach ($options as $v_option) {
                                            if ($v_option['checked'] == 1) {
                                                $checked = 'checked';
                                            } else {
                                                $checked = '';
                                            }
                                            ?>
                                        <div class="form-check form-check mb-3 mr">
                                            <input type="checkbox" class="form-check-input" style="width: 15px;height: 15px;margin-right: 5px"  <?= $checked ?> ><?= $v_option['label'] ?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <br/>
                                <?php endif; ?>
                                <?php if ($field_type == 'radio'): ?>
                                    <label class="col-form-label"><?php echo $value['label'] ?> <?php if (!empty($required)) { ?>
                                            <span class="text-danger">*</span><?php } ?></label>
                                    <div class="">
                                        <?php
                                        $options = $field_options['options'];
                                        foreach ($options as $v_option) {
                                            if ($v_option['checked'] == 1) {
                                                $checked = 'checked';
                                            } else {
                                                $checked = '';
                                            }
                                            ?>
                                        <div class="form-check form-radio-outline form-radio-primary mt mr">

                                            <input type="radio" class="form-check-input" style="width: 15px;height: 15px;margin-right: 5px"  <?= $checked ?> ><?= $v_option['label'] ?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <br/>
                                <?php endif; ?>

                                <?php
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function printDiv(printableArea) {
        var printContents = document.getElementById(printableArea).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
