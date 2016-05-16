<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 4/15/2016 1:53 PM
 */

use vsoft\ad\models\AdProduct;
use vsoft\express\components\StringHelper;

$categories = \vsoft\ad\models\AdCategory::getDb()->cache(function(){
    return \vsoft\ad\models\AdCategory::find()->indexBy('id')->asArray(true)->all();
});
$types = AdProduct::getAdTypes();

?>

<div class="title-sidebar"><?=$title?></div>
<ul class="clearfix list-post">
    <?= $this->render('/ad/_partials/list', ['products' => $products]) ?>
</ul>
