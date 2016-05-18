<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 4/1/2016 1:58 PM
 */
use yii\helpers\Url;

?>
<div class="item-sidebar">
    <div class="title-sidebar"><?=Yii::t('project', 'TOP VIEW PROJECT')?></div>
    <ul class="clearfix listing-item">
        <?php foreach($projects as $project) {?>
            <li class="col-xs-12 col-sm-6 col-lg-4">
                <div class="wrap-item-post">
                    <a href="<?= Url::to(["building-project/view", 'slug'=>$project->slug]); ?>" class="pic-intro rippler rippler-default">
                        <img src="<?=$project->logoUrl?>" alt="">
                    </a>
                    <p class="infor-by-up"><?=$project->investment_type?></p>
                    <p class="name-post"><a href="<?= Url::to(["building-project/view", 'slug'=>$project->slug]); ?>"><?=$project->name?> </a></p>
                    <p class="fs-13 font-400"><?=$project->location?></p>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>
