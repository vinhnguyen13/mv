<?php
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = Yii::t('express','We offer exeptional amenities and renowned white - glove services');
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/gmap.js', ['position' => View::POS_END]);
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=apiLoaded', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/search-map.js', ['position' => View::POS_END]);
?>
<div class="list-filters-result">
    <ul class="container clearfix">
        <li>
            <a href="#">Giá</a>
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
</div>
<div class="col-md-8 wrap-map-result">
    <div class="container-map">
    	<div id="map" style="height: 100%;"></div>
    </div>
</div>
<div class="col-md-4 result-items">
    <div class="wrap-col-fixed-result clearfix">
        <h1 id="search-title" class="zsg-content_collapsed">Listings</h1>
        <span class="num-results"><span id="count-listing">0</span> results.</span>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#moi-nhat" aria-controls="moi-nhat" role="tab" data-toggle="tab">Mới nhất</a></li>
            <li role="presentation"><a href="#re-nhat" aria-controls="re-nhat" role="tab" data-toggle="tab">Rẻ nhất</a></li>
            <li role="presentation"><a href="#phong-ngu" aria-controls="phong-ngu" role="tab" data-toggle="tab">Phòng ngủ</a></li>
            <li role="presentation"><a href="#khac" aria-controls="khac" role="tab" data-toggle="tab">Khác</a></li>
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
            <div role="tabpanel" class="tab-pane fade" id="phong-ngu">

            </div>
            <div role="tabpanel" class="tab-pane fade" id="khac">

            </div>
        </div>
    </div>
</div>
