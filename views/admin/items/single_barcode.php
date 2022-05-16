<style type="text/css" media="print">
    @page {
        margin-top: 10px;
        margin-bottom: 10px;
        size: auto;
    }

    .content-heading {
        display: none !important;;
    }

    body {
        padding-top: 72px;
        padding-bottom: 72px;
    }

    a[href]:after {
        content: none !important;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="pull-right float-end">       
                    <button type="button" class="pull-right btn btn-danger btn-sm mr" href="javascript:void();" onclick="window.print();">
                        <i class="fa fa-print"></i>
                    </button>
                </div>
                <h4 class="card-title mb-4">
                    <?= $title; ?>
                </h4>
                <div class="row" id="printableArea">
                    <?= $html ?>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top text-muted no-print">
                <a href="<?= base_url('admin/items/items_list')?>" class="btn btn-primary pull-left mr-5"><?= lang('close'); ?></a>
                <button class="btn btn-danger" href="javascript:void();" onclick="window.print();"><i class="fa fa-print"></i> <?= lang('print'); ?></button>
            </div>
        </div>
    </div>
</div>

