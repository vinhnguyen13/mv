<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 1/28/2016 11:12 AM
 */
use yii\helpers\Url;

$facilityListId = explode(",", $model->facilities);
$facilities = \vsoft\ad\models\AdFacility::find()->where(['id' => $facilityListId])->all();
$count_facilities = count($facilities);
?>

<?= $this->render('_partials/search-form') ?>

<div class="detail-listing">
    <div class="gallery-detail swiper-container">
        <div class="swiper-wrapper">
            <?php
            $gallery = explode(',', $model->gallery);
            if(!empty($gallery[0])) {
                foreach ($gallery as $image) {
                    ?>
                    <div class="swiper-slide">
                        <div class="bgcover"
                             style="background-image:url(<?= Url::to('/store/building-project-images/' . $image) ?>)"></div>
                        <ul class="clearfix">
                            <li><a href="#diadiem" class=""><span class="icon icon-loca"></span></a></li>
                            <li><a href="#" class=""><span class="icon icon-fave"></span></a></li>
                        </ul>
                    </div>
                <?php }
            } else { ?>
                <div class="swiper-slide">
                    <div class="bgcover"
                         style="background-image:url(<?= Yii::$app->view->theme->baseUrl."/resources/images/img-duan-demo.jpg" ?>)"></div>
                    <ul class="clearfix">
                        <li><a href="#diadiem" class="icon icon-loca"></a></li>
                        <li><a href="#" class="icon icon-fave"></a></li>
                    </ul>
                </div>
            <?php }  ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="infor-listing infor-duan">
        <p class="title-duan"><?= !empty($model->categories[0]->name) ? ucfirst($model->categories[0]->name) : "Chung cư căn hộ cao cấp" ?></p>
        <p class="name-duan"><?= strtoupper($model->name)?></p>
        <p class="address-listing"><?=$model->location?></p>
    </div>
    <div class="infor-listing infor-unit">
        <?php if(!empty($model->investors[0])){?> <p><span>CHỦ ĐẦU TƯ:</span> <?= $model->investors[0]->name  ?></p> <?php }?>
        <?php if(!empty($model->architects[0])){?> <p><span>KIẾN TRÚC SƯ:</span> <?=$model->architects[0]->name ?></p> <?php }?>
        <?php if(!empty($model->contractors[0])){?> <p><span>NHÀ THẦU THI CÔNG:</span> <?=$model->contractors[0]->name ?></p> <?php }?>
    </div>
    <div class="attr-detail">
        <div class="title-attr-listing">Diễn tả chi tiết</div>
        <p style="text-align: justify;"><?=!empty($model->description) ? $model->description : "Thông tin sẽ được cập nhật."?></p>
        
    </div>
    <div class="attr-detail">
        <div class="title-attr-listing">Thông tin chi tiết</div>
        <?php if(!empty($model->facade_width)){?> <p><span>Mặt tiền:</span> <?= $model->facade_width."m" ?></p> <?php }?>
        <?php if(!empty($model->floor_no)){?> <p><span>Tầng cao:</span> <?= $model->floor_no ?></p> <?php }?>
        <?php if(!empty($model->lift)){?> <p><span>Thang máy:</span> <?= $model->lift ?></p> <?php }?>
        <?php if(!empty($model->start_date)){?> <p><span>Ngày khởi công:</span> <?= Yii::$app->formatter->asDate($model->start_date, 'dd-MM-yyyy')?></p> <?php }?>
        <?php if(!empty($model->start_time)){?> <p><span>Bắt đầu dự án:</span> <?=$model->start_time?></p> <?php }?>
        <?php if(!empty($model->estimate_finished)){?> <p><span>Kết thúc dự án:</span> <?=$model->estimate_finished?></p> <?php }?>
        
    </div>
    <div class="attr-detail">
        <div class="title-attr-listing">Tiện ích <?=$count_facilities > 0 ? "({$count_facilities})" : ""?> </div>
        <?php if($count_facilities > 0){
            foreach($facilities as $facility) {?>
                <p><?= $facility->name?></p>
        <?php } } ?>
        
    </div>
    <div id="diadiem" class="attr-detail">
        <div class="title-attr-listing">Địa điểm</div>
        <div class="wrap-map wrap-img">
            <div id="map" style="height: 250px; width: 100%;"></div>
        </div>
    </div>
    <div class="attr-detail">
        <div class="title-attr-listing">5 Căn hộ được đăng tin</div>
        <div class="listing-duan-result">
            <ul class="clearfix">
                <li>
                    <a href="#" class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/21311_Khai-truong-Pearl-Plaza-2.jpg)"?>"></a>
                    <div class="infor-attr-duan">
                        <div class="clearfix row">
                            <div class="col-xs-6">
                                <p>3,2 tỷ VNĐ</p>
                                <p>102 m2</p>
                                <p>2 Phòng ngủ</p>
                                <p>1 Phòng tắm</p>
                            </div>
                            <div class="col-xs-6">
                                <p>Hoàn thiện:</p>
                                <p>Bếp</p>
                                <p>Nhà vệ sinh</p>
                                <p>Máy lạnh</p>
                            </div>
                        </div>
                        <p class="status-listin-duan">Available</p>
                    </div>
                </li>
                <li>
                    <a href="#" class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/21311_Khai-truong-Pearl-Plaza-2.jpg)"?>"></a>
                    <div class="infor-attr-duan">
                        <div class="clearfix row">
                            <div class="col-xs-6">
                                <p>3,2 tỷ VNĐ</p>
                                <p>102 m2</p>
                                <p>2 Phòng ngủ</p>
                                <p>1 Phòng tắm</p>
                            </div>
                            <div class="col-xs-6">
                                <p>Hoàn thiện:</p>
                                <p>Bếp</p>
                                <p>Nhà vệ sinh</p>
                                <p>Máy lạnh</p>
                            </div>
                        </div>
                        <p class="status-listin-duan">Available</p>
                    </div>
                </li>
                <li>
                    <a href="#" class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/21311_Khai-truong-Pearl-Plaza-2.jpg)"?>"></a>
                    <div class="infor-attr-duan">
                        <div class="clearfix row">
                            <div class="col-xs-6">
                                <p>3,2 tỷ VNĐ</p>
                                <p>102 m2</p>
                                <p>2 Phòng ngủ</p>
                                <p>1 Phòng tắm</p>
                            </div>
                            <div class="col-xs-6">
                                <p>Hoàn thiện:</p>
                                <p>Bếp</p>
                                <p>Nhà vệ sinh</p>
                                <p>Máy lạnh</p>
                            </div>
                        </div>
                        <p class="status-listin-duan">Available</p>
                    </div>
                </li>
                <li>
                    <a href="#" class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/21311_Khai-truong-Pearl-Plaza-2.jpg)"?>"></a>
                    <div class="infor-attr-duan">
                        <div class="clearfix row">
                            <div class="col-xs-6">
                                <p>3,2 tỷ VNĐ</p>
                                <p>102 m2</p>
                                <p>2 Phòng ngủ</p>
                                <p>1 Phòng tắm</p>
                            </div>
                            <div class="col-xs-6">
                                <p>Hoàn thiện:</p>
                                <p>Bếp</p>
                                <p>Nhà vệ sinh</p>
                                <p>Máy lạnh</p>
                            </div>
                        </div>
                        <p class="status-listin-duan">Available</p>
                    </div>
                </li>
                <li>
                    <a href="#" class="wrap-img bgcover" style="background-image:url(<?=Yii::$app->view->theme->baseUrl."/resources/images/21311_Khai-truong-Pearl-Plaza-2.jpg)"?>"></a>
                    <div class="infor-attr-duan">
                        <div class="clearfix row">
                            <div class="col-xs-6">
                                <p>3,2 tỷ VNĐ</p>
                                <p>102 m2</p>
                                <p>2 Phòng ngủ</p>
                                <p>1 Phòng tắm</p>
                            </div>
                            <div class="col-xs-6">
                                <p>Hoàn thiện:</p>
                                <p>Bếp</p>
                                <p>Nhà vệ sinh</p>
                                <p>Máy lạnh</p>
                            </div>
                        </div>
                        <p class="status-listin-duan">Available</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="attr-detail text-center">
        <button class="contact-agent">Xem chi tiết căn hộ</button>
    </div>
</div>
<script>
    function initMap() {
        var mapLatLng = {lat: <?=$model->lat?>, lng: <?=$model->lng?>};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: mapLatLng
        });

        var contentString = '<b><?=strtoupper($model->name)?></b>';

        var infowindow = new google.maps.InfoWindow({
            content: contentString,
            maxWidth: 300
        });

        var marker = new google.maps.Marker({
            position: mapLatLng,
            map: map,
            title: '<?=$model->name?>'
        });
        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTwptkS584b_mcZWt0j_86ZFYLL0j-1Yw&callback=initMap"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            spaceBetween: 30
        });
    });
</script>