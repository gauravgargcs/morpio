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
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <?php $this->load->view("admin/kb/search_box"); ?>
                <div class="nav flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <?php
                    $category_id = null;
                    if (!empty($all_kb_category)) {
                        foreach ($all_kb_category as $kb_category) {
                            if (!empty($articles_info->kb_category_id)) {
                                $category_id = $articles_info->kb_category_id;
                            } else {
                                $category_id = $this->uri->segment(5);
                            }
                            $total_kb = count(get_result('tbl_knowledgebase', array('kb_category_id' => $kb_category->kb_category_id, 'status' => 1)));
                            ?>
                       
                        <a class="nav-link mb-2 <?= !empty($category_id) && $category_id == $kb_category->kb_category_id ? 'sub-active' : '' ?>" href="<?= base_url('admin/knowledgebase/details/kb_category/' . $kb_category->kb_category_id) ?>"><?= $kb_category->category ?> <span class="badge badge-soft-primary pull-right mt-sm"><?= (!empty($total_kb) && $total_kb != 0 ? $total_kb : '') ?></span> </a>
                    <?php } } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <?php
                    $category = $this->uri->segment(4);
                    if ($category == 'kb_category') {
                        $category_info = get_row('tbl_kb_category', array('kb_category_id' => $category_id, 'status' => 1));
                        if (!empty($category_info)) {
                            echo $category_info->category;
                            echo '<small class="block" style="font-size: 12px">' . $category_info->description . '</small>';
                        }
                    } else {
                        if (!empty($articles_info)) {
                            echo $articles_info->title;
                        }
                    } ?>
                </h4>
            
                <div class="accordion" id="accordionExample">
                    <?php
                    if (!empty($articles_by_category)) {
                        foreach ($articles_by_category as $key => $by_category) {
                            if (!empty($by_category)) {
                            ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#ac-<?php echo $key ?>" aria-expanded="true" aria-controls="collapseOne">
                                <strong class="text-alpha-inverse font-size-10"><i class="fa fa-hand-o-right"></i> <?php echo $by_category->title; ?></strong>
                            </button>
                        </h2>
                        <div id="ac-<?php echo $key ?>" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="text-muted">
                                    <?php
                                    echo read_more($by_category->description, 500, 'admin/knowledgebase/details/articles/' . $by_category->kb_id);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } } } else {
                        if (!empty($articles_info)) {
                            echo $articles_info->description;
                            $uploaded_file = json_decode($articles_info->attachments);
                            if (!empty($uploaded_file)):  ?>
                    <hr/>
                    <div class="row">
                        <?php
                        foreach ($uploaded_file as $v_files):
                            if (!empty($v_files)): ?>
                        <div class="col-xl-3 col-6">
                            <div class="card">
                                <?php if ($v_files->is_image == 1) : ?>
                                <img class="card-img-top img-fluid img-thumbnail" src="<?= base_url() . $v_files->path ?>" alt="Attachment">
                                <?php else : ?>
                                <i class="fa fa-file-pdf-o"></i>
                                <?php endif; ?>
                                <div class="py-2 text-center">
                                    <a href="<?= base_url() ?>admin/knowledgebase/download/<?= $articles_info->kb_id . '/' . $v_files->fileName ?>" class="fw-medium"><i class="fa fa-paperclip"></i> <?= $v_files->fileName ?></a>

                                    <span class="mailbox-attachment-size">
                                      <?= $v_files->size ?> <?= lang('kb') ?>
                                        <a href="<?= base_url() ?>admin/knowledgebase/download/<?= $articles_info->kb_id . '/' . $v_files->fileName ?>" class="btn btn-default btn-sm pull-right"><i class="fa fa-cloud-download"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php
                        endif;
                        endforeach;
                        ?>
                    </div>
                    <?php endif;  } } ?>
                </div>
            </div>
        </div>
    </div>
</div>
