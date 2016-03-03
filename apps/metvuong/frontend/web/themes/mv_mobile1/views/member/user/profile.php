<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 2/2/2016 10:19 AM
 */
use vsoft\express\components\StringHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$avatar = \Yii::getAlias('@store') . DIRECTORY_SEPARATOR . "avatar" . DIRECTORY_SEPARATOR . $model->avatar;
$user = $model->getUser();
?>
<div class="title-fixed-wrap">
    <div class="profile-user">
        <div class="title-top clearfix"><a href="#" class="icon icon-back-top pull-left"></a> Profile User<a href="#" class="icon icon-back-top pull-right"></a></div>
        <div class="infor-user clearfix">
            <div class="avatar-user-pr">
                <div class="wrap-img avatar"><img id="profileAvatar" data-toggle="modal" data-target="#avatar" src="<?=$model->avatar?>" alt="metvuong avatar" /></div>
                <div class="name-user" ><?= $model->name ?></div>
                <div class="per-posi">Agent ID TTG<?=str_pad($model->user_id, 3, '0', STR_PAD_LEFT)?></div>
                <div class="text-center send-noti">
                    <a href="<?=$user->urlChat();?>" class="btn-common btn-chat rippler rippler-default"><span class="icon icon-chat-1"></span></a>
                    <a href="tel:<?= $model->mobile ?>" class="btn-common rippler rippler-default"><span class="icon icon-phone-1"></span></a>
                    <a href="sms:<?= $model->mobile ?>" class="btn-common rippler rippler-default"><span class="icon icon-phone-1"></span></a>
                </div>
            </div>
        </div>

        <div class="infor-person">
            <div class="title-text">Thông tin cá nhân</div>
            <div class="wrap-txt">
                <?= empty($model->about) ? "Đang cập nhật" : $model->about ?>
            </div>
        </div>

        <ul class="clearfix list-attr-per">
            <li>
                <div class="circle"><div><span class="icon icon-map-loca-1"></span></div></div>
                <div class="txt-infor-right">
                    <div>
                        <?= empty($model->address) ? "Đang cập nhật" : $model->address ?>
                    </div>
                </div>
            </li>
            <li>
                <div class="circle"><div><span class="icon icon-phone-1"></span></div></div>
                <div class="txt-infor-right">
                    <div>
                        <a href="tel:<?=$model->mobile ?>"><?= empty($model->mobile) ? "Đang cập nhật" : $model->mobile ?></a>
                    </div>
                </div>
            </li>
            <li>
                <div class="circle"><div><span class="icon icon-email-1"></span></div></div>
                <div class="txt-infor-right">
                    <div>
                        <?= empty($model->public_email) ? "Đang cập nhật" : $model->public_email ?>
                    </div>
                </div>
            </li>
        </ul>

        <?php
        if(count($products) > 0) {
        $categories = \vsoft\ad\models\AdCategory::find ()->indexBy( 'id' )->asArray( true )->all ();
        $types = \vsoft\ad\models\AdProduct::getAdTypes ();
        ?>

        <div class="list-per-post">
            <div class="title-text">Danh sách tin đã đăng</div>
            <ul class="clearfix list-post">
                <?php foreach ($products as $product) {
                    ?>
                <li>
                    <a href="<?= $product->urlDetail() ?>" class="rippler rippler-default">
                        <div class="img-show"><div><img src="<?= $product->representImage ?>">
                                <input type="hidden" value="<?= $product->representImage ?>">
                            </div></div>
                        <div class="title-item"><?= ucfirst($categories[$product->category_id]['name']) ?> <?= $types[$product->type] ?></div>
                    </a>
                    <a href="<?= $product->urlDetail() ?>"><p class="name-post"><span class="icon address-icon"></span><?=$product->getAddress()?></p></a>
                    <p class="id-duan">ID tin đăng:<span><?=$product->id?></span></p>
                    <ul class="clearfix list-attr-td">
                        <li>
                            <span class="icon icon-dt icon-dt-small"></span><?= $product->area ?>
                        </li>
                        <li>
                            <span class="icon icon-bed icon-bed-small"></span><?= $product->adProductAdditionInfo->room_no ?>
                        </li>
                        <li>
                            <span class="icon icon-pt icon-pt-small"></span><?= $product->adProductAdditionInfo->toilet_no ?>
                        </li>
                    </ul>
                    <p class="price-post">Giá bán <strong><?= StringHelper::formatCurrency($product->price) ?> đồng</strong></p>
                </li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>

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
                                    'clientOptions' => [
                                        'maxNumberOfFiles' => 1,
                                        'imageMaxWidth' => 800,
                                        'imageMaxHeight' => 800,
                                        'disableImageResize' => false,
                                        'imageCrop' => true,
                                        'noAvatar' => file_exists($avatar) ? true : false,
                                    ],
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
</div>
<script>
    $(document).ready(function () {
        $('#avatar').on('hidden.bs.modal', function () {
            var url = $('.files .name a').attr("href");
            if(url == null || url == '')
                url = '/store/avatar/default-avatar.jpg';
            $('#headAvatar').attr("src", url);
            $('.avatar img').attr("src", url);
            $('.user-edit img').attr("src", url);
        });

//        var username = '<?//=Yii::$app->user->identity->username?>//';
//        var paramUsername = '<?//=$username?>//';
//
//        if(username != paramUsername) {
//            $('#profileAvatar').removeAttr("data-target");
//        }
//
//        var editable = document.querySelectorAll('div[contenteditable=true]');
//        for (var i = 0, len = editable.length; i < len; i++) {
//            if(username != paramUsername) {
//                editable[i].setAttribute("contenteditable", false);
//                $('.change-pass').hide();
//            }
//            else {
//                var textTrim = editable[i].innerHTML.trim();
//                if (textTrim == "")
//                    editable[i].innerHTML = "";
//                editable[i].setAttribute('data-orig', $.trim(editable[i].innerHTML));
//                editable[i].onblur = function () {
//                    if ($.trim(this.innerHTML) == this.getAttribute('data-orig')) {
//                    }
//                    else {
//                        var name = this.getAttribute('name');
//                        txt = $.trim(this.innerHTML);
//                        // change has happened, store new value
//                        if (name == 'public_email') {
//                            var check = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
//                            if (txt == '' || !check.test(txt)) {
//                                alert("Vui lòng nhập email hợp lệ.");
////                            this.focus();
//                            } else {
//                                // after valid email call ajax
//                                this.setAttribute('data-orig', txt);
//                                sendDataProfile(<?//=$model->user_id?>//, txt, name);
//                            }
//                        }
//                        else {
//                            this.setAttribute('data-orig', txt);
//                            sendDataProfile(<?//=$model->user_id?>//, txt, name);
//                        }
//                    }
//                };
//            }
//        }
//
//        $("div[name=mobile]").keypress(function (e) {
//            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
//                return false;
//            }
//            if (e.length > 5)
//                return false;
//        });
//
//        function sendDataProfile(user_id, txtData, type) {
//            var timer = 0;
//            clearTimeout(timer);
//            timer = setTimeout(function () {
//                $.ajax({
//                    type: "post",
//                    dataType: 'json',
//                    url: '<?//=Url::to(['/member/profile', 'username'=>$username])?>//',
//                    data: {uid: user_id, txt: txtData, type: type},
//                    success: function (data) {
//                        console.log(data);
//                    }
//                });
//            }, 500);
//            return false;
//        }
    });
</script>