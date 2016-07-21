<?php
use yii\helpers\Html;
?>
<a href="<?=\yii\helpers\Url::home(true)?>" style="text-decoration: none;"><p style="color: #00a769;font-size: 16px;font-weight: bold;margin-bottom: 15px;">Thông báo từ metvuong.com</p></a>
<p style="margin-bottom: 10px;"><strong style="color: #222;font-size: 13px;">Xin chào, <?= $contact['recipient_email'] ?>!</strong></p>
<p style="font-size: 13px;margin-bottom: 35px;line-height:20px">
    Tin của bạn đã được chúng tôi đăng trên <a href="<?=\yii\helpers\Url::home(true)?>" style="text-decoration: none;">Metvuong.com</a>,
    một trong những người sử dụng <a href="<?=\yii\helpers\Url::home(true)?>" style="text-decoration: none;">Metvuong.com</a> đang muốn liên hệ về <?=$contact['category']?> của bạn.
    Họ có những câu hỏi như sau:
</p>

<p style="padding-left: 40px;font-size: 13px;margin-bottom: 35px;line-height:20px"><?= str_replace(PHP_EOL, "<br>", $contact['content'])?></p>

<?php if( !empty($profile_url)){ ?>
<p style="font-size: 13px;margin-bottom: 35px;line-height:20px">
    Chúng tôi đã tạo một tài khoản MetVuong cho bạn rồi. Để liên lạc với họ, bạn chỉ cần đang nhập vào <a href="<?=\yii\helpers\Url::to(['member/login'])?>" style="text-decoration: none;">Metvuong.com</a>.
    <?php if( !empty($token_url)){ ?>
        Hoặc ấn vào link sau đây: <b><?= Html::a(Html::encode($profile_url), $token_url) ?></b>
    <?php }else{ ?>
        Hoặc bạn tìm lại mật khẩu <a href="<?=\yii\helpers\Url::to(['member/forgot'])?>" style="text-decoration: none;">tại đây</a> với email <?= $contact['recipient_email'] ?>
    <?php } ?>
</p>
<?php } ?>

<table style="margin-bottom: 30px;">
    <tbody><tr>
        <td style="vertical-align:top;">
            <a href="<?= $contact['detailUrl'] ?>"><img style="width:200px;height:auto;" src="<?=$contact['imageUrl']?>"></a>
        </td>
        <td style="vertical-align:top;padding-left:10px;">
            <a style="display:block;font-size:16px;font-weight:bold;margin:0 0 10px 0;color:#009455;text-decoration:none;" href="<?= $contact['detailUrl'] ?>"><?=$contact['address']?></a>
            <p style="margin:0 0 10px 0;font-size:13px;text-transform:uppercase;"><?=$contact['category']?></p>

            <?php if(!empty($contact['pid'])){?>
            <p style="font-size:13px;margin:0 0 10px 0;"><span style="font-weight:bold;">Mã tin: </span><?=Yii::$app->params['listing_prefix_id'].$contact['pid']?> </p>
            <?php } ?>

            <p style="font-size:13px;margin:0 0 10px 0;">
                <?php if(!empty($contact['area']) && intval($contact['area']) > 0){?>
                    <span style="font-weight:bold;">Diện tích: </span><?=$contact['area']?> m<sup>2</sup>
                <?php }
                if(!empty($contact['room_no']) && intval($contact['room_no']) > 0){?>
                    <span style="font-weight:bold;">Phòng ngủ: </span><?=$contact['room_no']?>
                <?php }
                if(!empty($contact['toilet_no']) && intval($contact['toilet_no']) > 0){?>
                    <span style="font-weight:bold;">Phòng tắm: </span><?=$contact['toilet_no']?>
                <?php } ?>
            </p>

            <?php if(!empty($contact['price'])) { ?>
            <p style="border:1px solid #009455;display:inline-block;padding: 7px 15px;font-size:16px;font-weight:bold;line-height:100%;margin:5px 0 0 0;">Giá: <?=$contact['price']?></p>
            <?php } ?>
        </td>
    </tr>
    </tbody>
</table>
<?php if(!empty($contact['detailUrl'])){ ?>
<p style="margin-bottom: 45px;">Chi tiết xem <a style="font-size:13px;font-weight: bold;text-decoration:none;color:#009455;" href="<?= $contact['detailUrl'] ?>">tại đây.</a></p>
<?php } ?>
<p style="font-size: 13px;margin-bottom:5px;">Trân trọng,</p>
<p style="font-size: 13px;">Metvuong Team</p>
