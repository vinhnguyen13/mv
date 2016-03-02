<?php

namespace frontend\controllers;
use common\components\Util;
use dektrium\user\Mailer;
use Elasticsearch\ClientBuilder;
use frontend\models\Elastic;
use frontend\models\Tracking;
use frontend\models\UserActivity;
use GuzzleHttp\Ring\Client\CurlHandler;
use vsoft\tracking\models\base\AdProductVisitor;
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
        $user = User::findOne(1);
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
            $pids = [1, 2, 4, 3, 5, 6];
            $times = Util::me()->dateRange(strtotime('-30 days'), strtotime('+1 days'), '+1 day', 'd-m-Y H:i:s');
            foreach($pids as $pid){
                $uid = array_rand(array_flip($uids), 1);
                $time = array_rand(array_flip($times), 1);
                $time = strtotime($time);
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

        $content = $this->getUrlContent();
        echo "<pre>";
        var_dump($content);
        echo "</pre>";
        exit;
        $handler = new CurlHandler();
        $response = $handler([
            'http_method' => 'GET',
            'uri'         => '/',
            'headers'     => [
//                'host'  => ['localhost:9200/_all'],
                'host'  => ['dev.metvuong.com:9200/demo'],
                'x-foo' => ['baz']
            ]
        ]);

        $response->then(function (array $response) {
            echo $response['status'];
        });

        $response->wait();

        echo "<pre>";
        print_r(5);
        echo "</pre>";
        exit;
        $hosts = [
            '172.30.6.104:9200',         // IP + Port
            '172.30.6.104',              // Just IP
//            '125.234.107.93:9200',         // IP + Port
//            '125.234.107.93',              // Just IP
//            'dev.metvuong.com:9200', // Domain + Port
//            'dev.metvuong.com',     // Just Domain
        ];
        $singleHandler  = ClientBuilder::singleHandler();
        $multiHandler   = ClientBuilder::multiHandler();
        $client = ClientBuilder::create()           // Instantiate a new ClientBuilder
            ->setHosts($hosts)      // Set the hosts
            ->setHandler($singleHandler)
            ->build();              // Build the client object
        var_dump($client->transport->getConnection()->ping());
        $params = [
            'index' => [ 'demo' ]
        ];
        $results = $client->indices()->get($params);
//        var_dump( exec('curl -XGET http://172.30.6.104:9200/_all') );
//        var_dump( exec('curl http://batdongsan.com.vn/nha-dat-ban-quan-2') );
        echo "<pre>";
        print_r($results);
        echo "</pre>";
        exit;
    }


    private function getUrlContent()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://172.30.2.184:9200/demo');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpcode >= 200 && $httpcode < 300) ? $data : null;
    }

    public function actionSelect(){
        $this->layout = '@app/views/layouts/main';
        return $this->render('select');
    }

    public function actionMongo(){
        if(($adProductVisitor = AdProductVisitor::findOne(['user_id'=>1, 'product_id'=>1]))===null){
            $adProductVisitor = new AdProductVisitor();
            $adProductVisitor->user_id = 1;
            $adProductVisitor->product_id = 1;
        }
        $adProductVisitor->time = time();
        $adProductVisitor->count = 1;
        $adProductVisitor->save();
        echo "<pre>";
        print_r($adProductVisitor);
        echo "</pre>";
        exit;
    }

    public function actionDevice(){
        echo 'Mobile: '.Yii::$app->mobileDetect->isMobile();
        echo 'Tablet: '.Yii::$app->mobileDetect->isTablet();
    }

    public function actionActivity(){
        UserActivity::find()->saveActivity(UserActivity::ACTION_AD_FAVORITE, "{user} favorite {product} of {product_owner}", [
            'user'=>Yii::$app->user->identity->username,
            'product'=>5071,
            'product_owner'=>Yii::$app->user->identity->username
        ], 5071);
    }
}
