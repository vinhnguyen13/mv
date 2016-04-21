<?php
namespace api\modules\v1\models;
use \yii\db\ActiveRecord;
/**
 * Country Model
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class Migration extends ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'migration';
	}

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['version'];
    }

    /**
     * Define rules for validation
     */
    public function rules()
    {
        return [
            [['version'], 'required']
        ];
    }
}
