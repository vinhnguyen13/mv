<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CmsShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('cms', 'Danh sách thành phố');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-show-index">
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
			'id',
        	'name',
        	'code',
        	'slug',
        	'pre',
            [
            	'class' => 'yii\grid\ActionColumn',
            	'buttons' => [
            		'geo' => function($url, $model, $key) {
            			return Html::a('Geo this + All Districts + All Wards', Url::to(['geo-recursive', 'cityId' => $model->id]));
					},	
				],
				'template' => '{geo}'
			],
        ],
    ]); ?>

</div>
