<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$pathCss = Yii::$app->view->theme->baseUrl .'/resources/css';

Yii::$app->getView()->registerCssFile($pathCss.'/select2.css');
Yii::$app->getView()->registerCssFile($pathCss.'/jquery-ui.css');
Yii::$app->getView()->registerCssFile('https://fonts.googleapis.com/css?family=Roboto:400,700|Noticia+Text:400italic,400,700,700italic', ['type' => 'text/css']);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl .'/resources/js/plugins/select2.min.js');
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl .'/resources/js/plugins/jquery-ui.min.js');
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl .'/resources/js/book.js');
?>


<div id="book-page">
    <div class="container">
        <div class="center">
            <h1 class="title">Booking</h1>

            <div class="form-wrap">

                <?php
                $form = \yii\widgets\ActiveForm::begin([
                    'id' => 'create-booking-form',
                    'action' => Url::toRoute('/express/booking/booking-hotel'),
                ]); ?>
                    <div class="table">
                        <div class="table-row">
                            <label class="table-cell">Checkin date *</label>

                            <div class="table-cell">
                                <input class="date-picker text-field" type="text" name="checkin" readonly/>
                            </div>
                        </div>
                        <div class="table-row">
                            <label class="table-cell">Checkout date *</label>

                            <div class="table-cell">
                                <input class="date-picker text-field" type="text" name="checkout" readonly/>
                            </div>
                        </div>
                        <div class="table-row">
                            <label class="table-cell">Building *</label>

                            <div class="table-cell">
                                <select class="custom-select">
                                    <?php
                                    $buildings = \vsoft\express\models\LcBuilding::find()->where('isbooking = :is',[':is' => 1])->all();
                                    $listData = ArrayHelper::map($buildings, 'id', 'building_name');
                                    foreach($listData as $data){ ?>
                                    <option><?=$data?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="table-row break">
                            <div class="table-cell"><label>Apartment type *</label></div>
                            <div class="table-cell">
                                <select class="custom-select atype" name="apart" >
                                <?php
                                $apart_type = \vsoft\express\models\LcApartmentType::find()->all();
                                $apart_data = ArrayHelper::map($apart_type, 'id', 'name');
                                foreach($apart_data as $apart){ ?>
                                    <option><?=$apart?></option>
                                <?php }?>
                                ?>
                                </select>
                                <div class="break-on-mobile"></div>
                                <label>Floorplan *</label>
                                <select class="custom-select" name="floor">
                                    <?php
                                    $floorNum = [];
                                    foreach($buildings as $building){
                                        if($building->building_name === 'Lancaster Legacy'){
                                            for($x=1;$x <= $building->floor; $x++){ ?>
                                                <option><?=$x?></option>
                                           <?php }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="table-row">
                            <label class="table-cell">Full name *</label>

                            <div class="table-cell">
                                <input class="text-field" type="text" name="fullname" />
                            </div>
                        </div>
                        <div class="table-row">
                            <label class="table-cell">Phone number *</label>

                            <div class="table-cell">
                                <input class="text-field" type="text" name="phone" />
                            </div>
                        </div>
                        <div class="table-row">
                            <label class="table-cell">Email *</label>

                            <div class="table-cell">
                                <input class="text-field" type="text" name="email" />
                            </div>
                        </div>
                        <div class="table-row">
                            <label class="table-cell">Address</label>

                            <div class="table-cell">
                                <input class="text-field" type="text" name="address" />
                            </div>
                        </div>
                        <div class="table-row">
                            <label class="table-cell">Passport No.</label>

                            <div class="table-cell">
                                <input class="text-field" type="text" name="passport" />
                            </div>
                        </div>
                        <div class="table-row">
                            <label class="table-cell">Nationality</label>

                            <div class="table-cell">
                                <input class="text-field" type="text" name="nation" />
                            </div>
                        </div>
                        <div class="table-row">
                            <label class="table-cell v-top">Infomation</label>

                            <div class="table-cell">
                                <textarea class="textarea-field" rows="4" name="info" ></textarea>
                            </div>
                        </div>
                        <div class="table-row">
                            <div class="table-cell"></div>
                            <div class="table-cell center">
                                <?= Html::submitButton(Yii::t('express/booking','Submit'), ['class' => 'submit']) ?>
                            </div>
                        </div>
                    </div>
                <?php \yii\widgets\ActiveForm::end(); ?>

            </div>

        </div>
    </div>
</div>
