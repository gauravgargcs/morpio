<div class="col-sm-12 row">
    <div class="cps-service-box">

        <h4 class="cps-service-title"><?php echo $title; ?></h4>
        <hr />

        <?php echo $content; ?>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
    </div>
</div>


<style>

    .cps-service-box{
        /*min-height: 450px !important;*/
        height: auto !important;
        padding-bottom: 30px;
        overflow: scroll auto;

    }

    ::-webkit-scrollbar {

        background: transparent;  /*make scrollbar transparent */
    }

    .modal-footer {
        border-top: none !important;
        margin-top: -90px;

</style>

