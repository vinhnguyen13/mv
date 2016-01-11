<?php
use yii\helpers\Url;
?>
<div class="infor-user-profile col-xs-3">
    <div class="inner-infor">
        <div class="wrap-infor-profile">

            <ul class="clearfix left-dashboard">
                <li><a class="active" href="#">Current Listing</a></li>
                <li>-</li>
                <li><a href="#">Pending Listing</a></li>
                <li>-</li>
            </ul>

            <div class="title-box hide">
                <h2>Trang cá nhân</h2>
            </div>
            <div class="clearfix list-profile hide">
                <div class="item-box">
                    <ul class="clearfix">
                        <li><a href="<?=Url::to(['user-management/chart'])?>" class="partial">Thống kê</a></li>
                    </ul>
                </div>
                <div class="item-box">
                    <div class="title-box">Bất động sản của bạn</div>
                    <ul class="clearfix">
                        <li><a href="<?=Url::to(['user-management/ad'])?>" class="partial">Quản lý tin rao bán/cho thuê</a></li>
                    </ul>
                </div>
                <div class="item-box">
                    <div class="title-box">Quản lý thông tin cá nhân</div>
                    <ul class="clearfix">
                        <li><a href="<?=Url::to(['user-management/profile'])?>" class="partial">Thay đổi thông tin cá nhân</a></li>
                        <li><a href="<?=Url::to(['user-management/password'])?>" class="partial">Thay đổi mật khẩu</a></li>
                    </ul>
                </div>
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
                        if(data){
                            $('.user_management .wrap-settings').html(data);
                            $('.right-profile').hide();
                            $('.right-profile').toggle( "slide" );
                        }
                    }
                });
            }, 500);
            return false;
        });
    });
</script>