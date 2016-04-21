<?php 
use yii\helpers\Url;
?>
<div class="infor-duan-suggest clearfix">
	<a target="_blank" href="<?= Url::to(["building/$project->slug"]); ?>" class="pull-left">
		<span class="wrap-img">
			<div class="img-intro"><div><img src="<?= $project->logoUrl ?>"></div></div>
		</span>
	</a>
	<div class="overflow-all">
		<p class="name-duan"><a href="#"><?= $project->name ?></a></p>
		<p class="address-listing"><?= $project->location ?></p>
		<a target="_blank" href="<?= Url::to(["building/$project->slug"]); ?>" class="view-more"><?= Yii::t('ad', 'view more') ?><span class="icon arrowLeft-small-black"></span></a>
	</div>
</div>