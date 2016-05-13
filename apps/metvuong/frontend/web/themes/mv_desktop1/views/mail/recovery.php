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
 * @var dektrium\user\models\User  $user
 * @var dektrium\user\models\Token $token
 */
?>

<p style="font-size: 13px;margin-bottom: 10px;"><?= Yii::t('user', 'Hello') ?> <strong style="color: #222;font-size: 13px;"><?=$user->email?></strong>,</p>

<p style="font-size: 13px;margin-bottom: 35px;line-height:20px">
    <?= Yii::t('user', 'We have received a request to reset the password for your account on {0}', Yii::$app->name) ?>.
    <?= Yii::t('user', 'Please click the link below to complete your password reset') ?>.
</p>
<p style="font-size: 13px;margin-bottom: 35px;line-height:20px">
    <?= Html::a(Html::encode($token->url), $token->url); ?>
</p>
<p style="font-size: 13px;margin-bottom: 35px;line-height:20px">
    <?= Yii::t('user', 'If you cannot click the link, please try pasting the text into your browser') ?>.
</p>
<p style="font-size: 13px;margin-bottom: 35px;line-height:20px">
    <?= Yii::t('user', 'If you did not make this request you can ignore this email') ?>.
</p>

