<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 5/20/2016 9:09 AM
 */
use yii\helpers\Html;
?>

<p style="margin-bottom: 10px;font-size: 13px;">Kính chào <strong><?=$params["email"]?></strong>,</p>
<p style="font-size: 13px;margin-bottom: 15px;line-height:20px">
    Metvuong is designed to be a platform to connect buyers and sellers of real estate, part of this initative is to provide the sellers away to find RELEVANT and INTERESTED buyers/renters.
</p>
<p style="font-size:13px;line-height:20px;margin-bottom:15px;">
    This can be done through our Dashboard tool. Our Dashboard tool allows you to see as well as contact who has searched, liked, and shared your listings, allowing you to easily find buyers who are guaranteed to be interseted.
</p>
<p style="font-size:13px;margin-bottom: 15px;line-height:20px;">
    You will get to use this dashboard feature for FREE, for ONE MONTH, to see how this could help you in your search to find the right customer.
</p>
<p style="font-size:13px;margin-bottom: 15px;line-height:20px;">
    Click below try out the dashboard feature with your existing listings.: <b><?= Html::a('LINK', '/') ?></b>
</p>

<p style="font-size:13px;line-height:20px;margin-bottom:15px;">
    Nếu bạn cần thêm thông tin: <a style="color:#00a769;text-decoration:none;" href="mailto:contact@metvuong.com">contact@metvuong.com</a><br>
    <span style="color:red;">(*)</span> Đây là email tự động, xin đừng hồi âm cho địa chỉ email này.
</p>

<p style="font-size: 13px;margin-bottom:5px;">Trân trọng,</p>
<p style="font-size: 13px;line-height:20px;">
    <strong>Phòng Dịch Vụ Khách Hàng</strong><br>
    <a style="color:#00a769;text-decoration:none;" href="<?=Yii::$app->urlManager->hostInfo?>"><strong>MetVuong.com </strong></a><br>
</p>


