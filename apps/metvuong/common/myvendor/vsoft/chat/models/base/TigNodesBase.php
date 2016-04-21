<?php

namespace vsoft\chat\models\base;

use Yii;

/**
 * This is the model class for table "{{%tig_nodes}}".
 *
 * @property string $nid
 * @property string $parent_nid
 * @property string $uid
 * @property string $node
 *
 * @property TigUsers $u
 * @property TigPairs[] $tigPairs
 */
class TigNodesBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tig_nodes}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbChat');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_nid', 'uid'], 'integer'],
            [['uid', 'node'], 'required'],
            [['node'], 'string', 'max' => 255],
            [['parent_nid', 'uid', 'node'], 'unique', 'targetAttribute' => ['parent_nid', 'uid', 'node'], 'message' => 'The combination of Parent Nid, Uid and Node has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nid' => 'Nid',
            'parent_nid' => 'Parent Nid',
            'uid' => 'Uid',
            'node' => 'Node',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getU()
    {
        return $this->hasOne(TigUsersBase::className(), ['uid' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTigPairs()
    {
        return $this->hasMany(TigPairsBase::className(), ['nid' => 'nid']);
    }
}
