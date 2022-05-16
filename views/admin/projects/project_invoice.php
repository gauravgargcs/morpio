<form action="<?php echo base_url() ?>admin/projects/preview_invoice/<?php if (!empty($project_info->project_id)) echo $project_info->project_id; ?>" method="post" class="form-horizontal">
    <div class="modal-header">
        <h5 class="modal-title"><?= lang('preview_invoice')?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="form-check form-radio-outline form-radio-primary mb-3">
                        <input checked type="radio" name="items_name" value="single_line" <?= ($project_info->billing_type == 'tasks_hours' || $project_info->billing_type == 'project_timer') ? 'disabled' : '' ?> class="form-check-input" id="single_line">
                        <label class="form-check-label <?= ($project_info->billing_type == 'tasks_hours' || $project_info->billing_type == 'project_timer') ? 'disabled' : '' ?>" for="single_line"><?= lang('single_line') ?>
                            <i title="<?= lang('single_line_help') ?>"
                               class="fa fa-question-circle" data-html="true" data-bs-toggle="tooltip"
                               data-bs-placement="top"></i>
                        </label>
                    </div>
                    <div class="form-check form-radio-outline form-radio-primary mb-3">
                        <input <?= ($project_info->billing_type == 'tasks_hours' || $project_info->billing_type == 'fixed_rate') ? 'disabled' : '' ?>  type="radio" name="items_name" value="project_timer" class="form-check-input" id="project_timer">
                        <label class="form-check-label <?= ($project_info->billing_type == 'tasks_hours' || $project_info->billing_type == 'fixed_rate') ? 'disabled' : '' ?>" for="project_timer"><?= lang('project_timer') ?>
                            <i title="<?= lang('project_timer_help') ?>"
                               class="fa fa-question-circle" data-html="true" data-bs-toggle="tooltip"
                               data-bs-placement="top"></i>
                        </label>
                    </div>
                    <div class="form-check form-radio-outline form-radio-primary mb-3">
                        <input <?= ($project_info->billing_type == 'tasks_hours' || $project_info->billing_type == 'tasks_and_project_hours') ? 'checked' : '' ?> <?= ($project_info->billing_type == 'fixed_rate') ? 'disabled' : '' ?> type="radio" name="items_name" value="task_per_item" class="form-check-input" id="task_per_item">
                        <label class="form-check-label <?= ($project_info->billing_type == 'fixed_rate') ? 'disabled' : '' ?>" for="task_per_item"><?= lang('task_per_item') ?>
                            <i title="<?= lang('task_per_item_help') ?>"
                               class="fa fa-question-circle" data-html="true" data-bs-toggle="tooltip"
                               data-bs-placement="top"></i>
                        </label>
                    </div>
                    <div class="form-check form-radio-outline form-radio-primary mb-3">
                        <input id="" <?= ($project_info->billing_type == 'fixed_rate') ? 'disabled' : '' ?> type="radio" name="items_name" value="all_timesheet_individually" class="form-check-input" id="all_timesheet_individually">
                        <label class="form-check-label <?= ($project_info->billing_type == 'fixed_rate') ? 'disabled' : '' ?>" for="all_timesheet_individually"><?= lang('all_timesheet_individually') ?>
                            <i title="<?= lang('all_timesheet_individually_help') ?>"
                               class="fa fa-question-circle" data-bs-toggle="tooltip" data-html="true"
                               data-bs-placement="top"></i>
                        </label>
                    </div>
                </div>
                <?php
                $all_task_info = $this->db->where('project_id', $project_info->project_id)->order_by('task_id', 'DESC')->get('tbl_task')->result();
                $all_expense_info = $this->db->where(array('project_id' => $project_info->project_id, 'type' => 'Expense'))->order_by('transactions_id', 'DESC')->get('tbl_transactions')->result();
                if (!empty($all_task_info)) { ?>
                    <div class="row mb-3">
                        <a href="#" onclick="slideToggle('#tasks_who_will_be_billed'); return false;"><b><?= lang('see_task_on_invoice') ?></b></a>
                        <div id="tasks_who_will_be_billed" style="display: none;">
                            <div class="form-check form-check-primary mb-3 mr mt">
                                <input type="checkbox" id="select_all_tasks" class="invoice_select_all_tasks form-check-input">
                                <label class="form-check-label" for="select_all_tasks"><?= lang('select_all') . ' ' . lang('task') ?>
                                </label>
                            </div>
                            <hr class="mr0 mb0 mt-sm">
                            <?php foreach ($all_task_info as $v_tasks) { ?>
                                <div class="col-sm-10">
                                    <div class="form-check form-check-primary mb-3 mr mt">
                                        <input value="<?= $v_tasks->task_id ?>" checked name="tasks[]" class="tasks_list form-check-input" type="checkbox" id="tk-<?= $v_tasks->task_id ?>">
                                        <label class="form-check-label" for="tk-<?= $v_tasks->task_id ?>">
                                            <strong class="inline-block"><?= $v_tasks->task_name ?></strong>
                                                <?php
                                                $time = $this->items_model->get_spent_time($this->items_model->task_spent_time_by_id($v_tasks->task_id), true);
                                                if ($time != "0 : 0 : 0") {
                                                    echo '<small><strong>' . $time . '</strong></small>';
                                                } else {
                                                    ?>
                                                    <small class="text-danger"><?= lang('no_timer_for_task') ?></small>
                                                <?php }
                                                ?>
                                        </label>
                                    </div>
                                </div>
                                <?php
                                if ($v_tasks->task_status == 'completed') {
                                    $label = 'success';
                                } elseif ($v_tasks->task_status == 'not_started') {
                                    $label = 'info';
                                } elseif ($v_tasks->task_status == 'deferred') {
                                    $label = 'danger';
                                } else {
                                    $label = 'warning';
                                }
                                ?>
                                <div class="col-sm-2 mt-sm ">
                                    <small class=""><strong class="inline-block label label-<?= $label ?>"><?= lang($v_tasks->task_status) ?></strong>
                                    </small>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <strong class="text-danger"> <?= lang('no_tasks_to_bill_in_invoice'); ?></strong>
                <?php }
                if (!empty($all_expense_info)) {
                    ?>
                    <div class="row mb-3">
                        <a href="#"
                           onclick="slideToggle('#expense_who_will_be_billed'); return false;"><b><?= lang('see_expense_on_invoice') ?></b></a>
                        <div id="expense_who_will_be_billed" style="display: none;">
                            <div class="form-check form-check-primary mb-3 mr mt">
                                <input type="checkbox" id="select_all_expense" class="invoice_select_all_tasks form-check-input">
                                 <label class="form-check-label"><?= lang('select_all') . ' ' . lang('expense') ?>
                                </label>
                            </div>
                            <hr class="mr0 mb0 mt-sm">
                            <?php foreach ($all_expense_info as $v_expense) {
                                $category_info = $this->db->where('expense_category_id', $v_expense->category_id)->get('tbl_expense_category')->row();
                                if (!empty($category_info)) {
                                    $category = $category_info->expense_category;
                                } else {
                                    $category = 'Undefined Category';
                                }
                                $curency = $this->items_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                ?>
                                <div class="col-sm-10">
                                    <div class="form-check form-check-primary mb-3 mr mt">
                                        <input name="expense[]" value="<?= $v_expense->transactions_id ?>" checked class="expense_list form-check-input" type="checkbox" id="ex-<?= $v_expense->transactions_id ?>">
                                        <label class="form-check-label" for="ex-<?= $v_expense->transactions_id ?>"> <strong class="inline-block"><?= $category . ' [' . $v_expense->name . ']' ?></strong>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-2 mt-sm ">
                                    <small class=""><strong class="inline-block"><?= display_money($v_expense->amount, $curency->symbol) ?></strong>
                                    </small>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php }
                if (!empty($all_task_info)) { ?>
                    <strong class="text-danger"><?= lang('all_billed_tasks_marked'); ?></strong>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('invoice_project') ?></button>            
        </div>
    </div>
</form>
<script>
    function slideToggle($id) {
        $($id).slideToggle("slow");
    }
    $(document).ready(function () {
        $("#select_all_tasks").click(function () {
            $(".tasks_list").prop('checked', $(this).prop('checked'));
        });
        $("#select_all_expense").click(function () {
            $(".expense_list").prop('checked', $(this).prop('checked'));
        });
        $('[data-bs-toggle="popover"]').popover();

    });
</script>
