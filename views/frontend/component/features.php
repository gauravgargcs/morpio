<!-- Features Tab -->

<?php
$all_features = get_order_by('tbl_frontend_features', array('status' => 1, 'type'=>'features'), 'sort', true);
?>


<!-- Feature Box -->
<div class="cps-section cps-bottom-0" id="service-box">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="cps-section-header text-center">
                    <h3 class="cps-section-title"><?= config_item('features_heading') ?></h3>
                    <p class="cps-section-text"><?= config_item('features_description') ?></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="cps-service-boxs">


                <?php
                if (!empty($all_features)) {
                foreach ($all_features as $features) {
                ?>

                    <div class="col-sm-4">
                        <div class="cps-service-box">
                            <div class="cps-service-icon">

                                <?php
                                if ($features->icon!="" || $features->icon!=NULL) {
                                 echo '<i class="'.$features->icon.'"></i>';
                                }
                                else {
                                    echo '<span class="ti-layers-alt"></span>';
                                }
                                ?>

                            </div>
                            <h4 class="cps-service-title"><?= $features->title ?></h4>
                            <p class="cps-service-text"><?= $features->description ?></p>
                        </div>
                    </div>

                <?php }
                }
                ?>

            </div>
        </div>
    </div>
</div>
<!-- Feature Box End -->

