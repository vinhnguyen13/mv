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
use yii\helpers\ArrayHelper;

class Report extends Component
{
    const TYPE_REGISTER     = 1;
    const TYPE_LOGIN        = 2;
    const TYPE_LISTING      = 3;
    const TYPE_TRANSACTION  = 4;
    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function chart($filter){
        $arrFilter = [
            'week' => -6,
            '2week' => -13,
            'month' => -30,
            'quarter' => -90,
        ];
        $from = strtotime($arrFilter[$filter].' days');
        $to = time();

        $dateRange = Util::me()->dateRange( $from, $to,'+1 day', 'd/m/Y');
        if(!empty($dateRange)){
            $dataRegister = array_map(function($v){
                return ['y'=>0, 'date'=>$v, 'type'=>self::TYPE_REGISTER];
            },$dateRange);
            $dataLogin = array_map(function($v){
                return ['y'=>0, 'date'=>$v, 'type'=>self::TYPE_LOGIN];
            },$dateRange);
            $dataListing = array_map(function($v){
                return ['y'=>0, 'date'=>$v, 'type'=>self::TYPE_LISTING];
            },$dateRange);
            /**
             * user register
             */
            $month_year = new \yii\db\Expression("DATE_FORMAT(FROM_UNIXTIME(`created_at`), '%d/%m/%Y')");
            $query = new Query();
            $query->select(['count(*) total', $month_year." today"])->from('user')
                ->where(['>', 'created_at', $from])
                ->andWhere(['<', 'created_at', $to])
                ->andWhere('updated_at > created_at')
                ->groupBy('today')->orderBy('created_at DESC');
            $stats_register = $query->all();
            $totalRegister = 0;
            foreach($stats_register as $item){
                $totalRegister += $item['total'];
                $kDate = array_search($item['today'], $dateRange);
                $dataRegister[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today'], 'type'=>self::TYPE_REGISTER];
            }
            /**
             * user login
             */
            $month_year = new \yii\db\Expression("DATE_FORMAT(FROM_UNIXTIME(`updated_at`), '%d/%m/%Y')");
            $query = new Query();
            $query->select(['count(*) total', $month_year." today"])->from('user')
                ->where(['>', 'updated_at', $from])
                ->andWhere(['<', 'updated_at', $to])
                ->andWhere('updated_at > created_at')
                ->groupBy('today')->orderBy('updated_at DESC');
            $stats_login = $query->all();

            $query = \frontend\models\UserActivity::find();
            $query->select(['action', 'updated']);
            $query->andWhere(['IN', 'action', [\frontend\models\UserActivity::ACTION_USER_LOGIN]])
                ->andFilterWhere(['BETWEEN', 'updated', $from, $to])
                /*->groupBy('{{user_activity}}.id')*/->orderBy('updated DESC');
            $login_results = $query->asArray()->all();
            if(!empty($login_results)){
                $stats_login2 = ArrayHelper::merge($stats_login, $login_results);
                array_filter($login_results, function($element, $key) use (&$stats_login3) {
                    $today = !empty($element['today']) ? $element['today'] : date('d/m/Y', $element['updated']);
                    $_key = strtotime(str_replace('/', '-', $today));
                    if(!empty($stats_login3[$_key])){
                        $stats_login3[$_key]['total'] ++;
                    }else{
                        $stats_login3[$_key]['total'] = 1;
                        $stats_login3[$_key]['today'] = $today;
                    }
                    return $element;
                }, ARRAY_FILTER_USE_BOTH);
                ksort($stats_login3);
            }

//            $stats_login2 = array_values($stats_login2);
//            $stats_login = ArrayHelper::merge($stats_login, $stats_login2);

            $totalLogin = 0;
            foreach($stats_login3 as $item){
                $totalLogin += $item['total'];
                $kDate = array_search($item['today'], $dateRange);
                $dataLogin[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today'], 'type'=>self::TYPE_LOGIN];
            }

            /**
             * listing
             */
            $month_year = new \yii\db\Expression("DATE_FORMAT(FROM_UNIXTIME(`created_at`), '%d/%m/%Y')");
            $query = new Query();
            $query->select(['count(*) total', $month_year." today"])->from('ad_product')
                ->where(['>', 'created_at', $from])
                ->andWhere(['<', 'created_at', $to])
                ->groupBy('today')->orderBy('created_at DESC');
            $stats_listing = $query->all();
            $totalListing = 0;
            foreach($stats_listing as $item){
                $totalListing += $item['total'];
                $kDate = array_search($item['today'], $dateRange);
                $dataListing[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today'], 'type'=>self::TYPE_LISTING];
            }
            /**
             * load data to chart
             */
            $categories = $dateRange;
            $dataChart[0] = [
                'name' => 'Register',
                'data' => $dataRegister
            ];
            $dataChart[1] = [
                'name' => 'Login',
                'data' => $dataLogin
            ];
            $dataChart[2] = [
                'name' => 'Listing',
                'data' => $dataListing
            ];
            return ['categories'=>$categories, 'dataChart'=>$dataChart];
        }
        return false;
    }

    public function chartDetail($type, $date){
        $from = strtotime(str_replace('/', '-', $date).' 00:00:00');
        $to = strtotime(str_replace('/', '-', $date).' 23:59:59');

        switch($type){
            case Report::TYPE_REGISTER;
                $query = new Query();
                $query->select(['id', 'username'])->from('user')
                    ->where(['>', 'created_at', $from])
                    ->andWhere(['<', 'created_at', $to])
                    ->andWhere('updated_at > created_at')
                    ->orderBy('created_at DESC');
                $data = $query->all();
                return $data;
                break;
            case Report::TYPE_LOGIN;
                $query = \frontend\models\UserActivity::find();
                $query->select(['owner_id', 'owner_username']);
                $query->andWhere(['IN', 'action', [\frontend\models\UserActivity::ACTION_USER_LOGIN]])
                    ->andFilterWhere(['BETWEEN', 'updated', $from, $to])
                    ->orderBy('updated DESC');
                $stats_login2 = $query->asArray()->all();
                if(!empty($stats_login2)){
                    array_filter($stats_login2, function($element, $key) use (&$newUserLogin) {
                        $newUserLogin[$key]['id'] =  $element['owner_id'];
                        $newUserLogin[$key]['username'] =  $element['owner_username'];
                        return $element;
                    }, ARRAY_FILTER_USE_BOTH);
                    return $newUserLogin;
                }else{
                    $query = new Query();
                    $query->select(['id', 'username'])->from('user')
                        ->where(['>', 'updated_at', $from])
                        ->andWhere(['<', 'updated_at', $to])
                        ->andWhere('updated_at > created_at')
                        ->orderBy('updated_at DESC');
                    $stats_login = $query->all();
                    return $stats_login;
                }
                break;
            case Report::TYPE_LISTING;
                $query = new Query();
                $query->select(['id'])->from('ad_product')
                    ->where(['>', 'created_at', $from])
                    ->andWhere(['<', 'created_at', $to])
                    ->orderBy('created_at DESC');
                $stats_listing = $query->all();
                return $stats_listing;
                break;

        }
    }

}