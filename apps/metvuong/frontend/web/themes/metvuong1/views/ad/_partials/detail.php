<?php 
	use vsoft\ad\models\AdStreet;
	use vsoft\ad\models\AdWard;
	use vsoft\ad\models\AdDistrict;
	use vsoft\ad\models\AdCity;
	use vsoft\ad\models\AdCategory;
	use vsoft\ad\models\AdProduct;
	use vsoft\ad\models\AdBuildingProject;
use yii\bootstrap\ActiveForm;
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

    $detailUrl = Yii::$app->urlManager->createAbsoluteUrl(['ad/detail', 'id'=>$product->id, 'slug'=>\yii\helpers\Inflector::slug($product->getAddress())]);
?>
<div id="detail-listing">

        <div class="detail-slide">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="tabs-detail-item clearfix">
                        <ul class="pull-left">
                            <li><a href="#" class="save-item icon-hear" data-id="<?=$product->id;?>" href="#" data-url="<?=Url::to(['/ad/favorite'])?>"><em class="fa fa-heart-o"></em>Save</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#box-share-1" data-url="<?=trim($detailUrl)?>" data-address="<?=trim($address)?>" class="share-item-1"><em class="fa fa-share-alt"></em>Share</a></li>
                            <li><a href="#" class="more-item">More<em class="fa fa-sort-desc"></em></a>
                                <div class="sub-more hidden-effect">
                                    <div class="wrap-effect clearfix">
                                        <ul class='clearfix'>
                                            <li><a class="print-detail" href="#"><em class="icon-printer"></em>Print</a></li>
                                            <li><a href="#" class="report_modal" data-toggle="modal" data-target="#report-listing" data-url="<?=Url::to(['/ad/report'])?>" data-uid="<?=empty(Yii::$app->user->id) ? 0 : Yii::$app->user->id?>"><em class="icon-info"></em>Báo cáo bài viết</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="pull-right">
                            <li class="expand-win"><a href="<?=Url::to(['ad/detail', 'id'=>$product->id])?>" target="_blank"><em class="fa fa-expand"></em>Expand</a></li>
                            <li class="close-detail">
                                <button type="button" class="close btn-close-detail" data-dismiss="modal" aria-label="Close">
                                    <em class="fa fa-close"></em>close
                                </button>
                            </li>
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
                                            <h3>Báo cáo bài viết</h3>
                                            <form id="frm-report" action="<?=Url::to(['/ad/sendreport'])?>">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="optionsRadios" id="r1" value="1">
                                                        Lừa đảo
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="optionsRadios" id="r2" value="2">
                                                        Trùng địa chỉ
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="optionsRadios" id="r3" value="3">
                                                        Tin đã đăng
                                                    </label>
                                                </div>
                                                <input type="hidden" id="pid" name="pid" value="<?=$product->id?>">
                                                <input type="hidden" id="uid" name="uid" value="<?=empty(Yii::$app->user->id) ? 0 : Yii::$app->user->id?>">
                                                <button class="btn btn-common send_report">Gửi báo cáo</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div id="modal-detail-listting" class="modal-body">
                    <div class="wrap-modal clearfix">
                    	<?= $this->render('slide', ['images' => $images]) ?>
                        <div class="row detail-post" data-id="<?=$product->id;?>">
                            <div class="col-sm-8 dt-left-col">
                                <h1 class="title-dt"><?= $address ?></h1>
                                <div class="pull-right hdp-summary">
                                    <p class="type-result"><?= mb_strtoupper("$categoryName $typeName", 'UTF-8') ?></p>
                                    <table>
                                        <?php if($product->project_building_id): ?>
                                        <tr>
                                            <th>Dự án</th>
                                            <td><?= AdBuildingProject::findOne($product->project_building_id)->name ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <th>Giá:</th>
                                            <td><p class="price-summary"><?= StringHelper::formatCurrency($product->price) ?><?= $product->type == AdProduct::TYPE_FOR_RENT ? '/tháng' : '' ?></p></td>
                                        </tr>
                                        <tr>
                                            <th>Diện tích:</th>
                                            <td><?= StringHelper::formatNumber($product->area) ?> m<sup>2</sup></td>
                                        </tr>
                                    </table>
                                </div>
                                <p class="home-attr">4 phòng <span>.</span> 3 toilets <span>.</span> 2,274 sqft</p>
                                <p class="infor-post-date"><em class="fa fa-calendar"></em><?= date('d/m/Y', $product->created_at) ?></p>
                                <div class="rating" data-url="<?=Url::to(['/ad/rating', 'type'=>'']);?>">
                                    <fieldset class="rate">
                                        <?php for($i = 10; $i > 0; $i--):?>
                                        <input type="radio" id="rating<?=$i?>" name="rating" value="<?=$i?>" <?=($i==round($product->rating)) ? 'checked' : ''?> /><label for="rating<?=$i?>" <?=($i%2!=0) ? 'class="half"' : ''?> title="<?=ceil($i/2)?> stars"></label>
                                        <?php endfor;?>
                                    </fieldset>
                                </div>
                                <div class="clearfix"></div>
                                
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
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
