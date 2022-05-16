<?php
if (!empty($holiday_list)) {
    $holiday_id = $holiday_list->holiday_id;
    $companies_id = $holiday_list->companies_id;
} else {
    $holiday_id = null;
    $companies_id = null;
}
echo form_open(base_url('admin/holiday/save_holiday/' . $holiday_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
    <div class="modal-header">
        <h5 class="modal-title"><?= lang('new_holiday') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="col-xl-12">
            <?php super_admin_form_modal($companies_id, 3, 8) ?>
            <div class="row mb-3">
                <label class="col-sm-3 form-label"><?= lang('event_name') ?>
                    <span class="required">*</span></label>

                <div class="col-sm-8">
                    <input required type="text" name="event_name" class="form-control" value="<?php
                    if (!empty($holiday_list->event_name)) {
                        echo $holiday_list->event_name;
                    }
                    ?>" id="field-1" placeholder="Enter Your <?= lang('event_name') ?>"/>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 form-label"><?= lang('description') ?><span class="required"> *</span></label>

                <div class="col-sm-8">
                    <textarea required style="height: 100px" name="description" class="form-control " id="field-1"
                              placeholder="Enter Your Description"><?php
                        if (!empty($holiday_list->description)) {
                            echo $holiday_list->description;
                        }
                        ?></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 form-label"><?= lang('start_date') ?><span
                            class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input required type="text" class="form-control datepicker" name="start_date" value="<?php
                        if (!empty($holiday_list->start_date)) {
                            echo date('d-m-Y H-i', strtotime($holiday_list->start_date));
                        } else {
                            echo date('d-m-Y H-i');
                        }
                        ?>">
                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 form-label"><?= lang('end_date') ?><span
                            class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="input-group ">
                        <input required type="text" class="form-control datepicker" name="end_date" value="<?php
                        if (!empty($holiday_list->end_date)) {
                            echo date('d-m-Y H-i', strtotime($holiday_list->end_date));
                        } else {
                            echo date('d-m-Y H-i');
                        }
                        ?>">
                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                    
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 form-label"><?= lang('location') ?></label>

                <div class="col-sm-8">
                    <input type="text" name="location" class="form-control" value="<?php
                    if (!empty($holiday_list->location)) {
                        echo $holiday_list->location;
                    }
                    ?>" id="field-1" placeholder="Enter Your <?= lang('location') ?>"/>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 form-label"></label>

                <div class="color-palet col-sm-8">
                    <span style="background-color:#83c340" class="color-tag clickable active" data-color="#83c340"></span>
                    <span style="background-color:#29c2c2" class="color-tag clickable" data-color="#29c2c2"></span>
                    <span style="background-color:#2d9cdb" class="color-tag clickable" data-color="#2d9cdb"></span>
                    <span style="background-color:#aab7b7" class="color-tag clickable" data-color="#aab7b7"></span>
                    <span style="background-color:#f1c40f" class="color-tag clickable" data-color="#f1c40f"></span>
                    <span style="background-color:#e18a00" class="color-tag clickable" data-color="#e18a00"></span>
                    <span style="background-color:#e74c3c" class="color-tag clickable" data-color="#e74c3c"></span>
                    <span style="background-color:#d43480" class="color-tag clickable" data-color="#d43480"></span>
                    <span style="background-color:#ad159e" class="color-tag clickable" data-color="#ad159e"></span>
                    <span style="background-color:#34495e" class="color-tag clickable" data-color="#34495e"></span>
                    <span style="background-color:#dbadff" class="color-tag clickable" data-color="#dbadff"></span>
                    <span style="background-color:#f05050" class="color-tag clickable" data-color="#f05050"></span>
                    <input type="text" id="color" name="color" value="<?php if (!empty($holiday_list->color)) { echo $holiday_list->color; }else{ echo '#83c340'; } ?>" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" id="sbtn"  class="btn btn-primary w-md waves-effect waves-light"><?= lang('save') ?></button>            
        </div>
    </div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $(document).ready(function () {
       
        $("#color").spectrum({
            showPaletteOnly: true,
            showPalette: true,
            color: '<?php if (!empty($holiday_list->color)) { echo $holiday_list->color; }else{ echo '#83c340'; } ?>',
            palette: [ 
                ['#83c340', '#29c2c2', '#2d9cdb', '#aab7b7', '#f1c40f','#dbadff'],
                ['#e18a00', '#e74c3c', '#d43480', '#ad159e', '#34495e','#f05050']
            ]
        });

    });
</script>
