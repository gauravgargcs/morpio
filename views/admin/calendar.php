<?php
echo message_box('success');
echo message_box('error');
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
    <?php $this->load->view('admin/calendar_body'); ?>          
</div>
<?php if($action=="new"){
     ?> 

<script type="text/javascript">
    $(document).ready(function () {
        $('#btn-new-event').click();
    })
</script>
<?php } ?>