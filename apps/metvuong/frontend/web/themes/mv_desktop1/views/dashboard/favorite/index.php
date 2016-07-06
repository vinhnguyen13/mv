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
?>
<div class="title-fixed-wrap container">
	<div class="favori block-dash">
		<div class="title-top"><?=Yii::t('activity', 'Favorites')?></div>
		<div class="inner-dash">
			<ul class="clearfix listing-item">
			<?php
			if(!empty($products)) {
				foreach ($products as $product):
				?>
					<li class="col-xs-12 col-sm-6 col-lg-4">
						<?=$this->render('/ad/_partials/list-item', ['product' => $product, 'categories'=>$categories, 'types'=>$types]);?>
						<span class="dele-favori icon-mv" data-pid="<?=$product->id;?>"><span class="icon-close-icon"></span></span>
					</li>
				<?php endforeach;
			}else {
				?>
				<li class="col-xs-12 col-sm-6 col-lg-4">
					<?=Yii::t('common', '{object} no data', ['object'=>Yii::t('activity', 'Favorites')])?>
				</li>
				<?php
			}
				?>
			</ul>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		$(document).on('click', '.listing-item .dele-favori', function (e) {
			$(this).parent().remove();
			var pid = $(this).attr('data-pid');
			if($('.listing-item .item').length == 0){
				location.reload();
			}
			if(pid) {
				$.ajax({
					type: "post",
					dataType: 'json',
					url: '<?=\yii\helpers\Url::to(['/ad/favorite'])?>',
					data: {id: pid, stt: 0},
					success: function (data) {

					}
				});
			}
			return false;
		});
	});
</script>
