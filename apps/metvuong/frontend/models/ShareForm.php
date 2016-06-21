<?php
namespace frontend\models;

use yii\base\Model;
use Yii;

class ShareForm extends Model
{
    /** @var string */
    public $recipient_email;
    public $your_email;
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
            'share' => ['recipient_email', 'your_email', 'subject', 'content', 'address', 'detailUrl', 'domain', 'type', 'pid', 'uid', 'from_name','to_name',
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
        ];
    }

}