<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\ad\models\AdProductReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Reports';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-product-report-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
<?= Html::a('Create Product Report', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    $user = \frontend\models\User::findOne($model->user_id);
                    return $user->username;
                },
            ],
            [
                'attribute' => 'product_id',
                'value' => function ($model) {
                    $ad_product = \vsoft\ad\models\AdProduct::findOne($model->product_id);
                    return $ad_product->getAddress();
                },
            ],
            'type',
//            'description',
            'ip',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return \vsoft\news\models\Status::labels($model->status);
                },
                'options' => ['width' => '100']
            ],
            [
                'attribute' => 'report_at',
                'value' => function ($model) {
                    return $model->report_at;
                },
                'format' => ['datetime', 'php: d/m/Y H:i a']
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
