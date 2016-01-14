<?php
	use yii\web\View;
	use yii\helpers\Url;
	use vsoft\express\components\UploadHelper;
	use vsoft\ad\models\AdBuildingProject;
	use vsoft\ad\models\AdAreaType;

	$this->registerCssFile(Yii::$app->view->theme->baseUrl . '/resources/css/owl.carousel.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/jquery.maphilight.js', ['depends' => ['yii\web\YiiAsset']]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/jquery.colorbox-min.js', ['depends' => ['yii\web\YiiAsset']]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/owl.carousel.js', ['depends' => ['yii\web\YiiAsset']]);
	
$script = <<<EOD
	function runSlideDuAn(item1, item2) {
            var sync1 = $(item1);
            var sync2 = $(item2);

            sync1.owlCarousel({
                singleItem : true,
                slideSpeed : 300,
                navigation: true,
                pagination:false,
                afterAction : syncPosition,
                responsiveRefreshRate : 200,
            });

            sync2.owlCarousel({
                items : 15,
                itemsDesktop      : [1199,10],
                itemsDesktopSmall     : [979,10],
                itemsTablet       : [768,8],
                itemsMobile       : [479,4],
                pagination:false,
                responsiveRefreshRate : 100,
                afterInit : function(el){
                el.find(".owl-item").eq(0).addClass("synced");
            }
            });

            function syncPosition(el){
                var current = this.currentItem;
                sync2
                .find(".owl-item")
                .removeClass("synced")
                .eq(current)
                .addClass("synced")
                if(sync2.data("owlCarousel") !== undefined){
                    center(current)
                }
            }

            sync2.on("click", ".owl-item", function(e){
                e.preventDefault();
                var number = $(this).data("owlItem");
                sync1.trigger("owl.goTo",number);
            });

            function center(number){
                var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
                var num = number;
                var found = false;
                for(var i in sync2visible){
                    if(num === sync2visible[i]){
                        var found = true;
                    }
                }

                if(found===false){
                    if(num>sync2visible[sync2visible.length-1]){
                        sync2.trigger("owl.goTo", num - sync2visible.length+2)
                    }else{
                        if(num - 1 === -1){
                            num = 0;
                        }
                        sync2.trigger("owl.goTo", num);
                    }
                } else if(num === sync2visible[sync2visible.length-1]){
                    sync2.trigger("owl.goTo", sync2visible[1])
                } else if(num === sync2visible[0]){
                    sync2.trigger("owl.goTo", num-1)
                }
            }
        }
        
        $(document).ready(function() {
            runSlideDuAn('#sync1','#sync2');
        });
EOD;
	$this->registerJs($script, View::POS_HEAD, 'bxslider' );

$areaTypes = AdBuildingProject::getAreaTypes ();
?>

<div class="detail-template-2">
	<div id="scroll-rearch-reals"></div>
	<div class="intro-duan">
		<div class="container">
			<div class="name-duan">
				<h1>LANCASTER LUX</h1>
				<p>luxury for the privilege few</p>
			</div>
			<div class="wrap-quick-review">
				<div class="quick-view clearfix">
					<div class="">
						<div class="txt-intro">
							<div class="loca-modern">
								<div><span></span>Perfect Location</div>
								<p>In the heart of District 1</p>
							</div>
							<div class="loca-modern">
								<div><span></span>Branded Residence</div>
								<p>Unique branding</p>
							</div>
							<div class="loca-modern">
								<div><span></span>Modern Design</div>
								<p>Designed by International Architects</p>
							</div>
						</div>
					</div>
					<div class="">
						<div class="txt-intro">
							<p>An advanced admin panel allowing your users to easily and quickly customize theme.Layout, styling, color or even fonts, everything is never been so simple.</p>
							<p>In less than 2 minutes, install your fresh wordpress thme and be ready to do somethign beautifull thanks to Lecco.</p>
						</div>
					</div>
					<div class="">
						<div class="txt-intro">
							<a href="#" class="see-project-map">See project location on map</a>
							<a href="#" class="see-gallery">See the Sale Gallery?</a>
							<a href="#">Book us a visit now</a>
						</div>
					</div>
				</div>
			</div>
			<div class="btn-scroll-down"><a href="#">Scroll down for more</a></div>
		</div>
	</div>
    <div class="swiper-container gallery-detail-duan">
        <div class="swiper-wrapper">
            <div class="swiper-slide bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-2.jpg)">
                <a data-lightbox="detail-post" class="group" href="<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-2.jpg"></a>
            </div>
            <div class="swiper-slide bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-4.jpg)">
                <a data-lightbox="detail-post" class="group" href="<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-4.jpg"></a>
            </div>
            <div class="swiper-slide bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/22311_Khai-truong-Pearl-Plaza-10.jpg)">
                <a data-lightbox="detail-post" class="group" href="<?=Yii::$app->view->theme->baseUrl?>/resources/images/22311_Khai-truong-Pearl-Plaza-10.jpg"></a>
            </div>
            <div class="swiper-slide bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/23311_Khai-truong-Pearl-Plaza-11.jpg)">
                <a data-lightbox="detail-post" class="group" href="<?=Yii::$app->view->theme->baseUrl?>/resources/images/23311_Khai-truong-Pearl-Plaza-11.jpg"></a>
            </div>
            <div class="swiper-slide bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-2.jpg)">
                <a data-lightbox="detail-post" class="group" href="<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-2.jpg"></a>
            </div>
            <div class="swiper-slide bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg)">
                <a data-lightbox="detail-post" class="group" href="<?=Yii::$app->view->theme->baseUrl?>/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg"></a>
            </div>
            <div class="swiper-slide bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/621042015085736.jpg)">
                <a data-lightbox="detail-post" class="group" href="<?=Yii::$app->view->theme->baseUrl?>/resources/images/621042015085736.jpg"></a>
            </div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    <div class="container infor-listing">
        <div class="row">
            <div class="col-md-6">
                <p class="name-duan">LANCASTER LUX</p>
                <p class="address-duan">230 NGUYEN TRAI, DISTRICT 1, BEN NGHE WARD</p>
            </div>
            <div class="col-md-6">
                <a href="#" class="view-floorplan pull-left"><span class="icon"></span>View Floorplan</a>
                <a href="#" class="view-map pull-left"><em class="fa fa-map-marker"></em>View map</a>
                <div class="clearfix"></div>
                <div class="pull-right pull-left pdT-20">
                    <button class="btn-favorite"><em class="fa fa-heart-o"></em></button>
                    <button class="btn-contact-agent">contact agent</button>
                </div>
            </div>
        </div>
        <div class="row item-infor-detail">
            <div class="col-md-4">
                <strong>Key details</strong>
                <span class="num-infor">20</span>
            </div>
            <div class="wrap-infor-attr col-md-8">
                <div class="col-md-6">
                    <ul class="clearfix">
                        <li><span>Ownership:</span>Freehold</li>
                        <li><span>Minimum down payment:</span>10%</li>
                        <li><span>Units in building:</span>458</li>
                        <li><span>Unit floor:</span>12</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="clearfix">
                        <li><span>Building floors:</span>40</li>
                        <li><span>building age:</span>new</li>
                        <li><span>Maintenance fee:</span>18,000VND/SQM/Month</li>
                        <li><span>Developer:</span>TTG holding</li>
                        <li><span>Designer:</span>Bim factory</li>
                        <li><span>Contractor:</span>cofico</li>
                    </ul>
                </div>
            </div>
            <a href="#" class="see-less pull-right"><span class="icon"></span><span class="txt-toggle">see less</span></a>
        </div>
        <div class="row item-infor-detail">
            <div class="col-md-4">
                <strong>Amenities</strong>
                <span class="num-infor">20</span>
            </div>
            <div class="wrap-infor-attr col-md-8">
                <div class="col-md-6">
                    <ul class="clearfix">
                        <li>Gym</li>
                        <li>Spa</li>
                        <li>Swimming pool</li>
                        <li>Sky park</li>
                        <li>Club house</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="clearfix">
                        <li>Library</li>
                        <li>Kids playroom</li>
                        <li>Mailbox</li>
                        <li>24/7 concierge</li>
                    </ul>
                </div>
            </div>
            <a href="#" class="see-less pull-right"><span class="icon"></span><span class="txt-toggle">see less</span></a>
        </div>
        <div class="row item-infor-detail">
            <div class="col-md-4">
                <strong>Finishing schedule</strong>
                <span class="num-infor">20</span>
            </div>
            <div class="wrap-infor-attr col-md-8">
                <div class="col-md-6">
                    <ul class="clearfix">
                        <li><span>Kitchen</span></li>
                        <li>Miele or bosch equivalent</li>
                        <li>Refridgerator</li>
                        <li>Kitchen hob</li>
                        <li>Electric water heater</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="clearfix">
                        <li><span>Living room</span></li>
                        <li>Split typeaircon</li>
                        <li>Home automation system</li>
                        <li>Video phone unit</li>
                        <li>Rfid / Password protected door</li>
                    </ul>
                </div>
            </div>
            <a href="#" class="see-less pull-right"><span class="icon"></span><span class="txt-toggle">see less</span></a>
        </div>
        <div class="row item-infor-detail">
            <div class="col-md-4">
                <strong>Nearby landmarks</strong>
            </div>
        </div>
        <div class="row item-infor-detail">
            <div class="col-md-4">
                <strong>Pricing schedule</strong>
            </div>
        </div>
        <div class="row item-infor-detail">
            <div class="col-md-4">
                <strong>Payment terms</strong>
            </div>
        </div>
        <div class="row item-infor-detail">
            <div class="col-md-4">
                <strong>Mortgate calculator</strong>
            </div>
        </div>
        <div class="row item-infor-detail">
            <div class="col-md-4">
                <strong>Project status</strong>
            </div>
        </div>
        <div class="contact-agent-bottom">
            <a href="#" class="wrap-img infor-agent">
                <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/demo-agent.jpg" alt="">
                <span class="name-agent">Hao Do</span>
                <span class="email-agent">hao.do@metvuong.vn</span>
            </a>
            <button class="btn-contact-agent">contact agent</button>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?=Yii::$app->view->theme->baseUrl?>/resources/js/lightbox.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 'auto',
            paginationClickable: true,
            spaceBetween: 0,
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev'
        });

        lightbox.option({
            'resizeDuration': 300,
            'fadeDuration': 400
        });
    });
</script>



<div class="row hide">
	<div class="col-xs-7 pdR-0">
		<div class="wrap-detail-duan">
			<div id="tab-show-1" class="item-detail item-tqda">
				<div id="sync1" class="owl-carousel custom-slide">
					<?php $gallery = explode(',', $model->gallery);
						foreach($gallery as $gal): ?>
					<div class="item bgcover img-big-duan" style="background-image: url(<?= Url::to('/store/building-project-images/' . $gal) ?>)"></div>
					<?php endforeach; ?>
				</div>
				<div id="sync2" class="owl-carousel thumnail-list">
					<?php foreach($gallery as $gal): ?>
					<div class="item bgcover" style="background-image: url(<?= Url::to('/store/building-project-images/' . $gal) ?>)"></div>
					<?php endforeach; ?>
				</div>
			</div>
			<div id="tab-show-2" class="item-detail item-bdvt">
				<?= $model->location_detail ?>
			</div>
			<div id="tab-show-3" class="item-detail item-ti">
				<div class="wrap-img">
					<?php if($model->facilities_detail): ?>
					<?php
						$bpFacilities = explode(',', $model->facilities_detail);
						foreach ($bpFacilities as $bpFacilitie):
					?>
					<img src="<?= Url::to('/store/building-project-images/' . $bpFacilitie) ?>" alt="">
					<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
			<div id="tab-show-4" class="item-detail item-phim3dduan">
				<iframe class="phim3d" frameborder="0" name="1447229561444" allowfullscreen="" src=""></iframe>
			</div>
			<div id="tab-show-5" class="item-detail item-tdxd"></div>
			<?php
		    	$counter = 5;
		    	foreach ($model->adAreaTypes as $areaType) :
		    		$counter++;
		    ?>
		    <div id="tab-show-<?= $counter ?>" class="item-detail">
				<div id="tab-show-<?= $counter ?>1" class="item-tab"></div>
				<?php if($areaType['payment']): ?>
				<div id="tab-show-<?= $counter ?>2" class="item-tab"><?= $areaType['payment'] ?></div>
				<?php endif; ?>
				<?php if($areaType['promotion']): ?>
				<div id="tab-show-<?= $counter ?>3" class="item-tab"><?= $areaType['promotion'] ?></div>
				<?php endif; ?>
				<?php if($areaType['document']): ?>
				<div id="tab-show-<?= $counter ?>4" class="item-tab"><?= $areaType['document'] ?></div>
				<?php endif; ?>
			</div>
		    <?php endforeach; ?>
		</div>
	</div>
	<div class="col-xs-5 pdL-10">
		<div class="header-duan clearfix">
			<a href="#" class="logo-duan"><img src="<?= Url::to('/store/building-project-images/' . $model->logo) ?>" alt=""></a>
			<h2><?= $model->name ?></h2>
		</div>
		<div class="wrap-infor-duan">
			<div class="item-infor has-sub tab-type-1">
				<a href="#tab-show-1" class="clearfix active" href="#">TỔNG QUAN DỰ
					ÁN<em class="fa fa-chevron-right pull-right"></em>
				</a>
				<div class="show-infor in fadeInLeft">
					<table>
						<?php if($model->location): ?>
						<tr>
							<th>VỊ TRÍ DỰ ÁN</th>
							<td><?= $model->location ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->investment_type): ?>
						<tr>
							<th>LOẠI HÌNH ĐẦU TƯ</th>
							<td><?= $model->investment_type ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->land_area): ?>
						<tr>
							<th>DIỆN TÍCH KHU ĐẤT</th>
							<td><?= $model->land_area ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->floor_no): ?>
						<tr>
							<th>SỐ TẦNG</th>
							<td><?= $model->floor_no ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->apartment_no): ?>
						<tr>
							<th>SỐ LƯỢNG SẢN PHẨM</th>
							<td><?= $model->apartment_no ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->start_time): ?>
						<tr>
							<th>THỜI GIAN XÂY DỰNG</th>
							<td><?= $model->start_time ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->estimate_finished): ?>
						<tr>
							<th>DỰ KIẾN HOÀN THÀNH</th>
							<td><?= $model->estimate_finished ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->owner_type): ?>
						<tr>
							<th>HÌNH THỨC SỞ HỮU</th>
							<td><?= $model->owner_type ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->facilities): ?>
						<tr>
							<th>TIỆN ÍCH</th>
							<td><?= $model->facilities ?></td>
						</tr>
						<?php endif; ?>
					</table>
				</div>
			</div>
			<?php if($model->location_detail): ?>
			<div class="item-infor tab-type-2">
				<a href="#tab-show-2" class="clearfix">BẢN ĐỒ VỊ TRÍ</a>
			</div>
			<?php endif; ?>
			<?php if($model->facilities_detail): ?>
			<div class="item-infor tab-type-2">
				<a href="#tab-show-3" class="clearfix">TIỆN ÍCH</a>
			</div>
			<?php endif; ?>
			<?php if($model->video): ?>
			<div class="item-infor has-sub tab-type-3">
				<a href="#tab-show-4" class="clearfix">PHIM 3D DỰ ÁN<em
					class="fa fa-chevron-right pull-right"></em></a>
				<div class="show-infor clearfix">
					<div class="video-thumb">
						<?php
							$videos = array_filter(explode(PHP_EOL, $model->video));
							foreach ($videos as $k => $video):
								parse_str(parse_url($video, PHP_URL_QUERY), $videoParams);
								$videoId = $videoParams['v'];
						?>
						<div class="video-item">
							<a <?= $k == 0 ? 'data-active-first="true" ' : '' ?>class="video-img" href="#" title="" rel="https://www.youtube.com/embed/<?= $videoId ?>">
								<div class="bgcover" style="background-image: url(http://img.youtube.com/vi/<?= $videoId ?>/1.jpg);">
									<span class="icon-play"></span>
								</div>
							</a>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php endif; ?>
			<?php
				if($model->progress):
					$progress = json_decode($model->progress, true);
					$groupByYears = [];
					$firstActive = true;
					
					foreach ($progress as $bpp) {
						$groupByYears[$bpp['year']][] = $bpp;
					}
			?>
			<div class="item-infor tab-tdxd has-sub tab-type-3">
				<a href="#tab-show-5" class="clearfix">TIẾN ĐỘ XÂY DỰNG<em
					class="fa fa-chevron-right pull-right"></em></a>
				<div class="show-infor">
					<?php foreach ($groupByYears as $year => $groupByYear) : ?>
					<h4><em class="fa fa-angle-right mgR-5"></em><?= $year ?></h4>
					<div class="row list-pics-tdxd">
						<?php
							foreach ($groupByYear as $progessGal):
								$images = explode(',', $progessGal['images']);
								array_walk($images, function(&$item) {
									$item = '"' . Url::to('/store/building-project-images/' . $item) . '"';
								});
						?>
						<div class="col-md-3 item-pics">
							<div class="wrap-img">
								<a <?= $firstActive ? 'data-active-first="true" ' : '' ?>href="#" title=""
									data-imgsrc='[<?= implode(',', $images) ?>]'><img
									src=<?= $images[0] ?>></a>
							</div>
							<span><em class="icon-calendar"></em>THÁNG <?= $progessGal['month'] ?>/<?= $progessGal['year'] ?></span>
						</div>
						<?php
							$firstActive = false;
							endforeach;
						?>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
			<?php
				$mapLabels = AdAreaType::mapLabels();
		    	$counter = 5;
		    	foreach ($model->adAreaTypes as $areaType) :
		    		$counter++;
		    ?>
		    <div class="item-infor has-sub tab-kch tab-type-4">
				<a href="#tab-show-<?= $counter ?>" class="clearfix" data-active-first="true"><?= mb_strtoupper($mapLabels[$areaType->type], 'utf-8') ?><em class="fa fa-chevron-right pull-right"></em></a>
				<div class="show-infor fadeInLeft">
					<?php if($areaType->floor_plan): $areaType->floor_plan = json_decode($areaType->floor_plan, true); ?>
					<div class="item-infor">
						<a href="#" class="clearfix" data-active-first="true">MẶT BẰNG</a>
						<div class="show-infor">
							<?php foreach($areaType->floor_plan as $k => $floor_plan):
								if(isset($floor_plan['imagesDetail'][0])) {
									$imagesDetail = explode(',', $floor_plan['imagesDetail'][0]);
									array_walk($imagesDetail, function(&$item) {
										$item = Url::to('/store/building-project-images/' . $item);
									});
								} else {
									$imagesDetail = [];
									$floor_plan['imagesCoordinate'] = [];
								}
								
							?>
								<div class="item-infor">
									<a data-coord="<?= implode(',', $floor_plan['imagesCoordinate']) ?>" data-images="<?= htmlentities(json_encode($imagesDetail)) ?>" href="#tab-show-<?= $counter ?>1" class="clearfix" <?= $k==0 ? 'data-active-first="true" ' : '' ?>data-srcmb="<?= $floor_plan['images'] ? Url::to('/store/building-project-images/' . $floor_plan['images']) : '' ?>"><?= $floor_plan['title'] ?></a>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>
					<?php if($areaType['payment']): ?>
					<div class="item-infor">
						<a href="#tab-show-<?= $counter ?>2" class="clearfix tab-sub-link">GIÁ BÁN & THANH TOÁN</a>
					</div>
					<?php endif; ?>
					<?php if($areaType['promotion']): ?>
					<div class="item-infor">
						<a href="#tab-show-<?= $counter ?>3" class="clearfix tab-sub-link">CHƯƠNG TRÌNH BÁN HÀNG</a>
					</div>
					<?php endif; ?>
					<?php if($areaType['document']): ?>
					<div class="item-infor">
						<a href="#tab-show-<?= $counter ?>4" class="clearfix tab-sub-link">TÀI LIỆU BÁN HÀNG</a>
					</div>
					<?php endif; ?>
				</div>
			</div>
		    <?php endforeach; ?>
		</div>
	</div>
</div>