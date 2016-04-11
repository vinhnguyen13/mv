<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 4/8/2016 11:26 AM
 */
use yii\helpers\Url;
use yii\widgets\LinkPager;

$count_product = count($products);
?>

    <?php
    if($count_product > 0) {
        $categories = \vsoft\ad\models\AdCategory::find()->indexBy('id')->asArray( true )->all();
        $types = \vsoft\ad\models\AdProduct::getAdTypes ();
        foreach ($products as $product) { ?>
            <li>
                <div class="item">
                    <div class="img-show">
                        <div>
                            <a href="<?= Url::to(['/dashboard/statistics', 'id' => $product->id]) ?>" title="<?=Yii::t('statistic', 'View statistic detail')?>"><img
                                    src="<?= $product->representImage ?>" alt="<?=Yii::t('statistic', 'View statistic detail')?>"></a>
                        </div>
                        <a href="<?= Url::to(['/ad/update', 'id' => $product->id]) ?>" class="edit-duan">
                            <span class="icon-mv"><span class="icon-edit-copy-4"></span></span>
                        </a>
                    </div>
                    <div class="intro-detail">
                        <div class="address-feat clearfix">
                            <p class="pull-right text-lowercase"><span><?= ucfirst($categories[$product->category_id]['name']) ?> <?= $types[$product->type] ?></span></p>
                            <p class="date-post"><span
                                    class="font-600"><?= Yii::t('statistic', 'Date of posting') ?>
                                    :</span> <?= date("d/m/Y", $product->created_at) ?></p>

                            <?php if ($product->projectBuilding): ?>
                                <p class="name-duan"><?= $product->projectBuilding->name ?></p>
                            <?php endif; ?>
                            <p class="loca-duan"><a href="<?=$product->urlDetail(true)?>"><?= $product->address ?></a></p>

                            <p class="id-duan">
                                ID:<span><?= Yii::$app->params['listing_prefix_id'] . $product->id; ?></span>
                            </p>
                            <ul class="clearfix list-attr-td">
                                <?php if(empty($product->area) && empty($product->adProductAdditionInfo->room_no) && empty($product->adProductAdditionInfo->toilet_no)){ ?>
                                    <li><span><?=Yii::t('listing','updating')?></span></li>
                                <?php } else {
                                    echo $product->area ? '<li> <span class="icon-mv"><span class="icon-page-1-copy"></span></span>' . $product->area . 'm2 </li>' : '';
                                    echo $product->adProductAdditionInfo->room_no ? '<li> <span class="icon-mv"><span class="icon-bed-search"></span></span>' . $product->adProductAdditionInfo->room_no . ' </li>' : '';
                                    echo $product->adProductAdditionInfo->toilet_no ? '<li> <span class="icon-mv"><span class="icon-bathroom-search-copy-2"></span></span> ' . $product->adProductAdditionInfo->toilet_no . ' </li>' : '';
                                } ?>
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
                                <div class="clearfix"></div>
                                <a href="<?=$product->urlDetail(true)?>" class="see-detail-listing fs-13 font-600 color-cd-hover mgT-5"><span class="text-decor"><?=Yii::t('statistic', 'Go detail page')?></span><span class="icon-mv mgL-10"><span class="icon-angle-right"></span></span></a>
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
                    </div>
                </div>
            </li>
        <?php }?>
        <a href="#" data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> $type,'last_id' => $last_id])?>" class="load_listing pull-right"><?=Yii::t('listing','More listing')?>...</a>
    <?php } else { ?>
       <span class="pull-right">That's all listing.</span>";
    <?php } ?>
<script>
    $('#list-all a.load_listing').click(function(){
        $('body').loading();
        $(this).remove();
        $.ajax({
            type: "get",
            dataType: 'html',
            url: $(this).data('url'),
            success: function (data) {
                $('#list-all .list-item').append(data);
                $('body').loading({done: true});
            }
        });
    });
    $('#list-sell a.load_listing').click(function(){
        $('body').loading();
        $(this).remove();
        $.ajax({
            type: "get",
            dataType: 'html',
            url: $(this).data('url'),
            success: function (data) {
                $('#list-sell .list-item').append(data);
                $('body').loading({done: true});
            }
        });
    });
    $('#list-rent a.load_listing').click(function(){
        $('body').loading();
        $(this).remove();
        $.ajax({
            type: "get",
            dataType: 'html',
            url: $(this).data('url'),
            success: function (data) {
                $('#list-rent .list-item').append(data);
                $('body').loading({done: true});
            }
        });
    });
</script>

