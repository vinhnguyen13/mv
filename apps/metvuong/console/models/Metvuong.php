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
    public static function sendMailContact($code=null, $limit=100)
    {
        $contacts = AdContactInfo::find()->select('email, count(product_id) as total, group_concat(product_id) as list_id')
                ->where('email is not null')
                ->andWhere("email NOT IN (select email from mark_email)")
                ->andWhere("email NOT IN (select email from user where updated_at > created_at )")
                ->groupBy('email')->orderBy('count(product_id) desc')->limit($limit)->all();

        if(count($contacts) > 0) {
            $coupon = CouponCode::getDb()->cache(function() use($code){
                return CouponCode::find()->where('code = :c',[':c' => $code])->one();
            });

            if(!$coupon->check()) {
                print_r("{$code} was used. Please, select other coupon code.");
                return;
            }
            if(count($coupon) == 0)
                $code = null;

            foreach ($contacts as $contact) {
                $email = trim($contact["email"]);
                $email = mb_strtolower($email);
                // check user exists or create new user
                $user = $contact->createUserInfo();
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
                    'link_user' => Url::to(['member/profile','username' => $user->username],true),
                    'token' => $token,
                    'product_list' => $product_list,
                    'rest_total' => $rest_total,
                    'code' => $code
                ];

                $subjectEmail = "Thông báo tin đăng từ metvuong.com";
                try {
                    $mailer = new \common\components\Mailer();
                    $mailer->viewPath = '@common/mail';
                    $status = $mailer->compose(['html' => 'contactEmail-html'], ['params' => $params])
                        ->setFrom(Yii::$app->params['noreplyEmail'])
                        ->setTo([$email])
                        ->setSubject($subjectEmail)
                        ->send();
                    $status > 0 ? print_r("\n {$mailer->transport['username']} sent to {$email}") : "Send mail error.";
                    // Count email marketing has sent
                    Metvuong::markEmail($email, $status);
                    usleep(300000);
                } catch (Exception $ex) {
                    print_r("\n Error .");
                }
            }
            print_r("\n--------------------\nSend email finish.");
        } else
            print_r("No contact info.");
    }

    public static function transport(){
        $transport = [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.gmail.com',
            'username' => '',
            'password' => '',
            'port' => '587',
            'encryption' => 'tls',
        ];
        $sender = Yii::$app->params['sender'];
        $k = array_rand($sender);
        $transport = ArrayHelper::merge(
            $transport,
            $sender[$k]
        );
        return $transport;
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
            if($content) {
                file_put_contents($filePath, $content);

                $helper->makeFolderSizes($folder);
                $helper->resize($filePath);

                $folderColumn = str_replace("\\", "/", $folderColumn);
                return [$fileName, $folderColumn, $folder];
            }
        }
        return null;
    }

}