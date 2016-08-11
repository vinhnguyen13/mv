<?php
	use yii\helpers\Url;
	use vsoft\ad\models\AdProduct;
	use yii\db\Query;
use yii\db\Expression;
	
	if(isset($_COOKIE['compareItems'])) {
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
?>
    <div class="container">
        <div class="menuUser">
            <div class="listing-compare">
                <div class="title">Select Listing</div>
                <?php if(isset($products)) : ?>
                <ul class="clearfix">
                	<?php foreach ($products as $product) : ?>
                    <li>
                        <label for="" class="checkbox-ui"><input type="checkbox" <?= $compares[$product->id] ? 'checked="checked"' : '' ?>><span class="icon-mv"><span class="icon-checkbox"></span></span><?= $product->address ?></label>
                        <a class="remove-compare" href=""><span class="icon-mv"><span class="icon-close-icon"></span></span></a>
                    </li>
                	<?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
            <div class="option-choose-compare">
                <div class="title">Tùy chọn compare</div>
                <ul class="clearfix">
                    <li><label for="" class="checkbox-ui"><input type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span>Phòng ngủ</label></li>
                    <li><label for="" class="checkbox-ui"><input type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span>Phòng tắm</label></li>
                    <li><label for="" class="checkbox-ui"><input type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span>Diện tích</label></li>
                    <li><label for="" class="checkbox-ui"><input type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span>Giá</label></li>
                    <li><label for="" class="checkbox-ui"><input type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span>Tiện ích</label></li>
                    <li><label for="" class="checkbox-ui"><input type="checkbox"><span class="icon-mv"><span class="icon-checkbox"></span></span>Số tầng</label></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="title-fixed-wrap container">
        <div class="u-allduan">
            <div class="title-top">Compare Listing</div>
            <div class="compare-block">
                <div class="row list-compare">
                    <div class="col-xs-12 col-sm-6 col-lg-4 item">
                        <div class="inner-box">
                            <a href="" class="pic-intro"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/Government_-South_Australia_Police_Headquarters_Built_Environs_main.jpg' ?>" alt=""></a>
                            <div class="infor">
                                <p class="address-listing">Đường Mai Chí Thọ, Phường An Phú, Quận 2, Hồ Chí Minh</p>
                                <ul>
                                    <li class="price-item">
                                        1,6191 <span class="txt-unit">tỷ</span> <span class="txt-unit">VNĐ</span>
                                        <a href="" class="pull-right arrow-updown"><span class="icon-mv"><span class="icon-long-arrow-down"></span></span></a>
                                    </li>
                                    <li>
                                        <span class="icon-mv"><span class="icon-page-1-copy"></span></span>82m<sup>2</sup>
                                        <a href="" class="pull-right arrow-updown"><span class="icon-mv"><span class="icon-long-arrow-up"></span></span></a>
                                    </li>
                                    <li>
                                        <span class="icon-mv"><span class="icon-bed-search"></span></span>2 phòng ngủ
                                        <a href="" class="pull-right arrow-updown"><span class="icon-mv"><span class="icon-long-arrow-up"></span></span></a>
                                    </li>
                                    <li>
                                        <span class="icon-mv"><span class="icon-icon-bathroom"></span></span>2 phòng tắm
                                        <a href="" class="pull-right arrow-updown"><span class="icon-mv"><span class="icon-long-arrow-up"></span></span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-lg-4 item">
                        <div class="inner-box">
                            <a href="" class="pic-intro"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/Government_-South_Australia_Police_Headquarters_Built_Environs_main.jpg' ?>" alt=""></a>
                            <div class="infor">
                                <p class="address-listing">Đường Mai Chí Thọ, Phường An Phú, Quận 2, Hồ Chí Minh</p>
                                <ul>
                                    <li class="price-item">
                                        1,6191 <span class="txt-unit">tỷ</span> <span class="txt-unit">VNĐ</span>
                                        <a href="" class="pull-right arrow-updown"><span class="icon-mv"><span class="icon-long-arrow-down"></span></span></a>
                                    </li>
                                    <li>
                                        <span class="icon-mv"><span class="icon-page-1-copy"></span></span>82m<sup>2</sup>
                                        <a href="" class="pull-right arrow-updown"><span class="icon-mv"><span class="icon-long-arrow-up"></span></span></a>
                                    </li>
                                    <li>
                                        <span class="icon-mv"><span class="icon-bed-search"></span></span>2 phòng ngủ
                                        <a href="" class="pull-right arrow-updown"><span class="icon-mv"><span class="icon-long-arrow-up"></span></span></a>
                                    </li>
                                    <li>
                                        <span class="icon-mv"><span class="icon-icon-bathroom"></span></span>2 phòng tắm
                                        <a href="" class="pull-right arrow-updown"><span class="icon-mv"><span class="icon-long-arrow-up"></span></span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-lg-4 item">
                        <div class="inner-box">
                            <a href="" class="pic-intro"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/Government_-South_Australia_Police_Headquarters_Built_Environs_main.jpg' ?>" alt=""></a>
                            <div class="infor">
                                <p class="address-listing">Đường Mai Chí Thọ, Phường An Phú, Quận 2, Hồ Chí Minh</p>
                                <ul>
                                    <li class="price-item">
                                        1,6191 <span class="txt-unit">tỷ</span> <span class="txt-unit">VNĐ</span>
                                        <a href="" class="pull-right arrow-updown"><span class="icon-mv"><span class="icon-long-arrow-down"></span></span></a>
                                    </li>
                                    <li>
                                        <span class="icon-mv"><span class="icon-page-1-copy"></span></span>82m<sup>2</sup>
                                        <a href="" class="pull-right arrow-updown"><span class="icon-mv"><span class="icon-long-arrow-up"></span></span></a>
                                    </li>
                                    <li>
                                        <span class="icon-mv"><span class="icon-bed-search"></span></span>2 phòng ngủ
                                        <a href="" class="pull-right arrow-updown"><span class="icon-mv"><span class="icon-long-arrow-up"></span></span></a>
                                    </li>
                                    <li>
                                        <span class="icon-mv"><span class="icon-icon-bathroom"></span></span>2 phòng tắm
                                        <a href="" class="pull-right arrow-updown"><span class="icon-mv"><span class="icon-long-arrow-up"></span></span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('.option-choose-compare ul label').checkbox_ui();
            $('.listing-compare ul label').checkbox_ui();
        });
    </script>