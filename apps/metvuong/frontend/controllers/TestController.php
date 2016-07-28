<?php

namespace frontend\controllers;
use common\components\Util;
use frontend\components\Mailer;
use Elasticsearch\ClientBuilder;
use frontend\models\Avg;
use frontend\models\Elastic;
use frontend\models\NganLuong;
use frontend\models\Payment;
use frontend\models\Tracking;
use frontend\models\UserActivity;
use GuzzleHttp\Ring\Client\CurlHandler;
use vsoft\tracking\models\base\AdProductVisitor;
use frontend\models\User;
use Yii;
use yii\db\mssql\PDO;
use yii\helpers\ArrayHelper;
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

    public function actionPayment2(){
        $obj = (object) array(
            'error_code' => '00',
            'merchant_id' => '46893',
            'merchant_account' => 'gateway@trungthuygroup.vn',
            'pin_card' => '94083621396476',
            'card_serial' => '36182500453622',
            'type_card' => 'VNP',
            'order_id' => '89363abaea2f303afc79b682eb2282b2',
            'client_fullname' => 'Vinh Quang',
            'client_email' => 'vnphone2014@gmail.com',
            'client_mobile' => '0908100060',
            'card_amount' => 20000,
            'amount' => 16000,
            'transaction_id' => '61918050',
            'error_message' => 'Nạp thẻ thành công, mệnh giá thẻ = 20000',
        );
        Payment::me()->processTransactionByMobileCard(Yii::$app->user->id, '039638a4d07d4717d39efa3468b0efd8', $obj);
        echo "<pre>";
        print_r(5);
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
        UserActivity::find()->saveActivity(UserActivity::ACTION_AD_FAVORITE, "{owner} favorite {product} of {buddy}", [
            'user'=>Yii::$app->user->identity->getUsername(),
            'product'=>5071,
            'product_owner'=>Yii::$app->user->identity->getUsername()
        ], 5071);
    }

    public function actionLang(){
        Yii::t('activity', '{user} view {product} of {product_owner}');
        Yii::t('activity', '{user} view {product} of {product_owner}');
    }

    public function actionClearCache(){
        Yii::$app->cache->flush();
    }

    public function actionChart($pid, $index){
        for($i = $index; $i >= 0; $i-- ){
            $useDate = new \DateTime(date('Y-m-d', time()));
            $f = date_format($useDate, 'Y-m-d 00:00:00');
            $dateFrom = new \DateTime($f);
            $from = strtotime("-$i days", $dateFrom->getTimestamp());

            $uid_arr = Yii::$app->db->cache(function(){
                return ArrayHelper::getColumn(User::find()->select(['id'])->orderBy(['id' => SORT_ASC])->asArray()->all(), 'id');
            });

            $rid2 = rand(2, 4);
            for($i2 = $rid2; $i2 >= 0; $i2-- ) {
//                $uid = rand(1, 30);
                $uid = $uid_arr[array_rand($uid_arr, 1)];
                Tracking::find()->productVisitor($uid, $pid, $from);
            }

            $rid3 = rand(1, 3);
            for($i3 = $rid3; $i3 >= 0; $i3-- ) {
//                $uid = rand(1, 30);
                $uid = $uid_arr[array_rand($uid_arr, 1)];
                Tracking::find()->productShare($uid, $pid, $from, 2);
            }

            $rid = (($rid2 > $rid3) ? $rid2 : $rid3) + 3;
            for($i1 = $rid; $i1 >= 0; $i1-- ) {
//                $uid = rand(1, 30);
                $uid = $uid_arr[array_rand($uid_arr, 1)];
                Tracking::find()->productFinder($uid, $pid, $from);
            }

        }
        echo "<pre>";
        print_r(999);
        echo "</pre>";
        exit;
    }

    public function actionInfo(){
        echo "<pre>";
        print_r(phpinfo());
        echo "</pre>";
        exit;
    }

    public function actionAvg()
    {
        Avg::me()->calculation([9,1800,1900,1944,1950,1972,2000,2184,2200,2480,2900,3000,3182,3300,3474,3700,5000,5700,5700,5704,6400,6500,6900,7000,7000,7000,7000,7500,7500,7600,8000,8000,8000,8000,8200,8300,8320,8320,8320,8320,8346,8400,8446,8500,8600,8700,8700,8700,8700,8750,8750,8800,8840,8844,8900,9000,9000,9000,10000,10000,10000,10000,11430,12090,12090,12090,15360,15360,15360,15360,15360,15360,15360,15360,15600,16800,17040,17280,17280,17280,17280,17280,17280,17400,17600,17800,17800,17800,17864,17900,17900,18000,18000,18000,18000,18000,18200,18300,18500,18600,18720,18850,19000,19000,19000,19000,19000,19000,19748,19752,19752,20069,20500,20500,22000,22168,22360,22600,23000,25300,27000,27706,28000,36000,37000,37369,37900,38000,38000,38000]);
    }
}
