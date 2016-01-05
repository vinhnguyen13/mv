<?php
use yii\helpers\Url;
?>
<div class="pull-right wrap-menu-option">
<ul class="menu-home">
    <?php if(Yii::$app->user->isGuest){?>
        <li><a href="#" data-toggle="modal" data-target="#frmRegister"><em class="icon-user"></em>Đăng ký</a></li>
        <li><a href="#" data-toggle="modal" data-target="#frmLogin"><em class="icon-key"></em>Đăng nhập</a></li>
        <li class="lang-icon icon-en"><a href="<?=Url::current(['language-change'=>'en-US'])?>"></a></li>
        <li class="lang-icon icon-vi"><a href="<?=Url::current(['language-change'=>'vi-VN'])?>"></a></li>
    <?php }else{?>
        <li class="user-loggin"><a href="<?=Url::to(['user-management/index'])?>">
                <span class="avatar-user"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/default-avatar.jpg" alt="" width="20" height="20"></span>
                <span class="name-user"><?=!empty(Yii::$app->user->identity->profile->name) ? Yii::$app->user->identity->profile->name : Yii::$app->user->identity->email;?></span>
            </a>
        </li>
        <li>
            <a href="#" class="sub-setting-user"><em class="fa fa-chevron-down"></em></a>
            <div class="settings container-effect hidden-effect">
                <ul class="sub-setting wrap-effect">
                    <li>
                        <a data-method="post" href="<?=\yii\helpers\Url::to(['/member/logout'])?>"><em class="icon-logout pull-right"></em><?=Yii::t('user', 'Logout')?></a>
                    </li>
                    <li>
                        <a href="<?=Url::to(['user-management/index'])?>"><em class="fa fa-cog pull-right"></em>Settings</a>
                    </li>
                    <li class="line-option">
                        <div class="select-lang">
                            <a href="<?=Url::current(['language-change'=>'en-US'])?>" class="lang-icon icon-en pull-right"></a>
                            <a href="<?=Url::current(['language-change'=>'vi-VN'])?>" class="lang-icon icon-vi pull-right"></a>
                            Language
                        </div>
                    </li>
                </ul>
            </div>
        </li>
    <?php }?>
    
</ul>
</div>