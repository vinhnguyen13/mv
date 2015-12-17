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
	    <ul class="container clearfix">
	        <li>
	            <a href="#">Giá</a>
                <div class="filter-pane filter-common">
                    <div id="minmax-entries" class="minmax-entries search-entry">
                        <div class="dualboxes">
                            <div class="box1">
                                <input class="text commaFormat" maxlength="11" size="10" name="price-min" id="price-min" type="text" placeholder="Min">
                            </div>
                            <div class="dash">&nbsp;</div>
                            <div class="box2">
                                <input class="text commaFormat" maxlength="11" size="11" name="price-max" id="price-max" type="text" placeholder="Max">
                            </div>
                        </div>
                    </div>
                    <div class="filter-minmax">
                        <div id="min-price-options" class="minmax-options min-price-options">
                            <ul class="dropdown-options search-entry">
                                <li data-value=""><a class="option" tabindex="0">0</a></li>
                                <li data-value="50,000"><a class="option" tabindex="0">100 triệu</a></li>
                                <li data-value="75,000"><a class="option" tabindex="0">500 triệu</a></li>
                                <li data-value="100,000"><a class="option" tabindex="0">1 tỷ</a></li>
                                <li data-value="150,000"><a class="option" tabindex="0">1,5 tỷ</a></li>
                                <li data-value="200,000"><a class="option" tabindex="0">2 tỷ</a></li>
                                <li data-value="250,000"><a class="option" tabindex="0">2,5 tỷ</a></li>
                                <li data-value="300,000"><a class="option" tabindex="0">3 tỷ</a></li>
                                <li data-value="400,000"><a class="option" tabindex="0">3,5 tỷ</a></li>
                                <li data-value="500,000"><a class="option" tabindex="0">4 tỷ</a></li>
                            </ul>
                        </div>
                        <div id="max-price-options" class="minmax-options max-price-options hide">
                            <ul class="dropdown-options search-entry">
                                <li data-value="50,000"><a class="option" tabindex="0">100 triệu</a></li>
                                <li data-value="75,000"><a class="option" tabindex="0">500 triệu</a></li>
                                <li data-value="100,000"><a class="option" tabindex="0">1 tỷ</a></li>
                                <li data-value="150,000"><a class="option" tabindex="0">1,5 tỷ</a></li>
                                <li data-value="200,000"><a class="option" tabindex="0">2 tỷ</a></li>
                                <li data-value="250,000"><a class="option" tabindex="0">2,5 tỷ</a></li>
                                <li data-value="300,000"><a class="option" tabindex="0">3 tỷ</a></li>
                                <li data-value="400,000"><a class="option" tabindex="0">3,5 tỷ</a></li>
                                <li data-value="500,000"><a class="option" tabindex="0">4 tỷ</a></li>
                                <li data-value=""><a class="option" tabindex="0">Bất kỳ</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
	        </li>
	        <li>
	            <a href="#">Diện tích</a>
                <div class="filter-common filter-pane">
                    <div id="minmax-entries" class="minmax-entries search-entry">
                        <div class="dualboxes">
                            <div class="box1">
                                <input class="text commaFormat" maxlength="11" size="10" name="price-min" id="price-min" type="text" placeholder="Min">
                            </div>
                            <div class="dash">&nbsp;</div>
                            <div class="box2">
                                <input class="text commaFormat" maxlength="11" size="11" name="price-max" id="price-max" type="text" placeholder="Max">
                            </div>
                        </div>
                    </div>
                    <div class="filter-minmax">
                        <div id="min-dt-options" class="minmax-options min-dt-options">
                            <ul class="dropdown-options search-entry">
                                <li data-value=""><a class="option" tabindex="0">0</a></li>
                                <li data-value="50,000"><a class="option" tabindex="0">10 m<sup>2</sup></a></li>
                                <li data-value="75,000"><a class="option" tabindex="0">20 m<sup>2</sup></a></li>
                                <li data-value="100,000"><a class="option" tabindex="0">40 m<sup>2</sup></a></li>
                                <li data-value="150,000"><a class="option" tabindex="0">60 m<sup>2</sup></a></li>
                                <li data-value="200,000"><a class="option" tabindex="0">80 m<sup>2</sup></a></li>
                                <li data-value="250,000"><a class="option" tabindex="0">100 m<sup>2</sup></a></li>
                                <li data-value="300,000"><a class="option" tabindex="0">150 m<sup>2</sup></a></li>
                                <li data-value="400,000"><a class="option" tabindex="0">200 m<sup>2</sup></a></li>
                                <li data-value="500,000"><a class="option" tabindex="0">250 m<sup>2</sup></a></li>
                            </ul>
                        </div>
                        <div id="max-dt-options" class="minmax-options max-dt-options hide">
                            <ul class="dropdown-options search-entry">
                                <li data-value="50,000"><a class="option" tabindex="0">10 m<sup>2</sup></a></li>
                                <li data-value="75,000"><a class="option" tabindex="0">20 m<sup>2</sup></a></li>
                                <li data-value="100,000"><a class="option" tabindex="0">40 m<sup>2</sup></a></li>
                                <li data-value="150,000"><a class="option" tabindex="0">60 m<sup>2</sup></a></li>
                                <li data-value="200,000"><a class="option" tabindex="0">80 m<sup>2</sup></a></li>
                                <li data-value="250,000"><a class="option" tabindex="0">100 m<sup>2</sup></a></li>
                                <li data-value="300,000"><a class="option" tabindex="0">150 m<sup>2</sup></a></li>
                                <li data-value="400,000"><a class="option" tabindex="0">200 m<sup>2</sup></a></li>
                                <li data-value="500,000"><a class="option" tabindex="0">250 m<sup>2</sup></a></li>
                                <li data-value=""><a class="option" tabindex="0">Bất kỳ</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
	        </li>
	        <li>
	            <a href="#">Phòng ngủ</a>
                <div class="filter-common filter-pane filter-bed">
                    <div class="filter-bed">
                        <ul class="dropdown-options search-entry">
                            <li data-value="50,000"><a class="option" tabindex="0">0</a></li>
                            <li data-value="50,000"><a class="option" tabindex="0">1</a></li>
                            <li data-value="50,000"><a class="option" tabindex="0">2</a></li>
                            <li data-value="50,000"><a class="option" tabindex="0">3</a></li>
                            <li data-value="50,000"><a class="option" tabindex="0">4</a></li>
                            <li data-value="50,000"><a class="option" tabindex="0">5</a></li>
                            <li data-value="50,000"><a class="option" tabindex="0">6</a></li>
                            <li data-value=""><a class="option" tabindex="0">Bất kỳ</a></li>
                        </ul>
                    </div>
                </div>
	        </li>
	        <li>
	            <a href="#">Loại BDS</a>
                <div class="filter-common filter-pane filter-loaibds">
                    <ul class="combobox-options multicheck-dropdown-options hometype-options">
                        <li class="hometype">
                            <input id="hometype-input-1" name="hometype-input" type="checkbox" class="hometype-input checkbox">
                            <label for="hometype-input-1">
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
	        </li>
	        <li>
	            <a href="#">Khác</a>
                <div class="filter-common filter-pane filter-other">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Phòng tắm</label>
                            <div class="col-sm-8">
                                <select class="form-control">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group minmax-frm-inline">
                            <label for="" class="col-sm-4 control-label">Năm</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control val-min" placeholder="Min">
                                <input type="text" class="form-control val-max" placeholder="Max">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Ngày đăng</label>
                            <div class="col-sm-8">
                                <select class="form-control">
                                    <option>1 ngày</option>
                                    <option>7 ngày</option>
                                    <option>14 ngày</option>
                                    <option>30 ngày</option>
                                    <option>60 ngày</option>
                                    <option>90 ngày</option>
                                    <option>6 tháng</option>
                                    <option>12 tháng</option>
                                    <option>24 tháng</option>
                                    <option>36 tháng</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Từ khóa</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label"></label>
                            <div class="col-sm-8">
                                <button type="button" class="btn btn-primary btn-sm btn-common">Apply</button>
                            </div>
                        </div>
                    </form>
                </div>
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
                <ul class="list-results clearfix">
                    <li>
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
                    </li>
                </ul>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="re-nhat">

            </div>
        </div>
    </div>
</div>