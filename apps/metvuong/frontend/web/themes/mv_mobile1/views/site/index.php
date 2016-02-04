<?php 
	use yii\helpers\Url;
?>
<section class="search-box">
    <div class="search-homepage">
        <ul class="clearfix">
            <li><a href="#">Mua</a></li>
            <li><a href="#">Thuê</a></li>
            <li><a href="#">Bán / Cho Thuê</a></li>
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
    <p>Being the savage's bowsman, that is, the person who pulled the bow-oar in his boat (the second one from forward), it was my cheerful duty to attend upon him while taking that hard-scrabble scramble upon the dead whale's back. You have seen Italian organ-boys holding a dancing-ape by a long cord.</p>
    <div class="wrap-pic-demo wrap-img">
        <img src="<?=Yii::$app->view->theme->baseUrl."/resources/"?>images/mac-demo.jpg" alt="" />
    </div>
    <div class="text-center"><a class="txt-link-timnha" href="#">tìm nhà</a></div>
</section>
<section class="regis-home">
    <h2>Tìm người khách hàng cho căn nhà của bạn dễ dàng hơn bao giờ hết</h2>
    <p>Being the savage's bowsman, that is, the person who pulled the bow-oar in his boat (the second one from forward), it was my cheerful duty to attend upon him while taking that hard-scrabble scramble upon the dead whale's back. You have seen Italian organ-boys holding a dancing-ape by a long cord. </p>
    <div class="wrap-pic-demo wrap-img">
        <img src="<?=Yii::$app->view->theme->baseUrl."/resources/"?>images/mac-demo.jpg" alt="" />
    </div>
    <div class="text-center"><a class="txt-link-timnha" href="#">đăng tin</a></div>
</section>