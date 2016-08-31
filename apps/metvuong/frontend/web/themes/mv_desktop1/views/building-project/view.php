<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Request;
use yii\web\View;
use common\models\SlugSearch;

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/swiper.jquery.min.js', ['position'=>View::POS_END]);

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
$this->registerCss('.map-wrap {position: relative;} .map-wrap:after {display: block; content: ""; padding-top: 75%;} .map-inside {position: absolute; width: 100%; height: 100%;} #map {height: 100%;}');

$tongquan = $model->description;
$tabProject = null;
if(!empty($model->data_html)){
    $tabProject = json_decode($model->data_html, true);
    if (count($tabProject) > 0) {
        $key_index = key($tabProject);
        $tongquan = html_entity_decode($tabProject[$key_index], ENT_HTML5, 'utf-8');
    }
}
$tongquan = trim(strip_tags($tongquan));

Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => $model->location
]);
Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => $tongquan
]);

Yii::$app->view->registerMetaTag([
    'property' => 'og:title',
    'content' => $model->location
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:description',
    'content' => $tongquan
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
$tabKeys = [
    'tong-quan' => Yii::t('project','General'),
    'vi-tri' => Yii::t('project','Position'),
    'ha-tang' => Yii::t('project','Facility'),
    'thiet-ke' => Yii::t('project','Design'),
    'tien-do' => Yii::t('project','Progress'),
    'ban-hang' => Yii::t('project','Business'),
    'ho-tro' => Yii::t('project','Support'),
];
$user = Yii::$app->user->identity;
$email = Yii::$app->user->isGuest ? null : (empty($user) ? "" : (empty($user->profile->public_email) ? $user->email : $user->profile->public_email));
?>
<div class="title-fixed-wrap">
    <div class="container">
        <div class="detail-duan-moi">
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
                                            <a href="<?= \yii\helpers\Url::to('/store/building-project-images/' . $image) ?>" class="fancybox" rel="gallery1">
                                                <img src="<?= \yii\helpers\Url::to('/store/building-project-images/' . $image) ?>" alt="<?= $model->name ?>">
                                            </a>
                                        </div>
                                    <?php }
                                }
                            } else {
                                ?>
                                <div class="swiper-slide">
                                    <a href="<?=$logoUrl?>" class="fancybox" rel="gallery1">
                                        <img src="<?=$logoUrl?>" alt="<?=$model->name?>">
                                    </a>
                                </div>
                            <?php }  ?>
                        </div>
                        <div class="swiper-button-next"><span></span></div>
                        <div class="swiper-button-prev"><span></span></div>
                    </div>
                    <div class="item infor-address-duan">
                        <p><?=Yii::t('project', $model->investment_type) ?></p>
                        <strong><?= $model->name ?></strong>
                        <?= empty($model->location) ? $lbl_updating : $model->location ?>
                        <ul class="pull-right icons-detail">
                            <li><a href="#" data-toggle="modal" data-target="#popup-share-social" class="icon icon-share-td"></a></li>
                            <li><a href="#" data-toggle="modal" data-target="#popup-map" class="icon icon-map-loca"></a></li>
                        </ul>
                    </div>
                    <?php
                    if(count($tabProject) > 0){
                        ?>
                        <div class="infor-bds">
                            <ul class="tabProject clearfix">
                                <?php
                                foreach($tabProject as $key => $tabValue) {
                                    if (!empty($tabValue)) {
                                        ?>
                                        <li>
                                            <a href="javascript:void(0)" rel="nofollow"><?= $tabKeys[$key] ?></a>
                                        </li>
                                    <?php }
                                }
                                $investors = $model->investors;
                                if(count($investors) > 0){ ?>
                                    <li>
                                        <a href="javascript:void(0)" rel="nofollow"><?=Yii::t('project', 'Investor')?></a>
                                    </li>
                                <?php }
                                $model_facilities = $model->facilities;
                                if(!empty($model->start_time) || !empty($model->estimate_finished) || !empty($model->building_density) ||
                                    !empty($model->land_area) || !empty($model->apartment_no) || !empty($model->units_no) || !empty($model->gfa) ||
                                    !empty($model->no_1_bed) || !empty($model->sqm_1_bed) || !empty($model->no_2_bed) || !empty($model->sqm_2_bed) ||
                                    !empty($model->no_3_bed) || !empty($model->sqm_3_bed) || !empty($model_facilities)) { ?>
                                    <li>
                                        <a href="javascript:void(0)" rel="nofollow"><?=Yii::t('project', 'Others Information')?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                            <?php
                            foreach($tabProject as $key => $tabValue) {
                                if (!empty($tabValue)) {
                                    ?>
                                    <div class="editor">
                                        <div class="a1">
                                            <?= $tabValue ?>
                                        </div>
                                    </div>
                                <?php }
                            }
                            $architects = $model->architects;
                            $contractors = $model->contractors;
                            if(count($investors) > 0 || count($architects) > 0 || count($contractors) > 0) {
                            ?>
                            <div class="editor">
                                <div class="a1">
                                    <?php foreach ($investors as $investor) {
                                    $src_img = $investor->logo;
                                    if (empty($investor->logo))
                                        $src_img = \vsoft\ad\models\AdImages::defaultImage();
                                    if (file_exists(Yii::getAlias('@store') . "/investor/" . $investor->logo))
                                        $src_img = "/store/investor/" . $investor->logo;?>
                                    <div class="item chudautu-infor">
                                        <div class="clearfix">
                                            <div class="wrap-img">
                                                <img src="<?= (filter_var($src_img, FILTER_VALIDATE_URL) === FALSE) ? $src_img : $investor->logo ?>"
                                                    alt="<?= $investor->name ?>">
                                            </div>
                                            <div class="infor-detail-chudautu">
                                                <ul>
                                                    <li><strong
                                                            class="fs-18 font-600"><?= empty($investor->name) ? $lbl_updating : $investor->name ?></strong>
                                                    </li>
                                                    <li>
                                                        <strong><?= Yii::t('project', 'Address') ?></strong>:
                                                        <?= empty($investor->address) ? $lbl_updating : $investor->address ?>
                                                    </li>
                                                    <li>
                                                        <strong><?= Yii::t('project', 'Phone') ?></strong>:
                                                        <?= empty($investor->phone) ? $lbl_updating : $investor->phone ?>
                                                        |
                                                        <strong><?= Yii::t('project', 'Fax') ?></strong>:
                                                        <span><?= empty($investor->fax) ? $lbl_updating : $investor->fax ?></span>
                                                    </li>
                                                    <li>
                                                        <strong><?= Yii::t('project', 'Website') ?></strong>:
                                                        <span><?= empty($investor->website) ? $lbl_updating : "<a href='#' class='investor_website' data-url='" . $investor->website . "'>" . $investor->website . "</a>" ?></span>
                                                    </li>
                                                    <li>
                                                        <strong><?= Yii::t('project', 'Email') ?></strong>:
                                                        <span><?= empty($investor->email) ? $lbl_updating : $investor->email ?></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }
                                    if(count($architects) > 0){ ?>
                                    <div class="item chudautu-infor">
                                        <div class="title-section"><?=Yii::t('project', 'Architect')?></div>
                                        <?php foreach($architects as $architect){ ?>
                                            <div class="info-detail-architect">
                                                <?= empty($architect->name) ? $lbl_updating : $architect->name ?></li>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php }
                                    if(count($contractors) > 0){ ?>
                                        <div class="item chudautu-infor">
                                            <div class="title-section"><?=Yii::t('project', 'Contractor')?></div>
                                            <?php foreach($contractors as $contractor){ ?>
                                                <div class="info-detail">
                                                    <?= empty($contractor->name) ? $lbl_updating : $contractor->name ?></li>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php }
                            if(!empty($model->start_time) || !empty($model->estimate_finished) || !empty($model->building_density) ||
                            !empty($model->land_area) || !empty($model->apartment_no) || !empty($model->units_no) || !empty($model->gfa) ||
                            !empty($model->no_1_bed) || !empty($model->sqm_1_bed) || !empty($model->no_2_bed) || !empty($model->sqm_2_bed) ||
                            !empty($model->no_3_bed) || !empty($model->sqm_3_bed) || !empty($model_facilities)) { ?>
                                <div class="editor">
                                    <div class="a1">
                                        <div class="item chudautu-infor thong-tin-khac">
                                            <div class="info-detail">
                                                <?= !empty($model->start_time) ? "<div><span>". Yii::t('project', 'Construction Start'). ":</span><p>". $model->start_time. "</p></div>" : null?>
                                                <?= !empty($model->estimate_finished) ? "<div><span>". Yii::t('project', 'Completion'). ":</span><p>". $model->estimate_finished. "</p></div>" : null?>
                                                <?= !empty($model->building_density) ? "<div><span>". Yii::t('project', 'Building Density'). ":</span><p>". $model->building_density. "</p></div>" : null?>
                                                <?= !empty($model->land_area) ? "<div><span>". Yii::t('project', 'Land Area'). ":</span> ". $model->land_area. "</p></div>" : null?>
                                                <?= !empty($model->apartment_no) ? "<div><span>". Yii::t('project', '# of Building'). ":</span><p>". $model->apartment_no. "</p></div>" : null?>
                                                <?= !empty($model->units_no) ? "<div><span>". Yii::t('project', 'Units No'). ":</span><p>". $model->units_no. "</p></div>" : null?>
                                                <?= !empty($model->gfa) ? "<div><span>". Yii::t('project','GFA'). ":</span><p>". $model->gfa. "</div>" : null?>
                                                <?= !empty($model->lift) ? "<div><span>". Yii::t('project', 'Lift'). ":</span><p>". $model->lift. "</p></div>" : null?>
                                                <?= !empty($model->no_1_bed) ? "<div><span>". Yii::t('project','# 1 Bed'). ":</span><p>". $model->no_1_bed. "</p></div>" : null?>
                                                <?= !empty($model->sqm_1_bed) ? "<div><span>". Yii::t('project', 'SQM 1 Bed'). ":</span><p>". $model->sqm_1_bed. "</p></div>" : null?>
                                                <?= !empty($model->no_2_bed) ? "<div><span>". Yii::t('project','# 2 Bed'). ":</span><p>". $model->no_2_bed. "</p></div>" : null?>
                                                <?= !empty($model->sqm_2_bed) ? "<div><span>". Yii::t('project', 'SQM 2 Bed'). ":</span><p>". $model->sqm_2_bed. "</p></div>" : null?>
                                                <?= !empty($model->no_3_bed) ? "<div><span>". Yii::t('project','# 3 Bed'). ":</span><p>". $model->no_3_bed. "</p></div>" : null?>
                                                <?= !empty($model->sqm_3_bed) ? "<div><span>". Yii::t('project', 'SQM 3 Bed'). ":</span><p>". $model->sqm_3_bed. "</p></div>" : null?>
                                                <br>
                                            </div>
                                        </div>
                                        <?php
                                        if(!empty($model_facilities)){?>
                                            <div class="chudautu-infor">
                                                <div class="title-section">Tiện ích</div>
                                                <ul class="clearfix list-tienich">
                                                    <?php
                                                    $_facility = \vsoft\ad\models\AdFacility::getDb()->cache(function() use($model_facilities){
                                                        return \vsoft\ad\models\AdFacility::find()->where("id in ({$model_facilities})")->asArray()->all();
                                                    });
                                                    $facilities = ArrayHelper::getColumn($_facility, 'name');
                                                    if(count($facilities) > 0) {
                                                        foreach ($facilities as $k => $facility) {
                                                            $class = \common\components\Slug::me()->slugify($facility); ?>
                                                            <li>
                                                                <span class="icon-mv"><span class="icon-<?=$class?>"></span></span>
                                                                <?=Yii::t('ad', $facility)?>
                                                            </li>
                                                        <?php }
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        <?php  } ?>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
                    <?php } ?>

                    <div class="listing-post-by-project">
                        <?php
                        $sell_products = \frontend\models\Ad::find()->listingOfBuilding($model->id, \vsoft\ad\models\AdProduct::TYPE_FOR_SELL);
                        $rent_products = \frontend\models\Ad::find()->listingOfBuilding($model->id, \vsoft\ad\models\AdProduct::TYPE_FOR_RENT);
                        ?>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#tab-can-mua" aria-controls="home" role="tab" data-toggle="tab"><?=Yii::t('ad', 'For Sell')?></a></li>
                            <li role="presentation"><a href="#tab-can-thue" aria-controls="home" role="tab" data-toggle="tab"><?=Yii::t('ad', 'For Rent')?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="tab-can-mua">
                                <?php
                                $slug = SlugSearch::find()->where(['table' => 'ad_building_project', 'value' => $model->id])->one()->slug;
                                if(!empty($sell_products)) {
                                    ?>
                                    <ul class="clearfix listing-item">
                                        <?=$this->render('/ad/_partials/list', ['products' => $sell_products]);?>
                                    </ul>
                                    <div class="text-center">
                                        <a href="<?= Url::to(['/ad/index1', 'params'=>$slug]) ?>" class="btn-common btn-view-more"><?=Yii::t('general', 'View more')?></a>
                                    </div>
                                    <?php
                                }else {
                                    ?>
                                    <ul class="clearfix listing-item">
                                        <li class="col-xs-12 col-sm-6 col-lg-4">
                                            <?=Yii::t('common', '{object} no data', ['object'=>Yii::t('ad', 'For Sell')])?>
                                        </li>
                                    </ul>
                                    <?php
                                }
                                ?>
                            </div>
                            <div role="tabpanel" class="tab-pane fade in" id="tab-can-thue">
                                <?php
                                if(!empty($rent_products)) {
                                    ?>
                                    <ul class="clearfix listing-item">
                                        <?=$this->render('/ad/_partials/list', ['products' => $rent_products]);?>
                                    </ul>
                                    <div class="text-center">
                                        <a href="<?= Url::to(['/ad/index2', 'params'=>$slug]) ?>" class="btn-common btn-view-more"><?=Yii::t('general', 'View more')?></a>
                                    </div>
                                    <?php
                                }else {
                                    ?>
                                    <ul class="clearfix listing-item">
                                        <li class="col-xs-12 col-sm-6 col-lg-4">
                                            <?=Yii::t('common', '{object} no data', ['object'=>Yii::t('ad', 'For Rent')])?>
                                        </li>
                                    </ul>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3 col-right sidebar-col">

                </div>
            </div>
        </div>
    </div>
</div>

<div id="popup-map" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <a href="#" class="btn-close-map close" data-dismiss="modal" aria-label="Close"><?=Yii::t('listing','Close')?></a>
                <div id="map_project" data-lat="<?= $model->lat ?>" data-lng="<?= $model->lng ?>"></div>
            </div>
        </div>
    </div>
</div>


<?php
$content = $tongquan;
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
        $(".fancybox").fancybox({
            openEffect  : 'none',
            closeEffect : 'none',
            padding: 3
        });
        $('.tabProject li').eq(0).addClass('tabActiveProject');
        $('.wrap-duan-moi .editor').eq(0).show();
        $('.tabProject li a').on('click', function (e) {
            e.preventDefault();
            var _this = $(this),
                indexItem = _this.parent().index();
            $('.tabProject li').removeClass('tabActiveProject');
            _this.parent().addClass('tabActiveProject');
            $('.wrap-duan-moi .editor').hide();
            $('.wrap-duan-moi .editor').eq(indexItem).velocity("fadeIn", { duration: 200 });
        });

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
            url: '<?=Url::to(['building-project/load-sidebar'])?>'+'?limit=6&current_id=<?=$model->id?>',
            success: function (data) {
                $(".sidebar-col").html(data);
                $('.sidebar-col').loading({done: true});
            }
        });

        $('#popup-map').on('show.bs.modal', function (e) {
            setTimeout(function(){
                var map_project = $('#map_project');
                var latLng_project = {lat: Number(map_project.data('lat')), lng:  Number(map_project.data('lng'))};

                var pmap = new google.maps.Map(map_project.get(0), {
                    center: latLng_project,
                    zoom: 16,
                    mapTypeControl: false,
                    zoomControl: false,
                    streetViewControl: false
                });

                var marker = new google.maps.Marker({
                    position: latLng_project,
                    map: pmap
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

        $('.investor_website').click(function(){
            var url = $(this).data('url');
            if (!/^(?:f|ht)tps?\:\/\//.test(url)) {
                url = "http://" + url;
            }
            window.location = url;
        });

    });
</script>