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
    ?>
    <div class="show_content">
        <div><?= $k + 1 . '/' . $s->title ?></div>
        <div><?php // strlen($s->content) > 50 ? substr($s->content, 0, 50) . '...' : $s->content ?></div>
    </div>
    <?php
} ?>
