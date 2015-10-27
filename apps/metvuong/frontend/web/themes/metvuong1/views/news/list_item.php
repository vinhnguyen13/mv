<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/27/2015 8:56 AM
 */
?>

<div class="col-xs-6 col-sm-6 item-list">
    <div class="wrap-img bgcover" style="background-image: url(/store/news/show/<?=$model->banner?>);"><a href="#"></a></div>
    <p><span><em class="fa fa-calendar"></em>27/10/2015</span><a class="color-title-link" href="<?=\yii\helpers\Url::to(['news/view', 'id' => $model->id, 'slug' => $model->slug]) ?>"><?= strlen($model->title) > 30 ? mb_substr($model->title, 0, 30) . '...' : $model->title ?></a></p>
    <p><?= strlen($model->brief) > 120 ? mb_substr($model->brief, 0, 120) . '...' : $model->brief ?></p>
</div>
