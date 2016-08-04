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
    public $MailChimp;
    public function __construct(){
        $api_key = '1f07b702500f2668d602a127acfa9b1a-us13';
        if (!class_exists('Mailchimp')) {
            require(Yii::getAlias('@common').'/myvendor/mailchimp-api-php/src/Mailchimp.php');
        }
        $this->MailChimp = new \Mailchimp($api_key, [
//                'debug'=>true
        ]);
    }
    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function send()
    {
        try {
            $cid = '4ed11c996a';
            $list_id = 'c9e01b7cfb';
            $email = 'contact@metvuong.com';
            $merge_vars = array();

            $result = $this->testMail();
            echo "<pre>";
            print_r($result);
            echo "</pre>";
            exit;

        } catch (\Exception $ex) {
            return $ex;
        }
    }
    private function testMail(){
        $campaigns = $this->MailChimp->call('campaigns/list', array(
            'filters'       => array(),
            'start'         => 0,
            'limit'         => 10,
            'sort_field'    => 'create_time',
            'sort_dir'      => 'DESC',
        ));
        /*echo "<pre>";
        print_r($campaigns);
        echo "</pre>";
        exit;*/

        $cid = '01c10b52fc';
//        $cid = 'aa535d8f21';
//        $cid = '4ed11c996a';
        $list_id = 'c9e01b7cfb';
        return $this->MailChimp->call("campaigns/template-content", array(
                'cid' => $cid,
            )
        );

    }
    /**
     * add subscribe
     */
    private function lists_subscribe(){
        $list_id = 'c9e01b7cfb';
        return $this->MailChimp->call('lists/subscribe', array(
            'id'                => $list_id,
            'email'             => array('email'=>'lenh.quach@trungthuygroup.vn'),
            'merge_vars'        => array('FNAME'=>'Lenh', 'LNAME'=>'Quach Tuan'),
            'double_optin'      => false,
            'update_existing'   => true,
            'replace_interests' => false,
            'send_welcome'      => false,
        ));
    }

    private function lists_members(){
        $list_id = 'c9e01b7cfb';
        return $this->MailChimp->call("lists/subscribe", [
            'id' => $list_id,
            'email' => array('email'=>'vinh@dwm.vn'),
            'merge_vars' => array(),
            'email_type' => null,
            'double_optin' => false,
            'update_existing' => false,
            'replace_interests' => false,
            'send_welcome' => false,
        ]);
    }


    private function campaigns_list(){
        return $this->MailChimp->call('campaigns/list', array(
            'filters'       => array(),
            'start'         => 0,
            'limit'         => 10,
            'sort_field'    => 'create_time',
            'sort_dir'      => 'DESC',
        ));
    }

    private function campaigns_send(){
        return $this->MailChimp->call('campaigns/send', array(
            'cid'                => '4ed11c996a',
        ));
    }

    private function campaigns_send_test(){
        return $this->MailChimp->call('campaigns/send-test', array(
            'cid'                => '4ed11c996a',
            'test_emails'        => array('email'=>'quangvinhit2007@gmail.com'),
            'send_type'          => 'html',
        ));
    }

    private function campaigns_template_content(){
        return $this->MailChimp->call('campaigns/template-content', array(
            'cid'                => 'aa535d8f21',
        ));
    }

    private function campaigns_create(){
        $list_id = 'c9e01b7cfb';
        return $this->MailChimp->call("campaigns/create", array(
                'type' => 'regular',
                'options' => array(
                    'list_id' => $list_id,
                    'subject' => 'Test Campaign '.date('m/d/y g:ia'),
                    'from_email' => 'email@hotmail.com',
                    'from_name' => 'Test Sender',
                    'to_name' => 'Test Recipient',
                    'template_id' => '123456',
                    'title' => 'example title'
                ),
                'content' => array(
                    'text' => 'example text'
                )
            )
        );
    }


}
