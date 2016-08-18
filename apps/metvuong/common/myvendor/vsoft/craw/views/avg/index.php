<?php 
	use yii\helpers\Url;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdCategory;

	$this->registerCss('.summary {position: absolute; right: 0px; top: -20px;} .cms-show-index {padding-top: 40px; position: relative;} .filter-col {margin-right: 12px;} .container {max-width: none; width: auto;} .summary {float: right;font-size: 20px;margin-top: 28px;} .title {float: left;} .min {width: 100px; display: inline-block;} table {white-space: nowrap;}');
	$this->registerCssFile(Yii::getAlias('@web') . '/css/avg.css');
	$this->registerJsFile(Yii::getAlias('@web') . '/js/avg.js', ['depends' => ['yii\web\YiiAsset']]);
	
	$types = AdProduct::getAdTypes();
	$categories = AdCategory::find()->all();
?>
<div id="avg-page">
	<div id="filter-wrap">
		<div id="avg-search-wrap">
			<input data-url="<?= Url::to(['search']) ?>" class="big-field" id="avg-search" type="text" placeholder="Nhập tên Quận, Phường Hoặc Dự án" />
			<div class="big-field avg-search-placeholder"><span class="text"></span><a href="#" class="close">x</a></div>
			<div id="result-search-wrap" class="hide"><ul class="result-search"></ul></div>
		</div>
		<select name="type" id="type">
			<?php foreach ($types as $k => $type): ?>
			<option value="<?= $k ?>"><?= $type ?></option>
			<?php endforeach; ?>
		</select>
		<div id="input-wrap">
			<input id="export" class="btn btn-primary" type="button" value="Export Excel" />
			<a target="_blank" href="#" class="btn btn-primary">View Listings</a>
		</div>
	</div>
	<div id="view-wrap" class="hide">
		<div id="loading"><img src="/admin/images/submit-loading.gif" /></div>
		<div id="view">
			<div id="tabs">
				<div id="tabs-title"></div>
				<div id="tabs-content"></div>
			</div>
		</div>
	</div>
</div>