<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$value = \Yii::$app->getRequest()->getCookies()->getValue('searchParams');
$searchParams = json_decode($value);
?>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).bind( 'submit_search', function(event, json, string, callback){
            setTimeout(function(){callback();},1000);
            setTimeout(function() {
                if (typeof ga !== "undefined") {
                    ga('send', {hitType: 'event',eventCategory: 'Listing',eventAction: 'click',eventLabel: 'SearchForm'});
                }
                setTimeout(function() {$('#search-kind').submit();},100);
            },1000);
            return false;
        });

        $(document).bind( 'real-estate/news', function(event, json, string, callback){
            setTimeout(function(){callback();},1000);
            setTimeout(function() {
                if (typeof ga !== "undefined") {
                    ga('send', {hitType: 'event',eventCategory: 'PostListing',eventAction: 'click',eventLabel: 'SearchForm'});
                }
                setTimeout(function() {$('#search-kind').submit();},100);
            },1000);
        });
        
        $(document).bind( 'real-estate/post', function(event, json, string, callback){
            setTimeout(function(){callback();},1000);
            setTimeout(function() {
                if (typeof ga !== "undefined") {
                    ga('send', {hitType: 'event',eventCategory: 'News',eventAction: 'click',eventLabel: 'SearchForm'});
                }
                setTimeout(function() {$('#search-kind').submit();},100);
            },1000);
        });

    });
</script>
<header class="home-page cd-secondary-nav border-shadow wrap-page-home show-fixed">
    <div class="container clearfix">
        <?php $this->beginContent('@app/views/layouts/_partials/menuMain.php'); ?><?php $this->endContent();?>
        <div class="wrap-search-home">
            <div class="wrap-logo"><div class="bgcover logo-home ani-logo" style="background-image:url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/logo.png);"><a href="<?=Url::home()?>"></a></div></div>
            <div class="box-search-header clearfix ani-search">
                <div class="pull-left">
                    <?php $form = ActiveForm::begin([
                        'options'=>['class' => 'form-inline pull-left', 'method'=>'POST'],
                        'id'=>'search-kind',
                        'action'=>Url::to(['/ad/redirect']),
                        'fieldConfig' => [],
                    ]); ?>
                        <div class="form-group">
                            <div class="type-search">
                                <ul class="outsideevent"></ul>
                                <input id="searchInput" name="search" type="text" class="form-control outsideevent" placeholder="" readonly="readonly">
                            </div>

                            <div id="mua-thue" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn Mua/Thuê ?">
                                <div class="wrap-effect">
                                    <div class="search-item">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <div class="wrap-step">
                                            <h3></h3>
                                            <ul class="clearfix">
                                                <li data-id="1"><a href="#" data-item="mua-thue" data-slug-name data-next="tinh-thanh" data-prev>Cho Mua</a></li>
                                                <li data-id="2"><a href="#" data-item="mua-thue" data-slug-name data-next="tinh-thanh" data-prev>Cho Thuê</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="ban-thue" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn Bán/Thuê ?">
                                <div class="wrap-effect">
                                    <div class="search-item">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <div class="wrap-step">
                                            <h3></h3>
                                            <ul class="clearfix">
                                                <li data-id="1"><a href="#" data-item="ban-thue" data-slug-name data-next="tinh-thanh" data-prev>Đăng Ký Bán</a></li>
                                                <li data-id="2"><a href="#" data-item="ban-thue" data-slug-name data-next="tinh-thanh" data-prev>Đăng Ký Cho Thuê</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="tinh-thanh" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn Tỉnh/Thành ?">
                                <div class="wrap-effect">
                                    <div class="search-item">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <div class="wrap-step">
                                            <h3></h3>
                                            <ul></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="quan-huyen" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn Quận/Huyện ?">
                                <div class="wrap-effect">
                                    <div class="search-item">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <div class="wrap-step">
                                            <h3></h3>
                                            <ul></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="loai-bds" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn loại bất động sản ?">
                                <div class="wrap-effect">
                                    <div class="search-item">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <div class="wrap-step">
                                            <h3></h3>
                                            <ul></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="loai-tin-tuc" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn loại tin tức ?">
                                <div class="wrap-effect">
                                    <div class="search-item">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <div class="wrap-step">
                                            <h3></h3>
                                            <ul></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="news" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn tin tức ?">
                                <div class="wrap-effect">
                                    <div class="search-item">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <div class="wrap-step">
                                            <h3></h3>
                                            <ul></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="loai-duan" class="outsideevent search-wrap hidden-effect" data-step-title="Chọn dự án ?">
                                <div class="wrap-effect">
                                    <div class="search-item">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <div class="wrap-step">
                                            <h3></h3>
                                            <ul></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="min-max" class="outsideevent search-wrap hidden-effect" data-step-title="Nhập khoảng giá ?">
                                <div class="wrap-effect">
                                    <div class="search-item clearfix">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <div class="wrap-step">
                                            <h3></h3>
                                            <div class="frm-cost-min-max clearfix">
                                                <div class="form-group inline-group box-cost col-xs-5" data-tab="min">
                                                    <input name="costMin" id="minCost" type="text" class="form-control cost-value" placeholder="min" readonly="readonly">
                                                    <div class="outsideevent wrap-cost-bds hidden-cost">
                                                        <div class="wrap-effect-cost">
                                                            <ul>
                                                                <li data-cost="0" data-unit=""><span>0</span></li>
                                                                <li data-cost="1" data-unit="triệu"><span>1 triệu</span></li>
                                                                <li data-cost="2" data-unit="triệu"><span>2 triệu</span></li>
                                                                <li data-cost="3" data-unit="triệu"><span>3 triệu</span></li>
                                                                <li data-cost="4" data-unit="triệu"><span>4 triệu</span></li>
                                                                <li data-cost="5" data-unit="triệu"><span>5 triệu</span></li>
                                                                <li data-cost="6" data-unit="tỷ"><span>6 triệu</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="line-center form-group inline-group box-cost col-xs-2"><span></span></div>
                                                <div class="form-group inline-group box-cost col-xs-5" data-tab="max">
                                                    <input name="costMax" id="maxCost" type="text" class="form-control cost-value" placeholder="max" readonly="readonly">
                                                    <div class="outsideevent wrap-cost-bds hidden-cost">
                                                        <div class="wrap-effect-cost">
                                                            <ul>
                                                                <li data-cost="0" data-unit=""><span>0</span></li>
                                                                <li data-cost="1" data-unit="triệu"><span>1 triệu</span></li>
                                                                <li data-cost="2" data-unit="triệu"><span>2 triệu</span></li>
                                                                <li data-cost="3" data-unit="triệu"><span>3 triệu</span></li>
                                                                <li data-cost="4" data-unit="triệu"><span>4 triệu</span></li>
                                                                <li data-cost="5" data-unit="triệu"><span>5 triệu</span></li>
                                                                <li data-cost="6" data-unit="tỷ"><span>6 triệu</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="btn-cost">
                                                    <button type="button" class="btn btn-primary btn-sm btn-common">OK</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="btn-search" type="submit" class="btn btn-default">
                            <span><em class=""></em></span>
                        </button>

                        <input class="valInputHidden" id="valSearch" name="valSearch" type="hidden" value="">
                        <input class="valInputHidden" id="valTabActive" name="activeSearch" type="hidden" value="">

                        <input class="valInputHidden" id="valTinhThanh" name="city" type="hidden" value="">
                        <input class="valInputHidden" id="valQuanHuyen" name="district" type="hidden" value="">
                        <input class="valInputHidden" id="valLoaiBDS" name="category" type="hidden" value="">
                        <input class="valInputHidden" id="valDuAn" name="project" type="hidden" value="">
                        <input class="valInputHidden" id="valTinTuc" name="newsType" type="hidden" value="">
                        <input class="valInputHidden" id="valLoaiTinTuc" name="newsCat" type="hidden" value="">
                        
                    <?php ActiveForm::end(); ?>
                    <div class="pull-left text-right mgT-10 options-search">
                        <div class="icon-selected">
                            <a href="#">
                                <span><em class="fa fa-home"></em><em class="fa fa-file-text"></em></span>
                            </a>
                            <em class="fa fa-sort-desc"></em>
                        </div>
                        <div data-active="1" data-tab="mua-thue" class="search-select active" data-step='step1'>
                            <a href="#" title="Muốn Mua/Thuê">
                                <span>
                                    <em class="fa fa-home"></em>
                                    <em class="fa fa-search"></em>
                                </span>
                                <i>Muốn Mua/Thuê</i>
                            </a>
                        </div>
                        <div data-active="2" data-tab="ban-thue" class="search-select" data-step='step2'>
                            <a href="#" title="Đăng ký Bán/Thuê">
                                <span>
                                    <em class="fa fa-home"></em>
                                    <em class="fa fa-pencil-square-o"></em>
                                </span>
                                <i>Đăng ký Bán/Thuê</i>
                            </a>
                        </div>
                        <div data-active="3" data-tab="tin-tuc"  class="search-select" data-step='step3'>
                            <a href="#" title="Tin Tức">
                                <span>
                                    <em class="fa fa-home"></em>
                                    <em class="fa fa-file-text"></em>
                                </span>
                                <i>Tin Tức</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>