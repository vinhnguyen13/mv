<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
    <meta name='viewport' content='width=device-width, initial-scale=1.0' >
    <?= Html::csrfMetaTags() ?>
    <title><?= (!empty($this->title) ? Html::encode($this->title).', ' : '').Yii::$app->name ?></title>
    <?php $this->head() ?>
</head>
<body <?=!empty($this->params['body']) ? \common\components\Util::me()->arrayToHtmlAttributes($this->params['body']) : ''?>>
    <?php $this->beginBody() ?>
        <?= $content ?>
    <?php $this->endBody() ?>
    <?php $this->beginContent('@app/views/chat/_partials/connect.php'); ?><?php $this->endContent();?>
    <?php $this->beginContent('@app/views/layouts/_partials/analyticstracking.php'); ?><?php $this->endContent();?>

    <div id="alert-noti"></div>

    <div id="popup-campain" class="modal popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="wrap-popup">
                        <div class="title-popup">
                            Thông báo
                            <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                        </div>
                        <div class="inner-popup">
                            <p class="mgB-15">Cập nhật thông tin của bạn, để nhận ngay <span class="font-600 text-decor">35 Keys</span> cho việc đăng tin <span class="font-600">VIP</span>.</p>
                            <p class="mgB-5 font-600">Email</p>
                            <input type="text" class="form-control" placeholder="abc@gmail.com">
                            <p class="mgB-5 font-600 mgT-15">Mật khẩu</p>
                            <input type="password" class="form-control" placeholder="******">
                            <p class="mgB-5 font-600 mgT-15">Nhập lại mật khẩu</p>
                            <input type="password" class="form-control" placeholder="******">
                            <p class="mgB-5 font-600 mgT-15">Số điện thoại</p>
                            <input type="text" class="form-control" placeholder="012345678">
                            <div class="text-center mgT-15">
                                <button class="btn-common btn-bd-radius btn-cancel" data-dismiss="modal" aria-label="Close">Bỏ Qua</button>
                                <button class="btn-common btn-bd-radius">Nhận Keys</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(window).on('load', function () {
            setTimeout(function () {
                $('#popup-campain').modal({backdrop: 'static', keyboard: false});
                $('#popup-campain').on('hidden.bs.modal', function (e) {
                    // do something...
                    console.log(1);
                })
            },900);
        });
    </script>
</body>
</html>
<?php $this->endPage() ?>