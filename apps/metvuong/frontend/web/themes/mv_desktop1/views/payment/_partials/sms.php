<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 6/16/2016
 * Time: 11:33 AM
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div id="sms" class="item-payment">
    <div class="title-item">Thanh toán bằng tin nhắn SMS</div>
    <div class="w-50 pdT-20">
        <div class="noti-pay">
            <p class="mgB-10"><span class="color-red d-ib pdR-10 font-700 text-decor">Lưu ý:</span>
                Giá trị tiền được cộng vào tài khoản cho khách hàng là giá trị sau khi trừ <b>35%</b> phí dịch vụ thanh toán cho nhà mạng và <b>10%</b> thuế VAT. Ví dụ, nạp thẻ 100.000đ sẽ được cộng 80.000đ vào tài khoản.
            </p>
            <div class="clearfix">
                <ul class="val-payment w-100">
                    <li><span>100,000vnd</span> = <span>54 keys</span></li>
                    <li><span>50,000vnd</span> = <span>27 keys</span></li>
                </ul>
            </div>
        </div>
        <div class="text-center">
            <p class="mgB-5">Soạn tin nhắn với cú pháp</p>
            <p class="mgB-5"><span class="color-cd font-700">TT MV</span> [Mã Thành Viên] [Số Tiền] gửi <strong>19001590</strong></p>
            <p>VD: TT MV 1234 10000 gửi 19001590</p>
        </div>
    </div>
</div>