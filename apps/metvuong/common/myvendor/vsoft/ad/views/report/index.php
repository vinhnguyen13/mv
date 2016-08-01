<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\ad\models\AdProductReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Reports';
$this->params['breadcrumbs'][] = $this->title;

$action_get_user = Url::to(['report/get-user-report']);
$script = <<<EOD
$('.modal').each(function () {
    var t = $(this),
        d = t.find('.modal-dialog'),
        fadeClass = (t.is('.fade') ? 'fade' : '');
    // render dialog
    t.removeClass('fade')
        .addClass('invisible')
        .css('display', 'block');
    // read and store dialog height
    d.data('height', d.height());
    // hide dialog again
    t.css('display', '')
        .removeClass('invisible')
        .addClass(fadeClass);
});

$('#user-report').on('show.bs.modal', function (event) {
    var t = $(this),
        d = t.find('.modal-dialog'),
        dh = d.data('height'),
        w = $(window).width(),
        h = $(window).height();
    // if it is desktop & dialog is lower than viewport
    // (set your own values)
    if (w > 380 && (dh + 60) < h) {
        d.css('margin-top', Math.round(0.96 * (h - dh) / 2));
    } else {
        d.css('margin-top', '');
    }
});

$('.user_report').click(function(){
    $('#popup-user-report .modal-body').html('');
    var pid = $(this).parents().parents().data('key').product_id;
    if(pid){
        $.ajax({
            type: "get",
            dataType: 'html',
            url: '{$action_get_user}?product_id='+pid,
            success: function (data) {
                if(data){
                    $('#popup-user-report .modal-body').html(data);
                    $('#popup-user-report').modal('show');
                }
            }
        });
    }
});

EOD;
$this->registerJs($script, View::POS_READY);


?>
<div class="ad-product-report-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'product_id',
                'format' => 'html',
                'value' => function ($model) {
                    $ad_product = \vsoft\ad\models\AdProduct::findOne($model->product_id);
                    $link = Yii::$app->urlManager->hostInfo. "/mv". $model->product_id;
                    $text = $ad_product->getAddress($ad_product->show_home_no);
                    $str_return = "<a href='{$link}'>{$text}</a> ";
                    return $str_return;
                },
            ],
            [
                'label' => 'Count',
                'attribute' => 'count_user',
                'format' => 'html',
                'value' => function ($model) {
                    return "<a href='#' class='user_report'>{$model->count_user}</a>";
                }
            ],
            [
                'attribute' => 'report_at',
                'value' => function ($model) {
                    return $model->report_at;
                },
                'format' => ['datetime', 'php: d/m/Y H:i']
            ],
            'ip'
        ],
    ]); ?>

</div>
<div id="popup-user-report" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">User report</h4>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>