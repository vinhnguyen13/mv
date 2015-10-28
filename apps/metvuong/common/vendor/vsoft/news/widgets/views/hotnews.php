<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template Du an noi bat 1 col 2 rows
 * @var $news from NewsWidget parameter
 */
use yii\bootstrap\Html;
use yii\helpers\StringHelper;

?>
<div class="siderbar widget-tinmoi clearfix siderbar-style">
    <div class="widget-title clearfix"><h2>Tin mới</h2></div>
    <?php if (!empty($news)) { ?>
    <ul>
        <?php foreach ($news as $k => $n) { ?>
            <li>
                <a class="pull-left wrap-img" href="<?=\yii\helpers\Url::to(['news/view', 'id' => $n->id, 'slug' => $n->slug])?>">
                    <img src="/store/news/show/<?= $n->banner ?>" alt="<?= $n->title ?>" style="width: 82px; height: 55px;">
                </a>
                <div>
                    <?= Html::a(StringHelper::truncate($n->title, 30), ['view', 'id' => $n->id, 'slug' => $n->slug], ['class' => 'color-title-link']) ?>
                    <p> <?= StringHelper::truncate($n->brief, 100)?> </p>
                </div>
            </li>
        <?php } ?>
    </ul>
    <?php } ?>
</div>



