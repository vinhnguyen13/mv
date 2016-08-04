<?php

use funson86\cms\Module;
use yii\grid\GridView;
use yii\helpers\Html;


$this->title = Yii::t('cms', 'Building Project');
$this->params['breadcrumbs'][] = $this->title;

$cityData = \vsoft\ad\models\AdCity::getDb()->cache(function(){
    return \yii\helpers\ArrayHelper::map(\vsoft\ad\models\AdCity::find()->all(), 'id', 'name');
});
$districtData = \vsoft\ad\models\AdDistrict::getDb()->cache(function(){
    return \yii\helpers\ArrayHelper::map(\vsoft\ad\models\AdDistrict::find()->all(), 'id', 'name');
});
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
//            [
//                'attribute' => 'city_id',
//                'value' => function($model){
//                    $city = \vsoft\ad\models\AdCity::findOne($model->city_id);
//                    if($city)
//                        return $city->name;
//                    return null;
//                },
//                'filter' => Html::activeDropDownList($searchModel, 'city_id', $cityData,['class'=>'form-control','prompt' => 'All'])
//            ],
//            [
//                'attribute' => 'district_id',
//                'value' => function($model){
//                    $district = \vsoft\ad\models\AdDistrict::findOne($model->district_id);
//                    if($district)
//                        return $district->pre. " " . $district->name;
//                },
//                'filter' => Html::activeDropDownList($searchModel, 'district_id', $districtData,['class'=>'form-control','prompt' => 'All'])
//            ],
            [
                'attribute' => 'click',
                'value' => function($model){
                    return $model->click;
                },
                'filter' => false
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return date('d F Y', $model->created_at);
                },
                'filter' => false
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
