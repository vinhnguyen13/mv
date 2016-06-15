<?php

namespace vsoft\ec\models;

use vsoft\ec\models\base\EcPackageBase;
use Yii;

/**
 * This is the model class for table "ec_package".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class EcPackage extends EcPackageBase
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ec_package';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at'], 'integer'],
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
            'updated_at' => Yii::t('ec', 'Updated At'),
        ];
    }
}
