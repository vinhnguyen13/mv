<?php

?>
<div class="pull-right wrap-menu-option">
    <ul class="pull-right menu-home">
        <?php if(Yii::$app->user->isGuest){?>
            <li><a href="#" data-toggle="modal" data-target="#frmRegister"><em class="icon-user"></em>Đăng ký</a></li>
            <li><a href="#" data-toggle="modal" data-target="#frmLogin"><em class="icon-key"></em>Đăng nhập</a></li>
        <?php }else{?>
            <li><a data-method="post" href="<?=\yii\helpers\Url::to(['/member/logout'])?>">
                    <em class="icon-user"></em>
                    <?=!empty(Yii::$app->user->identity->profile->name) ? Yii::$app->user->identity->profile->name : Yii::$app->user->identity->email;?>
            </a></li>
        <?php }?>
        <li class="lang-icon icon-en"><a href="<?=\yii\helpers\Url::current(['language-change'=>'en-US'])?>"></a></li>
        <li class="lang-icon icon-vi"><a href="<?=\yii\helpers\Url::current(['language-change'=>'vi-VN'])?>"></a></li>
    </ul>
</di>