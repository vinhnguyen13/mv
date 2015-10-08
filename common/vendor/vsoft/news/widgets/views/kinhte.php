<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template 3 columns 1 rows
 */
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
            <img src="store/news/show/<?= $n->banner ?>" alt="<?= $n->title ?>">

            <h3 class="cap rotobobold"> <?= $n->title ?> </h3>

            <p class="textcatbox"><?= $n->brief?></p>

        </div>
    </div>
<?php } ?>
