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
        $filename = $path . "duplicate.log";
        if(file_exists($filename))
            $data = file_get_contents($filename);
        else
        {
            $this->writeFileJson($filename, null);
            $data = file_get_contents($filename);
        }
        $time = time();
        ob_start();
        for($i = $start; $i<=$end; $i ++) {
            $row = $thread.': '.$i.PHP_EOL;
            echo $row;
            $this->writeFileJson($filename, $row);
            usleep(500000);
            ob_flush();
        }
        print_r('Total time: '.(time() - $time));
    }

    public static function writeFileJson($filePath, $data)
    {
        $handle = fopen($filePath, 'a+') or die('Cannot open file:  ' . $filePath);
        $int = fwrite($handle, $data);
        fclose($handle);
        return $int;
    }
}