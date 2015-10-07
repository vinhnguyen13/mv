<?php
/* @var $this yii\web\View */
use vsoft\news\widgets\NewsWidget;
?>
<style>
    .carousel .item {
        height: 330px;
    }
    .item > img {
        width: 100%;
    }
</style>

<h1>NEWS</h1>
<!-- Header Carousel -->
<div class="col-lg-12">
    <div id="myCarousel" class="carousel slide">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
                <img src="https://c1.staticflickr.com/1/688/21331645963_a401b9fa3b_b.jpg">
                <div class="carousel-caption">
                    <h3>Caption 1</h3>
                </div>
            </div>
            <div class="item">
                <img src="https://c2.staticflickr.com/6/5723/21787103720_cae2be80bf_b.jpg">

                <div class="carousel-caption">
                    <h3>Caption 2</h3>
                </div>
            </div>
            <div class="item">
                <img src="https://c2.staticflickr.com/6/5819/21784781758_e39965778f_b.jpg">

                <div class="carousel-caption">
                    <h3>Caption 3</h3>
                </div>
                </img>
            </div>

            <!-- Controls -->
            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                <span class="icon-prev"></span>
            </a>
            <a class="right carousel-control" href="#myCarousel" data-slide="next">
                <span class="icon-next"></span>
            </a>
        </div>
    </div>
    <br>
</div>

<div class="content">
    <div class="template_1_2">
        <hr>
        <b>
            Khu vuc Ben Phai Slider
        </b>
        <?= NewsWidget::widget(['c_id' => 4, 's_id' => 0, 'view' => null, 'care' => null, 'after_slider' => true]) // tai chinh   ?>
    </div>

    <div class="template_2_3">
        <hr>
        <b>
            Khu vuc Bat dong san
        </b>
        <?= NewsWidget::widget(['c_id' => 2, 's_id' => 0, 'view' => null]) // template_2_3   ?>
    </div>


    <div class="template_1_8">
        <hr>
        <b>
            Khu vuc Dang Quan Tam 1_8
        </b>
        <?= NewsWidget::widget(['c_id' => 2, 's_id' => 0, 'view' => null, 'care' => true, 'after_slider' => null]) ?>
    </div>


    <div class="template_1_1">
        <hr>
        <b>
            Khu vuc Tai Chinh Template_1_1
        </b>
        <?= NewsWidget::widget(['c_id' => 4, 's_id' => 0, 'view' => null, 'care' => null, 'after_slider' => null]) // tai chinh   ?>
    </div>

    <div class="template_1_1">
        <hr>
        <b>
            Khu vuc Doanh Nghiep Template_1_1
        </b>
        <?= NewsWidget::widget(['c_id' => 4, 's_id' => 0, 'view' => null, 'care' => null, 'after_slider' => null]) // tai chinh   ?>
    </div>

    <div class="template_duan">
        <hr>
        <b>
            Khu vuc Du An Noi Bat
        </b>
        <br>
    </div>

    <div class="template_3_1">
        <hr>
        <b>
            Khu vuc Template_3_1
        </b>
        <?= NewsWidget::widget(['c_id' => 5, 's_id' => 0, 'view' => null, 'care' => null, 'after_slider' => null]) // doanh nghiep last widget   ?>
    </div>

    <div>
        <hr>
        <b>
            Khu vuc Footer
        </b>

    </div>
</div>

<!-- Script to Activate the Carousel -->
<script>
    $('.carousel').carousel({
        interval: 1000 //changes the speed
    })
</script>
