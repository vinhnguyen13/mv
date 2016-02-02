<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 2/2/2016 10:19 AM
 */

?>
<div class="profile-user">
    <div class="title-top clearfix"><a href="#" class="icon icon-back-top pull-left"></a> Profile <a href="#" class="icon icon-back-top pull-right"></a></div>
    <div class="infor-user clearfix">
        <div class="col-xs-4 num-follow">
            <span class="num">1.3K</span>
            <span class="txt-num">FOLLOWERS</span>
            <span class="icon-add">+</span>
        </div>
        <div class="col-xs-4 avatar-user-pr">
            <div class="wrap-img avatar"><img src="<?=Yii::$app->view->theme->baseUrl."/resources/images/MV-Agent Photo.jpg"?>" alt="" /></div>
            <span class="name-user">Hao Do</span>
            <span class="address-user"><em class="fa fa-map-marker"></em>HO CHI MINH, VIETNAM</span>
        </div>
        <div class="col-xs-4 num-rating">
            <span class="num">4.3</span>
            <span class="txt-num">RATING</span>
            <span class="notifa"><em class="icon-bubble"></em></span>
        </div>
    </div>
    <div class="swiper-container gallery-img-user">
        <div class="swiper-wrapper">
            <div class="swiper-slide wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/21311_Khai-truong-Pearl-Plaza-2.jpg"?>);"></div>
            <div class="swiper-slide wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/22311_Khai-truong-Pearl-Plaza-6.jpg"?>);"></div>
            <div class="swiper-slide wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/22311_Khai-truong-Pearl-Plaza-7.jpg"?>);"></div>
            <div class="swiper-slide wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg"?>);"></div>
            <div class="swiper-slide wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/img-duan-demo.jpg"?>);"></div>
            <div class="swiper-slide wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/21311_Khai-truong-Pearl-Plaza-2.jpg"?>);"></div>
            <div class="swiper-slide wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/21311_Khai-truong-Pearl-Plaza-2.jpg"?>);"></div>
        </div>
    </div>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Tóm tắt tiểu sử <span class="icon"></span>
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    Mauris non tempor quam, et lacinia sapien. Mauris accumsan eros eget libero posuere vulputate. Etiam elit elit, elementum sed varius at, adipiscing vitae est. Sed nec felis pellentesque, lacinia dui sed, ultricies sapien. Pellentesque orci lectus, consectetur vel posuere posuere, rutrum eu ipsum.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Các...<span class="icon"></span>
                    </a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                    Mauris non tempor quam, et lacinia sapien. Mauris accumsan eros eget libero posuere vulputate. Etiam elit elit, elementum sed varius at, adipiscing vitae est. Sed nec felis pellentesque, lacinia dui sed, ultricies sapien. Pellentesque orci lectus, consectetur vel posuere posuere, rutrum eu ipsum.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFour">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Kinh nghiệm<span class="icon"></span>
                    </a>
                </h4>
            </div>
            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                <div class="panel-body">
                    Mauris non tempor quam, et lacinia sapien. Mauris accumsan eros eget libero posuere vulputate. Etiam elit elit, elementum sed varius at, adipiscing vitae est. Sed nec felis pellentesque, lacinia dui sed, ultricies sapien. Pellentesque orci lectus, consectetur vel posuere posuere, rutrum eu ipsum.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFive">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Các giao dịch gần nhất<span class="icon"></span>
                    </a>
                </h4>
            </div>
            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                <div class="panel-body">
                    <div class="rating-mv-listing">
                        <ul>
                            <li>
                                <div class="clearfix">
                                    <span class="pull-right">6/10</span>
                                    Thông tin chung
                                </div>
                                <div class="rating-percent">
                                    <div class="num-percent" style="width:50%;"></div>
                                </div>
                            </li>
                            <li>
                                <div class="clearfix">
                                    <span class="pull-right">6/10</span>
                                    Thông tin chi tiết
                                </div>
                                <div class="rating-percent">
                                    <div class="num-percent" style="width:80%;"></div>
                                </div>
                            </li>
                            <li>
                                <div class="clearfix">
                                    <span class="pull-right">6/10</span>
                                    Hình ảnh
                                </div>
                                <div class="rating-percent">
                                    <div class="num-percent" style="width:60%;"></div>
                                </div>
                            </li>
                            <li>
                                <div class="clearfix">
                                    <span class="pull-right">6/10</span>
                                    Bản vẽ mặt bằng
                                </div>
                                <div class="rating-percent">
                                    <div class="num-percent" style="width:20%;"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSix">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                        Các tin môi giới đang đăng<span class="icon"></span>
                    </a>
                </h4>
            </div>
            <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                <div class="panel-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab-ban" aria-controls="tab-signin" role="tab" data-toggle="tab">BÁN</a><span class="line-center"></span></li>
                        <li role="presentation"><a href="#tab-chothue" aria-controls="tab-signup" role="tab" data-toggle="tab">CHO THUÊ</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="tab-ban">
                            <div class="tab-ban">
                                <ul class="clearfix">
                                    <li>
                                        <a href="#">
                                            <span class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg"?>);"></span>
                                            <span class="name-listing">Quisque volutpat augue enim</span>
                                            <span class="price">3,4 tỷ VNĐ</span>
                                            <span class="short-txt">2 Phòng ngủ…</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg"?>);"></span>
                                            <span class="name-listing">Quisque volutpat augue enim</span>
                                            <span class="price">3,4 tỷ VNĐ</span>
                                            <span class="short-txt">2 Phòng ngủ…</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg"?>);"></span>
                                            <span class="name-listing">Quisque volutpat augue enim</span>
                                            <span class="price">3,4 tỷ VNĐ</span>
                                            <span class="short-txt">2 Phòng ngủ…</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg"?>);"></span>
                                            <span class="name-listing">Quisque volutpat augue enim</span>
                                            <span class="price">3,4 tỷ VNĐ</span>
                                            <span class="short-txt">2 Phòng ngủ…</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg"?>);"></span>
                                            <span class="name-listing">Quisque volutpat augue enim</span>
                                            <span class="price">3,4 tỷ VNĐ</span>
                                            <span class="short-txt">2 Phòng ngủ…</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg"?>);"></span>
                                            <span class="name-listing">Quisque volutpat augue enim</span>
                                            <span class="price">3,4 tỷ VNĐ</span>
                                            <span class="short-txt">2 Phòng ngủ…</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab-chothue">
                            <div class="tab-chothue">
                                <ul class="clearfix">
                                    <li>
                                        <a href="#">
                                            <span class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg"?>);"></span>
                                            <span class="name-listing">Quisque volutpat augue enim</span>
                                            <span class="price">3,4 tỷ VNĐ</span>
                                            <span class="short-txt">2 Phòng ngủ…</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg"?>);"></span>
                                            <span class="name-listing">Quisque volutpat augue enim</span>
                                            <span class="price">3,4 tỷ VNĐ</span>
                                            <span class="short-txt">2 Phòng ngủ…</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg"?>);"></span>
                                            <span class="name-listing">Quisque volutpat augue enim</span>
                                            <span class="price">3,4 tỷ VNĐ</span>
                                            <span class="short-txt">2 Phòng ngủ…</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg"?>);"></span>
                                            <span class="name-listing">Quisque volutpat augue enim</span>
                                            <span class="price">3,4 tỷ VNĐ</span>
                                            <span class="short-txt">2 Phòng ngủ…</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg"?>);"></span>
                                            <span class="name-listing">Quisque volutpat augue enim</span>
                                            <span class="price">3,4 tỷ VNĐ</span>
                                            <span class="short-txt">2 Phòng ngủ…</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg"?>);"></span>
                                            <span class="name-listing">Quisque volutpat augue enim</span>
                                            <span class="price">3,4 tỷ VNĐ</span>
                                            <span class="short-txt">2 Phòng ngủ…</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSeven">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                        Thông tin liên lạc<span class="icon"></span>
                    </a>
                </h4>
            </div>
            <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
                <div class="panel-body">
                    <div class="email-agent"><div><span class="icon"></span></div>ccollins@twimbo.info</div>
                    <div class="phone-agent"><div><span class="icon"></span></div>8 (800) 123456789</div>
                    <div class="id-agent"><div><span class="icon"></span></div>AGENT ID TTG001</div>
                </div>
            </div>
        </div>
    </div>
</div>
