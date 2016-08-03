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
class MailChimp extends Component
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
            $Mailchimp = new Mailchimp($api_key);
            $result = $Mailchimp->campaigns->create('regular',
                array('list_id'               => 'my_list_id',
                    'subject'                       => 'This is a test subject',
                    'from_email'                        => 'test@test.com',
                    'from_name'                         => 'From Name'),
                array('html'                    => '<div>test html email</div>',
                    'text'                                  => 'This is plain text.')
            );

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
            return false;
        }
    }
}
