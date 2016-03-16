<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 3/9/2016 5:24 PM
 */
use yii\helpers\Url;

?>

<?php if(count($models) > 0) {
    foreach ($models as $model) {
        $image = '/themes/metvuong2/resources/images/default-ads.jpg';
        $gallery = array();
        if($model->gallery)
            $gallery = explode(',', $model->gallery);
        if (count($gallery) > 0) {
            $imageUrl = Yii::getAlias('@store')."/building-project-images/". $gallery[0];
            if(file_exists($imageUrl)){
                $image = Url::to('/store/building-project-images/' . $gallery[0]);
            }
        }
        ?>
        <div class="wrap-item">
            <div class="item">
                <a href="<?= Url::to(["building/$model->slug"]); ?>" class="pic-intro rippler rippler-default">
                    <div class="img-show">
                        <div><img src="<?=$image?>"
                                  data-original="<?=$image?>"
                                  style="display: block;"></div>
                    </div>
                </a>

                <div class="info-item">
                    <div class="address-feat">
                        <p><?= !empty($model->categories[0]->name) ? \vsoft\ad\models\AdBuildingProject::mb_ucfirst($model->categories[0]->name,'UTF-8') : "Chung cư cao cấp" ?></p>
                        <a href="<?= Url::to(["building/$model->slug"]); ?>"><strong><?= mb_strtoupper($model->name) ?></strong></a>
                        <span class="icon address-icon"></span><?= empty($model->location) ? "Đang cập nhật" : $model->location ?>
                    </div>
                    <div class="bottom-feat-box clearfix">
                        <p><?=\yii\helpers\StringHelper::truncate($model->description, 180)?></p>
                        <a href="<?= Url::to(["building/$model->slug"]); ?>" class="pull-right">Chi tiết</a>
                    </div>
                </div>
            </div>
        </div>
    <?php }
} else {?>
    <div>Không tìm thấy dự án <span class="found-name"></span></div>
<?php } ?>
