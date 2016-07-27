<?php
namespace frontend\models;
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/14/2015
 * Time: 6:11 PM
 */
use vsoft\ad\models\AdProduct;
use dektrium\user\helpers\Password;
use frontend\models\Profile;
use frontend\models\User;
use yii\base\Model;
use Yii;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class ProfileForm extends Model
{
    /** @var string */
    public $user_id;
    public $name;
    public $public_email;
    public $phone;
    public $mobile;
    public $address;
    public $avatar;
    public $created_at;
    public $bio;
    public $about;
    public $rating_point;
    public $rating_no;
    public $slug;
    public $first_name;
    public $last_name;
    public $owner;
    public $aliasname;

    /** @var string */
    public $old_password;
    public $new_password;

    /** @var User */
    protected $user;

    /**
     * @param array  $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email'    => Yii::t('user', 'Email'),
            'old_password' => Yii::t('user', 'Old Password'),
            'new_password' => Yii::t('user', 'New Password'),
            'mobile' => Yii::t('user', 'Mobile'),
            'public_email' => Yii::t('user', 'Email'),
        ];
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            'updateprofile' => ['user_id', 'name', 'public_email', 'phone', 'mobile', 'address', 'bio', 'rating_point', 'rating_no'],
            'password' => ['old_password', 'new_password'],
            'updateavatar' => ['avatar', 'created_at'],
            'updatebio' => ['bio'],
            'updatealias' => ['aliasname'],
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            'nameRequired' => ['name', 'required'],

            'emailTrim' => ['public_email', 'filter', 'filter' => 'trim'],
            'emailRequired' => ['public_email', 'required'],
            'emailPattern' => ['public_email', 'email'],
            'emailExist' => [
                'public_email',
                function($attribute){
                    $user = Yii::$app->user->identity;
                    $count_user = User::find()->where('email=:email',[':email'=>$this->public_email])->andWhere('email != :em',[':em' => $user->email])->count('email');
                    if($count_user > 0)
                        $this->addError($attribute, Yii::t('profile','Email already exists!'));
                },
            ],
//            'emailUnconfirmed' => [
//                'public_email',
//                function ($attribute) {
//                    $this->user = $this->finder->findUserByEmail($this->public_email);
//                    if ($this->user !== null && $this->module->enableConfirmation && !$this->user->getIsConfirmed()) {
//                        $this->addError($attribute, Yii::t('user', 'You need to confirm your email address'));
//                    }
//                }
//            ],

//            'phoneValidate' => ['phone', 'integer'],
            'mobileValidate' => ['mobile', 'integer'],
            'mobileValidateMinMax' => [['mobile'], 'string', 'min' => 7, 'max' => 11],
            'mobileRequired' => ['mobile', 'required'],

            'oldPasswordRequired' => ['old_password', 'required'],
            'oldPasswordLength' => ['old_password', 'string', 'min' => 6],
            'oldPasswordValidate' => [
                'old_password',
                function ($attribute) {
                    $user = User::findIdentity(Yii::$app->user->id);
                    if(!Password::validate($this->old_password, $user->password_hash)) {
                        $this->addError($attribute, Yii::t('user', 'Old password incorrect. '));
                    }
                }
            ],

            'newPasswordRequired' => ['new_password', 'required'],
            'newPasswordLength' => ['new_password', 'string', 'min' => 6],
            'newPasswordValidate' => [
                'new_password',
                function ($attribute) {
                    if($this->old_password == $this->new_password) {
                        $this->addError($attribute, Yii::t('user', 'New password like old password. Try again.'));
                    }
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'profile-form';
    }

    public function resetPass(){
        $user = User::findIdentity(Yii::$app->user->id);
        if (!$this->hasErrors()) {
            $result = $user->resetPassword($this->new_password);
            if($result){
                User::getDb()->createCommand()->update(User::tableName(), ['confirmed_at' => time()], 'id = :id', [':id' => Yii::$app->user->id])->execute();
                return $result;
            }
        }
        return false;
    }

    public function updateProfile(){
        $profile = Yii::$app->user->identity->profile;
        if(!empty($profile)) {
            $firstEmail = $profile->public_email;
            $profile->user_id = $this->user_id;
            $profile->name = $this->name;
//            $profile->public_email = $this->public_email;
            $profile->phone = $this->phone;
            $profile->mobile = $this->mobile;
            $profile->address = $this->address;
            $profile->bio = $this->bio;
            $profile->user->updateAttributes(['aliasname'=>$this->aliasname]);
            $profile->avatar = $this->avatar;
            if($profile->save(false)){
                if($firstEmail != $this->public_email) {
//                    $profile->user->email = $profile->public_email;
//                    $profile->user->save(false);
                    // gui den email cu~ xac thuc thay doi email moi
                    $mailer = new \common\components\Mailer();
                    $subject = "Metvuong.com - Xác nhận thay đổi email đăng nhập";
                    $view = ['html' => "@frontend/mail/vi-VN/change_email_user"];
                    $params['name'] = $profile->name;
                    $params['old_email'] = $firstEmail;
                    $params['new_email'] = $this->public_email;

                    // token
                    $new_token = new Token();
                    $new_token->user_id = Yii::$app->user->id;
                    $new_token->code = Yii::$app->security->generateRandomString();
                    $new_token->type = Token::TYPE_CHANGE_USER_EMAIL;
                    $new_token->created_at = time();
                    $new_token->save();
                    $params['link'] =  Url::to(['/member/change-email', 'id' => md5($new_token->user_id.$new_token->code), 'email' => $params['new_email'], 'code' => $new_token->code], true);

                    $result = $mailer->compose($view, $params)
                        ->setFrom(Yii::$app->params['noreplyEmail'])
                        ->setTo([$firstEmail])
                        ->setSubject($subject)
                        ->send();
                    if($result){
                        return 4444;
                    }
                }
                return 200;
            }
        }
        return 404;
    }

    public static function humanTiming($time)
    {
        $time = time() - $time; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            $timing = $text.(($numberOfUnits>1)?'s':'');
            return $numberOfUnits.' '.Yii::t('time', $timing);
        }
        return null;
    }

//    public function slugify($string, $replacement = '-', $lowercase = true, $transliterateOptions = null){
//        $slugifier = new Slugifier($string);
//        if ($transliterateOptions !== null) {
//            $slugifier->transliterateOptions = $transliterateOptions;
//        }
//        $slugifier->replacement = $replacement;
//        $slugifier->lowercase = $lowercase;
//        return $slugifier->getSlug();
//    }

    public function loadProfile($username, $scenario = 'updateprofile'){
        $profile = null;
        $user = User::find()->where('aliasname = :usrn', [':usrn' => $username])->one();
        if($user){
            $profile = $user->profile;
            $profile->avatar = $profile->getAvatarUrl();
        } else {
            if(StringHelper::startsWith(strtolower($username), 'mv')) {
                $id = str_replace('mv', '', strtolower($username));
                $product = AdProduct::findOne($id);
                if($product) {
                    Yii::$app->response->redirect($product->urlDetail());
                    Yii::$app->end();
                }
            }
            throw new NotFoundHttpException('Not Found');
        }
        $model = Yii::createObject([
            'class'    => ProfileForm::className(),
            'scenario' => $scenario,
        ]);

        $model->user_id = $profile->user_id;
        $model->name = $profile->name;
        $model->public_email = empty($profile->public_email) ? $user->email : $profile->public_email;
        $model->phone = $profile->phone;
        $model->mobile = $profile->mobile;
        $model->address = $profile->address;
        $model->avatar = $profile->avatar;
        $model->bio = $profile->bio;
        $model->rating_point = $profile->rating_point;
        $model->rating_no = $profile->rating_no;
        $model->created_at = Yii::$app->user->id;
        $model->user = $user;
        $model->aliasname = $user->aliasname;
        return $model;
    }

    public function updateAvatar($filename){
        $profile = Yii::$app->user->identity->profile;
        if(!empty($profile)) {
            $profile->avatar = $filename;
            return $profile->save(false);
        }
        return false;
    }

    public function getUser(){
        return !empty($this->user) ? $this->user : false;
    }

    public function compareToUpdate($data) {
    	$profile = Yii::$app->user->identity->profile;
    	
    	if(!empty($data['name']) && !$profile->name) {
    		$profile->name = $data['name'];
    	}
    	
    	if(!empty($data['phone']) && !$profile->phone) {
    		$profile->phone = $data['phone'];
    	}
    	
    	if(!empty($data['mobile']) && !$profile->mobile) {
    		$profile->mobile = $data['mobile'];
    	}
    	
    	if(!empty($data['email']) && !$profile->public_email) {
    		$profile->public_email = $data['email'];
    	}
    	
    	if(!empty($data['address']) && !$profile->address) {
    		$profile->address = $data['address'];
    	}
    	
    	if($profile->getDirtyAttributes()) {
    		$profile->save();
    	}
    }

}