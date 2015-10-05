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

/**
 * @var yii\web\View 					$this
 * @var dektrium\user\models\User 		$user
 * @var dektrium\user\models\Profile 	$profile
 */

?>
<?php $this->beginContent('@dektrium/user/views/admin/update.php', ['user' => $user]) ?>


<?= \dosamigos\fileupload\FileUploadUI::widget([
    'model' => $profile,
    'attribute' => 'avatar',
    'url' => ['media/upload', 'id' => $profile->user_id],
    'gallery' => false,
    'fieldOptions' => [
        'accept' => 'image/*'
    ],
    'clientOptions' => [
//        'maxFileSize' => 2000000
    ],
    // ...
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
        'fileuploadfail' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
    ],
]);
?>



<?=1
//$form->field($profile, 'avatar')->widget(\kartik\file\FileInput::classname(), [
//    'options' => ['multiple' => true, 'accept' => 'image/*'],
//    'pluginOptions' => [
//        'previewFileType' => 'image',
//        'uploadUrl' => \yii\helpers\Url::to(['/user/admin/update-profile?id=1']),
//        'uploadExtraData' => [
//            'album_id' => 20,
//            'cat_id' => 'Nature'
//        ],
//        'maxFileCount' => 1,
//    ]
//]);
?>

<?php $this->endContent() ?>