<?php

use vsoft\news\models\Status;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\coupon\models\CouponCode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="create-random-coupon-form">

    <?php $form = ActiveForm::begin();
    $events = \yii\helpers\ArrayHelper::map(\vsoft\coupon\models\CouponEvent::find()->where(['status' => Status::STATUS_ACTIVE])->all(), 'id', 'name');
    $model->amount_type = 1;
    ?>
    <table class="table table-striped">
        <tr>
            <th>Parameter</th>
            <th>Type</th>
            <th>Default</th>
            <th>Custom value</th>
        </tr>
        <tr>
            <th>Coupon Event</th>
            <td>number</td>
            <td></td>
            <td><?=$form->field($model, 'cp_event_id')->dropDownList($events, [
                    'options' => [$model->cp_event_id => ['Selected ' => true]],
                    'prompt' => ''
                ])->label(false);?></td>
        </tr>
        <tr>
            <th>Coupon Type</th>
            <td>number</td>
            <td></td>
            <td><?= $form->field($model, 'type')->dropDownList(\vsoft\coupon\models\CouponCode::getTypes())->label(false) ?></td>
        </tr>
        <tr>
            <th>Discount by </th>
            <td>number</td>
            <td></td>
            <td><?= $form->field($model, 'amount_type')->radioList(\vsoft\coupon\models\CouponCode::getAmountTypes())->label(false) ?>
                <?= $form->field($model, 'amount')->textInput(['placeholder' => Yii::t('coupon','Input amount')])->label(false) ?></td>
        </tr>
        <tr>
            <th>Number of coupons</th>
            <td>number</td>
            <td>1</td>
            <td><input class="form-control" type="number" name="no_of_coupons" value="1" min="1"/></td>
        </tr>
        <tr>
            <th>Prefix</th>
            <td>string</td>
            <td></td>
            <td><input class="form-control" type="text" name="prefix" value="" /></td>
        </tr>
        <tr>
            <th>Suffix</th>
            <td>string</td>
            <td></td>
            <td><input class="form-control" type="text" name="suffix" value="" /></td>
        </tr>
        <tr>
            <th>Numbers</th>
            <td>boolean</td>
            <td>true</td>
            <td>
                <select class="form-control" name="numbers">
                    <option value="false">False</option>
                    <option selected value="true">True</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>Letters</th>
            <td>boolean</td>
            <td>true</td>
            <td>
                <select class="form-control" name="letters">
                    <option value="false">False</option>
                    <option selected value="true">True</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>Symbols</th>
            <td>boolean</td>
            <td>false</td>
            <td>
                <select class="form-control" name="symbols">
                    <option selected value="false">False</option>
                    <option value="true">True</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>Random register (includes lower and uppercase)</th>
            <td>boolean</td>
            <td>false</td>
            <td>
                <select class="form-control" name="random_register">
                    <option selected value="false">False</option>
                    <option value="true">True</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>Length</th>
            <td>number</td>
            <td>6</td>
            <td><input class="form-control" type="number" name="length" value="6" min="1" /></td>
        </tr>
        <tr>
            <th>Mask</th>
            <td>string or boolean</td>
            <td>false</td>
            <td><input class="form-control" type="text" name="mask" value="XXXXXX" /></td>
        </tr>
    </table>

    <div class="form-group text-center">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('coupon', 'Create') : Yii::t('coupon', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
