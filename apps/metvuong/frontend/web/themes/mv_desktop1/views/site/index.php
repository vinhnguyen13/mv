<?php
use yii\helpers\StringHelper;
use yii\helpers\Url;
use frontend\models\AdProductSearch;
use vsoft\ad\models\AdProduct;
use yii\web\View;

$this->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/string-helper.js', ['position'=>View::POS_HEAD]);
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
                    <li><a class="rippler rippler-default" href="<?= Url::to(['/ad/index1', 'city_id' => AdProductSearch::DEFAULT_CITY, 'district_id' => AdProductSearch::DEFAULT_DISTRICT]) ?>"><?=Yii::t('general', 'Buy')?></a></li>
                    <li><a class="rippler rippler-default" href="<?= Url::to(['/ad/index2', 'city_id' => AdProductSearch::DEFAULT_CITY, 'district_id' => AdProductSearch::DEFAULT_DISTRICT]) ?>"><?=Yii::t('general', 'Rent')?></a></li>
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
            $.ajax({
                type: "get",
                dataType: 'html',
                url: '<?=Url::to(['site/feature-listings'])?>',
                success: function (data) {
                    $( ".box-feature-item .wrap-item").html(data);
                    $('.box-feature-item').loading({done:true});
                }
            });

            $('.news-item').loading({full: false});
            $.ajax({
                type: "get",
                dataType: 'html',
                url: '<?=Url::to(['site/news'])?>',
                success: function (data) {
                    $( ".news-item .wrap-item").html(data);
                    $('.news-item').loading({done:true});
                }
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
                            <p><?=Yii::t('general','metvuong_address')?></p>
                        </li>
                        <li>
                            <span class="icon-mv"><span class="icon-phone-profile"></span></span>
                            <p><a href="tel:<?=Yii::t('general','metvuong_phone')?>"><?=Yii::t('general','metvuong_phone')?></a> - <a href="tel:<?=Yii::t('general','metvuong_mobile')?>"><?=Yii::t('general','metvuong_mobile')?></a></p>
                        </li>
                        <li>
                            <span class="icon-mv"><span class="icon-mail-profile"></span></span>
                            <p><?=Yii::t('general','metvuong_email')?></p>
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

    <section class="keyword_wrapper clearfix">
        <div class="container">
            <div class=" col-md-2  col-sm-4  col-xs-12">
                <div class="keyword_col">
                    <h2><a href="#" title="Phòng trọ, nhà trọ tại Hồ Chí Minh"><span>Phòng trọ, nhà trọ</span> Hồ Chí Minh</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận 1"><span>Phòng trọ, nhà trọ</span> Quận 1</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận 2"><span>Phòng trọ, nhà trọ</span> Quận 2</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận 3"><span>Phòng trọ, nhà trọ</span> Quận 3</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận 4"><span>Phòng trọ, nhà trọ</span> Quận 4</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận 5"><span>Phòng trọ, nhà trọ</span> Quận 5</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận 6"><span>Phòng trọ, nhà trọ</span> Quận 6</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận 7"><span>Phòng trọ, nhà trọ</span> Quận 7</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận 8"><span>Phòng trọ, nhà trọ</span> Quận 8</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận 9"><span>Phòng trọ, nhà trọ</span> Quận 9</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận 10"><span>Phòng trọ, nhà trọ</span> Quận 10</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận 11"><span>Phòng trọ, nhà trọ</span> Quận 11</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận 12"><span>Phòng trọ, nhà trọ</span> Quận 12</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Bình Tân"><span>Phòng trọ, nhà trọ</span> Quận Bình Tân</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Bình Thạnh"><span>Phòng trọ, nhà trọ</span> Quận Bình Thạnh</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Gò Vấp"><span>Phòng trọ, nhà trọ</span> Quận Gò Vấp</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Phú Nhuận"><span>Phòng trọ, nhà trọ</span> Quận Phú Nhuận</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Tân Bình"><span>Phòng trọ, nhà trọ</span> Quận Tân Bình</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Tân Phú"><span>Phòng trọ, nhà trọ</span> Quận Tân Phú</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Thủ Đức"><span>Phòng trọ, nhà trọ</span> Quận Thủ Đức</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Bình Chánh"><span>Phòng trọ, nhà trọ</span> Huyện Bình Chánh</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Cần Giờ"><span>Phòng trọ, nhà trọ</span> Huyện Cần Giờ</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Củ Chi"><span>Phòng trọ, nhà trọ</span> Huyện Củ Chi</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Hóc Môn"><span>Phòng trọ, nhà trọ</span> Huyện Hóc Môn</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Nhà Bè"><span>Phòng trọ, nhà trọ</span> Huyện Nhà Bè</a></h3></li>                
                    </ul>
                </div>
            </div>
            <div class=" col-md-2  col-sm-4  col-xs-12">
                <div class="keyword_col">
                    <h2><a href="#" title="Phòng trọ, nhà trọ tại Hà Nội"><span>Phòng trọ, nhà trọ</span> Hà Nội</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Hoàn Kiếm"><span>Phòng trọ, nhà trọ</span> Quận Hoàn Kiếm</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Ba Đình"><span>Phòng trọ, nhà trọ</span> Quận Ba Đình</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Đống Đa"><span>Phòng trọ, nhà trọ</span> Quận Đống Đa</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Hai Bà Trưng"><span>Phòng trọ, nhà trọ</span> Quận Hai Bà Trưng</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Thanh Xuân"><span>Phòng trọ, nhà trọ</span> Quận Thanh Xuân</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Tây Hồ"><span>Phòng trọ, nhà trọ</span> Quận Tây Hồ</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Cầu Giấy"><span>Phòng trọ, nhà trọ</span> Quận Cầu Giấy</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Hoàng Mai"><span>Phòng trọ, nhà trọ</span> Quận Hoàng Mai</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Long Biên"><span>Phòng trọ, nhà trọ</span> Quận Long Biên</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Đông Anh"><span>Phòng trọ, nhà trọ</span> Huyện Đông Anh</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Gia Lâm"><span>Phòng trọ, nhà trọ</span> Huyện Gia Lâm</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Sóc Sơn"><span>Phòng trọ, nhà trọ</span> Huyện Sóc Sơn</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Thanh Trì"><span>Phòng trọ, nhà trọ</span> Huyện Thanh Trì</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Từ Liêm"><span>Phòng trọ, nhà trọ</span> Huyện Từ Liêm</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Hà Đông"><span>Phòng trọ, nhà trọ</span> Quận Hà Đông</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Thị Xã Sơn Tây"><span>Phòng trọ, nhà trọ</span> Thị Xã Sơn Tây</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Mê Linh"><span>Phòng trọ, nhà trọ</span> Huyện Mê Linh</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Ba Vì"><span>Phòng trọ, nhà trọ</span> Huyện Ba Vì</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Phúc Thọ"><span>Phòng trọ, nhà trọ</span> Huyện Phúc Thọ</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Đan Phượng"><span>Phòng trọ, nhà trọ</span> Huyện Đan Phượng</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Hoài Đức"><span>Phòng trọ, nhà trọ</span> Huyện Hoài Đức</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Quốc Oai"><span>Phòng trọ, nhà trọ</span> Huyện Quốc Oai</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Thạch Thất"><span>Phòng trọ, nhà trọ</span> Huyện Thạch Thất</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Chương Mỹ"><span>Phòng trọ, nhà trọ</span> Huyện Chương Mỹ</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Thanh Oai"><span>Phòng trọ, nhà trọ</span> Huyện Thanh Oai</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Thường Tín"><span>Phòng trọ, nhà trọ</span> Huyện Thường Tín</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Phú Xuyên"><span>Phòng trọ, nhà trọ</span> Huyện Phú Xuyên</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Ứng Hòa"><span>Phòng trọ, nhà trọ</span> Huyện Ứng Hòa</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Mỹ Đức"><span>Phòng trọ, nhà trọ</span> Huyện Mỹ Đức</a></h3></li>
                    </ul>
                </div>
            </div>
            <div class=" col-md-2  col-sm-4  col-xs-12">
                <div class="keyword_col">
                    <h2><a href="#" title="Phòng trọ, nhà trọ tại Bình Dương"><span>Phòng trọ, nhà trọ</span> Bình Dương</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Thị Xã Thủ Dầu Một"><span>Phòng trọ, nhà trọ</span> Thị Xã Thủ Dầu Một</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Dầu Tiếng"><span>Phòng trọ, nhà trọ</span> Huyện Dầu Tiếng</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Bến Cát"><span>Phòng trọ, nhà trọ</span> Huyện Bến Cát</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Phú Giáo"><span>Phòng trọ, nhà trọ</span> Huyện Phú Giáo</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Tân Uyên"><span>Phòng trọ, nhà trọ</span> Huyện Tân Uyên</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Dĩ An"><span>Phòng trọ, nhà trọ</span> Huyện Dĩ An</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Thuận An"><span>Phòng trọ, nhà trọ</span> Huyện Thuận An</a></h3></li>    
                    </ul>
                </div>
                <div class="keyword_col">
                    <h2><a href="#" title="Phòng trọ, nhà trọ tại Hải Phòng"><span>Phòng trọ, nhà trọ</span> Hải Phòng</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Hồng Bàng"><span>Phòng trọ, nhà trọ</span> Quận Hồng Bàng</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Ngô Quyền"><span>Phòng trọ, nhà trọ</span> Quận Ngô Quyền</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Lê Chân"><span>Phòng trọ, nhà trọ</span> Quận Lê Chân</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Hải An"><span>Phòng trọ, nhà trọ</span> Quận Hải An</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Kiến An"><span>Phòng trọ, nhà trọ</span> Quận Kiến An</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Đồ Sơn"><span>Phòng trọ, nhà trọ</span> Quận Đồ Sơn</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Kinh Dương"><span>Phòng trọ, nhà trọ</span> Quận Kinh Dương</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Thuỷ Nguyên"><span>Phòng trọ, nhà trọ</span> Huyện Thuỷ Nguyên</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện An Dương"><span>Phòng trọ, nhà trọ</span> Huyện An Dương</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện An Lão"><span>Phòng trọ, nhà trọ</span> Huyện An Lão</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Kiến Thụy"><span>Phòng trọ, nhà trọ</span> Huyện Kiến Thụy</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Tiên Lãng"><span>Phòng trọ, nhà trọ</span> Huyện Tiên Lãng</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Vĩnh Bảo"><span>Phòng trọ, nhà trọ</span> Huyện Vĩnh Bảo</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Cát Hải"><span>Phòng trọ, nhà trọ</span> Huyện Cát Hải</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Bạch Long Vĩ"><span>Phòng trọ, nhà trọ</span> Huyện Bạch Long Vĩ</a></h3></li>
                    </ul>
                </div>
            </div>
            <div class=" col-md-2  col-sm-4  col-xs-12">
                <div class="keyword_col">
                    <h2><a href="#" title="Phòng trọ, nhà trọ tại Đà Nẵng"><span>Phòng trọ, nhà trọ</span> Đà Nẵng</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Liên Chiểu"><span>Phòng trọ, nhà trọ</span> Quận Liên Chiểu</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Thanh Khê"><span>Phòng trọ, nhà trọ</span> Quận Thanh Khê</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Hải Châu"><span>Phòng trọ, nhà trọ</span> Quận Hải Châu</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Sơn Trà"><span>Phòng trọ, nhà trọ</span> Quận Sơn Trà</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Ngũ Hành Sơn"><span>Phòng trọ, nhà trọ</span> Quận Ngũ Hành Sơn</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Cẩm Lệ"><span>Phòng trọ, nhà trọ</span> Quận Cẩm Lệ</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Hoà Vang"><span>Phòng trọ, nhà trọ</span> Huyện Hoà Vang</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Hoàng Sa"><span>Phòng trọ, nhà trọ</span> Huyện Hoàng Sa</a></h3></li>
                    </ul>
                </div>
                <div class="keyword_col">
                    <h2><a href="#" title="Phòng trọ, nhà trọ tại Cần Thơ"><span>Phòng trọ, nhà trọ</span> Cần Thơ</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Ninh Kiều"><span>Phòng trọ, nhà trọ</span> Quận Ninh Kiều</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Ô Môn"><span>Phòng trọ, nhà trọ</span> Quận Ô Môn</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Bình Thuỷ"><span>Phòng trọ, nhà trọ</span> Quận Bình Thuỷ</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Cái Răng"><span>Phòng trọ, nhà trọ</span> Quận Cái Răng</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Quận Thốt Nốt"><span>Phòng trọ, nhà trọ</span> Quận Thốt Nốt</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Vĩnh Thạnh"><span>Phòng trọ, nhà trọ</span> Huyện Vĩnh Thạnh</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Cờ Đỏ"><span>Phòng trọ, nhà trọ</span> Huyện Cờ Đỏ</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Phong Điền"><span>Phòng trọ, nhà trọ</span> Huyện Phong Điền</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Thới Lai"><span>Phòng trọ, nhà trọ</span> Huyện Thới Lai</a></h3></li>
                    </ul>
                </div>
            </div>
            <div class=" col-md-2  col-sm-4  col-xs-12">
                <div class="keyword_col">
                    <h2><a href="#" title="Phòng trọ, nhà trọ tại Long An"><span>Phòng trọ, nhà trọ</span> Long An</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Thành Phố Tân An"><span>Phòng trọ, nhà trọ</span> Thành Phố Tân An</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Tân Hưng"><span>Phòng trọ, nhà trọ</span> Huyện Tân Hưng</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Vĩnh Hưng"><span>Phòng trọ, nhà trọ</span> Huyện Vĩnh Hưng</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Mộc Hóa"><span>Phòng trọ, nhà trọ</span> Huyện Mộc Hóa</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Tân Thạnh"><span>Phòng trọ, nhà trọ</span> Huyện Tân Thạnh</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Đức Huệ"><span>Phòng trọ, nhà trọ</span> Huyện Đức Huệ</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Đức Hòa"><span>Phòng trọ, nhà trọ</span> Huyện Đức Hòa</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Bến Lức"><span>Phòng trọ, nhà trọ</span> Huyện Bến Lức</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Thủ Thừa"><span>Phòng trọ, nhà trọ</span> Huyện Thủ Thừa</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Tân Trụ"><span>Phòng trọ, nhà trọ</span> Huyện Tân Trụ</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Cần Đước"><span>Phòng trọ, nhà trọ</span> Huyện Cần Đước</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Cần Giuộc"><span>Phòng trọ, nhà trọ</span> Huyện Cần Giuộc</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Châu Thành Long An"><span>Phòng trọ, nhà trọ</span> Huyện Châu Thành</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Tân Thạnh"><span>Phòng trọ, nhà trọ</span> Huyện Tân Thạnh</a></h3></li>
                    </ul>
                </div>
                <div class="keyword_col">
                    <h2><a href="#" title="Phòng trọ, nhà trọ tại Bà Rịa Vũng Tàu"><span>Phòng trọ, nhà trọ</span> Bà Rịa Vũng Tàu</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Thành Phố Vũng Tầu"><span>Phòng trọ, nhà trọ</span> Thành Phố Vũng Tầu</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Thị Xã Bà Rịa"><span>Phòng trọ, nhà trọ</span> Thị Xã Bà Rịa</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Châu Đức"><span>Phòng trọ, nhà trọ</span> Huyện Châu Đức</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Xuyên Mộc"><span>Phòng trọ, nhà trọ</span> Huyện Xuyên Mộc</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Long Điền"><span>Phòng trọ, nhà trọ</span> Huyện Long Điền</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Đất Đỏ"><span>Phòng trọ, nhà trọ</span> Huyện Đất Đỏ</a></h3></li>    
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Tân Thành"><span>Phòng trọ, nhà trọ</span> Huyện Tân Thành</a></h3></li>      
                        <li class="item"><h3><a class="link" href="#" title="Phòng trọ, nhà trọ tại Huyện Côn Đảo"><span>Phòng trọ, nhà trọ</span> Huyện Côn Đảo</a></h3></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>