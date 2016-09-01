<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 5/20/2016 9:09 AM
 */
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Tracking;

$linkHref = Url::to(['/tracking/mail-click', 'rd'=>$link, 'c'=>$code, 'e'=>Tracking::MAIL_HOW_USE_DASHBOARD], true);
?>

<p style="margin-bottom: 10px;font-size: 13px;">Xin chào các Khách hàng thân thiết,</p>
<p style="font-size: 13px;margin-bottom: 15px;line-height:20px">
    Metvuong là trang web bất động sản được xây dựng nhằm kết nối giữa người mua/thuê và người bán/cho thuê, trong đó một tiện ích quan trọng của chúng tôi là cung cấp cho bạn những thông tin HỮU ÍCH và danh sách những NGƯỜI CẦN MUA/CẦN THUÊ TIỀM NĂNG NHẤT.
</p>
<table>
    <tbody>
    <tr>
        <td style="padding-right:10px;"><a href="<?=$linkHref;?>" style="color:#00a769;text-decoration:none;"><img src="<?=\yii\helpers\Url::to(['images/mail/dashboard/img-dash.jpg'], true)?>" alt="" style="width:100%"></a></td>
        <td style="padding-left:10px;"><a href="<?=$linkHref;?>" style="color:#00a769;text-decoration:none;"><img src="<?=\yii\helpers\Url::to(['images/mail/dashboard/img-popup-dash.jpg'], true)?>" alt="" style="width:100%"></a></td>
    </tr>
    <tr>
        <td style="text-align:center;font-size:13px;font-style:italic;">Bảng thống kê (Dashboard)</td>
        <td style="text-align:center;font-size:13px;font-style:italic;">Thống kê người dùng</td>
    </tr>
    </tbody>
</table>
<p style="font-size:13px;line-height:20px;margin-bottom:15px;">
    Và tiện ích trên được thể hiện qua công cụ Bảng thống kê (Dashboard) của chúng tôi. Bảng thống kê sẽ cho phép người dùng xem được tất cả liên hệ (như họ tên, email, số điện thoại…) của khách hàng đã tìm kiếm, yêu thích hoặc chia sẻ tin đăng của bạn, tất cả bạn chỉ cần làm là click vào danh sách để đảm bảo bạn đã liên hệ đúng khách hàng tiềm năng của mình.
</p>
<p style="font-size:13px;margin-bottom: 15px;line-height:20px;">
    Đặc biệt hơn, chúng tôi xin gởi đến bạn ưu đãi sử dụng thử MIỄN PHÍ các tiện ích của Bảng thống kê này trong 30 ngày.
</p>
<p style="font-size:13px;margin-bottom: 15px;line-height:20px;">
    Hãy thử ngay bây giờ bằng cách click vào đường dẫn bên dưới:<br/> <b><a href="<?=$linkHref;?>" style="color:#00a769;text-decoration:none;"><?=$link;?></a></b>
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


