<?php $this->beginContent('@app/views/layouts/layout.php'); ?>
<div class="wrap-user-profile row">
    <?php $this->beginContent('@app/views/user-management/layouts/menu.php'); ?><?php $this->endContent();?>
    <?= $content ?>
</div>
<?php $this->endContent();?>
