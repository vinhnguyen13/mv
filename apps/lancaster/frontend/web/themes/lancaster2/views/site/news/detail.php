<?php
$this->title = Yii::t('news', ucfirst(strtolower($detail->catalog->surname)));
/* @var $this yii\web\View */
use yii\helpers\Url;
?>
<div id="detail-page">
    <div class="container">
        <div class="clear">
            <div class="left">
                <div class="detail">
                    <div class="title"><?=$detail->title;?></div>
                    <img alt="" src="<?=$detail->getUrlBanner($detail->banner);?>" />
                    <div class="content">
                        <?=$detail->content;?>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="related-title">Related post</div>
                <div class="news-list clear">
                    <?php foreach($relatedPost as $key=>$new):?>
                        <div class="item">
                        <div class="item-wrap-content">
                            <a class="item-img-wrap" href="<?=Url::toRoute(['/site/news-detail', 'id' => $new->id, 'slug' => $new->slug]);?>">
                                <img alt="" src="<?=$new->getUrlBanner($new->banner);?>" />
                                <span class="post-date">
                                    <span class="date"><?=date('d', $new->created_at);?></span>
                                    <span class="month"><?=strtoupper(date('M', $new->created_at));?></span>
                                </span>
                                <span class="overlay">
                                    <span class="overlay-border">
                                        <span class="overlay-center">Read More</span>
                                    </span>
                                </span>
                            </a>
                            <div class="item-content">
                                <a href="<?=Url::toRoute(['/site/news-detail', 'id' => $new->id, 'slug' => $new->slug, 'cat'=>strtolower($new->catalog->surname)]);?>" class="title"><?=$new->title;?></a>
                                <span class="item-content-border"></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
