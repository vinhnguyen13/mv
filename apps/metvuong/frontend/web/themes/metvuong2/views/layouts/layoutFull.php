<?php $this->beginContent('@app/views/layouts/_partials/head/container.php', ['options'=>[]]); ?><?php $this->endContent();?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <?php $this->beginContent('@app/views/layouts/_partials/news/nav.php'); ?><?php $this->endContent();?>
    <div class="o-wrapper clearfix">
        <?php $this->beginContent('@app/views/layouts/_partials/header.php'); ?><?php $this->endContent();?>
            <?= $content ?>
<!--        --><?php //$this->beginContent('@app/views/layouts/_partials/footer.php'); ?><!----><?php //$this->endContent();?>
    </div>
<?php $this->endContent();?>

