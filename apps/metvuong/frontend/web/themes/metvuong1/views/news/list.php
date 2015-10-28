<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/9/2015 10:17 AM
 *
 * @var $list_news by $cat_id catalog
 */
use yii\widgets\ListView;
use yii\widgets\Pjax;

?>
<script src="<?= Yii::$app->view->theme->baseUrl ?>/resources/js/jquery.bxslider.js"></script>
<script>
    $(document).ready(function() {
        $('.bxslider').bxSlider({
            mode: 'fade',
            auto: true,
            autoHover: true
        });
    });
</script>
<div class="row">
    <div class="col-sm-8 col-lg-9 col-right-home lists-page">
        <div class="news-hot box-danb-slide bxslider">
            <div class="">
                <div class="wrap-img bgcover" style="background-image: url(<?= Yii::$app->view->theme->baseUrl ?>/resources/images/galaxy9.jpg);"><a href="#"></a></div>
                <div class="content-news-hot">
                    <h2>9 dự án đang và sẽ thay đổi diện mạo Quận 4 1</h2>
                    <p>ngày đầu tiên là tên một mới được bổ nhiệm vào vị trí  mới được bổ nhiệm vào vị trí  mới được bổ nhiệm vào vị trí  mới được bổ nhiệm vào vị trí  mới được bổ nhiệm vào vị trí...</p>
                </div>
            </div>
            <div class="">
                <div class="wrap-img bgcover" style="background-image: url(<?= Yii::$app->view->theme->baseUrl ?>/resources/images/2013080515PDR.jpg);"><a href="#"></a></div>
                <div class="content-news-hot">
                    <h2>9 dự án đang và sẽ thay đổi diện mạo Quận 4 2</h2>
                    <p>ngày đầu tiên là tên một trong bổ nhiệm vào vị trí  mới được bổ nhiệm vào vị trí  mới được bổ nhiệm vào vị trí  mới được bổ nhiệm vào vị trí...</p>
                </div>
            </div>
            <div class="">
                <div class="wrap-img bgcover" style="background-image: url(<?= Yii::$app->view->theme->baseUrl ?>/resources/images/87bbds2.jpg);"><a href="#"></a></div>
                <div class="content-news-hot">
                    <h2>9 dự án đang và sẽ thay đổi diện mạo Quận 4 3</h2>
                    <p>ngày đầu tiên là tên một trong những cuốn sách trên bàn làm việc của Nguyễn Trung Tín, mới được bổ nhiệm vào vị trí  mới được vị trí...</p>
                </div>
            </div>
            <div class="">
                <div class="wrap-img bgcover" style="background-image: url(<?= Yii::$app->view->theme->baseUrl ?>/resources/images/galaxy9.jpg);"><a href="#"></a></div>
                <div class="content-news-hot">
                    <h2>9 dự án đang và sẽ thay đổi diện mạo Quận 4 4</h2>
                    <p>ngày đầu tiên là tên một trong những cuốn sách trên bàn làm việc của Nguyễn Trung Tín, mới được bổ nhiệm vào vị trí  mới được bổ nhiệm ...</p>
                </div>
            </div>
        </div>

        <?php
        Pjax::begin();
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => [
                'tag' => 'div',
                'class' => 'row',
                'id' => 'list-wrapper',
            ],
            'summary' => '',
            'itemOptions' => [
                'tag' => false,
            ],
            'itemView' => 'list_item',
            'pager' => [
                'firstPageLabel' => false,
                'lastPageLabel' => false,
                'nextPageLabel' => '<em class="fa fa-chevron-right"></em>',
                'prevPageLabel' => '<em class="fa fa-chevron-left"></em>',
                'maxButtonCount' => 10,
                'options' => ['class' => 'pagination text-center'],
            ],
        ]);
        Pjax::end();
        ?>
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





