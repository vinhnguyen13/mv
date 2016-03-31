<?php
use yii\helpers\Url;

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
//$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/detail.js', ['position' => View::POS_END]);
$this->registerCss('.map-wrap {position: relative;} .map-wrap:after {display: block; content: ""; padding-top: 75%;} .map-inside {position: absolute; width: 100%; height: 100%;} #map {height: 100%;}');

$tabProject = json_decode($model->data_html, true);
Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => $model->location
]);
Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => $tabProject["tong-quan"]
]);

Yii::$app->view->registerMetaTag([
    'property' => 'og:title',
    'content' => $model->location
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:description',
    'content' => $tabProject["tong-quan"]
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
$tabKeys = [
    'tong-quan' => Yii::t('project','General'),
    'vi-tri' => Yii::t('project','Position'),
    'ha-tang' => Yii::t('project','Facility'),
    'thiet-ke' => Yii::t('project','Design'),
    'tien-do' => Yii::t('project','Progress'),
    'ban-hang' => Yii::t('project','Business'),
    'ho-tro' => Yii::t('project','Support'),
];
?>
<div class="title-fixed-wrap">
    <div class="container">
        <div class="detail-duan-moi">
            <div class="title-top"><?=empty($model->name) ? Yii::t('project', 'Project') : $model->name?></div>
            <div class="wrap-duan-moi">
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
                                        <img src="<?=$model->logoUrl?>" alt="<?=$model->logoUrl?>">
                                    </div>
                                </div>
                            </div>
                        <?php }  ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <div class="item infor-address-duan">
                	<p><?= $model->investment_type  ?></p>
                    <strong><?= $model->name ?></strong>
                    <?= empty($model->location) ? $lbl_updating : $model->location ?>
                    <ul class="pull-right icons-detail">
                        <li><a href="#popup-share-social" class="icon icon-share-td"></a></li>
                        <!--                    <li><a href="#" class="icon save-item" data-id="4115" data-url="/ad/favorite"></a></li>-->
                        <li><a href="#popup-map" class="icon icon-map-loca"></a></li>
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
            </div>
        </div>
    </div>
</div>

<div id="popup-map" class="popup-common hide-popup">
    <div class="wrap-popup">
        <div class="inner-popup">
            <a href="#" class="btn-close-map"><?=Yii::t('project', 'Back')?></a>
            <div id="map" data-lat="<?= $model->lat ?>" data-lng="<?= $model->lng ?>"></div>
        </div>
    </div>
</div>

<?=$this->renderAjax('/ad/_partials/shareEmail',[ 'project' => $model, 'yourEmail' => Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->email, 'recipientEmail' => '', 'params' => ['your_email' => false, 'setValueToEmail' => false] ])?>

<?=$this->render('/ad/_partials/shareSocial',[
    'url' => Url::to(["building/$model->slug"], true),
    'title' => $model->name,
    'description' => \yii\helpers\StringHelper::truncate($model->description, 200, $suffix = '...', $encoding = 'UTF-8'),
    'image' => $model->logoUrl
])?>

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
            spaceBetween: 30
        });

        $('#popup-map').popupMobi({
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
            closeBtn: ".btn-close",
            styleShow: "center"
        });

        $(document).on('click', '#popup-share-social .icon-email-1', function (e) {
            $('#popup-share-social').addClass('hide-popup');
            $('.email-btn').trigger('click');
        });

    });
</script>