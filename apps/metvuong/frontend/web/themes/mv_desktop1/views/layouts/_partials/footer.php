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
		<div class="license">
			<p><?=Yii::t('general', 'Copyright {year} {domain}', ['year'=>2016, 'domain'=>'MetVuong.com'])?></p>
			<p><?=Yii::t('general', 'By granting the Department of Planning and Investment of Ho Chi Minh City, Date 05.19.2016')?></p>
			<p><?=Yii::t('general', 'Permission No. 0313814871 - ® Copying information from this website should be included the tag “metvuong.com”')?></p>
			<p><?=Yii::t('general', 'Developed by MetVuong LTD. Company')?></p>
			<a class="logo-bct" href="http://online.gov.vn/HomePage/WebsiteDisplay.aspx?DocId=24505"><img alt="" title="" src="http://online.gov.vn/seals/IZqmwD4BzN5EISz3RG0KRQ==.jpgx" /></a>
		</div>
	</div>
	<div class="dt-footer clearfix container">
		<!-- <div class="logo-footer pull-left">
			<a href="#" class="wrap-img">
				<img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/logo-white.png' ?>" alt="">
			</a>
		</div> -->
		<ul class="list-menu-footer">
			<li><a href="<?=Url::to(['site/page', 'view'=>'about'])?>"><?=Yii::t('general', 'About Us')?></a></li>
			<li><a href="<?=Url::to(['site/page', 'view'=>'faq'])?>"><?=Yii::t('general', 'FAQs')?></a></li>
			<li><a href="<?=Url::to(['site/page', 'view'=>'terms'])?>"><?=Yii::t('general', 'Terms and Conditions')?></a></li>
			<li><a href="<?=Url::to(['site/page', 'view'=>'interestRate'])?>"><?=Yii::t('general', 'Interest rate')?></a></li>
		</ul>
		<div class="license">
			<p><?=Yii::t('general', 'Copyright {year} {domain}', ['year'=>2016, 'domain'=>'MetVuong.com'])?></p>
			<p><?=Yii::t('general', 'By granting the Department of Planning and Investment of Ho Chi Minh City, Date 05.19.2016')?></p>
			<p><?=Yii::t('general', 'Permission No. 0313814871 - ® Copying information from this website should be included the tag “metvuong.com”')?></p>
			<p><?=Yii::t('general', 'Developed by MetVuong LTD. Company')?></p>
			<a class="logo-bct" href="http://online.gov.vn/HomePage/WebsiteDisplay.aspx?DocId=24505"><img alt="" title="" src="http://online.gov.vn/seals/IZqmwD4BzN5EISz3RG0KRQ==.jpgx" /></a>
		</div>
	</div>
</footer>
