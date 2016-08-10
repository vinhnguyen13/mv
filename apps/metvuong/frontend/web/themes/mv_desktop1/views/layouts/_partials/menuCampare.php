<?php
use yii\helpers\Url;
?>
    <div class="container">
        <div class="menuUser">
            <div class="listing-compare">
                <div class="title">Select Listing</div>
                <ul class="clearfix">
                    <li>
                        <a href="" class="active">Lê Thanh Tôn</a>
                        <a class="remove-compare" href=""><span class="icon-mv"><span class="icon-close-icon"></span></span></a>
                    </li>
                    <li>
                        <a href="">Lê Thanh Tôn</a>
                        <a class="remove-compare" href=""><span class="icon-mv"><span class="icon-close-icon"></span></span></a>
                    </li>
                    <li>
                        <a href="">Lê Thanh Tôn</a>
                        <a class="remove-compare" href=""><span class="icon-mv"><span class="icon-close-icon"></span></span></a>
                    </li>
                    <li>
                        <a href="">Lê Thanh Tôn</a>
                        <a class="remove-compare" href=""><span class="icon-mv"><span class="icon-close-icon"></span></span></a>
                    </li>
                    <li>
                        <a href="">Lê Thanh Tôn</a>
                        <a class="remove-compare" href=""><span class="icon-mv"><span class="icon-close-icon"></span></span></a>
                    </li>
                </ul>
            </div>
            <div class="option-choose-compare">
                <div class="title">Tùy chọn compare</div>
                <ul class="clearfix">
                    <li><label for="" class="checkbox-ui"><input type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span>Phòng ngủ</label></li>
                    <li><label for="" class="checkbox-ui"><input type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span>Phòng tắm</label></li>
                    <li><label for="" class="checkbox-ui"><input type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span>Diện tích</label></li>
                    <li><label for="" class="checkbox-ui"><input type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span>Giá</label></li>
                    <li><label for="" class="checkbox-ui"><input type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span>Tiện ích</label></li>
                    <li><label for="" class="checkbox-ui"><input type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span>Số tầng</label></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="title-fixed-wrap container">
        <div class="u-allduan">
            <div class="title-top">Compare Listing</div>
            <div class="compare-block">
                <div class="tbl-wrap w-60">
                    <div class="thead-row">
                        <div class="thead wrap-tr-each">
                            <div class="w-10"><span>ID</span></div>
                            <div class="w-15"><span>Ngày/Thời gian</span></div>
                            <div class="w-15"><span>Loại</span></div>
                            <div class="w-15"><span>Trạng thái</span></div>
                            <div class="w-15"><span>Số tiền</span></div>
                            <div class="w-30"><span>Lưu ý</span></div>
                        </div>
                    </div>
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="wrap-tr-each swiper-slide">
                                <div class="w-10"><span>1</span></div>
                                <div class="w-15"><span>01/07/2016, 12:13</span></div>
                                <div class="w-15"><span>Thêm Keys</span></div>
                                <div class="w-15"><span class="color-cd">Thành công</span></div>
                                <div class="w-15"><span>35 Keys</span></div>
                                <div class="w-30"><span>Thêm 35.00 Keys từ coupon bởi nhập mã I641J3</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"><span class=""></span></div>
                    <div class="swiper-button-prev"><span class=""></span></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('.option-choose-compare ul label').checkbox_ui();
            /*$('.menuReport li a').click(function (e) {
                $('body').loading();
                $('.menuUser li a').removeClass('active');
                $(this).addClass('active');
                window.history.pushState("object or string", "Title", $(this).attr('href'));
                if($(this).hasClass('wrapNotifyChat')){
                    $(document).trigger('chat/removeBoxChat');
                }
                $.ajax({
                    type: "get",
                    dataType: 'html',
                    url: $(this).attr('href'),
                    success: function (data) {
                        $('body').loading({done: true});
                        $('.contentContainer').html(data);
                        $(document).trigger('chat/listLoadDefault');
                        $(window).trigger('resize');
                    }
                });

                return false;
            });*/
        });
    </script>