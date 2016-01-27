<?php $this->beginContent('@app/views/layouts/_partials/head/container.php', ['options'=>[]]); ?><?php $this->endContent();?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<div id="container">
    <?php $this->beginContent('@app/views/layouts/_partials/header.php'); ?><?php $this->endContent();?>
    <div id="wrapper">
        <?=$content;?>
    </div>
    <?php $this->beginContent('@app/views/layouts/_partials/footer.php'); ?><?php $this->endContent();?>
</div>
<?php $this->endContent();?>

