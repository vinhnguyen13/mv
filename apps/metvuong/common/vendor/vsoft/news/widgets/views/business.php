<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template 1 columns 1 rows
 */
?>
<div class="col-xs-6">
    <div class="widget-title clearfix"><h2>doanh nghiệp</h2></div>
    <?php
    if (!empty($news)) {
        foreach ($news as $k => $n) { ?>
            <div class="wrap-img">
                <a class="pull-left wrap-img" href="<?=\yii\helpers\Url::to(['news/view', 'id' => $n->id, 'slug' => $n->slug])?>">
                    <img src="/store/news/show/<?= $n->banner ?>" alt="<?= $n->title ?>" style="width: 442px; height: 294px;">
                </a>
            </div>
            <p>
                <a class="color-title-link" href="<?=\yii\helpers\Url::to(['news/view', 'id' => $n->id, 'slug' => $n->slug])?>">
                    <?=strlen($n->title) > 30 ? mb_substr($n->title, 0, 30) . '...' : $n->title?>
                </a>
            </p>
            <p>
                <?= strlen($n->brief) > 200 ? mb_substr($n->brief, 0, 200) : $n->brief ?>
            </p>
        <?php }
    } ?>
</div>

