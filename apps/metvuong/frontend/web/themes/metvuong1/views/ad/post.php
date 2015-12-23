<?php
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use vsoft\ad\models\AdProduct;
use yii\helpers\Html;
use common\widgets\FileUploadUI;
use yii\helpers\ArrayHelper;
use vsoft\ad\models\AdProductAdditionInfo;
use vsoft\ad\models\AdContactInfo;
use common\widgets\CKEditor;
$this->title = Yii::t ( 'express', 'We offer exeptional amenities and renowned white - glove services' );

$this->registerCssFile(Yii::$app->view->theme->baseUrl . '/resources/css/select2.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);

$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/select2.min.js', ['position' => View::POS_END]);
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/gmap.js', ['position' => View::POS_END]);
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=apiLoaded', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/dang-tin.js', ['position' => View::POS_END]);
$this->registerCss('.preview canvas {vertical-align: middle;} .price-editable{position: relative;} .price-editable .price-format {display: inline; position: absolute; top: 10px; left: 100%; white-space: nowrap; padding-left: 9px;} .preview img {width: 120px; height: 80px;} .select2-drop-active {border-color: #ccc;} .select2-drop {margin-top: -2px;} .editable select.select2 {padding: 0 20px;} .editable {cursor: pointer; max-width: 100%; min-width: 160px;} .editable:not(.editing):hover {background: rgba(0, 0, 0, 0.15);border-radius: 4px;padding: 3px;}');
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/jquery.bxslider.js', ['position' => View::POS_END]);
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/imagesloaded.3.0.4.js', ['position' => View::POS_END]);
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/lightbox.min.js', ['position' => View::POS_END]);

$type = [ 
	AdProduct::TYPE_FOR_SELL => Yii::t ( 'ad', 'Nhà đất bán' ),
	AdProduct::TYPE_FOR_RENT => Yii::t ( 'ad', 'Nhà đất cho thuê' ) 
];

if(Yii::$app->user->identity->profile->avatar) {
	$avatar = Url::to('/store/avatar/' . Yii::$app->user->identity->profile->avatar);
} else {
	$avatar = Yii::$app->view->theme->baseUrl . '/resources/images/default-avatar.jpg';
}

?>
<div class="wrap-dangtin">
	<div class="title-frm" clearfix>Đăng tin</div>
	<div class="wrap-step-post clearfix">
		<?php $form = ActiveForm::begin ( [ 
				'id' => 'frm-post-tin',
				'options' => [ 
					'spellcheck' => 'false',
					'class' => 'form-horizontal'
				]]); ?>
			<ul id="progressbar" class="clearfix">
				<li class="active">
					<div>
						<a href="#">1</a> <span class="line-process"></span>
						<div>Step 1</div>
					</div>
				</li>
				<li class="step-center">
					<div>
						<a href="#">2</a> <span class="line-process"></span>
						<div>Step 2</div>
					</div>
				</li>
				<li class="text-left">
					<div>
						<a href="#">3</a> <span class="line-process"></span>
						<div>Hoàn thành</div>
					</div>
				</li>
			</ul>
			<div class="fieldset clearfix" style="display: block;">
				<?= Html::activeHiddenInput($model, 'city_id') ?>
				<?= Html::activeHiddenInput($model, 'district_id') ?>
				<?= Html::activeHiddenInput($model, 'category_id') ?>
				<?= Html::activeHiddenInput($model, 'type') ?>
				<div class="form-group">
					<label for="" class="col-sm-3 control-label">Địa chỉ *</label>
					<div class="col-sm-9 group-item-frm">
						<div class="form-group">
							<?= Html::activeDropDownList($model, 'ward_id', ['' => ''], ['class' => 'select2 ad-select', 'data-placeholder' => 'Phường/Xã'])?>
							<div class="help-block" style="display: none;"></div>
						</div>
						<div class="form-group">
							<?= Html::activeDropDownList($model, 'street_id', ['' => ''], ['class' => 'select2 ad-select', 'data-placeholder' => 'Đường'])?>
							<div class="help-block" style="display: none;"></div>
						</div>
						<div class="form-group mgB-0">
							<?= Html::activeTextInput($model, 'home_no', ['class' => 'form-control ad-input', 'placeholder' => $model->getAttributeLabel('home_no')]) ?>
							<div class="help-block" style="display: none;"></div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-3 control-label">Thuộc dự án</label>
					<div class="col-sm-9 group-item-frm">
						<?= Html::activeDropDownList($model, 'project_building_id', [], ['class' => 'form-control mgB-0', 'prompt' => 'Dự án'])?>
					</div>
				</div>
				<div class="form-group text-inline">
					<label for="" class="col-sm-3 control-label">Diện tích *</label>
					<div class="col-sm-9">
						<div class="inline-group col-xs-6">
							<?= Html::activeTextInput($model, 'area', ['class' => 'form-control number-only ad-input', 'data-float' => '1']) ?>
						<div class="help-block" style="display: none;"></div>
						</div>
						<div class="inline-group col-xs-6 pdR-0">
							<span>
								<span style="display: inline;" id="area-format" data-ref="adproduct-area"></span>
								m<sup>2</sup>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-3 control-label">Giá *</label>
					<div class="col-sm-9 group-item-frm">
						<div class="inline-group col-xs-6 mgB-10">
							<?= Html::activeTextInput($model, 'price', ['class' => 'form-control number-only ad-input']) ?>
							<div class="help-block" style="display: none;"></div>
						</div>
						<div class="inline-group col-xs-6 pdR-0">
							<span style="display: inline;" id="price-format" class="price-format" data-ref="adproduct-price"></span>
						</div>
					</div>
				</div>
				<button type="button"
					class="btn btn-primary btn-common mgT-15 next action-button pull-right">
					Tiếp theo<em class="fa fa-chevron-right"></em>
				</button>
			</div>
			<div class="fieldset clearfix" style="display: none;">
		    	<div class="form-group">
		    		<?= Html::activeTextarea($model, 'content', ['class' => 'form-control ad-input', 'placeholder' => 'Nội dung tin đăng', 'style' => 'height: 100px;']) ?>
		    		<div class="help-block" style="display: none;"></div>
		    	</div>
				<?= FileUploadUI::widget([
					'name' => 'images',
					'id' => 'images',
					'url' => Url::to(['upload']),
					'fieldOptions' => ['values' => implode(',', ArrayHelper::getColumn($model->adImages, 'file_name'))],
					'clientOptions' => [
						'previewMaxWidth' => 120,
						'previewMaxHeight' => 80,
					],
				]) ?>
				<div class="form-group">
					<div class="title-sub-frm">Thông tin mở rộng</div>
				</div>
				<div class="form-group">
					<div class="row row-group-inline">
						<div class="col-xs-3">
							<?= Html::activeTextInput($adProductAdditionInfo, 'facade_width', ['class' => 'form-control number-only', 'data-float' => '1', 'placeholder' => 'Mặt tiền (m)']) ?>
						</div>
						<div class="col-xs-3">
							<?= Html::activeTextInput($adProductAdditionInfo, 'land_width', ['class' => 'form-control number-only', 'data-float' => '1', 'placeholder' => 'Đường vào (m)']) ?>
						</div>
						<div class="col-xs-3">
							<?= Html::activeDropDownList($adProductAdditionInfo, 'home_direction', AdProductAdditionInfo::directionList(), ['class' => 'form-control', 'prompt' => 'Hướng nhà'])?>
						</div>
						<div class="col-xs-3">
							
							<?= Html::activeDropDownList($adProductAdditionInfo, 'facade_direction', AdProductAdditionInfo::directionList(), ['class' => 'form-control', 'prompt' => 'Hướng ban công'])?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row row-group-inline">
						<div class="col-xs-3">
							<?= Html::activeTextInput($adProductAdditionInfo, 'floor_no', ['class' => 'form-control number-only', 'placeholder' => 'Số tầng']) ?>
						</div>
						<div class="col-xs-3">
							<?= Html::activeTextInput($adProductAdditionInfo, 'room_no', ['class' => 'form-control number-only', 'placeholder' => 'Số phòng ngủ']) ?>
						</div>
						<div class="col-xs-3">
							<?= Html::activeTextInput($adProductAdditionInfo, 'toilet_no', ['class' => 'form-control number-only', 'placeholder' => 'Số toilet']) ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?= Html::activeTextarea($adProductAdditionInfo, 'interior', ['class' => 'form-control', 'placeholder' => 'Nội thất']) ?>
				</div>
				<div class="clearfix"></div>
				<div class="form-group">
					<div class="title-sub-frm">Thông tin liện hệ</div>
				</div>
				<div class="form-group">
					<div class="row row-group-inline">
						<div class="col-xs-3">
							<?= Html::activeTextInput($adContactInfo, 'name', ['class' => 'form-control', 'placeholder' => 'Tên liên hệ']) ?>
						</div>
						<div class="col-xs-3">
							<?= Html::activeTextInput($adContactInfo, 'phone', ['class' => 'form-control number-only', 'placeholder' => 'Điện thoại', 'data-zero-first' => '1']) ?>
							<div class="help-block" style="display: none;"></div>
						</div>
						<div class="col-xs-3">
							<div class="form-group mgB-0">
								<?= Html::activeTextInput($adContactInfo, 'mobile', ['class' => 'form-control ad-input number-only', 'placeholder' => 'Di động *', 'data-zero-first' => '1']) ?>
								<div class="help-block" style="display: none;"></div>
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group mgB-0">
								<?= Html::activeTextInput($adContactInfo, 'email', ['class' => 'form-control ad-input', 'placeholder' => 'Email']) ?>
								<div class="help-block" style="display: none;"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="form-group">
					<?= Html::activeTextInput($adContactInfo, 'address', ['class' => 'form-control', 'placeholder' => 'Địa chỉ']) ?>
				</div>
				<div class="form-group" style="display: none;">
					<div class="title-sub-frm">Bản đồ</div>
				</div>
				<div class="form-group" style="display: none;">
					<p>
						Để tăng độ tin cậy và tin rao được nhiều người quan tâm hơn, hãy
						sửa vị trí tin rao của bạn trên bản đồ bằng cách kéo icon<em
							class="fa fa-map-marker"></em>tới đúng vị trí của tin rao.
					</p>
					<div id="map" style="height: 450px;"></div>
					<?= Html::activeHiddenInput($model, 'lat') ?>
					<?= Html::activeHiddenInput($model, 'lng') ?>
				</div>
				<!-- <button id="preview" type="button" class="btn btn-primary btn-common mgT-15 pull-left" data-toggle="modal" data-target="#detail-listing" style="display: none;">
					Xem trước
				</button> -->
				<button id="preview" type="button"
					class="btn btn-primary btn-common mgT-15 action-button pull-right" data-ajax-post="false" data-toggle="modal" data-target="#detail-listing">
					Tiếp theo<em class="fa fa-chevron-right"></em>
				</button>
				<button type="button"
					class="btn btn-primary btn-common mgT-15 previous action-button pull-right">
					<em class="fa fa-chevron-left"></em>Quay lại
				</button>
			</div>
			<div class="fieldset clearfix finish-post" style="display: none;">
				<div style="font-weight: bold; font-size: 20px; color: #514AB3; margin: -8px 0 22px 0;">Đăng tin thành công !</div>
				<div style="font-size: 14px; margin-bottom: 32px;">Tin của bạn đang chờ xét duyệt và sẽ được hiển thị đến người mua sớm nhất.<br /><br />Cảm ơn đã sử dụng dịch vụ của MetVuong</div>
				<a href="<?= Url::current() ?>" style="font-size: 16px; text-transform: uppercase;">Tiếp tục đăng tin</a>
			</div>
		<?php $form->end()?>
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
                            <div class="bxslider"></div>
                        </div>
                        <div class="row detail-post">
                            <div class="col-sm-8 dt-left-col">
                                <h1 class="title-dt">
                                	<span class="editable" id="ref-adproduct-home_no"></span>,
                                	<span class="editable" id="ref-adproduct-street_id"></span>,
                                	<span class="editable" id="ref-adproduct-ward_id"></span>,
                                	<span id="ref-adproduct-district_id"></span>,
                                	<span id="ref-adproduct-city_id"></span>
                                </h1>
                                <table>
                                    <tr class="not-required-wrap">
                                        <th>Dự án</th>
                                        <td><span class="editable not-required" id="ref-adproduct-project_building_id"></span></td>
                                    </tr>
                                    <tr>
                                        <th>Giá:</th>
                                        <td><span class="editable price-editable" id="ref-price-format"></span></td>
                                    </tr>
                                    <tr>
                                        <th>Diện tích:</th>
                                        <td><span class="editable" id="ref-area-format"></span>m<sup>2</sup></td>
                                    </tr>
                                </table>
                                <p class="ttmt">Thông tin mô tả</p>
                                <div class="wrap-ttmt" style="white-space: pre;"><div class="editable" id="ref-adproduct-content"></div></div>
                               	<p class="ttmt">Thông tin thêm</p>
                                <table>
                                    <tr class="not-required-wrap">
                                        <th>Mặt tiền</th>
                                        <td><span class="editable not-required" id="ref-adproductadditioninfo-facade_width"></span> m</td>
                                    </tr>
                                    <tr class="not-required-wrap">
                                        <th>Đường vào</th>
                                        <td><span class="editable not-required" id="ref-adproductadditioninfo-land_width"></span> m</td>
                                    </tr>
                                    <tr class="not-required-wrap">
                                        <th>Hướng nhà</th>
                                        <td><span class="editable not-required" id="ref-adproductadditioninfo-home_direction"></span></td>
                                    </tr>
                                    <tr class="not-required-wrap">
                                        <th>Hướng ban công</th>
                                        <td><span class="editable not-required" id="ref-adproductadditioninfo-facade_direction"></span></td>
                                    </tr>
                                    <tr class="not-required-wrap">
                                        <th>Số tầng</th>
                                        <td><span class="editable not-required" id="ref-adproductadditioninfo-floor_no"></span></td>
                                    </tr>
                                    <tr class="not-required-wrap">
                                        <th>Số phòng ngủ</th>
                                        <td><span class="editable not-required" id="ref-adproductadditioninfo-room_no"></span></td>
                                    </tr>
                                    <tr class="not-required-wrap">
                                        <th>Số toilet</th>
                                        <td><span class="editable not-required" id="ref-adproductadditioninfo-toilet_no"></span></td>
                                    </tr>
                                    <tr class="not-required-wrap">
                                        <th>Nội thất</th>
                                        <td><span class="editable not-required" id="ref-adproductadditioninfo-interior"></span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4 dt-right-col">
                                <div class="contact-wrapper">
                                	<div class="rating pull-right">
                                        <ul class="clearfix">
                                            <li class="active"><a href="#"><em class="fa fa-star-o"></em></a></li>
                                            <li class="active"><a href="#"><em class="fa fa-star-o"></em></a></li>
                                            <li class="active"><a href="#"><em class="fa fa-star-o"></em></a></li>
                                            <li><a href="#"><em class="fa fa-star-o"></em></a></li>
                                            <li><a href="#"><em class="fa fa-star-o"></em></a></li>
                                        </ul>
                                    </div>
                                    <p class="title-contact">Liên hệ</p>
                                    <div class="contact-person clearfix">
                                        <a href="#" class="wrap-img pull-left"><img src="<?= $avatar ?>" alt=""></a>
                                        <div class="clearfix">
                                            <p class="not-required-wrap"><strong>Tên:</strong><span class="editable not-required" id="ref-adcontactinfo-name"></span></p>
                                            <p class="not-required-wrap"><strong>Điện thoại:</strong><span class="editable not-required" id="ref-adcontactinfo-phone"></span></p>
                                            <p><strong>Di động:</strong><span class="editable" id="ref-adcontactinfo-mobile"></span></p>
                                            <p class="not-required-wrap"><strong>Email:</strong><span class="editable not-required" id="ref-adcontactinfo-email"></span></p>
                                            <p class="not-required-wrap"><strong>Địa chỉ:</strong><span class="editable not-required" id="ref-adcontactinfo-address"></span></p>
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
                        </div>
                        <div style="text-align: center;"><input data-ajax-post="true" id="submit-form" type="button" value="ĐĂNG TIN" class="btn btn-primary btn-common action-button" style="width: 200px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>