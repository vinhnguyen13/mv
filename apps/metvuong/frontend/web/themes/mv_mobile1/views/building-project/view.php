<div class="title-fixed-wrap">
    <div class="detail-duan-moi">
        <div class="title-top"><?= strtoupper($model->name)?></div>
        <div class="wrap-duan-moi">
            <div class="gallery-detail swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    $gallery = explode(',', $model->gallery);
                    if(!empty($gallery[0])) {
                        foreach ($gallery as $image) {
                            ?>
                            <div class="swiper-slide">
                                <div class="img-show">
                                    <div>
                                        <img src="<?= \yii\helpers\Url::to('/store/building-project-images/' . $image) ?>" alt="<?=$model->location?>">
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <div class="swiper-slide">
                            <div class="img-show">
                                <div>
                                    <img src="<<?= Yii::$app->view->theme->baseUrl."/resources/images/img-duan-demo.jpg" ?>" alt="<?=$model->location?>">
                                </div>
                            </div>
                        </div>
                    <?php }  ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
            <div class="item infor-address-duan">
                <p><?= !empty($model->categories[0]->name) ? \vsoft\ad\models\AdBuildingProject::mb_ucfirst($model->categories[0]->name,'UTF-8') : "Chung cư cao cấp" ?></p>
                <strong><?= strtoupper($model->name)?></strong>
                <span class="icon address-icon"></span><?= empty($model->location) ? "Đang cập nhật" : $model->location ?>
                <ul class="pull-right icons-detail">
                    <li><a href="#" class="icon icon-share-td"></a></li>
<!--                    <li><a href="#" class="icon save-item" data-id="4115" data-url="/ad/favorite"></a></li>-->
                    <li><a href="#" class="icon icon-map-loca"></a></li>
                </ul>
            </div>
            <div class="item infor-time">
                <p><strong>Chủ đầu tư:</strong> <?= empty($model->investors[0]->name) ? "Đang cập nhật" : $model->investors[0]->name ?></p>
                <p><strong>Kiến trúc sư:</strong> <?=empty($model->architects[0]->name) ? "Đang cập nhật" : $model->architects[0]->name ?></p>
                <p><strong>Nhà thầu thi công:</strong> <?=empty($model->contractors[0]->name) ? "Đang cập nhật" : $model->contractors[0]->name ?></p>
                <p><strong>Ngày khởi công:</strong> <?=empty($model->start_date) ? "Đang cập nhật" : date('d/m/Y', $model->start_date) ?></p>
                <p><strong>Dự kiến hoàn thành:</strong> <?=empty($model->estimate_finished) ? "Đang cập nhật" : $model->estimate_finished ?></p>
            </div>
            <div class="item detail-infor">
                <p class="title-attr-duan">Diễn tả chi tiết</p>
                <p><?=$model->description ?></p>
            </div>
            <div class="item infor-attr">
                <p class="title-attr-duan">Thông tin dự án</p>
                <ul class="clearfix">
                    <li><strong>Mặt tiền:</strong><?=$model->facade_width?></li>
                    <li><strong>Tầng cao:</strong><?=$model->floor_no?> Tầng</li>
                    <li><strong>Thang máy:</strong><?=$model->lift?></li>
                </ul>
            </div>
            <div class="item tien-ich-duan">
                <p class="title-attr-duan">Tiện ích</p>
                <?php
                $facilityListId = explode(",", $model->facilities);
                $facilities = \vsoft\ad\models\AdFacility::find()->where(['id' => $facilityListId])->all();
                $count_facilities = count($facilities);
                if($count_facilities > 0){
                ?>
                <ul class="clearfix">
                    <?php foreach($facilities as $facility){ ?>
                    <li>
                        <div><p><span class="icon-ti icon-sport"></span><?= $facility->name ?></p></div>
                    </li>
                    <?php } ?>
                </ul>
                <?php } else {?>
                <p>Đang cập nhật</p>
                <?php }?>
            </div>
        </div>
    </div>
</div>

<div id="popup-map" class="popup-common hide-popup">
    <div class="wrap-popup">
        <div class="inner-popup">
            <a href="#" class="btn-close-map">trở lại</a>
            <iframe width="600" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJT7lZ30cvdTER8skpPrOuvGs&key=AIzaSyDgukAnWQNq0fitebULUbottG5gvK64OCQ" allowfullscreen></iframe>
        </div>
    </div>
</div>

<div id="popup-share-social" class="popup-common hide-popup">
    <div class="wrap-popup">
        <div class="inner-popup">
            <a href="#" class="btn-close"><span class="icon icon-close"></span></a>
            <div class="wrap-body-popup">
                <span>Share on Social Network</span>
                <ul class="clearfix">
                    <li>
                        <a href="#">
                            <div class="circle"><div><span class="icon icon-face"></span></div></div>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="circle"><div><span class="icon icon-email-1"></span></div></div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            spaceBetween: 30
        });

        $('#popup-map').popupMobi({
            btnClickShow: ".icon-map-loca",
            closeBtn: "#popup-map .btn-close-map"
        });

        $('#popup-share-social').popupMobi({
            btnClickShow: ".icons-detail .icon-share-td",
            closeBtn: ".btn-close",
            styleShow: "center"
        });
    });
</script>