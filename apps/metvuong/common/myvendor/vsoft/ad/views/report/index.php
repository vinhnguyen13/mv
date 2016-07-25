<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\ad\models\AdProductReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Reports';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="ad-product-report-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' => function ($model) {
                    $user = \frontend\models\User::findOne($model->user_id);
                    return Html::a($user->username, Yii::$app->urlManager->hostInfo."/".$user->username);
                },
            ],
            [
                'attribute' => 'product_id',
                'format' => 'html',
                'value' => function ($model) {
                    $ad_product = \vsoft\ad\models\AdProduct::findOne($model->product_id);
                    $link = Url::to(['/ad/detail' . $ad_product->type, 'id' => $ad_product->id, 'slug' => \common\components\Slug::me()->slugify($ad_product->getAddress($ad_product->show_home_no))]);
                    $link = str_replace("/admin/", "/", $link);
                    $text = $ad_product->getAddress($ad_product->show_home_no);
                    $count = \vsoft\ad\models\AdProductReport::find()->where(['product_id' => $model->product_id])->count();
                    $str_return = Html::a($text, $link);
                    if($count > 1)
                        $str_return = $str_return." ".Html::a("({$count})", "#");
                    return $str_return;
                },
            ],
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    if($model->type == -1)
                        return $model->description;
                    return \vsoft\ad\models\ReportType::getReportName($model->type);
                },
            ],
//            'type',
//            'description',
            'ip',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return \vsoft\news\models\Status::labels($model->status);
                },
                'options' => ['width' => '100']
            ],
            [
                'attribute' => 'report_at',
                'value' => function ($model) {
                    return $model->report_at;
                },
                'format' => ['datetime', 'php: d/m/Y H:i a']
            ],

        ],
    ]); ?>

</div>
