<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 6/29/2016 2:01 PM
 */
use yii\helpers\Url;
use frontend\models\Ad;
use vsoft\ad\models\AdProduct;

$categoriesDb = \vsoft\ad\models\AdCategory::getDb();
$categories = $categoriesDb->cache(function($categoriesDb){
	return \vsoft\ad\models\AdCategory::find()->indexBy('id')->asArray(true)->all();
});
$types = AdProduct::getAdTypes();
$products = Ad::find()->listingFavorite();
if(!empty($products)) {
	?>
	<div class="title-fixed-wrap container">
		<div class="favori block-dash">
			<div class="title-top">Favorites</div>
			<div class="inner-dash">
				<ul class="clearfix listing-item">
					<?php

					foreach ($products as $product):
					?>
						<?=$this->render('/ad/_partials/list-item', ['product' => $product, 'categories'=>$categories, 'types'=>$types]);?>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
	<?php
}
?>