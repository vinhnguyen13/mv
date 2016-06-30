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
					<li><a href="<?=Url::to(['site/page', 'view'=>'quy-che-hoat-dong'])?>">Quy chế hoạt động</a></li>
					<li><a href="<?=Url::to(['site/page', 'view'=>'chinh-sach-bao-mat'])?>">Chính sách bảo mật</a></li>
				</ul>
			</div>
			<div class="pull-right col-xs-6">
				<ul>
					<li><a href="<?=Url::to(['site/contact'])?>"><?=Yii::t('general', 'Contact Us')?></a></li>
					<li><a href="<?=Url::to(['site/page', 'view'=>'terms'])?>"><?=Yii::t('general', 'Terms and Conditions')?></a></li>
					<li><a href="<?=Url::to(['site/page', 'view'=>'giai-quyet-tranh-chap'])?>">Quy trình giải quyết tranh chấp</a></li>
				</ul>
			</div>
		</div>
		<div class="infor-address">
			<p><?=Yii::t('general', 'MetVuong Address')?></p>
			<p><?=Yii::t('general', 'Copyright {year} {domain}', ['year'=>2016, 'domain'=>'MetVuong.com'])?></p>
			<p>Giấy phép số 0313814871, Cấp Bởi Sở Kế Hoạch và Đầu Tư Thành Phố Hồ Chí Minh, Ngày 19/05/2016.</p>
			<p>Các thông tin phát hành lại từ trang này phải ghi nguồn "metvuong.com"</p>
			<p><?=Yii::t('general', 'Developed by MetVuong LTD. Company')?></p>
			<a href="http://online.gov.vn/HomePage/WebsiteDisplay.aspx?DocId=24505"><img alt="" title="" src="http://online.gov.vn/seals/IZqmwD4BzN5EISz3RG0KRQ==.jpgx" /></a>
		</div>
	</div>
	<div class="dt-footer clearfix container">
		<div class="logo-footer pull-left">
			<a href="#" class="wrap-img"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/logo-white.png' ?>" alt=""></a>
		</div>
		<div class="pull-right col-right">
			<div class="clearfix">
				<ul>
					<li><a href="<?=Url::to(['site/page', 'view'=>'about'])?>"><?=Yii::t('general', 'About Us')?></a></li>
					<li><a href="<?=Url::to(['site/page', 'view'=>'faq'])?>"><?=Yii::t('general', 'FAQs')?></a></li>
					<li><a href="<?=Url::to(['site/page', 'view'=>'terms'])?>"><?=Yii::t('general', 'Terms and Conditions')?></a></li>
					<!-- <li><a href="<?=Url::to(['site/page', 'view'=>'interestRate'])?>"><?=Yii::t('general', 'Interest rate')?></a></li> -->
				</ul>
			</div>
			<p><?=Yii::t('general', 'Copyright {year} {domain}', ['year'=>2016, 'domain'=>'MetVuong.com'])?></p>
			<p>Giấy phép số 0313814871, Cấp Bởi Sở Kế Hoạch và Đầu Tư Thành Phố Hồ Chí Minh, Ngày 19/05/2016.</p>
			<p>Các thông tin phát hành lại từ trang này phải ghi nguồn "metvuong.com"</p>
			<p>Người quản lý trang tin: <span class="font-600">Nguyễn Thị Ngọc Thủy</span></p>
			<p><?=Yii::t('general', 'Developed by MetVuong LTD. Company')?></p>
			<a href="http://online.gov.vn/HomePage/WebsiteDisplay.aspx?DocId=24505"><img alt="" title="" src="http://online.gov.vn/seals/IZqmwD4BzN5EISz3RG0KRQ==.jpgx" /></a>
		</div>
	</div>
</footer>
