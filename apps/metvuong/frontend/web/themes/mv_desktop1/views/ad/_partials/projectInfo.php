<?php 
use yii\helpers\Url;
?>
<div class="infor-duan-suggest clearfix" data-lat="<?= $project->lat ?>" data-lng="<?= $project->lng ?>">
	<a target="_blank" href="<?= Url::to(['building-project/view', 'slug' => $project->slug]); ?>" class="">
		<span class="wrap-img pull-left">
			<div class="img-intro"><div><img src="<?= $project->logoUrl ?>"></div></div>
		</span>
		<div class="overflow-all">
			<p class="name-duan"><?= $project->name ?></p>
			<div class="address-listing"><p><?= $project->location ?></p></div>
		</div>
	</a>
</div>