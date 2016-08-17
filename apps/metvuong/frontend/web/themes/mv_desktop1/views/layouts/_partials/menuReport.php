<?php
use yii\helpers\Url;
use common\components\Acl;

$permissionName = !empty(Yii::$app->setting->get('aclReport')) ? Yii::$app->setting->get('aclReport') : Acl::ACL_REPORT;
?>
<?php if(!Yii::$app->user->isGuest && Acl::me()->checkACL($permissionName)) { ?>
        <li>
            <a class="dashboard-item <?= !empty($this->params['menuDaily']) ? 'active' : ''; ?>" href="<?= Url::to(['/report/index']) ?>">
                <div><span class="icon-mv"><span class="icon-user-icon-profile1 fs-25"></span></span></div>
                <?= Yii::t('report', 'Daily') ?>
            </a>
        </li>
        <li>
            <a class="dashboard-item <?= !empty($this->params['menuMail']) ? 'active' : ''; ?>" href="<?= Url::to(['/report/mail']) ?>">
                <div><span class="icon-mv"><span class="icon-mail-profile fs-20"></span></span></div>
                <?= Yii::t('report', 'Mail') ?>
            </a>
        </li>
<?php }?>