<?php

use yii\web\View;
use vsoft\ad\models\AdDistrict;
use vsoft\ad\models\AdWard;
use yii\helpers\Url;

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&libraries=geometry', ['position' => View::POS_END]);
$this->registerJsFile(Yii::getAlias('@web') . '/js/geo-recursive.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerCssFile(Yii::getAlias('@web') . '/css/geo.css');

$districts = json_encode(AdDistrict::find()->asArray(true)->select(['id', 'name', 'pre', 'slug', 'IF(`geometry` IS NULL, 0, 1) AS `geometry`'])->where(['city_id' => $city['id']])->all(), JSON_UNESCAPED_UNICODE);
$wards = json_encode(AdWard::find()->asArray(true)->select(['id', 'district_id', 'name', 'pre', 'slug', 'IF(`geometry` IS NULL, 0, 1) AS `geometry`'])->where(['city_id' => $city['id']])->all(), JSON_UNESCAPED_UNICODE);
$cityVar = json_encode($city);
$encodeUrl = Url::to(['/ad/geo/encode-geometry']);

$script = <<<EOD
	var districts = $districts;
	var wards = $wards;
	var city = $cityVar;
	var encodeUrl = '$encodeUrl';
EOD;

$this->registerJs($script, View::POS_BEGIN);
?>

<a href="<?= Url::to(['/ad/geo']) ?>">Trở về danh sách Thành Phố</a>

<h1><?= $city['name'] ?></h1>

<?php
	$file = $city['slug'] . '.js';
	if(in_array($file, $geoFiles)):
?>
<input type="hidden" id="file" value="<?= $file ?>" />
<?php else: ?>
<div><label for="file">Chọn file tương ứng bên dưới</label></div>
<select id="file">
	<?php foreach ($geoFiles as $geoFile): ?>
	<option value="<?= $geoFile ?>"><?= $geoFile ?></option>
	<?php endforeach; ?>
</select>
<?php endif; ?>
<input id="start" type="button" value="Bắt đầu PARSE" />
<div id="logs"></div>

<div id="district-mapper" style="display: none;">
	<label>Chọn quận/huyện phù hợp với <span id="district-mapper-label"></span></label>
	<select id="district-mapper-select"></select>
	<input type="button" id="district-mapper-ok" value="Xác nhận" />
	<input type="button" id="district-mapper-cancel" value="Bỏ qua" />
</div>

<div id="ward-mapper" style="display: none;">
	<label>Chọn quận/huyện phù hợp với <span id="ward-mapper-label"></span></label>
	<select id="ward-mapper-select"></select>
	<input type="button" id="ward-mapper-ok" value="Xác nhận" />
	<input type="button" id="ward-mapper-cancel" value="Bỏ qua" />
</div>