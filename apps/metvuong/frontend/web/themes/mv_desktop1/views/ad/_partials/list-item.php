<?php 
use vsoft\ad\models\AdImages;
use vsoft\express\components\StringHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;

$room_no = empty($product->adProductAdditionInfo) ? null : $product->adProductAdditionInfo->room_no;
$toilet_no = empty($product->adProductAdditionInfo) ? null : $product->adProductAdditionInfo->toilet_no;
$catType = ucfirst(Yii::t('ad', $categories[$product->category_id]['name'])) . ' ' . mb_strtolower($types[$product->type], 'utf8');
$alt = $catType . ' - ' . $product->getAddress($product->show_home_no);

?>

	<div class="item">
		<a data-id="<?= $product->id ?>" class="clearfix" href="<?= $product->urlDetail(); ?>" title="<?= $alt ?>">
			<div class="pic-intro">
				<img alt="<?= $alt ?>" src="<?= $product->file_name ? AdImages::getImageUrl($product->folder, $product->file_name, AdImages::SIZE_MEDIUM) : AdImages::defaultImage() ?>" />
			</div>
			<div class="info-item clearfix">
				<div class="address-listing">
					<p><?= $product->getAddress($product->show_home_no) ?></p>
				</div>
				<p class="infor-by-up">
					<strong><?= $catType ?></strong>
				</p>
				<div class="clearfix">
					<div class="right-attr-detail clearfix">
						<p class="id-duan"><?= Yii::t('ad', 'ID') ?>:<span><?= Yii::$app->params['listing_prefix_id'] . $product->id;?></span></p>
			    		<ul class="icon-num-get">
					    	<li><span class="icon-mv"><span class="icon-heart-icon-listing"></span></span>3</li>
					    	<li><span class="icon-mv"><span class="icon-share-social"></span></span>5</li>
					    	<li><span class="icon-mv"><span class="icon-icons-search"></span></span>12</li>
					    </ul>
			    	</div>
					<div class="left-attr-detail">
						<?php if(empty($product->area) && empty($room_no) && empty($toilet_no)){ ?>
		                    
		                <?php } else {
		                	?>
							<ul class="clearfix list-attr-td">
		                	<?php
		                    echo $product->area ? '<li> <span class="icon-mv"><span class="icon-page-1-copy"></span></span>' . $product->area . 'm<sup>2</sup> </li>' : '';
		                    echo $room_no ? '<li><span class="icon-mv"><span class="icon-bed-search"></span></span>' . $room_no . ' </li>' : '';
		                    echo $toilet_no ? '<li> <span class="icon-mv"><span class="icon-icon-bathroom"></span></span>' . $toilet_no . ' </li>' : '';
		                    ?>
							</ul>
		                    <?php
		                } ?>
				        <p class="price-item"><?= StringHelper::formatCurrency($product->price) . ' <span class="txt-unit">' . Yii::t('ad', 'VND').'</span>' ?></p>
			    	</div>
			    </div>
		    	<p class="date-post"><?= Yii::t('ad', 'đăng') ?> <?= StringHelper::previousTime($product->start_date) ?><span class="pull-right"><?= Yii::t('ad', 'Điểm') ?>: <?php $score = round($product->score - 0.00001157407 * (time() - $product->start_date)); if($score > 0) echo $score; else echo 0; ?></span></p>
		    </div>
		</a>
	</div>
