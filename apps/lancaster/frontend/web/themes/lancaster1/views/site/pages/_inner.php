<?php
$settings = \yii\helpers\Json::decode($photo->settings, false);
$displayCaption = true;
if(isset($settings->displayCaption)){
    $displayCaption = $settings->displayCaption;
}
?>
    <div class="item<?=($key==0) ? ' active' : ''?>">
    <div class="imgcontent"><img class="first-slide" src="<?=$photo->getThumbUrl('original')?>" alt="First slide"></div>
    <?php if(!empty($photo->name) && $displayCaption == true):?>
    <div class="caption" style="display: none">
        <?=$photo->description;?>
    </div>
    <?php endif; ?>
</div>