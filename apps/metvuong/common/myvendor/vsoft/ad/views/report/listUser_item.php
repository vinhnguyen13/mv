<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/7/2016 1:19 PM
 */

use yii\helpers\Url;

if(count($list_user) > 0) {
    foreach ($list_user as $user) {
        $username = $user['username'];
        $avatar = Url::to( '/images/default-avatar.jpg');
        if($user['avatar']) {
            $pathinfo = pathinfo($user['avatar']);
            $filePath = Yii::getAlias('@store').'/avatar/' . $pathinfo['filename'] . '.thumb.' . $pathinfo['extension'];
            if(file_exists($filePath)){
                $avatar = Url::to('/store/avatar/' . $pathinfo['filename'] . '.thumb.' . $pathinfo['extension']);
            }
        }
        ?>
        <li class="<?=$user['report_at']?>">
            <a href="<?= Yii::$app->urlManager->hostInfo. "/". $username?>" title="<?=$username?>">
                <?=$username?>
            </a>
            <span><?php
                $type = (int)$user['type'];
                if($type == -1){
                    echo $user['description'];
                } else {
                    echo \vsoft\ad\models\ReportType::getReportName($type);
                }
                ?>
            </span>
            <span> <?=date('d M Y h:i a', $user['report_at'])?></span>
        </li>
        <?php
    }
} ?>