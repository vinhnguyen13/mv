<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/27/2015 11:17 AM
 * @var $news is a cms_show
 * @var $author get data from dektrium\user\models\Profile
 */

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
?>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '526268097523112',
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
        <div class="wrap-detail-article">
            <input id="current_id" type="hidden" value="<?=$news->id?>">
            <input id="current_slug" type="hidden" value="<?=$news->slug?>">
            <input id="current_title" type="hidden" value="<?=$news->title?>">
            <input id="cat_id" type="hidden" value="<?=$news->catalog_id?>">
            <article>
                <div class="time-post">
                    <a href="<?=  \yii\helpers\Url::to(['news/list', 'cat_id' => $news->catalog_id, 'slug' => $catalog->slug]) ?>" class="color-title-link"><?=$catalog->title?></a>
                    <span class="">&nbsp;&nbsp;<?=date("d/m/Y g:i a",$news->created_at)?></span>
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
                            <div id="social<?=$news->id?>" class="share-social mgT-10 wrap-img">
                                <div class="fb-like" data-href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news->id, 'slug' => $news->slug, 'cat_id' => $news->catalog_id, 'cat_slug' => $catalog->slug]) ?>" data-layout="button_count" style="margin-right: 10px;"></div>
                                <div class="fb-send" data-href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news->id, 'slug' => $news->slug, 'cat_id' => $news->catalog_id, 'cat_slug' => $catalog->slug]) ?>" data-show-faces="false" style="margin-right: 10px;"></div>
                                <div class="fb-share-button" data-href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news->id, 'slug' => $news->slug, 'cat_id' => $news->catalog_id, 'cat_slug' => $catalog->slug]) ?>" data-layout="button_count"></div><br>
                                <div class="fb-comments" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl(['news/view', 'id' => $news->id, 'slug' => $news->slug, 'cat_id' => $news->catalog_id, 'cat_slug' => $catalog->slug])?>" data-width="100%" data-numposts="3"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </article>
        </div>
        <!-- <div class="toHeight" style="height: 10px;"></div> -->
        <div class="load-more-article pull-right col-xs-9">
            <div class="loading text-center pull-right col-xs-9" >
                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/loader.gif" alt="Loading..." title="<?=$news->title?>" />
            </div>
        </div>
    </div>
    
    
    <div class="col-sm-4 col-lg-3 col-left-home">
        <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'hotnews'])?>
        <div class="siderbar widget-ads clearfix">
            <a class="wrap-img" href="http://www.dreamplex.co/"><img src="http://www.reic.info/Content/themes/v1/Images/banner/Dreamplex-300x250.jpg" alt="dreamplex"></a>
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
</style>
<script type="text/javascript">
    $(document).ready(function(){
//        var offset=350; // At what pixels show Back to Top Button


//        $('.detail-news').bind('contextmenu',function(e){return false;});
        var timer;
        $(window).scroll(function () {
            var currentID = parseInt($('#current_id').val());
            var catID = parseInt($('#cat_id').val());

            var divHeight = $(".toHeight").position().top;
            var h = $(document).height() - $(window).height();

            var hArticle = $('.wrap-detail-article').outerHeight() + $('header').outerHeight();

            $(window).scrollTop(function() {
                var scroll = $(this).scrollTop();
                $(".loading").show();
                if(hArticle - scroll <= 500){
                    $.ajax({
                        url: '<?php echo Yii::$app->getUrlManager()->createUrl(["news/getone?current_id="]); ?>' + currentID + '&cat_id=' + catID,
                        type: 'POST',
                        success: function (data) {
                            if (data) {
                                $(".loading").hide();
                                $('#current_id').val(data.id);
                                $('#current_slug').val(data.slug);
                                $('#current_title').val(data.title);
                                document.title = data.title;
                                var time = timeConverter(data.created_at);
                                var cat_id = data.catalog_id;
                                window.history.pushState(data.slug, data.title, data.id+"-"+data.slug);
                                $('.wrap-detail-article').append(
                                    '<article>' +
                                    '<div class="time-post">'+
                                        '<a href="<?= Yii::$app->urlManager->createAbsoluteUrl('news')?>/' + data.catalog_id + '-' + data.cat_slug + '"  class="color-title-link">' + data.catalog_name + '</a>'+
                                        '<span>&nbsp;&nbsp;'+time+'</span>'+
                                    '</div>'+
                                    '<h1 class="big-title">'+data.title+'</h1>'+
                                    '<div class="row">'+
                                        '<div class="col-xs-3 tg-post pdR-5">'+
                                            '<div>Tác giả</div>'+
                                            '<div class="mgT-10"><a href="" class="color-title-link">'+data.author_name+'</a></div>'+
                                            '<div class="mgT-10">'+
                                                '<img src="/store/avatar/'+data.avatar+'" title="" style="max-width:100%;">'+
                                            '</div>'+
                                            '<div class="fItalic mgT-10">'+data.bio+'</div>'+
                                            '<div class="mgT-10"><a class="btn btn-primary btn-normal" href="">Yêu thích</a></div>'+
                                        '</div>'+
                                        '<div class="col-xs-9 detail-content pdL-5">'+
                                            '<div class="box-content">'+
                                                '<div>'+data.content+'</div>'+
                                                '<div id="social'+data.id+'" class="share-social mgT-10 wrap-img">'+
                                                    '<div class="fb-like" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news')?>/'+data.catalog_id+'-'+data.cat_slug+'/' + data.id + '-'+data.slug+'" data-layout="button_count" style="margin-right: 10px;"></div>' +
                                                    '<div class="fb-send" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news')?>/'+data.catalog_id+'-'+data.cat_slug+'/' + data.id + '-'+data.slug+'" data-show-faces="false" style="margin-right: 10px;"></div>' +
                                                    '<div class="fb-share-button" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news')?>/'+data.catalog_id+'-'+data.cat_slug+'/' + data.id + '-'+data.slug+'" data-layout="button_count"></div><br>' +
                                                    '<div class="fb-comments" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news')?>/'+data.catalog_id+'-'+data.cat_slug+'/' + data.id + '-'+data.slug+'" data-width="600" data-numposts="3" ></div>' +
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>' +
                                    '</article>');
                                // console.log(data);
                            }
                            FB.XFBML.parse();
                        },
                        error: function() {
                            $('#current_id').val(0);
                            $(".loading").hide();
                        }
                    });
                }
            });
            /*if ($(window).scrollTop() > h - divHeight ) {
                $(".loading").show();
                if(currentID > 0) {
                    if ( timer ) clearTimeout(timer);
                    timer = setTimeout(function(){
                        
                        $.ajax({
                            url: '<?php echo Yii::$app->getUrlManager()->createUrl(["news/getone?current_id="]); ?>' + currentID + '&cat_id=' + catID,
                            type: 'POST',
                            success: function (data) {
                                if (data) {
                                    $(".loading").hide();
                                    $('#current_id').val(data.id);
                                    $('#current_slug').val(data.slug);
                                    $('#current_title').val(data.title);
                                    document.title = data.title;
                                    var time = timeConverter(data.created_at);
                                    var cat_id = data.catalog_id;
                                    window.history.pushState(data.slug, data.title, data.id+"-"+data.slug);
                                    $('.wrap-detail-article').append(
                                        '<article>' +
                                        '<div class="time-post">'+
                                            '<a href="<?= Yii::$app->urlManager->createAbsoluteUrl('news')?>/' + data.catalog_id + '-' + data.cat_slug + '"  class="color-title-link">' + data.catalog_name + '</a>'+
                                            '<span>&nbsp;&nbsp;'+time+'</span>'+
                                        '</div>'+
                                        '<h1 class="big-title">'+data.title+'</h1>'+
                                        '<div class="row">'+
                                            '<div class="col-xs-3 tg-post pdR-5">'+
                                                '<div>Tác giả</div>'+
                                                '<div class="mgT-10"><a href="" class="color-title-link">'+data.author_name+'</a></div>'+
                                                '<div class="mgT-10">'+
                                                    '<img src="/store/avatar/'+data.avatar+'" title="" style="max-width:100%;">'+
                                                '</div>'+
                                                '<div class="fItalic mgT-10">'+data.bio+'</div>'+
                                                '<div class="mgT-10"><a class="btn btn-primary btn-normal" href="">Yêu thích</a></div>'+
                                            '</div>'+
                                            '<div class="col-xs-9 detail-content pdL-5">'+
                                                '<div class="box-content">'+
                                                    '<div>'+data.content+'</div>'+
                                                    '<div id="social'+data.id+'" class="share-social mgT-10 wrap-img">'+
                                                        '<div class="fb-like" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news')?>/'+data.catalog_id+'-'+data.cat_slug+'/' + data.id + '-'+data.slug+'" data-layout="button_count" style="margin-right: 10px;"></div>' +
                                                        '<div class="fb-send" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news')?>/'+data.catalog_id+'-'+data.cat_slug+'/' + data.id + '-'+data.slug+'" data-show-faces="false" style="margin-right: 10px;"></div>' +
                                                        '<div class="fb-share-button" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news')?>/'+data.catalog_id+'-'+data.cat_slug+'/' + data.id + '-'+data.slug+'" data-layout="button_count"></div><br>' +
                                                        '<div class="fb-comments" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news')?>/'+data.catalog_id+'-'+data.cat_slug+'/' + data.id + '-'+data.slug+'" data-width="600" data-numposts="3" ></div>' +
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>' +
                                        '</article>');
                                    // console.log(data);
                                }
                                FB.XFBML.parse();
                            },
                            error: function() {
                                $('#current_id').val(0);
                                $(".loading").hide();
                            }
                        });

                    }, 500);
                }

            }*/
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
