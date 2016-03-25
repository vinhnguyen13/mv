<?php
use yii\helpers\Url;

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
//$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/detail.js', ['position' => View::POS_END]);
$this->registerCss('.map-wrap {position: relative;} .map-wrap:after {display: block; content: ""; padding-top: 75%;} .map-inside {position: absolute; width: 100%; height: 100%;} #map {height: 100%;}');

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
            <div class="title-top"><?= strtoupper($model->name)?></div>
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
                    		<img src="<?=filter_var($image, FILTER_VALIDATE_URL) ? $investor->logo : Yii::getAlias('@store') . "/building-project-images/" . $this->logo; ?>" alt="<?=$investor->name?>">
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
				                    <span><?=empty($investor->website) ? $lbl_updating : "<a href=\"".$investor->website."\">".$investor->website."</a>" ?></span>
				                </li>
				                <li>
				                    <strong id=""><?=Yii::t('project','Email')?></strong>:
				                    <span><?=empty($investor->email) ? $lbl_updating : $investor->email ?></span>
				                </li>
				            </ul>
                    	</div>
                    </div>
                </div>
                <?php } ?>
                <?php
                $tabProject = json_decode($model->data_html, true);
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

<div id="popup-share-social" class="popup-common hide-popup">
    <div class="wrap-popup">
        <div class="inner-popup">
            <a href="#" class="btn-close"><span class="icon icon-close"></span></a>
            <div class="wrap-body-popup">
                <span><?=Yii::t('project', 'Share on Social Network')?></span>
                <ul class="clearfix">
                    <li>
                        <a href="#" class="share-facebook">
                            <div class="circle"><div><span class="icon icon-face"></span></div></div>
                        </a>
                    </li>
                    <li>
                        <a href="#popup-email" class="email-btn">
                            <div class="circle"><div><span class="icon icon-email-1"></span></div></div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?=$this->renderAjax('/ad/_partials/shareEmail',[ 'project' => $model, 'yourEmail' => Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->email, 'recipientEmail' => '', 'params' => ['your_email' => false, 'setValueToEmail' => false] ])?>

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
            closeBtn: ".btn-close, .email-btn, .share-facebook",
            styleShow: "center"
        });

        $('#popup-email').popupMobi({
            btnClickShow: ".email-btn",
            closeBtn: '#popup-email .btn-cancel',
            styleShow: "full"
        });

        $(document).on('click', '.share-facebook', function() {
            FB.ui({
                method: 'share',
                href: '<?=Yii::$app->request->absoluteUrl?>'
            }, function(response){});
        });

    });
</script>