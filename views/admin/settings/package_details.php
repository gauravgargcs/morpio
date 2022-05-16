<style type="text/css">
    section.package-section {
        background: #fff;
        color: #7a90ff;
        /*padding: 2em 0 8em;*/
        min-height: 100vh;
        position: relative;
        -webkit-font-smoothing: antialiased;
        /*margin-top: 30px;*/
    }

    .packaging {
        display: -webkit-flex;
        display: flex;
        -webkit-flex-wrap: wrap;
        flex-wrap: wrap;
        -webkit-justify-content: center;
        justify-content: center;
        width: 100%;
        margin: 0 auto 3em;
    }

    .packaging-item {
        position: relative;
        display: -webkit-flex;
        display: flex;
        -webkit-flex-direction: column;
        flex-direction: column;
        -webkit-align-items: stretch;
        align-items: stretch;
        text-align: center;
        -webkit-flex: 0 1 550px;
        flex: 0 1 550px;
        margin-bottom: 2em !important;
    }

    .packaging-action {
        color: inherit;
        border: none;
        background: none;
    }

    .packaging-action:focus {
        outline: none;
    }

    .packaging-feature-list {
        text-align: left;
    }

    .packaging-palden .packaging-item {
        font-family: 'Open Sans', sans-serif;
        cursor: default;
        color: #84697c;
        background: #fff;
        box-shadow: 0 0 10px rgba(46, 59, 125, 0.23);
        /*border-radius: 20px 20px 10px 10px;*/
        margin: 1em;
    }

    @media screen and (min-width: 66.25em) {
        .packaging-palden .packaging-item {
            margin: 1em -0.5em;
        }

        .packaging-palden .packaging__item--featured {
            margin: 0;
            z-index: 10;
            box-shadow: 0 0 20px rgba(46, 59, 125, 0.23);
        }
    }

    .packaging-palden .packaging-deco {
        /*border-radius: 10px 10px 0 0;*/
        padding: 4em 0 9em;
        position: relative;
    }

    .packaging-palden .packaging-deco-img {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 160px;
    }

    .packaging-palden .packaging-title {
        font-size: 0.75em;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 5px;
        color: #fff;
    }

    .packaging-palden .deco-layer {
        -webkit-transition: -webkit-transform 0.5s;
        transition: transform 0.5s;
    }

    .packaging-palden .packaging-item:hover .deco-layer--1 {
        -webkit-transform: translate3d(15px, 0, 0);
        transform: translate3d(15px, 0, 0);
    }

    .packaging-palden .packaging-item:hover .deco-layer--2 {
        -webkit-transform: translate3d(-15px, 0, 0);
        transform: translate3d(-15px, 0, 0);
    }

    .packaging-palden .icon {
        font-size: 2.5em;
    }

    .packaging-palden .packaging-package {
        font-size: 5em;
        font-weight: bold;
        padding: 0;
        color: #fff;
        margin: 0 0 0.25em 0;
        line-height: 0.75;
    }

    .packaging-palden .packaging-currency {
        font-size: 0.15em;
        vertical-align: top;
    }

    .packaging-palden .packaging-period {
        font-size: 0.15em;
        padding: 0 0 0 0.5em;
        font-style: italic;
    }

    .packaging-palden .packaging__sentence {
        font-weight: bold;
        margin: 0 0 1em 0;
        padding: 0 0 0.5em;
    }

    .packaging-palden .packaging-feature-list {
        margin: 0;
        padding: 0.25em 0 15px;
        list-style: none;
        /*text-align: center;*/
    }

    .packaging-palden .packaging-feature {
        padding: 2px 0;
    }

    .packaging-palden li.packaging-feature {
        border-bottom: 1px dashed #564aa3;
        margin-right: 33px;
        margin-left: 20px;
    }

    .packaging-palden .packaging-action {
        font-weight: bold;
        margin: auto 3em 2em 3em;
        padding: 1em 2em;
        color: #fff;
        border-radius: 30px;
        -webkit-transition: background-color 0.3s;
        transition: background-color 0.3s;
    }

    .packaging-palden .packaging-action:hover, .packaging-palden .packaging-action:focus {
        background-color: #3378ff;
    }

    .packaging-palden .packaging-item--featured .packaging-deco {
        padding: 5em 0 8.885em 0;
    }

    .packaging-feature i {
        font-size: 15px;
        float: left;
        margin: 0px 8px 0px 0px;;
    }

    .custom_ul {
        list-style: none;
        padding: 0.25em 13px 0px;;
    }

    .custom_ul li {
        border-bottom: 1px dashed #564aa3;
        padding: 4px 0px;
    }

    .custom_ul li a {
        padding: 0.25em 0 2.5em;
    }

    .custom_ul i {
        font-size: 15px;
        /*float: left;*/
        margin: 0px 8px 0px 0px;;
    }

    .package_position {
        margin-bottom: 10px;
        text-align: initial;
        margin-left: 10px;
        float: left;
    }

    .package_position .packaging-title {
        letter-spacing: 2px !important;
    }
    .custom-bg-2{
        background: #556ee6;
    }
</style>
<?php
$currency = get_old_data('tbl_currencies', array('code' => config_item('default_currency')));
?>

    <div class="modal-header">
      
        <h4 class="modal-title" id="myModalLabel"><?= lang('package_details') . ' - ' . $package_info->name ?></h4>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body wrap-modal wrap">
        <section class="package-section">
            <div class='packaging packaging-palden'>
                <div class='packaging-item'>
                    <div class='packaging-deco custom-bg-2'>
                        <svg class='packaging-deco-img' enable-background='new 0 0 300 100' height='100px' id='Layer_1'
                             preserveAspectRatio='none' version='1.1' viewBox='0 0 300 100' width='300px' x='0px'
                             xml:space='preserve'
                             xmlns='http://www.w3.org/2000/svg'
                             y='0px'>
          <path class='deco-layer deco-layer--1'
                d='M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z'
                fill='#FFFFFF' opacity='0.6'></path>
                            <path class='deco-layer deco-layer--2'
                                  d='M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729&#x000A;	c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z'
                                  fill='#FFFFFF' opacity='0.6'></path>
                            <path class='deco-layer deco-layer--3'
                                  d='M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716&#x000A;	H42.401L43.415,98.342z'
                                  fill='#FFFFFF' opacity='0.7'></path>
                            <path class='deco-layer deco-layer--4'
                                  d='M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z'
                                  fill='#FFFFFF'></path>
</svg>
                        <div class='packaging-package'><span
                                    class='packaging-currency'> </span><?= $package_info->name ?>
                            <span class='packaging-period'></span>
                        </div>

                        <?php
                        if (!empty($package_info->subscriptions_id)) {
                            $currency = $this->db->where('code', $package_info->currency)->get('tbl_currencies')->row();
                            ?>
                            <div class="package_position">
                                <h3 class='packaging-title'><?= display_money($package_info->amount, $currency->symbol) . ' /' . $package_info->frequency ?></h3>
                            </div>
                        <?php } else {
                            if (!empty($package_info)) {
                                $all_currency_price = get_old_data('tbl_currencywise_price', array('frontend_pricing_id' => $package_info->id), true);
                                if (!empty($all_currency_price)) {
                                    foreach ($all_currency_price as $currency_price) {
                                        $currency = $this->db->where('code', $currency_price->currency)->get('tbl_currencies')->row();
                                        ?>
                                        <div class="package_position">
                                            <h3 class='packaging-title'><?= display_money($currency_price->monthly, $currency->symbol) . ' /' . lang('mo') ?></h3>
                                            <h3 class='packaging-title'><?= display_money($currency_price->yearly, $currency->symbol) . ' /' . lang('yr') ?></h3>
                                        </div>
                                    <?php }
                                }
                            }
                        }
                        ?>


                    </div>
                    <ul class='packaging-feature-list'>
                        <li class='packaging-feature'><?= pricing_format_admin_YN($package_info->multi_branch, lang('multi_branch')) ?></li>
                        <?php
                        $all_module = get_old_data('tbl_modules', array('active' => 1, 'module_name !=' => 'mailbox'), true);
                        if (!empty($all_module)) {
                            foreach ($all_module as $v_module) {
                                $name = 'allow_' . $v_module->module_name; ?>
                                <li class='packaging-feature'> <?= pricing_format_admin_YN($package_info->$name, lang($v_module->module_name)); ?></li>
                            <?php }
                        } ?>
                        <li class='packaging-feature'><?= pricing_format_admin($package_info->employee_no, lang('Users')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin($package_info->disk_space, lang('disk_space')) ?></li>
                        <?php if (!empty($package_info->trial_period)) { ?>
                            <li class='packaging-feature'><?= pricing_format_admin($package_info->trial_period, lang('days') . ' ' . lang('trail_period')) ?></li>
                        <?php } ?>
                        <li class='packaging-feature'><?= pricing_format_admin($package_info->client_no, lang('Contacts')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin($package_info->project_no, lang('Projects')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin($package_info->invoice_no, lang('Invoices')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin($package_info->leads, lang('leads')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin($package_info->accounting, lang('accounting')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin($package_info->bank_account, lang('bank') . ' ' . lang('account')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin($package_info->tasks, lang('tasks')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin_YN($package_info->online_payment, lang('Online payments')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin_YN($package_info->mailbox, lang('mailbox')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin_YN($package_info->live_chat, lang('Team Chat')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin_YN($package_info->tickets, lang('tickets')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin_YN($package_info->recruitment, lang('job_circular')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin_YN($package_info->attendance, lang('attendance')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin_YN($package_info->payroll, lang('payroll')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin_YN($package_info->leave_management, lang('leave_management')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin_YN($package_info->performance, lang('Performance Tracking')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin_YN($package_info->training, lang('training')) ?></li>
                        <li class='packaging-feature'><?= pricing_format_admin_YN($package_info->reports, lang('report')) ?></li>
                    </ul>
                </div>
            </div>
        </section>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></a>
    </div>
