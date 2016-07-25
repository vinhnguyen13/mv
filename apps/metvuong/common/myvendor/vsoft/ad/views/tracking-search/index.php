<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use funson86\cms\Module;
use vsoft\ad\models\AdBuildingProject;
use vsoft\ad\models\AdStreet;
use vsoft\ad\models\AdWard;
use vsoft\ad\models\AdCategoryGroup;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CmsShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('cms', 'Tracking Search');
$this->params['breadcrumbs'][] = $this->title;

$categoryGroup = AdCategoryGroup::find()->all();

$categoryDropDown = [];
foreach ($categoryGroup as $categoryG) {
	$categoryDropDown[implode(',', $categoryG->categories_id)] = AdCategoryGroup::$mapTrans[$categoryG->name];
}
?>
<div class="cms-show-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
        	'location',
        	 [
				'attribute' => 'category',
				'value' => 'category.nameTranslate',
        		'filter' => Html::activeDropDownList($searchModel, 'category_search', $categoryDropDown, ['class' => 'form-control', 'prompt' => 'Loại BĐS'])
			],
        	'room_no',
        	'toilet_no',
        	'price_min',
        	'price_max',
        	'size_min',
        	'size_max',
        	'created_at:datetime'
        ],
    ]); ?>

</div>
