<?php

namespace vsoft\express\models;

use vsoft\express\models\base\MetadataBase;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "lc_meta".
 *
 * @property string $id
 * @property string $url
 * @property string $metadata
 */
class Metadata extends MetadataBase
{
    public function rules()
    {
        return [
            [['metadata'], 'string'],
            [['url'], 'string', 'max' => 255],
            [['url'], 'unique'],
            [['url'], 'required'],
        ];
    }

}
