<?php

namespace vsoft\chat\models\base;

use Yii;

/**
 * This is the model class for table "{{%tig_pairs}}".
 *
 * @property string $nid
 * @property string $uid
 * @property string $pkey
 * @property string $pval
 *
 * @property TigUsers $u
 * @property TigNodes $n
 */
class TigPairsBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tig_pairs}}';
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
            [['nid', 'uid'], 'integer'],
            [['uid', 'pkey'], 'required'],
            [['pval'], 'string'],
            [['pkey'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nid' => 'Nid',
            'uid' => 'Uid',
            'pkey' => 'Pkey',
            'pval' => 'Pval',
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
    public function getN()
    {
        return $this->hasOne(TigNodesBase::className(), ['nid' => 'nid']);
    }
}
