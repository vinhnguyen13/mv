<?php 
	use vsoft\ad\models\AdStreet;
	use vsoft\ad\models\AdWard;
	use vsoft\ad\models\AdDistrict;
	use vsoft\ad\models\AdCity;
	use vsoft\ad\models\AdCategory;
	use vsoft\ad\models\AdProduct;
	use vsoft\ad\models\AdBuildingProject;
	use yii\helpers\Url;
	use vsoft\ad\models\AdProductAdditionInfo;
	use vsoft\express\components\StringHelper;
	
	$images = $product->adImages;
	$street = AdStreet::findOne($product->street_id);
	$ward = AdWard::findOne($product->ward_id);
	$district = AdDistrict::findOne($product->district_id);
	$city = AdCity::findOne($product->city_id);
	$categoryName = AdCategory::findOne($product->category_id)->name;
	$typeName = ($product->type == AdProduct::TYPE_FOR_SELL) ? 'bán' : 'cho thuê';
	$direction = AdProductAdditionInfo::directionList();
	
	$owner = \frontend\models\User::findOne($product->user_id);
	if($owner && $owner->profile) {
        $avatar = $owner->profile->getAvatarUrl();
	} else {
		$avatar = Yii::$app->view->theme->baseUrl . '/resources/images/default-avatar.jpg';
	}
	
	$address = '';
	
	if($product->home_no) {
		$address .= "{$product->home_no}, ";
	}
	
	if($street) {
		$address .= "{$street->pre} {$street->name}, ";
	}
	
	if($ward) {
		$address .= "{$ward->pre} {$ward->name}, ";
	}
	
	if($address) {
		$address .= "{$district->pre} {$district->name}, {$city->name}";
	} else {
		$address = "{$district->pre} {$district->name}, {$city->name}";
	}
?>
<div id="detail-listing">
        <div class="detail-slide">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="tabs-detail-item clearfix">
                        <ul class="pull-left">
                            <li><a href="#" class="save-item"><em class="fa fa-heart-o"></em>Save</a></li>
                            <li><a href="#" class="share-item"><em class="fa fa-share-alt"></em>Share</a></li>
                            <li><a href="#" class="more-item">More<em class="fa fa-sort-desc"></em></a></li>
                        </ul>
                        <ul class="pull-right">
                            <li><a href="#">Expand</a></li>
                            <li class="close-detail">
                                <button type="button" class="close btn-close-detail" data-dismiss="modal" aria-label="Close">
                                    <em class="fa fa-close"></em>close
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="wrap-modal clearfix">
                    	<?php
                    		if(!empty($images)) :
									$firstImage = array_shift($images);
									$images = array_chunk($images, 4);
						?>
                                    <div class="gallery-detail clearfix" style="visibility: hidden;">
			                            <div class="bxslider">
			                                <div class="wrap-img-detail">
			                                    <ul class="clearfix">
			                                        <li class="img-big">
			                                        	<?php if(!StringHelper::startsWith($firstImage->file_name, 'http')): ?>
			                                            <div class="bgcover" style="background-image:url(<?= $firstImage->imageMedium ?>);"></div>
			                                            <a data-lightbox="detail-post" class="group mask" href="<?= $firstImage->imageLarge ?>"><em class="fa fa-search"></em><img src="<?= $firstImage->imageMedium ?>" alt="" style="display:none;"></a>
			                                        	<?php else: ?>
			                                        	<div class="bgcover" style="background-image:url(<?= str_replace('/745x510/', '/350x280/', $firstImage->file_name) ?>);"></div>
			                                            <a data-lightbox="detail-post" class="group mask" href="<?= $firstImage->file_name ?>"><em class="fa fa-search"></em><img src="<?= str_replace('/745x510/', '/350x280/', $firstImage->file_name) ?>" alt="" style="display:none;"></a>
			                                        	<?php endif; ?>
			                                        </li>
			                                    </ul>
			                                </div>
			                                <?php foreach($images as $imagesGroup): ?>
			                                	<div class="wrap-img-detail">
			                                    	<ul class="clearfix">
					                                <?php foreach($imagesGroup as $image): 
					                                		if(!StringHelper::startsWith($image->file_name, 'http')):
					                                ?>
					             						<li>
				                                            <div class="bgcover" style="background-image:url(<?= $image->imageThumb ?>);"></div>
				                                            <a data-lightbox="detail-post" class="group mask" href="<?= $image->imageLarge ?>"><em class="fa fa-search"></em><img src="<?= $image->imageThumb ?>" alt="" style="display:none;"></a>
				                                            
				                                        </li>
					                                <?php else: ?>
					                                <li>
					                                	<div class="bgcover" style="background-image:url(<?= str_replace('/745x510/', '/350x280/', $image->file_name) ?>);"></div>
					                                	<a data-lightbox="detail-post" class="group mask" href="<?= $image->file_name ?>"><em class="fa fa-search"></em><img src="<?= str_replace('/745x510/', '/350x280/', $image->file_name) ?>" alt="" style="display:none;"></a>
					                                </li>
					                                <?php endif; endforeach; ?>
					                                </ul>
				                                </div>
			                                <?php endforeach; ?>
			                            </div>
			                        </div>
                        <?php else: ?>
                        <div class="gallery-detail clearfix"><div class="bxslider no-image"><?= Yii::t('ad', 'Không có hình ảnh đính kèm') ?></div></div>
                        <?php endif; ?>
                        <div class="row detail-post" data-id="<?=$product->id;?>">
                            <div class="col-sm-8 dt-left-col">
                                <h1 class="title-dt"><?= $address ?></h1>
                                <p class="infor-post-date"><em class="fa fa-calendar"></em><?= date('d/m/Y', $product->created_at) ?></p>
                                <p class="type-result"><em class="fa fa-circle for-rent"></em><?= mb_strtoupper("$categoryName $typeName", 'UTF-8') ?></p>
                                <table>
                                    <?php if($product->project_building_id): ?>
                                    <tr>
                                        <th>Dự án</th>
                                        <td><?= AdBuildingProject::findOne($product->project_building_id)->name ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th>Giá:</th>
                                        <td><?= StringHelper::formatCurrency($product->price) ?><?= $product->type == AdProduct::TYPE_FOR_RENT ? '/tháng' : '' ?></td>
                                    </tr>
                                    <tr>
                                        <th>Diện tích:</th>
                                        <td><?= StringHelper::formatNumber($product->area) ?>m<sup>2</sup></td>
                                    </tr>
                                </table>
                                <p class="ttmt">Thông tin mô tả</p>
                                <div class="wrap-ttmt"><?= str_replace("\n", "<br />", htmlspecialchars($product->content)) ?></div>
                                <?php
                                	$additionInfo = $product->adProductAdditionInfo;
                                	unset($additionInfo['product_id']);
                                	if($additionInfo && array_filter($additionInfo->attributes)):
                                ?>
                                <p class="ttmt">Thông tin thêm</p>
                                <table>
                                    <?php if($additionInfo->facade_width): ?>
                                    <tr>
                                        <th>Mặt tiền</th>
                                        <td><?= StringHelper::formatNumber($additionInfo->facade_width) ?> m</td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($additionInfo->land_width): ?>
                                    <tr>
                                        <th>Đường vào</th>
                                        <td><?= StringHelper::formatNumber($additionInfo->land_width) ?> m</td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($additionInfo->home_direction): ?>
                                    <tr>
                                        <th>Hướng nhà</th>
                                        <td><?= $direction[$additionInfo->home_direction] ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($additionInfo->facade_direction): ?>
                                    <tr>
                                        <th>Hướng ban công</th>
                                        <td><?= $direction[$additionInfo->facade_direction] ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($additionInfo->floor_no): ?>
                                    <tr>
                                        <th>Số tầng</th>
                                        <td><?= $additionInfo->floor_no ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($additionInfo->room_no): ?>
                                    <tr>
                                        <th>Số phòng ngủ</th>
                                        <td><?= $additionInfo->room_no ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($additionInfo->toilet_no): ?>
                                    <tr>
                                        <th>Số toilet</th>
                                        <td><?= $additionInfo->toilet_no ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($additionInfo->interior): ?>
                                    <tr>
                                        <th>Nội thất</th>
                                        <td><?= $additionInfo->interior ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                                <?php endif; ?>
                            </div>
                            <?php if($product->adContactInfo): ?>
                            <div class="col-sm-4 dt-right-col">
                                <div class="contact-wrapper">
                                    <div class="rating pull-right">
                                        <fieldset class="rate">
                                            <input type="radio" id="rating-contact-10" name="rating-contact" value="10" /><label for="rating-contact-10" title="5 stars"></label>
                                            <input type="radio" id="rating-contact-9" name="rating-contact" value="9" /><label class="half" for="rating-contact-9" title="4 1/2 stars"></label>
                                            <input type="radio" id="rating-contact-8" name="rating-contact" value="8" /><label for="rating-contact-8" title="4 stars"></label>
                                            <input type="radio" id="rating-contact-7" name="rating-contact" value="7" /><label class="half" for="rating-contact-7" title="3 1/2 stars"></label>
                                            <input type="radio" id="rating-contact-6" name="rating-contact" value="6" /><label for="rating-contact-6" title="3 stars"></label>
                                            <input type="radio" id="rating-contact-5" name="rating-contact" value="5" /><label class="half" for="rating-contact-5" title="2 1/2 stars"></label>
                                            <input type="radio" id="rating-contact-4" name="rating-contact" value="4" /><label for="rating-contact-4" title="2 stars"></label>
                                            <input type="radio" id="rating-contact-3" name="rating-contact" value="3" /><label class="half" for="rating-contact-3" title="1 1/2 stars"></label>
                                            <input type="radio" id="rating-contact-2" name="rating-contact" value="2" /><label for="rating-contact-2" title="1 star"></label>
                                            <input type="radio" id="rating-contact-1" name="rating-contact" value="1" /><label class="half" for="rating-contact-1" title="1/2 star"></label>
                                        </fieldset>
                                    </div>
                                    <p class="title-contact">Liên hệ</p>
                                    <div class="contact-person clearfix">
                                        <a href="#" class="wrap-img pull-left"><img src="<?= $avatar ?>" alt=""></a>
                                        <div class="clearfix">
                                            <?php if($product->adContactInfo->name): ?>
                                            <p><strong>Tên:</strong><?= $product->adContactInfo->name ?></p>
                                            <?php endif; ?>
                                            <?php if($product->adContactInfo->phone): ?>
                                            <p><strong>Điện thoại:</strong><?= $product->adContactInfo->phone ?></p>
                                            <?php endif; ?>
                                            <?php if($product->adContactInfo->mobile): ?>
                                            <p><strong>Di động:</strong><?= $product->adContactInfo->mobile ?></p>
                                            <?php endif; ?>
                                            <?php if($product->adContactInfo->email): ?>
                                            <p><strong>Email:</strong><?= $product->adContactInfo->email ?></p>
                                            <?php endif; ?>
                                            <?php if($product->adContactInfo->address): ?>
                                            <p><strong>Địa chỉ:</strong><?= $product->adContactInfo->address ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <form style="display: none;" action="" id="frm-contact-person">
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
                                <div class="function-listing clearfix">
                                    <p>Đánh giá</p>
                                    <div class="rating" data-url="<?=Url::to(['/ad/rating', 'type'=>'']);?>">
                                        <fieldset class="rate">
                                            <?php for($i = 10; $i > 0; $i--):?>
                                            <input type="radio" id="rating<?=$i?>" name="rating" value="<?=$i?>" <?=($i==round($product->rating)) ? 'checked' : ''?> /><label for="rating<?=$i?>" <?=($i%2!=0) ? 'class="half"' : ''?> title="<?=ceil($i/2)?> stars"></label>
                                            <?php endfor;?>
                                        </fieldset>
                                    </div>
                                    <div class="dropdown report-listing">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="btn-report" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            Report
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="btn-report">
                                            <li><a href="#" data-toggle="modal" data-target="#report-listing">Lừa đảo</a></li>
                                            <li><a href="#" data-toggle="modal" data-target="#report-listing">Trùng địa chỉ</a></li>
                                            <li><a href="#" data-toggle="modal" data-target="#report-listing">Tin đã đăng</a></li>
                                        </ul>
                                        <div class="modal fade" id="report-listing" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="wrap-modal clearfix">
                                                            <h3>Report</h3>
                                                            <textarea class="form-control" rows="3" placeholder="nội dung report..."></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="icon-item-listing">
                                        <a title="Lưu" class="icon-hear" data-id="<?=$product->id;?>" href="#" data-url="<?=Url::to(['/ad/favorite'])?>">
                                            <em class="fa fa-heart-o"></em>
                                            <span>Lưu</span>
                                        </a>
                                        <!--<a class="icon-favo" href="#">
                                            <em class="icon-ok-sign"></em>
                                            <span>Yêu thích</span>
                                        </a>-->
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>