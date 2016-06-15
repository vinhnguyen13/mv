<?php

namespace vsoft\ec\models\base;

use Yii;

/**
 * This is the model class for table "ec_payment_method".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 */
class EcPaymentMethodBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ec_payment_method';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('ec', 'ID'),
            'title' => Yii::t('ec', 'Title'),
            'description' => Yii::t('ec', 'Description'),
            'status' => Yii::t('ec', 'Status'),
            'created_at' => Yii::t('ec', 'Created At'),
        ];
    }
}
