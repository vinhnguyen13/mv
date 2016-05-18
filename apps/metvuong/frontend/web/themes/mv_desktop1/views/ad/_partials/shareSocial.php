<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 3/3/2016 3:50 PM
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\StringHelper;
use yii\helpers\Url;

$user = Yii::$app->user->identity;
?>

<div id="popup-share-social" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="wrap-popup">
                    <div class="inner-popup">
                        <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                        <div class="wrap-body-popup">
                            <span>Share on Social Network</span>
                            <?php if(isset($product) && !empty($product)) {?>
                            <ul class="clearfix">
                                <li>
                                    <a href="#" class="share-facebook" data-toggle="modal" data-target="#popup_email_share"
                                       data-url="<?=Url::to(['/ad/tracking-share', 'product_id' => $product->id, 'type' => \vsoft\tracking\models\base\AdProductShare::SHARE_FACEBOOK], true)?>">
                                        <div class="circle"><div><span class="icon icon-face"></span></div></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                       data-url="<?=Url::to(['/ad/tracking-share', 'product_id' => $product->id, 'type' => \vsoft\tracking\models\base\AdProductShare::SHARE_EMAIL], true)?>"
                                       data-toggle="modal" data-target="#popup_email_share" class="email-btn">
                                        <div class="circle"><div><span class="icon icon-email-1"></span></div></div>
                                    </a>
                                </li>
                            </ul>
                            <?php } else {?>
                            <ul class="clearfix">
                                <li>
                                    <a href="#" class="share-facebook">
                                        <div class="circle"><div><span class="icon icon-face"></span></div></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" data-toggle="modal" data-target="#popup_email" data-type="share" class="email-btn">
                                        <div class="circle"><div><span class="icon icon-email-1"></span></div></div>
                                    </a>
                                </li>
                            </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if(isset($product) && !empty($product)){
echo $this->renderAjax('/ad/_partials/shareEmail',[
    'popup_email_name' => 'popup_email_share',
    'product' => $product,
    'yourEmail' => empty($user) ? "" : (empty($user->profile->public_email) ? $user->email : $user->profile->public_email),
    'recipientEmail' => null,
    'params' => ['your_email' => false, 'recipient_email' => true] ]);
} else if(isset($project) && !empty($project)){
    echo $this->renderAjax('/ad/_partials/shareEmail',[
        'popup_email_name' => 'popup_email_share',
        'project' => $project,
        'yourEmail' => empty($user) ? "" : (empty($user->profile->public_email) ? $user->email : $user->profile->public_email),
        'recipientEmail' => null,
        'params' => ['your_email' => false, 'recipient_email' => true] ]);
}?>

<script>
    function fbShare(url, title, descr, image, winWidth, winHeight) {
        var winTop = (screen.height / 2) - (winHeight / 2);
        var winLeft = (screen.width / 2) - (winWidth / 2);
        window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(url), 'facebook-share-dialog', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
//        window.open('http://www.facebook.com/sharer.php?s=100&p[url]=' + url + '&p[title]=' + title + '&p[summary]=' + descr + '&p[images][0]=' + image, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
    }

    $('.share-facebook').click(function (){
        $('#popup-share-social').modal('hide');
        fbShare('<?=$url ?>', '<?=$title ?>', '<?=$description ?>', '<?= $image ?>', 800, 600);
    });

</script>
