<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 4/1/2016 1:58 PM
 */
use yii\helpers\Url;

Yii::t('project','Cao ốc văn phòng');
Yii::t('project','Dự án khác');
Yii::t('project','Khu căn hộ');
Yii::t('project','Khu công nghiệp');
Yii::t('project','Khu dân cư');
Yii::t('project','Khu du lịch- nghỉ dưỡng');
Yii::t('project','Khu phức hợp');
Yii::t('project','Khu thương mại dịch vụ');
Yii::t('project','Khu đô thị mới');
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
                    <p class="infor-by-up"><?=Yii::t('project', $project->investment_type)?></p>
                    <p class="name-post"><a href="<?= Url::to(["building-project/view", 'slug'=>$project->slug]); ?>"><?=$project->name?> </a></p>
                    <p class="fs-13 font-400"><?=$project->location?></p>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>
