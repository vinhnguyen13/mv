<?php
	use vsoft\express\components\StringHelper;
	if($images):
		$firstGroup = array_splice($images, 0, 5);
		$imagesGroup = array_chunk($images, 8);
?>
<div class="gallery-detail clearfix" style="visibility: hidden;">
	<div class="bxslider">
		<div class="wrap-img-detail first-slide">
			<ul class="clearfix">
				<?php
					foreach ($firstGroup as $k => $image): 
						$thumb = ($k == 0) ? $image->imageMedium : $image->imageThumb;	
				?>
				<li>
					<div class="bgcover" style="background-image:url(<?= $thumb ?>);"></div>
					<a data-lightbox="detail-post" class="group mask" href="<?= $image->imageLarge ?>"><em class="fa fa-search"></em><img src="<?= $thumb ?>" alt="" style="display:none;"></a>
            	</li>
            	<?php endforeach; ?>
			</ul>
		</div>
		<?php foreach($imagesGroup as $images): ?>
		<div class="wrap-img-detail">
			<ul class="clearfix">
				<?php foreach ($images as $image): ?>
				<li>
					<div class="bgcover" style="background-image:url(<?= $image->imageThumb ?>);"></div>
					<a data-lightbox="detail-post" class="group mask" href="<?= $image->imageLarge ?>"><em class="fa fa-search"></em><img src="<?= $image->imageThumb ?>" alt="" style="display:none;"></a>
            	</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<?php else: ?>
<div class="gallery-detail clearfix"><div class="bxslider no-image"><?= Yii::t('ad', 'Không có hình ảnh đính kèm') ?></div></div>
<?php endif ?>

<script type="text/javascript">
	/*$(document).bind('gallery-load', function () {
		$('.gallery-detail').imagesLoaded()
	 	.always( function( instance ) {
	 		
	 		$('#map-loading').hide();
			if($('#detail-listing .bxslider').find('.wrap-img-detail').length > 0) {
				
				$('#detail-listing .bxslider').bxSlider({
					moveSlides: 1,
	                startSlide: 0,
	                startSlide: 0,
	                onSliderLoad: function() {
	                    this.infiniteLoop = false;
	                    this.hideControlOnEnd = true;
	                }
	            });
				lightbox.option({
		            'resizeDuration': 300,
		            'fadeDuration': 400
		        });
				$('.gallery-detail').css('visibility', 'visible');
			}
	 	});
	});*/
</script>