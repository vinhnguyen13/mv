<?php
use yii\helpers\Url;
?>
<div class="menuUser">
    <ul class="clearfix">
        <li>
            <a href="<?=Url::to(['/dashboard/ad', 'username'=> Yii::$app->user->identity->username])?>" class="<?=!empty($this->params['menuDashboard']) ? 'active' : '' ;?>">
                <div>
                    <span class="wrap-icon-svg"><svg class="icon-svg icon-listing-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-listing-svg"></use></svg></span>
                </div>
                <?=Yii::t('ad', 'Listings')?>
            </a>
        </li>
        <li>
            <a href="<?=Url::to(['/notification/index', 'username'=> Yii::$app->user->identity->username])?>" class="wrapNotifyOther <?=!empty($this->params['menuNotification']) ? 'active' : '' ;?>">
                <div>
                    <span class="wrap-icon-svg"><svg class="icon-svg icon-bell-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-bell-svg"></use></svg></span>
                    <?php if(!empty($this->params['notify_other'])){?>
                        <span id="notifyOther" class="notifi"><?=$this->params['notify_other'];?></span>
                    <?php }?>
                </div>
                <?=Yii::t('activity', 'Notification')?>
            </a>
        </li>
        <li>
            <a href="<?=Url::to(['/chat/index', 'username'=> Yii::$app->user->identity->username])?>" class="wrapNotifyChat  <?=!empty($this->params['menuChat']) ? 'active' : '' ;?>">
                <div>
                    <span class="wrap-icon-svg"><svg class="icon-svg icon-chat-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chat-svg"></use></svg></span>
                    <?php if(!empty($this->params['notify_chat'])){?>
                        <span id="notifyChat" class="notifi"><?=$this->params['notify_chat'];?></span>
                    <?php }?>
                </div><?=Yii::t('chat', 'Chat')?>
            </a>
        </li>
        <li><a href="<?=Url::to(['member/update-profile', 'username'=>Yii::$app->user->identity->username])?>" class="<?=!empty($this->params['menuUpdateProfile']) ? 'active' : '' ;?>"><div><span class="icon icon-settings"></span></div><?=Yii::t('user', 'Setting')?></a></li>
                <div>
                    <span class="wrap-icon-svg"><svg class="icon-svg icon-setting-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-setting-svg"></use></svg></span>
                </div>
                <?=Yii::t('user', 'Setting')?>
            </a>
        </li>
    </ul>
</div>