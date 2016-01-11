<?php
use yii\web\View;
Yii::$app->getView()->registerJsFile('http://code.highcharts.com/highcharts.js', ['position' => View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('http://code.highcharts.com/modules/exporting.js', ['position' => View::POS_BEGIN]);
?>
<?php $this->beginContent('@app/views/layouts/layout-no-footer.php'); ?>
<div class="wrap-user-profile row">
	<div class="function-setting-user">
		<ul class="clearfix">
			<li>
				<a href="#">
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
				<a href="#">
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
				<a href="#">
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
