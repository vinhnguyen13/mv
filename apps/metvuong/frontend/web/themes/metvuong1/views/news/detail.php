<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/27/2015 11:17 AM
 * @var $news is a cms_show
 * @var $author get data from dektrium\user\models\Profile
 */
?>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '736950189771012',
            xfbml      : true,
            version    : 'v2.5'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/vi_VN/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<div class="row">
    <div class="col-sm-8 col-lg-9 col-right-home detail-news">
        <div id="list_news">
            <input id="news_<?=$news->id?>" type="hidden" value="<?=$news->id?>-<?=$news->slug?>">
        </div>
        <input id="current_id" type="hidden" value="<?=$news->id?>">
        <input id="current_slug" type="hidden" value="<?=$news->slug?>">
        <input id="current_title" type="hidden" value="<?=$news->title?>">
        <input id="cat_id" type="hidden" value="<?=$news->catalog_id?>">
        <article>
            <div class="time-post">
                <a href="<?= \yii\helpers\Url::to(['news/list', 'cat_id' => $news->catalog_id]) ?>" class="color-title-link">Bất động sản</a>
                <span class=""><?=date("d/m/Y g:i a",$news->created_at)?></span>
            </div>
            <h1 class="big-title"><?=$news->title?></h1>
            <div class="row">
                <div class="col-xs-3 tg-post pdR-5">
                    <div>Tác giả</div>
                    <div class="mgT-10"><a href="" class="color-title-link"><?=$author->name?></a></div>
                    <div class="mgT-10">
                        <img src="/store/avatar/<?=$author->avatar?>" title="<?=$author->name?>" style="max-width:100%;">
                    </div>
                    <div class="fItalic mgT-10"><?=$author->bio?></div>
                    <div class="mgT-10"><a class="btn btn-primary btn-normal" href="">Yêu thích</a></div>
                </div>
                <div class="col-xs-9 detail-content pdL-5">
                    <div class="box-content">
                        <div><?=$news->content?></div>
                        <div id="social" class="share-social mgT-10 wrap-img">
                            <div class="fb-like" data-href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news->id, 'slug' => $news->slug]) ?>" data-layout="button_count" style="margin-right: 10px;"></div>
                            <div class="fb-send" data-href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news->id, 'slug' => $news->slug]) ?>" data-show-faces="false" style="margin-right: 10px;"></div>
                            <div class="fb-share-button" data-href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news->id, 'slug' => $news->slug]) ?>" data-layout="button_count"></div>
                            <br>
                            <div class="fb-comments" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl(['news/view','id' => $news->id])?>" data-width="100%" data-numposts="3"></div>
                        </div>

                    </div>
                </div>
            </div>
        </article>
        <a href="#" class="top">&uarr;</a>
    </div>
    <div class="col-sm-4 col-lg-3 col-left-home">
        <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'hotnews'])?>
        <div class="siderbar widget-ads clearfix">
            <a class="wrap-img" href="#"><img src="<?= Yii::$app->view->theme->baseUrl?>/resources/images/img295x210.jpg" alt=""></a>
        </div>
        <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'important'])?>
    </div>
</div>

<div class="social-share">
    <ul>
        <li><a href="#"><em class="fa fa-facebook"></em></a></li>
        <li><a href="#"><em class="fa fa-twitter"></em></a></li>
        <li><a href="#"><em class="fa fa-instagram"></em></a></li>
        <li><a href="#"><em class="fa fa-google-plus"></em></a></li>
        <li><a href="#"><em class="fa fa-youtube-play"></em></a></li>
        <li><a href="#"><em class="fa fa-pinterest"></em></a></li>
        <li><a href="#"><em class="fa fa-linkedin"></em></a></li>
    </ul>
</div>
<style>
    .detail-news > a.top{
        background-color: #2f781f;
        bottom: 2em;
        color: #fff;
        display: none;
        opacity:0.6;
        padding: 1.5em;
        position: fixed;
        right: 1.5em;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
    }
    .detail-news > a.top:hover{
        opacity:1;
        transition:1s;
    }

    .animated, .box-content img {
        -webkit-animation-duration: 2s;
        animation-duration: 2s;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
    }

    @-webkit-keyframes fadeInLeft {
        0% {
            opacity: 0;
            -webkit-transform: translateX(-20px);
        }
        100% {
            opacity: 1;
            -webkit-transform: translateX(0);
        }
    }
    @keyframes fadeInLeft {
        0% {
            opacity: 0;
            transform: translateX(-20px);
        }
        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }
    .fadeInLeft, .box-content img {
        -webkit-animation-name: fadeInLeft;
        animation-name: fadeInLeft;
    }
</style>
