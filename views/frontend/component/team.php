<div class="cps-service-box" style="padding: 0px">

    <h4 class="cps-service-title"><?php echo $title; ?></h4>
    <hr/>

    <?php

    $qry_team = $this->db->get_where(' tbl_partners', array('status' => 1, 'type' => $control));

    foreach ($qry_team->result() as $qry_team_res) {
        ?>

        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="team-box">
                <div class="team-head col-sm-6">
                    <img src="<?= base_url() . $qry_team_res->profile; ?>" class="img-responsive mauto" alt="">
                    <div class="social">
                        <ul>
                            <li class="facebook-icon">
                                <a target="_blank" href="<?= $qry_team_res->facebook; ?>">
                                    <span class="fa fa-facebook"></span>
                                </a>
                            </li>
                            <li class="twitter-icon">
                                <a target="_blank" href="<?= $qry_team_res->twitter; ?>">
                                    <span class="fa fa-twitter"></span>
                                </a>
                            </li>
                            <li class="linkedin-icon">
                                <a target="_blank" href="<?= $qry_team_res->linkedin; ?>">
                                    <span class="fa fa-linkedin"></span>
                                </a>
                            </li>
                            <li class="dribbble-icon">
                                <a target="_blank" href="<?= $qry_team_res->dribbble; ?>">
                                    <span class="fa fa-dribbble"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="team-content col-sm-6">
                    <h3><?= $qry_team_res->name; ?></h3>
                    <b><?= $qry_team_res->designation; ?></b>
                    <p><?= $qry_team_res->description; ?></p>
                    <!-- end team-social -->
                </div>
                <!-- end team-content -->
            </div>
            <!-- end team-box -->
        </div>

        <?php
    }

    ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
</div>


<style>

    .cps-service-box {
        /*min-height: 450px !important;*/
        height: auto !important;
        overflow: scroll auto;
        margin-bottom: 10px !important;
    }

    ::-webkit-scrollbar {
        background: transparent; /*make scrollbar transparent */
    }

    .modal-footer {
        border-top: none !important;
        padding-top: 0px !important;
        /*margin-top: -90px;*/
    }

</style>

