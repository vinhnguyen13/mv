<?php

/* @var $this yii\web\View */

$this->title = Yii::t('express','We offer exeptional amenities and renowned white - glove services');
?>
<style>
    body{
        overflow: hidden;
    }
    .main_content img{
        min-height: 100%;
        min-width: 100%;
    }
</style>
<div class="container-fluid layoutindex">
    <div class="row main_content">
        <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/IMG/01.png">
    </div>
    <div class="bannerblock">
        <ul>
            <li class="title noticaitalic">Lancaster Legacy offers you a sweeping panoramic view of the city skyline<span class="linebuttom"></span> </li>
            <li>Besides 109 ultra-luxury and graciously furnished apartments ranging from studios to penthouses, the building also features 6 floors of working space for setting up professional and supreme offices.</li>
        </ul>
    </div>
</div>
