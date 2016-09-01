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
use frontend\models\Tracking;
use frontend\models\Transaction;
use frontend\models\User;
use vsoft\ad\models\AdContactInfo;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\MarkEmail;
use vsoft\coupon\models\CouponCode;
use vsoft\express\components\AdImageHelper;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class Mail extends Component
{
    const TYPE_WELCOME_AGENT = 1;
    const TYPE_HOW_USE_DASHBOARD = 2;

    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function welcomeAgent($code=null, $limit=100)
    {
        print_r("----------Start-----------".PHP_EOL);
        Yii::$app->getDb()->createCommand('SET group_concat_max_len = 5000000')->execute();
        $contacts = AdContactInfo::find()->select('email, count(product_id) as total, group_concat(product_id) as list_id')
                ->where('email is not null')
                ->andWhere("email NOT IN (SELECT email FROM mark_email WHERE type = ".self::TYPE_WELCOME_AGENT.")")
                ->andWhere("email NOT IN (SELECT email FROM user WHERE updated_at > created_at )")
                ->groupBy('email')->orderBy('count(product_id) desc')->limit($limit)->all();

        if(!empty($contacts)) {
            $coupon = CouponCode::getDb()->cache(function() use($code){
                return CouponCode::find()->where('code = :c',[':c' => $code])->one();
            });
            if(!empty($coupon) && !$coupon->check()) {
                print_r("{$code} was used. Please, select other coupon code.");
                return;
            }else{
                $code = null;
            }
            foreach ($contacts as $contact) {
                $email = trim($contact["email"]);
                $email = mb_strtolower($email);
                // check user exists or create new user
                $user = $contact->createUserInfo();
                if (!empty($user->id)) {
                    $token = new Token();
                    $token->user_id = $user->id;
                    $token->code = Yii::$app->security->generateRandomString();
                    $token->type = Token::TYPE_CRAWL_USER_EMAIL;
                    $token->created_at = time();
                    $res = $token->save();
                    if ($res == false) {
                        print_r("{$email} cannot create new token".PHP_EOL);
                        continue;
                    }
                    $array_product_id = explode(",", $contact["list_id"]);
                    $products = AdProduct::getDb()->cache(function () use ($array_product_id) {
                        return AdProduct::find()->where(['IN', 'id', $array_product_id])->limit(3)->all();
                    });
                    $product_list = array();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $url = $product->urlDetail(true);
                            $id = $product->id;
                            $product_list[Yii::$app->params['listing_prefix_id'] . $id] = $url;
                        }
                    }

                    $rest_total = intval($contact["total"]) - 3;
                    $params = [
                        'email' => $email,
                        'link_user' => Url::to(['member/profile', 'username' => $user->username], true),
                        'token' => $token,
                        'product_list' => $product_list,
                        'rest_total' => $rest_total,
                        'code' => $code
                    ];
                    $tr = md5($email.self::TYPE_WELCOME_AGENT);
                    Yii::$app->view->params['tr'] = !empty($tr) ? (string) $tr : '';
                    Yii::$app->view->params['tp'] = Tracking::LOGO_MAIL_WELCOME;
                    $subjectEmail = "Thông báo tin đăng Bất Động Sản của bạn từ MetVuong.com";
                    try {
                        $mailer = new \common\components\Mailer();
                        $mailer->viewPath = '@common/mail';
                        $status = $mailer->compose(['html' => 'contactEmail-html'], ['params' => $params])
                            ->setFrom(Yii::$app->params['noreplyEmail'])
                            ->setTo([$email])
                            ->setSubject($subjectEmail)
                            ->send();
                        $status > 0 ? print_r("[{$mailer->transport['username']}] sent to [{$email}] success !".PHP_EOL) : print_r("Send mail error.".PHP_EOL);
                        // Count email marketing has sent
                        $return = Mail::markEmail(self::TYPE_WELCOME_AGENT, $email, $status);
                        usleep(300000);
                    } catch (Exception $ex) {
                        print_r("Error .".PHP_EOL);
                    }
                }else{
                    Mail::markEmail(self::TYPE_WELCOME_AGENT, $email, -1);
                    print_r("Not create account $email.".PHP_EOL);
                }
            }
        } else{
            print_r("No contact info.".PHP_EOL);
        }
        print_r("----------End-----------".PHP_EOL);
    }

    public static function markEmail($type, $email, $status)
    {
        $markEmail = MarkEmail::find()->where('email = :e AND type = :type',[':e' => $email, ':type'=>$type])->one();
        if(count($markEmail) > 0){
            if($status) {
                $markEmail->count += 1;
            }
        } else {
            $markEmail = new MarkEmail();
            $markEmail->email = $email;
            $markEmail->count = 1;
        }
        $markEmail->type = $type;
        $markEmail->send_time = time();
        $markEmail->status = $status;
        $markEmail->save(false);
        return $markEmail;
    }

    public function howUseDashboard($limit)
    {
        print_r("----------Start-----------".PHP_EOL);
        $query = new Query();
        $query->select(['a.email', 'a.username'])->from('user a')
            ->innerJoin('ec_transaction_history b', 'a.id = b.user_id')
            ->where(['=', 'b.status', Transaction::STATUS_SUCCESS])
            ->andWhere("email NOT IN (SELECT email FROM mark_email WHERE type = ".self::TYPE_HOW_USE_DASHBOARD.")")
            ->groupBy('a.id');
        $contacts = $query->limit($limit)->all();
        if(!empty($contacts)) {
            foreach ($contacts as $contact) {
                $email = trim($contact["email"]);
                $email = mb_strtolower($email);
                if (!empty($email)) {
                    $link = Url::to(['/dashboard/ad', 'username' => $contact["username"]], true);
                    $code = md5($email.self::TYPE_HOW_USE_DASHBOARD);
                    $params = [
                        'email' => $email,
                        'link' => $link,
                        'code' => $code,
                    ];
                    Yii::$app->view->params['tr'] = !empty($code) ? (string) $code : '';
                    Yii::$app->view->params['tp'] = Tracking::LOGO_MAIL_HOW_USE_DASHBOARD;
                    $subjectEmail = "Bảng thống kê (Dashboard) của Bất Động Sản MetVuong.com";
                    try {
                        $mailer = new \common\components\Mailer();
                        $mailer->viewPath = '@common/mail';
                        $status = $mailer->compose(['html' => 'howUseDashboard'], $params)
                            ->setFrom(Yii::$app->params['noreplyEmail'])
                            ->setTo([$email])
//                            ->setTo(['dien.truong@trungthuygroup.vn', 'quangvinh.nguyen@trungthuygroup.vn'])
                            ->setSubject($subjectEmail)
                            ->send();
                        $status > 0 ? print_r("[{$mailer->transport['username']}] sent to [{$email}] success !".PHP_EOL) : print_r("Send mail error.".PHP_EOL);
                        $return = Mail::markEmail(self::TYPE_HOW_USE_DASHBOARD, $email, $status);
                        // Count email marketing has sent
                        usleep(300000);
                    } catch (Exception $ex) {
                        print_r("Error: {$email}".PHP_EOL);
                    }
                }else{
                    Mail::markEmail(self::TYPE_HOW_USE_DASHBOARD, $email, -1);
                    print_r("Not create account $email.".PHP_EOL);
                }
            }
        } else{
            print_r("No contact info.".PHP_EOL);
        }
        print_r("----------End-----------".PHP_EOL);
    }
}