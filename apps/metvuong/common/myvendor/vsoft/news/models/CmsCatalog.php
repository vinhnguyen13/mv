<?php

namespace vsoft\news\models;

use funson86\cms\Module;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class CmsCatalog extends \funson86\cms\models\CmsCatalog
{
    public static function getCatalogPageTypeLabels($id = null)
    {
        $data = [
            self::PAGE_TYPE_LIST => Module::t('cms', 'LIST'),
            self::PAGE_TYPE_PAGE => Module::t('cms', 'PAGE'),
        ];

        if ($id !== null && isset($data[$id])) {
            return $data[$id];
        } else {
            return $data;
        }
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => 'common\components\Slug',
                'slugAttribute' => 'slug',
                'attribute' => 'title',
                // optional params
                'ensureUnique' => true,
                'replacement' => '-',
                'lowercase' => true,
                'immutable' => false,
                // If intl extension is enabled, see http://userguide.icu-project.org/transforms/general.
                'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
            ],
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => new Expression('UNIX_TIMESTAMP()'),
            ],
        ];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['slug'], 'string', 'max' => 255]
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'slug' => Module::t('cms', 'Slug'),
        ]);
    }
}
