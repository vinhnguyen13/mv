<?php
use frontend\models\Ad;

$categoriesDb = \vsoft\ad\models\AdCategory::getDb();
$categories = $categoriesDb->cache(function($categoriesDb){
    return \vsoft\ad\models\AdCategory::find()->indexBy('id')->asArray(true)->all();
});
$types = \vsoft\ad\models\AdProduct::getAdTypes();
$products = Ad::find()->homePageRandom();
if(!empty($products)) {
    ?>
    <ul class="clearfix">
        <?php foreach ($products as $product):
            $url = $product->urlDetail(true);
            $address = $product->getAddress(true);
            ?>
            <li>
                <div class="item">
                    <a href="<?= $url ?>" class="pic-intro rippler rippler-default">
                        <div class="img-show">
                            <div><img src="<?= $product->representImage ?>" data-original=""></div>
                        </div>
                        <div
                            class="title-item"><?= ucfirst(Yii::t('ad', $categories[$product->category_id]['name'])) ?> <?= $types[$product->type] ?></div>
                    </a>

                    <div class="info-item">
                        <div class="address-feat clearfix">
                            <p class="date-post"><?= Yii::t('statistic', 'Date of posting') ?>:
                                <strong><?= date("d/m/Y", $product->created_at) ?></strong></p>

                            <div class="address-listing">
                                <a title="<?= $address ?>"
                                   href="<?= $url ?>"><?= $address ?></a>
                            </div>
                            <p class="id-duan">
                                ID:<span><?= Yii::$app->params['listing_prefix_id'] . $product->id; ?></span></p>
                            <ul class="clearfix list-attr-td">
                                <?php if (empty($product->area) && empty($product->adProductAdditionInfo->room_no) && empty($product->adProductAdditionInfo->toilet_no)) { ?>
                                    <li><span><?= Yii::t('listing', 'updating') ?></span></li>
                                <?php } else {
                                    echo $product->area ? '<li> <span class="icon-mv"><span class="icon-page-1-copy"></span></span>' . $product->area . 'm2 </li>' : '';
                                    echo $product->adProductAdditionInfo->room_no ? '<li> <span class="icon-mv"><span class="icon-bed-search"></span></span>' . $product->adProductAdditionInfo->room_no . ' </li>' : '';
                                    echo $product->adProductAdditionInfo->toilet_no ? '<li> <span class="icon-mv"><span class="icon-bathroom-search-copy-2"></span></span> ' . $product->adProductAdditionInfo->toilet_no . ' </li>' : '';
                                } ?>
                            </ul>
                        </div>
                        <div class="bottom-feat-box clearfix">
                            <a href="<?= $url ?>" class="pull-right color-cd-hover">Chi tiết</a>

                            <p><?= Yii::t('listing', 'Price') ?>
                                <strong><?= vsoft\express\components\StringHelper::formatCurrency($product->price) ?>
                                    vnd</strong></p>
                        </div>
                    </div>
                </div>
            </li>
            <?php
        endforeach;
        ?>
    </ul>
    <?php
}
?>
