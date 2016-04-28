<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ad_category_group".
 *
 * @property integer $id
 * @property string $name
 * @property string $categories_id
 * @property integer $order
 * @property integer $apply_to_type
 * @property integer $status
 */
class AdCategoryGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_category_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['id', 'order', 'apply_to_type', 'status'], 'integer'],
            [['name', 'categories_id'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'categories_id' => 'Categories ID',
            'order' => 'Order',
            'apply_to_type' => 'Apply To Type',
            'status' => 'Status',
        ];
    }
}
