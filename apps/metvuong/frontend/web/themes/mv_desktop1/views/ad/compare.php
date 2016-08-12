<?php
	use yii\helpers\Url;
	use vsoft\ad\models\AdProduct;
	use yii\db\Query;
	use yii\db\Expression;
	use yii\web\View;
	
	if(!empty($_COOKIE['compareItems'])) {
		$temp = explode(',', $_COOKIE['compareItems']);
		$compares = [];
		foreach ($temp as $t) {
			list($id, $status) = explode(':', $t);
			
			$compares[$id] = $status;
		}
		
		$ids = array_keys($compares);
		$expression = new Expression('FIELD(ad_product.id,' . implode(',', $ids) . ')');
		$products = AdProduct::find()->where(['ad_product.id' => $ids])->orderBy($expression)->all();
	}
	
	$this->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/compare.js', ['position'=>View::POS_END]);
	$this->registerJs("var url = '" . Url::current() . "'", View::POS_HEAD);
?>
    <div class="container">
        <div class="menuUser">
            <div class="listing-compare">
                <div class="title">Select Listing</div>
                <?php
                	if(isset($products)) :
                		$selectProducts = []
                ?>
                <ul class="clearfix">
                	<?php
                		foreach ($products as $product) :
                			if($compares[$product->id]) {
                				$selectProducts[] = $product;
                			}
                	?>
                    <li data-id="<?= $product->id ?>">
                        <label class="checkbox-ui"><input class="active-compare" type="checkbox" <?= $compares[$product->id] ? 'checked="checked"' : '' ?>><span class="icon-mv"><span class="icon-checkbox"></span></span><?= $product->address ?></label>
                        <a class="remove-compare" href=""><span class="icon-mv"><span class="icon-close-icon"></span></span></a>
                    </li>
                	<?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
            <div class="option-choose-compare">
                <div class="title">Tùy chọn compare</div>
                <ul class="clearfix">
                    <li><label for="" class="checkbox-ui"><input data-row="s" class="custom-compare" checked="checked" type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span><?= Yii::t('ad', 'Number of storeys') ?></label></li>
                    <li><label for="" class="checkbox-ui"><input data-row="fw" class="custom-compare" checked="checked" type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span><?= Yii::t('ad', 'Facade') ?></label></li>
                    <li><label for="" class="checkbox-ui"><input data-row="lw" class="custom-compare" checked="checked" type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span><?= Yii::t('ad', 'Entry width') ?></label></li>
                    <li><label for="" class="checkbox-ui"><input data-row="hd" class="custom-compare" checked="checked" type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span><?= Yii::t('ad', 'House direction') ?></label></li>
                    <li><label for="" class="checkbox-ui"><input data-row="bd" class="custom-compare" checked="checked" type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span><?= Yii::t('ad', 'Balcony direction') ?></label></li>
                    <li><label for="" class="checkbox-ui"><input data-row="f" class="custom-compare" checked="checked" type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span><?= Yii::t('ad', 'Facilities') ?></label></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="title-fixed-wrap container">
        <div class="u-allduan">
            <div class="title-top">Compare Listing</div>
            <div class="compare-block">
            	<?php if(isset($selectProducts)): ?>
            	<?= $this->render('_partials/compare.php', ['products' => $selectProducts]) ?>
            	<?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('.option-choose-compare ul label').checkbox_ui();
            $('.listing-compare ul label').checkbox_ui();
        });
    </script>