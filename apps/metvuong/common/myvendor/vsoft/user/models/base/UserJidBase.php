<?php

namespace vsoft\user\models\base;

use Yii;

/**
 * This is the model class for table "user_jid".
 *
 * @property integer $user_id
 * @property string $username
 * @property integer $jid
 * @property string $jid_id
 *
 * @property User $user
 */
class UserJidBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_jid';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'jid_id'], 'integer'],
            [['username'], 'string', 'max' => 255],
            [['jid'], 'string', 'max' => 2049]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'username' => 'Username',
            'jid' => 'Jid',
            'jid_id' => 'Jid ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
