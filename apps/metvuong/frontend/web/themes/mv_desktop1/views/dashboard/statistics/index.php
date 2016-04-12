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

$finderFrom = (!empty($finders) && isset($finders["from"])) ? $finders["from"] : 0;
$finderTo = (!empty($finders) && isset($finders["to"])) ? $finders["to"] : 0;

$visitorFrom = (!empty($visitors) && isset($visitors["from"])) ? $visitors["from"] : 0;
$visitorTo = (!empty($visitors) && isset($visitors["to"])) ? $visitors["to"] : 0;

$favouriteFrom = (!empty($favourites) && isset($favourites["from"])) ? $favourites["from"] : 0;
$favouriteTo = (!empty($favourites) && isset($favourites["to"])) ? $favourites["to"] : 0;

$shareFrom = (!empty($shares) && isset($shares["from"])) ? $shares["from"] : 0;
$shareTo = (!empty($shares) && isset($shares["to"])) ? $shares["to"] : 0;

//echo "<pre>";
//var_dump(date('Y-m-d H:i:s',$shareFrom));
//var_dump(date('Y-m-d H:i:s',$shareTo));
//echo "<pre>";
//exit();

//$fb_appId = '680097282132293'; // stage.metvuong.com
//if(strpos(Yii::$app->urlManager->hostInfo, 'dev.metvuong.com'))
//    $fb_appId = '736950189771012';
//else if(strpos(Yii::$app->urlManager->hostInfo, 'local.metvuong.com'))
//    $fb_appId = '891967050918314';

?>

<div class="title-fixed-wrap">
    <div class="container">
        <div class="statis">
        	<section class="clearfix mgB-40">
        		<!-- <div id="sandbox-container">
                    <input type="text" class="form-control toDate" readonly placeholder="<?=Yii::t('statistic','Select date')?>">
                    <span class="icon arrowDown"></span>
                </div> -->

                <div class="pull-right fs-13 mgB-15">
                    Chọn lọc
                    <select name="" id="" class="mgL-10">
                        <option value="">Theo tuần</option>
                        <option value="">Theo tháng</option>
                        <option value="">Theo quý</option>
                    </select>
                </div>
                <div class="clearfix"></div>
        		<div class="summary clearfix">
                    <div class="wrap-chart clearfix">
        				<div class="wrap-img">
                            <div class="wrapChart">
                                <?=$this->render('/dashboard/chart/'.$view, ['id' => $id, 'from' => $finderFrom, 'to' => $finderTo, 'address' => $address, 'urlDetail' => $urlDetail]);?>
                            </div>
                        </div>
        			</div>
                    <ul class="option-view-stats clearfix">
                        <li><a href="#" class="btn-finder active" data-url="<?=\yii\helpers\Url::to(['/dashboard/chart', 'view'=>'_partials/finder', 'id' => $id, 'from' => $finderFrom, 'to' => $finderTo, 'address' => $address, 'urlDetail' => $urlDetail])?>"><?=Yii::t('statistic','Search')?><span><?=$search_count ?></span></a></li>
                        <li><a href="#" class="btn-visitor" data-url="<?=\yii\helpers\Url::to(['/dashboard/chart', 'view'=>'_partials/visitor', 'id' => $id, 'from' => $visitorFrom, 'to' => $visitorTo, 'address' => $address, 'urlDetail' => $urlDetail])?>"><?=Yii::t('statistic','Click')?><span><?=$click_count ?></span></a></li>
                        <li><a href="#" class="btn-favourite" data-url="<?=\yii\helpers\Url::to(['/dashboard/chart', 'view'=>'_partials/saved', 'id' => $id, 'from' => $favouriteFrom, 'to' => $favouriteTo, 'address' => $address, 'urlDetail' => $urlDetail])?>"><?=Yii::t('statistic','Favourite')?><span><?=$fav_count ?></span></a></li>
                        <li><a href="#" class="btn-share" data-url="<?=\yii\helpers\Url::to(['/dashboard/chart', 'view'=>'_partials/share', 'id' => $id, 'from' => $shareFrom, 'to' => $shareTo, 'address' => $address, 'urlDetail' => $urlDetail])?>"><?=Yii::t('statistic','Share')?> <span><?=$share_count?></span></a></li>
                    </ul>
        		</div>
        	</section>
            <h2 class="text-uper fs-16 font-600 mgB-30 color-cd">STATISTIC</h2>
            <table class="tbl-review">
                <tr>
                    <th class="text-uper fs-15 font-600">LƯỢT XEM</th>
                    <th class="text-uper fs-15 font-600">THÍCH</th>
                </tr>
                <tr>
                    <td>
                        <?php if(isset($visitors["visitors"]) && count($visitors["visitors"]) > 0){
                            foreach($visitors["visitors"] as $key => $value){?>
                                <div class="clearfix">
                                    <a class="fs-13" href="<?=Url::to(['member/profile', 'username'=>$key])?>">
                                        <img class="pull-left" src="<?=Url::to($value['avatar'], true)?>" alt="">
                                        <span class="num-show pull-right color-cd font-600"><?=$value['count']?></span>
                                        <span class="name-user"><?=$key?></span>
                                    </a>
                                </div>
                            <?php }
                        }?>
                    </td>
                    <td>
                        <?php if(isset($favourites["saved"]) && count($favourites["saved"]) > 0){
                            foreach($favourites["saved"] as $key => $value){?>
                                <div class="clearfix">
                                    <a class="fs-13" href="<?=Url::to(['member/profile', 'username'=>$key])?>">
                                        <img class="pull-left" src="<?=Url::to($value['avatar'], true)?>" alt="">
                                        <span class="num-show pull-right color-cd font-600"><?=$value['count']?></span>
                                        <span class="name-user"><?=$key?></span>
                                    </a>
                                </div>
                            <?php }
                        }?>
                    </td>
                </tr>
                <tr>
                    <th class="text-uper fs-15 font-600">TÌM KIẾM</th>
                    <th class="text-uper fs-15 font-600">CHIA SẺ</th>
                </tr>
                <tr>
                    <td>
                        <?php if(isset($finders["finders"]) && count($finders["finders"]) > 0){
                            foreach($finders["finders"] as $key => $finder){?>
                                <div class="clearfix">
                                    <a class="fs-13" href="<?=Url::to(['member/profile', 'username'=>$key])?>">
                                        <img class="pull-left" src="<?=Url::to($finder['avatar'], true)?>" alt="">
                                        <span class="num-show pull-right color-cd font-600"><?=$finder['count']?></span>
                                        <span class="name-user"><?=$key?></span>
                                    </a>
                                </div>
                            <?php }
                        }?>
                    </td>
                    <td>
                        <?php if(isset($shares["shares"]) && count($shares["shares"]) > 0){
                            foreach($shares["shares"] as $key => $value){?>
                                <div class="clearfix">
                                    <a class="fs-13" href="<?=Url::to(['member/profile', 'username'=>$key])?>">
                                        <img class="pull-left" src="<?=Url::to($value['avatar'], true)?>" alt="">
                                        <span class="num-show pull-right color-cd font-600"><?=$value['count']?></span>
                                        <span class="name-user"><?=$key?></span>
                                    </a>
                                </div>
                            <?php }
                        }?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- div id="popup-user-inter" class="popup-common hide-popup">
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
                <a href="#" data-toggle="modal" data-target="#popup-email" class="btn-common btn-email share-email-btn"><span class="icon icon-email-1"></span></a>
            </div>
        </div>
    </div>
</div -->

<?php //$this->renderAjax('/ad/_partials/shareEmail',[ 'product' => $product, 'yourEmail' => empty($user->profile->public_email) ? $user->email : $user->profile->public_email, 'recipientEmail' => '', 'params' => ['your_email' => false, 'setValueToEmail' => true] ])?>

<?php
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/bootstrap-datepicker.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'datepicker');
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/bootstrap-datepicker.min.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/bootstrap-datepicker.vi.min.js', ['position'=>View::POS_BEGIN]);
?>

<script>
    $(document).ready(function () {
        /*$('#popup-user-inter').popupMobi({
            btnClickShow: '.statis .panel-body .list-item a.popup_enable',
            styleShow: 'center',
            closeBtn: '#popup-user-inter .btn-close, #popup-user-inter .share-email-btn',
            funCallBack: function(item) {
                if(item == '') {
                    $('#popup-user-inter').addClass('hide-popup');
                } else {
                    $('#popup-user-inter .avatar-user-inter img').attr('src', item.data('ava'));
                    $('#popup-user-inter .avatar-user-inter a').attr('href', '/' + item[0].innerText.trim());
                    $('#popup-user-inter .name-user-inter').html('Bạn có thể Chat và Email với tài khoản: <a href="/' + item[0].innerText.trim() + '">' + item[0].innerText.trim() + '</a>');
                    $('#popup-user-inter .btn-chat').attr('href', '/chat/with/' + item[0].innerText.trim());
                    $('.recipient_email').attr('value', item[0].attributes["data-email"].value);
                }
            }
        });

        $('#popup-email').popupMobi({
            btnClickShow: ".share-email-btn",
            closeBtn: '#popup-email .btn-cancel',
            styleShow: 'full'
        });*/

//        var params = getUrlParams();
//        if(params["date"] !== undefined && params["date"] !== 'undefined-undefined-'){
//            var arrDate = params["date"].split("-");
//            var useDate = arrDate[2]+"/"+arrDate[1]+"/"+arrDate[0];
//            $('.toDate').attr('placeholder', ""+useDate);
//        }
//
//        $('#sandbox-container input').datepicker({
//            language: "vi",
//            autoclose: true,
//            onSelect: function() {
//                return $(this).trigger('change');
//            }
//        });
//
//        $('#sandbox-container input').change(function(){
//            $('body').loading();
//            var theDate = $(this).datepicker().val();
//            var arrDate = theDate.split("/");
//            var useDate = arrDate[2]+"-"+arrDate[1]+"-"+arrDate[0];
//            if(useDate) {
//                window.location = '<?//=Url::to(['/dashboard/statistics','id' => $id], true)?>//' + '&date=' + useDate;
//            }
//        });
//
//        function getUrlParams()
//        {
//            var vars = [], hash;
//            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
//            for(var i = 0; i < hashes.length; i++)
//            {
//                hash = hashes[i].split('=');
//                vars.push(hash[0]);
//                vars[hash[0]] = hash[1];
//            }
//            return vars;
//        }

        $(document).on('click', '.btn-finder', function() {
            var url = $(this).attr('data-url');
            if(url != '') {
                $('.wrapChart').html('');
                $('body').loading();
                $.ajax({
                    type: "get",
                    dataType: 'html',
                    url: url,
                    success: function (data) {
                        $('body').loading({done: true});
                        $('.option-view-stats li a').removeClass("active");
                        $('.btn-finder').addClass("active");
                        $('.wrapChart').html(data);
                    }
                });
            }
            return false;
        });

        $(document).on('click', '.btn-visitor', function() {
            var url = $(this).attr('data-url');
            if(url != '') {
                $('.wrapChart').html('');
                $('body').loading();
                $.ajax({
                    type: "get",
                    dataType: 'html',
                    url: url,
                    success: function (data) {
                        $('body').loading({done: true});
                        $('.option-view-stats li a').removeClass("active");
                        $('.btn-visitor').addClass("active");
                        $('.wrapChart').html(data);
                    }
                });
            }
            return false;
        });

        $(document).on('click', '.btn-share', function() {
            var url = $(this).attr('data-url');
            if(url != '') {
                $('.wrapChart').html('');
                $('body').loading();
                $.ajax({
                    type: "get",
                    dataType: 'html',
                    url: url,
                    success: function (data) {
                        $('body').loading({done: true});
                        $('.option-view-stats li a').removeClass("active");
                        $('.btn-share').addClass("active");
                        $('.wrapChart').html(data);
                    }
                });
            }
            return false;
        });

        $(document).on('click', '.btn-favourite', function() {
            var url = $(this).attr('data-url');
            if(url != '') {
                $('.wrapChart').html('');
                $('body').loading();
                $.ajax({
                    type: "get",
                    dataType: 'html',
                    url: url,
                    success: function (data) {
                        $('body').loading({done: true});
                        $('.option-view-stats li a').removeClass("active");
                        $('.btn-favourite').addClass("active");
                        $('.wrapChart').html(data);
                    }
                });
            }
            return false;
        });

        function fbShare(url, title, descr, image, winWidth, winHeight) {
            var winTop = (screen.height / 2) - (winHeight / 2);
            var winLeft = (screen.width / 2) - (winWidth / 2);
            window.open('http://www.facebook.com/sharer.php?s=100&p[url]=' + url + '&p[title]=' + title + '&p[summary]=' + descr + '&p[images][0]=' + image, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
        }
    });
</script>