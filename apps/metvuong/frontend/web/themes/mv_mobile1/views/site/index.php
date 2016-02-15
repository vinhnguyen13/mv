<?php 
	use yii\helpers\Url;
?>
<section class="search-box">
    <div class="text-center sologan">
        Đưa bạn đến ngôi nhà yêu thích<br>
        Tìm hiểu &amp; giao dịch bất động sản dễ dàng
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
</section>
<section class="search-home">
    <h2>Chúng tôi sẽ giúp bạn tìm được ngôi nhà mà mình mong muốn trong chôc lát</h2>
    <p>Mét Vuông cung cấp lên đến hàng chục ngàn các căn hộ cao cấp, nhà riêng và biệt thự cho thuê hoặc đang rao bán tại hầu hết các tỉnh thành trên cả nước. Thông qua những mô tả chi tiết và hình ảnh chân thật, chúng tôi cam kết giúp khách hàng tìm được sản phẩm phù hợp nhất, một cách nhanh chóng và hiệu quả.</p>
    <div class="wrap-pic-demo wrap-img">
        <img src="<?=Yii::$app->view->theme->baseUrl."/resources/"?>images/mac-demo.jpg" alt="" />
    </div>
    <div class="text-center"><a class="txt-link-timnha" href="#">tìm nhà</a></div>
</section>
<section class="regis-home">
    <h2>Tìm người khách hàng cho căn nhà của bạn dễ dàng hơn bao giờ hết</h2>
    <p>Chỉ với vài thao tác nhập thông tin đơn giản, Mét Vuông sẽ là cầu nối giúp bạn nhanh chóng tiếp cận với nguồn khách hàng tiềm năng. Bạn có thể giới thiệu về BĐS của mình tới hàng triệu người có nhu cầu một cách trực quan nhất.</p>
    <div class="wrap-pic-demo wrap-img">
        <img src="<?=Yii::$app->view->theme->baseUrl."/resources/"?>images/mac-demo.jpg" alt="" />
    </div>
    <div class="text-center"><a class="txt-link-timnha" href="#">đăng tin</a></div>
</section>