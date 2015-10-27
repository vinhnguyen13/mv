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
<div class="row">
    <div class="col-sm-8 col-lg-9 col-right-home lists-page">
        <div class="news-hot">
            <div class="wrap-img bgcover"
                 style="background-image: url(<?= Yii::$app->view->theme->baseUrl ?>/resources/images/galaxy9.jpg);"><a
                    href="#"></a></div>
            <div class="content-news-hot">
                <h2>9 dự án đang và sẽ thay đổi diện mạo Quận 4</h2>

                <p>Ngày đầu tiên là tên một trong những cuốn sách trên bàn làm việc của Nguyễn Trung Tín, mới được bổ
                    nhiệm
                    vào vị trí mới được bổ nhiệm vào vị trí mới được bổ nhiệm vào vị trí mới được bổ nhiệm vào vị trí
                    mới
                    được bổ nhiệm vào vị trí...</p>
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
            'itemView' => 'list_item',
            'itemOptions' => [
                'tag' => false,
            ],
            'pager' => [
                'firstPageLabel' => false,
                'lastPageLabel' => false,
                'nextPageLabel' => '<em class="fa fa-chevron-right"></em>',
                'prevPageLabel' => '<em class="fa fa-chevron-left"></em>',
                'maxButtonCount' => 10,
            ],
        ]);
        Pjax::end();
        ?>
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





