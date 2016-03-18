<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/27/2015 11:17 AM
 * @var $news is a cms_show
 * @var $author get data from dektrium\user\models\Profile
 */

use yii\helpers\Url;

$newsCatID = isset(Yii::$app->params["newsCatID"]) ? Yii::$app->params["newsCatID"] : 0;
$catalogs = \vsoft\news\models\CmsCatalog::findAll(['parent_id'=>$newsCatID]);

Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => $news->title
]);
Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => $news->brief
]);

Yii::$app->view->registerMetaTag([
    'property' => 'og:title',
    'content' => $news->title
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:description',
    'content' => $news->brief
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:type',
    'content' => 'article'
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:image',
    'content' => Yii::$app->urlManager->createAbsoluteUrl('/store/news/show/'. $news->banner)
]);

$fb_appId = '680097282132293'; // stage.metvuong.com
if(strpos(Yii::$app->urlManager->hostInfo, 'dev.metvuong.com'))
    $fb_appId = '736950189771012';
else if(strpos(Yii::$app->urlManager->hostInfo, 'local.metvuong.com'))
    $fb_appId = '891967050918314';

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
<div class="title-fixed-wrap">
    <div class="container">
        <div class="detail-news page-news">
            <div class="title-top clearfix">
                <div class="list-menu-news swiper-container">
                    <div class="container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <a class="active" href="<?=Url::to(['news/index'])?>"><?=Yii::t('news','Tất cả')?></a>
                            </div>
                            <?php if(!empty($catalogs)){?>
                            <?php foreach($catalogs as $catalog){?>
                                    <div class="swiper-slide">
                                        <a href="<?=\yii\helpers\Url::to(['news/list', 'cat_id'=>$catalog->id, 'cat_slug'=>$catalog->slug])?>"><?=Yii::t('news', $catalog->title)?></a>
                                    </div>
                            <?php }?>
                            <?php }?>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
                <h2>TIN TỨC</h2>
            </div>

            <div class="wrap-detail-article">
                <input id="current_id" type="hidden" value="<?=$news->id?>">
                <input id="current_slug" type="hidden" value="<?=$news->slug?>">
                <input id="current_title" type="hidden" value="<?=$news->title?>">
                <input id="cat_id" type="hidden" value="<?=$news->catalog_id?>">
                <article>
                    <h1 class="big-title"><?=$news->title?></h1>
                    <div class="time-post">
                        <span class=""><?=date("d/m/Y g:i a",$news->created_at)?></span>,
                        <a href="#" class="name-cate">DOANH NGHIỆP</a>
                    </div>
                    <div class="detail-content pdL-5">
                        <div class="box-content">
                            <div><?=$news->content?></div>
                            <div id="social<?=$news->id?>" class="share-social mgT-10 wrap-img">
                                <div class="fb-like" data-href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news->id, 'slug' => $news->slug], true) ?>" data-layout="button_count" style="margin-right: 10px;"></div>
                                <div class="fb-send" data-href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news->id, 'slug' => $news->slug], true) ?>" data-show-faces="false" style="margin-right: 10px;"></div>
                                <div class="fb-share-button" data-href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news->id, 'slug' => $news->slug], true) ?>" data-layout="button_count"></div><br>
                                <div class="fb-comments" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl(['news/view', 'id' => $news->id, 'slug' => $news->slug])?>" data-width="100%" data-numposts="3"></div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <!-- <div class="toHeight" style="height: 10px;"></div> -->
            <div class="load-more-article">
                <div class="loading text-center" >
                    <img src="<?=Yii::$app->view->theme->baseUrl."/resources/images/loading-listing.gif"?>" alt="Loading..." title="<?=$news->title?>" />
                </div>
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

        var timer;
        $(window).scroll(function () {
            var currentID = parseInt($('#current_id').val());
            var catID = parseInt($('#cat_id').val());
            var hArticle = $('.wrap-detail-article').outerHeight() + $('header').outerHeight();

            $(window).scrollTop(function() {
                var scroll = $(this).scrollTop();
                if(hArticle - scroll <= 900 && currentID > 0){
                    $(".loading").show();
                    if ( timer ) clearTimeout(timer);
                    timer = setTimeout(function() {
                        $.ajax({
                            url: '<?php echo Yii::$app->getUrlManager()->createUrl(["news/getone?current_id="]); ?>' + currentID + '&cat_id=' + catID,
                            type: 'POST',
                            success: function (data) {
                                if (data) {
//                                    console.log(data);
                                    $(".loading").hide();
                                    $('#current_id').val(data.id);
                                    $('#current_slug').val(data.slug);
                                    $('#current_title').val(data.title);
                                    document.title = data.title;
                                    var time = timeConverter(data.created_at);
                                    var cat_id = data.catalog_id;
                                    window.history.pushState(data.slug, data.title, data.id + '-' + data.slug );
                                    $('.wrap-detail-article').append(
                                        '<article>' +
                                        '<h1 class="big-title">' + data.title + '</h1>' +
                                        '<div class="time-post">' +
                                        '<span>' + time + '</span>' +
                                        '</div>' +
                                        '<div class="detail-content pdL-5">' +
                                        '<div class="box-content">' +
                                        '<div>' + data.content + '</div>' +
                                        '<div id="social' + data.id + '" class="share-social mgT-10 wrap-img">' +
                                        '<div class="fb-like" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news')?>/view/' +  data.id + '-' + data.slug + '" data-layout="button_count" style="margin-right: 10px;"></div>' +
                                        '<div class="fb-send" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news')?>/view/' +  data.id + '-' + data.slug + '" data-show-faces="false" style="margin-right: 10px;"></div>' +
                                        '<div class="fb-share-button" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news')?>view/' +  data.id + '-' + data.slug + '" data-layout="button_count"></div><br>' +
                                        '<div class="fb-comments" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news')?>/view/' +  data.id + '-' + data.slug+ '" data-width="600" data-numposts="3" ></div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</article>');
                                    // console.log(data);
                                }
                                FB.XFBML.parse();
                            },
                            error: function () {
                                $('#current_id').val(0);
                                $(".loading").hide();
                                $(".loading").remove();
                                var last_news = $('.wrap-detail-article').find(".last_news");
                                if(!last_news[0])
                                    $('.wrap-detail-article').append('<div class="last_news">This is last news in this categories</div>');
                            }
                        }); // end ajax
                    }, 800);
                }
            });

        });



    });

    function timeConverter(UNIX_timestamp){
        var a = new Date(UNIX_timestamp * 1000);
//        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        var year = a.getFullYear();
//        var month = months[a.getMonth()];
        var month = a.getMonth()+1;
        var date = a.getDate();
        var dateFormatted = date < 10 ? "0"+date : date;
        var hour = a.getHours();
        var min = a.getMinutes();
        var hourFormatted = hour % 12 || 12; // hour returned in 24 hour format
        var minuteFormatted = min < 10 ? "0" + min : min;
        var morning = hour < 12 ? "am" : "pm";

        var time = dateFormatted + '/' + month + '/' + year + ' ' + hourFormatted + ':' + minuteFormatted + ' ' + morning ;
        return time;
    }

</script>
