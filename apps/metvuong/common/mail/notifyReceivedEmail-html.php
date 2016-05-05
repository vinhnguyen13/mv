<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<style>
    *{margin:0;padding:0;}
    body {
        font-family: arial;
        color: #3c3c3c;
    }
</style>

<table cellspacing="0" cellpadding="0" border="0" width="630" style="margin: 0 auto 0;">
    <tbody><tr>
        <td>
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tbody><tr>
                    <td style="background: #00a769;padding: 10px 30px;">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody><tr>
                                <td><a href="#"><img style="width: 150px;" alt="" src="<?=\yii\helpers\Url::home(true)?>images/logo-white.png"></a></td>
                                <td style="text-align:right; color: #fff;font-size: 14px;font-weight: bold;">Kênh mua sắm bất động sản hàng đầu!</td>
                            </tr>
                            </tbody></table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px 30px;border: 1px solid #e7e7e7;">
                        <a href="<?=\yii\helpers\Url::home(true)?>" style="text-decoration: none;"><p style="color: #00a769;font-size: 16px;font-weight: bold;margin-bottom: 15px;">Thông báo từ metvuong.com</p></a>
                        <p style="margin-bottom: 10px;"><strong style="color: #222;font-size: 13px;">Xin chào, <?=!empty($contact->to_name) ? $contact->to_name : $contact->recipient_email?>!</strong></p>
                        <p style="font-size: 13px;margin-bottom: 35px;line-height:20px"><?= str_replace(PHP_EOL, "<br>", $contact->content)?></p>
                        <p style="margin-bottom: 45px;"><a style="background: #00a769;font-size:13px;font-weight: bold;text-decoration:none;color:#fff;padding: 5px 15px;" href="<?= $contact->detailUrl ?>">Xem chi tiết tin đăng</a></p>
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
                                    <p>(08) 789 456    |	support@metvuong.com</p>
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
