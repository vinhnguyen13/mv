<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 */
use yii\helpers\Html;

?>
<div class="news-bds">
    <div class="widget-title clearfix"><h2>bất động sản</h2></div>
    <?php if (!empty($news)) { ?>
        <div class="row">
            <?php foreach ($news as $k => $n) { ?>
                <div class="col-sm-4">
                    <div class="wrap-img bgcover"
                         style="background-image: url('/store/news/show/<?= $n->banner ?>');"></div>
                    <p>
                        <a class="color-title-link" href="<?= \yii\helpers\Url::toRoute(['view', 'id' => $n->id, 'slug' => $n->slug]) ?>">
                            <?= strlen($n->title) > 30 ? mb_substr($n->title, 0, 30) . '...' : $n->title ?>
                        </a>
                    </p>

                    <p> <?= strlen($n->brief) > 150 ? mb_substr($n->brief, 0, 150) . '...' : $n->brief ?></p>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>

