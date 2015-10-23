<?php

namespace vsoft\news\models;

use funson86\cms\Module;
use Yii;

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

}
