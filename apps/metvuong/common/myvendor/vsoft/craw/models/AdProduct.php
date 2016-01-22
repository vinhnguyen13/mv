<?php

namespace vsoft\craw\models;

use Yii;
use vsoft\ad\models\AdProduct as AP;

/**
 * This is the model class for table "ad_product".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $project_building_id
 * @property string $home_no
 * @property integer $user_id
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $ward_id
 * @property integer $street_id
 * @property integer $type
 * @property string $content
 * @property double $area
 * @property string $price
 * @property double $price_input
 * @property integer $price_type
 * @property double $lng
 * @property double $lat
 * @property integer $start_date
 * @property integer $end_date
 * @property integer $score
 * @property integer $view
 * @property integer $verified
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $source
 * @property string $rating
 *
 * @property AdContactInfo $adContactInfo
 * @property AdImages[] $adImages
 * @property AdCategory $category
 * @property AdCity $city
 * @property AdDistrict $district
 * @property AdStreet $street
 * @property AdWard $ward
 * @property AdProductAdditionInfo $adProductAdditionInfo
 * @property AdProductRating[] $adProductRatings
 * @property User[] $users
 * @property AdProductReport[] $adProductReports
 * @property User[] $users0
 * @property AdProductSaved[] $adProductSaveds
 * @property User[] $users1
 */
class AdProduct extends AP
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_product';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
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
            [['category_id', 'project_building_id', 'user_id', 'city_id', 'district_id', 'ward_id', 'street_id', 'type', 'price', 'price_type', 'start_date', 'end_date', 'score', 'view', 'verified', 'created_at', 'updated_at', 'status', 'source'], 'integer'],
            [['area', 'price_input', 'lng', 'lat', 'rating'], 'number'],
            [['home_no'], 'string', 'max' => 32],
            [['content'], 'string', 'max' => 3200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Phân loại',
            'project_building_id' => 'Thuộc dự án',
            'project' => 'Thuộc dự án',
            'home_no' => 'Số nhà',
            'user_id' => 'User ID',
            'city_id' => 'City ID',
            'city' => 'Tỉnh/Thành',
            'district_id' => 'District ID',
            'district' => 'Quận/Huyện',
            'ward_id' => 'Ward ID',
            'ward' => 'Phưỡng/Xã',
            'street_id' => 'Street ID',
            'street' => 'Đường/Phố',
            'type' => 'Hình thức',
            'content' => 'Nội dung',
            'area' => 'Diện tích(m)',
            'price' => 'Giá',
            'facadeWidth' => 'Mặt tiền(m)',
            'landWidth' => 'Đường vào(m)',
            'homeDirection' => 'Hướng nhà',
            'facadeDirection' => 'Hướng ban công',
            'floor' => 'Số tầng',
            'room' => 'Số phòng ngủ',
            'toilet' => 'Số phòng tắm',
            'interior' => 'Nội thất',
            'name' => 'Tên liên hệ',
            'address' => 'Địa chỉ',
            'phone' => 'Số điện thoại',
            'mobile' => 'Số di động',
            'price_input' => 'Price Input',
            'price_type' => 'Price Type',
            'lng' => 'Lng',
            'lat' => 'Lat',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'score' => 'Score',
            'view' => 'View',
            'verified' => 'Verified',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'source' => 'Source',
            'rating' => 'Rating',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdContactInfo()
    {
        return $this->hasOne(AdContactInfo::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdImages()
    {
        return $this->hasMany(AdImages::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(AdCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(AdCity::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(AdDistrict::className(), ['id' => 'district_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStreet()
    {
        return $this->hasOne(AdStreet::className(), ['id' => 'street_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWard()
    {
        return $this->hasOne(AdWard::className(), ['id' => 'ward_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
    	return $this->hasOne(AdBuildingProject::className(), ['id' => 'project_building_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdProductAdditionInfo()
    {
        return $this->hasOne(AdProductAdditionInfo::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdProductRatings()
    {
        return $this->hasMany(AdProductRating::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('ad_product_rating', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdProductReports()
    {
        return $this->hasMany(AdProductReport::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers0()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('ad_product_report', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdProductSaveds()
    {
        return $this->hasMany(AdProductSaved::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers1()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('ad_product_saved', ['product_id' => 'id']);
    }
    
    public function getTypeText() {
    	$type = [
    		AdProduct::TYPE_FOR_SELL => Yii::t ( 'ad', 'Nhà đất bán' ),
    		AdProduct::TYPE_FOR_RENT => Yii::t ( 'ad', 'Nhà đất cho thuê' )
    	];
    	
    	return $type[$this->type];
    }
}
