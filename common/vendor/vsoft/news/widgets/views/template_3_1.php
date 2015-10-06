<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template 3 columns 1 rows
 */

foreach ($template_3_1 as $k => $s) {
    ?>
    <div class="show_content">
        <img src="" alt="">

        <div><?= $k+1 .'/'. $s->title?></div>
        <div><br></div>
    </div>
<?php } ?>
