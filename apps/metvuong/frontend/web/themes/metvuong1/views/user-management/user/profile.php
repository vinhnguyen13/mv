<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/11/2015
 * Time: 2:34 PM
 */

use frontend\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

?>
<div class="col-xs-9 right-profile quanlytinraoban">
    <div class="wrap-quanly-profile">
        <div class="title-frm">Cập nhật thông tin của bạn</div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="alert hide"></div>
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
                    <?= $form->field($model, 'name')->textInput(['class' => 'form-control']) ?>
                    <?= $form->field($model, 'public_email')->textInput(['class' => 'form-control']) ?>
                    <?= $form->field($model, 'phone')->textInput(['class' => 'form-control']) ?>
                    <?= $form->field($model, 'mobile')->textInput(['class' => 'form-control']) ?>
                    <?= $form->field($model, 'address')->textInput(['class' => 'form-control']) ?>
                    <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-9">
                            <?= Html::Button(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-success save']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var timer = 0;
        $(document).on('click', '#change-profile-form .save', function () {
            var _this = $(this);
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
                            $('.panel-body .alert').addClass("alert-success");
                            $('.panel-body .alert').removeClass("hide");
                            console.log(data);
                        } else{
                            var strMessage = '';
                            $.each(data.parameters, function(idx, val){
                                var element = 'change-pass-form-'+idx;
                                strMessage += val;
                            });
                            $('.panel-body .alert').text(strMessage);
                            $('.panel-body .alert').addClass("alert-warning");
                            $('.panel-body .alert').removeClass("hide");
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

        $('.panel-body .alert').click(function () {
            $('.panel-body .alert').addClass("hide");
        });
    });
</script>
