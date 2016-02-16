<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 1/28/2016 10:15 AM
 */
use yii\helpers\Url;

?>



<div class="infor-duan">
    <h1>THÔNG TIN DỰ ÁN</h1>
    <p class="intro-duan">Mét Vuông mang lại cho bạn những thông tin dự án mới và chi tiết nhất</p>
    <?php
    if(!empty($models) && count($models) > 0 ) {
        foreach ($models as $model):
            $image = '';
            if ($gallery = explode(',', $model->gallery)) {
                $image = $gallery[0];
            }
            ?>
            <div class="item-duan">
                <div class="intro-img">
                    <div class="wrap-img"><img src="<?= Url::to('/store/building-project-images/' . $image) ?>" alt="<?=$model->name?>"/>
                    </div>
                    <p class="title-duan"><?= !empty($model->categories[0]->name) ? ucfirst($model->categories[0]->name) : "Chung cư căn hộ cao cấp" ?></p>

                    <p class="name-duan"><?= mb_strtoupper($model->name) ?></p>

                    <p class="address-listing"><?= $model->location ?></p>
                </div>
                <div class="short-txt">
                    <p style="text-align: justify;"><?=strlen($model->description) > 0 ? mb_substr($model->description, 0, 200)."..." : "Thông tin chi tiết về {$model->name}..." ?></p>

                    <div class="text-right see-more-listing"><a href="<?= Url::to(["building/$model->slug"]); ?>" title="<?=$model->name?>">Xem thêm</a></div>
                </div>
            </div>
        <?php endforeach;
    } else {?>
        <p class="intro-duan"><b>Hiện tại chưa có dự án mới. <br>Cảm ơn bạn đã quan tâm.</b></p>
    <?php }?>
</div>