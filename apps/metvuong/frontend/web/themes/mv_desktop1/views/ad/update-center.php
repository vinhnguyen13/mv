<?php
	use yii\web\View;
	use yii\helpers\Html;
	use vsoft\ad\models\AdCity;
	use yii\helpers\ArrayHelper;
	use yii\helpers\Url;
	
	$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&libraries=geometry', ['position' => View::POS_END]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/update-center.js', ['position' => View::POS_END]);
	$this->registerJs('var url = "' . Url::to(['/ad/update-center']) . '";', View::POS_BEGIN);
?>

<?= Html::dropDownList('city', null, ArrayHelper::map(AdCity::find()->all(), 'id', 'name'), ['prompt' => 'Chọn thành phố']) ?>
<ul class="status"></ul>