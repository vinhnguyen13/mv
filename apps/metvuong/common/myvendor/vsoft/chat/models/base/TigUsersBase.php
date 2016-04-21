<?php

namespace vsoft\chat\models\base;

use Yii;

/**
 * This is the model class for table "{{%tig_users}}".
 *
 * @property string $uid
 * @property string $user_id
 * @property string $sha1_user_id
 * @property string $user_pw
 * @property string $acc_create_time
 * @property string $last_login
 * @property string $last_logout
 * @property integer $online_status
 * @property integer $failed_logins
 * @property integer $account_status
 *
 * @property TigNodes[] $tigNodes
 * @property TigPairs[] $tigPairs
 */
class TigUsersBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tig_users}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbChat');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'sha1_user_id'], 'required'],
            [['acc_create_time', 'last_login', 'last_logout'], 'safe'],
            [['online_status', 'failed_logins', 'account_status'], 'integer'],
            [['user_id'], 'string', 'max' => 2049],
            [['sha1_user_id'], 'string', 'max' => 128],
            [['user_pw'], 'string', 'max' => 255],
            [['sha1_user_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'user_id' => 'User ID',
            'sha1_user_id' => 'Sha1 User ID',
            'user_pw' => 'User Pw',
            'acc_create_time' => 'Acc Create Time',
            'last_login' => 'Last Login',
            'last_logout' => 'Last Logout',
            'online_status' => 'Online Status',
            'failed_logins' => 'Failed Logins',
            'account_status' => 'Account Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTigNodes()
    {
        return $this->hasMany(TigNodesBase::className(), ['uid' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTigPairs()
    {
        return $this->hasMany(TigPairsBase::className(), ['uid' => 'uid']);
    }
}
