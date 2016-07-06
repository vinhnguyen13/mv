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
			<?php foreach ($products as $k => $product): ?>
			<?php
				/*
				 * Get address
				 */
				$address = [];
				
				if($product['show_home_no'] && $product['home_no']) {
					$address[] = $product['home_no'];
				}
				
				if(isset($searchModel->streets[$product['street_id']])) {
					$street = $searchModel->streets[$product['street_id']];
					
					$address[] = $street['pre'] . ' ' . $street['name'];
				}
				
				/*
				if(isset($searchModel->wards[$product['ward_id']])) {
					$ward = $searchModel->wards[$product['ward_id']];
					
					$address[] = $ward['pre'] . ' ' . $ward['name'];
				}
				
				if(isset($searchModel->districts[$product['district_id']])) {
					$district = $searchModel->districts[$product['district_id']];
					
					$address[] = trim($district['pre'] . ' ' . $district['name']);
				}
				*/
				
				$address = implode(', ', array_filter($address));
				
				/*
				 * Get image
				 */
				$image = AdImages::find()->orderBy('`order` ASC')->where(['product_id' => $product['id']])->one();
				$imateUrl = $image ? $image->getUrl(AdImages::SIZE_THUMB) : AdImages::defaultImage();
				
				/*
				 * Get Url
				 */
				$urlDetail = Url::to(['/ad/detail' . $product['type'], 'id' => $product['id'], 'slug' => \common\components\Slug::me()->slugify($address)]);
				
				$alt = ucfirst(Yii::t('ad', $categories[$product['category_id']]['name'])) . ' ' . mb_strtolower($types[$product['type']], 'utf8') . ' - ' . $address;
			?>
			<li class="col-xs-12 col-sm-6 col-lg-4">
				<div class="item">
					<a data-id="<?= $product['id'] ?>" class="clearfix<?php if($k < 3 && $product['boost_time']) echo ' vip' ?>" href="<?= $urlDetail ?>" title="<?= $alt ?>">
						<div class="pic-intro">
							<img src="<?= $imateUrl ?>" alt="<?= $alt ?>" />
						</div>
						<div class="info-item clearfix">
							<div class="address-listing">
								<p><?= $address ?></p>
							</div>
							<!-- <p class="infor-by-up">
								<strong><?= ucfirst(Yii::t('ad', $categories[$product['category_id']]['name'])) ?> <?= mb_strtolower($types[$product['type']], 'utf8') ?></strong>
							</p> -->
							<!-- <p class="id-duan"><?= Yii::t('ad', 'ID') ?>:<span><?= Yii::$app->params['listing_prefix_id'] . $product['id'] ?></span></p> -->
							<div class="clearfix price-attr">
								<p class="price-item"><span class="icon-mv"><span class="icon-pricing"></span></span> <?= StringHelper::formatCurrency($product['price']) . ' <span class="txt-unit">' . Yii::t('ad', 'VND').'</span>' ?></p>
								<?php if($product['area'] || $product['room_no'] || $product['toilet_no']) : ?>
									<ul class="clearfix list-attr-td">
			                    	<?php if($product['area']): ?><li> <span class="icon-mv"><span class="icon-page-1-copy"></span></span><?= $product['area'] ?>m<sup>2</sup> </li><?php endif; ?>
			                    	<?php if($product['room_no']): ?><li><span class="icon-mv"><span class="icon-bed-search"></span></span><?= $product['room_no'] ?> </li><?php endif; ?>
			                    	<?php if($product['toilet_no']): ?><li> <span class="icon-mv"><span class="icon-icon-bathroom"></span></span><?= $product['toilet_no'] ?> </li><?php endif; ?>
			                    	</ul>
			                    <?php else: ?>
			                    	
			                    <?php endif; ?>
							</div>
					    	<p class="date-post"><?= Yii::t('ad', 'đăng') ?> <?= StringHelper::previousTime($product['start_date']) ?><span class="pull-right"><?= Yii::t('ad', 'Điểm') ?>: <?= $product['score'] ?></span></p>
					    </div>
					</a>
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