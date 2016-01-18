<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 10:14 AM
 */

namespace frontend\models;
use common\components\Util;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdProductSaved;
use vsoft\tracking\models\AdProductFinder;
use vsoft\tracking\models\AdProductVisitor;
use Yii;
use yii\base\Component;
use yii\helpers\Url;

class Chart extends Component
{
    const DATE_FORMAT = 'd-m-Y';
    public static function find()
    {
        return Yii::createObject(Chart::className());
    }

    public function getDataFinder(){
        $from = strtotime('-30 days');
        $to = strtotime('+1 days');
        $pids = [];

        $query = AdProductFinder::find();
        $query->andFilterWhere(['between', 'time', $from, $to]);
        if(!empty($pids)){
            $query->andWhere(['product_id' => $pids]);
        }
        $adProductVisitors = $query->all();
        $dateRange = Util::me()->dateRange($from, $to, '+1 day', self::DATE_FORMAT);
        $defaultData = array_map(function ($key, $date) {
            return ['y' => 0,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact', 'date'=>$date])];
        }, array_keys($dateRange), $dateRange);
        if(!empty($adProductVisitors)){
            return $this->pushDataToChart($adProductVisitors, $defaultData, $dateRange);
        }
        return false;
    }


    public function getDataVisitor(){
        $from = strtotime('-30 days');
        $to = strtotime('+1 days');
        $pids = [];

        $query = AdProductVisitor::find();
        $query->andFilterWhere(['between', 'time', $from, $to]);
//        $query->andWhere(['>', 'time', $from]);
//        $query->andWhere(['<', 'time', $to]);
        if(!empty($pids)){
            $query->andWhere(['product_id' => $pids]);
        }
        $adProductVisitors = $query->all();
        $dateRange = Util::me()->dateRange($from, $to, '+1 day', self::DATE_FORMAT);
        $defaultData = array_map(function ($key, $date) {
            return ['y' => 0,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact', 'date'=>$date])];
        }, array_keys($dateRange), $dateRange);
        if(!empty($adProductVisitors)){
            return $this->pushDataToChart($adProductVisitors, $defaultData, $dateRange);
        }
        return false;
    }

    public function getDataSaved(){
        $from = strtotime('-30 days');
        $to = strtotime('+1 days');
        $pids = [];

        $query = AdProductSaved::find();
        $query->select(['product_id', 'saved_at as time']);
        $query->andFilterWhere(['between', 'saved_at', $from, $to]);
        if(!empty($pids)){
            $query->andWhere(['product_id' => $pids]);
        }
        $adProductSaveds = $query->all();
        $dateRange = Util::me()->dateRange($from, $to, '+1 day', self::DATE_FORMAT);
        $defaultData = array_map(function ($key, $date) {
            return ['y' => 0,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact', 'date'=>$date])];
        }, array_keys($dateRange), $dateRange);
        if(!empty($adProductSaveds)){
            return $this->pushDataToChart($adProductSaveds, $defaultData, $dateRange);
        }
        return false;
    }

    private function pushDataToChart($adProductSaveds, $defaultData, $dateRange){
        if(!empty($adProductSaveds)){
            $tmpDataByPid = [];
            foreach($adProductSaveds as $k => $item){
                $day = date(self::DATE_FORMAT, $item->time);
                $product = AdProduct::getDb()->cache(function ($db) use ($item) {
                    return AdProduct::find()->where(['id' => $item->product_id])->one();
                });
                $key = $item->product_id;
                if(empty($tmpDataByPid[$key]['data'])){
                    $tmpDataByPid[$key]['data'] = $defaultData;
                }
                $kDate = array_search($day, $dateRange);
                $tmpDataByPid[$key]['data'][$kDate]['y']++;
                $tmpDataByPid[$key]['name'] = $product->getAddress();
            }
            return ['dataChart'=>$tmpDataByPid, 'categories'=>$dateRange];
        }
    }
}