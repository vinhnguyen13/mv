<?php
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Json;
use vsoft\ad\models\AdProduct;
use frontend\models\UserActivity;
use yii\helpers\Html;
?>
<div class="title-fixed-wrap container">
    <div class="giao-dich">
        <div class="title-top">Giao Dịch</div>
        <div class="wrap-giao-dich">
            <div class="title-gd mgB-15">Thông tin tài khoản</div>
            <div class="mgB-30">
                Số Gold Còn Lại: <span class="d-ib mgL-20 font-700"><span class="icon-mv mgR-5 color-gold fs-20"><span class="icon-coin-dollar"></span></span>10 Gold</span>
                <a href="#" class="d-ib btn mgL-20 pdT-5 pdB-5 font-600 fs-13">Nạp Gold</a>
            </div>
            <div class="title-gd mgB-5">Giao dịch gần đây</div>
            <table class="fixed_headers" cellspacing="0" cellpadding="0" border="0">
                <thead>
                    <tr>
                        <th class="w-10"><span>Mã GD</span></th>
                        <th class="w-20"><span>Ngày/Giờ</span></th>
                        <th class="w-20"><span>Tiêu đề</span></th>
                        <th class="w-20"><span>Loại giao dịch</span></th>
                        <th class="w-10"><span>Tình trạng</span></th>
                        <th class="w-20"><span>Số tiền</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1234</td>
                        <td>08/06/2016 10:15:00 AM</td>
                        <td><a href="#" class="color-cd">The Lancaster</a></td>
                        <td>Up</td>
                        <td class="color-cd">Thành công</td>
                        <td>20.000 VND</td>
                    </tr>
                    <tr>
                        <td>1234</td>
                        <td>08/06/2016 10:15:00 AM</td>
                        <td><a href="#" class="color-cd">The Lancaster</a></td>
                        <td>Up</td>
                        <td class="color-red">Thất bại</td>
                        <td>20.000 VND</td>
                    </tr>
                    <tr>
                        <td>1234</td>
                        <td>08/06/2016 10:15:00 AM</td>
                        <td><a href="#" class="color-cd">The Lancaster</a></td>
                        <td>Up</td>
                        <td class="color-cd">Thành công</td>
                        <td>20.000 VND</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>