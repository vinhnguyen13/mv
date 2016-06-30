<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property string  $name
 * @property string  $public_email
 * @property string  $gravatar_email
 * @property string  $gravatar_id
 * @property string  $location
 * @property string  $website
 * @property string  $bio
 * @property User    $user
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class Profile extends ActiveRecord
{
    /** @var \dektrium\user\Module */
    protected $module;

    /** @inheritdoc */
    public function init()
    {
        $this->module = Yii::$app->getModule('user');
    }

    /** @inheritdoc */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'bioString' => ['bio', 'string'],
            'publicEmailPattern' => ['public_email', 'email'],
            'gravatarEmailPattern' => ['gravatar_email', 'email'],
            'websiteUrl' => ['website', 'url'],
            'nameLength' => ['name', 'string', 'max' => 255],
            'publicEmailLength' => ['public_email', 'string', 'max' => 255],
            'gravatarEmailLength' => ['gravatar_email', 'string', 'max' => 255],
            'locationLength' => ['location', 'string', 'max' => 255],
            'websiteLength' => ['website', 'string', 'max' => 255],
            'address' => ['address', 'string', 'max' => 255],
            'rating_point' => ['rating_point', 'integer'],
            'rating_no' => ['rating_no', 'integer'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'name'           => Yii::t('user', 'Name'),
            'public_email'   => Yii::t('user', 'Email (public)'),
            'gravatar_email' => Yii::t('user', 'Gravatar email'),
            'location'       => Yii::t('user', 'Location'),
            'website'        => Yii::t('user', 'Website'),
            'bio'            => Yii::t('user', 'Bio'),
            'address'            => Yii::t('user', 'Address')
        ];
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isAttributeChanged('gravatar_email')) {
                $this->setAttribute('gravatar_id', md5(strtolower($this->getAttribute('gravatar_email'))));
            }

            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne($this->module->modelMap['User'], ['id' => 'user_id']);
    }

    public function getAvatarUrl()
    {
        $avatar = Url::to( '/images/default-avatar.jpg');
        if($this->avatar) {
            $pathinfo = pathinfo($this->avatar);
            $filePath = Yii::getAlias('@store').'/avatar/' . $pathinfo['filename'] . '.thumb.' . $pathinfo['extension'];
            if(file_exists($filePath)){
                $avatar = Url::to('/store/avatar/' . $pathinfo['filename'] . '.thumb.' . $pathinfo['extension']);
            }
        } 
        return $avatar;
    }

    public function getDisplayName()
    {
        return !empty($this->name) ? $this->name : $this->user->email;
    }

    public static function get_facebook_user_avatar($fbId, $uid){
        // fix get link SSL
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $img = file_get_contents("https://graph.facebook.com/" . $fbId . "/picture?type=large", false, stream_context_create($arrContextOptions));
        $dir = \Yii::getAlias('@store') . "/avatar";
        $orginal = "u_". $uid. "_" . $fbId . ".jpg";
        $thumbnail = "u_". $uid. "_" . $fbId . ".thumb.jpg";
        $orginalPath = $dir. "/" . $orginal;
        $thumbnailPath = $dir. "/". $thumbnail;
        $org_result = file_put_contents($orginalPath, $img);
        $thumb_result = file_put_contents($thumbnailPath, $img);
        if($org_result > 0 && $thumb_result > 0){
            return [$orginal, $thumbnail];
        }
        return null;
    }

    static function getUrlContent($url)
    {
        $agent = "Mozilla/5.0 (X11; U; Linux i686; en-US) " .
            "AppleWebKit/532.4 (KHTML, like Gecko) " .
            "Chrome/4.0.233.0 Safari/532.4";
        $referer = "http://www.google.com/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpcode >= 200 && $httpcode < 300) ? $data : null;
    }
}
