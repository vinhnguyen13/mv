<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\StringHelper;
use yii\web\View;
use yii\helpers\Url;

Yii::$app->getView()->registerJsFile('http://code.highcharts.com/highcharts.js', ['position' => View::POS_BEGIN]);
//Yii::$app->getView()->registerJsFile('http://code.highcharts.com/modules/exporting.js', ['position' => View::POS_BEGIN]);

$id = $product->id;
$address = $product->getAddress();
$urlDetail = $product->urlDetail(true);
$user = Yii::$app->user->identity;
$backUrl = empty($user) ? "#" : Url::to([$user->username."/ad"]);

$finderFrom = isset($finders["from"]) ? $finders["from"] : strtotime('-6 days', time());;
$finderTo = isset($finders["to"]) ? $finders["to"] : time();

$visitorFrom = isset($visitors["from"]) ? $visitors["from"] : strtotime('-6 days', time());;
$visitorTo = isset($visitors["to"]) ? $visitors["to"] : time();

$favouriteFrom = isset($favourites["from"]) ? $favourites["from"] : strtotime('-6 days', time());;
$favouriteTo = isset($favourites["to"]) ? $favourites["to"] : time();

//echo "<pre>";
//var_dump(date('Y-m-d H:i:s',$finderFrom));
//var_dump(date('Y-m-d H:i:s',$finderTo));
//var_dump(date('Y-m-d H:i:s',$visitorFrom));
//var_dump(date('Y-m-d H:i:s',$visitorTo));
//var_dump(date('Y-m-d H:i:s',$favouriteFrom));
//var_dump(date('Y-m-d H:i:s',$favouriteTo));
//echo "<pre>";
//exit();

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
    <div class="statis">
    	<div class="title-top">
            Thống kê
            <a href="<?=$backUrl?>" id="prev-page"><span class="icon arrowRight-1"></span></a>
        </div>
    	<section>
    		<div id="sandbox-container">
    			<input type="text" class="form-control toDate" placeholder="Ngày">
    			<span class="icon arrowDown"></span>
    		</div>
    		<div class="summary clearfix">
                <ul class="option-view-stats">
                    <li><a href="#">Favourite</a></li>
                    <li><a href="#">Click</a></li>
                    <li><a href="#">Search</a></li>
                </ul>
                <!-- <div class="clearfix">
                                    <span class="pull-right views-stats"><em class="fa fa-square-o"></em>
                        <select class="chart_stats">
                            <option class="tab" value="finder" data-url="<?=\yii\helpers\Url::to(['/dashboard/chart', 'view'=>'_partials/finder', 'id' => $id, 'from' => $finderFrom, 'to' => $finderTo, 'address' => $address, 'urlDetail' => $urlDetail])?>">Search</option>
                            <option class="tab" value="visitor" data-url="<?=\yii\helpers\Url::to(['/dashboard/chart', 'view'=>'_partials/visitor', 'id' => $id, 'from' => $visitorFrom, 'to' => $visitorTo, 'address' => $address, 'urlDetail' => $urlDetail])?>">Click</option>
                            <option class="tab" value="favourite" data-url="<?=\yii\helpers\Url::to(['/dashboard/chart', 'view'=>'_partials/saved', 'id' => $id, 'from' => $favouriteFrom, 'to' => $favouriteTo, 'address' => $address, 'urlDetail' => $urlDetail])?>">Favourite</option>
                        </select>
                    </span>
                </div> -->
    			<div class="wrap-chart clearfix">
    				<div class="wrap-img">
                        <div class="wrapChart">
                            <?=$this->render('/dashboard/chart/'.$view, ['id' => $id, 'from' => $finderFrom, 'to' => $finderTo, 'address' => $address, 'urlDetail' => $urlDetail]);?>
                        </div>
                    </div>
    			</div>
    		</div>
    	</section>
    	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    		<div class="panel panel-default finder" style="display: block;">
    			<div class="panel-heading title-sub" role="tab" id="headingOne">
    				<h4 class="panel-title">
    					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
    					<em class="icon-eye"></em> Search
    					<span class="pull-right icon"></span>
    					</a>
    				</h4>
    			</div>
    			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
    				<div class="panel-body">
    					<ul class="clearfix list-item">
                            <?php if(isset($finders["finders"]) && count($finders["finders"]) > 0){?>
                            <?php foreach($finders["finders"] as $key => $finder){
                                    $classPopupUser = 'popup_enable';
                                    if($key == $user->username)
                                        $classPopupUser = '';
                                ?>
    						<li>
                                <a class="<?=$classPopupUser?>" href="#popup-user-inter" data-email="<?=$finder['email']?>" data-ava="<?=Url::to($finder['avatar'], true)?>">
                                    <img src="<?=Url::to($finder['avatar'], true)?>" alt="<?=$key?>">
                                    <?=$key?>
                                </a>
    							<span class="pull-right"><?=$finder['count']?></span>
    						</li>
                            <?php }
                            } else { ?>
                            <li>Không có người tìm kiếm</li>
                            <?php }?>
    					</ul>
    				</div>
    			</div>
    		</div>
    		<div class="panel panel-default visitor" style="display: none;">
    			<div class="panel-heading title-sub" role="tab" id="headingTwo">
    				<h4 class="panel-title">
    					<a class="" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
    					<em class="icon-user"></em> Click
    					<span class="pull-right icon"></span>
    					</a>
    				</h4>
    			</div>
    			<div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
    				<div class="panel-body">
    					<ul class="clearfix list-item">
                            <?php if(isset($visitors["visitors"]) && count($visitors["visitors"]) > 0){
                                foreach($visitors["visitors"] as $key => $visitor){
                                    $classPopupUser = 'popup_enable';
                                    if($key == $user->username)
                                        $classPopupUser = '';
                                    ?>
                                    <li>
                                        <a class="<?=$classPopupUser?>" href="#popup-user-inter" data-email="<?=$visitor['email']?>" data-ava="<?=Url::to($visitor['avatar'], true)?>">
                                            <img src="<?=$visitor['avatar']?>" alt="<?=$key?>">
                                            <?=$key?>
                                        </a>
                                        <span class="pull-right"><?=$visitor['count']?></span>
                                    </li>
                                <?php }
                            }  else { ?>
                                <li>Không có người ghé thăm</li>
                            <?php }?>
    					</ul>
    				</div>
    			</div>
    		</div>
    		<div class="panel panel-default favourite" style="display: none;">
    			<div class="panel-heading title-sub" role="tab" id="headingThree">
    				<h4 class="panel-title">
    					<a class="" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
    					<em class="icon-heart"></em> favourite
    					<span class="pull-right icon"></span>
    					</a>
    				</h4>
    			</div>
    			<div id="collapseThree" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
    				<div class="panel-body">
    					<ul class="clearfix list-item">
                            <?php if(isset($favourites["saved"]) && count($favourites["saved"]) > 0){
                                foreach($favourites["saved"] as $key => $favourite){
                                    ?>
                                    <li>
                                        <a class="popup_enable" href="#popup-user-inter" data-email="<?=$favourite['email']?>"  data-ava="<?=Url::to($favourite['avatar'], true)?>">
                                            <img src="<?=$favourite['avatar']?>" alt="<?=$key?>">
                                            <?=$key?>
                                        </a>
                                    </li>
                                <?php }
                            }  else { ?>
                                <li>Không có lưu lại</li>
                            <?php } ?>

    					</ul>
    				</div>
    			</div>
    		</div>
    	</div>
    	<div class="share-social">
            <ul class="clearfix list-attr-per">
                <li>
                    <a class="share-email-btn" href="#popup-email">
                        <div class="circle"><div><span class="icon icon-email-1"></span></div></div>
                        <div class="txt-infor-right">
                            <div>Share With Email</div>
                        </div>
                    </a>
                </li>
                <li class="share-facebook">
                    <a href="#">
                        <div class="circle"><div><span class="icon icon-face"></span></div></div>
                        <div class="txt-infor-right">
                            <div>Share With Facebook</div>
                        </div>
                    </a>
                </li>
            </ul>
    	</div>
    </div>
</div>

<div id="popup-user-inter" class="popup-common hide-popup">
    <div class="wrap-popup">
        <div class="inner-popup">
            <a href="#" class="btn-close"><span class="icon icon-close"></span></a>
            <div class="pull-left circle avatar-user-inter">
                <a href="#">
                <img src="/images/default-avatar.jpg" alt="" width="50" height="50">
                </a>
            </div>
            <div class="overflow-all">
                <p class="name-user-inter">James Bond</p>
                <a href="#" class="btn-common btn-chat"><span class="icon icon-chat-1"></span></a>
                <a href="#popup-email" class="btn-common btn-email share-email-btn"><span class="icon icon-email-1"></span></a>
            </div>
        </div>
    </div>
</div>

<?=$this->renderAjax('/ad/_partials/shareEmail',[ 'product' => $product, 'yourEmail' => empty($user->profile->public_email) ? $user->email : $user->profile->public_email, 'recipientEmail' => '', 'params' => ['your_email' => false, 'setValueToEmail' => true] ])?>

<?php 
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/bootstrap-datepicker.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'datepicker');
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/bootstrap-datepicker.min.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/bootstrap-datepicker.vi.min.js', ['position'=>View::POS_BEGIN]);
?>

<script>

    $(document).ready(function () {
        $('#popup-user-inter').popupMobi({
            btnClickShow: '.statis .panel-body .list-item a.popup_enable',
            styleShow: 'center',
            closeBtn: '#popup-user-inter .btn-close, #popup-user-inter .share-email-btn',
            funCallBack: function(item) {
                $('#popup-user-inter .avatar-user-inter img').attr('src', item.data('ava'));
                $('#popup-user-inter .avatar-user-inter a').attr('href', '/'+item[0].innerText.trim());
                $('#popup-user-inter .name-user-inter').html('Bạn có thể Chat và Email với tài khoản: <a href="/'+item[0].innerText.trim()+'">'+item[0].innerText.trim()+'</a>');
                $('#popup-user-inter .btn-chat').attr('href','/chat/with/'+item[0].innerText.trim());
                $('.recipient_email').attr('value', item[0].attributes["data-email"].value)
            }
        });

        $('#popup-email').popupMobi({
            btnClickShow: ".share-email-btn",
            closeBtn: '#popup-email .btn-cancel',
            styleShow: 'full'
        });

        var params = getUrlParams();
        if(params["date"] !== undefined && params["date"] !== 'undefined-undefined-'){
            var arrDate = params["date"].split("-");
            var useDate = arrDate[2]+"/"+arrDate[1]+"/"+arrDate[0];
            $('.toDate').attr('placeholder', ""+useDate);
        }

        $('#sandbox-container input').datepicker({
            language: "vi",
            autoclose: true,
            onSelect: function() {
                return $(this).trigger('change');
            }
        });

        $('#sandbox-container input').change(function(){
            $('body').loading();
            var theDate = $(this).datepicker().val();
            var arrDate = theDate.split("/");
            var useDate = arrDate[2]+"-"+arrDate[1]+"-"+arrDate[0];
            if(useDate) {
                window.location = '<?=Url::to(['/dashboard/statistics','id' => $id])?>' + '&date=' + useDate;
            }
        });

        function getUrlParams()
        {
            var vars = [], hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for(var i = 0; i < hashes.length; i++)
            {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
            return vars;
        }

        $('.chart_stats').change(function () {
//            var timer = 0;
//            clearTimeout(timer);
            var url = '';
            var valueOption = '';
            $( "select option:selected" ).each(function() {
                valueOption = $(this).attr('value');
                url = $(this).attr('data-url');
            });
            if(url != '') {
                $('.wrapChart').html('');
                $('body').loading();
                $.ajax({
                    type: "get",
                    dataType: 'html',
                    url: url,
                    success: function (data) {
                        $('body').loading({done: true});
                        $('.wrapChart').html(data);
                        if(valueOption == 'finder'){
                            $('.finder').show();
                            $('.visitor').hide();
                            $('.favourite').hide();
                        }
                        else if(valueOption == 'visitor'){
                            $('.finder').hide();
                            $('.visitor').show();
                            $('.favourite').hide();
                        }
                        else if(valueOption == 'favourite'){
                            $('.finder').hide();
                            $('.visitor').hide();
                            $('.favourite').show();
                        }
                    }
                });
            }
            return false;
        });

        $(document).on('click', '.share-social .share-facebook', function() {
            var detailUrl = $('#share_form ._detailUrl').val();
            if(detailUrl == null || detailUrl == '' )
                detailUrl = $('#share_form ._domain').val();
            FB.ui({
                method: 'share',
                href: detailUrl
            }, function(response){});
        });
    });



</script>