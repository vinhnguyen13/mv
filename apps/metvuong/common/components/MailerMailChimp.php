<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Mailer.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class MailerMailChimp extends Component
{
    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function send()
    {
        try {
            $api_key = '1f07b702500f2668d602a127acfa9b1a-us13';
            require(Yii::getAlias('@common').'/myvendor/mailchimp-api-php/src/Mailchimp.php');

//Create Campaign
            $MailChimp = new \Mailchimp($api_key, [
//                'debug'=>true
            ]);
            $list_id = 'c9e01b7cfb';
            $email = 'contact@metvuong.com';
            $merge_vars = array();

            /**
             * add subscribe
             */
            $result = $MailChimp->call('campaigns/send-test', array(
                'id'                => $list_id,
                'email'             => array('email'=>'quangvinhit2007@gmail.com'),
                'merge_vars'        => array(),
                'double_optin'      => false,
                'update_existing'   => true,
                'replace_interests' => false,
                'send_welcome'      => false,
            ));
            echo "<pre>";
            print_r($result);
            echo "</pre>";
            exit;

        } catch (\Exception $ex) {
            return $ex;
        }
    }
    /**
     * add subscribe
     */
    private function addSubscribe($MailChimp, $list_id){
        $result = $MailChimp->call('lists/subscribe', array(
            'id'                => $list_id,
            'email'             => array('email'=>'quangvinh.nguyen@trungthuygroup.vn'),
            'merge_vars'        => array(),
            'double_optin'      => false,
            'update_existing'   => true,
            'replace_interests' => false,
            'send_welcome'      => false,
        ));
    }
}
