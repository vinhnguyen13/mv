<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 5/18/2016 1:13 PM
 */

namespace console\models;

use frontend\components\Mailer;
use frontend\models\Token;
use frontend\models\User;
use vsoft\ad\models\AdContactInfo;
use vsoft\ad\models\AdProduct;
use vsoft\coupon\models\CouponCode;
use vsoft\express\components\AdImageHelper;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Metvuong extends Component
{

    public static function sendMailContact($code=null){
        $contacts = AdContactInfo::getDb()->cache(function(){
            $sql = "SELECT email, count(product_id) as total, group_concat(product_id) as list_id
                    FROM metvuong_dev.ad_contact_info where email is not null group by email order by count(product_id) desc limit 200";
            return AdContactInfo::getDb()->createCommand($sql)->queryAll();
        });
//        $contacts = [
//            [
//                'email' => 'nhuttranm@gmail.com',
//                'total' => 6,
//                'list_id' => '501,503,516,517,518,520'
//            ],
//            [
//                'email' => 'nhut.love@live.com',
//                'total' => 2,
//                'list_id' => '521,522'
//            ]
//        ];
        if(count($contacts) > 0) {
            $count_code = CouponCode::getDb()->cache(function() use($code){
                return CouponCode::find()->where('code = :c',[':c' => $code])->count('code');
            });
            if($count_code == 0)
                $code = null;

            foreach ($contacts as $contact) {
                $email = trim($contact["email"]);
                $user = User::getDb()->cache(function () use ($email) {
                    return User::find()->where(['email' => $email])->one();
                });

//                if (count($user) > 0 && ($user->updated_at > $user->created_at)) {
//                    continue;
//                } else {
                if(true){
                    $token = Token::find()->where(['user_id' => $user->id])->one();
                    if(count($token) <= 0){
                        /** @var Token $token */
                        $token = new Token();
                        $token->user_id = $user->id;
                        $token->code = Yii::$app->security->generateRandomString();
                        $token->type = Token::TYPE_CRAWL_USER_EMAIL;
                        $token->created_at = time();
                        $res = $token->save();
                        if($res == false){
                            print_r("{$email} cannot create new token");
                            continue;
                        }
                    }

                    $array_product_id = explode(",", $contact["list_id"]);
                    $products = AdProduct::getDb()->cache(function () use ($array_product_id) {
                        return AdProduct::find()->where(['IN', 'id', $array_product_id])->limit(3)->all();
                    });
                    $product_list = array();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $url = $product->urlDetail(true); // loi ko the su dung Url::to()
                            $id = $product->id;
//                        $slug = \common\components\Slug::me()->slugify($product->getAddress($product->show_home_no));
//                        $url = "http://local.metvuong.com/real-estate/detail/{$id}-{$slug}";
                            $product_list[Yii::$app->params['listing_prefix_id'] . $id] = $url;
                        }
                    }

                    $rest_total = intval($contact["total"]) - 3;

                    $params = [
                        'email' => $email,
                        'username' => $user->username,
                        'token' => $token,
                        'product_list' => $product_list,
                        'rest_total' => $rest_total,
                        'code' => $code
                    ];

                    $subjectEmail = "Thông báo tin đăng từ metvuong.com";
                    $print = \Yii::$app->mailer->compose(['html'=>'contactEmail-html'], ['params' => $params])
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo([$email])
                        ->setSubject($subjectEmail)
                        ->send();
                    $print > 0 ? print_r("\nsent to {$email}") : "Send mail error.";
                }
            }
        } else
            print_r("No contact info");
    }

    public static function DownloadImage($link, $uploaded_at)
    {
        $helper = new AdImageHelper();
        $uploaded_at = empty($uploaded_at) ? time() : $uploaded_at;
        $folderColumn = $helper->getAbsoluteUploadFolderPath($uploaded_at);
        $folder = Yii::getAlias('@store'). DIRECTORY_SEPARATOR . $folderColumn;
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
        if (!empty($link)) {
            $ext = explode('.', $link);
            $length = count($ext) - 1;
            $fileName = uniqid() . '.' . $ext[$length];
            $filePath = $folder . DIRECTORY_SEPARATOR . $fileName;
            $content = file_get_contents($link);
            file_put_contents($filePath, $content);

            foreach ($helper::$sizes as $size) {
                $sub_folder = $folder . DIRECTORY_SEPARATOR . $helper::makeFolderName($size);
                if (!is_dir($sub_folder)) {
                    mkdir($sub_folder, 0777, true);
                }
                $sub_filePath = $sub_folder . DIRECTORY_SEPARATOR . $fileName;
                file_put_contents($sub_filePath, $content);
            }
            return [$fileName, $folderColumn];
        }
        return null;
    }

}