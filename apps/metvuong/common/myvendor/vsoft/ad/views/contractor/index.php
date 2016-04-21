<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\ad\models\AdContractorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('contractor', 'Ad Contractors');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-contractor-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('contractor', 'Create Ad Contractor'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'address',
            'phone',
            'fax',
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
