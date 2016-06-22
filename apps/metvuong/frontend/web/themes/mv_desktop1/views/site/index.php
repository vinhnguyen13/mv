<?php
use yii\helpers\StringHelper;
use yii\helpers\Url;
use frontend\models\AdProductSearch;
use vsoft\ad\models\AdProduct;
use yii\web\View;

$this->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/string-helper.js', ['position'=>View::POS_HEAD]);
$request = Yii::$app->request;
$cookie = $request->cookies['cohomepage'];
if(empty($cookie)){
    Yii::$app->response->cookies->add(new \yii\web\Cookie([
        'name' => 'cohomepage',
        'value' => true,
        'expire' => time() + (10 * 365 * 24 * 60 * 60)
    ]));
?>
<script>
    var txtTour = ["At the top you will find the HomeBar, this bar will always be visible to you, and will let you quickly navigate to all of Metvuong's key Features.","<p class='mgB-10'>The main feature of the Metvuong homepage is our Search Bar. It will let you quickly select how you want to search for your property, whether it be through it's location (city, district, ward and street) or by which Development it belongs to.</p><p>If you know the MVID of your listing, you can also use this as a shortcut to take you to the listing that you want.</p>"];
</script>
<?php
    $this->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/tour-intro.min.js', ['position'=>View::POS_END]);
}
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
                    <li><a class="rippler rippler-default" href="<?= Url::to(['/ad/index1']) ?>"><?=Yii::t('general', 'Buy')?></a></li>
                    <li><a class="rippler rippler-default" href="<?= Url::to(['/ad/index2']) ?>"><?=Yii::t('general', 'Rent')?></a></li>
                    <li><a class="rippler rippler-default" href="<?= Url::to(['/ad/post']) ?>"><?=Yii::t('general', 'Sell')?></a></li>
                </ul>
                <form id="search-form" action="<?= Url::to(['/map/search']) ?>">
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

    <section class="box-item box-feature-item bg-f5f5f5">
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

    <section class="box-item pdT-50 keyword_wrapper clearfix mgB-0 bg-f5f5f5">
        <div class="container">
            <div class=" col-md-2  col-sm-4  col-xs-12">
                <div class="keyword_col">
                    <h2><a href="#" title="Nhà đất tại Hồ Chí Minh">Nhà Đất Hồ Chí Minh</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>10])?>" title="Nhà đất tại Quận 1">Quận 1</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>14])?>" title="Nhà đất tại Quận 2">Quận 2</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>15])?>" title="Nhà đất tại Quận 3">Quận 3</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>16])?>" title="Nhà đất tại Quận 4">Quận 4</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>17])?>" title="Nhà đất tại Quận 5">Quận 5</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>18])?>" title="Nhà đất tại Quận 6">Quận 6</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>19])?>" title="Nhà đất tại Quận 7">Quận 7</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>20])?>" title="Nhà đất tại Quận 8">Quận 8</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>21])?>" title="Nhà đất tại Quận 9">Quận 9</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>11])?>" title="Nhà đất tại Quận 10">Quận 10</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>12])?>" title="Nhà đất tại Quận 11">Quận 11</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>13])?>" title="Nhà đất tại Quận 12">Quận 12</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>2])?>" title="Nhà đất tại Quận Bình Tân">Quận Bình Tân</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>3])?>" title="Nhà đất tại Quận Bình Thạnh">Quận Bình Thạnh</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>6])?>" title="Nhà đất tại Quận Gò Vấp">Quận Gò Vấp</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>9])?>" title="Nhà đất tại Quận Phú Nhuận">Quận Phú Nhuận</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>22])?>" title="Nhà đất tại Quận Tân Bình">Quận Tân Bình</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>23])?>" title="Nhà đất tại Quận Tân Phú">Quận Tân Phú</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>24])?>" title="Nhà đất tại Quận Thủ Đức">Quận Thủ Đức</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>1])?>" title="Nhà đất tại Huyện Bình Chánh">Huyện Bình Chánh</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>4])?>" title="Nhà đất tại Huyện Cần Giờ">Huyện Cần Giờ</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>5])?>" title="Nhà đất tại Huyện Củ Chi">Huyện Củ Chi</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>7])?>" title="Nhà đất tại Huyện Hóc Môn">Huyện Hóc Môn</a></h3></li>
                        <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>8])?>" title="Nhà đất tại Huyện Nhà Bè">Huyện Nhà Bè</a></h3></li>
                    </ul>
                </div>
            </div>
            <div class=" col-md-2  col-sm-4  col-xs-12 disable">
                <div class="keyword_col">
                    <h2><a href="#" title="Nhà đất tại Hà Nội">Nhà Đất Hà Nội</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Hoàn Kiếm">Quận Hoàn Kiếm</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Ba Đình">Quận Ba Đình</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Đống Đa">Quận Đống Đa</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Hai Bà Trưng">Quận Hai Bà Trưng</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Thanh Xuân">Quận Thanh Xuân</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Tây Hồ">Quận Tây Hồ</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Cầu Giấy">Quận Cầu Giấy</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Hoàng Mai">Quận Hoàng Mai</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Long Biên">Quận Long Biên</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Đông Anh">Huyện Đông Anh</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Gia Lâm">Huyện Gia Lâm</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Sóc Sơn">Huyện Sóc Sơn</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Thanh Trì">Huyện Thanh Trì</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Từ Liêm">Huyện Từ Liêm</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Hà Đông">Quận Hà Đông</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Thị Xã Sơn Tây">Thị Xã Sơn Tây</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Mê Linh">Huyện Mê Linh</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Ba Vì">Huyện Ba Vì</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Phúc Thọ">Huyện Phúc Thọ</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Đan Phượng">Huyện Đan Phượng</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Hoài Đức">Huyện Hoài Đức</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Quốc Oai">Huyện Quốc Oai</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Thạch Thất">Huyện Thạch Thất</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Chương Mỹ">Huyện Chương Mỹ</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Thanh Oai">Huyện Thanh Oai</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Thường Tín">Huyện Thường Tín</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Phú Xuyên">Huyện Phú Xuyên</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Ứng Hòa">Huyện Ứng Hòa</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Mỹ Đức">Huyện Mỹ Đức</a></h3></li>
                    </ul>
                </div>
            </div>
            <div class=" col-md-2  col-sm-4  col-xs-12 disable">
                <div class="keyword_col">
                    <h2><a href="#" title="Nhà đất tại Bình Dương">Nhà Đất Bình Dương</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Thị Xã Thủ Dầu Một">Thị Xã Thủ Dầu Một</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Dầu Tiếng">Huyện Dầu Tiếng</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Bến Cát">Huyện Bến Cát</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Phú Giáo">Huyện Phú Giáo</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Tân Uyên">Huyện Tân Uyên</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Dĩ An">Huyện Dĩ An</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Thuận An">Huyện Thuận An</a></h3></li>    
                    </ul>
                </div>
                <div class="keyword_col">
                    <h2><a href="#" title="Nhà đất tại Hải Phòng">Nhà Đất Hải Phòng</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Hồng Bàng">Quận Hồng Bàng</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Ngô Quyền">Quận Ngô Quyền</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Lê Chân">Quận Lê Chân</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Hải An">Quận Hải An</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Kiến An">Quận Kiến An</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Đồ Sơn">Quận Đồ Sơn</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Kinh Dương">Quận Kinh Dương</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Thuỷ Nguyên">Huyện Thuỷ Nguyên</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện An Dương">Huyện An Dương</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện An Lão">Huyện An Lão</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Kiến Thụy">Huyện Kiến Thụy</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Tiên Lãng">Huyện Tiên Lãng</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Vĩnh Bảo">Huyện Vĩnh Bảo</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Cát Hải">Huyện Cát Hải</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Bạch Long Vĩ">Huyện Bạch Long Vĩ</a></h3></li>
                    </ul>
                </div>
            </div>
            <div class=" col-md-2  col-sm-4  col-xs-12 disable">
                <div class="keyword_col">
                    <h2><a href="#" title="Nhà đất tại Đà Nẵng">Nhà Đất Đà Nẵng</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Liên Chiểu">Quận Liên Chiểu</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Thanh Khê">Quận Thanh Khê</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Hải Châu">Quận Hải Châu</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Sơn Trà">Quận Sơn Trà</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Ngũ Hành Sơn">Quận Ngũ Hành Sơn</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Cẩm Lệ">Quận Cẩm Lệ</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Hoà Vang">Huyện Hoà Vang</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Hoàng Sa">Huyện Hoàng Sa</a></h3></li>
                    </ul>
                </div>
                <div class="keyword_col">
                    <h2><a href="#" title="Nhà đất tại Cần Thơ">Nhà Đất Cần Thơ</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Ninh Kiều">Quận Ninh Kiều</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Ô Môn">Quận Ô Môn</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Bình Thuỷ">Quận Bình Thuỷ</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Cái Răng">Quận Cái Răng</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Quận Thốt Nốt">Quận Thốt Nốt</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Vĩnh Thạnh">Huyện Vĩnh Thạnh</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Cờ Đỏ">Huyện Cờ Đỏ</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Phong Điền">Huyện Phong Điền</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Thới Lai">Huyện Thới Lai</a></h3></li>
                    </ul>
                </div>
            </div>
            <div class=" col-md-2  col-sm-4  col-xs-12 disable">
                <div class="keyword_col">
                    <h2><a href="#" title="Nhà đất tại Long An">Nhà Đất Long An</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Thành Phố Tân An">Thành Phố Tân An</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Tân Hưng">Huyện Tân Hưng</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Vĩnh Hưng">Huyện Vĩnh Hưng</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Mộc Hóa">Huyện Mộc Hóa</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Tân Thạnh">Huyện Tân Thạnh</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Đức Huệ">Huyện Đức Huệ</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Đức Hòa">Huyện Đức Hòa</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Bến Lức">Huyện Bến Lức</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Thủ Thừa">Huyện Thủ Thừa</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Tân Trụ">Huyện Tân Trụ</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Cần Đước">Huyện Cần Đước</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Cần Giuộc">Huyện Cần Giuộc</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Châu Thành Long An">Huyện Châu Thành</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Tân Thạnh">Huyện Tân Thạnh</a></h3></li>
                    </ul>
                </div>
                <div class="keyword_col">
                    <h2><a href="#" title="Nhà đất tại Bà Rịa Vũng Tàu">Nhà Đất Bà Rịa Vũng Tàu</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Thành Phố Vũng Tầu">Thành Phố Vũng Tầu</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Thị Xã Bà Rịa">Thị Xã Bà Rịa</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Châu Đức">Huyện Châu Đức</a></h3></li>                
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Xuyên Mộc">Huyện Xuyên Mộc</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Long Điền">Huyện Long Điền</a></h3></li>              
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Đất Đỏ">Huyện Đất Đỏ</a></h3></li>    
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Tân Thành">Huyện Tân Thành</a></h3></li>      
                        <li class="item"><h3><a class="link" href="#" title="Nhà đất tại Huyện Côn Đảo">Huyện Côn Đảo</a></h3></li>
                    </ul>
                </div>
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
            <!-- <div class="title-sub"><?= Yii::t('general', 'LIÊN HỆ') ?></div> -->
            <div class="clearfix">
                <div class="box-about">
                    <div class="color-cd font-600 fs-17 mgB-10">Về MetVuong</div>
                    <p class="fs-14 lh-24 text-justify">Metvuong.com là phương bất động sản. Tìm kiếm hay mua và bán Bất động sản sẽ dễ dàng hơn bao giờ hết với <a href="/" class="text-decor color-cd">metvuong.com</a>. Với hệ thống thông tin bát nháo hiện nay, ta dễ dàng thất lạc trong hàng đống dữ liệu. Nhưng với <a href="/" class="text-decor color-cd">metvuong.com</a>, mọi rắc rối sẽ không còn nữa. Việc tìm, thuê hay mua bán một căn nhà hay một căn hộ chưa bao giờ dễ dàng như thế. Chuẩn xác và đơn giản, bất động sản chẳng còn là một vấn đề nan giải. </p>
                </div>
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
</div>