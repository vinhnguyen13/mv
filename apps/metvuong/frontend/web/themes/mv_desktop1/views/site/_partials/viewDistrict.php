<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/5/2016 1:42 PM
 */


use vsoft\ad\models\AdDistrict;
use yii\helpers\Url;
?>

<div class=" col-md-2  col-sm-4  col-xs-12">
    <div class="keyword_col">
        <h2><a href="<?=Url::to(['ad/index1', 'city_id'=>1])?>" title="Nhà đất tại Hồ Chí Minh">Nhà Đất Hồ Chí Minh</a></h2>
        <ul class="keyword_child_location clearfix">
            <?php
            $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListByCity(1); });
            if(count($districts) > 0){
                foreach($districts as $district) { ?>
                    <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>$district['id']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                <?php }
            } ?>
        </ul>
    </div>
</div>
<div class=" col-md-2  col-sm-4  col-xs-12">
    <div class="keyword_col">
        <h2><a href="<?=Url::to(['ad/index1', 'city_id'=>2])?>" title="Nhà đất tại Hà Nội">Nhà Đất Hà Nội</a></h2>
        <ul class="keyword_child_location clearfix">
            <?php
            $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListByCity(2); });
            if(count($districts) > 0){
                foreach($districts as $district) { ?>
                    <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>$district['id']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                <?php }
            } ?>
        </ul>
    </div>
</div>
<div class=" col-md-2  col-sm-4  col-xs-12">
    <div class="keyword_col">
        <h2><a href="<?=Url::to(['ad/index1', 'city_id'=>3])?>" title="Nhà đất tại Bình Dương">Nhà Đất Bình Dương</a></h2>
        <ul class="keyword_child_location clearfix">
            <?php
            $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListByCity(3); });
            if(count($districts) > 0){
                foreach($districts as $district) { ?>
                    <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>$district['id']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                <?php }
            } ?>
        </ul>
    </div>
    <div class="keyword_col">
        <h2><a href="<?=Url::to(['ad/index1', 'city_id'=>5])?>" title="Nhà đất tại Hải Phòng">Nhà Đất Hải Phòng</a></h2>
        <ul class="keyword_child_location clearfix">
            <?php
            $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListByCity(5); });
            if(count($districts) > 0){
                foreach($districts as $district) { ?>
                    <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>$district['id']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                <?php }
            } ?>
        </ul>
    </div>
</div>
<div class=" col-md-2  col-sm-4  col-xs-12">
    <div class="keyword_col">
        <h2><a href="<?=Url::to(['ad/index1', 'city_id'=>4])?>" title="Nhà đất tại Đà Nẵng">Nhà Đất Đà Nẵng</a></h2>
        <ul class="keyword_child_location clearfix">
            <?php
            $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListByCity(4); });
            if(count($districts) > 0){
                foreach($districts as $district) { ?>
                    <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>$district['id']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                <?php }
            } ?>
        </ul>
    </div>
    <div class="keyword_col">
        <h2><a href="<?=Url::to(['ad/index1', 'city_id'=>18])?>" title="Nhà đất tại Cần Thơ">Nhà Đất Cần Thơ</a></h2>
        <ul class="keyword_child_location clearfix">
            <?php
            $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListByCity(18); });
            if(count($districts) > 0){
                foreach($districts as $district) { ?>
                    <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>$district['id']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                <?php }
            } ?>
        </ul>
    </div>
</div>
<div class=" col-md-2  col-sm-4  col-xs-12">
    <div class="keyword_col">
        <h2><a href="<?=Url::to(['ad/index1', 'city_id'=>6])?>" title="Nhà đất tại Long An">Nhà Đất Long An</a></h2>
        <ul class="keyword_child_location clearfix">
            <?php
            $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListByCity(6); });
            if(count($districts) > 0){
                foreach($districts as $district) { ?>
                    <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>$district['id']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                <?php }
            } ?>
        </ul>
    </div>
    <div class="keyword_col">
        <h2><a href="<?=Url::to(['ad/index1', 'city_id'=>7])?>" title="Nhà đất tại Bà Rịa Vũng Tàu">Nhà Đất Bà Rịa Vũng Tàu</a></h2>
        <ul class="keyword_child_location clearfix">
            <?php
            $districts = Yii::$app->db->cache(function(){ return AdDistrict::getListByCity(7); });
            if(count($districts) > 0){
                foreach($districts as $district) { ?>
                    <li class="item"><h3><a class="link" href="<?=Url::to(['ad/index1', 'district_id'=>$district['id']])?>" title="Nhà đất tại <?=$district['name']?>"><?=$district['name']?></a></h3></li>
                <?php }
            } ?>
        </ul>
    </div>
</div>