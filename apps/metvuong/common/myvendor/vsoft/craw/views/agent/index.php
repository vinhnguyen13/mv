<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\craw\models\AdAgentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('agent', 'Agents');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-agent-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('agent', 'Create Ad Agent'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'name',
            'address',
            'mobile',
            'phone',
            // 'fax',
            // 'email:email',
            // 'website',
            // 'tax_code',
            // 'rating',
            // 'working_area',
            // 'source',
            // 'type',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
