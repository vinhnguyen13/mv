<?php
use vsoft\news\widgets\NewsWidget;
?>
<div class="news-default-index">
    <h1>News</h1>
    <div class="content">
        <div clas="slider">
            This is slider.
        </div>
        <div class="template_1_2">
            <?= NewsWidget::widget(['c_id' => 5, 's_id' => 0, 'view' => null]) ?>
        </div>
        <div class="template_2_3">
            <?= NewsWidget::widget(['c_id' => 4, 's_id' => 0, 'view' => null]) ?>
        </div>
        <div class="template_1_8">

        </div>

    </div>

</div>
