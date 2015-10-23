<?php

namespace vsoft\news\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "lc_meta".
 *
 * @property string $id
 * @property string $url
 * @property string $metadata
 */
class LcMeta extends ActiveRecord
{
    public static function tableName()
    {
        return 'lc_meta';
    }

    public function rules()
    {
        return [
            [['metadata'], 'string'],
            [['url'], 'string', 'max' => 255],
            [['url'], 'unique'],
            [['url'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('meta', 'ID'),
            'url' => Yii::t('meta', 'Url'),
            'metadata' => Yii::t('meta', 'Metadata'),
        ];
    }

}
