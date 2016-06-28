<?php
use yii\helpers\Url;

Yii::t('chart','Finders');
Yii::t('chart','Visitors');
Yii::t('chart','Saved');
Yii::t('chart','Shares');
?>
<div class="clearfix mgB-15">
    <h3><?=Yii::t('chart', $view);?></h3>
    <p style="color: #4a933a;" class="desTotal"> Tổng số người: <?=count($favourites)?></p>
</div>
<ul class="clearfix">
<?php
if(count($favourites) > 0) {
    foreach ($favourites as $key => $val) {
        $classPopupUser = 'popup_enable';
        ?>
        <li>
            <a class="<?=$classPopupUser?>" href="#popup-user-inter">
                <img src="<?=$val['avatar']?>"> <?=$key?>
            </a>

            <div class="crt-item">
                <a href="#" class="btn-email-item mgR-15 tooltip-show" data-placement="bottom" title="" data-target="#popup_email" data-type="contact" data-toggle="modal" data-email="<?=$val['email']?>" data-original-title="Send email">
                    <span class="icon-mv fs-16"><span class="icon-mail-profile"></span></span>
                </a>
                <a href="#" class="chat-now tooltip-show" data-chat-user="<?=$key?>" data-placement="bottom" title="" data-original-title="Send message">
                    <span class="icon-mv fs-18"><span class="icon-bubbles-icon"></span></span>
                </a>
            </div>
        </li>
<!--        $li = '<li><a class="'.$classPopupUser.'" href="#popup-user-inter" data-email="'.$val["email"].'" data-ava="'.Url::to($val['avatar'], true).'">'.-->
<!--            '<img src="'.$val['avatar'].'"> '.$key.'</a>'.-->
<!--            '<span class="pull-right">'.$val['count'].'</span></li>';-->
<!--        $html .= $li;-->
    <?php
    }
}
?>
</ul>
<p class="push-price">
    Để tin đạt hiệu quả hơn vui lòng click vào đây <a href="#" data-toggle="modal" data-target="#upgrade-time" data-product="79110" class="btn-nang-cap mgL-10 btn-up">Boost</a>
</p>
