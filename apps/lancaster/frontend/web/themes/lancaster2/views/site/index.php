<?php
use yii\web\View;

/* @var $this yii\web\View */
$this->registerCssFile('https://fonts.googleapis.com/css?family=Roboto:400,700|Noticia+Text:400italic,400,700,700italic', ['type' => 'text/css']);
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/swiper.css");

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/plugins/swiper.jquery.min.js', ['position'=>View::POS_END]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/plugins/jquery.easing.js', ['position'=>View::POS_END]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/plugins/jquery.scrollspeed.js', ['position'=>View::POS_END]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/home.js', ['position'=>View::POS_END]);
Yii::$app->getView()->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=initMap', ['position'=>View::POS_END, 'defer'=>'', 'async'=>'']);

$this->title = Yii::t('express','We offer exeptional amenities and renowned white - glove services');
?>
<div id="home-page">
    <div id="paralax-overlay">
        <div class="sk-cube-grid">
            <div class="sk-cube sk-cube1"></div>
            <div class="sk-cube sk-cube2"></div>
            <div class="sk-cube sk-cube3"></div>
            <div class="sk-cube sk-cube4"></div>
            <div class="sk-cube sk-cube5"></div>
            <div class="sk-cube sk-cube6"></div>
            <div class="sk-cube sk-cube7"></div>
            <div class="sk-cube sk-cube8"></div>
            <div class="sk-cube sk-cube9"></div>
        </div>
    </div>
    <div id="paralax-wrapper">
        <div id="paralax-nav">
            <ul class="items">
                <li class="active"><a href="#"><?= Yii::t('home', 'The Building') ?></a><div class="arrow-down"></div></li>
                <li><a href="#"><?= Yii::t('home', 'Apartments') ?></a><div class="arrow-down"></div></li>
                <li><a href="#"><?= Yii::t('home', 'Amenities') ?></a><div class="arrow-down"></div></li>
                <li><a href="#"><?= Yii::t('home', 'Views') ?></a><div class="arrow-down"></div></li>
                <li><a href="#"><?= Yii::t('home', 'Neighborhood') ?></a><div class="arrow-down"></div></li>
                <li><a href="#"><?= Yii::t('home', 'Pricing') ?></a><div class="arrow-down"></div></li>
                <li><a href="#"><?= Yii::t('home', 'Location') ?></a><div class="arrow-down"></div></li>
            </ul>
        </div>
        <div id="paralax-page">
            <div class="section section-building" style="background-image: url(<?= '/store/building-project-images/' . $building->main_background ?>)">
                <div class="container">
                    <div class="flyout">
                        <h2 class="noti"><?= $building->introduction_title ?><span class="hr"></span></h2>
                        <p><?= $building->introduction_content ?></p>
                    </div>
                </div>
            </div>
            <?php
            	$sectionArray = $building::sectionArray();
            	foreach ($sectionArray as $sectionName => $sectionTab) :
            		$st = $building->getSectionTab($sectionName, $sectionTab);
            ?>
            <div class="section section-swiper">
                <div class="swiper-group">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                        	<?php foreach ($sectionTab as $v): ?>
                            <div class="swiper-slide" style="background-image: url(<?= $building::imageLink($st[$v[0]]['image']) ?>)"></div>
                        	<?php endforeach; ?>
                        </div>
                        <div class="swiper-button swiper-button-prev"></div>
                        <div class="swiper-button swiper-button-next"></div>
                    </div>
                    <div class="swiper-map">
                        <div class="swiper-wrapper">
                        	<?php foreach ($sectionTab as $v): ?>
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button"><?= strtoupper($v[1]) ?></div></div>
                        	<?php endforeach; ?>
                        </div>
                    </div>
                    <div class="slide-contents">
                        <?php foreach ($sectionTab as $v): ?>
                        <div class="slide-content">
                            <p class="bold"><?= $v[1] ?></p>
                            <p><?= $st[$v[0]]['content'] ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="section section-neighborhood">
				<div class="neighborhood-wrap">
					<div class="map-wrap"><div id="neighborhood-map"></div></div>
					<div class="swiper-map">
					    <div class="swiper-wrapper">
					        <div data-type="" class="swiper-slide active"><div class="arrow-up"></div><div class="slide-button" style="border-color: transparent;">ALL</div></div>
					        <div data-type="r" class="swiper-slide swiper-slide-r"><div class="arrow-up"></div><div class="slide-button" style="border-color: #44A3D3;">RESTAURANTS</div></div>
					        <div data-type="m" class="swiper-slide swiper-slide-m"><div class="arrow-up"></div><div class="slide-button" style="border-color: #4CAF50;">MARKETS</div></div>
					        <div data-type="s" class="swiper-slide swiper-slide-s"><div class="arrow-up"></div><div class="slide-button" style="border-color: #E91E63;">SHOPPING</div></div>
					        <div data-type="e" class="swiper-slide swiper-slide-e"><div class="arrow-up"></div><div class="slide-button" style="border-color: #9C27B0;">ENTERTAINMENT</div></div>
					        <div data-type="p" class="swiper-slide swiper-slide-p"><div class="arrow-up"></div><div class="slide-button" style="border-color: #009688;">PARKS</div></div>
						</div>
					</div>
					<div style="display: none;" id="markers" data-lng="106.697443" data-lat="10.786618" data-title="Lancaster Legency" data-address="78 Ton That Thuyet Street, Ward 16, District 4, HCMC, Viet Nam" data-phone="+84 919 866 993">
						<img alt="" src="/frontend/web/themes/lancaster2/resources/images/about-1.jpg" />
						<div data-type="r"
								data-lng="106.701859"
								data-lat="10.784094"
								data-title="Celler de Can Roca"
								data-address="78 Ton That Thuyet Street, Ward 16, District 4, HCMC, Viet Nam"
								data-phone="+84 919 866 993"><img alt="" src="/frontend/web/themes/lancaster2/resources/images/restaurants.jpg" /></div>
						<div data-type="r"
								data-lng="106.693775"
								data-lat="10.788132"
								data-title="Celler de Can Roca"
								data-address="78 Ton That Thuyet Street, Ward 16, District 4, HCMC, Viet Nam"
								data-phone="+84 919 866 993"><img alt="" src="/frontend/web/themes/lancaster2/resources/images/restaurants.jpg" /></div>
						<div data-type="r"
								data-lng="106.695417"
								data-lat="10.789231"
								data-title="Celler de Can Roca"
								data-address="78 Ton That Thuyet Street, Ward 16, District 4, HCMC, Viet Nam"
								data-phone="+84 919 866 993"><img alt="" src="/frontend/web/themes/lancaster2/resources/images/restaurants.jpg" /></div>
						<div data-type="r"
								data-lng="106.704064"
								data-lat="10.781139"
								data-title="Celler de Can Roca"
								data-address="78 Ton That Thuyet Street, Ward 16, District 4, HCMC, Viet Nam"
								data-phone="+84 919 866 993"><img alt="" src="/frontend/web/themes/lancaster2/resources/images/restaurants.jpg" /></div>
						<div data-type="m"
								data-lng="106.700079"
								data-lat="10.785917"
								data-title="Maximax Supermarket"
								data-address="78 Ton That Thuyet Street, Ward 16, District 4, HCMC, Viet Nam"
								data-phone="+84 919 866 993"><img alt="" src="/frontend/web/themes/lancaster2/resources/images/markets.jpg" /></div>
						<div data-type="m"
								data-lng="106.695803"
								data-lat="10.782572"
								data-title="Maximax Supermarket"
								data-address="78 Ton That Thuyet Street, Ward 16, District 4, HCMC, Viet Nam"
								data-phone="+84 919 866 993"><img alt="" src="/frontend/web/themes/lancaster2/resources/images/markets.jpg" /></div>
						<div data-type="s"
								data-lng="106.702139"
								data-lat="10.781470"
								data-title="Shopping"
								data-address="78 Ton That Thuyet Street, Ward 16, District 4, HCMC, Viet Nam"
								data-phone="+84 919 866 993"><img alt="" src="/frontend/web/themes/lancaster2/resources/images/shopping.jpg" /></div>
						<div data-type="e"
								data-lng="106.699253"
								data-lat="10.782218"
								data-title="Entertament"
								data-address="78 Ton That Thuyet Street, Ward 16, District 4, HCMC, Viet Nam"
								data-phone="+84 919 866 993"><img alt="" src="/frontend/web/themes/lancaster2/resources/images/entertainment.jpg" /></div>
						<div data-type="p"
								data-lng="106.704124"
								data-lat="10.785896"
								data-title="Park"
								data-address="78 Ton That Thuyet Street, Ward 16, District 4, HCMC, Viet Nam"
								data-phone="+84 919 866 993"><img alt="" src="/frontend/web/themes/lancaster2/resources/images/park.jpg" /></div>
					</div>
				</div>
			</div>
            <div class="section section-pricing">
                <div class="container">
                    <table>
                        <tbody>
                        <tr class="bgtitle">
                            <th>AREA (SQM)</th>
                            <th>KIND OF APATMENT</th>
                            <th>MONTHLY RATES (VND)</th>
                            <th>DAILY RATES (VND)</th>
                        </tr>
	                	<?php
	                		$pricings = $building->pricing;
	                		foreach ($pricings as $pricing) :
						?>
                        <tr>
                            <td><?= number_format($pricing->area) ?></td>
                            <td><?= $pricing->apartType->name ?></td>
                            <td><?= number_format($pricing->monthly_rates) ?></td>
                            <td><?= number_format($pricing->daily_rates) ?></td>
                        </tr>
						<?php endforeach; ?>
                        </tbody>
                    </table>
                    <div><a href="#" class="book-now">BOOK NOW</a></div>
                    <div><a href="#" class="phone noti"><i class="icon icon-phone"></i>Call 0903 090 909 for more infomation</a></div>
                </div>
            </div>
            <div class="section section-location">
                <div class="container">
                    <div class="clear">
                        <div class="left">
                            <ul class="lancaster-list">
                                <li>
                                    <a data-lat="10.753647" data-lng="106.711543" href="#" class="title noti">Lancaster Legacy</a>
                                    <div class="content-wrap">
                                        <div class="content">
                                            <div class="label">ADDRESS</div>
                                            <div class="text">78 Ton That Thuyet Street, Ward 16, District 4, HCMC, Viet Nam</div>
                                            <div class="label">PHONE</div>
                                            <div class="text">+ 84 8 3 8246810</div>
                                            <div class="label">FAX</div>
                                            <div class="text">+ 84 8 3 8298163</div>
                                            <div class="label">EMAIL</div>
                                            <div class="text">sales@trungthuygroup.vn</div>
                                            <div class="label">HOTLINE</div>
                                            <div class="text">0903 090 909</div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a data-lat="10.780980" data-lng="106.704539" href="#" class="title noti">Lancaster Le Thanh Ton</a>
                                    <div class="content-wrap">
                                        <div class="content">
                                            <div class="label">ADDRESS</div>
                                            <div class="text">22 - 22 Bis Le Thanh Ton Street, Ben Nghe </div>
                                            <div class="label">PHONE</div>
                                            <div class="text">+ 84 8 3 8246666</div>
                                            <div class="label">FAX</div>
                                            <div class="text">+ 84 8 3 8299556 - 3 8298700</div>
                                            <div class="label">EMAIL</div>
                                            <div class="text">sales@trungthuygroup.vn</div>
                                            <div class="label">HOTLINE</div>
                                            <div class="text">0903 090 909</div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a data-lat="10.764702" data-lng="106.686850" href="#" class="title noti">Lancaster Nguyen Trai</a>
                                    <div class="content-wrap">
                                        <div class="content">
                                            <div class="label">ADDRESS</div>
                                            <div class="text">230 Nguyen Trai Street, District 1, HCMC, Viet Nam</div>
                                            <div class="label">PHONE</div>
                                            <div class="text">+ 84 8 3 8246810</div>
                                            <div class="label">FAX</div>
                                            <div class="text">+ 84 8 3 8298163</div>
                                            <div class="label">EMAIL</div>
                                            <div class="text">sales@trungthuygroup.vn</div>
                                            <div class="label">HOTLINE</div>
                                            <div class="text">0903 090 909</div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a data-lat="21.029205" data-lng="105.823676" href="#" class="title noti">Lancaster Ha Noi</a>
                                    <div class="content-wrap">
                                        <div class="content">
                                            <div class="label">ADDRESS</div>
                                            <div class="text">20 Nui Truc Street, Ba Dinh District, Ha Noi, Viet Nam</div>
                                            <div class="label">PHONE</div>
                                            <div class="text">+ 84 4 3 726 3865</div>
                                            <div class="label">FAX</div>
                                            <div class="text">+ 84 4 3 726 3864</div>
                                            <div class="label">EMAIL</div>
                                            <div class="text">sales.lhb@trungthuygroup.vn</div>
                                            <div class="label">HOTLINE</div>
                                            <div class="text">0939 44 2222</div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="location-right-wrap">
                            <div class="right">
                                <div class="social">
                                    <a href="#" class="icon-social icon-facebook"></a>
                                    <a href="#" class="icon-social icon-instagram"></a>
                                    <a href="#" class="icon-social icon-youtube"></a>
                                </div>
                                <div class="ratio-wrap" id="map-wrap">
                                    <div class="ratio"><div id="map"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>