<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/11/2015
 * Time: 2:34 PM
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

?>
<div class="col-xs-9 right-profile quanlytinraoban">
    <div class="wrap-quanly-profile">
        <div class="title-frm">Cập nhật thông tin của bạn</div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'change-pass-form',
                        'enableAjaxValidation'   => false,
                        'enableClientValidation' => true,
                        'layout' => 'horizontal',
                        'fieldConfig' => [
                            'horizontalCssClasses' => [
                                'wrapper' => 'col-sm-9',
                            ],
                        ],
                    ]); ?>
                        <?= $form->field($model, 'old_password')->textInput(['class'=>'form-control']) ?>
                        <?= $form->field($model, 'new_password')->textInput(['class'=>'form-control']) ?>
                        <div class="form-group">
                            <div class="col-lg-offset-3 col-lg-9">
                                <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-success']) ?>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
