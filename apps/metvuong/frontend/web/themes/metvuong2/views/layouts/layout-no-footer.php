<?php $this->beginContent('@app/views/layouts/_partials/head/container.php', ['options'=>[]]); ?><?php $this->endContent();?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <?php $this->beginContent('@app/views/layouts/_partials/news/nav.php'); ?><?php $this->endContent();?>
    <div class="o-wrapper clearfix">
        <?php $this->beginContent('@app/views/layouts/_partials/header.php'); ?><?php $this->endContent();?>
        <?= $content ?>
    </div>
<?php $this->endContent();?>