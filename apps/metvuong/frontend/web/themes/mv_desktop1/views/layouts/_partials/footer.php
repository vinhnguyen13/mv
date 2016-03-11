<?php
use yii\helpers\Url;
?>
<footer class="">
	<div class="m-footer">
		<div class="clearfix">
			<div class="pull-left col-xs-6">
				<ul>
					<li><a href="<?=Url::to(['site/page', 'view'=>'about'])?>">Về Chúng Tôi</a></li>
					<li><a href="<?=Url::to(['site/page', 'view'=>'faq'])?>">Câu Hỏi Thường Gặp</a></li>
				</ul>
			</div>
			<div class="pull-right col-xs-6">
				<ul>
					<li><a href="<?=Url::to(['site/contact'])?>">Liên Hệ</a></li>
					<li><a href="<?=Url::to(['site/page', 'view'=>'terms'])?>">Điều Khoản & Điều Kiện</a></li>
				</ul>
			</div>
		</div>
		<div class="infor-address">
			<p>21 Nguyễn Trung Ngạn, P.Bến Nghé, Q1, Tp.Ho Chi Minh</p>
			<p>&copy; Copyright 2016 <a href="#">metvuong.com</a>. All rights reserved.</p>
		</div>
	</div>
	<div class="dt-footer clearfix container">
		<a href="#" class="logo-footer pull-left wrap-img"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/logo-white.png' ?>" alt=""></a>
		<div class="pull-right">
			<ul class="clearfix">
				<li><a href="#">Về Chúng Tôi</a></li>
				<li><a href="#">Những Câu Hỏi Thường Gặp</a></li>
				<li><a href="#">Điều Khoản & Điều Kiện</a></li>
			</ul>
			<p>© Copyright 2016 Metvuong.com. All rights reserved.</p>
		</div>
	</div>
</footer>