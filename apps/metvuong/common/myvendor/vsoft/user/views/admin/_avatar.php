<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use common\widgets\FileUploadUI;
use yii\helpers\Url;

/**
 * @var yii\web\View 					$this
 * @var dektrium\user\models\User 		$user
 * @var dektrium\user\models\Profile 	$profile
 */

?>
<?php $this->beginContent('@dektrium/user/views/admin/update.php', ['user' => $user]) ?>
<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-9',
        ],
    ],
]); ?>
<?=Html::hiddenInput('deleteLater', '', ['id' => 'delete-later']);?>
<?= $form->field($profile, 'avatar')->widget(FileUploadUI::className(), [
    'url' => Url::to(['/express/upload/image', 'folder'=>'avatar']),
    'clientOptions' => ['maxNumberOfFiles' => 1],
    'fieldOptions' => ['folder'=>'avatar'],
]) ?>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-2">
        <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-block btn-success']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>