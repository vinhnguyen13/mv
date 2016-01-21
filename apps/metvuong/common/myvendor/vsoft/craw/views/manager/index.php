<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use funson86\cms\Module;
use vsoft\craw\models\AdCategory;
use vsoft\craw\models\AdProduct;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CmsShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('cms', 'Building Project');

$type = [
	AdProduct::TYPE_FOR_SELL => Yii::t ( 'ad', 'Nhà đất bán' ),
	AdProduct::TYPE_FOR_RENT => Yii::t ( 'ad', 'Nhà đất cho thuê' )
];
?>
<div class="cms-show-index">
	<h2 class="title">Danh sách tin craw</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			['attribute' => 'category_id', 'value' => 'category.name', 'filter' => Html::activeDropDownList($filterModel, 'category_id', ArrayHelper::map(AdCategory::find()->all(), 'id', 'name'), ['class' => 'form-control', 'prompt' => 'Chọn loại tin'])],
    		['attribute' => 'project', 'value' => 'project.name'],
    		'home_no',
    		['attribute' => 'ward', 'value' => 'ward.fullName'],
    		['attribute' => 'district', 'value' => 'district.fullName'],
    		['attribute' => 'street', 'value' => 'street.fullName'],
    		['attribute' => 'city', 'value' => 'city.name'],
    		['attribute' => 'type', 'value' => function($m) { return $m->getTypeText(); }, 'filter' => Html::activeDropDownList($filterModel, 'type', $type, ['class' => 'form-control', 'prompt' => 'Chọn hình thức'])],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
