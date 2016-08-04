<?php

use funson86\cms\Module;
use yii\grid\GridView;
use yii\helpers\Html;


$this->title = Yii::t('cms', 'Building Project');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-show-index">
    <p>
        <?= Html::a(Yii::t('cms', 'Thêm dự án'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return date('d F Y', $model->created_at);
                },
                'filter' => false
            ],
            [
                'attribute' => 'click',
                'value' => function($model){
                    return $model->click;
                },
                'filter' => false
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
