<?php $this->load->view('frontend/component/main_header'); ?>
<?php
if (!empty($subview)) {
    echo $subview;
} else {
    $this->load->view('frontend/component/banner');
    $this->load->view('frontend/component/content');
} ?>

<?php $this->load->view('frontend/component/footer'); ?>