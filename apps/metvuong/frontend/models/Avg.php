<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 7/27/2016
 * Time: 10:18 AM
 */

namespace frontend\models;
use common\components\Util;
use Yii;
use yii\base\Component;
use yii\db\Query;

class Avg extends Component
{
    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function calculation_boxplot($arr){
        sort($arr);
        $n = count($arr);

        $idx_M = ($n+1)/2;
        if(!is_float($idx_M)){
            $M = $arr[$idx_M - 1];
        }else{
            $_index = intval($idx_M) - 1;
            $M = ($arr[$_index] + $arr[($_index+1)])/2;
        }
        $idx_Q1 = ($n+1)/4;
        if(!is_float($idx_Q1)){
            $Q1 = $arr[$idx_Q1 - 1];
        }else{
            $_index = intval($idx_Q1) - 1;
            $_percent = $idx_Q1 - intval($idx_Q1);
            $Q1 = $arr[$_index] + (($arr[$_index+1] - $arr[$_index]) * $_percent);
        }
        $idx_Q3 = (3*($n+1))/4;
        if(!is_float($idx_Q3)){
            $Q3 = $arr[$idx_Q3 - 1];
        }else{
            $_index = intval($idx_Q3) - 1;
            $_percent = $idx_Q3 - intval($idx_Q3);
            $Q3 = $arr[$_index] + (($arr[$_index+1] - $arr[$_index]) * $_percent);
        }
        $INTERQUARTILE_RANGE = $Q3-$Q1;
        $average_of_foo = array_sum($arr) / $n;
        /*echo "<pre>";
        print_r(PHP_EOL);
        print_r("List: " . implode(',', $arr));
        print_r(PHP_EOL);
        print_r(PHP_EOL);
        print_r("n: " . $n);
        print_r(PHP_EOL);
        print_r("(n+1)/2: " . ($n + 1) / 2);
        print_r(PHP_EOL);
        print_r("(n+1)/4: " . ($n + 1) / 4);
        print_r(PHP_EOL);
        print_r("3*(n+1)/2: " . (3 * ($n + 1)) / 4);
        print_r(PHP_EOL);
        print_r(PHP_EOL);
        print_r("Total: ".number_format(array_sum($arr)));
        print_r(PHP_EOL);
        print_r("1. Avg: ".number_format($average_of_foo));

        print_r(PHP_EOL);
        print_r("2. Boxplot");
        print_r(PHP_EOL);
        print_r("2.b Median M=x((n+1)/2): " . ($M));
        print_r(PHP_EOL);
        print_r("2.d FIRST QUARTILE & THIRD QUARTILE");
        print_r(PHP_EOL);
        print_r("Index Q1: $idx_Q1" . ", Q1: " . ($Q1));
        print_r(PHP_EOL);
        print_r("Index Q1: $idx_Q3" . ", Q3: " . ($Q3));
        print_r(PHP_EOL);
        print_r("Q3 - Q1 = " . ($INTERQUARTILE_RANGE));
        echo "</pre>";*/

        return [
            'low'=>!empty($arr[0]) ? intval($arr[0]) : 0,
            'q1'=>($Q1),
            'median'=>($M),
            'q3'=>($Q3),
            'high'=>!empty($arr[$n-1]) ? intval($arr[$n-1]) : 0,
            'IQR'=>$INTERQUARTILE_RANGE
        ];
    }

}