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

class Report extends Component
{
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
            $default = array_map(function($v){
                return ['y'=>0, 'date'=>'#'];
            },$dateRange);
            $dataRegister = $dataLogin = $dataListing = $default;
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
                $dataRegister[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today']];
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

            $totalLogin = 0;
            foreach($stats_login as $item){
                $totalLogin += $item['total'];
                $kDate = array_search($item['today'], $dateRange);
                $dataLogin[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today']];
            }

            $query = \frontend\models\UserActivity::find();
            $query->select(['action', 'updated']);
            $query->andWhere(['IN', 'action', [\frontend\models\UserActivity::ACTION_USER_LOGIN]])
                ->andFilterWhere(['BETWEEN', 'updated', $from, $to])
                /*->groupBy('{{user_activity}}.id')*/->orderBy('updated DESC');
            $login_results = $query->asArray()->all();
            $stats_login = [];
            if(!empty($login_results)){
                foreach($login_results as $login_result){
                    $today = date('d/m/Y', $login_result['updated']);
                    if(!empty($stats_login[$today])){
                        $stats_login[$today]['total'] ++;
                    }else{
                        $stats_login[$today]['total'] = 1;
                        $stats_login[$today]['today'] = $today;
                    }
                }

            }
            $stats_login = array_values($stats_login);

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
                $dataListing[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today']];
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

}