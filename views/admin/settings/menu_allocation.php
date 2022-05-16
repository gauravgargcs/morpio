<form method="post" action="<?= base_url() ?>admin/settings/update_menu_allocation" enctype="multipart/form-data"
      class="form-horizontal">
<div class="card" data-spy="scroll" data-offset="0">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    <div class="card-body">
        <div class="pull-right float-end">

            <button type="submit" class="btn btn-sm btn-primary"></i> <?= lang('submit') ?></button> 
        </div>
        <h4 class="card-title mb-4"><?= lang('menu_allocation'); ?></h4>
                    
        <div class="row">
            <div class="col-md-6">
                <div class="card border">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><?= lang('active_menu'); ?></h4>
                   
                        <div id="nestable" class="dd">
                            <?php echo $active_menu ?>
                        </div>
                        <textarea id="nestable-output" name="all_active_menu" class="form-control hidden"></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><?= lang('inactive_menu'); ?></h4>
                       
                        <div id="nestable2" class="dd">
                            <?= !empty($inactive_menu) ? $inactive_menu : '' ?>
                        </div>
                        <textarea id="nestable2-output" name="all_inactive_menu" class="form-control hidden"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<script src="<?php echo base_url(); ?>assets/plugins/nestable/jquery.nestable.js"></script>
<script type="text/javascript">
    // Nestable demo
    // -----------------------------------
    (function (window, document, $, undefined) {

        $(function () {

            var updateOutput = function (e) {
                var list = e.length ? e : $(e.target),
                    output = list.data('output');
                if (window.JSON) {
                    console.log(window.JSON.stringify(list.nestable('serialize')));
                    output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));

                } else {
                    output.val('JSON browser support required for this demo.');
                }
            };
            // activate Nestable for list 1
            $('#nestable').nestable({
                    group: 1
                })
                .on('change', updateOutput);

            // activate Nestable for list 2
            $('#nestable2').nestable({
                    group: 1
                })
                .on('change', updateOutput);
            // output initial serialised data
            updateOutput($('#nestable').data('output', $('#nestable-output')));
            updateOutput($('#nestable2').data('output', $('#nestable2-output')));
            $('.js-nestable-action').on('click', function (e) {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });

        });


    })(window, document, window.jQuery);
</script>
<script type="text/javascript">
    $(document).ready(function(){
      $("#nestable").on('change' ,function () {
          $("#nestable2").trigger('change');
      });
      //  $("#nestable2").on('change' ,function () {
      //     $("#nestable").trigger('change');
      // });

      // $("#nestable").trigger('change');
      //   $("#nestable2").trigger('change');
    });
</script>
