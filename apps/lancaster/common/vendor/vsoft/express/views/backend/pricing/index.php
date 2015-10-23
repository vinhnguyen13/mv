<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\express\models\LcPricingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('pricing', 'Pricings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lc-pricing-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('pricing', 'Create Pricing'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'area',
//            'apart_type_id',
            [
                'label' => 'Kind of apartment',
                'attribute' => 'apart_type_id',
                'value' => function ($model) {
                    if ($model->getApartType()) {
                        $name = $model->getApartType()->one()->name;
                        return $name;
                    }
                    return '';
                },
            ],
            'monthly_rates:decimal',
            'daily_rates:decimal',
            'description',
            'created_at:datetime',
            'updated_at:datetime',
            // 'created_by',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
