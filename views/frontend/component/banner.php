<!-- Banner -->
<div class="cps-banner" id="banner">

    <div class="cps-banner-slider owl-carousel" id="cps-banner-slider">


        <?php
        $data_x = array("right", "left", "center");
        $randomNumber = rand(0, (count($data_x) - 1));
        $all_slider = $this->db->where('status', 1)->order_by('sort', 'ASC')->get('tbl_frontend_slider')->result();
        foreach ($all_slider as $v_slider) { ?>


            <div class="cps-banner-item cps-banner-item-<?= $v_slider->id ?>"
                 style="background-image: url(<?= base_url() . $v_slider->slider ?>) !important;">
                <div class="cps-banner-content">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12">
                                <h1 class="cps-banner-title"><?= $v_slider->title ?></h1>
                                <p class="cps-banner-text"><?= $v_slider->description ?></p>
                                <div class="cps-button-group">
                                    <a href="<?= trim($v_slider->button_one_link) ?>" class="btn"><?= $v_slider->button_one ?></a>
                                    <a href="<?= $v_slider->button_two_link ?>" class="btn"><?= $v_slider->button_two ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        <?php } ?>


    </div>
</div>
<!-- Banner End -->