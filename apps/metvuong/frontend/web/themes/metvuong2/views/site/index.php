<?php
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('express','We offer exeptional amenities and renowned white - glove services');
?>
<?php $this->beginContent('@app/views/layouts/_partials/js/jsContainer.php', ['options'=>[]]); ?><?php $this->endContent();?>
<script>
    $(document).ready(function(){
        $(document).bind( 'submit_search', function(event, json, string, callback){
            //animateSearch();
            setTimeout(function(){callback();},1000);
            setTimeout(function() {
                if (typeof ga !== "undefined") {
                    ga('send', {hitType: 'event',eventCategory: 'Listing',eventAction: 'click',eventLabel: 'SearchForm'});
                }
                setTimeout(function() {$('#search-kind').submit();},100);
            },500);
            return false;
        });

        $(document).bind( 'real-estate/news', function(event, json, string, callback){
            //animateSearch();
            setTimeout(function(){callback();},1000);
            setTimeout(function() {
                if (typeof ga !== "undefined") {
                    ga('send', {hitType: 'event',eventCategory: 'PostListing',eventAction: 'click',eventLabel: 'SearchForm'});
                }
                //setTimeout(function() {$('#search-kind').submit();},100);
            },500);
        });
        
        $(document).bind( 'real-estate/post', function(event, json, string, callback){
            //animateSearch();
            setTimeout(function(){callback();},1000);
            setTimeout(function() {
                if (typeof ga !== "undefined") {
                    ga('send', {hitType: 'event',eventCategory: 'News',eventAction: 'click',eventLabel: 'SearchForm'});
                }
                setTimeout(function() {$('#search-kind').submit();},100);
            },500);
        });

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
    <header class="clearfix">
        <div class="container inner-header">
            <ul class="pull-right list-menu">
                <li><a href="<?=Url::to(['ad/index', 'type'=>1]);?>">Buy</a></li>
                <li><a href="<?=Url::to(['ad/index', 'type'=>1]);?>">Rent</a></li>
                <li><a href="<?=Url::to(['ad/post']);?>">Sell</a></li>
                <li><a href="javascript:alert('Comming Soon !');">Market Insights</a></li>
                <?php if(Yii::$app->user->isGuest){?>
                    <li class="link-signup"><a href="#" data-toggle="modal" data-target="#frmRegister">Sign up</a></li>
                    <li class="link-login"><a href="#" data-toggle="modal" data-target="#frmLogin">Login</a></li>
                    <li class="change-lang"><a href="<?=Url::current(['language-change'=>'en-US'])?>" class="lang-icon icon-en"></a></li>
                    <li class="change-lang"><a href="<?=Url::current(['language-change'=>'vi-VN'])?>" class="lang-icon icon-vi"></a></li>
                <?php }else{?>
                    <li class="user-loggin"><a href="<?=Url::to(['user-management/index'])?>">
                            <span class="avatar-user"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/default-avatar.jpg" alt="" width="40" height="40"></span>
                            <span class="name-user"><?=!empty(Yii::$app->user->identity->profile->name) ? Yii::$app->user->identity->profile->name : Yii::$app->user->identity->email;?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sub-setting-user"></a>
                        <div class="settings container-effect hidden-effect">
                            <ul class="sub-setting wrap-effect">
                                <li>
                                    <a data-method="post" href="<?=\yii\helpers\Url::to(['/member/logout'])?>"><em class="icon-logout pull-right"></em><?=Yii::t('user', 'Logout')?></a>
                                </li>
                                <li>
                                    <a href="<?=Url::to(['user-management/index'])?>"><em class="fa fa-cog pull-right"></em>Settings</a>
                                </li>
                                <li class="line-option">
                                    <div class="select-lang">
                                        <a href="<?=Url::current(['language-change'=>'vi-VN'])?>" class="lang-icon icon-vi pull-right"></a>
                                        <a href="<?=Url::current(['language-change'=>'en-US'])?>" class="lang-icon icon-en pull-right"></a>
                                        Language
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php }?>
            </ul>
            <a href="<?=Url::home()?>" class="logo-header"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/logo.png" alt=""></a>
        </div>
    </header>

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
                                    Buy/Rent
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

                                <div id="mua-thue" class="outsideevent search-wrap hidden-effect" data-step-title="Muốn Mua/Thuê ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <ul class="clearfix">
                                                    <li data-id="1"><a href="#" data-item="mua-thue" data-slug-name data-next="tinh-thanh" data-prev>Muốn Mua</a></li>
                                                    <li data-id="2"><a href="#" data-item="mua-thue" data-slug-name data-next="tinh-thanh" data-prev>Muốn Thuê</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="ban-thue" class="outsideevent search-wrap hidden-effect" data-step-title="Đăng Ký Bán/Thuê ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <ul class="clearfix">
                                                    <li data-id="1"><a href="#" data-item="ban-thue" data-slug-name data-next="tinh-thanh" data-prev>Đăng Ký Bán</a></li>
                                                    <li data-id="2"><a href="#" data-item="ban-thue" data-slug-name data-next="tinh-thanh" data-prev>Đăng Ký Cho Thuê</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="tinh-thanh" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn Tỉnh/Thành ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <ul></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="quan-huyen" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn Quận/Huyện ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <ul></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="loai-bds" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn loại bất động sản ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <ul></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="loai-tin-tuc" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn loại tin tức ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <ul></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="news" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn tin tức ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <ul></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="loai-duan" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn dự án ?">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <ul></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="min-max" class="outsideevent search-wrap hidden-effect" data-step-title="Nhập khoảng giá ?">
                                    <div class="wrap-effect">
                                        <div class="search-item clearfix">
                                            <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                            <div class="wrap-step">
                                                <h3></h3>
                                                <div class="frm-cost-min-max clearfix">
                                                    <div class="form-group inline-group box-cost" data-tab="min">
                                                        <input type="text" class="form-control cost-value" placeholder="min" readonly="readonly">
                                                        <input type="hidden" id="minCost" name="costMin" class="valPrice">
                                                        <div class=" wrap-cost-bds hidden-cost">
                                                            <div class="wrap-effect-cost">
                                                                <ul></ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="line-center form-group inline-group box-cost"><span></span></div>
                                                    <div class="form-group inline-group box-cost" data-tab="max">
                                                        <input type="text" class="form-control cost-value" placeholder="max" readonly="readonly">
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
                                    <a href="#"><span class="icon pull-right"></span>Find out more</a>
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
                                        <a href="#"><span class="icon pull-right"></span>Find out more</a>
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
                                        <a href="#"><span class="icon pull-right"></span>Find out more</a>
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
                                <a href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news[$i]['id'], 'slug' => $news[$i]['slug'], 'cat_id' => $news[$i]['catalog_id']]) ?>"><span class="icon pull-right"></span>Find out more</a>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="clearfix item-2">
                        <div class="pull-right item-review-txt">
                            <strong><?=$news[$i]['title']?></strong>

                            <p><?=$news[$i]['brief']?></p>

                            <div class="find-more-btn">
                                <a href="<?= \yii\helpers\Url::to(['news/view', 'id' => $news[$i]['id'], 'slug' => $news[$i]['slug'], 'cat_id' => $news[$i]['catalog_id']]) ?>"><span class="icon pull-right"></span>Find out more</a>
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

    <div id="iePopup">
        <div id="jr_overlay"></div>
        <div id="jr_wrap">
            <div id="jr_inner">
                <h1 id="jr_header">Bạn có biết rằng trình duyệt của bạn đã lỗi thời?</h1>
                <p>Trình duyệt của bạn đã lỗi thời, và có thể không tương thích tốt với website, chắc chắn rằng trải nghiệm của bạn trên website sẽ bị hạn chế. Bên dưới là danh sách những trình duyệt phổ biến hiện nay.</p>
                <p>Click vào biểu tượng để tải trình duyệt bạn muốn.</p>
                <ul>
                    <li id="jr_chrome"><a href="http://www.google.com/chrome/" target="_blank">Chrome 34</a></li>
                    <li id="jr_firefox"><a href="http://www.mozilla.com/firefox/" target="_blank">Firefox 29</a></li>
                    <li id="jr_msie"><a href="http://www.microsoft.com/windows/Internet-explorer/" target="_blank">Internet Explorer 10</a></li>
                    <li id="jr_opera"><a href="http://www.opera.com/download/" target="_blank">Opera 20</a></li>
                    <li id="jr_safari"><a href="http://www.apple.com/safari/download/" target="_blank">Safari</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $this->beginContent('@app/views/layouts/_partials/footer.php'); ?><?php $this->endContent();?>
<?php $this->beginContent('@app/views/layouts/_partials/popup.php'); ?><?php $this->endContent();?>