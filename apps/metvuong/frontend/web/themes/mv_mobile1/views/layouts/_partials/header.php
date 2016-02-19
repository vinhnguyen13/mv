<?php
use yii\helpers\Url;
?>
<header class="clearfix header">
    <a href="#menu-header" id="menu-toggle" class="pull-left icon"></a>
    <a href="#settings-user" id="avatar-user" class="wrap-img">
        <?php if(Yii::$app->user->isGuest){?>
            <img src="/images/default-avatar.jpg" alt="">
        <?php } else{?>
            <img id="headAvatar" src="<?=Yii::$app->user->identity->profile->getAvatarUrl();?>" alt="">
<!--            <span id="notifyTotal"></span>-->
        <?php }?>
    </a>
    <div class="logo">
        <a href="<?=Url::home()?>">metvuong</a>
    </div>
    <div id="menu-header" class="menu-header">
        <div class="wrap-menu">
            <a href="#" id="hide-menu" class="icon"></a>
            <ul class="clearfix">
                <li><a href="<?= Url::to(['/ad/index']) ?>">mua</a></li>
                <li><a href="#">thuê</a></li>
                <li><a href="<?= Url::to(['/ad/post']) ?>">bán / cho thuê</a></li>
                <li><a href="<?=Url::to(['building-project/index']);?>">dự án mới</a></li>
                <li><a href="#">Tin tức</a></li>
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
                        <p><span class="name-user"><?=Yii::$app->user->identity->profile->getDisplayName();?></span>
                        <span class="address"><?=Yii::$app->user->identity->profile->address?></span></p>
                    </div>
                </a>
            </li>
            <!-- <li><a href="<?= Url::to(['/ad/post']) ?>"><em class="icon-plus"></em>Đăng tin mới</a></li> -->
            <li><a href="<?=Url::to(['/dashboard/notification'])?>" id="wrapNotifyOther"><em class="icon-bell"></em>Thông báo<!--<span id="notifyOther">5</span>--></a></li>
            <li><a href="<?=Url::to(['/dashboard/ads'])?>"><em class="icon-docs"></em>Listings</a></li>
            <li><a href="<?=Url::to(['/chat'])?>" id="wrapNotifyChat"><em class="icon-bubbles"></em>Chat<!--<span id="notifyChat">5</span>--></a></li>
            <li><a data-method="post" href="<?=Url::to(['/member/logout'])?>"><em class="icon-power"></em>Đăng xuất</a></li>
        </ul>
        <?php } ?>
    </div>
</header>