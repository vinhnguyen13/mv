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


// user get from username in address bar
$user = $model->getUser();
$recipientEmail = empty($model->public_email) ? $user->email : $model->public_email;
// get user was been login
$yourEmail = Yii::$app->user->isGuest ? "" : (empty(Yii::$app->user->identity->profile->public_email) ? Yii::$app->user->identity->email : Yii::$app->user->identity->profile->public_email);
?>
<div class="title-fixed-wrap">
    <div class="container">
        <div class="profile-user row">
            <div class="col-xs-12 col-md-9 left-user">
                <div class="user-avatar">
                    <div class="wrap-img avatar"><img id="profileAvatar" data-toggle="modal" data-target="#avatar" src="<?=$model->avatar?>" alt="metvuong avatar" /></div>
                    <div class="overflow-all">
                        <p class="name-user fs-18 font-600" ><?= $model->name ?></p>
                        <div class="stars">
                            <select class="rating" name="rating">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <p class="location"><?= empty($user->location) ?  "<i style=\"font-weight: normal;\">".Yii::t('general', 'Updating')."</i>" : $user->location->city ?></p>
                        <p class="num-mobi"><?= empty($model->mobile) ?  "<a href='#'><i style=\"font-weight: normal;\">".Yii::t('general', 'Updating')."</i></a>" : "<a href='tel:".$model->mobile."'>".$model->mobile."</a>" ?></p>
                        <p class="email-user"><a href="#popup-email" class="email-btn"><?= empty($model->public_email) ?  "<i style=\"font-weight: normal;\">".Yii::t('general', 'Updating')."</i>" : $model->public_email ?></a></p>
                    </div>
                </div>
                <ul class="clearfix tabs-scroll">
                    <li><a class="active" href="#tab-infor">Cá nhân</a></li>
                    <li><a href="#tab-list-post">Listings (10)</a></li>
                    <li><a href="#tab-review">Review (3)</a></li>
                </ul>
                <div id="tab-infor" class="infor-priva tabs-scroll-item">
                    <div class="title-text"><?=Yii::t('profile','Personal Information')?></div>
                    <p><?= empty($model->bio) ?  "<i style=\"font-weight: normal;\">".Yii::t('general', 'Updating')."</i>" : $model->bio ?></p>
                </div>
                <?php
                $count_product = $pagination->totalCount;

                if($count_product > 0) {
                $categories = \vsoft\ad\models\AdCategory::find ()->indexBy( 'id' )->asArray( true )->all ();
                $types = \vsoft\ad\models\AdProduct::getAdTypes ();
                $count_sale = count($sale_products);
                $count_rent = count($rent_products);
                ?>
                <div id="tab-list-post" class="list-per-post tabs-scroll-item">
                    <div class="title-text clearfix"><?=Yii::t('profile', 'LISTINGS')?>
                        <ul class="nav nav-tabs pull-right" role="tablist">
                            <li role="presentation" class="active"><a href="#list-all" aria-controls="list-all" role="tab" data-toggle="tab">Tất cả (<?=$count_product?>)</a></li>
                            <li role="presentation"><a href="#list-by" aria-controls="list-by" role="tab" data-toggle="tab">Bán(<?=$count_sale?>)</a></li>
                            <li role="presentation"><a href="#list-rent" aria-controls="list-rent" role="tab" data-toggle="tab">Cho thuê (<?=$count_rent?>)</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="list-all">
                            <ul class="clearfix list-post">
                                <?php
                                foreach ($products as $product) {
                                ?>
                                <li class="col-xs-12 col-sm-6">
                                    <div class="wrap-item-post">
                                        <a href="<?= $product->urlDetail() ?>" class="rippler rippler-default">
                                            <div class="img-show"><div><img src="<?= $product->representImage ?>">
                                                    <input type="hidden" value="<?= $product->representImage ?>">
                                                </div></div>
                                            <div class="title-item"><?= ucfirst($categories[$product->category_id]['name']) ?> <?= $types[$product->type] ?></div>
                                        </a>
                                        <div class="address-feat clearfix">
                                            <p class="date-post"><?=Yii::t('listing','Listing date')?>: <strong><?= date("d/m/Y H:i", $product->created_at) ?></strong></p>
                                            <div class="address-listing"><a href="<?= $product->urlDetail() ?>"><?=$product->getAddress()?></a></div>
                                            <p class="id-duan">ID:<span><?= Yii::$app->params['listing_prefix_id'] . $product->id;?></span></p>
                                            <ul class="clearfix list-attr-td">
                                                <?php if(empty($product->area) && empty($product->adProductAdditionInfo->room_no) && empty($product->adProductAdditionInfo->toilet_no)){ ?>
                                                    <li><span><?=Yii::t('listing','updating')?></span></li>
                                                <?php } else {
                                                    echo $product->area ? '<li> <span class="wrap-icon-svg"><svg class="icon-svg icon-dt-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-dt-svg"></use></svg></span>' . $product->area . 'm2 </li>' : '';
                                                    echo $product->adProductAdditionInfo->room_no ? '<li> <span class="icon icon-bed icon-bed-small"></span> ' . $product->adProductAdditionInfo->room_no . ' </li>' : '';
                                                    echo $product->adProductAdditionInfo->toilet_no ? '<li> <span class="icon icon-pt icon-pt-small"></span> ' . $product->adProductAdditionInfo->toilet_no . ' </li>' : '';
                                                } ?>
                                            </ul>    
                                        </div>
                                        <div class="bottom-item clearfix">
                                            <p><?=Yii::t('listing','Price')?> <strong class="color-cd pdL-5"><?= StringHelper::formatCurrency($product->price) ?> vnd</strong></p>
                                        </div>
                                    </div>
                                </li>
                                <?php } ?>
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
                            <ul class="clearfix list-post">
                                <?php
                                foreach ($sale_products as $product) {
                                    ?>
                                    <li class="col-xs-12 col-sm-6">
                                        <div class="wrap-item-post">
                                            <a href="<?= $product->urlDetail() ?>" class="rippler rippler-default">
                                                <div class="img-show"><div><img src="<?= $product->representImage ?>">
                                                        <input type="hidden" value="<?= $product->representImage ?>">
                                                    </div></div>
                                                <div class="title-item"><?= ucfirst($categories[$product->category_id]['name']) ?> <?= $types[$product->type] ?></div>
                                            </a>
                                            <div class="address-feat clearfix">
                                                <p class="date-post"><?=Yii::t('listing','Listing date')?>: <strong><?= date("d/m/Y H:i", $product->created_at) ?></strong></p>
                                                <div class="address-listing"><a href="<?= $product->urlDetail() ?>"><?=$product->getAddress()?></a></div>
                                                <p class="id-duan">ID:<span><?=$product->id?></span></p>
                                                <ul class="clearfix list-attr-td">
                                                    <li>
                                                        <span class="wrap-icon-svg"><svg class="icon-svg icon-dt-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-dt-svg"></use></svg></span><?= $product->area ?>
                                                    </li>
                                                    <li>
                                                        <span class="icon icon-bed icon-bed-small"></span><?= $product->adProductAdditionInfo->room_no ?>
                                                    </li>
                                                    <li>
                                                        <span class="icon icon-pt icon-pt-small"></span><?= $product->adProductAdditionInfo->toilet_no ?>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="bottom-item clearfix">
                                                <p><?=Yii::t('profile','Price')?> <strong class="color-cd pdL-5"><?= StringHelper::formatCurrency($product->price) ?> VND</strong></p>
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="list-rent">
                            <ul class="clearfix list-post">
                                <?php
                                foreach ($rent_products as $product) {
                                    ?>
                                    <li class="col-xs-12 col-sm-6">
                                        <div class="wrap-item-post">
                                            <a href="<?= $product->urlDetail() ?>" class="rippler rippler-default">
                                                <div class="img-show"><div><img src="<?= $product->representImage ?>">
                                                        <input type="hidden" value="<?= $product->representImage ?>">
                                                    </div></div>
                                                <div class="title-item"><?= ucfirst($categories[$product->category_id]['name']) ?> <?= $types[$product->type] ?></div>
                                            </a>
                                            <div class="address-feat clearfix">
                                                <p class="date-post"><?=Yii::t('listing','Listing date')?>: <strong><?= date("d/m/Y H:i", $product->created_at) ?></strong></p>
                                                <div class="address-listing"><a href="<?= $product->urlDetail() ?>"><?=$product->getAddress()?></a></div>
                                                <p class="id-duan">ID:<span><?=$product->id?></span></p>
                                                <ul class="clearfix list-attr-td">
                                                    <li>
                                                        <span class="wrap-icon-svg"><svg class="icon-svg icon-dt-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-dt-svg"></use></svg></span><?= $product->area ?>
                                                    </li>
                                                    <li>
                                                        <span class="icon icon-bed icon-bed-small"></span><?= $product->adProductAdditionInfo->room_no ?>
                                                    </li>
                                                    <li>
                                                        <span class="icon icon-pt icon-pt-small"></span><?= $product->adProductAdditionInfo->toilet_no ?>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="bottom-item clearfix">
                                                <p><?=Yii::t('profile','Price')?> <strong class="color-cd pdL-5"><?= StringHelper::formatCurrency($product->price) ?> VND</strong></p>
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>
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
                    <div class="title-text clearfix">REVIEW
                        <a href="#popup-review" class="btn-review btn-common pull-right">Viết Review</a>
                    </div>
                    <ul class="list-reivew">
                        <li>
                            <div class="stars">
                                <select class="rating" name="rating">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <p class="infor-user-review"><a href="#">Gao Ranger</a>12/02/2016, 8:30AM | Giúp tôi thuê nhà</p>
                            <p>Quisque varius iaculis odio sit amet elementum. Nunc porta cursus est a pretium. 
                                Nullam non metus tristique sem hendrerit tincidunt ac aliquet lectus. 
                                Vivamus ultrices laoreet convallis. Duis varius ultrices condimentum.
                                Quisque varius iaculis odio sit amet elementum. Nunc porta cursus est a pretium. 
                                Nullam non metus tristique sem hendrerit tincidunt ac aliquet lectus. 
                                Vivamus ultrices laoreet convallis. Duis varius ultrices condimentum.
                            </p>
                        </li>
                        <li>
                            <div class="stars">
                                <select class="rating" name="rating">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <p class="infor-user-review"><a href="#">Gao Ranger</a>12/02/2016, 8:30AM | Giúp tôi thuê nhà</p>
                            <p>Quisque varius iaculis odio sit amet elementum. Nunc porta cursus est a pretium. 
                                Nullam non metus tristique sem hendrerit tincidunt ac aliquet lectus. 
                                Vivamus ultrices laoreet convallis. Duis varius ultrices condimentum.
                                Quisque varius iaculis odio sit amet elementum. Nunc porta cursus est a pretium. 
                                Nullam non metus tristique sem hendrerit tincidunt ac aliquet lectus. 
                                Vivamus ultrices laoreet convallis. Duis varius ultrices condimentum.
                            </p>
                        </li>
                        <li>
                            <div class="stars">
                                <select class="rating" name="rating">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <p class="infor-user-review"><a href="#">Gao Ranger</a>12/02/2016, 8:30AM | Giúp tôi thuê nhà</p>
                            <p>Quisque varius iaculis odio sit amet elementum. Nunc porta cursus est a pretium. 
                                Nullam non metus tristique sem hendrerit tincidunt ac aliquet lectus. 
                                Vivamus ultrices laoreet convallis. Duis varius ultrices condimentum.
                                Quisque varius iaculis odio sit amet elementum. Nunc porta cursus est a pretium. 
                                Nullam non metus tristique sem hendrerit tincidunt ac aliquet lectus. 
                                Vivamus ultrices laoreet convallis. Duis varius ultrices condimentum.
                            </p>
                        </li>
                        <li>
                            <div class="stars">
                                <select class="rating" name="rating">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <p class="infor-user-review"><a href="#">Gao Ranger</a>12/02/2016, 8:30AM | Giúp tôi thuê nhà</p>
                            <p>Quisque varius iaculis odio sit amet elementum. Nunc porta cursus est a pretium. 
                                Nullam non metus tristique sem hendrerit tincidunt ac aliquet lectus. 
                                Vivamus ultrices laoreet convallis. Duis varius ultrices condimentum.
                                Quisque varius iaculis odio sit amet elementum. Nunc porta cursus est a pretium. 
                                Nullam non metus tristique sem hendrerit tincidunt ac aliquet lectus. 
                                Vivamus ultrices laoreet convallis. Duis varius ultrices condimentum.
                            </p>
                        </li>
                    </ul>
                    <div class="text-right">
                        <a href="#" class="color-cd fs-13 font-600">Xem tất cả Review</a>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-xs-3 sidebar-user">
                <div class="item-sidebar">
                    <div class="title-text"><?=Yii::t('profile','CONTACT WITH')?> <?= empty($model->name) ? strtoupper($user->username) : mb_strtoupper($model->name, "UTF-8") ?></div>
                    <?php
                    $share_form = Yii::createObject([
                        'class'    => \frontend\models\ShareForm::className(),
                        'scenario' => 'share',
                    ]);

                    $f = ActiveForm::begin([
                        'id' => 'profile_send_mail',
                        'enableAjaxValidation' => false,
                        'enableClientValidation' => true,
                        'action' => Url::to(['/ad/sendmail'])
                    ]);

                    ?>
                        <div class="form-group frm-item">
                            <input type="text" class="from_name" name="from_name" value="" placeholder="<?=Yii::t('send_email','Name')?> ...">
                            <input type="hidden" name="send_from" value="profile-<?=$user->username?>">
                        </div>
                        <div class="form-group frm-item">
                            <?= $f->field($share_form, 'your_email')->textInput(['class'=>'your_email', 'placeholder'=>Yii::t('send_email', 'Email...')])->label(false) ?>
                        </div>
                        <div class="form-group frm-item">
                            <?= $f->field($share_form, 'content')->textarea(['class'=>'content', 'cols' => 30, 'rows' => 10, 'placeholder'=>Yii::t('send_email', 'I am interested in "21, Nguyễn Trung Ngạn…"')])->label(false) ?>
                            <?= $f->field($share_form, 'recipient_email')->hiddenInput(['class'=>'recipient_email', 'value'=> $recipientEmail])->label(false);?>
                        </div>
                        <button class="btn-common btn-send-email"><?=Yii::t('send_email', 'Send mail')?></button>
                    <?php $f->end(); ?>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->render('/ad/_partials/shareEmail',[
    'user' => $user,
    'yourEmail' => $yourEmail,
    'recipientEmail' => $recipientEmail,
    'params' => ['your_email' => false, 'recipient_email' => false] ]);
?>
<div id="popup-review" class="popup-common hide-popup">
    <div class="wrap-popup">
        <div class="inner-popup">
            <a href="#" class="btn-close"><span class="icon icon-close"></span></a>
            <div class="review-box-popup">
                <h2 class="color-cd fs-18 text-uper font-600 mgB-20">REVIEW SILVER RANGER</h2>
                <p class="fs-13 mgB-10">Tell us about your experience with this agent. Your review will help other users find the agent that's right for them.</p>
                <p class="fs-13 mgB-5 font-600">Môi giới này đã</p>
                <select name="" id="" class="mgB-15">
                    <option value="">Giúp tôi thuê nhà</option>
                </select>
                <div class="check-rating mgB-15">
                    <span class="font-600 fs-13 pdR-10">Rate this agent</span>
                    <div class="stars">
                        <select class="rating" name="rating">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
                <p class="fs-13 mgB-5 font-600">Viết review</p>
                <textarea class="pd-5 mgB-5" name="" id="" cols="30" rows="10" placeholder="Nội dung"></textarea>
                <div class="text-right">
                    <button class="btn-common">GỬI</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#popup-review').popupMobi({
            btnClickShow: '.review-user a',
            closeBtn: '#popup-review .btn-close',
            styleShow: 'center'
        });

        $('#popup-email').popupMobi({
            btnClickShow: ".email-btn",
            closeBtn: '#popup-email .btn-cancel',
            styleShow: "full"
        });

        $('#profile_send_mail .btn-send-email').click( function(){
            var recipient_email = $('#profile_send_mail .recipient_email').val();
            var your_email = $('#profile_send_mail .your_email').val();
            if(recipient_email.length > 0 && your_email.length > 0) {
                $('body').loading(recipient_email);
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: $('#profile_send_mail').attr('action'),
                    data: $('#profile_send_mail').serializeArray(),
                    success: function (data) {
                        $('body').loading({done:true});
                        if(data.status == 200){
//                            $('.btn-cancel').trigger('click');
                            $('#profile_send_mail .from_name').val("");
                            $('#profile_send_mail .your_email').val("");
                            $('#profile_send_mail textarea').val("");
                        }
                        else if(data.status == 404){
                            var arr = [];
                            $.each(data.parameters, function (idx, val) {
                                var element = 'shareform-' + idx;
                                arr[element] = lajax.t(val);
                            });
                            $('#profile_send_mail').yiiActiveForm('updateMessages', arr, true);
//                            $('#popup-sent .btn-close').trigger('click');
                        } else {
                            console.log(data);
                        }
                        return true;
                    }
                });
            }
            return false;
        });
    });
    $(document).bind('chat/afterConnect', function (event, type) {
        var to_jid = chatUI.genJid('<?=$user->username?>');
        Chat.sendMessage(to_jid , 1, 'headline', {sttOnline: 0});
    });
</script>