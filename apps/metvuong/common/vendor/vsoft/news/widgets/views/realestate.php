<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 */
use yii\helpers\StringHelper;

?>
<div class="news-bds">
    <div class="widget-title clearfix">
        <h2>
            <a class="color-title-link" href="<?= \yii\helpers\Url::to(['news/list', 'cat_id' => $cat_id]) ?>">
                bất động sản
            </a>
        </h2>
    </div>
    <?php if (!empty($news)) { ?>
        <div class="row">
            <?php foreach ($news as $k => $n) { ?>
                <div class="col-sm-4">
                    <div class="wrap-img bgcover"
                         style="background-image: url('/store/news/show/<?= $n->banner ?>');"></div>
                    <p>
                        <a class="color-title-link"
                           href="<?= \yii\helpers\Url::to(['news/view', 'id' => $n->id, 'slug' => $n->slug]) ?>">
                            <?= StringHelper::truncate($n->title, 30) ?>
                        </a>
                    </p>

                    <p> <?= StringHelper::truncate($n->brief, 120) ?></p>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>

