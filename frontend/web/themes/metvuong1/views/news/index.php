<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 slider">
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <div class="item active">
                        <img src="<?= Yii::$app->view->theme->baseUrl ?>/resources/img/01.png" alt="...">

                        <div class="carousel-caption">
                            <h3>9 dự án đang và sẽ thay đổi diện mạo Quận 4</h3>
                            <b>by Mr Property</b>
                        </div>
                    </div>

                    <div class="item">
                        <img src="<?= Yii::$app->view->theme->baseUrl ?>/resources/img/02.png" alt="...">

                        <div class="carousel-caption">
                            <h3>9 dự án đang và sẽ thay đổi diện mạo Quận 4</h3>
                            <b>by Mr Property</b>
                        </div>
                    </div>
                    <div class="item">
                        <img src="<?= Yii::$app->view->theme->baseUrl ?>/resources/img/03.png" alt="...">

                        <div class="carousel-caption">
                            <h3>9 dự án đang và sẽ thay đổi diện mạo Quận 4</h3>
                            <b>by Mr Property</b>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="fa fa-angle-left"></span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="fa fa-angle-right"></span>
                </a>
            </div>
            <!-- Carousel -->
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 sibarnew">
            <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'sidebar']) ?>
        </div>
    </div>
    <!--row01-->
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 catbox">
            <div class="rowone">
                <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'batdongsan']) ?>
            </div>
            <!---rowone-->
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 grd11cover">
                <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'taichinh']) ?>
            </div>
            <!---grd11cover-->
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 grd11cover2">
                <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'doanhnghiep']) ?>
            </div>
            <!---grd11cover2-->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "></div>
            <!---grdtin noi bat-->
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 rgtcol">
            <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'quantam']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h2 class="titlebar">Dư an nổi bật</h2>

            <div class="brandbar">
                <div class="grd6">
                    <h3 class="cap rotobobold">Nông nghiệp Việt Nam đón nhận làn </h3>

                    <p class="textcatbox">Nông nghiệp Việt Nam đón nhận làn sóng đầu tư chưa từng có Nông nghiệp Việt
                        Nam đón nhận làn sóng đầu tư chưa từng có Nông nghiệp Việt Nam đón nhận làn sóng đầu tư chưa
                        từng có </p>

                    <ul class="listsel">
                        <li><i class="iconlist"></i>Plaschem Plaza</li>
                        <li><i class="iconlist"></i>Tòa nhà văn phòng 360 Tây Sơn</li>
                        <li><i class="iconlist"></i>Handico Tower</li>
                    </ul>
                </div>
                <ul class="grd3">
                    <li class="imageshow"><img src="<?= Yii::$app->view->theme->baseUrl ?>/resources/img/001.png"></li>
                    <li class="imageshow"><img src="<?= Yii::$app->view->theme->baseUrl ?>/resources/img/001.png"></li>
                    <li class="imageshow"><img src="<?= Yii::$app->view->theme->baseUrl ?>/resources/img/001.png"></li>
                </ul>
            </div>
        </div>
    </div>
    <!---du an noi bat-->
    <div class="row">
        <?= \vsoft\news\widgets\NewsWidget::widget(['view'=>'kinhte'])?>
    </div>
    <!---kinh te vi mo-->
</div>