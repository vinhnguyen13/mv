<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 2/2/2016 10:19 AM
 */
use vsoft\express\components\StringHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$user = $model->getUser();
?>
<div class="title-fixed-wrap">
    <div class="container">
        <div class="profile-user">
            <div class="title-top clearfix"><a href="#" class="icon icon-back-top pull-left"></a> <?=Yii::t('profile', 'Profile User');?><a href="#" class="icon icon-back-top pull-right"></a></div>
            <div class="infor-user clearfix">
                <div class="avatar-user-pr">
                    <div class="wrap-img avatar"><img id="profileAvatar" data-toggle="modal" data-target="#avatar" src="<?=$model->avatar?>" alt="metvuong avatar" /></div>
                    <div class="name-user" ><?= $model->name ?></div>
                    <div class="per-posi"><?=Yii::t('profile', 'Agent ID')?> TTG<?=str_pad($model->user_id, 3, '0', STR_PAD_LEFT)?></div>
                    <div class="text-center send-noti">
                        <a href="<?=$user->urlChat();?>" class="btn-common btn-chat rippler rippler-default"><span class="icon icon-chat-1"></span></a>
                        <a href="tel:<?= $model->mobile ?>" class="btn-common rippler rippler-default"><span class="icon icon-phone-1"></span></a>
                        <a href="sms:<?= $model->mobile ?>" class="btn-common rippler rippler-default"><span class="icon icon-sms"></span></a>
                    </div>
                </div>
            </div>

            <div class="infor-person">
                <div class="title-text"><?=Yii::t('profile', 'Personal Information')?></div>
                <div class="wrap-txt">
                    <?= empty($model->bio) ? "Đang cập nhật" : $model->bio ?>
                </div>
            </div>

            <ul class="clearfix list-attr-per">
                <li>
                    <div class="circle"><div><span class="icon icon-map-loca-1"></span></div></div>
                    <div class="txt-infor-right">
                        <div>
                            <?= empty($user->location) ? "Đang cập nhật" : $user->location->city ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="circle"><div><span class="icon icon-phone-1"></span></div></div>
                    <div class="txt-infor-right">
                        <div>
                            <a href="tel:<?=$model->mobile ?>"><?= empty($model->mobile) ? "Đang cập nhật" : $model->mobile ?></a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="circle"><div><span class="icon icon-email-1"></span></div></div>
                    <div class="txt-infor-right">
                        <div>
                            <?= empty($model->public_email) ? "Đang cập nhật" : $model->public_email ?>
                        </div>
                    </div>
                </li>
            </ul>

            <?php
            if(count($products) > 0) {
            $categories = \vsoft\ad\models\AdCategory::find ()->indexBy( 'id' )->asArray( true )->all ();
            $types = \vsoft\ad\models\AdProduct::getAdTypes ();
            ?>

            <div class="list-per-post">
                <div class="title-text"><?=Yii::t('profile', 'Posted by Agent')?></div>
                <ul class="clearfix list-post">
                    <?php foreach ($products as $product) {
                        ?>
                    <li>
                        <a href="<?= $product->urlDetail() ?>" class="rippler rippler-default">
                            <div class="img-show"><div><img src="<?= $product->representImage ?>">
                                    <input type="hidden" value="<?= $product->representImage ?>">
                                </div></div>
                            <div class="title-item"><?= ucfirst($categories[$product->category_id]['name']) ?> <?= $types[$product->type] ?></div>
                        </a>
                        <a href="<?= $product->urlDetail() ?>"><p class="name-post"><span class="icon address-icon"></span><?=$product->getAddress()?></p></a>
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
                        <p class="price-post">Giá <strong><?= StringHelper::formatCurrency($product->price) ?> đồng</strong></p>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>
        </div>
    </div>
</div>