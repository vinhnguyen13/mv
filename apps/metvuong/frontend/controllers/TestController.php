<?php

namespace frontend\controllers;
use dektrium\user\Mailer;
use frontend\models\Elastic;
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

    public function actionElastic(){
        $elastic = new Elastic();
        $client = $elastic->connect();
        /*$params = [
            'index' => 'tracking',
            'type' => 'search',
            'id' => '2222',
            'body' => [ 'testField' => 'abc']
        ];
        // Document will be indexed to my_index/my_type/my_id
        $response = $client->index($params);
        echo "<pre>";
        print_r($response);
        echo "</pre>";
        exit;*/
        $params = [
            'index' => 'tracking',
            'type' => 'search',
            'id' => '132',
        ];
        // Document will be indexed to my_index/my_type/my_id
        $response = $client->get($params);
//        $response = $client->cluster()->stats();
//        $response = $client->nodes()->stats();
        echo "<pre>";
        print_r($response);
        echo "</pre>";
        exit;
    }

    public function actionSelect(){
        return $this->render('select');
    }
}
