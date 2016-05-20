<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 5/20/2016 9:09 AM
 */
?>

<p style="margin-bottom: 10px;font-size: 13px;">Chào bạn <strong><?=$params["email"]?></strong>,</p>
<p style="font-size: 13px;margin-bottom: 15px;line-height:20px"><a href="#" style="font-weight: bold;text-decoration:none;color: #3c3c3c;">MetVuong.com</a> tìm được tin đăng về bất động sản của bạn và đã giúp bạn đăng tin này trên trang mạng của chúng tôi. Để xem tin đăng, bạn có thể thực hiện một trong các cách sau:</p>
<p style="font-size:13px;line-height:20px;margin-bottom:15px;">
    Click vào link này (hoặc dán link vào trình duyệt):<br>
    <?php
    foreach($params["product_list"] as $id => $link){ ?>
        <b><?=$id?></b>: <a href="<?=$link?>" style="color:#009445;"><?=$link?></a><br>
    <?php }
    if($params['rest_total'] > 0)
        echo "và ".$params['rest_total']." tin đăng khác.";
    ?>
</p>

<p style="font-size:13px;margin-bottom: 15px;line-height:20px;">
    Vui lòng xem tài khoản tại link: <a href="<?=Yii::$app->urlManager->hostInfo?>/<?=$params["username"]?>" style="color:#009445;text-decoration:none;"><?=Yii::$app->urlManager->hostInfo?>/<?=$params["username"]?></a>
</p>

<p style="font-size:13px;margin-bottom: 15px;line-height:20px;">
    Ngoài ra, MetVuong đã tạo tài khoản cá nhân để bạn sử dụng đăng các tin khác và hoàn toàn MIỄN PHÍ!
</p>

<p style="font-size:13px;line-height:20px;margin-bottom:15px;">
    Xin vui lòng liên lạc với chúng tôi nếu bạn cần thêm thông tin.<br>
    <span style="color:red;">(*)</span> Đây là email tự động, xin đừng hồi âm cho địa chỉ email này.
</p>

<p style="font-size: 13px;margin-bottom:5px;">Trân trọng,</p>
<p style="font-size: 13px;line-height:20px;">
    <strong>Phòng Dịch Vụ Khách Hàng</strong><br>
    <a style="color:#00a769;text-decoration:none;" href="<?=Yii::$app->urlManager->hostInfo?>">metvuong.com</a><br>
    <a style="color:#00a769;text-decoration:none;" href="mailto:contact@metvuong.com">contact@metvuong.com</a>
</p>


