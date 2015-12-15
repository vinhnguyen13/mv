<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template 3 columns 1 rows
 */
use yii\bootstrap\Html;
use yii\helpers\StringHelper;
$catalog = \vsoft\news\models\CmsCatalog::findOne($cat_id);
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 listgrd8">
    <div class="titlebg">
        <a class="color-title-link" href="<?= \yii\helpers\Url::to(['news/list', 'cat_id' => $cat_id, 'slug' => $catalog->slug]) ?>">
        <h2 class="title">kinh tế vĩ mô</h2>
        </a>
    </div>
</div>
<?php
foreach ($news as $n) {
    ?>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="grd7">
            <?= Html::a("<img src=\"/store/news/show/$n->banner \" alt=\"$n->title\">" , ['news/view', 'id' => $n->id, 'slug' => $n->slug, 'cat_id' => $cat_id, 'cat_slug' => $catalog->slug], ['style' => ['text-decoration' => 'none']]) ?>

            <h3 class="cap rotobobold"> <?= Html::a($n->title, ['news/view', 'id' => $n->id, 'slug' => $n->slug, 'cat_id' => $cat_id, 'cat_slug' => $catalog->slug], ['style' => ['text-decoration' => 'none']]) ?> </h3>

            <p class="textcatbox"><?= StringHelper::truncate($n->brief, 150) ?></p>

        </div>
    </div>
<?php } ?>
