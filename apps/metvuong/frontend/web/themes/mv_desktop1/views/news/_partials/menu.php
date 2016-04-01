<?php
use yii\helpers\Url;
$newsCatID = isset(Yii::$app->params["newsCatID"]) ? Yii::$app->params["newsCatID"] : 0;
$catalogs = \vsoft\news\models\CmsCatalog::findAll(['parent_id'=>$newsCatID]);
Yii::t('news', 'Real Estate');
Yii::t('news', 'Financial & Banking');
Yii::t('news', 'Business');
Yii::t('news', 'Economy');
?>
<div class="title-top clearfix">
    <div class="list-menu-news">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <a <?=(empty($cat_id)) ? 'class="active"' : '';?>  href="<?=Url::to(['news/index'])?>"><?=Yii::t('news','All')?></a>
                </div>
                <?php if(!empty($catalogs)){?>
                    <?php foreach($catalogs as $catalog){?>
                        <div class="swiper-slide">
                            <a <?=(!empty($cat_id) && $cat_id == $catalog->id) ? 'class="active"' : '';?> href="<?=Url::to(['news/list', 'cat_id'=>$catalog->id, 'cat_slug'=>$catalog->slug])?>"><?=Yii::t('news', trim($catalog->title))?></a>
                        </div>
                    <?php }?>
                <?php }?>
            </div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    <h2><?=Yii::t('news','NEWS')?></h2>
</div>
