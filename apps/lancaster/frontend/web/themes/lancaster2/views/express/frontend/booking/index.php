<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$path = Yii::$app->view->theme->baseUrl;

Yii::$app->getView()->registerCssFile('https://fonts.googleapis.com/css?family=Roboto:400,700|Noticia+Text:400italic,400,700,700italic', ['type' => 'text/css']);
Yii::$app->getView()->registerCssFile($path . '/resources/css/select2.css');
Yii::$app->getView()->registerCssFile($path . '/resources/css/jquery-ui.css');

//Yii::$app->getView()->registerJsFile($path . '/resources/js/plugins/select2.min.js');
//Yii::$app->getView()->registerJsFile($path . '/resources/js/plugins/jquery-ui.min.js');
//Yii::$app->getView()->registerJsFile($path . '/resources/js/book.js');
?>
<div id="book-page">
    <div class="container">
        <div class="center">
            <h1 class="title">Booking</h1>
            <?php
            if(Yii::$app->getSession()->hasFlash('reSuccess')) {
                \yii\bootstrap\Alert::begin([
                    'options' => [
                        'class' => 'alert alert-success',
                    ],
                ]);

                echo Yii::$app->getSession()->getFlash('reSuccess');

                \yii\bootstrap\Alert::end();
            }
            ?>
            <div class="form-wrap">

                <?php
                $model = new \vsoft\express\models\LcBooking();
                $form = \yii\widgets\ActiveForm::begin([
                    'id' => 'create-booking-form',
                    'action' => Url::toRoute('/express/booking/booking-hotel'),
                    'options' => [
                        'class' => 'form',
                        'role' => 'form',
                        'method' => 'post'
                    ],
                    'enableClientScript' => true
                ]);
                ?>
                <div class="table">
                    <div class="table-row">
                        <label class="table-cell"><?= Yii::t('booking','Checkin date')?> *</label>

                        <div class="table-cell">
<!--                            <input id="checkin" class="date-picker text-field" type="text" name="checkin" readonly/> -->
                            <?= $form->field($model, 'checkin')->textInput(['maxlength' => true, 'class' => 'date-picker text-field', 'readOnly' => 'true'])->label(false) ?>
                        </div>
                    </div>
                    <div class="table-row">
                        <label class="table-cell">Checkout date *</label>

                        <div class="table-cell">
<!--                            <input id="checkout" class="date-picker text-field" type="text" name="checkout" readonly/> -->
                            <?= $form->field($model, 'checkout')->textInput(['maxlength' => true, 'class' => 'date-picker text-field', 'readOnly' => 'true'])->label(false) ?>

                        </div>
                    </div>
                    <div class="table-row">
                        <label class="table-cell">Building *</label>

                        <div class="table-cell">
                            <?php
                            $buildings = \vsoft\express\models\LcBuilding::find()->where('isbooking = :is', [':is' => 1])->all();
                            $listData = ArrayHelper::map($buildings, 'id', 'building_name');
                            echo $form->field($model, 'lc_building_id')->dropDownList($listData, [
                                'class' => 'custom-select',
                                'style' => 'width: 100%',
                                'onchange' => '
                                $.get( "' . Url::toRoute('/express/booking/view-floor-by-building') . '", { id: $(this).val() } )
                                    .done(function( data ) {
                                        $("#floor option").remove();
                                        $("#floor").append(data);
                                        $("#floor option:last-child").attr("selected", "selected").change();
                                    }
                                );
                            ',
                            ])->label(false);
                            ?>
                        </div>
                    </div>
                    <div class="table-row break">
                        <div class="table-cell"><label>Apartment type *</label></div>
                        <div class="table-cell">
                            <select class="custom-select atype" name="apart_type">
                                <?php
                                $apart_type = \vsoft\express\models\LcApartmentType::find()->all();
                                $apart_data = ArrayHelper::map($apart_type, 'id', 'name');
                                foreach ($apart_data as $a_key => $apart) { ?>
                                    <option value="<?=$a_key?>"><?= $apart ?></option>
                                <?php } ?>
                                ?>
                            </select>

                            <div class="break-on-mobile"></div>
                            <label>Floorplan *</label>
                            <select class="custom-select" name="floorplan" id="floor">
                                <?php
                                $floorNum = [];
                                foreach ($buildings as $building) {
                                    if ($building->building_name === 'Lancaster Legacy') {
                                        for ($x = 1; $x <= $building->floor; $x++) {
                                            if ($x == $building->floor) { ?>
                                                <option selected value="<?= $x ?>"><?= $x ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $x ?>"><?= $x ?></option>
                                            <?php }
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="table-row">
                        <label class="table-cell">Full name *</label>
                        <div class="table-cell">
                            <?= $form->field($model, 'fullname')->textInput(['maxlength' => true, 'class' => 'text-field'])->label(false) ?>
                        </div>
                    </div>
                    <div class="table-row">
                        <label class="table-cell">Phone number *</label>
                        <div class="table-cell">
                            <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'class' => 'text-field'])->label(false) ?>
                        </div>
                    </div>
                    <div class="table-row">
                        <label class="table-cell">Email *</label>
                        <div class="table-cell">
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'class' => 'text-field'])->label(false) ?>
                        </div>
                    </div>
                    <div class="table-row">
                        <label class="table-cell">Address</label>
                        <div class="table-cell">
                            <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'class' => 'text-field'])->label(false) ?>
                        </div>
                    </div>
                    <div class="table-row">
                        <label class="table-cell">Passport No.</label>
                        <div class="table-cell">
                            <?= $form->field($model, 'passport_no')->textInput(['maxlength' => true, 'class' => 'text-field'])->label(false) ?>
                        </div>
                    </div>
                    <div class="table-row">
                        <label class="table-cell">Nationality</label>
                        <div class="table-cell">
                            <?= $form->field($model, 'nationality')->textInput(['maxlength' => true, 'class' => 'text-field'])->label(false) ?>
                        </div>
                    </div>
                    <div class="table-row">
                        <label class="table-cell v-top">Infomation</label>
                        <div class="table-cell">
                            <?= $form->field($model, 'info')->textarea(['maxlength' => true, 'class' => 'textarea-field', 'rows' => 4])->label(false) ?>
                        </div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell"></div>
                        <div class="table-cell center">
                            <?= Html::submitButton(Yii::t('express/booking', 'Submit'), ['class' => 'submit']) ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>

            </div>

        </div>
    </div>
</div>
<script>
    $(".book-now").hide();
</script>


