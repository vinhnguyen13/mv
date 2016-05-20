<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 5/20/2016 9:09 AM
 */
Yii::$app->view->title = "Marketing Contact Email";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Marketing Email</title>
    <style>
        *{margin:0;padding:0;}
        body {
            font-family: arial;
            color: #3c3c3c;
        }
    </style>
</head>
<body>
<table width="630" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto 0;">
    <tbody><tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody><tr>
                    <td style="background: #00a769;padding: 10px 30px;">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tbody><tr>
                                <td><a href="#"><img style="width: 150px;" alt="" src="images/logo-white.png"></a></td>
                                <td style="text-align:right; color: #fff;font-size: 14px;font-weight: bold;">Kênh mua sắm bất động sản hàng đầu!</td>
                            </tr>
                            </tbody></table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px 30px;border: 1px solid #e7e7e7;">

                        <p style="margin-bottom: 10px;font-size: 13px;">Chào bạn <strong><?=$params["email"]?></strong></p>
                        <p style="font-size: 13px;margin-bottom: 15px;line-height:20px"><a href="#" style="font-weight: bold;text-decoration:none;color: #3c3c3c;">MetVuong.com</a> tìm được tin đăng về bất động sản của bạn và đã giúp bạn đăng tin này trên trang mạng của chúng tôi. Để xem tin đăng, bạn có thể thực hiện một trong các cách sau:</p>
                        <p style="font-size:13px;line-height:20px;margin-bottom:15px;">
                            Click vào link này (hoặc dán link vào trình duyệt):
                            <?php
                            foreach($params["product_list"] as $id => $link){ ?>
                                <?=$id?>: <a href="<?=$link?>" style="color:#009445;"><?=$link?></a>
                            <?php }
                            ?>
                        </p>
                        <p style="font-size:13px;margin-bottom: 15px;line-height:20px;">
                            Ngoài ra, MetVuong đã tạo tài khoản cá nhân để bạn sử dụng đăng các tin khác và hoàn toàn MIỄN PHÍ!
                        </p>
                        <p style="font-size:13px;margin-bottom: 15px;line-height:20px;">
                            Vui lòng xem tài khoản tại link: <a href="<?=$params["profile_link"]?>" style="color:#009445;"><?=$params["profile_link"]?></a>
                        </p>

                        <p style="font-size:13px;line-height:20px;margin-bottom:15px;">
                            Xin vui lòng liên lạc với chúng tôi nếu bạn cần thêm thông tin.<br>
                            <span style="color:red;">(*)</span> Đây là email tự động, xin đừng hồi âm cho địa chỉ email này.
                        </p>


                        <p style="font-size: 13px;margin-bottom:5px;">Trân trọng,</p>
                        <p style="font-size: 13px;line-height:20px;">
                            <strong>Phòng Dịch Vụ Khách Hàng</strong><br>
                            <a style="color:#00a769;" href="#">metvuong.com</a><br>
                            <a style="color:#00a769;" href="#">contact@metvuong.com</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f1efef;padding: 10px 30px;font-size: 11px;">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tbody><tr>
                                <td>
                                    <p style="margin-bottom: 5px;"><strong>Metvuong Team</strong></p>
                                    <p style="margin-bottom: 5px;">21 Nguyễn Trung Ngạn, phường Bến Nghé, Q.1, Hồ Chí Minh</p>
                                    <p>(08) 789 456    |	support@metvuong.com</p>
                                </td>
                                <td style="text-align: right;">
                                    <a href="#" style="display:inline-block;margin-right:20px;"><img width="40" alt="" src="images/icon-face.png"></a>
                                    <a href="#"><img width="40" alt="" src="images/icon-go.png"></a>
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
    </tbody></table>
</body>
</html>
