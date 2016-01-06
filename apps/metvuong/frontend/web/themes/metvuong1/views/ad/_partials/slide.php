<?php
                    		use vsoft\express\components\StringHelper;
if(!empty($images)) :
									$firstImage = array_shift($images);
									$images = array_chunk($images, 4);
						?>
                                    <div class="gallery-detail clearfix" style="visibility: hidden;">
			                            <div class="bxslider">
			                                <div class="wrap-img-detail">
			                                    <ul class="clearfix">
			                                        <li class="img-big">
			                                        	<?php if(!StringHelper::startsWith($firstImage->file_name, 'http')): ?>
			                                            <div class="bgcover" style="background-image:url(<?= $firstImage->imageMedium ?>);"></div>
			                                            <a data-lightbox="detail-post" class="group mask" href="<?= $firstImage->imageLarge ?>"><em class="fa fa-search"></em><img src="<?= $firstImage->imageMedium ?>" alt="" style="display:none;"></a>
			                                        	<?php else: ?>
			                                        	<div class="bgcover" style="background-image:url(<?= str_replace('/745x510/', '/350x280/', $firstImage->file_name) ?>);"></div>
			                                            <a data-lightbox="detail-post" class="group mask" href="<?= $firstImage->file_name ?>"><em class="fa fa-search"></em><img src="<?= str_replace('/745x510/', '/350x280/', $firstImage->file_name) ?>" alt="" style="display:none;"></a>
			                                        	<?php endif; ?>
			                                        </li>
			                                    </ul>
			                                </div>
			                                <?php foreach($images as $imagesGroup): ?>
			                                	<div class="wrap-img-detail">
			                                    	<ul class="clearfix">
					                                <?php foreach($imagesGroup as $image): 
					                                		if(!StringHelper::startsWith($image->file_name, 'http')):
					                                ?>
					             						<li>
				                                            <div class="bgcover" style="background-image:url(<?= $image->imageThumb ?>);"></div>
				                                            <a data-lightbox="detail-post" class="group mask" href="<?= $image->imageLarge ?>"><em class="fa fa-search"></em><img src="<?= $image->imageThumb ?>" alt="" style="display:none;"></a>
				                                            
				                                        </li>
					                                <?php else: ?>
					                                <li>
					                                	<div class="bgcover" style="background-image:url(<?= str_replace('/745x510/', '/350x280/', $image->file_name) ?>);"></div>
					                                	<a data-lightbox="detail-post" class="group mask" href="<?= $image->file_name ?>"><em class="fa fa-search"></em><img src="<?= str_replace('/745x510/', '/350x280/', $image->file_name) ?>" alt="" style="display:none;"></a>
					                                </li>
					                                <?php endif; endforeach; ?>
					                                </ul>
				                                </div>
			                                <?php endforeach; ?>
			                            </div>
			                        </div>
                        <?php else: ?>
                        <div class="gallery-detail clearfix"><div class="bxslider no-image"><?= Yii::t('ad', 'Không có hình ảnh đính kèm') ?></div></div>
                        <?php endif; ?>