<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 5/18/2016 1:13 PM
 */

namespace console\models;

use dektrium\user\helpers\Password;
use frontend\components\Mailer;
use frontend\models\Token;
use frontend\models\User;
use vsoft\ad\models\AdContactInfo;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\MarkEmail;
use vsoft\coupon\models\CouponCode;
use vsoft\express\components\AdImageHelper;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class Metvuong extends Component
{
    public static function DownloadImage($link, $uploaded_at, $tempFolder=null)
    {
        $helper = new AdImageHelper();
        $uploaded_at = empty($uploaded_at) ? time() : $uploaded_at;
        $folderColumn = $helper->getAbsoluteUploadFolderPath($uploaded_at);
        $folder = Yii::getAlias('@store'). "/". $folderColumn;
        if($tempFolder){
            $folder = $tempFolder. $folderColumn;
        }

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
        if (!empty($link)) {
            $ext = explode('.', $link);
            $length = count($ext) - 1;
            $fileName = uniqid() . '.' . $ext[$length];
            $filePath = $folder . "/" . $fileName;
            $content = file_get_contents($link);
            if(!empty($content)) {
                file_put_contents($filePath, $content);

                $helper->makeFolderSizes($folder);
                $helper->resize($filePath);

                $folderColumn = str_replace("\\", "/", $folderColumn);
                return [$fileName, $folderColumn, $folder];
            }
        }
        return null;
    }

    public static function saveCurrentIdImageReDownload($id)
    {
        Helpers::writeLog($id, Yii::getAlias('@store'). "/files/download-image/", 'index');
    }

    public static function getCurrentIdImageReDownload()
    {
        return file_get_contents(Yii::getAlias('@store'). "/files/download-image/index");
    }

    public static function mapContactProduct($limit=1000)
    {
        $start_time = time();
        $connection = AdProduct::getDb();
        $connection->createCommand('SET group_concat_max_len = 5000000')->execute();
        $sql = "select c.email, u.id as user_id, group_concat(c.product_id) as list_id from ad_contact_info c inner join `user` u on c.email = u.email where c.email is not null ";

        $path = Yii::getAlias('@console'). "/data/bds_html/map_product/";
        $email_list_file_name = "map_contact_product.json";
        $log = Helpers::loadLog($path, $email_list_file_name);
        $log_emails = isset($log['emails']) ? $log['emails'] : array();

        if(count($log_emails) > 0){
            $str_emails = "'". implode("', '", $log_emails). "'";
            $sql = $sql. " and c.email not in ({$str_emails}) ";
        }
        $sql = $sql. " group by c.email";


        if($limit >=1 && $limit <= 1000){
            $sql = $sql. " limit ". $limit;
        } else {
            print_r("\nRecord limit from 1 to 1000");
            return;
        }

        $email_listings = $connection->createCommand($sql)->queryAll();
        if(count($email_listings) > 0){
            foreach ($email_listings as $key_email => $email_listing) {
                $list_id = $email_listing['list_id'];
                $user_id = $email_listing['user_id'];
                $email = $email_listing['email'];

                $update_sql = "UPDATE `ad_product` SET `user_id`={$user_id} WHERE id IN ({$list_id})";
                $connection->createCommand($update_sql)->execute();

                if(!in_array($email, $log['emails']))
                    $log['emails'][] = $email;
                Helpers::writeLog($log, $path, $email_list_file_name);
                print_r("\n" . ($key_email + 1) . " - ". $email. " - ". $list_id);

            }
        } else {
            print_r("Product not found");
        }

        $end_time = time();
        $time = $end_time - $start_time;
        print_r("\n\nTime: {$time}s");
    }

}