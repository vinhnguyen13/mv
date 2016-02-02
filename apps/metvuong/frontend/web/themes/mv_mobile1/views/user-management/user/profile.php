<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 2/2/2016 10:19 AM
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$avatar = \Yii::getAlias('@store') . DIRECTORY_SEPARATOR . "avatar" . DIRECTORY_SEPARATOR . $model->avatar;
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
            <div class="wrap-img avatar"><img data-toggle="modal" data-target="#avatar" src="<?= file_exists($avatar) ? Url::to('/store/avatar/' . $model->avatar) : Yii::$app->view->theme->baseUrl."/resources/images/MV-Agent Photo.jpg"?>" alt="metvuong.com avatar" /></div>
            <span class="name-user"><?=!empty($model->name) ? $model->name : "NO NAME"?></span>
            <span class="address-user"><em class="fa fa-map-marker"></em><?= !empty($model->address) ? $model->address : "HO CHI MINH, VIETNAM"?></span>
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
                <div class="panel-body" contenteditable>
                    <?=$model->about?>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Hoạt động<span class="icon"></span>
                    </a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body" contenteditable>
                    <?=$model->activity?>
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
                <div class="panel-body" contenteditable>
                    <?=$model->experience?>
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
                    <?php if(!empty($model->public_email)){?><div class="email-agent"><div><span class="icon"></span></div><div class="public_email" style="width: 80%" contenteditable><?= $model->public_email ?></div></div><?php }?>
                    <?php if(!empty($model->mobile)){?>
                        <div class="phone-agent"><div><span class="icon"></span></div><div class="mobile" style="width: 80%" contenteditable><?= $model->mobile ?></div></div>
                    <?php } else if(!empty($model->phone)){?>
                        <div class="phone-agent"><div><span class="icon"></span></div><div class="phone" contenteditable><?= $model->phone ?></div></div><?php }?>
                    <?php if(!empty($model->user_id)){?><div class="id-agent"><div><span class="icon"></span></div>AGENT ID TTG<?=str_pad($model->user_id, 3, '0', STR_PAD_LEFT)?></div><?php }?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="avatar" tabindex="-1" role="dialog" aria-labelledby="myModalAvatar">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="icon"></span>
                    </button>
                    <h3>Thay đổi hình đại diện</h3>
                </div>
                <div class="modal-body">
                    <div class="wrap-modal clearfix">
                        <div class="avatar" style="margin-bottom: 50px;">
                            <?php $form = ActiveForm::begin([
                                'id' => 'change-avatar-form',
                                'enableAjaxValidation' => false,
                                'enableClientValidation' => true,
                                'layout' => 'horizontal',
                                'fieldConfig' => [
                                    'horizontalCssClasses' => [
                                        'wrapper' => 'col-sm-9',
                                    ],
                                ],
                            ]); ?>
                            <?=Html::hiddenInput('deleteLater', '', ['id' => 'delete-later']);?>
                            <?= $form->field($model, 'avatar')->widget(\common\widgets\FileUploadAvatar::className(), [
                                'url' => Url::to(['/user-management/avatar', 'folder' => 'avatar']),
                                'clientOptions' => ['maxNumberOfFiles' => 1, 'maxFileSize' => 2000000],
                                'fieldOptions' => ['folder' => 'avatar'],
                            ])->label(false) ?>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 'auto',
        spaceBetween: 10
    });

    $('#avatar button.close').click(function(){
        var url = $('.files .name a').attr("href");
        $('.avatar img').attr("src", url);
    });

    var editable = document.querySelectorAll('div[contentEditable]');
    for (var i=0, len = editable.length; i<len; i++){
        editable[i].setAttribute('data-orig', editable[i].innerText);
        editable[i].onblur = function(){
            if (this.innerText == this.getAttribute('data-orig')) { }
            else {
                // change has happened, store new value
                var $email = $.trim(this.innerText);
                var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
                if ($email == '' || !re.test($email))
                {
                    alert('Please enter a valid email address.');
                } else {
                    // after valid email
                    this.setAttribute('data-orig', $email);

                }
            }
        };
    }

    $(".phone").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });

    $(".mobile").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
        if(e.length > 5)
            return false;
    });

    function sendInfo(txt){
        var timer = 0;
        clearTimeout(timer);
        timer = setTimeout(function () {
            $.ajax({
                type: "post",
                dataType: 'json',
                url: <?=Url::to(['/user-management/profile'])?>,
                data: {txt : txt},
                success: function (data) {
                    console.log(data);
                }
            });
        }, 500);
        return false;
    }

</script>