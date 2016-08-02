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
    foreach ($data as $key => $val) {
        $product = Yii::$app->db->cache(function() use($val){
            return \vsoft\ad\models\AdProduct::findOne($val["id"]);
        });
        $urlImage = $product->representImage;
        $address = $product->getAddress();
        ?>
        <li class="">
            <a href="<?=$product->urlDetail()?>" title="<?=$address?>">
                <img src="<?=$urlImage ?>" alt="<?=$address?>"><?=$address?>
            </a>
        </li>
        <?php
    }
} ?>