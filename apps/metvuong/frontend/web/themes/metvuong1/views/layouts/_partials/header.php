<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$value = \Yii::$app->getRequest()->getCookies()->getValue('searchParams');
$searchParams = json_decode($value, true);

?>
<script type="text/javascript">
    var jsonActive = [
        <?php if(!empty($searchParams['sug1'])):?>
        {
            "idItem": <?=$searchParams['sug1']?>,
            "stepId": 1,
            "stepShow": "#valTT",
            "name_item": dataCities[<?=$searchParams['sug1'];?>].name
        },
        <?php endif;?>
        <?php if(!empty($searchParams['sug2'])):?>
        {
            "idItem": <?=$searchParams['sug2']?>,
            "stepId": 2,
            "stepShow": "#valQh",
            "name_item": dataCities[<?=$searchParams['sug1'];?>].districts[<?=$searchParams['sug2']?>].name
        },
        <?php endif;?>
        <?php if(!empty($searchParams['sug3'])):?>
        {
            "idItem": <?=$searchParams['sug3']?>,
            "stepId": 3,
            "stepShow": "#valLoai",
            "name_item": dataCategories[<?=$searchParams['sug3']?>].name
        }
        <?php endif;?>
    ];

    $(document).ready(function() {
        getActiveSuggert(jsonActive);
    });

    $(document).bind( 'real-estate/news', function(event, json, string){
        setTimeout(function() {$('#search-kind').submit();},100);
    });
    $(document).bind( 'real-estate/post', function(event, json, string){
        setTimeout(function() {
            $('.wrap-search-home .logo-home').addClass('ani-logo');
            $('.box-search-header').addClass('ani-search');
            setTimeout(function() {
                $('header').addClass('border-shadow');
                location.href = '<?=Url::to(['/ads/post'])?>';
            },500);
        },500);
    });


</script>
<header class="home-page cd-secondary-nav border-shadow wrap-page-home">
    <div class="container clearfix">
        <?php $this->beginContent('@app/views/layouts/_partials/menuMain.php'); ?><?php $this->endContent();?>
        <div class="wrap-search-home">
            <div class="wrap-logo"><div class="bgcover logo-home ani-logo" style="background-image:url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/logo.png);"><a href="<?=Url::home()?>"></a></div></div>
            <div class="box-search-header clearfix ani-search">
                <div class="pull-left">
                    <?php $form = ActiveForm::begin([
                        'options'=>['class' => 'form-inline pull-left', 'method'=>'POST'],
                        'id'=>'search-kind',
                        'action'=>Url::to(['/ads/redirect']),
                        'fieldConfig' => [],
                    ]); ?>
                        <div class="form-group">
                            <div class="type-search">
                                <ul class="outsideevent"></ul>
                                <input id="searchInput" name="search" type="text" class="form-control outsideevent" placeholder="" readonly="readonly">
                            </div>
                            <div id="step-1" class="outsideevent search-wrap hidden-effect" data-txt-step="Bạn ở Tỉnh/Thành nào ?">
                                <div class="wrap-effect">
                                    <div class="search-item">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <h3>Bạn ở Thành phố nào ?</h3>
                                        <ul class="clearfix list-tinh-thanh"></ul>
                                    </div>
                                </div>
                            </div>
                            <div id="step-2" class="outsideevent search-wrap hidden-effect" data-txt-step="Bạn ở Quận/Huyện nào ?">
                                <div class="wrap-effect">
                                    <div class="search-item clearfix">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <h3>Bạn ở Quận nào ?</h3>
                                        <ul class="list-quan-huyen"></ul>
                                    </div>
                                </div>
                            </div>
                            <div id="step-3" class="outsideevent search-wrap hidden-effect" data-txt-step="Loại BDS bạn quan tâm ?">
                                <div class="wrap-effect">
                                    <div class="search-item clearfix">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <h3>Loại BDS bạn quan tâm ?</h3>
                                        <ul class="list-loai-bds"></ul>
                                    </div>
                                </div>
                            </div>

                            <div class="outsideevent search-wrap hidden-effect" data-txt-step="" data-template="suggest-list" data-end="true">
                                <div class="wrap-effect">
                                    <div class="search-item clearfix">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <h3>Chọn dự án</h3>
                                        <ul class="list-duan-suggest">
                                            <li><a href="#">RICHSTAR</a></li>
                                            <li><a href="#">SUNRISE RIVERSIDE</a></li>
                                            <li><a href="#">ORCHARD PARKVIEW</a></li>
                                            <li><a href="#">GOLDEN MANSION</a></li>
                                            <li><a href="#">KINGSTON RESIDENCE</a></li>
                                            <li><a href="#">THE BOTANICA</a></li>
                                            <li><a href="#">THE SUN AVENUE</a></li>
                                            <li><a href="#">ORCHARD GARDEN</a></li>
                                            <li><a href="#">SUNRISE CITYVIEW</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="outsideevent search-wrap hidden-effect" data-txt-step="" data-template="cost-min-max" data-end="true">
                                <div class="wrap-effect">
                                    <div class="search-item clearfix">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <h3>Nhập khoảng giá ?</h3>
                                        <div class="frm-cost-min-max clearfix">
                                            <div class="form-group inline-group box-cost col-xs-5" data-tab="min">
                                                <input id="minCost" type="text" class="form-control cost-value" placeholder="min" readonly="readonly">
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
                                                <input id="maxCost" type="text" class="form-control cost-value" placeholder="max" readonly="readonly">
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
                                        </div>
                                        <input id="valCosMin" type="hidden" value="" data-val-cost="min" class="val-cost">
                                        <input id="valCosMax" type="hidden" value="" data-val-cost="max" class="val-cost">
                                    </div>
                                </div>
                            </div>

                            <div class="outsideevent search-wrap hidden-effect" data-txt-step="Bạn nên chọn Loại Tin Tức ?" data-template="news" data-end="true">
                                <div class="wrap-effect">
                                    <div class="search-item clearfix">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <h3>Bạn nên chọn Loại Tin Tức ?</h3>
                                        <ul class="list-loai-tt"></ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <button id="btn-search" type="submit" class="btn btn-default">
                            <span><em class="fa fa-search"></em></span>
                        </button>
                        <input class="getValSuggest" type="hidden" id="valTT" name="city" value="">
                        <input class="getValSuggest" type="hidden" id="valQh" name="district" value="">
                        <input class="getValSuggest" type="hidden" id="valLoai" name="category" value="">
                        <input class="getValSuggest" type="hidden" id="valTTuc" name="news" value="">
                    <?php ActiveForm::end(); ?>
                    <div class="pull-left text-right mgT-10 mgL-15 options-search">
                        <div class="icon-selected">
                            <a href="#">
                                <span>
                                    <em class="fa fa-home"></em>
                                </span>
                            </a>
                            <em class="fa fa-sort-desc"></em>
                        </div>
                        <div class="search-select active">
                            <a href="#" data-placeholder="Bạn ở Tỉnh/Thành nào ?" rel="#dd-search">
                                <span>
                                    <em class="fa fa-home"></em>
                                    <em class="fa fa-search"></em>
                                </span>
                                <i>Muốn Mua/Thuê</i>
                            </a>
                        </div>
                        <div class="search-select">
                            <a href="#" data-placeholder="Bạn ở Tỉnh/Thành nào ?" rel="#dd-dky">
                                <span>
                                    <em class="fa fa-home"></em>
                                    <em class="fa fa-pencil-square-o"></em>
                                </span>
                                <i>Đăng ký Bán/Thuê</i>
                            </a>
                        </div>
                        <div class="search-select">
                            <a href="#" class="" data-step-fix="step-5" data-placeholder="Bạn nên chọn Loại Tin Tức ?" rel="#dd-news">
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
<!--        <ul class="list-menu-header">-->
<!--            <li><a href="#">Bán - Thuê</a></li>-->
<!--            <li><a href="#">Cần mua - Cần thuê</a></li>-->
<!--            <li><a href="#">Dự án</a></li>-->
<!--            <li><a href="#">Tin tức</a></li>-->
<!--        </ul>-->
    </div>
</header>