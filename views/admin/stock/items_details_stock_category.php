<div class="panel panel-custom">
    <div class="panel-heading">
        <h4 class="modal-title"
            id="myModalLabel"><?= (!empty($sub_category) ? $sub_category->stock_sub_category : '') . ' ' . lang('details') ?>
        </h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="panel-body form-horizontal">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="font-size: 13px;color: #000000;border: 1px solid #eee">
                    <th><?= lang('item_name') ?></th>
                    <th><?= lang('total_stock') ?></th>
                </tr>
                </thead>
                <tbody style="margin-bottom: 0px;background: #FFFFFF;font-size: 12px;">
                <?php
                if (!empty($items_details)) {
                    foreach ($items_details as $stock) :
                        $itemsInfo = itemsInfo($stock->saved_items_id);
                        ?>
                        <tr>
                            <td><?php echo !empty($itemsInfo->item_name) ? $itemsInfo->item_name : '-'; ?></td>
                            <td><?php echo $stock->total_stock ?></td>
                        </tr>
                    <?php endforeach;
                } else {
                    ?>
                    <tr>
                        <td colspan="2"><?= lang('no_data_available') ?></td>
                    </tr>
                <?php }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
    </div>
</div>
