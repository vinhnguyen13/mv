<?php

namespace vsoft\express\models\base;

use Yii;

/**
 * This is the model class for table "lc_meta".
 *
 * @property string $id
 * @property string $url
 * @property string $metadata
 */
class LcMetaBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lc_meta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['metadata'], 'string'],
            [['url'], 'string', 'max' => 255],
            [['url'], 'unique']
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
