<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
$this->title = Yii::t('express/about','About Us');
$oneNews = null;
?>
<div id="about-page">
    <div class="container">
        <h1 class="title"><?=Yii::t('express/about','About Us') ?></h1>
        <?php
        if(!empty($news)){
            $oneNews = $news[0];
        if(!empty($oneNews)){
            unset($news[0]);
        ?>
        <div class="first-about clear">
            <div class="left">
                <?=Html::img($oneNews->getUrlBanner($oneNews->banner))?>
            </div>
            <div class="first-about-right">
                <div class="title"><?=$oneNews->title;?></div>
                <div class="excerpt"><?=StringHelper::truncate($oneNews->brief, 390);?></div>
            </div>
        </div>
        <?php }?>
        <div class="about-list clear">
            <?php foreach($news as $key=>$new){?>
                <?php
                    $class = ($key%4==0 || $key%4==3) ? 'about-item about-item-right' : 'about-item';
                ?>
                <div class="<?=$class;?>">
                    <div class="item-wrap-content">
                        <div class="item-img-wrap-out">
                            <a class="item-img-wrap" href="<?=Url::toRoute(['/site/news-detail', 'id' => $new->id, 'slug' => $new->slug, 'cat'=>strtolower($new->catalog->surname)]);?>">
                                <?=Html::img($new->getUrlBanner($new->banner))?>
                                <span class="overlay">
                                    <span class="overlay-border">
                                        <span class="overlay-center"><i class="icon-read-more"></i><br/>Read More</span>
                                    </span>
                                </span>
                            </a>
                        </div>
                        <div class="item-content">
                            <a href="<?=Url::toRoute(['/site/news-detail', 'id' => $new->id, 'slug' => $new->slug, 'cat'=>strtolower($new->catalog->surname)]);?>" class="title"><?=$new->title;?></a>
                            <div class="excerpt"><?=StringHelper::truncate($new->brief, 320);?></div>
                        </div>
                    </div>
                </div>
            <?php }?>
        </div>
        <?php }?>
    </div>
</div>