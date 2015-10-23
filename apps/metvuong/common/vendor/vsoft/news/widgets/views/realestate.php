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
        <div class="col-xs-4">
            <div class="wrap-img">
                <a class="pull-left wrap-img" href="<?=\yii\helpers\Url::toRoute(['view', 'id' => $n->id, 'slug' => $n->slug])?>">
                    <img src="/store/news/show/<?= $n->banner ?>" alt="<?= $n->title ?>">
                </a>
            </div>
            <p>
                <?= Html::a(strlen($n->title) > 30 ? mb_substr($n->title, 0, 30) . '...' : $n->title, ['view', 'id' => $n->id, 'slug' => $n->slug], ['class' => 'color-title-link']) ?>
            </p>
            <p> <?= strlen($n->brief) > 300 ? mb_substr($n->brief, 0, 300) : $n->brief ?> </p>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
</div>

