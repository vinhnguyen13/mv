<?php
use yii\web\View;
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/bootstrap.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'bootstrap');
$this->registerCssFile("https://fonts.googleapis.com/css?family=Roboto:400,300,700", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'font-roboto');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/font-awesome.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'font-awesome');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/simple-line-icons.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'simple-line-icons');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/style-custom.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'style-custom');

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery.min.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/bootstrap.min.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/menu.min.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/common.js', ['position'=>View::POS_HEAD]);
/* @var $this yii\web\View */

$this->title = Yii::t('express','We offer exeptional amenities and renowned white - glove services');
?>
<div class="o-wrapper clearfix wrap-page-home">
    <header class="clearfix home-page">
        <ul class="pull-right">
            <li><a href="#"><em class="icon-plus"></em>Đăng tin</a></li>
            <li><a href="<?=\yii\helpers\Url::to(['building-project/index'])?>"><em class="fa fa-building-o"></em>Dự án</a></li>
            <li><a href="<?=\yii\helpers\Url::to(['news/index'])?>"><em class="fa fa-newspaper-o"></em>Tin tức</a></li>
            <li><a href="#"><em class="icon-user"></em>Đăng ký</a></li>
            <li><a href="#"><em class="icon-key"></em>Đăng nhập</a></li>
            <li class="lang-icon icon-en"><a href="#"></a></li>
            <li class="lang-icon icon-vi"><a href="#"></a></li>
        </ul>
    </header>
    <div class="container">
        <div class="wrap-search-home">
            <span class="helper-center"></span>
            <div class="insearch-box">
                <div class="bgcover logo-home" style="background-image:url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/logo.png);"><a href="#"></a></div>
                <div class="box-search-header clearfix">
                    <div class="pull-left">
                        <form class="form-inline pull-left" action="" id="search-kind">
                            <div class="form-group">
                                <div class="type-search">
                                    <ul></ul>
                                    <input type="text" class="form-control" placeholder="Tìm Kiếm...">
                                </div>
                                <div id="step-1" class="search-wrap hidden-effect">
                                    <div class="wrap-effect">
                                        <div class="search-item">
                                            <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                            <div class="suggest-search-text">
                                                <h3>--Gợi ý--</h3>
                                                <div class="loading-suggest"></div>
                                                <ul class="clearfix" style="display:none;">
                                                    <li><a href="#">Huyện <strong>Nhà</strong> Bè, Hồ Chí Minh<span>(1935 tin)</span></a></li>
                                                    <li><a href="#">Thành phố <strong>Nha</strong> Trang, Khánh Hòa<span>(1935 tin)</span></a></li>
                                                    <li><a href="#">Phường Nhân Chính, Quận Thanh Xuân, Hà Nội<span>(1935 tin)</span></a></li>
                                                    <li><a href="#">Thành phố <strong>Nha</strong> Trang, Khánh Hòa<span>(1935 tin)</span></a></li>
                                                </ul>
                                            </div>
                                            <h3>--Chọn Tỉnh/Thành phố--</h3>
                                            <ul class="clearfix">
                                                <li><a href="#">Hồ Chí Minh</a></li>
                                                <li><a href="#">Hà Nội</a></li>
                                                <li><a href="#">Bình Dương</a></li>
                                                <li><a href="#">Đà Nẵng</a></li>
                                                <li><a href="#">Hải Phòng</a></li>
                                                <li><a href="#">Long An</a></li>
                                                <li><a href="#">Hồ Chí Minh</a></li>
                                                <li><a href="#">Hà Nội</a></li>
                                                <li><a href="#">Bình Dương</a></li>
                                                <li><a href="#">Đà Nẵng</a></li>
                                                <li><a href="#">Hải Phòng</a></li>
                                                <li><a href="#">Long An</a></li>
                                                <li><a href="#">Hồ Chí Minh</a></li>
                                                <li><a href="#">Hà Nội</a></li>
                                                <li><a href="#">Bình Dương</a></li>
                                                <li><a href="#">Đà Nẵng</a></li>
                                                <li><a href="#">Hải Phòng</a></li>
                                                <li><a href="#">Long An</a></li>
                                                <li><a href="#">Hồ Chí Minh</a></li>
                                                <li><a href="#">Hà Nội</a></li>
                                                <li><a href="#">Bình Dương</a></li>
                                                <li><a href="#">Đà Nẵng</a></li>
                                                <li><a href="#">Hải Phòng</a></li>
                                                <li><a href="#">Long An</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div id="step-2" class="search-wrap hidden-effect">
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
                                            <h3>--Chọn Quận/Huyện--</h3>
                                            <ul>
                                                <li><a href="#">Quận 1</a></li>
                                                <li><a href="#">Quận 2</a></li>
                                                <li><a href="#">Quận 3</a></li>
                                                <li><a href="#">Quận 4</a></li>
                                                <li><a href="#">Quận 5</a></li>
                                                <li><a href="#">Quận 6</a></li>
                                                <li><a href="#">Quận 7</a></li>
                                                <li><a href="#">Quận 8</a></li>
                                                <li><a href="#">Quận 9</a></li>
                                                <li><a href="#">Quận 10</a></li>
                                                <li><a href="#">Quận 11</a></li>
                                                <li><a href="#">Quận 12</a></li>
                                                <li><a href="#">Bình Tân</a></li>
                                                <li><a href="#">Bình Thạnh</a></li>
                                                <li><a href="#">Gò Vấp</a></li>
                                                <li><a href="#">Phú Nhuận</a></li>
                                                <li><a href="#">Tân Bình</a></li>
                                                <li><a href="#">Tân Phú</a></li>
                                                <li><a href="#">Hồ Chí Minh</a></li>
                                                <li><a href="#">Hà Nội</a></li>
                                                <li><a href="#">Bình Dương</a></li>
                                                <li><a href="#">Đà Nẵng</a></li>
                                                <li><a href="#">Hải Phòng</a></li>
                                                <li><a href="#">Long An</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div id="step-3" class="search-wrap hidden-effect">
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
                                            <h3>--Chọn loại nhà đất--</h3>
                                            <ul>
                                                <li><a href="#">Chung cư</a></li>
                                                <li><a href="#">Văn phòng</a></li>
                                                <li><a href="#">Nhà riêng</a></li>
                                                <li><a href="#">Nhà biệt thự, liền kề</a></li>
                                                <li><a href="#">Nhà mặt phố</a></li>
                                                <li><a href="#">Bán đất</a></li>
                                                <li><a href="#">Đất nền dự án</a></li>
                                                <li><a href="#">Trang trại, khu nghỉ dưỡng</a></li>
                                                <li><a href="#">Bán kho, nhà xưởng</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div id="step-4" class="search-wrap hidden-effect">
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
                                <div id="step-5" data-step-box="fixed" class="search-wrap hidden-effect">
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
                                            <h3>--Chọn loại tin tức--</h3>
                                            <ul>
                                                <li><a href="#">Tin tức</a></li>
                                                <li><a href="#">Dự án</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-default">SEARCH</button>
                        </form>
                        <div class="pull-left text-right mgT-10 mgL-15">
                            <div class="search-select active"><a href="#" data-placeholder="Tìm Kiếm..." rel="#dd-search"><em class="fa fa-home"></em><em class="fa fa-search"></em></a></div>
                            <div class="search-select"><a href="#" data-placeholder="Đăng Ký..." rel="#dd-dky"><em class="fa fa-home"></em><em class="fa fa-usd"></em></a></div>
                            <div class="search-select"><a href="#" class="" data-step-fix="step-5" data-placeholder="Tin Tức..." rel="#dd-news"><em class="fa fa-home"></em><em class="fa fa-file-text"></em></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="clearfix">
        <!-- <div class="pull-left copyright">
            <p><span>&copy;</span>2015. Bản quyền thuộc về Công ty Metvuong</p>
        </div>
        <div class="pull-right polli">

        </div> -->
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