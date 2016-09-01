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

    public function calculation_boxplot($arr, $test = false){
        sort($arr);
        $n = count($arr);

        $idx_M = ($n+1)/2;
        if(!is_float($idx_M)){
            $M = $arr[$idx_M - 1];
        }else{
            $_index = intval($idx_M) - 1;
            $M = !empty($arr[$_index]) ?($arr[$_index] + $arr[($_index+1)])/2 : 0;
        }
        $idx_Q1 = ($n+1)/4;
        if(!is_float($idx_Q1)){
            $Q1 = $arr[$idx_Q1 - 1];
        }else{
            $_index = intval($idx_Q1) - 1;
            $_percent = $idx_Q1 - intval($idx_Q1);
            $Q1 = (!empty($arr[$_index]) && !empty($arr[$_index+1])) ? $arr[$_index] + (($arr[$_index+1] - $arr[$_index]) * $_percent) : 0;
        }
        $idx_Q3 = (3*($n+1))/4;
        if(!is_float($idx_Q3)){
            $Q3 = $arr[$idx_Q3 - 1];
        }else{
            $_index = intval($idx_Q3) - 1;
            $_percent = $idx_Q3 - intval($idx_Q3);
            $Q3 = (!empty($arr[$_index]) && !empty($arr[$_index+1])) ? $arr[$_index] + (($arr[$_index+1] - $arr[$_index]) * $_percent) : 0;
        }
        $INTERQUARTILE_RANGE = $Q3-$Q1;

        if($test == true) {
            echo "<pre>";
            print_r(PHP_EOL);
            print_r("List: " . implode(',', $arr));
            print_r(PHP_EOL);
            print_r("n: " . $n);
            print_r(PHP_EOL);
            print_r("Total: ".number_format(array_sum($arr)));
            print_r(PHP_EOL);
            print_r("Avg: ".array_sum($arr) / $n);
            print_r(PHP_EOL);
            print_r(PHP_EOL);
            print_r("----Boxplot---");
            print_r(PHP_EOL);
            print_r("Index Median: $idx_M  --> M: " . ($M));
            print_r(PHP_EOL);
            print_r("Index Q1: $idx_Q1" . " --> Q1: " . ($Q1));
            print_r(PHP_EOL);
            print_r("Index Q3: $idx_Q3" . " --> Q3: " . ($Q3));
            print_r(PHP_EOL);
            print_r("IQR = Q3 - Q1 = " . ($INTERQUARTILE_RANGE));
            echo "</pre>";
        }

        return [
            'low'=>!empty($arr[0]) ? intval($arr[0]) : 0,
            'q1'=>($Q1),
            'median'=>($M),
            'q3'=>($Q3),
            'high'=>!empty($arr[$n-1]) ? intval($arr[$n-1]) : 0,
            'IQR'=>$INTERQUARTILE_RANGE
        ];
    }

	function calQuartile($arr) {
		if(empty($arr)) {
			return null;
		}
		
		sort($arr);
		
		$q1 = $this->_calQuartile($arr, .25);
		$q2 = $this->_calQuartile($arr, .5);
		$q3 = $this->_calQuartile($arr, .75);
		
		return ['low' => $arr[0], 'q1'=> $q1, 'median' => $q2, 'q3' => $q3, 'high' => $arr[count($arr) - 1], 'IQR' => ($q3 - $q1)];
	}
	
	function _calQuartile($arr, $per) {
		$index = $per * (count($arr) - 1);
		$n = $index - floor($index);
		
		$mid = ($arr[ceil($index)] + $arr[floor($index)]) / 2;
		
		if($n == .25) {
			$q = ($mid + $arr[floor($index)]) / 2;
		} else if($n == .5) {
			$q = $mid;
		} else {
			$q = ($mid + $arr[ceil($index)]) / 2;
		}
		
		return $q;
	}
}