<?php

namespace vsoft\news\models;

use Yii;

/**
 * This is the model class for table "cms_show".
 *
 * @property integer $id
 * @property integer $catalog_id
 * @property string $title
 * @property string $slug
 * @property string $surname
 * @property string $brief
 * @property string $content
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 * @property string $banner
 * @property string $template_show
 * @property string $author
 * @property integer $click
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property CmsCatalog $catalog
 */
class CmsShow extends \funson86\cms\models\CmsShow
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_show';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(),[
            [['created_by', 'updated_by'], 'integer']
        ]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog1()
    {
        return $this->hasOne(CmsCatalog::className(), ['id' => 'catalog_id']);
    }

//    public function beforeSave($insert)
//    {
//        echo "<pre>";
//        print_r('12345');
//        echo "<pre>";
//        exit();
//        if(parent::beforeSave($insert)) {
//
//            if ($this->isNewRecord || $this->created_by < 1)
//                $this->created_by = Yii::$app->user->id;
//            $this->updated_by = Yii::$app->user->id;
//            return true;
//        } else
//            return false;
//    }
}
