<?php
namespace console\models;
use common\models\AdProductSaved;
use frontend\models\Tracking;
use vsoft\tracking\models\base\ChartStats;
use yii\base\Component;
use Yii;

/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 8/10/2016
 * Time: 5:12 PM
 */
class Product extends Component
{
    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function updateStats($step)
    {
        if($step == 'delete-stats'){
            $chart_stats = ChartStats::find()->where(['>','favorite', 0])->all();
            if(!empty($chart_stats)){
                foreach($chart_stats as $chart_stat){
                    $chart_stat->delete();
                    print_r("Total: ".$chart_stat->product_id.PHP_EOL);
                }
            }
        }
        if($step == 'update-stats'){
            $adProSaveds = AdProductSaved::find()->orderBy(['saved_at' => SORT_ASC])->groupBy('product_id')->asArray()->all();
            if(!empty($adProSaveds)){
                print_r("Total: ".count($adProSaveds).PHP_EOL);
                foreach($adProSaveds as $adProSaved){
                    print_r("update stats for: ".$adProSaved['product_id'].PHP_EOL);
                    echo Tracking::syncFavorite($adProSaved['product_id']);
                    echo PHP_EOL;
                }
            }
        }
        if($step == 'update-status-field'){
            $adProSaveds = AdProductSaved::find()->all();
            if(!empty($adProSaveds)){
                print_r("Total: ".count($adProSaveds).PHP_EOL);
                foreach($adProSaveds as $adProSaved){
                    if($adProSaved->saved_at > 0){
                        $adProSaved->status = 1;
                        $adProSaved->save();
                    }
                }
            }
        }
    }

}