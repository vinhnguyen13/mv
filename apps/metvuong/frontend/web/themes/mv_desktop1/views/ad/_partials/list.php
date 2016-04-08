<?php 
	use frontend\models\Tracking;
	use vsoft\ad\models\AdImages;
	use vsoft\express\components\StringHelper;
	use vsoft\ad\models\AdCategory;
	use yii\helpers\Url;
	use vsoft\ad\models\AdProduct;
	use yii\web\View;
	use yii\helpers\Html;
	use vsoft\ad\models\AdWard;
	use yii\helpers\ArrayHelper;
	use vsoft\ad\models\AdStreet;
	use yii\widgets\LinkPager;
	use common\models\AdCity;
	use vsoft\ad\models\AdDistrict;
	use vsoft\ad\models\AdBuildingProject;
	
	$types = AdProduct::getAdTypes ();
?>
<?php foreach ($products as $product): ?>
<li>
	<div class="item-listing">
		<a data-id="<?= $product->id ?>" class="clearfix" href="<?= $product->urlDetail(); ?>" title="<?= $product->getAddress($product->show_home_no) ?>">
			<div class="bgcover img-intro">
				<div>
					<img src="<?= $product->file_name ? AdImages::getImageUrl($product->folder, $product->file_name, AdImages::SIZE_THUMB) : AdImages::defaultImage() ?>" />
				</div>
			</div>
			<div class="attrs-item">
			<div class="wrap-attr-item">
				<p class="date-post"><strong><?= date("d/m/Y H:i", $product->created_at) ?></strong></p>
				<p class="address-listing">
					<?= $product->getAddress($product->show_home_no) ?>
				</p>
				<p class="infor-by-up">
					<strong><?= ucfirst($categories[$product->category_id]['name']) ?> <?= strtolower($types[$product->type]) ?></strong>
				</p>
				<p class="id-duan">ID:<span><?= Yii::$app->params['listing_prefix_id'] . $product->id;?></span></p>
				<ul class="clearfix list-attr-td">
					<?= $product->area ? '<li> <span class="icon-mv"><span class="icon-page-1-copy"></span></span>' . $product->area . 'm2 </li>' : '' ?>
					<?= $product->room_no ? '<li> <span class="icon-mv"><span class="icon-bed-search"></span></span>' . $product->room_no . ' </li>' : '' ?>
					<?= $product->toilet_no ? '<li> <span class="icon-mv"><span class="icon-bathroom-search-copy-2"></span></span>' . $product->toilet_no . ' </li>' : '' ?>
				</ul>
			</div>
			<div class="wrap-attr-bottom">
				<span class="price"><span><?= Yii::t('ad', 'Price') ?></span><?= StringHelper::formatCurrency($product->price) ?></span>
							</div>
		                </div>
					</a>
                    <?php
                    // tracking finder
                    if($product->user_id != Yii::$app->user->id && isset(Yii::$app->params['tracking']['all']) && Yii::$app->params['tracking']['all'] == true) {
                        Tracking::find()->productFinder(Yii::$app->user->id, (int)$product->id, time());
                    }
                    ?>
	</div>
</li>
<?php endforeach; ?>