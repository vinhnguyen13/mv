<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/7/2016 1:19 PM
 */

use frontend\models\User;

$count_data = count($data) > 0 ? count($data) : 0;
$last_key = count($data) - 1;
if($count_data > 0) {
    ?>
    <ul class="clearfix listContact">
    <?php
    foreach ($data as $key => $val) {
        $alias = $val["alias"];
        ?>
        <li class="">
            <?=$alias;?>
        </li>
        <?php
    }?>
    </ul>
<?php } ?>