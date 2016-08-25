<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 7/27/2016
 * Time: 10:18 AM
 */

namespace frontend\models;
use common\components\Util;
use vsoft\tracking\models\base\AdProductShare;
use vsoft\tracking\models\base\CompareStats;
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
    const TYPE_FAVORITE     = 5;
    const TYPE_SHARE        = 6;
    const TYPE_COMPARE      = 7;
    const TYPE_DASHBOARD    = 8;
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
            $dataTransaction = array_map(function($v){
                return ['y'=>0, 'date'=>$v, 'type'=>self::TYPE_TRANSACTION];
            },$dateRange);
            $dataFavorite = array_map(function($v){
                return ['y'=>0, 'date'=>$v, 'type'=>self::TYPE_FAVORITE];
            },$dateRange);
            $dataShare = array_map(function($v){
                return ['y'=>0, 'date'=>$v, 'type'=>self::TYPE_SHARE];
            },$dateRange);
            $dataCompare = array_map(function($v){
                return ['y'=>0, 'date'=>$v, 'type'=>self::TYPE_COMPARE];
            },$dateRange);
            $dataDashboard = array_map(function($v){
                return ['y'=>0, 'date'=>$v, 'type'=>self::TYPE_DASHBOARD];
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
            foreach($stats_register as $item){
                $kDate = array_search($item['today'], $dateRange);
                $dataRegister[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today'], 'type'=>self::TYPE_REGISTER];
            }
            /**
             * user login
             */
            $query = \frontend\models\UserActivity::find();
            $query->select(['action', 'updated']);
            $query->andWhere(['IN', 'action', [\frontend\models\UserActivity::ACTION_USER_LOGIN]])
                ->andFilterWhere(['BETWEEN', 'updated', $from, $to])
                /*->groupBy('{{user_activity}}.id')*/->orderBy('updated DESC');
            $login_results = $query->asArray()->all();
            if(!empty($login_results)){
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
            foreach($stats_login3 as $item){
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
                ->andWhere('ip IS NOT NULL')
                ->groupBy('today')->orderBy('created_at DESC');
            $stats_listing = $query->all();
            foreach($stats_listing as $item){
                $kDate = array_search($item['today'], $dateRange);
                $dataListing[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today'], 'type'=>self::TYPE_LISTING];
            }

            /**
             * Transaction
             */
            $month_year = new \yii\db\Expression("DATE_FORMAT(FROM_UNIXTIME(`created_at`), '%d/%m/%Y')");
            $query = new Query();
            $query->select(['count(*) total', $month_year." today"])->from('ec_transaction_history')
                ->where(['>', 'created_at', $from])
                ->andWhere(['<', 'created_at', $to])
                ->groupBy('today')->orderBy('created_at DESC');
            $stats_transaction = $query->all();
            foreach($stats_transaction as $item){
                $kDate = array_search($item['today'], $dateRange);
                $dataTransaction[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today'], 'type'=>self::TYPE_TRANSACTION];
            }

            /**
             * Favorite
             */
            $month_year = new \yii\db\Expression("DATE_FORMAT(FROM_UNIXTIME(`saved_at`), '%d/%m/%Y')");
            $query = new Query();
            $query->select(['count(*) total', $month_year." today"])->from('ad_product_saved')
                ->where(['>', 'saved_at', $from])
                ->andWhere(['<', 'saved_at', $to])
                ->groupBy('today')->orderBy('saved_at DESC');
            $stats_favorite = $query->all();
            foreach($stats_favorite as $item){
                $kDate = array_search($item['today'], $dateRange);
                $dataFavorite[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today'], 'type'=>self::TYPE_FAVORITE];
            }
            /**
             * Share
             */
            $query = AdProductShare::find();
            $query->select(['type', 'time']);
            $query->andFilterWhere(['BETWEEN', 'time', $from, $to])->orderBy('time DESC');
            $share_results = $query->asArray()->all();
            if(!empty($share_results)){
                array_filter($share_results, function($element, $key) use (&$stats_share) {
                    $today = !empty($element['today']) ? $element['today'] : date('d/m/Y', $element['time']);
                    $_key = strtotime(str_replace('/', '-', $today));
                    if(!empty($stats_share[$_key])){
                        $stats_share[$_key]['total'] ++;
                    }else{
                        $stats_share[$_key]['total'] = 1;
                        $stats_share[$_key]['today'] = $today;
                    }
                    return $element;
                }, ARRAY_FILTER_USE_BOTH);
                ksort($stats_share);
                foreach($stats_share as $item){
                    $kDate = array_search($item['today'], $dateRange);
                    $dataShare[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today'], 'type'=>self::TYPE_SHARE];
                }

            }
            /**
             * Share
             */
            $query = CompareStats::find();
            $query->select(['type', 'time']);
            $query->andFilterWhere(['BETWEEN', 'time', $from, $to])->orderBy('time DESC');
            $compare_results = $query->asArray()->all();
            if(!empty($compare_results)){
                array_filter($compare_results, function($element, $key) use (&$stats_compare) {
                    $today = !empty($element['today']) ? $element['today'] : date('d/m/Y', $element['time']);
                    $_key = strtotime(str_replace('/', '-', $today));
                    if(!empty($stats_compare[$_key])){
                        $stats_compare[$_key]['total'] ++;
                    }else{
                        $stats_compare[$_key]['total'] = 1;
                        $stats_compare[$_key]['today'] = $today;
                    }
                    return $element;
                }, ARRAY_FILTER_USE_BOTH);
                ksort($stats_compare);
                foreach($stats_compare as $item){
                    $kDate = array_search($item['today'], $dateRange);
                    $dataCompare[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today'], 'type'=>self::TYPE_COMPARE];
                }

            }
            /**
             * Dashboard
             */
            $month_year = new \yii\db\Expression("DATE_FORMAT(FROM_UNIXTIME(`start_at`), '%d/%m/%Y')");
            $query = new Query();
            $query->select(['count(*) total', $month_year." today"])->from('ec_statistic_view')
                ->where(['>', 'start_at', $from])
                ->andWhere(['<', 'start_at', $to])
                ->groupBy('today')->orderBy('start_at DESC');
            $stats_dashboard = $query->all();
            foreach($stats_dashboard as $item){
                $kDate = array_search($item['today'], $dateRange);
                $dataDashboard[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today'], 'type'=>self::TYPE_DASHBOARD];
            }
            /**
             * load data to chart
             */
            $categories = $dateRange;
            $dataChart[0] = [
                'name' => 'Register',
                'color' => '#00a769',
                'data' => $dataRegister
            ];
            $dataChart[1] = [
                'name' => 'Login',
                'color' => '#337ab7',
                'data' => $dataLogin
            ];
            $dataChart[2] = [
                'name' => 'Listing',
                'color' => '#a94442',
                'data' => $dataListing
            ];
            $dataChart[3] = [
                'name' => 'Transaction',
                'color' => '#8a6d3b',
                'data' => $dataTransaction
            ];
            $dataChart[4] = [
                'name' => 'Favorite',
                'color' => '#809000',
                'data' => $dataFavorite
            ];
            $dataChart[5] = [
                'name' => 'Share',
                'color' => '#840072',
                'data' => $dataShare
            ];
            $dataChart[6] = [
                'name' => 'Compare',
                'color' => '#006956',
                'data' => $dataCompare
            ];
            $dataChart[7] = [
                'name' => 'Dashboard',
                'color' => '#00bcd4',
                'data' => $dataDashboard
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
                return $query->all();
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
                    return $query->all();
                }
                break;
            case Report::TYPE_LISTING;
                $query = new Query();
                $query->select(['id'])->from('ad_product')
                    ->where(['>', 'created_at', $from])
                    ->andWhere(['<', 'created_at', $to])
                    ->andWhere('ip IS NOT NULL')
                    ->orderBy('created_at DESC');
                return $query->all();
                break;
            case Report::TYPE_TRANSACTION;
                $query = new Query();
                $query->select(['id'])->from('ec_transaction_history')
                    ->where(['>', 'created_at', $from])
                    ->andWhere(['<', 'created_at', $to])
                    ->orderBy('created_at DESC');
                return $query->all();
                break;
            case Report::TYPE_FAVORITE;
                $query = new Query();
                $query->select(['user_id', 'product_id'])->from('ad_product_saved')
                    ->where(['>', 'saved_at', $from])
                    ->andWhere(['<', 'saved_at', $to])
                    ->orderBy('saved_at DESC');
                return $query->all();
                break;
            case Report::TYPE_SHARE;
                $query = AdProductShare::find();
                $query->select(['user_id', 'product_id', 'type']);
                $query->andFilterWhere(['BETWEEN', 'time', $from, $to])
                    /*->groupBy('{{user_activity}}.id')*/->orderBy('time DESC');
                $share_results = $query->asArray()->all();
                if(!empty($share_results)){
                    return $share_results;
                }
                break;
            case Report::TYPE_COMPARE;
                $query = CompareStats::find();
                $query->select(['alias']);
                $query->andFilterWhere(['BETWEEN', 'time', $from, $to])
                    /*->groupBy('{{user_activity}}.id')*/->orderBy('time DESC');
                $share_results = $query->asArray()->all();
                if(!empty($share_results)){
                    return $share_results;
                }
                break;
            case Report::TYPE_DASHBOARD;
                $query = new Query();
                $query->select(['user_id id'])->from('ec_statistic_view')
                    ->where(['>', 'start_at', $from])
                    ->andWhere(['<', 'start_at', $to])
                    ->orderBy('start_at DESC');
                return $query->all();
                break;
        }
    }

    public function statistic(){
        $query = new Query();
        $query->select(['id', 'username'])->from('user');
        $data['Total User'] = $query->count();

        $queryTotalUserActive = $query;
        $queryTotalUserActive->where('updated_at > created_at')->orderBy('created_at DESC');
        $data['Total User Active'] = $query->count();


        $queryTotalUserActiveInMonth = $query;
        $queryTotalUserActiveInMonth->where('updated_at > created_at')->andWhere(['BETWEEN', 'updated_at', strtotime('-30 days'), time()])->orderBy('created_at DESC');
        $data['Total User Active In Month'] = $query->count();


        return $data;
    }

}