<?php
namespace frontend\models;

use yii\base\Model;
use Yii;
use vsoft\express\models\SysEmail;
use yii\helpers\Url;

class ShareForm extends Model
{
    /** @var string */
    public $recipient_email;
    public $your_email;
    public $mobile;
    public $subject;
    public $content;
    public $address;
    public $detailUrl;
    public $domain;
    public $type;
    public $pid;
    public $uid;
    public $from_name;
    public $to_name;

    public $category;
    public $area;
    public $room_no;
    public $toilet_no;
    public $price;
    public $imageUrl;


    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            'share' => ['recipient_email', 'your_email', 'mobile', 'subject', 'content', 'address', 'detailUrl', 'domain', 'type', 'pid', 'uid', 'from_name','to_name',
                'category', 'area', 'room_no', 'toilet_no', 'price', 'imageUrl'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'recipient_email'    => Yii::t('share', 'Recipient email'),
            'your_email' => Yii::t('share', 'Your email'),
        ];
    }

    public function rules() {
        return [
            [['recipient_email', 'your_email'], 'required'],
            [['recipient_email', 'your_email'], 'email', 'message' => Yii::t('share', 'Recipient email is not a valid email address.')],
            [['recipient_email', 'your_email'], 'string', 'max' => 255],
            [['address', 'content', 'detailUrl', 'domain', 'subject', 'type', 'pid', 'uid', 'from_name',
                'to_name','category', 'area', 'room_no', 'toilet_no', 'price', 'imageUrl'], 'string'],
            'mobileValidate' => ['mobile', 'integer'],
            'mobileValidateMinMax' => [['mobile'], 'string', 'min' => 7, 'max' => 11],
        ];
    }

    public function send($post) {
        $this->load($post);
        $this->validate();
        $profile_url = null;
        $token_url = null;
        if($this->type == "contact") {
            $this->subject = "Có Người Muốn liên hệ {$this->category} của bạn ở địa chỉ: {$this->address}";
        }elseif($this->type == "share") {
            $this->subject = "Có Người Muốn chia sẻ {$this->category} ở địa chỉ: {$this->address}";
        }
        if (!$this->hasErrors()) {
            $data = $this->attributes;
            if($this->type == "contact") {
                $uid = $this->uid;
                if($uid > 0) {
                    $user = Yii::$app->db->cache(function () use ($uid) {
                        return User::findOne($uid);
                    });
                    if (!empty($user)) {
                        $profile_url = Url::to(['dashboard/ad', 'username' => $user->username], true);
                        if(!empty($user->profile->public_email) && $user->email == $this->recipient_email) {
                            $token = new Token();
                            $token->user_id = $this->uid;
                            $token->code = Yii::$app->security->generateRandomString();
                            $token->type = Token::TYPE_CRAWL_USER_EMAIL;
                            $token->created_at = time();
                            if ($token->save())
                                $token_url = $token->url;
                        }
                    }
                }
                $typeSendMail = SysEmail::OBJECT_TYPE_CONTACT;
                $to_name = !empty($this->from_name) ? $this->from_name : $this->recipient_email;
                $view = ['html' => "@frontend/mail/vi-VN/product_contact"];
                $params = ['contact' => $data, 'profile_url' => $profile_url, 'token_url' => $token_url];
            }elseif($this->type == "share") {
                $typeSendMail = SysEmail::OBJECT_TYPE_SHARE;
                $to_name = $this->recipient_email;
                $view = ['html' => "@frontend/mail/vi-VN/product_share"];
                $params = ['contact' => $data];
            }
            $mailer = new \common\components\Mailer();
            $result = $mailer->compose($view, $params)
                ->setFrom(Yii::$app->params['noreplyEmail'])
                ->setTo([trim($this->recipient_email)])
                ->setSubject($this->subject)
                ->send();
            if($result){
                Tracking::find()->saveEmailLog([
                    'from_name'=>!empty($this->from_name) ? $this->from_name : $this->your_email,
                    'from_email'=>trim($this->your_email),
                    'to_name'=>$to_name,
                    'to_email'=>trim($this->recipient_email),
                    'object_id'=>$this->pid,
                    'object_type'=>$typeSendMail,
                    'subject'=>trim($this->subject),
                    'content'=>trim($this->content),
                    'params'=>$data
                ]);
                return ['status' => 200, 'result' => $result];
            }
        } else {
            return ['status' => 404, 'parameters' => $this->errors];
        }
    }

}