<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/9/2015 10:13 AM
 *
 * @var $news is a cms_show
 * @var $author get data from dektrium\user\models\Profile
 */
use yii\bootstrap\Html;

echo $news->click;
?>

<div class="container-fluid">
    <!--row01-->
    <div class="row">
        <div id="list_news">
            <input id="news_<?=$news->id?>" type="hidden" value="<?=$news->id?>-<?=$news->slug?>">
        </div>
        <input id="current_id" type="hidden" value="<?=$news->id?>">
        <input id="current_slug" type="hidden" value="<?=$news->slug?>">
        <input id="current_title" type="hidden" value="<?=$news->title?>">
        <input id="cat_id" type="hidden" value="<?=$news->catalog_id?>">

        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 rowleft" id="postResult">

            <div class="post_info">
                <?= Html::a($news->getCatalog()->one()->title .' ', ['list', 'cat_id' => $news->catalog_id], ['style' => ['text-decoration' => 'none']]) ?>
                <?=date("d/m/Y g:i a",$news->created_at)?>
            </div>
            <h2 class="titleditail"><?= $news->title ?></h2><br>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 titleprofile">
                <div class="name">
                    <span class="tg">Tác giả </span>
                    <span class="bv"><?=$author->name?></span>
                </div>
                <div class="blockprofile">
                    <img src="/store/avatar/<?=$author->avatar?>">
                    <p class="profiletext"><?=$author->bio?></p>

                </div>
                <div class="bt"><button>yêu thích</button></div>
            </div><!--contenleft-->
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 contennew">
                <div class="contentdeitail">
                    <?= $news->content ?>
                </div>
                <label class="btn-default right"><?=$news->click?> lượt xem</label>
                <br>
                <div id="social">
                    <div class="fb-like" data-href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news->id, 'slug' => $news->slug]) ?>" data-layout="button_count" style="margin-right: 10px;"></div>
                    <div class="fb-send" data-href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news->id, 'slug' => $news->slug]) ?>" data-show-faces="false" style="margin-right: 10px;"></div>
                    <div class="fb-share-button" data-href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news->id, 'slug' => $news->slug]) ?>" data-layout="button_count"></div>
                    <br>
                    <div class="fb-comments" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl(['news/view','id' => $news->id])?>" data-width="600" data-numposts="3"></div>
                </div>

            </div>
            <a href="#" class="top">&uarr;</a>
        </div>
        <div id="loader"></div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 rgtcol">
            <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'quantam']) ?>
        </div>
    </div>
</div>

<style>
    #postResult > a.top{
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
    }
    #postResult > a.top:hover{
        opacity:1;
        transition:1s;
    }

    .animated, .contentdeitail img {
        -webkit-animation-duration: 1s;
        animation-duration: 1s;
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
    .fadeInLeft, .contentdeitail img {
        -webkit-animation-name: fadeInLeft;
        animation-name: fadeInLeft;
    }
</style>

<script type="text/javascript">
    $(document).ready(function(){
        // Set page title
        document.title = '<?=$news->title?>';
        var offset=350, // At what pixels show Back to Top Button
            scrollDuration=400; // Duration of scrolling to top

        // Smooth animation when scrolling
        $('.top').click(function(event) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: 0}, scrollDuration);
        });

        $('#postResult').bind('contextmenu',function(e){return false;});

        $(window).scroll(function () {
            var currentID = $('#current_id').val();
            var catID = $('#cat_id').val();

            if ($(this).scrollTop() > offset) {
                $('.top').fadeIn(500); // Time(in Milliseconds) of appearing of the Button when scrolling down.
            } else {
                $('.top').fadeOut(500); // Time(in Milliseconds) of disappearing of Button when scrolling up.
            }

            if ($(window).scrollTop() == $(document).height() - $(window).height()) {

                if(currentID > 0) {
                    setTimeout(function(){
                        $.ajax({
                            url: '<?php echo Yii::$app->getUrlManager()->createUrl(["news/getone?current_id="]); ?>' + currentID + '&cat_id=' + catID,
                            type: 'POST',
                            success: function (data) {
                                if (data) {
                                    $('#list_news').append('<input id="news_'+data.id+'" type="hidden" value="'+data.id+'-'+data.slug+'">');
                                    $('#current_id').val(data.id);
                                    $('#current_slug').val(data.slug);
                                    $('#current_title').val(data.title);
                                    document.title = data.title;
                                    var time = timeConverter(data.created_at);
                                    window.history.pushState(data.slug, data.title, data.id+"-"+data.slug);
                                    $('#postResult').append(
                                        '<hr><div class="post_info"><?= Html::a($news->getCatalog()->one()->title .' ', ['list', 'cat_id' => $news->catalog_id], ['style' => ['text-decoration' => 'none']]) ?>' + time + '</div>' +
                                        '<h2 class="titleditail">' + data.title + '</h2><br>' +
                                        '<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 titleprofile">' +
                                            '<div class="name">' +
                                                '<span class="tg">Tác giả </span>' +
                                                '<span class="bv">keni pham </span>' +
                                            '</div>' +
                                            '<div class="blockprofile">' +
                                                '<img src="/store/avatar/toyota-hilux-2015-5.jpg">' +
                                                '<p class="profiletext">Nông nghiệp Việt Nam đón nhận làn sóng đầu tư chưa từng có Nông nghiệp Việt Nam đón nhận làn sóng đầu tư chưa từng có Nông nghiệp Việt Nam đón nhận làn sóng đầu tư chưa từng có</p>' +
                                            '</div>' +
                                            '<div class="bt"><button>yêu thích</button></div>'+
                                        '</div>'+
                                        '<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 contennew">' +
                                            '<div class="contentdeitail">' + data.content + '</div><br>' +
                                            '<div id="social">' +
                                            '<div class="fb-like" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news/view')?>?id=' + data.id + '" data-layout="button_count" style="margin-right: 10px;"></div>' +
                                            '<div class="fb-send" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news/view')?>?id=' + data.id + '" data-show-faces="false" style="margin-right: 10px;"></div>' +
                                            '<div class="fb-share-button" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news/view')?>?id=' + data.id + '" data-layout="button_count"></div><br>' +
                                            '<div class="fb-comments" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl('news/view')?>?id=' + data.id + '" data-width="600" data-numposts="3" ></div>' +
                                            '</div>' +
                                        '</div>');

        //                        console.log(data);
                                }
                                FB.XFBML.parse(document.getElementById('postResult'));
                            },
                            error: function() {
                                $('#current_id').val(0);
                                $('#loader').html('<div>Đã hết dữ liệu</div>');
                            }
                        })
                    }, 700);
                }

            }
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