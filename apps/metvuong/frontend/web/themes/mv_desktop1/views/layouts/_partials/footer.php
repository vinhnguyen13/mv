<?php
use yii\helpers\Url;
?>
<footer class="">
	<div class="m-footer">
		<div class="clearfix">
			<div class="pull-left col-xs-6">
				<ul>
					<li><a href="<?=Url::to(['site/page', 'view'=>'about'])?>"><?=Yii::t('general', 'About Us')?></a></li>
					<li><a href="<?=Url::to(['site/page', 'view'=>'faq'])?>"><?=Yii::t('general', 'FAQs')?></a></li>
				</ul>
			</div>
			<div class="pull-right col-xs-6">
				<ul>
					<li><a href="<?=Url::to(['site/contact'])?>"><?=Yii::t('general', 'Contact Us')?></a></li>
					<li><a href="<?=Url::to(['site/page', 'view'=>'terms'])?>"><?=Yii::t('general', 'Terms and Conditions')?></a></li>
				</ul>
			</div>
		</div>
		<div class="infor-address">
			<p><?=Yii::t('general', 'MetVuong Address')?></p>
			<p><?=Yii::t('general', 'Copyright {year} {domain}', ['year'=>2016, 'domain'=>'MetVuong.com'])?></p>
		</div>
	</div>
	<div class="dt-footer clearfix container">
		<a href="#" class="logo-footer pull-left wrap-img"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/logo-white.png' ?>" alt=""></a>
		<div class="pull-right">
			<ul class="clearfix">
				<li><a href="<?=Url::to(['site/page', 'view'=>'about'])?>"><?=Yii::t('general', 'About Us')?></a></li>
				<li><a href="<?=Url::to(['site/page', 'view'=>'faq'])?>"><?=Yii::t('general', 'FAQs')?></a></li>
				<li><a href="<?=Url::to(['site/page', 'view'=>'terms'])?>"><?=Yii::t('general', 'Terms and Conditions')?></a></li>
				<li><a href="<?=Url::to(['site/page', 'view'=>'interestRate'])?>"><?=Yii::t('general', 'Interest rate')?></a></li>
			</ul>
			<p><?=Yii::t('general', 'Copyright {year} {domain}', ['year'=>2016, 'domain'=>'MetVuong.com'])?></p>
		</div>
	</div>
	<div class="license container">
		<p>Giấy phép CNDKDN số 0313814871 - ® Ghi rõ nguồn "metvuong.com" khi phát hành lại thông tin từ trang mạng này.</p>
		<p>Phát triển bởi CT TNHH DTTMDV Mét Vuông</p>
	</div>
</footer>
