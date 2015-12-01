<ul class="pull-right menu-home">
    <?php if(Yii::$app->user->isGuest){?>
        <li><a href="#" data-toggle="modal" data-target="#frmRegister"><em class="icon-user"></em>Đăng ký</a></li>
        <li><a href="#" data-toggle="modal" data-target="#frmLogin"><em class="icon-key"></em>Đăng nhập</a></li>
    <?php }else{?>
        <li><a data-method="post" href="<?=\yii\helpers\Url::to(['/site/logout'])?>"><em class="icon-user"></em><?=Yii::$app->user->identity->username;?></a></li>
    <?php }?>
    <li class="lang-icon icon-en"><a href="#"></a></li>
    <li class="lang-icon icon-vi"><a href="#"></a></li>
</ul>