<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template 2 columns 3 rows
 */
echo '<br><br>';
foreach ($template_2_3 as $k => $s) {
    if ($k === 1) {
        ?>
        <div class="show_content_1">
            <div><?= $s->title ?></div>
            <div>Content substring a lots of words</div>
        </div>
    <? } else { ?>
        <div class="show_content">
            <div><?= $s->title ?></div>
            <div>Content substring a little words</div>
        </div>
    <?php }
} ?>
