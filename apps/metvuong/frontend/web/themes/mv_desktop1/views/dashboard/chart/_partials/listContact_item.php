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
        $_id = $view=='saved' ? $val['saved_at'] : $val['_id']->{'$id'};
        ?>
        <li class="<?=$_id?>">
            <a href="#popup-user-inter">
                <img src="<?=$avatar ?>" alt="<?=$username?>"><?=$username?>
            </a>

            <div class="crt-item">
                <a href="#" class="btn-email-item mgR-15 tooltip-show" data-placement="bottom" title="" data-target="#popup_email" data-type="contact" data-toggle="modal" data-email="<?=$email?>" data-original-title="Send email">
                    <span class="icon-mv fs-16"><span class="icon-mail-profile"></span></span>
                </a>
                <a href="#" class="chat-now tooltip-show" data-chat-user="<?=$username?>" data-placement="bottom" title="" data-original-title="Send message">
                    <span class="icon-mv fs-18"><span class="icon-bubbles-icon"></span></span>
                </a>
            </div>
        </li>
        <?php
    }
} ?>