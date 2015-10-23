<?php
$this->title = Yii::t('express/booking', 'Booking');
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 9/18/2015 11:16 AM
 */
use kartik\datetime\DateTimePicker;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

date_default_timezone_set('Asia/Ho_Chi_Minh');

?>

<div class="container-fluid booking">
    <div class="row main_content">
        <span class="btn_back"><img src="<?= Yii::$app->view->theme->baseUrl ?>/resources/IMG/btn_back.png"><a
                href="<?= \yii\helpers\Url::home() ?>"><?=Yii::t('express/booking', 'Back to Lancaster Legacy')?></a></span>

        <h1><?= Yii::t('express/booking', 'Booking'); ?></h1>
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
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mainbooking">
            <div class="btnpic">
                <a href="http://www.be3.com/asia/vietnam/ho_chi_minh_city/lancaster_saigon_serviced_apartments_le_thanh_ton.html?cur=USD&setcookienew=1">
                    <button class="btnclick" type="submit">Click here <span class="glyphicon glyphicon-play-circle"></span>
                    </button>
                </a>
            </div>
            <div class="btnpic">
                <a href="http://www.be3.com/asia/vietnam/ho_chi_minh_city/lancaster_saigon_serviced_apartments_le_thanh_ton.html?cur=USD&setcookienew=1">
                    <button class="btnclick" type="submit">Click here <span class="glyphicon glyphicon-play-circle"></span>
                    </button>
                </a>
            </div>
            <!-- <form action="#" method="post" class="form" role="form"> -->
            <?php
            $model = new \vsoft\express\models\LcBooking();
            $form = \yii\widgets\ActiveForm::begin([
                'id' => 'create-booking-form',
                'action' => Url::toRoute('/express/booking/booking-hotel'),
                'options' => [
                    'class' => 'form',
                    'role' => 'form',
                    'method' => 'post'
                ]
            ]); ?>
            
            <div class="frorminput">
                <div class="live">
                    <?= $form->field($model, 'checkin')->widget(DateTimePicker::className(),[
                        'name' => 'check_in',
                        'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                        'options' => [
                            'placeholder' => 'Input check in date & time ...',
                            'size' => '100%',
                        ],
                        'readonly' => true,
                        'removeButton' => false,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd hh:ii',
                            'todayHighlight' => true
//                            'pickerPosition' => 'bottom-left',
//                            'linkField' =>  Html::getInputId($model, 'checkin'),
//                            'linkFormat' => 'yyyy-mm-dd hh:ii:ss',
                        ],
                    ]) ?>

                </div>
                <div class="live">
                    <!--<input class="form-control" name="checkout" placeholder="Select" type="text" required
                           autofocus/><span class="glyphicon glyphicon-calendar"></span>-->
                    <?= $form->field($model, 'checkout')->widget(DateTimePicker::className(),[
                        'name' => 'check_out',
                        'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                        'options' => [
                            'placeholder' => 'Input check out date & time ...',
                            'size' => '100%',
                        ],
                        'readonly' => true,
                        'removeButton' => false,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd hh:ii',
                            'todayHighlight' => true
//                            'pickerPosition' => 'bottom-left',
//                            'linkField' =>  Html::getInputId($model, 'checkout'),
//                            'linkFormat' => 'yyyy-mm-dd hh:ii:ss',
                        ],
                    ])?>

                </div>
                <div class="live">
                    <!--<select class="form-control">
                        <option value="Year">Year</option>
                    </select>-->
                    <?php
                    $buildings = \vsoft\express\models\LcBuilding::find()->all();
                    $listData = ArrayHelper::map($buildings, 'id', 'building_name');
                    echo $form->field($model, 'lc_building_id')->dropDownList($listData, [
                        'onchange' => '
                                $.get( "' . Url::toRoute('/express/booking/view-floor-by-building') . '", { id: $(this).val() } )
                                    .done(function( data ) {
                                        $( "#' . Html::getInputId($model, 'floorplan') . '" ).html( data );
                                    }
                                );
                            ',
                    ]);
                    ?>
                </div>
                <div class="apartment">
                    
                        <div class="inputapert">
                            <?php
                            $apart_type = \vsoft\express\models\LcApartmentType::find()->all();
                            $apart_data = ArrayHelper::map($apart_type, 'id', 'name');
                            echo $form->field($model, 'apart_type')->dropDownList(
                                $apart_data,
                                [
                                    'options' => [1 => ['selected ' => true]],
//                                    'class' => 'form-control medium-width'
                                ]
                            );
                            ?>
                        </div>
                        <div class="inputapert">
                            <?php
                            $floorNum = [];
                            foreach($buildings as $building){
                                if($building->building_name === 'Lancaster Legacy'){
                                    for($x=1;$x <= $building->floor; $x++){
                                        $floorNum[$x] = $x;
                                    }
                                }
                            }
                            echo $form->field($model, 'floorplan')->dropDownList($floorNum,
                                [
                                    'options' => [22 => ['selected ' => true]],
//                                    'class' => 'form-control medium-width'
                                ]
                            ) ?>
                        </div>
                   
                </div>
                <div class="namephone">
<!--                    <input class="form-control" name="" placeholder="Full name" type=""/>-->
                    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true])?>
                </div>
                <div class="deafall">
<!--                    <input class="form-control" name="" placeholder="Phone number" type=""/>-->

                    <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(),[
                        'mask' => '9999 999 999[9]'
                    ]) ?>
                </div>
                <div class="deafall">
<!--                    <input class="form-control" name="" placeholder="Email" type=""/>-->
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="deafall">
<!--                    <input class="form-control" name="" placeholder="Address" type=""/>-->
                    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="deafall">
<!--                    <input class="form-control" name="" placeholder="Passport No" type=""/>-->
                    <?= $form->field($model, 'passport_no')->textInput(['maxlength' => true])?>
                </div>
                <div class="deafall">
<!--                    <input class="form-control" name="" placeholder="Nationality" type=""/>-->
                    <?= $form->field($model, 'nationality')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="deafall">
<!--                    <textarea class="form-control" name="" placeholder="Text here" cols="" rows="5"></textarea>-->
                    <?= $form->field($model, 'info')->textarea(['maxlength' => true, 'rows' => '4'])?>
                </div>
                <div class="btnsubmit">
<!--                    <button class="btn btn-lg btn_primary btn-block" type="submit">-->
<!--                        Submit-->
<!--                    </button>-->
                    <?= Html::submitButton(Yii::t('express/booking','Submit'), ['class' => 'btn btn-lg btn_primary btn-block']) ?>
                </div>
            </div>
            <?php \yii\widgets\ActiveForm::end(); ?>
            <!-- </form> -->
        </div>
    </div>
</div>

<!--
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
-->
<script src="<?= Yii::$app->view->theme->baseUrl ?>/js/jquery.js"></script>
<script src="<?= Yii::$app->view->theme->baseUrl ?>/js/bootstrap.js"></script>
<script src="<?= Yii::$app->view->theme->baseUrl ?>/js/holder.htm"></script>
<script src="<?= Yii::$app->view->theme->baseUrl ?>/js/ie10-viewport-bug-workaround.js"></script>


