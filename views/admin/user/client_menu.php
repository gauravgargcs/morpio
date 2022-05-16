<link href="<?php echo base_url() ?>asset/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="<?php echo base_url() ?>asset/js/bootstrap-toggle.min.js"></script>
<?php
if (!empty($login_info)) {
    $user_menu = $this->db->where('user_id', $login_info->user_id)->get('tbl_client_role')->result();
}
foreach ($all_client_menu as $key => $v_menu) {
    ?>
    <div class="row mb-3">
        <label  class="col-lg-3 form-label"><?= lang($v_menu->label) ?></label>
        <div class="col-sm-9">
            <div class="form-check form-check-primary mb-3">
                <input <?php
                    if (!empty($user_menu)) {
                        foreach ($user_menu as $v_u_menu) {
                            if ($v_u_menu->menu_id == $v_menu->menu_id) {
                                echo 'checked';
                            }
                        }
                    } ?> name="<?= $v_menu->label ?>" value="<?= $v_menu->menu_id ?>" type="checkbox" class="form-check-input" id="c_<?= $v_menu->menu_id ?>">
                <label class="form-check-label" for="c_<?= $v_menu->menu_id ?>"></label>
            </div>
        </div>
    </div>
    <?php
}
?>