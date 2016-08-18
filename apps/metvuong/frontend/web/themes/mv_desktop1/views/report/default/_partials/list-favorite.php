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
        $user = Yii::$app->db->cache(function() use($val){
            return User::findIdentity($val["user_id"]);
        });
        $product = Yii::$app->db->cache(function() use($val){
            return \vsoft\ad\models\AdProduct::findOne($val["product_id"]);
        });
        $username = $user->username;
        $email = empty($user->profile->public_email) ? $user->email : $user->profile->public_email;
        $avatar = $user->profile->getAvatarUrl();
        $address = $product->getAddress();

        $message = Yii::t('activity', '{owner} favorite {product}',
            [
                'owner'=> \yii\helpers\Html::a($user->profile->getDisplayName(), \yii\helpers\Url::to(['member/profile','username' => $username], true)),
                'product'=> \yii\helpers\Html::a($address, $product->urlDetail())
            ]
        );
        ?>
        <li class="">
            <?=$message;?>
        </li>
        <?php
    }?>
    </ul>
<?php } ?>