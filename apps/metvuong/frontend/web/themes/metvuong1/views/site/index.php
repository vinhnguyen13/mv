<?php
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('express','We offer exeptional amenities and renowned white - glove services');
?>
<?php $this->beginContent('@app/views/layouts/_partials/js/jsContainer.php', ['options'=>[]]); ?><?php $this->endContent();?>
<script>
    $(document).ready(function(){
        $(document).on('click', '#btn-search', function(){
            animateSearch();
            setTimeout(function() {
                setTimeout(function() {
                    $('#search-kind').submit();
                },500);
            },1000);
            return false;
        });

        $(document).bind( 'real-estate/news', function(event, json, string){
            animateSearch();
            setTimeout(function() {
                setTimeout(function() {$('#search-kind').submit();},100);
            },1000);
        });
        
        $(document).bind( 'real-estate/post', function(event, json, string){
            animateSearch();
            setTimeout(function() {
                setTimeout(function() {$('#search-kind').submit();},100);
            },1000);
        });

    });
</script>
<div class="o-wrapper clearfix wrap-page-home">
    <header class="home-page cd-secondary-nav">
        <div class="container clearfix">
            <?php $this->beginContent('@app/views/layouts/_partials/menuMain.php'); ?><?php $this->endContent();?>
            <div class="wrap-search-home">
                <div class="wrap-logo">
                    <div class="bgcover logo-home" style="background-image:url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/logo.png);"><a href="<?=Url::home()?>"></a></div>
                </div>
                <div class="box-search-header clearfix">
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
                                    <span><em class="fa fa-search"></em></span>
                                </button>

                                <input id="valSearch" type="hidden" value="">
                                <input id="valTabActive" type="hidden" value="">
                                
                            <?php ActiveForm::end(); ?>
                            <div class="pull-left text-right mgT-10">
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
        </div>
    </header>
    <div class="container">

    </div>
    <footer class="clearfix">
        <div class="pull-left copyright">
            <p><span>&copy;</span>2015. Bản quyền thuộc về Công ty Metvuong</p>
        </div>
        <div class="pull-right polli">
            <ul>
                <li><a href="#">Giới thiệu</a></li>
                <li><a href="#">Điều khoản</a></li>
                <li>
                    <span>Kết nối:</span>
                    <a title="facebook metvuong.com" class="logo-social fb-icon" href="#"></a>
                    <a title="twitter metvuong.com" class="logo-social twe-icon" href="#"></a>
                    <a title="google plus metvuong.com" class="logo-social g-icon" href="#"></a>
                    <a title="youtube metvuong.com" class="logo-social ytu-icon" href="#"></a>
                </li>
            </ul>
        </div>
    </footer>
    <div id="iePopup">
        <div id="jr_overlay"></div>
        <div id="jr_wrap">
            <div id="jr_inner">
                <h1 id="jr_header">Bạn có biết rằng trình duyệt của bạn đã lỗi thời?</h1>
                <p>Trình duyệt của bạn đã lỗi thời, và có thể không tương thích tốt với website, chắc chắn rằng trải nghiệm của bạn trên website sẽ bị hạn chế. Bên dưới là danh sách những trình duyệt phổ biến hiện nay.</p>
                <p>Click vào biểu tượng để tải trình duyệt bạn muốn.</p>
                <ul>
                    <li id="jr_chrome"><a href="http://www.google.com/chrome/" target="_blank">Chrome 34</a></li>
                    <li id="jr_firefox"><a href="http://www.mozilla.com/firefox/" target="_blank">Firefox 29</a></li>
                    <li id="jr_msie"><a href="http://www.microsoft.com/windows/Internet-explorer/" target="_blank">Internet Explorer 10</a></li>
                    <li id="jr_opera"><a href="http://www.opera.com/download/" target="_blank">Opera 20</a></li>
                    <li id="jr_safari"><a href="http://www.apple.com/safari/download/" target="_blank">Safari</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $this->beginContent('@app/views/layouts/_partials/popup.php'); ?><?php $this->endContent();?>