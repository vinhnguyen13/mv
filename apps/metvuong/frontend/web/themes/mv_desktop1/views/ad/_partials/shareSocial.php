<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 3/3/2016 3:50 PM
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\StringHelper;
use yii\helpers\Url;

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
                            <ul class="clearfix">
                                <li>
                                    <a href="#"
                                       data-url="<?=Url::to(['/ad/tracking-share', 'product_id' => $product_id, 'type' => \vsoft\tracking\models\base\AdProductShare::SHARE_FACEBOOK], true)?>" class="share-facebook">
                                        <div class="circle"><div><span class="icon icon-face"></span></div></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                       data-url="<?=Url::to(['/ad/tracking-share', 'product_id' => $product_id, 'type' => \vsoft\tracking\models\base\AdProductShare::SHARE_EMAIL], true)?>"
                                       data-toggle="modal" data-target="#popup-email" class="email-btn">
                                        <div class="circle"><div><span class="icon icon-email-1"></span></div></div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fbShare(url, title, descr, image, winWidth, winHeight) {
        var winTop = (screen.height / 2) - (winHeight / 2);
        var winLeft = (screen.width / 2) - (winWidth / 2);
        window.open('http://www.facebook.com/sharer.php?s=100&p[url]=' + url + '&p[title]=' + title + '&p[summary]=' + descr + '&p[images][0]=' + image, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
    }

    $('#popup-share-social ul li a').click(function (){
        var url = $(this).data("url");
        if(url.length > 0) {
            $('body').loading();
            $.ajax({
                type: "get",
                dataType: 'json',
                url: url,
                success: function (data) {
                    $('#popup-share-social').modal('hide');
                }
            });
            $('body').loading({done:true});
            if($(this).attr('class') == 'share-facebook')
                fbShare('<?=$url ?>', '<?=$title ?>', '<?=$description ?>', '<?= $image ?>', 520, 350);

            return true;
        }
        return false;
    });

    /*$('#popup-email').popupMobi({
        btnClickShow: ".email-btn",
        closeBtn: '#popup-email .btn-cancel',
        styleShow: "full"
    });*/
</script>
