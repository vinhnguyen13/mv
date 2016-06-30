<?php
use yii\helpers\Url;
use yii\web\View;

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/swiper.jquery.min.js', ['position'=>View::POS_END]);

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
//$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/detail.js', ['position' => View::POS_END]);
$this->registerCss('.map-wrap {position: relative;} .map-wrap:after {display: block; content: ""; padding-top: 75%;} .map-inside {position: absolute; width: 100%; height: 100%;} #map {height: 100%;}');

$description = strip_tags($model->description);
$description = html_entity_decode($description, ENT_HTML5, 'utf-8');
Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => $model->location
]);
Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => $description
]);

Yii::$app->view->registerMetaTag([
    'property' => 'og:title',
    'content' => $model->location
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:description',
    'content' => $description
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:type',
    'content' => 'article'
]);
$logoUrl = $model->logoUrl;
Yii::$app->view->registerMetaTag([
    'property' => 'og:image',
    'content' => $logoUrl
]);

$lbl_updating = Yii::t('general', 'Updating');
$user = Yii::$app->user->identity;
$email = Yii::$app->user->isGuest ? null : (empty($user) ? "" : (empty($user->profile->public_email) ? $user->email : $user->profile->public_email));
?>

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
                                            <img src="<?=$logoUrl?>" alt="<?=$model->location?>">
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
                        <p><?=Yii::t('project', $model->investment_type) ?></p>
                        <strong><?= $model->name?></strong>
                        <?= empty($model->location) ? $lbl_updating : $model->location ?>
                        <ul class="pull-right icons-detail">
                            <li><a href="#" data-toggle="modal" data-target="#popup-share-social" class="icon icon-share-td"></a></li>
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
                    <div class="text-center mgT-40">
                        <a class="btn-common mgR-10" href="<?=Url::to(['ad/index1', 'project_building_id'=>$model->id])?>" title="<?=Yii::t('project', 'Listing of this project')?>"><?=Yii::t('project', 'For Buy')?></a>
                        <a class="btn-common" href="<?=Url::to(['ad/index2', 'project_building_id'=>$model->id])?>" title="<?=Yii::t('project', 'Listing of this project')?>"><?=Yii::t('project', 'For Rent')?></a>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3 col-right sidebar-col">

                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->renderAjax('/ad/_partials/shareEmail',[
    'popup_email_name' => 'popup_email_share',
    'project' => $model,
    'yourEmail' => $email,
    'recipientEmail' => null,
    'params' => ['your_email' => false, 'recipient_email' => true] ])?>

<div id="popup-map" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <a href="#" class="btn-close-map close" data-dismiss="modal" aria-label="Close"><?=Yii::t('listing','Close')?></a>
                <div id="map" data-lat="<?= $model->lat ?>" data-lng="<?= $model->lng ?>"></div>
            </div>
        </div>
    </div>
</div>

<?php
$content = strip_tags($model->description);
$description = \yii\helpers\StringHelper::truncate($content, 500);
$description = str_replace("\r", "", $description);
$description = str_replace("\n", "", $description);
$imageUrl = $logoUrl;
if (!filter_var($imageUrl, FILTER_VALIDATE_URL))
    $imageUrl = Yii::$app->urlManager->hostInfo . $logoUrl;
echo $this->render('/ad/_partials/shareSocial',[
    'popup_email_name' => 'share',
    'project' => $model,
    'url' => Url::to(["building-project/view", 'slug'=>$model->slug], true),
    'title' => $model->name,
    'description' => $description,
    'image' => $imageUrl
])
?>

<script type="text/javascript">
    $(document).ready(function () {
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            spaceBetween: 0,
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev'
        });

        $('.sidebar-col').loading({full: false});
        $.ajax({
            type: "get",
            dataType: 'html',
            url: '<?=Url::to(['building-project/load-sidebar'])?>'+'?limit=6',
            success: function (data) {
                $(".sidebar-col").html(data);
                $('.sidebar-col').loading({done: true});
            }
        });

        $('#popup-map').on('show.bs.modal', function (e) {
            setTimeout(function(){
                var mapEl = $('#map');
                var latLng = {lat: Number(mapEl.data('lat')), lng:  Number(mapEl.data('lng'))};

                var map = new google.maps.Map(mapEl.get(0), {
                    center: latLng,
                    zoom: 16,
                    mapTypeControl: false,
                    zoomControl: false,
                    streetViewControl: false
                });

                var marker = new google.maps.Marker({
                    position: latLng,
                    map: map
                });
            }, 400);
        });

        function fbShare(url, title, descr, image, winWidth, winHeight) {
            var winTop = (screen.height / 2) - (winHeight / 2);
            var winLeft = (screen.width / 2) - (winWidth / 2);
            window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(url)+ '&p[title]=<?=$model->location?>&p[images][0]=' + image, 'facebook-share-dialog', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
        }

        $('.share-facebook').click(function (){
            fbShare('<?=Url::to(["building-project/view", 'slug'=>$model->slug], true) ?>', '<?=$model->name ?>', '<?=$description ?>', '<?= $imageUrl ?>', 800, 600);
            $('#popup-share-social').modal('hide');
        });

    });
</script>