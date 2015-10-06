<?php
/* @var $this yii\web\View */
use vsoft\news\widgets\NewsWidget;

?>
<h1>NEWS</h1>

<div class="content">
    <div clas="slider">
        <hr>
        <b>
        This is slider.</b>
    </div>

    <div class="template_1_2">
        <hr>
        <b>
            Khu vuc Phia sau Slider
        </b>
        <?= NewsWidget::widget(['c_id' => 4, 's_id' => 0, 'view' => null, 'care' => null, 'after_slider' => true ]) // tai chinh ?>
    </div>


    <div class="template_2_3">
        <hr>
        <b>
            Khu vuc Bat dong san 2_3
        </b>
        <?= NewsWidget::widget(['c_id' => 2, 's_id' => 0, 'view' => null]) // bat dong san bt ?>
    </div>


    <div class="template_1_8">
        <hr>
        <b>
            Khu vuc Dang Quan Tam 1_8
        </b>
        <?= NewsWidget::widget(['c_id' => 2, 's_id' => 0, 'view' => null, 'care' => true, 'after_slider' => null ]) // bat dong san dang quan tam?>
    </div>


    <div class="template_1_1">
        <hr>
        <b>
            Khu vuc Template_1_1
        </b>
        <?= NewsWidget::widget(['c_id' => 4, 's_id' => 0, 'view' => null, 'care' => null, 'after_slider' => null ]) // tai chinh ?>
    </div>

    <div class="template_1_1">
        <hr>
        <b>
            Khu vuc Template_1_1
        </b>
        <?= NewsWidget::widget(['c_id' => 4, 's_id' => 0, 'view' => null, 'care' => null, 'after_slider' => null ]) // tai chinh ?>
    </div>


    <div class="template_3_1">
        <hr>
        <b>
            Khu vuc Template_3_1
        </b>
        <?= NewsWidget::widget(['c_id' => 5, 's_id' => 0, 'view' => null, 'care' => null, 'after_slider' => null]) // doanh nghiep last widget ?>
    </div>

    <div>
        <hr>
        <b>
            Khu vuc Footer
        </b>

    </div>

</div>
