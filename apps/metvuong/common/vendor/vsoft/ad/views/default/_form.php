<?php 

use yii\widgets\ActiveForm;
?>
<div id="project-building-form" class="cms-show-form">
	<?php
		$form = ActiveForm::begin([
			'id' => 'bp-form',
	    	'options' => ['spellcheck' => 'false'],
	    	'enableClientValidation' => false,
	    	'enableAjaxValidation' => false,
		]);
	?>
	<div class="side-bar">
    	<ul class="bp-contents">
		    <li class="show-content active"><a href="#">Tổng quan dự án</a></li>
		    <li class="show-content"><a href="#">Bản đồ vị trí</a></li>
		    <li class="show-content"><a href="#">Tiện ích</a></li>
		    <li class="show-content"><a href="#">Phim 3D dự án</a></li>
		    <li class="show-content"><a href="#">Tiến độ xây dựng</a></li>
		    <li class="show-content"><a href="#">SEO</a></li>
		</ul>
		<div class="seperator"></div>
		<ul class="bp-contents">
		    <?php foreach($areaTypes as $f => $areaType): ?>
		    <li class="bp-subcontents">
		    	<a href="#"><?= $areaType ?></a>
		    	<ul>
		    		<li class="show-content"><a href="#">Mặt bằng</a></li>
			    	<li class="show-content"><a href="#">Giá bán & thanh toán</a></li>
			    	<li class="show-content"><a href="#">Chương trình bán hàng</a></li>
			    	<li class="show-content"><a href="#">Tài liệu bán hàng</a></li>
		    	</ul>
		    </li>
		    <?php endforeach; ?>
		</ul>
    </div>
    <?php ActiveForm::end(); ?>
</div>