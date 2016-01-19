<?php
use frontend\models\Chart;
?>
<p style="color: #4a933a;" class="desTotal"></p>
<?php
$data = Chart::find()->getContacts();
if(!empty($data)) {
    $provider = new \yii\data\ArrayDataProvider([
        'allModels' => $data,
        /*'sort' => [
            'attributes' => ['title'],
        ],*/
        'pagination' => [
            'pageSize' => 15,
        ],
    ]);
    echo \yii\grid\GridView::widget([
        'dataProvider' => $provider,
        'summary' => "",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'phone',
            'time',
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
