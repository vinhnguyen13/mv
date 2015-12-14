<?php
use yii\web\View;
Yii::$app->getView()->registerJsFile('http://code.highcharts.com/highcharts.js', ['position' => View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('http://code.highcharts.com/modules/exporting.js', ['position' => View::POS_BEGIN]);
?>
<?php $this->beginContent('@app/views/layouts/layout.php'); ?>
<div class="wrap-user-profile row">
    <?php $this->beginContent('@app/views/user-management/layouts/menu.php'); ?><?php $this->endContent();?>
    <div class="user_management">
        <?= $content ?>
    </div>
</div>
<?php $this->endContent();?>
