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
            $MailChimp = new \Mailchimp($api_key);
            $Mailchimp_Lists = new \Mailchimp_Lists($MailChimp);

            $list_id = 'c9e01b7cfb';
            $email = 'hello@email.com';
            $merge_vars = array();

            /*$subscriber = $Mailchimp_Lists->subscribe(
                $list_id,
                array('email'=>htmlentities($email)),
                $merge_vars,
                false,
                false,
                false,
                false
            );
echo "<pre>";
print_r($subscriber);
echo "</pre>";
exit;*/
            /*$result = $MailChimp->call('lists/subscribe', array(
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
exit;*/


            $result = $MailChimp->campaigns->create('regular',
                array('list_id'               => $list_id,
                    'subject'                       => 'This is a test subject',
                    'from_email'                        => 'test@test.com',
                    'from_name'                         => 'From Name'),
                array('html'                    => '<div>test html email</div>',
                    'text'                                  => 'This is plain text.')
            );
echo "<pre>";
print_r($result);
echo "</pre>";
exit;
            if( $result === false ) {
                // response wasn't even json
                echo 'didnt work';
            }
            else if( isset($result->status) && $result->status == 'error' ) {
                echo 'Error info: '.$result->status.', '.$result->code.', '.$result->name.', '.$result->error;
            } else {
                echo 'worked';
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
}
