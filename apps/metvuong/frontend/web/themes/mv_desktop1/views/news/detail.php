<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/27/2015 11:17 AM
 * @var $news is a cms_show
 * @var $author get data from dektrium\user\models\Profile
 */

use yii\helpers\Url;
use yii\web\View;

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/swiper.jquery.min.js', ['position'=>View::POS_END]);

//Yii::t('news', 'Real Estate');
//Yii::t('news', 'Financial & Banking');
//Yii::t('news', 'Business');
//Yii::t('news', 'Economy');

Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => empty($news["seo_keywords"]) ? $news["title"] : $news["seo_keywords"]
]);
Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => empty($news["seo_description"]) ? $news["brief"] : $news["seo_description"]
]);

Yii::$app->view->registerMetaTag([
    'property' => 'og:site_name',
    'content' => Yii::$app->name
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:title',
    'content' => $news["title"]
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:description',
    'content' => $news["brief"]
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:type',
    'content' => 'article'
]);

Yii::$app->view->registerMetaTag([
    'property' => 'og:image',
    'content' => \vsoft\news\models\CmsShow::getBanner($news["banner"])
]);

Yii::$app->view->registerMetaTag([
    'property' => 'og:url',
    'content' => \yii\helpers\Url::to(['news/view', 'id' => $news["id"], 'slug' => $news["slug"]], true)
]);

$fb_appId = '680097282132293'; // stage.metvuong.com
if(strpos(Yii::$app->urlManager->hostInfo, 'dev.metvuong.com'))
    $fb_appId = '736950189771012';
else if(strpos(Yii::$app->urlManager->hostInfo, 'local.metvuong.com'))
    $fb_appId = '891967050918314';

$_title = str_replace("'", "\'", $news["title"]);
$_brief = str_replace("'", "\'", $news["brief"]);
$banner = Yii::$app->urlManager->createAbsoluteUrl('/store/news/show/'. $news["banner"]);
//$checkBanner = file_exists(Yii::getAlias('@store')."/news/show/".$news["banner"]);
//if($checkBanner == false)
//    $banner = Yii::$app->urlManager->createAbsoluteUrl('/themes/metvuong2/resources/images/default-ads.jpg');
?>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : <?=$fb_appId?>,
            xfbml      : true,
            version    : 'v2.5'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.async=true;
        js.src = "//connect.facebook.net/vi_VN/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<div class="title-fixed-wrap container">
    <div class="detail-news page-news">
        <?= $this->render('/news/_partials/menu', ['cat_id'=>$news["catalog_id"]]); ?>
        <div class="wrap-detail-article wrap-news-page clearfix">
            <div class="wrap-news col-xs-12 col-md-9">
                <input id="current_id" type="hidden" value="<?=$news["id"]?>">
                <input id="current_slug" type="hidden" value="<?=$news["slug"]?>">
                <input id="current_title" type="hidden" value="<?=$news["title"]?>">
                <input id="cat_id" type="hidden" value="<?=$news["catalog_id"]?>">
                <article>
                    <h1 class="big-title"><?=$news["title"]?></h1>
                    <div class="time-post">
                        <span class=""><?=date("d/m/Y H:i",$news["created_at"])?></span>,
                        <a href="#" class="name-cate"><?=mb_strtoupper(Yii::t('news', $news["cat_title"]), 'UTF-8')?></a>
                    </div>
                    <div class="detail-content">
                        <div class="box-content">
                            <div><?=$news["content"]?></div>
                            <div id="social<?=$news["id"]?>" class="share-social mgT-10 wrap-img">
                                <div class="fb-like" data-href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news["id"], 'slug' => $news["slug"]], true) ?>" data-layout="button_count" style="margin-right: 10px;"></div>
                                <div class="fb-send" data-href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news["id"], 'slug' => $news["slug"]], true) ?>" data-show-faces="false" style="margin-right: 10px;"></div>
                                <a class="fb-share" data-href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news["id"], 'slug' => $news["slug"]], true) ?>" data-layout="button_count"><?=Yii::t('news','Share on Facebook')?></a><br>
                                <div class="fb-comments" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl(['news/view', 'id' => $news["id"], 'slug' => $news["slug"]])?>" data-width="100%" data-numposts="3"></div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-md-3 col-xs-12 sidebar-news">
                <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'hotnews', 'title' => 'HOT NEWS', 'limit' => 4])?>
                <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'finance', 'title' => 'FINANCIAL & BANKING NEWS', 'limit' => 4])?>
                <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'realestate', 'title' => 'REAL ESTATE NEWS', 'limit' => 4])?>
            </div>
        </div>
        <!-- <div class="toHeight" style="height: 10px;"></div> -->
        <div class="load-more-article">
            <div class="loading text-center" >
                <img src="<?=Yii::$app->view->theme->baseUrl."/resources/images/loading-listing.gif"?>" alt="Loading..." title="<?=$news["title"]?>" />
            </div>
        </div>
    </div>
</div>
<style>
    .loading { display: none; margin-bottom: 20px;}
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

    .last_news {
        font-size: 10pt;
        text-align: right;
        font-style: italic;
        padding-right: 20px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){

        var swiper = new Swiper('.list-menu-news > .container', {
            paginationClickable: true,
            spaceBetween: 0,
            slidesPerView: 'auto',
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev'
        });

        $('.sidebar-news').loading({full: false});
        $.ajax({
            type: "get",
            dataType: 'html',
            url: '<?=Url::to(['news/load-news-widget'])?>',
            success: function (data) {
                $(".sidebar-news").html(data);
                $('.sidebar-news').loading({done: true});
            }
        });

        function fbShare(winWidth, winHeight) {
            var winTop = (screen.height / 2) - (winHeight / 2);
            var winLeft = (screen.width / 2) - (winWidth / 2);
//            window.open('http://www.facebook.com/sharer.php?s=100&p[url]=' + url + '&p[title]=' + title + '&p[summary]=' + descr + '&p[images][0]=' + image, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
            window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href)+'&p[images][0]='+'<?=\vsoft\news\models\CmsShow::getBanner($news["banner"])?>', 'facebook-share-dialog', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
        }

        $(document).on('click', '.detail-content .fb-share', function(){
            fbShare(800, 600);
            return false;
        });

    });
</script>
