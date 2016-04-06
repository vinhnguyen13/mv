<?php

namespace vsoft\user\models\base;

use Yii;

/**
 * This is the model class for table "user_review".
 *
 * @property integer $user_id
 * @property integer $review_id
 * @property integer $rating
 * @property integer $type
 * @property string $description
 * @property integer $created_at
 */
class UserReview extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_review';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'review_id'], 'required'],
            [['user_id', 'review_id', 'rating', 'type', 'created_at'], 'integer'],
            [['name', 'username', 'description'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('review', 'User ID'),
            'name' => Yii::t('review', 'Name'),
            'review_id' => Yii::t('review', 'Review ID'),
            'rating' => Yii::t('review', 'Rating'),
            'type' => Yii::t('review', 'Type'),
            'description' => Yii::t('review', 'Description'),
            'created_at' => Yii::t('review', 'Created At'),
        ];
    }
}
