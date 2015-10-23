<?php

namespace vsoft\express\models\base;

use Yii;

/**
 * This is the model class for table "lc_banner".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $url
 * @property string $ad_link
 * @property integer $height
 * @property integer $width
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class LcBannerBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lc_banner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['height', 'width', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 60],
            [['description'], 'string', 'max' => 255],
            [['url', 'ad_link'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('building', 'ID'),
            'name' => Yii::t('building', 'Name'),
            'description' => Yii::t('building', 'Description'),
            'url' => Yii::t('building', 'Url'),
            'ad_link' => Yii::t('building', 'Ad Link'),
            'height' => Yii::t('building', 'Height'),
            'width' => Yii::t('building', 'Width'),
            'created_at' => Yii::t('building', 'Created At'),
            'created_by' => Yii::t('building', 'Created By'),
            'updated_at' => Yii::t('building', 'Updated At'),
            'updated_by' => Yii::t('building', 'Updated By'),
        ];
    }
}
