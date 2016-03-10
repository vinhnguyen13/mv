<?php
use frontend\models\Chart;
?>
<p style="color: #4a933a;" class="desTotal"></p>
<?php \yii\widgets\Pjax::begin(['timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container'], 'id'=>'pjax-job-gridview']); ?>
<?php
$data = Chart::find()->getContacts();
if(!empty($data)) {

    echo \yii\grid\GridView::widget([
        'id'=>'job-gridview',
        'dataProvider' => Chart::find()->getContacts(),
        'summary' => "",
        'columns' => [
            [
                'label' => 'Owner',
                'attribute' => 'user_id',
//                'type' => 'html',
                'value' => function($model) {
                    $user = \frontend\models\User::getDb()->cache(function ($db) use ($model) {
                        return \frontend\models\User::find()->where(['id' => $model->user_id])->one();
                    });
                    return $user->profile->displayName;
                }
            ],
            [
                'label' => 'Phone',
                'value' => function($model) {
                    $user = \frontend\models\User::getDb()->cache(function ($db) use ($model) {
                        return \frontend\models\User::find()->where(['id' => $model->user_id])->one();
                    });
                    return '0909xxxxxx';
                }
            ],
            [
                'label' => 'Time',
                'attribute' => 'time',
                'value' => function($model) {
                    return date('H:i:s d-m-Y', $model->time);
                }
            ],

        ],
    ]);
}else {
    ?>
    <div class="alert alert-warning">
        <p class="text-center">Không có người nào trong dữ liệu</p>
    </div>
    <?php
}
?>
<?php \yii\widgets\Pjax::end(); ?>