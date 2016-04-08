<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 4/8/2016 11:26 AM
 */
use yii\helpers\Url;
use yii\widgets\LinkPager;

$count_product = $pagination->totalCount;
?>
<ul class="clearfix list-item">
    <?php
    if($count_product > 0) {
        foreach ($products as $product) { ?>
            <li>
                <div class="item">
                    <div class="img-show">
                        <div>
                            <a href="<?= Url::to(['/dashboard/statistics', 'id' => $product->id]) ?>"><img
                                    src="<?= $product->representImage ?>"></a>
                        </div>
                        <a href="<?= Url::to(['/ad/update', 'id' => $product->id]) ?>" class="edit-duan">
                            <span class="icon-mv"><span class="icon-edit-copy-4"></span></span>
                        </a>
                    </div>
                    <div class="intro-detail">
                        <div class="address-feat clearfix">
                            <p class="date-post"><span
                                    class="font-600"><?= Yii::t('statistic', 'Date of posting') ?>
                                    :</span> <?= date("d/m/Y", $product->created_at) ?></p>
                            <?php if ($product->projectBuilding): ?>
                                <p class="name-duan"><?= $product->projectBuilding->name ?></p>
                            <?php endif; ?>
                            <p class="loca-duan"><?= $product->address ?></p>

                            <p class="id-duan">
                                ID:<span><?= Yii::$app->params['listing_prefix_id'] . $product->id; ?></span>
                            </p>
                            <ul class="clearfix list-attr-td">
                                <li><span class="icon-mv"><span class="icon-page-1-copy"></span></span>135m2
                                </li>
                                <li><span class="icon-mv"><span class="icon-bed-search"></span></span>3
                                </li>
                                <li><span class="icon-mv"><span class="icon-bathroom-search-copy-2"></span></span>2
                                </li>
                            </ul>
                        </div>
                        <div class="bottom-feat-box clearfix">
                            <div class="pull-right push-price">
                                <div class="status-duan">
                                    <?php if ($product->end_date < time()): ?>
                                        <div class="wrap-icon status-get-point">
                                            <div><span class="icon icon-inactive-pro"></span>
                                            </div>
                                            <strong><?= Yii::t('statistic', 'Inactive Project') ?></strong>
                                        </div>
                                    <?php else: ?>
                                        <div class="wrap-icon status-get-point">
                                            <div><span class="icon icon-active-pro"></span>
                                            </div>
                                            <strong><?= Yii::t('statistic', 'Active Project') ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($product->end_date > time()) {
                                    $d = $product->end_date - time();
                                    $day_number = floor($d / (60 * 60 * 24)); ?>
                                    <p><?= Yii::t('statistic', 'Expired in the last') ?>
                                        <strong><?= $day_number > 1 ? $day_number . " " . Yii::t('statistic', 'days') : $day_number . " " . Yii::t('statistic', 'day') ?></strong>
                                    </p>
                                <?php } ?>
                                <a href="#nang-cap"
                                   class="btn-nang-cap"><?= Yii::t('statistic', 'Upgrade') ?></a>
                            </div>
                            <?php
                            if (($search = \frontend\models\Tracking::find()->countFinders($product->id)) === null) {
                                $search = 0;
                            }
                            if (($click = \frontend\models\Tracking::find()->countVisitors($product->id)) === null) {
                                $click = 0;
                            }
                            if (($fav = \frontend\models\Tracking::find()->countFavourites($product->id)) === null) {
                                $fav = 0;
                            }
                            ?>
                            <div class="wrap-icon">
                                <span class="icon-mv"><span class="icon-icons-search"></span></span>
                                <strong><?= $search ?></strong><?= Yii::t('statistic', 'Search') ?>
                            </div>
                            <div class="wrap-icon">
                                <span class="icon-mv"><span class="icon-eye-copy"></span></span>
                                <strong><?= $click ?></strong><?= Yii::t('statistic', 'Click') ?>
                            </div>
                            <div class="wrap-icon">
                                <span class="icon-mv"><span class="icon-heart-icon-listing"></span></span>
                                <strong><?= $fav ?></strong><?= Yii::t('statistic', 'Favourite') ?>
                            </div>
                            <div class="wrap-icon">
                                <span class="icon-mv"><span class="icon-share-social"></span></span>
                                <strong>10</strong>Chia sẻ 
                            </div>
                        </div>
                        <a href="#" class="see-detail-listing fs-13 font-600 color-cd-hover"><span class="text-decor">Trang chi tiết</span><span class="icon-mv mgL-10"><span class="icon-angle-right"></span></tspan></a>
                    </div>
                </div>
            </li>
        <?php }
    } ?>
</ul>
<nav class="text-center">
    <?php
    echo LinkPager::widget([
        'pagination' => $pagination
    ]);
    ?>
</nav>
