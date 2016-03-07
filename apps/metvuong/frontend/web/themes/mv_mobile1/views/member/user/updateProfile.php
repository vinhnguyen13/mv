<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 2/26/2016
 * Time: 9:51 AM
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="title-fixed-wrap">
	<div class="edit-user-tt">
		<div class="title-top">
			<a href="#">TÀI KHOẢN CỦA BẠN</a>
			<a href="javascript:history.back()" id="prev-page"><span class="icon arrowRight-1"></span></a>
			<a href="#" id="done-page"><span class="icon icon-done"></span></a>
		</div>
		<div class="wrap-edit-tt">
			<div class="avatar-user-pr">
                <div class="wrap-img avatar">
                    <img id="profileAvatar" data-toggle="modal" data-target="#avatar" src="<?=$model->avatar?>" alt="metvuong avatar" />
                </div>
            </div>
			<section class="ttcn">
				<div class="title-update-tt">
					THÔNG TIN CÁ NHÂN
					<a href="#edit-ttcn" class="edit-tt"><span class="icon icon-edit-small-1"></span></a>
				</div>
				<div class="list-tt-user wrap-attr-detail">
					<ul class="clearfix">
						<li>
							<span class="attr-right pull-right first-name"><?=empty($model->first_name) ? $model->name : $model->first_name ?></span>
							<span>Tên</span>
						</li>
						<li>
							<span class="attr-right pull-right last-name"><?=$model->last_name?></span>
							<span>Họ</span>
						</li>
						<li>
							<span class="attr-right pull-right phone-num"><?=$model->mobile?></span>
							<span>Số điện thoại</span>
						</li>
						<li>
							<span class="attr-right pull-right im" data-im="1"><?=$model->owner == 2 ? "Người môi giới" : "Chủ nhà"?></span>
							<span>Tôi là</span>
						</li>
					</ul>
				</div>
			</section>
			
			<section class="mtbt">
				<div class="title-update-tt">
					MÔ TẢ BẢN THÂN
					<a href="#edit-mtbt" class="edit-tt"><span class="icon icon-edit-small-1"></span></a>
				</div>
				<div class="wrap-attr-detail">
					<div class="txt-wrap">
						<p class="txt-mota"><?=$model->about?></p>
					</div>
				</div>
			</section>

			<section class="diadiem">
				<div class="title-update-tt">
					ĐỊA ĐIỂM
					<!-- <a href="#" class="edit-tt"><span class="icon icon-edit-small-1"></span></a> -->
				</div>
				<div class="list-tt-user wrap-attr-detail">
					<ul class="clearfix">
						<li>
							<span class="attr-right pull-right">VietNam</span>
							<span>Country</span>
						</li>
					</ul>
				</div>
			</section>

			<section class="matkhau">
				<div class="title-update-tt">
					THAY MẬT KHẨU
					<a href="#edit-changepass" class="edit-tt"><span class="icon icon-edit-small-1"></span></a>
				</div>
			</section>
		</div>
	</div>
</div>

<div class="modal fade" id="avatar" tabindex="-1" role="dialog" aria-labelledby="myModalAvatar">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="icon"></span>
                </button>
                <h3>Thay đổi hình đại diện</h3>
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                    <div class="avatar" style="margin-bottom: 50px;">
                        <?php
                        $avatar = str_replace("/store/avatar/","", $model->avatar);
                        $avatar = \Yii::getAlias('@store') . DIRECTORY_SEPARATOR . "avatar" . DIRECTORY_SEPARATOR . $avatar;

                        $form = ActiveForm::begin([
                            'id' => 'change-avatar-form',
                            'enableAjaxValidation' => false,
                            'enableClientValidation' => true,
                            'layout' => 'horizontal',
                            'fieldConfig' => [
                                'horizontalCssClasses' => [
                                    'wrapper' => 'col-sm-9',
                                ],
                            ],
                        ]); ?>
                        <?=Html::hiddenInput('deleteLater', '', ['id' => 'delete-later']);?>
                        <?= $form->field($model, 'avatar')->widget(\common\widgets\FileUploadAvatar::className(), [
                            'url' => Url::to(['/user-management/avatar', 'folder' => 'avatar']),
                            'clientOptions' => [
                                'maxNumberOfFiles' => 1,
                                'imageMaxWidth' => 800,
                                'imageMaxHeight' => 800,
                                'disableImageResize' => false,
                                'imageCrop' => true,
                                'noAvatar' => file_exists($avatar) ? true : false,
                            ],
                            'fieldOptions' => ['folder' => 'avatar'],
                        ])->label(false) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="edit-ttcn" class="popup-common hide-popup">
	<div class="wrap-popup">
        <?php
        $profile_form = Yii::createObject([
            'class'    => \frontend\models\ProfileForm::className(),
            'scenario' => 'updateprofile',
        ]);
        $f = ActiveForm::begin([
            'id' => 'form-edit-ttcn',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'action' => Url::to(['member/update-profile', 'username'=>Yii::$app->user->identity->username])
        ]);
        ?>
		<div class="title-popup clearfix text-center">
			Thông tin cá nhân
			<a href="#" class="txt-cancel btn-cancel">Cancel</a>
			<a href="#" class="txt-done btn-done">Done</a>
		</div>
		<div class="inner-popup">
            <div class="list-tt-user wrap-attr-detail">
				<ul class="clearfix">
					<li>
						<span>Tên</span>
                        <?= $f->field($profile_form, 'first_name')->textInput(['class'=>'attr-right first-name', 'value' => empty($model->first_name) ? $model->name : $model->first_name ])->label(false)?>
					</li>
					<li>
						<span>Họ</span>
                        <?= $f->field($profile_form, 'last_name')->textInput(['class'=>'attr-right last-name', 'value' => $model->last_name ])->label(false)?>
					</li>
					<li>
						<span>Số điện thoại</span>
                        <?= $f->field($profile_form, 'mobile')->textInput(['class'=>'attr-right phone-num', 'value' => $model->mobile ])->label(false)?>
					</li>
					<li>
						<span>Tôi là</span>
                        <?= $f->field($profile_form, 'owner')->dropDownList([1 => 'Chủ nhà', 2 => 'Người môi giới'], [
                            'options' => [$model->owner => ['Selected ' => true]],
                            'class' => 'attr-right im'
                        ])->label(false)?>
					</li>
				</ul>

			</div>
		</div>
        <?php $f->end(); ?>
	</div>
</div>

<div id="edit-mtbt" class="popup-common hide-popup">
	<div class="wrap-popup">
        <?php
        $profile_form = Yii::createObject([
            'class'    => \frontend\models\ProfileForm::className(),
            'scenario' => 'updateprofile',
        ]);
        $f = ActiveForm::begin([
            'id' => 'form-edit-mtbt',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'action' => Url::to(['member/update-profile', 'username'=>Yii::$app->user->identity->username])
        ]);
        ?>
		<div class="title-popup clearfix text-center">
			MÔ TẢ BẢN THÂN
			<a href="#" class="txt-cancel btn-cancel">Cancel</a>
			<a href="#" class="txt-done btn-done">Done</a>
		</div>
		<div class="inner-popup">
            <div class="list-tt-user wrap-attr-detail">
                <?= $f->field($profile_form, 'about')->textarea(['class'=>'txt-mota', 'value' => $model->about ])->label(false)?>
			</div>
		</div>
        <?php $f->end(); ?>
	</div>
</div>

<div id="edit-changepass" class="popup-common hide-popup">
	<div class="wrap-popup">
        <?php
        $profile_form = Yii::createObject([
            'class'    => \frontend\models\ProfileForm::className(),
            'scenario' => 'password',
        ]);
        $f = ActiveForm::begin([
            'id' => 'form-edit-changepass',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'action' => Url::to(['member/password'])
        ]);
        ?>
		<div class="title-popup clearfix text-center">
			MẬT KHẨU
			<a href="#" class="txt-cancel btn-cancel">Back</a>
			<a href="#" class="txt-done btn-done">Done</a>
		</div>
		<div class="inner-popup">
            <div class="list-tt-user wrap-attr-detail">
				<ul class="clearfix">
					<li>
						<span>Password cũ</span>
                        <?= $f->field($model, 'old_password')->textInput(['class' => 'attr-right old_password', 'type' => 'password', 'placeholder' => 'Nhập...'])->label(false) ?>
					</li>
					<li>
						<span>Password mới</span>
                        <?= $f->field($model, 'new_password')->textInput(['class' => 'attr-right new_password', 'type' => 'password', 'placeholder' => 'Nhập...'])->label(false) ?>
					</li>
					<li>
						<span>Gõ lại Password mới</span>
						<input type="password" class="attr-right re-type-pass" value="" placeholder="Nhập...">
					</li>
                    <li>
						<div class="error hide" style="font-weight: bold;"></div>
					</li>
				</ul>
			</div>
		</div>
        <?php $f->end(); ?>
	</div>
</div>

<script>
	$(document).ready(function () {
		$('#edit-ttcn, #edit-mtbt, #edit-changepass').popupMobi({
			btnClickShow: ".edit-tt",
			closeBtn: '.btn-cancel',
			funCallBack: function (itemClick, popupItem) {
			}
		});

        $('#avatar').on('hidden.bs.modal', function () {
            var url = $('.files .name a').attr("href");
            console.log(url);
            if(url == null || url == '')
                url = '/store/avatar/default-avatar.jpg';
            $('#headAvatar').attr("src", url);
            $('.avatar img').attr("src", url);
            $('.user-edit img').attr("src", url);
        });

        $('#edit-ttcn .btn-done').click(function(){
            var timer = 0;
            clearTimeout(timer);
            timer = setTimeout(function () {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: $('#form-edit-ttcn').attr('action'),
                    data: $('#form-edit-ttcn').serializeArray(),
                    success: function (data) {
                        if(data.statusCode == 200){
                            $('#edit-ttcn').addClass('hide-popup');
                            $('.ttcn .first-name').html(data.modelResult.first_name);
                            $('.ttcn .last-name').html(data.modelResult.last_name);
                            $('.ttcn .phone-num').html(data.modelResult.mobile);
                            if(data.modelResult.owner == 1)
                                $('.ttcn .im').html('Chủ nhà');
                            else
                                $('.ttcn .im').html('Môi giới');
                        }
                        return true;
                    },
                    error: function (data) {
    //                    var strMessage = '';
    //                    $.each(data.parameters, function(idx, val){
    //                        var element = 'form-edit-ttcn_'+idx;
    //                        strMessage += "\n" + val;
    //                    });
    //                    alert(strMessage);
                        return false;
                    }
                });
            }, 500);
        });

        $('#edit-mtbt .btn-done').click(function(){
            var timer = 0;
            clearTimeout(timer);
            timer = setTimeout(function () {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: $('#form-edit-mtbt').attr('action'),
                    data: $('#form-edit-mtbt').serializeArray(),
                    success: function (data) {
                        if(data.statusCode == 200){
                            $('#edit-mtbt').addClass('hide-popup');
                            $('.mtbt .txt-mota').html(data.modelResult.about);
                        }
                        return true;
                    },
                    error: function (data) {
                        //                    var strMessage = '';
                        //                    $.each(data.parameters, function(idx, val){
                        //                        var element = 'form-edit-ttcn_'+idx;
                        //                        strMessage += "\n" + val;
                        //                    });
                        //                    alert(strMessage);
                        return false;
                    }
                });
            }, 500);
        });

        $('#edit-changepass .btn-done').click(function(){
            $('#edit-changepass .error').addClass('hide');
            var new_password = $('.new_password').val();
            var rePass = $('.re-type-pass').val();
            if(new_password !== rePass){
                $('#edit-changepass .error').html('<br>Confirm password not match.');
                $('#edit-changepass .error').removeClass('hide');
                $('.re-type-pass').focus();
                return false;
            }

            var timer = 0;
            clearTimeout(timer);
            timer = setTimeout(function () {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: $('#form-edit-changepass').attr('action'),
                    data: $('#form-edit-changepass').serializeArray(),
                    success: function (data) {
                        if(data.statusCode == 200){
                            $('#edit-changepass .error').html('<br> Reset password success.');
                            $('#edit-changepass .error').removeClass('hide');
                        } else {
                            $('#edit-changepass .error').removeClass('hide');
                            var strMessage = '';
                            $.each(data.parameters, function(idx, val){
                                var element = 'form-edit-changepass_'+idx;
                                strMessage += "<br/>" + val;
                            });
                            $('#edit-changepass .error').html(strMessage);
                        }
                        return true;
                    },
                    error: function (data) {
                        var strMessage = '';
                        $.each(data.parameters, function(idx, val){
                            var element = 'form-edit-changepass_'+idx;
                            strMessage += "\n" + val;
                        });
                        $('#edit-changepass .error').html(strMessage);
                        return false;
                    }
                });
            }, 500);
        });

    });
</script>