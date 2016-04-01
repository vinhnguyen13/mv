<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;

$newsCatID = isset(Yii::$app->params["newsCatID"]) ? Yii::$app->params["newsCatID"] : 0;
$catalogs = \vsoft\news\models\CmsCatalog::findAll(['parent_id'=>$newsCatID, 'status' => \vsoft\news\models\Status::STATUS_ACTIVE]);

?>
<div class="title-fixed-wrap">
    <div class="container">
        <div class="page-news">
            <?= $this->render('/news/_partials/menu', ['cat_id'=>$cat_id]); ?>
            <?php if(count($news)){?>
                <div class="row wrap-news-page">
                    <div class="wrap-news col-xs-12 col-md-9">
                        <ul class="clearfix row list-news">
                            <?php foreach($news as $n) {
                                $banner = "/store/news/show/".$n["banner"];
                                $checkBanner = file_exists(Yii::getAlias('@store')."/news/show/".$n["banner"]);
                                if($checkBanner == false)
                                    $banner = '/themes/metvuong2/resources/images/default-ads.jpg';
                                ?>
                                <li class="col-xs-12 col-sm-6 col-md-4">
                                    <div>
                                        <a href="<?=\yii\helpers\Url::to(['news/view', 'id' => $n["id"], 'slug' => $n["slug"]], true)?>" class="rippler rippler-default">
                                            <div class="img-show"><div><img src="<?=$banner?>" alt="<?=$n["title"]?>"></div></div>
                                        </a>
                                        <div>
                                            <a href="<?=\yii\helpers\Url::to(['news/list', 'cat_id'=>$n["catalog_id"], 'cat_slug'=>$n["cat_slug"]], true)?>" class="name-cate"><?=mb_strtoupper($n["cat_title"], "UTF-8")?></a>
                                            <p class="name-news"><a href="<?=\yii\helpers\Url::to(['news/view', 'id' => $n["id"], 'slug' => $n["slug"]], true)?>"><?=$n["title"]?></a></p>
                                            <p class="date-post"><?=date('d/m/Y, H:i', $n["created_at"])?></p>
                                            <p class="short-txt">
                                                <?=\yii\helpers\StringHelper::truncate($n["brief"], 200)?>
                                            </p>
                                            <a href="<?=\yii\helpers\Url::to(['news/view', 'id' => $n["id"], 'slug' => $n["slug"]], true)?>" class="view-more"><?=Yii::t('news','Read more')?> <span class="icon arrowLeft-small-black"></span></a>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                        <nav class="text-center">
                            <?php
                            echo LinkPager::widget([
                                'pagination' => $pagination
                            ]);
                            ?>
                        </nav>
                    </div>
                    <div class="col-md-3 col-xs-12 sidebar-news">
                        <div class="item-sidebar clearfix">
                            <div class="title-sidebar">TIN NỔI BẬT</div>
                            <div class="item-hot-sidebar">
                                <a href="#"><div class="img-show"><div><img src="http://file4.batdongsan.com.vn/resize/350x280/2016/01/21/20160121085435-7e5d.jpg" alt="<?=$n["title"]?>"></div></div></a>
                                <a href="#" class="name-post">Lorem ipsum dolorit diam est amet odio varius odio </a>
                                <p class="intro-txt">Quisque varius iaculis odio sit amet elementum. Nunc porta cursus est a pretium. Nullam non metus tristique sem hendrerit tincidunt ac aliquet lectus. Vivamus ultrices laoreet convallis.</p>
                            </div>
                            <ul class="list-lq">
                                <li><a href="#"><span class="dot"></span>Lorem ipsum dolorit diam est amet odio varius odio sem </a></li>
                                <li><a href="#"><span class="dot"></span>Lorem ipsum dolorit diam est amet odio varius odio sem </a></li>
                                <li><a href="#"><span class="dot"></span>Lorem ipsum dolorit diam est amet odio varius odio sem </a></li>
                            </ul>
                        </div>
                        <div class="item-sidebar clearfix">
                            <div class="title-sidebar">TIN TÀI CHÍNH NGÂN HÀNG</div>
                            <div class="item-hot-sidebar">
                                <a href="#"><div class="img-show"><div><img src="http://file4.batdongsan.com.vn/resize/350x280/2016/01/21/20160121085435-7e5d.jpg" alt="<?=$n["title"]?>"></div></div></a>
                                <a href="#" class="name-post">Lorem ipsum dolorit diam est amet odio varius odio </a>
                                <p class="intro-txt">Quisque varius iaculis odio sit amet elementum. Nunc porta cursus est a pretium. Nullam non metus tristique sem hendrerit tincidunt ac aliquet lectus. Vivamus ultrices laoreet convallis.</p>
                            </div>
                            <ul class="list-lq">
                                <li><a href="#"><span class="dot"></span>Lorem ipsum dolorit diam est amet odio varius odio sem </a></li>
                                <li><a href="#"><span class="dot"></span>Lorem ipsum dolorit diam est amet odio varius odio sem </a></li>
                                <li><a href="#"><span class="dot"></span>Lorem ipsum dolorit diam est amet odio varius odio sem </a></li>
                            </ul>
                        </div>
                        <div class="item-sidebar clearfix">
                            <div class="title-sidebar">TIN BẤT ĐỘNG SẢN</div>
                            <div class="item-hot-sidebar">
                                <a href="#"><div class="img-show"><div><img src="http://file4.batdongsan.com.vn/resize/350x280/2016/01/21/20160121085435-7e5d.jpg" alt="<?=$n["title"]?>"></div></div></a>
                                <a href="#" class="name-post">Lorem ipsum dolorit diam est amet odio varius odio </a>
                                <p class="intro-txt">Quisque varius iaculis odio sit amet elementum. Nunc porta cursus est a pretium. Nullam non metus tristique sem hendrerit tincidunt ac aliquet lectus. Vivamus ultrices laoreet convallis.</p>
                            </div>
                            <ul class="list-lq">
                                <li><a href="#"><span class="dot"></span>Lorem ipsum dolorit diam est amet odio varius odio sem </a></li>
                                <li><a href="#"><span class="dot"></span>Lorem ipsum dolorit diam est amet odio varius odio sem </a></li>
                                <li><a href="#"><span class="dot"></span>Lorem ipsum dolorit diam est amet odio varius odio sem </a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var swiper = new Swiper('.list-menu-news > .container', {
            paginationClickable: true,
            spaceBetween: 0,
            slidesPerView: 'auto',
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev'
        });
    });
</script>