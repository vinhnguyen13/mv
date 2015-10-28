<?php
use yii\web\View;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
$this->registerJsFile(Yii::getAlias('@web') . '/js/masonry.pkgd.min.js', ['depends' => ['yii\web\YiiAsset']]);

$javascript = <<<EOD
	var row = $('.row');
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
                    <div class="panel-heading">Express</div>
                    <div class="panel-body">
                        <ol class="list-unstyled">
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['express/booking'])?>">Booking</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['express/building'])?>">Building</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['express/contact'])?>">Contact</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['express/banner'])?>">Banner</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['express/pricing'])?>">Pricing</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['express/apartment-type'])?>">Apartment Type</a></li>
                        </ol>
                    </div>
                </div>
            </div>
            <?php if(false){?>
                <div class="col-lg-3 item">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Development</div>
                        <div class="panel-body">
                            <ol class="list-unstyled">
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['gii', 'id' => 0])?>">Gii</a></li>
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['setting'])?>">Setting</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            <?php }?>
            <div class="col-lg-3 item">
                <div class="panel panel-primary">
                    <div class="panel-heading">News</div>
                    <div class="panel-body">
                        <ol class="list-unstyled">
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['news/cms'])?>">Content</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['news/cms-catalog'])?>">Categories</a></li>
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
