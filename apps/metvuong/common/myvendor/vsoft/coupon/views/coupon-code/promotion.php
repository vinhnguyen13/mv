<?php

use vsoft\news\models\Status;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\coupon\models\CouponCode */
/* @var $form yii\widgets\ActiveForm */
$this->registerAssetBundle(yii\web\JqueryAsset::className(), \yii\web\View::POS_HEAD);
$this->registerJsFile(Yii::getAlias('@web') . '/js/select2.min.js');
$this->registerCssFile(Yii::getAlias('@web') . '/css/select2.min.css');
?>
<div class="coupon-code-form col-lg-4">
    <?php $form = ActiveForm::begin([
        'id'=>'frmCP'
    ]); ?>
    <?=Html::dropDownList('user_id', null, [], ['class' => 'form-control search region_user', 'prompt' => "..."])?>
    <?=Html::dropDownList('code_id', null, [], ['class' => 'form-control search region_coupon_code', 'prompt' => "..."])?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('coupon', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $(document).ready(function () {
        $('.region_user').select2({
            width: '100%',
            ajax: {
                url: '<?=Yii::$app->request->getHostInfo()?>/site/find-user',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        v: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.full_name,
                                slug: item.full_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
        });

        $('.region_coupon_code').select2({
            width: '100%',
            ajax: {
                url: '<?=Yii::$app->request->getHostInfo()?>/site/find-coupon-code',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        v: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.full_name,
                                slug: item.full_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
        });

        $(document).on('click', '.btn-primary', function (e) {
            var _this = $(this);
            _this.html('Loading......');
            $.post('<?=\yii\helpers\Url::to(['/coupon/coupon-code/promotion'])?>', $('#frmCP').serialize(), function (response) {
                _this.html('Submit');
            }).fail(function(response) {
                alert('L?u không thành công !');
            });
            return false;
        });
    });
</script>