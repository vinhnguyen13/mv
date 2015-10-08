<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template 1 columns 1 rows
 */
?>

<div class="titlebg">
    <h2 class="title">doanh nghiệp</h2>
</div>
<?php
foreach ($news as $k => $n) {
    ?>
    <div class="grd7">
        <img src="store/news/show/<?= $n->banner ?>" alt="<?= $n->title ?>">

        <h3 class="cap rotobobold"><?= $n->title ?> </h3>

        <p class="textcatbox">
            <?= $n->brief?>
        </p>

    </div>
<?php } ?>

