<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 4/1/2016 1:56 PM
 */
use yii\helpers\Url;

?>

<div class="item-sidebar">
    <div class="title-sidebar"><?=Yii::t('project', 'HOT PROJECT')?></div>
    <ul class="clearfix list-post">
        <?php foreach($projects as $project) {?>
        <li>
            <div class="wrap-item-post">
                <a href="<?= Url::to(["building-project/view", 'slug'=>$project->slug]); ?>" class="rippler rippler-default">
                    <div class="img-show"><div><img src="<?=$project->logoUrl?>" alt=""></div></div>
                </a>
                <p class="infor-by-up"><?=Yii::t('project', $project->investment_type)?></p>
                <p class="name-post"><a href="<?= Url::to(["building-project/view", 'slug'=>$project->slug]); ?>"><?=$project->name?> </a></p>
                <p class="fs-13 font-400"><?=$project->location?></p>
            </div>
        </li>
        <?php } ?>
    </ul>
</div>
