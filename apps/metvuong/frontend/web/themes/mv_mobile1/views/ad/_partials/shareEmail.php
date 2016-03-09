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
<div id="popup-email" class="popup-common hide-popup">
    <div class="wrap-popup">
        <div class="title-popup clearfix">
            <div class="text-center">SHARE VIA EMAIL</div>
            <a href="#" class="txt-cancel btn-cancel">Cancel</a>
            <a href="#popup-sent" class="txt-done btn-done send_mail">Send</a>
        </div>
        <div class="inner-popup">
            <?php
            $share_form = Yii::createObject([
                'class'    => \frontend\models\ShareForm::className(),
                'scenario' => 'share',
            ]);

            $f = ActiveForm::begin([
                'id' => 'share_form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => true,
                'action' => Url::to(['/ad/sendmail'])
            ]);

            if(isset($params['your_email']) && $params['your_email'] == false && !empty($yourEmail)) {
                echo $f->field($share_form, 'your_email')->hiddenInput(['class'=>'your_email', 'value'=> $yourEmail])->label(false);
            } else { ?>
            <div class="frm-item frm-email">
                <?= $f->field($share_form, 'your_email')->textInput(['class'=>'your_email', 'value' => isset($params['setValueFromEmail']) ? $yourEmail : "", 'placeholder'=>Yii::t('your_email', 'Email của bạn...')])->label(false) ?>
            </div>
            <?php }

            if(isset($params['recipient_email']) && $params['recipient_email'] == false && !empty($recipientEmail)) {
                echo $f->field($share_form, 'recipient_email')->hiddenInput(['class'=>'recipient_email', 'value'=> $recipientEmail])->label(false);
            } else {
            ?>
            <div class="frm-item frm-email">
                <?= $f->field($share_form, 'recipient_email')->textInput(['class'=>'recipient_email', 'value' => isset($params['setValueToEmail']) ? $recipientEmail : "", 'placeholder'=>Yii::t('recipient_email', 'Email người nhận...')])->label(false) ?>
            </div>
            <?php }  ?>

            <div class="frm-item frm-email">
                <?= $f->field($share_form, 'subject')->textInput(['class'=>'subject2', 'placeholder'=>Yii::t('subject', 'Tiêu đề...')])->label(false)?>
            </div>
            <div class="frm-item frm-email">
                <?= $f->field($share_form, 'content')->textarea(['class'=>'content', 'cols' => 30, 'rows' => 5, 'placeholder'=>Yii::t('content', 'Nội dung...')])->label(false) ?>
            </div>
            <div class="item-send">
                <?php
                if(isset($product) && !empty($product)){
                    $address = $product->getAddress();
                ?>
                <div class="img-show"><div><a href="<?= $product->urlDetail(true) ?>"><img src="<?= $product->representImage ?>" alt="<?=$address?>"></a></div></div>
                <div class="infor-send">
                    <p class="name"><a href="<?= $product->urlDetail(true) ?>"><?=$address?></a></p>
                    <p class="address"></p>
                    <p><?=StringHelper::truncate($product->content, 150)?></p>
                    <p class="send-by">BY METVUONG.COM</p>
                </div>

                <?= $f->field($share_form, 'address')->hiddenInput(['class' => '_address', 'value'=>$address])->label(false) ?>
                <?= $f->field($share_form, 'detailUrl')->hiddenInput(['class' => '_detailUrl', 'value'=> $product->urlDetail(true) ])->label(false) ?>
                <?= $f->field($share_form, 'domain')->hiddenInput(['class' => '_domain', 'value'=>Yii::$app->urlManager->getHostInfo()])->label(false) ?>
                <?php }
                else if(isset($project) && !empty($project)){
                    $image = '/themes/metvuong2/resources/images/default-ads.jpg';
                    $gallery = array();
                    if($project->gallery)
                        $gallery = explode(',', $project->gallery);
                    if (count($gallery) > 0) {
                        $imageUrl = Yii::getAlias('@store')."/building-project-images/". $gallery[0];
                        if(file_exists($imageUrl)){
                            $image = Url::to('/store/building-project-images/' . $gallery[0]);
                        }
                    }
                    ?>
                    <div class="img-show"><div><a href="<?= Url::to(["building/$project->slug"],true) ?>"><img src="<?= $image ?>" alt="<?=Url::to(["building/$project->slug"],true)?>"></a></div></div>
                    <div class="infor-send">
                        <p class="name"><a href="<?= Url::to(["building/$project->slug"],true) ?>"><?=mb_strtoupper($project->name)?></a></p>
                        <p class="address"></p>
                        <p><?=StringHelper::truncate($project->description, 150)?></p>
                        <p class="send-by">BY METVUONG.COM</p>
                    </div>
                    <?= $f->field($share_form, 'address')->hiddenInput(['class' => '_address', 'value'=> Url::to(["building/$project->slug"],true) ])->label(false) ?>
                    <?= $f->field($share_form, 'detailUrl')->hiddenInput(['class' => '_detailUrl', 'value'=> Url::to(["building/$project->slug"],true) ])->label(false) ?>
                    <?= $f->field($share_form, 'domain')->hiddenInput(['class' => '_domain', 'value'=>Yii::$app->urlManager->getHostInfo()])->label(false) ?>
                <?php }?>
            </div>
            <?php $f->end(); ?>
        </div>
    </div>
</div>

<div id="popup-sent" class="popup-common hide-popup">
    <div class="wrap-popup">
        <div class="inner-popup">
            <a href="#" class="btn-close"><span class="icon icon-close"></span></a>
            <div class="overflow-all">
                <p>Thanks for send mail to <b class="user_name"></b></p>
                <br />
                <div><a style="color: #00a769;" href="<?=Url::home()?>" class="">Return homepage</a></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.send_mail', function(){
        var timer = 0;
        var recipient_email = $('#share_form .recipient_email').val();
        var your_email = $('#share_form .your_email').val();
        if(recipient_email != null && your_email != null) {
            $('#popup-sent .user_name').html(recipient_email);
            clearTimeout(timer);
            timer = setTimeout(function () {
                $('#popup-email').addClass('hide-popup');
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: $('#share_form').attr('action'),
                    data: $('#share_form').serializeArray(),
                    success: function (data) {
                        if(data.status == 200){
                        }
                        else {
                            var strMessage = '';
                            $.each(data.parameters, function(idx, val){
                                var element = 'share_form_'+idx;
                                strMessage += "\n" + val;
                            });
                            alert(strMessage+"\nTry again");
                            $('#share_form .recipient_email').focus();
                        }
                        return true;
                    },
                    error: function (data) {
                        var strMessage = '';
                        $.each(data.parameters, function(idx, val){
                            var element = 'share_form_'+idx;
                            strMessage += "\n" + val;
                        });
                        alert(strMessage);
                        return false;
                    }
                });
            }, 500);
        }
        return false;
    });

    $('#popup-sent').popupMobi({
        btnClickShow: '#popup-email .send_mail',
        styleShow: 'center',
        closeBtn: '#popup-sent .btn-close'
    });

</script>
