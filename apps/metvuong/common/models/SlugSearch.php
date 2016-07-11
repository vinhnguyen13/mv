<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "slug_search".
 *
 * @property integer $id
 * @property string $slug
 * @property string $table
 * @property string $column
 * @property integer $value
 */
class SlugSearch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'slug_search';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slug', 'table', 'column', 'value'], 'required'],
            [['value'], 'integer'],
            [['slug'], 'string', 'max' => 255],
            [['table', 'column'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'table' => 'Table',
            'column' => 'Column',
            'value' => 'Value',
        ];
    }
}
