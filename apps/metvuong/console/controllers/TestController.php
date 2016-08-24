<?php
namespace console\controllers;

use console\models\Test;
use Yii;
use yii\console\Controller;
use frontend\models\Elastic;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class TestController extends Controller {
    public $thread;
    public $start;
    public $end;
    public function options()
    {
        return ['thread','start','end'];
    }
    public function optionAliases()
    {
        return ['thread'=>'thread','start'=>'start','end'=>'end'];
    }

    public function actionDuplicate($thread = '1', $start = 1, $end = 10)
    {
        Test::me()->duplicate($thread, $start, $end);
    }

    public function actionTransaction()
    {
        $pid = uniqid();
        for($i = $this->start; $i<=$this->end; $i ++) {
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $content = <<<EOD
This may seem obvious, but it caused me some frustration. If you try and use htmlspecialchars with the argument set and the string you run it on is not actually the same charset you specify, you get any empty string returned without any notice/warning/error.
EOD;
                echo $this->thread.'_'.$i.PHP_EOL;
                usleep(1000000);
                $transaction->commit();
                if($i%3===0){
                    Test::me()->transaction($content, file_get_contents('http://serverfault.com/questions/118504/how-to-improve-mysql-insert-and-update-performance'));
                }else{
                    Test::me()->transaction($pid.'_'.$this->thread.'_'.$i, $content);
                }
                print_r('=====DONE !=====');
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        exit;

    }

}