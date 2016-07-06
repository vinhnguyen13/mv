<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 4/8/2016 11:26 AM
 */
use yii\helpers\Url;
use yii\widgets\LinkPager;
use vsoft\ad\models\AdProduct;

$now = time();

$count_product = count($products);
    if($count_product > 0) {
        $categories = \vsoft\ad\models\AdCategory::find()->indexBy('id')->asArray( true )->all();
        $types = \vsoft\ad\models\AdProduct::getAdTypes ();
        foreach ($products as $product) {
            if (($search = \frontend\models\Tracking::find()->countFinders($product->id)) === null) {
                $search = 0;
            }
            if (($click = \frontend\models\Tracking::find()->countVisitors($product->id)) === null) {
                $click = 0;
            }
            if (($fav = \frontend\models\Tracking::find()->countFavourites($product->id)) === null) {
                $fav = 0;
            }
            if (($share = \frontend\models\Tracking::find()->countShares($product->id)) === null) {
                $share = 0;
            }
            $thumb = $product->representImage;
            if(strpos($thumb, "default")){
                if($product->projectBuilding){
                    $thumb = $product->projectBuilding->logoUrl;
                }
            }

            $adProductAdditionInfo = $product->adProductAdditionInfo;
            $room_no = empty($adProductAdditionInfo) ? 0 : $adProductAdditionInfo->room_no;
            $toilet_no = empty($adProductAdditionInfo) ? 0 : $adProductAdditionInfo->toilet_no;
            ?>
            <li id="p-<?= $product->id ?>" class="col-xs-12 col-md-6 col-sm-6">
                <div class="item p<?=$product->id?> clearfix">
                    <div class="wrap-img-list">
                        <a class="pic-intro" href="<?= Url::to(['/dashboard/statistics', 'id' => $product->id]) ?>" title="<?=Yii::t('statistic', 'View statistic detail')?>">
                            <img src="<?=$thumb ?>" alt="<?=Yii::t('statistic', 'View statistic detail')?>"></a>
                        <a href="<?= Url::to(['/ad/update', 'id' => $product->id]) ?>" class="edit-duan">
                            <span class="icon-mv"><span class="icon-edit-copy-4"></span></span>
                        </a>
                    </div>
                    <div class="intro-detail">
                        <div class="address-feat clearfix">
                            <div class="clearfix mgB-5">
                                <p class="date-post"><span
                                        class="font-600"><?= Yii::t('statistic', 'Date of posting') ?>
                                        :</span> <?= date("d/m/Y", $product->created_at) ?></p>
                            </div>
                            <?php if ($product->projectBuilding){ ?>
                                <p class="loca-duan"><a href="<?= Url::to(['/dashboard/statistics', 'id' => $product->id]) ?>"><?= $product->projectBuilding->name ?></a></p>
                            <?php } else { ?>
                            <p class="loca-duan"><a href="<?= Url::to(['/dashboard/statistics', 'id' => $product->id]) ?>" title="<?= $product->address ?>"><?= $product->address ?></a></p>
                            <?php } ?>
                            <p class="fs-13 text-cappi"><span><?= ucfirst(Yii::t('ad', $categories[$product->category_id]['name'])) ?> <?= $types[$product->type] ?></span></p>
                            <p class="id-duan">
                                ID: <span><?= Yii::$app->params['listing_prefix_id'] . $product->id; ?></span>
                            </p>
                            <a href="<?=$product->urlDetail(true)?>" class="see-detail-listing fs-13 font-600 color-cd-hover pull-right"><span class="text-decor"><?=Yii::t('statistic', 'Go detail page')?></span><span class="icon-mv mgL-10 fs-17"><span class="icon-angle-right"></span></span></a>
                            <?php if(empty($product->area) && empty($room_no) && empty($toilet_no)){ ?>
                                
                            <?php } else {
                                ?>
                                <ul class="clearfix list-attr-td">
                                <?php
                                echo $product->area ? '<li> <span class="icon-mv"><span class="icon-page-1-copy"></span></span>' . $product->area . 'm2 </li>' : '';
                                echo $room_no ? '<li> <span class="icon-mv"><span class="icon-bed-search"></span></span>' . $room_no . ' </li>' : '';
                                echo $toilet_no ? '<li> <span class="icon-mv"><span class="icon-icon-bathroom"></span></span> ' . $toilet_no . ' </li>' : '';
                                ?>
                                </ul>
                                <?php
                            } ?>
                        </div>
                        <div class="bottom-feat-box clearfix">
                            <div class="pull-right push-price">
                            	<?php if($product->status == AdProduct::STATUS_PENDING): ?>
                            	<div class="status-duan" style="opacity: 0.5">
                                    <div class="wrap-icon status-get-point">
										<div class="mgR-5"><span class="icon icon-inactive-pro"></span></div>
                                        <strong><?= Yii::t('statistic', 'Chưa được đăng') ?></strong>
                                    </div>
                                </div>
                                <a href="#"  data-toggle="modal" data-target="#update-status" data-product="<?=$product->id;?>" class="btn-nang-cap mgL-10 btn-active"><?= Yii::t('statistic', 'Đăng tin') ?></a>
                            	<?php else: ?>
                            		<?php if ($product->is_expired): ?>
                            		<div class="status-duan" style="opacity: 0.7">
	                                    <div class="wrap-icon status-get-point">
											<div class="mgR-5"><span class="icon icon-inactive-pro"></span></div>
	                                        <strong><?= Yii::t('statistic', 'Tin đã hết hạn') ?></strong>
	                                    </div>
	                                </div>
	                                <a href="#"  data-toggle="modal" data-target="#update-expired" data-product="<?=$product->id;?>" class="btn-nang-cap mgL-10 btn-expired"><?= Yii::t('statistic', 'Gia hạn tin đăng') ?></a>
                            		<div class="clearfix"></div>
	                                <a href="<?= Url::to(['/dashboard/statistics', 'id' => $product->id]) ?>" class="see-detail-listing fs-13 font-600 color-cd-hover mgT-5"><span class="text-decor"><?=Yii::t('statistic','Xem thống kê')?></span><span class="icon-mv mgL-10"><span class="icon-angle-right"></span></span></a>
	                            	<?php else: ?>
                            		<div class="status-duan">
	                                    <div class="wrap-icon status-get-point">
                                            <div class="mgR-5"><span class="icon icon-active-pro"></span>
                                            </div>
                                            <strong><?= Yii::t('statistic', 'Active Project') ?></strong>
                                        </div>
                                        <?php // $day_number = !empty($product->expired && $product->expired > 0) ? $product->expired : 0; ?>
                                        <p class="expired"><?= Yii::t('statistic', 'Exipire on') ?>
                                            <strong><?= date("d-m-Y", $product->end_date) ?></strong>
                                        </p>
                                        <?php if($product->boost_time > $now): ?>
                                        <p class="expired"><?= Yii::t('statistic', 'Được boost đến') ?>
                                            <strong><?= date("d-m-Y", $product->boost_time) ?></strong>
                                        </p>
                                        <?php endif; ?>
	                                </div>
	                                <a href="#"  data-toggle="modal" data-target="#update-boost" data-product="<?=$product->id;?>" class="btn-nang-cap mgL-10 btn-boost"><?= Yii::t('statistic', 'Up') ?></a>
	                                <div class="clearfix"></div>
	                                <a href="<?= Url::to(['/dashboard/statistics', 'id' => $product->id]) ?>" class="see-detail-listing fs-13 font-600 color-cd-hover mgT-5"><span class="text-decor"><?=Yii::t('statistic','Xem thống kê')?></span><span class="icon-mv mgL-10"><span class="icon-angle-right"></span></span></a>
	                            	<?php endif; ?>
                            	<?php endif; ?>
                            </div>
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
                                <strong><?= $share ?></strong><?=Yii::t('statistic','Share')?>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        <?php }?>
    <?php } ?>


