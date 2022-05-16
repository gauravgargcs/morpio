<?php echo message_box('success'); ?>

<?php echo message_box('error');

$created = can_action('142', 'created');

$edited = can_action('142', 'edited');

$deleted = can_action('142', 'deleted');

?>
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
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body bordered">
                <?php if (!empty($created)) { ?>
                <div class="pull-right hidden-print float-end">
                    <a href="<?= base_url() ?>admin/knowledgebase/new_categories" class="btn btn-sm btn-primary"  data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal"><i class="fa fa-plus "></i> <?= ' ' . lang('new') . ' ' . lang('categories') ?></a>
                </div>
                <?php } ?>
                <h4 class="card-title mb-4"><?= lang('category') ?></h4>
                <table class="table table-striped dt-responsive nowrap w-100" id="all_kb_cat_datatable">
                    <thead>
                        <tr>
                            <?php super_admin_opt_th() ?>
                            <th><?= lang('categories') ?></th>
                            <th><?= lang('description') ?></th>
                            <th class="col-sm-1"><?= lang('active') ?></th>
                            <th class="col-sm-1"><?= lang('order') ?></th>
                            <th><?= lang('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($all_kb_category_info)):foreach ($all_kb_category_info as $key => $v_kb_category): ?>
                        <tr>
                            <?php super_admin_opt_td($v_kb_category->companies_id) ?>
                            <td><?php echo $v_kb_category->category; ?></td>
                            <td><?php echo $v_kb_category->description; ?></td>
                            <td>
                                <div class="form-check form-switch mb-3 change_kb_category">
                                    <input class="form-check-input" data-id="<?= $v_kb_category->kb_category_id ?>" data-bs-toggle="toggle" name="active" value="1" <?php if (!empty($v_kb_category->status) && $v_kb_category->status == '1') { echo 'checked'; } ?> type="checkbox">
                                </div>
                            </td>
                            <td><?php echo $v_kb_category->sort; ?></td>
                            <td>
                                <?php if (!empty($edited)) { ?>
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('edit') ?>"> <a href="<?= base_url() ?>admin/knowledgebase/new_categories/<?= $v_kb_category->kb_category_id ?>" class="btn btn-outline-success btn-sm"  data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal"> <i class="fa fa-pencil-square-o"></i> </a> </span>
                                <?php }
                                if (!empty($deleted)) { ?>
                                    <?php echo btn_delete('admin/knowledgebase/delete_categories/' . $v_kb_category->kb_category_id) ?>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.change_kb_category input[type="checkbox"]').change(function () {
            var kb_category_id = $(this).data().id;
            var status = $(this).is(":checked");
            if (status == true) {
                status = 1;
            } else {
                status = 2;
            }
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= base_url()?>admin/knowledgebase/change_status/' + status + '/' + kb_category_id, // the url where we want to POST
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
                success: function (res) {
                    if (res) {
                        toastr[res.status](res.message);
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })
        });
    })
</script>