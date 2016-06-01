<?php 
	use yii\widgets\LinkPager;
	use yii\helpers\Url;
	use vsoft\ad\models\AdCategory;
	use vsoft\ad\models\AdStreet;
	use vsoft\ad\models\AdWard;
	use vsoft\ad\models\AdDistrict;
	use vsoft\ad\models\AdImages;
	use vsoft\ad\models\AdProduct;
	use vsoft\express\components\StringHelper;
	
	$db = Yii::$app->getDb();
	
	$categories = $db->cache(function(){
		return AdCategory::find()->indexBy('id')->all();
	});
	
	if($searchModel->street_id) {
		$streets = [$searchModel->street_id => $searchModel->street->getAttributes()];
	} else if($searchModel->district_id) {
		$streets = AdStreet::find()->asArray(true)->where(['district_id' => $searchModel->district_id])->indexBy('id')->all();
	} else if($searchModel->city_id) {
		$streets = AdStreet::find()->asArray(true)->where(['city_id' => $searchModel->city_id])->indexBy('id')->all();
	}
	
	if($searchModel->ward_id) {
		$wards = [$searchModel->ward_id => $searchModel->ward->getAttributes()];
	} else if($searchModel->district_id) {
		$wards = AdWard::find()->asArray(true)->where(['district_id' => $searchModel->district_id])->indexBy('id')->all();
	} else if($searchModel->city_id) {
		$wards = AdWard::find()->asArray(true)->where(['city_id' => $searchModel->city_id])->indexBy('id')->all();
	}
	
	if($searchModel->district_id) {
		$districts = [$searchModel->district_id => $searchModel->district->getAttributes()];
	} else if($searchModel->city_id) {
		$districts = AdDistrict::find()->asArray(true)->where(['city_id' => $searchModel->city_id])->indexBy('id')->all();
	}
	
	$types = AdProduct::getAdTypes();
	
	$products = $list['products'];
	$pages = $list['pages'];
?>

<?php if($products): ?>
<div id="has-result">
	<div class="top-listing clearfix">
		<p><span id="count-from"><?= $pages->offset + 1 ?></span> - <span id="count-to"><?= $pages->offset + count($products) ?></span> <?= sprintf(Yii::t('ad', 'of %s listings'), '<span id="count-total">' . $pages->totalCount . '</span>') ?></p>
	</div>
	<div id="listing-list" class="wrap-lazy">
		<ul class="clearfix">
			<?php foreach ($products as $product): ?>
			<?php
				/*
				 * Get address
				 */
				$address = [];
				
				if($product['show_home_no'] && $product['home_no']) {
					$address[] = $product['home_no'];
				}
				
				if(isset($streets[$product['street_id']])) {
					$street = $streets[$product['street_id']];
					
					$address[] = $street['pre'] . ' ' . $street['name'];
				}
				
				if(isset($wards[$product['ward_id']])) {
					$ward = $wards[$product['ward_id']];
					
					$address[] = $ward['pre'] . ' ' . $ward['name'];
				}
				
				if(isset($districts[$product['district_id']])) {
					$district = $districts[$product['district_id']];
					
					$address[] = trim($district['pre'] . ' ' . $district['name']);
				}
				
				$address = implode(', ', array_filter($address));
				
				/*
				 * Get image
				 */
				$image = AdImages::find()->orderBy('`order` ASC')->where(['product_id' => $product['id']])->one();
				$imateUrl = $image ? $image->getUrl(AdImages::SIZE_THUMB) : AdImages::defaultImage();
				
				/*
				 * Get Url
				 */
				$urlDetail = Url::to(['/ad/detail', 'id' => $product['id'], 'slug' => \common\components\Slug::me()->slugify($address)]);
			?>
			<li class="col-xs-12 col-sm-6 col-lg-4">
				<div class="item">
					<a data-id="<?= $product['id'] ?>" class="clearfix" href="<?= $urlDetail ?>" title="<?= $address ?>">
						<div class="pic-intro">
							<img src="<?= $imateUrl ?>" />
						</div>
						<div class="info-item clearfix">
							<div class="address-listing">
								<?= $address ?>
							</div>
							<p class="infor-by-up">
								<strong><?= ucfirst(Yii::t('ad', $categories[$product['category_id']]['name'])) ?> <?= mb_strtolower($types[$product['type']]) ?></strong>
							</p>
							<p class="id-duan"><?= Yii::t('ad', 'ID') ?>:<span><?= Yii::$app->params['listing_prefix_id'] . $product['id'] ?></span></p>
							<ul class="clearfix list-attr-td">
			                    <?php if($product['area'] || $product['room_no'] || $product['toilet_no']) : ?>
			                    	<?php if($product['area']): ?><li> <span class="icon-mv"><span class="icon-page-1-copy"></span></span><?= $product['area'] ?>m<sup>2</sup> </li><?php endif; ?>
			                    	<?php if($product['room_no']): ?><li><span class="icon-mv"><span class="icon-bed-search"></span></span><?= $product['room_no'] ?> </li><?php endif; ?>
			                    	<?php if($product['toilet_no']): ?><li> <span class="icon-mv"><span class="icon-bathroom-search-copy-2"></span></span><?= $product['toilet_no'] ?> </li><?php endif; ?>
			                    <?php else: ?>
			                    	<li><?=Yii::t('listing', 'updating')?></li>
			                    <?php endif; ?>
							</ul>
					        <p class="price-item"><?= Yii::t('listing', 'Price') ?><strong><?= StringHelper::formatCurrency($product['price']) . ' ' . Yii::t('ad', 'VND') ?></strong></p>   
					    	<p class="date-post"><?= StringHelper::previousTime($product['updated_at']) ?></p>
					    </div>
					</a>
			        <?php /* tracking finder
				        if($product->user_id != Yii::$app->user->id && isset(Yii::$app->params['tracking']['all']) && Yii::$app->params['tracking']['all'] == true) {
				            Tracking::find()->productFinder(Yii::$app->user->id, (int)$product->id, time());
				        }
				    */ ?>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
		<nav class="text-center dt-pagination">
            <?php
                echo LinkPager::widget([
                    'pagination' => $pages,
					'maxButtonCount' => 5
                ]);
            ?>
            </nav>
		</div>
	</div>
<?php else: ?>
<div class="container" id="no-result"><?= sprintf(Yii::t('ad', 'Chưa có tin đăng theo tìm kiếm của bạn, %sđăng ký nhận thông báo khi có tin đăng phù hợp%s.'), '<a href="#">', '</a>') ?></div>
<?php endif; ?>