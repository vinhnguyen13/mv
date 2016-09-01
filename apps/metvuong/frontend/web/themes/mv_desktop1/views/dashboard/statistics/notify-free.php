<?php
	use vsoft\ec\models\EcStatisticView;
	use yii\helpers\Url;
?>
<div class="title-fixed-wrap">
    <div class="container">
        <div class="statis">
        	<div class="title-top">
                <?=Yii::t('statistic','Statistic')?>
            </div>
            <section class="clearfix mgB-40">
                <div class="wrap-img img-demo">
                    <table>
                        <tbody>
                            <tr>
                                <td><img src="/frontend/web/themes/mv_desktop1/resources/images/img-dash.jpg" alt=""></td>
                                <td><img src="/frontend/web/themes/mv_desktop1/resources/images/img-popup-dash.jpg" alt=""></td>
                            </tr>
                            <tr>
                                <td class="text-center fs-13 font-italic pdT-5">Bảng thống kê (Dashboard)</td>
                                <td class="text-center fs-13 font-italic pdT-5">Thống kê người dùng</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="fs-14 mgB-20">
                    <p class="mgB-10">
                    Bảng thống kê sẽ cho phép người dùng xem được tất cả liên hệ (như họ tên, email, số điện thoại…) của khách hàng đã tìm kiếm, yêu thích hoặc chia sẻ tin đăng của bạn, tất cả bạn chỉ cần làm là click vào danh sách để đảm bảo bạn đã liên hệ đúng khách hàng tiềm năng của mình.
                    </p>
                    <p>
                        Đặc biệt hơn, chúng tôi xin gởi đến bạn ưu đãi sử dụng thử <span class="font-600 fs-15">MIỄN PHÍ</span> các tiện ích của Bảng thống kê này trong <span class="font-600 fs-15">30 ngày</span>.
                    </p>
                </div>

                <div>
                    <button class="btn-common btn-bd-radius btn-small"><span class="icon-mv fs-17 mgR-10"><span class="icon-coin-dollar"></span></span>Nhận Keys</button>
                </div>
            </section>
        </div>
    </div>
</div>