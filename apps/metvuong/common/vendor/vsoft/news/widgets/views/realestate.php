<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 */
use yii\helpers\StringHelper;

$catalog = \vsoft\news\models\CmsCatalog::findOne($cat_id);

?>
<div class="news-bds">
    <a class="color-title-link" href="<?= \yii\helpers\Url::to(['news/list', 'cat_id' => $cat_id, 'slug' => $catalog->slug]) ?>">
        <div class="widget-title clearfix">
            <h2>bất động sản</h2>
        </div>
    </a>
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

