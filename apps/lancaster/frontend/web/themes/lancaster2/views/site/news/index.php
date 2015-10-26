<?php

use yii\web\View;
use yii\helpers\StringHelper;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = Yii::t('express/news', 'News');
$oneNews = null;
?>
<div id="news-page">
    <div class="container">
        <h1 class="title"><?=Yii::t('express/news', 'News')?></h1>
        <div class="news-list clear">
            <?php
            if(!empty($news)){
            ?>
            <?php foreach($news as $key=>$new){?>
            <div class="item">
                <div class="item-wrap-content">
                    <a class="item-img-wrap" href="<?=Url::toRoute(['/site/news-detail', 'id' => $new->id, 'slug' => $new->slug, 'cat'=>strtolower($new->catalog->surname)]);?>">
                        <?=Html::img($new->getUrlBanner($new->banner))?>
                        <span class="post-date">
                            <span class="date"><?=date('d', $new->created_at);?></span>
                            <span class="month"><?=strtoupper(date('M', $new->created_at));?></span>
                        </span>
                        <span class="overlay">
                            <span class="overlay-border">
                                <span class="overlay-center"><i class="icon-read-more"></i><br />Read More</span>
                            </span>
                        </span>
                    </a>
                    <div class="item-content">
                        <a href="<?=Url::toRoute(['/site/news-detail', 'id' => $new->id, 'slug' => $new->slug, 'cat'=>strtolower($new->catalog->surname)]);?>" class="title"><?=$new->title;?></a>
                        <div class="excerpt"><?=StringHelper::truncate($new->brief, 90);?></div>
                        <span class="item-content-border"></span>
                    </div>
                </div>
            </div>
            <?php }?>
            <?php }?>
        </div>
    </div>
</div>