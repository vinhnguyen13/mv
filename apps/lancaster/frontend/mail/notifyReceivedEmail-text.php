<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
Hello <?= $isbooking === 'Y' ? $contact->fullname : $contact->name ?> ,

We are received your <?= $isbooking === 'Y' ? 'booking' : 'contact' ?> and reply to you later.

Thank you!
