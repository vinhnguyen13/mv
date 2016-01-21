<?php
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('express','We offer exeptional amenities and renowned white - glove services');
?>
<?php $this->beginContent('@app/views/layouts/_partials/js/jsContainer.php', ['options'=>[]]); ?><?php $this->endContent();?>
<script>
    $(document).ready(function(){
        var swiper = new Swiper('.swiper-container', {
            scrollbar: '.swiper-scrollbar',
            scrollbarHide: false,
            slidesPerView: 4,
            slidesPerColumn: 2,
            paginationClickable: true,
            spaceBetween: 0,
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
        });
    });
</script>
<div id="wrapper-body" class="o-wrapper clearfix wrap-page-home">
    <?php $this->beginContent('@app/views/layouts/_partials/header.php'); ?><?php $this->endContent();?>

    <div id="scroll-rearch-reals"></div>
    <section class="search-reals">
        <div class="wrap-search">
            <div class="wrap-search-reals">
                <h1>Making the search for your ideal<br> home easy and transparent</h1>
                <div class="box-search-header clearfix">
                    <div class="pull-left">
                        <div class="pull-left text-right mgT-10 list-tabs-search">
                            <div data-active="1" data-tab="mua-thue" class="search-select active" data-step='step1'>
                                <a href="#">
                                    Buy / Rent
                                </a>
                            </div>
                            <div data-active="2" data-tab="ban-thue" class="search-select" data-step='step2'>
                                <a href="#">
                                    Sell
                                </a>
                            </div>
                        </div>
                        <?php $form = ActiveForm::begin([
                            'options'=>['class' => 'form-inline pull-left', 'method'=>'POST'],
                            'id'=>'search-kind',
                            'action'=>Url::to(['/ad/redirect']),
                            'fieldConfig' => [],
                        ]); ?>
                            <div class="form-group">
                                <div class="type-search">
                                    <ul class="outsideevent"></ul>
                                    <input id="searchInput" name="search" type="text" class="form-control outsideevent" placeholder="" readonly="readonly">
                                </div>

                                <div id="mua-thue" class="outsideevent search-wrap hidden-effect" data-step-title="Bạn Muốn Mua / Thuê ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <div class="item-render clearfix">
                                                    <ul class="clearfix">
                                                        <li class="buy-icon" data-id="1"><a href="#" data-item="mua-thue" data-slug-name data-next="tinh-thanh" data-prev><span></span>Muốn Mua</a></li>
                                                        <li class="rent-icon" data-id="2"><a href="#" data-item="mua-thue" data-slug-name data-next="tinh-thanh" data-prev><span></span>Muốn Thuê</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="ban-thue" class="outsideevent search-wrap hidden-effect" data-step-title="Đăng Ký Bán / Thuê ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <div class="item-render clearfix">
                                                    <ul class="clearfix">
                                                        <li class="buy-icon" data-id="1"><a href="#" data-item="ban-thue" data-slug-name data-next="tinh-thanh" data-prev><span></span>Đăng Ký Bán</a></li>
                                                        <li class="rent-icon" data-id="2"><a href="#" data-item="ban-thue" data-slug-name data-next="tinh-thanh" data-prev><span></span>Đăng Ký Cho Thuê</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="tinh-thanh" class="outsideevent search-wrap hidden-effect" data-step-title="Hãy Chọn Tỉnh / Thành ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <div class="item-render clearfix">
                                                    <ul></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="quan-huyen" class="outsideevent search-wrap hidden-effect" data-step-title="Hãy Chọn Quận / Huyện ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <div class="item-render clearfix">
                                                    <ul></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="loai-bds" class="outsideevent search-wrap hidden-effect" data-step-title="Loại bất động sản Bạn cần tìm ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <div class="item-render clearfix">
                                                    <ul></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="loai-tin-tuc" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn loại tin tức ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <div class="item-render clearfix">
                                                    <ul></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="news" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn tin tức ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <div class="item-render clearfix">
                                                    <ul></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="loai-duan" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn dự án chung cư ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <div class="item-render clearfix">
                                                    <ul></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="min-max" class="outsideevent search-wrap hidden-effect" data-step-title="Khoảng giá Bạn cần tìm ?">
                                    <div class="wrap-effect">
                                        <div class="search-item clearfix">
                                            <a href="#" class="btn-close-search"></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <div class="frm-cost-min-max clearfix">
                                                    <div class="form-group inline-group box-cost" data-tab="min">
                                                        <input type="text" class="form-control cost-value" placeholder="Min" readonly="readonly">
                                                        <input type="hidden" id="minCost" name="costMin" class="valPrice">
                                                        <div class=" wrap-cost-bds hidden-cost">
                                                            <div class="wrap-effect-cost">
                                                                <ul></ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="line-center form-group inline-group box-cost"><span></span></div>
                                                    <div class="form-group inline-group box-cost" data-tab="max">
                                                        <input type="text" class="form-control cost-value" placeholder="Max" readonly="readonly">
                                                        <input type="hidden" id="maxCost" name="costMax" class="valPrice">
                                                        <div class=" wrap-cost-bds hidden-cost">
                                                            <div class="wrap-effect-cost">
                                                                <ul></ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="btn-cost">
                                                        <button type="button" class="btn btn-primary btn-sm btn-common">OK</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <button id="btn-search" type="submit" class="btn btn-default icon">
                                <span><em class=""></em></span>
                            </button>

                            <input class="valInputHidden" id="valSearch" name="valSearch" type="hidden" value="">
                            <input class="valInputHidden" id="valTabActive" name="activeSearch" type="hidden" value="">
                            <input class="valInputHidden" id="valType" name="type" type="hidden" value="">
                            <input class="valInputHidden" id="valTinhThanh" name="city" type="hidden" value="">
                            <input class="valInputHidden" id="valQuanHuyen" name="district" type="hidden" value="">
                            <input class="valInputHidden" id="valLoaiBDS" name="category" type="hidden" value="">
                            <input class="valInputHidden" id="valDuAn" name="project" type="hidden" value="">
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
            <div class="btn-out-more"><a href="#">Find out more</a></div>
        </div>
    </section>
    
    <section class="tools-mv">
        <div class="container">
            <div class="text-center txt-top">
                <div class="buy-sell-review">
                    <button class="btn btn-common btn-byorrent">Buy or Rent</button>
                    <button class="btn btn-common btn-sell">Sell</button>
                </div>
                <h3>A simple solution for those looking to find a new owner for their home</h3>
            </div>
            <?php
            if(!empty($metvuong_news) && count($metvuong_news) > 0)
            {
                for ($i = 0; $i < count($metvuong_news); $i++) {
                    if ($i == 0) { ?>
                        <div class="item-review clearfix item-1">
                            <div class="pull-right item-review-txt">
                                <strong><?= $metvuong_news[$i]["title"] ?></strong>

                                <p><?= $metvuong_news[$i]["brief"] ?></p>

                                <div class="find-more-btn">
                                    <a href="<?= \yii\helpers\Url::to(['news/view', 'id' => $metvuong_news[$i]['id'], 'slug' => $metvuong_news[$i]['slug'], 'cat_id' => $metvuong_news[$i]['catalog_id'], 'cat_slug' => $metvuong_news[$i]["cat_slug"]]) ?>"><span class="icon pull-right"></span>Find out more</a>
                                </div>
                            </div>
                            <div class="img-review">
                                <img src="<?= "/store/news/show/".$metvuong_news[$i]["banner"]?>" alt="<?= $metvuong_news[$i]["title"] ?>">
                            </div>
                        </div>
                    <?php } else {
                        if ($i % 2 != 0) {
                            ?>
                            <div class="item-review clearfix item-2">
                                <div class="pull-right item-review-txt">
                                    <strong><?= $metvuong_news[$i]["title"] ?></strong>

                                    <p><?= $metvuong_news[$i]["brief"] ?></p>

                                    <div class="find-more-btn">
                                        <a href="<?= \yii\helpers\Url::to(['news/view', 'id' => $metvuong_news[$i]['id'], 'slug' => $metvuong_news[$i]['slug'], 'cat_id' => $metvuong_news[$i]['catalog_id'], 'cat_slug' => $metvuong_news[$i]["cat_slug"]]) ?>"><span class="icon pull-right"></span>Find out more</a>
                                    </div>
                                </div>
                                <div class="img-review">
                                    <img src="<?= "/store/news/show/" . $metvuong_news[$i]["banner"] ?>"
                                         alt="<?= $metvuong_news[$i]["title"] ?>">
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="item-review clearfix item-3">
                                <div class="pull-right img-review">
                                    <img src="<?= "/store/news/show/" . $metvuong_news[$i]["banner"] ?>"
                                         alt="<?= $metvuong_news[$i]["title"] ?>">
                                </div>
                                <div class="item-review-txt">
                                    <strong><?= $metvuong_news[$i]["title"] ?></strong>
                                    <p><?= $metvuong_news[$i]["brief"] ?></p>
                                    <div class="find-more-btn">
                                        <a href="<?= \yii\helpers\Url::to(['news/view', 'id' => $metvuong_news[$i]['id'], 'slug' => $metvuong_news[$i]['slug'], 'cat_id' => $metvuong_news[$i]['catalog_id'], 'cat_slug' => $metvuong_news[$i]["cat_slug"]]) ?>"><span class="icon pull-right"></span>Find out more</a>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    }
                }
            }?>
        </div>
    </section>

    <section class="projects">
        <div class="container">
            <h2>Featured Project</h2>
            <ul class="list-filter-projects">
                <li><a href="#">Condominium</a></li>
                <li><a href="#">Town House</a></li>
                <li><a href="#">Landed Property</a></li>
            </ul>
            <div class="row list-du-an">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-1.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-2.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-3.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-4.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-1.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-2.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-3.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-4.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-1.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-2.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-3.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-4.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-1.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-2.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-3.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 swiper-slide">
                            <a href="#">
                                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-4.jpg" alt="">
                                <div>
                                    <p><span class="name-duan">Syrena Nha Trang</span>
                                      <span class="see-more">Click to see more</span></p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="swiper-scrollbar"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
    </section>
    <?php if(!empty($news) && count($news) > 0){?>
    <section class="explore-us">
        <div class="container">
            <h2>Explore with us</h2>
            <?php for($i=0; $i<count($news); $i++) {
                if ($i%2 == 0) {
                    ?>
                    <div class="clearfix item-1">
                        <div class="pull-right img-review">
                            <img src="<?=Url::to('/store/news/show/' . $news[$i]['banner']) ?>" alt="<?=$news[$i]['title']?>" alt="<?=$news[$i]['title']?>" width="550" height="350">
                        </div>
                        <div class="item-review-txt">
                            <strong><?=$news[$i]['title']?></strong>

                            <p><?=$news[$i]['brief']?></p>

                            <div class="find-more-btn">
                                <a href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news[$i]['id'], 'slug' => $news[$i]['slug'], 'cat_id' => $news[$i]['catalog_id'], 'cat_slug' => $news[$i]["cat_slug"]]) ?>"><span class="icon pull-right"></span>Find out more</a>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="clearfix item-2">
                        <div class="pull-right item-review-txt">
                            <strong><?=$news[$i]['title']?></strong>

                            <p><?=$news[$i]['brief']?></p>

                            <div class="find-more-btn">
                                <a href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news[$i]['id'], 'slug' => $news[$i]['slug'], 'cat_id' => $news[$i]['catalog_id'], 'cat_slug' => $news[$i]["cat_slug"]]) ?>"><span class="icon pull-right"></span>Find out more</a>
                            </div>
                        </div>
                        <div class="img-review">
                            <img src="<?=Url::to('/store/news/show/' . $news[$i]['banner']) ?>" alt="<?=$news[$i]['title']?>" width="550" height="350">
                        </div>
                    </div>
                <?php }
            }?>
        </div>
    </section>
    <?php } ?>
</div>
<?php $this->beginContent('@app/views/layouts/_partials/footer.php'); ?><?php $this->endContent();?>