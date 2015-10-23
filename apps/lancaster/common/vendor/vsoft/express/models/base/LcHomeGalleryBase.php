<?php

namespace vsoft\express\models\base;

use Yii;

/**
 * This is the model class for table "lc_home_gallery".
 *
 * @property integer $id
 * @property integer $gallery_id
 * @property string $code
 * @property integer $status
 */
class LcHomeGalleryBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lc_home_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gallery_id', 'status'], 'integer'],
            [['code'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gallery_id' => 'Gallery ID',
            'code' => 'Code',
            'status' => 'Status',
        ];
    }
}
