<?php

namespace frontend\controllers;
use dektrium\user\Mailer;
use Elasticsearch\ClientBuilder;
use frontend\models\Elastic;
use frontend\models\Tracking;
use vsoft\user\models\User;
use Yii;
use yii\db\mssql\PDO;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use vsoft\ad\models\AdBuildingProject;

class TestController extends \yii\web\Controller
{
    public $layout = '@app/views/layouts/layout';
    public function actionMail()
    {
        $user = User::findOne(5);
        $mailer = new Mailer();
        $chk = $mailer->sendWelcomeMessage($user, null, true);
        echo "<pre>";
        print_r($chk);
        echo "</pre>";
        exit;
    }

    public function actionNews()
    {
        $sql =  'CALL getContent (1)' ;
        $command = Yii::$app->db->createCommand($sql);
        $news = $command->queryAll();

        $news = CmsShow::findBySql('CALL getContent (1)')->all();
        if(!empty($news)){
            echo 'Begin ...<br />';
            foreach($news as $idx => $new){
                echo "-------- ID: {$new->id} Title: {$new->title} <br />";
                flush();
                ob_flush();
                sleep(1);
            }
            echo 'End ...<br />';
        }
    }

    public function actionElastic($action=''){
        if($action == 'add'){
            $uids = [4, 2, 3, 7, 9];
            $pids = range(1, 2);
            $times = [strtotime('6-1-2016 8:30'), strtotime('6-1-2016 12:30'),
                strtotime('5-1-2016 7:30'), strtotime('5-1-2016 9:30'),
                strtotime('4-1-2016 4:30'), strtotime('4-1-2016 7:30'),
                strtotime('7-1-2016 4:30'), strtotime('7-1-2016 7:30')
            ];
            foreach($pids as $pid){
                $uid = array_rand(array_flip($uids), 1);
                $time = array_rand(array_flip($times), 1);
                $ck = Tracking::find()->productVisitor($uid, $pid, $time);
                var_dump($ck);
            }
        }elseif($action == 'search'){
            $startTime = strtotime('2-1-2016');
            $endTime = strtotime('19-1-2016');
            $dataTracking = Tracking::find()->getProductTracking($startTime, $endTime, [1513]);
            echo "<pre>";
            print_r($dataTracking);
            echo "</pre>";
            exit;
        }
    }

    public function actionElastic2(){
        $hosts = [
//            '127.0.0.1:9200',         // IP + Port
//            '127.0.0.1',              // Just IP
            'local.metvuong.com:9200', // Domain + Port
            'local.metvuong.com',     // Just Domain
        ];
        $singleHandler  = ClientBuilder::singleHandler();
        $multiHandler   = ClientBuilder::multiHandler();
        $client = ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($hosts)      // Set the hosts
        ->setHandler($singleHandler)
            ->build();              // Build the client object
        echo "<pre>";
        print_r($client);
        echo "</pre>";
        exit;
    }

    public function actionSelect(){
        $this->layout = '@app/views/layouts/main';
        return $this->render('select');
    }
}
