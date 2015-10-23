<?php
/**
 * Common Status Class
 * User: Nhut Tran
 * Date: 2015/10/02
 */

namespace vsoft\news\models;


use vsoft\news\Module;

class Status extends \funson86\cms\models\Status
{
    public static function labels($id = null)
    {
        $data = [
            self::STATUS_ACTIVE => Module::t('cms', 'Active'),
            self::STATUS_INACTIVE => Module::t('cms', 'Inactive'),
            self::STATUS_DELETED => Module::t('cms', 'Deleted'),
        ];

        if ($id !== null && isset($data[$id])) {
            return $data[$id];
        } else {
            return $data;
        }
    }
}
