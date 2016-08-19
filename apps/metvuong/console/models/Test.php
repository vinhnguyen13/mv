<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 8/10/2016
 * Time: 11:42 AM
 */

namespace console\models;


use yii\base\Component;
use Yii;

class Test extends Component
{
    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function duplicate($thread, $start, $end)
    {

        $path = Yii::getAlias('@console') . "/data/test/";
        if(!is_dir($path)){
            mkdir($path, 0777);
        }
        $time = time();
        ob_start();
        for($i = $start; $i<=$end; $i ++) {
            $pid = posix_getpid();
            $row = $pid.': '.$i.PHP_EOL;
            echo $row;
            if($thread==1){
                $this->saveDuplicate($pid, $row);
            }else{
                $this->saveDuplicateNot($pid, $row);
            }
            sleep(7);
            ob_flush();
        }
        $row2 = "start: ".date('d-m H:i:s', $time).", end: ".date('d-m H:i:s', time()).', Total time: '.(time() - $time).PHP_EOL;
        if($thread==1){
            $this->saveDuplicate($pid, $row2);
        }else{
            $this->saveDuplicateNot($pid, $row2);
        }
    }

    public static function saveDuplicate($pid, $data)
    {
        Yii::$app->dbCraw->createCommand("INSERT INTO `test_duplicate` (pid, content) VALUES ('$pid', '$data')")->execute();
    }

    public static function saveDuplicateNot($pid, $data)
    {
        Yii::$app->dbCraw->createCommand("INSERT INTO `test_duplicate_not` (pid, content) VALUES ('$pid', '$data')")->execute();
    }

    public function transaction($pid, $data)
    {
        Yii::$app->db->createCommand("INSERT INTO `test_transaction` (pid, content) VALUES ('$pid', '$data')")->execute();
    }
}