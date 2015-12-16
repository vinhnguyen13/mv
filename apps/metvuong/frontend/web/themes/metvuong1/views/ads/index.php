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
                <div class="sub-filter" style="display:none;">
                    <div class="inner-sub">
                        <div class="filter-box">
                            <div class="filter-pane">

                            <div id="price-entries" class="price-entries zsg-separator search-entry">

                            <div class="dualboxes">

                            <div class="box1">
                            <input class="text commaFormat" maxlength="11" size="10" name="price-min" id="price-min" type="text" placeholder="Min">
                            </div>

                            <div class="dash">&nbsp;
                            </div>

                            <div class="box2">
                            <input class="text commaFormat" maxlength="11" size="11" name="price-max" id="price-max" type="text" placeholder="Max">
                            </div>
                            </div>
                            </div>

                            <div id="rent-entries" class="price-entries zsg-separator search-entry hide">

                            <div class="dualboxes">

                            <div class="box1">
                            <input class="text commaFormat" maxlength="11" size="10" name="rental-payment-min" id="rental-payment-min" type="text" placeholder="Min">
                            </div>

                            <div class="dash">&nbsp;
                            </div>

                            <div class="box2">
                            <input class="text commaFormat" maxlength="11" size="11" name="rental-payment-max" id="rental-payment-max" type="text" placeholder="Max">
                            </div>
                            </div>
                            </div>

                            <div class="filter-price">

                            <div id="price-min-options" class="price-options min-price-options custom-dropdown" data-dropdown-id="price-min">
                            <ul class="zf-dropdown-options search-entry zsg-menu-linklist">
                            <li data-value=""><a class="option" tabindex="0">0</a></li>
                            <li data-value="50,000"><a class="option" tabindex="0">$50,000+</a></li>
                            <li data-value="75,000"><a class="option" tabindex="0">$75,000+</a></li>
                            <li data-value="100,000"><a class="option" tabindex="0">$100,000+</a></li>
                            <li data-value="150,000"><a class="option" tabindex="0">$150,000+</a></li>
                            <li data-value="200,000"><a class="option" tabindex="0">$200,000+</a></li>
                            <li data-value="250,000"><a class="option" tabindex="0">$250,000+</a></li>
                            <li data-value="300,000"><a class="option" tabindex="0">$300,000+</a></li>
                            <li data-value="400,000"><a class="option" tabindex="0">$400,000+</a></li>
                            <li data-value="500,000"><a class="option" tabindex="0">$500,000+</a></li>
                            </ul>
                            </div>

                            <div id="rental-payment-min-options" class="price-options min-price-options custom-dropdown hide" data-dropdown-id="rental-payment-min">
                            <ul class="zf-dropdown-options search-entry zsg-menu-linklist">
                            <li data-value=""><a class="option" tabindex="0">0</a></li>
                            <li data-value="500"><a class="option" tabindex="0">$500+</a></li>
                            <li data-value="750"><a class="option" tabindex="0">$750+</a></li>
                            <li data-value="1,000"><a class="option" tabindex="0">$1,000+</a></li>
                            <li data-value="1,250"><a class="option" tabindex="0">$1,250+</a></li>
                            <li data-value="1,500"><a class="option" tabindex="0">$1,500+</a></li>
                            <li data-value="1,750"><a class="option" tabindex="0">$1,750+</a></li>
                            <li data-value="2,000"><a class="option" tabindex="0">$2,000+</a></li>
                            <li data-value="2,500"><a class="option" tabindex="0">$2,500+</a></li>
                            <li data-value="3,000"><a class="option" tabindex="0">$3,000+</a></li>
                            </ul>
                            </div>

                            <div id="price-max-options" class="price-options max-price-options custom-dropdown hide" data-dropdown-id="price-max">
                            <ul class="zf-dropdown-options search-entry zsg-menu-linklist">
                            <li data-value="100,000"><a class="option" tabindex="0">$100,000</a></li>
                            <li data-value="200,000"><a class="option" tabindex="0">$200,000</a></li>
                            <li data-value="300,000"><a class="option" tabindex="0">$300,000</a></li>
                            <li data-value="400,000"><a class="option" tabindex="0">$400,000</a></li>
                            <li data-value="500,000"><a class="option" tabindex="0">$500,000</a></li>
                            <li data-value="600,000"><a class="option" tabindex="0">$600,000</a></li>
                            <li data-value="700,000"><a class="option" tabindex="0">$700,000</a></li>
                            <li data-value="800,000"><a class="option" tabindex="0">$800,000</a></li>
                            <li data-value="900,000"><a class="option" tabindex="0">$900,000</a></li>
                            <li data-value=""><a class="option" tabindex="0">Any Price</a></li>
                            </ul>
                            </div>

                            <div id="rental-payment-max-options" class="price-options max-price-options custom-dropdown hide" data-dropdown-id="rental-payment-max">
                            <ul class="zf-dropdown-options search-entry zsg-menu-linklist">
                            <li data-value="1,000"><a class="option" tabindex="0">$1,000</a></li>
                            <li data-value="1,500"><a class="option" tabindex="0">$1,500</a></li>
                            <li data-value="2,000"><a class="option" tabindex="0">$2,000</a></li>
                            <li data-value="2,500"><a class="option" tabindex="0">$2,500</a></li>
                            <li data-value="3,000"><a class="option" tabindex="0">$3,000</a></li>
                            <li data-value="3,500"><a class="option" tabindex="0">$3,500</a></li>
                            <li data-value="4,000"><a class="option" tabindex="0">$4,000</a></li>
                            <li data-value="4,500"><a class="option" tabindex="0">$4,500</a></li>
                            <li data-value="5,000"><a class="option" tabindex="0">$5,000</a></li>
                            <li data-value=""><a class="option" tabindex="0">Any Price</a></li>
                            </ul>
                            </div>

                            <div class="saf-entry-link-section">

                            <div class="saf-bottom-entry"><a class="saf-entry-link">How much can I afford?</a>
                            </div>
                            </div>
                            </div>

                            <div class="filter-price-affordability hide" id="yui_3_18_1_1_1449739093703_2970">

                            <div class="saf-root saf-annual-income">

                            <div class="zsg-form-field saf-annual-income">
                            <label for="saf-annual-income-input">Annual income
                            </label>

                            <div class="zsg-input-overlay_left zsg-input-overlay_right">

                            <div class="zsg-input-overlay-text_left">$
                            </div>
                            <input id="saf-annual-income-input" type="text" value="70,000">

                            <div class="zsg-input-overlay-text_right">/yr
                            </div>
                            </div>

                            <p class="zsg-form-help-text">Calculate by <a href="#payment" class="saf-mode-link">payment</a></p>
                            </div>

                            <div class="zsg-form-field saf-monthly-payment">
                            <label for="saf-monthly-payment-input">Maximum payment
                            </label>

                            <div class="zsg-input-overlay_left zsg-input-overlay_right">

                            <div class="zsg-input-overlay-text_left">$
                            </div>
                            <input id="saf-monthly-payment-input" type="text" value="1,850">

                            <div class="zsg-input-overlay-text_right">/mo
                            </div>
                            </div>

                            <p class="zsg-form-help-text">Calculate by <a href="#income" class="saf-mode-link">income</a></p>
                            </div>

                            <div class="zsg-form-field">
                            <label for="saf-down-payment-input">Down payment
                            </label>

                            <div class="zsg-input-overlay_left">

                            <div class="zsg-input-overlay-text_left">$
                            </div>
                            <input id="saf-down-payment-input" type="text" value="20,000">
                            </div>
                            </div>

                            <div class="zsg-form-field saf-annual-income">
                            <label for="saf-monthly-debts-input">Monthly debts
                            </label>

                            <div class="zsg-input-overlay_left zsg-input-overlay_right">

                            <div class="zsg-input-overlay-text_left">$
                            </div>
                            <input id="saf-monthly-debts-input" type="text" value="250">

                            <div class="zsg-input-overlay-text_right">/mo
                            </div>
                            </div>
                            </div><h4 class="saf-max-price-label zsg-content_collapsed">Your max house price:</h4><h6 class="zsg-h2">$

                            <span class="saf-max-price-value">299,358
                            </span></h6>
                            <ul class="zsg-button-group">
                            <li><a href="#" class="saf-apply zsg-button_primary">Apply</a></li>
                            <li><a class="saf-close zsg-button">Cancel</a></li>
                            </ul>
                            <input type="text" class="saf-rate hide" value="3.754" id="yui_3_18_1_1_1449739093703_1333">
                            </div>

                            <div class="saf-bottom-entry"><h4 class="saf-pre-approval-next zsg-content_collapsed">NEXT STEP</h4><a class="saf-pre-approval-link">Get Pre-Approved on Zillow</a>
                            </div>

                            <div class="saf-bottom-entry hide">

                            <p class="saf-pre-approval-upsell-header">Getting pre-approved will let your agent know you're serious about buying.</p><button class="saf-pre-approval-link zsg-button_primary">Get Pre-Approved</button>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
	        </li>
	        <li>
	            <a href="#">Diện tích</a>
	        </li>
	        <li>
	            <a href="#">Phòng ngủ</a>
	        </li>
	        <li>
	            <a href="#">Loại BDS</a>
	        </li>
	        <li>
	            <a href="#">Khác</a>
	        </li>
	    </ul>
    </form>
</div>
<div class="col-md-8 wrap-map-result" style="z-index: 0">
    <div class="container-map">
    	<div id="map" style="height: 100%;"></div>
    </div>
</div>
<div class="col-md-4 result-items" style="z-index: 1">
	<div id="detail-wrap" style="position: absolute; z-index: -1;height: 100%;width: 800px; overflow: auto;z-index: -1;left: 0px;"><div id="detail-listing"></div></div>
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