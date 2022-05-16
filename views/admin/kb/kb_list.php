<style type="text/css">
    .widget {
        overflow-wrap: break-word;
    }
</style>
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
                <div class="row search-box mb-4">
                     <div class="col-lg-4"></div>
                     <div class="col-lg-5">
                        <h2 class="text-center"><?= lang('how_can_we_help') ?></h2>
                        <?php $this->load->view("admin/kb/search_box"); ?>
                    </div>
                </div>

                <?php
                if (!empty($all_kb_category)) {
                    foreach ($all_kb_category as $kb_category) {
                        $total_kb = count(get_result('tbl_knowledgebase', array('kb_category_id' => $kb_category->kb_category_id, 'status' => 1)));
                        ?>
                <div class="row">
                    <div class="col-lg-4 kb">
                        <a href="<?= base_url('admin/knowledgebase/details/kb_category/' . $kb_category->kb_category_id) ?>"
                           style="text-decoration: none;color: inherit"><!-- START widget-->
                            <div class="card widget border">
                                <div class="card-body ">
                                    <div class="row row-table row-flush text-center">
                                        <h4><?= $kb_category->category ?></h4>
                                        <p><?= $kb_category->description ?>
                                        </p>
                                        <p>
                                            <strong> <?= !empty($total_kb) ? $total_kb : lang('no');
                                                echo ' ' . lang('articles') ?> </strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a><!-- END widget-->
                    </div>
                </div>
                <?php }
                } ?>
            </div>
        </div>
    </div>
</div>