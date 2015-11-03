<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template 1 columns 8 rows
 */
use yii\bootstrap\Html;
use yii\helpers\StringHelper;
$catalog = \vsoft\news\models\CmsCatalog::findOne($cat_id);
?>
<div class="siderbar widget-dqt clearfix siderbar-style">
    <div class="widget-title clearfix"><h2>đáng quan tâm</h2></div>
    <?php if (!empty($news)) { ?>
    <ul>
        <?php foreach ($news as $k => $n) { ?>
            <li>
                <a class="pull-left wrap-img" href="<?=\yii\helpers\Url::to(['news/view', 'id' => $n->id, 'slug' => $n->slug, 'cat_id' => $catalog->id, 'cat_slug' => $catalog->slug])?>">
                    <img src="/store/news/show/<?= $n->banner ?>" alt="<?= $n->title ?>" style="width: 82px; height: 55px;">
                </a>
                <div>
                    <?= Html::a(StringHelper::truncate($n->title, 30), ['news/view', 'id' => $n->id, 'slug' => $n->slug, 'cat_id' => $catalog->id, 'cat_slug' => $catalog->slug], ['class' => 'color-title-link']) ?>
                    <p> <?= StringHelper::truncate($n->brief, 100) ?> </p>
                </div>
            </li>
        <?php } ?>
    </ul>
    <?php } ?>
</div>
