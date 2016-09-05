<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <style>
        *{margin:0;padding:0;}
        body {
            font-family: arial;
            color: #3c3c3c;
        }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<table width="630" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto 0;">
    <tbody>
        <tr>
            <td>
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody><tr>
                        <td style="background: #00a769;padding: 10px 30px;">
                            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tbody><tr>
                                    <td><a href="<?=Yii::$app->urlManager->hostInfo?>"><img style="width: 150px;" alt="" src="<?=\yii\helpers\Url::to(['/tracking/logo', 'tr'=>!empty(Yii::$app->view->params['tr']) ? Yii::$app->view->params['tr'] : '', 'tp'=>!empty(Yii::$app->view->params['tp']) ? Yii::$app->view->params['tp'] : ''], true)?>"></a></td>
                                    <td style="text-align:right; color: #fff;font-size: 14px;font-weight: bold;">Kênh mua sắm bất động sản hàng đầu Việt Nam!</td>
                                </tr>
                                </tbody></table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px 30px;border: 1px solid #e7e7e7;">
                            <?php $this->beginBody() ?>
                            <?= $content ?>
                            <?php $this->endBody() ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f1efef;padding: 10px 30px;font-size: 11px;">
                            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tbody><tr>
                                    <td>
                                        <p style="margin-bottom: 5px;"><strong>Metvuong Team</strong></p>
                                        <p style="margin-bottom: 5px;">21 Nguyễn Trung Ngạn, Bến Nghé, Quận 1, Hồ Chí Minh, Việt Nam</p>
                                        <p style="margin-bottom: 5px;"><b>Điện thoại:</b> (+84) (8) 7309 9966, ­ (+84) (8) 3824 6810</p>
                                        <p style="margin-bottom: 5px;"><b>Email:</b> info@metvuong.com</p>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="https://www.facebook.com/metvuongbatdongsan" style="display:inline-block;margin-right:20px;"><img width="40" alt="" src="<?=\yii\helpers\Url::to(['images/icon-face.png'], true)?>"></a>
                                        <a href="https://plus.google.com/109274474274313872943"><img width="40" alt="" src="<?=\yii\helpers\Url::to(['images/icon-go.png'], true)?>"></a>
                                    </td>
                                </tr>
                                </tbody></table>
                        </td>
                    </tr>
                    <tr>
                        <td style="background: #00a769;padding: 5px 0;"></td>
                    </tr>
                    </tbody></table>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>
<?php $this->endPage() ?>
