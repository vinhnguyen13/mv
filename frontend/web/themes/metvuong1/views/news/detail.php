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

?>

<div class="container-fluid">
    <!--row01-->
    <div class="row">
        <input id="current_id" type="hidden" value="<?=$news->id?>">
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
                <br>
                <div id="social">
                    <div class="fb-like" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl(['news/view','id' => $news->id])?>" data-layout="button_count" style="margin-right: 10px;"></div>
                    <div class="fb-send" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl(['news/view','id' => $news->id])?>" data-show-faces="false" style="margin-right: 10px;"></div>
                    <div class="fb-share-button" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl(['news/view','id' => $news->id])?>" data-layout="button_count"></div>
                    <br>
                    <div class="fb-comments" data-href="<?= Yii::$app->urlManager->createAbsoluteUrl(['news/view','id' => $news->id])?>" data-width="600" data-numposts="3"></div>
                </div>
            </div>

        </div>
        <div id="loader"></div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 rgtcol">
            <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'quantam']) ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(window).scroll(function () {
        var currentID = $('#current_id').val();
        var catID = $('#cat_id').val();
        if ($(window).scrollTop() == $(document).height() - $(window).height()) {
            if(currentID > 0) {
                $.ajax({
                    url: '<?php echo Yii::$app->getUrlManager()->createUrl(["news/getone?current_id="]); ?>' + currentID + '&cat_id=' + catID,
                    type: 'POST',
                    success: function (data) {
                        if (data) {
                            $('#current_id').val(data.id);
                            var time = timeConverter(data.created_at);
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
                });
            }
        }

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