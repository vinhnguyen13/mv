<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "ec_balance".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $amount
 * @property string $amount_promotion
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Balance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ec_balance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'amount', 'amount_promotion', 'created_at', 'updated_at'], 'integer'],
            [['amount', 'amount_promotion'], 'required']
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
            'amount' => 'Amount',
            'amount_promotion' => 'Amount Promotion',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
	public function beforeSave($insert)
	{
	    if (parent::beforeSave($insert)) {
	        
	    	$this->updated_at = time();
	    	
	        return true;
	    } else {
	        return false;
	    }
	}
    
	public function afterSave ( $insert, $changedAttributes )
	{
		Yii::$app->db->schema->refreshTableSchema('ec_balance');
		
	    return parent::afterSave($insert, $changedAttributes);
	}
}
