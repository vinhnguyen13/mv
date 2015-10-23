<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template 3 columns 1 rows
 */
use yii\bootstrap\Html;

?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 listgrd8">
    <div class="titlebg">
        <h2 class="title">kinh tê vĩ mô</h2>
    </div>
</div>
<?php
foreach ($news as $n) {
    ?>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="grd7">
            <?= Html::a("<img src=\"/store/news/show/$n->banner \" alt=\"$n->title\">" , ['view', 'id' => $n->id, 'slug' => $n->slug], ['style' => ['text-decoration' => 'none']]) ?>

            <h3 class="cap rotobobold"> <?= Html::a($n->title, ['view', 'id' => $n->id, 'slug' => $n->slug], ['style' => ['text-decoration' => 'none']]) ?> </h3>

            <p class="textcatbox"><?= strlen($n->brief) > 150 ? mb_substr($n->brief, 0, 150) . '...' : $n->brief ?></p>

        </div>
    </div>
<?php } ?>
