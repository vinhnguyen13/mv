<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 10/29/2015
 * Time: 3:29 PM
 */
use yii\helpers\Html;

$yourArray = [
    [
        'title' => 'T1',
        'net_cashflow' => '-272,315,008,848',
        'net_accumulative_cashflow' => '-270,064,471,585',
        'ls_vay' => '10%',
    ],[
        'title' => 'T2',
        'net_cashflow' => '-272,315,008,848',
        'net_accumulative_cashflow' => '-270,064,471,585',
        'ls_vay' => '10%',
    ],[
        'title' => 'T3',
        'net_cashflow' => '-272,315,008,848',
        'net_accumulative_cashflow' => '-270,064,471,585',
        'ls_vay' => '10%',
    ],[
        'title' => 'T4',
        'net_cashflow' => '-272,315,008,848',
        'net_accumulative_cashflow' => '-270,064,471,585',
        'ls_vay' => '10%',
    ],

];
$provider = new \yii\data\ArrayDataProvider([
    'allModels' => $yourArray,
    'sort' => [
        'attributes' => ['id', 'username', 'email'],
    ],
    'pagination' => [
        'pageSize' => 10,
    ],
]);

echo \yii\grid\GridView::widget([
    'dataProvider' => $provider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'title',
        'net_cashflow',
        'net_accumulative_cashflow',
        'ls_vay',
    ],
]);
?>
