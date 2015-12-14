<?php
use yii\helpers\Url;
?>
<div class="col-xs-3 infor-user-profile">
    <div class="inner-infor">
        <div class="title-box">
            <h2>Trang cá nhân</h2>
        </div>
        <div class="clearfix list-profile">
            <div class="item-box mgB-15">
                <ul class="clearfix">
                    <li><a href="<?=Url::to(['user-management/chart'])?>" class="partial">Thống kê</a></li>
                </ul>
            </div>
            <div class="item-box mgB-15">
                <div class="title-box">Bất động sản của bạn</div>
                <ul class="clearfix">
                    <li><a href="<?=Url::to(['user-management/ads'])?>" class="partial">Quản lý tin rao bán/cho thuê</a></li>
                    <li><a href="#">Đăng tin rao bán/cho thuê</a></li>
                    <!--                        <li><a href="#">Quản lý tin nháp</a></li>-->
                </ul>
            </div>
            <div class="item-box mgB-15">
                <div class="title-box">Quản lý thông tin cá nhân</div>
                <ul class="clearfix">
                    <li><a href="<?=Url::to(['user-management/profile'])?>" class="partial">Thay đổi thông tin cá nhân</a></li>
                    <li><a href="<?=Url::to(['user-management/profile'])?>" class="partial">Thay đổi mật khẩu</a></li>
                </ul>
            </div>
            <div class="item-box mgB-15">
                <div class="title-box">Công cụ</div>
                <ul class="clearfix">
                    <li><a href="#">Phân tích - thống kê</a></li>
                    <li><a href="#">Nạp tiền</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        var timer = 0;
        $(document).on('click', '.list-profile li a.partial', function(){
            var _this = $(this);
            clearTimeout(timer);
            timer = setTimeout(function() {
                $.ajax({
                    type: "get",
                    dataType: 'html',
                    url: _this.attr('href'),
                    success: function(data) {
                        console.log(data);
                        $('.right-profile').replaceWith(data);
                    }
                });
            }, 500);
            return false;
        });
    });
</script>