<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 4/15/2016 1:53 PM
 */

use vsoft\ad\models\AdCategory;
use vsoft\ad\models\AdProduct;
use vsoft\express\components\StringHelper;

$categories = AdCategory::find()->indexBy( 'id' )->asArray( true )->all();
$types = AdProduct::getAdTypes();

?>

<div class="title-sidebar"><?=$title?></div>
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
                    <div class="address-feat clearfix">
                        <p class="date-post"><?=Yii::t('listing','Listing date')?>: <strong><?= date("d/m/Y H:i", $product->created_at) ?></strong></p>
                        <div class="address-listing">
                            <a title="<?= $product->getAddress($product->show_home_no) ?>" href="<?= $product->urlDetail(); ?>"><?= $product->getAddress($product->show_home_no) ?></a>
                        </div>
                        <p class="id-duan">ID:<span><?= Yii::$app->params['listing_prefix_id'] . $product->id;?></span></p>
                        <ul class="clearfix list-attr-td">
                            <?php if(empty($product->area) && empty($product->adProductAdditionInfo->room_no) && empty($product->adProductAdditionInfo->toilet_no)){ ?>
                                <li><?=Yii::t('listing','updating')?></li>
                            <?php } else {
                                echo $product->area ? '<li> <span class="icon-mv"><span class="icon-page-1-copy"></span></span>' . $product->area . 'm2 </li>' : '';
                                echo $product->adProductAdditionInfo->room_no ? '<li><span class="icon-mv"><span class="icon-bed-search"></span></span>' . $product->adProductAdditionInfo->room_no . ' </li>' : '';
                                echo $product->adProductAdditionInfo->toilet_no ? '<li> <span class="icon-mv"><span class="icon-bathroom-search-copy-2"></span></span>' . $product->adProductAdditionInfo->toilet_no . ' </li>' : '';
                            } ?>
                        </ul>
                    </div>
                    <div class="bottom-feat-box clearfix">
                        <p><?=Yii::t('listing','Price')?> <strong><?= StringHelper::formatCurrency($product->price) ?> vnd</strong></p>
                    </div>
                </div>
            </div>
        </li>
    <?php } ?>
</ul>
