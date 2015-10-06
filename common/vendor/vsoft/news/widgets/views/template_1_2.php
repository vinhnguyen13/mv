<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template Du an noi bat 1 col 2 rows
 */
foreach ($template_1_2 as $k => $s) { ?>
<div>
    <span><?=$k+1 . '/'?><?=$s->title?></span>
</div>
<?php } ?>

