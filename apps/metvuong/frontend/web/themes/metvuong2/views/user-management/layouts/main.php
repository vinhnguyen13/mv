<?php
use yii\web\View;
use yii\helpers\Url;
Yii::$app->getView()->registerJsFile('http://code.highcharts.com/highcharts.js', ['position' => View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('http://code.highcharts.com/modules/exporting.js', ['position' => View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/dashboard.js', ['position'=>View::POS_BEGIN]);
?>
<?php $this->beginContent('@app/views/layouts/layout-no-footer.php'); ?>
<div class="wrap-user-profile row">
	<div class="function-setting-user">
		<ul class="clearfix">
			<li>
				<a href="<?=Url::to(['user-management/chart'])?>">
					<em class="icon-layers"></em>
					<span>Dashboard</span>	
				</a>
			</li>
			<li>
				<a href="#">
					<em class="icon-camcorder"></em>
					<span>News</span>
				</a>
			</li>
			<li>
				<a href="#">
					<em class="icon-envelope"><i>20</i></em>
					<span>Leads</span>
				</a>
			</li>
			<li>
				<a href="#">
					<em class="icon-bubble"><i>5</i></em>
					<span>Messages</span>
				</a>
			</li>
			<li>
				<a href="<?=Url::to(['user-management/ad'])?>">
					<em class="icon-picture"></em>
					<span>Listings</span>
				</a>
			</li>
			<li>
				<a href="#">
					<em class="icon-bell"></em>
					<span>Notifications</span>
				</a>
			</li>
			<li class="pull-right">
				<a href="<?=Url::to(['user-management/profile'])?>">
					<em class="icon-settings"></em>
					<span>Settings</span>
				</a>
			</li>
		</ul>
	</div>
    <?php $this->beginContent('@app/views/user-management/layouts/menu.php'); ?><?php $this->endContent();?>
    <div class="user_management col-xs-9">
    	<div class="wrap-setting-detail clearfix">
    		<div class="wrap-settings">
	        	<?= $content ?>
	        </div>
        </div>
    </div>
</div>
<?php $this->endContent();?>

<div class="notifi-popup">
	<div class="notifi-item">
		<em class="fa fa-comment-o"></em>Có 1 tin nhắn từ <a href="#">Minh Quang</a> gửi cho bạn		
	</div>
</div>

	
<div class="chat-group">
	<div class="title-chat clearfix">
		<em class="fa fa-close pull-right"></em><em class="fa fa-comments"></em>Nhà mô giới
	</div>
	<div class="wrap-chat clearfix">
		<div class="wrap-me chat-infor">
			<div class="avatar-chat pull-left"><a href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/2015 - dddd1.jpg" alt=""></a></div>
			<div class="wrap-txt-chat pull-left">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
			</div>
		</div>
		<div class="wrap-you chat-infor">
			<div class="avatar-chat pull-right"><a href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/621042015085736.jpg" alt=""></a></div>
			<div class="wrap-txt-chat pull-right">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
			</div>
		</div>
		<div class="wrap-me chat-infor">
			<div class="avatar-chat pull-left"><a href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/2015 - dddd1.jpg" alt=""></a></div>
			<div class="wrap-txt-chat pull-left">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
			</div>
		</div>
		<div class="wrap-you chat-infor">
			<div class="avatar-chat pull-right"><a href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/621042015085736.jpg" alt=""></a></div>
			<div class="wrap-txt-chat pull-right">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
			</div>
		</div>
		<div class="wrap-me chat-infor">
			<div class="avatar-chat pull-left"><a href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/2015 - dddd1.jpg" alt=""></a></div>
			<div class="wrap-txt-chat pull-left">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
			</div>
		</div>
		<div class="wrap-you chat-infor">
			<div class="avatar-chat pull-right"><a href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/621042015085736.jpg" alt=""></a></div>
			<div class="wrap-txt-chat pull-right">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
			</div>
		</div>
		<div class="loading-chat">
			Typing<span class="one">.</span><span class="two">.</span><span class="three">.</span>
		</div>
	</div>
	<div class="type-input-chat">
		<input type="text" placeholder="Nhập tin nhắn...">
		<button class="sm-chat"><em class="fa fa-location-arrow"></em></button>
	</div>
</div>
