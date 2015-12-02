<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<script type="text/javascript">
    var jsonActive = [
        {
            "idItem": 1,
            "stepId": 1,
            "stepShow": "#valTT",
            "name_item": "Hồ chí minh"
        },
        {
            "idItem": 2,
            "stepId": 2,
            "stepShow": "#valQh",
            "name_item": "Quận 1"
        },
        {
            "idItem": 3,
            "stepId": 3,
            "stepShow": "#valLoai",
            "name_item": "Nhà mặt phố"
        }
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
            <div class="bgcover logo-home ani-logo" style="background-image:url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/logo.png);"><a href="<?=Url::home()?>"></a></div>
            <div class="box-search-header clearfix ani-search">
                <div class="pull-left">
                    <?php $form = ActiveForm::begin([
                        'options'=>['class' => 'form-inline pull-left', 'method'=>'POST'],
                        'id'=>'search-kind',
                        'action'=>Url::to(['/ads/search']),
                        'fieldConfig' => [],
                    ]); ?>
                        <div class="form-group">
                            <div class="type-search">
                                <ul class="outsideevent"></ul>
                                <input id="searchInput" name="search" type="text" class="form-control outsideevent" placeholder="" disabled>
                            </div>
                            <div id="step-1" class="outsideevent search-wrap hidden-effect" data-txt-step="Bạn ở Thành phố nào ?">
                                <div class="wrap-effect">
                                    <div class="search-item">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <h3>--Chọn Tỉnh/Thành phố--</h3>
                                        <ul class="clearfix list-tinh-thanh">
                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div id="step-2" class="outsideevent search-wrap hidden-effect" data-txt-step="Bạn ở Quận nào ?">
                                <div class="wrap-effect">
                                    <div class="search-item clearfix">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <h3>Bạn ở Quận nào ?</h3>
                                        <ul class="list-quan-huyen">
                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div id="step-3" class="outsideevent search-wrap hidden-effect" data-txt-step="Loại BDS bạn quan tâm ?">
                                <div class="wrap-effect">
                                    <div class="search-item clearfix">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <h3>Loại BDS bạn quan tâm ?</h3>
                                        <ul class="list-loai-bds">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div id="step-4" class="outsideevent search-wrap hidden-effect" data-txt-step="Tìm kiếm...">
                                <div class="wrap-effect">
                                    <div class="search-item clearfix">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <div class="suggest-search-text">
                                            <h3>--Gợi ý--</h3>
                                            <div class="loading-suggest"></div>
                                            <ul class="clearfix" style="display:none;">
                                                <li><a href="#">Bè, Hồ Chí Minh Huyện <strong>Nhà</strong> Bè, Hồ Chí Minh<span>(1935 tin)</span></a></li>
                                                <li><a href="#">Trang, Khánh Hòa Thành phố <strong>Nha</strong> Trang, Khánh Hòa<span>(1935 tin)</span></a></li>
                                                <li><a href="#">Quận Thanh Xuân, Hà Nội Phường Nhân Chính, Quận Thanh Xuân, Hà Nội<span>(1935 tin)</span></a></li>
                                                <li><a href="#">Thành phố <strong>Nha</strong> Trang, Khánh Hòa<span>(1935 tin)</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="step-5" data-step-box="fixed" class="outsideevent search-wrap hidden-effect" data-txt-step="Bạn nên chọn Loại Tin Tức ?">
                                <div class="wrap-effect">
                                    <div class="search-item clearfix">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        <h3>Bạn nên chọn Loại Tin Tức ?</h3>
                                        <ul class="list-loai-tt">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="btn-search" type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                        <input class="getValSuggest" type="hidden" id="valTT" name="city" value="">
                        <input class="getValSuggest" type="hidden" id="valQh" name="district" value="">
                        <input class="getValSuggest" type="hidden" id="valLoai" name="category" value="">
                        <input class="getValSuggest" type="hidden" id="valTTuc" name="news" value="">
                    <?php ActiveForm::end(); ?>
                    <div class="pull-left text-right mgT-10 mgL-15">
                        <div class="search-select active">
                            <a href="#" data-placeholder="Bạn ở Thành phố nào ?" rel="#dd-search">
                                <span>
                                    <em class="fa fa-home"></em>
                                    <em class="fa fa-search"></em>
                                </span>
                                <i>Muốn Mua/Thuê</i>
                            </a>
                        </div>
                        <div class="search-select">
                            <a href="#" data-placeholder="Bạn ở Thành phố nào ?" rel="#dd-dky">
                                <span>
                                    <em class="fa fa-home"></em>
                                    <em class="fa fa-pencil-square-o"></em>
                                </span>
                                <i>Đăng ký Bán/Thuê</i>
                            </a>
                        </div>
                        <div class="search-select">
                            <a href="#" class="" data-step-fix="step-5" data-placeholder="--Chọn Loại Tin Tức--" rel="#dd-news">
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