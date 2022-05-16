<!-- START Dropdown menu-->
<button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="bx bx-bell bx-tada"></i>
    <?php if ($unread_notifications > 0) { ?>
        <span class="badge bg-danger rounded-pill"><?php echo $unread_notifications; ?></span>
    <?php } ?>

</button>
<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
    <div class="p-3">
        <div class="row align-items-center">
            <div class="col">
                <h6 class="m-0" key="t-notifications"> <?php echo lang('Notifications'); ?> </h6>
            </div>
            <div class="col-auto">
                <a href="#!" class="small" key="t-view-all" onclick="mark_all_as_read(); return false;"> <?php echo lang('mark_all_as_read'); ?></a>
            </div>
        </div>
    </div>
    <div data-simplebar style="max-height: 230px;">
        <?php
        $user_notifications = $this->global_model->get_user_notifications(false);
        if (!empty($user_notifications)) {
            foreach ($user_notifications as $notification) { 
                ?>

            <a href="<?php echo base_url() . $notification->link; ?>" class="text-reset notification-item">
                <div class="d-flex">
                    <div class="avatar-xs me-3">
                        <span class="avatar-title bg-primary rounded-circle font-size-16">
                            <?php
                            if ($notification->from_user_id != 0) {
                                $img = base_url() . staffImage($notification->from_user_id);
                            } else {
                                $img = 'https://raw.githubusercontent.com/encharm/Font-Awesome-SVG-PNG/master/black/png/128/' . $notification->icon . '.png';
                            } ?>
                            <img src="<?= $img ?>" alt="Avatar" width="40" height="40" class="img-thumbnail img-circle n-image">
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <?php
                        $description = lang($notification->description, $notification->value);
                        if ($notification->from_user_id != 0) { ?>   
                        <h6 class="mt-0 mb-1" key="t-your-order"><?php echo fullname($notification->from_user_id); ?></h6>
                        <?php } ?>
                        <div class="font-size-12 text-muted">
                            <p class="mb-1" key="t-grammer"><?php echo $description; ?></p>
                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-min-ago"> <?php echo time_ago($notification->date); ?></span></p>
                        </div>
                    </div>
                </div>
            </a>
        <?php }
        }
        ?>
    </div>
    <div class="p-2 border-top d-grid">
        <?php if (count($user_notifications) > 0) { ?>
        <a class="btn btn-sm btn-link font-size-14 text-center" href="<?php echo base_url(); ?>admin/user/user_details/<?= $this->session->userdata('user_id') ?>/notifications">
            <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more"><?php echo lang('View_More'); ?></span>
        </a>
        <?php } else { ?>
            <?php echo lang('no_notification'); ?>
        <?php } ?>
    </div>
</div>
<!-- END Dropdown menu-->