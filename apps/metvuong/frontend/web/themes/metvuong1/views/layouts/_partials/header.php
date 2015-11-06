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
                        <input type="text" class="form-control" placeholder="<?=Yii::t('header', 'Find')?>...">
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                            <span class="txt-selected">Loại...</span>
                            <span class="wrap-arrow-dropdown"><span class="caret"></span></span>
                        </button>
                        <ul id="dd-search" class="">
                            <li><a data-value-id="1" data-value-text="Chung cư" href="#">Chung cư</a></li>
                            <li><a  data-value-id="2" data-value-text="Nhà riêng" href="#">Nhà riêng</a></li>
                            <li><a  data-value-id="3" data-value-text="Văn phòng" href="#">Văn phòng</a></li>
                            <li><a  data-value-id="3" data-value-text="DAT" href="#">DAT</a></li>
                        </ul>
                        <ul id="dd-dky" class="">
                            <li><a data-value-id="1" data-value-text="Chung cư" href="#">Chung cư</a></li>
                            <li><a  data-value-id="2" data-value-text="Căn hộ" href="#">Căn hộ</a></li>
                            <li><a  data-value-id="3" data-value-text="Nhà riêng" href="#">Nhà riêng</a></li>
                            <li><a  data-value-id="3" data-value-text="Văn phòng" href="#">Văn phòng</a></li>
                            <li><a  data-value-id="3" data-value-text="DAT" href="#">DAT</a></li>
                        </ul>
                        <ul id="dd-news" class="dropdown-menu">
                            <li><a data-value-id="1" data-value-text="Tin tức" href="<?=Url::to(['news/index'])?>">Tin tức</a></li>
                            <li><a data-value-id="2" data-value-text="Dự án" href="<?=Url::to(['/building-project'])?>">Dự án</a></li>
                        </ul>
                    </div>
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                </form>
                <div class="pull-left text-right mgT-10 mgL-15">
                    <div class="search-select"><a href="#" data-placeholder="Tìm Kiếm..." rel="#dd-search"><em class="fa fa-home"></em><em class="fa fa-search"></em></a></div>
                    <div class="search-select"><a href="#" data-placeholder="Đăng Ký..." rel="#dd-dky"><em class="fa fa-home"></em><em class="fa fa-usd"></em></a></div>
                    <div class="search-select active"><a href="#" class="" data-placeholder="Tin Tức..." rel="#dd-news"><em class="fa fa-home"></em><em class="fa fa-file-text"></em></a></div>
                </div>
            </div>
        </div>
    </div>
</header>