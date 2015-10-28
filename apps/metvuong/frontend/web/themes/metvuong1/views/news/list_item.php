<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/27/2015 8:56 AM
 */
use yii\helpers\StringHelper;

?>

<div class="col-xs-6 col-sm-6 item-list">
    <div class="wrap-img bgcover" style="background-image: url(/store/news/show/<?=$model->banner?>);"><a href="#"></a></div>
    <p><span><em class="fa fa-calendar"></em><?=date("d/m/Y g:i a",$model->created_at)?></span><a class="color-title-link" href="<?=\yii\helpers\Url::to(['news/view', 'id' => $model->id, 'slug' => $model->slug]) ?>"><?= StringHelper::truncate($model->title, 30) ?></a></p>
    <p><?= StringHelper::truncate($model->brief, 120) ?></p>
</div>
