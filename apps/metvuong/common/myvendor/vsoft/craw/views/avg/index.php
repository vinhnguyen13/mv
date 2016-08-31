<?php 
	use yii\helpers\Url;
	use vsoft\ad\models\AdProduct;
	use vsoft\ad\models\AdCategory;

	$this->registerCss('.summary {position: absolute; right: 0px; top: -20px;} .cms-show-index {padding-top: 40px; position: relative;} .filter-col {margin-right: 12px;} .container {max-width: none; width: auto;} .summary {float: right;font-size: 20px;margin-top: 28px;} .title {float: left;} .min {width: 100px; display: inline-block;} table {white-space: nowrap;}');
	$this->registerCssFile(Yii::getAlias('@web') . '/css/avg.css');
	$this->registerCssFile(Yii::getAlias('@web') . '/css/jquery-ui.css');
	$this->registerJsFile(Yii::getAlias('@web') . '/js/jquery-ui.min.js', ['depends' => ['yii\web\YiiAsset']]);
	$this->registerJsFile(Yii::getAlias('@web') . '/js/avg.js', ['depends' => ['yii\web\YiiAsset']]);
	
	$types = AdProduct::getAdTypes();
	$categories = AdCategory::find()->all();
?>
<div id="avg-page">
	<div id="filter-wrap">
		<div id="avg-search-wrap">
			<input data-url="<?= Url::to('/api/v1/craw-search/get') ?>" class="big-field" id="avg-search" type="text" placeholder="Nhập tên Quận, Phường Hoặc Dự án" />
			<div class="big-field avg-search-placeholder"><span class="text"></span><a href="#" class="close">x</a></div>
			<div id="result-search-wrap" class="hide"><ul class="result-search"></ul></div>
		</div>
		<select name="type" id="type">
			<?php foreach ($types as $k => $type): ?>
			<option value="<?= $k ?>"><?= $type ?></option>
			<?php endforeach; ?>
		</select>
		<div id="input-wrap">
			<a id="export" class="btn btn-primary" type="button" href="#">Export Excel</a>
			<a id="view-listing" target="_blank" href="#" class="btn btn-primary">View Listings</a>
			<div id="addition-setting">
				<div class="field-set">
					<div class="field-set-legend">Thiết lập thêm</div>
					<div class="field-set-body">
						<div style="display: none;">
							<label id="has-ward-wrap"><input class="cb" type="checkbox" name="has-ward" value="1" /><span>Chỉ tính trên những tin có thông tin <span style="color: #337ab7;">Phường</span></span></label><br />
							<label id="has-project-wrap" style="margin-right: 6px;"><input class="cb" type="checkbox" name="has-project" value="1" /><span>Chỉ tính trên những tin có thông tin <span style="color: #337ab7;">Dự án</span></span></label>
						</div>
						<div class="field-wrap">
							<label>Loại BĐS</label>
							<select name="category_id" id="category_id">
								<?php foreach ($categories as $category): ?>
								<option value="<?= $category->id ?>"<?= $category->id == 6 ? ' selected="selected"' : '' ?>><?= Yii::t('ad', $category->name) ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="field-wrap" style="display: none;">
							<label>Round number</label>
							<select name="round" id="round">
								<option value="-1">Giữ nguyên</option>
								<option value="0" selected="selected">0</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
							</select>
						</div>
						<div class="field-wrap">
							<label>Ngày đăng</label>
							<input class="datepicker big-field" type="text" placeholder="From" name="date-from" id="date-from" />
							<input class="datepicker big-field" type="text" placeholder="To" name="date-to" id="date-to" />
						</div>
					</div>
				</div>
			</div>
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