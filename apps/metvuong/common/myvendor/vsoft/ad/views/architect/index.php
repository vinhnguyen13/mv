<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\ad\models\AdArchitectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('architect', 'Ad Architects');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-architect-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('architect', 'Create Ad Architect'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'address',
            'phone',
//            'fax',
             'website:url',
             'email:email',
             'description',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return \vsoft\news\models\Status::labels($model->status);
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', \vsoft\news\models\Status::labels(),['class'=>'form-control','prompt' => 'All']),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
