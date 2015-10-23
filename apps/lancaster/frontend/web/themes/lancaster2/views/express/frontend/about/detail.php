<?php
$this->title = Yii::t('news', 'News');
/* @var $this yii\web\View */
use yii\helpers\Html;
?>
<div class="container-fluid newsdetail">
    <div class="row main_content">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 newsleft">
            <span class="btn_pre"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/IMG/btn_back.png"><a href="<?=\yii\helpers\Url::home()?>">Back to Lancaster Legacy</a></span>
            <span class="btn_next"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/IMG/btn_back.png"><a href="<?=\yii\helpers\Url::toRoute(['/express/about'])?>">Back to About</a></span>
            <div class="imgnewdetail"><img src="<?=$detail->getUrlBanner($detail->banner);?>"></div>
            <?php if(!empty($relatedPost)):?>
                <div class="relatedpost">
                    <p>Related post</p>
                    <?php foreach($relatedPost as $key=>$new):?>
                        <div class="imgpost">
                            <ul>
                                <li><?=Html::a(Html::img($new->getUrlBanner($new->banner)), \yii\helpers\Url::toRoute(['/express/about/detail', 'id' => $new->id, 'slug' => $new->slug]));?></li>
                                <li class="bodertext"><?=Html::a($new->title, \yii\helpers\Url::toRoute(['/express/about/detail', 'id' => $new->id, 'slug' => $new->slug]));?> </li>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 newsright">
            <span class="blockempty"></span>
            <h2><?=$detail->title;?></h2>
            <ul>
                <li class="textcontent">
                    <?=$detail->content;?>
                </li>
                <li class="textfooter"><span class="footer"><?=date('d M Y', $detail->created_at)?></span><span class="share">Share</span><span class="facebookblack"></span>
                    <span class="google"></span></li>
            </ul>
        </div>
    </div>
</div>