<?php
use yii\helpers\Url;
?>
<div class="menuUser">
    <ul class="clearfix">
        <li><a href="<?=Url::to(['/dashboard/ad', 'username'=> Yii::$app->user->identity->username])?>"><div><span class="icon icon-listings"></span></div><?=Yii::t('ad', 'Listings')?></a></li>
        <li>
            <a href="<?=Url::to(['/notification/index', 'username'=> Yii::$app->user->identity->username])?>" class="wrapNotifyOther">
                <div><span class="icon icon-alert"></span>
                    <?php if(!empty($this->params['notify_other'])){?>
                        <span id="notifyOther" class="notifi"><?=$this->params['notify_other'];?></span>
                    <?php }?>
                </div><?=Yii::t('activity', 'Notification')?>
            </a>
        </li>
        <li>
            <a href="<?=Url::to(['/chat/index', 'username'=> Yii::$app->user->identity->username])?>" class="wrapNotifyChat">
                <div>
                    <span class="icon icon-chat"></span>
                    <?php if(!empty($this->params['notify_chat'])){?>
                        <span id="notifyChat" class="notifi"><?=$this->params['notify_chat'];?></span>
                    <?php }?>
                </div><?=Yii::t('chat', 'Chat')?>
            </a>
        </li>
        <li><a data-method="post" href="<?=Url::to(['member/update-profile', 'username'=>Yii::$app->user->identity->username])?>"><div><span class="icon icon-settings"></span></div><?=Yii::t('user', 'Setting')?></a></li>

    </ul>
</div>