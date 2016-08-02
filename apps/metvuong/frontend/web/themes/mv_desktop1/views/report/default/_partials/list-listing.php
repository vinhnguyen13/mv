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
        $user_id = $val["user_id"];
        $user = Yii::$app->db->cache(function() use($user_id){
            return User::findIdentity($user_id);
        });
        $username = $user->username;
        $email = empty($user->profile->public_email) ? $user->email : $user->profile->public_email;
        $avatar = $user->profile->getAvatarUrl();
        ?>
        <li class="">
            <a href="<?=\yii\helpers\Url::to(['member/profile','username' => $username], true)?>" title="<?=$username?>">
                <img src="<?=$avatar ?>" alt="<?=$username?>"><?=$username?>
            </a>
        </li>
        <?php
    }
} ?>