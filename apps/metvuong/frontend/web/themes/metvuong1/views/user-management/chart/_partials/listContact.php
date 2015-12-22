<h3></h3>
<p style="color: #4a933a;" class="desTotal"></p>
<?php
$yourArray = [
    1 => [
        'title' => 'Nguyễn Trung Ngạn',
        'phone' => '0909030605',
        'time' => date('H:i:s d-m-Y', strtotime('-2days')),
    ],
    2 => [
        'title' => 'Quách Tuấn Lệnh',
        'phone' => '0909030605',
        'time' => date('H:i:s d-m-Y', strtotime('-3days')),
    ],
    3 => [
        'title' => 'Quách Tuấn Du',
        'phone' => '0909030605',
        'time' => date('H:i:s d-m-Y', strtotime('-5days')),
    ],

];
$provider = new \yii\data\ArrayDataProvider([
    'allModels' => $yourArray,
    /*'sort' => [
        'attributes' => ['title'],
    ],*/
    'pagination' => [
        'pageSize' => 15,
    ],
]);
echo \yii\grid\GridView::widget([
    'dataProvider' => $provider,
    'summary'=>"",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'title',
        'phone',
        'time',
    ],
]);?>
<p style="color: #4a933a;">Và <span class="totalNext" style="font-weight: bold"></span> người khác nữa. Bạn vui lòng <a href="javascript:alert('Coming soon !');">nạp thêm tiền</a> để có thể xem thêm</p>
