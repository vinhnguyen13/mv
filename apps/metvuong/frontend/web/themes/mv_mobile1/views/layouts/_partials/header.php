<?php
use yii\helpers\Url;
?>
<header class="clearfix header">
    <a href="#menu-header" id="menu-toggle" class="pull-left icon"></a>
    <a href="#settings-user" id="avatar-user" class="wrap-img"><img src="/images/default-avatar.jpg" alt=""><span>10</span></a>
    <div class="logo">
        <a href="/">metvuong</a>
    </div>
    <div id="menu-header" class="menu-header">
        <div class="wrap-menu">
            <a href="#" id="hide-menu" class="icon"></a>
            <ul class="clearfix">
                <li><a href="<?= Url::to(['/ad/index']) ?>">mua</a></li>
                <li><a href="#">thuê</a></li>
                <li><a href="<?= Url::to(['/ad/post']) ?>">bán / cho thuê</a></li>
                <li><a href="<?=Url::to(['building-project/index']);?>">dự án mới</a></li>
            </ul>
        </div>
    </div>

    <div id="settings-user" class="settings-user">
        <a href="#" id="hide-settings" class="icon"></a>
        <?php if(Yii::$app->user->isGuest){?>
            <ul class="clearfix">
                <li><a href="<?=Url::to(['member/login'])?>">Đăng nhập</a></li>
                <li><a href="<?=Url::to(['member/signup'])?>">Đăng ký</a></li>
            </ul>
        <?php } else{?>
        <ul class="clearfix">
            <li class="user-edit">
                <a href="<?=Url::to(['member/profile', 'username'=>Yii::$app->user->identity->username])?>">
                    <span class="wrap-img"><img src="<?=Yii::$app->user->identity->profile->getAvatarUrl();?>" alt="" width="40" height="40"></span>
                    <div>
                        <span class="name-user"><?=Yii::$app->user->identity->profile->getDisplayName();?></span>
                        <span class="address"><?=Yii::$app->user->identity->profile->address?></span>
                    </div>
                </a>
            </li>
            <li><a href="<?= Url::to(['/ad/post']) ?>"><em class="icon-plus"></em>Đăng tin mới</a></li>
            <li><a href="<?=Url::to(['/dashboard/ads'])?>"><em class="icon-docs"></em>Tất cả dự án</a></li>
            <li><a href="<?=Url::to(['/dashboard/statistics'])?>"><em class="icon-bar-chart"></em>Statistics</a></li>
            <li><a href="<?=Url::to(['/chat'])?>"><em class="icon-bubbles"></em>Chat <span>5</span></a></li>
            <li><a href="<?=Url::to(['/dashboard/notification'])?>"><em class="icon-bell"></em>Notification <span>5</span></a></li>
            <li><a data-method="post" href="<?=Url::to(['/member/logout'])?>"><em class="icon-power"></em>Đăng xuất</a></li>
        </ul>
        <?php } ?>
    </div>
</header>