<?php
namespace frontend\models;

use yii\base\Model;
use Yii;

class ShareForm extends Model
{
    /** @var string */
    public $recipient_email;
    public $your_email;
    public $content;
    public $address;

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            'share' => ['recipient_email', 'your_email', 'content', 'address'],
        ];
    }

    public function rules() {
        return [
            [['recipient_email', 'your_email'], 'required'],
            [['recipient_email', 'your_email'], 'email'],
            [['address', 'recipient_email', 'your_email', 'content'], 'string', 'max' => 255],
        ];
    }

}