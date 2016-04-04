<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 2/2/2016 10:19 AM
 */
use vsoft\express\components\StringHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

// user get from username in address bar
$user = $model->getUser();
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
                        <ul class="rating">
                            <li>rating</li>    
                        </ul>
                        <p class="location"><?= empty($user->location) ?  "<i style=\"font-weight: normal;\">".Yii::t('general', 'Updating')."</i>" : $user->location->city ?></p>
                        <p class="num-mobi"><?= empty($model->mobile) ?  "<a href='#'><i style=\"font-weight: normal;\">".Yii::t('general', 'Updating')."</i></a>" : "<a href='tel:".$model->mobile."'>".$model->mobile."</a>" ?></p>
                        <p class="email-user"><a href="#" data-toggle="modal" data-target="#popup-email" class="email-btn"><?= empty($model->public_email) ?  "<i style=\"font-weight: normal;\">".Yii::t('general', 'Updating')."</i>" : $model->public_email ?></a></p>
                    </div>
                </div>
                <div class="infor-priva">
                    <div class="title-text">THÔNG TIN CÁ NHÂN</div>
                    <p><?= empty($model->bio) ?  "<i style=\"font-weight: normal;\">".Yii::t('general', 'Updating')."</i>" : $model->bio ?></p>
                </div>
                <?php
                if(count($products) > 0) {
                $categories = \vsoft\ad\models\AdCategory::find ()->indexBy( 'id' )->asArray( true )->all ();
                $types = \vsoft\ad\models\AdProduct::getAdTypes ();
                ?>
                <div class="list-per-post">
                    <div class="title-text clearfix"><?=Yii::t('profile', 'LISTINGS')?>
                        <ul class="nav nav-tabs pull-right" role="tablist">
                            <li role="presentation" class="active"><a href="#list-all" aria-controls="list-all" role="tab" data-toggle="tab">Tất cả (10)</a></li>
                            <li role="presentation"><a href="#list-by" aria-controls="list-by" role="tab" data-toggle="tab">Bán (3)</a></li>
                            <li role="presentation"><a href="#list-rent" aria-controls="list-rent" role="tab" data-toggle="tab">Cho thuê (7)</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="list-all">
                            <ul class="clearfix list-post">
                                <?php foreach ($products as $product) {
                                    ?>
                                <li class="col-xs-12 col-sm-6">
                                    <div class="wrap-item-post">
                                        <a href="<?= $product->urlDetail() ?>" class="rippler rippler-default">
                                            <div class="img-show"><div><img src="<?= $product->representImage ?>">
                                                    <input type="hidden" value="<?= $product->representImage ?>">
                                                </div></div>
                                            <div class="title-item"><?= ucfirst($categories[$product->category_id]['name']) ?> <?= $types[$product->type] ?></div>
                                        </a>
                                        <p class="date-post">Ngày đăng tin: <strong>12/2/2016, 8:30AM</strong></p>
                                        <p class="name-post"><a href="<?= $product->urlDetail() ?>"><?=$product->getAddress()?></a></p>
                                        <p class="id-duan">ID:<span><?=$product->id?></span></p>
                                        <ul class="clearfix list-attr-td">
                                            <li>
                                                <span class="icon icon-dt icon-dt-small"></span><?= $product->area ?>
                                            </li>
                                            <li>
                                                <span class="icon icon-bed icon-bed-small"></span><?= $product->adProductAdditionInfo->room_no ?>
                                            </li>
                                            <li>
                                                <span class="icon icon-pt icon-pt-small"></span><?= $product->adProductAdditionInfo->toilet_no ?>
                                            </li>
                                        </ul>
                                        <div class="bottom-item clearfix">
                                            <a href="#" class="pull-right fs-13">Chi tiết</a>
                                            <p>Giá <strong class="color-cd pdL-5"><?= StringHelper::formatCurrency($product->price) ?> đồng</strong></p>
                                        </div>
                                    </div>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="list-by">...</div>
                        <div role="tabpanel" class="tab-pane fade" id="list-rent">...</div>
                    </div>
                </div>
                <?php } else { ?>
                <div class="list-per-post">
                    <div class="title-text clearfix"><?=Yii::t('profile', 'NO LISTING')?></div>
                    <p><a href="<?= Url::to(['/ad/post']) ?>"><?=Yii::t('profile', 'Please, post listing here')?>.</a></p>
                </div>
                <?php } ?>
                <div class="review-user">
                    <div class="title-text clearfix">REVIEW
                        <a href="#" data-toggle="modal" data-target="#popup-review" class="btn-review btn-common pull-right">Viết Review</a>
                    </div>
                    <ul class="list-reivew">
                        <li>
                            <ul class="rating">
                                <li>rating</li>
                            </ul>
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
                            <ul class="rating">
                                <li>rating</li>
                            </ul>
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
                            <ul class="rating">
                                <li>rating</li>
                            </ul>
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
                            <ul class="rating">
                                <li>rating</li>
                            </ul>
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
                    <div class="title-text">CONTACT WITH SILVER RANGER</div>
                    <form action="">
                        <div class="form-group frm-item">
                            <input type="text" id="" class="" name="" value="" placeholder="Tên ...">
                        </div>
                        <div class="form-group frm-item">
                            <input type="text" id="" class="" name="" value="" placeholder="Email ...">
                        </div>
                        <div class="form-group frm-item">
                            <textarea name="" id="" cols="30" rows="10" placeholder="I am interested in “21, Nguyễn Trung Ngạn…”"></textarea>
                        </div>
                        <button class="btn-common btn-send-email">Gửi email</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->render('/ad/_partials/shareEmail',[
    'user' => $user,
    'yourEmail' => $yourEmail,
    'recipientEmail' => (empty($user->profile->public_email) ? $user->email : $user->profile->public_email),
    'params' => ['your_email' => false, 'recipient_email' => false] ]);
?>
<div id="popup-review" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                <div class="wrap-popup">
                    <div class="inner-popup">
                        <div class="review-box-popup">
                            <h2 class="color-cd fs-18 text-uper font-600 mgB-20">REVIEW SILVER RANGER</h2>
                            <p class="fs-13 mgB-10">Tell us about your experience with this agent. Your review will help other users find the agent that's right for them.</p>
                            <p class="fs-13 mgB-5 font-600">Môi giới này đã</p>
                            <select name="" id="" class="mgB-15">
                                <option value="">Giúp tôi thuê nhà</option>
                            </select>
                            <div class="check-rating mgB-15">
                                <span class="font-600 fs-13 pdR-10">Rate this agent</span>
                                <ul class="rating clearfix">
                                    <li>rating</li>
                                </ul>
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
        </div>
    </div>
</div>
<script>
    $(document).bind('chat/afterConnect', function (event, type) {
        var to_jid = chatUI.genJid('<?=$user->username?>');
        Chat.sendMessage(to_jid , 1, 'headline', {sttOnline: 0});
    });
</script>