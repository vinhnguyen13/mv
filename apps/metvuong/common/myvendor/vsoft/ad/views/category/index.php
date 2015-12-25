<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use funson86\cms\Module;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CmsShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('cms', 'Investor');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-show-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('cms', 'Thêm loại tin'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('cms', 'Sắp xếp thứ tự'), ['sort'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
			'status',
			'limit_area',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
