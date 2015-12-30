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
			      <div class="panel-heading">Quản lý tin đăng</div>
			      <div class="panel-body">
			      	<ol class="list-unstyled">
	                    <li><a href="<?=Yii::$app->urlManager->createUrl(['ad/category/'])?>">Phân loại tin đăng</a></li>
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
<!--	                    <li><a href="--><?//=Yii::$app->urlManager->createUrl(['news/banner'])?><!--">Banner</a></li>-->
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
			<?php
			if (Yii::$app->user->can('accessSystems')) {
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
