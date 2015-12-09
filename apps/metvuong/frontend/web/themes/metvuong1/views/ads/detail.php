<?php 
	use common\vendor\vsoft\ad\models\AdStreet;
	use common\vendor\vsoft\ad\models\AdWard;
	use common\vendor\vsoft\ad\models\AdDistrict;
	use common\vendor\vsoft\ad\models\AdCity;
	
	$images = $product->adImages;
	$street = AdStreet::findOne($product->street_id);
	$ward = AdWard::findOne($product->ward_id);
	$district = AdDistrict::findOne($product->district_id);
	$city = AdCity::findOne($product->city_id);
?>
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
                    	<?php
                    		if(!empty($images)) :
								$firstImage = array_shift($images);
								$images = array_chunk($images, 4);
						?>
                        <div class="gallery-detail clearfix">
                            <div class="bxslider">
                                <div class="wrap-img-detail">
                                    <ul class="clearfix">
                                        <li class="img-big">
                                            <div class="bgcover" style="background-image:url(<?= $firstImage->imageMedium ?>);"></div>
                                            <a data-lightbox="detail-post" class="group mask" href="<?= $firstImage->imageLarge ?>"><em class="fa fa-search"></em><img src="<?= $firstImage->imageLarge ?>" alt="" style="display:none;"></a>
                                        </li>
                                    </ul>
                                </div>
                                <?php foreach($images as $imagesGroup): ?>
                                	<div class="wrap-img-detail">
                                    	<ul class="clearfix">
		                                <?php foreach($imagesGroup as $image): ?>
		             						<li>
	                                            <div class="bgcover" style="background-image:url(<?= $image->imageThumb ?>);"></div>
	                                            <a data-lightbox="detail-post" class="group mask" href="<?= $image->imageLarge ?>"><em class="fa fa-search"></em><img src="<?= $image->imageLarge ?>" alt="" style="display:none;"></a>
	                                            
	                                        </li>
		                                <?php endforeach; ?>
		                                </ul>
	                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
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
                                <h1 class="title-dt"><?= "{$product->home_no}, {$street->pre} {$street->name}, {$ward->pre} {$ward->name}, {$district->pre} {$district->name} {$city->name}" ?></h1>
                                <table>
                                    <tr>
                                        <th>Giá:</th>
                                        <td><?= $product->priceFormated ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tiện ích:</th>
                                        <td>10 phòng ngủ, 6 phòng tắm, 10 toilet</td>
                                    </tr>
                                    <tr>
                                        <th>Diện tích:</th>
                                        <td>77m<sup>2</sup></td>
                                    </tr>
                                </table>
                                <p class="ttmt">Thông tin mô tả</p>
                                <?= $product->content ?>
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