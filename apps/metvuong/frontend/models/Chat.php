<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 10:14 AM
 */

namespace frontend\models;
use common\components\Util;
use vsoft\chat\models\TigUsers;
use vsoft\user\models\UserJid;
use Yii;
use yii\base\Component;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class Chat extends Component
{
    const DOMAIN = 'metvuong.com';

    public static function find()
    {
        return Yii::createObject(Chat::className());
    }

    public function getTigUser(){
        if (!Yii::$app->user->isGuest) {
            $user_id = $this->getJid(Yii::$app->user->identity->username);
            if(($tigUser = TigUsers::findOne(['user_id'=>$user_id])) === null){
                $tigUser = new TigUsers();
                $tigUser->user_id = $user_id;
                $tigUser->sha1_user_id = sha1($user_id);
                $tigUser->user_pw = $this->generateKey();
                $tigUser->save();
            }
            if(($userJid = UserJid::findOne(['username'=>Yii::$app->user->identity->username])) === null){
                $jid_id = Yii::$app->dbChat->createCommand('SELECT jid_id FROM tig_ma_jids tmj WHERE jid=:jid')->bindValues([':jid' => $tigUser->user_id])->queryOne();
                if(!empty($jid_id['jid_id'])){
                    $userJid = new UserJid();
                    $userJid->user_id = Yii::$app->user->identity->id;
                    $userJid->username = Yii::$app->user->identity->username;
                    $userJid->jid = $tigUser->user_id;
                    $userJid->jid_id = $jid_id['jid_id'];
                    $userJid->validate();
                    $userJid->save();
                }
            }
            return $tigUser;
        }
    }

    public function getJid($username){
        return $username.'@'.self::DOMAIN;
    }

    public function getUsername($jid){
        return str_replace('@'.self::DOMAIN, '', $jid);
    }

    public function getKey(){
        $tigUser = $this->getTigUser();
        return $tigUser->user_pw;
    }

    public function getDomain(){
        return self::DOMAIN;
    }

    public function generateKey(){
        if (!Yii::$app->user->isGuest) {
            $key = Yii::$app->user->identity->getAuthKey();
            return md5($key);
        }
        return false;
    }

    public function searchUserFromConversation($userOnlined, $userFind)
    {
        if(strlen(trim($userFind)) <= 0){
            return false;
        }
        $dbNameChat = Util::me()->getDsnAttribute('dbname', Yii::$app->get('dbChat')->dsn);
        $dbNamePri = Util::me()->getDsnAttribute('dbname', Yii::$app->get('db')->dsn);
        $jidUserOnlined = $this->getJid($userOnlined);

        $sqlFindJidFromUserFind = "SELECT u.jid_id FROM `".$dbNamePri."`.`profile` p, `".$dbNamePri."`.`user_jid` u WHERE `name` LIKE '%".$userFind."%' AND p.user_id=u.user_id";
        $sqlFindJidFromUserOnline = "SELECT jid_id FROM `".$dbNamePri."`.`user_jid` WHERE jid = '".$jidUserOnlined."'";
        $sql1 = "SELECT tbl.* FROM (SELECT IF(direction = 0, owner_id, buddy_id) AS withuser FROM `$dbNameChat`.`tig_ma_msgs` tmm WHERE (owner_id IN (".$sqlFindJidFromUserFind.") AND buddy_id IN (".$sqlFindJidFromUserOnline.")) OR (buddy_id IN (".$sqlFindJidFromUserFind.") AND owner_id IN (".$sqlFindJidFromUserOnline."))" .
            " ORDER BY ts DESC) tbl GROUP BY withuser ";
        $existConversation = Yii::$app->get('dbChat')->createCommand($sql1)->queryAll();
        echo "<pre>";
        print_r($existConversation);
        echo "</pre>";
        exit;
        if(!empty($existConversation)){
            $sql = "SELECT username, name FROM `".$dbNamePri."`.`profile` p INNER JOIN `user` u ON p.user_id=u.id WHERE `name` LIKE '%".$userFind."%'";
            $return = Yii::$app->get('db')->createCommand($sql)->queryAll();
            if(!empty($return)){
                return $return;
            }
        }
        return false;
    }

}