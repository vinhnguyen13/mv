<?php
namespace common\components;

use Yii;
use yii\base\Component;

/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 1/11/2016
 * Time: 5:56 PM
 */
class Util extends Component
{
    /**
     * @return mixed
     */
    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function search($array, $key, $value)
    {
        $results = array();
        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }
            foreach ($array as $subarray) {
                $results = array_merge($results, $this->search($subarray, $key, $value));
            }
        }
        return $results;
    }

    public function dateRange($first, $last, $step = '+1 day', $output_format = 'd/m/Y')
    {
        $dates = array();
        $current = is_numeric($first) ? $first : strtotime($first);
        $last = is_numeric($last) ? $last : strtotime($last);
        while ($current <= $last) {
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }


}