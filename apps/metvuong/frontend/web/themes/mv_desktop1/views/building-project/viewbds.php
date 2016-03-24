<?php
use yii\helpers\Url;

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
//$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/detail.js', ['position' => View::POS_END]);
$this->registerCss('.map-wrap {position: relative;} .map-wrap:after {display: block; content: ""; padding-top: 75%;} .map-inside {position: absolute; width: 100%; height: 100%;} #map {height: 100%;}');

$lbl_updating = Yii::t('general', 'Updating');

$fb_appId = '680097282132293'; // stage.metvuong.com
if(strpos(Yii::$app->urlManager->hostInfo, 'dev.metvuong.com'))
    $fb_appId = '736950189771012';
else if(strpos(Yii::$app->urlManager->hostInfo, 'local.metvuong.com'))
    $fb_appId = '891967050918314';
?>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : <?=$fb_appId?>,
            xfbml      : true,
            version    : 'v2.5'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/vi_VN/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<div class="title-fixed-wrap">
    <div class="container">
        <div class="detail-duan-moi">
            <div class="title-top"><?= strtoupper($model->name)?></div>
            <div class="wrap-duan-moi">
                <div class="gallery-detail swiper-container">
                    <div class="swiper-wrapper">
                        <?php
                        if(!empty($model->gallery)) {
                            $gallery = explode(',', $model->gallery);
                            if (count($gallery) > 0) {
                                foreach ($gallery as $image) {
                                    ?>
                                    <div class="swiper-slide">
                                        <div class="img-show">
                                            <div>
                                                <img
                                                    src="<?= \yii\helpers\Url::to('/store/building-project-images/' . $image) ?>"
                                                    alt="<?= $model->location ?>">
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            }
                        } else {
                            ?>
                            <div class="swiper-slide">
                                <div class="img-show">
                                    <div>
                                        <img src="<?=$model->logoUrl?>" alt="<?=$model->logoUrl?>">
                                    </div>
                                </div>
                            </div>
                        <?php }  ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <div class="item infor-address-duan">
                	<p><?= !empty($model->categories[0]->name) ? \vsoft\ad\models\AdBuildingProject::mb_ucfirst($model->categories[0]->name,'UTF-8') : "Chung cư cao cấp" ?></p>
                    <strong><?= strtoupper($model->name)?></strong>
                    <?= empty($model->location) ? $lbl_updating : $model->location ?>
                    <ul class="pull-right icons-detail">
                        <li><a href="#popup-share-social" class="icon icon-share-td"></a></li>
                        <!--                    <li><a href="#" class="icon save-item" data-id="4115" data-url="/ad/favorite"></a></li>-->
                        <li><a href="#popup-map" class="icon icon-map-loca"></a></li>
                    </ul>
                </div>
                <div class="item infor-time">
                    <p><strong><?=Yii::t('project','Investor')?>: </strong> <?= empty($model->investors[0]->name) ? $lbl_updating : $model->investors[0]->name ?></p>
                    <p><strong><?=Yii::t('project', 'Start date')?>: </strong> <?=empty($model->start_date) ? $lbl_updating : date('d/m/Y', $model->start_date) ?></p>
                    <p><strong><?=Yii::t('project', 'Finish time')?>:</strong> <?=empty($model->estimate_finished) ? $lbl_updating : $model->estimate_finished ?></p>
                </div>
                
                <div class="infor-bds">
	                <ul class="tabProject clearfix">
						<li value="1" class="tabActiveProject">
							<a href="javascript:void(0)" rel="nofollow" style="white-space:nowrap;">Tổng quan</a>
						</li>
						<li value="4">
							<a href="javascript:void(0)" rel="nofollow" style="white-space:nowrap;">Vị trí</a>
						</li>
						<li value="2" class="">
							<a href="javascript:void(0)" rel="nofollow" style="white-space:nowrap;">Hạ tầng - Quy hoạch</a>
						</li>
						<li value="5">
							<a href="javascript:void(0)" rel="nofollow" style="white-space:nowrap;">Bán hàng</a>
						</li>
						<li style="" value="10" class=""><a>Chủ đầu tư</a></li>
					</ul>

					<div class="editor" style="clear: both;">
		                <input type="hidden" name="ctl00$LeftMainContent$_projectDetail$RptVale$ctl00$hdProjectId" id="LeftMainContent__projectDetail_RptVale_hdProjectId_0" value="1">
		                <div class="a1">
		                    <h2><strong>Thông tin dự án Khu dân cư Long Thạnh Mỹ</strong></h2>

							<p>Khu dân cư Long Thạnh Mỹ là dự án có tính nhân bản, diện tích phù hợp với nhu cầu thực tế, giá rẻ, pháp lý đầu đủ, đáp ứng nhu cầu cấp thiết cho nhiều gia đình trong xã hội. Dự án được đầu tư với quy mô hơn 110 nền, chia làm nhiều giai đoạn.</p>

							<p>Các lô đất có diện tích từ 52-100m2, đường mặt tiền 30m, đường nội bộ nhỏ nhất 10m, hệ thống điện nước âm toàn dự án, công viên cây xanh bao phủ, vỉa hè 4m tiện nghi, rộng rãi, giá của dự án có thông điệp là "325 triệu/nền/50%".</p>

							<h3 style="text-align:center"><em><img alt="" src="http://file4.batdongsan.com.vn/2016/03/09/0I4XNtd4/20160309083337-e0a2.jpg" style="height:366px; width:550px"><br>
							Phối cảnh Khu dân cư Long Thạnh Mỹ</em></h3>

							<p><strong>Đánh giá cơ hội đầu tư và niềm tin</strong></p>

							<p>1. Theo số liệu thống kê số lượng giao dịch BĐS ở quận 9 tăng gấp 2 lần trong năm 2015 so với những năm trước. Theo đánh giá, con số này sẽ tăng lên hơn nữa trong năm 2016 với sự nóng sốt của thị trường như hiện nay.</p>

							<p>2. Quận 9 là cửa ngõ trung tâm Tp.HCM - Vũng Tàu - TP. Biên Hoà, Đồng Nai, thuận tiện cho việc trao đổi mua bán hàng hoá.</p>

							<p>3. Quận 9 là điểm nhấn phát triển mới thu hút mạnh nguồn vốn đầu tư, hạ tầng hoàn thiện và đồng bộ với tốc độ nhanh rõ rệt. Sự hình thành của hàng loạt các tiện ích như Aeon Mall, Khu phức hợp Vinhomes quận 9, KĐT Gofl Him Lam, Khu công nghệ cao... Sẽ là điểm mấu chốt kéo theo các khu vực lân cận phát triển.</p>

							<p>4. Đất nền quận 9 được xem là không rẻ, tuy nhiên so với các quận lân cận khác như Thủ Đức, quận 7, quận 2, đất nền quận 9 còn rất mềm so với tiềm năng phát triển, những nhà đầu tư sẽ có được giá trị gia tăng rất cao khi có niềm tin đoán đầu xu thế trước khi thị trường bùng nổ rầm rộ.</p>

							<p>5. Cơ hội sẽ không đợi chờ một ai, bằng chứng là những nhà đầu tư lớn đã có mặt đầy đủ tại thị trường quận 9 và việc còn lại là khách hàng có đủ sự quyết đoán để chọn cho mình 1 thời cơ hay không.</p>

							<p>6. Công ty BĐS Vừa Tầm Tay với đội ngũ chuyên viên tư vấn am hiểu rõ thị trường quận 9 bề dày kinh nghiệm trên 2 năm, tận tâm hỗ trợ khách hàng.</p>

							<p>7. Mọi khó khăn về tài chính Công ty BĐS Vừa Tầm Tay hỗ trợ khách hàng tối đa có thể từ phương thức thanh toán theo từng đợt, đến việc hỗ trợ khách hàng vay vốn ngân hàng từ 50-70%. Công ty BĐS Vừa Tầm Tay cam kết hoàn tiền lại khách hàng 100% khi không ra sổ riêng từng nền.</p>

							<p><strong>Thông tin liên hệ:<br>
							Công ty BĐS Vừa Tầm Tay<br>
							Hotline: 0938.3535.88<br>
							Địa chỉ văn phòng: 169 Thống Nhất, phường Bình Thọ, quận Thủ Đức, Tp.HCM</strong></p>
		                </div>
		            </div>
					<div class="editor" style="display:none;clear: both">
		                <input type="hidden" name="ctl00$LeftMainContent$_projectDetail$RptVale$ctl01$hdProjectId" id="LeftMainContent__projectDetail_RptVale_hdProjectId_1" value="4">
		                <div class="a1">
		                    <p>- Quận 9 được đánh giá là “Trái tim của Khu Đông Sài Gòn” nên nơi đây là điểm kết nối của đa số giới đầu tư.</p>

							<p>- Dự án nằm ngay trên mặt tiền đường 30m Nguyễn Xiển, phường Long Thạnh Mỹ, quận 9 - Đường nội bộ nhỏ nhất 10m, cách trục đường chính Nguyễn Duy Trinh - Trường Lưu - Lã Xuân Oai nối dài 2km.</p>

							<p>- Dự án ngay sát Khu quy hoạch Trường ĐH Sân khấu Điện ảnh Tp.HCM, cách Khu Công nghệ cao 2km, cách chung cư cao cấp của Tập đoàn Vingroup 3km.</p>

							<p>- Đường Lã Xuân Oai mở rộng 40m đã có quyết định, kết nối trực tiếp vào Vành Đai 3, tuyến đường giao thông trọng điểm cao tốc Long Thành - Dầu Giây kết nối Tp.HCM, Vũng Tàu, Đồng Nai.</p>

							<p>- Giao thông kết nối mọi tuyến đường, dễ dàng di chuyển đến trung tâm, quận 2, quận 7, Thủ Đức.</p>

							<p style="text-align:center">&nbsp;</p>

							<p style="text-align:center">&nbsp;</p>

		                </div>
		            </div>
		            <div class="editor" style="clear: both; display: none;">
		                <input type="hidden" name="ctl00$LeftMainContent$_projectDetail$RptVale$ctl02$hdProjectId" id="LeftMainContent__projectDetail_RptVale_hdProjectId_2" value="2">
		                <div class="a1">
		                    <p>- Giá trị liên kết vùng khá lớn, trong phạm vi 1km là khu công nghệ cao rộng 13 ha, những tập đoàn đa quốc gia đã và đang hoạt động, đây là trung tâm hành chính mới quận 9 rộng 34 ha.</p>

							<p>- Giá trị hiện hữu ngay trong dự án về tiện nghi cuộc sống với tổng diện tích công viên cây xanh bao phủ là 2.000m2. Môi trường sống văn hoá, tri thức vì tiếp giáp dự án KDC Lộc Vừng là KNO CBNV Trường ĐH Sân Khấu Điện Ảnh Tp.HCM</p>

							<p>- Cách chợ Gò Công 500m, gần nhà trẻ, trường học cấp 1, 2,3, bệnh viện, ngân hàng.</p>

							<p>-&nbsp; Dự án “KDC VTT” được đầu tư bài bản với hệ thống cơ sở hạ tầng hoàn chỉnh: điện nước âm, hành lang 2 bên đường lát gạch, phủ cây xanh. Khu công viên rộng.</p>

							<p style="text-align:center"><img alt="" src="http://file4.batdongsan.com.vn/2016/03/09/0I4XNtd4/20160309084008-2a6c.jpg" style="height:389px; width:550px"><br>
							<em>Quy hoạch tổng thể dự án Khu dân cư Long Thạnh Mỹ</em></p>

							<p>&nbsp;</p>

		                </div>
		            </div>
		            <div class="editor" style="display:none;clear: both">
		                <input type="hidden" name="ctl00$LeftMainContent$_projectDetail$RptVale$ctl03$hdProjectId" id="LeftMainContent__projectDetail_RptVale_hdProjectId_3" value="5">
		                <div class="a1">
		                    <p>Các nền đất tại dự án có giá chỉ 12-13.5 triệu/m2.</p>

							<p>Tiến độ thanh toán:<br>
							- Đặt chỗ giữ chỗ 10 triệu<br>
							- Sau khi buổi mở bán kết thúc 1 tuần sau thanh toán tiếp 50 triệu<br>
							- 1 tháng sau đi công chứng hợp đồng đặt cọc thanh toán tiếp 60% (bao gồm lần 2)<br>
							- 2 tháng sau đi sang tên chuyển nhượng thanh toán tiếp 30%<br>
							- Phần còn lại thanh toán tiếp khi có sổ</p>

							<p>&nbsp;</p>

							<p>&nbsp;</p>

		                </div>
		            </div>
		            <div class="editor" style="display:none;clear: both;">
				        <div id="LeftMainContent__projectDetail_EnterpriseInfo1_enterpriseForm">
						    <h3>
						       <span>Công ty TNHH Liên Doanh Hoa Việt</span></h3>
						    <div class="info" style="margin-top: 10px; padding: 10px;">
						        <div class="d11">
						            <img id="LeftMainContent__projectDetail_EnterpriseInfo1_imgLogo" title="Công ty TNHH Liên Doanh Hoa Việt" src="http://file4.batdongsan.com.vn/images/no-photo.jpg" style="height:110px;width:110px;">
						        </div>
						        <div class="d12">
						            <ul>
						                <li>
						                    <span id="LeftMainContent__projectDetail_EnterpriseInfo1_lblAddress">Địa chỉ</span>:
						                    Đường Hoàng Hữu Nam, phường Long Thạnh Mỹ, quận 9, Tp.HCM</li>
						                <li>
						                    <span id="LeftMainContent__projectDetail_EnterpriseInfo1_lblPhone">Điện thoại</span>:
						                    0862800021
						                    |
						                    <span id="LeftMainContent__projectDetail_EnterpriseInfo1_lblFax">Fax</span>:
						                    <span style="color:#999999">Đang cập nhật</span>
						                </li>
						                <li>
						                    <span id="LeftMainContent__projectDetail_EnterpriseInfo1_lblWebpage">Website</span>:
						                    <span id="LeftMainContent__projectDetail_EnterpriseInfo1_hplWebpage" title="<span style='color:#999999'>Đang cập nhật</span>"><span style="color:#999999">Đang cập nhật</span></span>
						                </li>
						                <li>
						                    <span id="LeftMainContent__projectDetail_EnterpriseInfo1_lblEmail">Email</span>:
						                    <span style="color:#999999">Đang cập nhật</span>
						                </li>
						                <li>
						                    <span id="LeftMainContent__projectDetail_EnterpriseInfo1_Label1">Chat với người liên hệ công ty</span>
						                    <span class="nickchat">
						                        <img alt="Yahoo" src="http://file4.batdongsan.com.vn/images/Default/images/yahoo.gif">
						                        <a id="LeftMainContent__projectDetail_EnterpriseInfo1_hplChat" href="javascript:void(0)">Nick chat chưa được cập nhật!</a>
						                    </span></li>
						            </ul>
						        </div>
						        <div class="clear">
						        </div>
						    </div>
						    <div class="separable">
						    </div>
						</div>
				    </div>
			    </div>
                <!-- <div class="item detail-infor">
                    <p class="title-attr-duan"><?=Yii::t('ad', 'Description')?></p>
                    <p><?=$model->description ?></p>
                </div>
                <div class="item infor-attr">
                    <p class="title-attr-duan"><?=Yii::t('project', 'Project information')?></p>
                    <ul class="clearfix">
                        <li><strong><?=Yii::t('project', 'Facade width')?>:</strong><?=!empty($model->facade_width) ? $model->facade_width : Yii::t('profile','updating')?></li>
                        <li><strong><?=Yii::t('project', 'Floor')?>:</strong><?=!empty($model->floor_no) ? $model->floor_no : Yii::t('profile','updating')?></li>
                        <li><strong><?=Yii::t('project', 'Lift')?>:</strong><?=!empty($model->lift) ? $model->lift : Yii::t('profile','updating')?></li>
                    </ul>
                </div>
                <div class="item tien-ich-duan">
                    <p class="title-attr-duan"><?=Yii::t('project', 'Facility')?></p>
                    <?php
                    $facilityListId = explode(",", $model->facilities);
                    $facilities = \vsoft\ad\models\AdFacility::find()->where(['id' => $facilityListId])->all();
                    $count_facilities = count($facilities);
                    if($count_facilities > 0){
                        ?>
                        <ul class="clearfix">
                            <?php foreach($facilities as $facility){ ?>
                                <li>
                                    <div><p><span class="icon-ti icon-sport"></span><?= $facility->name ?></p></div>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } else {?>
                        <p><?=$lbl_updating;?></p>
                    <?php }?>
                </div> -->
            </div>
        </div>
    </div>
</div>

<div id="popup-map" class="popup-common hide-popup">
    <div class="wrap-popup">
        <div class="inner-popup">
            <a href="#" class="btn-close-map"><?=Yii::t('project', 'Back')?></a>
            <div id="map" data-lat="<?= $model->lat ?>" data-lng="<?= $model->lng ?>"></div>
        </div>
    </div>
</div>

<div id="popup-share-social" class="popup-common hide-popup">
    <div class="wrap-popup">
        <div class="inner-popup">
            <a href="#" class="btn-close"><span class="icon icon-close"></span></a>
            <div class="wrap-body-popup">
                <span><?=Yii::t('project', 'Share on Social Network')?></span>
                <ul class="clearfix">
                    <li>
                        <a href="#" class="share-facebook">
                            <div class="circle"><div><span class="icon icon-face"></span></div></div>
                        </a>
                    </li>
                    <li>
                        <a href="#popup-email" class="email-btn">
                            <div class="circle"><div><span class="icon icon-email-1"></span></div></div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?=$this->renderAjax('/ad/_partials/shareEmail',[ 'project' => $model, 'yourEmail' => Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->email, 'recipientEmail' => '', 'params' => ['your_email' => false, 'setValueToEmail' => false] ])?>

<script type="text/javascript">
    $(document).ready(function () {
    	$('.tabProject li a').on('click', function (e) {
    		e.preventDefault();
    		var _this = $(this),
    			indexItem = _this.parent().index();
    		$('.tabProject li').removeClass('tabActiveProject');
    		_this.parent().addClass('tabActiveProject');
    		$('.wrap-duan-moi .editor').hide();
    		$('.wrap-duan-moi .editor').eq(indexItem).velocity("fadeIn", { duration: 200 });
    	});
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            spaceBetween: 30
        });

        $('#popup-map').popupMobi({
            btnClickShow: ".icon-map-loca",
            closeBtn: "#popup-map .btn-close-map",
            effectShow: "show-hide",
            funCallBack: function() {
                var mapEl = $('#map');
                var latLng = {lat: Number(mapEl.data('lat')), lng:  Number(mapEl.data('lng'))};
                var map = new google.maps.Map(mapEl.get(0), {
                    center: latLng,
                    zoom: 16,
                    mapTypeControl: false,
                    zoomControl: true,
                    streetViewControl: false
                });

                var marker = new google.maps.Marker({
                    position: latLng,
                    map: map
                });
            }
        });

        $('#popup-share-social').popupMobi({
            btnClickShow: ".icons-detail .icon-share-td",
            closeBtn: ".btn-close, .email-btn, .share-facebook",
            styleShow: "center"
        });

        $('#popup-email').popupMobi({
            btnClickShow: ".email-btn",
            closeBtn: '#popup-email .btn-cancel',
            styleShow: "full"
        });

        $(document).on('click', '.share-facebook', function() {
            FB.ui({
                method: 'share',
                href: '<?=Yii::$app->request->absoluteUrl?>'
            }, function(response){});
        });

    });
</script>