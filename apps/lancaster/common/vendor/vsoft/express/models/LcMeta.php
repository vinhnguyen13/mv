<?php

namespace vsoft\express\models;

use vsoft\express\models\base\LcMetaBase;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "lc_meta".
 *
 * @property string $id
 * @property string $url
 * @property string $metadata
 */
class LcMeta extends LcMetaBase
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
