<?php
use yii\helpers\Url;
?>
<header class="clearfix cd-secondary-nav">
    <div class="container">
        <a href="#" class="logo-header pull-left bgcover" style="background-image:url('<?=Yii::$app->view->theme->baseUrl?>/resources/images/logo.png');"></a>
        <div class="pull-right user-setting">
            <div class="dropdown select-lang">
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                    <span class="icon-lang-select lang-<?=substr(Yii::$app->language, 0, 2)?>"></span>
                <span class="caret"></span></button>
                <?php $supportedLanguages = Yii::$app->bootstrap['languageSelector']['supportedLanguages'];?>
                <ul class="dropdown-menu">
                    <li><a class="icon-lang lang-vi<?=(!empty($supportedLanguages[1]) && Yii::$app->language == $supportedLanguages[1]) ? ' active' : '';?>" href="<?=Url::current(['language-change' => $supportedLanguages[1]])?>">VIETNAMESE</a></li>
                    <li><a class="icon-lang lang-en<?=(!empty($supportedLanguages[0]) && Yii::$app->language == $supportedLanguages[0]) ? ' active' : '';?>" href="<?=Url::current(['language-change' => $supportedLanguages[0]])?>">ENGLISH</a></li>
<!--                    <li><a class="icon-lang lang-en" href="#">ENGLISH</a></li>-->
<!--                    <li><a class="icon-lang lang-vi" href="#">VIETNAMESE</a></li>-->
                </ul>
            </div>
            <a class="user-option" href="#"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Mr.Nguyen Ong</a>
            <a href="#">logout</a>
        </div>
        <div class="box-search-header clearfix">
            <div class="pull-left">
                
                <form class="form-inline pull-left" action="" id="search-kind">
                    <div class="form-group">
                        <!-- <input type="text" class="form-control" placeholder="<?=Yii::t('header', 'Find')?>..."> -->
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
                                        <ul class="clearfix">
                                            <li><a href="#">Cần bán căn 70m2 <strong>chung cư</strong> Điện Lực Tower, Trung Hòa Nhân Chính, nhận nhà ngay Điện Lực Tower, Trung Hòa Nhân Chính, nhận nhà ngay</a></li>
                                            <li><a href="#">Bán gấp 3 lô đất KDC An Thạnh, Thuận An. Giá 1 tỷ 450tr/lô</a></li>
                                            <li><a href="#"><strong>Chung cư</strong> 87 Lĩnh Nam New Horizon, chân trời mới cho hạnh phúc của bạn</a></li>
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
                                        <ul class="clearfix">
                                            <li><a href="#">Cần bán căn 70m2 <strong>chung cư</strong> Điện Lực Tower, Trung Hòa Nhân Chính, nhận nhà ngay Điện Lực Tower, Trung Hòa Nhân Chính, nhận nhà ngay</a></li>
                                            <li><a href="#">Bán gấp 3 lô đất KDC An Thạnh, Thuận An. Giá 1 tỷ 450tr/lô</a></li>
                                            <li><a href="#"><strong>Chung cư</strong> 87 Lĩnh Nam New Horizon, chân trời mới cho hạnh phúc của bạn</a></li>
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
                                        <ul class="clearfix">
                                            <li><a href="#">Cần bán căn 70m2 <strong>chung cư</strong> Điện Lực Tower, Trung Hòa Nhân Chính, nhận nhà ngay Điện Lực Tower, Trung Hòa Nhân Chính, nhận nhà ngay</a></li>
                                            <li><a href="#">Bán gấp 3 lô đất KDC An Thạnh, Thuận An. Giá 1 tỷ 450tr/lô</a></li>
                                            <li><a href="#"><strong>Chung cư</strong> 87 Lĩnh Nam New Horizon, chân trời mới cho hạnh phúc của bạn</a></li>
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
                    </div>
                    
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                </form>
                <div class="pull-left text-right mgT-10 mgL-15">
                    <div class="search-select"><a href="#" data-placeholder="Tìm Kiếm..." rel="#dd-search"><em class="fa fa-home"></em><em class="fa fa-search"></em></a></div>
                    <div class="search-select"><a href="#" data-placeholder="Đăng Ký..." rel="#dd-dky"><em class="fa fa-home"></em><em class="fa fa-usd"></em></a></div>
                    <div class="search-select active"><a href="#" class="no-suggest" data-placeholder="Tin Tức..." rel="#dd-news"><em class="fa fa-home"></em><em class="fa fa-file-text"></em></a></div>
                </div>
            </div>
        </div>
    </div>
</header>