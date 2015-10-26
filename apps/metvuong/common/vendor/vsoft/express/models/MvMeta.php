<?php

namespace vsoft\express\models;

use Yii;

/**
 * This is the model class for table "mv_meta".
 *
 * @property string $id
 * @property string $url
 * @property string $metadata
 */
class MvMeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mv_meta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['metadata'], 'string'],
            [['url'], 'string', 'max' => 255],
            [['url'], 'unique'],
            [['url'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('meta', 'ID'),
            'url' => Yii::t('meta', 'Url'),
            'metadata' => Yii::t('meta', 'Metadata'),
        ];
    }
}
