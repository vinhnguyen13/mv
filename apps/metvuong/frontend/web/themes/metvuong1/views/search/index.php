<?php
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = Yii::t('express','We offer exeptional amenities and renowned white - glove services');
?>
<div class="list-filters-result">
    <ul class="container clearfix">
        <li>
            <a href="#">Giá</a>
        </li>
        <li>
            <a href="#">Diện tích</a>
        </li>
        <li>
            <a href="#">Phòng ngủ</a>
        </li>
        <li>
            <a href="#">Loại BDS</a>
        </li>
        <li>
            <a href="#">Khác</a>
        </li>
    </ul>
</div>
<div class="col-md-8 wrap-map-result">
    <div class="container-map">
        <iframe width="600" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJ0T2NLikpdTERKxE8d61aX_E&key=AIzaSyDgukAnWQNq0fitebULUbottG5gvK64OCQ" allowfullscreen></iframe>
    </div>
</div>
<div class="col-md-4 result-items">
    <div class="wrap-col-fixed-result clearfix">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#moi-nhat" aria-controls="moi-nhat" role="tab" data-toggle="tab">Mới nhất</a></li>
            <li role="presentation"><a href="#re-nhat" aria-controls="re-nhat" role="tab" data-toggle="tab">Rẻ nhất</a></li>
            <li role="presentation"><a href="#phong-ngu" aria-controls="phong-ngu" role="tab" data-toggle="tab">Phòng ngủ</a></li>
            <li role="presentation"><a href="#khac" aria-controls="khac" role="tab" data-toggle="tab">Khác</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="moi-nhat">
                <ul class="list-results clearfix">
                    <li>
                        <a href="#" class="wrap-img pull-left"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/IS5em8q8mi2p8p0000000000.jpg" alt=""></a>
                        <div class="infor-result">
                            <p class="item-title">620 1/2 Locust Ave # B, Clarksburg, WV</p>
                            <p class="type-result"><em class="fa fa-circle for-rent"></em>APARTMENT FOR RENT</p>
                            <p class="rice-result">$750/mo</p>
                            <p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>
                            <p class="date-post-rent"><span class="toz-count">7&nbsp;</span>ngày trước</p>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="wrap-img pull-left"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/ISxzl4dulivj820000000000.jpg" alt=""></a>
                        <div class="infor-result">
                            <p class="item-title">620 1/2 Locust Ave # B, Clarksburg, WV</p>
                            <p class="type-result"><em class="fa fa-circle for-sale"></em>APARTMENT FOR RENT</p>
                            <p class="rice-result">$750/mo</p>
                            <p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>
                            <p class="date-post-rent"><span class="toz-count">7&nbsp;</span>ngày trước</p>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="wrap-img pull-left"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/IS5em8q8mi2p8p0000000000.jpg" alt=""></a>
                        <div class="infor-result">
                            <p class="item-title">620 1/2 Locust Ave # B, Clarksburg, WV</p>
                            <p class="type-result"><em class="fa fa-circle for-rent"></em>APARTMENT FOR RENT</p>
                            <p class="rice-result">$750/mo</p>
                            <p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>
                            <p class="date-post-rent"><span class="toz-count">7&nbsp;</span>ngày trước</p>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="wrap-img pull-left"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/ISxzl4dulivj820000000000.jpg" alt=""></a>
                        <div class="infor-result">
                            <p class="item-title">620 1/2 Locust Ave # B, Clarksburg, WV</p>
                            <p class="type-result"><em class="fa fa-circle for-sale"></em>APARTMENT FOR RENT</p>
                            <p class="rice-result">$750/mo</p>
                            <p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>
                            <p class="date-post-rent"><span class="toz-count">7&nbsp;</span>ngày trước</p>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="wrap-img pull-left"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/IS5em8q8mi2p8p0000000000.jpg" alt=""></a>
                        <div class="infor-result">
                            <p class="item-title">620 1/2 Locust Ave # B, Clarksburg, WV</p>
                            <p class="type-result"><em class="fa fa-circle for-rent"></em>APARTMENT FOR RENT</p>
                            <p class="rice-result">$750/mo</p>
                            <p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>
                            <p class="date-post-rent"><span class="toz-count">7&nbsp;</span>ngày trước</p>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="wrap-img pull-left"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/ISxzl4dulivj820000000000.jpg" alt=""></a>
                        <div class="infor-result">
                            <p class="item-title">620 1/2 Locust Ave # B, Clarksburg, WV</p>
                            <p class="type-result"><em class="fa fa-circle for-sale"></em>APARTMENT FOR RENT</p>
                            <p class="rice-result">$750/mo</p>
                            <p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>
                            <p class="date-post-rent"><span class="toz-count">7&nbsp;</span>ngày trước</p>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="wrap-img pull-left"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/IS5em8q8mi2p8p0000000000.jpg" alt=""></a>
                        <div class="infor-result">
                            <p class="item-title">620 1/2 Locust Ave # B, Clarksburg, WV</p>
                            <p class="type-result"><em class="fa fa-circle for-rent"></em>APARTMENT FOR RENT</p>
                            <p class="rice-result">$750/mo</p>
                            <p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>
                            <p class="date-post-rent"><span class="toz-count">7&nbsp;</span>ngày trước</p>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="wrap-img pull-left"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/ISxzl4dulivj820000000000.jpg" alt=""></a>
                        <div class="infor-result">
                            <p class="item-title">620 1/2 Locust Ave # B, Clarksburg, WV</p>
                            <p class="type-result"><em class="fa fa-circle for-sale"></em>APARTMENT FOR RENT</p>
                            <p class="rice-result">$750/mo</p>
                            <p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>
                            <p class="date-post-rent"><span class="toz-count">7&nbsp;</span>ngày trước</p>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="wrap-img pull-left"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/IS5em8q8mi2p8p0000000000.jpg" alt=""></a>
                        <div class="infor-result">
                            <p class="item-title">620 1/2 Locust Ave # B, Clarksburg, WV</p>
                            <p class="type-result"><em class="fa fa-circle for-rent"></em>APARTMENT FOR RENT</p>
                            <p class="rice-result">$750/mo</p>
                            <p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>
                            <p class="date-post-rent"><span class="toz-count">7&nbsp;</span>ngày trước</p>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="wrap-img pull-left"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/ISxzl4dulivj820000000000.jpg" alt=""></a>
                        <div class="infor-result">
                            <p class="item-title">620 1/2 Locust Ave # B, Clarksburg, WV</p>
                            <p class="type-result"><em class="fa fa-circle for-sale"></em>APARTMENT FOR RENT</p>
                            <p class="rice-result">$750/mo</p>
                            <p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>
                            <p class="date-post-rent"><span class="toz-count">7&nbsp;</span>ngày trước</p>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="wrap-img pull-left"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/IS5em8q8mi2p8p0000000000.jpg" alt=""></a>
                        <div class="infor-result">
                            <p class="item-title">620 1/2 Locust Ave # B, Clarksburg, WV</p>
                            <p class="type-result"><em class="fa fa-circle for-rent"></em>APARTMENT FOR RENT</p>
                            <p class="rice-result">$750/mo</p>
                            <p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>
                            <p class="date-post-rent"><span class="toz-count">7&nbsp;</span>ngày trước</p>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="wrap-img pull-left"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/ISxzl4dulivj820000000000.jpg" alt=""></a>
                        <div class="infor-result">
                            <p class="item-title">620 1/2 Locust Ave # B, Clarksburg, WV</p>
                            <p class="type-result"><em class="fa fa-circle for-sale"></em>APARTMENT FOR RENT</p>
                            <p class="rice-result">$750/mo</p>
                            <p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>
                            <p class="date-post-rent"><span class="toz-count">7&nbsp;</span>ngày trước</p>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="wrap-img pull-left"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/IS5em8q8mi2p8p0000000000.jpg" alt=""></a>
                        <div class="infor-result">
                            <p class="item-title">620 1/2 Locust Ave # B, Clarksburg, WV</p>
                            <p class="type-result"><em class="fa fa-circle for-rent"></em>APARTMENT FOR RENT</p>
                            <p class="rice-result">$750/mo</p>
                            <p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>
                            <p class="date-post-rent"><span class="toz-count">7&nbsp;</span>ngày trước</p>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="wrap-img pull-left"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/ISxzl4dulivj820000000000.jpg" alt=""></a>
                        <div class="infor-result">
                            <p class="item-title">620 1/2 Locust Ave # B, Clarksburg, WV</p>
                            <p class="type-result"><em class="fa fa-circle for-sale"></em>APARTMENT FOR RENT</p>
                            <p class="rice-result">$750/mo</p>
                            <p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>
                            <p class="date-post-rent"><span class="toz-count">7&nbsp;</span>ngày trước</p>
                        </div>
                    </li>
                </ul>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="re-nhat">

            </div>
            <div role="tabpanel" class="tab-pane fade" id="phong-ngu">

            </div>
            <div role="tabpanel" class="tab-pane fade" id="khac">

            </div>
        </div>
    </div>
</div>
