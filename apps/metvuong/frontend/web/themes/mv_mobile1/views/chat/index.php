<?php
use yii\helpers\Url;
?>
<!-- Chat History
<br>
Chat with <a href="<?=Url::to(['/chat/with', 'username'=>'xxxx'])?>">XXXX</a> -->

<div class="chat-history">
	<div class="title-top">Chat history</div>
	<div class="chat-list clearfix">
		<div class="item">
			<a href="<?=Url::to(['/chat/with', 'username'=>'xxxx'])?>">
				<span class="wrap-img"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/default-avatar.jpg' ?>" alt=""></span>
				<div class="chat-detail">
					<span class="pull-right time-chat">8h</span>
					<span class="name">James Bond</span>
					<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</span>
				</div>
			</a>
		</div>
		<div class="item">
			<a href="<?=Url::to(['/chat/with', 'username'=>'xxxx'])?>">
				<span class="wrap-img"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/MV-Agent Photo.jpg' ?>" alt=""></span>
				<div class="chat-detail">
					<span class="pull-right time-chat">8h</span>
					<span class="name">James Bond</span>
					<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</span>
				</div>
			</a>
		</div>
		<div class="item">
			<a href="<?=Url::to(['/chat/with', 'username'=>'xxxx'])?>">
				<span class="wrap-img"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/default-avatar.jpg' ?>" alt=""></span>
				<div class="chat-detail">
					<span class="pull-right time-chat">8h</span>
					<span class="name">James Bond</span>
					<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</span>
				</div>
			</a>
		</div>
		<div class="item">
			<a href="<?=Url::to(['/chat/with', 'username'=>'xxxx'])?>">
				<span class="wrap-img"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/22311_Khai-truong-Pearl-Plaza-7.jpg' ?>" alt=""></span>
				<div class="chat-detail">
					<span class="pull-right time-chat">1d</span>
					<span class="name">James Bond</span>
					<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</span>
				</div>
			</a>
		</div>
		<div class="item">
			<a href="<?=Url::to(['/chat/with', 'username'=>'xxxx'])?>">
				<span class="wrap-img"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/default-avatar.jpg' ?>" alt=""></span>
				<div class="chat-detail">
					<span class="pull-right time-chat">1d</span>
					<span class="name">James Bond</span>
					<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</span>
				</div>
			</a>
		</div>
		<div class="item">
			<a href="<?=Url::to(['/chat/with', 'username'=>'xxxx'])?>">
				<span class="wrap-img"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/MV-Agent Photo.jpg' ?>" alt=""></span>
				<div class="chat-detail">
					<span class="pull-right time-chat">2d</span>
					<span class="name">James Bond</span>
					<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</span>
				</div>
			</a>
		</div>
	</div>
</div>