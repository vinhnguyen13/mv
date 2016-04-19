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
//$backUrl = empty($user) ? "#" : Url::to([$user->username."/ad"]);

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
            <div class="title-top">
                <?=Yii::t('statistic','Statistic')?>
            </div>
        	<section class="clearfix mgB-40">
                <div class="pull-right fs-13 mgB-15">
                    Chọn lọc
                    <select id="filterChart" class="mgL-10">
                        <option value="week"><?=Yii::t('statistic','Week')?></option>
                        <option value="month"><?=Yii::t('statistic','Month')?></option>
                        <option value="quarter"><?=Yii::t('statistic','Quarter')?></option>
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
                        <li><a href="#" class="btn-finder active" data-url="<?=\yii\helpers\Url::to(['/dashboard/chart', 'view'=>'_partials/finder', 'id' => $id, 'from' => $finderFrom, 'to' => $finderTo, 'address' => $address, 'urlDetail' => $urlDetail])?>"> <span class="icon-mv fs-19"><span class="icon-icons-search"></span></span> <?=Yii::t('statistic','Search')?><span><?=$search_count ?></span></a></li>
                        <li><a href="#" class="btn-visitor" data-url="<?=\yii\helpers\Url::to(['/dashboard/chart', 'view'=>'_partials/visitor', 'id' => $id, 'from' => $visitorFrom, 'to' => $visitorTo, 'address' => $address, 'urlDetail' => $urlDetail])?>"><span class="icon-mv"><span class="icon-eye-copy"></span></span><?=Yii::t('statistic','Click')?><span><?=$click_count ?></span></a></li>
                        <li><a href="#" class="btn-favourite" data-url="<?=\yii\helpers\Url::to(['/dashboard/chart', 'view'=>'_partials/saved', 'id' => $id, 'from' => $favouriteFrom, 'to' => $favouriteTo, 'address' => $address, 'urlDetail' => $urlDetail])?>"><span class="icon-mv"><span class="icon-heart-icon-listing"></span></span><?=Yii::t('statistic','Favourite')?><span><?=$fav_count ?></span></a></li>
                        <li><a href="#" class="btn-share" data-url="<?=\yii\helpers\Url::to(['/dashboard/chart', 'view'=>'_partials/share', 'id' => $id, 'from' => $shareFrom, 'to' => $shareTo, 'address' => $address, 'urlDetail' => $urlDetail])?>"> <span class="icon-mv"><span class="icon-share-social"></span></span> <?=Yii::t('statistic','Share')?> <span><?=$share_count?></span></a></li>
                    </ul>
        		</div>
        	</section>
            <h2 class="text-uper fs-16 font-600 mgB-30 color-cd"><?=Yii::t('statistic','STATISTIC')?></h2>
            <table class="tbl-review">
                <tr>
                    <th class="text-uper fs-15 font-600"><span class="icon-mv color-cd mgR-10 fs-17"><span class="icon-eye-copy"></span></span><?=Yii::t('statistic','VIEW')?></th>
                    <th class="text-uper fs-15 font-600"><span class="icon-mv color-cd mgR-10"><span class="icon-heart-icon-listing fs-19"></span></span><?=Yii::t('statistic','LIKE')?></th>
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
                        } else {?>
                            <div class="clearfix fs-13">
                                <span><?=Yii::t('statistic','Not found')?>.</span><a href="<?= Url::to(['/ad/update', 'id' => $id]) ?>"><?=Yii::t('statistic','Please, update your listing')?>.</a>
                            </div>
                        <?php }?>
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
                        } else {?>
                            <div class="clearfix fs-13">
                                <span><?=Yii::t('statistic','Not found')?>.</span><a href="<?= Url::to(['/ad/update', 'id' => $id]) ?>"><?=Yii::t('statistic','Please, update your listing')?>.</a>
                            </div>
                        <?php }?>
                    </td>
                </tr>
                <tr>
                    <th class="text-uper fs-15 font-600"><span class="icon-mv color-cd mgR-10"><span class="icon-icons-search fs-19"></span></span><?=Yii::t('statistic','SEARCH')?></th>
                    <th class="text-uper fs-15 font-600"><span class="icon-mv color-cd mgR-10"><span class="icon-share-social fs-19"></span></span><?=Yii::t('statistic','SHARE')?></th>
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
                        } else {?>
                            <div class="clearfix fs-13">
                                <span><?=Yii::t('statistic','Not found')?>.</span><a href="<?= Url::to(['/ad/update', 'id' => $id]) ?>"><?=Yii::t('statistic','Please, update your listing')?>.</a>
                            </div>
                        <?php }?>
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
                        } else {?>
                            <div class="clearfix fs-13">
                                <span><?=Yii::t('statistic','Not found')?>.</span><a href="<?= Url::to(['/ad/update', 'id' => $id]) ?>"><?=Yii::t('statistic','Please, update your listing')?>.</a>
                            </div>
                        <?php }?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<?php
//$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/bootstrap-datepicker.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'datepicker');
//Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/bootstrap-datepicker.min.js', ['position'=>View::POS_BEGIN]);
//Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/bootstrap-datepicker.vi.min.js', ['position'=>View::POS_BEGIN]);
?>

<script>
    $(document).ready(function () {

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

        $('#filterChart').val('<?=$filter?>');

        $('#filterChart').change(function(){
            var val = $(this).val();
            if (val != '') {
                var goto = '<?=Url::to(['/dashboard/statistics', 'id' => $id], true)?>'+'&filter='+val;
                window.location = goto;
            }
        });
    });
</script>