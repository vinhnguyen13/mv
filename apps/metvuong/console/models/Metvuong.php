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
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Metvuong extends Component
{
    public static function sendMailContact($code=null)
    {
        $contacts = AdContactInfo::find()->select('email, count(product_id) as total, group_concat(product_id) as list_id')
                ->where('email is not null')
                ->andWhere("email NOT IN (select email from mark_email where status = 1)")
                ->groupBy('email')->orderBy('count(product_id) desc')->limit(100)->all();

        if(count($contacts) > 0) {
            $count_code = CouponCode::getDb()->cache(function() use($code){
                return CouponCode::find()->where('code = :c',[':c' => $code])->count('code');
            });
            if($count_code == 0)
                $code = null;

            foreach ($contacts as $contact) {
                $email = trim($contact["email"]);
                $email = mb_strtolower($email);
                // check user exists or create new user
                $user = $contact->createUserInfo();
                if (count($user) > 0 && ($user->updated_at > $user->created_at)) {
                    print_r("\n{$email} was disabled");
                    continue;
                } else {
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
                    $status = \Yii::$app->mailer->compose(['html'=>'contactEmail-html'], ['params' => $params])
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo([$email])
                        ->setSubject($subjectEmail)
                        ->send();
                    $status > 0 ? print_r("\nsent to {$email}") : "Send mail error.";
                    // Count email marketing has sent
                    Metvuong::markEmail($email, $status);
                }
            }
            print_r("\n--------------------\nSend email finish.");
        } else
            print_r("No contact info.");
    }

    public static function markEmail($email, $status)
    {
        $markEmail = MarkEmail::find()->where('email = :e',[':e' => $email])->one();
        if(count($markEmail) > 0){
            if($status) {
                $c = $markEmail->count;
                $markEmail->count = $c + 1;
            }
            $markEmail->send_time = time();
            $markEmail->status = $status;
            $markEmail->update(false);
        } else {
            $markEmail = new MarkEmail();
            $markEmail->email = $email;
            $markEmail->count = 1;
            $markEmail->send_time = time();
            $markEmail->status = $status;
            $markEmail->save(false);
        }
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