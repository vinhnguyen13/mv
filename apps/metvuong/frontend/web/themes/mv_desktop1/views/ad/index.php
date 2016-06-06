<?php 
	use yii\helpers\Url;
	use vsoft\ad\models\AdCategoryGroup;
	use yii\helpers\Html;
	use vsoft\ad\models\AdProduct;
	use yii\web\View;
	
	$db = Yii::$app->getDb();
	
	$hideSearchForm = Yii::$app->request->get('s') || (Yii::$app->request->get('page', 1) != 1);
	
	$compress = isset(Yii::$app->params['local']) ? '' : '.compress';
	
	$resourceListingMap = Yii::$app->view->theme->baseUrl . '/resources/js/map' . $compress . '.js';
	$resourceHistoryJs = Yii::$app->view->theme->baseUrl . '/resources/js/history.js';
	$resourceApi = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=desktop.loadedResource&libraries=geometry';
	
	$autoFillValue = $searchModel->getAutoFillValue();
	$actionId = ($searchModel->type == AdProduct::TYPE_FOR_SELL) ? Yii::t('url', 'nha-dat-ban') : Yii::t('url', 'nha-dat-cho-thue');
	$loadProjectUrl = Url::to(['/ad/get-project']);
	
	$script = <<<EOD
	var resources = ['$resourceHistoryJs', '$resourceListingMap', '$resourceApi'];
	var actionId = '$actionId';
	var loadProjectUrl = '$loadProjectUrl';
EOD;
	
	$this->registerCss('.infor-duan-suggest .img-intro {margin-bottom: -6px;} #map-wrap {position: relative;} #progress-bar {transition: width 0.1s; display: none; top:0; position: absolute; width: 0%; height: 2px; background: #00a769;} #search-list .center {text-align: center; margin: 10px 0; opacity: 0.6;} #search-list .hint {padding: 8px 20px; display: block; background: #00a769; color: #FFF;} .gm-style-mtc {opacity: 0.8;} .gm-style-mtc:hover {opacity: 1;} .gm-style {font-family: "open-sans", monospace} .dt-pagination { display: block; margin-top: 12px; margin-bottom: 6px; } #map-search-wrap {position: relative; width: 220px;} #map-search {height: 40px; line-height: 40px; font-size: 14px; padding: 0 8px 0 8px; width: 100%;} #search-list {position: absolute; background: #FFF; box-shadow: 1px 2px 2px rgba(0, 0, 0, 0.3); font-size: 15px; white-space: nowrap; z-index: 1;} #search-list a {display: block; padding: 6px 12px; border-top: 1px solid rgba(0, 0, 0, 0.1);} #search-list .active a {background: #F5F5F5;} @media (max-width: 985px) {#map-search-wrap {width: 100%;} #search-list{ width: 100%; white-space: initial;}}');
	$this->registerCss(".draw-wrap{padding-top: 10px; opacity: 0.8;} .draw-wrap:hover {opacity: 1;} .draw-wrap .button {text-decoration: none; cursor: pointer; display: block; font-family: Roboto, Arial, sans-serif; -webkit-user-select: none; font-size: 11px; height: 31px; border-radius: 2px; -webkit-background-clip: padding-box; box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px; font-weight: 500; background-color: rgb(255, 255, 255); background-clip: padding-box; line-height: 31px; overflow: hidden; padding: 0 8px;} .draw-wrap:hover .button {background: rgb(235, 235, 235), color: #000;} .draw-wrap .icon-edit-copy-4 {font-size: 14px; vertical-align: middle;}.draw-wrap .icon-mv {margin-right: 4px; float:left;}.draw-wrap .remove-button {display: none;}.draw-wrap-drawing .draw-button {display: none}.draw-wrap-drawing .remove-button {display: block}");
	$this->registerJs($script, View::POS_BEGIN);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/string-helper.js', ['position'=>View::POS_HEAD]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/listing' . $compress . '.js', ['position' => View::POS_END]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/swiper.jquery.min.js', ['position'=>View::POS_END]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery.rateit.js', ['position'=>View::POS_END]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/clipboard.min.js', ['position'=>View::POS_END]);
?>
<div class="result-listing clearfix">
	<div class="wrap-listing-item">
		<div class="items-list">
			<div class="inner-wrap">
				<form id="search-form" action="/<?= $actionId ?>" method="get">
					<div class="search-subpage">
						<div class="advande-search clearfix">
							<div class="toggle-search"<?= $hideSearchForm ? ' style="display: none"' : '' ?>>
								<div class="frm-item select-location show-num-frm">
									<div id="map-search-wrap">
										<input class="exclude" type="text" id="map-search" value="<?= $autoFillValue ?>" data-val="<?= $autoFillValue ?>" autocomplete="off" />
										<div id="search-list" class="hide">
											<div class="hint-wrap">
												<div class="hint"><?= Yii::t('ad', 'Nhập tên dự án, đường, phường, quận hoặc thành phố.') ?></div>
												<div class="center"><?= Yii::t('ad', 'Tìm kiếm gần đây') ?></div>
											</div>
											<ul></ul>
										</div>
										<?= Html::activeHiddenInput($searchModel, 'city_id', ['class' => 'auto-fill']); ?>
										<?= Html::activeHiddenInput($searchModel, 'district_id', ['class' => 'auto-fill']); ?>
										<?= Html::activeHiddenInput($searchModel, 'ward_id', ['class' => 'auto-fill']); ?>
										<?= Html::activeHiddenInput($searchModel, 'street_id', ['class' => 'auto-fill']); ?>
										<?= Html::activeHiddenInput($searchModel, 'project_building_id', ['class' => 'auto-fill']); ?>
									</div>
								</div>
								<div class="frm-item select-loaibds">
									<div class="box-dropdown dropdown-common">
										<div class="val-selected style-click">
											<span class="selected" data-placeholder="<?= Yii::t('ad', 'Property Types') ?>"><?= Yii::t('ad', 'Property Types') ?></span>
											<span class="arrowDownFillFull"></span>
										</div>
										<div class="item-dropdown hide-dropdown">
											<ul class="clearfix loai-bds">
												<?php
													$groupCategories = $db->cache(function(){
														return AdCategoryGroup::find()->all();
													});
													
													foreach ($groupCategories as $groupCategory):
												?>
												<li><a href="#" data-value="<?= implode(',', $groupCategory->categories_id) ?>" data-order="3"><?= Yii::t('ad', $groupCategory['name']) ?></a></li>
												<?php endforeach; ?>
											</ul>
											<?= Html::activeHiddenInput($searchModel, 'category_id', ['id' => 'loai-bds']); ?>
										</div>
									</div>
								</div>
								<div class="frm-item num-phongngu">
									<div class="box-dropdown dropdown-common">
										<div class="val-selected style-click" data-text-add="<?= Yii::t('ad', 'Beds') ?>"><span class="selected">0+ <?= Yii::t('ad', 'Beds') ?></span><span class="arrowDownFillFull"></span></div>
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
										<div class="val-selected style-click" data-text-add="<?= Yii::t('ad', 'Bath') ?>"><span class="selected">0+ <?= Yii::t('ad', 'Bath') ?></span><span class="arrowDownFillFull"></span></div>
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
								<div class="frm-item choice_price_dt select-price">
									<div class="box-dropdown" data-item-minmax="prices">
										<div class="val-selected style-click price-search">
											<span class="txt-holder-minmax"><?= Yii::t('ad', 'Price') ?> </span>
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
												<span class="txt-min min-max active min-val" data-value="" data-text="<?= Yii::t('ad', 'Min') ?>"><?= Yii::t('ad', 'Min') ?></span>
												<span class="text-center"><span></span></span>
												<span class="txt-max min-max max-val" data-value="" data-text="<?= Yii::t('ad', 'Max') ?>"><?= Yii::t('ad', 'Max') ?></span>
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
												<span class="txt-min min-max active min-val" data-value=""><?= Yii::t('ad', 'Min') ?></span>
												<span class="text-center"><span></span></span>
												<span class="txt-max min-max max-val" data-value=""><?= Yii::t('ad', 'Max') ?></span>
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
								<div class="frm-item select-others show-num-frm">
									<div class="box-dropdown dropdown-common">
										<div class="val-selected style-click">
											<span class="selected" data-placeholder="Loại bất động sản?"><?= Yii::t('ad', 'More') ?></span>
											<span class="arrowDownFillFull"></span>
										</div>
										<div class="item-dropdown hide-dropdown">
											<?= Html::activeHiddenInput($searchModel, 'type', ['name' => '']); ?>
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
														$tDays = Yii::t('ad', 'days');
														$tMonth = Yii::t('ad', 'month');
														$items = [
															"-1 day" => "1 " . Yii::t('ad', 'day'),
															"-7 day" => "7 " . $tDays,
															"-14 day" => "14 " . $tDays,
															"-30 day" => "30 " . $tDays,
															"-60 day" => "60 " . $tDays,
															"-90 day" => "90 " . $tDays,
															"-6 month" => "6 " . $tMonth,
															"-12 month" => "12 " . $tMonth,
															"-24 month" => "24 " . $tMonth,
															"-36 month" => "36 " . $tMonth,
														];
													?>
													<?= Html::activeDropDownList($searchModel, 'created_before', $items, ['prompt' => Yii::t('ad', 'Time posted'), 'class' => 'form-control']) ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<button class="btn-submit btn-common <?= $hideSearchForm ? '' : 'active' ?>"><?= Yii::t('ad', 'Search') ?></button>
						</div>
					</div>
					<div id="project-info">
						<?php if($searchModel->project_building_id): ?>
						<?= $this->render('_partials/projectInfo', ['project' => $searchModel->projectBuilding]) ?>
						<?php endif; ?>
					</div>
					<div id="sort" class="dropdown-select option-show-listing">
						<div class="val-selected style-click clearfix">
							<?php
								$items = [
									'-score' => Yii::t('ad', 'Point'),
									'-updated_at' => Yii::t('ad', 'Newest'),
									'-price' =>Yii::t('ad', 'Price (High to Low)'),
									'price' =>Yii::t('ad', 'Price (Low to Hight)'),
								];
							?>
							<?= Html::activeDropDownList($searchModel, 'order_by', $items, ['prompt' => Yii::t('ad', 'Sort by'), 'class' => 'form-control']) ?>
						</div>
					</div>
					<input type="hidden" name="s" value="1" />
					<div style="display: none;" id="af-wrap">
						<?= Html::activeHiddenInput($searchModel, 'rm', ['disabled' => true]); ?>
						<input type="hidden" id="rl" name="rl" />
						<?= Html::activeHiddenInput($searchModel, 'ra', ['disabled' => true]); ?>
						<?= Html::activeHiddenInput($searchModel, 'rect', ['disabled' => true]); ?>
						<?= Html::activeHiddenInput($searchModel, 'ra_k', ['disabled' => true]); ?>
						<?= Html::activeHiddenInput($searchModel, 'iz', ['disabled' => true]); ?>
						<?= Html::activeHiddenInput($searchModel, 'z', ['disabled' => true]); ?>
						<?= Html::activeHiddenInput($searchModel, 'c', ['disabled' => true]); ?>
						<?= Html::activeHiddenInput($searchModel, 'did', ['disabled' => true]); ?>
						<?= Html::activeHiddenInput($searchModel, 'page', ['disabled' => true]); ?>
					</div>
				</form>
				<div style="position: relative;">
					<div class="wrap-listing clearfix">
						<div id="content-holder"><?= $this->render('_partials/side-list', ['searchModel' => $searchModel, 'list' => $list]) ?></div>
						<div id="loading-list" class="loading-proccess" style="display: none; position: absolute;top: 0px;left: 0px;right: 0;bottom: 0;z-index: 999;background: rgba(0, 0, 0, 0.5);"><span></span></div>
					</div>
				</div>
			</div>
			<div class="detail-listing-dt">
				<div class="inner-detail-listing">
					<div class="bar-top-detail">
						<a href="#" class="close-slide-detail font-700 fs-14 color-cd-hover"><span class="icon-mv fs-12 mgR-5"><span class="icon-close-icon"></span></span><?= Yii::t('ad', 'Close') ?></a>
						<a href="#" class="btn-extra font-700 fs-14 color-cd-hover" target="_blank"><span class="icon-mv fs-14 mgR-5"><span class="icon-up-arrow"></span></span><?= Yii::t('ad', 'Expand') ?></a>
					</div>
					<div class="detail-listing" style="position: relative;">
						<div class="container"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="wrap-map-listing">
		<div id="map-wrap" class="inner-wrap">
			<div id="map" class="inner-wrap"></div>
			<div id="progress-bar"><div id="progress-bar-p"></div></div>
		</div>
	</div>
</div>