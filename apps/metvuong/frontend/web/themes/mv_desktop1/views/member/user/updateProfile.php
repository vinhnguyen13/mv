<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 2/26/2016
 * Time: 9:51 AM
 */
use vsoft\ad\models\AdCity;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$user = $model->getUser();
$user_location = \frontend\models\UserLocation::find()->where(['user_id' => Yii::$app->user->id])->one();
Yii::t('time','years');
Yii::t('time','year');
Yii::t('time','months');
Yii::t('time','month');
Yii::t('time','weeks');
Yii::t('time','week');
?>
<div class="title-fixed-wrap container" xmlns="http://www.w3.org/1999/html">
    <div class="edit-user-tt">
        <div class="title-top">Settings</div>
        <div class="wrap-edit-tt">
            <section class="infor-user-settings">
                <div class="wrap-attr-detail clearfix">
                    <a href="#" class="edit-profile"><span class="icon-mv"><span class="icon-edit-copy-4"></span></span></a>
                    <div class="avatar wrap-img pull-left mgR-15">
                        <img id="profileAvatar" src="<?=$model->avatar?>" alt="metvuong avatar" />
                    </div>    
                    <div class="overflow-all">
                        <p class="name-user fs-18 font-600 color-cd text-uper mgB-5"><span class="name"><?=empty($model->name) ? $user->username : $model->name  ?></span></p>
                        <p class="location mgB-5">
                            <span class="icon-mv"><span class="icon-pin-active-copy-3"></span></span>
                            <span class="address"><?=empty($user_location) ? Yii::t('general', 'Updating') : $user_location->city?></span>
                        </p>
                        <p class="num-mobi mgB-5">
                            <span class="icon-mv"><span class="icon-phone-profile"></span></span>
                            <a href="<?=empty($model->mobile) ? "#" : "tel:".$model->mobile ?>" class="mobile">
                                <?=empty($model->mobile) ? "<i style=\"font-weight: normal;\">".Yii::t('general', 'Updating')."</i>" : $model->mobile ?>
                            </a>
                        </p>
                        <p class="email-user">
                            <a href="#" class="email-btn">
                                <span class="icon-mv"><span class="icon-mail-profile"></span></span>
                                <span class="public_email"><?=empty($model->public_email) ? $user->email : $model->public_email ?></span>
                            </a>
                        </p>
                    </div>
                </div>
                <div class="box-edit-show wrap-attr-detail clearfix">
                    <div class="posi_absolute btn-mani">
                        <a href="#" class="done-profile btn-common"><?= Yii::t('user', 'Xong') ?></a>    
                        <a href="#" class="cancel-profile btn-common btn-cancel"><?= Yii::t('user', 'Hủy') ?></a>    
                    </div>
                    <div class="avatar wrap-img pull-left mgR-15 text-center">
                        <?php
                        $avatar = str_replace("/store/avatar/","", $model->avatar);
                        $avatar = \Yii::getAlias('@store') . DIRECTORY_SEPARATOR . "avatar" . DIRECTORY_SEPARATOR . $avatar;

                        echo Html::hiddenInput('deleteLater', '', ['id' => 'delete-later']);
                        echo \common\widgets\FileUploadAvatar::widget([
                            'model' => $model, 'attribute' => 'avatar',
                            'url' => Url::to(['member/upload', 'folder' => 'avatar'], true),
                            'clientOptions' => [
                                'maxNumberOfFiles' => 1,
                                'imageMaxWidth' => 1024,
                                'imageMaxHeight' => 1024,
                                'disableImageResize' => false,
                                'imageCrop' => true,
                                'noAvatar' => file_exists($avatar) ? true : false,
                            ],
                            'fieldOptions' => ['folder' => 'avatar'],
                            'clientEvents' => [
                                'fileuploaddone' => 'function(e, data) {
                                    $(".avatar-user").prop("value", data.result.files[0]["name"]);
                                    $(".avatar img").attr("src", data.result.files[0]["thumbnailUrl"]);
                                    $(".user-edit img").attr("src", data.result.files[0]["thumbnailUrl"]);
                                }',
                                'fileuploaddestroyed' => 'function(e, data){
                                    $(".avatar-user").prop("value", "/images/default-avatar.jpg");
                                    $(".avatar img").attr("src", "/images/default-avatar.jpg");
                                    $(".user-edit img").attr("src", "/images/default-avatar.jpg");
                                }'
                            ]
                        ]);   ?>
                    </div>
                    <div class="overflow-all">
                        <?php
                        $profile_form = Yii::createObject([
                            'class'    => \frontend\models\ProfileForm::className(),
                            'scenario' => 'updateprofile',
                        ]);
                        $f = ActiveForm::begin([
                            'id' => 'form-edit-ttcn',
                            'enableAjaxValidation' => true,
                            'enableClientValidation' => true,
                            'action' => Url::to(['member/update-profile', 'username'=>Yii::$app->user->identity->getUsername()])
                        ]);
                        ?>
                        <div class="name-user fs-18 font-600 color-cd text-uper mgB-5">
                            <?= $f->field($profile_form, 'name')->textInput(['value' => empty($model->name) ? $user->username : $model->name ])->label(false)?>
                        </div>
                        <div class="num-mobi">
                            <span class="icon-mv"><span class="icon-phone-profile"></span></span>
                            <?= $f->field($profile_form, 'mobile')->textInput(['type'=>'number', 'maxlength' => 11, 'value' => $model->mobile ])->label(false)?>
                        </div>
                        <div class="email-user">
                            <span class="icon-mv"><span class="icon-mail-profile"></span></span>
                            <?= $f->field($profile_form, 'public_email')->textInput(['value' => empty($model->public_email) ? $user->email : $model->public_email ])->label(false)?>
                            <input type="hidden" name="scenario" value="updateprofile">
                            <input type="hidden" name="profile-form[avatar]" class="avatar-user" value="">
                        </div>
                        <?php $f->end(); ?>
                        <div class="location mgB-5">
                            <span class="icon-mv"><span class="icon-pin-active-copy-3"></span></span>
                            <?php
                            if(empty($user_location))
                                $user_location = new \frontend\models\UserLocation();

                            $form = ActiveForm::begin ( [
                                'id' => 'user-location-form',
                                'enableClientValidation' => false,
                                'options' => [
                                    'autocomplete' => 'off',
                                    'spellcheck' => 'false'
                                ],
                                'action' => Url::to(['member/update-user-location'])
                            ]);
                            $cities = AdCity::find()->all();
                            $citiesDropdown = ArrayHelper::map($cities, 'id', 'name');
                            //                            $citiesOptions = ArrayHelper::map($cities, 'id', function($city){ return ['disabled' => ($city->id != \frontend\models\UserLocation::DEFAULT_CITY)]; });
                            echo $form->field($user_location, 'city_id', ['options' => ['class' => 'attr-right city']])
                                ->dropDownList($citiesDropdown, ['prompt' => Yii::t('profile','Select...'), 'options' => [empty($user_location) ? 1 : $user_location->city_id => ['Selected ' => true]]])
                                ->label(false);

                            echo $form->field($user_location, 'user_id')->hiddenInput(['value'=>Yii::$app->user->id])->label(false);
                            $form->end();
                            ?>
                        </div>
                    </div>

                </div>
            </section>
            <section class="mtbt">
                <div class="title-update-tt">
                    <?=Yii::t('profile', 'Description')?>
                </div>
                <div class="wrap-attr-detail">
                    <a href="#" class="edit-profile"><span class="icon-mv"><span class="icon-edit-copy-4"></span></span></a>
                    <div class="txt-wrap">
                        <p class="txt-mota"><?=empty($model->bio) ? "<i style=\"font-weight: normal;\">".Yii::t('general', 'Updating')."</i>" : $model->bio ?></p>
                    </div>
                </div>
                <div class="box-edit-show wrap-attr-detail">
                    <div class="posi_absolute btn-mani">
                        <a href="#" class="done-profile btn-common"><?= Yii::t('user', 'Xong') ?></a>
                        <a href="#" class="cancel-profile btn-common btn-cancel"><?= Yii::t('user', 'Hủy') ?></a>
                    </div>
                    <?php
                    $profile_form = Yii::createObject([
                        'class'    => \frontend\models\ProfileForm::className(),
                        'scenario' => 'updatebio',
                    ]);
                    $f = ActiveForm::begin([
                        'id' => 'form-edit-mtbt',
                        'enableAjaxValidation' => false,
                        'enableClientValidation' => true,
                        'action' => Url::to(['member/update-bio', 'username'=>Yii::$app->user->identity->getUsername()])
                    ]);
                    ?>
                    <div class="txt-wrap">
                        <?= $f->field($profile_form, 'bio')->textarea(['value' => $model->bio ])->label(false)?>
                        <input type="hidden" name="scenario" value="updatebio">
                    </div>
                    <?php $f->end(); ?>
                </div>
            </section>
            <section class="changepass">
                <div class="title-update-tt">
                    <?=Yii::t('profile', 'Change password')?>
                </div>
                <div class="wrap-attr-detail">
                    <a href="#" class="edit-profile"><span class="icon-mv"><span class="icon-edit-copy-4"></span></span></a>
<!--                    <a class="text-decor color-cd-hover fs-13 font-600 link-change-pass" href="#">Đổi mật khẩu</a> -->
                    <span class="pass_result"></span>
                    <span class="last_changed"><?=Yii::t('profile','Password last changed')?> <?=\frontend\models\ProfileForm::humanTiming($user->confirmed_at)?>.</span>
                </div>
                <div class="box-edit-show wrap-attr-detail">
                    <div class="posi_absolute btn-mani">
                        <a href="#" class="done-profile btn-common"><?= Yii::t('user', 'Xong') ?></a>
                        <a href="#" class="cancel-profile btn-common btn-cancel"><?= Yii::t('user', 'Hủy') ?></a>
                    </div>
                    <div class="row">
                        <br>
                        <?php
                        $profile_form = Yii::createObject([
                            'class'    => \frontend\models\ProfileForm::className(),
                            'scenario' => 'password',
                        ]);
                        $f = ActiveForm::begin([
                            'id' => 'form-edit-changepass',
                            'enableAjaxValidation' => false,
                            'enableClientValidation' => true,
                            'action' => Url::to(['member/password'])
                        ]);
                        ?>
                        <div class="col-xs-12 col-md-3">
                            <?=Yii::t('profile', 'Old password')?>
                        </div>
                        <div class="col-xs-12 col-md-9 mgB-10">
                            <?= $f->field($model, 'old_password')->textInput(['class' => 'old_password', 'type' => 'password', 'placeholder' => Yii::t('profile','Input...')])->label(false) ?>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <?=Yii::t('profile', 'New password')?>
                        </div>
                        <div class="col-xs-12 col-md-9 mgB-10">
                            <?= $f->field($model, 'new_password')->textInput(['class' => 'new_password', 'type' => 'password', 'placeholder' => Yii::t('profile','Input...')])->label(false) ?>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <?=Yii::t('profile', 'Confirm password')?>
                        </div>
                        <div class="col-xs-12 col-md-9">
                            <input type="password" class="re-type-pass" value="" placeholder="<?=Yii::t('profile','Input...')?>">
                            <p class="help-block help-block-error error hide"></p>
                        </div>
                        <?php $f->end(); ?>
                    </div>
                </div>
            </section>
            <section class="lang">
                <div class="title-update-tt">
                    NGÔN NGỮ        
                </div>
                <div class="mgT-15">
                    <a href="<?=Url::current(['language-change'=>'en-US'])?>" class="d-ib mgR-10"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/flag-en.png' ?>" alt=""></a>
                    <a href="<?=Url::current(['language-change'=>'vi-VN'])?>"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/flag-vn.png' ?>" alt=""></a>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
<script>
	$(document).ready(function () {
		$('.edit-profile, .link-change-pass').on('click', function (e) {
            e.preventDefault();
            var _this = $(this);
            _this.closest('section').find('.wrap-attr-detail').hide();
            _this.closest('section').find('.box-edit-show').show();
        });

        $('.cancel-profile').on('click', function (e) {
            e.preventDefault();
            var _this = $(this);
            _this.closest('section').find('.wrap-attr-detail').show();
            _this.closest('section').find('.box-edit-show').hide();
        });

        $('.infor-user-settings .done-profile').on('click', function(e){
            e.preventDefault();
            var _this = $(this);
            _this.closest('section').loading({
                full: false
            });
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: $('#form-edit-ttcn').attr('action'),
                data: $('#form-edit-ttcn').serializeArray(),
                success: function (data) {
                    _this.closest('section').loading({done: true});
                    if(data.statusCode == 200){
                        $('.user-edit .name-user').html(data.model.name);
                        $('.infor-user-settings .name').html(data.model.name);
                        $('.infor-user-settings .mobile').html(data.model.mobile);
                        $('.infor-user-settings .mobile').attr("href","tel:"+data.model.mobile);
                        $('.infor-user-settings .public_email').html(data.model.public_email);

                        _this.closest('section').find('.wrap-attr-detail').show();
                        _this.closest('section').find('.box-edit-show').hide();
                    } else if (data.statusCode == 400) {
                        var arr = [];
                        $.each(data.parameters, function (idx, val) {
                            var element = 'profile-form-' + idx;
                            arr[element] = val;
                        });
                        $('#form-edit-ttcn').yiiActiveForm('updateMessages', arr, true);
                    } else {
                        _this.html('Try again !');
                    }
                    return true;
                },
                error: function (data) {
                    return false;
                }
            });
        });

        $('.mtbt .done-profile').on('click', function(){
            var _this = $(this);
            _this.closest('section').loading({
                full: false
            });
            $('.edit-mtbt .error').addClass('hide');
            
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: $('#form-edit-mtbt').attr('action'),
                data: $('#form-edit-mtbt').serializeArray(),
                success: function (data) {
                    _this.closest('section').loading({done: true});
                    if(data.statusCode == 200){
                        $('.mtbt .txt-mota').html(data.model.bio);
                        _this.closest('section').find('.wrap-attr-detail').show();
                        _this.closest('section').find('.box-edit-show').hide();
                    } else {
                        return false;
                    }
                    return false;
                },
                error: function (data) {
                   return false;
                }
            });
        });

        $('.changeAlias .done-profile').on('click', function(){
            var _this = $(this);
            _this.closest('section').loading({
                full: false
            });
            $('.edit-mtbt .error').addClass('hide');

            $.ajax({
                type: 'post',
                dataType: 'json',
                url: $('#form-edit-alias').attr('action'),
                data: $('#form-edit-alias').serializeArray(),
                success: function (data) {

                },
                error: function (data) {
                    return false;
                }
            });
            return false;
        });

        $('.changepass .done-profile').on('click', function(){
            var _this = $(this);
            _this.closest('section').loading({
                full: false
            });

            var new_password = $('.new_password').val();
            var rePass = $('.re-type-pass').val();
            if(new_password !== rePass){
                _this.closest('section').loading({done: true});
                $('.changepass .error').html('<?=Yii::t('profile','Confirm password not match.')?>');
                $('.changepass .error').removeClass('hide');
                $('.changepass .re-type-pass').focus();
                return false;
            }

            $.ajax({
                type: 'post',
                dataType: 'json',
                url: $('#form-edit-changepass').attr('action'),
                data: $('#form-edit-changepass').serializeArray(),
                success: function (data) {
                    _this.closest('section').loading({done: true});
                    if(data.statusCode == 200){
                        $('.changepass .last_changed').remove();
                        _this.closest('section').find('.wrap-attr-detail').show();
                        _this.closest('section').find('.box-edit-show').hide();
                        _this.closest('section').find('.pass_result').html("<span class=\"reset_success\"><?=Yii::t('profile','Reset password sucesss.')?></span>");
                        $('#form-edit-changepass input').val('');

                    } else {
                        var strMessage = '';
                        var focusName = '';
                        $.each(data.parameters, function (idx, val) {
                            focusName = 'profile-form-' + idx;
                            strMessage += "<br>" + val;
                        });
                        $('#'+focusName).focus();
                        $('.changepass .error').html(strMessage);
                        $('.changepass .error').removeClass('hide');
                    }
                    return true;
                },
                error: function (data) {
                    l(data);
                    var strMessage = '';
                    $.each(data.parameters, function(idx, val){
                        strMessage += "<br>" + val;
                    });
                    $('.changepass .error').html(strMessage);
                    $('.changepass .error').removeClass('hide');
                    return false;
                }
            });

        });
        
        $('#userlocation-city_id').change(function(){
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: $('#user-location-form').attr('action'),
                data: $('#user-location-form').serializeArray(),
                success: function (data) {
                    if(data.statusCode == 200){
                        var addr = $('#userlocation-city_id option:selected').html();
                        $('.user-edit .address').html(addr);
                        $('.infor-user-settings .address').html(addr);
                    }
                    return true;
                },
                error: function (data) {
                    return false;
                }
            });
        });

        $("#profile-form-mobile").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
//            if (this.value.length >= this.maxLength) {
//                return false;
//            }
        });

    });
</script>