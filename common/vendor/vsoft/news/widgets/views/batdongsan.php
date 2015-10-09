<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template 2 columns 3 rows
 */
use yii\helpers\Html;

?>

<div class="titlebg">
    <h2 class="title"><?= Html::a('Bất động sản', ['list', 'cat_id' => $cat_id], ['style' => ['text-decoration' => 'none']]) ?></h2>
</div>

<?php
foreach ($news as $k => $n) {
    if ($k === 0) {
        ?>
        <div class="grd7 pdr">
            <?= Html::a("<img src=\"/store/news/show/$n->banner \" alt=\"$n->title\">" , ['view', 'id' => $n->id], ['style' => ['text-decoration' => 'none']]) ?>

            <h3 class="cap rotobobold">
                <?= Html::a($n->title, ['view', 'id' => $n->id], ['style' => ['text-decoration' => 'none']]) ?>
            </h3>
            <p class="textcatbox">
                <?= strlen($n->brief) > 300 ? mb_substr($n->brief, 0, 300) . '...' : $n->brief  ?>
            </p>
        </div>

    <?php } else { ?>
        <div class="grd4">
            <div class="newstbl">
                <?= Html::a("<img src=\"/store/news/show/$n->banner \" alt=\"$n->title\">" , ['view', 'id' => $n->id], ['style' => ['text-decoration' => 'none']]) ?>
            </div>
            <div class="frst pdl">
                <h3 class="title rotobobold"> <?= Html::a($n->title, ['view', 'id' => $n->id], ['style' => ['text-decoration' => 'none']]) ?> </h3>

                <p class="textfrst">
                    <?= strlen($n->brief) > 200 ? mb_substr($n->brief, 0, 200) . '...' : $n->brief ?>
                </p>

            </div>

        </div>
    <?php } ?>

    <?php
} ?>


