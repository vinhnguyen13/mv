<?php

namespace vsoft\news\models;

use dektrium\user\models\User;
use lajax\translatemanager\models\Language;
use vsoft\news\Module;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Url;
use yii\image\drivers\Image;

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
    const THUMB400x0 = 'thumb400x0_';
    const HOT_NEWS_YES = 1;
    const HOT_NEWS_NO = 0;

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
            [['created_at', 'updated_at', 'created_by', 'updated_by', 'hot_news'], 'integer'],
            [['language_id'], 'string']
        ]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog()
    {
        return $this->hasOne(CmsCatalog::className(), ['id' => 'catalog_id']);
    }

    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['language_id' => 'language_id']);
    }

    public function getUserName($IDuser=NULL)
    {
        if($IDuser!=NULL)
            $username=User::findOne($IDuser);
        else
            $username=User::findOne(Yii::$app->user->getId());
        return $username->username;
    }

    public static function getHotNews($id = NULL){
        $data = [
            self::HOT_NEWS_YES => Module::t('cms', 'Yes'),
            self::HOT_NEWS_NO => Module::t('cms', 'No'),
        ];

        if ($id !== null && isset($data[$id])) {
            return $data[$id];
        } else {
            return $data;
        }
    }

    public static function getShowForHomepage(){
//        $limit = Yii::$app->mobileDetect->isMobile() ? 3 : 4;
        $query = CmsShow::find()->select(['cms_show.id','cms_show.banner','cms_show.title','cms_show.slug','cms_show.brief', 'cms_show.created_at','cms_show.catalog_id', 'cms_catalog.title as cat_title', 'cms_catalog.slug as cat_slug'])
            ->join('inner join', CmsCatalog::tableName(), 'cms_show.catalog_id = cms_catalog.id')
            ->where('cms_show.catalog_id = :id', [':id' => Yii::$app->params['homepageCatID']])
            ->andWhere('cms_show.status = :status', [':status' => Status::STATUS_ACTIVE])
            ->andWhere('cms_catalog.status = :status', [':status' => Status::STATUS_ACTIVE])
            ->asArray()->orderBy(['cms_show.created_at' => SORT_DESC])->limit(4)->all();
        return $query;
    }

    public static function getShowForMetvuong(){
        $query = CmsShow::find()->select(['cms_show.id','cms_show.banner','cms_show.title','cms_show.slug','cms_show.brief', 'cms_show.created_at','cms_show.catalog_id', 'cms_catalog.title as cat_title', 'cms_catalog.slug as cat_slug'])
            ->join('inner join', CmsCatalog::tableName(), 'cms_show.catalog_id = cms_catalog.id')
            ->where('cms_catalog.title = :title', [':title' => 'Metvuong'])
            ->andWhere('cms_show.status = :status', [':status' => Status::STATUS_ACTIVE])
            ->andWhere('cms_catalog.status = :status', [':status' => Status::STATUS_ACTIVE])
            ->asArray()->limit(3)->all();
        return $query;
    }

    public static function getLatestNews($limit = 100){
//        $newsCatID = isset(Yii::$app->params["newsCatID"]) ? Yii::$app->params["newsCatID"] : 0;
//        $homepageCatID = isset(Yii::$app->params["homepageCatID"]) ? Yii::$app->params["homepageCatID"] : 0;
//        $metvuongCatID = isset(Yii::$app->params["metvuongCatID"]) ? Yii::$app->params["metvuongCatID"] : 0;
        $newsDb = CmsShow::getDb();
        $news = $newsDb->cache(function($newsDb) use($limit){
            return CmsShow::find()->select(['cms_show.id','cms_show.banner','cms_show.title','cms_show.slug','cms_show.brief', 'cms_show.created_at','cms_show.catalog_id', 'cms_catalog.title as cat_title', 'cms_catalog.slug as cat_slug'])
                ->join('inner join', CmsCatalog::tableName(), 'cms_show.catalog_id = cms_catalog.id')
                ->where('cms_show.status = :status', [':status' => Status::STATUS_ACTIVE])
                ->andWhere('cms_catalog.status = :status', [':status' => Status::STATUS_ACTIVE])
                ->andWhere(['IN', 'cms_show.language_id', [Yii::$app->language]])
                ->andWhere(['NOT IN', 'cms_show.catalog_id', [1]])
                ->asArray()->orderBy('cms_show.created_at DESC')->limit($limit)->all();
        });
        return $news;
    }

    public static function getBanner($banner){
        $imgPath = Url::to("/images/default-ads.jpg", true);
        if($banner) {
            $checkThumbFile = file_exists(Yii::getAlias('@store')."/news/show/".CmsShow::THUMB400x0.$banner);
            if($checkThumbFile)
                $imgPath = Url::to('/store/news/show/'.CmsShow::THUMB400x0.$banner, true);
            else {
                $checkFile = file_exists(Yii::getAlias('@store')."/news/show/".$banner);
                if($checkFile)
                    $imgPath = Url::to('/store/news/show/'.$banner, true);
            }

        }
        return $imgPath;
    }

    public static function saveThumbnail($filePath, $size){
        $resource = \Yii::$app->image->load($filePath);
        if($resource) {
            $path = pathinfo($filePath);
            $thumbPath = $path["dirname"]."/". CmsShow::THUMB400x0 . $path["basename"];
            $resizingConstraints = ($resource->width > $resource->height) ? Image::HEIGHT : Image::WIDTH;
            $resource->resize($size[0], $size[1], $resizingConstraints)->crop($size[0], $size[1])->save($thumbPath);
        }
    }

}
