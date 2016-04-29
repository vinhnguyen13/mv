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
$email = empty($user) ? "" : (empty($user->profile->public_email) ? $user->email : $user->profile->public_email);

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
                    <th class="text-uper fs-15 font-600"><span class="icon-mv color-cd mgR-10"><span class="icon-heart-icon-listing fs-19"></span></span><?=Yii::t('statistic','FAVORITE')?></th>
                </tr>
                <tr>
                    <td>
                        <?php if(isset($visitors["visitors"]) && count($visitors["visitors"]) > 0){
                            foreach($visitors["visitors"] as $key => $value){?>
                                <div class="clearfix">
                                    <a class="fs-13" href="<?=Url::to(['member/profile', 'username'=>$key])?>">
                                        <img class="pull-left" src="<?=Url::to($value['avatar'], true)?>" alt="">
                                        <span class="name-user"><?=$key?></span>
                                    </a>
                                    <div class="crt-item">
                                        <a href="#" class="btn-email-item mgR-15 tooltip-show" data-placement="bottom" title="Send email" data-url="<?=Url::to(['member/profile-render-email', 'username'=>$key])?>" data-user="<?=$key?>">
                                            <span class="icon-mv fs-16">
                                                <span class="icon-mail-profile"></span>
                                            </span>
                                        </a>
                                        <a href="#" class="chat-now tooltip-show" data-chat-user="<?=$key?>" data-placement="bottom" title="Send message">
                                            <span class="icon-mv fs-18">
                                                <span class="icon-bubbles-icon"></span>
                                            </span>
                                        </a>
                                    </div>
                                    <span class="num-show pull-right color-cd font-600"><?=$value['count']?></span>
                                </div>
                            <?php }
                        } else {?>
                            <div class="clearfix fs-13 text-center">
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
                                        <span class="name-user"><?=$key?></span>
                                    </a>
                                    <div class="crt-item">
                                        <a href="#" class="btn-email-item mgR-15 tooltip-show" data-placement="bottom" title="Send email" data-url="<?=Url::to(['member/profile-render-email', 'username'=>$key])?>" data-user="<?=$key?>">
                                            <span class="icon-mv fs-16">
                                                <span class="icon-mail-profile"></span>
                                            </span>
                                        </a>
                                        <a href="#" class="chat-now tooltip-show" data-chat-user="<?=$key?>" data-placement="bottom" title="Send message">
                                            <span class="icon-mv fs-18">
                                                <span class="icon-bubbles-icon"></span>
                                            </span>
                                        </a>
                                    </div>
                                    <span class="num-show pull-right color-cd font-600"><?=$value['count']?></span>
                                </div>
                            <?php }
                        } else {?>
                            <div class="clearfix fs-13 text-center">
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
                                        <span class="name-user"><?=$key?></span>
                                    </a>
                                    <div class="crt-item">
                                        <a href="#" class="btn-email-item mgR-15 tooltip-show" data-placement="bottom" title="Send email" data-url="<?=Url::to(['member/profile-render-email', 'username'=>$key])?>" data-user="<?=$key?>">
                                            <span class="icon-mv fs-16">
                                                <span class="icon-mail-profile"></span>
                                            </span>
                                        </a>
                                        <a href="#" class="chat-now tooltip-show" data-chat-user="<?=$key?>" data-placement="bottom" title="Send message">
                                            <span class="icon-mv fs-18">
                                                <span class="icon-bubbles-icon"></span>
                                            </span>
                                        </a>
                                    </div>
                                    <span class="num-show pull-right color-cd font-600"><?=$finder['count']?></span>
                                </div>
                            <?php }
                        } else {?>
                            <div class="clearfix fs-13 text-center">
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
                                        <span class="name-user"><?=$key?></span>
                                    </a>
                                    <div class="crt-item">
                                        <a href="#" class="btn-email-item mgR-15 tooltip-show" data-placement="bottom" title="Send email" data-url="<?=Url::to(['member/profile-render-email', 'username'=>$key])?>" data-user="<?=$key?>">
                                            <span class="icon-mv fs-16">
                                                <span class="icon-mail-profile"></span>
                                            </span>
                                        </a>
                                        <a href="#" title="Send message" class="chat-now tooltip-show" data-chat-user="<?=$key?>" data-placement="bottom">
                                            <span class="icon-mv fs-19">
                                                <span class="icon-bubbles-icon"></span>
                                            </span>
                                        </a>
                                    </div>
                                    <span class="num-show pull-right color-cd font-600"><?=$value['count']?></span>
                                </div>
                            <?php }
                        } else {?>
                            <div class="clearfix fs-13 text-center">
                                <span><?=Yii::t('statistic','Not found')?>.</span><a href="<?= Url::to(['/ad/update', 'id' => $id]) ?>"><?=Yii::t('statistic','Please, update your listing')?>.</a>
                            </div>
                        <?php }?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?=$this->renderAjax('/ad/_partials/shareEmail', [
    'popup_email_name' => 'popup_email_contact',
    'user' => $user,
    'yourEmail' => $email,
    'recipientEmail' => null,
    'params' => ['your_email' => false, 'recipient_email' => false]]);?>
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

        $(document).on('click', '.btn-email-item', function() {
            var url = $(this).data('url');
            if(url) {
                $.ajax({
                    type: "get",
                    dataType: 'json',
                    url: url,
                    success: function (data) {
                        if(data.email) {
                            $('#share_form #shareform-recipient_email').attr('value', data.email);
                            $('#share_form .img-show img').attr('src', data.ava);
                            $('#share_form .infor-send .name a').text(data.name);
                            $('#share_form .infor-send .address').text(data.address);
                            $('#popup_email_contact').modal('show');
                        }
                    }
                });
            }
            return false;
        });

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