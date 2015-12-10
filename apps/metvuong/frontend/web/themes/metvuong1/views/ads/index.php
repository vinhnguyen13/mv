<?php
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use vsoft\ad\models\AdCategory;
$this->title = Yii::t('express','We offer exeptional amenities and renowned white - glove services');
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/gmap.js', ['position' => View::POS_END]);
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=apiLoaded', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/imagesloaded.3.0.4.js', ['position' => View::POS_END]);
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/lightbox.min.js', ['position' => View::POS_END]);
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/jquery.bxslider.js', ['position' => View::POS_END]);
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/search-map.js', ['position' => View::POS_END]);
$this->registerJs('var categories = ' . json_encode(AdCategory::find()->indexBy('id')->asArray(true)->all()) . ';', View::POS_BEGIN);
?>
<div class="list-filters-result">
	<form id="map-search-form" action="<?= Url::to('/real-estate/result') ?>" method="post">
		<input type="hidden" name="result" value="1" />
		<input type="hidden" name="cityId" id="city-id" value="<?= Yii::$app->request->get('city') ?>" />
		<input type="hidden" name="districtId" id="district-id" value="<?= Yii::$app->request->get('district') ?>" />
		<input type="hidden" name="categoryId" id="category-id" value="<?= Yii::$app->request->get('category') ?>" />
		<input type="hidden" name="orderBy" id="order-by" value="created_at" />
	    <ul class="container clearfix">
	        <li>
	            <a href="#">Giá</a>
	        </li>
	        <li>
	            <a href="#">Diện tích</a>
	        </li>
	        <li>
	            <a href="#">Phòng ngủ</a>
	        </li>
	        <li>
	            <a href="#">Loại BDS</a>
	        </li>
	        <li>
	            <a href="#">Khác</a>
	        </li>
	    </ul>
    </form>
</div>
<div class="col-md-8 wrap-map-result">
    <div class="container-map">
    	<div id="map" style="height: 100%;"></div>
    </div>
</div>
<div class="col-md-4 result-items">
    <div class="wrap-col-fixed-result clearfix">
        <h1 id="search-title" class="zsg-content_collapsed">Listings</h1>
        <span class="num-results"><span id="count-listing">0</span> results.</span>
        <ul id="order-by-tab" class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a data-order="created_at" href="#" aria-controls="moi-nhat" role="tab" data-toggle="tab">Mới nhất</a></li>
            <li role="presentation"><a data-order="price" href="#" aria-controls="re-nhat" role="tab" data-toggle="tab">Rẻ nhất</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="moi-nhat">
                <ul class="list-results clearfix">
                    <li>
                        <div class="loading_new_feed">          
                            <div class="_2iwr"></div>            
                            <div class="_2iws"></div>            
                            <div class="_2iwt"></div>            
                            <div class="_2iwu"></div>            
                            <div class="_2iwv"></div>            
                            <div class="_2iww"></div>            
                            <div class="_2iwx"></div>            
                            <div class="_2iwy"></div>            
                            <div class="_2iwz"></div>            
                            <div class="_2iw-"></div>            
                            <div class="_2iw_"></div>            
                            <div class="_2ix0"></div>        
                        </div>
                    </li>
                </ul>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="re-nhat">

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="detail-listing" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="wrap-modal clearfix">
                        <div class="gallery-detail clearfix">
                            <div class="bxslider">
                                <div class="wrap-img-detail">
                                    <ul class="clearfix">
                                        <li class="img-big">
                                            <div class="bgcover" style="background-image:url(http://photos1.zillowstatic.com/p_h/ISdc652nxhtvxs0000000000.jpg);"></div>
                                            <a data-lightbox="detail-post" class="group mask" href="http://photos1.zillowstatic.com/p_h/ISdc652nxhtvxs0000000000.jpg"><em class="fa fa-search"></em><img src="http://photos1.zillowstatic.com/p_h/ISdc652nxhtvxs0000000000.jpg" alt="" style="display:none;"></a>
                                            
                                        </li>
                                    </ul>
                                </div>
                                <div class="wrap-img-detail">
                                    <ul class="clearfix">
                                        <li>
                                            <div class="bgcover" style="background-image:url(http://photos2.zillowstatic.com/p_c/ISptnvzc0h2vl21000000000.jpg);"></div>
                                            <a data-lightbox="detail-post" class="group mask" href="http://photos1.zillowstatic.com/p_f/ISdc652nxhtvxs0000000000.jpg"><em class="fa fa-search"></em><img src="http://photos2.zillowstatic.com/p_c/ISptnvzc0h2vl21000000000.jpg" alt="" style="display:none;"></a>
                                            
                                        </li>
                                        <li>
                                            <div class="bgcover" style="background-image:url(http://photos2.zillowstatic.com/p_c/ISplloe7f3cvu21000000000.jpg);"></div>
                                            <a data-lightbox="detail-post" class="group mask" href="http://photos2.zillowstatic.com/p_f/ISptnvzc0h2vl21000000000.jpg"><em class="fa fa-search"></em><img src="http://photos2.zillowstatic.com/p_c/ISplloe7f3cvu21000000000.jpg" alt="" style="display:none;"></a>
                                            
                                        </li>
                                        <li>
                                            <div class="bgcover" style="background-image:url(http://photos2.zillowstatic.com/p_c/ISd8nrbh00lw041000000000.jpg);"></div>
                                            <a data-lightbox="detail-post" class="group mask" href="http://photos2.zillowstatic.com/p_c/ISd8nrbh00lw041000000000.jpg"><em class="fa fa-search"></em><img src="http://photos2.zillowstatic.com/p_c/ISd8nrbh00lw041000000000.jpg" alt="" style="display:none;"></a>
                                            
                                        </li>
                                        <li>
                                            <div class="bgcover" style="background-image:url(http://photos1.zillowstatic.com/p_c/IS9doo0n73xzj41000000000.jpg);"></div>
                                            <a data-lightbox="detail-post" class="group mask" href="http://photos1.zillowstatic.com/p_c/IS9doo0n73xzj41000000000.jpg"><em class="fa fa-search"></em><img src="http://photos1.zillowstatic.com/p_c/IS9doo0n73xzj41000000000.jpg" alt="" style="display:none;"></a>
                                            
                                        </li>
                                    </ul>
                                </div>
                                <div class="wrap-img-detail">
                                    <ul class="clearfix">
                                        <li>
                                            <div class="bgcover" style="background-image:url(http://photos3.zillowstatic.com/p_c/ISh7lgi7qlt58s1000000000.jpg);"></div>
                                            <a data-lightbox="detail-post" class="group mask" href="http://photos3.zillowstatic.com/p_c/ISh7lgi7qlt58s1000000000.jpg"><em class="fa fa-search"></em><img src="http://photos3.zillowstatic.com/p_c/ISh7lgi7qlt58s1000000000.jpg" alt="" style="display:none;"></a>
                                            
                                        </li>
                                        <li>
                                            <div class="bgcover" style="background-image:url(http://photos1.zillowstatic.com/p_c/IS91z4ftnmff1w1000000000.jpg);"></div>
                                            <a data-lightbox="detail-post" class="group mask" href="http://photos1.zillowstatic.com/p_c/IS91z4ftnmff1w1000000000.jpg"><em class="fa fa-search"></em><img src="http://photos1.zillowstatic.com/p_c/IS91z4ftnmff1w1000000000.jpg" alt="" style="display:none;"></a>
                                            
                                        </li>
                                        <li>
                                            <div class="bgcover" style="background-image:url(http://photos3.zillowstatic.com/p_c/IS5avcsirawn6w1000000000.jpg);"></div>
                                            <a data-lightbox="detail-post" class="group mask" href="http://photos3.zillowstatic.com/p_c/IS5avcsirawn6w1000000000.jpg"><em class="fa fa-search"></em><img src="http://photos3.zillowstatic.com/p_c/IS5avcsirawn6w1000000000.jpg" alt="" style="display:none;"></a>
                                            
                                        </li>
                                        <li>
                                            <div class="bgcover" style="background-image:url(http://photos2.zillowstatic.com/p_c/ISp1qarocv6yus1000000000.jpg);"></div>
                                            <a data-lightbox="detail-post" class="group mask" href="http://photos2.zillowstatic.com/p_c/ISp1qarocv6yus1000000000.jpg"><em class="fa fa-search"></em><img src="http://photos2.zillowstatic.com/p_c/ISp1qarocv6yus1000000000.jpg" alt="" style="display:none;"></a>
                                            
                                        </li>
                                    </ul>
                                </div>
                                <div class="wrap-img-detail">
                                    <ul class="clearfix">
                                        <li>
                                            <div class="bgcover" style="background-image:url(http://photos2.zillowstatic.com/p_c/ISp1qarocv6yus1000000000.jpg);"></div>
                                            <a data-lightbox="detail-post" class="group mask" href="http://photos2.zillowstatic.com/p_c/ISp1qarocv6yus1000000000.jpg"><em class="fa fa-search"></em><img src="http://photos2.zillowstatic.com/p_c/ISp1qarocv6yus1000000000.jpg" alt="" style="display:none;"></a>
                                            
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row detail-post">
                            <div class="col-sm-8 dt-left-col">
                                <div class="rating pull-right">
                                    <ul class="clearfix">
                                        <li class="active"><a href="#"><em class="fa fa-star-o"></em></a></li>
                                        <li class="active"><a href="#"><em class="fa fa-star-o"></em></a></li>
                                        <li class="active"><a href="#"><em class="fa fa-star-o"></em></a></li>
                                        <li><a href="#"><em class="fa fa-star-o"></em></a></li>
                                        <li><a href="#"><em class="fa fa-star-o"></em></a></li>
                                    </ul>
                                </div>
                                <h1 class="title-dt">Mở bán tháp T5 căn hộ Maseti</h1>
                                <table>
                                    <tr>
                                        <th>Giá:</th>
                                        <td>1 tỷ 200 triệu</td>
                                    </tr>
                                    <tr>
                                        <th>Tiện ích:</th>
                                        <td>10 phòng ngủ, 6 phòng tắm, 10 toilet</td>
                                    </tr>
                                    <tr>
                                        <th>Diện tích:</th>
                                        <td>77m<sup>2</sup></td>
                                    </tr>
                                    <tr>
                                        <th>Địa chỉ:</th>
                                        <td>123 Trần Não, Quận 2, Hồ Chí Minh</td>
                                    </tr>
                                </table>
                                <p class="ttmt">Thông tin mô tả</p>
                                <div class="wrap-ttmt">
                                    <p style=" font-size: 14px; line-height: 20px; ">Chủ đầu tư- công ty cp đầu tư phát triển nhà Đà Nẵng(NDN).</p> <p style=" font-size: 14px; line-height: 20px; ">186 Trần Phú, quận Hải Châu, Tp Đà Nẵng.</p> <p style=" font-size: 14px; line-height: 20px; ">Chương trình chiết khấu đặc biệt.</p> <p style=" font-size: 14px; line-height: 20px; ">Phòng kinh doanh: Ms Lanh 0905.220.855.</p> <p style=" font-size: 14px; line-height: 20px; ">Email: Lanhtran266@gmail.com.</p><br> <p style=" font-size: 14px; line-height: 20px; ">+ Vị trí đắc địa: Nằm ở 4 mặt tiền đường (đường Trần Phú, Nguyễn Du, Đặng Tử Kính, đường nội bộ 5m), 1uận Hải Châu, Đà Nẵng. Cách sông Hàn 50m, được xem là con đường đắc địa và là quận 1 của Đà Nẵng. Đất trên con đường Trần Phú được định giá là 80 triệu/m2. Từ vị trí này đi ra bãi biển Mỹ Khê chỉ mất 5 phút xe máy.</p><br> <p style=" font-size: 14px; line-height: 20px; ">+ Tiện ích xung quanh: Chỉ mất 5- 7 phút xe máy là đi đến các trường học nổi tiếng của Đà Nẵng (trường mầm non Thần Đồng, trường tiểu học Phan Thanh, Phù Đổng, trường THPT Phan Chu Trinh…), đến bệnh viên lớn (Đa Khoa, bệnh viện C,.. ), đến các chợ lớn(chợ Hàn, chợ Cồn, Big C) và các khu vui chơi giải trí.</p><br> <p style=" font-size: 14px; line-height: 20px; ">+ Tổng quan tòa nhà:</p> <p style=" font-size: 14px; line-height: 20px; ">Đà Nẵng Plaza gồm 2 khối nhà 18 tầng và 1 tầng hầm. Trong đó 3 tầng đế là thương mại và tầng 4 có hồ bơi ngoài trời, phòng tập gym trong nhà, café, sân vườn. Còn từ tầng 4 đến tầng 18 là căn hộ. Đặc biệt tầng 17 và tầng 18 là căn Penthouse thông tầng, có sân vườn riêng biệt thoáng mát, rộng rãi.</p><br> <p style=" font-size: 14px; line-height: 20px; ">+ Thiết kế sang trọng, thông thoáng, an toàn:</p> <p style=" font-size: 14px; line-height: 20px; ">Trang bị hiện đại với 2 thang máy cao cấp, quản lý bằng thẻ từ. Hệ thống báo cháy tự động. Căn hộ được thiết kế với nhiều cửa sổ thông thoáng, View nhìn đẹp, phòng ngủ lót ván sàn, toàn bộ trần thạch cao trang trí với đèn lon đẹp mắt, WC có máy nước nóng, bếp hoàn thiện...</p> <p style=" font-size: 14px; line-height: 20px; ">Nhiều loại căn hộ: 1 PN, 2 PN và 3 Phòng ngủ. Diện tích từ: 69m2; 79m2; 130m2 và hai tầng dành riêng cho căn hộ Penthouse (diện tích từ 160 – 324m2).</p><br> <p style=" font-size: 14px; line-height: 20px; ">+ Gía hợp lý: Từ 1 tỷ 3 đến 2,34 tỷ. Căn Penthouse (giá từ 3,3 tỷ - 7,1 tỷ).</p> <p style=" font-size: 14px; line-height: 20px; ">Ngân hàng hỗ trợ cho vay đến 70% giá trị căn hộ, thời gian vay đến 15 năm.</p> <p style=" font-size: 14px; line-height: 20px; ">Rất thích hợp để ở - đi du lịch – đầu tư.</p>
                                </div>
                                <div class="wrap-tienich">
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingOne">
                                                <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                    <em class="fa fa-chevron-right pull-right"></em>Tiện ích xung quanh 1
                                                </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="panel-body">
                                                    <p>Chỉ mất 5- 7 phút xe máy là đi đến các trường học nổi tiếng của Đà Nẵng (trường mầm non Thần Đồng, trường tiểu học Phan Thanh, Phù Đổng, trường THPT Phan Chu Trinh…), đến bệnh viên lớn (Đa Khoa, bệnh viện C,.. ), đến các chợ lớn(chợ Hàn, chợ Cồn, Big C) và các khu vui chơi giải trí.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingOne">
                                                <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                    <em class="fa fa-chevron-right pull-right"></em>Tiện ích xung quanh 2
                                                </a>
                                                </h4>
                                            </div>
                                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="panel-body">
                                                    <p>Chỉ mất 5- 7 phút xe máy là đi đến các trường học nổi tiếng của Đà Nẵng (trường mầm non Thần Đồng, trường tiểu học Phan Thanh, Phù Đổng, trường THPT Phan Chu Trinh…), đến bệnh viên lớn (Đa Khoa, bệnh viện C,.. ), đến các chợ lớn(chợ Hàn, chợ Cồn, Big C) và các khu vui chơi giải trí.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingOne">
                                                <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                    <em class="fa fa-chevron-right pull-right"></em>Tiện ích xung quanh 3
                                                </a>
                                                </h4>
                                            </div>
                                            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="panel-body">
                                                    <p>Chỉ mất 5- 7 phút xe máy là đi đến các trường học nổi tiếng của Đà Nẵng (trường mầm non Thần Đồng, trường tiểu học Phan Thanh, Phù Đổng, trường THPT Phan Chu Trinh…), đến bệnh viên lớn (Đa Khoa, bệnh viện C,.. ), đến các chợ lớn(chợ Hàn, chợ Cồn, Big C) và các khu vui chơi giải trí.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 dt-right-col">
                                <div class="contact-wrapper">
                                    <p class="title-contact">Liên hệ</p>
                                    <div class="contact-person clearfix">
                                        <a href="#" class="wrap-img pull-left"><img src="<?= Yii::$app->view->theme->baseUrl ?>/resources/images/11.jpg" alt=""></a>
                                        <div class="clearfix">
                                            <div class="rating pull-right">
                                                <ul class="clearfix">
                                                    <li class="active"><a href="#"><em class="fa fa-star-o"></em></a></li>
                                                    <li class="active"><a href="#"><em class="fa fa-star-o"></em></a></li>
                                                    <li class="active"><a href="#"><em class="fa fa-star-o"></em></a></li>
                                                    <li><a href="#"><em class="fa fa-star-o"></em></a></li>
                                                    <li><a href="#"><em class="fa fa-star-o"></em></a></li>
                                                </ul>
                                            </div>
                                            <p><strong>Tên:</strong>Nguyễn Văn A Tủn</p>
                                            <p><strong>DĐ:</strong>0905269326</p>
                                            <p><strong>ĐC:</strong>123 Trần Khánh Dư, Q1, HCM</p>
                                        </div>
                                    </div>
                                    <form action="" id="frm-contact-person">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Tên bạn">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Email">
                                        </div>
                                        <div class="form-group mgB-0">
                                            <input type="text" class="form-control" placeholder="Điện thoại">
                                        </div>
                                        <p class="alert">Tin nhắn này sẽ được đọc khi người này online</p>
                                        <div class="checkbox">
                                            <label><input type="checkbox"> Hiện thông báo khi người này đọc tin nhắn</label>
                                        </div>
                                        <div class="text-center">
                                            <button type="button" class="btn btn-primary">Gửi tin nhắn cho người này</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
