<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/9/2015 10:17 AM
 *
 * @var $list_news by $cat_id catalog
 */
use yii\bootstrap\Html;

?>
<div class="row">
    <div class="col-xs-8 col-lg-9 pdR-5">
        <div class="news-hot">
            <div class="wrap-img"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/bg-banner.jpg" alt=""></div>
            <div class="content-news-hot">
                <h2>9 dự án đang và sẽ thay đổi diện mạo Quận 4</h2>
                <p>ngày đầu tiên là tên một trong những cuốn sách trên bàn làm việc của Nguyễn Trung Tín, mới được bổ nhiệm vào vị trí  mới được bổ nhiệm vào vị trí  mới được bổ nhiệm vào vị trí  mới được bổ nhiệm vào vị trí  mới được bổ nhiệm vào vị trí  mới được bổ nhiệm vào vị trí Tổng Giám đốc Công ty Cổ phần Tập đoàn ổng Giám đốc Công ty Cổ phần Tập đoàn ổng Giám đốc Công ty Cổ phần Tập đoàn ổng Giám đốc Công ty Công ty Cổ phần Tập đoàn ổng Giám đốc Công ty Công ty Cổ phần Tập đoàn ổng Giám đốc Công ty  Cổ phần Tập đoàn ổng Giám đốc Công ty Cổ phần Tập đoàn Trung Thủy (TTG) hôm 15.1, đúng vào dịp sinh nhật lần thứ 52 của thân mẫu, bà Dương Thanh Thủy</p>
            </div>
        </div>
        <div class="news-bds">
            <div class="widget-title clearfix"><h2>bất động sản</h2></div>
            <div class="row">
                <?php foreach ($list_news as $k => $news) {?>
                <div class="col-xs-4">
                    <div class="wrap-img"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/img283x190.jpg" alt=""></div>
                    <p><a class="color-title-link" href="#">Nông nghiệp Việt Nam đón nhận làn sóng đầu tư chưa</a></p>
                    <p>Việt Nam có điều kiện tự nhiên thuận lợi để sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ, manh mún nên không xây dựng được thương hiệu... </p>
                </div>

                <div class="col-xs-4">
                    <div class="wrap-img"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/img283x190-1.jpg" alt=""></div>
                    <p><a class="color-title-link" href="#">Nông nghiệp Việt Nam đón nhận làn sóng đầu tư chưa</a></p>
                    <p>Việt Nam có điều kiện tự nhiên thuận lợi để sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ, manh mún nên không xây dựng được thương hiệu... </p>
                </div>
                <div class="col-xs-4">
                    <div class="wrap-img"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/img283x190-2.jpg" alt=""></div>
                    <p><a class="color-title-link" href="#">Nông nghiệp Việt Nam đón nhận làn sóng đầu tư chưa</a></p>
                    <p>Việt Nam có điều kiện tự nhiên thuận lợi để sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ, manh mún nên không xây dựng được thương hiệu... </p>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="col-xs-4 col-lg-3 pdL-5">
        <div class="siderbar widget-tinmoi clearfix siderbar-style">
            <div class="widget-title clearfix"><h2>Tin mới</h2></div>
            <ul>
                <li>
                    <a class="pull-left wrap-img" href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg" alt=""></a>
                    <div>
                        <a class="color-title-link" href="#">Việt Nam có điều kiện tự nhiên ...</a>
                        <p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ, manh mún nên không xây dựng được thương hiệu... </p>
                    </div>
                </li>
                <li>
                    <a class="pull-left wrap-img" href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg" alt=""></a>
                    <div>
                        <a class="color-title-link" href="#">Việt Nam có điều kiện tự nhiên ...</a>
                        <p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ, manh mún nên không xây dựng được thương hiệu... </p>
                    </div>
                </li>
                <li>
                    <a class="pull-left wrap-img" href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg" alt=""></a>
                    <div>
                        <a class="color-title-link" href="#">Việt Nam có điều kiện tự nhiên ...</a>
                        <p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ, manh mún nên không xây dựng được thương hiệu... </p>
                    </div>
                </li>
                <li>
                    <a class="pull-left wrap-img" href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg" alt=""></a>
                    <div>
                        <a class="color-title-link" href="#">Việt Nam có điều kiện tự nhiên ...</a>
                        <p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ, manh mún nên không xây dựng được thương hiệu... </p>
                    </div>
                </li>
            </ul>
        </div>
        <div class="siderbar widget-ads clearfix">
            <a class="wrap-img" href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/img295x210.jpg" alt=""></a>
        </div>
        <div class="siderbar widget-dqt clearfix siderbar-style">
            <div class="widget-title clearfix"><h2>đáng quan tâm</h2></div>
            <ul>
                <li>
                    <a class="pull-left wrap-img" href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg" alt=""></a>
                    <div>
                        <a class="color-title-link" href="#">Việt Nam có điều kiện tự nhiên ...</a>
                        <p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ, manh mún nên không xây dựng được thương hiệu... </p>
                    </div>
                </li>
                <li>
                    <a class="pull-left wrap-img" href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg" alt=""></a>
                    <div>
                        <a class="color-title-link" href="#">Việt Nam có điều kiện tự nhiên ...</a>
                        <p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ, manh mún nên không xây dựng được thương hiệu... </p>
                    </div>
                </li>
                <li>
                    <a class="pull-left wrap-img" href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg" alt=""></a>
                    <div>
                        <a class="color-title-link" href="#">Việt Nam có điều kiện tự nhiên ...</a>
                        <p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ, manh mún nên không xây dựng được thương hiệu... </p>
                    </div>
                </li>
                <li>
                    <a class="pull-left wrap-img" href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg" alt=""></a>
                    <div>
                        <a class="color-title-link" href="#">Việt Nam có điều kiện tự nhiên ...</a>
                        <p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ, manh mún nên không xây dựng được thương hiệu... </p>
                    </div>
                </li>
                <li>
                    <a class="pull-left wrap-img" href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg" alt=""></a>
                    <div>
                        <a class="color-title-link" href="#">Việt Nam có điều kiện tự nhiên ...</a>
                        <p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ, manh mún nên không xây dựng được thương hiệu... </p>
                    </div>
                </li>
                <li>
                    <a class="pull-left wrap-img" href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg" alt=""></a>
                    <div>
                        <a class="color-title-link" href="#">Việt Nam có điều kiện tự nhiên ...</a>
                        <p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ, manh mún nên không xây dựng được thương hiệu... </p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>






<div class="container-fluid" style="display: none">

    <!--row01-->
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 rowleft">
            <div class="titlebg">
                <h2 class="title"><?= Html::a('Bất động sản', ['list', 'cat_id' => $cat_id], ['style' => ['text-decoration' => 'none']]) ?></h2>
            </div>
            <?php foreach ($list_news as $k => $news) {
                if ($k === 0) { ?>
                    <div class="banner">
                        <img src="/store/news/show/<?= $news->banner ?>" alt="<?= $news->title ?>" title="<?=$news->brief?>">

                        <div class="hotnew">
                            <div class="block"></div>
                            <div class="text">
                                <h2 class="titlehotnew">
                                    <?= Html::a($news->title, ['view', 'id' => $news->id, 'slug' => $news->slug], ['style' => ['text-decoration' => 'none'], 'title' => $news->brief, ]) ?>
                                </h2>
                                <span class="tgpost">by Steve</span>
                                <span class="showtextcontent">
                                    <?= strlen($news->brief) > 320 ? mb_substr($news->brief, 0, 320) . '...' : $news->brief ?>
                                </span>
                                <span class="btndeitail"><button><?= Html::a('Xem chi tiết', ['view', 'id' => $news->id, 'slug' => $news->slug], ['style' => ['text-decoration' => 'none']]) ?></button></span>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <!--banner -->
                    <div class="listitemcontent">
                        <div class="grd4">
                            <div class="newstbl">
                                <?= Html::a("<img src=\"/store/news/show/$news->banner \" alt=\"$news->title\" title=\"$news->brief\" >", ['view', 'id' => $news->id, 'slug' =>  $news->slug], ['style' => ['text-decoration' => 'none']]) ?>

                            </div>
                            <div class="frst pdl">
                                <h3 class="title rotobobold"><?= Html::a($news->title, \yii\helpers\Url::to(['news/view', 'id' => $news->id, 'slug' => $news->slug]), ['style' => ['text-decoration' => 'none']]) ?></h3>

                                <p class="textfrst">
                                    <?= strlen($news->brief) > 300 ? mb_substr($news->brief, 0, 300) . '...' : $news->brief ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!--listitemcontent-->
                <?php }
            } ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 rgtcol">
            <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'hotnews']) ?>
        </div>
    </div>

</div>
