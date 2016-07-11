<?php
use yii\helpers\Url;
use yii\web\View;

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/swiper.jquery.min.js', ['position'=>View::POS_END]);

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
//$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/detail.js', ['position' => View::POS_END]);
$this->registerCss('.map-wrap {position: relative;} .map-wrap:after {display: block; content: ""; padding-top: 75%;} .map-inside {position: absolute; width: 100%; height: 100%;} #map {height: 100%;}');

$tabProject = json_decode(strip_tags($model->data_html), true);
$tongquan = html_entity_decode($tabProject["tong-quan"], ENT_HTML5, 'utf-8');
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
            <!-- <div class="title-top"><?=empty($model->name) ? Yii::t('project', 'Project') : $model->name?></div> -->
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
                                                    <img
                                                        src="<?= \yii\helpers\Url::to('/store/building-project-images/' . $image) ?>"
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
                                            <img src="<?=$logoUrl?>" alt="<?=$logoUrl?>">
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
                        <strong><?= $model->name ?></strong>
                        <?= empty($model->location) ? $lbl_updating : $model->location ?>
                        <ul class="pull-right icons-detail">
                             <li><a href="#" data-toggle="modal" data-target="#popup-share-social" class="icon icon-share-td"></a></li>
                            <!--<li><a href="#" class="icon save-item" data-id="4115" data-url="/ad/favorite"></a></li>-->
                            <li><a href="#" data-toggle="modal" data-target="#popup-map" class="icon icon-map-loca"></a></li>
                        </ul>
                    </div>
                    <?php
                    if(count($model->investors) > 0){
                        $investor = $model->investors[0];
                        $image = $investor->logo;
                        if(empty($investor->logo))
                            $image = \vsoft\ad\models\AdImages::defaultImage();?>
                    <div class="item chudautu-infor">
                        <div class="title-section"><?=Yii::t('project', 'Investor')?></div>
                        <div class="clearfix">
                        	<div class="wrap-img pull-left">
                        		<img src="<?=(filter_var($image, FILTER_VALIDATE_URL) === FALSE) ? Yii::getAlias('@store') . "/building-project-images/" . $investor->logo : $investor->logo ?>" alt="<?=$investor->name?>">
                        	</div>
                        	<div class="infor-detail-chudautu">
                        		<ul>
    				                <li>
    				                    <strong id=""><?=Yii::t('project','Address')?></strong>:
    				                    <?=empty($investor->address) ? $lbl_updating : $investor->address ?></li>
    				                <li>
    				                    <strong id=""><?=Yii::t('project','Phone')?></strong>:
                                        <?=empty($investor->phone) ? $lbl_updating : $investor->phone ?>
    				                    |
    				                    <strong id=""><?=Yii::t('project','Fax')?></strong>:
    				                    <span><?=empty($investor->fax) ? $lbl_updating : $investor->fax ?></span>
    				                </li>
    				                <li>
    				                    <strong id=""><?=Yii::t('project','Website')?></strong>:
    				                    <span><?=empty($investor->website) ? $lbl_updating : "<a href=\"//".$investor->website."\">".$investor->website."</a>" ?></span>
    				                </li>
    				                <li>
    				                    <strong id=""><?=Yii::t('project','Email')?></strong>:
    				                    <span><?=empty($investor->email) ? $lbl_updating : $investor->email ?></span>
    				                </li>
    				            </ul>
                        	</div>
                        </div>
                    </div>
                    <?php }
                    if(count($tabProject) > 0){
                    ?>
                    <div class="infor-bds">
    	                <ul class="tabProject clearfix">
                            <?php
                            foreach($tabProject as $key => $tabValue){
                            ?>
    						<li class="">
    							<a href="javascript:void(0)" rel="nofollow" style="white-space:nowrap;"><?=$tabKeys[$key]?></a>
    						</li>
                            <?php } ?>
    					</ul>
    					<?php
                        foreach($tabProject as $key => $tabValue){
                        ?>
    					<div class="editor" style="display:none;clear: both">
                            <div class="a1">
                                <?=$tabValue?>
                            </div>
                        </div>
                        <?php } ?>
    			    </div>
                    <?php } ?>
                    <!-- <div class="text-center mgT-40">
                        <a class="btn-common mgR-10" href="<?=Url::to(['ad/index1', 'project_building_id'=>$model->id])?>" title="<?=Yii::t('project', 'Listing of this project')?>"><?=Yii::t('project', 'For Buy')?></a>
                        <a class="btn-common" href="<?=Url::to(['ad/index2', 'project_building_id'=>$model->id])?>" title="<?=Yii::t('project', 'Listing of this project')?>"><?=Yii::t('project', 'For Rent')?></a>
                    </div> -->
                    <div class="listing-post-by-project">
                        <?php
                        $categoriesDb = \vsoft\ad\models\AdCategory::getDb();
                        $categories = $categoriesDb->cache(function($categoriesDb){
                            return \vsoft\ad\models\AdCategory::find()->indexBy('id')->asArray(true)->all();
                        });
                        $types = \vsoft\ad\models\AdProduct::getAdTypes();
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
                                if(!empty($sell_products)) {
                                    ?>
                                    <ul class="clearfix listing-item">
                                        <?=$this->render('/ad/_partials/list', ['products' => $sell_products]);?>
                                    </ul>
                                    <div class="text-center">
                                        <a href="<?= Url::to(['/ad/index1', 'project_building_id'=>$model->id]) ?>" class="btn-common btn-view-more"><?=Yii::t('general', 'View more')?></a>
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
                                        <a href="<?= Url::to(['/ad/index2', 'project_building_id'=>$model->id]) ?>" class="btn-common btn-view-more"><?=Yii::t('general', 'View more')?></a>
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