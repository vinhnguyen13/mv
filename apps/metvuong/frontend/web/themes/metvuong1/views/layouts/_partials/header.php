<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$value = \Yii::$app->getRequest()->getCookies()->getValue('searchParams');
$searchParams = json_decode($value);
?>
<script type="text/javascript">
    $(document).bind( 'real-estate/news', function(event, json, string){
        //setTimeout(function() {$('#search-kind').submit();},100);
    });
    $(document).bind( 'real-estate/post', function(event, json, string){
        //setTimeout(function() {$('#search-kind').submit();},100);
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

                            <div class="outsideevent search-wrap hidden-effect">
                                <div class="wrap-effect">
                                    <div class="search-item">
                                        <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="btn-search" type="submit" class="btn btn-default">
                            <span><em class="fa fa-search"></em></span>
                        </button>

                        <input id="valSearch" type="hidden" value="">
                        <input id="valTabActive" type="hidden" value="">
                        
                    <?php ActiveForm::end(); ?>
                    <div class="pull-left text-right mgT-10 options-search">
                        <div class="icon-selected">
                            <a href="#">
                                <span><em class="fa fa-home"></em><em class="fa fa-file-text"></em></span>
                            </a>
                            <em class="fa fa-sort-desc"></em>
                        </div>
                        <div data-active="1" data-tab="mua-thue" class="search-select active" data-step='tinh-thanh|quan-huyen|{ "loai-bds": { "chung-cu": { "template": "loai-duan" }, "template_common": { "template": "min-max" } }}'>
                            <a href="#" title="Muốn Mua/Thuê">
                                <span>
                                    <em class="fa fa-home"></em>
                                    <em class="fa fa-search"></em>
                                </span>
                                <i>Muốn Mua/Thuê</i>
                            </a>
                        </div>
                        <div data-active="2" data-tab="ban-thue" class="search-select" data-step='tinh-thanh|quan-huyen|loai-bds'>
                            <a href="#" title="Đăng ký Bán/Thuê">
                                <span>
                                    <em class="fa fa-home"></em>
                                    <em class="fa fa-pencil-square-o"></em>
                                </span>
                                <i>Đăng ký Bán/Thuê</i>
                            </a>
                        </div>
                        <div data-active="3" data-tab="tin-tuc"  class="search-select" data-step='{ "news": { "tin-tuc": { "template": "loai-tin-tuc" }, "du-an": { "template": "tinh-thanh|quan-huyen|loai-duan" } }}'>
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
<!--        <ul class="list-menu-header">-->
<!--            <li><a href="#">Bán - Thuê</a></li>-->
<!--            <li><a href="#">Cần mua - Cần thuê</a></li>-->
<!--            <li><a href="#">Dự án</a></li>-->
<!--            <li><a href="#">Tin tức</a></li>-->
<!--        </ul>-->
    </div>
</header>