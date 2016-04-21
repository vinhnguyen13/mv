<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<div class="password-reset">
    <p><a href="<?= $contact->domain ?>" target="_blank"><b>Metvuong.com </b></a><br><br>
        <a href="<?= $contact->detailUrl ?>" target="_blank"><?= $contact->address ?></a></p>
    <p><br> <?= Html::encode($contact->content)?></p>
    <p>Thanks for read mail!</p>
</div>
