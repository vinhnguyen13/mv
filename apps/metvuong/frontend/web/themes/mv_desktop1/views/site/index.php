<?php
use vsoft\ad\models\AdDistrict;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use frontend\models\AdProductSearch;
use vsoft\ad\models\AdProduct;
use yii\web\View;
use vsoft\ad\models\TrackingSearch;

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
                <img src="/images/logo/<?=Yii::$app->language?>/logo-beta.png" alt="">
            </div>
            <div class="search-homepage">
                <ul class="clearfix">
                    <li><a class="rippler rippler-default" href="<?= Url::to(['/ad/index1', 'params' => 'ho-chi-minh-quan-1']) ?>"><?=Yii::t('general', 'Buy')?></a></li>
                    <li><a class="rippler rippler-default" href="<?= Url::to(['/ad/index2', 'params' => 'ho-chi-minh-quan-1']) ?>"><?=Yii::t('general', 'Rent')?></a></li>
                    <li><a class="rippler rippler-default" href="<?= Url::to(['/ad/post']) ?>"><?=Yii::t('general', 'Sell')?></a></li>
                </ul>
                <form id="search-form" action="<?= Url::to(['/map/search']) ?>">
                    <input autocomplete="off" id="search" name="v" type="text" placeholder="<?=Yii::t('general', 'Quick Search')?>" />
                    <button type="submit" id="btn-search"><span class="icon-mv"><span class="icon-icons-search"></span></span></button>
                    <div class="suggest-search hide">
                        <div class="content-suggest">
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

    <section class="search-home bg-f5f5f5">
        <div class="container">
            <!-- <div class="title-sub"><?= Yii::t('general', 'LIÊN HỆ') ?></div> -->
            <div class="clearfix">
                <div class="box-about">
                    <p class="color-cd text-uper mgB-10 fs-20">Về metvuong</p>
                    <p class="fs-14 lh-24 text-justify"><?=Yii::t('general',"Metvuong.com là phương bất động sản. Tìm kiếm hay mua và bán Bất động sản sẽ dễ dàng hơn bao giờ hết với <a href='/' class='text-decor color-cd'>metvuong.com</a>. Với hệ thống thông tin bát nháo hiện nay, ta dễ dàng thất lạc trong hàng đống dữ liệu. Nhưng với <a href='/' class='text-decor color-cd'>metvuong.com</a>, mọi rắc rối sẽ không còn nữa. Việc tìm, thuê hay mua bán một căn nhà hay một căn hộ chưa bao giờ dễ dàng như thế. Chuẩn xác và đơn giản, bất động sản chẳng còn là một vấn đề nan giải.")?></p>
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

    <section class="box-item pdT-50 keyword_wrapper clearfix mgB-0">
        <div class="container">
            <div class=" col-md-2  col-sm-4  col-xs-12">
                <div class="keyword_col">
                    <h2><a href="<?=Url::to(['ad/index1', 'params'=>'ho-chi-minh'])?>" title="Nhà đất tại Hồ Chí Minh">Nhà Đất Hồ Chí Minh</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <?php
                        $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListSlugByCity(1); });
                        if(count($districts) > 0){
                            foreach($districts as $district) { ?>
                                <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'params'=>$district['slug']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                            <?php }
                        } ?>
                    </ul>
                </div>
            </div>
            <div class=" col-md-2  col-sm-4  col-xs-12">
                <div class="keyword_col">
                    <h2><a href="<?=Url::to(['ad/index1', 'params'=>'ha-noi'])?>" title="Nhà đất tại Hà Nội">Nhà Đất Hà Nội</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <?php
                        $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListSlugByCity(2); });
                        if(count($districts) > 0){
                            foreach($districts as $district) { ?>
                                <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'params'=>$district['slug']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                            <?php }
                        } ?>
                    </ul>
                </div>
            </div>
            <div class=" col-md-2  col-sm-4  col-xs-12">
                <div class="keyword_col">
                    <h2><a href="<?=Url::to(['ad/index1', 'params'=>'binh-duong'])?>" title="Nhà đất tại Bình Dương">Nhà Đất Bình Dương</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <?php
                        $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListSlugByCity(3); });
                        if(count($districts) > 0){
                            foreach($districts as $district) { ?>
                                <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'params'=>$district['slug']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                            <?php }
                        } ?>
                    </ul>
                </div>
                <div class="keyword_col">
                    <h2><a href="<?=Url::to(['ad/index1', 'params'=>'hai-phong'])?>" title="Nhà đất tại Hải Phòng">Nhà Đất Hải Phòng</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <?php
                        $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListSlugByCity(5); });
                        if(count($districts) > 0){
                            foreach($districts as $district) { ?>
                                <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'params'=>$district['slug']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                            <?php }
                        } ?>
                    </ul>
                </div>
            </div>
            <div class=" col-md-2  col-sm-4  col-xs-12">
                <div class="keyword_col">
                    <h2><a href="<?=Url::to(['ad/index1', 'params'=>'da-nang'])?>" title="Nhà đất tại Đà Nẵng">Nhà Đất Đà Nẵng</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <?php
                        $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListSlugByCity(4); });
                        if(count($districts) > 0){
                            foreach($districts as $district) { ?>
                                <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'params'=>$district['slug']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                            <?php }
                        } ?>
                    </ul>
                </div>
                <div class="keyword_col">
                    <h2><a href="<?=Url::to(['ad/index1', 'params'=>'can-tho'])?>" title="Nhà đất tại Cần Thơ">Nhà Đất Cần Thơ</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <?php
                        $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListSlugByCity(18); });
                        if(count($districts) > 0){
                            foreach($districts as $district) { ?>
                                <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'params'=>$district['slug']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                            <?php }
                        } ?>
                    </ul>
                </div>
            </div>
            <div class=" col-md-2  col-sm-4  col-xs-12">
                <div class="keyword_col">
                    <h2><a href="<?=Url::to(['ad/index1', 'params'=>'long-an'])?>" title="Nhà đất tại Long An">Nhà Đất Long An</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <?php
                        $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListSlugByCity(6); });
                        if(count($districts) > 0){
                            foreach($districts as $district) { ?>
                                <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'params'=>$district['slug']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                            <?php }
                        } ?>
                    </ul>
                </div>
                <div class="keyword_col">
                    <h2><a href="<?=Url::to(['ad/index1', 'params'=>'ba-ria-vung-tau'])?>" title="Nhà đất tại Bà Rịa Vũng Tàu">Nhà Đất Bà Rịa Vũng Tàu</a></h2>
                    <ul class="keyword_child_location clearfix">
                        <?php
                        $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListSlugByCity(7); });
                        if(count($districts) > 0){
                            foreach($districts as $district) { ?>
                                <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'params'=>$district['slug']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                            <?php }
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>