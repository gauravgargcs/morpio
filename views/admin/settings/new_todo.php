<?php //  include_once 'asset/admin-ajax.php'; ?>
<?php  echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<form action="<?php echo base_url() ?>admin/dashboard/save_todo/<?php if (!empty($todo_info->todo_id)) { echo $todo_info->todo_id; } ?>" method="post">
    <div class="modal-header">
        <h5 class="modal-title"><?= lang('new') . ' ' . lang('to_do') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <?php
            if ($this->session->userdata('user_type') == 1) {
                $all_users = get_result('tbl_users', array('role_id !=' => 2, 'activated' => 1));
                ?>
            <div class="col-xl-12">
                <div class="mb-3" id="border-none">
                    <label class="form-label"><?= lang('users') ?> <span class="required">*</span></label>
                    <select name="user_id" style="width: 100%" id="employee" required class="form-control modal_select_box">
                        <option value=""><?= lang('select_employee') ?>...</option>
                        <?php if (!empty($all_users)): ?>
                            <?php foreach ($all_users as $v_user) :
                                $user_profile = $this->db->where(array('user_id' => $v_user->user_id))->get('tbl_account_details')->row();
                                ?>
                                <option value="<?php echo $v_user->user_id; ?>"
                                    <?php
                                    if (!empty($todo_info->user_id)) {
                                        $user_id = $todo_info->user_id;
                                    } else {
                                        $user_id = $this->session->userdata('user_id');
                                    }
                                    if (!empty($user_id)) {
                                        echo $v_user->user_id == $user_id ? 'selected' : '';
                                    }
                                    ?>><?php echo $user_profile->fullname ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <?php } ?>
            <div class="col-xl-12">
               <div class="mb-3">
                    <label class="form-label"><?= lang('what') . ' ' . lang('to_do') ?><span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" required="required" value="<?php if (!empty($todo_info->title)) {echo $todo_info->title;}?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
               <div class="mb-3">
                    <label class="form-label"><?= lang('notes') ?></label>

                    <textarea name="notes" class="form-control" rows="3"><?php if (!empty($todo_info->notes)) {echo $todo_info->notes;}?></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="mb-3">
                    <label class="form-label"><?= lang('status') ?></label>
                    <?php
                    if (!empty($todo_info->status)) {
                        $todo_status = $todo_info->status;
                    } else {
                        $todo_status = null;
                    }
                    $options=array();
                    $all_status = $this->admin_model->get_todo_status();
                    foreach ($all_status as $v_status) {
                        $status=$v_status['value']; 
                        $status_name=$v_status['name']; 
                        $options[$status]=$status_name;
                    }
                    echo form_dropdown('status', $options, $todo_status, 'style="width:100%" class="form-select"'); ?>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="mb-3">
                    <label class="form-label"><?= lang('due_date') ?> <span class="required">*</span></label>
                    <div class="input-group" id="datepicker1">
                        <input required type="text" name="due_date" placeholder="<?= lang('enter') . ' ' . lang('due_date') ?>" class="form-control datepicker" autocomplete="off" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container='#datepicker1' value="<?php
                        if (!empty($todo_info->due_date)) {
                            echo date('d-m-Y H-i', strtotime($todo_info->due_date));
                        } else {
                            echo date('d-m-Y H-i');
                        }
                        ?>">
                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                    
                    </div>
                </div>        
            </div>
        </div>
    <div class="modal-footer">
        <div class="mb-3">
            <div class="col-sm-offset-3 col-sm-7">
                <button type="submit" id="sbtn" name="sbtn" value="1" class="btn btn-primary"><?= lang('save') ?></button>
            </div>
        </div>
    </div>
</form>