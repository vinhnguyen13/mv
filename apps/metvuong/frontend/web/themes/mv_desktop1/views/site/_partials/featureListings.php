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
    <ul class="clearfix listing-item">
        <?= $this->render('/ad/_partials/list', ['products' => $products, 'categories' => $categories]) ?>
    </ul>
    <?php
}
?>
