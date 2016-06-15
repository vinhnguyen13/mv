<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 5/20/2016 9:09 AM
 */
use yii\helpers\Html;

$token = $params["token"];
?>

<p style="margin-bottom: 10px;font-size: 13px;">Kính chào <strong><?=$params["email"]?></strong>,</p>
<p style="font-size: 13px;margin-bottom: 15px;line-height:20px">Tin Đăng bất động sản của <strong><?=$params["email"]?></strong> đã được đăng tải trên website phát triển nhanh nhất về bất động sản tại Việt Nam, <a href="<?=Yii::$app->urlManager->hostInfo?>" style="font-weight: bold;text-decoration:none;color: #3c3c3c;">MetVuong.com</a>.
    Chúng tôi là cổng kết nối bất động sản được phát triển riêng cho các văn phòng đại diện, với các công cụ giúp anh/chị bán hoặc cho thuê nhanh hơn, dễ dàng hơn và hiệu quả hơn.</p>
<p style="font-size:13px;line-height:20px;margin-bottom:15px;">
    Bạn có thể tham khảo những tin đăng của mình qua những link ở dưới.
    <?php
    foreach($params["product_list"] as $id => $link){ ?>
        <br><b><?=$id?></b>: <a href="<?=$link?>" style="color:#009445;"><?=$link?></a>
    <?php }
//    if($params['rest_total'] > 0)
//        echo "và ".$params['rest_total']." tin đăng khác.";
    ?>
</p>

<p style="font-size:13px;margin-bottom: 15px;line-height:20px;">
    Chúng tôi đã tạo ra các công cụ có thể giúp bạn tìm những người khách hàng tiềm năng đang tìm kiếm, click và chia sẻ tin đăng của mình.
    Đăng kí ngay, và nhận được 1 triệu từ Metvuong (để dùng cho quảng cáo và đăng tin).
    <?= !empty($params["code"]) ? "Bạn chỉ cần tạo một tài khoản cá nhân và sử dụng mã khuyến mãi <b>" .$params["code"]. "</b>" : "" ?>
</p>

<p style="font-size:13px;margin-bottom: 15px;line-height:20px;">
    Vui lòng xem tài khoản tại link: <b><?= Html::a(Html::encode(Yii::$app->urlManager->hostInfo."/".$params["username"]), $token->url) ?></b>
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


