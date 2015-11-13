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
        var player;
        $(document).ready(function() {
            runSlideDuAn('#sync1','#sync2');

            var videos = new Array();
            $('.video-item').each(function(index, el) {
                var vhref = $(this).find('.video-setup').attr('href');
                var vposter = "/Data/Sites/1/media/Video/video_preload.png";
                var vtitle = $(this).find('.video-setup').attr('title');
                videos.push({src : [vhref],poster:vposter,title:vtitle})
            });


            player = videojs('video');
            player.playList(videos, {
                getVideoSource: function(vid, cb) {
                    cb(vid.src, vid.poster);
                }
            });

            $('.video-item a').click(function(){
                var crrvideo = $(this).parent().prevAll(".video-item").length;
                player.playid(crrvideo);
                player.play();
                return false;
            });
        });
EOD;
	$this->registerJs($script, View::POS_HEAD, 'bxslider' );

	$areaTypes = BuildingProject::getAreaTypes();
?>
<div class="row">
	<div class="col-xs-6 pdR-0">
		<div class="wrap-detail-duan">
			<div id="item-tqda" class="item-detail item-tqda">
				<div id="sync1" class="owl-carousel custom-slide">
					<?php 
						$gallery = explode(',', $model->bpGallery);
						foreach($gallery as $gal):
					?>
					<div class="item bgcover img-big-duan" style="background-image: url(<?= Url::to('/store/building-project-images/' . $gal) ?>)"></div>
					<?php endforeach; ?>
				</div>
				<div id="sync2" class="owl-carousel thumnail-list">
					<?php 
						$gallery = explode(',', $model->bpGallery);
						foreach($gallery as $gal):
					?>
					<div class="item bgcover" style="background-image: url(<?= Url::to('/store/building-project-images/' . $gal) ?>)"></div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php if($model->bpMapLocation): ?>
			<div id="item-bdvt" class="item-detail item-bdvt">
				<div class="wrap-img">
					<img src="<?= Url::to('/store/building-project-images/' . $model->bpMapLocation) ?>" alt="">
				</div>
				<?= $model->bpMapLocationDes ?>
			</div>
			<?php endif; ?>
			<?php if($model->bpFacilitiesDetail): ?>
			<div id="item-ti" class="item-detail item-ti">
				<div class="wrap-img">
					<img src="<?= Url::to('/store/building-project-images/' . $model->bpFacilitiesDetail) ?>" alt="">
				</div>
				<?= $model->bpFacilitiesDetailDes ?>
			</div>
			<?php endif; ?>
			<div id="item-tdxd" class="item-detail item-tdxd"></div>
			<?php
				$bpfApartmentArea = json_decode($model->bpfApartmentArea, true);
				
				$checkBpfApartmentArea = array_filter($bpfApartmentArea);
				if($checkBpfApartmentArea): ?>
				<div id="item-kch" class="item-detail item-tdxd">
				<div id="gbtt" class="item-tab">
					<?= $bpfApartmentArea['payment'] ?>
				</div>
				<div id="ctbh" class="item-tab">
					<?= $bpfApartmentArea['promotion'] ?>
				</div>
				<?php if($bpfApartmentArea['document']): ?>
				<div id="tlbh" class="item-tab">
					<img alt="" src="<?= Url::to('/store/building-project-images/' . $bpfApartmentArea['document']) ?>" />
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<?php
				$bpfCommercialArea = json_decode($model->bpfCommercialArea, true);
				
				$checkBpfCommercialArea = array_filter($bpfCommercialArea);
				if($checkBpfCommercialArea): ?>
			<div id="item-ktm" class="item-detail item-tdxd">
				<div id="gbtt" class="item-tab">
					<?= $bpfCommercialArea['payment'] ?>
				</div>
				<div id="ctbh" class="item-tab">
					<?= $bpfCommercialArea['promotion'] ?>
				</div>
				<?php if($bpfCommercialArea['document']): ?>
				<div id="tlbh" class="item-tab">
					<img alt="" src="<?= Url::to('/store/building-project-images/' . $bpfCommercialArea['document']) ?>" />
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<?php
				$bpfTownhouseArea = json_decode($model->bpfTownhouseArea, true);
				
				$checkBpfTownhouseArea = array_filter($bpfTownhouseArea);
				if($checkBpfTownhouseArea): ?>
			<div id="item-knp" class="item-detail item-tdxd">
				<div id="gbtt" class="item-tab">
					<?= $bpfTownhouseArea['payment'] ?>
				</div>
				<div id="ctbh" class="item-tab">
					<?= $bpfTownhouseArea['promotion'] ?>
				</div>
				<?php if($bpfTownhouseArea['document']): ?>
				<div id="tlbh" class="item-tab">
					<img alt="" src="<?= Url::to('/store/building-project-images/' . $bpfTownhouseArea['document']) ?>" />
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<?php
				$bpfOffice = json_decode($model->bpfOffice, true);
				
				$checkBpfOffice = array_filter($bpfOffice);
				if($checkBpfOffice): ?>
			<div id="item-ko" class="item-detail item-tdxd">
				<div id="gbtt" class="item-tab">
					<?= $bpfOffice['payment'] ?>
				</div>
				<div id="ctbh" class="item-tab">
					<?= $bpfOffice['promotion'] ?>
				</div>
				<?php if($bpfOffice['document']): ?>
				<div id="tlbh" class="item-tab">
					<img alt="" src="<?= Url::to('/store/building-project-images/' . $bpfOffice['document']) ?>" />
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<div id="item-phim3dduan" class="item-detail item-phim3dduan">
				<div class='Module Module-385'>
					<div class="wrap2"></div>
					<div class="video-wrap">
						<video id="video" preload="metadata"
							class="video-js vjs-default-skin" data-setup="" width="100%"
							height="460" poster="<?=Yii::$app->view->theme->baseUrl?>/resources/images/video_preload.png"></video>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-6 pdL-10">
		<div class="header-duan clearfix">
			<a href="#" class="logo-duan"><img src="<?= Url::to('/store/building-project-images/' . $model->bpLogo) ?>" alt=""></a>
			<h2><?= $model->title ?></h2>
		</div>
		<div class="wrap-infor-duan">
			<div class="item-infor active">
				<a href="#item-tqda" class="clearfix" href="#">TỔNG QUAN DỰ ÁN<em
					class="fa fa-chevron-right pull-right"></em></a>
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
			<?php if($model->bpMapLocation): ?>
			<div class="item-infor">
				<a href="#item-bdvt" class="clearfix">BẢN ĐỒ VỊ TRÍ</a>
			</div>
			<?php endif; ?>
			<?php if($model->bpFacilitiesDetail): ?>
			<div class="item-infor">
				<a href="#item-ti" class="clearfix">TIỆN ÍCH</a>
			</div>
			<?php endif; ?>
			<div class="item-infor">
				<a href="#item-phim3dduan" class="clearfix">PHIM 3D DỰ ÁN<em
					class="fa fa-chevron-right pull-right"></em></a>
				<div class="show-infor clearfix">
					<div class="video-thumb">
						<div class="video-item">
							<a class="video-img"
								href="<?=Yii::$app->view->theme->baseUrl?>/resources/video/Văn phòng SmartOffice tiện lợi cho doanh nghiệp vừa và nhỏ - Novaland.mp4"
								title="" rel="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Smart Office.jpg">
								<div class="bgcover"
									style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/video_preload.png);"></div>
								<span></span>
							</a> <a class="video-title" title=""
								href="<?=Yii::$app->view->theme->baseUrl?>/resources/video/Văn phòng SmartOffice tiện lợi cho doanh nghiệp vừa và nhỏ - Novaland.mp4"
								rel="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Smart Office.jpg"> <span>Văn phòng SmartOffice tiện
									lợi cho doanh nghiệp vừa và nhỏ</span>
							</a> <a class="video-setup hidden"
								href="<?=Yii::$app->view->theme->baseUrl?>/resources/video/Văn phòng SmartOffice tiện lợi cho doanh nghiệp vừa và nhỏ - Novaland.mp4"
								title="" rel="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Smart Office.jpg"> </a>
						</div>
						<div class="video-item">
							<a class="video-img"
								href="<?=Yii::$app->view->theme->baseUrl?>/resources/video/SẢN XUẤT PHIM 3D KIẾN TRÚC DỰ ÁN THE EVERRICH 2.mp4"
								title="" rel="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Smart Office.jpg">
								<div class="bgcover"
									style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/0.jpg);"></div> <span></span>
							</a> <a class="video-title" title=""
								href="<?=Yii::$app->view->theme->baseUrl?>/resources/video/SẢN XUẤT PHIM 3D KIẾN TRÚC DỰ ÁN THE EVERRICH 2.mp4"
								rel="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Smart Office.jpg"> <span>Văn phòng SmartOffice tiện
									lợi cho doanh nghiệp vừa và nhỏ</span>
							</a> <a class="video-setup hidden"
								href="<?=Yii::$app->view->theme->baseUrl?>/resources/video/SẢN XUẤT PHIM 3D KIẾN TRÚC DỰ ÁN THE EVERRICH 2.mp4"
								title="" rel="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Smart Office.jpg"> </a>
						</div>
					</div>
				</div>
			</div>
			<?php
				if($model->bpProgress):
					$bpProgress = json_decode($model->bpProgress, true);
					$groupByYears = [];
					
					foreach ($bpProgress as $bpp) {
						$groupByYears[$bpp['year']][] = $bpp;
					}
			?>
			<div class="item-infor tab-tdxd">
				<a href="#item-tdxd" class="clearfix">TIẾN ĐỘ XÂY DỰNG<em
					class="fa fa-chevron-right pull-right"></em></a>
				<div class="show-infor">
					<?php foreach ($groupByYears as $year => $groupByYear) : ?>
					<h4>
						<em class="fa fa-angle-right mgR-5"></em><?= $year ?>
					</h4>
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
								<div>
									<a class="group1" href="#" title=""
										data-imgsrc='[<?= implode(',', $images) ?>]'><img
										src=<?= $images[0] ?>></a>
								</div>
							</div>
							<span><em class="icon-calendar"></em>THÁNG <?= $progessGal['month'] ?>/<?= $progessGal['year'] ?></span>
						</div>
						<?php endforeach; ?>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
			<?php if($checkBpfApartmentArea): ?>
				<div class="item-infor group-menu tab-kch">
					<a href="#item-kch" class="clearfix">KHU CĂN HỘ<em
						class="fa fa-chevron-right pull-right"></em></a>
					<div class="show-infor fadeInLeft">
						<div class="item-infor">
							<a href="#item-kch" class="clearfix">MẶT BẰNG</a>
							<div class="show-infor">
								<?php foreach($bpfApartmentArea['floorPlan'] as $floorPlan): ?>
								<div class="item-infor">
									<a href="#item-kch" class="clearfix"
										data-srcmb="<?= Url::to('/store/building-project-images/' . $floorPlan['images']) ?>"><?= $floorPlan['title'] ?></a>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
						<?php if($bpfApartmentArea['payment']): ?>
						<div class="item-infor">
							<a href="#item-kch" class="clearfix" data-tabsub="#gbtt">GIÁ BÁN & THANH TOÁN</a>
						</div>
						<?php endif; ?>
						<?php if($bpfApartmentArea['promotion']): ?>
						<div class="item-infor">
							<a href="#item-kch" class="clearfix" data-tabsub="#ctbh">CHƯƠNG TRÌNH BÁN HÀNG</a>
						</div>
						<?php endif; ?>
						<?php if($bpfApartmentArea['document']): ?>
						<div class="item-infor">
							<a href="#item-kch" class="clearfix" data-tabsub="#tlbh">TÀI LIỆU BÁN HÀNG</a>
						</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if($checkBpfCommercialArea): ?>
				<div class="item-infor group-menu tab-ktm">
					<a href="#item-ktm" class="clearfix">KHU THƯƠNG MẠI<em
						class="fa fa-chevron-right pull-right"></em></a>
					<div class="show-infor fadeInLeft">
						<div class="item-infor">
							<a href="#item-ktm" class="clearfix">MẶT BẰNG</a>
							<div class="show-infor">
								<?php foreach($bpfCommercialArea['floorPlan'] as $floorPlan): ?>
								<div class="item-infor">
									<a href="#item-ktm" class="clearfix"
										data-srcmb="<?= Url::to('/store/building-project-images/' . $floorPlan['images']) ?>"><?= $floorPlan['title'] ?></a>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
						<?php if($bpfCommercialArea['payment']): ?>
						<div class="item-infor">
							<a href="#item-ktm" class="clearfix" data-tabsub="#gbtt">GIÁ BÁN & THANH TOÁN</a>
						</div>
						<?php endif; ?>
						<?php if($bpfCommercialArea['promotion']): ?>
						<div class="item-infor">
							<a href="#item-ktm" class="clearfix" data-tabsub="#ctbh">CHƯƠNG TRÌNH BÁN HÀNG</a>
						</div>
						<?php endif; ?>
						<?php if($bpfCommercialArea['document']): ?>
						<div class="item-infor">
							<a href="#item-ktm" class="clearfix" data-tabsub="#tlbh">TÀI LIỆU BÁN HÀNG</a>
						</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if($checkBpfTownhouseArea): ?>
				<div class="item-infor group-menu tab-knp">
					<a href="#item-knp" class="clearfix">KHU NHÀ PHỐ<em
						class="fa fa-chevron-right pull-right"></em></a>
					<div class="show-infor fadeInLeft">
						<div class="item-infor">
							<a href="#item-knp" class="clearfix">MẶT BẰNG</a>
							<div class="show-infor">
								<?php foreach($bpfTownhouseArea['floorPlan'] as $floorPlan): ?>
								<div class="item-infor">
									<a href="#item-knp" class="clearfix"
										data-srcmb="<?= Url::to('/store/building-project-images/' . $floorPlan['images']) ?>"><?= $floorPlan['title'] ?></a>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
						<?php if($bpfTownhouseArea['payment']): ?>
						<div class="item-infor">
							<a href="#item-knp" class="clearfix" data-tabsub="#gbtt">GIÁ BÁN & THANH TOÁN</a>
						</div>
						<?php endif; ?>
						<?php if($bpfTownhouseArea['promotion']): ?>
						<div class="item-infor">
							<a href="#item-knp" class="clearfix" data-tabsub="#ctbh">CHƯƠNG TRÌNH BÁN HÀNG</a>
						</div>
						<?php endif; ?>
						<?php if($bpfTownhouseArea['document']): ?>
						<div class="item-infor">
							<a href="#item-knp" class="clearfix" data-tabsub="#tlbh">TÀI LIỆU BÁN HÀNG</a>
						</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if($checkBpfOffice): ?>
				<div class="item-infor group-menu tab-ko">
					<a href="#item-ko" class="clearfix" data-srcmb="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Mat bang tang-02.jpg">KHU OFFICE - OFFICETEL<em
						class="fa fa-chevron-right pull-right"></em></a>
					<div class="show-infor fadeInLeft">
						<div class="item-infor">
							<a href="#item-ko" class="clearfix">MẶT BẰNG</a>
							<div class="show-infor">
								<?php foreach($bpfOffice['floorPlan'] as $floorPlan): ?>
								<div class="item-infor">
									<a href="#item-knp" class="clearfix"
										data-srcmb="<?= Url::to('/store/building-project-images/' . $floorPlan['images']) ?>"><?= $floorPlan['title'] ?></a>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
						<?php if($bpfOffice['payment']): ?>
						<div class="item-infor">
							<a href="#item-ko" class="clearfix" data-tabsub="#gbtt">GIÁ BÁN & THANH TOÁN</a>
						</div>
						<?php endif; ?>
						<?php if($bpfOffice['promotion']): ?>
						<div class="item-infor">
							<a href="#item-ko" class="clearfix" data-tabsub="#ctbh">CHƯƠNG TRÌNH BÁN HÀNG</a>
						</div>
						<?php endif; ?>
						<?php if($bpfOffice['document']): ?>
						<div class="item-infor">
							<a href="#item-ko" class="clearfix" data-tabsub="#tlbh">TÀI LIỆU BÁN HÀNG</a>
						</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
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