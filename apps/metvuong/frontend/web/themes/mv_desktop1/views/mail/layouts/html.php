<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var \yii\web\View        $this
 * @var yii\mail\BaseMessage $content
 */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <style>
        *{margin:0;padding:0;}
        body {
            font-family: arial;
            color: #3c3c3c;
        }
    </style>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <?php $this->head() ?>
</head>
<body bgcolor="#f6f6f6">
<table cellspacing="0" cellpadding="0" border="0" width="630" style="margin: 0 auto 0;">
    <tbody><tr>
        <td>
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tbody><tr>
                    <td style="background: #00a769;padding: 10px 30px;">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody><tr>
                                <td><a href="<?=\yii\helpers\Url::home(true)?>"><img style="width: 150px;" alt="" src="<?=\yii\helpers\Url::home(true)?>images/logo-white.png"></a></td>
                                <td style="text-align:right; color: #fff;font-size: 14px;font-weight: bold;">Kênh mua sắm bất động sản hàng đầu Việt Nam!</td>
                            </tr>
                            </tbody></table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px 30px;border: 1px solid #e7e7e7;">
                        <a href="<?=\yii\helpers\Url::home(true)?>" style="text-decoration: none;"><p style="color: #00a769;font-size: 16px;font-weight: bold;margin-bottom: 15px;">Thông báo từ metvuong.com</p></a>
                            <?php $this->beginBody() ?>
                            <?= $content ?>
                            <?php $this->endBody() ?>

                        <p style="font-size: 13px;margin-bottom:5px;">Trân trọng,</p>
                        <p style="font-size: 13px;">Metvuong Team</p>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f1efef;padding: 10px 30px;font-size: 11px;">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody><tr>
                                <td>
                                    <p style="margin-bottom: 5px;"><strong>Metvuong Team</strong></p>
                                    <p style="margin-bottom: 5px;">21 Nguyễn Trung Ngạn, Bến Nghé, Quận 1, Hồ Chí Minh, Việt Nam</p>
                                    <p style="margin-bottom: 5px;"><b>Điện thoại:</b> (+84) (8) 7309 9966, ­ (+84) (8) 3824 6810</p>
                                    <p style="margin-bottom: 5px;"><b>Email:</b> info@metvuong.com</p>
                                </td>
                                <td style="text-align: right;">
                                    <a href="https://www.facebook.com/metvuongbatdongsan" style="display:inline-block;margin-right:20px;"><img width="40" alt="Facebook" src="<?=\yii\helpers\Url::home(true)?>images/icon-face.png"></a>
                                    <a href="https://plus.google.com/109274474274313872943"><img width="40" alt="Google plus" src="<?=\yii\helpers\Url::home(true)?>images/icon-go.png"></a>
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
