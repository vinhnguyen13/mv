<?php
namespace frontend\models;
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/14/2015
 * Time: 6:11 PM
 */
use dektrium\user\helpers\Password;
use frontend\models\Profile;
use frontend\models\User;
use yii\base\Model;
use Yii;
use Zelenin\Slugifier\Slugifier;
use Zelenin\yii\behaviors\Slug;

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
    public $activity;
    public $experience;
    public $slug;

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
        ];
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            'updateprofile' => ['user_id', 'name', 'public_email', 'phone', 'mobile', 'address', 'about', 'activity', 'experience', 'slug'],
            'password' => ['old_password', 'new_password'],
            'updateavatar' => ['avatar', 'created_at', 'bio'],
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            'emailTrim' => ['public_email', 'filter', 'filter' => 'trim'],
            'emailRequired' => ['public_email', 'required'],
            'emailPattern' => ['public_email', 'email'],
//            'emailExist' => [
//                'public_email',
//                'exist',
//                'targetClass' => Yii::$app->getModule('user')->modelMap['User'],
//                'message' => Yii::t('user', 'There is no user with this email address'),
//            ],
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
//            'mobileValidate' => ['mobile', 'integer'],

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
                        $this->addError($attribute, Yii::t('user', 'New password is the same old password. '));
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
            return $user->resetPassword($this->new_password);
        }
        return false;
    }

    public function updateProfile(){
        $profile = Yii::$app->user->identity->profile;
        if(!empty($profile)) {
            $profile->user_id = $this->user_id;
            $profile->name = $this->name;
            $profile->public_email = $this->public_email;
            $profile->phone = $this->phone;
            $profile->mobile = $this->mobile;
            $profile->address = $this->address;
            $profile->about = $this->about;
            $profile->activity = $this->activity;
            $profile->experience = $this->experience;
            if(!empty($profile->slug)) {
                $user = User::findIdentity(Yii::$app->user->id);
                $profile->slug = $user->username;
            }
            return $profile->save();
        }
        return false;
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

    public function loadProfile($username){
        $profile = null;
        $user = User::find()->where('username = :usrn', [':usrn' => $username])->one();
        if($user){
            $profile = $user->profile;
        }
        $model = Yii::createObject([
            'class'    => ProfileForm::className(),
            'scenario' => 'updateprofile',
        ]);

        $model->user_id = $profile->user_id;
        $model->name = $profile->name;
        $model->public_email = $profile->public_email;
        $model->phone = $profile->phone;
        $model->mobile = $profile->mobile;
        $model->address = $profile->address;
        $model->avatar = $profile->avatar;
        $model->bio = $profile->bio;
        $model->created_at = Yii::$app->user->id;
        $model->about = $profile->about;
        $model->activity = $profile->activity;
        $model->experience = $profile->experience;
        $model->slug = $profile->slug;
        $model->user = $user;
        return $model;
    }

    public function updateAvatar($filename){
        $profile = Yii::$app->user->identity->profile;
        if(!empty($profile)) {
            $profile->avatar = $filename;
            return $profile->save();
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