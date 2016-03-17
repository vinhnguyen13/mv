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
            <?php
            if (Yii::$app->user->can('accessSystems')) {
                ?>
                <div class="col-lg-3 item">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Systems</div>
                        <div class="panel-body">
                            <ol class="list-unstyled">
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['dev/clear-cache'])?>">Clear cache</a></li>
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
