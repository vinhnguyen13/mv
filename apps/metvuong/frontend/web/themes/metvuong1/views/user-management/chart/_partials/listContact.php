<h3>Người theo dõi</h3>
<p style="color: #4a933a;">
    Có <span class="total" style="font-weight: bold"></span> người theo dõi tin <span class="news" style="font-weight: bold"></span>.
</p>

<?php
$yourArray = [
    0 => [
        'title' => 'Nguyễn Quang Vinh',
        'phone' => '0909030605',
        'time' => date('H:i:s d-m-Y', strtotime('-1days')),
    ],
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
    'sort' => [
        'attributes' => ['title','net_accumulative_cashflow'],
    ],
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
<p>Và 500 người nữa đang theo dõi tin <span class="news" style="font-weight: bold"></span> của bạn. Bạn vui lòng <a href="javascript:alert('Coming soon !');">nạp thêm tiền</a> để có thể xem thêm</p>
