<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template 1 columns 8 rows
 */
use yii\bootstrap\Html;

?>
<div class="titlebg_blue">
    <h2 class="title"><?= $cat_id != 0 ? 'ĐÁNG QUAN TÂM' : 'Tin nổi bật' ?></h2>
</div>
<?php
foreach ($news as $k => $n) {
    if ($k === 0) {
        ?>
        <div class="grd7 mgb">
            <?= Html::a("<img src=\"/store/news/show/$n->banner \" alt=\"$n->title\">" , ['view', 'id' => $n->id], ['style' => ['text-decoration' => 'none']]) ?>

            <h3 class="cap rotobobold"> <?= Html::a($n->title, ['view', 'id' => $n->id], ['style' => ['text-decoration' => 'none']]) ?> </h3>

            <p class="textcatbox">
                <?= strlen($n->brief) > 150 ? mb_substr($n->brief, 0, 150) . '...' : $n->brief ?>
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
                    <?= strlen($n->brief) > 100 ? mb_substr($n->brief, 0, 100) . '...' : $n->brief ?>
                </p>

            </div>
        </div>
    <?php } ?>

    <?php
} ?>
