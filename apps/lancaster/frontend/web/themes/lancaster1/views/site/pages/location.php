<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 9/23/2015
 * Time: 2:14 PM
 */
use vsoft\express\models\LcBuilding;

?>
<div class="container layoutlocation">
    <div class="row main_content">
        <!-- Info -->
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 locationleft">
            <div id="accordion1">
                <?php
                $buildings = LcBuilding::find()->all();
                $i = 1;
                foreach ($buildings as $building) {
                    ?>
                    <div class="panel">
                        <div class="panel-heading accordion-toggle collapsed" data-toggle="collapse"
                             data-parent="#accordion1" data-target="#collapse<?= $i ?>">
                            <h4 class="panel-title linetext"><?= $building->building_name ?></h4>
                            <span class="linetitleboder">View Address<i class="glyphicon glyphicon-menu-down"></i></span>
                        </div>
                        <div id="collapse<?= $i ?>" class="panel-collapse collapse <?= $i == 1 ? 'in' : '' ?>">
                            <div class="panel-body">
                                <ul class="lineone">
                                    <li>Address<br>
                                        <?= $building->address ?><br><br>
                                    </li>
                                    <li>Phone<br>
                                        <?= $building->phone ?><br><br></li>
                                    <li>Fax<br>
                                        <?= $building->fax ?><br><br></li>
                                    <li>Email<br>
                                        <?= $building->email ? $building->email : 'sales@trungthuygroup.vn' ?><br><br></li>
                                    <li><b>Hotline<br>
                                            <?= $building->hotline ? $building->hotline : '0903 090 909' ?></b></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php
                    $i = $i + 1;
                } ?>

            </div>
        </div>
        <!-- Map -->
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 locationright">
            <div class="iconmap">
                <span class="facebook"></span>
                <span class="youtube"></span>
                <span class="images"></span>
            </div>
            <div class="map">
                <img src="<?= Yii::$app->view->theme->baseUrl ?>/resources/IMG/map.png">
            </div>
        </div>

    </div>
</div><!--end content-->
<div class="container-fluid layoutlocation">
    <div class="row main_footer">
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 logofooter"></div>
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 rightfooter">
            <ul class="list-inline ul">
                <li>Lancaster Legacy</li>
                <li>.</li>
                <li>Lancaster Le Thanh Ton</li>
                <li>.</li>
                <li>Lancaster Nguyen Trai</li>
                <li>.</li>
                <li>Lancaster Hanoi</li>
            </ul>
            <ul class="list-inline ulmenu">
                <li><a href="<?=\yii\helpers\Url::toRoute('/express/about/index');?>">About Us</a></li>
                <li><a href="<?=\yii\helpers\Url::toRoute('/express/news/index');?>">News</a></li>
                <li><a href="<?=\yii\helpers\Url::toRoute('/express/contact/index');?>">Contact</a></li>
                <li><a href="javascript:;">Newsletter</a></li>
                <li class="btnfooter">
                    <a href="<?= \yii\helpers\Url::toRoute('/booking') ?>" style="text-decoration: none;">
                        <button type="submit" class="btn-lg btn_primary btn-block">
                            booknow
                        </button>
                    </a>
                </li>
            </ul>

        </div>
    </div>
</div>
