<?php
use yii\helpers\Url;
?>
<div class="clearfix mgB-15">
    <p style="color: #4a933a;" class="desTotal"><strong><?=Yii::t('chart','Total user')?>: <?=1?></strong></p>
</div>
<ul class="clearfix listContact">
<?= $this->render($viewItem,['data' => $data]);?>
</ul>
<input type="button" class="btn-common pull-right _loadmore" value="<?=Yii::t('statistic', 'Load more')?>" data-url="<?=Url::to(['dashboard/clickchart-load-more'], true)?>">
