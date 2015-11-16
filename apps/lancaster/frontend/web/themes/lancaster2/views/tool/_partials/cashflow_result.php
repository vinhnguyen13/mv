<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 10/29/2015
 * Time: 3:29 PM
 */
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\Pjax;

$filePath = Yii::$app->view->theme->basePath . '/resources/chart/data.json';
$handle = fopen($filePath, 'r') or die('Cannot open file:  '.$filePath);
$data = [];
if(filesize($filePath) > 0) {
    $fileContent = fread($handle, filesize($filePath));
    $data = Json::decode($fileContent, true);
}
$arr_data = [];
$scenario_1 = $data["scenario_1"];
foreach($scenario_1 as $key => $value) {
    $arr_data[$key] = [
        'title' => $key,
//        'net_cashflow' => $value * 10 / 100,
        'net_accumulative_cashflow' => number_format($value[0], 0 , "." , "," ),
        'ls_vay' => '10%',
    ];
}
//$yourArray = [
//    0 => [
//        'title' => 'T1',
//        'net_cashflow' => '-272,315,008,848',
//        'net_accumulative_cashflow' => '-270,064,471,585',
//        'ls_vay' => '10%',
//    ],
//    1 => [
//        'title' => 'T2',
//        'net_cashflow' => '-272,315,008,848',
//        'net_accumulative_cashflow' => '-270,064,471,585',
//        'ls_vay' => '10%',
//    ],
//    2 => [
//        'title' => 'T3',
//        'net_cashflow' => '-272,315,008,848',
//        'net_accumulative_cashflow' => '-270,064,471,585',
//        'ls_vay' => '10%',
//    ],
//    3 => [
//        'title' => 'T4',
//        'net_cashflow' => '-272,315,008,848',
//        'net_accumulative_cashflow' => '-270,064,471,585',
//        'ls_vay' => '10%',
//    ],
//
//];

$provider = new \yii\data\ArrayDataProvider([
    'allModels' => $arr_data,
    'sort' => [
        'attributes' => ['id', 'username', 'email'],
    ],
    'pagination' => [
        'pageSize' => 10,
    ],
]);
?>

<?php Pjax::begin(['id' => 'scenario1']) ?>
<?=\yii\grid\GridView::widget([
    'dataProvider' => $provider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'title',
//        'net_cashflow',
        'net_accumulative_cashflow',
        'ls_vay',
    ],
]);?>
<?php Pjax::end() ?>
