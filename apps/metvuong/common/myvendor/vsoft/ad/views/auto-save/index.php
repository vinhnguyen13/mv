<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use funson86\cms\Module;
use vsoft\express\components\StringHelper;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdCategory;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CmsShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('cms', 'Building Project');
$this->params['breadcrumbs'][] = $this->title;

$types = AdProduct::getAdTypes();
?>
<div class="cms-show-index">
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
				'attribute' => 'category',
				'value' => function($model) {return $model->category ? $model->category->name : null;},
				'filter' => Html::activeDropDownList($searchModel, 'category_id', ArrayHelper::map(AdCategory::find()->all(), 'id', 'name'), ['class' => 'form-control', 'prompt' => 'Chọn loại tin'])
			],
        	[
        		'attribute' => 'type',
        		'value' => function($model) use ($types) {return $types[$model->type];},
        		'filter' => Html::activeDropDownList($searchModel, 'type', $types, ['class' => 'form-control', 'prompt' => 'Chọn hình thức'])
        	],
			[
				'attribute' => 'project',
				'value' => function($model) {return $model->project ? $model->project->name : null;},
				'filter' => Html::activeTextInput($searchModel, 'project_search', ['class' => 'form-control min price', 'placeholder' => 'dự án'])
			],
        	[
        		'attribute' => 'city',
        		'value' => function($model) {return $model->district ? $model->city->name : null;},
				'filter' => Html::activeTextInput($searchModel, 'city_search', ['class' => 'form-control min price', 'placeholder' => 'thành phố'])
        	],
        	[
        		'attribute' => 'district',
        		'value' => function($model) {return $model->district ? $model->district->pre . ' ' . $model->district->name : null;},
				'filter' => Html::activeTextInput($searchModel, 'district_search', ['class' => 'form-control min price', 'placeholder' => 'quận/huyện'])
        	],
        	[
        		'attribute' => 'ward',
        		'value' => function($model) {return $model->ward ? $model->ward->pre . ' ' . $model->ward->name : null;},
				'filter' => Html::activeTextInput($searchModel, 'ward_search', ['class' => 'form-control min price', 'placeholder' => 'phường/xã'])
        	],
        	[
        		'attribute' => 'street',
        		'value' => function($model) {return $model->street ? $model->street->pre . ' ' . $model->street->name : null;},
				'filter' => Html::activeTextInput($searchModel, 'street_search', ['class' => 'form-control min price', 'placeholder' => 'đường/phố'])
        	],
        	'home_no',
			[
				'format' => 'raw',
				'attribute' => 'price',
				'value' => function($model) {return $model->price ? StringHelper::formatCurrency($model->price) : null;}
			],
			'created_at:date',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
