<?php
use frontend\models\Tracking;
use vsoft\ad\models\AdImages;
use vsoft\express\components\StringHelper;
use vsoft\ad\models\AdCategory;
use yii\helpers\Url;
use vsoft\ad\models\AdProduct;
use yii\web\View;
use yii\helpers\Html;
use vsoft\ad\models\AdWard;
use yii\helpers\ArrayHelper;
use vsoft\ad\models\AdStreet;
use yii\widgets\LinkPager;
use common\models\AdCity;
use vsoft\ad\models\AdDistrict;
use vsoft\ad\models\AdBuildingProject;

$this->registerCssFile(Yii::$app->view->theme->baseUrl . '/resources/css/select2.min.css');
$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/select2.full.min.js', ['position' => View::POS_END]);
$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/listing.js', ['position' => View::POS_END]);

$categories = AdCategory::find ()->indexBy ( 'id' )->asArray ( true )->all ();

$hideSearchForm = Yii::$app->request->get('s') || (Yii::$app->request->get('page', 1) != 1);


$resourceListingMap = Html::encode(Yii::$app->view->theme->baseUrl . '/resources/js/listing-map.js');
$resourceGmapV2 = Html::encode(Yii::$app->view->theme->baseUrl . '/resources/js/gmap-v2.js');
$resourceApi = Html::encode('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4');

$categoryCHCK = AdCategory::CATEGORY_CHCK;

$script = <<<EOD
	var CATEGORY_CHCK = $categoryCHCK;
	var resources = ['$resourceListingMap', '$resourceGmapV2', '$resourceApi'];
EOD;

$this->registerJs($script, View::POS_BEGIN);
// $srcGmapV2 = Yii::$app->view->theme->baseUrl . '/resources/js/gmap-v2.js';

// $params = Yii::$app->request->get();
// if(!empty($params['street_id']) || !empty($params['project_building_id'])) {
// 	$initialZoom = 'zoomLevel.district.max';
// 	$areaId = $searchModel->district_id;
// } else if(!empty($params['ward_id'])) {
// 	$initialZoom = 'zoomLevel.ward.max';
// 	$areaId = $searchModel->ward_id;
// } else if(!empty($params['district_id'])) {
// 	$initialZoom = 'zoomLevel.district.max';
// 	$areaId = $params['district_id'];
// } else {
// 	$initialZoom = 'zoomLevel.city.max';
// 	$areaId = $searchModel->city_id;
// }

// $script = <<<EOD
// 	var zoomLevel = {
// 		detail: {min: 17, max: 21},
// 		ward: {min: 15, max: 16},
// 		district: {min: 12, max: 14},
// 		city: {min: 0, max: 11}
// 	};
// 	var srcApi = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=map.apiLoaded';
// 	var srcGmapV2 = '$srcGmapV2';
// 	var areaId = $areaId;
// 	var initialZoom = $initialZoom;
// EOD;

// $this->registerJs($script, View::POS_BEGIN);
?>

<div class="result-listing clearfix">
	<div class="wrap-listing-item">
		<div class="inner-wrap">
			<form id="search-form" action="<?= Url::to(['/ad/index']) ?>" method="get">
				<!-- <div class="list-choice-city" data-prev-section="true">
					<ul class="clearfix"></ul>
					<em class="icon-pencil"></em>
				</div> -->
				<div class="search-subpage">
					<div class="advande-search clearfix">
						<div class="toggle-search"<?= $hideSearchForm ? ' style="display: none"' : '' ?>>
							<div class="frm-item select-location">
								<div class="box-dropdown dropdown-common">
									<div class="val-selected style-click" data-text-add="Locations"><span class="selected">Locations</span><span class="arrowDownFillFull"></span></div>
									<div class="item-dropdown hide-dropdown">
										<div class="form-group col-xs-12 col-sm-6">
											<div class="">
												<?php 
													$cities = AdCity::find()->all();
													$citiesDropdown = ArrayHelper::map($cities, 'id', 'name');
													$citiesOptions = ArrayHelper::map($cities, 'id', function($city){ return ['disabled' => ($city->id != AdProduct::DEFAULT_CITY)]; });
												?>
												<?= Html::activeDropDownList($searchModel, 'city_id', $citiesDropdown, ['class' => 'form-control select2 style-common', 'options' => $citiesOptions]) ?>
											</div>
										</div>
										<div class="form-group col-xs-12 col-sm-6">
											<div class="">
												<?php 
													$items = AdDistrict::getListByCity($searchModel->city_id);
												?>
												<?= Html::activeDropDownList($searchModel, 'district_id', $items, ['prompt' => $searchModel->getAttributeLabel('district_id'), 'class' => 'form-control select2 style-common']) ?>
											</div>
										</div>
										<div class="form-group col-xs-12 col-sm-6">
											<div class="">
												<?php 
													$items = $searchModel->district_id ? AdWard::getListByDistrict($searchModel->district_id) : [];
												?>
												<?= Html::activeDropDownList($searchModel, 'ward_id', $items, ['prompt' => $searchModel->getAttributeLabel('ward_id'), 'class' => 'form-control select2 style-common']) ?>
											</div>
										</div>
										<div class="form-group col-xs-12 col-sm-6">
											<div class="">
												<?php 
													$items = $searchModel->district_id ? AdStreet::getListByDistrict($searchModel->district_id) : [];
												?>
												<?= Html::activeDropDownList($searchModel, 'street_id', $items, ['prompt' => $searchModel->getAttributeLabel('street_id'), 'class' => 'form-control select2 style-common']) ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="frm-item num-phongngu">
								<div class="box-dropdown dropdown-common">
									<div class="val-selected style-click" data-text-add="Beds"><span class="selected">0+ Beds</span><span class="arrowDownFillFull"></span></div>
									<div class="item-dropdown item-bed-bath hide-dropdown">
										<ul class="clearfix">
											<li><a href="#" data-value="1">1+</a></li>
											<li><a href="#" data-value="2">2+</a></li>
											<li><a href="#" data-value="3">3+</a></li>
											<li><a href="#" data-value="4">4+</a></li>
											<li><a href="#" data-value="5">5+</a></li>
											<li><a href="#" data-value="6">6+</a></li>
											<li><a href="#" data-value="7">7+</a></li>
											<li><a href="#" data-value="8">8+</a></li>
											<li><a href="#" data-value="9">9+</a></li>
											<li><a href="#" data-value="10">10+</a></li>
										</ul>
										<?= Html::activeHiddenInput($searchModel, 'room_no'); ?>
									</div>
								</div>
							</div>
							<div class="frm-item num-phongtam">
								<div class="box-dropdown dropdown-common">
									<div class="val-selected style-click" data-text-add="Bath"><span class="selected">0+ Bath</span><span class="arrowDownFillFull"></span></div>
									<div class="item-dropdown item-bed-bath hide-dropdown">
										<ul class="clearfix">
											<li><a href="#" data-value="1">1+</a></li>
											<li><a href="#" data-value="2">2+</a></li>
											<li><a href="#" data-value="3">3+</a></li>
											<li><a href="#" data-value="4">4+</a></li>
											<li><a href="#" data-value="5">5+</a></li>
											<li><a href="#" data-value="6">6+</a></li>
											<li><a href="#" data-value="7">7+</a></li>
											<li><a href="#" data-value="8">8+</a></li>
											<li><a href="#" data-value="9">9+</a></li>
											<li><a href="#" data-value="10">10+</a></li>
										</ul>
										<?= Html::activeHiddenInput($searchModel, 'toilet_no'); ?>
									</div>
								</div>
							</div>
							<!-- <div class="frm-item select-tinh-thanh">
								<div class="box-dropdown dropdown-common">
									<div class="val-selected style-click">
										<span class="selected" data-placeholder="<?= Yii::t('ad', 'City') ?>"><?= Yii::t('ad', 'City') ?></span>
										<span class="pull-right icon arrowDown"></span>
									</div>
									<div class="item-dropdown hide-dropdown">
										<ul class="clearfix tinh-thanh"></ul>
										<?= Html::activeHiddenInput($searchModel, 'city_id', ['id' => 'tinh-thanh']); ?>
									</div>
								</div>
							</div> -->
							<!-- <div class="frm-item select-quan-huyen">
								<div class="box-dropdown dropdown-common">
									<div class="val-selected style-click">
										<span class="selected" data-placeholder="<?= Yii::t('ad', 'District') ?>"><?= Yii::t('ad', 'District') ?></span>
										<span class="pull-right icon arrowDown"></span>
									</div>
									<div class="item-dropdown hide-dropdown">
										<ul class="clearfix quan-huyen"></ul>
										<?= Html::activeHiddenInput($searchModel, 'district_id', ['id' => 'quan-huyen']); ?>
									</div>
								</div>
							</div> -->
							<div class="frm-item select-loaibds">
								<div class="box-dropdown dropdown-common">
									<div class="val-selected style-click">
										<span class="selected" data-placeholder="<?= Yii::t('ad', 'Property Types') ?>"><?= Yii::t('ad', 'Property Types') ?></span>
										<span class="arrowDownFillFull"></span>
									</div>
									<div class="item-dropdown hide-dropdown">
										<ul class="clearfix loai-bds">
											<?php
												foreach ($categories as $categoryId => $category):
													if($category['apply_to_type'] == $searchModel->type || $category['apply_to_type'] == AdCategory::APPLY_TO_TYPE_BOTH):
											?>
											<li><a href="#" data-value="<?= $categoryId ?>" data-order="3"><?= $category['name'] ?></a></li>
											<?php endif; endforeach; ?>
										</ul>
										<?= Html::activeHiddenInput($searchModel, 'category_id', ['id' => 'loai-bds']); ?>
									</div>
								</div>
							</div>
							
							<div class="select-duan frm-item">
								<div class="wrap-duan">
									<?php
										$items = [];
										if($searchModel->district_id) {
											$items = ($projects = AdBuildingProject::find()->where(['status' => 1, 'district_id' => $searchModel->district_id])->all()) ? ArrayHelper::map($projects, 'id', 'name') : [];
										}
										
										$options = ['prompt' => $searchModel->getAttributeLabel('project_building_id'), 'class' => 'form-control select2'];
										
										if($searchModel->category_id != AdCategory::CATEGORY_CHCK) {
											$options['class'] = $options['class'] . ' hide';
										}
										
										echo Html::activeDropDownList($searchModel, 'project_building_id', $items, $options)
									?>
								</div>
							</div>
								
							<div class="frm-item choice_price_dt select-price">
								<div class="box-dropdown" data-item-minmax="prices">
									<div class="val-selected style-click price-search">
										<span class="txt-holder-minmax"><?= Yii::t('ad', 'Price') ?> </span>
										<!-- <div>
											<span class="tu"><?= Yii::t('ad', 'from') ?></span>
											<span class="wrap-min"></span>
											<span class="trolen"><?= Yii::t('ad', 'above') ?></span>
											<span class="den"><?= Yii::t('ad', 'to') ?></span>
											<span class="wrap-max"></span>
											<span class="troxuong"><?= Yii::t('ad', 'below') ?></span>
										</div> -->
										<div>
											<span class="wrap-min"></span>
											<span class="trolen">+</span>
											<span class="den">-</span>
											<span class="wrap-max"></span>
											<span class="troxuong"><?= Yii::t('ad', 'below') ?></span>
										</div>
										<span class="arrowDownFillFull"></span>
									</div>
									<div class="item-dropdown hide-dropdown wrap-min-max">
										<div class="box-input clearfix">
											<span class="txt-min min-max active min-val" data-value="" data-text="Thấp nhất"><?= Yii::t('ad', 'Min') ?></span>
											<span class="text-center"><span></span></span>
											<span class="txt-max min-max max-val" data-value="" data-text="Cao nhất"><?= Yii::t('ad', 'Max') ?></span>
											<?= Html::activeHiddenInput($searchModel, 'price_min', ['id' => 'priceMin']); ?>
											<?= Html::activeHiddenInput($searchModel, 'price_max', ['id' => 'priceMax']); ?>
										</div>
										<div class="filter-minmax clearfix">
											<ul data-wrap-minmax="min-val" class="wrap-minmax clearfix"></ul>
											<ul data-wrap-minmax="max-val" class="wrap-minmax clearfix"></ul>
										</div>
									</div>
								</div>
							</div>
							<div class="frm-item choice_price_dt select-dt">
								<div class="box-dropdown" data-item-minmax="area">
									<div class="val-selected style-click dt-search">
										<span class="txt-holder-minmax"><?= Yii::t('ad', 'Size') ?></span>
										<!-- <div>
											<span class="tu">từ</span>
											<span class="wrap-min">1 tỷ</span>
											<span class="trolen">trở lên</span>
											<span class="den">đến</span>
											<span class="wrap-max">4 tỷ</span>
											<span class="troxuong">trở xuống</span>
										</div> -->
										<div>
											<span class="wrap-min"></span>
											<span class="trolen">+</span>
											<span class="den">-</span>
											<span class="wrap-max"></span>
											<span class="troxuong"><?= Yii::t('ad', 'below') ?></span>
										</div>
										<span class="arrowDownFillFull"></span>
									</div>
									<div class="item-dropdown hide-dropdown wrap-min-max">
										<div class="box-input clearfix">
											<span class="txt-min min-max active min-val" data-value="">Thấp nhất</span>
											<span class="text-center"><span></span></span>
											<span class="txt-max min-max max-val" data-value="">Cao nhất</span>
											<?= Html::activeHiddenInput($searchModel, 'size_min', ['id' => 'dtMin']); ?>
											<?= Html::activeHiddenInput($searchModel, 'size_max', ['id' => 'dtMax']); ?>
										</div>
										<div class="filter-minmax clearfix">
											<ul data-wrap-minmax="min-val" class="wrap-minmax"></ul>
											<ul data-wrap-minmax="max-val" class="wrap-minmax"></ul>
										</div>
									</div>
								</div>
							</div>
							<!-- <div class="frm-item clearfix select-bed-bath">
								<div class="col-xs-6 num-phongngu">
									<div class="box-dropdown dropdown-common">
										<div class="val-selected style-click" data-text-add="Phòng ngủ trở lên"><span class="selected">Phòng ngủ</span><span class="pull-right icon arrowDown"></span></div>
										
										<div class="item-dropdown item-bed-bath hide-dropdown">
											<ul class="clearfix">
												<li><a href="#">1</a></li>
												<li><a href="#">2</a></li>
												<li><a href="#">3</a></li>
												<li><a href="#">4</a></li>
												<li><a href="#">5</a></li>
												<li><a href="#">6</a></li>
												<li><a href="#">7</a></li>
												<li><a href="#">8</a></li>
												<li><a href="#">9</a></li>
												<li><a href="#">10</a></li>
											</ul>
											<input type="hidden" id="val-bed" class="value_selected" name="roomNo" value="<?= Yii::$app->request->get('roomNo') ?>" />
										</div>
									</div>
								</div>
								<div class="col-xs-6 num-phongtam">
									<div class="box-dropdown dropdown-common">
										<div class="val-selected style-click" data-text-add="Phòng tắm trở lên"><span class="selected">Phòng tắm</span><span class="pull-right icon arrowDown"></span></div>
										<div class="item-dropdown item-bed-bath hide-dropdown">
											<ul class="clearfix">
												<li><a href="#">1</a></li>
												<li><a href="#">2</a></li>
												<li><a href="#">3</a></li>
												<li><a href="#">4</a></li>
												<li><a href="#">5</a></li>
												<li><a href="#">6</a></li>
												<li><a href="#">7</a></li>
												<li><a href="#">8</a></li>
												<li><a href="#">9</a></li>
												<li><a href="#">10</a></li>
											</ul>
											<input type="hidden" id="val-bath" class="value_selected" name="toiletNo" value="<?= Yii::$app->request->get('toiletNo') ?>" />
										</div>
									</div>
								</div>
							</div> -->
							<div class="frm-item select-others">
								<div class="box-dropdown dropdown-common">
									<div class="val-selected style-click">
										<span class="selected" data-placeholder="Loại bất động sản?"><?= Yii::t('ad', 'More') ?></span>
										<span class="arrowDownFillFull"></span>
									</div>
									<div class="item-dropdown hide-dropdown">
										<?= Html::activeHiddenInput($searchModel, 'type') ?>
										
										<div class="form-group col-xs-12 col-sm-6">
											<div class="">
												<?php 
													$items = [AdProduct::OWNER_HOST => Yii::t('ad', 'Owner'), AdProduct::OWNER_AGENT => Yii::t('ad', 'Agent')];
												?>
												<?= Html::activeDropDownList($searchModel, 'owner', $items, ['prompt' => Yii::t('ad', Yii::t('ad', 'Owner') . '/' . Yii::t('ad', 'Agent')), 'class' => 'form-control']) ?>
											</div>
										</div>
										<div class="form-group col-xs-12 col-sm-6">
											<div class="">
												<?php
													$tDay = Yii::t('ad', 'day');
													$tMonth = Yii::t('ad', 'month');
													$items = [
														"-1 day" => "1 " . $tDay,
														"-7 day" => "7 " . $tDay,
														"-14 day" => "14 " . $tDay,
														"-30 day" => "30 " . $tDay,
														"-60 day" => "60 " . $tDay,
														"-90 day" => "90 " . $tDay,
														"-6 month" => "6 " . $tMonth,
														"-12 month" => "12 " . $tMonth,
														"-24 month" => "24 " . $tMonth,
														"-36 month" => "36 " . $tMonth,
													];
												?>
												<?= Html::activeDropDownList($searchModel, 'created_before', $items, ['prompt' => Yii::t('ad', 'Any time'), 'class' => 'form-control']) ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- <div class="frm-item row">
								<div class="col-xs-12 other-fill">
									<div class="value-selected style-click">Thêm tuỳ chọn<span class="pull-right icon arrowDown"></span></div>
								</div>
							</div> -->
						</div>
						
						<button class="btn-submit btn-common <?= $hideSearchForm ? '' : 'active' ?>">Tìm kiếm</button>
					</div>
				</div>
				<div id="sort" class="dropdown-select option-show-listing">
					<div class="val-selected style-click clearfix">
						<?php
							$items = [
								'-created_at' => Yii::t('ad', 'Newest'),
								'-price' =>Yii::t('ad', 'Price (High to Low)'),
								'price' =>Yii::t('ad', 'Price (Low to Hight)'),
							];
						?>
						<?= Html::activeDropDownList($searchModel, 'order_by', $items, ['prompt' => Yii::t('ad', 'Sort by'), 'class' => 'form-control']) ?>
					</div>
				</div>
				<input type="hidden" name="s" value="1" />
			</form>
			<div class="wrap-listing clearfix">
				<div id="content-holder">
					<?php if(count($products) > 0): ?>
					<div class="top-listing clearfix">
						<p><span id="count-from"><?= $pages->offset + 1 ?></span> - <span id="count-to"><?= $pages->offset + count($products) ?></span> Tin từ <span id="count-total"><?= $pages->totalCount ?></span> Tin</p>
					</div>
					<div id="listing-list" class="wrap-lazy">
						<ul class="clearfix">
							<?= $this->render('_partials/list', ['products' => $products, 'categories' => $categories]) ?>
						</ul>
						<div id="loading-list" class="hide" style="text-align: center;"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/loading-listing.gif' ?>" /></div>
						<nav class="text-center dt-pagination">
				            <?php
				                echo LinkPager::widget([
				                    'pagination' => $pages,
									'maxButtonCount' => 5
				                ]);
				            ?>
			            </nav>
					</div>
					<?php else: ?>
					<div class="container" id="no-result">Chưa có tin đăng theo tìm kiếm của bạn, <a href="#">đăng ký nhận thông báo khi có tin đăng phù hợp</a>.</div>
					<?php endif; ?>
				</div>

				<div class="detail-listing-dt">
					<div class="detail-listing" style="position: relative;">
						<a href="#" class="btn-close"><span class="icon icon-close"></span></a>
						<div class="container"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="wrap-map-listing">
		<div id="map" class="inner-wrap"></div>
	</div>
</div>