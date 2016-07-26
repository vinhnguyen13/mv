<?php $this->beginContent('@app/views/layouts/_partials/head/container.php', ['options'=>[]]); ?><?php $this->endContent();?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<div id="container">
    <?php if(empty($this->params['noHeader'])){?>
        <?php $this->beginContent('@app/views/layouts/_partials/header.php'); ?><?php $this->endContent();?>
    <?php }?>
    <div id="wrapper" class="">
        <?php if(!empty($this->params['isDashboard'])){?>
            <?php $this->beginContent('@app/views/layouts/_partials/menuUser.php'); ?><?php $this->endContent();?>
        <?php }?>
        <?php if(!empty($this->params['isReport'])){?>
            <?php $this->beginContent('@app/views/layouts/_partials/menuReport.php'); ?><?php $this->endContent();?>
        <?php }?>
        <div class="contentContainer">
            <?=$content;?>
        </div>
    </div>
    <?php if(empty($this->params['noFooter'])){?>
        <?php $this->beginContent('@app/views/layouts/_partials/footer.php'); ?><?php $this->endContent();?>
    <?php }
    $this->beginContent('@app/views/layouts/_partials/tutorial.php');$this->endContent();
    $this->beginContent('@app/views/layouts/_partials/betaAutoGetCoupon.php');$this->endContent();
    ?>
</div>
<?php $this->endContent();?>

