<?php
	use yii\web\View;
use yii\helpers\Url;
use vsoft\buildingProject\models\BuildingProject;

	$this->registerCssFile(Yii::$app->view->theme->baseUrl . '/resources/css/owl.carousel.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/video.js', ['depends' => ['yii\web\YiiAsset']]);
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

$areaTypes = BuildingProject::getAreaTypes ();
?>
<div class="row">
	<div class="col-xs-6 pdR-0">
		<div class="wrap-detail-duan">
			<div id="tab-show-1" class="item-detail item-tqda">
				<div id="sync1" class="owl-carousel custom-slide">
					<?php $gallery = explode(',', $model->bpGallery);
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
				<div class="wrap-img">
					<img src="<?= Url::to('/store/building-project-images/' . $model->bpMapLocation) ?>" alt="">
				</div>
				<?= $model->bpMapLocationDes ?>
			</div>
			<div id="tab-show-3" class="item-detail item-ti">
				<div class="wrap-img">
					<img src="<?= Url::to('/store/building-project-images/' . $model->bpFacilitiesDetail) ?>" alt="">
				</div>
				<?= $model->bpFacilitiesDetailDes ?>
			</div>
			<div id="tab-show-4" class="item-detail item-phim3dduan">
				<iframe class="phim3d" frameborder="0" name="1447229561444" allowfullscreen="" src=""></iframe>
			</div>
			<div id="tab-show-5" class="item-detail item-tdxd"></div>
			<?php
		    	$areaTypes = BuildingProject::getAreaTypes();
		    	$counter = 5;
		    	foreach ($areaTypes as $name => $label) :
		    		$counter++;
		    		$areaType = json_decode($model->$name, true);
		    		if(array_filter($areaType)) :
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
				<div id="tab-show-<?= $counter ?>4" class="item-tab"><img src="<?= Url::to('/store/building-project-images/' . $areaType['document']) ?>" alt=""></div>
				<?php endif; ?>
			</div>
		    <?php endif; endforeach; ?>
		</div>
	</div>
	<div class="col-xs-6 pdL-10">
		<div class="header-duan clearfix">
			<a href="#" class="logo-duan"><img src="<?= Url::to('/store/building-project-images/' . $model->bpLogo) ?>" alt=""></a>
			<h2><?= $model->title ?></h2>
		</div>
		<div class="wrap-infor-duan">
			<div class="item-infor has-sub tab-type-1">
				<a href="#tab-show-1" class="clearfix active" href="#">TỔNG QUAN DỰ
					ÁN<em class="fa fa-chevron-right pull-right"></em>
				</a>
				<div class="show-infor in fadeInLeft">
					<table>
						<?php if($model->bpLocation): ?>
						<tr>
							<th>VỊ TRÍ DỰ ÁN</th>
							<td><?= $model->bpLocation ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->bpType): ?>
						<tr>
							<th>LOẠI HÌNH ĐẦU TƯ</th>
							<td><?= $model->bpType ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->bpAcreage): ?>
						<tr>
							<th>DIỆN TÍCH KHU ĐẤT</th>
							<td><?= $model->bpAcreage ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->bpFloorNo): ?>
						<tr>
							<th>SỐ TẦNG</th>
							<td><?= $model->bpFloorNo ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->bpApartmentNo): ?>
						<tr>
							<th>SỐ LƯỢNG SẢN PHẨM</th>
							<td><?= $model->bpApartmentNo ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->bpStartTime): ?>
						<tr>
							<th>THỜI GIAN XÂY DỰNG</th>
							<td><?= $model->bpStartTime ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->bpEstimateFinished): ?>
						<tr>
							<th>DỰ KIẾN HOÀN THÀNH</th>
							<td><?= $model->bpEstimateFinished ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->bpOwnerType): ?>
						<tr>
							<th>HÌNH THỨC SỞ HỮU</th>
							<td><?= $model->bpOwnerType ?></td>
						</tr>
						<?php endif; ?>
						<?php if($model->bpFacilities): ?>
						<tr>
							<th>TIỆN ÍCH</th>
							<td><?= $model->bpFacilities ?></td>
						</tr>
						<?php endif; ?>
					</table>
				</div>
			</div>
			<div class="item-infor tab-type-2">
				<a href="#tab-show-2" class="clearfix">BẢN ĐỒ VỊ TRÍ</a>
			</div>
			<div class="item-infor tab-type-2">
				<a href="#tab-show-3" class="clearfix">TIỆN ÍCH</a>
			</div>
			<div class="item-infor has-sub tab-type-3">
				<a href="#tab-show-4" class="clearfix">PHIM 3D DỰ ÁN<em
					class="fa fa-chevron-right pull-right"></em></a>
				<div class="show-infor clearfix">
					<div class="video-thumb">
						<?php
							$bpVideos = array_filter(explode(PHP_EOL, $model->bpVideo));
							foreach ($bpVideos as $k => $bpVideo):
								parse_str(parse_url($bpVideo, PHP_URL_QUERY), $videoParams);
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
			<?php
				if($model->bpProgress):
					$bpProgress = json_decode($model->bpProgress, true);
					$groupByYears = [];
					$firstActive = true;
					
					foreach ($bpProgress as $bpp) {
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
		    	$counter = 5;
		    	foreach ($areaTypes as $name => $label) :
		    		$counter++;
		    		$areaType = json_decode($model->$name, true);
		    		if(array_filter($areaType)) :
		    ?>
		    <div class="item-infor has-sub tab-kch tab-type-4">
				<a href="#tab-show-<?= $counter ?>" class="clearfix" data-active-first="true"><?= mb_strtoupper($label, 'utf-8') ?><em class="fa fa-chevron-right pull-right"></em></a>
				<div class="show-infor fadeInLeft">
					<div class="item-infor">
						<a href="#" class="clearfix" data-active-first="true">MẶT BẰNG</a>
						<div class="show-infor">
							<?php foreach($areaType['floorPlan'] as $k => $floorPlan): ?>
								<div class="item-infor">
									<a href="#tab-show-<?= $counter ?>1" class="clearfix" <?= $k==0 ? 'data-active-first="true" ' : '' ?>data-srcmb="<?= Url::to('/store/building-project-images/' . $floorPlan['images']) ?>"><?= $floorPlan['title'] ?></a>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
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
		    <?php endif; endforeach; ?>
		</div>
	</div>
</div>
<div class="social-share">
	<ul>
		<li><a href="#"><em class="fa fa-facebook"></em></a></li>
		<li><a href="#"><em class="fa fa-twitter"></em></a></li>
		<li><a href="#"><em class="fa fa-instagram"></em></a></li>
		<li><a href="#"><em class="fa fa-google-plus"></em></a></li>
		<li><a href="#"><em class="fa fa-youtube-play"></em></a></li>
		<li><a href="#"><em class="fa fa-pinterest"></em></a></li>
		<li><a href="#"><em class="fa fa-linkedin"></em></a></li>
	</ul>
</div>