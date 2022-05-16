<?php
echo message_box('success');
echo message_box('error'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            

        </div>
    </div>
</div>
<!-- end page title -->
<?php 
$todo_status = $this->admin_model->get_todo_status();
$kanban = $this->session->userdata('todo_kanban');
$uri_segment = $this->uri->segment(4);
if (!empty($kanban)) {
    $todo = 'kanban';
} elseif ($uri_segment == 'kanban') {
    $todo = 'kanban';
} else {
    $todo = 'list';
}

if ($todo == 'kanban') {
    $text = 'list';
    $btn = 'primary';
} else {
    $text = 'kanban';
    $btn = 'danger';
} ?>
<div class="row">
    <div class="mb-lg pull-left col-xl-7">
        <div class="pull-left">
            <a href="<?= base_url() ?>admin/dashboard/all_todo/<?= $text ?>" class="btn btn-xs btn-<?= $btn ?>" data-bs-toggle="tooltip"  data-bs-placement="top" title="<?= lang('switch_to_' . $text) ?>"><i class="fa fa-undo"> </i><?= ' ' . lang('switch_to_' . $text) ?>
            </a>
        </div>
    </div>
    <div class="pull-right float-end text-sm col-xl-3">
    
    <?php if ($this->session->userdata('user_type') == 1) {
    $all_users = get_result('tbl_users', array('role_id !=' => 2, 'activated' => 1)); ?>
        <form class="" role="form" id="form" action="<?= base_url() ?>admin/dashboard/all_todo" method="post">
            <div class="input-group position-relative" id="IncomeDate">
                <select name="user_id" style="width: 78%" required id="employee" class="form-control select_box">
                    <option value=""><?= lang('select') . ' ' . lang('users') ?>...</option>
                    <option value="0" <?php if ($user_id==0) { echo "selected"; } ?>><?= lang('all') . ' ' . lang('users') ?></option>
                    <?php if (!empty($all_users)): ?>
                        <?php foreach ($all_users as $v_user) :
                            $user_profile = $this->db->where(array('user_id' => $v_user->user_id))->get('tbl_account_details')->row();
                            ?>
                            <option value="<?php echo $v_user->user_id; ?>"
                                <?php
                                
                                if (!empty($user_id)) {
                                    echo $v_user->user_id == $user_id ? 'selected' : '';
                                }
                                ?>><?php echo $user_profile->fullname ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <button type="submit" title="Search" name="flag" value="1" data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-primary mt-sm-10" data-original-title="Search"><span class="bx bx-search-alt"></span></button>
            </div>                                
        </form>
    <?php } ?>
    </div>          
    <div class="pull-right float-end col-xl-2">
        <a class="btn btn-xs btn-success new_todo_link" href="<?= base_url() ?>admin/dashboard/new_todo" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal"><?= lang('add_new') ?></a>
    </div>
</div>

<?php if ($todo == 'kanban') { ?>
   <?php $this->load->view('admin/settings/all_todo_kanban'); ?>
<?php } else { ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 0 ? 'active' : '' ?>" href="<?= base_url().'admin/dashboard/all_todo' ?>"><?= lang('all') ?></a>
                    </li>
                    <?php 
                    foreach ($todo_status as $v_status) {
                        $status=$v_status['value'];
                        if($user_id>0){
                            $t_where = array('user_id' => $user_id, 'status' => $status);
                        }else{
                            $t_where = array('status' => $status);
                        }
                        if (!empty($where)) {
                            $t_where = array_merge($t_where, $where);
                        }
                        $all_todo = $this->db->where($t_where)->order_by('order', 'ASC')->get('tbl_todo')->result();

                        $total_status = count($all_todo); 
                    ?>
                            
                    <li class="nav-item waves-light"><a class="nav-link pull-right <?= $active == $status ? 'active' : ''; ?>" href="<?= base_url() ?>admin/dashboard/all_todo/<?= $status ?>"><?= $v_status['name'] ?><small class="badge badge-soft-danger ml"><?php if ($total_status != 0) { echo $total_status; } ?></small></a></li>

                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- Stock Category List tab Starts -->
                    <div class="tab-pane <?= $active >=0 ? 'active' : '' ?>" id="task_list">
                        <div class="dropdown tbl-action">
                            <button class="btn btn-primary dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_status') ?><i class="mdi mdi-chevron-down"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <?php 
                                foreach ($todo_status as $v_status) {
                                    $status=$v_status['value']; 
                                    $status_name=$v_status['name']; 
                                    ?>
                                <a class="dropdown-item" onclick="multipe_change_status('<?= $status; ?>')" href="#"><?= $status_name; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                
                        <!-- <div class="table-responsive"> -->
                            <table class="table table-striped dt-responsive w-100" id="list_todo_datatable">
                                <thead>
                                <tr>
                                    <th data-check-all style="width: 8%;">
                                        <div class="form-check font-size-16 check-all">
                                            <input type="checkbox" id="parent_present" class="form-check-input">
                                            <label for="parent_present" class="toggle form-check-label"></label>
                                        </div>
                                    </th>
                                    <th style="width: 15%;"><?= lang('what') . ' ' . lang('to_do') ?></th>
                                    <th style="width: 20%" class="text-wrap"><?= lang('notes') ?></th>
                                    <th style="width: 10%;"><?= lang('status') ?></th>
                                    <th style="width: 10%;"><?= lang('end_date') ?></th>
                                    <th style="width: 10%;"><?= lang('assigned_to') ?></th>
                                    <th style="width: 10%;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                
                                <?php
                                if($user_id>0){
                                    $t_where = array('user_id' => $user_id);
                                }else{
                                    $t_where = array();
                                }
                                if (!empty($where)) {
                                    $t_where = array_merge($t_where, $where);
                                }
                                if(!empty($filterByStatus)){
                                    $t_where = array_merge($t_where, $filterByStatus);
                                }
                                $my_todo_list = $this->db->where($t_where)->order_by('todo_id', 'DESC')->get('tbl_todo')->result();
                                if (!empty($my_todo_list)):foreach ($my_todo_list as $tkey => $my_todo):

                                    $mytodo_status=$my_todo->status;

                                    $mytodo_status_arr = $this->admin_model->get_todo_status($mytodo_status);

                                    $mytodo_status_name= $mytodo_status_arr[0]['name'];
                                    $mytodo_status_label= $mytodo_status_arr[0]['label'];
                                    if (!$mytodo_status_label) {
                                        $mytodo_status_label = 'primary';
                                    }

                                    $todo_label = '<small style="font-size:10px;padding:2px;" class="badge badge-soft-'.$mytodo_status_label.'">' .$mytodo_status_name. '</small>';
                                    
                                    if (!empty($my_todo->due_date)) {
                                        $due_date = display_datetime($my_todo->due_date);
                                    } else {
                                        $due_date = date('d-m-Y H-i');
                                    }

                                    $todo_user_id=$my_todo->user_id;

                                    ?>
                                    <tr class="item" data-item-id="<?= $my_todo->todo_id ?>">
                                        <td style="width: 8%;">
                                            <div class="form-check font-size-16">
                                                <input class="action-check form-check-input" type="checkbox" data-id="<?= $my_todo->todo_id ?>" style="position: absolute;" name="todo_id[]" value="<?= $my_todo->todo_id ?>">
                                                <label class="form-check-label"></label>
                                            </div>
                                        </td>
                                    
                                        <td style="width: 15%;">
                                            <a <?php
                                            if ($my_todo->status == 3) {
                                                echo 'style="text-decoration: line-through;"';
                                            }
                                            ?> class="text-info" data-bs-toggle="modal" data-bs-target="#myModal_lg"
                                               href="<?= base_url() ?>admin/dashboard/new_todo/<?= $my_todo->todo_id ?>">
                                                <?php echo $my_todo->title; ?></a>
                                            <?php if (!empty($my_todo->assigned) && $my_todo->assigned != 0) {
                                                $a_userinfo = $this->db->where('user_id', $my_todo->assigned)->get('tbl_account_details')->row();
                                                ?>
                                                <small class="block" data-bs-toggle="tooltip"
                                                       data-bs-placement="top"><?= lang('assign_by') ?><a
                                                        class="text-danger"
                                                        href="<?= base_url() ?>admin/user/user_details/<?= $my_todo->assigned ?>"> <?= $a_userinfo->fullname ?></a>
                                                </small>
                                            <?php } ?>
                                        </td>
                                        <td style="width: 20%" class="text-break"><?php echo $my_todo->notes; ?></td>

                                        <td style="width: 10%;">
                                            <?= $todo_label ?>
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupVerticalDrop1" type="button" class="btn btn-success dropdown-toggle font-size-11 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?= lang('change_status') ?>">
                                                    <i class="bx bxs-edit-alt"></i><i class="mdi mdi-chevron-down"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                                    <?php 
                                                    foreach ($todo_status as $v_status) {
                                                        $status=$v_status['value']; 
                                                        $status_name=$v_status['name']; 
                                                        ?>
                                                    <a class="dropdown-item" href="<?= base_url() ?>admin/dashboard/change_todo_status/<?= $my_todo->todo_id . '/'.$status ?>"><?= $status_name; ?></a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width: 10%;">
                                            <strong data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="<?= date("l", strtotime($due_date)) ?>"><?= display_datetime($due_date) ?>
                                                <span class="block"><?= daysleft($due_date) ?></span>

                                            </strong>

                                        </td>
                                        <td style="width: 10%;">
                                            <?php if ($todo_user_id != 0) {
                                            $todo_userinfo = $this->db->where('user_id', $todo_user_id)->get('tbl_account_details')->row();
                                            ?>

                                            <div class="avatar-group">
                                                <div class="avatar-group-item">
                                                    <a href="<?=base_url('admin/user/user_details/'.$todo_user_id);?>" data-bs-toggle="tooltip"
                                                       data-bs-placement="top" title="<?=$todo_userinfo->fullname;?>" class="d-inline-block">
                                                       <img src="<?= base_url() . $todo_userinfo->avatar ?>" class="rounded-circle avatar-xs" alt="">
                                                    </a>
                                                </div>

                                            </div>
                                            <?php } ?>
                                        </td>
                                        <td style="width: 10%;"><?= btn_edit_modal('admin/dashboard/new_todo/' . $my_todo->todo_id) ?>
                                            <?= btn_delete('admin/dashboard/delete_todo/' . $my_todo->todo_id) ?></td>
                                    </tr>
                                    <?php
                                endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function () {
        // init_items_sortable(true);
        <?php if($id=="new"){ ?> 
            $('.new_todo_link').click();
        <?php  } ?>
    });
    function checkSelected(){
        if($(".action-check").is(":checked")){
            return true;
        }
        toastr['error']('Please select any record');
        return false
    }
    function multipe_change_status(status){
        if(!$(".action-check").is(":checked")){
             toastr['error']('Please select any record');
          return false
        }
        if( !confirm("<?=lang('are_you_sure_want_to_update_status_for_selected_tasks');?>")){
            return false;
        }
        var todo_id = [];
          $(".action-check:checked").each(function(){
             todo_id.push($(this).val());
        });
        $('#loader-wrapper').show();

       // data = data.serialize()
        // console.log(data);
          $.ajax({
           url: '<?=site_url('admin/dashboard/multiple_todo_change_status');?>',
           data: {todo_id:todo_id, status:status },
          
           type: 'POST',
           success: function(data){
           data = jQuery.parseJSON(data);
              $('#loader-wrapper').hide();
             if(data['success']==true){
                 toastr['success'](data['message']);
                 window.location.reload();
             }else{
                toastr['erorr'](data['message']);
             }
             
        },
           error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus+" "+errorThrown);
          }
        });
    }
</script>
