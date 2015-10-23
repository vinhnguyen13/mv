<?php
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use vsoft\express\models\LcBuilding;

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/ie-emulation-modes-warning.js', ['position'=>View::POS_HEAD]);

/* @var $this yii\web\View */

$this->title = Yii::t('express','We offer exeptional amenities and renowned white - glove services');
?>
<style>
    .menutop{
        position: fixed;
        z-index: 10000;
    }
</style>
<?=$this->render('@app/views/site/pages/apartments');?>
<?=$this->render('@app/views/site/pages/amenities');?>
<?=$this->render('@app/views/site/pages/views');?>
<?=$this->render('@app/views/site/pages/neighborhood');?>

<script src="<?=Yii::$app->view->theme->baseUrl?>/resources/js/bootstrap.js"></script>




