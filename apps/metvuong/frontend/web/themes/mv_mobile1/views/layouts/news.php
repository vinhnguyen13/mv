<?php $this->beginContent('@app/views/layouts/_partials/head/container.php', ['options'=>[]]); ?><?php $this->endContent();?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<div id="container">
    <?php if(empty($this->params['noHeader'])){?>
        <?php $this->beginContent('@app/views/layouts/_partials/header.php'); ?><?php $this->endContent();?>
    <?php }?>
    <div id="wrapper">
        <?=$content;?>
    </div>
    <?php if(empty($this->params['noFooter'])){?>
        <?php $this->beginContent('@app/views/layouts/_partials/footer.php'); ?><?php $this->endContent();?>
    <?php }?>
</div>
<?php $this->endContent();?>