<?php
use yii\web\View;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
$this->registerJsFile(Yii::getAlias('@web') . '/js/masonry.pkgd.min.js', ['depends' => ['yii\web\YiiAsset']]);

$javascript = <<<EOD
	var row = $('.row');

	row.masonry({itemSelector: '.item', columnWidth: '.sizer'});
	$('#search').keyup(function(){
		var key = $(this).val().toLowerCase();
		row.find('.item').each(function(){
			var self = $(this);
			if(self.text().toLowerCase().indexOf(key) == -1) {
				self.hide();
			} else {
				self.show();
			}
		});
		row.masonry('layout');
	});
EOD;

$this->registerJs($javascript, View::POS_END, 'masonry');
?>
<div class="site-index">
    <div class="body-content">
		<input type="text" id="search" class="form-control" style="margin-bottom: 12px; width: 320px" placeholder="Filter" />
        <div class="row">
        	<div class="col-lg-3 sizer"></div>
            <div class="col-lg-3 item">
            	<div class="panel panel-primary">
			      <div class="panel-heading">Quản lý tin CRAW</div>
			      <div class="panel-body">
			      	<ol class="list-unstyled">
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['craw/manager/'])?>">Quản lý tin CRAW</a></li>
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['craw/manager/index2'])?>">Quản lý tin CRAW - 2</a></li>
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['craw/agent/'])?>">Quản lý môi giới</a></li>
	                </ol>
			      </div>
			    </div>
            </div>
            <div class="col-lg-3 item">
            	<div class="panel panel-primary">
			      <div class="panel-heading">Quản lý tin đăng</div>
			      <div class="panel-body">
			      	<ol class="list-unstyled">
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['ad/tracking-search/'])?>">Tracking search</a></li>
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['ad/auto-save/'])?>">Tin lưu tự động</a></li>
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['ad/category-group/'])?>">Phân loại tin đăng theo nhóm</a></li>
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['ad/category/'])?>">Phân loại tin đăng</a></li>
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['ad/report/'])?>">Báo cáo tin đăng</a></li>
	                </ol>
			      </div>
			    </div>
            </div>
            <div class="col-lg-3 item">
            	<div class="panel panel-primary">
			      <div class="panel-heading">Dự án / Chủ đầu tư</div>
			      <div class="panel-body">
			      	<ol class="list-unstyled">
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['ad/building-project/'])?>">Dự án</a></li>
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['ad/investor/'])?>">Chủ đầu tư</a></li>
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['ad/architect/'])?>">Danh sách kiến trúc sư</a></li>
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['ad/contractor/'])?>">Nhà thầu thi công</a></li>
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['ad/facility/'])?>">Tiện ích</a></li>
	                </ol>
			      </div>
			    </div>
            </div>
            <div class="col-lg-3 item">
            	<div class="panel panel-primary">
			      <div class="panel-heading">News</div>
			      <div class="panel-body">
			      	<ol class="list-unstyled">
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['news/cms'])?>">Content</a></li>
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['news/cms-catalog'])?>">Categories</a></li>
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['express/meta'])?>">Meta</a></li>
	                </ol>
			      </div>
			    </div>
            </div>
            <div class="col-lg-3 item">
            	<div class="panel panel-primary">
			      <div class="panel-heading">User Management</div>
			      <div class="panel-body">
			      	<ol class="list-unstyled">
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['user/admin'])?>">User</a></li>
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['admin'])?>">User ACL</a></li>
	                </ol>
			      </div>
			    </div>
            </div>
			<div class="col-lg-3 item">
				<div class="panel panel-primary">
					<div class="panel-heading">Languages</div>
					<div class="panel-body">
						<ol class="list-unstyled">
							<li><a href="<?=Yii::$app->urlManager->createUrl(['translatemanager'])?>">Manager</a></li>
							<li><a href="<?=Yii::$app->urlManager->createUrl(['translatemanager/language/scan'])?>">Scan</a></li>
							<li><a href="<?=Yii::$app->urlManager->createUrl(['translatemanager/language/optimizer'])?>">Optimizer</a></li>
						</ol>
					</div>
				</div>
			</div>
            <div class="col-lg-3 item">
                <div class="panel panel-primary">
                    <div class="panel-heading">Coupon</div>
                    <div class="panel-body">
                        <ol class="list-unstyled">
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['coupon/coupon-event'])?>">Coupon Event</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['coupon/coupon-code'])?>">Coupon Code</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['coupon/coupon-code/promotion'])?>">Donate</a></li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 item">
                <div class="panel panel-primary">
                    <div class="panel-heading">Payment</div>
                    <div class="panel-body">
                        <ol class="list-unstyled">
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['ec/ec-payment-method'])?>">Payment method</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['ec/ec-package'])?>">Package</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['ec/ec-charge'])?>">Charge</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['ec/ec-balance'])?>">Balance</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['ec/ec-transaction-history'])?>">Transaction</a></li>
                        </ol>
                    </div>
                </div>
            </div>
			<?php
			$permissionName = !empty(Yii::$app->setting->get('aclAdmin')) ? Yii::$app->setting->get('aclAdmin') : \common\components\Acl::ACL_ADMIN;
			if (Yii::$app->user->can($permissionName)) {
				?>
				<div class="col-lg-3 item">
					<div class="panel panel-primary">
						<div class="panel-heading">Systems</div>
						<div class="panel-body">
							<ol class="list-unstyled">
								<li><a href="<?= Yii::$app->urlManager->createUrl(['gii']) ?>">Gii</a></li>
								<li><a href="<?= Yii::$app->urlManager->createUrl(['setting']) ?>">Setting</a></li>
							</ol>
						</div>
					</div>
				</div>
				<?php
			}
			?>
        </div>
    </div>
</div>
