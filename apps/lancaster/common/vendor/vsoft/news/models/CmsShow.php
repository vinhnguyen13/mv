<?php

namespace vsoft\news\models;

use dektrium\user\models\User;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Url;

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

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => 'Zelenin\yii\behaviors\Slug',
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
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_by',
                ],
                'value' => function ($event) {
                    return Yii::$app->user->id;
                },
            ],
            // BlameableBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['created_at', 'updated_at'], 'integer'],
            [['created_by', 'updated_by'], 'integer']
        ]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog()
    {
        return $this->hasOne(CmsCatalog::className(), ['id' => 'catalog_id']);
    }

    public function getUserName($IDuser=NULL)
    {
        if($IDuser!=NULL)
            $username=User::findOne($IDuser);
        else
            $username=User::findOne(Yii::$app->user->getId());
        return $username->username;
    }

    /**
     * @param null $fileName
     * @return string
     */
    public function getPathBanner($fileName = null)
    {
        return \Yii::getAlias('@store/news/show/').(!empty($fileName) ? $fileName: '');
    }

    /**
     * @param null $fileName
     * @return string
     */
    public function getUrlBanner($fileName = null)
    {
        return Url::to('/store/news/show/').(!empty($fileName) ? $fileName: '');
    }
}
