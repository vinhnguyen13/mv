<?php
use yii\helpers\StringHelper;
use yii\helpers\Url;
use frontend\models\Ad;
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
                            <ul></ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <span class="arrow-down"></span>
    </section>
    <?php
    $categories = \vsoft\ad\models\AdCategory::find ()->indexBy ( 'id' )->asArray ( true )->all ();
    $types = \vsoft\ad\models\AdProduct::getAdTypes ();
    $products = Ad::find()->homePageRandom();
    ?>
    <section class="box-item box-feature-item">
        <div class="title-sub"><?= Yii::t('listing', 'Featured properties') ?></div>
        <div class="wrap-item wrap-lazy">
            <ul class="clearfix">
                <?php foreach ($products as $product): ?>
                <li>
                    <div class="item">
                        <a href="<?= $product->urlDetail(true) ?>" class="pic-intro rippler rippler-default">
                            <div class="img-show">
                                <div><img src="<?= $product->representImage ?>" data-original=""></div>
                            </div>
                            <div class="title-item"><?= ucfirst($categories[$product->category_id]['name']) ?> <?= $types[$product->type] ?></div>
                        </a>
                        <div class="info-item">
                            <div class="address-feat clearfix">
                                <p class="date-post"><?= Yii::t('statistic', 'Date of posting') ?>: <strong><?= date("d/m/Y", $product->created_at) ?></strong></p>
                                <div class="address-listing">
                                    <a title="<?= $product->getAddress(true) ?>" href="<?= $product->urlDetail(true) ?>"><?= $product->getAddress(true) ?></a>
                                </div>
                                <p class="id-duan">ID:<span><?=$product->id;?></span></p>
                                <ul class="clearfix list-attr-td">
                                    <?php if(empty($product->area) && empty($product->adProductAdditionInfo->room_no) && empty($product->adProductAdditionInfo->toilet_no)){ ?>
                                        <li><span><?=Yii::t('listing','updating')?></span></li>
                                    <?php } else {
                                        echo $product->area ? '<li> <span class="icon-mv"><span class="icon-page-1-copy"></span></span>' . $product->area . 'm2 </li>' : '';
                                        echo $product->adProductAdditionInfo->room_no ? '<li> <span class="icon-mv"><span class="icon-bed-search"></span></span>' . $product->adProductAdditionInfo->room_no . ' </li>' : '';
                                        echo $product->adProductAdditionInfo->toilet_no ? '<li> <span class="icon-mv"><span class="icon-bathroom-search-copy-2"></span></span> ' . $product->adProductAdditionInfo->toilet_no . ' </li>' : '';
                                    } ?>
                                </ul>
                            </div>
                            <div class="bottom-feat-box clearfix">
                                <a href="<?= $product->urlDetail(true) ?>" class="pull-right color-cd-hover">Chi tiết</a>
                                <p><?=Yii::t('listing','Price')?> <strong><?= vsoft\express\components\StringHelper::formatCurrency($product->price) ?> vnd</strong></p>
                            </div>
                        </div>
                    </div>
                </li>
                <?php
                endforeach;
                ?>
            </ul>
        </div>
    </section>

    <?php if(count($news) > 0) {?>
    <section class="box-item news-item">
        <div class="title-sub"><?=Yii::t('news','NEWS')?></div>
        <div class="wrap-item">
            <?php
                foreach($news as $n){
            ?>
            <div class="item clearfix">
                <a class="rippler rippler-default" href="<?= \yii\helpers\Url::to(['news/view', 'id' => $n['id'], 'slug' => $n['slug']], true) ?>">
                    <div class="img-show"><div><img src="<?=Url::to('/store/news/show/' . $n['banner']) ?>"></div></div>
                    <span class="txt-short-news">
                        <span class="title-news color-30a868"><?=$n['title']?></span>
                        <span class="date-news"><?=date('d/m/Y, H:i', $n['created_at'])?></span>
                        <?=StringHelper::truncate($n['brief'], 130)?>
                    </span>
                </a>
            </div>
            <?php } ?>
        </div>
    </section>
    <?php } ?>

    <section class="search-home">
        <div class="title-sub">LIÊN HỆ</div>
        <div class="clearfix">
            <div class="wrap-img map-location">
                <div class="img-show"><iframe width="100%" height="100%" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=place_id:EksyMSBOZ3V54buFbiBUcnVuZyBOZ-G6oW4sIELhur9uIE5naMOpLCBRdeG6rW4gMSwgSOG7kyBDaMOtIE1pbmgsIFZp4buHdCBOYW0&key=AIzaSyDgukAnWQNq0fitebULUbottG5gvK64OCQ" allowfullscreen></iframe></div>
            </div>
            <div class="txt-location">
                <p class="name-cty color-cd">METVUONG TEAM</p>
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
    </section>
</div>