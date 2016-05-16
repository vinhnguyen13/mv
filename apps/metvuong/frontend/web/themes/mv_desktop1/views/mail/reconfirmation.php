<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;

/**
 * @var dektrium\user\models\Token $token
 */
?>
<p style="font-size: 13px;margin-bottom: 10px;"><?= Yii::t('user', 'Hello') ?>,</p>
<p style="font-size: 13px;margin-bottom: 35px;line-height:20px;">
    <?= Yii::t('user', 'We have received a request to change the email address for your account on {0}', ['0' => Yii::$app->name]) ?>.
    <?= Yii::t('user', 'In order to complete your request, please click the link below') ?>.
</p>
<p style="font-size: 13px;margin-bottom: 35px;line-height:20px;">
    <?= Html::a(Html::encode($token->getUrl()), $token->getUrl()); ?>
</p>
<p style="font-size: 13px;margin-bottom: 35px;line-height:20px;">
    <?= Yii::t('user', 'If you cannot click the link, please try pasting the text into your browser') ?>.
</p>
<p style="font-size: 13px;margin-bottom: 35px;line-height:20px;">
    <?= Yii::t('user', 'If you did not make this request you can ignore this email') ?>.
</p>
