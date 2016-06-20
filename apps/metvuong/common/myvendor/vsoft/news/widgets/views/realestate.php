<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 */
use yii\helpers\StringHelper;
Yii::t('news', 'REAL ESTATE NEWS');
Yii::t('news', 'HOT NEWS');
Yii::t('news', 'FINANCIAL & BANKING NEWS');

if(count($news) > 0){
?>
<div class="item-sidebar clearfix">
    <div class="title-sidebar">
        <?php if($cat_id > 0) {?>
            <a href="<?= \yii\helpers\Url::to(['news/list', 'cat_id' => $cat_id, 'cat_slug' => $cat_slug]) ?>"><?=Yii::t('news', $title)?></a>
        <?php } else {?>
            <?=Yii::t('news', $title)?>
        <?php } ?>
    </div>
    <?php foreach($news as $k => $n) {
        if($k == 0){
            $banner = \vsoft\news\models\CmsShow::getBanner($n->banner);?>
        <div class="item-hot-sidebar">
            <a href="<?=\yii\helpers\Url::to(['news/view', 'id' => $n->id, 'slug' => $n->slug], true)?>" title="<?=$n->title?>">
                <div class="img-show">
                    <div><img src="<?= $banner ?>" alt="<?=$n->title?>"></div>
                </div>
            </a>
            <a href="<?=\yii\helpers\Url::to(['news/view', 'id' => $n->id, 'slug' => $n->slug], true)?>" title="<?=$n->title?>" class="name-post"><?= StringHelper::truncate($n->title, 60) ?></a>
            <p class="intro-txt" title="<?=$n->brief?>"><?= StringHelper::truncate($n->brief, 180) ?></p>
        </div>
        <?php } else {?>
        <ul class="list-lq">
            <li><a href="<?=\yii\helpers\Url::to(['news/view', 'id' => $n->id, 'slug' => $n->slug], true)?>" title="<?=$n->title?>"><span class="dot"></span><?= StringHelper::truncate($n->title, 60) ?> </a></li>
        </ul>
    <?php }
    }?>
</div>
<?php }?>

