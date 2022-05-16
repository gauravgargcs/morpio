<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                 <?= '<b>' . lang('version') . '</b> ' . config_item('version') ?>
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    <strong>&copy; <a href="<?= getConfigItems('copyright_url') ?>"> <?= getConfigItems('copyright_name') ?></a>.</strong> 
                    All rights reserved.
                </div>
            </div>
        </div>
    </div>
</footer>
<!--- dropzone ---->
<?php if (!empty($dropzone)) { ?>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>skote_assets/plugins/dropzone/dropzone.min.css">
    <script type="text/javascript" src="<?= base_url() ?>skote_assets/plugins/dropzone/dropzone.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>skote_assets/plugins/dropzone/dropzone.custom.min.js"></script>
    <script type="text/javascript">// Immediately after the js include
      Dropzone.autoDiscover = false;
    </script>
<?php } ?>
<!-- EASY PIE CHART-->
<script src="<?php echo base_url() ?>skote_assets/plugins/easy-pie-chart/jquery.easypiechart.min.js"></script>
<?php if (empty($dataTables)) { ?>
    <?php include_once 'skote_assets/plugins/dataTables/js/dataTables.php'; ?>


<?php } ?>
<script type="text/javascript">
    $(document).ready(function () {
       $(window).scroll(function(){

          if (($(this).scrollTop()) > ($('.page-content').height() - window.innerHeight + $('.footer').height() + 80 )) {
              $('.footer').addClass('fix-footer');

          } else {
              $('.footer').removeClass('fix-footer');

          }
      });
    })
</script>
<style type="text/css">
    .fix-footer{
        position: fixed;
        bottom: 0;
    }
</style>

<?php
$profile = profile();
$chat = false;
if (!empty($profile)) {
    $role = $profile->role_id;
    if ($role == 2) { // check client menu permission
        $chat_menu = get_row('tbl_client_role', array('user_id' => $profile->user_id, 'menu_id' => client_menu_by_company(19)));
        if (!empty($chat_menu)) {
            $chat = true;
        }
        $this->view = 'client/';
    } elseif ($role != 1) {// check staff menu permission
        if (!empty($profile->designations_id)) {
            $user_menu = get_row('tbl_user_role', array('designations_id' => $profile->designations_id, 'menu_id' => menu_by_company(139)));
            if (!empty($user_menu)) {
                $chat = true;
            }
        }
    } else if ($role == 1) {
        $chat = true;
    }
}

if (!empty($chat)) {
    ?>
    <!--star live_chat_section-->
    <?php $this->load->view('chat/chat_list') ?>
<?php } ?>

