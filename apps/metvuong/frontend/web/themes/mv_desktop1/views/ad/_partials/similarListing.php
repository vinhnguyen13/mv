<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 4/4/2016 2:08 PM
 */

use vsoft\ad\models\AdImages;
use vsoft\ad\models\AdProduct;
use vsoft\express\components\StringHelper;

if(empty($city_id))
    $city_id = 1;

if(empty($district_id))
    $district_id = 10;

$categories = \vsoft\ad\models\AdCategory::find ()->indexBy( 'id' )->asArray( true )->all ();
$types = AdProduct::getAdTypes();
$products = AdProduct::find()->innerJoin(AdImages::tableName(), "ad_product.id = ad_images.product_id")
    ->where(['city_id' => $city_id, 'district_id' => $district_id])->orderBy(new \yii\db\Expression('rand()'))->limit(3)->all();

if(count($products) > 0) {
?>
<div class="col-xs-12 col-md-3 col-right sidebar-col">
    <div class="item-sidebar">
        <div class="title-sidebar"><?=Yii::t('listing','SIMILAR LISTINGS')?></div>
        <ul class="clearfix list-post">
            <?php foreach($products as $product){
                ?>
            <li>
                <div class="item">
                    <a href="<?= $product->urlDetail(); ?>" class="pic-intro rippler rippler-default">
                        <div class="img-show">
                            <div><img src="<?=$product->representImage?>" alt="<?=$product->getAddress($product->show_home_no)?>"></div>
                        </div>
                        <div class="title-item"><?= ucfirst($categories[$product->category_id]['name']) ?> <?= strtolower($types[$product->type]) ?></div>
                    </a>
                    <div class="info-item">
                        <div class="address-feat">
                            <p class="date-post"><?=Yii::t('listing','Listing date')?>: <strong><?= date("d/m/Y H:i", $product->created_at) ?></strong></p>
                            <a title="<?= $product->getAddress($product->show_home_no) ?>" href="<?= $product->urlDetail(); ?>"><?= $product->getAddress($product->show_home_no) ?></a>
                            <p class="id-duan">ID:<span><?= Yii::$app->params['listing_prefix_id'] . $product->id;?></span></p>
                            <ul class="clearfix list-attr-td">
                                <?= $product->area ? '<li> <span class="icon icon-dt icon-dt-small"></span>' . $product->area . 'm2 </li>' : '' ?>
                                <?= $product->adProductAdditionInfo->room_no ? '<li> <span class="icon icon-bed icon-bed-small"></span> ' . $product->adProductAdditionInfo->room_no . ' </li>' : '' ?>
                                <?= $product->adProductAdditionInfo->toilet_no ? '<li> <span class="icon icon-pt icon-pt-small"></span> ' . $product->adProductAdditionInfo->toilet_no . ' </li>' : '' ?>
                            </ul>
                        </div>
                        <div class="bottom-feat-box clearfix">
                            <a href="<?= $product->urlDetail(); ?>" class="pull-right"><?=Yii::t('listing', 'Detail')?></a>
                            <p>Giá <strong><?= StringHelper::formatCurrency($product->price) ?></strong></p>
                        </div>
                    </div>
                </div>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>
<?php } ?>