<header class="clearfix cd-secondary-nav">
    <div class="container">
        <a href="#" class="logo-header pull-left bgcover" style="background-image:url('<?=Yii::$app->view->theme->baseUrl?>/resources/images/logo.png');"></a>
        <div class="pull-right user-setting">
            <div class="dropdown select-lang">
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                    <span class="icon-lang-select lang-vi"></span>
                <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a class="icon-lang lang-en" href="#">ENGLISH</a></li>
                    <li><a class="icon-lang lang-vi" href="#">VIETNAMESE</a></li>
                </ul>
            </div>
            <a class="user-option" href="#"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Mr.Nguyen Ong</a>
            <a href="#">logout</a>
        </div>
        <div class="box-search-header clearfix">
            <div class="pull-left">
                
                <form class="form-inline pull-left" action="" id="search-kind">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Tìm Kiếm...">
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                            <span class="txt-selected">Loại...</span>
                            <span class="wrap-arrow-dropdown"><span class="caret"></span></span>
                        </button>
                        <ul id="dd-search" class="dropdown-menu">
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
                        <ul id="dd-news" class="">
                            <li><a data-value-id="1" data-value-text="Dự án" href="#">Dự án</a></li>
                        </ul>
                    </div>
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                </form>
                <div class="pull-left text-right mgT-10 mgL-15">
                    <div class="search-select active"><a href="#" data-placeholder="Tìm Kiếm..." rel="#dd-search"><em class="fa fa-home"></em><em class="fa fa-search"></em></a></div>
                    <div class="search-select"><a href="#" data-placeholder="Đăng Ký..." rel="#dd-dky"><em class="fa fa-home"></em><em class="fa fa-usd"></em></a></div>
                    <div class="search-select"><a href="#" class="" data-placeholder="Tin Tức..." rel="#dd-news"><em class="fa fa-home"></em><em class="fa fa-file-text"></em></a></div>
                </div>
            </div>
        </div>
    </div>
</header>
<?php
use vsoft\news\models\CmsCatalog;
use vsoft\news\models\Status;

$catalog = CmsCatalog::getDb()->cache(function($db){
    return CmsCatalog::find()->where(['status' => Status::STATUS_ACTIVE])->andWhere('parent_id = :pid',[':pid' => Yii::$app->params['newsCatID']])->asArray()->orderBy('id')->limit(6)->all();
});

?>
