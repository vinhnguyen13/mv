<?php
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use vsoft\ad\models\AdCategory;
$this->title = Yii::t('express','We offer exeptional amenities and renowned white - glove services');
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/gmap.js', ['position' => View::POS_END]);
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=apiLoaded', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/imagesloaded.3.0.4.js', ['position' => View::POS_END]);
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/lightbox.min.js', ['position' => View::POS_END]);
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/jquery.bxslider.js', ['position' => View::POS_END]);
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/search-map.js', ['position' => View::POS_END]);
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/search-results.js', ['position' => View::POS_END]);
$this->registerJs('var categories = ' . json_encode(AdCategory::find()->indexBy('id')->asArray(true)->all()) . ';', View::POS_BEGIN);
?>
<div id="map-loading" style="display:none;position: absolute;width: 100%;height: 100%;background: rgba(0, 0, 0, 0.5);z-index: 3;">
	<img style="    position: absolute;left: 50%;top: 50%;margin-left: -24px;margin-top: -24px;" src="<?= Yii::$app->view->theme->baseUrl ?>/resources/images/map-loading.gif" />
</div>
<div class="list-filters-result">
	<form id="map-search-form" action="<?= Url::to('/real-estate/result') ?>" method="post">
		<input type="hidden" name="result" value="1" />
		<input type="hidden" name="cityId" id="city-id" value="<?= Yii::$app->request->get('city') ?>" />
		<input type="hidden" name="districtId" id="district-id" value="<?= Yii::$app->request->get('district') ?>" />
		<input type="hidden" name="categoryId" id="category-id" value="<?= Yii::$app->request->get('category') ?>" />
		<input type="hidden" name="orderBy" id="order-by" value="created_at" />
		<input type="hidden" name="type" id="type" value="<?= Yii::$app->request->get('type') ?>" />
        <input id="price-min-filter" type="hidden" name="costMin" value="<?= Yii::$app->request->get('costMin') ?>" />
        <input id="price-max-filter" type="hidden" name="costMax" value="<?= Yii::$app->request->get('costMax') ?>" />
        <input id="dt-min-filter" type="hidden" name="areaMin" value="<?= Yii::$app->request->get('areaMin') ?>" />
        <input id="dt-max-filter" type="hidden" name="areaMax" value="<?= Yii::$app->request->get('areaMax') ?>" />
        <input id="bed-filter" type="hidden" name="roomNo" value="<?= Yii::$app->request->get('roomNo') ?>" />
        <input id="bath-filter" type="hidden" name="toiletNo" value="<?= Yii::$app->request->get('toiletNo') ?>" />

        <ul class="container clearfix outsideevent list-filter">
	        <li>
	            <a href="#">Giá</a>
                <div class="filter-pane filter-common hidden-effect" data-filter="price-min-max">
                    <div class="wrap-effect clearfix">
                        <div id="minmax-entries" class="minmax-entries search-entry">
                            <div class="dualboxes">
                                <div class="box1">
                                    <input readonly="readonly" class="text commaFormat" maxlength="11" size="10" name="price-min" id="min-val" type="text" placeholder="Min">
                                </div>
                                <div class="dash">&nbsp;</div>
                                <div class="box2">
                                    <input readonly="readonly" class="text commaFormat" maxlength="11" size="11" name="price-max" id="max-val" type="text" placeholder="Max">
                                </div>
                            </div>
                        </div>
                        <div class="filter-minmax">
                            <div id="min-price-options" class="minmax-options min-price-options minmax" data-toggle-filter="min-val">
                                <ul class="dropdown-options search-entry">
                                    <li data-number="0" data-unit=""><a class="option">0</a></li>
                                    <li data-number="100000000" data-unit="triệu"><a class="option">100 triệu</a></li>
                                    <li data-number="500000000" data-unit="triệu"><a class="option">500 triệu</a></li>
                                    <li data-number="1000000000" data-unit="tỷ"><a class="option">1 tỷ</a></li>
                                    <li data-number="1500000000" data-unit="tỷ"><a class="option">1,5 tỷ</a></li>
                                    <li data-number="2000000000" data-unit="tỷ"><a class="option">2 tỷ</a></li>
                                    <li data-number="2500000000" data-unit="tỷ"><a class="option">2,5 tỷ</a></li>
                                    <li data-number="3000000000" data-unit="tỷ"><a class="option">3 tỷ</a></li>
                                    <li data-number="3500000000" data-unit="tỷ"><a class="option">3,5 tỷ</a></li>
                                    <li data-number="4000000000" data-unit="tỷ"><a class="option">4 tỷ</a></li>
                                </ul>
                            </div>
                            <div id="max-price-options" class="minmax-options max-price-options hide minmax" data-toggle-filter="max-val">
                                <ul class="dropdown-options search-entry">
                                    <li data-number="100000000" data-unit="triệu"><a class="option">100 triệu</a></li>
                                    <li data-number="500000000" data-unit="triệu"><a class="option">500 triệu</a></li>
                                    <li data-number="1000000000" data-unit="tỷ"><a class="option">1 tỷ</a></li>
                                    <li data-number="1500000000" data-unit="tỷ"><a class="option">1,5 tỷ</a></li>
                                    <li data-number="2000000000" data-unit="tỷ"><a class="option">2 tỷ</a></li>
                                    <li data-number="2500000000" data-unit="tỷ"><a class="option">2,5 tỷ</a></li>
                                    <li data-number="3000000000" data-unit="tỷ"><a class="option">3 tỷ</a></li>
                                    <li data-number="3500000000" data-unit="tỷ"><a class="option">3,5 tỷ</a></li>
                                    <li data-number="4000000000" data-unit="tỷ"><a class="option">4 tỷ</a></li>
                                    <li class="anyVal" data-number><a class="option">Bất kỳ</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
	        </li>
	        <li>
	            <a href="#" data-symbol-unit="m<sup>2</sup>">Diện tích</a>
                <div class="filter-common filter-pane hidden-effect" data-filter="dt-min-max">
                    <div class="wrap-effect clearfix">
                        <div id="minmax-entries" class="minmax-entries search-entry">
                            <div class="dualboxes">
                                <div class="box1">
                                    <input readonly="readonly" class="text commaFormat" maxlength="11" size="10" name="dt-min" id="min-val" type="text" placeholder="Min">
                                </div>
                                <div class="dash">&nbsp;</div>
                                <div class="box2">
                                    <input readonly="readonly" class="text commaFormat" maxlength="11" size="11" name="dt-max" id="max-val" type="text" placeholder="Max">
                                </div>
                            </div>
                        </div>
                        <div class="filter-minmax">
                            <div id="min-dt-options" class="minmax-options min-dt-options minmax" data-toggle-filter="min-val">
                                <ul class="dropdown-options search-entry">
                                    <li data-number="0"><a class="option">0</a></li>
                                    <li data-number="10"><a class="option">10 m<sup>2</sup></a></li>
                                    <li data-number="20"><a class="option">20 m<sup>2</sup></a></li>
                                    <li data-number="40"><a class="option">40 m<sup>2</sup></a></li>
                                    <li data-number="60"><a class="option">60 m<sup>2</sup></a></li>
                                    <li data-number="80"><a class="option">80 m<sup>2</sup></a></li>
                                    <li data-number="100"><a class="option">100 m<sup>2</sup></a></li>
                                    <li data-number="150"><a class="option">150 m<sup>2</sup></a></li>
                                    <li data-number="200"><a class="option">200 m<sup>2</sup></a></li>
                                    <li data-number="250"><a class="option">250 m<sup>2</sup></a></li>
                                </ul>
                            </div>
                            <div id="max-dt-options" class="minmax-options max-dt-options hide minmax" data-toggle-filter="max-val">
                                <ul class="dropdown-options search-entry">
                                    <li data-number="10"><a class="option">10 m<sup>2</sup></a></li>
                                    <li data-number="20"><a class="option">20 m<sup>2</sup></a></li>
                                    <li data-number="40"><a class="option">40 m<sup>2</sup></a></li>
                                    <li data-number="60"><a class="option">60 m<sup>2</sup></a></li>
                                    <li data-number="80"><a class="option">80 m<sup>2</sup></a></li>
                                    <li data-number="100"><a class="option">100 m<sup>2</sup></a></li>
                                    <li data-number="150"><a class="option">150 m<sup>2</sup></a></li>
                                    <li data-number="200"><a class="option">200 m<sup>2</sup></a></li>
                                    <li data-number="250"><a class="option">250 m<sup>2</sup></a></li>
                                    <li class="anyVal" data-number><a class="option">Bất kỳ</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
	        </li>
	        <li>
	            <a href="#"><span></span>Phòng ngủ</a>
                <div class="filter-common filter-pane filter-bed filter-dropdown hidden-effect" data-filter="phong-ngu">
                    <div class="wrap-effect clearfix">
                        <div class="filter-bed">
                            <ul class="dropdown-options search-entry">
                                <li data-value=""><a class="option">0</a></li>
                                <li data-value=""><a class="option">1</a></li>
                                <li data-value=""><a class="option">2</a></li>
                                <li data-value=""><a class="option">3</a></li>
                                <li data-value=""><a class="option">4</a></li>
                                <li data-value=""><a class="option">5</a></li>
                                <li data-value=""><a class="option">6</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
	        </li>
            <li>
                <a href="#"><span></span>Phòng tắm</a>
                <div class="filter-common filter-pane filter-bed filter-dropdown hidden-effect" data-filter="phong-tam">
                    <div class="wrap-effect clearfix">
                        <div class="filter-bed">
                            <ul class="dropdown-options search-entry">
                                <li data-value=""><a class="option">0</a></li>
                                <li data-value=""><a class="option">1</a></li>
                                <li data-value=""><a class="option">2</a></li>
                                <li data-value=""><a class="option">3</a></li>
                                <li data-value=""><a class="option">4</a></li>
                                <li data-value=""><a class="option">5</a></li>
                                <li data-value=""><a class="option">6</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
	        <!-- <li>
	            <a href="#">Loại BDS</a>
                <div class="filter-common filter-pane filter-loaibds hidden-effect">
                    <div class="wrap-effect clearfix">
                        <ul class="hometype-options">
                            <li class="hometype">
                                <input type="checkbox" class="">
                                <label>
                                    <span id="hometype-top-filters-label" class="hometype-label option">Chung cư</span>
                                </label>
                            </li>
                            <li class="hometype">
                                <input id="hometype-input-2" name="hometype-input" type="checkbox" class="hometype-input checkbox">
                                <label for="hometype-input-2">
                                    <span id="hometype-top-filters-label" class="hometype-label option">Nhà riêng</span>
                                </label>
                            </li>
                            <li class="hometype">
                                <input id="hometype-input-3" name="hometype-input" type="checkbox" class="hometype-input checkbox">
                                <label for="hometype-input-3">
                                    <span id="hometype-top-filters-label" class="hometype-label option">Nhà biệt thự, liền kề</span>
                                </label>
                            </li>
                            <li class="hometype">
                                <input id="hometype-input-4" name="hometype-input" type="checkbox" class="hometype-input checkbox">
                                <label for="hometype-input-4">
                                    <span id="hometype-top-filters-label" class="hometype-label option">Nhà mặt phố</span>
                                </label>
                            </li>
                            <li class="hometype">
                                <input id="hometype-input-5" name="hometype-input" type="checkbox" class="hometype-input checkbox">
                                <label for="hometype-input-5">
                                    <span id="hometype-top-filters-label" class="hometype-label option">Đất nền dự án</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
	        </li> -->
            <li>
                <a href="#">Khác</a>
                <div class="filter-common filter-pane filter-other hidden-effect">
                    <div class="wrap-effect clearfix">
                    		<div class="form-group">
                                <label for="" class="col-sm-4 control-label">Người đăng</label>
                                <div class="col-sm-8">
                                    <select class="form-control">
                                        <option>Tất cả</option>
                                        <option>Nhà môi giới</option>
                                        <option>Chính chủ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-4 control-label">Ngày đăng</label>
                                <div class="col-sm-8">
                                    <select name="time" class="form-control">
                                        <option value="">Bất kỳ</option>
                                        <option value="-1 day">1 ngày</option>
                                        <option value="-7 day">7 ngày</option>
                                        <option value="-14 day">14 ngày</option>
                                        <option value="-30 day">30 ngày</option>
                                        <option value="-60 day">60 ngày</option>
                                        <option value="-90 day">90 ngày</option>
                                        <option value="-6 month">6 tháng</option>
                                        <option value="-12 month">12 tháng</option>
                                        <option value="-24 month">24 tháng</option>
                                        <option value="-36 month">36 tháng</option>
                                    </select>
                                </div>
                            </div>
                    </div>
                </div>
            </li>
	        
            <li>
                <button id="submit-filter" type="button" class="btn btn-primary btn-sm btn-common"><em class="fa fa-filter"></em>Chọn lọc</button>
            </li>
	    </ul>
    </form>
</div>
<div class="col-md-8 wrap-map-result" style="z-index: 0">
    <div class="container-map">
    	<div id="map" style="height: 100%;"></div>
    </div>
</div>
<div class="col-md-4 result-items">
	<div id="detail-wrap"><div id="detail-listing"></div></div>
    <div class="wrap-col-fixed-result clearfix" style="background: #FFFFFF">
        
        <h1 id="search-title" class="zsg-content_collapsed">Listings</h1>
        <span class="num-results"><span id="count-listing">0</span> results.</span>
        
        <ul id="order-by-tab" class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a data-order="created_at" href="#" aria-controls="moi-nhat" role="tab" data-toggle="tab">Mới nhất</a></li>
            <li role="presentation"><a data-order="price" href="#" aria-controls="re-nhat" role="tab" data-toggle="tab">Rẻ nhất</a></li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="moi-nhat">
                <div id="listing-loading" style="padding: 10px;">
                	<div class="loading_new_feed">          
                            <div class="_2iwr"></div>            
                            <div class="_2iws"></div>            
                            <div class="_2iwt"></div>            
                            <div class="_2iwu"></div>            
                            <div class="_2iwv"></div>            
                            <div class="_2iww"></div>            
                            <div class="_2iwx"></div>            
                            <div class="_2iwy"></div>            
                            <div class="_2iwz"></div>            
                            <div class="_2iw-"></div>            
                            <div class="_2iw_"></div>            
                            <div class="_2ix0"></div>        
                        </div>
                </div>
                <ul class="list-results clearfix"></ul>
                <div id="no-result" style="text-align: center; padding: 22px; display: none;">Chưa có tòa nhà nào được đăng như tìm kiếm của bạn.</div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="re-nhat">

            </div>
        </div>
    </div>
</div>