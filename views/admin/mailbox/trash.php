<div class="btn-group">
    <a class="btn btn-primary <?php echo ($trash_view == 'inbox') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/mailbox/index/trash/inbox" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('inbox') ?>"><i class="fa fa-inbox "></i> <span class="hidden-xs"><?= lang('inbox') ?></span></a>
    <a class="btn btn-primary <?php echo ($trash_view == 'sent') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/mailbox/index/trash/sent" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('sent') ?>"><i class="fa fa-envelope-o "></i> <span class="hidden-xs"><?= lang('sent') ?></span></a>
    <a class="btn btn-primary <?php echo ($trash_view == 'draft') ? 'active' : ''; ?>"href="<?= base_url() ?>admin/mailbox/index/trash/draft" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('draft') ?>"><i class="fa fa-file-text-o"></i> <span class="hidden-xs"><?= lang('draft') ?></span></a>
</div>
<div style="margin-top: 5px;">
    <?php $this->load->view('admin/mailbox/trash/' . $trash_view) ?>
</div>
