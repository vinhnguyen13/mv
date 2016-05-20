<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>


<a href="<?=\yii\helpers\Url::home(true)?>" style="text-decoration: none;"><p style="color: #00a769;font-size: 16px;font-weight: bold;margin-bottom: 15px;">Thông báo từ metvuong.com</p></a>
<p style="margin-bottom: 10px;"><strong style="color: #222;font-size: 13px;">Xin chào, <?= $contact->recipient_email ?>!</strong></p>
<p style="font-size: 13px;margin-bottom: 35px;line-height:20px"><?= str_replace(PHP_EOL, "<br>", $contact->content)?></p>

<table style="margin-bottom: 30px;">
    <tbody><tr>
        <td style="vertical-align:top;">
            <a href="<?= $contact->detailUrl ?>"><img style="width:200px;height:auto;" src="<?=$contact->imageUrl?>"></a>
        </td>
        <td style="vertical-align:top;padding-left:10px;">
            <a style="display:block;font-size:16px;font-weight:bold;margin:0 0 10px 0;color:#009455;text-decoration:none;" href="<?= $contact->detailUrl ?>"><?=$contact->address?></a>
            <p style="margin:0 0 10px 0;font-size:13px;text-transform:uppercase;"><?=$contact->category?></p>
            <p style="font-size:13px;margin:0 0 10px 0;"><span style="font-weight:bold;">Mã tin: </span><?=$contact->pid?> </p>
            <p style="font-size:13px;margin:0 0 10px 0;">
                <?php if(!empty($contact->area) && intval($contact->area) > 0){?>
                    <span style="font-weight:bold;">Diện tích: </span><?=$contact->area?>m2
                <?php }
                if(!empty($contact->room_no) && intval($contact->room_no) > 0){?>
                    <span style="font-weight:bold;">Phòng ngủ: </span><?=$contact->room_no?>
                <?php }
                if(!empty($contact->toilet_no) && intval($contact->toilet_no) > 0){?>
                    <span style="font-weight:bold;">Phòng tắm: </span><?=$contact->toilet_no?>
                <?php } ?>
            </p>
            <p style="border:1px solid #009455;display:inline-block;padding: 7px 15px;font-size:16px;font-weight:bold;line-height:100%;margin:5px 0 0 0;">Giá: <?=$contact->price?></p>
        </td>
    </tr>
    </tbody>
</table>

<p style="margin-bottom: 45px;">Chi tiết xem <a style="font-size:13px;font-weight: bold;text-decoration:none;color:#009455;" href="<?= $contact->detailUrl ?>">tại đây</a>.</p>
<p style="font-size: 13px;margin-bottom:5px;">Trân trọng,</p>
<p style="font-size: 13px;">Metvuong Team</p>
