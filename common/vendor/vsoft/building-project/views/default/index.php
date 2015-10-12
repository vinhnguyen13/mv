<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use funson86\cms\Module;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CmsShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('cms', 'Cms Shows');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-show-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Module::t('cms', 'Thêm dự án'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
			'created_at:date',
			'updated_at:date',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
