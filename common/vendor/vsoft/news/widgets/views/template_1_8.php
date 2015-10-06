<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template 1 columns 8 rows
 */

foreach ($template_1_8 as $k => $s) {
    ?>
    <div class="show_content">
        <div><?= $k + 1 . '/' . $s->title ?></div>
        <div><br></div>
    </div>
    <?php
} ?>
