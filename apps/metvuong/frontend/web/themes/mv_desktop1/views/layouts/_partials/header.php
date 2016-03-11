<?php
use yii\helpers\Url;
?>
<header class="clearfix header">
    <div class="container wrap-header">
        <div class="m-header">
            <a href="#menu-header" id="menu-toggle" class="pull-left"><span class="icon"></span></a>
            <a href="#settings-user" id="avatar-user" class="wrap-img">
                <?php if(Yii::$app->user->isGuest){?>
                    <img src="/images/default-avatar.jpg" alt="">
                <?php } else{?>
                    <img id="headAvatar" src="<?=Yii::$app->user->identity->profile->getAvatarUrl();?>" alt="">
                    <?php if(!empty($this->params['notify_total'])){?>
                        <span id="notifyTotal"><?=$this->params['notify_total'];?></span>
                    <?php }?>
                <?php }?>
            </a>
            <div class="logo">
                <a href="<?=Url::home()?>">metvuong</a>
            </div>
            <div id="menu-header" class="menu-header">
                <div class="wrap-menu">
                    <a href="#" id="hide-menu" class="icon"></a>
                    <ul class="clearfix">
                        <li><a href="<?= Url::to(['/ad/index']) ?>"><div><span class="icon icon-search"></span></div>cần mua</a></li>
                        <li><a href="#"><div><span class="icon icon-search"></span></div>cần thuê</a></li>
                        <li><a href="<?= Url::to(['/ad/post']) ?>"><div><span class="icon icon-key"></span></div>đăng bán / cho thuê</a></li>
                        <li><a href="<?=Url::to(['building-project/index']);?>"><div><span class="icon icon-home"></span></div>dự án mới</a></li>
                        <li><a href="<?=Url::to(['news/index']);?>"><div><span class="icon icon-news"></span></div>Tin tức</a></li>
                        <li><a href="<?=Url::to(['/payment/package'])?>"><div><span class="icon icon-tags"></span></div>Các gói giá</a></li>
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
                                <span class="address"><?=empty(Yii::$app->user->identity->location) ? "" : Yii::$app->user->identity->location->city?></span></p>
                            </div>
                        </a>
                    </li>
                    <!-- <li><a href="<?= Url::to(['/ad/post']) ?>"><em class="icon-plus"></em>Đăng tin mới</a></li> -->
                    <li><a href="<?=Url::to(['/notification/index', 'username'=> Yii::$app->user->identity->username])?>" id="wrapNotifyOther"><div><span class="icon icon-alert"></span></div>Thông báo
                            <?php if(!empty($this->params['notify_other'])){?>
                                <span id="notifyOther" class="notifi"><?=$this->params['notify_other'];?></span>
                            <?php }?>
                        </a></li>
                    <li><a href="<?=Url::to(['/dashboard/ad', 'username'=> Yii::$app->user->identity->username])?>"><div><span class="icon icon-listings"></span></div>Listings</a></li>
                    <li><a href="<?=Url::to(['/chat/index', 'username'=> Yii::$app->user->identity->username])?>" id="wrapNotifyChat"><div><span class="icon icon-chat"></span></div>Chat
                            <?php if(!empty($this->params['notify_chat'])){?>
                                <span id="notifyChat" class="notifi"><?=$this->params['notify_chat'];?></span>
                            <?php }?>
                    </a></li>
                    <li><a data-method="post" href="<?=Url::to(['member/update-profile', 'username'=>Yii::$app->user->identity->username])?>"><div><span class="icon icon-per"></span></div>TÀI KHOẢN</a></li>
                    <li><a data-method="post" href="<?=Url::to(['/member/logout'])?>"><div><span class="icon icon-logout"></span></div>Đăng xuất</a></li>
                </ul>
                <?php } ?>
            </div>
        </div>

        <div class="dt-header clearfix">
            <div class="user-login pull-right">
                <a href="#">Đăng nhập</a> <span>/</span> <a href="#">Đăng ký</a>
            </div>
            <ul class="clearfix list-menu">
                <li class="dt-logo"><a href="/" class="wrap-img"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/logo.png' ?>" alt=""></a></li>
                <li><a href="#">CẦN MUA</a></li>
                <li><a href="#">CẦN THUÊ</a></li>
                <li><a href="#">BÁN/CHO THUÊ</a></li>
                <li><a href="#">DỰ ÁN MỚI</a></li>
                <li><a href="#">TIN TỨC</a></li>
            </ul>
        </div>
    </div>
</header>