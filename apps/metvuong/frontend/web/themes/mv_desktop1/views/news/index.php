<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\web\View;

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/swiper.jquery.min.js', ['position'=>View::POS_END]);

$newsCatID = isset(Yii::$app->params["newsCatID"]) ? Yii::$app->params["newsCatID"] : 0;
$catalogs = \vsoft\news\models\CmsCatalog::findAll(['parent_id'=>$newsCatID, 'status' => \vsoft\news\models\Status::STATUS_ACTIVE]);
Yii::t('news', 'Real Estate');
Yii::t('news', 'Financial & Banking');
Yii::t('news', 'Business');
Yii::t('news', 'Economy');
?>
<div class="title-fixed-wrap container">
    <div class="page-news">
        <?= $this->render('/news/_partials/menu'); ?>
        <?php if(count($news) > 0){?>
        <div class="clearfix wrap-news-page">
            <div class="wrap-news col-xs-12 col-md-9">
                <ul class="clearfix row list-news">
                    <?php foreach($news as $n) {
                        $banner = \vsoft\news\models\CmsShow::getBanner($n["banner"]);
//                        $checkBanner = file_exists(Yii::getAlias('@store')."/news/show/".\vsoft\news\models\CmsShow::THUMB400x0.$n["banner"]);
//                        if($checkBanner == false)
//                            $banner = '/themes/metvuong2/resources/images/default-ads.jpg';
                    ?>
                    <li class="col-xs-12 col-sm-6 col-md-4">
                        <div title="<?=$n["title"]?>">
                            <a href="<?=\yii\helpers\Url::to(['news/view', 'id' => $n["id"], 'slug' => $n["slug"]], true)?>" class="rippler rippler-default">
                                <div class="img-show"><div><img src="<?=$banner?>" alt="<?=$n["title"]?>"></div></div>
                            </a>
                            <div>
                                <a href="<?=\yii\helpers\Url::to(['news/list', 'cat_id'=>$n["catalog_id"], 'cat_slug'=>$n["cat_slug"]], true)?>" class="name-cate"><?=mb_strtoupper(Yii::t('news', $n["cat_title"]), 'UTF-8')?></a>
                                <p class="name-news"><a href="<?=\yii\helpers\Url::to(['news/view', 'id' => $n["id"], 'slug' => $n["slug"]], true)?>" title="<?=$n["title"]?>"><?=$n["title"]?></a></p>
                                <p class="date-post"><?=date('d/m/Y, H:i', $n["created_at"])?></p>
                                <p class="short-txt">
                                    <?=\yii\helpers\StringHelper::truncate($n["brief"], 200)?>
                                </p>
                                <a href="<?=\yii\helpers\Url::to(['news/view', 'id' => $n["id"], 'slug' => $n["slug"]], true)?>" class="view-more"><?=Yii::t('news','Read more')?> <span class="icon arrowLeft-small-black"></span></a>
                            </div>
                        </div>
                    </li>
                    <?php } ?>
                </ul>

                <nav class="text-center">
                <?php
                    echo LinkPager::widget([
                        'pagination' => $pagination
                    ]);
                ?>
                </nav>
            </div>
            <div class="col-md-3 col-xs-12 sidebar-news">
                <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'hotnews', 'title' => 'HOT NEWS', 'limit' => 4])?>
                <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'finance', 'title' => 'FINANCIAL & BANKING NEWS', 'limit' => 4])?>
                <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'realestate', 'title' => 'REAL ESTATE NEWS', 'limit' => 4])?>
            </div>
        </div>
        <?php } else {?>
            <div class="row wrap-news-page">
                <div class="wrap-news">
                    <br><br>
                    <p class="text-center">
                        <?=Yii::t('news','We are updating News...')?>
                        <?=Yii::t('news', 'Please try again later!')?><br>
                        <?=Yii::t('news', 'THANK YOU')?>
                    </p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>