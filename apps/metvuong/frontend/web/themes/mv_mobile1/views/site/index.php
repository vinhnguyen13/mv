<?php 
	use yii\helpers\Url;
?>
<section class="search-box">
    <!-- <div class="text-center sologan">
        Đưa bạn đến ngôi nhà yêu thích<br>
        Tìm hiểu &amp; giao dịch bất động sản dễ dàng
    </div> -->
    <div class="search-wrap-home">
        <div class="text-center logo-search-box wrap-img">
            <img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/logo.png' ?>" alt="">
        </div>
        <div class="search-homepage">
            <ul class="clearfix">
                <li><a href="<?= Url::to(['/ad/index']) ?>">Mua</a></li>
                <li><a href="#">Thuê</a></li>
                <li><a href="<?= Url::to(['/ad/post']) ?>">Bán / Cho Thuê</a></li>
            </ul>
            <form id="" action="">
                <input autocomplete="off" data-url="<?= Url::to(['site/search']) ?>" id="search" type="text" placeholder="Tìm kiếm nhanh..." />
                <button type="submit" id="btn-search"><span class="icon"></span></button>
                <div class="suggest-search hide">
                    <div class="content-suggest">
                        <ul></ul>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<section class="search-home">
    <div class="txt-intro">
        <h2>Chúng tôi sẽ giúp bạn tìm được ngôi nhà mà mình mong muốn trong chốc lát</h2>
        <p>Mét Vuông cung cấp lên đến hàng chục ngàn các căn hộ cao cấp, nhà riêng và biệt thự cho thuê hoặc đang rao bán tại hầu hết các tỉnh thành trên cả nước. Thông qua những mô tả chi tiết và hình ảnh chân thật, chúng tôi cam kết giúp khách hàng tìm được sản phẩm phù hợp nhất, một cách nhanh chóng và hiệu quả.</p>
    </div>
</section>

<section class="box-item">
    <div class="title-sub">Featured properties</div>
    <div class="wrap-item">
        <div class="item">
            <a href="#" class="pic-intro">
                <div class="wrap-img"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/Government_-South_Australia_Police_Headquarters_Built_Environs_main.jpg' ?>" alt=""></div>
                <span class="address-feat">
                    <strong>Lancaster x</strong>
                    21 Nguyễn Trung Ngạn, P.Bến Nghé, Q1
                </span>
                
            </a>
            <div class="info-item">
                <ul class="clearfix list-tienich-intro">
                    <li>
                        <span class="wrap-icon"><span class="icon icon-dt"></span></span>Diện tích
                        <strong class="pull-right color-30a868">52m2</strong>
                    </li>
                    <li>
                        <span class="wrap-icon"><span class="icon icon-pt"></span></span>Phòng tắm
                        <strong class="pull-right color-30a868">02</strong>
                    </li>
                    <li>
                        <span class="wrap-icon"><span class="icon icon-bed"></span></span>Phòng ngủ
                        <strong class="pull-right color-30a868">02</strong>
                    </li>
                    <li class="price-line">
                        <span class="price">2.8 tỷ</span>
                        <a href="#" class="pull-right">Chi tiết</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="item">
            <a href="#" class="pic-intro">
                <div class="wrap-img"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/Government_-South_Australia_Police_Headquarters_Built_Environs_main.jpg' ?>" alt=""></div>
                <span class="address-feat">
                    <strong>Lancaster x</strong>
                    21 Nguyễn Trung Ngạn, P.Bến Nghé, Q1
                </span>
                
            </a>
            <div class="info-item">
                <ul class="clearfix list-tienich-intro">
                    <li>
                        <span class="wrap-icon"><span class="icon icon-dt"></span></span>Diện tích
                        <strong class="pull-right color-30a868">52m2</strong>
                    </li>
                    <li>
                        <span class="wrap-icon"><span class="icon icon-pt"></span></span>Phòng tắm
                        <strong class="pull-right color-30a868">02</strong>
                    </li>
                    <li>
                        <span class="wrap-icon"><span class="icon icon-bed"></span></span>Phòng ngủ
                        <strong class="pull-right color-30a868">02</strong>
                    </li>
                    <li class="price-line">
                        <span class="price">2.8 tỷ</span>
                        <a href="#" class="pull-right">Chi tiết</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="item">
            <a href="#" class="pic-intro">
                <div class="wrap-img"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/Government_-South_Australia_Police_Headquarters_Built_Environs_main.jpg' ?>" alt=""></div>
                <span class="address-feat">
                    <strong>Lancaster x</strong>
                    21 Nguyễn Trung Ngạn, P.Bến Nghé, Q1
                </span>
                
            </a>
            <div class="info-item">
                <ul class="clearfix list-tienich-intro">
                    <li>
                        <span class="wrap-icon"><span class="icon icon-dt"></span></span>Diện tích
                        <strong class="pull-right color-30a868">52m2</strong>
                    </li>
                    <li>
                        <span class="wrap-icon"><span class="icon icon-pt"></span></span>Phòng tắm
                        <strong class="pull-right color-30a868">02</strong>
                    </li>
                    <li>
                        <span class="wrap-icon"><span class="icon icon-bed"></span></span>Phòng ngủ
                        <strong class="pull-right color-30a868">02</strong>
                    </li>
                    <li class="price-line">
                        <span class="price">2.8 tỷ</span>
                        <a href="#" class="pull-right">Chi tiết</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="box-item news-item">
    <div class="title-sub">news</div>
    <div class="wrap-item">
        <div class="item clearfix">
            <a href="#">
                <div class="wrap-img bgcover" style="background-image:url(<?= Yii::$app->view->theme->baseUrl . '/resources/images/Government_-South_Australia_Police_Headquarters_Built_Environs_main.jpg' ?>);"></div>
                <span class="txt-short-news">
                    <span class="title-news color-30a868">Lorem ipsum dolor sit amet, consectetur adipiscing elit</span>
                    <span class="date-news">12/02/2016, 14:30</span>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua...
                </span>
            </a>
        </div>
        <div class="item clearfix">
            <a href="#">
                <div class="wrap-img bgcover" style="background-image:url(<?= Yii::$app->view->theme->baseUrl . '/resources/images/Government_-South_Australia_Police_Headquarters_Built_Environs_main.jpg' ?>);"></div>
                <span class="txt-short-news">
                    <span class="title-news color-30a868">Lorem ipsum dolor sit amet, consectetur adipiscing elit</span>
                    <span class="date-news">12/02/2016, 14:30</span>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua...
                </span>
            </a>
        </div>
        <div class="item clearfix">
            <a href="#">
                <div class="wrap-img bgcover" style="background-image:url(<?= Yii::$app->view->theme->baseUrl . '/resources/images/Government_-South_Australia_Police_Headquarters_Built_Environs_main.jpg' ?>);"></div>
                <span class="txt-short-news">
                    <span class="title-news color-30a868">Lorem ipsum dolor sit amet, consectetur adipiscing elit</span>
                    <span class="date-news">12/02/2016, 14:30</span>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua...
                </span>
            </a>
        </div>
    </div>
</section>