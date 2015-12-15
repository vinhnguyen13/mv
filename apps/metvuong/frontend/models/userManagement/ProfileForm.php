<?php
namespace frontend\models\userManagement;
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/14/2015
 * Time: 6:11 PM
 */
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
            'old_password' => Yii::t('user', 'Password'),
            'new_password' => Yii::t('user', 'Password'),
        ];
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            'request' => ['email'],
            'password'   => ['old_password', 'new_password'],
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            'emailTrim' => ['email', 'filter', 'filter' => 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern' => ['email', 'email'],
            'emailExist' => [
                'email',
                'exist',
                'targetClass' => Yii::$app->getModule('user')->modelMap['User'],
                'message' => Yii::t('user', 'There is no user with this email address'),
            ],
            'emailUnconfirmed' => [
                'email',
                function ($attribute) {
                    $this->user = $this->finder->findUserByEmail($this->email);
                    if ($this->user !== null && $this->module->enableConfirmation && !$this->user->getIsConfirmed()) {
                        $this->addError($attribute, Yii::t('user', 'You need to confirm your email address'));
                    }
                }
            ],
            'oldPasswordRequired' => ['old_password', 'required'],
            'oldPasswordLength' => ['old_password', 'string', 'min' => 6],
            'newPasswordRequired' => ['new_password', 'required'],
            'newPasswordLength' => ['new_password', 'string', 'min' => 6],
        ];
    }

    /**
     * Sends recovery message.
     *
     * @return bool
     */
    public function updateProfile()
    {
        if ($this->validate()) {
            /** @var Token $token */
            $token = Yii::createObject([
                'class'   => Token::className(),
                'user_id' => $this->user->id,
                'type'    => Token::TYPE_RECOVERY,
            ]);
            $token->save(false);
            $this->mailer->sendRecoveryMessage($this->user, $token);
            return Yii::t('user', 'An email has been sent with instructions for resetting your password');
        }

        return false;
    }

    /**
     * Resets user's password.
     *
     * @param Token $token
     *
     * @return bool
     */
    public function resetPassword(Token $token)
    {
        if (!$this->validate() || $token->user === null) {
            return false;
        }

        if ($token->user->resetPassword($this->password)) {
            Yii::$app->session->setFlash('success', Yii::t('user', 'Your password has been changed successfully.'));
            $token->delete();
            return Yii::t('user', 'Your password has been changed successfully.');
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'An error occurred and your password has not been changed. Please try again later.'));
            return Yii::t('user', 'An error occurred and your password has not been changed. Please try again later.');
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'profile-form';
    }
}