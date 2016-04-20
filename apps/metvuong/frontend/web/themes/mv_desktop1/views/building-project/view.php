<?php
use yii\helpers\Url;

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
//$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/detail.js', ['position' => View::POS_END]);
$this->registerCss('.map-wrap {position: relative;} .map-wrap:after {display: block; content: ""; padding-top: 75%;} .map-inside {position: absolute; width: 100%; height: 100%;} #map {height: 100%;}');

Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => $model->location
]);
Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => $model->description
]);

Yii::$app->view->registerMetaTag([
    'property' => 'og:title',
    'content' => $model->location
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:description',
    'content' => $model->description
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:type',
    'content' => 'article'
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:image',
    'content' => $model->logoUrl
]);

$lbl_updating = Yii::t('general', 'Updating');

$fb_appId = '680097282132293'; // stage.metvuong.com
if(strpos(Yii::$app->urlManager->hostInfo, 'dev.metvuong.com'))
    $fb_appId = '736950189771012';
else if(strpos(Yii::$app->urlManager->hostInfo, 'local.metvuong.com'))
    $fb_appId = '891967050918314';
?>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : <?=$fb_appId?>,
            xfbml      : true,
            version    : 'v2.5'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/vi_VN/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<div class="title-fixed-wrap">
    <div class="container">
        <div class="detail-duan-moi">
            <!-- <div class="title-top"><?= $model->name?></div> -->
            <div class="wrap-duan-moi row">
                <div class="col-xs-12 col-md-9 col-left">
                    <div class="gallery-detail swiper-container">
                        <div class="swiper-wrapper">
                            <?php
                            if(!empty($model->gallery)) {
                                $gallery = explode(',', $model->gallery);
                                if (count($gallery) > 0) {
                                    foreach ($gallery as $image) {
                                        ?>
                                        <div class="swiper-slide">
                                            <div class="img-show">
                                                <div>
                                                    <img src="<?= \yii\helpers\Url::to('/store/building-project-images/' . $image) ?>"
                                                        alt="<?= $model->location ?>">
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                }
                            } else {
                                ?>
                                <div class="swiper-slide">
                                    <div class="img-show">
                                        <div>
                                            <img src="<?=$model->logoUrl?>" alt="<?=$model->location?>">
                                        </div>
                                    </div>
                                </div>
                            <?php }  ?>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"><span></span></div>
                        <div class="swiper-button-prev"><span></span></div>
                    </div>
                    <div class="item infor-address-duan">
                        <p><?= $model->investment_type ?></p>
                        <strong><?= $model->name?></strong>
                        <?= empty($model->location) ? $lbl_updating : $model->location ?>
                        <ul class="pull-right icons-detail">
                            <li><a href="#" data-toggle="modal" data-target="#popup-share-social" class="icon icon-share-td"></a></li>
        <!--                    <li><a href="#" class="icon save-item" data-id="4115" data-url="/ad/favorite"></a></li>-->
                            <li><a href="#" data-toggle="modal" data-target="#popup-map" class="icon icon-map-loca"></a></li>
                        </ul>
                    </div>
                    <div class="item infor-time">
                        <p><strong><?=Yii::t('project','Investor')?>: </strong> <?= empty($model->investors[0]->name) ? $lbl_updating : $model->investors[0]->name ?></p>
                        <p><strong><?=Yii::t('project', 'Start date')?>: </strong> <?=empty($model->start_date) ? $lbl_updating : date('d/m/Y', $model->start_date) ?></p>
                        <p><strong><?=Yii::t('project', 'Finish time')?>:</strong> <?=empty($model->estimate_finished) ? $lbl_updating : $model->estimate_finished ?></p>
                    </div>
                    <div class="item detail-infor">
                        <p class="title-attr-duan"><?=Yii::t('ad', 'Description')?></p>
                        <p><?=$model->description ?></p>
                    </div>
                    <div class="item infor-attr">
                        <p class="title-attr-duan"><?=Yii::t('project', 'Project information')?></p>
                        <ul class="clearfix">
                            <li><strong><?=Yii::t('project', 'Facade width')?>:</strong><?=!empty($model->facade_width) ? $model->facade_width : $lbl_updating?></li>
                            <li><strong><?=Yii::t('project', 'Floor')?>:</strong><?=!empty($model->floor_no) ? $model->floor_no : $lbl_updating?></li>
                            <li><strong><?=Yii::t('project', 'Lift')?>:</strong><?=!empty($model->lift) ? $model->lift : $lbl_updating?></li>
                        </ul>
                    </div>
                    <div class="item tien-ich-duan">
                        <p class="title-attr-duan"><?=Yii::t('project', 'Facility')?></p>
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
                        <p><?=$lbl_updating;?></p>
                        <?php }?>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3 col-right sidebar-col">
                    <?php
//                    $hotproject = \vsoft\ad\models\AdBuildingProject::getHotProject();
//                    if(count($hotproject) > 0)
//                        echo $this->render('/building-project/_partials/hotproject',['projects' => $hotproject]);

                    $topproject = \vsoft\ad\models\AdBuildingProject::getTopProject();
                    if(count($topproject) > 0)
                        echo $this->render('/building-project/_partials/topproject',['projects' => $topproject]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="popup-map" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <a href="#" class="btn-close-map close" data-dismiss="modal" aria-label="Close"><?=Yii::t('project', 'Back')?></a>
                <div id="map" data-lat="<?= $model->lat ?>" data-lng="<?= $model->lng ?>"></div>
            </div>
        </div>
    </div>
</div>

<div id="popup-share-social" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                <div class="wrap-popup">
                    <div class="inner-popup">
                        <div class="wrap-body-popup">
                            <span><?=Yii::t('project', 'Share on Social Network')?></span>
                            <ul class="clearfix">
                                <li>
                                    <a href="#" class="share-facebook">
                                        <div class="circle"><div><span class="icon icon-face"></span></div></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" data-toggle="modal" data-target="#popup-email" class="email-btn">
                                        <div class="circle"><div><span class="icon icon-email-1"></span></div></div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?=$this->renderAjax('/ad/_partials/shareEmail',[ 'popup_email_name' => 'popup_email_share', 'project' => $model, 'yourEmail' => Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->email, 'recipientEmail' => '', 'params' => ['your_email' => false, 'setValueToEmail' => false] ])?>

<script type="text/javascript">
    $(document).ready(function () {
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            spaceBetween: 0,
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev'
        });

        /*$('#popup-map').popupMobi({
            btnClickShow: ".icon-map-loca",
            closeBtn: "#popup-map .btn-close-map",
            effectShow: "show-hide",
            funCallBack: function() {
                var mapEl = $('#map');
                var latLng = {lat: Number(mapEl.data('lat')), lng:  Number(mapEl.data('lng'))};
                var map = new google.maps.Map(mapEl.get(0), {
                    center: latLng,
                    zoom: 16,
                    mapTypeControl: false,
                    zoomControl: true,
                    streetViewControl: false
                });

                var marker = new google.maps.Marker({
                    position: latLng,
                    map: map
                });
            }
        });

        $('#popup-share-social').popupMobi({
            btnClickShow: ".icons-detail .icon-share-td",
            closeBtn: ".btn-close, .email-btn, .share-facebook",
            styleShow: "center"
        });

        $('#popup-email').popupMobi({
            btnClickShow: ".email-btn",
            closeBtn: '#popup-email .btn-cancel',
            styleShow: "full"
        });*/

        $(document).on('click', '.share-facebook', function() {
            FB.ui({
                method: 'share',
                href: '<?=Yii::$app->request->absoluteUrl?>'
            }, function(response){});
        });

    });
</script>