<?php
use yii\helpers\Url;
$newsCatID = isset(Yii::$app->params["newsCatID"]) ? Yii::$app->params["newsCatID"] : 0;
$catalogs = \vsoft\news\models\CmsCatalog::findAll(['parent_id'=>$newsCatID, 'status' => \vsoft\news\models\Status::STATUS_ACTIVE]);
Yii::t('news', 'Real Estate');
Yii::t('news', 'Financial & Banking');
Yii::t('news', 'Business');
Yii::t('news', 'Economy');
?>
<div class="title-top clearfix">
    <h2><?=Yii::t('news','NEWS')?></h2>
</div>
<div class="list-menu-news">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <a <?=(empty($cat_id)) ? 'class="active"' : '';?>  href="<?=Url::to(['news/index'])?>"><?=Yii::t('news','All')?></a>
            </div>
            <?php if(!empty($catalogs)){?>
                <?php foreach($catalogs as $catalog){?>
                    <div class="swiper-slide">
                        <a <?=(!empty($cat_id) && $cat_id == $catalog->id) ? 'class="active"' : '';?> href="<?=Url::to(['news/list', 'cat_id'=>$catalog->id, 'cat_slug'=>\yii\helpers\Inflector::slug(Yii::t('news', trim($catalog->title)))])?>"><?=Yii::t('news', trim($catalog->title))?></a>
                    </div>
                <?php }?>
            <?php }?>
        </div>
    </div>
    <div class="swiper-button-next"><span></span></div>
    <div class="swiper-button-prev"><span></span></div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var swiper = new Swiper('.list-menu-news .swiper-container', {
            paginationClickable: true,
            spaceBetween: 0,
            slidesPerView: 'auto',
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            preventClicks: false
        });
    });
</script>