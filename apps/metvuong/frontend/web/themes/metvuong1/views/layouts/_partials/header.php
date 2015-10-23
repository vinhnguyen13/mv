<header class="clearfix">
    <a href="#" class="logo-header pull-left"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/logo.png" alt="logo"></a>
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
            <span class="icon-sale pull-left"></span>
            <form class="form-inline pull-left" action="" id="search-kind">
                <div class="form-group">
                    <input type="text" class="form-control">
                </div>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                        <span class="txt-selected">LOẠI</span>
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="#">LOẠI 1</a></li>
                        <li><a href="#">LOẠI 2</a></li>
                        <li><a href="#">LOẠI 3</a></li>
                    </ul>
                </div>
                <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
            </form>
            <div class="pull-right text-right">
                <a href="#" class="icon-cart"></a>
                <a href="#" class="icon-door"></a>
                <a href="#" class="icon-handhome"></a>
            </div>
        </div>
    </div>
    <a href="#" id="slide-menu-right"><em class="fa fa-reorder"></em></a>
</header>


<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-home"></span></a>
        </div>
        <div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Bất động sản  </a></li>
                <li><a href="#">Chứng khoán</a></li>
                <li><a href="#">Tài chính &amp; ngân hàng</a></li>
                <li><a href="#">Doanh Nghiệp</a></li>
                <li><a href="#">kinh tế vĩ mô</a></li>
                <li><a href="#">phong thủy</a></li>
            </ul>
        </div>
    </div>
</nav>