<?php

use funson86\cms\Module;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;


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
                }
            ],
            [
                'attribute' => 'hot_project',
                'format' => 'raw',
                'contentOptions'=>['style'=>'text-align:center'],
                'value' => function ($model, $index, $widget) {
                    return Html::checkbox('hot_news', $model->hot_project, ['value' => $index, 'disabled' => false]);
                },
                'filter' => Html::activeDropDownList($searchModel, 'hot_project', \vsoft\news\models\CmsShow::getHotNews(),['class'=>'form-control','prompt' => 'All']),
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
<script src="/frontend/web/js/lib/jquery-2.0.3.min.js"></script>
<script>
    $(document).ready(function () {
        $('input[type=checkbox]').each(function() {
            $(this).change(function(){
                var _id = $(this).val();
                var question = "Do you want to remove hot project?";
                var ch = 0;
                if($(this).is(":checked")) {
                    ch = 1;
                    question = "Do you want to set hot project?";
                }
                var retVal = confirm(question);
                if( retVal == true ){
                    $.ajax({
                        type: "get",
                        dataType: 'json',
                        url: '<?=Url::to(['building-project/update-hot-project'])?>'+'?_id='+ _id +'&stage='+ch,
                        success: function (data) {
                        }
                    });
                    return true;
                }
                else{
                    if(ch == 1)
                        $(this).prop('checked', false);
                    else
                        $(this).prop('checked', true);
                    return false;
                }
            });
        });
    });
</script>