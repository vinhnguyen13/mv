<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template 1 columns 8 rows
 */
?>
<div class="titlebg_blue">
    <h2 class="title">ĐÁNG QUAN TÂM</h2>
</div>
<?php
foreach ($news as $k => $n) {
    if ($k === 0) {
        ?>
        <div class="grd7 mgb">
            <img src="store/news/show/<?=$n->banner?>" alt="<?=$n->title?>">

            <h3 class="cap rotobobold"> <?=$n->title?> </h3>

            <p class="textcatbox">
                <?= $n->brief?>
            </p>

        </div>
    <?php } else { ?>
        <div class="grd4">
            <div class="newstbl">
                <img src="store/news/show/<?=$n->banner?>" alt="<?=$n->title?>">
            </div>
            <div class="frst pdl">
                <h3 class="title rotobobold"> <?=$n->title?> </h3>

                <p class="textfrst">
                    <?= $n->brief?>
                </p>

            </div>
        </div>
    <?php } ?>

    <?php
} ?>
