<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 12/11/2015
 */

use frontend\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

?>
<style>
    .field-profile-form-avatar>div {
        margin-left: 0 !important;
    }
    .fileupload-buttonbar {
        position: absolute;
        bottom: -30px;
    }
</style>
<div class="col-xs-9 right-profile quanlytinraoban">
    <div class="wrap-quanly-profile">
        <div class="title-frm">Cập nhật thông tin của bạn</div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="alert alert-success hide"></div>
                    <?php $form = ActiveForm::begin([
                        'id' => 'change-profile-form',
                        'action' => Url::to(['/user-management/profile']),
                        'enableAjaxValidation' => false,
                        'enableClientValidation' => true,
                        'layout' => 'horizontal',
                        'fieldConfig' => [
                            'horizontalCssClasses' => [
                                'wrapper' => 'col-sm-9',
                            ],
                        ],
                    ]); ?>
                    <?= $form->field($model, 'name')->textInput(['class' => 'form-control name']) ?>
                    <?= $form->field($model, 'public_email')->textInput(['class' => 'form-control public_email']) ?>
                    <?= $form->field($model, 'phone')->textInput(['class' => 'form-control phone']) ?>
                    <?= $form->field($model, 'mobile')->textInput(['class' => 'form-control mobile']) ?>
                    <?= $form->field($model, 'address')->textInput(['class' => 'form-control address']) ?>
                    <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-9">
                            <?= Html::Button(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-success save']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="avatar" style="margin-bottom: 50px;">
                <?php $form = ActiveForm::begin([
                    'id' => 'change-avatar-form',
                    'enableAjaxValidation' => false,
                    'enableClientValidation' => true,
                    'layout' => 'horizontal',
                    'fieldConfig' => [
                        'horizontalCssClasses' => [
                            'wrapper' => 'col-sm-9',
                        ],
                    ],
                ]); ?>
                <?=Html::hiddenInput('deleteLater', '', ['id' => 'delete-later']);?>
                <?= $form->field($model, 'avatar')->widget(\common\widgets\FileUploadAvatar::className(), [
                        'url' => Url::to(['/user-management/avatar']),
                        'clientOptions' => ['maxNumberOfFiles' => 1],
                        'fieldOptions' => ['folder' => 'avatar'],
                    ])->label(false) ?>
                <?php ActiveForm::end(); ?>
            </div>
            <div>Member since: <?= Yii::$app->formatter->asDate(User::findIdentity(Yii::$app->user->id)->created_at, "php:d-m-Y");?></div>
            <br>
            <div>
                <?= $model->bio ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var timer = 0;
        $(document).on('click', '#change-profile-form .save', function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: $('#change-profile-form').attr('action'),
                    data: $('#change-profile-form').serializeArray(),
                    success: function (data) {
                        console.log(data);
                        if (data.statusCode == true) {
                            $('.panel-body .alert').text("Update profile success");
                            $('.panel-body .alert').removeClass("hide");
                            $('.panel-body .alert').show("slow");
                            console.log(data);
                        } else{
                            var strMessage = '';
                            $.each(data.parameters, function(idx, val){
                                var element = 'change-pass-form-'+idx;
                                strMessage += val;
                            });
                            $('.panel-body .alert').text(strMessage);
                            $('.panel-body .alert').removeClass("hide");
                            $('.panel-body .alert').show("slow");
                            console.log(data)
                        }
                    }
                });
            }, 500);
            return false;
        });

        $('#change-profile-form input').keypress(function (e) {
            if (e.which == 13) {
                //$('#change-profile-form .reset').click();
            }
        });

        $("#change-profile-form .phone").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        $("#change-profile-form .mobile").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        $('.panel-body .alert').click(function () {
            $(this).fadeOut(2000, function(){
                $(this).addClass("hide");
            });
        });

    });
</script>
