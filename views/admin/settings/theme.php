 <div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>

    <form action="<?php echo base_url() ?>admin/settings/save_theme" enctype="multipart/form-data"
          class="form-horizontal" method="post">
       
        <div class="card-body">
            <h4 class="card-title mb-4"><?= lang('theme_settings') ?></h4>
                <input type="hidden" name="settings" value="<?= $load_setting ?>">
                <div class="row mb-2">
                    <label class="col-lg-3 control-label hidden-md"><?= lang('site_name') ?></label>
                    <div class="col-lg-9">
                        <input type="text" name="website_name" class="form-control"
                               value="<?= config_item('website_name') ?>">
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-lg-3 control-label hidden-md"><?= lang('logo') ?></label>
                    <div class="col-lg-9">
                        <select name="logo_or_icon" class="form-select">
                            <?php $logoicon = config_item('logo_or_icon'); ?>
                            <option
                                value="logo_title"<?= ($logoicon == "logo_title" ? ' selected="selected"' : '') ?>><?= lang('logo') ?>
                                & <?= lang('site_name') ?></option>
                            <option
                                value="logo"<?= ($logoicon == "logo" ? ' selected="selected"' : '') ?>><?= lang('logo') ?></option>
                        </select>
                    </div>
                </div>
               
                <div class="row mb-2">
                    <label class="col-lg-3 control-label hidden-md"><?= lang('company_logo') ?></label>
                    <div class="col-lg-9">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" >
                                <?php if (config_item('company_logo') != '') : ?>
                                    <img src="<?php echo base_url() . config_item('company_logo'); ?>" >
                                <?php else: ?>
                                    <img src="<?php echo base_url('uploads/logo_tranparent.png');?>" alt="Comapny logo" >
                                <?php endif; ?>
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" ></div>
                            <div class="input-group">
                                        <input type="file" class="form-control" name="company_logo" value="upload"
                                               data-buttonText="<?= lang('choose_file') ?>" id="myImg"/>
                                   
                                    <!-- <a href="#" class="btn btn-default fileinput-exists"
                                       data-dismiss="fileinput"><?= lang('remove') ?></a> -->

                            </div>

                            <div id="valid_msg" style="color: #e11221">Show on your dashboard and login page, size: 140x40px </div>

                        </div>
                    </div>
                </div>

                <!-- <div class="row mb-2">
                    <label class="col-lg-3 control-label hidden-md"><?= lang('company_footer_logo') ?></label>
                    <div class="col-lg-9">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" >
                                <?php if (config_item('company_footer_logo') != '') : ?>
                                    <img src="<?php echo base_url() . config_item('company_footer_logo'); ?>">
                                <?php else: ?>
                                    <img src="<?php echo base_url('uploads/logo_tranparent.png');?>" alt="Comapny footer Logo">
                                <?php endif; ?>
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" ></div>
                            <div class="fileinput-new">
                                        <input type="file" class="form-control" name="company_footer_logo" value="upload"
                                               data-buttonText="<?= lang('choose_file') ?>" id="myImg"/>
                                        <span class="fileinput-exists"><?= lang('change') ?></span>
                                   
                                    <a href="#" class="btn btn-default fileinput-exists"
                                       data-dismiss="fileinput"><?= lang('remove') ?></a>

                            </div>

                            <div id="valid_msg" style="color: #e11221"></div>

                        </div>
                    </div>
                </div> -->

                <div class="row mb-2">
                    <label class="col-lg-3 control-label hidden-md"><?= lang('favicon') ?></label>
                    <div class="col-lg-9">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" >
                                <?php if (config_item('favicon') != '') : ?>
                                    <img src="<?php echo base_url() . config_item('favicon'); ?>">
                                <?php else: ?>
                                    <img src="<?php echo base_url('uploads/logo_tranparent.png');?>" alt="favicon">
                                <?php endif; ?>
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" ></div>
                            <div class="fileinput-new">
                                        <input type="file" class="form-control" name="favicon" value="upload"
                                               data-buttonText="<?= lang('choose_file') ?>" id="myImg"/>
                                        <!-- <span class="fileinput-exists"><?= lang('change') ?></span> -->
                                  
                                   <!--  <a href="#" class="btn btn-default fileinput-exists"
                                       data-dismiss="fileinput"><?= lang('remove') ?></a> -->

                            </div>

                            <div id="valid_msg" style="color: #e11221"></div>

                        </div>
                    </div>
                </div>
                <?php
                $lbg = config_item('login_background');
                if (!empty($lbg)) {
                    $login_background = _mime_content_type($lbg);
                    $login_background = explode('/', $login_background);
                }
                ?>
                <div class="row mb-2">
                    <label class="col-lg-3 control-label hidden-md"><?= lang('Login Page Image') ?></label>
                    <div class="col-lg-9">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 210px;">
                                <?php if (!empty($login_background[0]) && $login_background[0] == 'video') { ?>
                                    <video style="width: 100%;min-height: 100%" autoplay="autoplay" muted="muted"
                                           preload="auto" loop>
                                        <source
                                            src="<?php echo base_url() . config_item('login_background'); ?>"
                                            type="video/webm">
                                    </video>
                                <?php } ?>
                                <?php if (!empty($login_background[0]) && $login_background[0] == 'image') {
                                    ?>
                                    <img src="<?php echo base_url() . config_item('login_background'); ?>">
                                <?php } ?>
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" ></div>
                            <div class="fileinput-new">
                                        <input type="file" class="form-control" name="login_background" value="upload"
                                               data-buttonText="<?= lang('choose_file') ?>" id="myImg"/>
                                      <!--   <span class="fileinput-exists"><?= lang('change') ?></span> -->
                                    
                                  <!--   <a href="#" class="btn btn-default fileinput-exists"
                                       data-dismiss="fileinput"><?= lang('remove') ?></a> -->

                            </div>
                            <div id="valid_msg" style="color: #e11221">Display on the left side of your login page, size: 500x500px.</div>

                        </div>
                    </div>
                </div>
             <!--    <div class="row mb-2">
                    <label class="col-lg-3 control-label hidden-md"><?= lang('login_position') ?></label>
                    <div class="col-lg-4">
                        <select name="login_position" class="form-control">
                            <?php $login_position = config_item('login_position'); ?>
                            <option
                                value="left"<?= ($login_position == "left" ? ' selected="selected"' : '') ?>><?= lang('left') ?></option>
                            <option
                                value="right" <?= ($login_position == "right" ? ' selected="selected"' : '') ?>><?= lang('right') ?></option>
                            <option
                                value="center" <?= ($login_position == "center" ? ' selected="selected"' : '') ?>><?= lang('center') ?></option>
                        </select>
                    </div>
                </div> -->
                <div class="row mb-2">
                    <label class="col-lg-3 control-label hidden-md"><?= lang('layout') ?></label>
                    <div class="col-lg-9" id="app-settings">
                        <?php $theme = config_item('sidebar_theme'); ?>
                        <div class="mb">
                            <div class="row">
                                <div class="col">
                                    <label class="form-check">
                                        <input type="radio" class="form-check-input"
                                               name="sidebar_theme"
                                               value="light-mode-switch" <?= $theme == 'light-mode-switch' ? 'checked' : null ?>>
                                        Light mode
                                    </label>
                              <img src="<?php echo base_url(); ?>skote_assets/images/layouts/layout-1.jpg" class="img-fluid img-thumbnail" alt="">
                                </div>
                            

                          <div class="col">
                                    <label class="form-check">
                                        <input type="radio" class="form-check-input"
                                               name="sidebar_theme"
                                               value="dark-mode-switch" <?= $theme == 'dark-mode-switch' ? 'checked' : null ?>>
                                        Dark Mode
                                    </label>
                              <img src="<?php echo base_url(); ?>skote_assets/images/layouts/layout-2.jpg" class="img-fluid img-thumbnail" alt="">
                                </div> 

                             <div class="col">
                                    <label class="form-check">
                                        <input type="radio" class="form-check-input"
                                               name="sidebar_theme"
                                               value="rtl-mode-switch" <?= $theme == 'rtl-mode-switch' ? 'checked' : null ?>>
                                        RTL Mode
                                    </label>
                              <img src="<?php echo base_url(); ?>skote_assets/images/layouts/layout-3.jpg" class="img-fluid img-thumbnail" alt="">
                                </div>
                             <div class="col">
                                    <label class="form-check">
                                        <input type="radio" class="form-check-input"
                                               name="sidebar_theme"
                                               value="dark-rtl-mode-switch" <?= $theme == 'dark-rtl-mode-switch' ? 'checked' : null ?>>
                                       Dark RTL Mode
                                    </label>
                              <img src="<?php echo base_url(); ?>skote_assets/images/layouts/layout-4.jpg" class="img-fluid img-thumbnail" alt="">
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
                 <div class="row mb-2 d-none">
                    <label class="col-lg-3 control-label hidden-md"><?= lang('active_pre_loader') ?></label>
                    <div class="col-lg-2 form-check form-check-primary ml">
                            <input type="checkbox" <?php
                            $active_pre_loader = config_item('active_pre_loader');
                            // if (!empty($active_pre_loader) && $active_pre_loader == 1) {
                                echo 'checked';
                            // }
                            ?> value="1" name="active_pre_loader" class="form-check-input">
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-lg-3 control-label hidden-md"><?= lang('active_custom_color') ?></label>
                    <div class="col-lg-2 form-check form-check-primary ml">
                            <input type="checkbox" <?php
                            $custom_color = config_item('active_custom_color');
                            if (!empty($custom_color) && $custom_color == 1) {
                                echo 'checked';
                            }
                            ?> value="1" name="active_custom_color" id="active_custom_color"  class="form-check-input">
                    </div>
                </div>
                <div class="custom-color">
                    <div class="row mb-2">
                        <label class="col-lg-3 control-label hidden-md"></label>
                        <!--- Header Custom Color Start---->
                        <div class="col-lg-3 col-md-4">
                            <label><?= lang('navbar_logo_background') ?></label>
                            <input id="navbar_logo_background" name="navbar_logo_background" type="text"
                                   class="form-control colorpickerinput"
                                   value="<?= config_item('navbar_logo_background'); ?>"/>
                    <span class="navbar_logo_background color-previewer"
                          style="background-color:<?= config_item('navbar_logo_background'); ?>"></span>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <label><?= lang('top_bar_background') ?></label>
                            <input id="top_bar_background" name="top_bar_background" type="text"
                                   class="form-control colorpickerinput"
                                   value="<?= config_item('top_bar_background'); ?>"/>
                    <span class="top_bar_background color-previewer"
                          style="background-color:<?= config_item('top_bar_background'); ?>"></span>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <label><?= lang('top_bar_color') ?></label>
                            <input id="top_bar_color" name="top_bar_color" type="text"
                                   class="form-control colorpickerinput"
                                   value="<?= config_item('top_bar_color'); ?>"/>
                    <span class="top_bar_color color-previewer"
                          style="background-color:<?= config_item('top_bar_color'); ?>"></span>
                        </div>
                    </div>
                    <!--- Header Custom Color End---->
                    <!--- Sidebar Custom Color Start---->
                    <div class="row mb-2">
                        <label class="col-lg-3 control-label hidden-md"></label>
                        <div class="sidebar-custom-color col-lg-9 row">
                            <div class="col-md-4">
                                <label><?= lang('sidebar_background') ?></label>
                                <input id="sidebar_background" name="sidebar_background" type="text"
                                       class="form-control colorpickerinput"
                                       value="<?= config_item('sidebar_background'); ?>"/>
                        <span class="sidebar_background color-previewer"
                              style="background-color:<?= config_item('sidebar_background'); ?>"></span>
                            </div>

                            <div class="col-md-4">
                                <label><?= lang('sidebar_color') ?></label>
                                <input id="sidebar_color" name="sidebar_color" type="text"
                                       class="form-control colorpickerinput"
                                       value="<?= config_item('sidebar_color'); ?>"/>
                    <span class="sidebar_color color-previewer"
                          style="background-color:<?= config_item('sidebar_color'); ?>"></span>
                            </div>

                            <div class="col-md-4">
                                <label><?= lang('sidebar_active_background') ?></label>
                                <input id="sidebar_active_background" name="sidebar_active_background" type="text"
                                       class="form-control colorpickerinput"
                                       value="<?= config_item('sidebar_active_background'); ?>"/>
                    <span class="sidebar_active_background color-previewer"
                          style="background-color:<?= config_item('sidebar_active_background'); ?>"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-lg-3 control-label hidden-md"></label>
                        <div class="sidebar-custom-color col-lg-9 row">
                            <div class="col-md-4">
                                <label><?= lang('sidebar_active_color') ?></label>
                                <input id="sidebar_active_color" name="sidebar_active_color" type="text"
                                       class="form-control colorpickerinput"
                                       value="<?= config_item('sidebar_active_color'); ?>"/>
                        <span class="sidebar_active_color color-previewer"
                              style="background-color:<?= config_item('sidebar_active_color'); ?>"></span>
                            </div>

                            <div class="col-md-6">
                                <label><?= lang('submenu_open_background') ?></label>
                                <input id="submenu_open_background" name="submenu_open_background" type="text"
                                       class="form-control colorpickerinput"
                                       value="<?= config_item('submenu_open_background'); ?>"/>
                    <span class="submenu_open_background color-previewer"
                          style="background-color:<?= config_item('submenu_open_background'); ?>"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-lg-3 control-label hidden-md"></label>
                        <div class="sidebar-custom-color col-lg-9 row">
                            <div class=" col-md-4">
                                <label><?= lang('active_background') ?></label>
                                <input id="active_background" name="active_background" type="text"
                                       class="form-control colorpickerinput"
                                       value="<?= config_item('active_background'); ?>"/>
                    <span class="active_background color-previewer"
                          style="background-color:<?= config_item('active_background'); ?>"></span>
                            </div>
                            <div class="col-md-4">
                                <label><?= lang('active_color') ?></label>
                                <input id="active_color" name="active_color" type="text"
                                       class="form-control colorpickerinput"
                                       value="<?= config_item('active_color'); ?>"/>
                                <span class="active_color color-previewer"
                                      style="background-color:<?= config_item('active_color'); ?>"></span>
                            </div>
                            <div class="col-md-4">
                                <label><?= lang('body_background') ?></label>
                                <input id="body_background" name="body_background" type="text"
                                       class="form-control colorpickerinput"
                                       value="<?= config_item('body_background'); ?>"/>
                                 <span class="body_background color-previewer"
                                        style="background-color:<?= config_item('body_background'); ?>"></span>
                            </div>
                        </div>
                    </div>
                     <div class="row mb-2">
                        <label class="col-lg-3 control-label hidden-md"></label>
                        <div class="sidebar-custom-color col-lg-9 row">
                            <div class=" col-md-4">
                                  <label><?= lang('Dashboard widget background colour') ?></label>
                                <input id="dash_widget_background" name="dash_widget_background" type="text"
                                       class="form-control colorpickerinput"
                                       value="<?= config_item('dash_widget_background'); ?>"/>
                                 <span class="dash_widget_background color-previewer"
                                        style="background-color:<?= config_item('dash_widget_background'); ?>"></span>
                            </div>
                             <div class=" col-md-4">
                                  <label><?= lang('Dashboard widget text colour') ?></label>
                                <input id="dash_widget_text_color" name="dash_widget_text_color" type="text"
                                       class="form-control colorpickerinput"
                                       value="<?= config_item('dash_widget_text_color'); ?>"/>
                                 <span class="dash_widget_text_color color-previewer"
                                        style="background-color:<?= config_item('dash_widget_text_color'); ?>"></span>
                            </div>
                             <div class=" col-md-4">
                                  <label><?= lang('Dashboard widget button colour') ?></label>
                                <input id="dash_widget_button_color" name="dash_widget_button_color" type="text"
                                       class="form-control colorpickerinput"
                                       value="<?= config_item('dash_widget_button_color'); ?>"/>
                                 <span class="dash_widget_button_color color-previewer"
                                        style="background-color:<?= config_item('dash_widget_button_color'); ?>"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2 d-none">
                    <label class="col-lg-3 control-label hidden-md"><?= lang('layout') ?></label>
                    <div class="col-lg-9">
                        <div class="p">
                            <div class="clearfix">
                                <p class="float-start">Fixed</p>
                                <div class="float-end">
                                    <label class="form-check form-switch">
                                        <input id="chk-fixed" class="form-check-input" name="layout-fixed" value="layout-fixed"
                                               type="checkbox"
                                               data-toggle-state="layout-fixed" <?= config_item('layout-fixed') == 'layout-fixed' ? 'checked' : null ?>>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="clearfix">
                                <p class="float-start">Boxed</p>
                                <div class="float-end">
                                    <label class="form-check form-switch">
                                        <input id="chk-boxed" class="form-check-input"  name="layout-boxed" value="layout-boxed"
                                               type="checkbox"
                                               data-toggle-state="layout-boxed" <?= config_item('layout-boxed') == 'layout-boxed' ? 'checked' : null ?>>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="clearfix">
                                <p class="float-start">Collapsed</p>
                                <div class="float-end">
                                    <label class="form-check form-switch">
                                        <input id="chk-collapsed" class="form-check-input"  type="checkbox" name="aside-collapsed"
                                               value="aside-collapsed"
                                               data-toggle-state="aside-collapsed" <?= config_item('aside-collapsed') == 'aside-collapsed' ? 'checked' : null ?>>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="clearfix">
                                <p class="float-start">Float</p>
                                <div class="float-end">
                                    <label class="form-check form-switch">
                                        <input id="chk-float" class="form-check-input"  type="checkbox" name="aside-float" value="aside-float"
                                               data-toggle-state="aside-float" <?= config_item('aside-float') == 'aside-float' ? 'checked' : null ?>>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="clearfix">
                                <p class="text-danger float-start">RTL</p>
                                <div class="float-end">
                                    <label class="form-check form-switch">
                                        <input id="chk-rtl" class="form-check-input" 
                                               name="RTL" <?= config_item('RTL') == 'on' ? 'checked' : null ?>
                                               type="checkbox">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!--- Sidebar Custom Color Start---->
            <div class="row mb-2">
                <label class="col-lg-3 control-label hidden-md"></label>
                <div class="col-lg-4">
                    <button type="submit" class="btn btn-xs btn-primary"><?= lang('save_changes') ?></button>
                </div>
            </div>
        </div>
    </form>
</div>
<link href="<?php echo base_url() ?>assets/plugins/bootstrap-colorpicker/bootstrap-colorpicker.css"
      rel="stylesheet">
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
<script>
    $(document).ready(function () {
        $(".custom-color").hide();
        <?php
        if (!empty($custom_color) && $custom_color == 1) {?>
        $(".custom-color").show();
        <?php } ?>

    });
    $(function () {
        $('#active_custom_color').click(function () {
            if ($(this).prop('checked')) {
                $(".custom-color").slideDown(200);
            } else {
                $(".custom-color").slideUp(200);
            }
        });
        var colors = {
            '#161b1f': '#161b1f',
            '#d8dce3': '#d8dce3',
            '#11a7db': '#11a7db',
            '#2aa96b': '#2aa96b',
            '#5bc0de': '#5bc0de',
            '#f0ad4e': '#f0ad4e',
            '#ed5564': '#ed5564'
        };
        var sliders = {
            saturation: {
                maxLeft: 200,
                maxTop: 200
            },
            hue: {
                maxTop: 200
            },
            alpha: {
                maxTop: 200
            }
        };


        $('.colorpickerinput').colorpicker({
            customClass: 'colorpicker-2x',
            // colorSelectors: colors,
            align: 'right',
            sliders: sliders
        }).on('changeColor', function (e) {
 
            if (e.target.id == "navbar_logo_background") {
                $('#page-topbar .navbar-brand-box')
                    .css('background', e.color);

                $('.navbar_logo_background.color-previewer')
                    .css('background', e.color);
            }
            if (e.target.id == "top_bar_background") {
                $('#page-topbar .navbar-header')
                    .css('background', e.color);
                $('li.user-header')
                    .css('background-color', e.color);

                $('.top_bar_background.color-previewer')
                    .css('background', e.color);
            }
            if (e.target.id == "top_bar_color") {
              
                $(' #page-topbar i,#page-topbar .header-item')
                    .css('color', $('#'+e.target.id).val());

                $('.top_bar_color.color-previewer')
                    .css('background', e.color);
            }
            if (e.target.id == "sidebar_background") {
                $('body[data-sidebar="colored"] .vertical-menu')
                    .css('background', e.color);

                $('.sidebar_background.color-previewer')
                    .css('background', e.color);

            }
            if (e.target.id == "sidebar_color") {
                $('body[data-sidebar=colored] #sidebar-menu ul li a, body[data-sidebar=colored] #sidebar-menu ul li, body[data-sidebar=colored] #sidebar-menu ul li a i')
                    .css('color', $('#'+e.target.id).val());

                $('.sidebar_color.color-previewer')
                    .css('background', e.color);

            }
            if (e.target.id == "sidebar_active_background") {
                $('body[data-sidebar=colored] .vertical-menu  li.active, body[data-sidebar=colored] .vertical-menu  li.open, body[data-sidebar=colored] .vertical-menu  li.active  a, body[data-sidebar=colored] .vertical-menu  li.open  a, body[data-sidebar=colored] .vertical-menu  li.active .nav, body[data-sidebar=colored] .vertical-menu  li.open .nav')
                    .css('background', e.color);

                $('.sidebar_active_background.color-previewer')
                    .css('background', e.color);

            }
            if (e.target.id == "sidebar_active_color") {
                $('body[data-sidebar=colored] .vertical-menu  li.active, body[data-sidebar=colored] .vertical-menu  li.open, body[data-sidebar=colored] .vertical-menu  li.active  a, body[data-sidebar=colored] .vertical-menu  li.open  a, body[data-sidebar=colored] .vertical-menu  li.active .nav, body[data-sidebar=colored] .vertical-menu  li.open .nav,body[data-sidebar=colored] .vertical-menu  li.active  a  em, body[data-sidebar=colored] .vertical-menu  li.open  a  em')
                    .css('color', $('#'+e.target.id).val());

                $('.sidebar_active_color.color-previewer')
                    .css('background', e.color);

                $('body[data-sidebar=colored] .vertical-menu > li.active')
                    .css('border-left-color', e.color);

            }
            if (e.target.id == "submenu_open_background") {
                $('.sidebar-subnav')
                    .css('background', e.color);

                $('.submenu_open_background.color-previewer')
                    .css('background', e.color);
            }
            if (e.target.id == "active_background") {
                $('.nav-pills > li.active , .nav-pills > li:focus , li.user-header')
                    .attr('style', 'background: ' + e.color + ' !important');
                $('.active_background.color-previewer')
                    .css('background', e.color);
            }
            if (e.target.id == "active_color") {
                $('.nav-pills > li.active > a, .nav-pills > li.active > a:focus')
                    .css('color', $('#'+e.target.id).val());

                $('.active_color.color-previewer')
                    .css('background', e.color);
            }
            if (e.target.id == "body_background") {
                $('body,.wrapper > section')
                    .css('background', e.color);

                $('.body_background.color-previewer')
                    .css('background', e.color);
            }

           
            if (e.target.id == "dash_widget_background") {
                 $('.dash_widget_background.color-previewer')
                    .css('background', e.color);
            }
             if (e.target.id == "dash_widget_text_color") {
                 $('.dash_widget_text_color.color-previewer')
                    .css('background', e.color);
            }
             if (e.target.id == "dash_widget_button_color") {
                 $('.dash_widget_button_color.color-previewer')
                    .css('background', e.color);
            }

        });
         $("input[type='file']").change(function(){
        readURL(this);
    });
         $('#active_custom_color').on('change',function () {
              if(!$('#active_custom_color').is(":checked")){
                $('#bg-custom').remove();
            }else{
                $('.colorpickerinput').trigger('change');
            }
         })
         $("input[name='sidebar_theme']").on("change", function (e) {
         
            if(!$('#active_custom_color').is(":checked")){
                $('#bg-custom').remove();
            }else{
                $('.colorpickerinput').trigger('change');
            }
         
           if($(this).val() == 'rtl-mode-switch' || $(this).val() == 'dark-rtl-mode-switch' ){
                $('#chk-rtl').prop('checked',true);
           }else{
                $('#chk-rtl').prop('checked',false);

           }
           // $('.colorpickerinput').trigger('change');
           

        });
          

    });

function readURL(input) {
     for(var i =0; i< input.files.length; i++){
         if (input.files[i]) {
            var reader = new FileReader();

            reader.onload = function (e) {
               var img = $(input).closest('.fileinput').find('img');
               img.attr('src', e.target.result);
               // img.appendTo('#form1');  
            }
            reader.readAsDataURL(input.files[i]);
           }
        }
    }

   
</script>