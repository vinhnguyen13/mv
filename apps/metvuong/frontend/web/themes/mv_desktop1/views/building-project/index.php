<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 1/28/2016 10:15 AM
 */
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>
<div class="title-fixed-wrap">
    <div class="container">
        <div class="infor-duan">
            <div class="title-top"><?=Yii::t('general', 'New Project')?></div>
            <div class="wrap-infor-duan">
                <div class="search-duan">
                    <form id="search-duan-form" action="<?= \yii\helpers\Url::to(['building-project/find'], true) ?>">
                        <input name="project_name" class="project_name" type="text" placeholder="<?=Yii::t('general', 'Find by name...')?>">
                        <button type="submit" id="btn-search-duan"><span class="icon icon-search-small-1"></span></button>
                    </form>
                </div>
                <div class="list-duan">
                    <ul class="clearfix row">
                        <?php if(count($models) > 0) {
                            foreach ($models as $model) { ?>
                                <li class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="wrap-item">
                                        <a href="<?= Url::to(["building/$model->slug"]); ?>" class="pic-intro rippler rippler-default">
                                            <div class="img-show">
                                                <div><img src="<?=$model->logoUrl?>"
                                                          data-original="<?=$model->logoUrl?>"
                                                          style="display: block;"></div>
                                            </div>
                                        </a>

                                        <div class="info-item">
                                            <div class="address-feat">
                                                <p><?= $model->investment_type ?></p>
                                                <a href="<?= Url::to(["building/$model->slug"]); ?>" title="<?= $model->name ?>">
                                                    <strong><?= $model->name ?></strong>
                                                </a>
                                                <p class="address-duan"><?= empty($model->location) ? Yii::t('general', 'Updating') : $model->location ?></p>
                                                <p class="date-post"><?=date('d/m/Y, H:i', $model->created_at)?></p>
                                            </div>
                                            <div class="bottom-feat-box clearfix">
                                                <input type="hidden" value="<?=$model->id?>">
                                                <p><?=\yii\helpers\StringHelper::truncate($model->description, 180)?></p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                            <nav class="text-center">
                                <?php
                                echo LinkPager::widget([
                                    'pagination' => $pagination
                                ]);
                                ?>
                            </nav>
                        <?php } else {?>
                            <div>Không tìm thấy dự án "<b class="found-name"><?=$project_name?></b>"</div>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>