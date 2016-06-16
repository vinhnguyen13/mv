<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>


<a href="<?=\yii\helpers\Url::home(true)?>" style="text-decoration: none;"><p style="color: #00a769;font-size: 16px;font-weight: bold;margin-bottom: 15px;">Notification from metvuong.com</p></a>
<p style="margin-bottom: 10px;"><strong style="color: #222;font-size: 13px;">Hello, <?= $contact->recipient_email ?>!</strong></p>
<p style="font-size: 13px;margin-bottom: 35px;line-height:20px"><?= str_replace(PHP_EOL, "<br>", $contact->content)?></p>

<table style="margin-bottom: 30px;">
    <tbody><tr>
        <td style="vertical-align:top;">
            <a href="<?= $contact->detailUrl ?>"><img style="width:200px;height:auto;" src="<?=$contact->imageUrl?>"></a>
        </td>
        <td style="vertical-align:top;padding-left:10px;">
            <a style="display:block;font-size:16px;font-weight:bold;margin:0 0 10px 0;color:#009455;text-decoration:none;" href="<?= $contact->detailUrl ?>"><?=$contact->address?></a>
            <p style="margin:0 0 10px 0;font-size:13px;text-transform:uppercase;"><?=$contact->category?></p>
            <?php if(!empty($contact->pid)){?>
            <p style="font-size:13px;margin:0 0 10px 0;"><span style="font-weight:bold;">ID: </span><?=$contact->pid?> </p>
            <?php } ?>
            <p style="font-size:13px;margin:0 0 10px 0;">
                <?php if(!empty($contact->area) && intval($contact->area) > 0){?>
                    <span style="font-weight:bold;">Home size: </span><?=$contact->area?> m<sup>2</sup>
                <?php }
                if(!empty($contact->room_no) && intval($contact->room_no) > 0){?>
                    <span style="font-weight:bold;">Beds: </span><?=$contact->room_no?>
                <?php }
                if(!empty($contact->toilet_no) && intval($contact->toilet_no) > 0){?>
                    <span style="font-weight:bold;">Baths: </span><?=$contact->toilet_no?>
                <?php } ?>
            </p>
            <?php if(!empty($contact->price)) { ?>
            <p style="border:1px solid #009455;display:inline-block;padding: 7px 15px;font-size:16px;font-weight:bold;line-height:100%;margin:5px 0 0 0;">Price: <?=$contact->price?></p>
            <?php } ?>
        </td>
    </tr>
    </tbody>
</table>
<?php if(!empty($contact->detailUrl)){ ?>
<p style="margin-bottom: 45px;">View details are <a style="font-size:13px;font-weight: bold;text-decoration:none;color:#009455;" href="<?= $contact->detailUrl ?>">here.</a></p>
<?php } ?>
<p style="font-size: 13px;margin-bottom:5px;">Regards,</p>
<p style="font-size: 13px;">Metvuong Team</p>
