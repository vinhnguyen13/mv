<?php
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use vsoft\express\models\LcBuilding;
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/screen.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
], 'css-screen');
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/ie-emulation-modes-warning.js', ['position'=>View::POS_HEAD]);
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <nav class="navbar navbar-default menubar">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->

            <div class="navbar-header">
                <div class="logoheader">
                    <a href="<?=\yii\helpers\Url::home()?>" class="logoindexlancaster"></a>
                    <a href="<?=\yii\helpers\Url::home()?>" class="booklancaster"></a>
                </div>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse bar_bg" id="bs-example-navbar-collapse-1">
                <?php $supportedLanguages = Yii::$app->bootstrap['languageSelector']['supportedLanguages']; ?>
                <div class="logoheaderpage"><a href="<?=\yii\helpers\Url::home()?>" class="book"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/IMG/textimages.png" alt="LANCASTER LEGACY"></a></div>
                <div class="text_pagecontent">
                    <?php
                    $buildings = LcBuilding::getDb()->cache(function ($db) {
                        return LcBuilding::find()->all();
                    });
                    if(!empty($buildings)):
                        ?>
                        <ul class="dropdown">
                            <li><a href="<?=\yii\helpers\Url::to(['/'])?>" class="logoindex"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/IMG/logolancter.png" alt="LANCASTER LEGACY"></a>
                                <ul class="sub_menu">
                                    <?php foreach($buildings as $building):?>
                                        <li><a href="<?=Url::toRoute(['/site/page', 'view'=>'location'])?>"><?= $building->building_name ?></a></li>
                                    <?php endforeach;?>
                                </ul>
                            </li>
                        </ul>
                    <?php endif;?>
                </div>

                <ul class="nav navbar-nav navbar-right">
                    <li><a href="<?=\yii\helpers\Url::toRoute(['/site/language', 'language' => !empty($supportedLanguages[1]) ? $supportedLanguages[1] : ''])?>" <?=(!empty($supportedLanguages[1]) && Yii::$app->language == $supportedLanguages[1]) ? 'class="active"' : '';?>>Vi</a></li>
                    <li><a href="<?=\yii\helpers\Url::toRoute(['/site/language', 'language' => !empty($supportedLanguages[0]) ? $supportedLanguages[0] : ''])?>" <?=(!empty($supportedLanguages[0]) && Yii::$app->language == $supportedLanguages[0]) ? 'class="active"' : '';?>>En</a></li>
                    <li><a>|</a></li>
                    <li><a href="<?=\yii\helpers\Url::toRoute('/express/contact/index')?>" <?= (\Yii::$app->controller->id=='contact') ? 'class="active"' : ''?>><?=\Yii::t('express/contact', 'Contact');?></a></li>
                    <li><a href="<?=\yii\helpers\Url::toRoute('/express/about/index')?>" <?= (\Yii::$app->controller->id=='about') ? 'class="active"' : ''?>><?=\Yii::t('express/about', 'About Us');?><span class="sr-only">(current)</span></a></li>
                    <li><a href="<?=\yii\helpers\Url::toRoute('/express/news/index')?>" <?= (\Yii::$app->controller->id=='news') ? 'class="active"' : ''?>><?=\Yii::t('express/news', 'News');?></a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="rightbgmenu"><a href="<?=\yii\helpers\Url::toRoute('/express/booking/index')?>" class="book"><?=\Yii::t('express/booking', 'Book Now');?></a></li>
                    <li><i class="glyphicon glyphicon-earphone icon"></i><a href="#" class="sdt">0903 090 909</a></li>
                </ul>


            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <?php $this->beginContent('@app/views/layouts/_partials/menutop.php'); ?><?php $this->endContent();?>
    <?= $content ?>
<script src="<?=Yii::$app->view->theme->baseUrl?>/resources/js/bootstrap.js"></script>
<script src="<?=Yii::$app->view->theme->baseUrl?>/resources/js/ie10-viewport-bug-workaround.js"></script>
<script src="<?=Yii::$app->view->theme->baseUrl?>/resources/js/hoverIntent.js"></script>
<script src="<?=Yii::$app->view->theme->baseUrl?>/resources/js/screenlancaster.js"></script>
<?php $this->endContent();?>
