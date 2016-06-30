<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ec_statistic_view".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $start_at
 * @property integer $end_at
 *
 * @property User $user
 */
class EcStatisticView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ec_statistic_view';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'start_at', 'end_at'], 'required'],
            [['user_id', 'start_at', 'end_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'start_at' => 'Start At',
            'end_at' => 'End At',
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
