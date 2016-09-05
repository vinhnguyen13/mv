<?php

namespace frontend\controllers;
use Aws\Ses\SesClient;
use common\components\MailerMailChimp;
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
        $mailer = new \common\components\Mailer();
        $mailer->viewPath = '@frontend/mail';
        $status = $mailer->compose(['html' => 'test'], ['params' => []])
            ->setFrom(Yii::$app->params['noreplyEmail'])
            ->setTo(['quangvinh.nguyen@trungthuygroup.vn'])
//            ->setTo(['contact@metvuong.com'])
            ->setSubject('Hello')
            ->send();
        echo "<pre>";
        print_r($status);
        echo "</pre>";
        exit;

        $stt = MailerMailChimp::me()->send();
        echo "<pre>";
        print_r($stt);
        echo "</pre>";
        exit;
    }

    public function actionSend()
    {
        $folder = Yii::getAlias('@store'.'/mail');
        $filePath = $folder.'/text.txt';
        if(!is_dir($folder)){
            mkdir($folder, 0777);
        }
        $handle = fopen($filePath, 'w') or die('Cannot open file:  ' . $filePath);
        $int = fwrite($handle, var_export($_SERVER));
        fclose($handle);
        echo "<pre>";
        print_r($int);
        echo "</pre>";
        exit;
    }

    public function actionTracking()
    {
        $uid = 8;
        $ip = Yii::$app->getRequest()->getUserIP();
        $products = [
            161077,
            156071,
            134816
        ];
        $return = Tracking::find()->compareStats(null, $ip, $products);
        echo "<pre>";
        print_r($return);
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
        $return = Avg::me()->calculation_boxplot([9,1800,1900,1944,1950,1972,2000,2184,2200,2480,2900,3000,3182,3300,3474,3700,5000,5700,5700,5704,6400,6500,6900,7000,7000,7000,7000,7500,7500,7600,8000,8000,8000,8000,8200,8300,8320,8320,8320,8320,8346,8400,8446,8500,8600,8700,8700,8700,8700,8750,8750,8800,8840,8844,8900,9000,9000,9000,10000,10000,10000,10000,11430,12090,12090,12090,15360,15360,15360,15360,15360,15360,15360,15360,15600,16800,17040,17280,17280,17280,17280,17280,17280,17400,17600,17800,17800,17800,17864,17900,17900,18000,18000,18000,18000,18000,18200,18300,18500,18600,18720,18850,19000,19000,19000,19000,19000,19000,19748,19752,19752,20069,20500,20500,22000,22168,22360,22600,23000,25300,27000,27706,28000,36000,37000,37369,37900,38000,38000,38000]);
        echo "<pre>";
        print_r($return);
        echo "</pre>";
        exit;
    }

    public function actionLoginData($r=30)
    {
        $from = strtotime('-'.$r.' days');
        $to = time();
        $query = \frontend\models\UserActivity::find();
        $query->select(['action', 'owner_id', 'updated']);
        $query->andWhere(['IN', 'action', [\frontend\models\UserActivity::ACTION_USER_LOGIN]])
            ->andFilterWhere(['BETWEEN', 'updated', $from, $to])
            /*->groupBy('{{user_activity}}.id')*/->orderBy('updated DESC');
        $login_results = $query->asArray()->all();
        if(!empty($login_results)){
            array_filter($login_results, function($element, $key) use (&$stats_login3, &$arrTrung) {
                $today = !empty($element['today']) ? $element['today'] : date('d/m/Y', $element['updated']);
                $_key = strtotime(str_replace('/', '-', $today));
                if(!empty($stats_login3[$_key])){
                    $stats_login3[$_key]['total'] ++;
                }else{
                    $stats_login3[$_key]['total'] = 1;
                    $stats_login3[$_key]['today'] = $today;
                }
                $_keyExist = $_key.'_'.$element['owner_id'];
                if(!empty($arrTrung[$_keyExist]['total'])){
                    $arrTrung[$_keyExist]['total'] += 1;
                    $arrTrung[$_keyExist]['id'][] = (string)$element['_id'];
                    $arrTrung[$_keyExist]['today'] = $today;
                }else{
                    $arrTrung[$_keyExist]['total']=1;
                    $arrTrung[$_keyExist]['id'][] = (string)$element['_id'];
                    $arrTrung[$_keyExist]['today'] = $today;
                }
                return $element;
            }, ARRAY_FILTER_USE_BOTH);


            array_filter($arrTrung, function($element, $key){
                if(!empty($element['total']) && $element['total'] > 1){
                    $_id = $element['id'][0];
                    $loginRecord = \frontend\models\UserActivity::findOne($_id);
                    if(!empty($loginRecord)){
                        $loginRecord->delete();
                    }
                }
            }, ARRAY_FILTER_USE_BOTH);

            echo "<pre>";
            print_r($arrTrung);
            echo "</pre>";
            exit;
            ksort($stats_login3);
        }
    }
}





