<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
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
            <div class="card-body">
                <h4 class="card-title mb-4"><?= lang('job_application_list') ?></h4>
                <table class="table table-striped dt-responsive nowrap w-100" id="contentTable">
                    <thead>
                    <tr>
                        <?php super_admin_opt_th() ?>
                        <th><?= lang('job_title') ?></th>
                        <th><?= lang('name') ?></th>
                        <th><?= lang('email') ?></th>
                        <th class="col-sm-1"><?= lang('mobile') ?></th>
                        <th class="col-sm-1"><?= lang('apply_on') ?></th>
                        <th class="col-sm-1"><?= lang('status') ?></th>
                        <th class="col-sm-2"><?= lang('action') ?></th>
                    </tr>
                    </thead>
                    <?php /* ?><tbody>
                        <?php

                        if (!empty($job_application_info)): foreach ($job_application_info as $v_job_application):
                            ?>
                            <tr>
                                <?php super_admin_opt_td($v_job_application->companies_id) ?>
                                <td><?php echo $v_job_application->job_title; ?></td>
                                <td><?php echo $v_job_application->name; ?></td>
                                <td><?php echo $v_job_application->email; ?></td>
                                <td><?php echo $v_job_application->mobile; ?></td>
                                <td><?php echo display_datetime($v_job_application->apply_date); ?></td>

                                <td>
                                    <?php
                                    if ($v_job_application->application_status == 0) {
                                        echo '<span class="badge badge-soft-warning">' . lang('unread') . '</span>';
                                    } elseif ($v_job_application->application_status == 1) {
                                        echo '<span class="badge badge-soft-success">' . lang('approved') . '</span>';
                                    } elseif ($v_job_application->application_status == 2) {
                                        echo '<span class="badge badge-soft-primary">' . lang('primary_selected') . '</span>';
                                    } elseif ($v_job_application->application_status == 3) {
                                        echo '<span class="badge badge-soft-purple">' . lang('call_for_interview') . '</span>';
                                    } else {
                                        echo '<span class="badge badge-soft-danger">' . lang('rejected') . '</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="<?= base_url() ?>admin/job_circular/download_resume/<?= $v_job_application->job_appliactions_id ?>"
                                       class="btn btn-outline-primary btn-sm" data-bs-placement="top"
                                       title="<?= lang('download') . ' ' . lang('resume') ?>"
                                       data-bs-toggle="tooltip"><span
                                            class="fa fa-download"></span></a>
                                    <a href="<?= base_url() ?>admin/job_circular/change_application_status/<?= $v_job_application->job_appliactions_id ?>"
                                       class="btn btn-outline-success btn-sm" title="<?= lang('change_status') ?>" data-bs-toggle="modal"
                                       data-bs-target="#myModal"><span
                                            class="fa fa-pencil-square-o"></span> <?= lang('status') ?></a>
                                    <a href="<?= base_url() ?>admin/job_circular/jobs_application_details/<?= $v_job_application->job_appliactions_id ?>" class="btn btn-outline-info btn-sm" title="View" data-bs-toggle="modal" data-bs-target="#myModal"><span class="fa fa-list-alt"></span></a>
                                    <?php echo btn_delete('admin/job_circular/delete_jobs_application/' . $v_job_application->job_appliactions_id); ?>
                                </td>
                            </tr>
                            <?php

                        endforeach;
                            ?>
                        <?php endif; ?>
                    </tbody><?php */ ?>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Script -->
 <script type="text/javascript">
     $(document).ready(function(){
        $('#contentTable').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
             'url':'<?=base_url()?>admin/datatable/jobs_applications'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_deposit_"+iDisplayIndex);
            return nRow;
          },
          'columns': [
		  <?php if (is_company_column_ag()) { ?>
             { data: 'companies_id' },
		  <?php } ?>
             { data: 'job_title' },
             { data: 'name' },
             { data: 'email' },
             { data: 'mobile' },
             { data: 'apply_on' },
             { data: 'application_status' },
             { data: 'action' },
          ]
        });
     });
 </script>