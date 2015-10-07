<?php

namespace vsoft\news\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "banner".
 *
 * @property integer $id
 * @property string $name
 * @property integer $priority
 * @property string $image
 * @property string $url
 * @property string $description
 * @property string $keyword
 * @property string $alt_text
 * @property string $additional_html
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Banner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banner';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'keyword',
            ],
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => new Expression('UNIX_TIMESTAMP()'),
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_by',
                ],
                'value' => function ($event) {
                    return Yii::$app->user->id;
                },
            ],
            // BlameableBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['priority', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'image', 'url', 'description', 'keyword', 'alt_text', 'additional_html'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('banner', 'ID'),
            'name' => Yii::t('banner', 'Name'),
            'priority' => Yii::t('banner', 'Priority'),
            'image' => Yii::t('banner', 'Image'),
            'url' => Yii::t('banner', 'Url'),
            'description' => Yii::t('banner', 'Description'),
            'keyword' => Yii::t('banner', 'Keyword'),
            'alt_text' => Yii::t('banner', 'Alt Text'),
            'additional_html' => Yii::t('banner', 'Additional Html'),
            'created_at' => Yii::t('banner', 'Created At'),
            'updated_at' => Yii::t('banner', 'Updated At'),
            'created_by' => Yii::t('banner', 'Created By'),
            'updated_by' => Yii::t('banner', 'Updated By'),
        ];
    }
}
