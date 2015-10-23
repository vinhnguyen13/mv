<?php
use vsoft\express\models\LcBuilding;
?>
<header>
    <div id="top-bar" class="clear">
        <?php
        $buildings = LcBuilding::getDb()->cache(function ($db) {
            return LcBuilding::find()->all();
        });
        if(!empty($buildings)):
        ?>
        <div id="branch-wrap">
            <ul>
                <?php foreach($buildings as $building):?>
                    <li><a href="#"><?= $building->building_name ?></a></li>
                <?php endforeach;?>
            </ul>
        </div>
        <?php endif;?>
        <a href="#" id="logo" class="left"><span class="logo"></span><span class="arrow"></span></a>
        <a href="#" class="menu-button" id="mobile-menu-button"></a>
        <div id="mobile-menu">
            <a href="#" class="book-now right"><?=\Yii::t('express/booking', 'Book Now');?></a>
            <a href="#" class="menu-button" id="menu-nav"></a>
            <div class="right nav">
                <ul class="menu clear">
                    <li><a href="#"><?=\Yii::t('express/about', 'About Us');?></a></li>
                    <li><a href="#"><?=\Yii::t('express/news', 'News');?></a></li>
                    <li><a href="#"><?=\Yii::t('express/contact', 'Contact');?></a></li>
                </ul>
                <i class="separator"></i>
                <?php $supportedLanguages = Yii::$app->bootstrap['languageSelector']['supportedLanguages']; ?>
                <ul class="langs clear">
                    <li <?=(!empty($supportedLanguages[1]) && Yii::$app->language == $supportedLanguages[1]) ? 'class="active"' : '';?>><a href="<?=\yii\helpers\Url::toRoute(['/site/language', 'language' => !empty($supportedLanguages[1]) ? $supportedLanguages[1] : ''])?>">Vi</a></li>
                    <li <?=(!empty($supportedLanguages[0]) && Yii::$app->language == $supportedLanguages[0]) ? 'class="active"' : '';?>><a href="<?=\yii\helpers\Url::toRoute(['/site/language', 'language' => !empty($supportedLanguages[0]) ? $supportedLanguages[0] : ''])?>">En</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>