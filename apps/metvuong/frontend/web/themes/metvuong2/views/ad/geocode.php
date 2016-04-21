<?php 
	use yii\helpers\Url;
use yii\web\View;
	
	$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/gmap-v2.js', ['position' => View::POS_END]);
	$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=loaded', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
	$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/geocode.js', ['position' => View::POS_END]);
?>
<select id="city" name="city" style="padding: 4px; line-height: 32px;">
	<option value="">Chọn tỉnh/thành</option>
</select>
<input type="button" id="geo" value="Geocode" style="padding: 0 4px; line-height: 28px;">
<input id="csrf" type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
<input data-url="<?= Url::to(['geocode']) ?>" type="button" id="save" value="Save to DB" style="padding: 0 4px; line-height: 28px;" disabled="disabled">
<div style="margin-top: 20px;" id="log"></div>