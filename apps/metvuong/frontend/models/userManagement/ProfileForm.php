<?php
namespace frontend\models\userManagement;
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

class ProfileForm extends Model
{
    /** @var string */
    public $name;
    public $public_email;
    public $phone;
    public $mobile;
    public $address;
    public $avatar;
    public $created_at;
    public $bio;

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
            'updateprofile' => ['name', 'public_email', 'phone', 'mobile', 'address'],
            'password'   => ['old_password', 'new_password'],
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

            'phoneValidate' => ['phone', 'integer'],
            'mobileValidate' => ['mobile', 'integer'],

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
        $user = User::findIdentity(Yii::$app->user->id);
        $profile = $user->profile;
        if(!empty($profile)) {
            $profile->name = $this->name;
            $profile->public_email = $this->public_email;
            $profile->phone = $this->phone;
            $profile->mobile = $this->mobile;
            $profile->address = $this->address;
            return $profile->save();
        }
        return false;
    }

    public function loadProfile(){
        $user = User::findIdentity(Yii::$app->user->id);
        $profile = $user->profile;

        $model = Yii::createObject([
            'class'    => ProfileForm::className(),
            'scenario' => 'updateprofile',
        ]);

        $model->name = $profile->name;
        $model->public_email = $profile->public_email;
        $model->phone = $profile->phone;
        $model->mobile = $profile->mobile;
        $model->address = $profile->address;
        $model->avatar = $profile->avatar;
        $model->bio = $profile->bio;
        $model->created_at = $user->created_at;

        return $model;
    }

    public function updateAvatar($filename){
        $user = User::findIdentity(Yii::$app->user->id);
        $profile = $user->profile;
        if(!empty($profile)) {
            $profile->avatar = $filename;
            return $profile->save();
        }
        return false;
    }

}