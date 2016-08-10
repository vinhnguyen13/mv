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
					<a data-id="<?= $product['id'] ?>" class="clearfix<?php if($product['boost_sort']) echo ' vip' ?>" href="<?= $urlDetail ?>" title="<?= $alt ?>">
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
					<div class="compare-button flag-compare-set hide" data-value="<?= $product['id'] ?>"><span class="inner-box"><span class="icon-mv mgR-5"><span class="icon-balance-scale"></span></span><span class="txt-change">So Sánh</span></span></div>
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

<script>
	$(document).ready(function () {
		
		var compare = {
			countCompare: 0,
			numCheck: 3,
			setNumItem: $('.tool-compare button'),
			numGet: $('.tool-compare .num-show'),
			saveGetItem: $('.getCompare'),
			saveArr: [],
			init: function () {
				$(document).on('click', '.flag-compare-set', function (e) {
					e.preventDefault();
					compare.add($(this));
				});
				$(document).on('click', '.flag-compare-remove', function (e) {
					e.preventDefault();
					compare.remove($(this));
				});
			},
			add: function (item) {
				compare.countCompare += 1;
				if ( compare.countCompare > 0 && compare.countCompare <= compare.numCheck ) {
					item.removeClass('flag-compare-set').addClass('flag-compare-remove');
					item.find('.txt-change').text('Đã thêm so sánh');
					item.find('.icon-balance-scale').attr('class','icon-close-icon');
					compare.numGet.text('('+compare.countCompare+')');
					compare.effectShow();
					compare.checkVal(item, 1);
				}else {
					alert("Bạn đã chọn đủ 3 tin đăng");
					compare.countCompare = compare.numCheck;
				}
			},
			remove: function (item) {
				item.removeClass('flag-compare-remove').addClass('flag-compare-set');
				item.find('.txt-change').text('So Sánh');
				item.find('.icon-close-icon').attr('class','icon-balance-scale');
				compare.countCompare -= 1;
				if ( compare.countCompare == 0 ) {
					compare.numGet.text('');
				}else {
					compare.numGet.text('('+compare.countCompare+')');	
				}
				
				compare.effectShow();
				compare.checkVal(item, 0);
			},
			checkVal: function (item, flag) {
				var idItem = item.data('value');
				if ( flag ) {
					compare.saveArr.push(idItem);
				}else {
					for ( var i = 0; i < compare.saveArr.length; i++ ) {
						if ( idItem == compare.saveArr[i] ) {
							compare.saveArr.splice(i, 1);
						}
					}
				}
				
				var valSet = '['+compare.saveArr.toString()+']';
				compare.saveGetItem.val(valSet);
			},
			effectShow: function () {
				compare.setNumItem.addClass('get-show-num');
				setTimeout(function(){compare.setNumItem.removeClass('get-show-num')},300);
			}
		};

		compare.init();

		var favorite = {

		};
	});
</script>