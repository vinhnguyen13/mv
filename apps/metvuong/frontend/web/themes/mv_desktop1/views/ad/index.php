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

$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/gmap-v2.js', ['position' => View::POS_END]);
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=m2Map.loaded', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/listing.js', ['position' => View::POS_END]);

$categories = AdCategory::find ()->indexBy ( 'id' )->asArray ( true )->all ();
$types = AdProduct::getAdTypes ();

$products = $dataProvider->models;
$pages = $dataProvider->pagination;

$isSearch = Yii::$app->request->get('s');
?>

<div class="result-listing clearfix">
	<div class="wrap-listing-item">
		<div class="inner-wrap">
			<a href="#" class="toggle-listing">open</a>
			<form id="search-form" action="<?= Url::to(['/ad/index']) ?>" method="get">
				<!-- <div class="list-choice-city" data-prev-section="true">
					<ul class="clearfix"></ul>
					<em class="icon-pencil"></em>
				</div> -->
				<div class="search-subpage">
					<div class="advande-search clearfix">
						<div class="toggle-search"<?= $isSearch ? ' style="display: none"' : '' ?>>
							<div class="frm-item select-tinh-thanh">
								<div class="box-dropdown dropdown-common">
									<div class="val-selected style-click">
										<span class="selected" data-placeholder="Bạn ở tỉnh/thành nào?">Bạn ở tỉnh/thành nào?</span>
										<span class="pull-right icon arrowDown"></span>
									</div>
									<div class="item-dropdown hide-dropdown">
										<ul class="clearfix tinh-thanh"></ul>
										<?= Html::activeHiddenInput($searchModel, 'city_id', ['id' => 'tinh-thanh']); ?>
									</div>
								</div>
							</div>
							<div class="frm-item select-quan-huyen">
								<div class="box-dropdown dropdown-common">
									<div class="val-selected style-click">
										<span class="selected" data-placeholder="Bạn ở quận/huyện nào?">Bạn ở quận/huyện nào?</span>
										<span class="pull-right icon arrowDown"></span>
									</div>
									<div class="item-dropdown hide-dropdown">
										<ul class="clearfix quan-huyen"></ul>
										<?= Html::activeHiddenInput($searchModel, 'district_id', ['id' => 'quan-huyen']); ?>
									</div>
								</div>
							</div>
							<div class="frm-item select-loaibds">
								<div class="box-dropdown dropdown-common">
									<div class="val-selected style-click">
										<span class="selected" data-placeholder="Loại bất động sản?">Loại bất động sản?</span>
										<span class="pull-right icon arrowDown"></span>
									</div>
									<div class="item-dropdown hide-dropdown">
										<ul class="clearfix loai-bds"></ul>
										<?= Html::activeHiddenInput($searchModel, 'category_id', ['id' => 'loai-bds']); ?>
									</div>
								</div>
							</div>
							<div id="du-an-select" class="select-duan frm-item <?= Yii::$app->request->get('project_building') ? '' : 'hide' ?>">
								<div class="box-dropdown dropdown-common">
									<div class="val-selected style-click">
										<span class="selected" data-placeholder="Dự án bạn đang tìm?">Dự án bạn đang tìm?</span>
										<span class="pull-right icon arrowDown"></span>
									</div>
									<div class="item-dropdown hide-dropdown">
										<ul class="clearfix du-an"></ul>
										<?= Html::activeHiddenInput($searchModel, 'project_building_id', ['id' => 'du-an']); ?>
									</div>
								</div>
							</div>
							<div class="frm-item choice_price_dt select-price" data-item-minmax="prices">
								<div class="box-dropdown">
									<div class="val-selected style-click price-search">
										Giá 
										<div>
											<span class="tu">từ</span>
											<span class="wrap-min">1 tỷ</span>
											<span class="trolen">trở lên</span>
											<span class="den">đến</span>
											<span class="wrap-max">4 tỷ</span>
											<span class="troxuong">trở xuống</span>
										</div>
										<span class="pull-right icon arrowDown"></span>
									</div>
									<div class="item-dropdown hide-dropdown wrap-min-max">
										<div class="box-input clearfix">
											<span class="txt-min min-max active min-val" data-value="">Thấp nhất</span>
											<span class="text-center">Đến</span>
											<span class="txt-max min-max max-val" data-value="">Cao nhất</span>
											<?= Html::activeHiddenInput($searchModel, 'price_min', ['id' => 'priceMin']); ?>
											<?= Html::activeHiddenInput($searchModel, 'price_max', ['id' => 'priceMax']); ?>
										</div>
										<div class="filter-minmax clearfix">
											<ul data-wrap-minmax="min-val" class="wrap-minmax"></ul>
											<ul data-wrap-minmax="max-val" class="wrap-minmax"></ul>
										</div>
									</div>
								</div>
							</div>
							<div class="frm-item choice_price_dt select-dt" data-item-minmax="area">
								<div class="box-dropdown">
									<div class="val-selected style-click dt-search">
										Diện tích
										<div>
											<span class="tu">từ</span>
											<span class="wrap-min">1 tỷ</span>
											<span class="trolen">trở lên</span>
											<span class="den">đến</span>
											<span class="wrap-max">4 tỷ</span>
											<span class="troxuong">trở xuống</span>
										</div>
										<span class="pull-right icon arrowDown"></span>
									</div>
									<div class="item-dropdown hide-dropdown wrap-min-max">
										<div class="box-input clearfix">
											<span class="txt-min min-max active min-val" data-value="">Thấp nhất</span>
											<span class="text-center">Đến</span>
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
										<span class="selected" data-placeholder="Loại bất động sản?">Nâng Cao</span>
										<span class="pull-right icon arrowDown"></span>
									</div>
									<div class="item-dropdown hide-dropdown">
										<div class="form-group">
											<label for="" class="col-sm-4 control-label"><?= $searchModel->getAttributeLabel('ward_id') ?></label>
											<div class="col-sm-8">
												<?php 
													$items = $searchModel->district_id ? ArrayHelper::map($searchModel->district->adWards, 'id', function ($model) { return $model->pre . ' ' . $model->name; }) : [];
												?>
												<?= Html::activeDropDownList($searchModel, 'ward_id', $items, ['prompt' => $searchModel->getAttributeLabel('ward_id'), 'class' => 'form-control']) ?>
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-sm-4 control-label"><?= $searchModel->getAttributeLabel('street_id') ?></label>
											<div class="col-sm-8">
												<?php 
													$items = $searchModel->district_id ? ArrayHelper::map($searchModel->district->adStreets, 'id', function ($model) { return $model->pre . ' ' . $model->name; }) : [];
												?>
												<?= Html::activeDropDownList($searchModel, 'street_id', $items, ['prompt' => $searchModel->getAttributeLabel('street_id'), 'class' => 'form-control']) ?>
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-sm-4 control-label">Người đăng</label>
											<div class="col-sm-8">
												<?php 
													$items = [AdProduct::OWNER_HOST => Yii::t('ad', 'Owner'), AdProduct::OWNER_AGENT => Yii::t('ad', 'Agent')];
												?>
												<?= Html::activeDropDownList($searchModel, 'owner', $items, ['prompt' => Yii::t('ad', 'All'), 'class' => 'form-control']) ?>
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-sm-4 control-label">Ngày đăng</label>
											<div class="col-sm-8">
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
										<div class="form-group">
											<label for="" class="col-sm-4 control-label">Phòng ngủ</label>
											<div class="col-sm-8">
												<?php
													$items = [];
													for ($i = 0; $i <= 10; $i++) {
														$items[$i] = $i . '+';
													}
												?>
												<?= Html::activeDropDownList($searchModel, 'room_no', $items, ['class' => 'form-control']) ?>
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-sm-4 control-label">Phòng tắm</label>
											<div class="col-sm-8">
												<?= Html::activeDropDownList($searchModel, 'toilet_no', $items, ['class' => 'form-control']) ?>
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
						
						<button class="btn-submit btn-common <?= $isSearch ? '' : 'active' ?>">Tìm kiếm</button>
					</div>
				</div>
				<div class="dropdown-select option-show-listing">
					<div class="val-selected style-click clearfix">
						Hiển thị theo <span class="selected">Tin mới nhất</span>
						<span class="icon arrowDown pull-right"></span>
					</div>
					<div class="item-dropdown hide-dropdown">
						<ul>
							<li><a data-value="-created_at" href="#">Tin mới nhất</a></li>
							<li><a data-value="-price" href="#">Giá giảm dần</a></li>
							<li><a data-value="price" href="#">Giá tăng dần</a></li>
							<li><a data-value="score" href="#">Điểm cao nhất</a></li>
						</ul>
						<?= Html::activeHiddenInput($searchModel, 'order_by', ['id' => 'sort', 'class' => 'value_selected']) ?>
					</div>
				</div>
			</form>
			<div class="wrap-listing clearfix">
				<div id="content-holder">
					<?php if(count($products) > 0): ?>
					<div class="top-listing clearfix">
						<p><?= $pages->offset + 1 ?> - <span id="count-to"><?= $pages->offset + count($products) ?></span> Tin từ <?= $pages->totalCount ?> Tin</p>
					</div>
					<div id="listing-list" class="wrap-lazy">
						<ul class="clearfix">
							<?php foreach ($products as $product): ?>
							<li>
								<div class="item-listing">
									<div class="bgcover img-intro">
										<div>
											<a class="rippler rippler-default" href="<?= $product->urlDetail(); ?>"><img src="" data-original="<?= $product->representImage ?>"></a>
										</div>
									</div>
									<div class="attrs-item">
										<div class="wrap-attr-item">
											<p class="infor-by-up">
												<strong><?= ucfirst($categories[$product->category_id]['name']) ?> <?= strtolower($types[$product->type]) ?></strong> bởi Môi Giới
											</p>
											<p class="address-listing">
												<a href="<?= $product->urlDetail(); ?>" title="<?= $product->getAddress($product->show_home_no) ?>"><?= $product->getAddress($product->show_home_no) ?></a>
											</p>
											<p class="id-duan">ID:<span><?= Yii::$app->params['listing_prefix_id'] . $product->id;?></span></p>
											<p class="date-post"><strong><?= date("d/m/Y H:i", $product->created_at) ?></strong></p>
											<div class="clearfix"></div>
											<ul class="clearfix list-attr-td">
												<?= $product->area ? '<li> <span class="icon icon-dt icon-dt-small"></span>' . $product->area . 'm2 </li>' : '' ?>
												<?= $product->adProductAdditionInfo->room_no ? '<li> <span class="icon icon-bed icon-bed-small"></span> ' . $product->adProductAdditionInfo->room_no . ' </li>' : '' ?>
												<?= $product->adProductAdditionInfo->toilet_no ? '<li> <span class="icon icon-pt icon-pt-small"></span> ' . $product->adProductAdditionInfo->toilet_no . ' </li>' : '' ?>
											</ul>
										</div>
										<div class="wrap-attr-bottom">
											<span class="price"><?= StringHelper::formatCurrency($product->price) ?></span>
											<a href="<?= Url::to(['/ad/detail', 'id' => $product->id, 'slug' => \common\components\Slug::me()->slugify($product->getAddress())]) ?>" class="pull-right view-more">Chi tiết</a>
					                    </div>
					                </div>
				                    <?php
				                    // tracking finder
				                    if($product->user_id != Yii::$app->user->id && isset(Yii::$app->params['tracking']['all']) && Yii::$app->params['tracking']['all'] == true) {
				                        Tracking::find()->productFinder(Yii::$app->user->id, (int)$product->id, time());
				                    }
				                    ?>
								</div>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php else: ?>
					<div id="no-result">Chưa có tin đăng theo tìm kiếm của bạn, <a href="#">đăng ký nhận thông báo khi có tin đăng phù hợp</a>.</div>
					<?php endif; ?>
				</div>
				<div id="item-loading" style="text-align: center;" class="<?php if($pages->page >= $pages->pageCount - 1) echo 'hide' ?>">
					<img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/loading-listing.gif' ?>" />
				</div>
			</div>
		</div>
	</div>
	<div class="wrap-map-listing">
		<div id="map" class="inner-wrap"></div>
	</div>
</div>