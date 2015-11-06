<?php
	use yii\web\View;

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
?>
<div class="row">
	<div class="col-xs-6 pdR-0">
		<div class="wrap-detail-duan">
			<div id="item-tqda" class="item-detail item-tqda">
				<div id="sync1" class="owl-carousel custom-slide">
					<div class="item bgcover img-big-duan"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-1.jpg)"></div>
					<div class="item bgcover img-big-duan"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/87bbds2.jpg)"></div>
					<div class="item bgcover img-big-duan"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg)"></div>
					<div class="item bgcover img-big-duan"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/22311_Khai-truong-Pearl-Plaza-10.jpg)"></div>
					<div class="item bgcover img-big-duan"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-1.jpg)"></div>
					<div class="item bgcover img-big-duan"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-1.jpg)"></div>
				</div>
				<div id="sync2" class="owl-carousel thumnail-list">
					<div class="item bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-1.jpg)"></div>
					<div class="item bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/87bbds2.jpg)"></div>
					<div class="item bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg)"></div>
					<div class="item bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/22311_Khai-truong-Pearl-Plaza-10.jpg)"></div>
					<div class="item bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-1.jpg)"></div>
					<div class="item bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-1.jpg)"></div>
				</div>
			</div>
			<div id="item-bdvt" class="item-detail item-bdvt">
				<div class="wrap-img">
					<img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Galaxy_Ban do_800x500px.jpg" alt="">
				</div>
				<p>Galaxy 9 tọa lạc tại mặt tiền đường Nguyễn Khoái, Quận 4, sở hữu
					vị trí giao thông liên vùng thuận lợi.</p>
				<ul>
					<li>Cách Quận 1 chỉ 5 phút qua cầu Calmette.<span></span></li>
					<li>Cách Quận 5 chỉ 3 phút qua cầu Nguyễn Văn Cừ.<span></span></li>
				</ul>
			</div>
			<div id="item-ti" class="item-detail item-ti">
				<div class="wrap-img">
					<img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Galaxy Siteplan.jpg" alt="">
				</div>
			</div>
			<div id="item-tdxd" class="item-detail item-tdxd"></div>
			<div id="item-kch" class="item-detail item-tdxd">
				<div id="gbtt" class="item-tab">
					<p style="text-align: center;">
						<span style="line-height: 1.4; text-align: left; font-size: 16px;"><span
							style="font-size: 16px;"><strong></strong><span
								style="border: 0px; margin: 0px; padding: 0px; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; font-size: 16px; color: #3a3a3a;"><strong
									style="border: 0px; margin: 0px; padding: 0px; font-size: 12px;"><strong
										style="border: 0px; margin: 0px; padding: 0px; font-size: 16px; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 22.3999996185303px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff;"></strong></strong></span><span
								style="font-size: 16px;"><strong>CHƯƠNG TRÌNH BÁN HÀNG DỰ ÁN <span
										style="color: #ff0000;">GALAXY 9</span><br> “QUÝ III/2015”
								</strong></span><br> <span
								style="font-family: 'Times New Roman';"> (Thời hạn áp dụng từ
									ngày 01/07/2015 đến khi có thông báo mới)</span><span
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: 'Times New Roman'; color: #3a3a3a;"></span><span
								style="font-size: 12px;"></span></span><span
							style="line-height: 1.4;"><strong
								style="font-size: 16px; text-align: -webkit-center; line-height: 1.4;"></strong></span></span>
					</p>
					<p style="text-align: center;">
						<span
							style="line-height: 1.4; text-align: left; font-family: 'Times New Roman'; font-size: 16px;"><br>
						</span>
					</p>
					<strong>1.Lịch thanh toán tiêu chuẩn (PA1):<br>
					</strong><br> <img alt=""
						src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Galaxy9_CTBH_Thanhtoan_chuan.jpg">
					<div>
						<br> <strong>2.Thanh toán rút ngắn:<br>
						</strong><span
							style="font-size: 0px; line-height: 0px; color: #ff0000;"><br>
							Thanh toán rút ngắn:<img alt=""
							src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/_Galaxy9_CTBH_Thanhtoan_Rutngan.jpg"><br> </span>
					</div>
					<div>
						<span style="font-size: 0px; line-height: 0px;"><span
							style="color: #ff0000;">Lịch thanh toán tiêu chuẩn (PA1):Lịch
								thanh toán tiêu chuẩn (PA1):</span><br> </span> <span
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: -webkit-center; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; color: #3a3a3a;">
							<div
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; color: #3a3a3a; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: -webkit-center; background-color: #ffffff;">
								<span size="3"
									style="font-weight: normal; border: 0px; margin: 0px; padding: 0px; font-size: 12px; line-height: 22.3999996185303px;"><strong
									style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #3a3a3a; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><span
										style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><strong
											style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; line-height: 1.4;">
												--------------------------------------------------------------------------------------------------</strong></span></strong></span>
							</div> <span size="3"
							style="font-weight: normal; border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-align: -webkit-center; line-height: 22.3999996185303px; font-family: Tahoma, sans-serif; color: #3a3a3a; background-color: #ffffff;"><strong
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #3a3a3a; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><span
									style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><strong
										style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; color: #3a3a3a; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;">
											<div
												style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; text-align: center;">
												<strong
													style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold;"><span
													style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;">Quý
														khách vui lòng liên hệ Phòng Kinh Doanh để được tư vấn
														thêm</span></strong>
											</div>
									</strong></span></strong></span>
						</span>
						<p>
							<strong
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;"><span
								style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><strong
									style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><span
										style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><br>
									</span></strong></span></strong>
						</p>
						<div style="text-align: center;">
							<strong
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;"><strong
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><strong
									style="font-size: 12px;"><span style="font-size: 14px;"><strong
											style="font-size: 12px;"><span style="font-size: 14px;"><span
													style="font-size: 14px; color: #000000;">DT :</span><span
													style="font-size: 14px; color: #ff0000;">&nbsp;&nbsp;(08)
														3825 55 66</span></span></strong></span></strong></strong></strong>
						</div>
						<strong
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;"><strong
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;">
								<span
								style="border: 0px; margin: 0px; padding: 0px; font-size: 14px; color: #000000;">
									<div style="text-align: center;">
										<strong style="font-size: 12px;"><span
											style="font-size: 14px;"><strong style="font-size: 12px;"><span
													style="font-size: 14px;"><span
														style="font-size: 14px; color: #000000;">Hotline :</span><span
														style="font-size: 14px; color: #ff0000;">&nbsp;0943 79 79
															79</span></span></strong></span></strong>
									</div>
							</span>
						</strong></strong>
						<p class="no-margin">&nbsp;</p>
						<br>
					</div>
				</div>
				<div id="ctbh" class="item-tab">
					<div style="text-align: center;">
						<span style="line-height: 1.4; text-align: left; font-size: 16px;"><strong></strong><span
							style="border: 0px; margin: 0px; padding: 0px; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; font-size: 16px; color: #3a3a3a;"><strong
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px;"><strong
									style="border: 0px; margin: 0px; padding: 0px; font-size: 16px; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 22.3999996185303px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff;"></strong></strong></span><span
							style="font-size: 16px;"><strong>CHƯƠNG TRÌNH BÁN HÀNG DỰ ÁN <span
									style="color: #ff0000;">GALAXY 9</span><br> “QUÝ III/2015"
							</strong></span><br> <span
							style="font-family: 'Times New Roman';"> (Thời hạn áp dụng từ
								ngày 01/07/2015 đến khi có thông báo mới)<br>
						</span></span>
					</div>
					<span
						style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: -webkit-center; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; color: #3a3a3a;">
						<div
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; color: #3a3a3a; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: -webkit-center; background-color: #ffffff;">
							<div style="text-align: justify;">
								<br> <br>
							</div>
							<span size="3"
								style="font-weight: normal; border: 0px; margin: 0px; padding: 0px; font-size: 12px; line-height: 22.3999996185303px;"><strong
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #3a3a3a; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><span
									style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><strong
										style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; line-height: 1.4;">
											--------------------------------------------------------------------------------------------------</strong></span></strong></span>
						</div> <span size="3"
						style="font-weight: normal; border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-align: -webkit-center; line-height: 22.3999996185303px; font-family: Tahoma, sans-serif; color: #3a3a3a; background-color: #ffffff;"><strong
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #3a3a3a; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><span
								style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><strong
									style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; color: #3a3a3a; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;">
										<div
											style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; text-align: center;">
											<strong
												style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold;"><span
												style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;">Quý
													khách vui lòng liên hệ Phòng Kinh Doanh để được tư vấn thêm</span></strong>
										</div>
								</strong></span></strong></span>
					</span>
					<p>
						<strong
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;"><span
							style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><strong
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><span
									style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><br>
								</span></strong></span></strong>
					</p>
					<div style="text-align: center;">
						<strong
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;"><strong
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><strong
								style="font-size: 12px;"><span style="font-size: 14px;"><strong
										style="font-size: 12px;"><span style="font-size: 14px;"><span
												style="font-size: 14px; color: #000000;">DT :</span><span
												style="font-size: 14px; color: #ff0000;">&nbsp;&nbsp;(08)
													3825 55 66</span></span></strong></span></strong></strong></strong>
					</div>
					<strong
						style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;"><strong
						style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;">
							<span
							style="border: 0px; margin: 0px; padding: 0px; font-size: 14px; color: #000000;">
								<div style="text-align: center;">
									<strong style="font-size: 12px;"><span style="font-size: 14px;"><strong
											style="font-size: 12px;"><span style="font-size: 14px;"><span
													style="font-size: 14px; color: #000000;">Hotline :</span><span
													style="font-size: 14px; color: #ff0000;">&nbsp;0943 79 79
														79</span></span></strong></span></strong>
								</div>
						</span>
					</strong></strong>
					<p class="no-margin">&nbsp;</p>
				</div>
			</div>
			<div id="item-ktm" class="item-detail item-tdxd">
				<div id="gbtt" class="item-tab">
					<p style="text-align: center;">
						<span style="line-height: 1.4; text-align: left; font-size: 16px;"><span
							style="font-size: 16px;"><strong></strong><span
								style="border: 0px; margin: 0px; padding: 0px; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; font-size: 16px; color: #3a3a3a;"><strong
									style="border: 0px; margin: 0px; padding: 0px; font-size: 12px;"><strong
										style="border: 0px; margin: 0px; padding: 0px; font-size: 16px; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 22.3999996185303px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff;"></strong></strong></span><span
								style="font-size: 16px;"><strong>CHƯƠNG TRÌNH BÁN HÀNG DỰ ÁN <span
										style="color: #ff0000;">GALAXY 9</span><br> “QUÝ III/2015”
								</strong></span><br> <span
								style="font-family: 'Times New Roman';"> (Thời hạn áp dụng từ
									ngày 01/07/2015 đến khi có thông báo mới)</span><span
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: 'Times New Roman'; color: #3a3a3a;"></span><span
								style="font-size: 12px;"></span></span><span
							style="line-height: 1.4;"><strong
								style="font-size: 16px; text-align: -webkit-center; line-height: 1.4;"></strong></span></span>
					</p>
					<p style="text-align: center;">
						<span
							style="line-height: 1.4; text-align: left; font-family: 'Times New Roman'; font-size: 16px;"><br>
						</span>
					</p>
					<strong>1.Lịch thanh toán tiêu chuẩn (PA1):<br>
					</strong><br> <img alt=""
						src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Galaxy9_CTBH_Thanhtoan_chuan.jpg">
					<div>
						<br> <strong>2.Thanh toán rút ngắn:<br>
						</strong><span
							style="font-size: 0px; line-height: 0px; color: #ff0000;"><br>
							Thanh toán rút ngắn:<img alt=""
							src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/_Galaxy9_CTBH_Thanhtoan_Rutngan.jpg"><br> </span>
					</div>
					<div>
						<span style="font-size: 0px; line-height: 0px;"><span
							style="color: #ff0000;">Lịch thanh toán tiêu chuẩn (PA1):Lịch
								thanh toán tiêu chuẩn (PA1):</span><br> </span> <span
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: -webkit-center; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; color: #3a3a3a;">
							<div
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; color: #3a3a3a; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: -webkit-center; background-color: #ffffff;">
								<span size="3"
									style="font-weight: normal; border: 0px; margin: 0px; padding: 0px; font-size: 12px; line-height: 22.3999996185303px;"><strong
									style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #3a3a3a; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><span
										style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><strong
											style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; line-height: 1.4;">
												--------------------------------------------------------------------------------------------------</strong></span></strong></span>
							</div> <span size="3"
							style="font-weight: normal; border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-align: -webkit-center; line-height: 22.3999996185303px; font-family: Tahoma, sans-serif; color: #3a3a3a; background-color: #ffffff;"><strong
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #3a3a3a; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><span
									style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><strong
										style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; color: #3a3a3a; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;">
											<div
												style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; text-align: center;">
												<strong
													style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold;"><span
													style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;">Quý
														khách vui lòng liên hệ Phòng Kinh Doanh để được tư vấn
														thêm</span></strong>
											</div>
									</strong></span></strong></span>
						</span>
						<p>
							<strong
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;"><span
								style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><strong
									style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><span
										style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><br>
									</span></strong></span></strong>
						</p>
						<div style="text-align: center;">
							<strong
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;"><strong
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><strong
									style="font-size: 12px;"><span style="font-size: 14px;"><strong
											style="font-size: 12px;"><span style="font-size: 14px;"><span
													style="font-size: 14px; color: #000000;">DT :</span><span
													style="font-size: 14px; color: #ff0000;">&nbsp;&nbsp;(08)
														3825 55 66</span></span></strong></span></strong></strong></strong>
						</div>
						<strong
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;"><strong
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;">
								<span
								style="border: 0px; margin: 0px; padding: 0px; font-size: 14px; color: #000000;">
									<div style="text-align: center;">
										<strong style="font-size: 12px;"><span
											style="font-size: 14px;"><strong style="font-size: 12px;"><span
													style="font-size: 14px;"><span
														style="font-size: 14px; color: #000000;">Hotline :</span><span
														style="font-size: 14px; color: #ff0000;">&nbsp;0943 79 79
															79</span></span></strong></span></strong>
									</div>
							</span>
						</strong></strong>
						<p class="no-margin">&nbsp;</p>
						<br>
					</div>
				</div>
				<div id="ctbh" class="item-tab">
					<div style="text-align: center;">
						<span style="line-height: 1.4; text-align: left; font-size: 16px;"><strong></strong><span
							style="border: 0px; margin: 0px; padding: 0px; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; font-size: 16px; color: #3a3a3a;"><strong
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px;"><strong
									style="border: 0px; margin: 0px; padding: 0px; font-size: 16px; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 22.3999996185303px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff;"></strong></strong></span><span
							style="font-size: 16px;"><strong>CHƯƠNG TRÌNH BÁN HÀNG DỰ ÁN <span
									style="color: #ff0000;">GALAXY 9</span><br> “QUÝ III/2015"
							</strong></span><br> <span
							style="font-family: 'Times New Roman';"> (Thời hạn áp dụng từ
								ngày 01/07/2015 đến khi có thông báo mới)<br>
						</span></span>
					</div>
					<span
						style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: -webkit-center; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; color: #3a3a3a;">
						<div
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; color: #3a3a3a; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: -webkit-center; background-color: #ffffff;">
							<div style="text-align: justify;">
								<br> <br>
							</div>
							<span size="3"
								style="font-weight: normal; border: 0px; margin: 0px; padding: 0px; font-size: 12px; line-height: 22.3999996185303px;"><strong
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #3a3a3a; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><span
									style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><strong
										style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; line-height: 1.4;">
											--------------------------------------------------------------------------------------------------</strong></span></strong></span>
						</div> <span size="3"
						style="font-weight: normal; border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-align: -webkit-center; line-height: 22.3999996185303px; font-family: Tahoma, sans-serif; color: #3a3a3a; background-color: #ffffff;"><strong
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #3a3a3a; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><span
								style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><strong
									style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; color: #3a3a3a; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;">
										<div
											style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; text-align: center;">
											<strong
												style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold;"><span
												style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;">Quý
													khách vui lòng liên hệ Phòng Kinh Doanh để được tư vấn thêm</span></strong>
										</div>
								</strong></span></strong></span>
					</span>
					<p>
						<strong
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;"><span
							style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><strong
								style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><span
									style="border: 0px; margin: 0px; padding: 0px; font-size: 14px;"><br>
								</span></strong></span></strong>
					</p>
					<div style="text-align: center;">
						<strong
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;"><strong
							style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><strong
								style="font-size: 12px;"><span style="font-size: 14px;"><strong
										style="font-size: 12px;"><span style="font-size: 14px;"><span
												style="font-size: 14px; color: #000000;">DT :</span><span
												style="font-size: 14px; color: #ff0000;">&nbsp;&nbsp;(08)
													3825 55 66</span></span></strong></span></strong></strong></strong>
					</div>
					<strong
						style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; color: #000000; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; font-family: Tahoma, sans-serif; line-height: 16.8016796112061px; text-align: left; background-color: #ffffff;"><strong
						style="border: 0px; margin: 0px; padding: 0px; font-size: 12px; font-weight: bold; font-family: Tahoma, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8016796112061px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;">
							<span
							style="border: 0px; margin: 0px; padding: 0px; font-size: 14px; color: #000000;">
								<div style="text-align: center;">
									<strong style="font-size: 12px;"><span style="font-size: 14px;"><strong
											style="font-size: 12px;"><span style="font-size: 14px;"><span
													style="font-size: 14px; color: #000000;">Hotline :</span><span
													style="font-size: 14px; color: #ff0000;">&nbsp;0943 79 79
														79</span></span></strong></span></strong>
								</div>
						</span>
					</strong></strong>
					<p class="no-margin">&nbsp;</p>
				</div>
			</div>
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
			<a href="#" class="logo-duan"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/logo-duan.jpg" alt=""></a>
			<h2>KINGSTON RESIDENCE</h2>
		</div>
		<div class="wrap-infor-duan">
			<div class="item-infor active">
				<a href="#item-tqda" class="clearfix" href="#">TỔNG QUAN DỰ ÁN<em
					class="fa fa-chevron-right pull-right"></em></a>
				<div class="show-infor in fadeInLeft">
					<table>
						<tr>
							<th>VỊ TRÍ DỰ ÁN</th>
							<td>250 Nguyễn Văn Trỗi, Phường 8, Quận Phú Nhuận, Tp HCM</td>
						</tr>
						<tr>
							<th>LOẠI HÌNH ĐẦU TƯ</th>
							<td>Khu căn hộ - Thương mại</td>
						</tr>
						<tr>
							<th>DIỆN TÍCH KHU ĐẤT</th>
							<td>Khoảng 4585m²</td>
						</tr>
						<tr>
							<th>SỐ TẦNG</th>
							<td>22 tầng / 02 tầng hầm</td>
						</tr>
						<tr>
							<th>SỐ LƯỢNG SẢN PHẨM</th>
							<td>285 căn hộ + Office + Retail</td>
						</tr>
						<tr>
							<th>THỜI GIAN XÂY DỰNG</th>
							<td>Quý 2/2015</td>
						</tr>
						<tr>
							<th>DỰ KIẾN HOÀN THÀNH</th>
							<td>Quý 4/2017</td>
						</tr>
						<tr>
							<th>HÌNH THỨC SỞ HỮU</th>
							<td>Căn hộ ổn định lâu dài ( Sổ hồng )</td>
						</tr>
						<tr>
							<th>TIỆN ÍCH</th>
							<td>Hồ bơi, phòng tập gym, siêu thị,..</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="item-infor">
				<a href="#item-bdvt" class="clearfix">BẢN ĐỒ VỊ TRÍ</a>
			</div>
			<div class="item-infor">
				<a href="#item-ti" class="clearfix">TIỆN ÍCH</a>
			</div>
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
			<div class="item-infor tab-tdxd">
				<a href="#item-tdxd" class="clearfix">TIẾN ĐỘ XÂY DỰNG<em
					class="fa fa-chevron-right pull-right"></em></a>
				<div class="show-infor">
					<h4>
						<em class="fa fa-angle-right mgR-5"></em>2014
					</h4>
					<div class="row list-pics-tdxd">
						<div class="col-md-3 item-pics">
							<div class="wrap-img">
								<div>
									<a class="group1" href="#" title=""
										data-imgsrc='["<?=Yii::$app->view->theme->baseUrl?>/resources/images/tdgalaxy9754.jpg","<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-2.jpg","<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-1.jpg","<?=Yii::$app->view->theme->baseUrl?>/resources/images/22311_Khai-truong-Pearl-Plaza-7.jpg","<?=Yii::$app->view->theme->baseUrl?>/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg","<?=Yii::$app->view->theme->baseUrl?>/resources/images/22311_Khai-truong-Pearl-Plaza-9.jpg","<?=Yii::$app->view->theme->baseUrl?>/resources/images/tdgalaxy9754.jpg"]'><img
										src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/tdgalaxy9754.jpg"></a>
								</div>
							</div>
							<span><em class="icon-calendar"></em>THÁNG 09/2015</span>
						</div>
						<div class="col-md-3 item-pics">
							<div class="wrap-img">
								<div>
									<a class="group2" href="#" title=""
										data-imgsrc='["<?=Yii::$app->view->theme->baseUrl?>/resources/images/galaxy9_250515-4.jpg","<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-2.jpg","<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-1.jpg"]'><img
										src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/công-tác-sơn-nước.jpg"></a>
								</div>
							</div>
							<span><em class="icon-calendar"></em>THÁNG 09/2015</span>
						</div>
						<div class="col-md-3 item-pics">
							<div class="wrap-img">
								<div>
									<img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/tdxd-galaxy965.jpg">
								</div>
							</div>
							<span><em class="icon-calendar"></em>THÁNG 09/2015</span>
						</div>
						<div class="col-md-3 item-pics">
							<div class="wrap-img">
								<div>
									<img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/1công-tác-xây-gạch.jpg">
								</div>
							</div>
							<span><em class="icon-calendar"></em>THÁNG 09/2015</span>
						</div>
					</div>
					<h4>
						<em class="fa fa-angle-right mgR-5"></em>2015
					</h4>
					<div class="row list-pics-tdxd">
						<div class="col-md-3 item-pics">
							<div class="wrap-img">
								<div>
									<img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/galaxy9_250515-4.jpg">
								</div>
							</div>
							<span><em class="icon-calendar"></em>THÁNG 09/2015</span>
						</div>
						<div class="col-md-3 item-pics">
							<div class="wrap-img">
								<div>
									<img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/tdgalaxy9754.jpg">
								</div>
							</div>
							<span><em class="icon-calendar"></em>THÁNG 09/2015</span>
						</div>
						<div class="col-md-3 item-pics">
							<div class="wrap-img">
								<div>
									<img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/tdgalaxy9754.jpg">
								</div>
							</div>
							<span><em class="icon-calendar"></em>THÁNG 09/2015</span>
						</div>
						<div class="col-md-3 item-pics">
							<div class="wrap-img">
								<div>
									<img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/tdgalaxy9754.jpg">
								</div>
							</div>
							<span><em class="icon-calendar"></em>THÁNG 09/2015</span>
						</div>
					</div>
				</div>
			</div>
			<div class="item-infor group-menu tab-kch">
				<a href="#item-kch" class="clearfix"
					data-srcmb="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Mat bang tang-02.jpg">KHU CĂN HỘ<em
					class="fa fa-chevron-right pull-right"></em></a>
				<div class="show-infor fadeInLeft">
					<div class="item-infor">
						<a href="#item-kch" class="clearfix"
							data-srcmb="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Mat bang tang-02.jpg">MẶT BẰNG</a>
						<div class="show-infor">
							<div class="item-infor">
								<a href="#item-kch" class="clearfix"
									data-srcmb="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Mat bang tang-02.jpg">Mặt bằng:Tầng 7-14</a>
							</div>
							<div class="item-infor">
								<a href="#item-kch" class="clearfix"
									data-srcmb="<?=Yii::$app->view->theme->baseUrl?>/resources/images/MB-Tang-dien-hinh-01.jpg">Mặt bằng:Tầng
									15-18</a>
							</div>
							<div class="item-infor">
								<a href="#item-kch" class="clearfix"
									data-srcmb="<?=Yii::$app->view->theme->baseUrl?>/resources/images/MB-Tang-dien-hinh-03.jpg">Mặt bằng:Tầng
									19-22</a>
							</div>
						</div>
					</div>
					<div class="item-infor">
						<a href="#item-kch" class="clearfix" data-tabsub="#gbtt">GIÁ BÁN &
							THANH TOÁN</a>
					</div>
					<div class="item-infor">
						<a href="#item-kch" class="clearfix" data-tabsub="#ctbh">CHƯƠNG
							TRÌNH BÁN HÀNG</a>
					</div>
				</div>
			</div>
			<div class="item-infor group-menu tab-ktm">
				<a href="#item-ktm" class="clearfix"
					data-srcmb="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Mat bang tang-02.jpg">KHU THƯƠNG MẠI<em
					class="fa fa-chevron-right pull-right"></em></a>
				<div class="show-infor">
					<div class="item-infor">
						<a href="#item-ktm" class="clearfix"
							data-srcmb="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Mat bang tang-02.jpg">MẶT BẰNG</a>
						<div class="show-infor" id="">
							<div class="item-infor">
								<a href="#item-ktm" class="clearfix"
									data-srcmb="<?=Yii::$app->view->theme->baseUrl?>/resources/images/Mat bang tang-02.jpg">Mặt bằng:Tầng 7-14</a>
							</div>
							<div class="item-infor">
								<a href="#item-ktm" class="clearfix"
									data-srcmb="<?=Yii::$app->view->theme->baseUrl?>/resources/images/MB-Tang-dien-hinh-01.jpg">Mặt bằng:Tầng
									15-18</a>
							</div>
							<div class="item-infor">
								<a href="#item-ktm" class="clearfix"
									data-srcmb="<?=Yii::$app->view->theme->baseUrl?>/resources/images/MB-Tang-dien-hinh-03.jpg">Mặt bằng:Tầng
									19-22</a>
							</div>
						</div>
					</div>
					<div class="item-infor">
						<a href="#item-ktm" class="clearfix" data-tabsub="#gbtt">GIÁ BÁN &
							THANH TOÁN</a>
					</div>
					<div class="item-infor">
						<a href="#item-ktm" class="clearfix" data-tabsub="#ctbh">CHƯƠNG
							TRÌNH BÁN HÀNG</a>
					</div>
				</div>
			</div>
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