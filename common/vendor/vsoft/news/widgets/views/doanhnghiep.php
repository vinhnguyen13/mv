<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template 1 columns 1 rows
 */
use yii\bootstrap\Html;

?>

<div class="titlebg">
    <h2 class="title">doanh nghiệp</h2>
</div>
<?php
foreach ($news as $k => $n) {
    ?>
    <div class="grd7">
        <?= Html::a("<img src=\"/store/news/show/$n->banner \" alt=\"$n->title\">" , ['view', 'id' => $n->id, 'slug' => $n->slug], ['style' => ['text-decoration' => 'none']]) ?>

        <h3 class="cap rotobobold"> <?= Html::a($n->title, ['view', 'id' => $n->id, 'slug' => $n->slug], ['style' => ['text-decoration' => 'none']]) ?> </h3>

        <p class="textcatbox">
            <?= strlen($n->brief) > 300 ? mb_substr($n->brief, 0, 300) . '...' : $n->brief ?>
        </p>

    </div>
<?php } ?>

