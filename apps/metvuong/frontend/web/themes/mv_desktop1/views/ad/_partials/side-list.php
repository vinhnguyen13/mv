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
	use yii\data\Pagination;
	use frontend\components\SearchUrlManager;
	use frontend\models\Tracking;
	
	$db = Yii::$app->getDb();
	
	$categories = $db->cache(function(){
		return AdCategory::find()->indexBy('id')->all();
	});
	
	$types = AdProduct::getAdTypes();
	
	$limit = \Yii::$app->params['listingLimit'];
	$page = $searchModel->page ? $searchModel->page : 1;
	$offset = ($page - 1) * $limit;
	
	$products = $list['hits'];
	$total = $list['total'];
	
	$now = time();
	
	$pages = new Pagination(['totalCount' => $total, 'defaultPageSize' => $limit, 'route' => '/ad/index' . $searchModel->type, 'urlManager' => new SearchUrlManager()]);
	
	$tracking = false;
	
	if(\Yii::$app->params['tracking']['all']) {
		$tracking = (!empty($searchModel->project_building_id) || ((!empty($searchModel->ward_id) || !empty($searchModel->street_id)) && (!empty($searchModel->room_no) || !empty($searchModel->price_min) || !empty($searchModel->price_max)))) && \Yii::$app->user->identity;
		$userId = \Yii::$app->user->id;
	}
	
	$compares = isset($_COOKIE['compareItems']) ? array_map(function($item) { return current(explode(':', $item)); }, explode(',', $_COOKIE['compareItems'])) : [];
	
	if($top) {
		$topBoost = [];
		$ids = [];
		
		foreach ($top['hits'] as $hit) {
			if($hit['_source']['boost_start_time'] > 0) {
				$hit['vip'] = true;
				$topBoost[] = $hit;
				$ids[] = $hit['_id'];
			}
		}
		
		foreach ($products as $k => $product) {
			if(in_array($product['_id'], $ids)) {
				unset($products[$k]);
			}
		}
		
		$products = array_merge($topBoost, $products);
	}
?>

<?php if($products): ?>
<div id="has-result">
	<div class="top-listing clearfix">
		<p><span id="count-from"><?= $offset + 1 ?></span> - <span id="count-to"><?= $offset + count($products) ?></span> <?= sprintf(Yii::t('ad', 'of %s listings'), '<span id="count-total">' . $total . '</span>') ?></p>
	</div>
	<div id="listing-list" class="wrap-lazy">
		<ul class="clearfix">
			<?php foreach ($products as $k => $hit): ?>
			<?php
			
				$product = $hit['_source'];
				
				if($tracking && $product['user_id'] != $userId) {
					Tracking::find()->productFinder($userId, $product['id'], time());
				}
				/*
				 * Get Url
				 */
				$urlDetail = Url::to(['/ad/detail' . $searchModel->type, 'id' => $product['id'], 'slug' => \common\components\Slug::me()->slugify($product['address'])]);
				
				$alt = ucfirst(Yii::t('ad', $categories[$product['category_id']]['name'])) . ' ' . mb_strtolower($types[$product['type']], 'utf8') . ' - ' . $product['address'];
			?>
			<li class="col-xs-12 col-sm-6 col-lg-4">
				<div class="item">
					<a data-id="<?= $product['id'] ?>" class="clearfix<?php if(isset($hit['vip'])) echo ' vip' ?>" href="<?= $urlDetail ?>" title="<?= $alt ?>">
						<div class="pic-intro">
							<img src="<?= $product['img'] ? $product['img'] : AdImages::defaultImage() ?>" alt="<?= $alt ?>" />
						</div>
						<div class="info-item clearfix">
							<div class="address-listing">
								<p><?= $product['address'] ?></p>
							</div>
							<div class="clearfix price-attr">
								<p class="price-item"><span class="icon-mv"><span class="icon-pricing"></span></span> <?= StringHelper::formatCurrency($product['price']) . ' <span class="txt-unit">' . Yii::t('ad', 'VND').'</span>' ?></p>
								<?php if($product['area'] || $product['room_no'] || $product['toilet_no']) : ?>
									<ul class="clearfix list-attr-td">
			                    	<?php if($product['area']): ?><li> <span class="icon-mv"><span class="icon-page-1-copy"></span></span><?= $product['area'] ?>m<sup>2</sup> </li><?php endif; ?>
			                    	<?php if($product['room_no']): ?><li><span class="icon-mv"><span class="icon-bed-search"></span></span><?= $product['room_no'] ?> </li><?php endif; ?>
			                    	<?php if($product['toilet_no']): ?><li> <span class="icon-mv"><span class="icon-icon-bathroom"></span></span><?= $product['toilet_no'] ?> </li><?php endif; ?>
			                    	</ul>
			                    <?php endif; ?>
							</div>
							<p class="date-post"><?= Yii::t('ad', 'đăng') ?> <?= StringHelper::previousTime($product['start_date']) ?><span class="pull-right"><?= Yii::t('ad', 'Điểm') ?>: <?php $score = round($product['score'] - 0.00001157407 * ($now - $product['start_date'])); if($score > 0) echo $score; else echo 0; ?></span></p>
					    </div>
					</a>
					<ul class="icon-num-get">
				    	<li><span class="icon-mv"><span class="icon-heart-icon-listing"></span></span>3</li>
				    	<li><span class="icon-mv fs-13"><span class="icon-share-social"></span></span>5</li>
				    	<li><span class="icon-mv fs-13"><span class="icon-icons-search"></span></span>12</li>
				    	<li><span class="icon-mv fs-13"><span class="icon-eye-copy"></span></span>12</li>
				    </ul>
					<?php if(in_array($product['id'], $compares)) : ?>
					<div class="compare-button flag-compare-remove" data-value="<?= $product['id'] ?>"><span class="inner-box"><span class="icon-mv mgR-5"><span class="icon-close-icon"></span></span><span class="txt-change"><?= Yii::t('ad', 'Đã thêm so sánh') ?></span></span></div>
					<?php else: ?>
					<div class="compare-button flag-compare-set" data-value="<?= $product['id'] ?>"><span class="inner-box"><span class="icon-mv mgR-5"><span class="icon-balance-scale"></span></span><span class="txt-change"><?= Yii::t('ad', 'So Sánh') ?></span></span></div>
					<?php endif; ?>
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