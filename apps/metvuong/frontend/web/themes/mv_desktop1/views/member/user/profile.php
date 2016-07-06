<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 2/2/2016 10:19 AM
 */
use vsoft\express\components\StringHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\web\View;

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery.rateit.js', ['position'=>View::POS_END]);

// user get from username in address bar
$user = $model->getUser();
$recipientEmail = empty($model->public_email) ? $user->email : $model->public_email;
// get user was been login
$yourEmail = Yii::$app->user->isGuest ? "" : (empty(Yii::$app->user->identity->profile->public_email) ? Yii::$app->user->identity->email : Yii::$app->user->identity->profile->public_email);
$count_product = $pagination->totalCount;

$reviews = \frontend\models\UserReview::find()->where('review_id = :rid', [':rid' => $user->id]);
$count_review = $reviews->count();
$reviews = $reviews->orderBy(['created_at' => SORT_DESC])->limit(3)->all();
$report_list = \vsoft\ad\models\ReportType::find()->where(['is_user' => \vsoft\ad\models\ReportType::report_user])->all();
?>
<div class="title-fixed-wrap">
    <div class="container">
        <div class="profile-user row">
            <div class="title-top">
                Profile
            </div>
            <div class="col-xs-12 col-md-9 col-left">
                <div class="user-avatar">
                    <div class="wrap-img avatar pic-intro"><img id="profileAvatar" data-toggle="modal" data-target="#avatar" src="<?=$model->avatar?>" alt="metvuong avatar" /></div>
                    <div class="overflow-all">
                        <p class="name-user fs-18 font-600" >
                            <?= $model->name ?>
                            <a href="#" class="chat-now tooltip-show" data-chat-user="<?=$user->username?>" data-placement="bottom" title="Chat">
                                <span class="icon-mv"><span class="icon-bubbles-icon"></span></span>
                            </a>
                            <?php if(Yii::$app->user->id != $user->id) {?>
                            <a href="#" data-toggle="modal" data-placement="bottom" data-target="<?=Yii::$app->user->isGuest ? "" : "#report-listing" ?>" class="btn-report tooltip-show<?=Yii::$app->user->isGuest ? " user-login-link" : "" ?>" title="<?= Yii::t('profile', 'Report') ?>">
                                <span class="icon-mv"><span class="icon-warning"></span></span>
                            </a>
                            <?php }?>
                        </p>
                        <div class="stars">
                            <span id="rating-all" class="rateit" data-rateit-value="<?=$model->rating_point?>" data-rateit-ispreset="true" data-rateit-readonly="true"></span>
                            <span class="fs-13 font-600 count_review" data-number="<?=$count_review?>">(<?=$count_review?>)</span>
                        </div>
                        <p class="location">
                            <span class="icon-mv"><span class="icon-pin-active-copy-3"></span></span>
                            <?= empty($user->location) ?  "<i style=\"font-weight: normal;\">".Yii::t('general', 'Updating')."</i>" : $user->location->city ?>
                        </p>
                        <p class="num-mobi">
                            <span class="icon-mv"><span class="icon-phone-profile"></span></span>
                            <?= empty($model->mobile) ?  "<a href='#'><i style=\"font-weight: normal;\">".Yii::t('general', 'Updating')."</i></a>" : "<a href='tel:".$model->mobile."'>".$model->mobile."</a>" ?>
                        </p>
                        <p class="email-user">
                            <a href="#" data-toggle="modal" data-target="<?=$model->user_id == Yii::$app->user->id ? "" : "#popup_email"?>" data-type="contact" class="email-btn">
                                <span class="icon-mv"><span class="icon-mail-profile"></span></span>
                                <?= empty($model->public_email) ?  "<i style=\"font-weight: normal;\">".Yii::t('general', 'Updating')."</i>" : $model->public_email ?>
                            </a>
                        </p>
                        <p class="profile-url">
                            <a href="<?=Url::to(['/member/profile', 'username'=>$user->username], true)?>" class="email-btn">
                                <span class="icon-mv"><span class="icon-link"></span></span>
                                <?= str_replace(Yii::$app->language.'/', '', Url::to(['/member/profile', 'username'=>$user->username], true)) ?>
                            </a>
                        </p>
                    </div>
                </div>
                <ul class="clearfix tabs-scroll">
                    <li><a class="active" href="#tab-infor"><?=Yii::t('profile', 'About')?></a></li>
                    <?php if($count_product > 0) {?>
                    <li><a href="#tab-list-post"><?=Yii::t('profile', 'Listings')?> (<?=$count_product?>)</a></li>
                    <li><a href="#tab-review"><?=Yii::t('profile', 'Reviews')?>  (<?=$count_review?>)</a></li>
                    <?php } else { ?>
                    <li><a><?=Yii::t('profile', 'Listings')?></a></li>
                    <li><a><?=Yii::t('profile', 'Reviews')?></a></li>
                    <?php } ?>
                </ul>
                <div id="tab-infor" class="infor-priva tabs-scroll-item">
                    <div class="title-text"><?=Yii::t('profile','Personal Information')?></div>
                    <p><?= empty($model->bio) ?  "<i style=\"font-weight: normal;\">".Yii::t('general', 'Updating')."</i>" : $model->bio ?></p>
                </div>
                <?php
                if($count_product > 0) {
                $categories = \vsoft\ad\models\AdCategory::find ()->indexBy( 'id' )->asArray( true )->all ();
                $types = \vsoft\ad\models\AdProduct::getAdTypes ();
                $count_sale = count($sale_products);
                $count_rent = count($rent_products);
                ?>
                <div id="tab-list-post" class="list-per-post tabs-scroll-item">
                    <div class="title-text clearfix"><?=Yii::t('profile', 'LISTINGS')?>
                        <ul class="nav nav-tabs pull-right" role="tablist">
                            <li role="presentation" class="active"><a href="#list-all" aria-controls="list-all" role="tab" data-toggle="tab"><?=Yii::t('profile','All')?> (<?=$count_product?>)</a></li>
                            <li role="presentation"><a href="#list-by" aria-controls="list-by" role="tab" data-toggle="tab"><?=Yii::t('profile','Sell')?> (<?=$count_sale?>)</a></li>
                            <li role="presentation"><a href="#list-rent" aria-controls="list-rent" role="tab" data-toggle="tab"><?=Yii::t('profile','Rent')?> (<?=$count_rent?>)</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="list-all">
                            <ul class="clearfix listing-item">
                                <?= $this->render('/ad/_partials/list', ['products' => $products]) ?>
                            </ul>
                            <nav class="text-center">
                                <?php
                                echo LinkPager::widget([
                                    'pagination' => $pagination
                                ]);
                                ?>
                            </nav>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="list-by">
                            <ul class="clearfix listing-item">
                                <?= $this->render('/ad/_partials/list', ['products' => $sale_products]) ?>
                            </ul>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="list-rent">
                            <ul class="clearfix listing-item">
                                <?= $this->render('/ad/_partials/list', ['products' => $rent_products]) ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php } else { ?>
                <div class="list-per-post">
                    <div class="title-text clearfix"><?=Yii::t('profile', 'NO LISTING')?></div>
                    <p><a href="<?= Url::to(['/ad/post']) ?>"><?=Yii::t('profile', 'Please, post listing here')?>.</a></p>
                </div>
                <?php } ?>
                <div id="tab-review" class="review-user tabs-scroll-item">
                    <div class="title-text clearfix"><?=Yii::t('profile', 'REVIEW')?>
                        <?php if(Yii::$app->user->id != $user->id && $count_product > 0 && !Yii::$app->user->isGuest) { ?>
                            <a href="#" data-toggle="modal" data-target="#popup-review"
                                   class="btn-review btn-common pull-right"><?= Yii::t('profile', 'Write Review') ?></a>
                        <?php } ?>
                    </div>
                    <ul class="list-reivew">
                    <?php
                    if(count($reviews) <= 0){ ?>
                        <li>
                            <i><?=Yii::t('profile', 'Review not found.')?></i>
                        </li>
                    <?php } else { echo $this->render('/member/_partials/review', ['reviews' => $reviews]); } ?>
                    </ul>
                    <?php if($count_review > 3){ ?>
                    <div class="text-right">
                        <a href="#" data-url="<?=Url::to(['/member/review-all', 'review_id' => $user->id])?>" class="color-cd fs-13 font-600 read_more"><?=Yii::t('review','View all reviews')?></a>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <div class="col-xs-12 col-md-3 col-right sidebar-col">
                <div class="item-sidebar">

                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->render('/ad/_partials/shareEmail',[
    'popup_email_name' => 'popup_email_contact',
    'yourEmail' => $yourEmail,
    'recipientEmail' => $recipientEmail,
    'params' => ['your_email' => false, 'recipient_email' => false] ]);
?>
<div id="popup-review" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="wrap-popup">
                    <div class="inner-popup">
                        <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                        <div class="review-box-popup">
                            <h2 class="color-cd fs-18 text-uper font-600 mgB-20"><?=Yii::t('profile', 'REVIEW')?> <?= empty($model->name) ? strtoupper($user->username) : mb_strtoupper($model->name, "UTF-8") ?></h2>
                            <p class="fs-13 mgB-10"><?=Yii::t("review","Tell us about your experience with this agent. Your review will help other users find the agent that's right for them.")?></p>
                            <form id="review-form"
                                  action="<?=Url::to(['/member/review', 'review_id' => $user->id,
                                            'name' => (empty(Yii::$app->user->identity->profile->name) ? (Yii::$app->user->isGuest ? "" : Yii::$app->user->identity->username) : Yii::$app->user->identity->profile->name),
                                            'username' => (Yii::$app->user->isGuest ? "" : Yii::$app->user->identity->username)])?>">
                                <p class="fs-13 mgB-5 font-600"><?=Yii::t('review', 'This agent')?></p>
                                <select name="type" id="type" class="mgB-15">
                                    <option value="1"><?=Yii::t('review', 'Help me to buy')?></option>
                                    <option value="2"><?=Yii::t('review', 'Help me to rent')?></option>
                                </select>
                                <div class="check-rating mgB-15">
                                    <span class="font-600 fs-13 pdR-10"><?=Yii::t('profile', 'Rate this agent')?></span>
                                    <div class="stars">
                                        <span id="rating-review" class="rateit"></span>
                                        <input type="hidden" name="rating" id="val-rating" value="">
                                    </div>
                                </div>
                                <p class="fs-13 mgB-5 font-600"><?=Yii::t('profile', 'Review content')?></p>
                                <textarea class="pd-5 mgB-5" name="review_content" id="review_content" cols="30" rows="10" placeholder="<?=Yii::t('profile','Content')?>"></textarea>
                                <div class="text-right">
                                    <button class="btn-common send_review"><?=Yii::t('profile','Send review')?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- div id="popup-login" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                <div class="wrap-popup">
                    <div class="inner-popup">
                        <div class="wrap-body-popup">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div -->

<div id="report-listing" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="wrap-popup">
                    <div class="inner-popup">
                        <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                        <div class="review-box-popup">
                            <h2 class="color-cd fs-18 text-uper font-600 mgB-20"><?=Yii::t('profile', 'REPORT')?> <?= empty($model->name) ? strtoupper($user->username) : mb_strtoupper($model->name, "UTF-8") ?></h2>
                            <p class="fs-13 mgB-10"><?=Yii::t("report", "Tell us about your experience with this agent. Your report will help other users review the agent that's right for them.")?></p>
                            <?php  if(count($report_list) > 0){?>
                            <form id="report-form" class="fs-13"
                                  action="<?=Url::to(['/member/review', 'review_id' => $user->id,
                                      'name' => (empty(Yii::$app->user->identity->profile->name) ? (Yii::$app->user->isGuest ? "" : Yii::$app->user->identity->username) : Yii::$app->user->identity->profile->name),
                                      'username' => (Yii::$app->user->isGuest ? "" : Yii::$app->user->identity->username)])?>">
                                <?php // \yii\helpers\Html::radioList('type', null, $report_list, ['class' => 'fs-13'])
                                Yii::t('report', 'It is spam');
                                Yii::t('report', 'It is inappropriate');
                                Yii::t('report', 'It insults or attacks someone based on their religion, ethnicity or sexual orientation');
                                Yii::t('report', 'It describes buying or selling drugs, guns or regulated users');
                                foreach($report_list as $key_report => $report){
                                ?>
                                <label><input type="radio" name="type" value="<?=$report->id?>" <?=$key_report == 0 ? "checked" : ""?>> <?=Yii::t('report', $report->name)?> </label>
                                <?php } ?>
                                <label><input type="radio" name="type" value="-1"> <?=Yii::t('listing', 'Something else')?> </label>
                                <textarea class="pd-5 mgB-5" name="report_content" id="report_content" cols="30" rows="5" placeholder="<?=Yii::t('profile','Content')?>"></textarea>
                                <input type="hidden" name="ip" value="<?=Yii::$app->request->userIP ?>">
                                <input type="hidden" name="is_report" value="1">
                                <div class="text-right">
                                    <button class="btn-common send_report"><?=Yii::t('profile','Send report')?></button>
                                </div>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#rating-all').rateit();

        $('#rating-review').rateit({
            clickRating: function (value_rating) {
                $('#val-rating').val(value_rating);
            }
        });

        $('.item-sidebar').loading({full: false});
        $.ajax({
            type: "get",
            dataType: 'html',
            url: '<?=Url::to(['ad/load-listing-widget'])?>',
            success: function (data) {
                $(".item-sidebar").html(data);
                $('.item-sidebar').loading({done: true});
            }
        });

        $(document).on('click', '.send_review', function (e) {
            e.preventDefault();
            $('body').loading();
            $.ajax({
                type: "post",
                dataType: 'json',
                url: $('#review-form').attr('action'),
                data: $('#review-form').serializeArray(),
                success: function (data) {
                    $('#popup-review').modal('hide');
                    $('body').loading({done:true});
                    if(data.statusCode == 200) {
                        $('body').alertBox({
                            txt: "Review thành công !!!"
                        });
                        window.location.reload();
                    }
                    return true;
                }
            });
            return false;
        });

        $(document).on('click', '.send_report', function () {
            $('body').loading();
            $.ajax({
                type: "post",
                dataType: 'json',
                url: $('#report-form').attr('action'),
                data: $('#report-form').serializeArray(),
                success: function (data) {
                    $('#report-listing').modal('hide');
                    $('body').loading({done:true});
                    if(data.statusCode == 200) {
                        $('body').alertBox({
                            txt: "<?=Yii::t('listing', 'Report has been sent.')?>"
                        });
                    }
                    return true;
                }
            });
            return false;
        });

        $(document).on('click', '#tab-review .read_more', function () {
            $('body').loading();
            var last_time = $('#tab-review .list-reivew>li:last').attr('class');
            var url = $(this).data('url')+"&last_time="+last_time;
            var count_review = parseInt($('.count_review').data('number'));
            var current_review = $('#tab-review .list-reivew>li').length;
            if(current_review < count_review){
                $.ajax({
                    type: "get",
                    dataType: 'html',
                    url: url,
                    success: function (data) {
                        if(data) {
                            $('#tab-review .list-reivew').append(data);
                            var current_review = $('#tab-review .list-reivew>li').length;
                            if(current_review >= count_review) {
                                $('#tab-review .read_more').parent().remove();
                            }
                            $('body').loading({done: true});
                        }
                        return true;
                    }
                });
            }
            return false;
        });

//        $('#profile_send_mail .btn-send-email').click( function(){
//            var recipient_email = $('#profile_send_mail .recipient_email').val();
//            var your_email = $('#profile_send_mail .your_email').val();
//            if(recipient_email.length > 0 && your_email.length > 0) {
//                $('body').loading(recipient_email);
//                $.ajax({
//                    type: "post",
//                    dataType: 'json',
//                    url: $('#profile_send_mail').attr('action'),
//                    data: $('#profile_send_mail').serializeArray(),
//                    success: function (data) {
//                        $('body').loading({done:true});
//                        if(data.status == 200){
////                            $('.btn-cancel').trigger('click');
//                            $('#profile_send_mail .from_name').val("");
//                            $('#profile_send_mail .your_email').val("");
//                            $('#profile_send_mail textarea').val("");
//                        }
//                        else if(data.status == 404){
//                            var arr = [];
//                            $.each(data.parameters, function (idx, val) {
//                                var element = 'shareform-' + idx;
//                                arr[element] = lajax.t(val);
//                            });
//                            $('#profile_send_mail').yiiActiveForm('updateMessages', arr, true);
////                            $('#popup-sent .btn-close').trigger('click');
//                        } else {
//                            console.log(data);
//                        }
//                        return true;
//                    }
//                });
//            }
//            return false;
//        });
    });
    $(document).bind('chat/afterConnect', function (event, type) {
        var to_jid = chatUI.genJid('<?=$user->username?>');
        Chat.sendMessage(to_jid , 1, 'headline', {sttOnline: 0});
    });
</script>