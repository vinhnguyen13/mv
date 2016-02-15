<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/11/2015
 * Time: 2:34 PM
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="col-xs-12 right-profile quanlytinraoban">
    <div class="wrap-quanly-profile">
        <div class="title-frm"></div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="alert alert-success hide"></div>
                    <?php $form = ActiveForm::begin([
                        'id' => 'change-pass-form',
                        'action' => Url::to(['/member/password']),
                        'enableAjaxValidation' => false,
                        'enableClientValidation' => true,
                        'layout' => 'horizontal',
                        'fieldConfig' => [
                            'horizontalCssClasses' => [
                                'wrapper' => 'col-sm-12',
                            ],
                        ],
                    ]); ?>
                    <?= $form->field($model, 'old_password')->textInput(['class' => 'form-control', 'type' => 'password']) ?>
                    <?= $form->field($model, 'new_password')->textInput(['class' => 'form-control', 'type' => 'password']) ?>
                    <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-12">
                            <?= Html::submitButton(Yii::t('user', 'Reset'), ['class' => 'btn btn-block btn-success reset']) ?>
                        </div>
                    </div>
                    <a class="showPopup" href="#" data-toggle="modal" data-target="#frmForgot">Quên mật khẩu ?</a>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
//        $('.panel-body .alert').hide();
        var timer = 0;
        $(document).on('click', '#change-pass-form .reset', function () {
            var _this = $(this);
            clearTimeout(timer);
            timer = setTimeout(function () {
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: $('#change-pass-form').attr('action'),
                    data: $('#change-pass-form').serializeArray(),
                    success: function (data) {
                        console.log(data);
                        if (data.statusCode == true) {
                            $('.panel-body .alert').text("Reset password success");
                            $('.panel-body .alert').removeClass("hide");
                            $('.panel-body .alert').show("slow");
                            console.log(data);
                        } else{
                            var strMessage = '';
                            $.each(data.parameters, function(idx, val){
//                                var element = 'change-pass-form-'+idx;
                                strMessage += "\n" + val;
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

        $('#change-pass-form input').keypress(function (e) {
            if (e.which == 13) {
                //$('#change-pass-form .reset').click();
            }
        });

        $('.panel-body .alert').click(function () {
            $(this).fadeOut(2000, function(){
                $(this).addClass("hide");
            });
        });
    });
</script>