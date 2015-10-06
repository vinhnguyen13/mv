<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template 1 columns 8 rows
 */

foreach ($template_1_8 as $k => $s) {
    if ($k === 1) {
        ?>
        <div class="show_content_1">
            <img src="" alt="">

            <div><?=$s->title?></div>
            <div>Content substring a lots of words</div>
        </div>
    <? } else { ?>
        <div class="show_content">
            <img src="" alt="">
            <div><?=$s->title?></div>
            <div>Content substring a little words</div>
        </div>
    <?php }
} ?>
