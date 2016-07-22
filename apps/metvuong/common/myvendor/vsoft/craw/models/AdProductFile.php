<?php

namespace vsoft\craw\models;

use Yii;

/**
 * This is the model class for table "ad_product_file".
 *
 * @property string $file
 * @property string $path
 * @property integer $is_import
 * @property integer $imported_at
 * @property integer $product_tool_id
 * @property integer $is_copy
 * @property integer $copied_at
 * @property integer $product_main_id
 * @property string $vendor_link
 * @property integer $created_at
 * @property integer $updated_at
 */
class AdProductFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_product_file';
    }

    public static function getDb()
    {
        return Yii::$app->get('dbCraw');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file'], 'required'],
            [['is_import', 'imported_at', 'product_tool_id', 'is_copy', 'copied_at', 'product_main_id', 'created_at', 'updated_at'], 'integer'],
            [['file'], 'string', 'max' => 32],
            [['path'], 'string', 'max' => 255],
            [['vendor_link'], 'string', 'max' => 500],
            [['file'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file' => Yii::t('file', 'File'),
            'path' => Yii::t('file', 'Path'),
            'is_import' => Yii::t('file', 'Is Import'),
            'imported_at' => Yii::t('file', 'Imported At'),
            'product_tool_id' => Yii::t('file', 'Product Tool ID'),
            'is_copy' => Yii::t('file', 'Is Copy'),
            'copied_at' => Yii::t('file', 'Copied At'),
            'product_main_id' => Yii::t('file', 'Product Main ID'),
            'vendor_link' => Yii::t('file', 'Vendor Link'),
            'created_at' => Yii::t('file', 'Created At'),
            'updated_at' => Yii::t('file', 'Updated At'),
        ];
    }

    public function beforeSave($insert) {
        $now = time();
        if($insert) {
            $this->created_at = $this->created_at ? $this->created_at : $now;
        } else {
            $this->updated_at = $now;
        }
        return parent::beforeSave($insert);
    }

    public static function checkFileExists($product_id)
    {
        if($product_id){
            $count = AdProductFile::find()->where(['file' => $product_id])->count();
            if($count > 0)
                return true;
        }
        return false;
    }
}
