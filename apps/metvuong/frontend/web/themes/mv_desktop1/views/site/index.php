<?php
use yii\helpers\StringHelper;
use yii\helpers\Url;
use frontend\models\AdProductSearch;
use vsoft\ad\models\AdProduct;
?>
<div class="page-home">

    <section class="search-box hFullScreen">
        <!-- <div class="text-center sologan">
            Đưa bạn đến ngôi nhà yêu thích<br>
            Tìm hiểu &amp; giao dịch bất động sản dễ dàng
        </div> -->
        <div id="hScreen"></div>
        <div class="search-wrap-home">
            <div class="text-center logo-search-box wrap-img">
                <img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/logo.png' ?>" alt="">
            </div>
            <div class="search-homepage">
                <ul class="clearfix">
                    <li><a class="rippler rippler-default" href="<?= Url::to(['/ad/index', 'type' => AdProduct::TYPE_FOR_SELL, 'city_id' => AdProductSearch::DEFAULT_CITY, 'district_id' => AdProductSearch::DEFAULT_DISTRICT]) ?>"><?=Yii::t('general', 'Buy')?></a></li>
                    <li><a class="rippler rippler-default" href="<?= Url::to(['/ad/index', 'type' => AdProduct::TYPE_FOR_RENT, 'city_id' => AdProductSearch::DEFAULT_CITY, 'district_id' => AdProductSearch::DEFAULT_DISTRICT]) ?>"><?=Yii::t('general', 'Rent')?></a></li>
                    <li><a class="rippler rippler-default" href="<?= Url::to(['/ad/post']) ?>"><?=Yii::t('general', 'Sell')?></a></li>
                </ul>
                <form id="search-form" action="<?= Url::to(['site/search']) ?>">
                    <input autocomplete="off" id="search" name="v" type="text" placeholder="<?=Yii::t('general', 'Quick Search')?>" />
                    <button type="submit" id="btn-search"><span class="icon-mv"><span class="icon-icons-search"></span></span></button>
                    <div class="suggest-search hide">
                        <div class="content-suggest">
                            <a class="btn-close"><span class="icon icon-close"></span></a>
                            <ul></ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- <span class="arrow-down"></span> -->
    </section>

    <section class="box-item box-feature-item">
        <div class="container">
            <div class="title-sub"><?= Yii::t('listing', 'Featured listings') ?></div>
            <div class="wrap-item wrap-lazy">

            </div>
        </div>
    </section>


    <section class="box-item news-item">
        <div class="container">
            <div class="title-sub"><?=Yii::t('news','NEWS')?></div>
            <div class="wrap-item">

            </div>
        </div>
    </section>

    <script>
        $(document).ready(function () {
            $('.box-feature-item').loading({full: false});
            $( ".box-feature-item .wrap-item" ).load( "<?=Url::to(['site/feature-listings'])?>", function(data) {
                $( ".box-feature-item .wrap-item").html(data);
                $('body').loading({done:true});
            });

            $('.news-item').loading({full: false});
            $( ".news-item .wrap-item" ).load( "<?=Url::to(['site/news'])?>", function(data) {
                $( ".news-item .wrap-item").html(data);
                $('body').loading({done:true});
            });
        });
    </script>

    <section class="search-home">
        <div class="container">
            <div class="title-sub"><?= Yii::t('general', 'LIÊN HỆ') ?></div>
            <div class="clearfix">
                <div class="wrap-img map-location">
                    <div class="img-show"><iframe width="100%" height="100%" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=place_id:EksyMSBOZ3V54buFbiBUcnVuZyBOZ-G6oW4sIELhur9uIE5naMOpLCBRdeG6rW4gMSwgSOG7kyBDaMOtIE1pbmgsIFZp4buHdCBOYW0&key=AIzaSyDgukAnWQNq0fitebULUbottG5gvK64OCQ" allowfullscreen></iframe></div>
                </div>
                <div class="txt-location">
                    <p class="name-cty color-cd"><?= Yii::t('general', 'METVUONG TEAM') ?></p>
                    <ul class="clearfix">
                        <li>
                            <span class="icon-mv"><span class="icon-pin-active-copy-3"></span></span>
                            <p>Lầu 12 Tòa nhà Miss Aodai <br> 21 Nguyễn Trung Ngạn, Quận 1, thành phố Hồ Chí Minh.</p>
                        </li>
                        <li>
                            <span class="icon-mv"><span class="icon-phone-profile"></span></span>
                            <p><a href="tel:08345678">08. 345 678</a> - <a href="tel:0908123456">0908 123 456</a></p>
                        </li>
                        <li>
                            <span class="icon-mv"><span class="icon-mail-profile"></span></span>
                            <p>contact@metvuong.com</p>
                        </li>
                    </ul>
                    <p class="color-cd get-email"><?= Yii::t('general', 'SUBSCRIBED OUR NEWSLETTER') ?></p>
                    <form action="">
                        <input type="text" placeholder="<?= Yii::t('general', 'Your Email') ?>" />
                        <button type="submit" class="btn-common"><?= Yii::t('general', 'Subscribe') ?></button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>